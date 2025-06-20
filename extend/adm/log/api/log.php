<?php
/*
Name:用户日志API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
	
	if($act == 'del'){//删除日志
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		if($id){
			$ids = '';
			foreach ($id as $value) {
				$ids .= intval($value).",";
			}
			$ids = rtrim($ids, ",");
			$res = Db::table('log')->where('id','in','('.$ids.')')->del();//false
			//die($res);
			if($res){
				json(200,'删除成功');
			}json(201,'删除失败');
		}else{
			json(201,'没有需要删除的数据');
		}
	}
?>