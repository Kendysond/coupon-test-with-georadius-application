<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('code');

            $table->string('description')->nullable();

            $table->decimal('amount', 11, 2)->default(0);
            $table->string('currency')->default('UGX');
           
            $table->enum('status', ['active', 'deactivated'])->default('active');
            
            $table->date('valid_from')->nullable();
            $table->date('valid_till')->nullable();

            $table->string('location_longitude')->nullable();
            $table->string('location_latitude')->nullable();
            $table->decimal('location_radius', 11, 2)->nullable();
            
            $table->integer('quantity')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
