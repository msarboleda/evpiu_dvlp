<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo de Características
 * 
 * Este modelo se relaciona con la tabla de Características;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Caracteristicas_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'req_Caracteristicas';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza las características en un formato para llenar controles Select
	 * con el plugin 'Select2'.
	 *
	 * Este método se encarga de filtrar las características de los productos existentes 
	 * dependiendo de línea y sublínea requeridos, y luego organizar la información
	 * en un formato utilizado para mostrarse en un plugin con nombre 'Select2'.
	 *
	 * @param string $line_code Código de línea del producto.
	 * @param string $subline_code Código de sublínea del producto.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function fill_Caracteristicas_select($line_code = NULL, $subline_code = NULL, $order = 'asc') {
		if (!isset($line_code) || !isset($subline_code)) {
			return FALSE;
		}

		$this->db_evpiu->select('CodCaracteristica, NomCaracteristica');
		$this->db_evpiu->where('CodLinea', $line_code);
		$this->db_evpiu->where('CodSublinea', $subline_code);
		$this->db_evpiu->order_by('NomCaracteristica', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$caracteristicas = array();

			$caracteristicas[''] = 'Seleccione una Característica';

			foreach ($result as $row) {
				$caracteristicas[$row['CodCaracteristica']] = $row['NomCaracteristica'];
			}

			return $caracteristicas;
		}

    return FALSE;
	}
}