<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('postcodes', function (Blueprint $table) {
        $table->increments('id');
        $table->int('postcode');
        $table->string('suburb');
        $table->string('state');
        $table->string('dc');
        $table->string('type');
        $table->double('lat');
        $table->double('lon');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('postcodes');
    }
}
