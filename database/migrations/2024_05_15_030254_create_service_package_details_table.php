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
        Schema::create('service_package_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sum_person');
            $table->integer('price');
            $table->unsignedInteger('service_package_id');
            $table->foreign('service_package_id')->references('id')->on('service_packages')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_package_details');
    }
};
