<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->integer('country')->default(0);
            $table->text('address');
            $table->string('street')->nullable();
            $table->string('suite')->nullable();
            $table->integer('state')->nullable();
            $table->integer('city')->nullable();
            $table->integer('zip')->nullable();
            $table->integer('country_code')->nullable();
            $table->integer('phone')->nullable();
            $table->string('delivery_time_for_locations')->nullable();
            $table->string('delivery_fees')->nullable();
            $table->tinyInteger('pickup')->default(0);
            $table->string('delivery')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
};
