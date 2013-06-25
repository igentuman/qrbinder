<?php
include 'funcs.php';
if(isset($_REQUEST['mail']) && isset($_REQUEST['password'])) {
    $mysql = new MySql();
    $mail = $_REQUEST['mail'];
    $password = (int)$_REQUEST['password'];
    $sql = "SELECT * FROM users WHERE (mail = '{$mail}' AND pass = '{$password}')";
    $data = $mysql->select($sql);

    if(!$data) {
        echo "Неверная пара почта/пароль. <br /> <a href='../login.php'>Вернуться</a>";
        die;
    }

    $userId = $data[0]['id'];
    $user_tempo_code = rand(123123123,345345345345);
    $sql    = "INSERT INTO session (user_id,code) VALUES ({$userId},'{$user_tempo_code}')";
    $mysql->insert($sql);
    $mysql->disconnect();
    setcookie('user_session',$user_tempo_code, null, '/');
    ?>
    Вы успешно авторизованы! Перейти на <a href="../secret.php">SECRET PAGE</a>;
<?php
}