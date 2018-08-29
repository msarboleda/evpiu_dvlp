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
	 * Obtiene información de una categoría específica
	 *
	 * @param int $category_id
	 *
	 * @return array|bool
	 */
	public function get_Categoria($category_id = NULL) {
		if (!isset($category_id)) {
			return FALSE;
		}

		$category = $this->db_evpiu->get_where($this->_table, array('id' => $category_id));

		if ($category->num_rows() > 0) {
			return $category->row();
		}

		return FALSE;
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
	 * Actualiza una categoría
	 *
	 * @param int $category_id
	 * @param array $data
	 *
	 * @return bool
	 */
	public function update_Categoria($category_id = FALSE, $data = array()) {
		if (empty($category_id)) {
			$this->ion_auth_model->set_error('edit_category_id_empty');
			return FALSE;
		}

		if (empty($data) || !is_array($data)) {	
			$this->ion_auth_model->set_error('edit_category_data_empty');
			return FALSE;
		}

		$this->db_evpiu->update($this->_table, $data, array('id' => $category_id));

		$this->ion_auth_model->set_message('category_update_successful');

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

	/**
	 * Organiza las categorías existentes perfectamente 
	 * para que se muestren en un control 'Select'
	 *
	 * @return array|bool
	 */
	public function fill_Categorias_select() {
		$this->db_evpiu->select('CodCategoria, NomCategoria');
		$this->db_evpiu->order_by('NomCategoria', 'asc');

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$categorias = array();

			$categorias[''] = 'Seleccione una Categoría...';

			foreach ($result as $row) {
				$categorias[$row['CodCategoria']] = $row['NomCategoria'];
			}

			return $categorias;
		}

    return false;
	}
}