<?php namespace Piratmac\Idfm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePiratmacIdfmLinesRaw extends Migration
{
    public function up()
    {
        Schema::create('piratmac_idfm_lines_raw', function($table)
        {
            $table->engine = 'InnoDB';
            $table->string('ID_Line', 255);
            $table->string('ExternalCode_Line', 255)->nullable();
            $table->string('Name_Line', 255)->nullable();
            $table->string('ShortName_Line', 255)->nullable();
            $table->string('TransportMode', 255)->nullable();
            $table->string('TransportSubmode', 255)->nullable();
            $table->string('OperatorRef', 255)->nullable();
            $table->string('OperatorName', 255)->nullable();
            $table->string('NetworkRef', 255)->nullable();
            $table->string('NetworkName', 255)->nullable();
            $table->string('ID_GroupOfLines', 255)->nullable();
            $table->string('ShortName_GroupOfLines', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('piratmac_idfm_lines_raw');
    }
}