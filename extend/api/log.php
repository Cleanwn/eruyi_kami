<?php
/*
Name:删除系统日志
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
$log_key = isset($_GET['key']) && !empty($_GET['key']) ? purge($_GET['key']) : json(201,'秘钥为空');//没有秘钥不允许访问
if(defined('LOG_DEL') && LOG_DEL > 0){
	if($log_key != LOG_KEY)json(200,'KEY有误');
	$res = Db::table('log')->where('time','<',time() - (LOG_DEL*86400))->del();//false
	if($res == 0 || $res == true){
		json(200,'执行成功');
	}json(201,'执行失败');
}json(201,'未开启日志清除功能');
	
?>