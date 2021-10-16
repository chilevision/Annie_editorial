<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Rundowns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rundowns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->timestamp('starttime');
            $table->timestamp('stoptime');
            $table->timestamps();
        });

        Schema::create('rundown_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rundown_id');
            $table->foreign('rundown_id')->references('id')->on('rundowns')->onDelete('cascade');
            $table->integer('position');
            $table->char('color', 6);
            $table->string('story');
            $table->string('talent')->nullable();
            $table->string('cue')->nullable();
            $table->string('type');
            $table->string('source')->nullable();
            $table->string('audio')->nullable();
            $table->integer('duration');
            $table->binary('script')->nullable();
            $table->boolean('autotrigg')->default(1);
            $table->timestamps();
        });

        Schema::create('rundown_meta_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('row_id');
            $table->foreign('row_id')->references('id')->on('rundown_rows')->onDelete('cascade');
            $table->string('title');
            $table->string('type');
            $table->string('source');
            $table->integer('start');
            $table->integer('duration');
            $table->binary('data')->nullable();
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
        Schema::dropIfExists('rundown_meta_rows');
        Schema::dropIfExists('rundown_rows');
        Schema::dropIfExists('rundowns');
    }
}
