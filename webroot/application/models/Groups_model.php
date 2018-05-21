<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Grupos
 * 
 * Este modelo se relaciona con la tabla de groups en $db => 'users'.
 * Tiene la funcionalidad de ser una extensión del modelo Ion_auth para no
 * modificar la librería y romper el estándar.
 */
class Groups_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'groups';
	}

	/**
	 * Organiza los grupos existentes perfectamente 
	 * para que se muestren en un control 'Select'
	 *
	 * @return array|bool
	 */
	public function fill_Groups_select() {
		$this->db->select('id, name');
		$this->db->order_by('name', 'asc');

		$query = $this->db->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$groups = array();

			$groups[''] = 'Seleccione un Grupo...';

			foreach ($result as $row) {
				$groups[$row['id']] = $row['name'];
			}

			return $groups;
		}

    return false;
	}
}