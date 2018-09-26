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
