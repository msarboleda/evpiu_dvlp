<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Requerimientos
 */
class Requerimientos extends MX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->helper('language', 'load');
		$this->load->model('Auth/EVPIU/ModulosxCategoriasxGrupos_model');
		$this->load->model('Requerimientos/EVPIU/Requerimientos_model', 'Reqs_mdl');
		// Libreria para cargar datos en el Header
		$this->load->library('header');
		// Libreria para identificar roles del usuario actual
		$this->load->library('verification_roles');
		// Inicio y final del contenido de los errores de form_validation.
		$this->form_validation->set_error_delimiters('', '<br>');

		$this->lang->load('requerimientos');
		// Se configura la localización del tiempo para Colombia
		setlocale(LC_TIME, 'es_CO.utf8');	
	}
}