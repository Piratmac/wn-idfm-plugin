<?php namespace Piratmac\Idfm\Models;

use Model;

/**
 * Model
 */
class MonitoredStop extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    public $primaryKey = 'monitored_stop_id';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $fillable = ['stop', 'line', 'ignored_destinations'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'piratmac_idfm_monitored_stop';

    public $belongsTo = [
      'line' => ['Piratmac\IDFM\Models\Line',
        'table' => 'piratmac_idfm_lines',
      ],
      'stop' => ['Piratmac\IDFM\Models\Stop',
        'table' => 'piratmac_idfm_stops',
        'order' => 'name asc',
      ],
    ];

    public $belongsToMany = [
        'ignored_destinations' => ['Piratmac\IDFM\Models\Stop',
          'table' => 'piratmac_idfm_monitored_stop_ignored_destination',
          'key' => 'monitored_stop_id',
          'otherKey' => 'ignored_destination_id',
          'order' => 'name asc',
        ],
    ];

    public $hasMany = [
        'visits' => ['Piratmac\IDFM\Models\Visit',
          'table' => 'piratmac_idfm_visits',
          'key' => 'stop_id',
          'otherKey' => 'stop_id',
        ],
    ];

    public function getStopOptions()
    {
        if(is_object($this->line)) {
          return $this->line->stops->sortBy('name')->pluck('name', 'id')->toArray();
        }
        else return [];
    }

    public function getIgnoredDestinationsOptions()
    {
        if(is_object($this->line)) {
          return $this->line->stops->sortBy('name')->pluck('name', 'id')->toArray();
        }
        else return [];
    }
}
