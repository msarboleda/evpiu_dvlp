<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Categorías
 */
class Categorias extends MX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->helper('language');
		$this->load->model('Auth/EVPIU/ModulosxCategoriasxGrupos_model');
		$this->load->model('Categorias/EVPIU/Categorias_model', 'Categorias_mdl');
		// Libreria para cargar datos en el Header
		$this->load->library('header');
		// Inicio y final del contenido de los errores de form_validation.
		$this->form_validation->set_error_delimiters('', '<br>');

		$this->lang->load('categorias');
	}

	/*
	 * Página principal de Módulos
	 */
	public function index() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		} else {
			// Nombre de módulo que se muestra en la barra de navegación
			$header_data['module_name'] = lang('index_heading');
			// Categorías con su respectiva cantidad de módulos que se permiten a los grupos del usuario actual
			$header_data['Categorias'] = $this->header->cargarCategorias_Modulos()['Categorias'];
			// Módulos que se permiten a los grupos del usuario actual
			$header_data['Modulos'] = $this->header->cargarCategorias_Modulos()['Modulos'];

			if (!$header_data['Categorias'] || !$header_data['Modulos']) {
				return show_error('Ocurrió un error en la carga de sus aplicaciones asignadas.');
			}

			// Establecer un mensaje si hay un error de datos flash
			$view_data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			// Listar categorías
			$view_data['categories_list'] = $this->Categorias_mdl->get_Categorias();
			
			$this->load->view('headers' . DIRECTORY_SEPARATOR . 'header_main_dashboard', $header_data);
			$this->load->view('categorias' . DIRECTORY_SEPARATOR . 'index', $view_data);
			$this->load->view('footers' . DIRECTORY_SEPARATOR . 'footer_main_dashboard');
		}
	}

	/**
	 * Crear una categoría
	 *
	 */
	public function create_category() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		// Nombre de módulo que se muestra en la barra de navegación
		$header_data['module_name'] = lang('create_category_heading');
		// Categorías con su respectiva cantidad de módulos que se permiten a los grupos del usuario actual
		$header_data['Categorias'] = $this->header->cargarCategorias_Modulos()['Categorias'];
		// Módulos que se permiten a los grupos del usuario actual
		$header_data['Modulos'] = $this->header->cargarCategorias_Modulos()['Modulos'];

		if (!$header_data['Categorias'] || !$header_data['Modulos']) {
			return show_error('Ocurrió un error en la carga de sus aplicaciones asignadas.');
		}

		// Reglas de validación para los controles del formulario
		$this->form_validation->set_rules('NomCategoria', $this->lang->line('create_category_validation_name_label'), 'trim|required');
		$this->form_validation->set_rules('Icono', $this->lang->line('create_category_validation_icon_label'), 'trim|required');
		$this->form_validation->set_rules('Comentarios', $this->lang->line('create_category_validation_comments_label'), 'trim');

		if ($this->form_validation->run() === TRUE) {
			// Identificador de la última categoría existente
			$last_category_id = $this->Categorias_mdl->get_last_Categoria_id();

			$CodCategoria = $last_category_id+1;
			$NomCategoria = $this->input->post('NomCategoria');

			$data = array(
				'Icono' => $this->input->post('Icono'),
				'Comentarios' => $this->input->post('Comentarios'),
			);

			$create_category = $this->Categorias_mdl->create_Categoria($CodCategoria, $NomCategoria, $data);
		
			// Se verifica la creación de la categoría
			if ($create_category) {
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('categorias', 'refresh');
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());			
			}
		}

		// Establecer un mensaje si hay un error de datos o mensajes flash
		$view_data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$view_data['NomCategoria'] = array(
			'name'  => 'NomCategoria',
			'id'    => 'NomCategoria',
			'type'  => 'text',
		);

		$view_data['Icono'] = array(
			'name'  => 'Icono',
			'id'    => 'Icono',
			'type'  => 'text',
		);

		$view_data['Comentarios'] = array(
			'name'  => 'Comentarios',
			'id'    => 'Comentarios',
			'rows'	=> '3',
		);

		$this->load->view('headers' . DIRECTORY_SEPARATOR . 'header_main_dashboard', $header_data);
		$this->load->view('categorias' . DIRECTORY_SEPARATOR . 'create_category', $view_data);
		$this->load->view('footers' . DIRECTORY_SEPARATOR . 'footer_main_dashboard');
	}

	/**
	 * Edita una categoría
	 *
	 * @param int $id 
	 */
	public function edit_category($id) {
		if (!$id || empty($id)) {
			redirect('auth', 'refresh');
		}

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		// Nombre de módulo que se muestra en la barra de navegación
		$header_data['module_name'] = lang('edit_category_heading');
		// Categorías con su respectiva cantidad de módulos que se permiten a los grupos del usuario actual
		$header_data['Categorias'] = $this->header->cargarCategorias_Modulos()['Categorias'];
		// Módulos que se permiten a los grupos del usuario actual
		$header_data['Modulos'] = $this->header->cargarCategorias_Modulos()['Modulos'];

		if (!$header_data['Categorias'] || !$header_data['Modulos']) {
			return show_error('Ocurrió un error en la carga de sus aplicaciones asignadas.');
		}

		$category = $this->Categorias_mdl->get_Categoria($id);

		// Reglas de validación para los controles del formulario
		$this->form_validation->set_rules('NomCategoria', $this->lang->line('edit_category_validation_name_label'), 'trim|required');
		$this->form_validation->set_rules('Icono', $this->lang->line('edit_category_validation_icon_label'), 'trim|required');
		$this->form_validation->set_rules('Comentarios', $this->lang->line('edit_category_validation_comments_label'), 'trim');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$data = array(
					'NomCategoria' => $this->input->post('NomCategoria'),
					'Icono' => $this->input->post('Icono'),
					'Comentarios' => $this->input->post('Comentarios'),
 				);

				$category_update = $this->Categorias_mdl->update_Categoria($id, $data);

				// Se verifica la actualización del módulo
				if ($category_update) {
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect('categorias', 'refresh');
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());			
				}	
			}
		}

		// Establecer un mensaje si hay un error de datos o mensajes flash
		$view_data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// Se definen las estructuras de los controles del formulario
		$view_data['CodCategoria'] = array(
			'name'  => 'CodCategoria',
			'id'    => 'CodCategoria',
			'type'  => 'text',
			'readonly' => 'readonly',
			'value' => $this->form_validation->set_value('CodCategoria', $category->CodCategoria),
		);

		$view_data['NomCategoria'] = array(
			'name'  => 'NomCategoria',
			'id'    => 'NomCategoria',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('NomCategoria', $category->NomCategoria),
		);

		$view_data['Icono'] = array(
			'name'  => 'Icono',
			'id'    => 'Icono',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('Icono', $category->Icono),
		);

		$view_data['Comentarios'] = array(
			'name'  => 'Comentarios',
			'id'    => 'Comentarios',
			'rows'  => '3',
			'value' => $this->form_validation->set_value('Comentarios', $category->Comentarios),
		);

		$this->load->view('headers' . DIRECTORY_SEPARATOR . 'header_main_dashboard', $header_data);
		$this->load->view('categorias' . DIRECTORY_SEPARATOR . 'edit_category', $view_data);
		$this->load->view('footers' . DIRECTORY_SEPARATOR . 'footer_main_dashboard');
	}

	/**
	 * Habilitar categoría a un módulo
	 *
	 */
	public function enable_category_to_module() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$this->lang->load('modulosxcategoria');

		// Nombre de módulo que se muestra en la barra de navegación
		$header_data['module_name'] = lang('enable_category_heading');
		// Categorías con su respectiva cantidad de módulos que se permiten a los grupos del usuario actual
		$header_data['Categorias'] = $this->header->cargarCategorias_Modulos()['Categorias'];
		// Módulos que se permiten a los grupos del usuario actual
		$header_data['Modulos'] = $this->header->cargarCategorias_Modulos()['Modulos'];

		if (!$header_data['Categorias'] || !$header_data['Modulos']) {
			return show_error('Ocurrió un error en la carga de sus aplicaciones asignadas.');
		}

		// Esta variable contiene los datos necesarios para construir un control 'Select' de Categorías
		$categories_select = $this->Categorias_mdl->fill_Categorias_select();

		// Se carga el modelo de los Módulos, para construir otro control 'Select' de Módulos
		$this->load->model('Modulos/EVPIU/Modulos_model', 'Modulos_mdl');
		$modules_select = $this->Modulos_mdl->fill_Modulos_select();

		// Reglas de validación para los controles del formulario
		$this->form_validation->set_rules('Categoria', $this->lang->line('enable_category_validation_category_label'), 'required');
		$this->form_validation->set_rules('Modulo', $this->lang->line('enable_category_validation_module_label'), 'required');

		// Sí los dos controles 'Select' no están llenos, se redirecciona a Categorias de nuevo
		if (isset($categories_select) && isset($modules_select)) {
			$view_data['categories_select'] = $categories_select;
			$view_data['modules_select'] = $modules_select;
		} else {
			redirect('categorias', 'refresh');
		}

		if ($this->form_validation->run() === TRUE) {
			$this->load->model('Modulos/EVPIU/ModulosxCategoria_model', 'ModulosxCategoria_mdl');

			$category_code = $this->input->post('Categoria');
			$module_code = $this->input->post('Modulo');

			$assignment = $this->ModulosxCategoria_mdl->assign_Category_to_Module($module_code, $category_code);

			// Se verifica la asignación de la categoría al módulo
			if ($assignment) {
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('categorias', 'refresh');
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());			
			}
		}

		// Establecer un mensaje si hay un error de datos o mensajes flash
		$view_data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$view_data['Categoria'] = array(
			'name'  => 'Categoria',
			'id'    => 'Categoria',
		);

		$view_data['Modulo'] = array(
			'name'  => 'Modulo',
			'id'    => 'Modulo',
		);

		$this->load->view('headers' . DIRECTORY_SEPARATOR . 'header_main_dashboard', $header_data);
		$this->load->view('categorias' . DIRECTORY_SEPARATOR . 'enable_category_to_module', $view_data);
		$this->load->view('footers' . DIRECTORY_SEPARATOR . 'footer_main_dashboard');
	}
}