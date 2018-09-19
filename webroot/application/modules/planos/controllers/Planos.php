<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Planos
 *
 * Descripción de la clase
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Planos extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->library(array('header', 'verification_roles'));
    $this->load->helper(array('language', 'load', 'form'));
    $this->lang->load('planos');
  }

  /**
   * Lista todos los planos de productos
   * 
   * @return void
   */
  public function index() {
    if ($this->verification_roles->is_flat_manager() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('index_heading');
      
      add_css('themes/elaadmin/css/lib/select2/select2.min.css');
      add_js('themes/elaadmin/js/lib/select2/select2.full.min.js');
      add_js('dist/custom/js/planos/index.js');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('planos'. DS .'index');
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }
}