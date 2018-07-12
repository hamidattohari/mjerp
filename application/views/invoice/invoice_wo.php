<div class="container-fluid">
	<div class="row bg-title">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 pull-right">
			<div class="text-right">
				<button id="print-invoice" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
			</div>
		</div>
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-md-12">
			<div class="white-box printableArea">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-right text-left" style="width:300px">
							<h5>Mojosari, <?= $date ?></h5>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-left">
							<address>
								<?php
									if ($work_orders->ppn == 1){
								?>	
								<h3> &nbsp;<b class="text-info">PT. MEGAHJAYA CEMERLANG</b></h3>
								<p class="text-muted m-l-5">Jl. Raya Mojosari - Trawas Km 7 No 88 Mojokerto
									<br/> Kab. Mojokerto
									<br/> NPWP : 02.297.175.6-6-2.000
								<?php
									}
								?>	
							</address>
						</div>
						<div class="pull-right text-left">
							<address>
								<h4 class="font-bold"><?= $customer->name ?></h4>
								<?php $address = explode(",", $customer->address)?>
								<p class="text-muted"><?= $address[0] ?></p>
								<p class="text-muted"><?= $customer->telp ?></p>
							</address>
						</div>
					</div>
					<div class="col-md-12">
						<div class="text-center">
							<h3>SURAT PERINTAH KERJA</h3>
							<h4>No: <?= $work_orders->code ?></h4>
						</div>
						<p>Mohon dikirimkan tanggal: <?=  $enddate ?></p>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="clear: both; min-height: 178px;">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center" style="width:5%">No. </th>
										<th class="text-center" style="width:15%">Code</th>
										<th class="text-center" style="width:25%">Item</th>
										<th class="text-center" style="width:15%">Qty</th>
										<th class="text-center" style="width:15%">Unit</th>
										<th class="text-center" style="width:25%">Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										foreach ($work_order_detail as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-center" ><?= $q->pcode ?></td>
												<td class="text-left" ><?= $q->name ?></td>
												<td class="text-center"><?= $q->qty ?></td>
												<td class="text-center"><?= $q->symbol ?></td>
												<td class="text-center"><?= $q->note ?></td>
											</tr>
										 	<?php
										$i++;}
									?>
								</tbody>
							</table>
							<p>Mohon dikirim ke Alamat: </p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-right m-t-30 text-right">
							<table>
								<tr>
									<td><h5><b></b></h5></td>
									<td><h5></h5></td>
									<td><h5 style="text-align:right; width:120px;"></h5></td>
								</tr>
								<tr>
									<td><h5><b></b></h5></td>
									<td><h5></h5></td>
									<td><h5 style="text-align:right; width:120px;"></h5></td>
								</tr>
								<tr>
									<td><h3><b></b></h3></td>
									<td><h5></h5></td>
									<td><h3 style="text-align:right; width:120px;"></h3></td>
									<td><h3 style="text-align:right; width:120px;"></h3></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-left">No PO Cust.: <?= $so->po_cust; ?></div>
						<div class="pull-right m-t-30 text-right">
							<h3><b></b> </h3>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="pull-left" style="width:20%;">
						<p class="text-center">
							PEMBUAT
							<br>
							<br>
							<br>
							HANIS
						</p>
					</div>
					<div class="pull-left" style="width:20%;">
						<p class="text-center">
							PENERIMA
							<br>
							<br>
							<br>
							FARID
						</p>
					</div>
					<div class="pull-left" style="width:20%;">
						<p class="text-center">
							ADMIN
							<br>
							<br>
							<br>
							ATIKA
						</p>
					</div>
					<div class="pull-left" style="width:20%;">
						<p class="text-center">
							MENYETUJUI
							<br>
							<br>
							<br>
							SATRIO
						</p>
					</div>
					<div class="pull-left" style="width:20%;">
						<p class="text-center">
							MENGETAHUI
							<br>
							<br>
							<br>
							SLAMET SUBYAKTO
						</p>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- .row -->
	<!-- /.row -->
</div>
<!-- /.container-fluid -->
