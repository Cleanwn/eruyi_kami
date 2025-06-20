<?php
/*
Name:积分API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
	
	if($act == 'add'){//添加积分事件
		$add['name'] = isset($_POST['name']) ? purge($_POST['name']) : '';
		$add['fen_num'] = isset($_POST['fen_num']) ? intval($_POST['fen_num']) : 0;
		$add['vip_num'] = isset($_POST['vip_num']) ? intval($_POST['vip_num']) : 0;
		$add['appid'] = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		
		if($add['name'] == '')json(201,'积分事件名称为空');
		if($add['fen_num'] == 0)json(201,'请正确填写消耗积分数');
		if($add['vip_num'] < 0)json(201,'请正确填写兑换会员数');
		if($add['appid'] == 0)json(201,'绑定应用为空');
		
		$app_res = Db::table('app')->where('id',$add['appid'])->find();
		if(!$app_res)json(201,'应用不存在');
		
		$fen_res = Db::table('fen')->where(['name'=>$add['name'],'appid'=>$add['appid']])->find();
		if($fen_res)json(201,'积分事件名称已存在');
		
		$add_res = Db::table('fen')->add($add);
		//die($add_res); 
		if($add_res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'fen_add','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'添加成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'fen_add','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'添加失败');
		}
	}
	
	if($act == 'edit'){//编辑积分事件
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$update['name'] = isset($_POST['name']) ? purge($_POST['name']) : '';
		$update['fen_num'] = isset($_POST['fen_num']) ? intval($_POST['fen_num']) : 0;
		$update['vip_num'] = isset($_POST['vip_num']) ? intval($_POST['vip_num']) : 0;
		$update['appid'] = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		
		if($update['name'] == '')json(201,'积分事件名称为空');
		if($update['fen_num'] == 0)json(201,'请正确填写消耗积分数');
		if($update['vip_num'] < 0)json(201,'请正确填写兑换会员数');
		if($update['appid'] == 0)json(201,'绑定应用为空');
		
		$app_res = Db::table('app')->where('id',$update['appid'])->find();
		if(!$app_res)json(201,'应用不存在');
		
		$fen_res = Db::table('fen')->where(['name'=>$update['name'],'appid'=>$update['appid']])->find();
		if($fen_res){
			if($fen_res['id'] != $id)json(201,'积分事件名称已存在');
		}
		
		$res = Db::table('fen')->where('id',$id)->update($update);
		//die($res); 
		if($res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'fen_edit','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'编辑成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'fen_edit','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'编辑失败');
		}
	}
	
	if($act == 'del'){//删除积分事件
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		if($id){
			$ids = '';
			foreach ($id as $value) {
				$ids .= intval($value).",";
			}
			$ids = rtrim($ids, ",");
			$res = Db::table('fen')->where('id','in','('.$ids.')')->del();//false
			//die($res);
			if($res){
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'fen_del','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(200,'删除成功');
			}else{
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'fen_del','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(201,'删除失败');
			}
		}else{
			json(201,'没有需要删除的数据');
		}
	}
?>