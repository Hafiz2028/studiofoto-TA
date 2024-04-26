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
        Schema::create('add_on_package_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('service_package_id');
            $table->unsignedInteger('add_on_package_id');
            $table->integer('sum');
            $table->foreign('service_package_id')->references('id')->on('service_packages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('add_on_package_id')->references('id')->on('add_on_packages')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_on_package_details');
    }
};
