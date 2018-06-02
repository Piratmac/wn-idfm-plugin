<?php namespace Piratmac\Idfm\Models;

use Model;
use Carbon;
use Piratmac\Idfm\Models\Settings as IDFMSettings;

/**
 * Model
 */
class Visit extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \Piratmac\Idfm\Traits\Timezonable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $rules = [
    ];

    public $timezonable = [
      'record_time','departure_time'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'piratmac_idfm_visits';

    public $belongsTo = [
        'line' => ['Piratmac\IDFM\Models\Line',
          'table' => 'piratmac_idfm_lines',
        ],
        'stop' => ['Piratmac\IDFM\Models\Stop',
          'table' => 'piratmac_idfm_stops',
          'order' => 'name asc',
        ],
        'destination' => ['Piratmac\IDFM\Models\Stop',
          'table' => 'piratmac_idfm_stops',
          'key' => 'destination_id',
          'order' => 'name asc',
        ],
    ];

    public function scopeRecentOnly ($query) {
      return $query->where('departure_time', '>=', Carbon\Carbon::now()->addMinutes(-1 * IDFMSettings::get('displayPastXMinutes')));
    }
}
