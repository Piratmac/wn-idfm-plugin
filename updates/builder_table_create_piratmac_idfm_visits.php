<?php namespace Piratmac\Idfm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePiratmacIdfmVisits extends Migration
{
    public function up()
    {
        Schema::create('piratmac_idfm_visits', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('visit_id', 255)->nullable();
            $table->dateTime('record_time')->nullable();
            $table->string('line_id', 255)->nullable();
            $table->string('stop_id', 255)->nullable();
            $table->string('destination_id', 255)->nullable();
            $table->boolean('at_stop');
            $table->dateTime('departure_time')->nullable();
            $table->text('error_message')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('piratmac_idfm_visits');
    }
}