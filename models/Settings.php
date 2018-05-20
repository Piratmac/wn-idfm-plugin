<?php namespace Piratmac\Idfm\Models;

use Model;

class Settings extends Model
{
  use \October\Rain\Database\Traits\Validation;
  public $implement = ['System.Behaviors.SettingsModel'];

  // A unique code
  public $settingsCode = 'piratmac_idfm_settings';

  // Reference to field configuration
  public $settingsFields = 'fields.yaml';

  public $rules = [
    'end_time' => 'after:start_time',
  ];
}