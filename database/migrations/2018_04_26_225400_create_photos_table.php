<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('path');
			$table->date('date')->nullable();
			$table->string('city')->nullable();
			$table->string('photographer')->nullable();
			$table->string('license')->nullable();
			$table->boolean('is_verified')->default(false);
			$table->binary('data')->nullable();
			$table->softDeletes();
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
        Schema::dropIfExists('photos');
    }
}
