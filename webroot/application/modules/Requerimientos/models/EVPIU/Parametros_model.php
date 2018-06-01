<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Parámetros
 * 
 * Este modelo se relaciona con la tabla de Parámetros.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class Parametros_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'req_Parametros';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza los parámetros existentes perfectamente 
	 * para que se muestren en el plugin 'Select2'
	 *
	 * @param string $order
	 *
	 * @return array|bool
	 */
	public function fill_Parametros_select($order = 'asc') {
		$this->db_evpiu->select('idParametro, NomParametro');
		$this->db_evpiu->order_by('NomParametro', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$parametros = array();

			$parametros[''] = 'Selecciona un Parámetro';

			foreach ($result as $row) {
				$parametros[$row['idParametro']] = $row['NomParametro'];
			}

			return $parametros;
		}

    return FALSE;
	}
}