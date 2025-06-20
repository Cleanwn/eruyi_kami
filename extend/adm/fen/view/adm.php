<?php
/*
Sort:1
Hidden:false
Name:积分管理
Url:fen_adm
Right:fen
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问
$nums=Db::table('fen')->count();//获取商品总数
$page=isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = './?fen_adm&page=';
$bnums=($page-1)*$ENUMS;
?>

	<!-- start page title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="javascript: void(0);">首页</a></li>
						<li class="breadcrumb-item active">积分</li>
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
							<button type="button" onclick="modal_cut('add',0,0,0,0,0,0)" class="btn btn-danger mb-2 mr-2" data-toggle="modal" data-target="#modal"><i class="mdi mdi-coins mr-1"></i>添加积分事件</button>                           
						</div>
						<div class="col-lg-4">
							<div class="text-lg-right">
								<form action="" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="so" placeholder="搜索积分事件" value='<?php echo $so; ?>'>
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
									<th style="width: 20px;"><center><span class="badge badge-light-lighten">ID</span></center></th>
									<th><span class="badge badge-light-lighten">事件名称</span></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">应用名称</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">事件类型</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">积分</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">状态</span></center></th>
									<th style="width: 75px;"><center><span class="badge badge-light-lighten">编辑</span></center></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$fen = Db::table('fen','as F')->field('F.*,A.name as appname')->JOIN('app','as A','F.appid=A.id');
									if($so){
										$fen = $fen->where('F.name','like',"%{$so}%")->whereOr('A.name','like',"%{$so}%")->order('id desc');
									}else{
										$fen = $fen->order('id desc')->limit($bnums,$ENUMS);
									}
									$res = $fen->select();//false
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
										<?php echo $rows['name']; ?>
									</td>
									<td>
										<center>
											<?php if(empty($rows['appname'])):?><span class="badge badge-danger"><i class="mdi mdi-close-circle"></i> 应用不存在
											<?php else:?><span class="badge badge-primary"><i class="mdi mdi-cube-outline"></i> <?php echo $rows['appname']; ?>
											<?php endif; ?>
										</span></center>
									</td>
									<td>
										<center><?php if($rows['vip_num']<=0):?><span class="badge badge-warning-lighten">消耗积分<?php else: ?><span class="badge badge-danger-lighten">兑换会员<?php endif; ?></span></center>
									</td>
									<td>
										<center><span class="badge badge-info-lighten">
											<?php if($rows['fen_num'] >0){echo '+'.$rows['fen_num'];}else{echo $rows['fen_num'];} ?>
										</span></center>
									</td>
									<td>
										<center><?php if($rows['state']=='n'):?><span class="badge badge-danger">禁用<?php else: ?><span class="badge badge-success">正常<?php endif; ?></span></center>
									</td>
									<td>
										<center><a href="javascript:void(0);" onclick="modal_cut('edit',<?php echo $rows['id'];?>,'<?php echo $rows['name']; ?>','<?php if($rows['vip_num']<=0){echo 'fen';}else{echo 'vip';} ?>',<?php echo $rows['fen_num'];?>,<?php echo $rows['vip_num'];?>,<?php echo $rows['appid'];?>)" class="action-icon"> <i class="mdi mdi-border-color" data-toggle="modal" data-target="#modal"></i></a></center>
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
	</div><!-- end row -->
	
	<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal_title">添加积分事件</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<?php if($app_num > 0):?>
					<form class="pl-3 pr-3" method="post">
						<input class="form-control" type="number" id="modal_id" name="modal_id" value="" placeholder="事件ID" hidden>
						<div class="form-group">
							<label class="col-form-label">积分事件名称 *</label>
							<input class="form-control" type="text" id="modal_name" name="modal_name" placeholder="事件名称" required>
						</div>
						<div class="form-row">
							<div class="form-group col-md-4">
								<label>事件类型 *</label>
								<select class="form-control" name="modal_type" id="modal_type" onchange="type_change(this.value)">
									<option value="fen">消耗积分</option>
									<option value="vip">兑换会员</option>
								</select>
							</div>
							<div class="form-group col-md-8">
								<label id="type_a">消耗积分数 *</label>
								<div class="input-group">
									<input type="number" id="modal_fen_num" name="modal_fen_num" class="form-control" placeholder="0" value="">
									<div class="input-group-prepend">
										<span class="input-group-text">积分</span>
									</div>
								</div>
							</div>
						</div>
						<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>提示 - </strong> 消耗积分填写负数，增加积分填写正数
						</div>
						<div id="modal_vip" class="form-row" hidden>
							<div class="form-group col-md-12">
								<label id="type_a">获得会员 *</label>
								<div class="input-group">
									<input type="number" id="modal_vip_num" name="modal_vip_num" class="form-control" placeholder="0" value="">
									<div class="input-group-prepend">
										<span class="input-group-text" id="type_b">小时</span>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label>绑定应用 *</label>
							<select class="form-control" name="modal_appid" id="modal_appid">
								<?php
									$res = Db::table('app')->order('id desc')->select();
									foreach ($res as $k => $v){$rows = $res[$k];
								?>
								<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						
						<div id="add" class="form-group text-center">
							<button class="btn btn-primary" type="submit" name="add_submit" id="add_submit" value="确定">确认添加</button>
						</div>
						<div id="edit" class="form-group text-center" hidden>
							<button class="btn btn-primary" type="submit" name="edit_submit" id="edit_submit" value="确定">确认编辑</button>
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
			var modal_name = $("input[name='modal_name']").val();
			var modal_fen_num = $("input[name='modal_fen_num']").val();
			var modal_vip_num = $("input[name='modal_vip_num']").val();
			var modal_appid = $("select[name='modal_appid']").val();
			document.getElementById('add_submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在添加";
			document.getElementById('add_submit').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=fen_add",//请求的文件名
				data : {
					name:modal_name,
					fen_num:modal_fen_num,
					vip_num:modal_vip_num,
					appid:modal_appid
				},
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
		
		$('#edit_submit').click(function() {
			let t = window.jQuery;
			var modal_id = $("input[name='modal_id']").val();
			var modal_name = $("input[name='modal_name']").val();
			var modal_fen_num = $("input[name='modal_fen_num']").val();
			var modal_vip_num = $("input[name='modal_vip_num']").val();
			var modal_appid = $("select[name='modal_appid']").val();
			document.getElementById('edit_submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在添加";
			document.getElementById('edit_submit').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=fen_edit",//请求的文件名
				data : {
					id:modal_id,
					name:modal_name,
					fen_num:modal_fen_num,
					vip_num:modal_vip_num,
					appid:modal_appid
				},
				dataType : 'json',
				success : function(data) {
					console.log(data);
					document.getElementById('edit_submit').disabled=false;
					document.getElementById('edit_submit').innerHTML="确认添加";
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
		
		function modal_cut(cut,id,name,type,fen,vip,appid) {
			if(cut=='add'){
				document.getElementById("modal_title").innerHTML="添加积分事件";
				document.getElementById("add").removeAttribute("hidden");
				document.getElementById("edit").setAttribute("hidden",true);
				document.getElementById("modal_name").value = '';
				document.getElementById("modal_type").value = 'fen';
				document.getElementById("modal_vip").setAttribute("hidden",true);
				document.getElementById("modal_fen_num").value = '';
				document.getElementById("modal_vip_num").value = '';
			}else{
				document.getElementById("modal_title").innerHTML="编辑积分事件";
				document.getElementById("edit").removeAttribute("hidden");
				document.getElementById("add").setAttribute("hidden",true);
				document.getElementById("modal_id").value = id;
				document.getElementById("modal_name").value = name;
				document.getElementById("modal_type").value = type;
				if(type=='vip'){
					document.getElementById("modal_vip").removeAttribute("hidden");
				}else{
					document.getElementById("modal_vip").setAttribute("hidden",true);
				}
				document.getElementById("modal_fen_num").value = fen;
				document.getElementById("modal_vip_num").value = vip;
				document.getElementById("modal_appid").value = appid;
			}
		}
		
		function type_change(i) {
			if(i=='vip'){
				document.getElementById("modal_vip").removeAttribute("hidden");
			}else{
				document.getElementById("modal_vip").setAttribute("hidden",true);
			}
		}
	
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
				url : "ajax.php?act=fen_del",//请求的文件名
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