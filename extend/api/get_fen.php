<?php
/*
Name:积分验证
Version:1.1
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/	
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录方式不允许使用当前操作
	
	$token = isset($data_arr['token']) && !empty($data_arr['token']) ? purge($data_arr['token']) : out(125,$app_res);//请输TOKEN
	$fid = isset($data_arr['fid']) && !empty($data_arr['fid']) ? intval($data_arr['fid']) : out(143,$app_res);//请输填写积分事件ID
	$mark = isset($data_arr['mark']) ? purge($data_arr['mark']) : '';//积分事件标记
	
	$res_logon = Db::table('user_logon','as logon')->field('U.*')->JOIN('user','as U','logon.uid=U.id')->where('logon.appid',$appid)->where('U.appid',$appid)->where('logon.token',$token)->find();//false
	if(!$res_logon)out(127,$app_res);//TOKEN不存在或已失效
	if($res_logon['ban'] > time() || $res_logon['ban'] == 999999999)out(114,$res_logon['ban_notice'],$app_res);//账号被禁用
	Db::table('user_logon')->where('token',$token)->update(['last_t'=>time()]);//记录活动时间
	
	$res_fen = Db::table('fen')->where('id',$fid)->find();//false
	if(!$res_fen)out(144,$app_res);//积分事件不存在
	if($res_fen['state']=='n')out(145,$app_res);//积分事件已关闭
	
	if($app_res['mode'] == 'y'){//判断当前收费模式
		if($res_fen['vip_num'] > 0){//兑换会员
			if($res_logon['vip'] == '999999999')out(199,$app_res);//已经是永久会员了
			$surplus = $res_logon['fen'] + $res_fen['fen_num'];
			if($surplus >= 0){//积分正常
				if($res_logon['vip'] > time()){
					$vip = $res_logon['vip'] + 3600 * $res_fen['vip_num'];
				}else{
					$vip = time() + 3600 * $res_fen['vip_num'];
				}
				$res = Db::table('user')->where('id',$res_logon['id'])->update(['fen'=>$surplus,'vip'=>$vip]);//更新用户资料
				if(!$res)out(201,'扣除积分失败',$app_res);
				Db::table('fen_order')->add(['fid'=>$fid,'uid'=>$res_logon['id'],'time'=>time()]);//添加积分订单
				if(defined('USER_LOG') && USER_LOG == 1){
					Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'fen'=>$res_fen['fen_num'],'vip'=>$res_logon['vip'],'status'=>200,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);//记录日志
				}
				out(200,'验证成功',$app_res);
			}else{
				out(201,'积分不足',$app_res);
			}
		}else{
			if(empty($mark)){//为空每次扣分
				$surplus = $res_logon['fen'] + $res_fen['fen_num'];
				if($surplus >= 0){//积分正常
					$res = Db::table('user')->where('id',$res_logon['id'])->update(['fen'=>$surplus]);//更新用户资料
					if(!$res)out(201,'扣除积分失败',$app_res);
					Db::table('fen_order')->add(['fid'=>$fid,'uid'=>$res_logon['id'],'time'=>time()]);//添加积分订单
					if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'fen'=>$res_fen['fen_num'],'status'=>200,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
					out(200,'验证成功',$app_res);
				}else{
					out(201,'积分不足',$app_res);
				}
			}else{
				$res = Db::table('fen_order')->where(['fid'=>$fid,'uid'=>$res_logon['id'],'mark'=>$mark])->find();//false
				if($res)out(200,'验证成功1',$app_res);
				$surplus = $res_logon['fen'] + $res_fen['fen_num'];
				if($surplus >= 0){//积分正常
					$res = Db::table('user')->where('id',$res_logon['id'])->update(['fen'=>$surplus]);//更新用户资料
					if(!$res)out(201,'扣除积分失败',$app_res);
					$res = Db::table('fen_order')->add(['fid'=>$fid,'uid'=>$res_logon['id'],'mark'=>$mark,'time'=>time()]);//添加积分订单
					if(!$res)out(201,'验证失败',$app_res);
					if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_logon['id'],'type'=>$act,'fen'=>$res_fen['fen_num'],'status'=>200,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
					out(200,'验证成功',$app_res);
				}else{
					out(201,'积分不足',$app_res);
				}
			}
		}
	}else{
		if($res_fen['vip_num'] > 0){
			out(201,'免费模式无需兑换VIP',$app_res);
		}else{
			out(200,'验证成功',$app_res);
		}
	}
?>