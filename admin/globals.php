<?php
/*
* File：后台全局加载项
* Author：易如意
* QQ：51154393
* Url：www.eruyi.cn
*/

require_once '../include/global.php';
require_once 'userdata.php';
substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);

$action = isset($_GET['action']) ? purge($_GET['action']) : '';

//登录验证
if ($action == 'login') {
	$username = isset($_POST['user']) ? purge($_POST['user']) : '';
	$password = isset($_POST['pwd']) ? purge($_POST['pwd']) : '';
	
    if($username == '' || $password == ''){
		header('Location:./login.php?err=1');
		exit;
	}
	
	if($username == $user && $password == $pass){
		if(defined('ADM_LOG') && ADM_LOG == 1){Db::table('log')->add(['group'=>'adm','type'=>'logon','status'=>200,'time'=>time(),'ip'=>getip(),'data'=>json_encode($_POST)]);}//记录日志
		setcookie('ADMIN_COOKIE', $cookie, time() + 36000, '/');
		header('Location:./');
		exit;
	}else{
		header('Location:./login.php?err=2');
		exit;
	}
}
//退出
if ($action == 'logout') {
	setcookie('ADMIN_COOKIE', ' ', time() - 36000, '/');
	header('Location:./login.php');
	exit;
}

$ADMIN_COOKIE = isset($_COOKIE['ADMIN_COOKIE']) ? purge($_COOKIE['ADMIN_COOKIE']) : '';
if($ADMIN_COOKIE == $cookie){
	$islogin = true;
}else{
	$islogin = false;
}

if (!$islogin) {
	header('Location:./login.php?err=3');
	exit;
}

/*导航配置*/
$menu_arr = myScanDir(FCPATH.ADM_EXTEND_MULU,1); 
$titlename = [];
foreach($menu_arr as $value){
	$nav_arr = getPluginData($value);
	foreach($nav_arr as $val){
		$titlename = array_merge($titlename,[$val['file']=>$val['name']]);
	}
	if($value == 'web'){//continue;
		$web = $nav_arr;
	}else{
		if(!file_exists(FCPATH.ADM_EXTEND_MULU.$value."/menu.php")){//continue;
			foreach($nav_arr as $value){
				$menu[] = $value;
			}
		}else{
			$nav = include FCPATH.ADM_EXTEND_MULU.$value."/menu.php";
			$menu[] = array_merge($nav,['side-nav-second-level'=>$nav_arr]);
		}
	}
}
$sortKey =  array_column($menu,'sort');
array_multisort($sortKey,SORT_ASC,$menu);

$Filename = strpos($_SERVER["QUERY_STRING"],'&') ? txt_zuo($_SERVER["QUERY_STRING"],"&") : $_SERVER["QUERY_STRING"];
$title = !empty($titlename[$Filename])?$titlename[$Filename]:'首页';

function getPluginData($FilePath) {
	$file_arr = myScanDir(FCPATH.ADM_EXTEND_MULU.$FilePath.'/view',2);
	$nav_arr = [];
	foreach($file_arr as $val){
		$Data = implode('', file(FCPATH.ADM_EXTEND_MULU.$FilePath.'/view/'.$val));
		preg_match("/Sort:(.*)/i", $Data, $sort);
		preg_match("/Hidden:(.*)/i", $Data, $hidden);
		preg_match("/icons:(.*)/i", $Data, $icons);
		preg_match("/Name:(.*)/i", $Data, $name);
		preg_match("/Url:(.*)/i", $Data, $url);
		preg_match("/Right:(.*)/i", $Data, $right);
		$sort = isset($sort[1]) ? strip_tags(trim($sort[1])) : '';
		$hidden = isset($hidden[1]) ? strip_tags(trim($hidden[1])) : '';
		$icons = isset($icons[1]) ? strip_tags(trim($icons[1])) : '';
		$name = isset($name[1]) ? strip_tags(trim($name[1])) : '';
		$url = isset($url[1]) ? strip_tags(trim($url[1])) : '';
		$right = isset($right[1]) ? strip_tags(trim($right[1])) : '';
		//if($hidden == 'true')continue;
		$nav_arr[] = ['name' => $name,'file' => $url,'icons'=>$icons,'right' => $right,'sort' => $sort,'hidden' => $hidden];
	}
	$sortKey =  array_column($nav_arr,'sort');
	array_multisort($sortKey,SORT_ASC,$nav_arr);
	return $nav_arr;
}