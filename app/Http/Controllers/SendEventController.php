<?php

namespace App\Http\Controllers;

use App\Helpers\cAPIEvents;
use App\Helpers\PostgreSqlConnectPDO;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SendEventController extends Controller
{

    public function sendEvent(){
        $counts = array();
        for ($i = 0; $i<1; $i++){
            $result = cAPIEvents::eventMapper($i);
            array_push($counts,$result);
        }
        return $counts;

    }

        //dd($db_connection);



}
