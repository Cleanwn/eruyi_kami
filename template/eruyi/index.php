<?php if(!defined('INDEX_TEMPLATE'))header("Location: /");//非法访问拦截?>	
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>易如意网络验证系统1.7</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <!-- App css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/eruyi.min.css" rel="stylesheet" type="text/css" />
		
		<link href="assets/css/main.css" rel="stylesheet" />
		<style>
			a{color:#1e6bb8;text-decoration:none}a:hover{text-decoration:underline}.btn{display:inline-block;margin-bottom:1rem;color:rgba(255,255,255,0.7);background-color:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.2);border-style:solid;border-width:1px;border-radius:0.3rem;transition:color 0.2s, background-color 0.2s, border-color 0.2s}.btn:hover{color:rgba(255,255,255,0.8);text-decoration:none;background-color:rgba(255,255,255,0.2);border-color:rgba(255,255,255,0.3)}.btn+.btn{margin-left:1rem}@media screen and (min-width: 64em){.btn{padding:0.75rem 1rem}}@media screen and (min-width: 42em) and (max-width: 64em){.btn{padding:0.6rem 0.9rem;font-size:0.9rem}}@media screen and (max-width: 42em){.btn{display:block;width:100%;padding:0.75rem;font-size:0.9rem}.btn+.btn{margin-top:1rem;margin-left:0}}.page-header{text-align:center;}@media screen and (min-width: 64em){.page-header{padding:5rem 6rem}}@media screen and (min-width: 42em) and (max-width: 64em){.page-header{padding:3rem 4rem}}@media screen and (max-width: 42em){.page-header{padding:2rem 1rem}}.project-name{margin-top:0;margin-bottom:0.1rem}@media screen and (min-width: 64em){.project-name{font-size:3.25rem}}@media screen and (min-width: 42em) and (max-width: 64em){.project-name{font-size:2.25rem}}@media screen and (max-width: 42em){.project-name{font-size:1.75rem}}.project-tagline{margin-bottom:2rem;font-weight:normal;opacity:0.7}@media screen and (min-width: 64em){.project-tagline{font-size:1.25rem}}@media screen and (min-width: 42em) and (max-width: 64em){.project-tagline{font-size:1.15rem}}@media screen and (max-width: 42em){.project-tagline{font-size:1rem}}
		</style>
    </head>

    <body>
		<header class="uk-background-primary uk-background-norepeat uk-background-cover uk-background-center-center uk-light" 
			style="background-image: url(assets/images/header.jpg);">
			<section class="page-header">
				<h1 class="project-name" style="text-transform: uppercase;">
					易如意网络验证系统1.7</h1>
				<h2 class="project-tagline">
					全新用户统计方式，可添加多个应用软件增加收费授权注册功能，采用PHP+Mysql构架，管理后台功能强大、界面美观，可以使用手机、ipad等移动设备操作后台，快速完成日常管理操作。
				</h2>
				<a href="./?doc" class="btn">
					开发文档
				</a>
				<a href="./order.php" class="btn">
					订单查询
				</a>
				<a href="http://nvs.eruyi.cn" class="btn" target="_blank">
					下载程序
				</a>
				<a href="http://nvs.eruyi.cn" class="btn" target="_blank">
					更新日志
				</a>
			</section>
		</header>
		
		<!-- end row -->
        <div class="mt-2 mb-2">
            <div class="container" id="news">
                <div class="row mb-2">
					<div class="col-xl-12">
						<div class="row">
							<?php
								foreach ($app_res as $k => $v){$rows = $app_res[$k];
							?>
							<div id='app_<?php echo $rows['id']; ?>' class="col-lg-4">
								<div class="card widget-flat">
									<div class="card-body">
										<div class="float-right">
											<i class="mdi mdi-cube-outline widget-icon"></i>                                                
										</div>
										<h5 class="text-muted font-weight-normal mt-0"><?php echo $rows['name']; ?></h5>
										<h3 class="mt-1 mb-2"><?php echo $rows['unum']; ?></h3>
										<p class="mb-0 text-muted">
											<span class="text-nowrap">版本号：<?php echo $rows['app_bb']; ?></span>                                                
										</p>
									</div> <!-- end card-body-->
								</div> <!-- end card-->
							</div> <!-- end col-->
							<?php } ?>
							
						</div> <!-- end row -->
					</div> <!-- end col -->
				</div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        
		<!-- end row -->
        <div class="mt-1 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="row mt-2 mb-4">
                                <div class="col-md-4">
                                    <div class="text-center mt-3 pl-1 pr-1">
                                        <i class="dripicons-jewel bg-primary maintenance-icon text-white mb-2"></i>
                                        <h5 class="text-uppercase">方便快捷</h5>
                                        <p class="text-muted">多应用一站式高效率管理</p>
                                    </div>
                                </div> <!-- end col-->
                                <div class="col-md-4">
                                    <div class="text-center mt-3 pl-1 pr-1">
                                        <i class="dripicons-meter bg-primary maintenance-icon text-white mb-2"></i>
                                        <h5 class="text-uppercase">全新统计方式</h5>
                                        <p class="text-muted">轻松查看各种数据统计，分析各阶段数占比例。</p>
                                    </div>
                                </div> <!-- end col-->
                                <div class="col-md-4">
                                    <div class="text-center mt-3 pl-1 pr-1">
                                        <i class="dripicons-question bg-primary maintenance-icon text-white mb-2"></i>
                                        <h5 class="text-uppercase">安装使用环境</h5>
                                        <p class="text-muted">需要php环境>=5.6 和 mysql数据库5.6</p>
                                    </div>
                                </div> <!-- end col-->
                            </div> <!-- end row-->
                        </div> <!-- end /.text-center-->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <footer class="footer footer-alt" style="border-top:1px solid rgba(152,166,173,.15);">
            2018 - <?php echo date('Y',time());?> © <a href="http://www.eruyi.cn/" class="text-title" style="text-decoration:none" target="_blank">易如意</a> - eruyi.cn
        </footer>
        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
		<script src="assets/js/vendor/dataList.industry.news.js"></script>
    </body>
</html>
