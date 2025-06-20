<?php
/*
Name:设置账号
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录方式不允许使用当前操作
	
	$token = isset($data_arr['token']) && !empty($data_arr['token']) ? purge($data_arr['token']) : out(125,$app_res);//请输TOKEN
	$user = isset($data_arr['user']) && !empty($data_arr['user']) ? purge($data_arr['user']) : out(110,$app_res);//请输账号
	$pwd = isset($data_arr['password']) && !empty($data_arr['password']) ? purge($data_arr['password']) : '';//请输入密码
	if(preg_match("/^[\w]{32}$/",$token)==0)out(126,$app_res);//TOKEN不正确，32位字符串
	if(preg_match("/^[\w]{5,11}$/",$user)==0)out(116,$app_res);//账号长度5~11位，不支持中文和特殊字符
	$res_logon = Db::table('user_logon','as logon')->field('U.*')->JOIN('user','as U','logon.uid=U.id')->where('U.appid',$appid)->where('logon.token',$token)->find();//false
	if(!$res_logon)out(127,$app_res);//TOKEN不存在或已失效
	if(!empty($res_logon['user']))out(128,$app_res);//已设置过账号
	
	if($res_logon['ban'] > time() || $res_logon['ban'] == 999999999)out(114,$res_logon['ban_notice'],$app_res);//账号被禁用
	
	Db::table('user_logon')->where('token',$token)->update(['last_t'=>time()]);//记录活动时间
	
	$res_user = Db::table('user')->where(['user'=>$user,'appid'=>$appid])->find();//false
	if($res_user)out(115,$app_res);//账号已存在
	if($pwd == ''){
		if(empty($res_logon['pwd']))out(111,$app_res);//请输入密码
		$res = Db::table('user')->where('id',$res_logon['id'])->update(['user'=>$user]);
	}else{
		if(preg_match ("/^[a-zA-Z\d.*_-]{6,18}$/",$pwd)==0) out(119,'密码长度需要满足6-18位数,不支持中文以及.-*_以外特殊字符',$app_res);//密码长度6~18位
		$res = Db::table('user')->where('id',$res_logon['id'])->update(['user'=>$user,'pwd'=>md5($pwd)]);
	}
	
	//die($res); 
	if($res){
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>200,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(200,'设置成功',$app_res);
	}else{
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(201,'设置失败',$app_res);
	}
?>