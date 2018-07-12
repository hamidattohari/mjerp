<!-- Left navbar-header -->
<div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                        <!-- input-group -->
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
            </span> </div>
                        <!-- /input-group -->
					</li>

<?php
	$size = sizeof($menu);
	for($i = 0; $i < $size; $i++){
		$curr = $menu[$i];
		if($i == 0){
			$prev = null;
			$next = $menu[$i+1];
		}elseif($i == $size - 1){
			$prev = $menu[$i-1];
			$next = null;
		}else{
			$prev = $menu[$i-1];
			$next = $menu[$i+1];
		}

		if($curr->parent_id == 0 && $curr->link != "#"){
			echo '<li> <a href="'.site_url($curr->link).'" class="waves-effect"><i data-icon="P" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">'.$curr->menu.'</span></a> </li>';
		}elseif($curr->parent_id == 0 && $curr->link == "#"){
			echo '<li><a href="javascript:void(0);" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">'.$curr->menu.' <span class="fa arrow"></span></span></a>';
		}elseif($curr->parent_id != 0 ){
			if(($prev == null || $curr->parent_id != $prev->parent_id )){
				echo '<ul class="nav nav-second-level">
							<li> <a href="'.site_url($curr->link).'" class="waves-effect">'.$curr->menu.'</a></li>';
			}elseif(($next == null || $curr->parent_id != $next->parent_id )){
				echo '<li> <a href="'.site_url($curr->link).'" class="waves-effect">'.$curr->menu.'</a> </li>						
					</ul>
				</li>';
			}else{
				echo '<li> <a href="'.site_url($curr->link).'" class="waves-effect">'.$curr->menu.'</a></li>';
			}
		}
	}

?>
<!--
                    <li> <a href="<?=site_url('dashboard');?>" class="waves-effect"><i data-icon="P" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Dashboard</span></a> </li>
                    <li><a href="inbox.html" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Master <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
							<li> <a href="<?=site_url('material_categories');?>" class="waves-effect">Materials Category</a></li>
							<li> <a href="<?=site_url('materials');?>" class="waves-effect">Materials</a></li>
							<li> <a href="<?=site_url('product_categories');?>" class="waves-effect">Products Category</a> </li>							
							<li> <a href="<?=site_url('products');?>" class="waves-effect">Products</a> </li>
							<li> <a href="<?=site_url('usage_categories');?>" class="waves-effect">Usage Categories</a></li>
							<li> <a href="<?=site_url('processes');?>" class="waves-effect">Processes</a></li>	
							<li> <a href="<?=site_url('customers');?>">Customers</a></li>
                            <li> <a href="<?=site_url('vendors');?>" class="waves-effect">Vendors</a> </li>						
                            <li> <a href="<?=site_url('colors');?>" class="waves-effect">Colors</a> </li>						
                        </ul>
					</li>
					<li><a href="inbox.html" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Sales <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?=site_url('projects');?>" class="waves-effect">Sales Order</a> </li>
                            <li> <a href="<?=site_url('shipping');?>" class="waves-effect">Shipping</a> </li>							
                            <li> <a href="<?=site_url('sales_return');?>" class="waves-effect">Return</a> </li>							
                        </ul>
					</li>
					<li><a href="inbox.html" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Purchasing <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?=site_url('purchasing')?>" class="waves-effect">Purchase Lists</a> </li>
                            <li> <a href="<?=site_url('receiving')?>" class="waves-effect">Receive Items</a> </li>			
                            <li> <a href="<?=site_url('purchase_return')?>" class="waves-effect">Return</a> </li>			
                        </ul>
					</li>
					<li><a href="inbox.html" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Production <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?=site_url('work_orders');?>" class="waves-effect">Work Order</a> </li>
                            <li> <a href="<?=site_url('productions');?>" class="waves-effect">Production</a> </li>
							<li> <a href="<?=site_url('pickup_material')?>" class="waves-effect">Pickup Materials</a> </li>
                            <li> <a href="<?=site_url('return_material')?>" class="waves-effect">Return Materials</a> </li>
                            <li> <a href="<?=site_url('product_receiving')?>" class="waves-effect">Product Receiving</a> </li>
                        </ul>
					</li>
					<li><a href="inbox.html" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Inventory <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?=site_url('material_inventory');?>" class="waves-effect">Material Inventory</a> </li>                       
                            <li> <a href="<?=site_url('product_inventory');?>" class="waves-effect">Product Inventory</a> </li>						
                        </ul>
					</li>
					<li><a href="inbox.html" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Settings <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?=site_url('roles');?>" class="waves-effect">Roles</a> </li>
                            <li> <a href="<?=site_url('users');?>" class="waves-effect">Users</a> </li>
							<li> <a href="calendar.html" class="waves-effect">Previllages</a></li>
                            <li> <a href="calendar.html" class="waves-effect">Application</a></li>							
                        </ul>
					</li>
-->                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->
