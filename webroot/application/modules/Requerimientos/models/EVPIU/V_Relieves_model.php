<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para la vista de Relieves
 * 
 * Este modelo se relaciona con la vista de relieves;
 * Tiene funciones dedicadas exclusivamente a la vista definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta vista.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class V_relieves_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'V_Relieves';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza los relieves en un formato para llenar controles Select
	 * con el plugin 'Select2'.
	 *
	 * Este método se encarga de filtrar los relieves de los productos existentes 
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
	public function fill_Relieves_select($line_code = NULL, $subline_code = NULL, $order = 'asc') {
		if (!isset($line_code) || !isset($subline_code)) {
			return FALSE;
		}

		$this->db_evpiu->select('CodRelieve, NomRelieve');
		$this->db_evpiu->where('CodLinea', $line_code);
		$this->db_evpiu->where('CodSublinea', $subline_code);
		$this->db_evpiu->order_by('NomRelieve', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$relieves = array();

			$relieves[''] = 'Selecciona un Relieve';

			foreach ($result as $row) {
				$relieves[$row['CodRelieve']] = $row['NomRelieve'];
			}

			return $relieves;
		}

    return FALSE;
	}
}