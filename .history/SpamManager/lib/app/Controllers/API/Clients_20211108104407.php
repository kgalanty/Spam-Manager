<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\Client;

class Clients extends API
{
    public function get()
    {
        $query= $_GET['q'];
 
        if($query)
        {
            $result = Client::where('firstname', 'LIKE', '%'.$query.'%')
                ->orWhere('lastname', 'LIKE', '%'.$query.'%')
                ->orWhere('email', 'LIKE', '%'.$query.'%')
                ->take(10)
                ->get();
        }
        else
        {
            $result = Client::take(10)->get();
        }
        
       // $total = $result->count();
        return ['data' => $result];
        //     $results = DB::table('tbladmins as a')
        //    ->orderBy('a.firstname', 'ASC')
        //    ->get(['a.id', 'a.firstname', 'a.lastname']);
        //     return ['data' => $results];
    }
    public function post()
    {
    }
}
