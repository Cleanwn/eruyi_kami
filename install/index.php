<?php
if(file_exists('install.lock')){//已经安装过了
	header("Location: ../"); 
	return;
}
date_default_timezone_set("PRC");
$php_bb = phpversion();
$mysql_bb = function_exists ('mysqli_connect')?"支持":"不支持";

$web_mulu = dirname(dirname($_SERVER['SCRIPT_FILENAME']));//当前目录

$web_url = dirname((($_SERVER['SERVER_PORT']==443) ? 'https':'http').'://'.$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'],(substr($_SERVER['DOCUMENT_ROOT'],-1) == '/') ? '/':'',dirname($_SERVER['SCRIPT_FILENAME'])));//当前域名
$error_msg = '';
$a = isset($_GET['a']) ? intval($_GET['a']) : 0;
$submit = isset($_POST['install']) ? addslashes($_POST['install']) : '';
if($a == 1 && $submit){
	$error = [
		'db_server' => '请输入数据库地址',
		'db_u' => '请输入数据库用户名',
		'db_p' => '请输入数据库密码',
		'db_name' => '请输入数据库名',
		'adm_u' => '请输入管理员账号',
		'adm_p' => '请输入管理员密码'
	];
	foreach ($error as $key => $val) {
		if (!array_isset($_POST, $key)) {
			$error_msg = $val;
			break;
		}
	}
	if (!$error_msg) {
		$db_pre = isset($_POST['db_pre']) ? addslashes($_POST['db_pre']) : '';
        $conn = @mysqli_connect($_POST['db_server'], $_POST['db_u'], $_POST['db_p']);
		mysqli_query($conn,"set names utf8");
        if ($conn) {
            if (@mysqli_select_db($conn, $_POST['db_name'])) {
				require_once 'eruyi_1.7.php';//引入数据表
				foreach($sql as $value){
					mysqli_query($conn,$value);
				}
                $config = file_get_contents($web_mulu.'/include/db.config.php');
                $config = preg_replace("/define\('DB_HOST','.*?'\)/", "define('DB_HOST','{$_POST['db_server']}')", $config);
                $config = preg_replace("/define\('DB_USER','.*?'\)/", "define('DB_USER','{$_POST['db_u']}')", $config);
                $config = preg_replace("/define\('DB_PASSWD','.*?'\)/", "define('DB_PASSWD','{$_POST['db_p']}')", $config);
                $config = preg_replace("/define\('DB_NAME','.*?'\)/", "define('DB_NAME','{$_POST['db_name']}')", $config);
				$config = preg_replace("/define\('DB_PRE','.*?'\)/", "define('DB_PRE','{$db_pre}')", $config);
                file_put_contents('../include/db.config.php', $config);

                $userdata = file_get_contents($web_mulu.'/admin/userdata.php');
                $userdata = preg_replace('/\$user = \'.*?\'/', '$user = \'' . $_POST['adm_u'] . '\'', $userdata);
                $userdata = preg_replace('/\$pass = \'.*?\'/', '$pass = \'' . $_POST['adm_p'] . '\'', $userdata);
				$userdata = preg_replace('/\$cookie = \'.*?\'/', '$cookie = \'' . md5($_POST['adm_u'].$_POST['adm_p'].time()) . '\'', $userdata);
                file_put_contents($web_mulu.'/admin/userdata.php', $userdata);
				//-------生成唯一随机串防CSRF攻击
				$state  = md5(uniqid(rand(),TRUE));
				setcookie('install_state', $state, time() + 3600, '/');
                header("Location: ./?a=2&s={$state}");
            } else {
                $error_msg = '未找到数据库';
            }
        } else {
            $error_msg = '错误的数据库信息,连接失败';
        }
    }
}

if($a==2){
	if(!isset($_GET['s']) or !isset($_COOKIE['install_state']) or $_GET['s'] != $_COOKIE['install_state']){
		header("Location: ../");
	}
}

function array_isset($arr, $key){
	return isset($arr[$key]) && !empty($arr[$key]);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php if($a==0): ?>环境监测<?php elseif($a==1): ?>数据库配置<?php elseif($a==2): ?>安装完成<?php endif; ?> - 易如意网络验证系统1.7</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="../assets/images/favicon.ico">
		<!-- Summernote css -->
        <link href="../assets/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" />
        <!-- App css -->
        <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/eruyi.min.css" rel="stylesheet" type="text/css" />
		<link href="../assets/css/main.css" rel="stylesheet" />
		<style>
			a{text-decoration:none}a:hover{text-decoration:underline}.page-header{text-align:center;}@media screen and (min-width: 64em){.page-header{padding:5rem 6rem}}@media screen and (min-width: 42em) and (max-width: 64em){.page-header{padding:3rem 4rem}}@media screen and (max-width: 42em){.page-header{padding:2rem 1rem}}.project-name{margin-top:0;margin-bottom:0.1rem}@media screen and (min-width: 64em){.project-name{font-size:3.25rem}}@media screen and (min-width: 42em) and (max-width: 64em){.project-name{font-size:2.25rem}}@media screen and (max-width: 42em){.project-name{font-size:1.75rem}}.project-tagline{margin-bottom:2rem;font-weight:normal;opacity:0.7}@media screen and (min-width: 64em){.project-tagline{font-size:1.25rem}}@media screen and (min-width: 42em) and (max-width: 64em){.project-tagline{font-size:1.15rem}}@media screen and (max-width: 42em){.project-tagline{font-size:1rem}}
		</style>
    </head>
    <body>
		<header class="uk-background-primary uk-background-norepeat uk-background-cover uk-background-center-center uk-light" 
			style="background-image: url(../assets/images/header.jpg);">
			<section class="page-header">
				<h1 class="project-name" style="text-transform: uppercase;">
					<?php if($a==0): ?>环境监测<?php elseif($a==1): ?>数据库配置<?php elseif($a==2): ?>安装完成<?php endif; ?> - 系统安装
				</h1>
			</section>
		</header>
        <div class="mt-2 mb-2">
            <div class="container">
				<?php if($a==0): ?>
                <div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-body">
								<h2 id="user_reg">服务器环境监测</h2>
								<div class="table-responsive-sm">
									<table class="table table-bordered table-centered">
										<thead>
											<tr>
												<th class="text-center">参数</th>
												<th class="text-center">当前值</th>
												<th class="text-center">需求值</th>
												<th class="text-center">状态</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="text-center">当前域名</td>
												<td class="text-center"><?php echo $web_url;?></td>
												<td class="text-center">*</td>
												<td class="text-center"><?php if($web_url):?><span class="badge badge-success-lighten">正常<?php else:?><span class="badge badge-danger-lighten">异常<?php endif; ?></span></td>
											</tr>
											<tr>
												<td class="text-center">PHP版本</td>
												<td class="text-center"><?php echo $php_bb;?></td>
												<td class="text-center">>=5.6</td>
												<td class="text-center"><?php if($php_bb>=5.6):?><span class="badge badge-success-lighten">正常<?php else:?><span class="badge badge-danger-lighten">异常<?php endif; ?></span></td>
											</tr>
											<tr>
												<td class="text-center">MYSQL</td>
												<td class="text-center"><?php echo $mysql_bb;?></td>
												<td class="text-center">支持</td>
												<td class="text-center"><?php if($mysql_bb=='支持'):?><span class="badge badge-success-lighten">正常<?php else:?><span class="badge badge-danger-lighten">异常<?php endif; ?></span></td>
											</tr>
											<tr>
												<td class="text-center">服务器系统</td>
												<td class="text-center"><?php echo PHP_OS;?></td>
												<td class="text-center">WINNT/LINUX</td>
												<td class="text-center"><?php if(strtoupper(PHP_OS)=='WINNT' or strtoupper(PHP_OS)=='LINUX'):?><span class="badge badge-success-lighten">正常<?php else:?><span class="badge badge-danger-lighten">异常<?php endif; ?></span></td>
											</tr>
										</tbody>
									</table>
								</div> <!-- end table-responsive-->
								
								<li class="next list-inline-item float-right"><a href="./?a=1" class="btn btn-primary">继续</a></li>
								<p class="mt-1">提示：只有所有状态都为 <span class="badge badge-success-lighten">正常</span> 时才能继续下一步</p>
							</div> <!-- end card-body-->
						</div> <!-- end card-->
					</div> <!-- end col -->
				</div><!-- end row-->
				<?php elseif($a==1): ?>
				<div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-body">
								<h2 id="user_reg">数据库安装</h2>
								<form action="./?a=1" method="post" id="addimg" name="addimg">
									<div class="form-row">
										<div class="form-group col-md-12">
											<label>数据库地址</label>
											<input name="db_server" id="db_server" type="text" class="form-control" value="localhost" required placeholder="数据库连接地址">
										</div>
										<div class="form-group col-md-12">
											<label>数据库用户</label>
											<input name="db_u" id="db_u" type="text" class="form-control" value="root" required placeholder="数据库账号">
										</div>
										<div class="form-group col-md-12">
											<label>数据库密码</label>
											<input name="db_p" id="db_p" type="text" class="form-control" required placeholder="填写数据库密码">
										</div>
										<div class="form-group col-md-12">
											<label>数据库名</label>
											<input name="db_name" id="db_name" type="text" class="form-control" required placeholder="数据库名称">
										</div>
										<div class="form-group col-md-12">
											<label>数据库前缀</label>
											<input name="db_pre" id="db_pre" type="text" class="form-control" value="eruyi_" required placeholder="数据库前缀">
										</div>
									</div>
									<hr>
									<div class="form-row">
										<div class="form-group col-md-12">
											<label>管理员账号</label>
											<input name="adm_u" id="adm_u" type="text" class="form-control" value="" required placeholder="后台管理账号">
										</div>
										<div class="form-group col-md-12">
											<label>管理员密码</label>
											<input name="adm_p" id="adm_p" type="text" class="form-control" value="" required placeholder="后台管理密码">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-11">
											<?php if($error_msg):?>
											<div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert" style="padding:.55rem .9rem!important;">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding:.55rem 1.0rem!important;">
													<span aria-hidden="true">&times;</span>
												</button>
												<strong>错误 - </strong> <?php echo $error_msg;?>
											</div>
											<?php else:?>
											<div class="alert alert-warning alert-dismissible fade show" role="alert" style="padding:.55rem .9rem!important;">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding:.55rem 1.0rem!important;">
													<span aria-hidden="true">&times;</span>
												</button>
												<strong>提示 - </strong> 请确保所有数据都填写正确，并且牢记账号密码，否则可能安装失败
											</div>
											<?php endif;?>
										</div>
										<div class="form-group col-md-1">
											<button type="submit" name="install" id="install" value="确定" class="btn btn-block btn-primary">安装</button>
										</div>
									</div>
								</form>
							</div> <!-- end card-body-->
						</div> <!-- end card-->
					</div> <!-- end col -->
				</div><!-- end row-->
				<?php elseif($a==2 && isset($_GET['s']) && isset($_COOKIE['install_state']) && $_GET['s'] == $_COOKIE['install_state']): @file_put_contents("install.lock",'欢迎使用易如意验证系统1.7');setcookie('install_state','',0, '/');?>
				<div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-body">
								<div class="text-center">
									<h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
									<h3 class="mt-0">Good for you !</h3>
									<p class="w-75 mb-2 mt-2 mx-auto">易如意网络验证系统1.7安装完成，您可以开始使用本系统了。若访问首页任然继续跳转至安装跳转，请自行在install/目录下创建一个 install.lock 空文档即可</p>
									<div class="mb-3 mt-2">
										<a href="../" class="btn btn-primary">返回首页</a>
										<a href="../admin" class="btn btn-success">前往后台</a>
									</div>
								</div>
							</div> <!-- end card-body-->
						</div> <!-- end card-->
					</div> <!-- end col -->
				</div><!-- end row-->
				<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
				<script>$.ajax({cache:false,type:"POST",url:"http://nvs.eruyi.cn/api.php?act=cdkey",data:{www:'<?php echo $web_url;?>'},dataType:'json',success:function(data){}});</script>
				<?php else:header("Location: /");endif; ?>
            </div><!-- end container -->
        </div>
		
		<div class="mt-5">
			<footer class="footer footer-alt" style="border-top:1px solid rgba(152,166,173,.15);">
				2018 - <?php echo date('Y',time());?> © <a href="http://www.eruyi.cn/" class="text-title" style="text-decoration:none" target="_blank">易如意</a> - eruyi.cn
			</footer>
		</div>	
        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>

    </body>
</html>
