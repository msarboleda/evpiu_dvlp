<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Estados de Requerimientos
 * 
 * Este modelo se relaciona con la tabla de EstadosRequerimientos.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class EstadosRequerimientos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'EstadosRequerimientos';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza los estados de requerimiento existentes perfectamente 
	 * para que se muestren en un control 'Select'
	 *
	 * @return array|bool
	 */
	public function fill_EstadosRequerimientos_select() {
		$this->db_evpiu->select('id, NomEstado');
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