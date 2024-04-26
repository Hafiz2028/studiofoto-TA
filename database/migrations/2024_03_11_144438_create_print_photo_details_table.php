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
        Schema::create('print_photo_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('service_package_id');
            $table->unsignedInteger('print_photo_id');
            $table->integer('price');
            $table->foreign('service_package_id')->references('id')->on('service_packages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('print_photo_id')->references('id')->on('print_photos')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_photo_details');
    }
};
