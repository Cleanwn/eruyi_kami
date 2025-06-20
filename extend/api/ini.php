<?php
/*
Name:获取配置
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	$app_bb = $app_res['app_bb'];//APP版本
	$app_nshow = $app_res['app_nshow'];//更新内容
	$app_nurl = $app_res['app_nurl'];//更新地址
	
	$ini_data = [//基本配置
		'app_bb'=>$app_bb,
		'app_nshow'=>$app_nshow,
		'app_nurl'=>$app_nurl
	];
	
	
	$app_exten = [];
	$app_exten_res = Db::table('app_exten')->where('appid',$appid)->order('id desc')->select();//获取扩展配置
	foreach ($app_exten_res as $k => $v){$rows = $app_exten_res[$k];
		$app_exten = array_merge($app_exten,[$rows['name']=>$rows['data']]);
	}
	if(count($app_exten) > 0){
		$ini_data = array_merge($ini_data,['exten'=>$app_exten]);
	}
	
	
	if(isset($_GET['pay'])){
		$pay_ini = [
			'state'=>$app_res['pay_state'],
			'url'=>$app_res['pay_url'],
			'appid'=>$app_res['pay_id'],
			'appkey'=>$app_res['pay_key'],
			'ali'=>$app_res['pay_ali_state'],
			'wx'=>$app_res['pay_wx_state'],
			'qq'=>$app_res['pay_qq_state']
		];
	}
	
	if(isset($pay_ini) && is_array($pay_ini)){
		$ini_data = array_merge($ini_data,['pay'=>$pay_ini]);
	}
	out(200,$ini_data);
?>