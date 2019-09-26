<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('setting_id')->unsigned()->index()->nullable();
            $table->unsignedBigInteger('site_id')->unsigned()->index()->nullable()->default(null);

            $table->string('data_type')->nullable()->default('string');
            $table->string('value')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('settings');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_values');
    }
}
