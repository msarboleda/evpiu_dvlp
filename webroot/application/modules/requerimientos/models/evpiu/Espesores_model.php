<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para Espesores
 * 
 * Este modelo se relaciona con la tabla de espesores;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Espesores_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'req_Espesores';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza los espesores en un formato para llenar controles Select
	 * con el plugin 'Select2'.
	 *
	 * Este método se encarga de filtrar los espesores de los productos existentes 
	 * dependiendo del material requerido, y luego organizar la información
	 * en un formato utilizado para mostrarse en un plugin con nombre 'Select2'.
	 *
	 * @param string $material_code Código de material del producto.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function fill_Espesores_select($material_code = NULL, $order = 'asc') {
		if (!isset($material_code)) {
			return FALSE;
		}

		$this->db_evpiu->select('CodEspesor, Espesor');
		$this->db_evpiu->where('CodMaterial', $material_code);
		$this->db_evpiu->order_by('Espesor', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$tams = array();

			$espesores[''] = 'Selecciona un Espesor';

			foreach ($result as $row) {
				$espesores[$row['CodEspesor']] = $row['Espesor'];
			}

			return $espesores;
		}

    return FALSE;
	}
}