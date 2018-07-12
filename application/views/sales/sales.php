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
		<div id="form-panel" class="col-md-12 form-hide">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Add Form</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
					<form id="form" data-toggle="validator">
						<div class="form-group">
							<label for="name" class="control-label">Code</label>
							<input type="text" class="form-control" id="code" name="code" placeholder="Code" readonly required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group row">
							<div class="col-sm-4">
								<input type="radio" class="check" id="vat" name="vat" value="1" data-radio="iradio_square-blue" checked>
                                <label for="vat"> VAT </label>
							</div>
							<div class="col-sm-4">
								<input type="radio" class="check" id="nonvat" name="vat" value="0" data-radio="iradio_square-blue">
                                <label for="nonvat"> Non VAT </label>
							</div>
						</div>
						<div class="form-group">
							<label for="customer_name" class="control-label">Customers</label>
							<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customers">
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="po_cust" class="control-label">No PO Customer</label>
							<input type="text" class="form-control" id="po_cust" name="po_cust" placeholder="No PO Customer">
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="description" class="control-label">Note</label>
							<input type="text" class="form-control" id="description" name="description" placeholder="Note">
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
						<input type="hidden" name="customers_id" value="">
						<input type="hidden" name="asd" value="">
						<input type="hidden" name="change_id">
					</form>
					<h3 class="box-title">Product List</h3>
                    <hr>
					<form id="form2" data-toggle="validator">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<input id="product_name" name="product_name" class="form-control" placeholder="Type Product Name" type="text" required>
								<span class="help-block"> </span> </div>
						</div>
						<!--/span-->
						<div class="col-md-2">
							<div class="form-group">
								<input id="price" name="price" class="form-control" placeholder="Price" min="0" type="number" required>
								<span class="help-block"> </span> </div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<input id="qty" name="qty" class="form-control" placeholder="Qty" min="0" type="number" required>
								<span class="help-block"> </span> </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<input id="note" name="note" class="form-control" placeholder="Note" type="text" required>
								<span class="help-block"> </span> </div>
						</div>
						<input type="hidden" id="products_id" name="products_id">
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
						<table id="datatable2" class="table table-striped">
							<thead>
								<tr>
									<?php
										foreach($columns2 as $column){	
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
	</div>
	<!-- /.row -->
</div>
</div>
