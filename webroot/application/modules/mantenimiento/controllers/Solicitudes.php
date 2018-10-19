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
    $this->load->model('Mantenimiento/evpiu/Estados_solicitudes_model', 'EstSolicitudes_mdl');
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
   * Visualizar una solicitud de mantenimiento.
   *
   * @param int $maint_request_code Código de la solicitud de mantenimiento.
   *
   */
  public function view_maint_request($maint_request_code) {
    $header_data = $this->header->show_Categories_and_Modules();
    $header_data['module_name'] = lang('view_mr_heading');
    $user_id = $this->ion_auth->user()->row()->id;

    add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
    add_css('dist/custom/css/mantenimiento/view_manager_maint_req.css');
    add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');

    // Se muestran los datos necesarios dependiendo del rol del usuario.
    switch ($user_id) {
      case $this->verification_roles->is_maint_req_manager($user_id):
      case $this->ion_auth->is_admin($user_id):
        $view_name = 'view_manager_maint_req';

        try {
          $mr_data = $this->get_maintenance_request($maint_request_code);
          $view_data['maint_request'] = $mr_data;

          try {
            $view_data['maint_request_history'] = $this->get_maintenance_request_history($maint_request_code);
          } catch (Exception $e) {
            $view_data['maint_request_history_error_message'] = $e->getMessage();
          }

          if ($this->input->post('comments')) {
            if (trim($this->input->post('comments')) !== '') {
              $concept_code = $this->Solicitudes_mdl->_updated_concept;

              try {
                $this->add_event_to_history($concept_code, $maint_request_code, $this->input->post('comments'));
              } catch (Exception $e) {
                $view_data['add_event_error'] = $this->messages->add($e->getMessage(), 'danger');
              }

              redirect('mantenimiento/solicitudes/view_maint_request/'.$maint_request_code);
            }
          }

          // Estados en los que se debe habilitar la generación de orden de trabajo.
          $avalaible_states_for_gen_work_orders = array(
            $this->EstSolicitudes_mdl->_in_revision_state,
            $this->EstSolicitudes_mdl->_approved_state,
            $this->EstSolicitudes_mdl->_in_process_state
          );

          // En caso de que el estado de la solicitud de mantenimiento coincida con alguno
          // de los estados habilitados para generar una orden de trabajo, se muestra el
          // botón, de lo contrario se oculta toda la sección de acciones.
          if (in_array($mr_data->CodEstado, $avalaible_states_for_gen_work_orders)) {
            $view_data['gen_wo_button_enabled'] = TRUE;

            add_js('dist/custom/js/mantenimiento/work_orders.js');
            add_js('dist/custom/js/terceros/maintenance_technicians.js');
            add_js('dist/custom/js/mantenimiento/view_manager_maint_req.js');
          } else {
            $view_data['gen_wo_button_enabled'] = FALSE;
          }
        } catch (Exception $e) {
          $view_data['maint_request_not_exist_error'] = $e->getMessage();
        }

        $view_data['app_errors'] = $this->messages->get();
        break;
      default:
        redirect('auth');
        break;
    }

    $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
    $this->load->view('mantenimiento'. DS . $view_name, $view_data);
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
          $maint_request_code = $this->save_request_maintenance($this->input->post());
          $success_message = sprintf($this->lang->line('add_rm_success'), $maint_request_code);
          $this->messages->add($success_message, 'success');

          try {
            $concept_code = $this->Solicitudes_mdl->_created_concept;
            $this->add_event_to_history($concept_code, $maint_request_code);

            redirect('mantenimiento/solicitudes/new_request_maintenance/');
          } catch (Exception $e) {
            $this->messages->add($e->getMessage(), 'danger');
          }
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
   * Obtiene la información de una solicitud de mantenimiento en específico.
   *
   * @param int $maint_request_code Código de la solicitud de mantenimiento.
   *
   * @return object
   */
  public function get_maintenance_request($maint_request_code) {
    try {
      return $this->Solicitudes_mdl->get_maintenance_request($maint_request_code);
    } catch (Exception $e) {
      throw $e;
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

  /**
   * Actualiza el estado de una solicitud de mantenimiento.
   *
   * @param int $maint_request_code Código de la solicitud de mantenimiento.
   * @param int $new_state Código del nuevo estado de la solicitud.
   *
   * @return boolean
   */
  public function update_maintenance_request_state($maint_request_code, $new_state) {
    try {
      return $this->Solicitudes_mdl->update_maintenance_request_state($maint_request_code, $new_state);
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
   * Obtiene el histórico de una solicitud de mantenimiento en específico.
   *
   * @param int $maint_request_code Código de la solicitud de mantenimiento.
   *
   * @return object
   */
  public function get_maintenance_request_history($maint_request_code) {
    try {
      return $this->Solicitudes_mdl->get_maintenance_request_history($maint_request_code);
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
   * Añade un evento al histórico de una solicitud de mantenimiento.
   *
   * @param int $concept_code Código del concepto del evento.
   * @param int $maint_request_code Código de la solicitud de mantenimiento.
   * @param string $comments En caso de que sea un concepto de actualización,
   * se deben anexar los comentarios que se hicieron en el formulario.
   *
   * @return int
   */
  public function add_event_to_history($concept_code, $maint_request_code, $comments = '') {
    try {
      return $this->Solicitudes_mdl->add_event_to_history($concept_code, $maint_request_code, $comments);
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
   * Envía una notificación de correo electrónico para informar que se
   * generó una orden de trabajo para una solicitud de mantenimiento
   * en específico.
   *
   * @param string $to Destinatario de la notificación de correo electrónico.
   * @param string $wo_creator Código del usuario que creó la orden de trabajo.
   * @param string $wo_date Fecha de la creación de la orden de trabajo.
   * @param int $mr_code Código de la solicitud de mantenimiento.
   * @param string $asset_name Nombre del activo al cuál se generó orden de trabajo.
   *
   * @return boolean
   */
  public function send_new_work_order_email_notification($to, $wo_creator, $wo_date, $mr_code, $asset_name) {
    $this->load->library('email');

    $subject = '¡Se ha generado una nueva orden de trabajo para tu solicitud de mantenimiento!';

    $params = array(
      'charset' => strtolower(config_item('charset')),
      'subject' => $subject,
      'wo_creator' => $wo_creator,
      'wo_date' => $wo_date,
      'maint_request' => $mr_code,
      'asset' => $asset_name
    );

    $body = $this->load->view('mantenimiento/notify_new_work_order', $params, TRUE);

    $result = $this->email->from('info@estradavelasquez.com', 'Notificaciones EVPIU')
        ->to($to)
        ->subject($subject)
        ->message($body)
        ->send();

    if ($result) {
      return $result;
    } else {
      throw new Exception(lang('wo_email_notification_not_sended'));
    }
  }
}
