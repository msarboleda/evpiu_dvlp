<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Modulos
 */
class Modulos extends MX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->helper('language');
		$this->load->model('Auth/EVPIU/ModulosxCategoriasxGrupos_model');
		$this->load->model('Modulos/EVPIU/Modulos_model', 'Modulos_mdl');
		// Libreria para cargar datos en el Header
		$this->load->library('header');
		// Inicio y final del contenido de los errores de form_validation.
		$this->form_validation->set_error_delimiters('', '<br>');

		$this->lang->load('modulos');
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
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			// Listar módulos
			$data['modulos_list'] = $this->Modulos_mdl->get_Modulos();
			
			$this->load->view('headers' . DIRECTORY_SEPARATOR . 'header_main_dashboard', $header_data);
			$this->load->view('modulos' . DIRECTORY_SEPARATOR . 'index', $data);
			$this->load->view('footers' . DIRECTORY_SEPARATOR . 'footer_main_dashboard');
		}
	}

	/**
	 * Edita un módulo
	 *
	 * @param int $id 
	 */
	public function edit_module($id) {
		if (!$id || empty($id)) {
			redirect('auth', 'refresh');
		}

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		// Nombre de módulo que se muestra en la barra de navegación
		$header_data['module_name'] = lang('edit_module_heading');
		// Categorías con su respectiva cantidad de módulos que se permiten a los grupos del usuario actual
		$header_data['Categorias'] = $this->header->cargarCategorias_Modulos()['Categorias'];
		// Módulos que se permiten a los grupos del usuario actual
		$header_data['Modulos'] = $this->header->cargarCategorias_Modulos()['Modulos'];

		if (!$header_data['Categorias'] || !$header_data['Modulos']) {
			return show_error('Ocurrió un error en la carga de sus aplicaciones asignadas.');
		}

		$module = $this->Modulos_mdl->get_Modulo($id);

		// Reglas de validación para los controles del formulario
		$this->form_validation->set_rules('NomModulo', $this->lang->line('edit_module_validation_name_label'), 'trim|required');
		$this->form_validation->set_rules('Ruta', $this->lang->line('edit_module_validation_route_label'), 'trim|required');
		$this->form_validation->set_rules('Icono', $this->lang->line('edit_module_validation_icon_label'), 'trim|required');
		$this->form_validation->set_rules('FechaActualizacion', $this->lang->line('edit_module_validation_updt_date_label'), 'trim|required');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$data = array(
					'NomModulo' => $this->input->post('NomModulo'),
					'Descripcion' => $this->input->post('Descripcion'),
					'Ruta' => $this->input->post('Ruta'),
					'Icono' => $this->input->post('Icono'),
					'FechaActualizacion' => $this->input->post('FechaActualizacion'),
 				);

				$module_update = $this->Modulos_mdl->update_Modulo($id, $data);

				// Se verifica la actualización del módulo
				if ($module_update) {
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect('modulos', 'refresh');
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());			
				}	
			}
		}

		// Establecer un mensaje si hay un error de datos o mensajes flash
		$view_data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// Se definen las estructuras de los controles del formulario
		$view_data['CodModulo'] = array(
			'name'  => 'CodModulo',
			'id'    => 'CodModulo',
			'type'  => 'text',
			'readonly' => 'readonly',
			'value' => $this->form_validation->set_value('CodModulo', $module->CodModulo),
		);

		$view_data['NomModulo'] = array(
			'name'  => 'NomModulo',
			'id'    => 'NomModulo',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('NomModulo', $module->NomModulo),
		);

		$view_data['Descripcion'] = array(
			'name'  => 'Descripcion',
			'id'    => 'Descripcion',
			'value' => $this->form_validation->set_value('Descripcion', $module->Descripcion),
			'rows'	=> '3',
		);

		$view_data['Ruta'] = array(
			'name'  => 'Ruta',
			'id'    => 'Ruta',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('Ruta', $module->Ruta),
		);

		$view_data['Icono'] = array(
			'name'  => 'Icono',
			'id'    => 'Icono',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('Icono', $module->Icono),
		);

		$view_data['FechaActualizacion'] = array(
			'name'  => 'FechaActualizacion',
			'id'    => 'FechaActualizacion',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('FechaActualizacion', $module->FechaActualizacion),
		);

		$this->load->view('headers' . DIRECTORY_SEPARATOR . 'header_main_dashboard', $header_data);
		$this->load->view('modulos' . DIRECTORY_SEPARATOR . 'edit_module', $view_data);
		$this->load->view('footers' . DIRECTORY_SEPARATOR . 'footer_main_dashboard');
	}
}