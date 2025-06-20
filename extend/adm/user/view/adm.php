<?php
/*
Sort:1
Hidden:false
Name:用户管理
Url:user_adm
Right:user
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问
$nums=Db::table('user')->count();//获取用户总数
$page=isset($_GET['page']) ? intval($_GET['page']) : 1;
$url="./?user_adm&page=";
$bnums=($page-1)*$ENUMS;
?>

	<!-- start page title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="javascript: void(0);">首页</a></li>
						<li class="breadcrumb-item active">用户</li>
					</ol>
				</div>
				<h4 class="page-title"><?php echo $title; ?></h4>
			</div> <!-- end page-title-box -->
		</div> <!-- end col-->
	</div>
	<!-- end page title -->

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row mb-2">
						<div class="col-lg-8">
							<button type="button" class="btn btn-danger mb-2 mr-2" data-toggle="modal" data-target="#add"><i class="mdi mdi-account-multiple-plus mr-1"></i>添加用户</button>                           
						</div>
						<div class="col-lg-4">
							<div class="text-lg-right">
								<form action="" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="so" placeholder="搜索用户" value='<?php echo $so; ?>'>
										<span class="mdi mdi-magnify"></span>
										<div class="input-group-append">
											<button class="btn btn-primary" type="submit">搜索</button>
										</div>
									</div>
								</form>
							</div>
						</div><!-- end col-->
					</div>
					<form action="" method="post" name="form_log" id="form_log">
					<div class="table-responsive">
						<table class="table table-centered table-striped dt-responsive nowrap w-100">
							<thead>
								<tr>
									<th style="width: 20px;">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="all" onclick="checkAll();">
											<label class="custom-control-label" for="all">&nbsp;</label>
										</div>
									</th>
									<th style="width: 20px;"><center><span class="badge badge-light-lighten">UID</span></center></th>
									<th><span class="badge badge-light-lighten">账号</span></th>
									<th style="width: 120px;"><center><span class="badge badge-light-lighten">用户组</span></center></th>
									<th style="width: 120px;"><center><span class="badge badge-light-lighten">应用名</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">注册时间</span></center></th>
									<th style="width: 100px;"><center><span class="badge badge-light-lighten">在线设备</span></center></th>
									<th style="width: 100px;"><center><span class="badge badge-light-lighten">账号状态</span></center></th>
									<th style="width: 75px;"><center><span class="badge badge-light-lighten">管理</span></center></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$user = Db::table('user','as U')->field('U.id,U.pic,U.email,U.user,U.phone,U.name as uname,U.vip,U.reg_time,U.ban,A.name as appname,IFNULL(L.zx,0) as zai')->JOIN('app','as A','U.appid=A.id')->JOIN("(SELECT uid,COUNT(*) AS zx FROM `{$DP}user_logon` where `last_t` > {$UTT} GROUP BY uid) AS L",'U.id=L.uid');
									if($so != ''){
										$user = $user->where('A.name','like',"%{$so}%")->whereOr('U.id','like',"%{$so}%")->whereOr('U.appid','like',"%{$so}%")->whereOr('U.user','like',"%{$so}%")->whereOr('U.email','like',"%{$so}%")->whereOr('U.phone','like',"%{$so}%")->whereOr('U.name','like',"%{$so}%")->order('id desc');
									}else{
										$user = $user->order('id desc')->limit($bnums,$ENUMS);
									}
									$res = $user->select();//false
									//die($res);
									foreach ($res as $k => $v){$rows = $res[$k];
								?>
								<tr>
									<td>
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" name="ids[]" value="<?php echo $rows['id']; ?>" id="<?php echo 'check_'.$rows['id']; ?>">
											<label class="custom-control-label" for="<?php echo 'check_'.$rows['id']; ?>"></label>
										</div>
									</td>
									<td>
										<center><?php echo $rows['id']; ?></center>
									</td>
									<td class="media">
										<img class="mr-3 rounded-circle" src="<?php echo get_pic($rows['pic'],true);?>" width="40" alt="Generic placeholder image">
										<div class="media-body">
											<a href="user_edit.php?id=<?php echo $rows['id']; ?>" class="text-title"><h5 class="mt-0 mb-1"><?php echo !empty($rows['user']) ? $rows['user'] : (!empty($rows['email']) ? $rows['email'] : $rows['phone']);?></h5></a>
											<span class="font-13"><?php echo $rows['uname'];?></span>
										</div>
									</td>
									<td>
										<center>
										<?php if($rows['vip']>time() || $rows['vip']=='999999999'): ?><span class="badge badge-danger">会员用户<?php else: ?><span class="badge badge-light">普通用户<?php endif; ?></span>
										</center>
									</td>
									<td>
										<center>
											<?php if(empty($rows['appname'])):?><span class="badge badge-danger"><i class="mdi mdi-close-circle"></i> 应用不存在
											<?php else:?><span class="badge badge-primary"><i class="mdi mdi-cube-outline"></i> <?php echo $rows['appname']; ?>
											<?php endif; ?>
										</span></center>
									</td>
									<td>
										<center><span class="badge badge-secondary-lighten">
										<?php echo date("Y-m-d H:i",$rows['reg_time']);?>
										</span></center>
									</td>
									<td>
										<center><?php echo $rows['zai'];?></center>
									</td>
									<td>
										<center><?php if($rows['ban']>0):?><span class="badge badge-danger">禁封<?php else: ?><span class="badge badge-success">正常<?php endif; ?></span></center>
									</td>
									<td>
										<center><a href="./?user_edit&id=<?php echo $rows['id']; ?>" class="action-icon"> <i class="mdi mdi-border-color"></i></a></center>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="progress-w-percent-s"></div>
					<div class="form-row">
						<div class="form-group col-md-6 mt-2">
							<div class="col-sm-4">
								<div class="list_footer">
									选中项：<a href="javascript:void(0);" onclick="delsubmit()" id="delsubmit">删除</a>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<nav  aria-label="Page navigation example">
								<ul class="pagination justify-content-end">
									<?php if(!$so){echo pagination($nums,$ENUMS,$page,$url);}?>
								</ul>
							</nav>
						</div>
					</div>
					</form>
				</div> <!-- end card-body-->
			</div> <!-- end card-->
		</div> <!-- end col -->
	</div>
	<!-- end row -->

	<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="add">添加用户</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<?php if($app_num > 0):?>
					<form class="pl-3 pr-3" method="post">
						<div class="form-group">
							<label class="col-form-label">账号</label>
							<input class="form-control" type="text" id="add_user" name="add_user" placeholder="账号" required>
						</div>
						<div class="form-group">
							<label>密码</label>
							<input class="form-control" type="text" id="add_pwd" name="add_pwd" placeholder="密码" required>
						</div>
						
						<div class="form-group">
							<label>绑定应用 *</label>
							<select class="form-control" name="add_appid" id="add_appid">
								<?php
									$res = Db::table('app')->order('id desc')->select();
									foreach ($res as $k => $v){$rows = $res[$k];
								?>
								<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						
						<div class="form-group text-center">
							<button class="btn btn-primary" type="submit" name="add_submit" id="add_submit" value="确定">确认添加</button>
						</div>

					</form>
					<?php else:?>
					<div class="text-center" style="margin-top:4rem!important;margin-bottom:6rem!important">
						<img src="../assets/images/no-app.svg" height="120" alt="File not found Image">
						<h4 class="text-uppercase mt-3 mb-3">需要添加应用后开启该功能</h4>
						<a href="./?app_adm"><button type="button" class="btn btn-dark">添加应用</button></a>
					</div>
					<?php endif;?>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script>
		$('#add_submit').click(function() {
			let t = window.jQuery;
			var add_user = $("input[name='add_user']").val();
			var add_pwd = $("input[name='add_pwd']").val();
			var add_appid = $("select[name='add_appid']").val();
			document.getElementById('add_submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在添加";
			document.getElementById('add_submit').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=user_add",//请求的文件名
				data : {pwd:add_pwd,user:add_user,appid:add_appid},
				dataType : 'json',
				success : function(data) {
					console.log(data);
					document.getElementById('add_submit').disabled=false;
					document.getElementById('add_submit').innerHTML="确认添加";
					if(data.code == 200){
						t.NotificationApp.send("成功",data.msg,"top-center","rgba(0,0,0,0.2)","success")
						window.setTimeout("window.location='"+window.location.href+"'",1000);
					}else{
						t.NotificationApp.send("失败",data.msg,"top-center","rgba(0,0,0,0.2)","error")
					}
				}
			});
			return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
		});

		function checkAll() {
			var code_Values = document.getElementsByTagName("input");
			var all = document.getElementById("all");
			if (code_Values.length) {
				for (i = 0; i < code_Values.length; i++) {
					if (code_Values[i].type == "checkbox") {
						code_Values[i].checked = all.checked;
					}
				}
			} else {
				if (code_Values.type == "checkbox") {
					code_Values.checked = all.checked;
				}
			}
		}
		function delsubmit(){
			var id_array=new Array();  
			$("input[name='ids[]']:checked").each(function(){  
				id_array.push($(this).val());//向数组中添加元素  
			});  //获取界面复选框的所有值
			//ar chapterstr = id_array.join(',');//把复选框的值以数组形式存放
			var url = window.location.href;
			let t = window.jQuery;
			if(id_array.length<=0){
				t.NotificationApp.send("提示","请选择要删除的项目","top-center","rgba(0,0,0,0.2)","warning")
				return false;
			}
			document.getElementById('delsubmit').innerHTML="<div class=\"spinner-border spinner-border-sm mr-1\" style=\"margin-bottom:2px!important\" role=\"status\"></div>删除中";
			document.getElementById("delsubmit").className = "text-title";
			$("#delsubmit").attr("disabled",true).css("pointer-events","none"); 
			
			console.log(id_array);
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=user_del",//请求的文件名
				data : {id:id_array},
				dataType : 'json',
				success : function(data) {
					if(data.code == 200){
						t.NotificationApp.send("成功",data.msg,"top-center","rgba(0,0,0,0.2)","success")
					}else{
						t.NotificationApp.send("失败",data.msg,"top-center","rgba(0,0,0,0.2)","error")
					}
					//console.log(data);
					window.setTimeout("window.location='"+url+"'",1000);
				}
			});
			return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
		}
	</script>