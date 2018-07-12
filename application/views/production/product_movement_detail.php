<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row">
		<div id="table-panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<div class="panel panel-success">
					<div class="panel-heading">Pickup or Return Material
						<div class="panel-action"><a href="<?=site_url('pickup_material/details/'.$woid.'/'.$pid);?>" target="_blank">Click Here</a></div>
					</div>				
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<div class="panel panel-warning">
					<div class="panel-heading">Pickup or Return Non-Material
						<div class="panel-action"><a href="<?=site_url('pickup_nonmaterial/details/'.$woid.'/'.$pid);?>" target="_blank">Click Here</a></div>
					</div>				
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div id="process_buttons">
							<?php foreach ($button as $q) {
								echo "<button class='btn btn-info m-r-10' value=".$q->id.">".$q->name."</button>";
							} ?>
							<button class='btn btn-danger m-r-10' value="-1">Unfinished</button>
							<button class='btn btn-success m-r-10' value="0">Finished</button>
						</div>
					</div>				
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
				<div class="panel panel-default">
					<div class="panel-heading"> <?=$table_title1;?> <span id="nopo" class="text-info"></span>        
						<div class="pull-right">
							<a href="javascript:void(0);" id="add-btn"><i class="ti-plus"></i> Add Data</a>
							<a href="javascript:void(0);" id="time-btn" class="btn btn-info m-l-10">Time Table</a>
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
								<br><br>
								<button class="btn btn-lg btn-info pull-right" id="move" onClick="move()" type="button">Move</button>
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
								<label for="category" class="control-label">Printing Length</label>
								<input type="text" class="form-control" id="length" name="length" placeholder="Printing Length" required>
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group">
								<label for="category" class="control-label">Machine</label>
								<select class="custom-select col-sm-12" id="machine_id" name="machine_id">
									<option value="" selected>Choose...</option>
								</select>
								<div class="help-block with-errors"></div>
							</div>
							<input type="hidden" name="woid" value="<?= $woid ?>">
							<input type="hidden" name="pid" value="<?= $pid ?>">
							<input type="hidden" name="id" value="">
							<div class="form-group text-right">
								<button type="button" id="saveBtn" class="btn btn-success">Save</button>
								<button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
							</div>
								<!-- <div class="table-responsive"> 
									<div id="jsGrid"></div> 
								</div> --> 
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="form-panel1" class="col-md-12">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Move Product</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
						<form id="form1" data-toggle="validator">
							<div class="form-group">
								<label for="category" class="control-label">Terget Process</label>
								<select class="custom-select col-sm-12" id="processes_id" name="processes_id" required>
									<option value="" selected>Choose...</option>
									<?php
									foreach($process as $item){
										echo '<option value="'.$item->id.'">'.$item->name.'</option>';
									}
									?>
									<option value="-2">Not Processed</option>
									<option value="-1">Unfinished</option>
									<option value="0">Finished</option>
								</select>
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group">
								<label for="category" class="control-label">Machine</label>
								<select class="custom-select col-sm-12" id="machine_id1" name="machine_id">
									<option value="" selected>Choose...</option>
								</select>
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group" id="number">
								<label for="category" class="control-label">Number</label>
								<input type="text" name="no" class="form-control">
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group" id="length1">
								<label for="category" class="control-label">Length</label>
								<input type="text" name="length" class="form-control" id="length1">
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group">
								<label for="category" class="control-label">Note</label>
								<input type="text" name="note" class="form-control" id="note">
								<div class="help-block with-errors"></div>
							</div>
							<input type="hidden" name="woid1" value="<?= $woid ?>">
							<input type="hidden" name="prid" value="">
							<input type="hidden" name="pm_id" value="<?= $pm_id ?>">
							<input type="hidden" name="pmd_id" value="">
							<!-- <div class="table-responsive"> 
								<div id="jsGrid"></div> 
							</div> --> 
							<div class="form-group text-right">
								<button type="button" id="saveBtn1" class="btn btn-success">Save</button>
								<button type="button" id="cancelBtn1" class="btn btn-danger">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="form-panel2" class="col-md-12">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Time Table</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
						<form id="form2" data-toggle="validator">
							<div class="row">
								<input type="hidden" name="details_id">
								<input type="hidden" name="wo_id">
								<input type="hidden" name="processes_id">
								<input type="hidden" name="products_id">
								<div class="col-md-2">
									<div class="form-group">
										<input id="datetime" name="datetime" class="form-control" placeholder="Type Date" type="text" required>
										<span class="help-block"> </span> 
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<input id="time_start" name="time_start" class="form-control clockpicker" placeholder="Type Start Time" type="text" required>
										<span class="help-block"> </span> 
									</div>
								</div>
								<div class="col-md-1"><p class="text-center" style="padding: 6px;">To</p></div>
								<div class="col-md-2">
									<div class="form-group">
										<input id="time_end" name="time_end" class="form-control clockpicker" placeholder="Type End Time" type="text" required>
										<span class="help-block"> </span> 
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<input id="note2" name="note2" class="form-control" placeholder="Note" type="text" required>
										<span class="help-block"> </span> 
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
									<button type="button" id="saveTimeBtn" class="btn btn-success"><i class="fa fa-save"></i></button>
									<button type="button" id="cancelTimeBtn" class="btn btn-danger"><i class="fa fa-remove"></i></button>
									</div>
								</div>
								<!--/span-->
							</div>
						</form>
						<div class="table-responsive"> 
							<div id="jsGrid"></div> 
						</div>
						<div class="form-group text-right m-t-10">
							<button type="button" id="cancelBtn2" class="btn btn-danger">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<!-- /.row -->
	</div>
