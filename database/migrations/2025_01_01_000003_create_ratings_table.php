<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('alternative_id');
            $table->unsignedBigInteger('criterion_id');

            $table->double('value');

            $table->timestamps();

            $table->foreign('alternative_id')
                  ->references('id')
                  ->on('alternatives')
                  ->onDelete('cascade');

            $table->foreign('criterion_id')
                  ->references('id')
                  ->on('criteria')
                  ->onDelete('cascade');

            $table->unique(['alternative_id', 'criterion_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
