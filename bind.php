<?php
include 'backend/funcs.php';

$temp_code = null;
$mysql = new MySql();
$rand = rand(123,44444);
$name = 'Test User-'.$rand;
$mail = 'mail'.$rand.'@igentu.com';
$pass = $rand;

if(!isset($_COOKIE['current_user'])) {
//Генерируем нового пользователя (для теста)
$sql    = "INSERT INTO users (name,mail,pass) VALUES ('{$name}','{$mail}','{$pass}')";
$result = $mysql->insert($sql);
if((int)$result === 0) die('Cannot create new user');
$userId = $result; //считаем, что пользователь авторизован и это его id в базе

    setcookie('current_user',$userId,time()+60);
    setcookie('current_user_name',$name,time()+60);
    setcookie('current_user_mail',$mail,time()+60);
    setcookie('current_user_pass',$pass,time()+60);
} else {
    $userId = @$_COOKIE['current_user'];
    $name = @$_COOKIE['current_user_name'];
    $mail = @$_COOKIE['current_user_mail'];
    $pass = @$_COOKIE['current_user_pass'];
}

$sql    = 'SELECT * FROM tempo WHERE user_id = '.$userId;
$data = $mysql->select($sql);

if(!empty($data)) {
    $temp_code = $data[0]['code'];
}

if(!$temp_code) {
    $temp_code = rand(10000000,1231231231231);
    $sql    = "INSERT INTO tempo (user_id,code) VALUES ({$userId},'{$temp_code}')";
    $mysql->insert($sql);
}
$mysql->disconnect();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Привязка устройства</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
</head>
<body>
<?php if(isset($_COOKIE['current_user'])) {
    echo 'Пользователей можно генерировать не чаще 1го раза в минуту';
}?>
<h3>Текущий аккаунт:</h3>
<b><?php echo $name?></b><br />
<b><?php echo $mail?></b><br />
<b><?php echo 'Пароль:  '.$pass?></b><br />
<i>Запомните эти данные на всякий случай</i><br />

<img src="http://chart.apis.google.com/chart?chs=250x250&cht=qr&chld=1/1&choe=utf-8&chl=<?php echo $rootUrl;?>backend/binder.php?bind=<?php echo $temp_code?>"/><br />

<script type="text/javascript">
    bindedFlg = false;
    setInterval(function(){
        if(!bindedFlg)
        $.ajax({
            type: "POST",
            url: "<?php echo $rootUrl;?>backend/binder.php",
            data: { temp_code: <?php echo $temp_code?>}
        }).done(function(response) {
                if(response.length > 0) {
                    bindedFlg = true;
                    alert('Устройство привязано, можно авторизовываться');
                    location.href = '<?php echo $rootUrl;?>';

                }
            });
    },3000)
</script>
<i>Отсканируйте код для привязки устройства к текущему аккаунту</i><br/><br/>
<a href="index.php">На главную (Выход)</a>
</body>
</html>
