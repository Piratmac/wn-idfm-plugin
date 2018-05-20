<?php namespace Piratmac\Idfm\Models;

use Model;
use Backend\Models\ImportModel;
use Piratmac\Idfm\Models\LineRaw;

/**
 * Model
 */
class ImportLine extends \Backend\Models\ImportModel
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $primaryKey = 'ID_Line';


    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'piratmac_idfm_lines_raw';


    public function importData($results, $sessionKey = null)
    {
      foreach ($results as $row => $data) {
        try {
          $line = ImportLine::firstOrNew(['ID_Line' => $data['ID_Line']]);
          $line->fill($data);
          $line->save();
          if ($line->wasRecentlyCreated)
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
