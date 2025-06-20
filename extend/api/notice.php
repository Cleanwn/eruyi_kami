<?php
/*
Name:获取通知
Version:1.1
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	$pg = isset($data_arr['pg']) ? intval($data_arr['pg']) : 1;
	$bnums = ($pg-1)*$ENUMS;
	
	$ret = [];
	$notice_res = Db::table('app_notice')->where('appid',$appid)->order('id desc')->limit($bnums,$ENUMS)->select();//获取通知列表
	if(is_array($notice_res)){
		foreach ($notice_res as $k => $v){$rows = $notice_res[$k];
			$ret[] = [
				'content' => $rows['content'],
				'date' => date("Y-m-d H:i:s",$rows['time']),
				'name' => $rows['adm']
			];
		}
		out(200,$ret,$app_res);
	}out(201,'通知列表加载失败',$app_res);
	
?>