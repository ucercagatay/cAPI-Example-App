<?php

namespace App\Helpers;

use Carbon\Carbon;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use http\Env;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Hash;

class cAPIEvents
{
    /**
     * @var string|Repository|Application|\Illuminate\Foundation\Application|mixed
     */
    private function getKeys(){
        $keys = array();
        $keys['access_token'] = config('facebookads.access_token');
        $keys['pixel_id'] = config('facebookads.pixel_id');
        $keys['api_version'] = config('facebookads.api_version');
        $keys['app_id']=config('facebookads.fb_app_id');
        $keys['app_secret'] = config('facebookads.fb_app_secret');
        return $keys;
    }

    function connection() {
        $keys = $this->getKeys();
        $api = Api::init(null,null,$keys['access_token']);
        $api->setLogger(new CurlLogger());
    }


    public  function sendEvent($eventName,$query){
        $this->connection();
        $keys = $this->getKeys();
        foreach ($keys as $key=>$value) {
            $$key = $value;
        }
        $connection_string = "https://graph.facebook.com/$api_version/$pixel_id/events?access_token=$access_token";
        $test = PostgreSqlConnectPDO::getUsers($query);
        $succesfull_event_count = 0;
        $genders=["f","M","empty"];
        if ($test['count'] > 0) {
            $events = array();
            for ($i = 0; $i < $test["count"]; $i++ ) {
                $gender = (pg_result($test["data"],$i,'gender') != null)? $genders[pg_result($test["data"],$i,'gender')-1] : null;
                $user_data = (new UserData())
                    ->setExternalId(pg_result($test["data"],$i,'id'))
                    ->setFirstName(Hash::make(pg_result($test["data"],$i,'name')) ?? null)
                    ->setLastName(Hash::make(pg_result($test["data"],$i,'sur_name')) ?? null)
                    ->setGender($gender)
                    ->setPhone(Hash::make(pg_result($test["data"],$i,'phone_number')))
                    ->setDateOfBirth(Hash::make(pg_result($test["data"],$i,'birth_date') ?? null))
                    ->setCountryCode((strlen(pg_result($test["data"],$i,'culture')) == 2)? pg_result($test["data"],$i,'culture') : substr(pg_result($test["data"],$i,'culture'),2) )
                    // It is recommended to send Client IP and User Agent for Conversions API Events.
                    ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
                    ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
                    ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
                    ->setFbp('fb.1.1558571054389.1098115397');
                $content = (new Content())
                    ->setProductId('iyiyasa')
                    ->setQuantity(1);
                $custom_data = (new CustomData())
                    ->setContents(array($content))
                    ->setCurrency('usd')
                    ->setValue(29.99);
                $event = (new Event())
                    ->setEventName($eventName)
                    ->setEventTime(time())
                    ->setEventSourceUrl($connection_string)
                    ->setUserData($user_data)
                    ->setCustomData($custom_data)
                    ->setActionSource(ActionSource::WEBSITE);
                        array_push($events, $event);

                    $succesfull_event_count++;

            }
            if (config('facebookads.fb_app_test') == true) {
                $request = (new EventRequest($pixel_id))
                    ->setTestEventCode('TEST27817')
                    ->setEvents($events);
            }
            else {
                $request = (new EventRequest($pixel_id))
                    ->setEvents($events);
            }
            $response = $request->execute();
        dd($response);
            return $succesfull_event_count;
    }

}
    public static function eventMapper($type){
        //$start_date = Carbon::now()->format('d-m-Y');
        //$end_date = Carbon::now()->addDay()->format('d-m-Y');
        $start_date = "01-01-2023";
        $end_date = "02-01-2023";
        $signUpQuery = "SELECT * FROM public.user where create_date between '$start_date' and '$end_date'";
        $purchaseEvent = "SELECT * FROM public.user u JOIN subscriptions s ON u.id = s.user_id where s.subscription_date between '$start_date' and '$end_date'";
        $loginQuery = "SELECT * FROM public.user WHERE id IN ( SELECT user_id FROM signup_logs WHERE create_date between  '$start_date' and '$end_date'";
        switch ($type){
            case 0 : {
                return (new cAPIEvents)->sendEvent('SignUp' , $signUpQuery);
            }
            case 1 : {

                return (new cAPIEvents())->sendEvent('Purchase' , $purchaseEvent);

            }
            case 2 : {
               return (new cAPIEvents())->sendEvent('Login',$loginQuery);
            }
        }
    }
}
