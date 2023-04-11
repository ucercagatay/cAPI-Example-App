<?php

use Illuminate\Support\Facades\Facade;

return [
    'access_token'=>env('FB_ACCESS_TOKEN',null),
    'pixel_id'=> env('FB_PIXEL_ID',null),
    'api_version' => env('FB_API_VERSION',null),
    'fb_app_id'=> env('FB_APP_ID',null),
    'fb_app_secret'=> env('FB_APP_SECRET',null),
    'fb_app_test'=>env('FB_ADS_TEST',null),
];
