<?php namespace Piratmac\Idfm\Models;

use Model;

/**
 * Model
 */
class Stop extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $incrementing = false;


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
    public $table = 'piratmac_idfm_stops';

    public $belongsTo = [
        'line' => ['Piratmac\IDFM\Models\Line',
          'table' => 'piratmac_idfm_lines',
        ],
    ];

    public $belongsToMany = [
        'monitored_stops' => ['Piratmac\IDFM\Models\MonitoredStop',
          'table' => 'piratmac_idfm_monitored_stop',
        ],
    ];
}
