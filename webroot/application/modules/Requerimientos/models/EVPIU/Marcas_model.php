<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Marcas
 * 
 * Este modelo se relaciona con la tabla de Marcas.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class Marcas_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Marcas';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza las marcas de productos existentes perfectamente 
	 * para que se muestren en el plugin 'Select2'
	 *
	 * @param string $term
	 * @param string $order
	 *
	 * @return array|bool
	 */
	public function fill_remote_Marcas_select($term = NULL, $order = 'asc') {
		if (!isset($term)) {
			return FALSE;
		}

		$this->db_evpiu->select('idMarca, NomMarca');
		$this->db_evpiu->like('NomMarca', $term);
		$this->db_evpiu->order_by('NomMarca', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$marcas = array();

			foreach ($result as $row) {
				$marcas[] = array('id' => $row['idMarca'], 'text' => $row['NomMarca']);    
			}
		} else {
   		$marcas[] = array('id' => '0', 'text' => 'Esta marca no se encuentra disponible.');
		}

		return $marcas;
	}
}