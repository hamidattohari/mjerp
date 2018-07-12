<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row">
		<div id="table-panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
			<div class="panel panel-default">
				<div class="panel-heading"> <?=$table_title;?>         
					<div class="pull-right">
						<a href="javascript:void(0);" id="add-btn"><i class="ti-plus"></i> Add Data</a>
						<a href="<?=site_url('return_nonmaterial/details/'.$woid.'/'.$pid);?>" target="_blank" class="btn btn-success btn-xs">Return Non-Material</a>
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
						<input type="hidden" name="work_orders_id" id="work_orders_id" value="<?= $woid ?>">	
						<input type="hidden" name="products_id" id="products_id" value="<?= $pid ?>">		
						<input type="hidden" name="woid" value="<?= $woid ?>">
						<input type="hidden" name="pid" value="<?= $pid ?>">				
						<!-- <div class="form-group">
							<label for="work_orders_code" class="control-label">Choose WO</label>
							<input type="text" class="form-control" id="work_orders_code" name="work_orders_code" placeholder="Type Work Orders Code" required>
							<div class="help-block with-errors"></div>
						</div> -->
						<div class="form-group row hidden">
							<div class="col-sm-4">
								<input type="radio" class="check" id="material" name="material" value="1" data-radio="iradio_square-blue">
                                <label for="material"> Material </label>
							</div>
							<div class="col-sm-4">
								<input type="radio" class="check" id="nonmaterial" name="material" value="0" data-radio="iradio_square-blue">
                                <label for="nonmaterial"> Non Material </label>
							</div>
						</div>
						<div class="form-group">
							<label for="code" class="control-label">Code</label>
							<input type="text" class="form-control" id="code" name="code" placeholder="Code" readonly>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="date" class="control-label">Picking Date</label>
							<input type="text" class="form-control" id="date" name="date" placeholder="Picking Date" required>
							<div class="help-block with-errors"></div>
						</div>
						<!-- <div class="form-group">
							<label for="products_id" class="control-label">Products</label>
							<select class="custom-select col-sm-12" id="products_id" name="products_id">
								<option value="" selected="">Choose...</option>
							</select>
						</div> -->
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
						<input type="hidden" name="change_id">
						<input type="hidden" name="asd" value="">
					</form>	
					<h3 class="box-title">Material List</h3>
                    <hr>	
						<form id="form2" data-toggle="validator">
							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<span class="form-control"><strong>Item</strong></span> </div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<input id="item_name" name="item_name" class="form-control" placeholder="Type Item Name" type="text" required>
										<span class="help-block"> </span> </div>
								</div>
								<!--/span-->
								<div class="col-md-2">
									<div class="form-group">
										<input id="qty" name="qty" class="form-control" placeholder="Quantity" type="number" required>
										<span class="help-block"> </span> </div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<input id="note" name="note" class="form-control" placeholder="Note" type="text">
										<span class="help-block"> </span> </div>
								</div>
								<input name="item_id" type="hidden">
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
