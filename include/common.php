<?php
$t_url = (($_SERVER['SERVER_PORT']==443) ? 'https':'http').'://'.$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'],(substr($_SERVER['DOCUMENT_ROOT'],-1) == '/') ? '/':'',dirname($_SERVER['SCRIPT_FILENAME']));
if($t_url == ''){
	include("./template/eruyi/error.html");//环境不合适
	return;
}else{
	if(file_exists('./install/upgrade.php')){//需要升级
		header("Location: " .$t_url ."/install/upgrade.php"); 
		return;
	}
	if(file_exists('./install/install.lock')==false){//需要安装
		header("Location: " .$t_url ."/install"); 
		return;
	}
}

require("include/global.php");
$app_res = Db::table('app','as A')->field('A.id,A.name,A.state,A.app_bb,IFNULL(U.us,0) as unum')->JOIN("(SELECT appid,COUNT(*) AS us FROM {$DP}user GROUP BY appid) AS U",'A.id=U.appid')->where('A.state',"y")->select();

?>