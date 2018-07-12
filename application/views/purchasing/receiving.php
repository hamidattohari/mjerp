<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row">
		<div id="table-panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
				<div class="panel panel-default">
					<div class="panel-heading"> <?=$table_title;?>         
						<!-- <div class="pull-right">
							<a href="javascript:void(0);" id="add-btn"><i class="ti-plus"></i> Add Data</a> 
						</div> -->
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
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
				<div class="panel panel-default">
					<div class="panel-heading"> <?=$table_title1;?> <span id="nopo"></span>        
						<div class="pull-right">
							<a href="javascript:void(0);" id="add-btn"><i class="ti-plus"></i> Add Data</a> 
						</div>
					</div>
					<div class="panel-wrapper collapse in" aria-expanded="true">
						<div class="panel-body">
							<div class="table-responsive">
								<table id="datatable1" class="table table-striped">
									<thead>
										<tr>
											<?php
												foreach($columns1 as $column){	
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
		</div>
		<div id="form-panel" class="col-md-12">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Add Form</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
					<form id="form" data-toggle="validator">
						<div class="form-group">
							<label for="code" class="control-label">Code</label>
							<input type="text" class="form-control" id="code" name="code" placeholder="Code" readonly>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="no_sj" class="control-label">No Surat Jalan</label>
							<input type="text" id="no_sj" name="no_sj" class="form-control" placeholder="No Surat Jalan">
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="date" class="control-label">Estimated Delivery Date</label>
							<input type="text" class="form-control" id="delivery_date" name="delivery_date" placeholder="Delivery Date" readonly>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="vendor" class="control-label">Vendor</label>
							<select class="custom-select col-sm-12" id="vendors_id" name="vendors_id" disabled>
								<option selected="">Choose...</option>
								<?php
									foreach($vendors as $vendor){
										echo '<option value="'.$vendor->id.'">'.$vendor->name.'</option>';
									}
								?>
							</select>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="date" class="control-label">Receiving Date</label>
							<input type="text" class="form-control" id="receive_date" name="receive_date" placeholder="Delivery Date" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
						<input type="hidden" name="asd">
						<input type="hidden" name="change_id">
					</form>
					<div class="table-responsive"> 
						<div id="jsGrid"></div> 
					</div> 
					<h3 class="box-title">Upload Delivery Order</h3>
                    <hr>
					<form id="form2">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="white-box">
										<label for="input-file-max-fs">Max image file size 2 MB</label>
										<input type="file" id="doc_path" name="doc_path" class="dropify" data-max-file-size="2M" data-allowed-file-extensions="png jpg jpeg"/>
									</div>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-6">
								<label  class="control-label">Download Document</label>
								<div class="form-group text-center">
									<button type="button" id="downloadBtn" class="btn btn-success">Download</button>
								</div>
							</div>
							<!--/span-->
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group text-right">
									<button type="button" id="uploadBtn" class="btn btn-success">Upload</button>
								</div>
							</div>
							<!--/span-->
						</div>
					</form>	
					</div>
				</div>
			</div>
		</div>
		<!-- <div id="detail-panel" class="col-md-12">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Details Form</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
					<form id="form" data-toggle="validator">
						<div class="form-group">
							<label for="code" class="control-label">Code</label>
							<input type="text" class="form-control" id="code" name="code" placeholder="Code" readonly>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="date" class="control-label">Delivery Date</label>
							<input type="text" class="form-control" id="delivery_date" name="delivery_date" placeholder="Delivery Date" readonly>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="vendor" class="control-label">Vendor</label>
							<select class="custom-select col-sm-12" id="vendors_id" name="vendors_id" disabled>
								<option selected="">Choose...</option>
								<?php
									foreach($vendors as $vendor){
										echo '<option value="'.$vendor->id.'">'.$vendor->name.'</option>';
									}
								?>
							</select>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="date" class="control-label">Receive Date</label>
							<input type="text" class="form-control" id="receive_date" name="receive_date" placeholder="Delivery Date" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="date" class="control-label">File Upload</label>
							<input type="file" id="file" name="file">
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
						<input type="hidden" name="asd">
						<div class="table-responsive"> 
			              <div id="jsGrid"></div> 
			            </div> 
						<input type="hidden" name="change_id">
					</form>
					</div>
				</div>
			</div>
		</div> -->
		
	</div>
	<!-- /.row -->
</div>
