<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Modulos por Categoría
 * 
 * Este modelo se relaciona con la tabla de Módulos por Categoría.
 * Tiene la funcionalidad de mostrar todo tipo de dato relacionado con
 * esta tabla.
 */
class ModulosxCategoria_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'ModulosxCategoria';
		$this->db_evpiu = $this->load->database('EVPIU', true);

		$this->load->model('Modulos/EVPIU/Modulos_model', 'Modulos_mdl');
		$this->load->model('Categorias/EVPIU/Categorias_model', 'Categorias_mdl');
	}

	/**
	 * Asignar un código de categoría a un código de módulo
	 *
	 * @param string $module_code
	 * @param string $category_code
	 *
	 * @return bool
	 */
	public function assign_Category_to_Module($module_code, $category_code) {
		if (empty($module_code)) {
			$this->ion_auth_model->set_error('enable_category_module_empty');
			return FALSE;
		}

		if (empty($category_code)) {
			$this->ion_auth_model->set_error('enable_category_category_empty');
			return FALSE;
		}

		if ($this->duplicate_check($module_code)) {
			$this->ion_auth_model->set_error('enable_category_duplicated_module');
			return FALSE;
		}

		$assign_data['CodModulo'] = $module_code;
		$assign_data['CodCategoria'] = $category_code;

		$module_name = $this->Modulos_mdl->get_Modulo_by_code($module_code)->NomModulo;
		$category_name = $this->Categorias_mdl->get_Categoria($category_code)->NomCategoria;

		$assign_data['Comentarios'] = "Módulo: $module_name, Categoría: $category_name"; 

		$this->db_evpiu->insert($this->_table, $assign_data);

		$this->ion_auth_model->set_message('relationship_constructed_successful');

		return TRUE;
	}

	/**
	 * Verificación de asignación a módulo existente
	 *
	 * @param string $module_code
	 *
	 * @return bool
	 */
	public function duplicate_check($module_code = '') {
		if (empty($module_code)) {
			return FALSE;
		}

		return $this->db_evpiu->where('CodModulo', $module_code)
						->limit(1)
						->count_all_results($this->_table) > 0;
	}
}