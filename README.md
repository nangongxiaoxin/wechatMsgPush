# wechatMsgPush
对接企业微信，实现消息推送
## 注意:
 1. 需要在config.php 填入对应的接口配置信息
 2. 需要使用Mysql创建对应的数据库文件
  (1) 创建 push_to_cop 数据库；
  ```
  	CREATE DATABASE push_to_cop;
  ```
  (2) 在刚刚的数据库创建 actoken 表：
  ```
  	CREATE TABLE `actoken` (
        	`id` int(11) NOT NULL AUTO_INCREMENT,
        	`accesstoken` varchar(300) NOT NULL,
        	`time` int(11) NOT NULL,
        	PRIMARY KEY (`id`)
      	) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
   ```
  (3) 再创建 users 表：
  ```
  	CREATE TABLE `user` (
        	`id` int(11) NOT NULL AUTO_INCREMENT,
        	`userName` varchar(25) NOT NULL,
        	`passWord` varchar(200) NOT NULL,
        	`token` varchar(200) DEFAULT NULL,
        	`cookieKey` varchar(200) DEFAULT NULL,
        	`cookieKeyTime` int(11) DEFAULT NULL,
        	`grade` int(11) DEFAULT NULL,
        	PRIMARY KEY (`id`,`userName`)
      	) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
  ```
