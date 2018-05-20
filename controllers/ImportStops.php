<?php namespace Piratmac\Idfm\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Redirect;

class ImportStops extends Controller
{
    public $implement = [ 'Backend.Behaviors.ImportExportController',   ];


    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Piratmac.Idfm', 'IDFM', 'import');
    }

    public function index() {
      $this->pageTitle = trans('piratmac.idfm::lang.menu.import');
    }

    public function onRedirect() {
      return Redirect::to('backend/piratmac/idfm/'.input('target').'/import');
    }
}
