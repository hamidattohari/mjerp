<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row" id="search-panel">
		<div class="col-sm-12">
			<div class="white-box">
				<form action="#" class="form-horizontal">
				<div class="form-body">
					<div class="row">
						<div class="col-md-3">
						</div>
						<!--/span-->
						<div class="col-md-9">
							<div class="form-group">
								<label class="control-label col-md-3">Date Range</label>
								<div class="col-md-7">
									<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
										<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
										<span></span> <b class="caret"></b>
									</div>
								</div>
								<input type="hidden" name="range">
								<div class="col-md-2">
									<button type="button" class="btn btn-block btn-outline btn-info" id="searchBtn"><i class="fa fa-search"></i> Search</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="table-panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
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
		<div id="form-panel" class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-heading"> Add Form
					<div class="pull-right">
						<a href="javascript:void(0);" id="add-btn"><i class="ti-plus"></i> Add Adjustment</a> 
					</div>
				</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
					<form>
						<div class="form-group">
							<label>Initial Stock</label>
							<input type="text" name="initial_qty" id="initial_qty" readonly>
						</div>
						<div class="form-group text-right">
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
						<div class="table-responsive"> 
			              <div id="jsGrid"></div> 
			            </div>
					</form>
					</div>
				</div>
			</div>
		</div>
		<div id="adjustment-panel" class="col-md-12">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Add Form</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
					<form id="form" data-toggle="validator">
						<input type="hidden" name="materials_id" id="materials_id">
						<div class="form-group">
							<label for="name" class="control-label">Material</label>
							<input type="text" class="form-control" id="materials" name="materials" placeholder="Material" readonly>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="material_categories_id" class="control-label">Adjustment Type</label>
							<select class="custom-select col-sm-12" id="type" name="type" required>
								<option selected= disabled>Choose...</option>
								<option value="in">IN</option>
								<option value="out">OUT</option>
							</select>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="name" class="control-label">Date</label>
							<input type="text" class="form-control" id="date" name="date" placeholder="Date" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="name" class="control-label">Qty</label>
							<input type="text" class="form-control" id="qty" name="qty" placeholder="Qty" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn1" class="btn btn-danger">Cancel</button>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
