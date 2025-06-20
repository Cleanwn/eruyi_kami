<?php
/*
Name:用户操作API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
	
	if($act == 'add'){
		$user = isset($_POST['user']) ? purge($_POST['user']) : '';
		$pwd = isset($_POST['pwd']) ? purge($_POST['pwd']) : '';
		$appid = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		$reg_time = time();
		if($user == '')json(201,'账号不能为空');
		if($pwd == '')json(201,'密码不能为空');
		if($appid == 0)json(201,'绑定应用不能为空');
		if(preg_match ("/^[\w]{5,11}$/",$user)==0)json(201,'账号长度5-11位数');
		if(preg_match ("/^[a-zA-Z\d.*_-]{6,18}$/",$pwd)==0)json(201,'密码长度需要满足6-18位数,不支持中文以及.-*_以外特殊字符');
		$app_res = Db::table('app')->where('id',$appid)->find();
		if(!$app_res)json(201,'应用不存在');
		$user_res = Db::table('user')->where(['appid'=>$appid,'user'=>$user])->find();
		if($user_res)json(201,'账号已存在');
		$add_res = Db::table('user')->add(['user'=>$user,'pwd'=>md5($pwd),'appid'=>$appid,'reg_time'=>$reg_time]);
		//die($res); 
		if($add_res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'user_add','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'添加成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'user_add','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'添加失败');
		}
	}
	
	if($act == 'edit'){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$update['fen'] = isset($_POST['fen']) ? intval($_POST['fen']) : 0;
		$update['vip'] = isset($_POST['vip']) ? intval($_POST['vip']) : 0;
		$update['ban'] = isset($_POST['ban']) ? intval($_POST['ban']) : 0;
		$update['ban_notice'] = isset($_POST['ban_notice']) ? purge($_POST['ban_notice']) : '';
		$update['openid_qq'] = isset($_POST['openid_qq']) ? purge($_POST['openid_qq']) : '';
		$update['openid_wx'] = isset($_POST['openid_wx']) ? purge($_POST['openid_wx']) : '';
		$pwd = isset($_POST['pwd']) ? purge($_POST['pwd']) : '';
		if($pwd != ''){
			$pass = md5($pwd);
			$update['pwd'] = $pass;
		}
		
		$res = Db::table('user')->where('id',$id)->update($update);
		//die($res); 
		if($res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'user_edit','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'编辑成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'user_edit','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'编辑失败');
		}
	}
	
	if($act == 'del'){//删除用户
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		if($id){
			$ids = '';
			foreach ($id as $value) {
				$ids .= intval($value).",";
			}
			$ids = rtrim($ids, ",");
			$res = Db::table('user')->where('id','in','('.$ids.')')->del();//false
			//die($res);
			if($res){
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'user_del','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(200,'删除成功');
			}else{
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'user_del','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(201,'删除失败');
			}
		}else{
			json(201,'没有需要删除的数据');
		}
	}
?>