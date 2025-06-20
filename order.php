<?php 
//php防注入和XSS攻击通用过滤.
$_GET     && SafeFilter($_GET);
$_POST    && SafeFilter($_POST);
$_COOKIE  && SafeFilter($_COOKIE);

function SafeFilter (&$arr){
  $ra=Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/','/script/','/javascript/','/vbscript/','/expression/','/applet/','/meta/','/xml/','/blink/','/link/','/style/','/embed/','/object/','/frame/','/layer/','/title/','/bgsound/','/base/','/onload/','/onunload/','/onchange/','/onsubmit/','/onreset/','/onselect/','/onblur/','/onfocus/','/onabort/','/onkeydown/','/onkeypress/','/onkeyup/','/onclick/','/ondblclick/','/onmousedown/','/onmousemove/','/onmouseout/','/onmouseover/','/onmouseup/','/onunload/');
  if (is_array($arr)){
    foreach ($arr as $key => $value){
      if(!is_array($value)){
        if (!get_magic_quotes_gpc()){             //不对magic_quotes_gpc转义过的字符使用addslashes(),避免双重转义。
          $value=addslashes($value);           //给单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）加上反斜线转义
        }
        $value=preg_replace($ra,'',$value);     //删除非打印字符，粗暴式过滤xss可疑字符串
        $arr[$key]     = htmlentities(strip_tags($value)); //去除 HTML 和 PHP 标记并转换为 HTML 实体
      }else{
        SafeFilter($arr[$key]);
      }
    }
  }
}
require 'include/global.php';
$so = isset($_GET['out_trade_no']) ? (purge($_GET['out_trade_no'])) : (isset($_GET['so']) ? purge($_GET['so']) : '');

