<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyDataIdToDailyDataDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_data_days', function (Blueprint $table) {
		$table->integer("daily_data_id");
		$table->integer("calories")->nullable()->change();
		$table->integer("carbs")->nullable()->change();
		$table->integer("proteins")->nullable()->change();
		$table->integer("fat")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_data_days', function (Blueprint $table) {
            //
        });
    }
}
