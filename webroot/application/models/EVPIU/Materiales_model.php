<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo de Materiales
 * 
 * Este modelo se relaciona con la tabla de Materiales;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Materiales_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Materiales';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Devuelve toda la información de un material.
	 *
	 * @param string $material_code Código de material del producto.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function find_Material($material_code = NULL, $order = 'asc') {
		if (!isset($material_code)) {
			return FALSE;
		}

		$this->db_evpiu->select();
		$this->db_evpiu->where('CodMaterial', $material_code);
		$this->db_evpiu->order_by('NomMaterial', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$row = $query->row();

			return $row;
		}

    return FALSE;
	}
}