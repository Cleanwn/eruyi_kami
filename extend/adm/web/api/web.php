<?php
/*
Name:系统配置API
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
	if(!isset($islogin))header("Location: /");//非法访问
	
	if($act == 'set'){
		$app_debug = isset($_POST['app_debug']) ? intval($_POST['app_debug']) : 0;
		$default_return_type = isset($_POST['default_return_type']) ? intval($_POST['default_return_type']) : 0;
		$user_token_time = isset($_POST['user_token_time']) ? intval($_POST['user_token_time']) : 0;
		$data_page_enums = isset($_POST['data_page_enums']) ? intval($_POST['data_page_enums']) : 0;
		$default_timezone = isset($_POST['default_timezone']) ? purge($_POST['default_timezone']) : '';
		$index_template = isset($_POST['index_template']) ? purge($_POST['index_template']) : '';
		$api_extend_mulu = isset($_POST['api_extend_mulu']) ? purge($_POST['api_extend_mulu']) : '';
		$adm_extend_mulu = isset($_POST['adm_extend_mulu']) ? purge($_POST['adm_extend_mulu']) : '';
		$user_pic_mulu = isset($_POST['user_pic_mulu']) ? purge($_POST['user_pic_mulu']) : '';
		$log_del = isset($_POST['log_del']) ? intval($_POST['log_del']) : 0;
		$log_key = isset($_POST['log_key']) ? purge($_POST['log_key']) : '';
		
		if($user_token_time == '')json(201,'用户在线状态有效期有误');
		if($default_timezone == '')json(201,'系统时区有误');
		if($api_extend_mulu == '')json(201,'接口扩展目录有误');
		if($adm_extend_mulu == '')json(201,'后台扩展目录有误');
		if($user_pic_mulu == '')json(201,'用户头像目录有误');
		if($log_key == '')json(201,'日志key不可为空');
		
		$userdata = file_get_contents('../include/config.php');
		$userdata = preg_replace("/\'APP_DEBUG',(\d+)/", "'APP_DEBUG',{$app_debug}", $userdata);
		$userdata = preg_replace("/\'DEFAULT_RETURN_TYPE',(\d+)/", "'DEFAULT_RETURN_TYPE',{$default_return_type}", $userdata);
		$userdata = preg_replace("/\'USER_TOKEN_TIME',(\d+)/", "'USER_TOKEN_TIME',{$user_token_time}", $userdata);
		$userdata = preg_replace("/\'DATA_PAGE_ENUMS',(\d+)/", "'DATA_PAGE_ENUMS',{$data_page_enums}", $userdata);
		$userdata = preg_replace("/\'DEFAULT_TIMEZONE','(.*?)'/", "'DEFAULT_TIMEZONE','{$default_timezone}'", $userdata);
		$userdata = preg_replace("/\'INDEX_TEMPLATE','(.*?)'/", "'INDEX_TEMPLATE','{$index_template}'", $userdata);
		$userdata = preg_replace("/\'API_EXTEND_MULU','(.*?)'/", "'API_EXTEND_MULU','{$api_extend_mulu}'", $userdata);
		$userdata = preg_replace("/\'ADM_EXTEND_MULU','(.*?)'/", "'ADM_EXTEND_MULU','{$adm_extend_mulu}'", $userdata);
		$userdata = preg_replace("/\'USER_PIC_MULU','(.*?)'/", "'USER_PIC_MULU','{$user_pic_mulu}'", $userdata);
		$userdata = preg_replace("/\'LOG_DEL',(\d+)/", "'LOG_DEL',{$log_del}", $userdata);
		$userdata = preg_replace("/\'LOG_KEY','(.*?)'/", "'LOG_KEY','{$log_key}'", $userdata);
		$adm_res = file_put_contents('../include/config.php', $userdata);
		if($adm_res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'web_set','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'修改成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'web_set','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'修改失败');
		}
	}
	
	if($act == 'pswd'){
		$user = isset($_POST['user']) ? purge($_POST['user']) : '';
		$pwd = isset($_POST['pwd']) ? purge($_POST['pwd']) : '';
		$okpwd = isset($_POST['okpwd']) ? purge($_POST['okpwd']) : '';
		if($user == '')json(201,'账号不能为空');
		if($pwd == '')json(201,'密码不能为空');
		if($okpwd == '')json(201,'请确认密码');
		if($okpwd != $pwd)json(201,'确认密码有误');
		$userdata = file_get_contents('userdata.php');
		//json(201,$userdata);
		$userdata = preg_replace('/\$user = \'.*?\'/', '$user = \'' . $user . '\'', $userdata);
		$userdata = preg_replace('/\$pass = \'.*?\'/', '$pass = \'' . $pwd . '\'', $userdata);
		$userdata = preg_replace('/\$cookie = \'.*?\'/', '$cookie = \'' . md5($user.$pwd.time()) . '\'', $userdata);
		$adm_res = file_put_contents('userdata.php', $userdata);
		if($adm_res){
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'web_pswd','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(200,'修改成功');
		}else{
			if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'web_pswd','status'=>201,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
			json(201,'修改失败');
		}
	}
	
	if($act == 'downfile'){
		$fileurl = isset($_POST['fileurl']) ? purge($_POST['fileurl']) : '';
		$home = isset($_POST['home']) ? purge($_POST['home']) : '';
		$mulu = isset($_POST['mulu']) ? purge($_POST['mulu']) : '';
		$filename = isset($_POST['filename']) ? purge($_POST['filename']) : '';
		
		if($fileurl == '')json(201,'文件下载地址为空');
		if($home == '')json(201,'扩展类型为空');
		if($filename == '')json(201,'文件名称为空');
		
		if($home == 'adm'){
			$save_dir = FCPATH.ADM_EXTEND_MULU.$mulu;
		}else{
			$save_dir = FCPATH.API_EXTEND_MULU.$mulu;
		}
		$res = getFile($fileurl,$save_dir,$filename,1);
		if(is_array($res) && !empty($res)){
			json(200,'更新成功');
		}json(201,'更新失败');
	}
	
	function getFile($url, $save_dir = '', $filename = '', $type = 0) {  
		if (trim($url) == '') {  
			return false;  
		}  
		//创建保存目录  
		if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {  
			return false;  
		}  
		//获取远程文件所采用的方法  
		if ($type) {  
			$ch = curl_init();  
			$timeout = 5;  
			curl_setopt($ch, CURLOPT_URL, $url);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
			$content = curl_exec($ch);  
			curl_close($ch);  
		} else {  
			ob_start();  
			readfile($url);  
			$content = ob_get_contents();  
			ob_end_clean();  
		}  
		//echo $content;  
		$size = strlen($content);  
		//文件大小  
		$fp2 = @fopen($save_dir . $filename, 'w');  
		fwrite($fp2, $content);  
		fclose($fp2);  
		unset($content, $url);  
		if($size == 0){
			return false; 
		}
		return array(  
			'file_name' => $filename,  
			'save_path' => $save_dir . $filename,  
			'file_size' => $size  
		);  
	}
?>