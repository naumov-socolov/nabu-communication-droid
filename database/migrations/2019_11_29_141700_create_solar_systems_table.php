<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolarSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'solar_systems',
            function(Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->string('slug');
                $table->string('region');
                $table->string('sector');
                $table->string('suns');
                $table->string('grid_coordinates');
                $table->string('trade_routes');
                $table->string('driver_name');
                $table->string('img');
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solar_systems');
    }
}
