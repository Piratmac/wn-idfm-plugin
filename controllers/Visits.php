<?php namespace Piratmac\Idfm\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Artisan;
use Flash;
use Symfony\Component\Console\Output\BufferedOutput;

class Visits extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController'    ];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Piratmac.Idfm', 'IDFM', 'visits');
    }

    public function onGetVisits () {
      try {
        Artisan::call('idfm:getvisits');
        Flash::success(trans('piratmac.idfm::lang.message.update_successful'));
        return $this->listRefresh();
      }
      catch (ApplicationException $e) {
        if (Config::get('app.debug', false))
          Flash::error($e->message);
        else
          Flash::error(trans('piratmac.idfm::lang.message.error_occurred_during_update'));
      }
    }

    public function onCleanUnmonitoredVisits () {
      try {
        Artisan::call('idfm:cleanunmonitoredvisits');
        Flash::success(trans('piratmac.idfm::lang.message.clean_unmonitored_successful'));
        return $this->listRefresh();
      }
      catch (ApplicationException $e) {
        if (Config::get('app.debug', false))
          Flash::error($e->message);
        else
          Flash::error(trans('piratmac.idfm::lang.message.error_occurred_during_clean_unmonitored'));
      }
    }
}
