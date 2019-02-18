<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeometryTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geometry', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('constituency_id')->nullable(true);
			$table->longText('geojson')->nullable(true);
			$table->timestamps();

			$table->foreign('constituency_id')->references('id')->on('constituencies')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('geometry');
	}
}
