<?php
/*
Sort:1
Hidden:true
Name:商品编辑
Url:goods_edit
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = Db::table('goods')->where(['id'=>$id])->find();
?>						
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="javascript: void(0);">首页</a></li>
						<li class="breadcrumb-item"><a href="goods_adm.php">商品管理</a></li>
						<li class="breadcrumb-item active">编辑商品</li>
					</ol>
				</div>
				<h4 class="page-title"><?php echo $title; ?></h4>
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
						<div class="eruyi-checkbox">
							<input type="checkbox" id="state" <?php if($res['state']=='y'):?>checked<?php endif; ?> data-switch="success" onchange="state_v(this.checked)"/>
							<label for="state" data-on-label="正常" data-off-label="停售" ></label>
							<label class="eruyi-label">商品状态</label>
						</div>
						
						<div class="view" name="state_y" id="state_y" <?php if($res['state']=='y'):?> style="display: block" <?php endif; ?>>
							<p class="text-muted">
								正常状态情况下，可以被 <code>商品列表接口</code> 正常输出
							</p>
						</div>	
						
						<div class="view" name="state_n" id="state_n" <?php if($res['state']=='n'):?> style="display: block" <?php endif; ?>>
							<p class="text-muted">
								停售状态情况下，<code>商品列表接口</code> 不在输出此商品
							</p>
						</div>
						
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_user" class="col-form-label">商品名称 *</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="商品名称" value="<?php echo $res['name']; ?>" required>
							</div>
						</div>
						
						<div class="form-row">
							<div class="form-group col-md-2">
								<label class="col-form-label">商品类型</label>
								<select class="form-control" name="type" id="type" onchange="type_change()">
									<option value="vip" <?php if($res['type']=='vip') echo 'selected = "selected"'; ?>>会员</option>
									<option value="fen" <?php if($res['type']=='fen') echo 'selected = "selected"'; ?>>积分</option>
								</select>
							</div>
							<div class="form-group col-md-10">
								<label class="col-form-label" id="amount_name">&nbsp;<?php if($res['type']=='vip'):?>会员天数 *<?php else: ?>积分数 *<?php endif; ?></label>
								<div class="input-group">
									<input type="number" id="amount" name="amount" class="form-control" placeholder="<?php echo $res['amount']; ?>" value="<?php echo $res['amount']; ?>" required>
									<div class="input-group-prepend">
										<span class="input-group-text" id="amount_a"><?php if($res['type']=='vip'):?>天<?php else: ?>积分<?php endif; ?></span>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_psw" class="col-form-label">商品金额 *</label>
								<input type="number" class="form-control" id="money" name="money" placeholder="商品金额" value="<?php echo $res['money']; ?>" required>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_psw" class="col-form-label">商品介绍</label>
								<textarea id="jie" name="jie" class="form-control" maxlength="70" rows="4" placeholder="给商品做个介绍或者备注，可空"><?php echo $res['jie']; ?></textarea>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="f_vip" class="col-form-label">绑定应用 *</label>
								<select class="form-control" name="appid" id="appid">
									<?php
										$app_res = Db::table('app')->order('id desc')->select();
										foreach ($app_res as $k => $v){$rows = $app_res[$k];
									?>
									<option value="<?php echo $rows['id']; ?>" <?php if($res['appid'] == $rows['id']) echo 'selected = "selected"'; ?>><?php echo $rows['name']; ?></option>
									<?php } ?>
									
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="ok" name="ok" value="y" required>
								<label class="custom-control-label" for="ok">确认是我操作</label>
							</div>
						</div>
						<button type="submit" class="btn btn-block btn-primary" name="submit" id="submit" value="确认">确认编辑</button>
					</form>

				</div> <!-- end card-body -->
			</div> <!-- end card-->
		</div> <!-- end col -->
	</div><!-- end row -->
	
	<script>
		$("#goods_adm").addClass("active");
		$('#submit').click(function() {
			let t = window.jQuery;
			var ok = document.getElementById("ok").checked;
			var state = document.getElementById("state").checked;
			if(state){
				state = 'y';
			}else{
				state = 'n';
			}
			var name = $("input[name='name']").val();
			var type = $("select[name='type']").val();
			var amount = $("input[name='amount']").val();
			var money = $("input[name='money']").val();
			var jie = $("textarea[name='jie']").val();
			var appid = $("select[name='appid']").val();
			if(!ok){
				t.NotificationApp.send("提示","请确认是我操作","top-center","rgba(0,0,0,0.2)","warning")
				return false;
			}
			document.getElementById('submit').innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在编辑";
			document.getElementById('submit').disabled=true;
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=goods_edit",//请求的文件名
				data : {
					id:<?php echo $id;?>,
					name:name,
					type:type,
					amount:amount,
					money:money,
					jie:jie,
					state:state,
					appid:appid
				},
				dataType : 'json',
				success : function(data) {
					console.log(data);
					document.getElementById('submit').disabled=false;
					document.getElementById('submit').innerHTML="确认添加";
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
		
		function type_change() {
			if($('#type').val()=='vip'){
				document.getElementById('amount_name').innerHTML="&nbsp;会员天数 *";
				document.getElementById('amount_a').innerHTML="天";
			}else{
				document.getElementById('amount_name').innerHTML="&nbsp;积分数 *";
				document.getElementById('amount_a').innerHTML="积分";
			}
		}
		
		function state_v(i) {
			if(i==true){
				$("#state_y").css("display", "block");
				$("#state_n").css("display", "none");
			}else{
				$("#state_y").css("display", "none");
				$("#state_n").css("display", "block");
			}
		}
	</script>							