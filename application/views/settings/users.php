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
						<div class="table-responsive">
							<table id="datatable" class="table table-striped">
								<thead>
									<tr>
										<?php
											foreach($columns as $column){	
										?>	
											<th class="dt-head-center" data-field="<?=$column['data_field'];?>" data-align="<?=$column['data_align'];?>" ><?=$column['label'];?></th>
										<?php
											}
										?>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<hr> 
		</div>
		<div id="form-panel" class="col-md-12">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Add Form</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
					<form id="form" data-toggle="validator">
						<div class="form-group">
							<label for="username" class="control-label">Username</label>
							<input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
							<div class="help-block with-errors"></div>
						</div>					
						<div class="form-group">
							<label for="password" class="control-label">Password</label>
							<div class="row">
								<div class="form-group col-sm-6">
									<input data-toggle="validator" data-minlength="6" class="form-control" id="password" name="password" placeholder="Password" required="" type="password">
									<div class="help-block with-errors"></div> </div>
								<div class="form-group col-sm-6">
									<input class="form-control" id="password-confirm" data-match="#password" data-match-error="Whoops, these don't match" placeholder="Confirm Password" required="" type="password">
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="control-label">Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="email" class="control-label">Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Email">
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
						<div class="form-group">
							<label for="roles_id" class="control-label">Role</label>
							<select id="roles_id" name="roles_id" class="form-control">
								<option >Choose Role</option>
								<?php
									foreach($roles as $role){
										echo '<option value="'.$role->id.'">'.$role->name.'</option>';
									}
								?>
							</select>
							<div class="help-block with-errors"></div>
						</div>
						<input type="hidden" name="change_id">
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
