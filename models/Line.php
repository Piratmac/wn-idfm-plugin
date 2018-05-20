<?php namespace Piratmac\Idfm\Models;

use Model;

/**
 * Model
 */
class Line extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    public $incrementing = false;

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'piratmac_idfm_lines';

    public $hasMany = [
      'stops' => ['Piratmac\IDFM\Models\Stop',
        'table' => 'piratmac_idfm_stops',
      ],
      'stops_count' => ['Piratmac\IDFM\Models\Stop',
        'table' => 'piratmac_idfm_stops',
        'count' => true,
      ],
      'monitored_stops' => ['Piratmac\IDFM\Models\MonitoredStop',
        'table' => 'piratmac_idfm_monitored_stop',
      ],
    ];
}
