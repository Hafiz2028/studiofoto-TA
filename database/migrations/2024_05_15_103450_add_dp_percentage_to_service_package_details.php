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
        Schema::table('service_package_details', function (Blueprint $table) {
            $table->float('dp_percentage', 10,5)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_package_details', function (Blueprint $table) {
            $table->dropColumn(['dp_percentage']);
        });
    }
};
