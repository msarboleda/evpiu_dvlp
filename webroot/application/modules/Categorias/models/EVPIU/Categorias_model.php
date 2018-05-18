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

	/**
	 * Obtiene el identificador de la última categoría existente.
	 *
	 * @return bool|int
	 */
	public function get_last_Categoria_id() {
		$this->db_evpiu->select('id');
		$this->db_evpiu->order_by('id', 'desc');
		$this->db_evpiu->limit(1);

		$query = $this->db_evpiu->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->row()->id;
		}

		return FALSE;
	}

	/**
	 * Crear una categoría
	 *
	 * @param int $category_code
	 * @param string $category_name
	 * @param array $data
	 *
	 * @return bool
	 */
	public function create_Categoria($category_code, $category_name, $data = array()) {
		if (empty($category_code)) {
			$this->ion_auth_model->set_error('create_category_code_empty');
			return FALSE;
		}

		if (empty($category_name)) {
			$this->ion_auth_model->set_error('create_category_name_empty');
			return FALSE;
		}

		if (empty($data) || !is_array($data)) {
			$this->ion_auth_model->set_error('create_category_data_empty');
			return FALSE;
		}

		if ($this->duplicate_check('CodCategoria', $category_code)) {
			$this->ion_auth_model->set_error('create_category_duplicated_code');
			return FALSE;
		}

		if ($this->duplicate_check('NomCategoria', $category_name)) {
			$this->ion_auth_model->set_error('create_category_duplicated_name');
			return FALSE;
		}

		$additional_data = array(
			'CodCategoria' => $category_code,
			'NomCategoria' => $category_name
		);

		$category_data = array_merge($additional_data, $data);

		$this->db_evpiu->insert($this->_table, $category_data);

		$this->ion_auth_model->set_message('category_create_successful');

		return TRUE;
	}

	/**
	 * Verificación de duplicación de categoría por una columna de identidad
	 *
	 * @param string $identity_column
	 * @param int $category_code
	 *
	 * @return bool
	 */
	public function duplicate_check($identity_column = '', $category_code = '') {
		if (empty($identity_column) || empty($category_code)) {
			return FALSE;
		}

		return $this->db_evpiu->where($identity_column, $category_code)
						->limit(1)
						->count_all_results($this->_table) > 0;
	}
}