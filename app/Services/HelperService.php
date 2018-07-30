<?php namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Coupon;

class HelperService
{
    public static function is_in_location_radius($radius, $center_longitude,$center_latitude,  $longitude , $latitude) {  
        $earth_radius = 6371;
    
        $latitude_difference_in_radians = deg2rad( $latitude - $center_latitude );  
        $longitude_difference_in_radians = deg2rad( $longitude - $center_longitude );  
    
        $a = sin($latitude_difference_in_radians/2) * sin($latitude_difference_in_radians/2) 
            + cos(deg2rad($center_latitude)) * cos(deg2rad($latitude)) 
            * sin($longitude_difference_in_radians/2) * sin($longitude_difference_in_radians/2);  
        $b = 2 * asin(sqrt($a));  
        $distance = $earth_radius * $b;  

        if($distance < $radius){
            return true;
        }else{
            return false;
        }
    
    }
    
    public static function generate_new_coupon_code()
    { 
    	$reference = '';
	    $check = true;
	    while ($check) {
	    	$characters = strtoupper('0abcd'.time().'efz1nrstu2o'.time().'123456'.time().'pqghijk'.time().'lm3456vwxy'.time().'789');
		    $charactersLength = strlen($characters);
		    $reference = 'SB';
		    for ($i = 0; $i < 8; $i++) {
		        $reference .= $characters[rand(0, $charactersLength - 1)];
		    }
	        $code_exists = Coupon::where('code', '=', $reference)->first();
		    if ($code_exists) {
		        $check = true;
		    } else {
		        $check = false;
		    }
	    }
	    return $reference;
    }
}