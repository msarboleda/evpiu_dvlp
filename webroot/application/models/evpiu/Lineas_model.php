<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo de Líneas
 * 
 * Este modelo se relaciona con la tabla de Líneas;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Lineas_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Lineas';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Devuelve toda la información de una línea.
	 *
	 * @param string $line_code Código de línea del producto.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function find_Line($line_code = NULL, $order = 'asc') {
		if (!isset($line_code)) {
			return FALSE;
		}

		$this->db_evpiu->select();
		$this->db_evpiu->where('CodLinea', $line_code);
		$this->db_evpiu->order_by('NomLinea', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$row = $query->row();

			return $row;
		}

    return FALSE;
	}
}