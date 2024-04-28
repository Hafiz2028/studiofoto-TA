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
        Schema::create('service_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('information')->nullable();
            $table->float('dp_percentage')->nullable();
            $table->tinyInteger('time_status')->default(0);
            $table->integer('price')->nullable();
            $table->unsignedInteger('service_event_id');

            $table->foreign('service_event_id')->references('id')->on('service_events')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_packages');
    }
};
