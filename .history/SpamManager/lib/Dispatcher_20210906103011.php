<?php

namespace WHMCS\Module\Addon\ChatManager;

/**
* Dispatch Handler
*/
class Dispatcher {
    
    /**
    * Dispatch request.
    *
    * @param string $action
    * @param array $parameters
    *
    * @return string
    */
    private function errorOutput($error)
    {
        
    }
    private function display($smarty, $tpl)
    {
        try {
            return $smarty->fetch('header.tpl').
            $smarty->fetch($tpl.'.tpl').
            $smarty->fetch('footer.tpl');
        }
        catch (\Exception $e)
        {
            return 'error: No tpl found';
        }
    }
    public function dispatch($controller, $action, $parameters)
    {
        $auth = new \WHMCS\Auth();
        if (!$auth->isLoggedIn()) {
            $auth->redirectToLogin();
        }
        if (!$action) {
            // Default to index if no action specified
            $action = 'index';
        }
        
        if($_REQUEST['json'])
        {
            ob_clean();
            
            header('Content-Type: application/json');
            $input = json_decode(file_get_contents("php://input"), true);
            echo json_encode(DispatcherAPI::dispatch($controller, $action.'JSON',$parameters, $input), 0, 512);
            
            exit;
        }
        
        $controllerParam = $controller;
        $controller = 'WHMCS\\Module\\Addon\\ChatManager\\app\\Controllers\\'.ucfirst($controller);
        $smarty = new \Smarty;
        $moduleConfig = call_user_func($parameters['module'].'_config');
        $smarty->assign('moduleName', $moduleConfig['name']);
        
        $smarty->addTemplateDir(__DIR__.'/../templates');
        $smarty->addTemplateDir(__DIR__.'/app/Views');
        $smarty->setCompileDir(ROOTDIR.'/templates_c');
        
        if(class_exists($controller))
        {
            $controller = new $controller();
            if(method_exists($controller, 'returnComponents'))
            {
                $vueComponents = $controller->returnComponents();
            }
        }
        else
        {
            $smarty->assign('error', 'Controller doesnt exist');
            return $this->display($smarty, 'error');
            
        }
        // Verify requested action is valid and callable
        if (is_callable(array($controller, $action))) {
            $return = $controller->$action($parameters);
            
            foreach($return as $k=>$v)
            {
                $smarty->assign($k, $v);
            }
            try {
                ob_clean();
                $smarty->assign('vueComponents', $vueComponents);
                $smarty->assign('addonPath', '/modules/addons/ChatManager/lib/app');
                
                return $smarty->fetch('header.tpl').
                $smarty->fetch($controllerParam.'@'.$action.'.tpl').
                $smarty->fetch('footer.tpl');
                exit;
            }
            catch (\Exception $e)
            {
                return 'Error: Template not found.'.$e->getMessage();
            }
        }
        
        return '<p>Invalid action requested. Please go back and try again.</p>';
    }
}