<?php
/*
Sort:1
Hidden:false
icons:mdi mdi-cogs
Name:系统设置
Url:web_set
Version:1.0
Author：易如意
QQ：51154393
Url：www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问拦截
?>
	<!-- start page title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="javascript: void(0);">首页</a></li>
						
						<li class="breadcrumb-item active">设置</li>
					</ol>
				</div>
				<h4 class="page-title"><?php echo $title; ?></h4>
			</div> <!-- end page-title-box -->
		</div> <!-- end col-->
	</div>
	<!-- end page title -->

	<!-- end row -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<form action="" method="post" id="addimg" name="addimg">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label class="col-form-label">错误输出</label>
								<select class="form-control" name="app_debug" id="app_debug">
									<option value="0" <?php if(APP_DEBUG == 0) echo 'selected = "selected"'; ?>>关闭</option>
									<option value="1" <?php if(APP_DEBUG == 1) echo 'selected = "selected"'; ?>>开启</option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label class="col-form-label">数据输出格式</label>
								<select class="form-control" name="default_return_type" id="default_return_type">
									<option value="0" <?php if(DEFAULT_RETURN_TYPE == 0) echo 'selected = "selected"'; ?>>JSON</option>
									<option value="1" <?php if(DEFAULT_RETURN_TYPE == 1) echo 'selected = "selected"'; ?>>XML</option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_user" class="col-form-label">用户在线状态有效期</label>
								
								<div class="input-group">
									<input type="number" class="form-control" id="user_token_time" name="user_token_time" placeholder="用户在线状态有效期" value="<?php echo USER_TOKEN_TIME; ?>" required>
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">秒</span>
									</div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_user" class="col-form-label">后台管理每页数据</label>
								
								<div class="input-group">
									<input type="number" class="form-control" id="data_page_enums" name="data_page_enums" placeholder="后台管理每页数据" value="<?php echo DATA_PAGE_ENUMS; ?>" required>
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">条</span>
									</div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_user" class="col-form-label">系统时区</label> ：<a href='https://www.php.net/manual/en/timezones.php'target="_blank">查看合法时区的列表</a>
								<input type="text" class="form-control" id="default_timezone" name="default_timezone" placeholder="系统时区" value="<?php echo DEFAULT_TIMEZONE; ?>" required>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label class="col-form-label">首页模板目录</label>
								<select class="form-control" name="index_template" id="index_template">
									<?php $template_arr = myScanDir(FCPATH.'template/',1); foreach($template_arr as $value){?>
									<option value="<?php echo $value; ?>" <?php if(INDEX_TEMPLATE == $value) echo 'selected = "selected"'; ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="f_user" class="col-form-label">接口扩展目录</label>
								<input type="text" class="form-control" id="api_extend_mulu" name="api_extend_mulu" placeholder="接口扩展目录" value="<?php echo API_EXTEND_MULU; ?>" required>
							</div>
							<div class="form-group col-md-6">
								<label for="f_user" class="col-form-label">后台扩展目录</label>
								<input type="text" class="form-control" id="adm_extend_mulu" name="adm_extend_mulu" placeholder="后台扩展目录" value="<?php echo ADM_EXTEND_MULU; ?>" required>
							</div>
						</div>
						
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_user" class="col-form-label">用户头像目录</label>
								<input type="text" class="form-control" id="user_pic_mulu" name="user_pic_mulu" placeholder="用户头像目录" value="<?php echo USER_PIC_MULU; ?>" required>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label class="col-form-label">管理员操作日志</label>
								<select class="form-control" name="adm_log" id="adm_log">
									<option value="0" <?php if(ADM_LOG == 0) echo 'selected = "selected"'; ?>>关闭</option>
									<option value="1" <?php if(ADM_LOG == 1) echo 'selected = "selected"'; ?>>开启</option>
								</select>
							</div>
							<div class="form-group col-md-6">
								<label class="col-form-label">用户操作日志</label>
								<select class="form-control" name="user_log" id="user_log">
									<option value="0" <?php if(USER_LOG == 0) echo 'selected = "selected"'; ?>>关闭</option>
									<option value="1" <?php if(USER_LOG == 1) echo 'selected = "selected"'; ?>>开启</option>
								</select>
							</div>
						</div>
						
						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="f_user" class="col-form-label">日志保留</label>
								<div class="input-group">
									<input type="number" class="form-control" id="log_del" name="log_del" placeholder="日志保留天数" value="<?php echo LOG_DEL; ?>" required>
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">天</span>
									</div>
								</div>
							</div>
							<div class="form-group col-md-8">
								<label class="col-form-label">日志KEY</label>
								<div class="input-group">
									<input type="text" class="form-control" id="log_key" name="log_key" value="<?php echo LOG_KEY;?>">
									<div class="input-group-append">
										<button class="btn btn-success" type="button" id="bian_key">随机更换KEY</button>
									</div>
								</div>	
							</div>
						</div>
						<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>提示 - </strong> 自动清理日志功能需要使用《计划任务》，任务API(每天/次)：<?php echo dirname(WEB_URL).'/api.php?act=log&key=';?><span id='log_key_url'><?php echo LOG_KEY;?></span>
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
			</div> <!-- end card-->
		</div> <!-- end col -->
	</div>

	<script>
		$('#submit').click(function() {
			let t = window.jQuery;
			var app_debug = $("select[name='app_debug']").val();
			var default_return_type = $("select[name='default_return_type']").val();
			var user_token_time = $("input[name='user_token_time']").val();
			var data_page_enums = $("input[name='data_page_enums']").val();
			var default_timezone = $("input[name='default_timezone']").val();
			var index_template = $("select[name='index_template']").val();
			var api_extend_mulu = $("input[name='api_extend_mulu']").val();
			var adm_extend_mulu = $("input[name='adm_extend_mulu']").val();
			var user_pic_mulu = $("input[name='user_pic_mulu']").val();
			var adm_log = $("select[name='adm_log']").val();
			var user_log = $("select[name='user_log']").val();
			var log_del = $("input[name='log_del']").val();
			var log_key = $("input[name='log_key']").val();
			var ok = document.getElementById("ok").checked;
			if(!ok){
				t.NotificationApp.send("提示","请确认是我操作","top-center","rgba(0,0,0,0.2)","warning")
				return false;
			}
			document.getElementById('submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在修改";
			document.getElementById('submit').disabled=true;
			
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=web_set",//请求的文件名
				data : {
					app_debug:app_debug,
					default_return_type:default_return_type,
					user_token_time:user_token_time,
					data_page_enums:data_page_enums,
					default_timezone:default_timezone,
					index_template:index_template,
					api_extend_mulu:api_extend_mulu,
					adm_extend_mulu:adm_extend_mulu,
					user_pic_mulu:user_pic_mulu,
					adm_log:adm_log,
					user_log:user_log,
					log_del:log_del,
					log_key:log_key
				},
				dataType : 'json',
				success : function(data) {
					console.log(data);
					document.getElementById('submit').disabled=false;
					document.getElementById('submit').innerHTML="确认修改";
					if(data.code == 200){
						document.getElementById('log_key_url').innerHTML=log_key;
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
		
		$('#bian_key').click(function() {
			var log_key=randomString(32);
			$("#log_key").val(log_key);
		});
		
		function randomString(len) {
			len = len || 32;
			var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
			var maxPos = $chars.length;
			var pwd = '';
			for (i = 0; i < len; i++) {
				pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
			}
			return pwd;
		}
	</script>