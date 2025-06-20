<?php
/*
Name:修改密码
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录方式不允许使用当前操作
	
	$user = isset($data_arr['user']) && !empty($data_arr['user']) ? purge($data_arr['user']) : out(110,$app_res);//请输账号
	$pwd = isset($data_arr['password']) && !empty($data_arr['password']) ? purge($data_arr['password']) : out(111,$app_res);//请输入密码
	$newpwd = isset($data_arr['newpassword']) && !empty($data_arr['newpassword']) ? purge($data_arr['newpassword']) : out(111,'请输入新密码',$app_res);//请输入密码
	
	$res_user = Db::table('user')->where(['pwd'=>md5($pwd),'appid'=>$appid],"(",")")->where('(user',$user)->whereOr(['email'=>$user,'phone'=>$user],")")->find();//false
	if(!$res_user)out(113,$app_res);//账号密码不正确
	if($res_user['ban'] > time() || $res_user['ban'] == 999999999)out(114,$res_user['ban_notice'],$app_res);//账号被禁用
	$res = Db::table('user')->where('id',$res_user['id'])->update(['pwd'=>md5($newpwd)]);
	
	//die($res); 
	if($res){
		if(defined('USER_LOG') && USER_LOG == 1){
			Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>200,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);//记录日志
		}
		out(200,'修改成功',$app_res);
	}else{
		if(defined('USER_LOG') && USER_LOG == 1){
			Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getip(),'appid'=>$appid]);//记录日志
		}
		out(201,'修改失败',$app_res);
	}
?>