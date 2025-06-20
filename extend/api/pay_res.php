<?php
/*
Name:支付结果
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录方式不允许使用当前操作
	
	$oid = isset($data_arr['oid']) && !empty($data_arr['oid']) ? purge($data_arr['oid']) : out(130);//订单信息，可以是订单号也可以是用户账号
	
	$order_res = Db::table('goods_order','as O')->field('O.state')->where('O.order',$oid)->find();
	if(!$order_res)out(153);
	if($order_res['state'] == 0){
		out(154);//等待支付
	}else if($order_res['state'] == 2){
		out(200);//充值成功
	}else if($order_res['state'] == 1){
		out(201);//充值失败
	}else{
		out(155);//未知订单状态
	}
	
?>