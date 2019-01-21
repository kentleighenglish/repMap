<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueStancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_stances', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('constituency_id');
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('issue_id');
            $table->unsignedInteger('electorate')->nullable(true);
            $table->unsignedInteger('turnout')->nullable(true);
            $table->unsignedTinyInteger('member_representing')->nullable(true);
            $table->string('member_stance', 255)->nullable(true);
            $table->string('constituency_stance', 255)->nullable(true);
            $table->timestamps();

			$table->foreign('constituency_id')->references('id')->on('constituencies');
			$table->foreign('member_id')->references('id')->on('members');
			$table->foreign('issue_id')->references('id')->on('issues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issue_stances');
    }
}
