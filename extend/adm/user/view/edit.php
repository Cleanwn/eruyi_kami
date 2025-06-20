<?php
/*
Sort:1
Hidden:true
Name:用户编辑
Url:user_edit
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = Db::table('user','as U')->field('U.*,A.name as appname,IFNULL(L.zx,0) as zai')->JOIN('app','as A','U.appid=A.id')->JOIN("(SELECT uid,COUNT(*) AS zx FROM `{$DP}user_logon` where `last_t` > {$UTT} GROUP BY uid) AS L",'U.id=L.uid')->where('U.id',$id)->find();

?>						
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="javascript: void(0);">首页</a></li>
						<li class="breadcrumb-item"><a href="user_adm.php">用户管理</a></li>
						<li class="breadcrumb-item active">编辑用户</li>
					</ol>
				</div>
				<h4 class="page-title"><?php echo $title; ?></h4>
			</div> <!-- end page-title-box -->
		</div> <!-- end col-->
	</div>
	<!-- end page title -->
	
	<div class="row">
		<div class="col-sm-12">
			<!-- Profile -->
			<div class="card">
				<div class="card-body profile-user-box">

					<div class="row">
						<div class="col-sm-8">
							<div class="media">
								<span class="float-left m-2 mr-4"><img src="<?php echo get_pic($res['pic'],true);?>" style="height: 100px;" alt="" class="rounded-circle img-thumbnail"></span>
								<div class="media-body">
									<h4 class="mt-2 mb-1"><?php echo $res['name'];?></h4>
									<p class="font-13">账号：<?php echo !empty($res['user']) ? $res['user'] : (!empty($res['email']) ? $res['email'] : $res['phone']);?>
									<br><?php if($res['zai'] >0):?><span class="badge badge-success-lighten">在线<?php else:?><span class="badge badge-dark-lighten">离线<?php endif; ?></span> 
										<span class="badge badge-primary-lighten"><?php echo $res['appname']; ?></span> 
										<?php if($res['inv'] > 0):?><span class="badge badge-info-lighten">邀请人ID:<?php echo $res['inv']; ?><?php else:?><span class="badge badge-info-lighten">无邀请人<?php endif; ?></span> 
									</p>
									<ul class="mb-0 list-inline">
										<li class="list-inline-item mr-3">
											<h5 class="mb-1"><?php echo $res['reg_ip'];?></h5>
											<p class="mb-0 font-13">注册IP</p>
										</li>
										<li class="list-inline-item">
											<h5 class="mb-1"><?php echo date("Y/m/d H:i:s",$res['reg_time']);?></h5>
											<p class="mb-0 font-13">注册时间</p>
										</li>
										
									</ul>
								</div> <!-- end media-body-->
							</div>
						</div> <!-- end col-->

						<div class="col-sm-4">
							<div class="text-center mt-sm-0 mt-3 text-sm-right">
								<div class="mt-2">
									<?php if($res['openid_wx']):?>
										<div id="wx" class="dropdown list-inline-item text-center">
											<a href="#" class="dropdown-toggle arrow-none card-drop social-list-item border-primary" data-toggle="dropdown" aria-expanded="false">
												<img src="../assets/images/logon-ico/wx.png" class="eruyi-img-login-2"></li>
											</a>
											<div class="dropdown-menu dropdown-menu-right" >
												<!-- item-->
												<a href="javascript:void(0);" onclick="undo_wx()" class="dropdown-item">解绑微信</a>
											</div>
										</div>
									<?php endif; ?>
									<?php if($res['openid_qq']):?>
										<div id="qq" class="dropdown list-inline-item text-center">
											<a href="#" class="dropdown-toggle arrow-none card-drop social-list-item border-danger" data-toggle="dropdown" aria-expanded="false">
												<img src="../assets/images/logon-ico/qq.png" class="eruyi-img-login-2"></li>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<!-- item-->
												<a href="javascript:void(0);" onclick="undo_qq()" class="dropdown-item">解绑QQ</a>
											</div>
										</div>
										
									<?php endif; ?>
								</div>
								
							</div>
						</div> <!-- end col-->
					</div> <!-- end row -->
				</div> <!-- end card-body/ profile-user-box-->
			</div><!--end profile/ card -->
		</div> <!-- end col-->
	</div><!-- end row-->
	
	<div class="row">
		<div class="col-md-7">
			<div class="card">
				<div class="card-body">
					<form action="" method="post" id="addimg" name="addimg">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label>用户密码</label>
								<input name="pwd" id="pwd" type="text" class="form-control" placeholder="空则不修改密码" value="">
							</div>
							
							<div class="form-group col-md-6" hidden>
								<label>微信绑定</label>
								<input name="openid_qq" id="openid_qq" type="text" class="form-control" value="<?php echo $res['openid_qq'];?>">
							</div>
							<div class="form-group col-md-6" hidden>
								<label>QQ绑定</label>
								<input name="openid_wx" id="openid_wx" type="text" class="form-control" value="<?php echo $res['openid_wx'];?>">
							</div>
							<div class="form-group col-md-6">
								<label>会员到期</label>
								<input name="vip" type="text" class="form-control" data-toggle="input-mask" data-mask-format="0000/00/00 00:00:00" placeholder="输入格式：<?php echo date("Y/m/d H:i:s",time());?>"<?php if($res['vip']=='999999999'): ?>value="<?php echo $res['vip'].'99999';?>"<?php elseif($res['vip']>time()): ?> value="<?php echo date("Y/m/d H:i:s",$res['vip']);?>"<?php endif; ?>>
								<span class="font-13 text-muted">永久会员格式 "9999/99/99"</span>
							</div>
							<div class="form-group col-md-6">
								<label>积分</label>
								<input name="fen" type="number" class="form-control" placeholder="0" value="<?php echo $res['fen'];?>" required>
							</div>
						</div>
					</form>
				</div> <!-- end card-body -->
			</div> <!-- end card-->
			
			<div class="card">
				<div class="card-body">
					<form>
						<div class="eruyi-checkbox">
						
							<input type="checkbox" id="ban_state" <?php if($res['ban'] > time() or $res['ban'] == 999999999):?>checked<?php endif; ?> data-switch="danger" onchange="ban_state_v(this.checked)"/>
							<label for="ban_state" data-on-label="禁用" data-off-label="正常" ></label>
							<label class="eruyi-label">用户控制</label>
						</div>
						<div class="view" name="ban_state_y" id="ban_state_y" <?php if($res['ban']> time() or $res['ban'] == 999999999):?> style="display: block" <?php endif; ?>>
							<p class="text-muted">
								用户禁用后，该用户将 <code>禁止所有操作</code> 
							</p>
							<div class="form-row">
								<div class="form-group col-md-4">
									<label>禁用到期</label>
									<input name="ban" type="text" class="form-control" data-toggle="input-mask" data-mask-format="0000/00/00 00:00:00" placeholder="输入格式：<?php echo date("Y/m/d H:i:s",time());?>" <?php if($res['ban']=='999999999'): ?>value="<?php echo $res['ban'].'99999';?>"<?php elseif($res['ban']>time()): ?> value="<?php echo date("Y/m/d H:i:s",$res['ban']);?> <?php endif; ?>>
									<span class="font-13 text-muted">永久禁用格式 "9999/99/99"</span>
								</div>
								<div class="form-group col-md-8">
									<label>禁用通知</label>
									<input name="ban_notice" type="text" class="form-control" placeholder="禁用通知" value="<?php echo $res['ban_notice'];?>">
								</div>
							</div>
							
						</div>
						<div class="view" name="ban_state_n" id="ban_state_n" <?php if($res['ban']==0 or $res['ban'] < time()):?> style="display: block" <?php endif; ?>>
							<p class="text-muted">
								当前用户状态 <code>正常</code> ，可以使用软件
							</p>
							
						</div>
						
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="ok" name="ok" value="y" required>
								<label class="custom-control-label" for="ok">确认是我操作</label>
							</div>
						</div>
						<button type="submit" class="btn btn-block btn-primary" name="submit" id="submit" value="确认">确认修改</button>
					</form>

				</div> <!-- end card-body -->
			</div> <!-- end card -->
		</div> <!-- end col -->
		
		<div class="col-md-5">
			
			<div class="card">
				<div class="card-body">
					<div class="dropdown float-right">
						<a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
							<i class="mdi mdi-dots-vertical"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<!-- item-->
							<a href="javascript:void(0);" onclick="see('log')" class="dropdown-item">用户日志</a>
							<!-- item-->
							<a href="javascript:void(0);" onclick="see('user_logon')" class="dropdown-item">用户设备</a>
						</div>
					</div>
					<h4 class="header-title mb-3" id="see_name">用户日志</h4>
					<div class="table-responsive" name="user_log" id="user_log">
						<?php 
							$res_log = Db::table('log')->where(['uid'=>$id])->order('id desc')->limit(0,5)->select();
							if(count($res_log)<=0):
						?>
						<div class="text-center" style="margin-top:6rem!important;margin-bottom:6rem!important">
							<img src="../assets/images/startman.svg" height="120" alt="File not found Image">
							<h4 class="text-uppercase mt-3">暂无用户日志</h4>
						</div>
						<?php else:?>
						<div class="table-responsive">
							<table class="table table-striped table-sm table-centered mb-0">
								<thead>
									<tr>
										<th><span class="badge badge-light-lighten">操作类型</span></th>
										<th><span class="badge badge-light-lighten">操作IP/操作时间</span></th>
										<th style="width: 100px;"><span class="badge badge-light-lighten">会员/积分变化</span></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($res_log as $k => $v){$rows_log = $res_log[$k];?>
									<tr>
										<td><?php echo $lang_user[$rows_log['type']];?></td>
										
										<td>
											<h5 class="font-15 mb-1 font-weight-normal"><?php echo $rows_log['ip'];?></h5>
											<span class="text-muted font-13"><?php echo date("Y/m/d H:i",$rows_log['time']);?></span>
										</td>
										<td>
											<h5 class="font-15 mb-1 font-weight-normal">会员：<?php echo ($rows_log['vip'] > 0) ? '+'.$rows_log['vip'].(isset($time_type[$rows_log['type']])?$time_type[$rows_log['type']]:"") : $rows_log['vip'].(isset($time_type[$rows_log['type']])?$time_type[$rows_log['type']]:""); ?></h5>
											<span class="text-muted font-13">积分：<?php if($rows_log['fen'] >0){echo '+'.$rows_log['fen'];}else{echo $rows_log['fen'];} ?></span>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div> <!-- end table-responsive-->	
						<?php endif; ?>
					</div> <!-- end table-responsive-->		
					<div class="table-responsive" name="user_logon" id="user_logon" style="display: none">
						<?php 
							$res_logon = Db::table('user_logon')->where(['uid'=>$id])->order('last_t desc')->limit(0,5)->select();
						if(count($res_logon)<=0):
						?>
						<div class="text-center" style="margin-top:6rem!important;margin-bottom:6rem!important">
							<img src="../assets/images/startman.svg" height="120" alt="File not found Image">
							<h4 class="text-uppercase mt-3">暂无用户设备</h4>
						</div>
						<?php else:?>
						<div class="table-responsive">
							<table class="table table-striped table-sm table-centered mb-0">
								<thead>
									<tr>
										<th><span class="badge badge-light-lighten">TOKEN/设备信息</span></th>
										<th style="width: 100px;"><span class="badge badge-light-lighten">最后活动时间/登录IP</span></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($res_logon as $k => $v){$rows_logon = $res_logon[$k];?>
									<tr>
										<td>
											<h5 class="font-15 mb-1 font-weight-normal"><?php echo $rows_logon['token'];?></h5>
											<span class="text-muted font-13"><?php if($rows_logon['log_in']==''){echo '无设备信息';}else{echo $rows_logon['log_in'];}?></span>
										</td>
										<td>
											<h5 class="font-15 mb-1 font-weight-normal"><?php echo date("Y/m/d H:i",$rows_logon['last_t']);?></h5>
											<span class="text-muted font-13"><?php echo $rows_logon['log_ip'];?></span>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div> <!-- end table-responsive-->	
						<?php endif; ?>
					</div> <!-- end table-responsive-->	
				</div> <!-- end card-body-->
			</div> <!-- end card-->
		</div><!-- end col-->
	</div><!-- end row-->

	<script> 
		$("#user_adm").addClass("active");
		$('#submit').click(function() {
			let t = window.jQuery;
			var ok = document.getElementById("ok").checked;
			var ban_state = document.getElementById("ban_state").checked;
			
			var pwd = $("input[name='pwd']").val();
			var fen = $("input[name='fen']").val();
			var vip = $("input[name='vip']").val();
			var ban = $("input[name='ban']").val();
			var ban_notice = $("input[name='ban_notice']").val();
			var openid_qq = $("input[name='openid_qq']").val();
			var openid_wx = $("input[name='openid_wx']").val();
			
			
			var myDate = new Date();
			if(vip.substr(0,10)=='9999/99/99'){
				vip = '999999999';
			}else{
				vip = Date.parse(vip)/1000;
			}
			if(isNaN(vip)){
				vip = 0;
			}
			
			if(ban_state){
				if(ban.substr(0,10)=='9999/99/99'){
					ban = '999999999';
				}else{
					ban = Date.parse(ban)/1000;
				}
			}else{
				ban = 0;
			}
			
			if(isNaN(ban) && ban_state){
				t.NotificationApp.send("提示","禁用时间有误","top-center","rgba(0,0,0,0.2)","warning")
				return false;
			}
			//console.log(vip,ban);
			if(!ok){
				t.NotificationApp.send("提示","请确认是我操作","top-center","rgba(0,0,0,0.2)","warning")
				return false;
			}
			document.getElementById('submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在修改";
			document.getElementById('submit').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=user_edit",//请求的文件名
				data : {
					id:<?php echo $id;?>,
					pwd:pwd,
					fen:fen,
					vip:vip,
					ban:ban,
					ban_notice:ban_notice,
					openid_qq:openid_qq,
					openid_wx:openid_wx
				},
				dataType : 'json',
				success : function(data) {
					console.log(data);
					document.getElementById('submit').disabled=false;
					document.getElementById('submit').innerHTML="确认修改";
					if(data.code == 200){
						t.NotificationApp.send("成功",data.msg,"top-center","rgba(0,0,0,0.2)","success")
						document.getElementById("ok").checked=false;
						//window.setTimeout("window.location='"+window.location.href+"'",1000);
					}else{
						t.NotificationApp.send("失败",data.msg,"top-center","rgba(0,0,0,0.2)","error")
					}
				}
			});
			return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
		});
		function ban_state_v(i) {
			//console.log(i);
			if(i==true){
				$("#ban_state_y").css("display", "block");
				$("#ban_state_n").css("display", "none");
			}else{
				$("#ban_state_y").css("display", "none");
				$("#ban_state_n").css("display", "block");
			}
		}
		
		function see(i) {
			if(i=='log'){
				document.getElementById('see_name').innerHTML="用户日志";
				$("#user_log").css("display", "block");
				$("#user_logon").css("display", "none");
			}else{
				document.getElementById('see_name').innerHTML="用户设备";
				$("#user_log").css("display", "none");
				$("#user_logon").css("display", "block");
			}
		}
		
		function undo_qq() {
			$("#qq").css("display", "none");
			$("#openid_qq").val('');
		}
		
		function undo_wx() {
			$("#wx").css("display", "none");
			$("#openid_wx").val('');
		}
		
	</script>						