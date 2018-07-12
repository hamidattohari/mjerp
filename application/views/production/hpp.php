<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row">
		<div id="table-panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
			<div class="panel panel-default">
				<div class="panel-heading"> <?=$table_title;?>         
					<div class="pull-right">
						<a href="javascript:void(0);" id="add-btn"><i class="ti-plus"></i> Add Data</a> 
						<button class="btn btn-info" id="add-btkl">ADD BTKL</button>
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
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="month" class="control-label">Months</label>
									<select class="custom-select col-sm-12" id="month" name="month">
									</select>
									<input type="text" name="months" style="display:none;" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="year" class="control-label">Years</label>
									<select class="custom-select col-sm-12" id="year" name="year">
									</select>
									<input type="text" name="years" style="display:none;" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="code" class="control-label">Code</label>
							<input type="text" class="form-control" id="code" name="code" placeholder="Code" readonly>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="products_id" class="control-label">Work Orders</label>
							<select class="custom-select col-sm-12" id="work_orders_id" name="work_orders_id">
								<option selected="">Choose...</option>
							</select>
							<input type="text" name="wocode" style="display:none;" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label for="products_id" class="control-label">Products</label>
							<select class="custom-select col-sm-12" id="products_id" name="products_id">
								<option selected="">Choose...</option>
							</select>
							<input type="text" name="pname" style="display:none;" class="form-control" readonly>
						</div>
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
						<input type="hidden" name="change_id">
						<input type="hidden" name="asd" value="">
						<input type="hidden" name="wo_id" value="">
					</form>	
					<h3 class="box-title">Material Cost</h3>
                    <hr>	
					<div class="table-responsive"> 
						<div id="jsGrid"></div> 
					</div> 
					<div class="col-md-12">
						<div class="pull-right text-right">
							<h3><b>Total :</b> <span id="mtotal"></span> </h3>
						</div>
					</div>
					<h3 class="box-title">BTKL</h3>
                    <hr>	
						<form id="form2" data-toggle="validator">
						<div class="row">
							<div class="col-md-1">
								<div class="form-group">
									<span class="form-control"><strong>Item</strong></span> </div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select class="form-control" id="process" name="process" required>
										<option value="" selected>Choose Process..</option>
										<option value="Printing">Printing</option>
										<option value="Sliter">Sliter</option>
										<option value="Rewinder">Rewinder</option>
										<option value="Perforasi">Perforasi</option>
										<option value="Lembur">Lembur</option>
										<option value="Idle">Idle</option>
									</select>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-2">
								<div class="form-group">
									<input id="qty" name="qty" class="form-control" placeholder="Quantity" type="text" required>
									<span class="help-block"> </span> </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input id="price" name="price" class="form-control" placeholder="Price" type="text" required>
									<span class="help-block"> </span> </div>
							</div>
							<input type="hidden" name="details_id">
							<div class="col-md-1">
								<div class="form-group">
								<button type="button" id="saveBtn2" class="btn btn-success form-control">Save</button></div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
								<button type="button" id="cancelBtn2" class="btn btn-danger form-control">Clear</button></div>
							</div>
							<!--/span-->
							<input type="hidden" name="details_id" id="details_id">
						</div>
						</form>
						<div class="table-responsive"> 
			              <div id="jsGrid2"></div> 
			            </div> 
						<div class="col-md-12">
							<div class="pull-right text-right">
								<h3><b>Total :</b> <span id="btotal"></span> </h3>
							</div>
						</div>
					<h3 class="box-title">BOP</h3>
                    <hr>
					<form id="form3" data-toggle="validator">
						<div class="form-group">
							<label for="penyusutan" class="control-label">Depreciation</label>
							<input type="number" class="form-control" id="penyusutan" name="penyusutan" placeholder="Depreciation" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="listrik" class="control-label">Electricity</label>
							<input type="number" class="form-control" id="listrik" name="listrik" placeholder="Electricity" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="lain_lain" class="control-label">Others</label>
							<input type="number" class="form-control" id="lain_lain" name="lain_lain" placeholder="Others" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group text-right">
							<button type="button" id="saveBtn3" class="btn btn-success">Save</button>
						</div>
					</form>	
					<div class="col-md-12">
						<div class="pull-right text-right">
							<h3><b>Total :</b> <span id="ototal"></span> </h3>
						</div>
					</div>
					<h3 class="box-title">Product Result</h3>
                    <hr>	
					<div class="table-responsive"> 
						<div id="jsGrid3"></div> 
					</div> 
					</div>
				</div>
			</div>
		</div>
		<div id="btkl-panel" class="col-md-12">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Add Form</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
					<form id="form" data-toggle="validator">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="month" class="control-label">Months</label>
									<select class="custom-select col-sm-12" id="month" name="month">
									</select>
									<input type="text" name="months1" style="display:none;" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="year" class="control-label">Years</label>
									<select class="custom-select col-sm-12" id="year" name="year">
									</select>
									<input type="text" name="years1" style="display:none;" class="form-control">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="total" class="control-label">Total</label>
							<input type="text" class="form-control" id="total" name="total" placeholder="Total">
							<div class="help-block with-errors"></div>
						</div>
					</form>	
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
