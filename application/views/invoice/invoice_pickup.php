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
							<h3>BUKTI PENGAMBILAN MATERIAL <b style="text-transform: uppercase;"><?= $material_usage->usage_categories ?></b></h3>
							<h4>No: <?= $material_usage->code ?></h4>
						</div>
						<p class="pull-left">Tanggal: <?= explode(" ", $material_usage->date)[0] ?></p>
						<p class="pull-right">No. SPK: <?= $material_usage->wocode ?></p>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="clear: both; min-height: 320px;">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">No. </th>
										<th class="text-center">NAMA BARANG</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Uom</th>
										<th class="text-center">Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										foreach ($material_usage_detail as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-left"><?= $q->name ?></td>
												<td class="text-center"><?=  number_format($q->qty, 0, ",", ".")  ?></td>
												<td class="text-center"><?= $q->symbol ?></td>
												<td class="text-left">><?= $q->note ?></td>
											</tr>
										 	<?php
										$i++;}
									?>
								</tbody>
							</table>
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
					<div class="pull-left" style="width: 20%;">
						<p class="text-center">
							Diserahkan Oleh,<br>
							Admin Gudang
							<br>
							<br>
							<br>
						</p>
					</div>
					<div class="pull-left" style="width: 20%;">
						<p class="text-center">
							Menyetujui,<br>
							PPIC
							<br>
							<br>
							<br>
						</p>
					</div>
					<div class="pull-left" style="width: 30%;">
						<p class="text-center">
							Mengetahui,<br>
							Kepala Bagian
							<br>
							<br>
							<br>
						</p>
					</div>
					<div class="pull-left" style="width: 30%;">
						<p class="text-center">
							Dibuat Oleh,<br>
							Admin Produksi
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
