<?php
/*
Sort:2
Hidden:false
icons:mdi mdi-account-edit
Name:修改密码
Url:web_edit
Version:1.0
Author：易如意
QQ：51154393
Url：www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问拦截
?>						

	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="javascript: void(0);">首页</a></li>
						<li class="breadcrumb-item active">修改管理员账号密码</li>
					</ol>
				</div>
				<h4 class="page-title">管理员信息</h4>
			</div> <!-- end page-title-box -->
		</div> <!-- end col-->
	</div>
	<!-- end page title -->
	<!-- Form row -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<form action="" method="post" id="addimg" name="addimg">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_user" class="col-form-label">账号</label>
								<input type="text" class="form-control" id="username" name="username" placeholder="用户账号" value="<?php echo $user; ?>" required>
							</div>
							
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_psw" class="col-form-label">确认密码</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="空则不修改密码" value="">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_psw" class="col-form-label">确认密码</label>
								<input type="password" class="form-control" id="okpassword" name="okpassword" placeholder="空则不修改密码" value="">
							</div>
						</div>
						
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="ok" name="ok" required>
								<label class="custom-control-label" for="ok">确认是我操作</label>
							</div>
						</div>
						<button type="submit" class="btn btn-block btn-primary" id="submit" value="确认">确认修改</button>

					</form>

				</div> <!-- end card-body -->
			</div> <!-- end card-->
		</div> <!-- end col -->
	</div><!-- end row -->
	
	<script> 
		$('#submit').click(function() {
			let t = window.jQuery;
			var user = $("input[name='username']").val();
			var pwd = $("input[name='password']").val();
			var okpwd = $("input[name='okpassword']").val();
			var ok = document.getElementById("ok").checked;
			//console.log(okpwd);
			if(!ok){
				t.NotificationApp.send("提示","请确认是我操作","top-center","rgba(0,0,0,0.2)","warning")
				return false;
			}
			document.getElementById('submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在修改";
			document.getElementById('submit').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=web_pswd",//请求的文件名
				data : {user:user,pwd:pwd,okpwd:okpwd},
				dataType : 'json',
				success : function(data) {
					console.log(data);
					document.getElementById('submit').disabled=false;
					document.getElementById('submit').innerHTML="确认修改";
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

	</script>					