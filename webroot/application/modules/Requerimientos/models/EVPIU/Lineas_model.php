<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Líneas
 * 
 * Este modelo se relaciona con la tabla de Líneas.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class Lineas_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Lineas';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza las líneas de productos existentes perfectamente 
	 * para que se muestren en el plugin 'Select2'
	 *
	 * @param string $order
	 *
	 * @return array|bool
	 */
	public function fill_Lineas_select($order = 'asc') {
		$this->db_evpiu->select('CodLinea, NomLinea');
		$this->db_evpiu->where('Estado !=', 0);
		$this->db_evpiu->order_by('NomLinea', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$lineas = array();

			$lineas[''] = 'Seleccione una Línea';

			foreach ($result as $row) {
				$lineas[$row['CodLinea']] = $row['NomLinea'];
			}

			return $lineas;
		}

    return FALSE;
	}
}