<?php namespace SublimeArts\SublimeStripe\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Single Charges Back-end Controller
 */
class SingleCharges extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('SublimeArts.SublimeStripe', 'sublimestripe', 'singlecharges');
    }
}