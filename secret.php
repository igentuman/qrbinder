<?php
include 'backend/funcs.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title> Секретная страница</title>
</head>
<body>
<?php
if(!isset($_COOKIE['user_session'])) {
    die('Не авторизован');
}
$session = $_COOKIE['user_session'];
$mysql = new MySql();
$sql    = "SELECT * FROM session WHERE code = '{$session}'";
$data = $mysql->select($sql);
if(empty($data)) die('Сессия устарела');
$uid = @$data[0]['user_id'];

$sql    = "SELECT * FROM users WHERE id = $uid";
$data = $mysql->select($sql);

if(empty($data)) die('Пользователь не найден');
$name = @$data[0]['name'];
$mail = @$data[0]['mail'];
if(!$mail)
    die('Данные не загружены');
echo 'Hi, '.$name.'<br />';
echo 'Your mail: ',$mail;
?><br />
<a href="index.php">Главная (выход)</a>
</body>
</html>