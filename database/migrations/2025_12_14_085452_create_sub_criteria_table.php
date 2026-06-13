<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sub_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criterion_id')
                  ->constrained('criteria')
                  ->cascadeOnDelete();
            $table->string('label');
            $table->double('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_criteria');
    }
};
