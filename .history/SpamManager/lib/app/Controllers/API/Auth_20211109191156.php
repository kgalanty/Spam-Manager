<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
class Auth extends API
{
    public function get()
    {
        if($_GET['a'] == 'readPermissions')
        {
            $data['aid'] = $_SESSION['adminid'];
            if($_SESSION['adminid'] > 0)
            {
               if(AuthControl::isAgent())
                {
                    $data['perm'] = 1;
                    
                    return ['results' => $data];
                }
                if(AuthControl::isAdmin())
                {
                    $data['perm'] = 2;
                    return ['results' => $data];
                }
               
                $data['perm'] = 0;
                return ['results' => $data];
            }
            $data['perm'] = 0;
            return ['results' => $data];
        }
    }
    public function post()
    {
    }
}
