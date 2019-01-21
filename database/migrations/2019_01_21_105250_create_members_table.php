<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname');
            $table->unsignedInteger('party_id');
            $table->unsignedInteger('constituency_id');
            $table->string('webpage', 255)->nullable(true);
            $table->string('twitter', 255)->nullable(true);
            $table->unsignedTinyInteger('elected')->nullable(true);
            $table->float('representation')->nullable(true);
            $table->timestamps();

			$table->foreign('party_id')->references('id')->on('parties');
			$table->foreign('constituency_id')->references('id')->on('constituencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
