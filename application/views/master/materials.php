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
											<th class="dt-center" data-field="<?=$column['data_field'];?>" data-align="<?=$column['data_align'];?>" ><?=$column['label'];?></th>
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
							<label for="material_categories_id" class="control-label">Category</label>
							<select class="form-control" id="material_categories_id" name="material_categories_id" required>
								<option selected="">Choose...</option>
								<?php
									foreach($m_categories as $item){
										if($item->deleted == 0){
											echo '<option value="'.$item->id.'">'.$item->name.'</option>';
										}
									}
								?>
							</select>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="name" class="control-label">Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label class="control-label">Initital Stock</label>
									<input id="initial_qty" name="initial_qty" class="form-control" placeholder="Initital Stock"  type="number" min="0" required>
									<div class="help-block with-errors"></div></div>
							</div>
							<!--/span-->
							<div class="col-md-5">
								<div class="form-group">
									<label class="control-label">Minimum Stock</label>
									<input id="min_stock" name="min_stock" class="form-control" placeholder="Minimum Stock" type="number" min="0" required>
									<div class="help-block with-errors"></div></span> </div>
							</div>
							<!--/span-->
							<div class="col-md-2">
								<div class="form-group">
								<label for="uom_id" class="control-label">Unit</label>
								<select class="form-control" id="uom_id" name="uom_id" required>
									<option selected="">Choose...</option>
									<?php
										foreach($uom as $item){
											echo '<option value="'.$item->id.'">'.$item->symbol.'</option>';
										}
									?>
								</select>
								<div class="help-block with-errors"></div>
								</div>
							</div>
							<!--/span-->
						</div>
						<input type="hidden" name="change_id">
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
					</form>
					<h3 class="box-title">List Vendor</h3>
                    <hr>
					<form id="form2" data-toggle="validator">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<span class="form-control"><strong>Add | Edit Vendor</strong></span> </div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<input id="vendor_name" name="vendor_name" class="form-control" placeholder="Type Vendor Name" type="text" required>
									<span class="help-block"> </span> </div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
								<button type="button" id="saveBtn2" class="btn btn-success form-control">Save</button></div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
								<button type="button" id="cancelBtn2" class="btn btn-danger form-control">Clear</button></div>
							</div>
							<!--/span-->
							<input type="hidden" id="vendors_id" name="vendors_id">
							<input type="hidden" name="details_id">
						</div>
						</form> 
					<div class="table-responsive"> 
						<div id="jsGrid"></div> 
					</div> 
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
