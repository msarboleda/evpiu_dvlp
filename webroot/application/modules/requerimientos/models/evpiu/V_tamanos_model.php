<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para la vista de Tamaños
 * 
 * Este modelo se relaciona con la vista de tamaños;
 * Tiene funciones dedicadas exclusivamente a la vista definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta vista.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class V_tamanos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'V_Tamanos';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza los tamaños en un formato para llenar controles Select
	 * con el plugin 'Select2'.
	 *
	 * Este método se encarga de filtrar los tamaños de los productos existentes 
	 * dependiendo de línea, sublínea y material requeridos, y luego organizar la información
	 * en un formato utilizado para mostrarse en un plugin con nombre 'Select2'.
	 *
	 * @param string $line_code Código de línea del producto.
	 * @param string $subline_code Código de sublínea del producto.
	 * @param string $material_code Código de material del producto.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function fill_Tamanos_select($line_code = NULL, $subline_code = NULL, $material_code = NULL, $order = 'asc') {
		if (!isset($line_code) || !isset($subline_code) || !isset($material_code)) {
			return FALSE;
		}

		$this->db_evpiu->select('CodTamano, Denominacion');
		$this->db_evpiu->where('CodLinea', $line_code);
		$this->db_evpiu->where('CodSublinea', $subline_code);
		$this->db_evpiu->where('CodMaterial', $material_code);
		$this->db_evpiu->order_by('Denominacion', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$tams = array();

			$tams[''] = 'Selecciona un Tamaño';

			foreach ($result as $row) {
				$tams[$row['CodTamano']] = $row['Denominacion'];
			}

			return $tams;
		}

    return FALSE;
	}
}