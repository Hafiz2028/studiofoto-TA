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
        Schema::create('venue_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('venue_id')->unsigned();
            $table->string('image');
            $table->timestamps();
            $table->foreign('venue_id')->references('id')->on('venues')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_images');
    }
};
