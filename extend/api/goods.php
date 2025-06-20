<?php
/*
Name:获取商品
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($app_res) or !is_array($app_res))out(100);//如果需要调用应用配置请先判断是否加载app配置
	if($app_res['logon_way'] != 0)out(164,$app_res);//不是账号登录方式不允许使用当前操作
	
	$ret = [];
	$goods_res = Db::table('goods')->where(['appid'=>$appid,'state'=>'y'])->select();//获取商品列表
	if(is_array($goods_res)){
		foreach ($goods_res as $k => $v){$rows = $goods_res[$k];
			$ret[] = [
				'gid' => $rows['id'],
				'gname' => $rows['name'],
				'gmoney' => $rows['money'],
				'gtype' => $rows['type'],
				'obtain' => $rows['amount'],
				'cv' => $rows['jie']
			];
		}
		out(200,$ret,$app_res);
	}out(201,'商品读取失败',$app_res);
	
?>