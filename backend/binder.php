<?php
include 'funcs.php';
//Проверка привязки
if(isset($_REQUEST['temp_code'])) {
    $temp_code = (int)$_REQUEST['temp_code'];

    $sql  = 'SELECT * FROM tempo WHERE code = '.$temp_code;
    $mysql = new MySql();
    $data = $mysql->select($sql);

    if(empty($data)) die;
    $userId = @$data[0]['user_id'];

    if((int)$userId === 0) die;
    $sql    = 'SELECT * FROM binded_devices WHERE user_id = '.$userId;
    $data = $mysql->select($sql);
    $mysql->disconnect();
    if(empty($data)) die;
    die(json_encode($data));
}

//Привязка устройства (QR код ведет сюда)
if(isset($_REQUEST['bind'])) {

    //Раскомментировать строчку ниже, что б не давать "перепривязывать" устройства
    //if(isset($_COOKIE['device_binder'])) die($_COOKIE['device_binder']);

    $userId = (int)$_REQUEST['bind'];
    $code = rand(10000000,11111111111);

    $mysql = new MySql();
    $sql    = 'SELECT * FROM tempo WHERE code = '.$userId;
    $data = $mysql->select($sql);
    if(empty($data)) die;
    $userId = @$data[0]['user_id'];

    if((int)$userId === 0) die;
    setcookie('device_binder',$code);
    $ip = $_SERVER['REMOTE_ADDR'];
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    $sql    = "INSERT INTO binded_devices (user_id, cookie, info, ip) VALUES ({$userId},'{$code}','{$uagent}','{$ip}')";
    $mysql->insert($sql);
    $mysql->disconnect();

    die('OK');
}