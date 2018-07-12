<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TableField{
    
    var $columns;

    function __construct(){
        $this->columns = array();
    }

    function addColumn($data_field, $data_align, $label){
        $column = array(
            'data_field' => $data_field,
            'data_align' => $data_align,
            'label' => $label
        );
        array_push($this->columns, $column);
    }

    function getColumns(){
        return $this->columns;
    }
}
