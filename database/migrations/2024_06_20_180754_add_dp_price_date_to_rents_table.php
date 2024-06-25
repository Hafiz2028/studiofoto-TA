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
        Schema::table('rents', function (Blueprint $table) {
            $table->dateTime('dp_price_date')->nullable()->after('dp_price');
            $table->dateTime('dp_payment')->nullable()->after('dp_price_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropColumn('dp_price_date');
            $table->dropColumn('dp_payment');
        });
    }
};
