<?php
include 'backend/funcs.php';

$temp_code = null;

$userId = 1; //считаем, что пользователь авторизован и это его id в базе

$mysql = new MySql();
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
    <title>iOS binder - Привязка устройства</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
</head>
<body>
Будем считать, что ты сейчас авторизован в своем аккаунте и привязываешь к нему устройство.<br />
<img src="http://chart.apis.google.com/chart?chs=250x250&cht=qr&chld=1/1&choe=utf-8&chl=<?php echo $rootUrl;?>backend/binder.php?bind=<?php echo $temp_code?>"/>

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
                    location.href = 'http://ostapchik.dev/iphone/';

                }
            });
    },3000)
</script>
</body>
</html>
