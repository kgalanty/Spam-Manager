<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers;
use WHMCS\Module\Addon\SpamManager\app\Middlewares\AuthMid;

abstract class API
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
