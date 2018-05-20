<?php namespace Piratmac\Idfm\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Stops extends Controller
{
    public $implement = [ 'Backend\Behaviors\ListController'];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Piratmac.Idfm', 'IDFM', 'stops');

        $overallMenu = BackendMenu::getActiveMainMenuItem();
        $context = BackendMenu::getContext();
        $this->breadcrumbTitle = trans($overallMenu->sideMenu[$context->sideMenuCode]->label);
    }
}
