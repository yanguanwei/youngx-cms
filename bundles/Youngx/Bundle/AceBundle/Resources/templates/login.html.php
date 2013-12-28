<style type="text/css">
    body {background-color: #1D2024;}
    .login-layout .widget-box { position: static;}
</style>

<div class="login-layout">
<div class="main-container">
<div class="main-content">
<div class="row">
<div class="col-sm-10 col-sm-offset-1">
<div class="login-container">
<div class="center">
    <h1>
        <!--<i class="icon-leaf green"></i>
        <span class="red">Ace</span>
        <span class="white">Application</span>-->
        <img src="<?php echo $this->asset_url('/Zhuiwei/images/admin-login-logo.png');?>" />
    </h1>
    <h4 class="blue">&nbsp;<!--&copy; Company Name--></h4>
</div>

<div class="space-6"></div>

<div class="position-relative">
    <div id="login-box" class="login-box visible widget-box no-border">
        <div class="widget-body">
            <div class="widget-main">
                <h4 class="header blue lighter bigger">
                    <i class="icon-coffee green"></i>
                     欢迎使用后台管理系统
                </h4>

                <div class="space-6"></div>
<?php echo $this->block('content');?>
                <form method="post" action="<?php echo $this->request()->getUri();?>">
                    <fieldset>
                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" name="username" placeholder="Username" />
															<i class="icon-user"></i>
														</span>
                        </label>

                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" name="password" placeholder="Password" />
															<i class="icon-lock"></i>
														</span>
                        </label>

                        <div class="space"></div>

                        <div class="clearfix">
                            <label class="inline">
                                <input type="checkbox" class="ace" name="rememberMe" />
                                <span class="lbl"> 记住我</span>
                            </label>

                            <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                <i class="icon-key"></i>
                                登录
                            </button>
                        </div>

                        <div class="space-4"></div>
                    </fieldset>
                </form>

        </div><!-- /widget-body -->
    </div><!-- /login-box -->

</div><!-- /position-relative -->
</div>
</div><!-- /.col -->
</div><!-- /.row -->
</div>
</div><!-- /.main-container -->

</div>
