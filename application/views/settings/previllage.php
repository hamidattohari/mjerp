<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- /row -->
	<div class="row">
		<div id="table-panel" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"> 
			<div class="panel panel-default">
				<div class="panel-heading"> <?=$table_title;?>         
				</div>
				<div class="panel-wrapper collapse in" aria-expanded="true">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="datatable" class="table table-striped hover-table">
								<thead style="display:none">
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
									<?php
										foreach($roles as $role){
											echo '<tr>
												<td>'.$role->id.'</td>
												<td>'.$role->name.'</td>
											</tr>';
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<hr> 
		</div>
		<div id="form-panel" class="col-md-8">
			<div class="panel panel-info">
				<div id="form-title" class="panel-heading"> Privilege</div>
				<div class="panel-wrapper collapse in block-div" aria-expanded="true">
					<div class="panel-body">
						<div id="tree"></div>
					</div>
				</div>
				<div class="panel-footer"> <button type="button" id="saveBtn" class="btn btn-success">Save</button> </div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
