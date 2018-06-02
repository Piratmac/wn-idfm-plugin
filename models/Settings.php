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
    'api_key' => 'alpha_num|size:56',
    'end_time' => 'after:start_time',
    'displayPastXMinutes' => 'integer',
    'displayErrorsForXHours' => 'integer',
  ];



  public function afterValidate()
  {
    if (!is_numeric(input('Settings.displayPastXMinutes')) ) {
      throw new \ValidationException(['Settings.displayPastXMinutes' => \Lang::get('system::validation.numeric', ['attribute' => \Lang::get('piratmac.idfm::lang.settings.display_past_x_minutes')])]);
    }
    if (!is_numeric(input('Settings.displayErrorsForXHours')) ) {
      throw new \ValidationException(['Settings.displayErrorsForXHours' => \Lang::get('system::validation.numeric', ['attribute' => \Lang::get('piratmac.idfm::lang.settings.displayErrorsForXHours')])]);
    }
  }

  public function getDefaultTimezoneOptions () {
    $preference = new \Backend\Models\Preference();
    return $preference->getTimezoneOptions();
  }
}