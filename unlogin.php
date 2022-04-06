<?php
    setcookie("user","",time()+6000,"/");
    setcookie("key","",time()+6000,"/");
    echo "<h1>已注销登录信息，即将跳转到登录界面</h1>";
    header('Refresh:2;url=../c/login.html');
?>