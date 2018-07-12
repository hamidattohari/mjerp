	<!-- Bootstrap Core CSS -->
	<link href="<?= asset_url('bootstrap/dist/css/bootstrap.min.css');?>" rel="stylesheet">
	<link href="<?= asset_url('plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css');?>" rel="stylesheet">
	<link href="<?= asset_url('plugins/bower_components/icheck/skins/all.css');?>" rel="stylesheet">
	<link rel="stylesheet" href="<?= asset_url('plugins/bower_components/jqueryui/jquery-ui.min.css');?>">
	<!--DataTables -->
	<link href="<?= asset_url('plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet');?>" type="text/css" />
	<link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css" rel="stylesheet" type="text/css" />
	<!-- Editable CSS -->
    <link type="text/css" rel="stylesheet" href="<?= asset_url('plugins/bower_components/jsgrid/dist/jsgrid.min.css');?>" />
	<link type="text/css" rel="stylesheet" href="<?= asset_url('plugins/bower_components/jsgrid/dist/jsgrid-theme.min.css');?>" />
	<!--alerts CSS -->
	<link href="<?= asset_url('plugins/bower_components/sweetalert/sweetalert.css');?>" rel="stylesheet" type="text/css">
	<!--Js Tree CSS -->
	<link href="<?= asset_url('plugins/bower_components/jstree/dist/themes/default/style.min.css');?>" rel="stylesheet" type="text/css">
	<!-- Date picker plugins css -->
	<link href="<?= asset_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css');?>" rel="stylesheet" type="text/css" />
	 <!-- Daterange picker plugins css -->
	 <link href="<?= asset_url('plugins/bower_components/timepicker/bootstrap-timepicker.min.css');?>" rel="stylesheet">
    <link href="<?= asset_url('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css');?>" rel="stylesheet">
    <!-- Clock picker plugins css -->
	<link href="<?= asset_url('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css');?>" rel="stylesheet" type="text/css" />
	<!-- Typehead CSS -->
	<link href="<?= asset_url('plugins/bower_components/typeahead.js-master/dist/typehead-min.css');?>" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<!-- xeditable css -->
	<link href="<?= asset_url('plugins/bower_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css');?>" rel="stylesheet" />
	<!-- Dropify -->
	<link rel="stylesheet" href="<?= asset_url('plugins/bower_components/dropify/dist/css/dropify.min.css');?>">
	<link href="<?= asset_url('plugins/bower_components/dropzone-master/dist/dropzone.css');?>" rel="stylesheet" type="text/css" />
	<!-- Menu CSS -->
	<link href="<?= asset_url('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css');?>" rel="stylesheet">
	<!-- morris CSS -->
	<link href="<?= asset_url('plugins/bower_components/morrisjs/morris.css');?>" rel="stylesheet">
	 <!-- toast CSS -->
	 <link href="<?= asset_url('plugins/bower_components/toast-master/css/jquery.toast.css');?>" rel="stylesheet">
	<!-- animation CSS -->
	<link href="<?= asset_url('css/animate.css');?>" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="<?= asset_url('css/style.css');?>" rel="stylesheet">
	<!-- color CSS -->
	<link href="<?= asset_url('css/colors/blue-dark.css');?>" id="theme" rel="stylesheet">
	<!-- fonts -->
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<style>
	    table.dataTable tbody tr.selected {
	        color: white !important;
	        background-color: #03a9f3 !important;
	    }
	</style>
	<?php
		if(isset($css_asset)){
			echo '<link href="'.asset_url('css/'.$css_asset.'.css').'" rel="stylesheet">';
		}
	?>