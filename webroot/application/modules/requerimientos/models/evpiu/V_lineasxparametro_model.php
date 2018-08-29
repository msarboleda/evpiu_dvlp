<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Vista de Líneas por Parámetro
 * 
 * Este modelo se relaciona con la Vista de Líneas por Parámetro.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta vista.
 */
class V_lineasxparametro_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'V_LineasxParametro';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Organiza las líneas dependiendo del parámetro perfectamente 
	 * para que se muestren en el plugin 'Select2'
	 *
	 * @param string $param
	 * @param string $order
	 *
	 * @return array|bool
	 */
	public function fill_Lineas_x_Parametro_select($param = NULL, $order = 'asc') {
		if (!isset($param)) {
			return FALSE;
		}

		$this->db_evpiu->select('CodLinea, NomLinea');
		$this->db_evpiu->where('CodParametro', $param);
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