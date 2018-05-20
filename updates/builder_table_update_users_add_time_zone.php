<?php namespace Piratmac\Idfm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateUsersAddTimeZone extends Migration
{
  public function up()
  {
    Schema::table('backend_users', function($table)
    {
      $table->string('timezone', 255)->nullable();
    });
  }

  public function down()
  {
    Schema::table('backend_users', function($table)
    {
        $table->dropColumn('timezone');
    });
  }
}