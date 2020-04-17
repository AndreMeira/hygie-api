<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnWeightRenameUserBodyParams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_data_days', function (Blueprint $table) {
            $table->renameColumn("weight", "weight_old");
        });

        Schema::table('daily_data_days', function (Blueprint $table) {
            $table->renameColumn("weight_precise", "weight");
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
