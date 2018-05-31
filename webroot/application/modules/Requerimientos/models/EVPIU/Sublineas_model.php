<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Sublíneas
 * 
 * Este modelo se relaciona con la tabla de Sublíneas.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class Sublineas_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Sublineas';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza las sublíneas de productos existentes perfectamente 
	 * para que se muestren en el plugin 'Select2'
	 *
	 * @param string $line_code
	 * @param string $order
	 *
	 * @return array|bool
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