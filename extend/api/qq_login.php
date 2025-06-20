<?php
/*
Name:QQ登录接口
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_state']=='n')out(103,$app_res['logon_notice'],$app_res);//判断是否可登录
	if($app_res['logon_way'] != 0)out(163,$app_res);//不是账号登录方式不允许使用当前操作
	
	$openid = isset($data_arr['openid']) && !empty($data_arr['openid']) ? purge($data_arr['openid']) : out(157,$app_res);//请输入openid
	$access_token = isset($data_arr['access_token']) && !empty($data_arr['access_token']) ? purge($data_arr['access_token']) : out(157,$app_res);//请输入access_token
	$qqappid = isset($data_arr['qqappid']) && !empty($data_arr['qqappid']) ? purge($data_arr['qqappid']) : out(161,$app_res);//请输入QQ互联ID
	$reg_in = isset($data_arr['markcode']) ? purge($data_arr['markcode']) : '';//机器码
	$inv = isset($data_arr['inv']) ? intval($data_arr['inv']) : 0;//邀请人
	
	$get_data = ['access_token'=>$access_token,'openid'=>$openid,'oauth_consumer_key'=>$qqappid,'format'=>'json'];
	$qq_data = http_gets('https://graph.qq.com/user/get_user_info',$get_data);
	if(!$qq_data)out(162,$app_res);
	$json_qq = json_decode($qq_data, true);
	if(isset($json_qq['errcode']))out(158,$app_res);//错误的身份信息
	
	$name = $json_qq['nickname'];
	$pic = $json_qq['figureurl_qq'];
	$token = md5($openid.getcode(32).time().$appid);//生成TOKEN
	
	$res_qq = Db::table('user')->where(['openid_qq'=>$openid,'appid'=>$appid])->find();//false
	if($res_qq){
		if($app_res['logon_check_in']=='y' && $reg_in == '')out(112,$app_res);//判断是否验证机器码
		if($res_qq['ban'] > time() || $res_user['ban'] == 999999999)out(114,$res_qq['ban_notice'],$app_res);//账号被禁用
		if($app_res['mode'] == 'y'){$vip = $res_qq['vip'];}else{$vip = '999999999';}//判断当前收费模式
		$user_info = [
			'id'=>$res_qq['id'],
			'pic'=>get_pic($res_qq['pic']),
			'name'=>$res_qq['name'],
			'vip'=>$vip,
			'fen'=>$res_qq['fen']
		];
		$res_user['id'] = $res_qq['id'];
	}else{
		$reg_ipon = $app_res['reg_ipon'];//获取IP重复注册间隔
		if($reg_ipon > 0){
			$ip_time = time()-$reg_ipon*3600;
			$res = Db::table('user')->where('appid'=>$appid,'reg_ip',getIp())->where('reg_time','>',$ip_time)->find();//寻找相同IP
			if($res) out(117,$app_res);//该IP已注册
		}
		
		$reg_inon = $app_res['reg_inon'];//获取机器码重复注册间隔
		if($reg_inon > 0){
			$in_time = time()-$reg_inon*3600;
			$res = Db::table('user')->where('appid'=>$appid,'reg_in',$reg_in)->where('reg_time','>',$in_time)->find();//寻找相同机器码
			if($res) out(117,$app_res);//该机器码已注册
		}
		
		if ($inv > 0){//邀请人事件
			$res = Db::table('user')->where('id',$inv)->where('appid',$appid)->find();//查询邀请者ID
			if(!$res)out(118,$app_res);//邀请人已存在
			$inv_award = $app_res['inv_award'];//奖励类型
			$inv_award_num = $app_res['inv_award_num'];//邀请奖励数
			if($inv_award_num > 0){
				if($inv_award == 'vip' && $res['vip'] != 999999999){//奖励类型是VIP
					if($res['vip'] > time()){//VIP没有过期
						$vip = $res['vip'] + 3600 * $inv_award_num;
					}else{//VIP已过期
						$vip = time() + 3600 * $inv_award_num;
					}
					$inv_res = Db::table('user')->where('id',$inv)->update(['vip'=>$vip]);//更新邀请人VIP数据
					if($inv_res){
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$inv,'type'=>'inv','status'=>200,'time'=>time(),'ip'=>getIp(),'vip'=>$inv_award_num,'appid'=>$appid]);}//记录日志
					}else{
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$inv,'type'=>'inv','status'=>201,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
					}
				}else if($inv_award == 'fen'){
					$fen = $res['fen'] + $inv_award_num;
					$inv_res = Db::table('user')->where('id',$inv)->update(['fen'=>$fen]);//更新邀请人积分数据
					if($inv_res){
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$inv,'type'=>'inv','status'=>200,'time'=>time(),'ip'=>getIp(),'fen'=>$inv_award_num,'appid'=>$appid]);}//记录日志
					}else{
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$inv,'type'=>'inv','status'=>201,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
					}
				}
			}
		}
		
		$reg_award = $app_res['reg_award'];//奖励类型
		$reg_award_num = $app_res['reg_award_num'];//注册奖励
		if($reg_award_num > 0){
			if($reg_award == 'vip'){
				$vips = time() + 60 * $reg_award_num;
				$add_res = Db::table('user')->add(['name'=>$name,'pic'=>$pic,'openid_qq'=>$openid,'vip'=>$vips,'inv'=>$inv,'reg_in'=>$markcode,'reg_ip'=>getIp(),'reg_time'=>time(),'appid'=>$appid]);
			}else{
				$fens = $reg_award_num;
				$add_res = Db::table('user')->add(['name'=>$name,'pic'=>$pic,'openid_qq'=>$openid,'fen'=>$fens,'inv'=>$inv,'reg_in'=>$markcode,'reg_ip'=>getIp(),'reg_time'=>time(),'appid'=>$appid]);
			}
		}else{
			$add_res = Db::table('user')->add(['name'=>$name,'pic'=>$pic,'openid_qq'=>$openid,'inv'=>$inv,'reg_in'=>$markcode,'reg_ip'=>getIp(),'reg_time'=>time(),'appid'=>$appid]);
		}
		if($add_res){
			$res_user['id'] = (int)$add_res;
			if($app_res['mode'] == 'y'){$vip = isset($vips) ? $vips : 0;}else{$vip = '999999999';}//判断当前收费模式
			$fen = isset($fens) ? $fens : 0;
			$user_info = [
				'id'=>$res_user['id'],
				'pic'=>$pic,
				'name'=>$name,
				'vip'=>$vip,
				'fen'=>$fen
			];
		}else{
			out(201,'注册失败',$app_res);	
		}
	}
	
	$res_num = Db::table('user_logon')->where(['uid'=>$res_user['id']])->count();
	if($res_num >= $app_res['logon_num']){//已超过最大登录数
		$res = Db::table('user_logon')->where(['uid'=>$res_user['id'],'log_in'=>$markcode])->find();//寻找相同设备
		if($res){//找到相同设备的登录信息
			$res_update = Db::table('user_logon')->where('id',$res['id'])->update(['token'=>$token,'log_time'=>time(),'log_ip'=>getIp(),'log_in'=>$markcode,'last_t'=>time()]);
			if($res_update){
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
				$data = ['token'=>$token,'info'=>$user_info];
				out(200,$data,$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
				out(201,'登录失败',$app_res);
			}
		}else{//没有找到相同登录信息
			$res_logon = Db::table('user_logon')->where(['uid'=>$res_user['id']])->order('last_t asc')->find();
			if($app_res['logon_check_in']=='y'){//需要验证机器码
				if($app_res['logon_check_t'] <= 0){//不限制换绑次数
					$res_update = Db::table('user_logon')->where('id',$res_logon['id'])->update(['token'=>$token,'log_time'=>time(),'log_ip'=>getIp(),'log_in'=>$markcode,'last_t'=>time()]);
					if($res_update){
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
						$data = ['token'=>$token,'info'=>$user_info];
						out(200,$data,$app_res);
					}else{
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
						out(201,'登录失败',$app_res);
					}
				}else{//限制设备换绑次数
					$end = $res_logon['log_time']+$app_res['logon_check_t']*3600;
					if($end > time())out(201,check_t(time(),$end));//已超换绑间隔
					$res_update = Db::table('user_logon')->where('id',$res_logon['id'])->update(['token'=>$token,'log_time'=>time(),'log_ip'=>getIp(),'log_in'=>$markcode,'last_t'=>time()]);
					if($res_update){
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
						$data = ['token'=>$token,'info'=>$user_info];
						out(200,$data,$app_res);
					}else{
						if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
						out(201,'登录失败',$app_res);
					}
				}
			}else{
				$res_update = Db::table('user_logon')->where('id',$res_logon['id'])->update(['token'=>$token,'log_time'=>time(),'log_ip'=>getIp(),'log_in'=>$markcode,'last_t'=>time()]);
				if($res_update){
					if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
					$data = ['token'=>$token,'info'=>$user_info];
					out(200,$data,$app_res);
				}else{
					if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
					out(201,'登录失败',$app_res);
				}
			}
		}
	}else{//未超贵最大登录数
		$res = Db::table('user_logon')->where(['uid'=>$res_user['id'],'log_in'=>$markcode])->find();
		if($res){//找到相同设备的登录信息
			$res_update = Db::table('user_logon')->where('id',$res['id'])->update(['token'=>$token,'log_time'=>time(),'log_ip'=>getIp(),'log_in'=>$markcode,'last_t'=>time()]);
			if($res_update){
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
				$data = ['token'=>$token,'info'=>$user_info];
				out(200,$data,$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
				out(201,'登录失败',$app_res);
			}
		}else{//没有找到相同登录信息
			$res_add = Db::table('user_logon')->add(['uid'=>$res_user['id'],'token'=>$token,'log_time'=>time(),'log_ip'=>getIp(),'log_in'=>$markcode,'last_t'=>time(),'appid'=>$appid]);
			if($res_add){
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
				$data = ['token'=>$token,'info'=>$user_info];
				out(200,$data,$app_res);
			}else{
				if(defined('USER_LOG') && USER_LOG == 1){Db::table('log')->add(['uid'=>$res_user['id'],'type'=>$act,'status'=>201,'time'=>time(),'ip'=>getIp(),'appid'=>$appid]);}//记录日志
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