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
			$table->unsignedInteger('constituency_id')->nullable(true);
            $table->unsignedInteger('member_id')->nullable(true);
            $table->unsignedInteger('issue_id')->nullable(true);
            $table->unsignedInteger('electorate')->nullable(true);
            $table->unsignedInteger('turnout')->nullable(true);
            $table->unsignedTinyInteger('member_representing')->nullable(true);
            $table->string('member_stance', 255)->nullable(true);
            $table->string('constituency_stance', 255)->nullable(true);
            $table->timestamps();

			$table->foreign('constituency_id')->references('id')->on('constituencies')->onDelete('set null');
			$table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
			$table->foreign('issue_id')->references('id')->on('issues')->onDelete('set null');
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
