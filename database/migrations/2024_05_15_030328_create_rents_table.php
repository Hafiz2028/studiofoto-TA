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
            $table->unsignedInteger('customer_id')->nullable()->unsigned();
            $table->unsignedInteger('service_package_detail_id')->unsigned();
            $table->tinyInteger('rent_status')->default(0);
            $table->tinyInteger('book_type');
            $table->date('date');
            $table->tinyInteger('payment_status')->nullable();
            $table->integer('dp_price')->nullable();
            $table->integer('total_price');
            $table->unsignedInteger('print_photo_detail_id')->nullable()->unsigned();
            $table->string('reject_note')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('print_photo_detail_id')->references('id')->on('print_photo_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('service_package_detail_id')->references('id')->on('service_package_details')->onUpdate('cascade')->onDelete('cascade');
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
