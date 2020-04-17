<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyDataDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_data_days', function (Blueprint $table) {
            $table->id();
	    $table->smallInteger("day_number");
	    $table->integer("calories");
	    $table->integer("carbs");
	    $table->integer("proteins");
	    $table->integer("fat");
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
        Schema::dropIfExists('daily_data_days');
    }
}
