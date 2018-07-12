<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /.row -->
	<!-- .row -->
	<div class="row">
		<div class="col-md-4 col-xs-12">
			<div class="white-box">
				<div class="user-bg"> <img width="100%" alt="user" src="<?= asset_url('images/profile-bg.jpeg');?>">
					<div class="overlay-box">
						<div class="user-content">
							<a href="javascript:void(0)"><img src="<?= asset_url('images/default-pict.jpg');?>" class="thumb-lg img-circle" alt="img"></a>
							<h4 class="text-white"><?=$this->session->userdata('username')?></h4>
							<h5 id="h-email" class="text-white">info@myadmin.com</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8 col-xs-12">
			<div class="white-box">
				<ul class="nav customtab nav-tabs" role="tablist">
					<li role="presentation" class="nav-item"><a href="#settings" class="nav-link active" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Setting</span></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="settings">
						<form id="form" class="form-horizontal form-material">
							<div class="form-group">
								<label class="col-md-12">Full Name</label>
								<div class="col-md-12">
									<input type="text" placeholder="Full Name" name="name" class="form-control form-control-line" required>
								</div>
							</div>
							<div class="form-group">
								<label for="example-email" class="col-md-12">Email</label>
								<div class="col-md-12">
									<input type="email" placeholder="mail@example.com" class="form-control form-control-line" name="email">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12">Password</label>
								<div class="col-md-12">
									<input type="password" name="password" class="form-control form-control-line" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12">Phone No</label>
								<div class="col-md-12">
									<input type="text" name="telp" placeholder="123 456 7890" class="form-control form-control-line">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12">Address</label>
								<div class="col-md-12">
									<textarea rows="5" name="address" class="form-control form-control-line"></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<button type="button" id="saveBtn" class="btn btn-success">Update Profile</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- /.container-fluid -->
