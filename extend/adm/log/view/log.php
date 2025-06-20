<?php
/*
Sort:10
icons:mdi mdi-clipboard-text-play-outline
Hidden:false
Name:系统日志
Url:log
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问

$page=isset($_GET['page']) ? intval($_GET['page']) : 1;
$see = isset($_GET['see']) ? purge($_GET['see']) : 'all';

if($see != 'all'){
	$nums=Db::table('log')->where('`group`',$see)->count();
	$url="./?log&see={$see}&page=";
}else{
	$nums=Db::table('log')->count();//获取用户日志总数
	$url="./?log&page=";
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
						<li class="breadcrumb-item active">系统</li>
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
							<form class="form-inline">
								<select class="form-control" name="see" id="see" onchange="get_screen(this.value)">
									<option value="all" <?php if($see == 'all') echo 'selected = "selected"'; ?>>全部</option>
									<option value="user" <?php if($see == 'user') echo 'selected = "selected"'; ?>>用户日志</option>
									<option value="adm" <?php if($see == 'adm') echo 'selected = "selected"'; ?>>管理员日志</option>
								</select>
							</form>                            
						</div>
						<div class="col-lg-4">
							<div class="text-lg-right">
								<form id="sousuo" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="so" placeholder="可搜索用户、应用、操作类型" value='<?php echo $so; ?>'>
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
									<th><span class="badge badge-light-lighten">账号</span></th>
									<th style="width: 120px;"><center><span class="badge badge-light-lighten">应用名</span></center></th>
									<th style="width: 120px;"><center><span class="badge badge-light-lighten">操作类型</span></center></th>
									<th style="width: 100px;"><center><span class="badge badge-light-lighten">积分变化</span></center></th>
									<th style="width: 100px;"><center><span class="badge badge-light-lighten">VIP变化</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">操作IP</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">日志时间</span></center></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$log = Db::table('log','as LOG')->field('LOG.*,U.pic,U.user,U.email,U.phone,U.name,A.name as appname')->JOIN('app','as A','LOG.appid=A.id')->JOIN("user",'as U','LOG.uid=U.id');
									
									if($so){
										$lang = array_merge($lang_adm,$lang_user);
										$lang_val = !empty(array_search($so,$lang)) ? array_search($so,$lang) : $so;
										$log = $log->where('A.name','like',"%{$so}%")->whereOr('LOG.type','like',"%{$lang_val}%")->whereOr('U.user','like',"%{$so}%")->whereOr('U.email','like',"%{$so}%")->whereOr('U.phone','like',"%{$so}%")->whereOr('U.name','like',"%{$so}%")->order('id desc');
									}else{
										if($see != 'all'){
											$log = $log->where('LOG.group',$see);
										}
										$log = $log->order('id desc')->limit($bnums,$ENUMS);
									}
									$res = $log->select();//false
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
										<img class="mr-3 rounded-circle" src="<?php echo ($rows['group'] == 'adm') ? '../assets/images/users/avatar-1.jpg' : get_pic($rows['pic'],true);?>" width="40" alt="Generic placeholder image">
										<div class="media-body">
										
											<a href="<?php echo ($rows['group'] == 'adm') ? 'javascript:void(0);' : './?user_edit&id='.$rows['uid']; ?>" class="text-title"><h5 class="mt-0 mb-1"><?php echo ($rows['group'] == 'adm') ? $user : (!empty($rows['user']) ? $rows['user'] : (!empty($rows['email']) ? $rows['email'] : $rows['phone'])); ?></h5></a>
											<span class="font-13"><?php echo ($rows['group'] == 'adm') ? '管理员' : $rows['name']; ?></span>
										</div>
									</td>
									<td>
										<center>
											<?php echo ($rows['group'] == 'adm') ? '<span class="badge badge-light">后台操作' : '<span class="badge badge-primary">'.$rows['appname']; ?>
										</span></center>
									</td>
									<td>
										<center><?php echo ($rows['group'] == 'adm') ? $lang_adm[$rows['type']] : $lang_user[$rows['type']]; ?></center>
									</td>
									<td>
										<center><?php echo ($rows['fen'] > 0) ? '+'.$rows['fen'] : $rows['fen']; ?></center>
									</td>
									<td>
										<center><?php echo ($rows['vip'] > 0) ? '+'.$rows['vip'].(isset($time_type[$rows['type']])?$time_type[$rows['type']]:"") : $rows['vip'].(isset($time_type[$rows['type']])?$time_type[$rows['type']]:""); ?></center>
									</td>
									<td>
										<center><?php echo $rows['ip'];?></center>
									</td>
									<td>
										<center><?php echo date("Y-m-d H:i",$rows['time']);?></center>
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
	
	<script>
		function get_screen(see){
			if(see != 'all'){
				location.href='./?log&see=' + see;
			}else{
				location.href='./?log';
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
			document.getElementById('delsubmit').innerHTML="<div class=\"spinner-border spinner-border-sm mr-1\" style=\"margin-bottom:2px!important\" role=\"status\"></div>删除中";
			document.getElementById("delsubmit").className = "text-title";
			$("#delsubmit").attr("disabled",true).css("pointer-events","none"); 
			
			console.log(id_array);
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=log_del",//请求的文件名
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