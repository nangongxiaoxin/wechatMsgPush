<?php 
    header('Connect-type:txt/html;charset=utf-8');


    function my_error($sql){
		$mysql_link = mysqli_connect('127.0.0.1:3306','root','123456') or die('数据库链接失败');
		//字符集处理
		mysqli_query($mysql_link,"set names utf8");
		//选择数据库
		mysqli_query($mysql_link,"use push_to_cop");

		//执行操作
		$res=mysqli_query($mysql_link,$sql);

		//处理可能存在的错误
		if(!$res){
			echo 'sql执行错误，错误编号为:' . mysqli_errno($mysql_link) . '<br/>';
			echo 'sql执行错误，错误信息为:' . mysqli_error($mysql_link) . '<br/>';

			//终止
			exit();
		}
		//mysqli_free_result($res);
        mysqli_close($mysql_link); 
		//var_dump(mysqli_fetch_array($res));		
        //return mysqli_fetch_array($res);
		return $res;
	}
	//my_error("select passWord from user where userName='test' ");
?>  