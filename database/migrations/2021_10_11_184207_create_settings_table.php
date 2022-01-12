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
            $table->string('name')->nullable();
            $table->integer('max_rundown_lenght')->nullable();
            $table->string('videoserver_name')->nullable();
            $table->string('videoserver_ip', 45)->nullable();
            $table->smallInteger('videoserver_port')->unsigned()->default(5250);
            $table->smallInteger('videoserver_channel')->unsigned()->default(1);
            $table->string('templateserver_name')->nullable();
            $table->string('templateserver_ip', 45)->nullable();
            $table->smallInteger('templateserver_port')->unsigned()->default(5250);
            $table->smallInteger('templateserver_channel')->unsigned()->default(1);
            $table->string('pusher_channel')->nullable();
            $table->string('logo_path')->nullable();
            $table->binary('colors')->nullable();
            $table->binary('mixer_inputs')->nullable();
            $table->binary('mixer_keys')->nullable();
            $table->boolean('sso')->default(0);
            $table->tinyInteger('user_ttl')->default(0);
            $table->timestamp('media_updated')->nullable();
            $table->timestamp('templates_updated')->nullable();
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
