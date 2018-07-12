<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= asset_url('images/logo-dark.png');?>">
	<title><?=$title;?></title>
	<!-- Bootstrap Core CSS -->
	<link href="<?= asset_url('bootstrap/dist/css/bootstrap.min.css');?>" rel="stylesheet">
	<link href="<?= asset_url('plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css');?>" rel="stylesheet">
	<!-- animation CSS -->
	<link href="<?= asset_url('css/animate.css');?>" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="<?= asset_url('css/style.css'); ?>" rel="stylesheet">
	<!-- color CSS -->
	<link href="<?= asset_url('css/colors/blue.css');?>" id="theme" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" class="login-register">
        <div class="login-box login-sidebar">
            <div class="white-box">
                <form class="form-horizontal form-material" id="loginform" action="<?=site_url('registration/add');?>" method="post">
					<a href="javascript:void(0)" class="text-center db"><img src="<?= asset_url('images/logo-dark.png'); ?>" alt="Home" />
						<br/><img src="<?= asset_url('images/logo-text-dark.png'); ?>" alt="Home" /></a>
                    <h3 class="box-title m-t-40 m-b-0">Register Now</h3>
					<div class="text-center" style="color: red; margin-top:10px;">
						<?php 
						if($this->session->flashdata('registration_error')){
							echo "Failed to add initial user";
						}
						?>
					</div>
					<div class="form-group m-t-20">
                        <div class="col-xs-12">
                            <input class="form-control" name="username" type="text" required="" placeholder="Username">
                        </div>
                    </div>
					<div class="form-group m-t-20">
                        <div class="col-xs-12">
                            <input class="form-control" name="name" type="text" required="" placeholder="Name">
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" name="email" type="text" required="" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" name="password" type="password" required="" placeholder="Password">
                        </div>
                    </div>
					<div class="form-group">
						<div class="col-xs-12">
							<select class="form-control" id="roles" name="roles" required="">
								<option>Choose Roles</option>
								<?php
									foreach($roles as $role){
										echo '<option value="'.$role->id.'">'.$role->name.'</option>';
									}
								?>
							</select>
                        </div>
					</div>
					<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>">
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- jQuery -->
    <script src="<?= asset_url('plugins/bower_components/jquery/dist/jquery.min.js');?>"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?= asset_url('bootstrap/dist/js/tether.min.js');?>"></script>
    <script src="<?= asset_url('bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= asset_url('plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js');?>"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?= asset_url('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js');?>"></script>
    <!--slimscroll JavaScript -->
    <script src="<?= asset_url('js/jquery.slimscroll.js');?>"></script>
    <!--Wave Effects -->
    <script src="<?= asset_url('js/waves.js');?>"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?= asset_url('js/custom.min.js');?>"></script>
    <!--Style Switcher -->
    <script src="<?= asset_url('plugins/bower_components/styleswitcher/jQuery.style.switcher.js');?>"></script>
</body>

</html>
