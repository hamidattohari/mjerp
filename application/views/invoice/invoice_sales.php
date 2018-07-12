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
									if ($so->vat == 1){
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
							<h2><strong>ORDER PENJUALAN</strong></h2>
							<h3><strong>SO. No: <?= $so->code ?></strong></h3>
						</div>
						<h5>No. PO Cust: <?= $so->po_cust ?></h5>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="clear: both; min-height: 178px;">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th class="text-center" style="width:5%"><strong>NO. </strong></th>
										<th class="text-center" style="width:35%"><strong>ITEM</strong></th>
										<th class="text-center" style="width:5%"><strong>QTY</strong></th>
										<th class="text-center" style="width:5%"><strong>UNIT</strong></th>
										<th class="text-center" style="width:10%"><strong>PRICE</strong></th>
										<th class="text-center" style="width:10%"><strong>TOTAL</strong></th>
										<th class="text-center" style="width:30%"><strong>NOTE</strong></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1; $total = 0;
										foreach ($so_detail as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-left"><?= $q->name ?></td>
												<td class="text-center"><?= number_format($q->qty, 0, ",", ".") ?></td>
												<td class="text-center"><?= $q->symbol ?></td>
												<td class="text-right"><?= number_format($q->price, 0, ",", ".") ?></td>
												<td class="text-right"><?= number_format($q->total_price, 0, ",", ".") ?></td>
												<td class="text-left"><?= $q->note ?></td>												
											</tr>
										 	<?php
											 $i++;$total += $q->total_price;
										}
									?>
								</tbody>
							</table>
							<p>Cara Pembayaran: </p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-right m-t-30 text-right">
							<table>
								<tr>
									<td><h5><b>Subtotal </b></h5></td>
									<td><h5>&nbsp;:&nbsp;</h5></td>
									<td><h5>&nbsp;Rp.&nbsp;</h5></td>
									<td><h5 style="text-align:right; width:120px;"><?= number_format($total, 0, ",", ".") ?></h5></td>
								</tr>
								<?php if ($so->vat == 1) { ?>
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
									<?php if ($so->vat == 1) { ?>
									<td><h3 style="text-align:right; width:120px;"><?= number_format($total+($total*0.1), 0, ",", ".") ?></h3></td>
									<?php } else { ?>
									<td><h3 style="text-align:right; width:120px;"><?= number_format($total, 0, ",", ".") ?></h3></td>
									<?php } ?>
								</tr>
							</table>
						</div>
						<div class="clearfix"></div>
						<?php if ($so->vat == 1) { ?>
						<div class="pull-left nb">NB: <?= $so->description ?></div>
						<?php } else { ?>
						<div class="pull-left nbnp">NB: <?= $so->description ?></div>
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
							CUSTOMER
						</h4>
					</div>
					<div class="bottom">
						<h4 class="text-center">
							PEMBUAT
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
	<div class="row bg-title">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 pull-right">
			<div class="text-right">
				<button id="print-invoice2" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
			</div>
		</div>
	</div>
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
							<address>
								<?php
									if ($so->vat == 1){
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
							<h2><strong>ORDER PENJUALAN</strong></h2>
							<h3><strong>SO. No: <?= $so->code ?></strong></h3>
						</div>
						<h5>No. PO Cust: </h5>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="clear: both; min-height: 150px;">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th class="text-center" style="width:5%"><strong>NO. </strong></th>
										<th class="text-center" style="width:35%"><strong>ITEM</strong></th>
										<th class="text-center" style="width:5%"><strong>QTY</strong></th>
										<th class="text-center" style="width:5%"><strong>UNIT</strong></th>
										<th class="text-center" style="width:30%"><strong>NOTE</strong></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1; $total = 0;
										foreach ($so_detail as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $i ?></td>
												<td class="text-left"><?= $q->name ?></td>
												<td class="text-center"><?= number_format($q->qty, 0, ",", ".") ?></td>
												<td class="text-center"><?= $q->symbol ?></td>
												<td class="text-left"><?= $q->note ?></td>												
											</tr>
										 	<?php
											 $i++;$total += $q->total_price;
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
									<td><h5>&nbsp;&nbsp;</h5></td>
									<td><h5 style="text-align:right; width:120px;"></h5></td>
								</tr>
								<?php if ($so->vat == 1) { ?>
								<tr>
									<td><h5><b></b></h5></td>
									<td><h5>&nbsp;&nbsp;</h5></td>
									<td><h5 style="text-align:right; width:120px;"></h5></td>
								</tr>
								<?php } ?>
								<tr>
									<td><h3><b> </b></h3></td>
									<td><h5>&nbsp;&nbsp;</h5></td>
									<?php if ($so->vat == 1) { ?>
									<td><h3 style="text-align:right; width:120px;"></h3></td>
									<?php } else { ?>
									<td><h3 style="text-align:right; width:120px;"></h3></td>
									<?php } ?>
								</tr>
							</table>
						</div>
						<div class="clearfix"></div>
						<?php if ($so->vat == 1) { ?>
						<div class="pull-left nb">NB: <?= $so->description ?></div>
						<?php } else { ?>
						<div class="pull-left nbnp">NB: <?= $so->description ?></div>
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
							CUSTOMER
						</h4>
					</div>
					<div class="bottom">
						<h4 class="text-center">
							PEMBUAT
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
<!-- /.container-fluid -->
