<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBodyParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_body_params', function (Blueprint $table) {
            $table->id();
	    $table->integer('user_id')->notNull();
	    $table->integer('height')->notNull();
	    $table->integer('weight')->notNull();
	    $table->integer('year_of_birth')->notNull();
	    $table->enum('gender', ['F', 'M'])->notNull();
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
        Schema::dropIfExists('user_body_params');
    }
}
