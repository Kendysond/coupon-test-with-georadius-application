<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/index', 'CouponController@index');

Route::get('/coupons', 'CouponController@list');
Route::post('/coupons/create', 'CouponController@create');
Route::post('/coupons/apply', 'CouponController@apply');
