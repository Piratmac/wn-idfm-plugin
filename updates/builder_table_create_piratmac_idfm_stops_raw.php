<?php namespace Piratmac\Idfm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePiratmacIdfmStopsRaw extends Migration
{
    public function up()
    {
        Schema::create('piratmac_idfm_stops_raw', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('MonitoringRef_ZDE', 255);
            $table->integer('reflex_lda_id')->unsigned()->nullable();
            $table->string('reflex_lda_nom', 255)->nullable();
            $table->integer('reflex_zdl_id')->unsigned()->nullable();
            $table->string('reflex_zdl_nom', 255)->nullable();
            $table->integer('reflex_zde_id')->unsigned()->nullable();
            $table->string('reflex_zde_nom', 255)->nullable();
            $table->string('gtfs_stop_id', 255);
            $table->string('Lineref', 255)->nullable();
            $table->string('gtfs_line_name', 255)->nullable();
            $table->string('codifligne_line_id', 255)->nullable();
            $table->string('codifligne_line_externalcode', 255)->nullable();
            $table->string('codifligne_network_name', 255)->nullable();
            $table->string('gtfs_agency', 255)->nullable();
            $table->integer('Dispo')->unsigned()->nullable();
            $table->double('reflex_zde_x', 10)->nullable();
            $table->double('reflex_zde_y', 10)->nullable();
            $table->string('xy', 255)->nullable();
            $table->unique(['gtfs_stop_id', 'codifligne_line_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('piratmac_idfm_stops_raw');
    }
}