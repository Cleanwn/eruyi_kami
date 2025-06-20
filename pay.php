<?php
	require 'include/global.php';
	$appid = isset($_GET['app']) && !empty($_GET['app']) ? intval($_GET['app']) : die("<script>location.href='./';</script>");//APPID
	$user = isset($_GET['u']) && !empty($_GET['u']) ? purge($_GET['u']) : die("<script>location.href='./';</script>");//用户
	$res_user = Db::table('user','as U')->field('U.pic,U.name,U.vip,U.fen,A.pay_ali_state,A.pay_wx_state,A.pay_qq_state')->JOIN('app','as A','U.appid=A.id')->where(['U.appid'=>$appid],"(",")")->where('(U.user',$user)->whereOr(['U.email'=>$user,'U.phone'=>$user],")")->find();//false
	if(!$res_user)die("<script>location.href='./';</script>");
	$name = $res_user['name'];
	$pic = get_pic($res_user['pic']);
	$vip = $res_user['vip'];
	$fen = $res_user['fen'];
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $name; ?> - 支付页面</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <!-- App css -->
        <link href="assets/css/eruyi.min.css" rel="stylesheet" type="text/css" />
		
		<link href="assets/css/style.css" rel="stylesheet" />
		<link href="assets/css/xtiper.css" type="text/css" rel="stylesheet" />
		<script src="assets/js/xtiper.js" type="text/javascript"></script>
		
    </head>
    <body>
		<!-- end row -->
        <div class="mt-2 mb-2">
            <div class="container" id="news">
                <div class="row mb-2">
					<div class="col-xl-12">
						<div class="row">
                            <div class="col-sm-12">
                                <!-- Profile -->
                                <div class="card">
                                    <div class="card-body profile-user-box">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="media">
                                                    <span class="float-left m-2 mr-4"><img src="<?php echo $pic; ?>" style="height: 100px;" alt="" class="rounded-circle img-thumbnail"></span>
                                                    <div class="media-body mt-3">
                                                        <h4 class="mt-1 mb-1"><?php echo $name; ?></h4>
                                                        <p class="font-13 mt-2">账号：<?php echo $user; ?></p>
                                                        <ul class="mb-0 list-inline">
															<p class="mb-0 font-13">
															<span class="badge badge-warning-lighten">积分：<?php echo $fen;?></span>
															<?php if($vip=='999999999'):?><span class="badge badge-danger-lighten">永久会员<?php elseif($vip>time()):?><span class="badge badge-danger-lighten">会员：<?php echo date("Y/m/d H:i",$vip); else:?><span class="badge badge-light">普通用户<?php endif; ?></span>
                                                            </p>
                                                        </ul>
                                                    </div> <!-- end media-body-->
                                                </div>
                                            </div> <!-- end col-->
                                        </div> <!-- end row -->
                                    </div> <!-- end card-body/ profile-user-box-->
                                </div><!--end profile/ card -->
                            </div> <!-- end col-->
						</div>
						
						<div class="row">
                            <div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div id="inpitassembly" class="inpit">
											<!--- 单选 --->
											<div class="li" checkbox>
												<?php
													$goods_res = Db::table('goods')->where(['appid'=>$appid,'state'=>'y'])->select();
													foreach ($goods_res as $k => $v){$rows = $goods_res[$k];
												?>
												<div name="goods" value="<?php echo $rows['id'] ?>" onclick="news(<?php echo $rows['id'] ?>,'¥&nbsp;<?php echo $rows['money'] ?>','<?php echo $rows['name'] ?>')" style="float:left;">
													<h2><?php echo $rows['name'] ?></h2>
													<p id="money_<?php echo $k ?>">¥&nbsp;<?php echo $rows['money'] ?></p>
													<p id="gid_<?php echo $k ?>" hidden><?php echo $rows['id'] ?></p>
												</div>
												<?php } ?>
											</div>
										</div> 
									</div>
								</div>
                            </div> <!-- end col -->
                        </div>
						
						<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
										<!-- sample modal content -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="clearfix pt-3">
                                                    <h6 class="text-muted">温馨提示:</h6>
                                                    <small>
                                                        请在30分钟内完成付款操作，否则该订单将作废无效
                                                    </small>
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-sm-6">
                                                <div class="float-right mt-3 mt-sm-0">
                                                    <p><b>小计：</b> <span class="float-right" id="money_span">¥&nbsp;0.00</span></p>
                                                    <p><b>优惠：</b> <span class="float-right">¥&nbsp;0.00</span></p>
                                                    <h3 id="money_h3">¥&nbsp;0.00&nbsp;RMB</h3>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div> <!-- end col -->
                                        </div>
                                        <!-- end row-->
                                        <div class="d-print-none mt-4">
                                            <div class="text-right">
												<?php if($res_user['pay_ali_state']=='y'):?><button type="button" id="ali_submit" class="btn btn-info">支付宝支付</button><?php endif; ?>
												<?php if($res_user['pay_wx_state']=='y'):?><button type="button" id="wx_submit" class="btn btn-success">微信支付</button><?php endif; ?>
												<?php if($res_user['pay_qq_state']=='y'):?><button type="button" id="qq_submit" class="btn btn-dark">QQ支付</button><?php endif; ?>
                                            </div>
                                        </div>  
                                        <!-- end buttons -->
                                    </div> <!-- end card-body-->
                                </div> <!-- end card -->
                            </div> <!-- end col-->
                        </div>
					</div> <!-- end col -->
				</div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="assets/js/inpitassembly-2.0.js"></script>
		<script>
			//定义变量获取屏幕视口宽度
			var windowWidth = $(window).width();
			if(windowWidth < 640){
				var goods = document.getElementsByName("goods");
				for (i = 0; i < goods.length; i++) {
					goods[i].style.cssFloat="none";
				}
			}
			
			var g_id = document.getElementById('gid_0').innerHTML;
			var g_money = document.getElementById('money_0').innerHTML;
			
			document.getElementById('money_span').innerHTML=g_money;
			document.getElementById('money_h3').innerHTML=g_money+"&nbsp;RMB";
			$(document).ready(function(){
				$("#inpitassembly").inpitassembly({
					selected:"ack",
					ischeck_:true,
					ischeck_class:false,
				});		
			})		
			// 撅起屁股 等待交易
			function news(id,money,name){
				g_id = id;
				
				document.getElementById('money_span').innerHTML=money;
				document.getElementById('money_h3').innerHTML=money+"&nbsp;RMB";
				//console.log(id,money,name);
			}
			
			$('#ali_submit').click(function() {
				var order = randomNumber();
				var mobile_flag = isMobile();
				if(mobile_flag){
					window.location.href='<?php echo WEB_URL."/api.php?app={$appid}&act=pay&ua=1&order=";?>'+order+'<?php echo "&account={$user}&way=ali&gid=";?>'+g_id;
				}else{
					window.location.href='<?php echo WEB_URL."/api.php?app={$appid}&act=pay&order=";?>'+order+'<?php echo "&account={$user}&way=ali&gid=";?>'+g_id;
				}
				return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
			});
			
			$('#wx_submit').click(function() {
				var order = randomNumber();
				var mobile_flag = isMobile();
				if(mobile_flag){
					window.location.href='<?php echo WEB_URL."/api.php?app={$appid}&act=pay&ua=1&order=";?>'+order+'<?php echo "&account={$user}&way=wx&gid=";?>'+g_id;
				}else{
					window.location.href='<?php echo WEB_URL."/api.php?app={$appid}&act=pay&order=";?>'+order+'<?php echo "&account={$user}&way=wx&gid=";?>'+g_id;
				}
				return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
			});
			
			$('#qq_submit').click(function() {
				var order = randomNumber();
				var mobile_flag = isMobile();
				if(mobile_flag){
					window.location.href='<?php echo WEB_URL."/api.php?app={$appid}&act=pay&ua=1&order=";?>'+order+'<?php echo "&account={$user}&way=qq&gid=";?>'+g_id;
				}else{
					window.location.href='<?php echo WEB_URL."/api.php?app={$appid}&act=pay&order=";?>'+order+'<?php echo "&account={$user}&way=qq&gid=";?>'+g_id;
				}
				return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
			});
			
			function setTimeDateFmt(s) {//个位数补齐十位数
			  return s < 10 ? '0' + s : s;
			}
			function randomNumber() {
			  const now = new Date()
			  let month = now.getMonth() + 1
			  let day = now.getDate()
			  let hour = now.getHours()
			  let minutes = now.getMinutes()
			  let seconds = now.getSeconds()
			  month = setTimeDateFmt(month)
			  day = setTimeDateFmt(day)
			  hour = setTimeDateFmt(hour)
			  minutes = setTimeDateFmt(minutes)
			  seconds = setTimeDateFmt(seconds)
			  let orderCode = now.getFullYear().toString() + month.toString() + day + hour + minutes + seconds + (Math.round(Math.random() * 100000)).toString();
			  //console.log(orderCode)
			  return orderCode;
			}
			
			function isMobile() {
				var userAgentInfo = navigator.userAgent;
	 
				var mobileAgents = [ "Android", "iPhone", "SymbianOS", "Windows Phone", "iPad","iPod"];
	 
				var mobile_flag = false;
	 
				//根据userAgent判断是否是手机
				for (var v = 0; v < mobileAgents.length; v++) {
					if (userAgentInfo.indexOf(mobileAgents[v]) > 0) {
						mobile_flag = true;
						break;
					}
				}
				return mobile_flag;
			}
			
		</script>
		<div class="mt-5">
			<footer class="footer footer-alt" style="border-top:1px solid rgba(152,166,173,.15);">
				2018 - <?php echo date('Y',time());?> © <a href="http://www.eruyi.cn/" class="text-title" style="text-decoration:none" target="_blank">易如意</a> - eruyi.cn
			</footer>
		</div>
    </body>

</html>