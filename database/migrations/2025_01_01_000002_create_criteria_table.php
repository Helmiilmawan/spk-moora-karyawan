<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCriteriaTable extends Migration
{
    public function up()
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // C1, C2, ...
            $table->string('name');
            $table->enum('type', ['cost','benefit']);
            $table->decimal('weight', 8, 4); // e.g., 0.3000
            $table->integer('order')->default(0); // order index
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('criteria');
    }
}
