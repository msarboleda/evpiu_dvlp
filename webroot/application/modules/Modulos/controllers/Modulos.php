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
}