<?php namespace Piratmac\Idfm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePiratmacIdfmMonitoredStopIgnore extends Migration
{
    public function up()
    {
        Schema::create('piratmac_idfm_monitored_stop_ignored_destination', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('monitored_stop_id')->unsigned();
            $table->string('ignored_destination_id', 255);
            $table->primary(['monitored_stop_id', 'ignored_destination_id'], 'id_for_ignored_destinations');
        });
    }

    public function down()
    {
        Schema::dropIfExists('piratmac_idfm_monitored_stop_ignored_destination');
    }
}