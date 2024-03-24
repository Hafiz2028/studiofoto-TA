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
        Schema::create('opening_hour_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('service_event_id');
            $table->unsignedInteger('opening_hour_id');
            $table->foreign('service_event_id')->references('id')->on('service_events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('opening_hour_id')->references('id')->on('opening_hours')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_hour_details');
    }
};
