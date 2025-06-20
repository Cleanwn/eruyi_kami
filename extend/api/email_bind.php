<?php
/*
Name:邮箱绑定接口文件 
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/	
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['reg_state']=='n')out(103,$app_res['reg_notice'],$app_res);//判断是否可注册
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录方式不允许使用当前操作
	
	$token = isset($data_arr['token']) && !empty($data_arr['token']) ? purge($data_arr['token']) : out(125,$app_res);//请输TOKEN
	$email = isset($data_arr['email']) && !empty($data_arr['email']) ? purge($data_arr['email']) : out(110,$app_res);//请输入账号
	$crc = isset($data_arr['crc']) && !empty($data_arr['crc']) ? intval($data_arr['crc']) : out(120,$app_res);//验证码为空
	
	$res_logon = Db::table('user_logon','as logon')->field('U.*')->JOIN('user','as U','logon.uid=U.id')->where('logon.appid',$appid)->where('U.appid',$appid)->where('logon.token',$token)->find();//false
	if(!$res_logon)out(127,$app_res);//TOKEN不存在或已失效
	if($res_logon['ban'] > time() || $res_logon['ban'] == 999999999)out(114,$res_logon['ban_notice'],$app_res);//账号被禁用
	Db::table('user_logon')->where('token',$token)->update(['last_t'=>time()]);//记录活动时间
	if(!empty($res_logon['email']))out(115,'当前账号已绑定邮箱，请解绑当前邮箱后绑定',$app_res);//已绑定邮箱
	
	if(!check_email($email)) out(116,'邮箱不合法',$app_res);//账号长度5~11位，不支持中文和特殊字符
	$res_user = Db::table('user')->where(['email'=>$email,'appid'=>$appid])->find();//false
	if($res_user)out(115,'该邮箱已绑定其他账号',$app_res);//邮箱已绑定
	
	$res_code = Db::table('captcha')->where(['email'=>$email,'code'=>$crc,'new'=>'y','appid'=>$appid])->order('id DESC')->find();//false
	if(!$res_code)out(124,$app_res);//验证码不正确
	Db::table('captcha')->where('id',$res_code['id'])->update(['new'=>'n']);
	
	$res = Db::table('user')->where('id',$res_logon['id'])->update(['email'=>$email]);
	
	if($res){
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>200,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(200,'绑定成功',$app_res);
	}else{
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(201,'绑定失败',$app_res);
	}
?>