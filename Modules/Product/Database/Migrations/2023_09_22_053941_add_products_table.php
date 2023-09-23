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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_key')->nullable();
            $table->integer('status')->default('0');
            $table->string('import_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->string('name_english')->charset('utf8');
            $table->string('name_arabic')->charset('utf8')->nullable();
            $table->string('slug');
            $table->integer('category')->default('0');
            $table->string('brand');
            $table->string('keyword_english')->charset('utf8');
            $table->string('keyword_arabic')->charset('utf8')->nullable();
            $table->mediumText('description_english')->charset('utf8')->nullable();
            $table->mediumText('description_arabic')->charset('utf8')->nullable();
            $table->string('barcode_type')->nullable();
            $table->string('barcode');
            $table->string('sku');
            $table->string('pack_size');
            $table->string('pack_unit');
            $table->string('pack_carton');
            $table->string('pack_avg');
            $table->string('pack_mode');
            $table->integer('stock')->default('0');
            $table->string('carton_weight')->nullable();
            $table->string('carton_weight_unit')->nullable();
            $table->string('carton_length')->nullable();
            $table->string('carton_length_unit')->nullable();
            $table->string('carton_height')->nullable();
            $table->string('carton_height_unit')->nullable();
            $table->string('carton_width')->nullable();
            $table->string('carton_width_unit')->nullable();
            $table->string('price_unit');
            $table->integer('ready_ship')->default('0');
            $table->integer('availability')->default('1');
            $table->integer('is_jordan')->default('1');
            $table->string('storage_temp');
            $table->integer('country_origin')->default('0');
            $table->string('warning')->nullable();
            $table->string('scent')->nullable();
            $table->string('gender');
            $table->string('item_weight')->default('');
            $table->string('item_height')->default('');
            $table->string('item_length')->default('');
            $table->string('item_width')->default('');
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
        Schema::dropIfExists('products');
    }
};
