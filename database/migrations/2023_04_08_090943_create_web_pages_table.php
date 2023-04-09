<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->foreign('website_id')->references('id')->on('websites');
            $table->string('name')->nullable();
            $table->integer('visits')->default(0);
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
        Schema::dropIfExists('web_pages');
    }
}
