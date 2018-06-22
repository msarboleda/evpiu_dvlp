<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Estados de Requerimientos
 * 
 * Este modelo se relaciona con la tabla de EstadosRequerimientos.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class Estados_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'req_Estados';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza los estados de requerimiento en un formato 
	 * para llenar controles Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de filtrar los estados de requerimientos existentes 
	 * y luego organizar la información en un formato utilizado para mostrarse 
	 * en un plugin con nombre 'Select2'.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function fill_EstadosRequerimientos_select() {
		$this->db_evpiu->select('CodEstado, NomEstado');
		$this->db_evpiu->order_by('NomEstado', 'asc');

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$estados = array();

			$estados['*'] = 'SIN FILTRO';

			foreach ($result as $row) {
				$estados[strtoupper($row['NomEstado'])] = $row['NomEstado'];
			}

			return $estados;
		}

    return FALSE;
	}
}