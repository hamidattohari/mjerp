<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row">
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
	<!-- /.row -->
	<div class="row">
		<div id="table-panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
			<div class="panel panel-default">
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
	</div>
	<!-- /.row -->
</div>
