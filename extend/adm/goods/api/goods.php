<?php
/*
Name:商品API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
	
	if($act == 'add'){
		$name = isset($_POST['name']) ? purge($_POST['name']) : '';
		$type = isset($_POST['type']) ? purge($_POST['type']) : 'vip';
		$amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
		$money = isset($_POST['money']) ? purge($_POST['money']) : '1.00';
		$jie = isset($_POST['jie']) ? purge($_POST['jie']) : '';
		$appid = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		
		if($name == '')json(201,'请设置商品名称');
		if($amount <= 0)json(201,'请设置购买数量');
		if($appid == 0)json(201,'绑定应用为空');
		$app_res = Db::table('app')->where('id',$appid)->find();
		if(!$app_res)json(201,'应用不存在');
		$goods_res = Db::table('goods')->where(['appid'=>$appid,'name'=>$name])->find();
		if($goods_res)json(201,'商品已存在');
		$add_res = Db::table('goods')->add(['name'=>$name,'type'=>$type,'amount'=>$amount,'money'=>$money,'jie'=>$jie,'appid'=>$appid]);
		//die($res); 
		if($add_res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'goods_add','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'添加成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'goods_add','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'添加失败');
		}
	}
	
	if($act == 'edit'){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$update['name'] = isset($_POST['name']) ? purge($_POST['name']) : '';
		$update['type'] = isset($_POST['type']) ? purge($_POST['type']) : 'vip';
		$update['amount'] = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
		$update['money'] = isset($_POST['money']) ? purge($_POST['money']) : '1.00';
		$update['jie'] = isset($_POST['jie']) ? purge($_POST['jie']) : '';
		$update['appid'] = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		$update['state'] = isset($_POST['state']) ? purge($_POST['state']) : 'y';
		
		if($update['name'] == '')json(201,'请设置商品名称');
		if($update['amount'] <= 0)json(201,'请设置购买数量');
		if($update['appid'] == 0)json(201,'绑定应用为空');
		
		$app_res = Db::table('app')->where('id',$update['appid'])->find();
		if(!$app_res)json(201,'应用不存在');
		
		$goods_res = Db::table('goods')->where(['appid'=>$update['appid'],'name'=>$update['name']])->find();
		if($goods_res){
			if($goods_res['id'] != $id)json(201,'商品已存在');
		}
		
		$res = Db::table('goods')->where('id',$id)->update($update);
		//die($res); 
		if($res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'goods_edit','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'编辑成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'goods_edit','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'编辑失败');
		}
	}
	
	if($act == 'del'){//删除商品
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		if($id){
			$ids = '';
			foreach ($id as $value) {
				$ids .= intval($value).",";
			}
			$ids = rtrim($ids, ",");
			$res = Db::table('goods')->where('id','in','('.$ids.')')->del();//false
			//die($res);
			if($res){
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'goods_del','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(200,'删除成功');
			}else{
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'goods_del','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(201,'删除失败');
			}
		}else{
			json(201,'没有需要删除的数据');
		}
	}
?>