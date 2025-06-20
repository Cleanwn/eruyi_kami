<?php
/*
Name:登录接口文件 
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_state']=='n')out(103,$app_res['logon_notice'],$app_res);//判断是否可登录
	if($app_res['logon_way'] != 0)out(163,$app_res);//不允许账号登录方式
	
	$user = isset($data_arr['account']) && !empty($data_arr['account']) ? purge($data_arr['account']) : out(110,$app_res);//请输入账号
	$pwd = isset($data_arr['password']) && !empty($data_arr['password']) ? purge($data_arr['password']) : out(111,$app_res);//请输入密码
	$log_in = isset($data_arr['markcode']) ? purge($data_arr['markcode']) : '';//机器码
	
	if($app_res['logon_check_in']=='y' && $log_in == '')out(112,$app_res);//判断是否验证机器码
	$log_ip = getIp();//登录IP
	$log_time = time();//登录时间
	$token = md5($user.getcode(32).time().$appid);//生成TOKEN
	
	$res_user = Db::table('user')->where(['pwd'=>md5($pwd),'appid'=>$appid],"(",")")->where('(user',$user)->whereOr(['email'=>$user,'phone'=>$user],")")->find();//false
	if(!$res_user)out(113,$app_res);//账号密码不正确
	if($res_user['ban'] > $log_time || $res_user['ban'] == 999999999)out(114,$res_user['ban_notice'],$app_res);//账号被禁用
	if($app_res['mode'] == 'y'){$vip = $res_user['vip'];}else{$vip = '999999999';}//判断当前收费模式
	$user_info = [
		'id'=>$res_user['id'],
		'pic'=>get_pic($res_user['pic']),
		'name'=>$res_user['name'],
		'vip'=>$vip,
		'fen'=>$res_user['fen']
	];
	
	$res_num = Db::table('user_logon')->where(['uid'=>$res_user['id']])->count();
	if($res_num >= $app_res['logon_num']){//已超过最大登录数
		$res = Db::table('user_logon')->where(['uid'=>$res_user['id'],'log_in'=>$log_in])->find();//寻找相同设备
		if($res){//找到相同设备的登录信息
			$res_update = Db::table('user_logon')->where('id',$res['id'])->update(['token'=>$token,'log_time'=>$log_time,'log_ip'=>$log_ip,'log_in'=>$log_in,'last_t'=>$log_time]);
			if($res_update){
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
				$data = ['token'=>$token,'info'=>$user_info];
				out(200,$data,$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
				out(201,'登录失败',$app_res);
			}
		}else{//没有找到相同登录信息
			$res_logon = Db::table('user_logon')->where(['uid'=>$res_user['id']])->order('last_t asc')->find();
			if($app_res['logon_check_in']=='y'){//需要验证机器码
				if($app_res['logon_check_t'] <= 0){//不限制换绑次数
					$res_update = Db::table('user_logon')->where('id',$res_logon['id'])->update(['token'=>$token,'log_time'=>$log_time,'log_ip'=>$log_ip,'log_in'=>$log_in,'last_t'=>$log_time]);
					if($res_update){
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
						$data = ['token'=>$token,'info'=>$user_info];
						out(200,$data,$app_res);
					}else{
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
						out(201,'登录失败',$app_res);
					}
				}else{//限制设备换绑次数
					$end = $res_logon['log_time']+$app_res['logon_check_t']*3600;
					if($end > $log_time)out(201,check_t($log_time,$end),$app_res);//已超换绑间隔
					$res_update = Db::table('user_logon')->where('id',$res_logon['id'])->update(['token'=>$token,'log_time'=>$log_time,'log_ip'=>$log_ip,'log_in'=>$log_in,'last_t'=>$log_time]);
					if($res_update){
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
						$data = ['token'=>$token,'info'=>$user_info];
						out(200,$data,$app_res);
					}else{
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
						out(201,'登录失败',$app_res);
					}
				}
			}else{
				$res_update = Db::table('user_logon')->where('id',$res_logon['id'])->update(['token'=>$token,'log_time'=>$log_time,'log_ip'=>$log_ip,'log_in'=>$log_in,'last_t'=>$log_time]);
				if($res_update){
					if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
					$data = ['token'=>$token,'info'=>$user_info];
					out(200,$data,$app_res);
				}else{
					if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
					out(201,'登录失败',$app_res);
				}
			}
		}
	}else{//未超贵最大登录数
		$res = Db::table('user_logon')->where(['uid'=>$res_user['id'],'log_in'=>$log_in])->find();
		if($res){//找到相同设备的登录信息
			$res_update = Db::table('user_logon')->where('id',$res['id'])->update(['token'=>$token,'log_time'=>$log_time,'log_ip'=>$log_ip,'log_in'=>$log_in,'last_t'=>$log_time]);
			if($res_update){
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
				$data = ['token'=>$token,'info'=>$user_info];
				out(200,$data,$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
				out(201,'登录失败',$app_res);
			}
		}else{//没有找到相同登录信息
			$res_add = Db::table('user_logon')->add(['uid'=>$res_user['id'],'token'=>$token,'log_time'=>$log_time,'log_ip'=>$log_ip,'log_in'=>$log_in,'last_t'=>$log_time,'appid'=>$appid]);
			if($res_add){
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
				$data = ['token'=>$token,'info'=>$user_info];
				out(200,$data,$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>$log_time,'ip'=>$log_ip,'appid'=>$appid]);}//记录日志
				out(201,'登录失败',$app_res);
			}
		}
		
	}
	
	function check_t($start,$end){
		$second = $end-$start; //结束时间戳减去当前时间戳
		// echo $second;
		$day = floor($second/3600/24);    //倒计时还有多少天
		if($day>0){
			return '当前账号已绑定其他设备，'.$day.'天后可在该设备登录';
		}
		$hr = floor($second/3600%24);     //倒计时还有多少小时（%取余数）
		if($hr>0){
			return '当前账号已绑定其他设备，'.$hr.'小时后可在该设备登录';
		}
		$min = floor($second/60%60);      //倒计时还有多少分钟
		if($min>0){
			return '当前账号已绑定其他设备，'.$min.'分钟后可在该设备登录';
		}
		$sec = floor($second%60);         //倒计时还有多少秒   
		if($sec>0){
			return '当前账号已绑定其他设备，'.$sec.'秒后可在该设备登录';
		}
		$str = $day."天".$hr."小时".$min."分钟".$sec."秒";  //组合成字符串
		return $str;
	}
?>