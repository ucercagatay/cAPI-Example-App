<?php

use Illuminate\Support\Facades\Facade;

return [
    'pg_host'=>env('PG_HOST',null),
    'pg_port'=> env('PG_PORT',5432),
    'pg_database' => env('PG_DATABASE',null),
    'pg_username'=> env('PG_USERNAME',null),
    'pg_password'=> env('PG_PASSWORD',null)
];
