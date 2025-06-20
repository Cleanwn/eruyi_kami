<?php
/*
Name:商品订单API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
	
	if($act == 'del'){//删除订单
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		if($id){
			$ids = '';
			foreach ($id as $value) {
				$ids .= intval($value).",";
			}
			$ids = rtrim($ids, ",");
			$res = Db::table('goods_order')->where('id','in','('.$ids.')')->del();//false
			//die($res);
			if($res){
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'goods_o_del','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(200,'删除成功');
			}else{
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'goods_o_del','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(201,'删除失败');
			}
		}else{
			json(201,'没有需要删除的数据');
		}
	}
?>