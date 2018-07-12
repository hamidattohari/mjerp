<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row">
		<div id="table-panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
			<div class="panel panel-default">
				<div class="panel-heading"> <?=$table_title;?>         
					<div class="pull-right">
						<a href="javascript:void(0);" id="add-btn"><i class="ti-plus"></i> Add Data</a> 
					</div>
				</div>
				<div class="panel-wrapper collapse in" aria-expanded="true">
					<div class="panel-body">
						
					</div>
				</div>
			</div>
			<hr> 
		</div>
		<div id="form-panel" class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-heading"> Add Form</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
							<!-- Nav tabs -->
							<ul class="nav customtab nav-tabs" role="tablist">
								<li role="presentation" class="nav-item"><a href="#home1" class="nav-link active" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Home</span></a></li>
								<li role="presentation" class="nav-item"><a href="#profile1" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Profile</span></a></li>
								<li role="presentation" class="nav-item"><a href="#messages1" class="nav-link" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Messages</span></a></li>
								<li role="presentation" class="nav-item"><a href="#settings1" class="nav-link" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Settings</span></a></li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane fade active in" id="home1">
									<div class="col-md-6">
										<h3>Best Clean Tab ever</h3>
										<h4>you can use it with the small code</h4>
									</div>
									<div class="col-md-5 pull-right">
										<p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
									</div>
									<div class="clearfix"></div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="profile1">
									<div class="col-md-12">
										<div class="table-responsive">
											<div id="jsGrid"></div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="messages1">
									<div class="col-md-6">
										<h3>Come on you have a lot message</h3>
										<h4>you can use it with the small code</h4>
									</div>
									<div class="col-md-5 pull-right">
										<p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
									</div>
									<div class="clearfix"></div>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="settings1">
									<div class="col-md-6">
										<h3>Just do Settings</h3>
										<h4>you can use it with the small code</h4>
									</div>
									<div class="col-md-5 pull-right">
										<p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>


<div id="detailsDialog">
		<form id="detailsForm" data-toggle="validator">
						<div class="form-group">
							<label for="name" class="control-label">Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="description" class="control-label">Description</label>
							<textarea id="description" name="description" class="form-control"></textarea>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="address" class="control-label">Address</label>
							<input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="telp" class="control-label">Telp</label>
							<input type="text" class="form-control" id="telp" name="telp" placeholder="Telp" required>
							<div class="help-block with-errors"></div>
						</div>
						<input type="hidden" name="change_id">
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
					</form>
</div>
