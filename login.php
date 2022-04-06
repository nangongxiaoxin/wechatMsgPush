<?php
    include_once './sql.php';
    $userName=$_POST['userName'];
    $password=$_POST['password'];
    
    $sql="select passWord from user where userName='{$userName}'";    
    $select_passWord= mysqli_fetch_array(my_error($sql))["passWord"];

    if($select_passWord==NULL){
        echo "该用户不存在";
        //进行跳转到注册页面
        exit();
    }
    if($password != $select_passWord){
        echo "密码错误".'<br>';
    }else{
        echo "密码正确".'<br>';
        $time=time();
        $cookieKey=$select_passWord . "tianhuan" . $time . $userName;
        $cookieKey=hash("sha256",$cookieKey);

        $sql="update user set cookieKey='{$cookieKey}',cookieKeyTime='{$time}' where userName='{$userName}'";
        my_error($sql);

        setcookie("user", "{$userName}", time()+6000,"/");
        //key仅仅用于后端验证key值是否被修改,以及userName是否正确
        setcookie("key","{$cookieKey}",time()+6000,"/");
        
        header('Refresh:1;url=./index.html');
        // echo "欢迎".$_COOKIE["user"]."用户";
         echo "<h1>欢迎".$userName."用户</h1>";


        // var_dump($cookieKey);
        // echo '<br>';
        // var_dump($password);
        // var_dump($select_passWord);

    }

?>


