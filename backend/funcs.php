<?php
include 'mysql.php';

$rootUrl = 'http://igentu.com/qrbind/';


function loguot()
{
    $code = null;
    if(isset($_COOKIE['user_session'])) {
        $code = $_COOKIE['user_session'];
    }
    setcookie ("user_session", "", time() - 3600);
    if($code) {
        $mysql = new MySql();
        $mysql->delete("DELETE FROM session WHERE code = '{$code}'");
        $mysql->disconnect();
    }
}