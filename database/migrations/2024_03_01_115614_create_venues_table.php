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
        Schema::create('venues', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('name');
            $table->tinyInteger('status')->default(0);
            $table->string('address');
            $table->string('imb')->nullable();
            $table->text('information')->nullable();
            $table->string('phone_number');
            $table->string('village_id')->nullable();
            $table->string('map_link')->nullable();
            $table->string('reject_note')->nullable();
            $table->unsignedInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('owners')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
