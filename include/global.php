<?php
	/*
	* File：Global.php
	* Author：易如意
	* QQ：51154393
	* Url：www.eruyi.cn
	*/	
	header("content-type:text/html; charset=utf-8"); 
	require_once 'config.php';//引入配置信息
	require_once 'db.class.php';//引入数据库类
	require_once 'lang/lang_cp.php';//引入日志配置
	
	if(APP_DEBUG == 0){error_reporting(0);}//关闭错误报告
	date_default_timezone_set(DEFAULT_TIMEZONE);//默认时区
	
	if(defined('DB_PRE')){$DP = DB_PRE;}else{$DP = '';}
	if(defined('DATA_PAGE_ENUMS')){$ENUMS = DATA_PAGE_ENUMS;}else{$ENUMS = 10;}
	if(defined('USER_TOKEN_TIME')){$UTT = time()-USER_TOKEN_TIME;}else{$UTT = time()-1800;}
	
	function out($code,$msg = null,$mi = null) {//输出结果
		if($msg && is_array($msg) && isset($msg['mi_state']) && isset($msg['mi_type'])){$mi = $msg;$msg = null;}
		if(!$msg && !is_array($msg)){
			require_once 'lang/lang_msg.php';//返回数组
			$msg = $lang_msg[$code];
		}
		if(DEFAULT_RETURN_TYPE == 0){
			if($mi && is_array($mi) && isset($mi['mi_state']) && isset($mi['mi_type'])){
				if($mi['mi_state'] == 'y' && $mi['mi_type'] ==1){
					if(is_array($msg)){$msg = json_encode($msg);}
					$msg = mi_rc4($msg,$mi['mi_rc4_key']);
				}elseif($mi['mi_state'] == 'y' && $mi['mi_type'] ==2){
					if(is_array($msg)){$msg = json_encode($msg);}
					$msg = RSA_SMI($msg,$app_res['mi_rsa_private_key']);
				}
			}
			$jdata = array('code'=>$code,'msg'=>$msg,'time'=>time());
			$data = json_encode($jdata);
		}elseif(DEFAULT_RETURN_TYPE == 1){
			require_once('class\Xml.php');//引入类配置信息
			header("Content-type:text/xml");//输出xml头信息
			$xml = new Array_to_Xml();//实例化类
			if($mi && is_array($mi) && isset($mi['mi_state']) && isset($mi['mi_type'])){
				if($mi['mi_state'] == 'y' && $mi['mi_type'] ==1){
					if(is_array($msg)){$msg = $xml->toXml($msg);}
					$msg = mi_rc4($msg,$mi['mi_rc4_key']);
				}elseif($mi['mi_state'] == 'y' && $mi['mi_type'] ==2){
					if(is_array($msg)){$msg = $xml->toXml($msg);}
					$msg = RSA_SMI($msg,$app_res['mi_rsa_private_key']);
				}
			}
			$res = array('code'=>$code,'msg'=>$msg,'time'=>time());
			$data = $xml->toXml($res);//转为数组 
		}
		echo $data;
		exit;
	}
	
	function timeRange($dayName = '',$date = FALSE){
		$startFix = ' 00:00:00';
		$endFix = ' 23:59:59';
		$day = date('Y-m-d');
		
		//当天 昨天 最近三天 最近七天 本月 上月
		//if($dayName)
		
		$data['t_a'] = $day.$startFix;//今天开始
		$data['t_b'] = $day.$endFix;//今天结束
		
		$data['zt_a'] = date('Y-m-d', strtotime('-1 day')).$startFix;//昨天开始
		$data['zt_b'] = date('Y-m-d', strtotime('-1 day')).$endFix;//昨天结束
		
		$data['t3_a'] = date('Y-m-d', strtotime('-3 day')).$startFix;//最近三天开始
		$data['t3_b'] = date('Y-m-d H:i:s');//最近三天结束
		
		$data['t7_a'] = date('Y-m-d', strtotime('-7 day')).$startFix;//最近三天开始
		$data['t7_b'] = date('Y-m-d H:i:s');//最近三天结束
		
		$data['yue_a'] = date('Y-m-01', strtotime(date("Y-m-d"))).$startFix;//本月开始
		$data['yue_b'] = date('Y-m-d', strtotime($data['yue_a'].' +1 month -1 day')).$endFix;//本月结束
		
		$data['syue_a'] = date('Y-m-01', strtotime('-1 month')).$startFix;//上月开始
		$data['syue_b'] = date('Y-m-t', strtotime('-1 month')).$endFix;//上月结束
		
		if($date == true){
			return $dayName ? $data[$dayName] : $data;
		}else{
			return $dayName ? strtotime($data[$dayName]) : $data;
		}
	}
	
	function pagination($count, $perlogs, $page, $url) {
		$pnums = @ceil($count / $perlogs);
		$re = '';
		$urlHome = preg_replace("|[\?&/][^\./\?&=]*page[=/\-]|", "", $url);
		for ($i = $page - 2; $i <= $page + 2 && $i <= $pnums; $i++) {
			if ($i > 0) {
				if ($i == $page) {
					$re .= "<li class=\"page-item active\"><a class=\"page-link\">$i</a></li>";
					//$re ."<li class=\"page-item active\"><a class=\"page-link\" >$i</a></li>";
					//$re .= "<li><span>$i</span></li>";
				} elseif ($i == 1) {
					
					$re .= "<li class=\"page-item\"><a class=\"page-link\" href=\"$urlHome\">$i</a></li>";
				} else {
					$re .= "<li class=\"page-item\"><a class=\"page-link\" href=\"$url$i\">$i</a></li>";
					//$re .= "<li><a href=\"$url$i\">$i</a></li>";
				}
			}
		}
		if($page > 0)
			if($pnums > $page){//前进
				$go = $page +1;
			}else{
				$go = $page;
			}
			if($page > 1){
				$after = $page -1;
			}else{
				$after = $page;
			}
			
			$re = "<li class=\"page-item\">	<a class=\"page-link\" href=\"$url$after\" aria-label=\"Previous\">		<span aria-hidden=\"true\">&laquo;</span>		<span class=\"sr-only\">Previous</span>	</a> </li>$re";
			$re .= "<li class=\"page-item\"><a class=\"page-link\" href=\"$url$go\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li>";
		if ($pnums <= 1)
			$re = '';
		return "<ul class=\"pagination justify-content-end\">".$re."</ul>";
	}
	
	function getIp() {
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		if (!ip2long($ip)) {
			$ip = '';
		}
		return $ip;
	}
	
	function getcode($length){ //取随机字符
		$str = null;  
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";  
		$max = strlen($strPol)-1;  
		for($i=0;$i<$length;$i++){
			$str.=$strPol[rand(0,$max)];
		}  
		return $str; 
	}
	
	function json($code,$msg) {//json输出
		$udata = array('code'=>$code,'msg'=>$msg);
		$jdata = json_encode($udata);
		echo $jdata;
		exit;
	}
	
	function send_mail($to, $name, $subject = '', $body = '', $attachment = null, $config = '') {//发送邮件
		$config = is_array($config) ? $config : array();
		require_once 'class/email/phpmailer.class.php';
		$mail = new PHPMailer();                           //PHPMailer对象
		$mail->CharSet = 'UTF-8';                         //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
		$mail->IsSMTP();                                   // 设定使用SMTP服务
		//$mail->IsHTML(true);
		$mail->SMTPDebug = 0;                             // 关闭SMTP调试功能 1 = errors and messages2 = messages only
		$mail->SMTPAuth = true;                           // 启用 SMTP 验证功能
		if ($config['smtp_port'] == 465)
		$mail->SMTPSecure = 'ssl';                    // 使用安全协议
		$mail->Host = $config['smtp_host'];                // SMTP 服务器
		$mail->Port = $config['smtp_port'];                // SMTP服务器的端口号
		$mail->Username = $config['smtp_user'];           // SMTP服务器用户名
		$mail->Password = $config['smtp_pass'];           // SMTP服务器密码
		$mail->SetFrom($config['from_email'],$config['from_name']);
		$replyEmail = $config['reply_email'] ? $config['reply_email'] : $config['reply_email'];
		$replyName = $config['reply_name'] ? $config['reply_name'] : $config['reply_name'];
		$mail->AddReplyTo($replyEmail, $replyName);
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$mail->AddAddress($to, $name);
		/*if (is_array($attachment)) { // 添加附件
			foreach ($attachment as $file) {
				if (is_array($file)) {
					is_file($file['path']) && $mail->AddAttachment($file['path'], $file['name']);
				} else {
					is_file($file) && $mail->AddAttachment($file);
				}
			}
		} else {
			is_file($attachment) && $mail->AddAttachment($attachment);
		}*/
		return $mail->Send() ? true : $mail->ErrorInfo;
	}
	
	function http_post($url,$data =null,$ua='') {//发送httppost请求
		require_once 'class/HttpCurl.php';
		$http = new HttpCurl();
		if(!empty($ua)){
			$result = $http->userAgent($ua)->post($url,$data);
		}else{
			$result = $http->post($url,$data);
		}
		return $result;
	}
	
	function http_gets($url,$data =null) {//发送httpget请求
		require_once 'class/HttpCurl.php';
		$http = new HttpCurl();
		$result = $http->get($url,$data);
		return $result;
	}
	
	function get_pic($pic_url,$dirname = FALSE) {//取头像链接
		if(substr($pic_url,0,4)=='http'){
			return $pic_url;
		}else{
			if(substr($pic_url,0,5) == '/pic/'){$pic_url = str_replace(substr($pic_url,0,5),'',$pic_url);}
			if($dirname){
				return dirname(WEB_URL).'/'.USER_PIC_MULU.$pic_url;
			}else{
				return WEB_URL.'/'.USER_PIC_MULU.$pic_url;
			}
		}
	}
	
	function purge($string,$trim = true,$filter = true,$force = 0, $strip = FALSE) {//递归addslashes  对参数进行净化
		$encode = mb_detect_encoding($string,array("ASCII","UTF-8","GB2312","GBK","BIG5"));
		if($encode != 'UTF-8'){
			$string = iconv($encode,'UTF-8',$string);
		}
		if($trim){$string=preg_replace('/\s+/','',$string);}
		if($filter){
			$farr = array(
				"/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
				"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
				"/select |insert |and |or |create |update |delete |alter |count |\'|\/\*|\*|\.\.\/|\.\/|\^|union |into |load_file|outfile |dump/is"
			);
			$string = preg_replace($farr,'',$string);
		}
		!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
		if(!MAGIC_QUOTES_GPC || $force) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					$string[$key] = purge($val, $force, $strip);
				}
			} else {
				$string = addslashes($strip ? stripslashes($string) : $string);
			}
		}
		
		return $string;
	}
	
	function check_phone($phone){//匹配手机号
        return preg_match('#^13[\d]{9}$|^14[5,6,7,8,9]{1}\d{8}$|^15[^4]{1}\d{8}$|^16[6]{1}\d{8}$|^17[0,1,2,3,4,5,6,7,8]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$#',$phone) ? true : false;
	}
	
	function check_email($email){//匹配邮箱
        return preg_match('/^[a-z0-9]+([._-][a-z0-9]+)*@([0-9a-z]+\.[a-z]{2,14}(\.[a-z]{2})?)$/i',$email) ? true : false;
	}
	
	function foreachArray($array = [], $count = 0){//数组维度判断
		if (!is_array($array)){
			return $count;
		}
		foreach ($array as $value){
			$count++;
			if (!is_array($value)){
				return $count;
			}
			return foreachArray($value, $count);
		}
	}
	
	function Arr_sign($arr,$key,$md5 = true){//数组签名
		unset($arr['sign']);
		unset($arr['app']);
		unset($arr['act']);
		$sign='';
		foreach ($arr as $k => $v) {
			$sign = $sign.$k . '='. $v .'&';
		}
		$sign = $sign.$key;
		if($md5){
			return md5($sign);
		}else{
			return $sign;
		}
	}
	
	function txt_Arr($txt){//文本转数组
		$arr = explode('&', $txt);
		$array = [];
		foreach($arr as $value){
			$tmp_arr = explode('=',$value);
			if(is_array($tmp_arr) && count($tmp_arr) == 2){
				$array = array_merge($array,[$tmp_arr[0]=>$tmp_arr[1]]);
			}
		}
		return $array;
	}
	
	function txt_zhong($str, $leftStr, $rightStr){//取文本中间
		$left = strpos($str, $leftStr);
		//echo '左边:'.$left;
		$right = strpos($str, $rightStr,$left);
		//echo '<br>右边:'.$right;
		if($left < 0 or $right < $left) return '';
		return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
	}

	function txt_you($str, $leftStr){//取文本右边
		$left = strpos($str, $leftStr);
		return substr($str, $left + strlen($leftStr));
	}

	function txt_zuo($str, $rightStr){//取文本左边
		$right = strpos($str, $rightStr);
		return substr($str, 0, $right);
	}
	
	function mi_rc4($data,$pwd,$t=0) {//t=0加密，1=解密
		$cipher = '';
		$key[] = "";
		$box[] = "";
		$pwd=mi_rc4_encode($pwd);
		$data=mi_rc4_encode($data);
		$pwd_length = strlen($pwd);
		if($t == 1){
			$data = hex2bin($data);
		}
		$data_length = strlen($data);
		for ($i = 0; $i < 256; $i++) {
			$key[$i] = ord($pwd[$i % $pwd_length]);
			$box[$i] = $i;
		}
		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $key[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		for ($a = $j = $i = 0; $i < $data_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$k = $box[(($box[$a] + $box[$j]) % 256)];
			$cipher .= chr(ord($data[$i]) ^ $k);
		}
		if($t == 1){
			return $cipher;
		}else{
			return bin2hex($cipher);
		}
	}
	
	function mi_rc4_encode($str,$turn = 0){//turn=0,utf8转gbk,1=gbk转utf8
		if(is_array($str)){
			foreach($str as $k => $v){
				$str[$k] = array_iconv($v);
			}
			return $str;
		}else{
			if(is_string($str) && $turn == 0){
				return mb_convert_encoding($str,'GBK','UTF-8');
			}elseif(is_string($str) && $turn == 1){
				return mb_convert_encoding($str,'UTF-8','GBK');
			}else{
				return $str;
			}
		}
	}

	function RSA_GMI($data,$key,$t=0) {//RSA公钥加解密
		require_once 'class/Rsa.php';//引入RSA加解密类
		if($t == 0){
			$mi_data = Rsa::publicEncrypt($data,$key);//使用公钥将数据加密
		}else{
			$mi_data = Rsa::publicDecrypt($data,$key);//使用公钥将数据解密
		}
		return $mi_data;
	}
	
	function RSA_SMI($data,$key,$t=0) {//RSA私钥加解密
		require_once 'class/Rsa.php';//引入RSA加解密类
		if($t == 0){
			$mi_data = Rsa::privateEncrypt($data,$key);//使用私钥将数据加密
		}else{
			$mi_data = Rsa::privateDecrypt($data,$key);//使用私钥将数据解密
		}
		return $mi_data;
	}
	
	function myScanDir($dir,$type = 0){//PHP 实现遍历出目录及其子文件
		$file_arr = scandir($dir);
		$new_arr = [];
		foreach($file_arr as $item){
			//echo $item.'<br>';
			if($type == 0 && $item != ".." && $item != "."){//目录和文件
				$new_arr[] = $item;
			}elseif($type == 1 &&  is_dir($dir.'/'.$item) && $item != ".." && $item != "."){//只要目录
				$new_arr[] = $item;
			}elseif($type == 2 &&  is_file($dir.'/'.$item) && $item != ".." && $item != "."){//只要文件
				$new_arr[] = $item;
			}
		}
		return $new_arr;
	}
?>