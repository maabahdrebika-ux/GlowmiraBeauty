<?php
namespace App\Services;

use App\Models\Notification;
use App\Models\Notifications as ModelsNotifications;

class Notifications
{
    public function addnotifaction($title,$users_id,$route)
    {

        $not=new ModelsNotifications();
        $not->title=$title;
        $not->users_id=$users_id;
        $not->route=$route;
        $not->save();
        return $not; 
     
       
    }
}
