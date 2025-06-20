<?php
/*
Name:QQ绑定
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录方式不允许使用当前操作
	
	$token = isset($data_arr['token']) && !empty($data_arr['token']) ? purge($data_arr['token']) : out(125,$app_res);//请输TOKEN
	$openid = isset($data_arr['openid']) && !empty($data_arr['openid']) ? purge($data_arr['openid']) : out(157,$app_res);//请输入openid
	$access_token = isset($data_arr['access_token']) && !empty($data_arr['access_token']) ? purge($data_arr['access_token']) : out(157,$app_res);//请输入access_token
	$qqappid = isset($data_arr['qqappid']) && !empty($data_arr['qqappid']) ? purge($data_arr['qqappid']) : out(161,$app_res);//请输入QQ互联ID
	
	$res_logon = Db::table('user_logon','as logon')->field('U.*')->JOIN('user','as U','logon.uid=U.id')->where('logon.appid',$appid)->where('U.appid',$appid)->where('logon.token',$token)->find();//false
	if(!$res_logon)out(127,$app_res);//TOKEN不存在或已失效
	if($res_logon['ban'] > time() || $res_logon['ban'] == 999999999)out(114,$res_logon['ban_notice'],$app_res);//账号被禁用
	Db::table('user_logon')->where('token',$token)->update(['last_t'=>time()]);//记录活动时间
	
	$res_qq = Db::table('user')->where(['openid_qq'=>$openid,'appid'=>$appid])->find();//false
	if($res_qq)out(160,$app_res);//已绑定其他账号
	
	$get_data = ['access_token'=>$access_token,'openid'=>$openid,'oauth_consumer_key'=>$qqappid,'format'=>'json'];
	$qq_data = http_gets('https://graph.qq.com/user/get_user_info',$get_data);
	if(!$qq_data)out(162,$app_res);
	$json_qq = json_decode($qq_data, true);
	if(isset($json_qq['errcode']))out(158,$app_res);//错误的身份信息
	
	$res = Db::table('user')->where('id',$res_logon['id'])->update(['openid_qq'=>$openid]);
	if($res){
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>200,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(200,'绑定成功',$app_res);
	}else{
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(201,'绑定成功',$app_res);
	}
?>