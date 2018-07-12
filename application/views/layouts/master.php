<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset_url($this->session->userdata('logo_path'));?>">
    <title><?=$title;?></title>
    <?php $this->load->view('layouts/css_assets.php'); ?>
	<style>
		.ui-autocomplete{
			max-height: 600px;
			overflow-y: auto;   /* prevent horizontal scrollbar */
			overflow-x: hidden; /* add padding to account for vertical scrollbar */
			z-index:1000 !important;
		}
	</style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <script src="http://www.w3schools.com/lib/w3data.js"></script>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
		<?php $this->load->view('layouts/header_menu'); ?>
		<?php $this->load->view('layouts/navigation_menu'); ?>		
        <!-- Page Content -->
        <div id="page-wrapper">
			<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>">
			<?php $this->load->view($page_view); ?>	
            <?php $this->load->view('layouts/footer'); ?>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <?php $this->load->view('layouts/script_assets.php'); ?>
</body>

</html>
