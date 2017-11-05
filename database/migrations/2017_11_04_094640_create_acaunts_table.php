<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcauntsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('acaunts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('autologin')->unique();
			$table->integer('proxy_id')->unsigned()->default(0);
			$table->foreign('proxy_id')->references('id')->on('proxies');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('acaunts');
	}
}
