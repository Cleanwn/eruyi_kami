<?php
/*
Sort:3
Hidden:false
Name:应用公告
Url:app_notice
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问

if(Db::table('app_notice')->exist()){//判断数据表是否存在
	$nums=Db::table('app_notice')->count();
	$page=isset($_GET['page']) ? intval($_GET['page']) : 1;
	$url="./?app_notice&page=";
	$bnums=($page-1)*$ENUMS;
}else{
	$sql = "CREATE TABLE IF NOT EXISTS `{$DP}app_notice` (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
	  `content` varchar(255) NOT NULL COMMENT '内容',
	  `appid` varchar(255) NOT NULL COMMENT '应用id',
	  `time` int(10) NOT NULL COMMENT '时间戳',
	  `adm` varchar(255) NOT NULL COMMENT '发布人',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
	$res = Db::establish($sql);
	if($res){
		echo "<script>location.href='./?app_notice';</script>";
	}
}
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
					<?php if($app_num > 0):?>
					<textarea class="form-control form-control-light mb-2" placeholder="请输入要发布的内容" id="add_content" name="add_content" rows="3"></textarea>
					<div class="row mt-2">
						<div class="col-lg-8">
							<form class="form-inline">
								<select class="form-control" name="add_appid" id="add_appid">
									<?php
										$res = Db::table('app')->order('id desc')->select();
										foreach ($res as $k => $v){$rows = $res[$k];
									?>
									<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name']; ?></option>
									<?php } ?>
								</select>
							</form>                            
						</div>
						<div class="col-lg-4">
							<div class="text-lg-right">
								<div class="btn-group ml-2">
									<button type="button" class="btn btn-primary btn-sm" name="add_submit" id="add_submit">发布公告</button>
								</div>
							</div>
						</div><!-- end col-->
					</div>
					<?php else:?>
					<div class="text-center" style="margin-top:6rem!important;margin-bottom:10rem!important">
						<img src="../assets/images/no-app.svg" height="120" alt="File not found Image">
						<h4 class="text-uppercase mt-3 mb-3">需要添加应用后开启该功能</h4>
						<a href="./?app_adm"><button type="button" class="btn btn-dark">添加应用</button></a>
					</div>
					<?php endif;?>
				</div> <!-- end card-body-->
			</div> <!-- end card-->
		</div> <!-- end col -->
	</div>
	<!-- end row -->
	<?php
		$res = Db::table('app_notice','as N')->field('N.*,A.name as appname')->JOIN('app','as A','N.appid=A.id')->order('id desc')->limit($bnums,$ENUMS)->select();
		foreach ($res as $k => $v){$rows = $res[$k];
	?>
	<div class="row">
		<div class="col-12">
			<div class="card mb-0 mt-2">
				<div class="card-body">
					<div class="media">
						<img src="../assets/images/users/avatar-1.jpg" alt="image" class="mr-3 d-none d-sm-block avatar-sm rounded-circle">
						<div class="media-body">
							<h5 class="mb-1 mt-0"><?php echo $rows['adm']; ?></h5>
							<p><span class="badge badge-light"><?php echo date("Y-m-d H:i:s",$rows['time']); ?></span> 
							<?php if(empty($rows['appname'])):?><span class="badge badge-danger-lighten"><i class="mdi mdi-close-circle"></i> 应用不存在
							<?php else:?><span class="badge badge-primary-lighten"><i class="mdi mdi-cube-outline"></i> <?php echo $rows['appname']; ?>
							<?php endif; ?>
							
							</p>
							<p class="mb-0 text-muted">
								<span class="font-italic"><?php echo $rows['content']; ?></span>
							</p>
						</div> <!-- end media-body -->
						<a id="delsubmit" href="javascript:void(0);" onclick="del(<?php echo $rows['id']; ?>)" class="dropdown-toggle arrow-none card-drop">
							<i class="mdi mdi-delete"></i>
						</a>
					</div> <!-- end media -->
				</div> <!-- end card-body -->
			</div> <!-- end col -->
		</div> <!-- end col -->
	</div><!-- end row -->
	<?php } ?>
	
	<div class="mb-0 mt-2">
		<nav aria-label="Page navigation example">
			<ul class="pagination justify-content-end">
				<?php if(!$so){echo pagination($nums,$ENUMS,$page,$url);}?>
			</ul>
		</nav>
	</div> <!-- end card-->
	
	<script>
		$('#add_submit').click(function() {
			let t = window.jQuery;
			var add_name = $("input[name='exten_name']").val();
			var add_content = $("textarea[name='add_content']").val();
			var add_appid = $("select[name='add_appid']").val();
			document.getElementById('add_submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在添加";
			document.getElementById('add_submit').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=app_notice_add",//请求的文件名
				data : {
					adm:add_name,
					content:add_content,
					appid:add_appid
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
		
		function del(id){
			let t = window.jQuery;
			document.getElementById("delsubmit").innerHTML="<div class=\"spinner-border spinner-border-sm mr-1\" style=\"margin-bottom:2px!important\" role=\"status\"></div>";
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=app_notice_del",//请求的文件名
				data : {id:id},
				dataType : 'json',
				success : function(data) {
					if(data.code == 200){
						t.NotificationApp.send("成功",data.msg,"top-center","rgba(0,0,0,0.2)","success")
					}else{
						t.NotificationApp.send("失败",data.msg,"top-center","rgba(0,0,0,0.2)","error")
					}
					//console.log(data);
					window.setTimeout("window.location='"+window.location.href+"'",1000);
				}
			});
			return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
		}
	</script>