if(!empty($so)){
	$order_res = Db::table('goods_order','as O')->field('O.*,U.name as uname,U.user,U.email,U.phone,G.type,G.amount')->JOIN("goods","as G",'O.gid=G.id')->JOIN("user",'as U','O.Uid=U.id')->where('O.order',$so)->whereOr(['U.user'=>$so,'U.email'=>$so,'U.phone'=>$so])->order('id desc')->find();//false
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>订单查询 - 易如意网络验证系统1.7</title>
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
			a{color:#1e6bb8;text-decoration:none}a:hover{text-decoration:underline}background-color:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.2);border-style:solid;border-width:1px;border-radius:0.3rem;transition:color 0.2s, background-color 0.2s, border-color 0.2s}.btn:hover{color:rgba(255,255,255,0.8);text-decoration:none;background-color:rgba(255,255,255,0.2);border-color:rgba(255,255,255,0.3)}.page-header{text-align:center;}@media screen and (min-width: 64em){.page-header{padding:5rem 6rem}}@media screen and (min-width: 42em) and (max-width: 64em){.page-header{padding:3rem 4rem}}@media screen and (max-width: 42em){.page-header{padding:2rem 1rem}}.project-name{margin-top:0;margin-bottom:0.1rem}@media screen and (min-width: 64em){.project-name{font-size:3.25rem}}@media screen and (min-width: 42em) and (max-width: 64em){.project-name{font-size:2.25rem}}@media screen and (max-width: 42em){.project-name{font-size:1.75rem}}.project-tagline{margin-bottom:2rem;font-weight:normal;opacity:0.7}@media screen and (min-width: 64em){.project-tagline{font-size:1.25rem}}@media screen and (min-width: 42em) and (max-width: 64em){.project-tagline{font-size:1.15rem}}@media screen and (max-width: 42em){.project-tagline{font-size:1rem}}
		</style>
    </head>

    <body>
		<header class="uk-background-primary uk-background-norepeat uk-background-cover uk-background-center-center uk-light" 
			style="background-image: url(assets/images/header.jpg);">
			<section class="page-header">
				<h1 class="project-name" style="text-transform: uppercase;">订单查询</h1>
				<div class="row mt-4">
					<div class="col-lg-3"></div>
					<div class="col-lg-6">
						<div class="text-lg-right">
							<form action="">
								<div class="input-group">
									<input type="text" class="form-control" name="so" placeholder="可以搜索订单号或者充值账号" value='<?php echo $so;?>'>
									<span class="mdi mdi-magnify"></span>
									<div class="input-group-append">
										<button class="btn btn-success" type="so_submit">查询订单</button>
									</div>
								</div>
							</form>
						</div>
					</div><!-- end col-->
				</div>
			</section>
		</header>
		
		<!-- end row -->
        <div class="mt-2 mb-2">
            <div class="container" id="news">
                <div class="row mb-2">
					<div class="col-xl-12">
						<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
										<!-- sample modal content -->
                                        <!-- Invoice Logo-->
										<?php if(!$order_res): ?>
										<div class="text-center" style="margin-top:6.5rem!important;margin-bottom:7.5rem!important">
											<img src="assets/images/report.svg" height="120" alt="File not found Image">
											<?php if(empty($so)):?>
											<h4 class="text-uppercase mt-3" id="so_msg">输入您的订单搜索看看吧</h4>
											<?php else: ?>
											<h4 class="text-uppercase mt-3" id="so_msg">没有搜索到相关信息的订单</h4>
											<?php endif; ?>
										</div>	
										<?php else: ?>
										<div class="clearfix">
											<div class="float-left mb-3">
												<img src="assets/images/logo-light.png" alt="" height="18">
											</div>
											<div class="float-right">
												<h4 class="m-0 d-print-none">订单信息</h4>
											</div>
										</div>

										<!-- Invoice Detail-->
										<div class="row">
											<div class="col-sm-6">
												<div class="float-left mt-3">
													<p><b>你好，<?php echo $order_res['uname'];?></b></p>
													<p class="text-muted font-13">为了不必要的麻烦，请认真核对您的订单，如有任何问题，请随时与我们联系。</p>
												</div>
			
											</div><!-- end col -->
											<div class="col-sm-4 offset-sm-2">
												<div class="mt-3 float-sm-right">
													<p class="font-13"><strong>订单编号: </strong><span class="float-right"><?php echo $order_res['order'];?></span></p>
													<p class="font-13"><strong>订单状态: </strong>
														<?php if($order_res['state']==0):?><span class="badge badge-warning-lighten">等待支付
														<?php elseif($order_res['state']==1):?><span class="badge badge-danger-lighten">充值失败
														<?php elseif($order_res['state']==2): ?><span class="badge badge-success-lighten">支付成功
														<?php elseif($order_res['state']==3): ?><span class="badge badge-danger-lighten">未找到用户
														<?php elseif($order_res['state']==4): ?><span class="badge badge-danger-lighten">未知商品类型
														<?php elseif($order_res['state']==9): ?><span class="badge badge-danger-lighten">永久会员
														<?php endif; ?></span>
													</span>
													</p>
												</div>
											</div><!-- end col -->
										</div>
										<!-- end row -->
	
										<div class="row">
											<div class="col-12">
												<div class="table-responsive">
													<table class="table mt-4">
														<thead>
															<tr>
																<th style="width: 50px;"><span class="badge badge-light-lighten">订单ID</span></th>
																<th><span class="badge badge-light-lighten">商品名称</span></th>
																<th><span class="badge badge-light-lighten">数量</span></th>
																<th><span class="badge badge-light-lighten">商品价格</span></th>
																<th class="text-right"><span class="badge badge-light-lighten">总计</span></th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<span class="badge badge-light-lighten">
																	<?php echo $order_res['id'];?>
																	</span>
																</td>
																<td>
																	&nbsp;<span class="badge badge-primary">
																	<b><?php echo $order_res['name'];?></b>
																	</span>
																</td>
																<td><span class="badge badge-light-lighten">&nbsp;&nbsp;1</span></td>
																<td>&nbsp;<span class="badge badge-success-lighten">¥&nbsp;<?php echo $order_res['money'];?></span></td>
																<td class="text-right"><span class="badge badge-danger-lighten">¥&nbsp;<?php echo $order_res['money'];?></span></td>
															</tr>
														</tbody>
													</table>
												</div> <!-- end table-responsive-->
											</div> <!-- end col -->
										</div>
										<!-- end row -->
										<?php endif; ?>
                                        <!-- end row-->
                                        <!-- end buttons -->
                                    </div> <!-- end card-body-->
                                </div> <!-- end card -->
                            </div> <!-- end col-->
						</div> <!-- end row -->
					</div> <!-- end col -->
				</div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>				
        <div class="mt-5">
			<footer class="footer footer-alt" style="border-top:1px solid rgba(152,166,173,.15);">
				2018 - <?php echo date('Y',time());?> © <a href="http://www.eruyi.cn/" class="text-title" style="text-decoration:none" target="_blank">易如意</a> - eruyi.cn
			</footer>
		</div>
        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
		<script src="assets/js/vendor/dataList.industry.news.js"></script>
    </body>
</html>
