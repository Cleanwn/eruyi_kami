<?php
/*
Name:打卡签到
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录模式不允许使用当前操作
	
	$token = isset($data_arr['token']) && !empty($data_arr['token']) ? purge($data_arr['token']) : out(125,$app_res);//请输TOKEN
	
	$res_logon = Db::table('user_logon','as logon')->field('U.*')->JOIN('user','as U','logon.uid=U.id')->where('logon.appid',$appid)->where('U.appid',$appid)->where('logon.token',$token)->find();//false
	if(!$res_logon)out(127,$app_res);//TOKEN不存在或已失效
	if($res_logon['ban'] > time() || $res_logon['ban'] == 999999999)out(114,$res_logon['ban_notice'],$app_res);//账号被禁用
	Db::table('user_logon')->where('token',$token)->update(['last_t'=>time()]);//记录活动时间
	if($app_res['diary_award_num'] == 0)out(146,$app_res);//签到功能未启用
	
	$res = Db::table('log')->where(['uid'=>$res_logon['id'],'type'=>$act])->where('time','between',[timeRange('t_a'),timeRange('t_b')])->find();
	if($res)out(147,$app_res);//今天已经签到过了
	
	if($app_res['diary_award'] == 'vip'){
		if($res_logon['vip'] == '999999999')out(199,$app_res);//账号不存在
		if($res_logon['vip'] > time()){
			$vip = $res_logon['vip'] + 3600 * $res_fen['vip_num'];
		}else{
			$vip = time() + 3600 * $res_fen['vip_num'];
		}
		$res = Db::table('user')->where('id',$res_logon['id'])->update(['vip'=>$vip]);//更新用户资料
		if(!$res)out(201,'签到失败',$app_res);
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>200,'vip'=>$app_res['diary_award_num'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(200,'签到成功',$app_res);
	}elseif($app_res['diary_award'] == 'fen'){
		$fen = $res_logon['fen'] + $app_res['diary_award_num'];
		$res = Db::table('user')->where('id',$res_logon['id'])->update(['fen'=>$fen]);//更新用户资料
		if(!$res)out(201,'签到失败',$app_res);
		if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>200,'fen'=>$app_res['diary_award_num'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
		out(200,'签到成功',$app_res);
	}
?>