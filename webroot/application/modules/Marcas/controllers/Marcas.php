<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Marcas
 *
 * Esta clase posee funciones útiles para las marcas de los productos
 * de la compañía.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Marcas extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/EVPIU/ModulosxCategoriasxGrupos_model');
    $this->load->library(array('header', 'verification_roles'));
    $this->load->helper(array('language', 'load', 'form'));
    $this->lang->load('marcas');
  }

  /**
   * Página principal de Marcas
   */
  public function index() {
    echo 'Hello world';
  }

  /**
   * Método para crear una nueva marca
   */
  public function new_mark() {
    if ($this->verification_roles->is_vendor() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('NM_heading');
      
      add_css('dist/custom/css/marcas/new_mark.css');

      $this->load->view('headers'.DS.'header_main_dashboard', $header_data);
      $this->load->view('marcas'.DS.'new_mark');
      $this->load->view('footers'.DS.'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }
}