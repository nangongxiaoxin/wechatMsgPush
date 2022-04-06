<?php
    	header('Connect-type:txt/html;charset=utf-8');

    function upload($corpid,$secret,$type){
        $tragetDate["media"]=new \CURLFile(realpath(__DIR__."/1.mp4"));
        $app_access_token = getAccessToken($corpid,$secret);
        $result=submitMedia($tragetDate,$type,$app_access_token);
        //echo '<pre>';
        //var_dump($result);
        return $result["media_id"];
    }

    function submitMedia($data,$type,$app_access_token){
        $url='https://qyapi.weixin.qq.com/cgi-bin/media/upload?access_token='.$app_access_token.'&type='.$type;
        $result=httpd($url,$data);
        $rs=json_decode($result,true);
        return $rs;
    }

    function httpd($url,$data=[],$header=[]){
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
        if(!empty($data)){
            curl_setopt($curl,CURLOPT_POST,1);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        }
        if(!empty($header)){
            curl_setopt($curl,CURLOPT_HEADER,$header);
        }
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        $array=curl_exec($curl);
        curl_close($curl);
        return $array;
    }
?>