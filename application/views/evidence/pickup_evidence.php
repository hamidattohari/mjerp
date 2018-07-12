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
								<h3> &nbsp;<b class="text-danger">PT. MEGAHJAYA CEMERLANG</b></h3>
								<p class="text-muted m-l-5">Jl. Raya Mojosari - Trawas Km 7 No 88 Mojokerto
									<br/> Kab. Mojokerto
									<br/> NPWP : 02.297.175.6-6-2.000
							</address>
						</div>
						<div class="pull-right text-right">
							<address>
								<h4 class="font-bold"><?= $customer->name ?></h4>
								<p class="text-muted m-l-30"><?= $customer->address ?>
									<br/> <?= $customer->telp ?>
							</address>
						</div>
					</div>
					<div class="col-md-12">
						<div class="text-center">
							<h3>ORDER PENJUALAN</h3>
							<h4>SO. No: <?= $so->code ?></h4>
						</div>
					</div>
					<div class="col-md-12">
						<div class="table-responsive m-t-40" style="clear: both;">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">No. </th>
										<th class="text-center">NAMA BARANG</th>
										<th class="text-right">Qty</th>
										<th class="text-center">Unit</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1; $total = 0;
										foreach ($so_detail as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-center"><?= $q->name ?></td>
												<td class="text-right"><?= $q->qty ?></td>
												<td class="text-center"><?= $q->symbol ?></td>
											</tr>
										 	<?php
											 $i++;
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-left">NB: <?= $so->description ?></div>
						<div class="pull-right m-t-30 text-right">
							<h3><b></b> </h3>
						</div>
						<div class="clearfix"></div>
						<hr>
					</div>
					<div class="col-md-6">
						<p>
							CATATAN:
							<br>1. Pada saat pengiriman barang, mohon dicantumkan No. PO
							<br>2. Pada saat penagihan, mohon disertakan tanda terima 
							<br>barang dari bagian gudang
						</p>
					</div>
					<div class="col-md-3">
						<p class="text-center">
							CUSTOMER
						</p>
					</div>
					<div class="col-md-3">
						<p class="text-center">
							PEMBUAT
							<br>
							<br>
							<br>
							DESSI
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
