<?php
/*
Name:邮箱解绑
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/	
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['reg_state']=='n')out(103,$app_res['reg_notice'],$app_res);//判断是否可注册
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录方式不允许使用当前操作
	
	$token = isset($data_arr['token']) && !empty($data_arr['token']) ? purge($data_arr['token']) : out(125,$app_res);//请输TOKEN
	$crc = isset($data_arr['crc']) && !empty($data_arr['crc']) ? intval($data_arr['crc']) : out(120,$app_res);//验证码为空
	
	$res_logon = Db::table('user_logon','as logon')->field('U.*')->JOIN('user','as U','logon.uid=U.id')->where('logon.appid',$appid)->where('U.appid',$appid)->where('logon.token',$token)->find();//false
	if(!$res_logon)out(127,$app_res);//TOKEN不存在或已失效
	if($res_logon['ban'] > time() || $res_logon['ban'] == 999999999)out(114,$res_logon['ban_notice'],$app_res);//账号被禁用
	Db::table('user_logon')->where('token',$token)->update(['last_t'=>time()]);//记录活动时间
	if(empty($res_logon['user']) && empty($res_logon['phone']))out(110,'没有账号不可以解绑邮箱',$app_res);//没有账号不可以解绑
	if(empty($res_logon['email']))out(165,$app_res);//没有绑定
	
	$res_code = Db::table('captcha')->where(['email'=>$res_logon['email'],'code'=>$crc,'new'=>'y','appid'=>$appid])->order('id DESC')->find();//false
	if(!$res_code)out(124,$app_res);//验证码不正确
	Db::table('captcha')->where('id',$res_code['id'])->update(['new'=>'n']);
	
	$res = Db::table('user')->where('id',$res_logon['id'])->update(['email'=>'']);
	
	if($res){
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>200,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(200,'解绑成功',$app_res);
	}else{
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(201,'解绑失败',$app_res);
	}
?>