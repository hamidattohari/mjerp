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
						<div class="pull-left">
							<address>
								<h3> &nbsp;<b class="text-info">PT. MEGAHJAYA CEMERLANG</b></h3>
								<p class="text-muted m-l-5">Jl. Raya Mojosari - Trawas Km 7 No 88 Mojokerto
									<br/> Kab. Mojokerto
									<br/> NPWP : 02.297.175.6-6-2.000
							</address>
						</div>
					</div>
					<div class="col-md-12">
						<div class="text-center">
							<h3>LAPORAN PENERIMAAN BARANG</h3>
						</div>
					</div>
					<div class="pull-left" style="width: 30%;">
						<table>
							<tr>
								<td width="175">Terima barang dari</td>
								<td width="10">:</td>
								<td><?= $receiving->name ?></td>
							</tr>
							<tr>
								<td>No. PO</td>
								<td>:</td>
								<td><?= $receiving->pcode ?></td>
							</tr>
							<tr>
								<td>No. Surat Jalan</td>
								<td>:</td>
								<td><?= $receiving->no_sj ?></td>
							</tr>
						</table>
					</div>
					<div class="pull-left" style="width: 40%;"></div>
					<div class="pull-left" style="width: 30%;">
						<table>
							<tr>
								<td width="175">Tanggal</td>
								<td width="10">:</td>
								<td><?= $date ?></td>
							</tr>
						</table>
					</div>
					<div style="clear:both;"></div>
					<div class="col-md-12">
						<div class="table-responsive m-t-40" style="clear: both; min-height: 230px;">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">No. </th>
										<th class="text-center">NAMA BARANG</th>
										<th class="text-center">Qty</th>
										<th class="text-center">SATUAN</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										foreach ($receive_det as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-center"><?= $q->name ?></td>
												<td class="text-center"><?= $q->qty ?></td>
												<td class="text-center"><?= $q->uom ?></td>
											</tr>
										 	<?php
										$i++;}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-left nb">NB: </div>
						<div class="pull-right m-t-30 text-right">
							<!-- <h3><b>Total :</b> <?= $total; ?></h3> -->
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="pull-left" style="width: 30%;">
						<p class="text-center">
							Menyetujui,
							<br>Admin Pembelian
							<br>
							<br>
						</p>
					</div>
					<div class="pull-left" style="width: 30%;">
						<p class="text-center">
							Mengetahui,
							<br>Kepala Pabrik
							<br>
							<br>
						</p>
					</div>
					<div class="pull-left" style="width: 30%;">
						<p class="text-center">
							Penerima,
							<br>Admin Gudang
							<br>
							<br>
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
