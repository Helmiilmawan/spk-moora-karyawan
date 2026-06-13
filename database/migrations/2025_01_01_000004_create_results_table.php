<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alternative_id');
            $table->decimal('yi', 12, 6);
            $table->integer('rank');
            $table->json('details')->nullable();
            $table->timestamps();

            $table->foreign('alternative_id')->references('id')->on('alternatives')->onDelete('cascade');
            $table->unique('alternative_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('results');
    }
}
