<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Categorias
 * 
 * Este modelo se relaciona con la tabla de Categorías.
 * Tiene la funcionalidad de mostrar todo tipo de dato relacionado con
 * esta tabla.
 */
class Categorias_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Categorias';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Obtiene toda la información de todos las Categorías existentes.
	 *
	 * @return bool|object
	 */
	public function get_Categorias() {
		$query = $this->db_evpiu->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		}

    return false;
	}
}