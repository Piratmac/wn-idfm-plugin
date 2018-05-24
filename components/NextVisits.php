<?php namespace Piratmac\Idfm\Components;

use Cms\Classes\ComponentBase;
use Piratmac\Idfm\Models\MonitoredStop;
use Carbon;
use Piratmac\Idfm\Models\Settings as IDFMSettings;

class NextVisits extends ComponentBase
{
    public $monitored_stops;

    public function componentDetails()
    {
        return [
            'name'        => 'Next visits',
            'description' => 'Displays the next visits for monitored stops'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun () {
      $this->addCss('/plugins/piratmac/idfm/assets/css/idfm.css');
      $this->monitored_stops = MonitoredStop::with([
        'visits' => function ($query) {
          $query->where(function ($query) {
                # This part keeps only recent departures (departure in last 5 minutes or in the future) and the error ones (for 1 day only)
                  $query->where('departure_time', '>=', Carbon\Carbon::now()->addMinutes(-1 * IDFMSettings::get('displayPastXMinutes')))
                        ->orWhere('error_message', '!=', '')->where('record_time', '>=', Carbon\Carbon::now()->addHours(-1 * IDFMSettings::get('displayErrorsForXHours')));
                })
                # This part removes the destinations marked as "ignored" by the users
                ->whereNotIn('destination_id', function ($query) {
                  $query->select('ignored_destination_id')
                        ->from('piratmac_idfm_monitored_stop_ignored_destination')
                        ->join('piratmac_idfm_monitored_stop', 'piratmac_idfm_monitored_stop.monitored_stop_id', '=', 'piratmac_idfm_monitored_stop_ignored_destination.monitored_stop_id');
                  });
        }],
        'visits.stop', 'visits.line', 'visits.destination')
        ->get();
    }
}