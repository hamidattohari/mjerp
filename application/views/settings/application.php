<div class="container-fluid">
	<?php $this->load->view('layouts/breadcumb') ?>
	<!-- .row -->
	<div class="row">
	<div class="col-sm-12">
		<div class="white-box">
			<div class="row">
				<div class="col-sm-6 ol-md-6 col-xs-12">
					<div class="white-box">
						<h3 class="box-title">Company Logo</h3>
						<label for="logo_path">Max image size 500kb</label>
						<form id="logo-form">
						<input type="file" id="logo_path" name="logo_path" class="dropify" data-max-file-size="500K" data-allowed-file-extensions="png jpg jpeg" />
						</form>
						<div class="text-right">
							<button id="upload1" class="btn btn-sm btn-success">Save</button>
						</div>
					</div>
				</div>
				<div class="col-sm-6 ol-md-6 col-xs-12">
					<div class="white-box">
						<h3 class="box-title">Company Text</h3>
						<label for="logo_title_path">Max image size 500kb</label>
						<form id="logo-title-form">
						<input type="file" id="logo_title_path" name="logo_title_path" class="dropify" data-max-file-size="500K" data-allowed-file-extensions="png jpg jpeg"  />
						</form>
						<div class="text-right">
							<button id="upload2" class="btn btn-sm btn-success">Save</button>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 text-right">
					<button id="enable" class="btn btn-success btn-sm">Edit</button>
				</div>
				<div class="col-md-12">
					<table style="clear: both" class="table table-bordered table-striped" id="user">
						<tbody>
							<tr>
								<td width="35%">Tax Payer Reg. Number</td>
								<td width="65%">
									<a href="javascript:void(0);" id="taxpayer_reg_number" class="editable" data-type="text" data-pk="1" data-placement="right" data-placeholder="Required" data-title="Enter Tax Reg. Number"><?=$info->taxpayer_reg_number;?></a>
								</td>
							</tr>
							<tr>
								<td>Company Name</td>
								<td>
									<a href="javascript:void(0);" id="name" class="editable" data-type="text" data-pk="1" data-placement="right" data-placeholder="Required" data-title="Enter Company Name"><?=$info->name;?></a>
								</td>
							</tr>
							<tr>
								<td>Address</td>
								<td><a href="javascript:void(0);" id="address" class="editable" data-type="textarea" data-pk="1" data-placeholder="Your Company Address..." data-title="Enter comments"><?=$info->address;?></a></td>
							</tr>
							<tr>
								<td>Telp</td>
								<td>
									<a href="javascript:void(0);" id="telp" class="editable" data-type="text" data-pk="1" data-placement="right" data-placeholder="Required" data-title="Enter Telephone Number"><?=$info->telp;?></a>
								</td>
							</tr>
							<tr>
								<td>Owner</td>
								<td>
									<a href="javascript:void(0);" id="owner" class="editable" data-type="text" data-pk="1" data-placement="right" data-placeholder="Required" data-title="Enter Company Owner"><?=$info->owner;?></a>
								</td>
							</tr>
							<tr>
								<td>Base Currency</td>
								<td>
									<a href="javascript:void(0);" id="currency_id" data-type="select" data-pk="1" data-value="<?=$info->currency_id;?>" data-title="Select currency"><?=$info->currency;?></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 ol-md-12 col-xs-12">
					<div class="white-box text-center">
						<a href="<?=site_url('history');?>" class="btn btn-rounded btn-info"><i class="fa fa-eye"></i> View User Log</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 ol-md-12 col-xs-12">
					<div class="white-box text-center">
						<a href="" class="btn btn-rounded btn-info"><i class="fa fa-book"></i> View User Guide</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
