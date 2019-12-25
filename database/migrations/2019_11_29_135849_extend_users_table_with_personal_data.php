<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendUsersTableWithPersonalData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'users',
            function(Blueprint $table) {
                $table->string('uuid');
                $table->string('rank');
                $table->string('origin')->nullable();
                $table->string('duties');
                $table->string('position');
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
        Schema::table(
            'users',
            function(Blueprint $table) {
                $table->dropColumn(['uuid', 'rank', 'origin', 'duties', 'position']);
                $table->dropSoftDeletes();
            }
        );
    }
}
