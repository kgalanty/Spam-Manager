<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers;
use WHMCS\Module\Addon\ChatManager\app\Middlewares\AuthMid;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl; 
abstract class APIProtected
{
    use AuthMid;

    public $params, $input;
    //public static $needAuth;
    public function __construct($params, $input)
    {
        //Vars from module output function
        $this->params = $params;
        //Entire php input variables
        $this->input = $input;
       
        if(!$this->checkPermission())
        {
            exit;
        }
    }
}
