<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('faktur');
            $table->string('name');
            $table->unsignedInteger('service_package_detail_id')->unsigned();
            $table->unsignedInteger('opening_hour_id')->unsigned();
            $table->date('date');
            $table->tinyInteger('payment_status');
            $table->integer('dp_price')->nullable();
            $table->integer('total_price');
            $table->string('reject_note')->nullable();
            $table->foreign('service_package_detail_id')->references('id')->on('service_package_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('opening_hour_id')->references('id')->on('opening_hours')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
