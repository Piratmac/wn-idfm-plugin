<?php namespace Piratmac\Idfm\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Lines extends Controller
{
    public $implement = [ 'Backend\Behaviors\ListController',
                          'Backend\Behaviors\RelationController'];

    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_stopRelation.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Piratmac.Idfm', 'IDFM', 'lines');

        $overallMenu = BackendMenu::getActiveMainMenuItem();
        $context = BackendMenu::getContext();
        $this->breadcrumbTitle = trans($overallMenu->sideMenu[$context->sideMenuCode]->label);
    }

    public function details ($recordId) {
        $this->vars['line'] = \Piratmac\Idfm\Models\Line::find($recordId);
        $this->initRelation($this->vars['line']);
        $this->pageTitle = trans('piratmac.idfm::lang.menu.line_details').$this->vars['line']->name.' ('.$this->vars['line']->network_name.')';
    }
}
