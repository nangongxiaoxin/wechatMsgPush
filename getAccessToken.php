 <?php
	header('Connect-type:txt/html;charset=utf-8');


    function getAccessToken($corpid,$secret){
        $sql="select time from actoken where id=1";
        $selected_time= mysqli_fetch_array(my_error($sql))["time"];
        
        if($tokenTime+660>time()){
            return readAccessToken_sql();
        }else{
            // 获取新的actoken并存储
            $app_access_token = getNewAccessToken($corpid,$secret);
            saveAccessToken_sql($app_access_token);
            return $app_access_token;
        }

        // //确认tokenCache的存在性
        // if(!(file_exists("tokenCache"))){
        //     fclose(fopen("tokenCache", "w"));
        // }

        // $tokenCacheFile=fopen("tokenCache", "r")or die("Unable to open file!");
        // $tokenTime=fgets($tokenCacheFile);
        // if($tokenTime+660>time()){
        //     $app_access_token=fgets($tokenCacheFile);
        //     fclose($tokenCacheFile);
        //     //echo $app_access_token;
        //     return $app_access_token;
        // }else{
        //     fclose($tokenCacheFile);    //关闭以只读打开的文件
        //     $tokenCacheFile=fopen("tokenCache", "w")or die("Unable to open file!");
        //     $app_access_token = getNewAccessToken($corpid,$secret);
        //     fwrite($tokenCacheFile, time() ."\n");
        //     fwrite($tokenCacheFile, $app_access_token);
        //     fclose($tokenCacheFile);
        //     //echo time();
        //     //echo $app_access_token;
        //     return $app_access_token;
        // }
    }

    // sqlToken存储
    function saveAccessToken_sql($app_access_token){
        $time=time();
        $sql="update actoken set accesstoken='{$app_access_token}',time='{$time}' where id=1";
        my_error($sql);
    }
    // sqlToken读取
    function readAccessToken_sql(){
        // 可优化，sql查询了两次
        $sql="select * from actoken where id=1";
        $selected_accesstoken= mysqli_fetch_array(my_error($sql))["accesstoken"];
        return $selected_accesstoken;
    }



    function getNewAccessToken($corpid,$secret){

		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" .$corpid. "&corpsecret=" .$secret;

		$ch = curl_init();//初始化curl
		curl_setopt($ch, CURLOPT_URL,$url); //要访问的地址 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过证书验证
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
		$data = json_decode(curl_exec($ch));
		if(curl_errno($ch)){
		  var_dump(curl_error($ch)); //若错误打印错误信息 
		}
		//var_dump($data); //打印信息
		$acc=objectToArray($data);
        //测试输出token
		//echo $acc["access_token"];
        return $acc["access_token"];
		curl_close($ch);//关闭curl
	}
    
    //objectToArray转换成Array
    function objectToArray($array) {  
        if(is_object($array)) {  
            $array = (array)$array;  
        } if(is_array($array)) {  
            foreach($array as $key=>$value) {  
                $array[$key] = objectToArray($value);  
                }  
        }
        return $array;
    }

?>