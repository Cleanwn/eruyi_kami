<?php
/*
Sort:1
Hidden:false
Name:应用管理
Url:app_adm
Right:app
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/

if(!isset($islogin))header("Location: /");//非法访问
$nums = Db::table('app')->count();//获取用户总数
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = "./?app_adm&page=";
$bnums = ($page-1)*$ENUMS;
?>

	<!-- start page title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="javascript: void(0);">首页</a></li>
						<li class="breadcrumb-item active">应用</li>
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
							<button type="button" class="btn btn-danger mb-2 mr-2" data-toggle="modal" data-target="#add"><i class="mdi mdi-cube-outline mr-1"></i>添加应用</button>                           
						</div>
						<div class="col-lg-4">
							<div class="text-lg-right">
								<form action="" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="so" placeholder="搜索应用名称、ID" value='<?php echo $so; ?>'>
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
									<th style="width: 20px;"><center><span class="badge badge-light-lighten">APPID</span></center></th>
									<th><span class="badge badge-light-lighten">应用名</span></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">版本号</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">用户量</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">签到数</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">在线数</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">模式</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">状态</span></center></th>
									<th style="width: 75px;"><center><span class="badge badge-light-lighten">管理</span></center></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$app = Db::table('app','as A')->field('A.id,A.name,A.state,A.mode,A.app_bb,IFNULL(U.us,0) as unum,IFNULL(Q.qs,0) as qnum,IFNULL(L.zx,0) as znum')->JOIN("(SELECT appid,COUNT(*) AS us FROM {$DP}user GROUP BY appid) AS U",'A.id=U.appid')->JOIN("(SELECT appid,COUNT(*) AS qs FROM {$DP}log where `type` = 'clock' GROUP BY appid) AS Q",'A.id=Q.appid')->JOIN("(SELECT appid,COUNT(*) AS zx FROM {$DP}user_logon where `last_t` > {$UTT} GROUP BY appid) AS L",'A.id=L.appid');
									if($so){
										$app = $app->where('A.id','like',"%{$so}%")->whereOr('A.name','like',"%{$so}%")->whereOr('A.appkey','like',"%{$so}%")->order('id desc');
									}else{
										$app = $app->order('id desc')->limit($bnums,$ENUMS);
									}
									$res = $app->select();//false
									//die($sql);
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
									<td>
										<span class="badge badge-primary">
											<i class="mdi mdi-cube-outline"></i>
											<?php echo $rows['name']; ?>
										</span>
									</td>
									<td>
										<center><span class="badge badge-primary">
											
											<?php echo $rows['app_bb']; ?>
										</span></center>
									</td>
									<td>
										<center><span class="badge badge-secondary-lighten">
											<i class="mdi mdi-account"></i>
											<?php echo $rows['unum']; ?>
										</span></center>
									</td>
									<td>
										<center><span class="badge badge-secondary-lighten">
											<i class="mdi mdi-calendar-check-outline"></i>
											<?php echo $rows['qnum']; ?>
										</span></center>
									</td>
									<td>
										<center><span class="badge badge-secondary-lighten">
											<i class="mdi mdi-airplane"></i>
											<?php echo $rows['znum']; ?>
										</span></center>
									</td>
									<td>
										<center><?php if($rows['mode']=='n'):?>
										<span class="badge badge-success-lighten">免费
										<?php else: ?><span class="badge badge-danger-lighten">收费
										<?php endif; ?></span></center>
									</td>
									<td>
										<center><?php if($rows['state']=='n'):?><span class="badge badge-danger">关闭<?php else: ?><span class="badge badge-success">正常<?php endif; ?></span></center>
									</td>
									<td>
										<center><a href="./?app_edit&id=<?php echo $rows['id']; ?>" class="action-icon"> <i class="mdi mdi-border-color"></i></a></center>
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
					<h4 class="modal-title" id="add">添加应用</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<form class="pl-3 pr-3" method="post">
						<div class="form-group">
							<label class="col-form-label">应用名称</label>
							<input class="form-control" type="text" id="add_name" name="add_name" placeholder="应用名称" required>
						</div>
						<div class="form-group">
							<label>应用版本</label>
							<input class="form-control" type="number" id="add_bb" name="add_bb" placeholder="1.0" value="" required>
						</div>
						<?php if($app_num > 0):?>
						<div class="form-group">
							<label>继承应用设置</label>
							<select class="form-control" name="add_appid" id="add_appid">
								<option value="null">不继承</option>
								<?php
									$res = Db::table('app')->order('id desc')->select();
									foreach ($res as $k => $v){$rows = $res[$k];
								?>
								<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<?php endif;?>
						<div class="form-group text-center">
							<button class="btn btn-primary" type="submit" name="add_submit" id="add_submit" value="确定">确认添加</button>
						</div>
					</form>

				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script>
		$('#add_submit').click(function() {
			let t = window.jQuery;
			var add_name = $("input[name='add_name']").val();
			var add_bb = $("input[name='add_bb']").val();
			var add_appid = $("select[name='add_appid']").val();
			document.getElementById('add_submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在添加";
			document.getElementById('add_submit').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=app_add",//请求的文件名
				data : {bb:add_bb,name:add_name,appid:add_appid},
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
			document.getElementById("delsubmit").innerHTML="<div class=\"spinner-border spinner-border-sm mr-1\" style=\"margin-bottom:2px!important\" role=\"status\"></div>删除中";
			document.getElementById("delsubmit").className = "text-title";
			$("#delsubmit").attr("disabled",true).css("pointer-events","none"); 
			
			console.log(id_array);
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=app_del",//请求的文件名
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