<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Listar Planos
 *
 * Esta clase sirve para listar los planos de los
 * productos de la compañía.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Get extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->library(array('header', 'verification_roles'));
    $this->load->helper(array('language', 'load', 'form'));
  }

  /**
   * @description Lista todos los planos de productos
   * 
   * @return void
   */
  public function index() {
    if ($this->verification_roles->is_flat_manager() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $this->add_Index_libraries();
      $header_data['module_name'] = lang('index_heading');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('planos'. DS .'get'. DS .'index');
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }

  /**
   * @description Añade las librerias CSS y JS principales del
   * método Index.
   * 
   * @return void 
   */
  public function add_Index_libraries() {
    $this->lang->load('get/index');
    add_js('dist/custom/js/planos/get/index.js');
  }
}