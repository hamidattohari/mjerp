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
				<div class="svlk">
					<img src="<?= asset_url('images/svlk-mj.png'); ?>">
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="pull-right text-left" style="width:300px">
							<h5>Mojosari, <?= $date ?></h5>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-left">
							<img src="<?= asset_url($logo->logo_path); ?>">
						</div>
						<div class="pull-left">
							<address>
								<h3> &nbsp;<b class="text-info" style="font-family: 'Roboto', serif; font-size:30px;">PT. Megahjaya Cemerlang</b></h3>
								<p class="text-muted m-l-5">Jl. Raya Mojosari - Trawas Km 7 No 88 Mojokerto
									<br/> Kab. Mojokerto
									<br/> NPWP : 02.297.175.6-6-2.000	
								</p>
							</address>
						</div>
						<div class="pull-right text-left">
							<address>
								<p>Kepada Yth,</p>
								<h4 class="font-bold"><?= $customer->name ?></h4>
								<p class="text-muted"><?= $customer->address ?></p>
								<p class="text-muted"><?= $customer->telp ?></p>
							</address>
						</div>
					</div>
					<div class="col-md-12">
						<div class="text-center">
							<address>
								<h3><b style="font-size:30px;">SURAT JALAN</b></h3>
								<p>NO SJ: <?= $shipping->code ?></p>
							</address>
						</div>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="clear: both; min-height: 200px;">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">No. </th>
										<th class="text-center">KODE BARANG</th>
										<th class="text-center">NAMA BARANG</th>
										<th class="text-center">JUMLAH</th>
										<th class="text-center">SATUAN</th>
										<th class="text-center">NO PO</th>
										<th class="text-center">KETERANGAN</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										foreach ($shipping_det as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-center"><?= $q->pcode ?></td>
												<td class="text-left"><?= $q->name ?></td>
												<td class="text-center"><?= $q->qty ?></td>
												<td class="text-center"><?= $q->uom ?></td>
												<td class="text-center"><?= $q->po_cust ?></td>
												<td class="text-left">9 box @ 8, 1 box @ 4</td>
											</tr>
										 	<?php
										$i++;}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-right text-right">
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
						<div class="clearfix"></div>
					</div>
					<div class="col-md-12">
						<div class="pull-left text-left">
							<table style="width:300px; margin-bottom:10px;">
								<tbody>
									<tr><td style="width:20%;">Toleransi</td><td>&nbsp;:&nbsp;</td><td>10%</td></tr>
									<tr><td style="width:20%;">Ekspedisi</td><td>&nbsp;:&nbsp;</td><td></td></tr>
									<tr><td style="width:20%;">No.Polisi</td><td>&nbsp;:&nbsp;</td><td></td></tr>
									<tr><td style="width:20%;">Pengemudi</td><td>&nbsp;:&nbsp;</td><td></td></tr>
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="clear: both;">
							<table class="table table-hover">
								<tbody>
									<tr style="height: 70px;">
										<td> PENERIMA:</td>
										<td>PENGIRIM:</td>
										<td>HORMAT KAMI:</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- .row -->
	<!-- /.row -->
</div>
<!-- /.container-fluid -->
