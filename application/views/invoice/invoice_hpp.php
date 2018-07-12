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
							<h3>LAPORAN HARGA POKOK PRODUKSI</h3>
						</div>
					</div>
					<div class="col-md-6">
						<table>
							<tr>
								<td width="175">Customer</td>
								<td width="10">:</td>
								<td></td>
							</tr>
							<tr>
								<td>Code Produksi</td>
								<td>:</td>
								<td><?= $product->code ?></td>
							</tr>
							<tr>
								<td>Jenis Produksi</td>
								<td>:</td>
								<td><?= $product->name ?></td>
							</tr>
						</table>
					</div>
					<div class="col-md-3"></div>
					<div class="col-md-3">
						Tanggal: <?= $hpp->created_at ?>
					</div>
					<div class="col-md-12">
						<div class="table-responsive m-t-40" style="clear: both;">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">Description </th>
										<th class="text-center">Qty</th>
										<th class="text-center">Tap</th>
										<th class="text-center">Unit</th>
										<th class="text-center">Price</th>
										<th class="text-center">Sub Total</th>
										<th class="text-center">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$grand_total = 0;
										$material_total = 0;
										$btkl_total = 0;
										$bop_total = 0;
									?>
									<!-- MATERIAL -->
									<tr>
										<td class="text-center"><strong>A. Bahan  Baku Utama</strong></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
									</tr>
									<?php
										$i = 1;
										foreach ($hpp_material as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $q['name'] ?></td>
												<?php
													if($q['pick'] != 0){
														$grand_total += $q['total_price'] ;
														$material_total += $q['total_price'] ;	
														?>
														<td class="text-center"><?= $q['used'] ?></td>
														<td class="text-center">-</td>
														<td class="text-center"><?= $q['symbol'] ?></td>
														<td class="text-center">Rp. <?= $q['unit_price'] ?></td>
														<td class="text-center">Rp. <?= $q['total_price'] ?></td>
												<?php		
													}else{
														?>
														<td class="text-center">-</td>
														<td class="text-center"><?= $q['return'] ?></td>
														<td class="text-center"><?= $q['symbol'] ?></td>
														<td class="text-center">-</td>
														<td class="text-center">-</td>
												<?php		
													}
												?>
												
												<td class="text-center"></td>
											</tr>
										 	<?php
										$i++;}
									?>
									<tr>
										<td class="text-center"><strong>Total Material</strong></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"><strong><?=number_format($material_total,2,".",",");?></strong></td>
									</tr>		
									<tr>
										<td class="text-center"><strong>B. BTKL</strong></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
									</tr>
									<?php
										$i = 1;
										foreach ($hpp_btkl as $q) {
											$grand_total += $q['total_price'] ;
											$btkl_total += $q['total_price'] ;
										 	?>
										 	<tr>
												<td class="text-center"><?= $q['processes_id'] ?></td>
												<td class="text-center"><?= $q['qty'] ?></td>
												<td class="text-center"></td>
												<td class="text-center">Hari</td>
												<td class="text-center"><?= $q['price'] ?></td>
												<td class="text-center"><?= $q['total_price'] ?></td>
												<td class="text-center"></td>
											</tr>
										 	<?php
										$i++;}
									?>	
									<tr>
										<td class="text-center"><strong>Total BTKL</strong></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"><strong><?=number_format($btkl_total,2,".",",");?></strong></td>
									</tr>	
									<tr>
										<td class="text-center"><strong>C. BOP</strong></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
									</tr>
									<tr>
										<td class="text-center">Penyusutan</td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"><?=$hpp->penyusutan;?></td>
										<td class="text-center"><?=$hpp->penyusutan;?></td>
										<td class="text-center"></td>
									</tr>
									<tr>
										<td class="text-center">Listrik</td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"><?=$hpp->listrik;?></td>
										<td class="text-center"><?=$hpp->listrik;?></td>
										<td class="text-center"></td>
									</tr>
									<tr>
										<td class="text-center">Lain-lain</td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"><?=$hpp->lain_lain;?></td>
										<td class="text-center"><?=$hpp->lain_lain;?></td>
										<td class="text-center"></td>
									</tr>
									<?php $grand_total = $grand_total + $hpp->penyusutan + $hpp->listrik + $hpp->lain_lain?>
									<?php $bop_total = $hpp->penyusutan + $hpp->listrik + $hpp->lain_lain?>
									<tr>
										<td class="text-center"><strong>Total BOP</strong></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"><strong><?=number_format($bop_total,2,".",",");?></strong></td>
									</tr>	
									<tr>
										<td class="text-center"><strong>Jumlah Total</strong></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"><strong><?= number_format($grand_total,2,".",",");?></strong></td>
									</tr>	
									<tr>
										<td class="text-center"><strong>D. Hasil Jadi</strong></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
										<td class="text-center"></td>
									</tr>
									<?php
										$i = 1;
										foreach ($hpp_product_result as $q) {
										 	?>
										 	<tr>
												<td class="text-center"><?= $q['description'] ?></td>
												<td class="text-center"><?= $q['qty'] ?></td>
												<td class="text-center"></td>
												<td class="text-center"><?= $q['unit'] ?></td>
												<td class="text-center"></td>
												<td class="text-center"><?=  number_format(($q['pct']/100)*$grand_total, 2, ".", ",");?></td>
												<td class="text-center"><?= number_format($q['pct'], 2, ".", ",")." %" ?></td>
											</tr>
										 	<?php
										$i++;}
									?>	
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pull-left"></div>
						<div class="pull-right m-t-30 text-right">
						</div>
						<div class="clearfix"></div>
						<hr>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- .row -->
	<!-- /.row -->
</div>
<!-- /.container-fluid -->
