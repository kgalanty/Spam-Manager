<?php

namespace WHMCS\Module\Addon\ChatManager\app;

use WHMCS\Database\Capsule as DB;

class ClientAreaDispatcher
{
  public static function processRequest($vars)
  {

    if (!$_GET['a']) {
      // Default to index if no action specified
      $action = 'index';
    } else $action = trim($_GET['a']);
    $controller = 'WHMCS\\Module\\Addon\\ChatManager\\app\\Controllers\\CA\\' . ucfirst($action);
    if (class_exists($controller)) {
      $controller = new $controller($vars);
    } else {
      return ['error' => 'Controller does not exist'];
    }
    // Verify requested action is valid and callable
    $method = $_SERVER['HTTP_CUSTOMMETHOD'] ? strtolower($_SERVER['HTTP_CUSTOMMETHOD']) : strtolower($_SERVER['REQUEST_METHOD']);
    if (is_callable(array($controller, $method))) {
      $return = $controller->$method($vars);

      return $return;
    } else {
      return ['error' => 'Action does not exist'];
    }
  }
}
