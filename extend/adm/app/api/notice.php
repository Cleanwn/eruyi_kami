<?php
/*
Name:通知API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
	
	if($act == 'add'){//添加通知
		$adm = isset($_POST['adm']) && !empty($_POST['adm']) ? purge($_POST['adm']) : '管理员';
		$content = isset($_POST['content']) ? purge($_POST['content']) : '';
		$appid = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		
		if($content == '')json(201,'通知内容为空');
		if($appid == 0)json(201,'绑定应用为空');
		
		$app_res = Db::table('app')->where('id',$appid)->find();
		if(!$app_res)json(201,'应用不存在');
		
		$add_res = Db::table('app_notice')->add(['adm'=>$adm,'content'=>$content,'appid'=>$appid,'time'=>time()]);
		//die($add_res); 
		if($add_res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'notice_add','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'添加成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'notice_add','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'添加失败');
		}
	}
	
	if($act == 'del'){//删除通知
		$id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : json(201,'请选择需要删除的数据');
		$res = Db::table('app_notice')->where('id',$id)->del();//false
		if($res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'notice_del','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'删除成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'notice_del','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'删除失败');
		}
	}
?>