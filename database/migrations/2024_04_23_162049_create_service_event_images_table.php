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
        Schema::create('service_event_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('service_event_id')->unsigned();
            $table->string('image',150);
            $table->timestamps();
            $table->foreign('service_event_id')->references('id')->on('service_events')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_event_images');
    }
};
