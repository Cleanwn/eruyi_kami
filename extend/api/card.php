<?php
/*
Name:充值卡密
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	
	if($app_res['logon_way'] == 0){
		$user = isset($data_arr['account']) ? purge($data_arr['account']) : '';//请输入账号
		$token = isset($data_arr['token']) ? purge($data_arr['token']) : '';//请输TOKEN
	}elseif($app_res['logon_way'] == 1){
		$mainkm = isset($data_arr['mainkm']) && !empty($data_arr['mainkm']) ? purge($data_arr['mainkm']) : out(110,'请输入出卡密',$app_res);//主卡密
	}
	$kami = isset($data_arr['kami']) && !empty($data_arr['kami']) ? purge($data_arr['kami']) : out(148,$app_res);//卡密为空
	$res_kami = Db::table('kami')->where('appid',$appid)->where('kami',$kami)->find();//false
	if(!$res_kami)out(149,$app_res);//卡密不存在
	if(!empty($res_kami['user']) or !empty($res_kami['use_time']))out(150,$app_res);//卡密已使用
	if($res_kami['state'] == 'n')out(151,$app_res);//卡密被禁用
	
	if(!empty($user)){//账号充值
		$res_user = Db::table('user')->where(['appid'=>$appid],"(",")")->where('(user',$user)->whereOr(['email'=>$user,'phone'=>$user],")")->find();//false
		if(!$res_user)out(122,$app_res);//账号不存在
		if($res_user['ban'] > time() || $res_user['ban'] == 999999999)out(114,$res_user['ban_notice'],$app_res);//账号被禁用
		$user = !empty($res_user['user']) ? $res_user['user'] : (!empty($res_user['email']) ? $res_user['email'] : $res_user['phone']);
		if($res_kami['type'] == 'vip'){
			if($res_user['vip'] == '999999999')out(199,$app_res);//已经是永久会员了
			if($res_user['vip'] > time()){//没有过期
				$vip = $res_user['vip'] + 86400 * $res_kami['amount'];
			}else{//已过期
				$vip = time() + 86400 * $res_kami['amount'];
			}
			$res = Db::table('user')->where('id',$res_user['id'])->update(['vip'=>$vip]);//更新用户资料
			if($res){
				Db::table('kami')->where('id',$res_kami['id'])->update(['use_time'=>time(),'user'=>$user]);//更新卡密信息
				if(defined('USER_LOG') && USER_LOG == 1){
					Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>200,'vip'=>$res_kami['amount'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);//记录日志
				}
				out(200,'充值成功',$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){
					Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'vip'=>$res_kami['amount'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);//记录日志
				}
				out(201,'充值失败',$app_res);
			}
		}elseif($res_kami['type'] == 'fen'){
			$fen = $res_user['fen'] + $res_kami['amount'];
			$res = Db::table('user')->where('id',$res_user['id'])->update(['fen'=>$fen]);//更新用户资料
			if($res){
				Db::table('kami')->where('id',$res_kami['id'])->update(['use_time'=>time(),'user'=>$user]);//更新卡密信息
				if(defined('USER_LOG') && USER_LOG == 1){
					Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>200,'fen'=>$res_kami['amount'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);//记录日志
				}
				out(200,'充值成功',$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){
					Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'fen'=>$res_kami['amount'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);//记录日志
				}
				out(201,'充值失败',$app_res);
			}
		}
	}elseif(!empty($token)){//token充值
		$res_logon = Db::table('user_logon','as logon')->field('U.*')->JOIN('user','as U','logon.uid=U.id')->where('U.appid',$appid)->where('logon.token',$token)->find();//false
		if(!$res_logon)out(127,$app_res);//TOKEN不存在或已失效
		if($res_logon['ban'] > time() || $res_logon['ban'] == 999999999)out(114,$res_logon['ban_notice'],$app_res);//账号被禁用
		$user = !empty($res_logon['user']) ? $res_logon['user'] : (!empty($res_logon['email']) ? $res_logon['email'] : $res_logon['phone']);
		if($res_kami['type'] == 'vip'){
			if($res_logon['vip'] == '999999999')out(199,$app_res);//已经是永久会员了
			if($res_logon['vip'] > time()){//没有过期
				$vip = $res_logon['vip'] + 86400 * $res_kami['amount'];
			}else{//已过期
				$vip = time() + 86400 * $res_kami['amount'];
			}
			$res = Db::table('user')->where('id',$res_logon['id'])->update(['vip'=>$vip]);//更新用户资料
			if($res){
				Db::table('kami')->where('id',$res_kami['id'])->update(['use_time'=>time(),'user'=>$user]);//更新卡密信息
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>200,'vip'=>$res_kami['amount'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
				out(200,'充值成功',$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>201,'vip'=>$res_kami['amount'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
				out(201,'充值失败',$app_res);
			}
		}elseif($res_kami['type'] == 'fen'){
			$fen = $res_logon['fen'] + $res_kami['amount'];
			$res = Db::table('user')->where('id',$res_logon['id'])->update(['fen'=>$fen]);//更新用户资料
			if($res){
				Db::table('kami')->where('id',$res_kami['id'])->update(['use_time'=>time(),'user'=>$user]);//更新卡密信息
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>200,'fen'=>$res_kami['amount'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
				out(200,'充值成功',$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'status'=>201,'fen'=>$res_kami['amount'],'time'=>time(),'ip'=>getip(),'appid'=>$appid]);}//记录日志
				out(201,'充值失败',$app_res);
			}
		}
	}elseif(!empty($mainkm)){
		$res_mainkm = Db::table('kami')->where('appid',$appid)->where('kami',$mainkm)->find();//false
		if(!$res_mainkm)out(149,'主卡密不存在',$app_res);//卡密不存在
		if($res_mainkm['state'] == 'n')out(151,'主卡密被禁用',$app_res);//卡密被禁用
		if($res_kami['type'] != $res_mainkm['type'])out(152,'主卡密和充值卡密类型不一样',$app_res);//主卡密和充值卡密类型不一样
		if($res_kami['type'] == 'vip'){
			if($res_mainkm['end_time'] == '999999999')out(199,$app_res);//已经是永久会员了
			if($res_mainkm['end_time'] > time()){//没有过期
				$vip = $res_mainkm['end_time'] + 86400 * $res_kami['amount'];
			}else{//已过期
				$vip = time() + 86400 * $res_kami['amount'];
			}
			$res = Db::table('kami')->where('id',$res_mainkm['id'])->update(['end_time'=>$vip]);//更新卡密信息
			if(!$res)out(201,'充值失败',$app_res);
			Db::table('kami')->where('id',$res_kami['id'])->update(['use_time'=>time(),'user'=>$mainkm]);//更新卡密信息
			out(200,'充值成功',$app_res);
		}elseif($res_kami['type'] == 'fen'){
			$fen = $res_mainkm['amount'] + $res_kami['amount'];
			$res = Db::table('kami')->where('id',$res_mainkm['id'])->update(['amount'=>$fen]);//更新卡密信息
			if(!$res)out(201,'充值失败',$app_res);
			Db::table('kami')->where('id',$res_kami['id'])->update(['use_time'=>time(),'user'=>$mainkm]);//更新卡密信息
			out(200,'充值成功',$app_res);
		}
	}else{
		out(201,'未知充值对象',$app_res);
	}
?>