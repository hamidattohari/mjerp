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
							<img src="<?= asset_url($logo->logo_path); ?>">
						</div>
						<div class="pull-left">
							<address>
								<?php
									if ($purchasing->vat == 1){
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
								<h4 class="font-bold"><?= $vendor->name ?></h4>
								<?php $address = explode(",", $vendor->address)?>
								<p class="text-muted"><?= $address[0] ?></p>
								<p class="text-muted"><?= $vendor->telp ?></p>
							</address>
						</div>
					</div>
					<div class="col-md-12">
						<div class="text-center">
							<address>
								<h3><b style="font-size:30px;">ORDER PEMBELIAN</b></h3>
								<p>PO. No: <?= $purchasing->code ?></p>
							</address>
						</div>
						<h5>Mohon dikirimkan kepada kami tanggal : <?= explode(" ", $purchasing->delivery_date)[0] ?></h5>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="clear: both; min-height: 178px;">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th class="text-center" style="width:5%;"><strong>No. </strong></th>
										<th class="text-center" style="width:20%;"><strong>NAMA BARANG</strong></th>
										<th class="text-center" style="width:10%;"><strong>Qty</strong></th>
										<th class="text-center" style="width:10%;"><strong>Unit</strong></th>
										<th class="text-center" style="width:10%;"><strong>Price</strong></th>
										<th class="text-center" style="width:10%;"><strong>Discount</strong></th>
										<th class="text-center" style="width:10%;"><strong>Sub Total</strong></th>
										<th class="text-center" style="width:25%;"><strong>Note</strong></th>

									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1; $total = 0;
										foreach ($purchase_detail as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-left"><?= $q->name ?></td>
												<td class="text-center"><?= number_format($q->qty, 0, ",", ".") ?></td>
												<td class="text-center"><?= $q->uom ?></td>
												<td class="text-right"><?= number_format($q->price, 0, ",", ".") ?></td>
												<td class="text-right"><?= number_format($q->discount, 0, ",", ".") ?></td>
												<td class="text-right"><?= number_format($q->total_price-$q->discount, 0, ",", ".") ?></td>
												<td class="text-left"><?= $q->note ?></td>	
											</tr>
										 	<?php
											 $i++;$total += $q->total_price-$q->discount;
										}
									?>
								</tbody>
							</table>
							<p>Cara Pembayaran: </p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-right text-right">
							<table>
								<tr>
									<td><h5><b>Subtotal </b></h5></td>
									<td><h5>&nbsp;:&nbsp;</h5></td>
									<td><h5>&nbsp;Rp.&nbsp;</h5></td>
									<td><h5 style="text-align:right; width:120px;"><?= number_format($total, 0, ",", ".") ?></h5></td>
								</tr>
								<?php if ($purchasing->vat == 1) { ?>
								<tr>
									<td><h5><b>Tax 10% </b></h5></td>
									<td><h5>&nbsp;:&nbsp;</h5></td>
									<td><h5>&nbsp;Rp.&nbsp;</h5></td>
									<td><h5 style="text-align:right; width:120px;"><?= number_format($total*0.1, 0, ",", ".") ?></h5></td>
								</tr>
								<?php } ?>
								<tr>
									<td><h3><b>Total </b></h3></td>
									<td><h5>&nbsp;:&nbsp;</h5></td>
									<td><h3>&nbsp;Rp.&nbsp;</h3></td>
									<?php if ($purchasing->vat == 1) { ?>
									<td><h3 style="text-align:right; width:120px;"><?= number_format($total+($total*0.1), 0, ",", ".") ?></h3></td>
									<?php } else { ?>
									<td><h3 style="text-align:right; width:120px;"><?= number_format($total, 0, ",", ".") ?></h3></td>
									<?php } ?>
								</tr>
							</table>
						</div>
						<div class="clearfix"></div>
						<?php if ($purchasing->vat == 1) { ?>
						<div class="pull-left nb">NB: <?= $purchasing->note ?></div>
						<?php } else { ?>
						<div class="pull-left nbnp">NB: <?= $purchasing->note ?></div>
						<?php } ?>
					</div>
					<div class="bottom">
						<p style="font-size:10px;">
							CATATAN:
							<br>1. Pada saat pengiriman barang, mohon dicantumkan No. PO
							<br>2. Pada saat penagihan, mohon disertakan tanda terima 
							<br>barang dari bagian gudang
						</p>
					</div>
					<div class="bottom">
						<div class="table-responsive" style="clear: both; width:300px; overflow: visible;">
							<table class="table table-hover">
								<tbody>
									<tr style="height: 70px;">
										<td>
											<h5 class="text-center">
												SUPPLIER
											</h5>
										</td>
										<td>
											<h5 class="text-center">
												PEMESAN
												<p>
												<br/>
												<br/>
												</p>
												DESSI
											</h5>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- .row -->	
	<!-- /.row -->
	<div class="row bg-title">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 pull-right">
			<div class="text-right">
				<button id="print-invoice2" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
			</div>
		</div>
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-md-12">
			<div class="white-box printableArea2">
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
								<?php
									if ($purchasing->vat == 1){
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
								<h4 class="font-bold"><?= $vendor->name ?></h4>
								<?php $address = explode(",", $vendor->address)?>
								<p class="text-muted"><?= $address[0] ?></p>
								<p class="text-muted"><?= $vendor->telp ?></p>
							</address>
						</div>
					</div>
					<div class="col-md-12">
						<div class="text-center">
							<h2><strong>ORDER PEMBELIAN</strong></h2>
							<h3><strong>PO. No: <?= $purchasing->code ?></strong></h3>
						</div>
						<h5>Mohon dikirimkan kepada kami tanggal : <?= explode(" ", $purchasing->delivery_date)[0] ?></h5>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="clear: both; min-height: 178px;">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th class="text-center"><strong>No. </strong></th>
										<th class="text-center"><strong>NAMA BARANG</strong></th>
										<th class="text-center"><strong>Qty</strong></th>
										<th class="text-center"><strong>Unit</strong></th>
										<th class="text-center"><strong>Note</strong></th>

									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1; $total = 0;
										foreach ($purchase_detail as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-left"><?= $q->name ?></td>
												<td class="text-center"><?= number_format($q->qty, 0, ",", ".") ?></td>
												<td class="text-center"><?= $q->uom ?></td>
												<td class="text-left"><?= $q->note ?></td>	
											</tr>
										 	<?php
											 $i++;$total += $q->total_price-$q->discount;
										}
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
								<?php if ($purchasing->vat == 1) { ?>
								<tr>
									<td><h5><b></b></h5></td>
									<td><h5></h5></td>
									<td><h5 style="text-align:right; width:120px;"></h5></td>
								</tr>
								<?php } ?>
								<tr>
									<td><h3><b></b></h3></td>
									<td><h5></h5></td>
									<?php if ($purchasing->vat == 1) { ?>
									<td><h3 style="text-align:right; width:120px;"></h3></td>
									<?php } else { ?>
									<td><h3 style="text-align:right; width:120px;"></h3></td>
									<?php } ?>
								</tr>
							</table>
						</div>
						<div class="clearfix"></div>
						<?php if ($purchasing->vat == 1) { ?>
						<div class="pull-left nb">NB: <?= $purchasing->note ?></div>
						<?php } else { ?>
						<div class="pull-left nbnp">NB: <?= $purchasing->note ?></div>
						<?php } ?>
					</div>
					<div class="bottom">
						<p>
							CATATAN:
							<br>1. Pada saat pengiriman barang, mohon dicantumkan No. PO
							<br>2. Pada saat penagihan, mohon disertakan tanda terima 
							<br>barang dari bagian gudang
						</p>
					</div>
					<div class="bottom">
						<h4 class="text-center">
							SUPPLIER
						</h4>
					</div>
					<div class="bottom">
						<h4 class="text-center">
							PEMESAN
							<p>
							<br/>
							<br/>
							</p>
							DESSI
						</h4>
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- .row -->
	<!-- /.row -->
</div>
</div>

<!-- /.container-fluid -->
