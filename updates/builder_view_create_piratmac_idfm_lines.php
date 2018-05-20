<?php namespace Piratmac\Idfm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Db;

class BuilderViewCreatePiratmacIdfmLines extends Migration
{

    public function up()
    {
        Db::statement("CREATE VIEW piratmac_idfm_lines AS
                        SELECT
                          ID_Line as id,
                          Name_Line as name,
                          TransportMode as transport_mode,
                          NetworkName as network_name
                        FROM piratmac_idfm_lines_raw");
    }

    public function down()
    {
        Db::statement("DROP VIEW piratmac_idfm_lines");
    }
}