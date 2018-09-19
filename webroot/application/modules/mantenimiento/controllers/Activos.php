<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Activos
 *
 * Descripción de la clase
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Activos extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->model('Mantenimiento/evpiu/Activos_model', 'Activos_mdl');
    $this->load->library(array('header', 'verification_roles', 'messages'));
    $this->load->helper(array('language', 'load', 'form'));
    $this->lang->load('activos');
  }

  /**
   * Página principal de Activos
   */
  public function index() {
    if ($this->verification_roles->is_assets_viewer() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('index_heading');

      add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
      add_js('themes/elaadmin/js/lib/datatables/datatables.min.js');
      add_js('themes/elaadmin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');
      add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');
      add_js('dist/custom/js/mantenimiento/index.js');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('mantenimiento'. DS .'index');
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }

  /**
   * Petición AJAX para obtener todos los activos existentes.
   * 
   * @return string JSON
   */
  public function xhr_get_all_assets() {
    try {
      $assets = $this->Activos_mdl->get_all_assets();
      echo json_encode($assets);
    } catch (Exception $e) {
      $exception_data = new stdClass();
      $exception_data->exception = $e->getMessage();
      $exception_data->data = array();
      echo json_encode($exception_data);
    }
  }
}