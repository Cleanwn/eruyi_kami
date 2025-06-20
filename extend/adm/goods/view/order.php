<?php
/*
Sort:2
Hidden:false
Name:商品订单
Url:goods_order
Version:1.0
Author:易如意
Author QQ:51154393
Author Url:www.eruyi.cn
*/
if(!isset($islogin))header("Location: /");//非法访问
$page=isset($_GET['page']) ? intval($_GET['page']) : 1;
$see = isset($_GET['see']) ? intval($_GET['see']) : -1;
$appid = isset($_GET['app']) ? intval($_GET['app']) : 0;
$goods = Db::table('goods_order','as O')->field('O.*,G.appid,G.type,A.name as appname')->JOIN("goods","as G",'O.gid=G.id')->JOIN('app','as A','G.appid=A.id');
if($see >= 0 && $appid > 0){
	$nums=$goods->where('O.state',$see)->where('G.appid',$appid)->count();
	$url="./?goods_order&see={$see}&app={$appid}&page=";
}elseif($see >= 0 && $appid <= 0){
	$nums=$goods->where('O.state',$see)->count();
	$url="./?goods_order&see={$see}&page=";
}elseif($see < 0 && $appid > 0){
	$nums=$goods->where('G.appid',$appid)->count();
	$url="./?goods_order&app={$appid}&page=";
}else{
	$nums=$goods->count(); 
	$url="./?goods_order&page=";
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
						<li class="breadcrumb-item active">商品</li>
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
									<option value="-1" <?php if($see == -1) echo 'selected = "selected"'; ?>>全部</option>
									<option value="2" <?php if($see == 2) echo 'selected = "selected"'; ?>>已支付</option>
									<option value="0" <?php if($see == 0) echo 'selected = "selected"'; ?>>未支付</option>
									<option value="1" <?php if($see == 1) echo 'selected = "selected"'; ?>>充值失败</option>
								</select>
							</form>                            
						</div>
						<div class="col-lg-4">
							<div class="text-lg-right">
								<form action="" method="post">
									<div class="input-group">
										<input type="text" class="form-control" name="so" placeholder="搜索订单" value='<?php echo $so; ?>'>
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
									<th><span class="badge badge-light-lighten">订单号</span></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">应用名称</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">商品名称</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">商品类型</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">金额</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">用户账号</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">订单时间</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">订单状态</span></center></th>
									<th style="width: 150px;"><center><span class="badge badge-light-lighten">支付类型</span></center></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$goods = Db::table('goods_order','as O')->field('O.*,U.user,U.email,U.phone,G.appid,G.type,A.name as appname')->JOIN("goods","as G",'O.gid=G.id')->JOIN('app','as A','G.appid=A.id')->JOIN("user",'as U','O.Uid=U.id');
									if($so){
										$goods = $goods->where('A.name','like',"%{$so}%")->whereOr('O.order','like',"%{$so}%")->whereOr('U.user','like',"%{$so}%")->whereOr('U.email','like',"%{$so}%")->whereOr('U.phone','like',"%{$so}%")->whereOr('O.name','like',"%{$so}%")->order('id desc');
									}else{
										if($see >= 0 && $appid > 0){
											$goods = $goods->where('O.state',$see)->where('G.appid',$appid);
										}elseif($see >= 0 && $appid <= 0){
											$goods = $goods->where('O.state',$see);
										}elseif($see < 0 && $appid > 0){
											$goods = $goods->where('G.appid',$appid);
										}
										$goods = $goods->order('id desc')->limit($bnums,$ENUMS);
									}
									$res = $goods->select();//false
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
									<td>
										<?php echo $rows['order']; ?>
									</td>
									<td>
										<center><span class="badge badge-primary">
											<i class="mdi mdi-cube-outline"></i>
											<?php echo $rows['appname']; ?>
										</span></center>
									</td>
									<td>
										<center><?php echo $rows['name']; ?></center>
									</td>
									<td>
										<center><?php if($rows['type']=='fen'):?><span class="badge badge-primary">积分<?php else: ?><span class="badge badge-danger">会员<?php endif; ?></span></center>
									</td>
									<td>
										<center><span class="badge badge-info-lighten">
											<i class="mdi mdi-cash-usd"></i>
											<?php echo $rows['money']; ?>
										</span></center>
									</td>
									<td>
										<center><?php echo !empty($rows['user']) ? $rows['user'] : (!empty($rows['email']) ? $rows['email'] : $rows['phone']);?></center>
									</td>
									<td>
										<center><?php echo date("Y-m-d H:i",$rows['o_time']);?></center>
									</td>
									
									<td>
										<center>
										<?php if($rows['state']==0):?><span class="badge badge-warning-lighten">等待支付
										<?php elseif($rows['state']==1):?><span class="badge badge-danger-lighten">充值失败
										<?php elseif($rows['state']==2): ?><span class="badge badge-success-lighten">支付成功
										<?php elseif($rows['state']==3): ?><span class="badge badge-danger-lighten">未找到用户
										<?php elseif($rows['state']==4): ?><span class="badge badge-danger-lighten">未知商品类型
										<?php elseif($rows['state']==9): ?><span class="badge badge-danger-lighten">永久会员
										<?php endif; ?></span>
										</center>
									</td>
									<td>
										<center>
										<?php if($rows['p_type']=='ali'):?><span class="badge badge-info">支付宝
										<?php elseif($rows['p_type']=='wx'):?><span class="badge badge-success">微信
										<?php elseif($rows['p_type']=='qq'): ?><span class="badge badge-dark">QQ钱包<?php endif; ?></span>
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
	
	<script>
		function get_screen(appid,see){
			var url = '';
			if(appid > 0){
				url = '&app=' + appid;
			}
			if(see >= 0){
				if(url == ''){
					url = '&see=' + see;
				}else{
					url = url + '&see=' + see;
				}
			}
			location.href='./?goods_order' + url;
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
				url : "ajax.php?act=goods_o_del",//请求的文件名
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