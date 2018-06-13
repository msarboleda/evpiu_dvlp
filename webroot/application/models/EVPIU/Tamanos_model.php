<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo de Tamaños
 * 
 * Este modelo se relaciona con la tabla de Tamaños;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Tamanos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Tamanos';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Devuelve toda la información de un tamaño.
	 *
	 * @param string $line_code Código de línea del producto.
	 * @param string $subline_code Código de sublínea del producto.
	 * @param string $material_code Código de material del producto.
	 * @param string $size_code Código de tamaño del producto.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function find_Size($line_code = NULL, $subline_code = NULL, $material_code = NULL, $size_code = NULL, $order = 'asc') {
		if (!isset($line_code) || !isset($subline_code) || !isset($material_code) || !isset($size_code)) {
			return FALSE;
		}

		$this->db_evpiu->select();
		$this->db_evpiu->where('CodLinea', $line_code);
		$this->db_evpiu->where('CodSublinea', $subline_code);
		$this->db_evpiu->where('CodMaterial', $material_code);
		$this->db_evpiu->where('CodTamano', $size_code);
		$this->db_evpiu->order_by('Denominacion', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$row = $query->row();

			return $row;
		}

    return FALSE;
	}
}