<?php
/*
Name:APP应用
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
$app_res = Db::table('app')->where('id',$appid)->find();
if(!$app_res)out(101);//应用不存在
if($app_res['state']=='n')out(102,$app_res['notice'],$app_res);//应用关闭
$bmd = ['ini','pay'];//白名单接口

if($app_res['mi_state'] == 'y' && !in_array($act,$bmd)){//数据已加密
	if($app_res['mi_type'] == 0){//明文模式
		$data_arr = $_REQUEST;//将post或GET数据移交给data_arr
		if($app_res['mi_sign'] == 'y'){//数据签名
			if($sign == '')out(104,$app_res);//签名为空
			$s = Arr_sign($data_arr,$app_res['appkey']);//生成签名
			if($s != strtolower($sign))out(106,$app_res);//签名有误
		}
		
	}else if($app_res['mi_type'] == 1){//RC4加密
		if($data=='')out(107,$app_res);//数据为空
		$rc4_data = mi_rc4($data,$app_res['mi_rc4_key'],1);//RC4解密
		//out(106,$rc4_data);//签名有误
		$data_arr = txt_Arr($rc4_data);//将rc4解密后的数据转为数组移交给data_arr
		if($app_res['mi_sign'] == 'y'){//数据签名
			if($sign == '')out(104,$app_res);//签名为空
			$s = Arr_sign($data_arr,$app_res['appkey']);//生成签名
			if($s != strtolower($sign))out(106,$app_res);//签名有误
		}
		
	}else if($app_res['mi_type'] == 2){//RSA加密
		if($data=='')out(107,$app_res);//数据为空
		$rsa_data = RSA_SMI($data,$app_res['mi_rsa_private_key'],1);//RSA私钥解密
		$data_arr = txt_Arr($rsa_data);//将rsa解密后的数据转为数组移交给data_arr
		if($app_res['mi_sign'] == 'y'){//数据签名
			if($sign == '')out(104,$app_res);//签名为空
			$s = Arr_sign($data_arr,$app_res['appkey']);//生成签名
			if($s != strtolower($sign))out(106,$app_res);//签名有误
		}
	}
	
	if($app_res['mi_time'] > 0){
		if(!isset($data_arr['t']))out(108,$app_res);//没有时间变量
		$sign_t = time() - intval($data_arr['t']);//服务器时间-客户端时间，对比时间差
		if($sign_t > $app_res['mi_time'])out(105,$app_res);//客户端时间小于服务器时间
	}
}else{
	$data_arr = $_REQUEST;//将post或GET数据移交给data_arr
}


?>