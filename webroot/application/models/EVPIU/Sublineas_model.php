<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo de Sublíneas
 * 
 * Este modelo se relaciona con la tabla de Sublíneas;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Sublineas_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Sublineas';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Devuelve toda la información de una sublínea.
	 *
	 * @param string $line_code Código de línea del producto.
	 * @param string $subline_code Código de sublínea del producto.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function find_Subline($line_code = NULL, $subline_code = NULL, $order = 'asc') {
		if (!isset($line_code) || !isset($subline_code)) {
			return FALSE;
		}

		$this->db_evpiu->select();
		$this->db_evpiu->where('CodLinea', $line_code);
		$this->db_evpiu->where('CodSublinea', $subline_code);
		$this->db_evpiu->order_by('NomSublinea', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$row = $query->row();

			return $row;
		}

    return FALSE;
	}

	/**
	 * Organiza las sublíneas de productos existentes perfectamente 
	 * para que se muestren en el plugin 'Select2'.
	 *
	 * Este método se encarga de filtrar las sublíneas de los productos existentes 
	 * dependiendo de la línea requerida, y luego organiza la información
	 * en un formato utilizado para mostrarse en un plugin con nombre 'Select2'.
	 *
	 * @param string $line_code Código de línea del producto.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function fill_Sublineas_select($line_code = NULL, $order = 'asc') {
		$this->db_evpiu->select('CodSublinea, NomSublinea');
		$this->db_evpiu->where('CodLinea', $line_code);
		$this->db_evpiu->where('Estado !=', 0);
		$this->db_evpiu->order_by('NomSublinea', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$sublineas = array();

			$sublineas[''] = 'Seleccione una Sublínea';

			foreach ($result as $row) {
				$sublineas[$row['CodSublinea']] = $row['NomSublinea'];
			}

			return $sublineas;
		}

    return FALSE;
	}
}