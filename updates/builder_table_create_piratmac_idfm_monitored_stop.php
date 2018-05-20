<?php namespace Piratmac\Idfm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePiratmacIdfmMonitoredStop extends Migration
{
    public function up()
    {
        Schema::create('piratmac_idfm_monitored_stop', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('monitored_stop_id')->unsigned();
            $table->string('label', 255);
            $table->text('stop_id');
            $table->text('line_id');
            $table->integer('sort_order')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('piratmac_idfm_monitored_stop');
    }
}
