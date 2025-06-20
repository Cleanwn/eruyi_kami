<?php
/*
Name:卡密API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
		
	if($act == 'add'){
		$appid = isset($_POST['appid']) ? intval($_POST['appid']) : 0;
		$note = isset($_POST['note']) ? purge($_POST['note']) : '';
		$type = isset($_POST['type']) ? purge($_POST['type']) : 'vip';
		$amount = isset($_POST['amount']) ? intval($_POST['amount']) : 1;
		$num = isset($_POST['num']) ? intval($_POST['num']) : 1;
		$out = isset($_POST['out']) ? intval($_POST['out']) : 0;
		$k_length = isset($_POST['k_length']) ? intval($_POST['k_length']) : 10;
		
		if($amount <= 0)json(201,'请设置卡密获得数');
		if($appid == 0)json(201,'绑定应用为空');
		$app_res = Db::table('app')->where('id',$appid)->find();
		if(!$app_res)json(201,'应用不存在');
		
		$str = '';
		for($i=1;$i<=$num;$i++){
			$key=getcode($k_length);
			if($out == 1){
				$add_res = Db::table('kami')->add(['kami'=>$key,'type'=>$type,'amount'=>$amount,'note'=>$note,'appid'=>$appid,'new'=>'y']);
			}else{
				$add_res = Db::table('kami')->add(['kami'=>$key,'type'=>$type,'amount'=>$amount,'note'=>$note,'appid'=>$appid]);
			}
			
			if(!$add_res){
				$key=getcode($k_length);
				$add_res = Db::table('kami')->add(['kami'=>$key,'type'=>$type,'amount'=>$amount,'note'=>$note,'appid'=>$appid]);
			}
			$str .= $key . "\r\n";
		}
		if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'kami_add','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
		if($out == 1){
			$str = "==============卡密开始================\r\n\r\n".$str."\r\n==============卡密结束================";
			json(202,$str);
		}else{
			json(200,'添加成功');
		}
	}

	if($act == 'note'){
		$id = isset($_POST['kid']) ? intval($_POST['kid']) : 0;
		$note = isset($_POST['note']) ? purge($_POST['note']) : '';
		if($id <= 0)json(201,'需要修改的卡密有误');
		$k_res = Db::table('kami')->where('id',$id)->find();
		if(!$k_res)json(201,'卡密不存在');
		
		$res = Db::table('kami')->where('id',$id)->update(['note'=>$note]);
		//die($res); 
		if($res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'kami_note','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'编辑成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'kami_note','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'编辑失败');
		}
	}

	if($act == 'state'){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$state = isset($_POST['state']) ? purge($_POST['state']) : 'y';
		if($id <= 0)json(201,'需要修改的卡密有误');
		$k_res = Db::table('kami')->where('id',$id)->find();
		if(!$k_res)json(201,'卡密不存在');
		
		$res = Db::table('kami')->where('id',$id)->update(['state'=>$state]);//,false
		//die($res); 
		if($res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'kami_state','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'编辑成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'kami_state','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'编辑失败');
		}
	}
	
	if($act == 'del'){//删除卡密
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		if($id){
			$ids = '';
			foreach ($id as $value) {
				$ids .= intval($value).",";
			}
			$ids = rtrim($ids, ",");
			$res = Db::table('kami')->where('id','in','('.$ids.')')->del();//false
			//die($res);
			if($res){
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'kami_del','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(200,'删除成功');
			}else{
				if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'kami_del','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
				json(201,'删除失败');
			}
		}else{
			json(201,'没有需要删除的数据');
		}
	}
?>