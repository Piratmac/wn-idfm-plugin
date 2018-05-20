<?php namespace Piratmac\Idfm;

use System\Classes\PluginBase;
use System\Helpers\DateTime;
use Carbon;
use Models\Settings;
use Config;
use Event;

class Plugin extends PluginBase
{
    public function registerComponents() {
      return [
        'Piratmac\Idfm\Components\NextVisits' => 'nextVisits',
      ];
    }

    public function registerSchedule($schedule)
    {
      $schedule->command('idfm:getvisits')->everyMinute()->when(function () {
        $start_time = new Carbon\Carbon (Models\Settings::get('start_time'));
        $start_time = Carbon\Carbon::createFromFormat('H:i:s', $start_time->format('H:i:s'), Config::get('app.timezone'));
        $end_time = new Carbon\Carbon (Models\Settings::get('end_time'));
        $end_time = Carbon\Carbon::createFromFormat('H:i:s', $end_time->format('H:i:s'), Config::get('app.timezone'));

        return (Carbon\Carbon::now(Config::get('app.timezone')) >= $start_time && Carbon\Carbon::now(Config::get('app.timezone')) <= $end_time);
      });


      $schedule->command('idfm:cleanUnmonitoredVisits')->daily();
    }

    public function registerSettings()
    {
      return [
        'settings' => [
          'label'       => trans('piratmac.idfm::lang.settings.title'),
          'description' => trans('piratmac.idfm::lang.settings.description'),
          'icon'        => 'oc-icon-train',
          'class'       => 'Piratmac\Idfm\Models\Settings',
          'order'       => 500,
          'keywords'    => 'idfm transport',
        ]
      ];
    }

    public function register()
    {
        $this->registerConsoleCommand('idfm.getvisits', 'Piratmac\Idfm\Console\GetVisits');
        $this->registerConsoleCommand('idfm.cleanUnmonitoredVisits', 'Piratmac\Idfm\Console\CleanUnmonitoredVisits');
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'time_since_short'     => [$this, 'twigTimeSinceShort']
            ],
        ];
    }
    public function registerListColumnTypes()
    {
      return [
        'time_since_short'     => [$this, 'twigTimeSinceShort']
      ];
    }
    function twigTimeSinceShort ($datetime)
    {
        return \System\Helpers\DateTime::makeCarbon($datetime)->diffForHumans(null, false, true);
    }

    public function boot() {
      Event::listen('backend.form.extendFields', function($widget) {

        // Only for the User controller
        if (!$widget->getController() instanceof \Backend\Controllers\Users) {
          return;
        }

        // Only for the User model
        if (!$widget->model instanceof \Backend\Models\User) {
          return;
        }

        $preference = new \Backend\Models\Preference();
        $widget->addTabFields([
          'timezone'  => [
            'label'   => 'backend::lang.backend_preferences.timezone',
            'comment' => 'backend::lang.backend_preferences.timezone_comment',
            'type'    => 'dropdown',
            'options' => $preference->getTimezoneOptions(),
            'default' => Config::get('app.timezone'),
          ]
        ]);
      });
    }




}
