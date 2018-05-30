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
    \Debugbar::info(input('displayPastXMinutes'));
    \Debugbar::info(is_numeric(input('displayPastXMinutes')));
    if (!is_numeric(input('displayPastXMinutes')) ) {
      throw new \ValidationException(['displayPastXMinutes' => \Lang::get('system::validation.numeric', ['attribute' => \Lang::get('piratmac.idfm::lang.settings.display_past_x_minutes')])]);
    }
    if (!is_numeric(input('displayErrorsForXHours')) ) {
      throw new \ValidationException(['displayErrorsForXHours' => \Lang::get('system::validation.numeric', ['attribute' => \Lang::get('piratmac.idfm::lang.settings.displayErrorsForXHours')])]);
    }
  }
}