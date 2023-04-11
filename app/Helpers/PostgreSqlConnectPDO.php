<?php

namespace App\Helpers;

use Carbon\Carbon;
use PgSql\Connection;

class PostgreSqlConnectPDO
{
    static function connect(){
        $pg_host = config('pg.pg_host');
        $pg_port = config('pg.pg_port');
        $pg_database = config('pg.pg_database');
        $pg_username = config('pg.pg_username');
        $pg_password = config('pg.pg_password');
        $connection_string = "host='$pg_host' port='$pg_port' dbname='$pg_database' user='$pg_username' password='$pg_password'";
        $client = pg_connect($connection_string);
        return $client;
}
    public static function getUsers($query = "SELECT * FROM public.user"){
        $client = PostgreSqlConnectPDO::connect();
        $result = pg_query($client,$query) or die();
        $row_count = pg_num_rows($result);
        return ['count'=> $row_count , 'data' =>$result];

    }

}
