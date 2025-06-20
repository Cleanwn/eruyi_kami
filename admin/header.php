<?php
/*
* File：页头
* Author：易如意
* QQ：51154393
* Url：www.eruyi.cn
*/
require_once 'globals.php';
$so = isset($_POST['so']) ? purge($_POST['so']) : '';
?>

<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title; ?> - 后台管理 - 易如意网络验证</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
        <!-- App favicon -->
        <link rel="icon" href="../assets/images/favicon.ico">
        <!-- App css -->
        <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/eruyi.min.css" rel="stylesheet" type="text/css" />
		<script src="../assets/js/modal_dialog.js"></script>
    </head>

    <body>
        <!-- Topbar Start -->
        <div class="navbar-custom topnav-navbar">
            <div class="container-fluid">
                <!-- LOGO -->
                <a href="index.php" class="topnav-logo">
                    <span class="topnav-logo-lg">
                        <img src="../assets/images/logo-light.png" alt="" height="16">                    
					</span>
                    <span class="topnav-logo-sm">
                        <img src="../assets/images/logo_sm.png" alt="" height="16">                    
					</span>                
				</a>

                <ul class="list-unstyled topbar-right-menu float-right mb-0">
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="account-user-avatar"> 
                                <img src="../assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle">                            
							</span>
                            <span>
                                <span class="account-user-name">管理员</span>
                                <span class="account-position"><?php echo $user;?></span>                            
							</span>                        
						</a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                            <!-- item-->
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>
							<!-- item-->
							<?php foreach($web as $val){if($val['hidden'] == 'true')continue;?>
							<a href="./?<?php echo $val['file']; ?>" class="dropdown-item notify-item">
                                <i class="<?php echo $val['icons']; ?>"></i>
                                <span><?php echo $val['name']; ?></span>                            
							</a>
							<?php }?>
                            <!-- item-->
                            <a href="./?action=logout" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-arrow-right mr-1"></i>
                                <span>退出登入</span>                            
							</a>
                        </div>
                    </li>
                </ul>
				<a class="button-menu-mobile disable-btn">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
            </div>
        </div>
        <!-- end Topbar -->
		
        <div class="container-fluid">
            <!-- Begin page -->
            <div class="wrapper">
                <!-- ============================================================== -->
                <!-- Start Page Content here -->
                <!-- ============================================================== -->
                <!-- Start Content-->
                <!-- ========== Left Sidebar Start ========== -->
                <div class="left-side-menu">
                    <div class="leftbar-user">
                        <a href="#">
                            <img src="../assets/images/users/avatar-1.jpg" alt="user-image" height="42" class="rounded-circle shadow-sm">
                            <span class="leftbar-user-name">管理员</span>                        
						</a>                    
					</div>

                    <!--- Sidemenu -->
                    <ul class="metismenu side-nav">

                        <li class="side-nav-title side-nav-item">导航</li>
						
                        <li id="index" class="side-nav-item">
                            <a id="index_a" href="./?index" class="side-nav-link">
                                <i class="mdi mdi-chart-arc"></i>
                                <span>首页</span>                            
							</a>
                        </li>
						<?php foreach($menu as $val){if(!isset($val['side-nav-second-level'])):?>
							<li <?php if(isset($val['file'])):?>id="<?php echo $val['file'];?>"<?php endif; ?> class="side-nav-item">
								<a <?php if(isset($val['file'])):?>id="<?php echo $val['file'];?>_a"<?php endif; ?> href="<?php echo isset($val['file']) ? './?'.$val['file'] : 'javascript: void(0);' ;?>" class="side-nav-link">
									<i class="<?php echo $val['icons'];?>"></i>
									<?php if(!empty($val['right'])):?><span class="badge badge-success float-right"><?php $num = Db::table($val['right'])->count(); if($num>99){echo '99+';}else{echo $num;}?></span><?php endif; ?>
									<span><?php echo $val['name'];?></span>
								</a>
							</li>
							<?php else:?>
							<li <?php if(isset($val['id'])):?>id="<?php echo $val['id'];?>"<?php endif; ?> class="side-nav-item">
								<a <?php if(isset($val['id'])):?>id="<?php echo $val['id'];?>_a"<?php endif; ?> href="javascript: void(0);" class="side-nav-link">
									<i class="<?php echo $val['icons'];?>"></i>
									<span><?php echo $val['name'];?></span> 
									<span class="menu-arrow"></span>
								</a>
								<ul <?php if(isset($val['id'])):?>id="<?php echo $val['id'];?>_ul"<?php endif; ?> class="side-nav-second-level" aria-expanded="false">
									<?php foreach($val['side-nav-second-level'] as $v){if($v['hidden'] == 'true')continue;?>
									<li id="<?php echo str_ireplace('/','_',$v['file']);?>">
										<a href="./?<?php echo $v['file'];?>"><?php echo $v['name'];if(!empty($v['right'])):?><span class="badge badge-success float-right"><?php $num = Db::table($v['right'])->count();if($v['right'] == 'app'){$app_num = $num;}if($num>99){echo '99+';}else{echo $num;}?></span><?php endif; ?></a>
									</li>
									<?php } ?>
								</ul>
							</li>
							<?php endif; ?>
						<?php } ?>
						
                    <div class="clearfix"></div>
                    <!-- Sidebar -left -->
                </div>
                <!-- Left Sidebar End -->
				<div class="content-page">
                    <div class="content">