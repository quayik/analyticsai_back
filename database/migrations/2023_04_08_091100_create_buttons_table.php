<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateButtonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buttons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('clicks')->nullable();
            $table->unsignedBigInteger('website_id');
            $table->foreign('website_id')->references('id')->on('websites');
            $table->unsignedBigInteger('webpage_id')->nullable();
            $table->foreign('webpage_id')->references('id')->on('web_pages');
            $table->longText('description');
            $table->string('token')->nullable();
            $table->longText('recommendation')->nullable();
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
        Schema::dropIfExists('buttons');
    }
}
