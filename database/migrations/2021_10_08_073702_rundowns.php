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
            $table->boolean('sortable')->default(1);
            $table->timestamp('starttime');
            $table->timestamp('stoptime');
            $table->timestamps();
        });

        Schema::create('rundown_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rundown_id');
            $table->foreign('rundown_id')->references('id')->on('rundowns')->onDelete('cascade');
            $table->integer('before_in_table')->nullable();
            $table->char('color', 6);
            $table->string('story');
            $table->string('talent')->nullable();
            $table->string('cue')->nullable();
            $table->string('type');
            $table->string('source')->nullable();
            $table->string('audio')->nullable();
            $table->integer('duration');
            $table->binary('script')->nullable();
            $table->binary('cam_notes')->nullable();
            $table->boolean('autotrigg')->default(1);
            $table->boolean('locked')->default(0);
            $table->boolean('script_locked')->default(0);
            $table->boolean('cam_notes_locked')->default(0);
            $table->timestamps();
        });

        Schema::create('rundown_meta_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rundown_rows_id');
            $table->foreign('rundown_rows_id')->references('id')->on('rundown_rows')->onDelete('cascade');
            $table->string('title');
            $table->string('type');
            $table->string('source');
            $table->integer('delay');
            $table->integer('duration');
            $table->binary('data')->nullable();
            $table->boolean('locked')->default(0);
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
