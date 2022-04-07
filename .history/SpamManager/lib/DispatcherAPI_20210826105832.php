<?php

namespace WHMCS\Module\Addon\ChatManager;

/**
* Dispatch Handler
*/
class DispatcherAPI {
    
    public static function dispatch($controller, $action, $parameters, $input)
    {
        if (!$action) {
            // Default to index if no action specified
            $action = 'index';
        }
        $controller = 'WHMCS\\Module\\Addon\\ChatManager\\app\\Controllers\\API\\'.ucfirst($controller);
        if(class_exists($controller))
        {
            $controller = new $controller($parameters, $input);
        }
        else
        {
            return ['error' => 'Controller doesnt exist'];
        }
        // Verify requested action is valid and callable
        $method = $_SERVER['HTTP_CUSTOMMETHOD'] ? strtolower($_SERVER['HTTP_CUSTOMMETHOD']) : strtolower($_SERVER['REQUEST_METHOD']);
        if (is_callable(array($controller, $method))) {
            $return = $controller->$method();
            
            return $return;
        }
        else{
            return ['error' => 'Action doesnt exist'];
        }
    }
}