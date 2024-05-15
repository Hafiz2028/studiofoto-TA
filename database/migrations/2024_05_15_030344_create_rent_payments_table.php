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
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');

            $table->unsignedInteger('rent_id')->unsigned();
            $table->unsignedInteger('payment_method_detail_id')->unsigned();

            $table->foreign('rent_id')->references('id')->on('rents')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_method_detail_id')->references('id')->on('payment_method_details')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
    }
};
