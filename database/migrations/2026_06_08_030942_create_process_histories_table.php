<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('process_histories', function (Blueprint $table) {
            $table->id();
            $table->string('process_name');
            $table->timestamp('process_date');
            $table->json('results_data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('process_histories');
    }
};