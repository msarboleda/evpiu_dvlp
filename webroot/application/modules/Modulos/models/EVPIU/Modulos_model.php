<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Modulos
 * 
 * Este modelo se relaciona con la tabla de Módulos.
 * Tiene la funcionalidad de mostrar todo tipo de dato relacionado con
 * esta tabla.
 */
class Modulos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Modulos M';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * @return object Modulos
	 *	or
	 * @return false En caso de que el Query no tenga ningún resultado
	 */
	public function get_Modulos() {
		$query = $this->db_evpiu->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		}

    return false;
	}
}