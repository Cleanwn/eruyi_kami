<?php
/*
Name:应用扩展配置API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
	
	if($act == 'add'){//添加配置
		$name = isset($_POST['name']) ? purge($_POST['name']) : '';
		$data = isset($_POST['data']) ? purge($_POST['data']) : '';
		$appid = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		
		if($name == '')json(201,'变量名称为空');
		if(preg_match ("/^[\w]{1,32}$/",$name)==0)json(201,'变量名称不合格');
		if($data == '')json(201,'扩展配置为空');
		if($appid == 0)json(201,'绑定应用为空');
		
		$app_res = Db::table('app')->where('id',$appid)->find();
		if(!$app_res)json(201,'应用不存在');
		
		$exten_res = Db::table('app_exten')->where(['name'=>$name])->find();
		if($exten_res)json(201,'变量名已存在');
		
		$add_res = Db::table('app_exten')->add(['name'=>$name,'data'=>$data,'appid'=>$appid]);
		//die($add_res); 
		if($add_res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'exten_add','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'添加成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'exten_add','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'添加失败');
		}
	}
	
	if($act == 'edit'){//编辑配置
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$update['name'] = isset($_POST['name']) ? purge($_POST['name']) : '';
		$update['data'] = isset($_POST['data']) ? purge($_POST['data']) : '';
		$update['appid'] = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		
		if($update['name'] == '')json(201,'变量名称为空');
		if(preg_match ("/^[\w]{1,32}$/",$update['name'])==0)json(201,'变量名称不合格');
		if($update['data'] == '')json(201,'扩展配置为空');
		if($update['appid'] == 0)json(201,'绑定应用为空');
		
		$app_res = Db::table('app')->where('id',$update['appid'])->find();
		if(!$app_res)json(201,'应用不存在');
		
		$exten_res = Db::table('app_exten')->where(['name'=>$update['name']])->find();
		if($exten_res){
			if($exten_res['id'] != $id)json(201,'变量名已存在');
		}
		
		$res = Db::table('app_exten')->where('id',$id)->update($update);
		//die($res); 
		if($res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'exten_edit','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'编辑成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'exten_edit','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'编辑失败');
		}
	}
	
	if($act == 'del'){//删除配置
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		if($id){
			$ids = '';
			foreach ($id as $value) {
				$ids .= intval($value).",";
			}
			$ids = rtrim($ids, ",");
			$res = Db::table('app_exten')->where('id','in','('.$ids.')')->del();//false
			//die($res);
			if($res){
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'exten_del','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(200,'删除成功');
			}else{
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'exten_del','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(201,'删除失败');
			}
		}else{
			json(201,'没有需要删除的数据');
		}
	}
?>