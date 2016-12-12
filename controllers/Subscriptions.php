<?php namespace SublimeArts\SublimeStripe\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Subscriptions Back-end Controller
 */
class Subscriptions extends Controller
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
        BackendMenu::setContext('RainLab.User', 'user', 'subscriptions');
    }
}