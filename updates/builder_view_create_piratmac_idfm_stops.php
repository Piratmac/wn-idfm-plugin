<?php namespace Piratmac\Idfm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Db;

class BuilderViewCreatePiratmacIdfmStops extends Migration
{
    public function up()
    {
        Db::statement("CREATE VIEW piratmac_idfm_stops AS
                        SELECT
                          id,
                          MonitoringRef_ZDE as idfm_id,
                          reflex_lda_nom as name,
                          Lineref as line_idfm_id,
                          codifligne_line_id as line_id
                        FROM piratmac_idfm_stops_raw");
    }

    public function down()
    {
        Db::statement("DROP VIEW piratmac_idfm_stops");
    }
}