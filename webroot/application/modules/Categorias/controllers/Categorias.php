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
}