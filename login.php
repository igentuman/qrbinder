<?php
include 'backend/funcs.php';
$login_id = null;
if(isset($_COOKIE['login_id']))
$login_id = $_COOKIE['login_id'];

if($login_id) {

    $mysql = new MySql();
    $sql    = "SELECT * FROM logins WHERE code = '{$login_id}'";
    $data = $mysql->select($sql);
    $code = null;
    if(!empty($data)) {
        $uid = @$data[0]['user_id'];
        $devId = @$data[0]['id'];
        $code = @$data[0]['code'];
    }
    if(!$code)
        $login_id = null;
    $mysql->disconnect();
}

if(!$login_id) {
    $login_id = rand(1231231,123123123123);
    setcookie('login_id',$login_id);

    $mysql = new MySql();
    $sql    = "INSERT INTO logins (code) VALUES ('{$login_id}')";
    $mysql->insert($sql);
    $mysql->disconnect();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Авторизация</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
</head>
<body>
<h2>Отсканируйте код ранее привязанным устройством</h2>

<img src="http://chart.apis.google.com/chart?chs=250x250&cht=qr&chld=1/1&choe=utf-8&chl=<?php echo $rootUrl?>backend/loginer.php?uid=<?php echo $login_id?>"/>
<h3>Или классический вход</h3>
<form action="backend/manual.php" method="post">
    <label for="mail" style="margin-right: 9px">Почта</label><input type="text" name="mail" id="mail"/><br />
    <label for="password">Пароль</label><input type="password" name="password" id="password"/><br />
    <input type="submit" value="Войти"/>
</form>
<script type="text/javascript">
    bindedFlg = false;
    setInterval(function(){
        if(!bindedFlg)
            $.ajax({
                type: "POST",
                url: "<?php echo $rootUrl?>backend/loginer.php",
                data: { check: <?php echo $login_id?>}
            }).done(function(response) {
                    if(response.length > 0) {
                        bindedFlg = true;
                        location.href = response;
                    }
                });
    },3000)
</script>
</body>
</html>
