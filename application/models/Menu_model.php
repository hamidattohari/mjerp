<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends MY_Model {

	protected $_t = 'menu';

	protected $_menus = array(
		'Dashboard' => array(
			'link' => 'dashboard',
			'child' => array()
		),
		'Master' => array(
			'link' => '#',
			'child' => array(
				'Material Category' => array(
					'link' => 'material_categories',
				),
				'Materials' => array(
					'link' => 'materials',
				),
				'Product Category' => array(
					'link' => 'product_categories',
				),
				'Products' => array(
					'link' => 'products',
				),
				'Usage Category' => array(
					'link' => 'usage_categories',
				),
				'Processes' => array(
					'link' => 'processes',
				),
				'Machine' => array(
					'link' => 'machine',
				),
				'Customers' => array(
					'link' => 'customers',
				),
				'Vendors' => array(
					'link' => 'vendors',
				),
				'Colors' => array(
					'link' => 'colors',
				),
				'Uom' => array(
					'link' => 'uom',
				),
				'Uom Converter' => array(
					'link' => 'uom_converter',
				),
				'Currency' => array(
					'link' => 'currency',
				)
			)
		), 
		'Sales' => array(
			'link' => '#',
			'child' => array(
				'Sales Order' => array(
					'link' => 'projects',
				),
				'Shipping' => array(
					'link' => 'shipping',
				),
				'Return' => array(
					'link' => 'sales_return',
				)
			)
		),
		'Purchasing' => array(
			'link' => '#',
			'child' => array(
				'Purchase Order' => array(
					'link' => 'purchasing',
				),
				'Receiving' => array(
					'link' => 'receiving',
				),
				'Return' => array(
					'link' => 'purchase_return',
				)
			)
		),
		'Production' => array(
			'link' => '#',
			'child' => array(
				'Work Order' => array(
					'link' => 'work_orders',
				),
				'Pickup Material' => array(
					'link' => 'pickup_material',
				),
				'Return Material' => array(
					'link' => 'return_material',
				),
				'Pickup Non Material' => array(
					'link' => 'pickup_nonmaterial',
				),
				'Return Non Material' => array(
					'link' => 'return_nonmaterial',
				),
				'Product Movement' => array(
					'link' => 'product_movement',
				),
				'HPP' => array(
					'link' => 'hpp',
				)
			)
		),
		'Inventory' => array(
			'link' => '#',
			'child' => array(
				'Material Inventory' => array(
					'link' => 'material_inventory',
				),
				'Product Inventory' => array(
					'link' => 'product_inventory',
				),
				'Unfinished Product Inventory' => array(
					'link' => 'unfinished_product_inventory',
				)
			)
		),
		'Report' => array(
			'link' => '#',
			'child' => array(
				'Purchase Report' => array(
					'link' => 'report/purchase',
				),
				'Receiving Report' => array(
					'link' => 'report/receiving',
				),
				'Purchase Return Report' => array(
					'link' => 'report/purchase_return',
				),
				'Sales Report' => array(
					'link' => 'report/sales',
				),
				'Shipping Report' => array(
					'link' => 'report/shipping',
				),
				'Sales Return Report' => array(
					'link' => 'report/sales_return',
				)
			)
		),
		'Setting' => array(
			'link' => '#',
			'child' => array(
				'Roles' => array(
					'link' => 'roles',
				),
				'Privilege' => array(
					'link' => 'previllage',
				),
				'Users' => array(
					'link' => 'users',
				),
				'Application' => array(
					'link' => 'application',
				)
			)
		)
	);

	public function add_initial_menu(){
		if($this->count_all_data() == 0){
			$this->add_menu();
		}
	}

	public function add_menu(){
		$i = 0;
		foreach($this->_menus as $key=>$value){
			$data = array(
				'menu' => $key,
				'parent_id' => 0,
				'link' => $value['link']
			);
			$pid = $this->add_id($data);
			if(isset($value['child'])){
				foreach($value['child'] as $k => $v){
					$data = array(
						'menu' => $k,
						'parent_id' => $pid,
						'link' => $v['link']
					);
					$status = $this->add($data);
				}
			}
		}
	}

}
