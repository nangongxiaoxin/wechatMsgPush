<?php 
	header('Connect-type:txt/html;charset=utf-8');

    include_once './sql.php';

    //单独引入配置文件
    include_once './config.php';
    include_once './getAccessToken.php';
    include_once './upload.php';

    
    // json处理
    $json_str=file_get_contents("php://input");
    $json_str=json_decode($json_str);

    // 类型提取
    $type=$json_str->Type;

    

    // 消息体构建
    switch($type){
        //文本消息
        case 'text':
            $content=$json_str->Content;
            $msg=array(
                'touser'=>'@all',
                'msgtype'=>'text',
                'agentid'=>$agentid,
                $type=>array(
                    "content"=>$content
                )
            );
            break;

        //卡片消息
        case 'textcard':
            $title=$json_str->Title;
            $description=$json_str->Description;
            $url=$json_str->Url;
            $msg=array(
                'touser'=>'@all',
                'msgtype'=>'textcard',
                'agentid'=>$agentid,
                $type=>array(
                    "title"=>$title,
                    "description"=>$description,
                    "url"=>$url,
                    "btntxt"=>"更多"
                )
            );
            break;

        // // markdown消息
        // case 'markdown':
        //     $description=$json_str->Content;
        //     $msg=array(
        //         'touser'=>'@all',
        //         'msgtype'=>'markdown',
        //         'agentid'=>$agentid,
        //         'markdown'=>array(
        //             "content"=>$description
        //         )
        //     );
        //     // echo $description;
        //     break;
        default:
            exit('错误switch');
    }







    // $type=$_POST['selectType'];

    // $content=isset($_POST[$type])?trim($_POST[$type]):'';


    
    
    
    // 使用type的类型进行分支选择，为msg赋值

    //图片media_id(注意开启此处函数后会使其上传两次文件)
    //echo $media_id=uploadImg($corpid,$secret) .'<br/>';
    //传输类型video image file voice textcard text markdown news
    
    //$type="textcard";
    // $content="你好";

    //test
    //markdown消息
    // $msg=array(
    //         'touser'=>'@all',
    //         'msgtype'=>$type,
    //         'agentid'=>$agentid,
    //         $type=>array(
    //             "content"=>"您的会议室已经预定，稍后会同步到`邮箱` 
    //                         >**事项详情** 
    //                         >事　项：<font color=\"info\">开会</font> 
    //                         >组织者：@miglioguan 
    //                         >参与者：@miglioguan、@kunliu、@jamdeezhou、@kanexiong、@kisonwang 
    //                         > 
    //                         >会议室：<font color=\"info\">广州TIT 1楼 301</font> 
    //                         >日　期：<font color=\"warning\">2018年5月18日</font> 
    //                         >时　间：<font color=\"comment\">上午9:00-11:00</font> 
    //                         > 
    //                         >请准时参加会议。 
    //                         > 
    //                         >如需修改会议信息，请点击：[修改会议信息](https://work.weixin.qq.com)"
    //         )
    // );


    //文件消息
    // $msg=array(
    //         'touser'=>'@all',
    //         'msgtype'=>$type,
    //         'agentid'=>$agentid,
    //         $type=>array(
    //             "media_id"=>upload($corpid,$secret,$type),
    //             "title"=>"我是标题",
    //             "description"=>"我是描述"
    //         )
    // );

    //视频消息
    // $msg=array(
    //         'touser'=>'@all',
    //         'msgtype'=>$type,
    //         'agentid'=>$agentid,
    //         $type=>array(
    //             "media_id"=>upload($corpid,$secret,$type),
    //             "title"=>"我是标题",
    //             "description"=>"我是描述"
    //         )
    // );

    // //图片消息
    // $msg=array(
    //         'touser'=>'@all',
    //         'msgtype'=>'$type,
    //         'agentid'=>$agentid,
    //         $type=>array(
    //             "media_id"=>upload($corpid,$secret,$type),
    //         )
    // );



    //图文消息
	// $msg = array(
    //          'touser'=>'@all', 
    //          'msgtype'=>$type,
    //          'agentid'=>$agentid,
    //          $type=>array(
    //              "articles"=> array(0=>array(
    //                  "title"=>"中秋",
    //                  "description"=>"今年中秋节公司有豪礼相送",
    //                  "url"=>"http://qq.com",
    //                  "picurl"=>"http://res.mail.qq.com/node/ww/wwopenmng/images/independent/doc/test_pic_msg1.png"
    //              ))
    //          )
    // );

// 使用js请求时需要开启cookie参数携带

    if (isset($_COOKIE["user"])&&$_COOKIE["user"]!=""&&$_COOKIE["key"]!=""){

        $sql="select cookieKey from user where userName='{$_COOKIE["user"]}'";
        
        if((mysqli_fetch_array(my_error($sql))["cookieKey"])==$_COOKIE["key"]){
            //token获取
	        $app_access_token = getAccessToken($corpid,$secret);
            //推送链接
	        $customMessageSendUrl = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$app_access_token."&debug=1";
            postPush($msg,$customMessageSendUrl);
        }else{
            echo "失败";
        }
    }else{
        header('Refresh:2;url=./login.html');
        exit("未登录或者登录失效!");
    }

    //post请求
    function postPush($msg,$customMessageSendUrl){
        $postJosnData = json_encode($msg);
        $ch = curl_init($customMessageSendUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postJosnData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $reInfo = curl_exec($ch);
        // 回调
        returnInfo($reInfo);
        curl_close($ch);//关闭curl
        // var_dump($data);
    }

    function returnInfo($reInfo){
        $reInfo=json_decode($reInfo,true);
        echo $reInfo["errmsg"];
        // echo $reInfo["msgid"];
    }
?>