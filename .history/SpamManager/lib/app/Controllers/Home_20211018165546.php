<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers;
use WHMCS\Module\Addon\ChatManager\Controller;
use LiveChat\Api\Client as LiveChat;

/**
 * Admin Area Controller
 */
class Home extends Controller
{
     /**
     * Index action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return array
     */
    public function __construct()
    {
         //parent::__construct();
    }
    public function index($vars)
    {
        
        //$vars['somevar'] = 'somevalue';
        return $vars;
    }
}