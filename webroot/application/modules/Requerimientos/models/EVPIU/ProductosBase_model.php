<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para Productos Base
 * 
 * Este modelo se relaciona con la tabla de productos base;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class ProductosBase_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'req_ProductosBase';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza los productos base en un formato para llenar controles Select
	 * con el plugin 'Select2'.
	 *
	 * Este método se encarga de listar los productos base existentes, 
	 * y luego organizar la información en un formato utilizado para
	 * mostrarse en un plugin con nombre 'Select2'.
	 *
	 * @param string $term Término de la búsqueda en el control <select>.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function fill_remote_Productos_Base_select($term = NULL, $order = 'asc') {
		if (!isset($term)) {
			return FALSE;
		}

		$this->db_evpiu->select('CodPrimario, DescripcionPrimaria');
		$this->db_evpiu->like('DescripcionPrimaria', $term);
		$this->db_evpiu->order_by('DescripcionPrimaria', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$prods_base = array();

			foreach ($result as $row) {
				$prods_base[] = array('id' => $row['CodPrimario'], 'text' => $row['DescripcionPrimaria']);    
			}
		} else {
			$prods_base[] = array('id' => NULL, 'text' => 'Este producto base no se encuentra disponible.');
		}

		return $prods_base;
	}
}