<?php
/*
Sort:1
Hidden:true
icons:mdi mdi-cogs
Name:文件更新
Url:web_file
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
						
						<li class="breadcrumb-item active">文件更新</li>
					</ol>
				</div>
				<h4 class="page-title"><?php echo $title; ?></h4>
			</div> <!-- end page-title-box -->
		</div> <!-- end col-->
	</div>
	<!-- end page title -->

	<!-- end row -->
	<div class="row">
		<div class="col-12" id="file_list">
			<div class="card" id="loading">
				<div class="card-body" style="margin-top:8rem!important;margin-bottom:8rem!important">        
					<center><div class="spinner-grow text-danger" role="status"></div></center>
					<center><p>正在加载中</p></center>
				</div><!--end card-body-->
			</div><!--end card-->
		</div>  <!--end col -->
	</div>
	
	<script>
		function file_submit(submit,url,home,mulu,filename){
			if(submit.indexOf("file_new") != -1){
				document.getElementById(submit).innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在更新";
			}else if(submit.indexOf("file_md5") != -1){
				document.getElementById(submit).innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在覆盖";
			}else if(submit.indexOf("file_lose") != -1){
				document.getElementById(submit).innerHTML="<span class=\"spinner-border spinner-border-sm mr-1\" role=\"status\" aria-hidden=\"true\"></span>正在下载";
			}
			document.getElementById(submit).disabled=true;
			//console.log(url,home,mulu,filename);
			let t = window.jQuery;
			$.ajax({
				cache: false,
				type: "POST",//请求的方式
				url : "ajax.php?act=web_downfile",//请求的文件名
				data : {fileurl:url,home:home,mulu:mulu,filename:filename},
				dataType : 'json',
				success : function(data) {
					if(data.code == 200){
						if(submit.indexOf("file_new") != -1){
							document.getElementById(submit).innerHTML="<i class=\"mdi mdi-check-all mr-1\"></i>更新成功";
						}else if(submit.indexOf("file_md5") != -1){
							document.getElementById(submit).innerHTML="<i class=\"mdi mdi-check-all mr-1\"></i>覆盖成功";
						}else if(submit.indexOf("file_lose") != -1){
							document.getElementById(submit).innerHTML="<i class=\"mdi mdi-check-all mr-1\"></i>下载成功";
						}
						t.NotificationApp.send("成功",data.msg,"top-center","rgba(0,0,0,0.2)","success")
					}else{
						if(submit.indexOf("file_new") != -1){
							document.getElementById(submit).innerHTML="<i class=\"mdi mdi-check-all mr-1\"></i>立即更新";
						}else if(submit.indexOf("file_md5") != -1){
							document.getElementById(submit).innerHTML="<i class=\"mdi mdi-check-all mr-1\"></i>立即覆盖";
						}else if(submit.indexOf("file_lose") != -1){
							document.getElementById(submit).innerHTML="<i class=\"mdi mdi-check-all mr-1\"></i>立即下载";
						}
						document.getElementById(submit).disabled=false;
						t.NotificationApp.send("失败",data.msg,"top-center","rgba(0,0,0,0.2)","error")
					}
					//console.log(data);
				}
			});
			return false;//重要语句：如果是像a链接那种有href属性注册的点击事件，可以阻止它跳转。
		}
	
		$.ajax({
			cache: false,
			type: "GET",//请求的方式
			url : "edition.php",//请求的文件名
			dataType : 'json',
			success : function(data) {
				document.getElementById("loading").setAttribute("hidden",true);
				if(data.code == 200){
					var json_data = false;
					if(data.msg.file_new.length > 0){
						json_data = true;
						$("#file_list").append('<div class="card" id="file_new"><div class="card-body" id="file_new_home"><h4 class="mt-0 mb-3">需要更新的文件（'+data.msg.file_new.length+'）</h4></div></div>');
						for(var i in data.msg.file_new){//遍历json数组时，这么写p为索引，0,1
							var mulu = data.msg.file_new[i].mulu;
							if(mulu.indexOf("/") != -1){
								mulu = mulu.replace('/', '_');
							}
							if(mulu.indexOf("/") != -1){
								mulu = mulu.replace('/','');
							}
							if(!document.getElementById("file_new_"+data.msg.file_new[i].home+"_"+mulu)){
								$("#file_new_home").append("<div class=\"media mt-2\" id=\"file_new_home_list\"><img class=\"mr-3 avatar-sm\" src=\"../assets/images/Folder.png\" style=\"width:2rem!important;height:2rem!important\"><div class=\"media-body\"><h6 class=\"mt-0\">文件夹</h5>路径：extend/" +data.msg.file_new[i].home + "/"+data.msg.file_new[i].mulu+"<div id=\"file_new_" + data.msg.file_new[i].home + "_" + mulu + "\"><div class=\"media mt-3\"><a class=\"pr-3\"><img src=\"../assets/images/phpfile.png\" class=\"avatar-sm\" style=\"width:2rem!important;height:2rem!important\"></a><div class=\"media-body\"><h5 class=\"mt-0\">"+data.msg.file_new[i].nav+"</h5>文件名："+data.msg.file_new[i].file+"&nbsp;&nbsp;&nbsp;&nbsp;版本："+data.msg.file_new[i].version+"<br>文件MD5："+data.msg.file_new[i].md5+"</div><div class=\"text-right\"><div class=\"btn-group mb-2 ml-2\"><button id=\"file_new_submit_"+i+"\" onclick=\"file_submit('file_new_submit_"+i+"','"+data.msg.file_new[i].fileurl+"','"+data.msg.file_new[i].home+"','"+data.msg.file_new[i].mulu+"','"+data.msg.file_new[i].file+"')\" type=\"button\" class=\"btn btn-success btn-sm\">立即更新</button></div></div></div></div></div></div>");
							}else{
								$("#file_new_"+data.msg.file_new[i].home+"_"+mulu).append("<div class=\"media mt-3\"><a class=\"pr-3\"><img src=\"../assets/images/phpfile.png\" class=\"avatar-sm\" style=\"width:2rem!important;height:2rem!important\"></a><div class=\"media-body\"><h5 class=\"mt-0\">" + data.msg.file_new[i].nav + "</h5>文件名：" + data.msg.file_new[i].file + "&nbsp;&nbsp;&nbsp;&nbsp;版本：" + data.msg.file_new[i].version + "<br>文件MD5：" + data.msg.file_new[i].md5 + "</div><div class=\"text-right\"><div class=\"btn-group mb-2 ml-2\"><button id=\"file_new_submit_"+i+"\" onclick=\"file_submit('"+data.msg.file_new[i].fileurl+"','"+data.msg.file_new[i].home+"','"+data.msg.file_new[i].mulu+"','"+data.msg.file_new[i].file+"')\" type=\"button\" class=\"btn btn-success btn-sm\">立即更新</button></div></div></div>");
							}
						}
					}
					if(data.msg.file_md5.length > 0){
						json_data = true;
						$("#file_list").append('<div class="card" id="file_md5"><div class="card-body" id="file_md5_home"><h4 class="mt-0 mb-3">被修改的文件（'+ data.msg.file_md5.length +'）</h4></div></div>');
						for(var i in data.msg.file_md5){//遍历json数组时，这么写p为索引，0,1
							var mulu = data.msg.file_md5[i].mulu;
							if(mulu.indexOf("/") != -1){
								mulu = mulu.replace('/', '_');
							}
							if(mulu.indexOf("/") != -1){
								mulu = mulu.replace('/','');
							}
							if(!document.getElementById("file_md5_"+data.msg.file_md5[i].home+"_"+mulu)){
								$("#file_md5_home").append("<div class=\"media mt-2\" id=\"file_md5_home_list\"><img class=\"mr-3 avatar-sm\" src=\"../assets/images/Folder.png\" style=\"width:2rem!important;height:2rem!important\"><div class=\"media-body\"><h6 class=\"mt-0\">文件夹</h5>路径：extend/" +data.msg.file_md5[i].home + "/"+data.msg.file_md5[i].mulu+"<div id=\"file_md5_" + data.msg.file_md5[i].home + "_" + mulu + "\"><div class=\"media mt-3\"><a class=\"pr-3\"><img src=\"../assets/images/phpfile.png\" class=\"avatar-sm\" style=\"width:2rem!important;height:2rem!important\"></a><div class=\"media-body\"><h5 class=\"mt-0\">"+data.msg.file_md5[i].nav+"</h5>文件名："+data.msg.file_md5[i].file+"&nbsp;&nbsp;&nbsp;&nbsp;版本："+data.msg.file_md5[i].version+"<br>文件MD5："+data.msg.file_md5[i].md5+"</div><div class=\"text-right\"><div class=\"btn-group mb-2 ml-2\"><button id=\"file_md5_submit_"+i+"\" onclick=\"file_submit('file_md5_submit_"+i+"','"+data.msg.file_md5[i].fileurl+"','"+data.msg.file_md5[i].home+"','"+data.msg.file_md5[i].mulu+"','"+data.msg.file_md5[i].file+"')\" type=\"button\" class=\"btn btn-dark btn-sm\">立即覆盖</button></div></div></div></div></div></div>");
							}else{
								$("#file_md5_"+data.msg.file_md5[i].home+"_"+mulu).append("<div class=\"media mt-3\"><a class=\"pr-3\"><img src=\"../assets/images/phpfile.png\" class=\"avatar-sm\" style=\"width:2rem!important;height:2rem!important\"></a><div class=\"media-body\"><h5 class=\"mt-0\">" + data.msg.file_md5[i].nav + "</h5>文件名：" + data.msg.file_md5[i].file + "&nbsp;&nbsp;&nbsp;&nbsp;版本：" + data.msg.file_md5[i].version + "<br>文件MD5：" + data.msg.file_md5[i].md5 + "</div><div class=\"text-right\"><div class=\"btn-group mb-2 ml-2\"><button id=\"file_md5_submit_"+i+"\" onclick=\"file_submit('file_md5_submit_"+i+"','"+data.msg.file_md5[i].fileurl+"','"+data.msg.file_md5[i].home+"','"+data.msg.file_md5[i].mulu+"','"+data.msg.file_md5[i].file+"')\" type=\"button\" class=\"btn btn-dark btn-sm\">立即覆盖</button></div></div></div>");
							}
							
						}
					}
					if(data.msg.file_lose.length > 0){
						json_data = true;
						$("#file_list").append('<div class="card" id="file_lose"><div class="card-body" id="file_lose_home"><h4 class="mt-0 mb-3">缺失的文件（'+data.msg.file_lose.length+'）</h4></div></div>');
						for(var i in data.msg.file_lose){//遍历json数组时，这么写p为索引，0,1
							var mulu = data.msg.file_lose[i].mulu;
							if(mulu.indexOf("/") != -1){
								mulu = mulu.replace('/', "_");
							}
							if(mulu.indexOf("/") != -1){
								mulu = mulu.replace('/','');
							}
							if(!document.getElementById("file_lose_"+data.msg.file_lose[i].home+"_"+mulu)){
								$("#file_lose_home").append("<div class=\"media mt-2\" id=\"file_lose_home_list\"><img class=\"mr-3 avatar-sm\" src=\"../assets/images/Folder.png\" style=\"width:2rem!important;height:2rem!important\"><div class=\"media-body\"><h6 class=\"mt-0\">文件夹</h5>路径：extend/" +data.msg.file_lose[i].home + "/"+data.msg.file_lose[i].mulu+"<div id=\"file_lose_" + data.msg.file_lose[i].home + "_" + mulu + "\"><div class=\"media mt-3\"><a class=\"pr-3\"><img src=\"../assets/images/phpfile.png\" class=\"avatar-sm\" style=\"width:2rem!important;height:2rem!important\"></a><div class=\"media-body\"><h5 class=\"mt-0\">"+data.msg.file_lose[i].nav+"</h5>文件名："+data.msg.file_lose[i].file+"&nbsp;&nbsp;&nbsp;&nbsp;版本："+data.msg.file_lose[i].version+"<br>文件MD5："+data.msg.file_lose[i].md5+"</div><div class=\"text-right\"><div class=\"btn-group mb-2 ml-2\"><button id=\"file_lose_submit_"+i+"\" onclick=\"file_submit('file_lose_submit_"+i+"','"+data.msg.file_lose[i].fileurl+"','"+data.msg.file_lose[i].home+"','"+data.msg.file_lose[i].mulu+"','"+data.msg.file_lose[i].file+"')\" type=\"button\" class=\"btn btn-danger btn-sm\">立即下载</button></div></div></div></div></div></div>");
							}else{
								$("#file_lose_"+data.msg.file_lose[i].home+"_"+mulu).append("<div class=\"media mt-3\"><a class=\"pr-3\"><img src=\"../assets/images/phpfile.png\" class=\"avatar-sm\" style=\"width:2rem!important;height:2rem!important\"></a><div class=\"media-body\"><h5 class=\"mt-0\">" + data.msg.file_lose[i].nav + "</h5>文件名：" + data.msg.file_lose[i].file + "&nbsp;&nbsp;&nbsp;&nbsp;版本：" + data.msg.file_lose[i].version + "<br>文件MD5：" + data.msg.file_lose[i].md5 + "</div><div class=\"text-right\"><div class=\"btn-group mb-2 ml-2\"><button id=\"file_lose_submit_"+i+"\" onclick=\"file_submit('file_lose_submit_"+i+"','"+data.msg.file_lose[i].fileurl+"','"+data.msg.file_lose[i].home+"','"+data.msg.file_lose[i].mulu+"','"+data.msg.file_lose[i].file+"')\" type=\"button\" class=\"btn btn-danger btn-sm\">立即下载</button></div></div></div>");
							}
							
						}
					}
					if(json_data == false){
						$("#file_list").append('<div class="card"><div class="text-center" style="margin-top:8rem!important;margin-bottom:8rem!important"><img src="../assets/images/normal.svg" height="80" alt="File not found Image"><h4 class="text-uppercase mt-3">你的系统非常棒，没有任何问题</h4></div></div>');
					}
				}else{
					$("#file_list").append('<div class="card"><div class="text-center" style="margin-bottom:8rem!important"><img src="../assets/images/upgrade.svg" width="100%" alt="File not found Image"><h4 class="text-uppercase mt-3 mb-4">当前程序需要升级至'+data.msg.edition+'</h4><a href="'+data.msg.new_url+'" target="_blank"><button type="button" class="btn btn-danger">立即升级</button></a></div></div>');
				}
			},
			error:function(xhr,state,errorThrown){
				document.getElementById("loading").setAttribute("hidden",true);
				$("#file_list").append('<div class="card"><div class="text-center" style="margin-top:8rem!important;margin-bottom:8rem!important"><img src="../assets/images/maintenance.svg" height="120" alt="File not found Image"><h4 class="text-uppercase mt-3 mb-4">服务器数据读取获失败，请刷新重试</h4><a href="./?web_file"><button type="button" class="btn btn-dark">立即刷新</button></a></div></div>');
			}
		});
		
		
	</script>