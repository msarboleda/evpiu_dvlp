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

		$this->_table = 'Modulos';
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

	/**
	 * Obtiene información de un módulo específico
	 *
	 * @param int $module_id
	 *
	 * @return array|bool
	 */
	public function get_Modulo($module_id = NULL) {
		if (isset($module_id)) {
			$module = $this->db_evpiu->get_where($this->_table, array('id' => $module_id));

			if ($module->num_rows() > 0) {
				return $module->row();
			}

			return FALSE;
		}

		return FALSE;
	}

	/**
	 * Actualiza un módulo
	 *
	 * @param int $module_id
	 * @param array $data
	 *
	 * @return bool
	 */
	public function update_Modulo($module_id = FALSE, $data = array()) {
		if (empty($module_id)) {
			$this->ion_auth_model->set_error('edit_module_id_empty');
			return FALSE;
		}

		if (empty($data) || !is_array($data)) {	
			$this->ion_auth_model->set_error('edit_module_data_empty');
			return FALSE;
		}

		$this->db_evpiu->update($this->_table, $data, array('id' => $module_id));

		$this->ion_auth_model->set_message('module_update_successful');

		return TRUE;
	}
}