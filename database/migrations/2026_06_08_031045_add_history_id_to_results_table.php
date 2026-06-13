<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('process_histories', function (Blueprint $table) {
            // Menambahkan kolom results_data setelah kolom process_date
            $table->json('results_data')->after('process_date'); 
        });
    }

    public function down(): void
    {
        Schema::table('process_histories', function (Blueprint $table) {
            $table->dropColumn('results_data');
        });
    }
};