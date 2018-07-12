<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row">
		<div id="table-panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
				<div class="panel panel-default">
					<div class="panel-heading"> <?=$table_title;?>         
						<div class="pull-right">
							<a href="javascript:void(0);" id="add-btn">Print Production</a> 
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
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
				<div class="panel panel-default">
					<div class="panel-heading"> <?=$table_title1;?> <span id="nopo"></span>        
						<!-- <div class="pull-right">
							<a href="javascript:void(0);" id="add-btn"><i class="ti-plus"></i> Add Data</a> 
						</div> -->
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
								<label for="category" class="control-label">Receive Date</label>
								<input type="text" class="form-control" id="receive_date" name="receive_date" placeholder="Date" required>
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group">
								<label for="category" class="control-label">Process</label>
								<select class="custom-select col-sm-12" id="processes_id" name="processes_id" required>
									<option selected="">Choose...</option>
									<option value="-1">Uninished</option>
									<option value="0">Finished</option>
								</select>
								<div class="help-block with-errors"></div>
							</div>
							<input type="hidden" name="change_id">
							<input type="hidden" name="asd" value="">
							<div class="form-group text-right">
								<button type="button" id="generateBtn" class="btn btn-success">Generate</button>
							</div>
							<div class="table-responsive"> 
								<div id="jsGrid"></div> 
							</div> 
							<hr>
							<div class="form-group text-right">
								<button type="button" id="printBtn" class="btn btn-success">Print</button>
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
