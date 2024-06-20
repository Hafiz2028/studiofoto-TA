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
            $table->date('dp_price_date')->nullable()->after('dp_price');
            $table->date('dp_payment')->nullable()->after('dp_price_date');
            $table->string('link_foto')->nullable()->after('total_price');
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
            $table->dropColumn('link_foto');
        });
    }
};
