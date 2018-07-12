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
							<label for="code" class="control-label">Code</label>
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
							<label for="delivery_date" class="control-label">Delivery Date</label>
							<input type="text" class="form-control" id="delivery_date" name="delivery_date" placeholder="Delivery Date" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="address" class="control-label">Delivery Address</label>
							<input type="text" class="form-control" id="delivery_place" name="delivery_place" placeholder="Delivery Address" value="<?=$company_address?>" required>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="note" class="control-label">Note</label>
							<textarea id="note" name="note" class="form-control"></textarea>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="vendor_name" class="control-label">Vendor</label>
							<input type="text" class="form-control" id="vendor_name" name="vendor_name" placeholder="Vendor">
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label for="currency" class="control-label">Currency</label>
							<select class="custom-select col-sm-12" id="currency" name="currency" required>
								<option selected="">Choose...</option>
								<?php
									foreach($currency as $q){
										echo '<option value="'.$q->id.'">'.$q->symbol.'</option>';
									}
								?>
							</select>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group text-right">
							<button type="button" id="saveBtn" class="btn btn-success">Save</button>
							<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
						</div>
						<input type="hidden" name="asd">
						<input type="hidden" name="vendors_id">
						<input type="hidden" name="change_id">
					</form>
					<h3 class="box-title">Product List</h3>
                    <hr>
					<form id="form2" data-toggle="validator">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<input id="material_name" name="material_name" class="form-control" placeholder="Type Material Name" type="text" required>
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
						<div class="col-md-2">
							<div class="form-group">
								<input id="discount" name="discount" class="form-control" placeholder="Discount" min="0" type="number" required>
								<span class="help-block"> </span> </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<input id="note2" name="note2" class="form-control" placeholder="Note" type="text" required>
								<span class="help-block"> </span> </div>
						</div>
						<input type="hidden" name="materials_id">
						<input type="hidden" name="details_id">
						<div class="col-md-1">
							<div class="form-group">
							<button type="button" id="saveBtn2" class="btn btn-success"><i class="fa fa-save"></i></button>
							<button type="button" id="cancelBtn2" class="btn btn-danger"><i class="fa fa-remove"></i></button>
							</div>
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
