<?php if(!defined('INDEX_TEMPLATE'))header("Location: /");//非法访问拦截?>	
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>开发文档 - 易如意网络验证系统1.7</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
		<!-- Summernote css -->
        <link href="assets/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" />
        <!-- App css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/eruyi.min.css" rel="stylesheet" type="text/css" />
		
		<link href="assets/css/main.css" rel="stylesheet" />
		<style>
			a{text-decoration:none}a:hover{text-decoration:underline}.page-header{text-align:center;}@media screen and (min-width: 64em){.page-header{padding:5rem 6rem}}@media screen and (min-width: 42em) and (max-width: 64em){.page-header{padding:3rem 4rem}}@media screen and (max-width: 42em){.page-header{padding:2rem 1rem}}.project-name{margin-top:0;margin-bottom:0.1rem}@media screen and (min-width: 64em){.project-name{font-size:3.25rem}}@media screen and (min-width: 42em) and (max-width: 64em){.project-name{font-size:2.25rem}}@media screen and (max-width: 42em){.project-name{font-size:1.75rem}}.project-tagline{margin-bottom:2rem;font-weight:normal;opacity:0.7}@media screen and (min-width: 64em){.project-tagline{font-size:1.25rem}}@media screen and (min-width: 42em) and (max-width: 64em){.project-tagline{font-size:1.15rem}}@media screen and (max-width: 42em){.project-tagline{font-size:1rem}}
		</style>
    </head>
    <body>
		<header class="uk-background-primary uk-background-norepeat uk-background-cover uk-background-center-center uk-light" 
			style="background-image: url(assets/images/header.jpg);">
			<section class="page-header">
				<h1 class="project-name" style="text-transform: uppercase;">
					开发文档 - 易如意网络验证系统1.7
				</h1>
			</section>
		</header>
		
        <div class="mt-2 mb-2">
            <div class="container">
                <div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-body">
								<div>
									<h2>协议规则</h2>
									<p>传输方式：HTTP</p>
									<p>提交方式：POST</p>
									<p>返回格式：JSON、XML</p>
									<p>数据加密：RSA、RC4</p>
									<p>签名算法：MD5</p>
									<p>字符编码：UTF-8</p>
									<hr/>
								</div>
								<div style="display:inline-block;width:100%;">
									<a href="#ini" class="btn btn-link btn-sm">[API]应用配置</a>
									<a href="#notice" class="btn btn-link btn-sm">[API]应用通知</a>
									<a href="#user_reg" class="btn btn-link btn-sm">[API]普通注册</a>
									<a href="#user_logon" class="btn btn-link btn-sm">[API]账号登录</a>
									<a href="#email_reg" class="btn btn-link btn-sm">[API]邮箱注册</a>
									<a href="#alter_name" class="btn btn-link btn-sm">[API]修改用户昵称</a>
									<a href="#alter_pass" class="btn btn-link btn-sm">[API]修改用户密码</a>
									<a href="#seek_pass" class="btn btn-link btn-sm">[API]找回密码</a>
									<a href="#email_bind" class="btn btn-link btn-sm">[API]绑定邮箱</a>
									<a href="#email_untie" class="btn btn-link btn-sm">[API]解绑邮箱</a>
									<a href="#set_up" class="btn btn-link btn-sm">[API]设置用户账号密码</a>
									<a href="#get_info" class="btn btn-link btn-sm">[API]获取用户信息</a>
									<a href="#get_fen" class="btn btn-link btn-sm">[API]积分验证</a>
									<a href="#get_vip" class="btn btn-link btn-sm">[API]会员验证</a>
									<a href="#clock" class="btn btn-link btn-sm">[API]打卡签到</a>
									<a href="#card" class="btn btn-link btn-sm">[API]卡密充值</a>
									<a href="#upic" class="btn btn-link btn-sm">[API]上传用户头像</a>
									<a href="#km_logon" class="btn btn-link btn-sm">[API]卡密登录</a>
									<a href="#qq_login" class="btn btn-link btn-sm">[API]QQ登录注册</a>
									<a href="#qq_bind" class="btn btn-link btn-sm">[API]绑定QQ</a>
									<a href="#wx_login" class="btn btn-link btn-sm">[API]微信登录注册</a>
									<a href="#wx_bind" class="btn btn-link btn-sm">[API]绑定微信</a>
									<a href="#goods" class="btn btn-link btn-sm">[API]获取商品</a>
									<a href="#pay" class="btn btn-link btn-sm">[API]发起支付</a>
									<a href="#pay_res" class="btn btn-link btn-sm">[API]查询支付结果</a>
									<a href="#order" class="btn btn-link btn-sm">[API]订单查询</a>
									<a href="#motion" class="btn btn-link btn-sm">[API]运动心跳</a>
									<a href="#afcrc" class="btn btn-link btn-sm">[API]获取验证码</a>
									<a href="#sign" class="btn btn-link btn-sm">[示例]Sign签名计算方式</a>
								</div>
							</div> <!-- end card-body-->
						</div> <!-- end card-->
					</div> <!-- end col -->
				</div><!-- end row-->
            </div><!-- end container -->
        </div>
		
		<?php $doc_arr = myScanDir(FCPATH.'template/'.INDEX_TEMPLATE.'/doc/',2); foreach($doc_arr as $value){include_once 'doc/'.$value;}?>
		<div class="mt-5">
			<footer class="footer footer-alt" style="border-top:1px solid rgba(152,166,173,.15);">
				2018 - <?php echo date('Y',time());?> © <a href="http://www.eruyi.cn/" class="text-title" style="text-decoration:none" target="_blank">易如意</a> - eruyi.cn
			</footer>
		</div>	
        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

    </body>
</html>
