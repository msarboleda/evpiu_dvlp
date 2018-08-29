<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reportes de Clientes
 *
 * Esta clase posee reportes útiles sobre los clientes
 * de la compañía.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Clientes extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->library(array('header', 'verification_roles'));
    $this->load->helper(array('language', 'load', 'form'));
  }

  /**
   * @description Página principal de Reporte de Clientes
   */
  public function index() {
    if ($this->verification_roles->is_vendor() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $this->add_Index_libraries();
      $header_data['module_name'] = lang('index_heading');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('reportes/clientes'. DS .'index');
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
    $this->lang->load('clientes/index');

    add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
    add_js('themes/elaadmin/js/lib/datatables/datatables.min.js');
    add_js('themes/elaadmin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');
    add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');
    add_js('dist/custom/js/reportes/clientes/index.js');
  }
}