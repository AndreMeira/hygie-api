<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnWeightRenameUserBodyParamsTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    /*
        Schema::table('user_body_params', function (Blueprint $table) {
            $table->renameColumn("weight", "weight_old");
        });

        Schema::table('user_body_params', function (Blueprint $table) {
            $table->renameColumn("weight_precise", "weight");
	});
	     */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_body_params', function (Blueprint $table) {
            //
        });
    }
}
