<?php

use WHMCS\Module\Addon\SpamManager\Dispatcher;
use WHMCS\Module\Addon\SpamManager\app\Addon;
use WHMCS\Module\Addon\SpamManager\app\ClientAreaDispatcher;
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}
if(file_exists(__DIR__.'/vendor/autoload.php') === true)
{
    require_once(__DIR__.'/vendor/autoload.php');
}
function SpamManager_config()
{
    return Addon::config();
}
function SpamManager_output($vars)
{
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    $ctrl = isset($_REQUEST['c']) ? $_REQUEST['c'] : 'home';
    $dispatcher = new Dispatcher();
    $response = $dispatcher->dispatch($ctrl, $action, $vars);
    echo $response;
    exit;
}

function SpamManager_activate()
{
    return Addon::activate();
}
function SpamManager_deactivate()
{
    return Addon::deactivate();
}
function SpamManager_upgrade()
{
    return Addon::upgrade();
}
function SpamManager_clientarea($vars)
{
    echo ClientAreaDispatcher::processRequest($vars);
    die('test');
}