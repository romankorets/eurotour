<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceTourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_tour', function (Blueprint $table) {
            $table->bigIncrements('place_id');
            $table->bigIncrements('tour_id');
            $table->unique(['place_id', 'tour_id']);
            $table->boolean('isStartPlace')->default(false);
            $table->boolean('isFinishPlace')->default(false);
            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('tour_id')->references('id')->on('tours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('place_tour');
    }
}
