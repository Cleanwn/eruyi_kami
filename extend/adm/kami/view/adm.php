<?php
/*
Sort:1
Hidden:false
Name:卡密管理
Url:kami_adm
Right:kami
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问

$page=isset($_GET['page']) ? intval($_GET['page']) : 1;
$see = isset($_GET['see']) ? intval($_GET['see']) : 0;
$appid = isset($_GET['app']) ? intval($_GET['app']) : 0;
if($see > 0 && $appid > 0){
	if($see == 1){
		$nums=Db::table('kami')->where('appid',$appid)->where('use_time',0)->count();
	}else{
		$nums=Db::table('kami')->where('appid',$appid)->where('use_time','>',0)->count();
	}
	$url="./?kami_adm&see={$see}&app={$appid}&page=";
}elseif($see > 0 && $appid <= 0){
	if($see == 1){
		$nums=Db::table('kami')->where('use_time',0)->count();
	}else{
		$nums=Db::table('kami')->where('use_time','>','0')->count();
	}
	$url="./?kami_adm&see={$see}&page=";
}elseif($see <= 0 && $appid > 0){
	$nums=Db::table('kami')->where('appid',$appid)->count();
	$url="./?kami_adm&app={$appid}&page=";
}else{
	$nums=Db::table('kami')->count(); 
	$url="./?kami_adm&page=";
}
$bnums=($page-1)*$ENUMS;
?>

	<!-- start page title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="javascript: void(0);">首页</a></li>
						<li class="breadcrumb-item active">卡密</li>
					</ol>
				</div>
				<h4 class="page-title"><?php echo $title; ?></h4>
			</div> <!-- end page-title-box -->
		</div> <!-- end col-->
	</div>
   
	<!-- end row-->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row mb-2">
						<div class="col-lg-8">
							<form class="form-inline">
								<select class="form-control" name="appid" id="appid" onchange="get_screen(this.value,<?php echo $see;?>)">
									<option value="0">全部</option>
									<?php
										$res = Db::table('app')->order('id desc')->select();
										foreach ($res as $k => $v){$rows = $res[$k];
									?>
									<option value="<?php echo $rows['id']; ?>" <?php if($appid == $rows['id']) echo 'selected = "selected"'; ?>><?php echo $rows['name']; ?></option>
									<?php } ?>
								</select>
								<label for="status-select" class="mr-2"></label>
								<select class="form-control" name="see" id="see" onchange="get_screen(<?php echo $appid;?>,this.value)">
									<option value="0" <?php if($see == 0) echo 'selected = "selected"'; ?>>全部</option>
									<option value="1" <?php if($see == 1) echo 'selected = "selected"'; ?>>未使用</option>
									<option value="2" <?php if($see == 2) echo 'selected = "selected"'; ?>>已使用</option>
								</select>
							</form>                            
						</div>
						<div class="col-lg-4">
							<div class="text-lg-right">
								<form id="sousuo" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="so" placeholder="输入卡密" value='<?php echo $so; ?>'>
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
									<th style="width: 200px;"><span class="badge badge-light-lighten">卡密</span></th>
									<th><span class="badge badge-light-lighten">备注</span></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">类型</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">使用者</span></center></th>
									<th style="width: 200px;"><center><span class="badge badge-light-lighten">使用时间</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">导出状态</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">应用名称</span></center></th>
									<th style="width: 75px;"><center><span class="badge badge-light-lighten">状态</span></center></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$kami = Db::table('kami','as K')->field('K.*,A.name as appname')->JOIN('app','as A','K.appid=A.id');
									if($so){
										$kami = $kami->where('K.kami','like',"%{$so}%")->whereOr('K.note','like',"%{$so}%")->whereOr('k.user','like',"%{$so}%")->whereOr('A.name','like',"%{$so}%")->order('id desc');
									}else{
										if($see > 0 && $appid > 0){
											if($see == 1){
												$kami = $kami->where('K.appid',$appid)->where('K.use_time',"0");
											}else{
												$kami = $kami->where('K.appid',$appid)->where('K.use_time','>',"0");
											}
										}elseif($see > 0 && $appid <= 0){
											if($see == 1){
												$kami = $kami->where('K.use_time',"0");
											}else{
												$kami = $kami->where('K.use_time','>',"0");
											}
										}elseif($see <= 0 && $appid > 0){
											$kami = $kami->where('K.appid',$appid);
										}
										$kami = $kami->order('id desc')->limit($bnums,$ENUMS);
									}
									$res = $kami->select();//false
									//die($sql.$see);
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
										<?php echo $rows['kami']; ?>
									</td>
									<td>
										<?php
											if($rows['note']==''){
												echo "<a href=\"javascript:void(0);\" onclick=\"note_id(".$rows['id'].",'".$rows['note']."')\"> <span class=\"badge badge-light\" data-toggle=\"modal\" data-target=\"#note-modal\"><i class=\"mdi mdi-comment-processing\"></i> 未备注</span></a>";
											}else{
												echo "<a href=\"javascript:void(0);\" onclick=\"note_id(".$rows['id'].",'".$rows['note']."')\"> <span class=\"badge badge-info\" data-toggle=\"modal\" data-target=\"#note-modal\"><i class=\"mdi mdi-comment-processing\"></i> ".$rows['note']."</span></a>";
											}
										?>
									</td>
									<td>
										<center><?php if($rows['type']=='fen'):?><span class="badge badge-warning-lighten">积分卡[<?php echo $rows['amount']; ?>]<?php else: ?><span class="badge badge-danger-lighten">会员卡[ <?php if($rows['amount'] == 999999999){echo '永久卡';}else{ echo $rows['amount'].'天';} ?> ]<?php endif; ?></span></center>
									</td>
									<td>
										<center>
										<?php if($rows['user']):?><span class="badge badge-warning-lighten">
										<?php echo $rows['user']; else: ?><span class="badge badge-success-lighten">未绑定<?php endif; ?></span>
										</center>
									</td>
									<td>
										<center>
										<?php if($rows['use_time']==0):?><span class="badge badge-info-lighten">未使用
										<?php else: ?><span class="badge badge-warning-lighten"><?php echo date("Y-m-d H:i",$rows['use_time']); endif; ?></span>
										</center>
									</td>
									<td>
										<center>
										<?php if($rows['new']=='n'):?><span class="badge badge-dark-lighten">未导出
										<?php else: ?><span class="badge badge-dark">已导出<?php endif; ?></span>
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
										<center>
										<?php if($rows['state']=='y'):?><a href="javascript:void(0);" onclick="edit_state(<?php echo $rows['id']; ?>,'<?php echo $rows['state'];?>')"><span id="state_<?php echo $rows['id']; ?>" class="badge badge-success">正常
										<?php else:?><a href="javascript:void(0);" onclick="edit_state(<?php echo $rows['id']; ?>,'<?php echo $rows['state'];?>')"><span id="state_<?php echo $rows['id']; ?>" class="badge badge-danger">禁用
										<?php endif; ?></span></a>
										</center>
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

	<div id="note-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<div class="text-center mt-2 mb-4"></div>
					<form class="pl-3 pr-3" action="" name="note_log" id="note_log" method="post">
						<div class="form-group">
							<input class="form-control" type="text" style="display:none" id="kid" name="kid" value="" ><!--style="display:none" --> 
							<label for="username">备注</label>
							<input class="form-control" type="text" id="note" name="note" required="" placeholder="备注内容">
						</div>
						<div class="form-group text-center">
							<button class="btn btn-primary" type="submit" name="submit_note" id="submit_note" value="确认">确认备注</button>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script>
		$('#submit_note').click(function() {
			let t = window.jQuery;
			var note = $("input[name='note']").val();
			var kid = $("input[name='kid']").val();
			document.getElementById('submit_note').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在备注";
			document.getElementById('submit_note').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=kami_note",//请求的文件名
				data : {
					note:note,
					kid:kid
				},
				dataType : 'json',
				success : function(data) {
					console.log(data);
					document.getElementById('submit_note').disabled=false;
					document.getElementById('submit_note').innerHTML="确认备注";
					if(data.code == 200){
						t.NotificationApp.send("成功",data.msg,"top-center","rgba(0,0,0,0.2)","success");
						window.setTimeout("window.location='"+window.location.href+"'",1000);
					}else{
						t.NotificationApp.send("失败",data.msg,"top-center","rgba(0,0,0,0.2)","error");
					}
				}
			});
			return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
		});
		function note_id(i,s) {
			var kid=document.getElementById("kid");
			var note=document.getElementById("note");
			kid.value= i;
			note.value= s;
		}
		
		function get_screen(appid,see){
			var url = '';
			if(appid > 0){
				url = '&app=' + appid;
			}
			if(see > 0){
				if(url == ''){
					url = '&see=' + see;
				}else{
					url = url + '&see=' + see;
				}
			}
			location.href='./?kami_adm' + url;
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
		
		function edit_state(id,state) {
			let t = window.jQuery;
			var badge = document.getElementById("state_"+id).className;
			if(badge == 'badge badge-danger'){
				state = 'y'
				document.getElementById("state_"+id).className = "badge badge-success";
				document.getElementById("state_"+id).innerHTML="正常";
			}else{
				state = 'n'
				document.getElementById("state_"+id).className = "badge badge-danger";
				document.getElementById("state_"+id).innerHTML="禁用";
			}
			//console.log(badge);
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=kami_state",//请求的文件名
				data : {id:id,state:state},
				dataType : 'json',
				success : function(data) {
					if(data.code == 200){
						t.NotificationApp.send("成功",data.msg,"top-center","rgba(0,0,0,0.2)","success")
					}else{
						t.NotificationApp.send("失败",data.msg,"top-center","rgba(0,0,0,0.2)","error")
					}
				}
			});
			return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
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
				url : "ajax.php?act=kami_del",//请求的文件名
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