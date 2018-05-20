<?php namespace Piratmac\Idfm\Models;

use Model;
use Backend\Models\ImportModel;
use Piratmac\Idfm\Models\StopRaw;

/**
 * Model
 */
class ImportStop extends \Backend\Models\ImportModel
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'piratmac_idfm_stops_raw';


    public function importData($results, $sessionKey = null)
    {
      foreach ($results as $row => $data) {
        try {
          $stop = ImportStop::firstOrNew(['gtfs_stop_id' => $data['gtfs_stop_id'], 'codifligne_line_id' => $data['codifligne_line_id']]);
          $stop->fill($data);
          $stop->save();
          if ($stop->wasRecentlyCreated)
            $this->logCreated();
          else
            $this->logUpdated();
        }
        catch (\Exception $ex) {
          $this->logError($row, $ex->getMessage());
        }
      }
    }
}
