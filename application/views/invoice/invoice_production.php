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
						<div class="text-center">
							<h3>BUKTI PENERIMAAN BARANG <?= $process ?></h3>
							<h4>No: ........</h4>
						</div>
						<p>tanggal: <?= $date ?></p>
					</div>
					<div class="col-md-12">
						<div class="table-responsive m-t-40" style="clear: both;">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">No. </th>
										<th>NAMA BARANG</th>
										<th class="text-center">Qty</th>
										<th>Satuan</th>
										<th>No. SPK</th>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										foreach ($product_movement_detail as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td><?= $q->name ?></td>
												<td class="text-center"><?= $q->qty ?></td>
												<td><?= $q->uom ?></td>
												<td><?= $q->wo ?></td>
												<td></td>
											</tr>
										 	<?php
										$i++;}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-4">
						<p class="text-center">
							Diterima Oleh,<br>
							Admin Gd. Barang Jadi
							<br>
							<br>
							<br>
						</p>
					</div>
					<div class="col-md-4">
						<p class="text-center">
							Mengetahui,<br>
							PPIC
							<br>
							<br>
							<br>
						</p>
					</div>
					<div class="col-md-4">
						<p class="text-center">
							Dibuat Oleh,<br>
							Kepala Bagian
							<br>
							<br>
							<br>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- .row -->
	<!-- /.row -->
</div>
<!-- /.container-fluid -->
