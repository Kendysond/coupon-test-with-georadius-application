<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Coupon extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'description', 'currency', 'amount', 'valid_from', 'valid_till', 'status','location_longitude','location_latitude','location_radius','quantity'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'id','deleted_at'
    ];

    public function getCodeAttribute($value)
    {
        return ucfirst($value);
    }
}
