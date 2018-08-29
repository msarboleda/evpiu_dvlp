<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Modulos por Grupos
 * 
 * Este modelo se relaciona con la tabla de Módulos por Grupos.
 * Tiene la funcionalidad de mostrar todo tipo de dato relacionado con
 * esta tabla.
 */
class Modulosxgrupos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'ModulosxGrupos';
		$this->db_evpiu = $this->load->database('EVPIU', true);

		$this->load->model('Modulos/EVPIU/Modulos_model', 'Modulos_mdl');
	}

	/**
	 * Asignar un código de módulo a un id. de grupo
	 *
	 * @param string $module_code
	 * @param int $group_id
	 *
	 * @return bool
	 */
	public function assign_Module_to_Group($module_code, $group_id) {
		if (empty($module_code)) {
			$this->ion_auth_model->set_error('enable_module_module_empty');
			return FALSE;
		}

		if (empty($group_id)) {
			$this->ion_auth_model->set_error('enable_module_group_empty');
			return FALSE;
		}

		if ($this->duplicate_check($module_code, $group_id)) {
			$this->ion_auth_model->set_error('enable_module_duplicated_module');
			return FALSE;
		}

		$assign_data['CodModulo'] = $module_code;
		$assign_data['group_id'] = $group_id;

		$module_name = $this->Modulos_mdl->get_Modulo_by_code($module_code)->NomModulo;
		$group_name = $this->ion_auth_model->group($group_id)->row()->name;

		$assign_data['Comentarios'] = "Módulo: $module_name, Grupo: $group_name"; 

		$this->db_evpiu->insert($this->_table, $assign_data);

		$this->ion_auth_model->set_message('relationship_constructed_successful');

		return TRUE;
	}

	/**
	 * Verificación de asignación a grupo ya existente
	 *
	 * @param string $module_code
	 * @param int $group_id
	 *
	 * @return bool
	 */
	public function duplicate_check($module_code = '', $group_id = '') {
		if (empty($module_code) || empty($group_id)) {
			return FALSE;
		}

		return $this->db_evpiu->where('CodModulo', $module_code)
						->where('group_id', $group_id)
						->limit(1)
						->count_all_results($this->_table) > 0;
	}
}