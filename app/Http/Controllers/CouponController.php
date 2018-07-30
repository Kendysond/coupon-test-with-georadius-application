<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\Paginator;


use App\Exceptions\InvalidRequestException;
use App\Exceptions\FailedRequestException;
use App\Exceptions\ResourceNotFoundException;


use Validator;

use App\Coupon;
use App\Services\HelperService;

use Carbon\Carbon;

class CouponController
{

    
    public function create(Request $request)
    {
      
        
        
        $rules = [
            'code' => 'unique:coupons,code',
            'amount' => 'required|numeric',
            'currency' => 'required|in:UGX,KES,GHS',
            'valid_from' => 'nullable|date_format:Y-m-d',
            'valid_till' => 'nullable|date_format:Y-m-d',
            'location_longitude' => 'required_with:location_latitude',
            'location_latitude' => 'required_with:location_longitude',
            'radius' => 'nullable|numeric',
           
        ];

        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
        ];
    
        $validator = Validator::make($request->all(), $rules,$customMessages);
        
        if ($validator->fails()) {
            throw new InvalidRequestException($validator->errors());
        }
        
        $code = $request->input('code');
        if($code == "" || $code == NULL){
           $code = HelperService::generate_new_coupon_code();
        }

        $description = $request->input('description');
        $amount = $request->input('amount');
        $currency = $request->input('currency') ? $request->input('currency') : 'UGX';

        $description = $request->input('description');
        $valid_from = $request->input('valid_from');
        $valid_till = $request->input('valid_till');

        if($valid_till){
            $todays_date = date("Y-m-d"); 
            if ($todays_date > $valid_till) {
                throw new FailedRequestException('Coupon Validity cannot be earlier than today');
            }
        }
        if($valid_from && $valid_till){
            if ($valid_from > $valid_till) {
                throw new FailedRequestException('Coupon Validity start date must be earlier than the end date');
            }
        }

        $location_longitude = $request->input('location_longitude');
        $location_latitude = $request->input('location_latitude');
        $location_radius = $request->input('location_radius');

        $quantity = $request->input('quantity') ? $request->input('quantity') : 0;
        $status = $request->input('status') ? $request->input('status') : 'active';
           
          
        $coupon = Coupon::create([
            'code' => $code,
            'description' => $description,
            'amount' => $amount,
            'currency' => $currency,
            'status' => $status,
            'valid_from' => $valid_from,
            'valid_till' => $valid_till,
            'location_longitude' => $location_longitude,
            'location_latitude' => $location_latitude,
            'location_radius' => $location_radius,
            'quantity' => $quantity,
        ]);
        
        return Response::json([
            'status' => 'success',
            'message' => $currency.$amount." coupon created",
            'data' => $coupon
        ]);
    }

    public function list(Request $request)
    {
        
        $page = $request->input('page') ? $request->input('page') : 1;
        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });

        $order = $request->input('order') ? $request->input('order') : 'DESC';
        $orderby = $request->input('orderby') ? $request->input('orderby') : 'created_at';
        
        $filters = [];
        $filters['currency'] = $request->input('currency');
        $filters['status'] = $request->input('status');
        $filters['amount'] = $request->input('amount');
        
        $query = Coupon::orderBy($orderby, $order);
        
        foreach ($filters as $key => $filter) {
            if ($filter != NULL) {
                $query->where($key,'=',$filter);
            }
        }

        $search = $request->input('search');

        if($search){
            $query->where(function ($query) use ($search) {

                $columns = array("code","currency", "amount", "description");
                foreach ($columns as $column){
                    $query->orWhere($column, 'LIKE', '%'. $search .'%');
                }

            });
        }
        $_result = $query->paginate(20);
        
        $result = $_result->toArray();

        $result['status'] = 'success';
        return Response::json($result);
    }

    public function apply(Request $request)
    {
        $rules = [
            'code' => 'required|exists:coupons,code',
            'origin_latitude' => 'required',
            'origin_longitude' => 'required',
            'destination_latitude' => 'required',
            'destination_longitude' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            throw new InvalidRequestException($validator->errors());
        }
        
        $coupon_validity_status = true;
        
        $code = $request->input('code');
        $origin_latitude = $request->input('origin_latitude');
        $origin_longitude = $request->input('origin_longitude');
        $destination_latitude = $request->input('destination_latitude');
        $destination_longitude = $request->input('destination_longitude');
        
        $coupon = Coupon::where('code', $request->input('code'))->first();

        if($coupon->status != 'active'){
            $coupon_validity_status = false;
            throw new FailedRequestException('Coupon is no more active');
        }

        if($coupon->valid_till){
            $todays_date = date("Y-m-d"); 
            if ($todays_date > $coupon->valid_till) {
                $coupon_validity_status = false;
                throw new FailedRequestException('Coupon is expired');
            }
        }

        $is_origin_in_radius = HelperService::is_in_location_radius($coupon->location_radius, $coupon->location_longitude,$coupon->location_latitude, $origin_longitude , $origin_latitude);
        $is_destination_in_radius = HelperService::is_in_location_radius($coupon->location_radius, $coupon->location_longitude,$coupon->location_latitude, $destination_longitude , $destination_latitude);
        
        if( $is_origin_in_radius == false && $is_destination_in_radius == false){
            $coupon_validity_status = false;
            throw new FailedRequestException('This coupon is not valid for your desired route');
        }

       
        return Response::json([
            'status' => "success",
            'message' => "",
            'data' => [
                'coupon' => $coupon,
                'valid' => $coupon_validity_status,
                'polylines' => [
                    ['latitude' => $origin_latitude,'longitude' => $origin_longitude],
                    ['latitude' => $destination_latitude,'longitude' => $destination_longitude],
                ],
            ]
        ]);
    }
}