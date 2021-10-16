<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('max_rundown_lenght')->nullable();
            $table->string('videoserver_ip', 45)->nullable();
            $table->smallInteger('videoserver_port')->unsigned()->nullable();
            $table->string('templateserver_ip', 45)->nullable();
            $table->smallInteger('templateserver_port')->unsigned()->nullable();
            $table->string('pusher_channel')->nullable();
            $table->string('logo_path')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
