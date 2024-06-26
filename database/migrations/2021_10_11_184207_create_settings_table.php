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
            $table->string('company')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_country')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();

            $table->integer('max_rundown_lenght')->nullable();
            $table->string('pusher_channel')->nullable();
            $table->string('logo_path')->nullable();
            $table->binary('colors')->nullable();

            $table->string('videoserver_name')->nullable();
            $table->string('videoserver_ip', 45)->nullable();
            $table->smallInteger('videoserver_port')->unsigned()->default(5250);
            $table->smallInteger('videoserver_channel')->unsigned()->default(1);
            $table->string('templateserver_name')->nullable();
            $table->string('templateserver_ip', 45)->nullable();
            $table->smallInteger('templateserver_port')->unsigned()->default(5250);
            $table->smallInteger('templateserver_channel')->unsigned()->default(1);
            $table->smallInteger('backgroundserver_channel')->unsigned()->nullable();
            $table->boolean('include_background')->default(0);
            $table->boolean('include_delay')->default(1);

            $table->binary('mixer_inputs')->nullable();
            $table->binary('mixer_keys')->nullable();
            
            $table->boolean('sso')->default(0);
            $table->tinyInteger('user_ttl')->default(0);
            $table->binary('user_roles')->nullable();
            $table->string('email_address')->nullable();
            $table->string('email_name')->nullable();
            $table->string('email_subject')->nullable();
            $table->binary('removal_email_body')->nullable();
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
