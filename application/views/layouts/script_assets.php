	<!-- jQuery -->
	<script src="<?= asset_url('plugins/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script src="<?= asset_url('plugins/bower_components/jqueryui/jquery-ui.min.js');?>"></script>
	<script src="<?= asset_url('plugins/bower_components/jqueryui/jquery.ui.autocomplete.scroll.min.js');?>"></script>
	<script src="<?= asset_url('plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');?>"></script>	
    <!-- Bootstrap Core JavaScript -->
    <script src="<?= asset_url('bootstrap/dist/js/tether.min.js');?>"></script>
    <script src="<?= asset_url('bootstrap/dist/js/bootstrap.min.js');?>"></script>
    <script src="<?= asset_url('plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js');?>"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?= asset_url('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js');?>"></script>
    <!--Counter js -->
    <script src="<?= asset_url('plugins/bower_components/waypoints/lib/jquery.waypoints.js');?>"></script>
    <script src="<?= asset_url('plugins/bower_components/counterup/jquery.counterup.min.js');?>"></script>
    <!--slimscroll JavaScript -->
    <script src="<?= asset_url('js/jquery.slimscroll.js');?>"></script>
    <!--Wave Effects -->
    <script src="<?= asset_url('js/waves.js');?>"></script>
    <!--Morris JavaScript -->
    <script src="<?= asset_url('plugins/bower_components/raphael/raphael-min.js');?>"></script>
    <script src="<?= asset_url('plugins/bower_components/morrisjs/morris.js');?>"></script>
    <script src="<?= asset_url('plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js');?>"></script>
	<!-- DataTables -->
	<script src="<?= asset_url('plugins/bower_components/datatables/jquery.dataTables.min.js');?>"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="<?= asset_url('plugins/bower_components/datatables/pdfmake.min.js');?>"></script>
    <script src="<?= asset_url('plugins/bower_components/datatables/vfs_fonts.js');?>"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
	<!-- Editable -->
	<script src="<?= asset_url('plugins/bower_components/jsgrid/dist/jsgrid.min.js');?>"></script>
	<!-- Js Tree -->
    <script src="<?= asset_url('plugins/bower_components/jstree/dist/jstree.min.js');?>"></script>
	<!--BlockUI Script -->
	<script src="<?= asset_url('plugins/bower_components/blockUI/jquery.blockUI.js');?>"></script>
	<!-- Plugin JavaScript -->
    <script src="<?= asset_url('plugins/bower_components/moment/moment.js');?>"></script>
	<!-- Date Picker Plugin JavaScript -->
	<script src="<?= asset_url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js');?>"></script>
	<!-- Date range Plugin JavaScript -->
    <script src="<?= asset_url('plugins/bower_components/timepicker/bootstrap-timepicker.min.js');?>"></script>
    <script src="<?= asset_url('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
    <!-- Clock Picker Plugin JavaScript -->
	<script src="<?= asset_url('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js');?>"></script>
	<!-- Form Validation -->
	<script src="<?= asset_url('js/validator.js');?>"></script>
	<!-- Sweet-Alert  -->
    <script src="<?= asset_url('plugins/bower_components/sweetalert/sweetalert.min.js');?>"></script>
    <script src="<?= asset_url('plugins/bower_components/sweetalert/jquery.sweet-alert.custom.js');?>"></script>
    <!-- Custom Theme JavaScript -->
	<script src="<?= asset_url('js/custom.min.js');?>"></script>
	 <!-- jQuery x-editable -->
    <script type="text/javascript" src="<?= asset_url('plugins/bower_components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js');?>"></script>
	<!-- Typehead Plugin JavaScript -->
	<script src="<?= asset_url('plugins/bower_components/typeahead.js-master/dist/typeahead.bundle.min.js');?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	 <!-- jQuery file upload -->
	 <script src="<?= asset_url('plugins/bower_components/dropify/dist/js/dropify.min.js');?>"></script>
	 <script src="<?= asset_url('plugins/bower_components/dropzone-master/dist/dropzone.js');?>"></script>
	 <!-- Toast -->
	 <script src="<?= asset_url('plugins/bower_components/toast-master/js/jquery.toast.js');?>"></script>
	 <!-- icheck -->
    <script src="<?= asset_url('plugins/bower_components/icheck/icheck.min.js');?>"></script>
    <script src="<?= asset_url('plugins/bower_components/icheck/icheck.init.js');?>"></script>
	<!-- Extra JavaScript -->
	<script src="<?= asset_url('apps-js/extra.js');?>"></script>
	<script src="<?= asset_url('js/jquery.PrintArea.js');?>" type="text/JavaScript"></script>
	<script>
		var base_url = "<?=base_url();?>";
		var site_url = "<?=site_url('/');?>";
		var role_id = "<?=$this->session->userdata('role_id')?>";
	</script>
	<?php
		if(isset($js_asset)){
			echo '<script src="'.asset_url('apps-js/'.$js_asset.'.js').'"></script>';
		}
	?>
    <!--Style Switcher -->
    <script src="<?= asset_url('plugins/bower_components/styleswitcher/jQuery.style.switcher.js');?>"></script>
