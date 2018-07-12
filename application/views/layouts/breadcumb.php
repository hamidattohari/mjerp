			<div class="row bg-title">
				<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
					<h4 class="page-title"><?=$page_title;?></h4>
				</div>
				<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
					<ol class="breadcrumb">
						<?php
							for($i = 0; $i < sizeof($breadcumb); $i++) {
								if($i == sizeof($breadcumb)-1){
									echo '<li class="active">'.$breadcumb[$i].'</li>';
								}else{
									echo '<li><a href="javascript:void(0);">'.$breadcumb[$i].'</a></li>';
								}
							}
						?>
					</ol>
				</div>
				<!-- /.col-lg-12 -->
			</div>
