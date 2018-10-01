<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Solicitudes de Mantenimiento
 *
 * Descripción de la clase
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Solicitudes extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->model('Mantenimiento/evpiu/Solicitudes_model', 'Solicitudes_mdl');
    $this->load->library(array('header', 'verification_roles', 'messages'));
    $this->load->helper(array('language', 'load', 'form'));
    $this->lang->load('solicitudes');
    $this->load->config('form_validation', TRUE);
    $this->form_validation->set_error_delimiters('', '<br>');
  }

  /**
   * Listar solicitudes de mantenimiento
   *
   */
  public function index() {
    $header_data = $this->header->show_Categories_and_Modules();
    $header_data['module_name'] = lang('index_heading');
    $user_id = $this->ion_auth->user()->row()->id;

    add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
    add_js('themes/elaadmin/js/lib/datatables/datatables.min.js');
    add_js('themes/elaadmin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');
    add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');

    $this->load->view('headers'. DS .'header_main_dashboard', $header_data);

    // Se muestran los datos necesarios dependiendo del rol del usuario.
    switch ($user_id) {
      case $this->verification_roles->is_assets_manager($user_id):
      case $this->ion_auth->is_admin($user_id):
        $view_name = 'req_maint_manager_index';
        add_js('dist/custom/js/mantenimiento/req_maint_manager_index.js');
        break;
      case $this->verification_roles->is_member($user_id):
        $view_name = 'req_maint_member_index';
        add_js('dist/custom/js/terceros/get_user_info.js');
        add_js('dist/custom/js/mantenimiento/req_maint_member_index.js');
        break;
      default:
        redirect('auth');
        break;
    }

    $this->load->view('mantenimiento'. DS . $view_name);
    $this->load->view('footers'. DS .'footer_main_dashboard');
  }

  /**
   * Petición AJAX para obtener todas las solicitudes de
   * mantenimiento existentes.
   *
   * @return string JSON
   */
  public function xhr_get_all_maintenance_requests() {
    try {
      $maintenance_requests = $this->Solicitudes_mdl->get_all_maintenance_requests();

      header('Content-Type: application/json');
      echo json_encode($maintenance_requests);
    } catch (Exception $e) {
      $exception_data = new stdClass();
      $exception_data->exception = $e->getMessage();
      $exception_data->data = array();

      header('Content-Type: application/json');
      echo json_encode($exception_data);
    }
  }

  /**
   * Petición AJAX para obtener las solicitudes de mantenimiento
   * que ha realizado un usuario específico de la plataforma.
   *
   * @return string JSON
   */
  public function xhr_get_user_maintenance_requests() {
    try {
      $user = $this->input->get('u');
      $user_maintenance_requests = $this->Solicitudes_mdl->get_user_maintenance_requests($user);

      header('Content-Type: application/json');
      echo json_encode($user_maintenance_requests);
    } catch (Exception $e) {
      $exception_data = new stdClass();
      $exception_data->exception = $e->getMessage();
      $exception_data->data = array();

      header('Content-Type: application/json');
      echo json_encode($exception_data);
    }
  }

  /**
   * Crear una solicitud de mantenimiento
   *
   */
  public function new_request_maintenance() {
    if ($this->verification_roles->is_member() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('new_rm_heading');

      if ($this->form_validation->run('solicitudes/req_maintenance') === TRUE) {
        try {
          $add_request = $this->save_request_maintenance($this->input->post());
          $success_message = sprintf($this->lang->line('add_rm_success'), $add_request);
          $this->messages->add($success_message, 'success');
        } catch (Exception $e) {
          $this->messages->add($e->getMessage(), 'danger');
        }
      }

      // Se filtran solo los activos que se encuentren en buen estado.
      $view_data['assets'] = modules::run('mantenimiento/activos/populate_assets_by_state', 1);
      $view_data['valid_errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
      $view_data['app_msgs'] = $this->messages->get();

      add_css('themes/elaadmin/css/lib/select2/select2.min.css');
      add_css('dist/vendor/pickadate.js/themes/classic.css');
      add_css('dist/vendor/pickadate.js/themes/classic.date.css');
      add_css('dist/vendor/pickadate.js/themes/classic.time.css');
      add_js('themes/elaadmin/js/lib/select2/select2.full.min.js');
      add_js('themes/elaadmin/js/lib/select2/i18n/es.js');
      add_js('dist/vendor/pickadate.js/picker.js');
      add_js('dist/vendor/pickadate.js/picker.date.js');
      add_js('dist/vendor/pickadate.js/picker.time.js');
      add_js('dist/vendor/pickadate.js/translations/date_es_ES.js');
      add_js('dist/vendor/pickadate.js/translations/time_es_ES.js');
      add_js('dist/custom/js/mantenimiento/request_maintenance.js');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('mantenimiento'. DS .'request_maintenance', $view_data);
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }

  /**
   * Agrega una solicitud de mantenimiento a la base de datos.
   *
   * @param array $data Nuevos datos para el activo.
   *
   * @return boolean
   */
  public function save_request_maintenance($data) {
    try {
      return $this->Solicitudes_mdl->add_request_maintenance($data);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
