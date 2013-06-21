<?php
include 'funcs.php';
//Проверка браузером
if(isset($_REQUEST['check'])) {
    $check = (int)$_REQUEST['check'];

    $mysql = new MySql();
    //проверяем есть ли у сессии девайс и юзер
    $sql    = "SELECT * FROM logins WHERE code = '{$check}'";
    $data = $mysql->select($sql);

    if(empty($data)) die;
    $userId = @$data[0]['user_id'];
    $devId = @$data[0]['id'];

    if((int)$userId === 0) die;

    $sql = "DELETE FROM tempo WHERE `code = '{$check}'";
    $mysql->delete($sql);

    $user_tempo_code = rand(123123123,345345345345);
    $sql    = "INSERT INTO session (user_id,code) VALUES ({$userId},'{$user_tempo_code}')";
    $mysql->insert($sql);

    $sql    = "DELETE FROM logins WHERE code = '{$check}'";
    $mysql->delete($sql);
    $mysql->disconnect();
    //все ОК логиним и редиректим
    setcookie('user_session',$user_tempo_code, null, '/');

    echo "{$rootUrl}secret.php";
}

//тут мы проверяем устройство и соответствие его с аккаунтом, генерируем ссылку входа
if(isset($_REQUEST['uid'])) {
    //Отсутствует кука = отказываем (пока это единственный критерий привязки)
    if(!isset($_COOKIE['device_binder'])) die('Устройство не привязано ни к одному аккаунту');

    //сессия логина
    $session = (int)$_REQUEST['uid'];

    //девайс
    $device = $_COOKIE['device_binder'];
    $code = rand(10000000,11111111111);

    $mysql = new MySql();
    //вытаскиваем соответствие девайс = юзер
    $sql    = "SELECT * FROM binded_devices WHERE cookie = '{$device}'";
    $data = $mysql->select($sql);

    if(empty($data)) die;
    $userId = @$data[0]['user_id'];
    $devId = @$data[0]['id'];

    if((int)$userId === 0) die;

    $sql = "UPDATE logins SET user_id = {$userId}, device_id = {$devId} WHERE code = '{$session}'";
    $mysql->update($sql);
    $mysql->disconnect();

    die('OK');
}