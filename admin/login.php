<?php
/*
* File：后台登陆
* Author：易如意
* QQ：51154393
* Url：www.eruyi.cn
*/
$err = isset($_GET['err']) ? intval($_GET['err']) : 0;
$errmsg = array(null,'账号密码不能为空','账号密码有误','您还没有登陆，请先登录！');
$error_msg = $errmsg[$err];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>后台管理 - 易如意验证系统</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <!-- App favicon -->
        <link rel="icon" href="../assets/images/favicon.ico">
        <!-- App css -->
        <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/eruyi.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="authentication-bg">
        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card">
                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <a href="../">
                                    <span><img src="../assets/images/logo.png" alt="" height="18"></span>
                                </a>
                            </div>
                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 font-weight-bold">登 录</h4>
                                    <p class="text-muted mb-4">输入您的账号和密码以访问管理面板。</p>
                                </div>

                                <form name="f" method="post" action="./index.php?action=login">

                                    <div class="form-group">
                                        <label for="emailaddress">账号</label>
                                        <input class="form-control" type="text" placeholder="请输入账号" name="user" required>
                                    </div>

                                    <div class="form-group">
                                        
                                        <label for="password">密码</label>
                                        <input class="form-control" type="password" placeholder="请输入密码" name="pwd" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                            <label class="custom-control-label" for="checkbox-signin">记住我</label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary" type="submit"> 登 录 </button>
                                    </div>

                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <?php if($error_msg):?>
						<div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>提示：</strong> <?php echo $error_msg; ?>
						</div>
						<?php endif; ?>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <footer class="footer footer-alt">
            2018 - <?php echo date('Y',time());?> © 易如意 - eruyi.cn
        </footer>

        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>
    </body>
</html>
