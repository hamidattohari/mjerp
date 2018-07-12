<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- .row -->
	<div class="row">
		<div class="col-md-12">
			<div class="well">
				<h4>Hello, <?=$this->session->userdata('name');?></h4>
				<p>Welcome to ERP system.</p>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- /.container-fluid -->
