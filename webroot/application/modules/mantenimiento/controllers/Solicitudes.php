<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Solicitudes de Mantenimiento
 *
 * Descripción de la clase
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

 // Se crea una excepción propia para separar los errores de SQL de las
 // excepciones corrientes
class BDException extends Exception {}

class Solicitudes extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->model('Mantenimiento/evpiu/Activos_model', 'Activos_mdl');
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

          // Muestra las ordenes de trabajo vinculadas a la solicitud
          try {
            $this->load->model('Mantenimiento/evpiu/Ordenes_trabajo_model', 'OrdenesT_mdl');
            $view_data['show_linked_orders'] = TRUE;
            $view_data['linked_work_orders'] = $this->OrdenesT_mdl->get_work_orders_from_maintenance_request($maint_request_code);
          } catch (Exception $e) {
            $view_data['show_linked_orders'] = FALSE;
            $this->messages->add($e->getMessage(), 'danger');
          }

          if ($this->input->post('comments')) {
            if (trim($this->input->post('comments')) !== '') {
              $concept_code = $this->Solicitudes_mdl->_updated_concept;

              try {
                $event_code = $this->add_event_to_history($concept_code, $maint_request_code, $this->input->post('comments'));
                $event_data = $this->get_event_from_maintenance_request_history($event_code);

                // Se obtiene el id del usuario que realizó la solicitud de mantenimiento, de esta forma se averigua
                // el email al cuál enviar la notificación de un nuevo comentario para esta solicitud.
                $applicant_user_id = modules::run('terceros/usuarios/get_user_id_from_username', $mr_data->CodSolicitante);
                $applicant_user_email = $this->ion_auth->user($applicant_user_id)->row()->email;

                // Parámetros incluidos en la notificación de correo electrónico.
                $co_params = array(
                  'comment_creator' => $event_data->NomUsuario,
                  'comment_date' => $event_data->BeautyDate,
                  'maint_request_code' => $maint_request_code,
                  'asset_name' => $mr_data->NomActivo
                );

                // Se notifica la adición de un nuevo comentario en la solicitud
                modules::run('mantenimiento/solicitudes/send_new_comment_email_notification', $applicant_user_email, $co_params['comment_creator'], $co_params['comment_date'], $co_params['maint_request_code'], $co_params['asset_name']);
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
      case $this->verification_roles->is_maint_applicant($user_id):
        $view_name = 'view_applicant_maint_req';

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
                $event_code = $this->add_event_to_history($concept_code, $maint_request_code, $this->input->post('comments'));
                $event_data = $this->get_event_from_maintenance_request_history($event_code);

                // Se obtienen los correos electrónicos de los gestores de solicitudes de mantenimiento
                $maint_req_manager_emails = modules::run('terceros/usuarios/get_emails_from_users_group', 'maint_req_manager');

                // Parámetros incluidos en la notificación de correo electrónico.
                $co_params = array(
                  'comment_creator' => $event_data->NomUsuario,
                  'comment_date' => $event_data->BeautyDate,
                  'maint_request_code' => $maint_request_code,
                  'asset_name' => $mr_data->NomActivo
                );

                // Si hay más de un gestor de solicitudes, se envían múltiples correos
                if (count($maint_req_manager_emails > 1)) {
                  foreach ($maint_req_manager_emails as $email) {
                    modules::run('mantenimiento/solicitudes/send_new_comment_email_notification', $email->email, $co_params['comment_creator'], $co_params['comment_date'], $co_params['maint_request_code'], $co_params['asset_name']);
                  }
                }

                if (count($maint_req_manager_emails === 1)) {
                  modules::run('mantenimiento/solicitudes/send_new_comment_email_notification', $maint_req_manager_emails, $co_params['comment_creator'], $co_params['comment_date'], $co_params['maint_request_code'], $co_params['asset_name']);
                }
              } catch (Exception $e) {
                $view_data['add_event_error'] = $this->messages->add($e->getMessage(), 'danger');
              }

              redirect('mantenimiento/solicitudes/view_maint_request/'.$maint_request_code);
            }
          }
        } catch (Exception $e) {
          $view_data['maint_request_not_exist_error'] = $e->getMessage();
        }

        $view_data['app_errors'] = $this->messages->get();
        break;
      case $this->verification_roles->is_maint_technician($user_id):
        $view_name = 'tech_view_maint_req';

        try {
          // Consulta los datos de una solicitud de mantenimiento
          $mr_data = $this->get_maintenance_request($maint_request_code);

          // Indica que se puede mostrar la solicitud de mantenimiento
          $view_data['show_maint_request'] = TRUE;

          // Envía los datos de la solicitud a la vista
          $view_data['maint_request'] = $mr_data;

          try {
            // Consulta el histórico de una solicitud de mantenimiento
            $mr_historical = $this->get_maintenance_request_history($maint_request_code);

            // Indica que se puede mostrar el histórico de la solicitud
            $view_data['show_maint_request_historical'] = TRUE;

            // Envía los datos del histórico a la vista
            $view_data['maint_request_historical'] = $mr_historical;
          } catch (Exception $e) {
            // Indica que no se puede mostrar el histórico de la solicitud
            $view_data['show_maint_request_historical'] = FALSE;
            $this->messages->add($e->getMessage(), 'danger');
          }
        } catch (Exception $e) {
          // Indica que no se puede mostrar la solicitud de mantenimiento porque no existe
          $view_data['show_maint_request'] = FALSE;
          $this->messages->add($e->getMessage(), 'danger');
        }

        $view_data['app_messages'] = $this->messages->get();
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

      // Se filtran solo los activos de un responsable que se encuentren en buen estado.
      try {
        $asset_good_state = $this->Activos_mdl->_good_state;
        $view_data['assets'] = modules::run('mantenimiento/activos/populate_assets_by_responsible_and_state', $asset_good_state, $this->ion_auth->user()->row()->username);
      } catch (Exception $e) {
        $this->messages->add($e->getMessage() . ' No tienes activos asignados.', 'danger');
        $view_data['assets_not_loaded'] = TRUE;
      }

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
   * Obtiene información de un evento del histórico de las solicitudes de
   * mantenimiento.
   *
   * @param int $event_code Código de evento a consultar.
   *
   * @return object
   */
  public function get_event_from_maintenance_request_history($event_code) {
    try {
      return $this->Solicitudes_mdl->get_event_from_maintenance_request_history($event_code);
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

  /**
   * Envía una notificación de correo electrónico para informar que se
   * realizó un comentario en una solicitud de mantenimiento en específico.
   *
   * @param string $to Destinatario de la notificación de correo electrónico.
   * @param string $co_creator Código del usuario que creó la orden de trabajo.
   * @param string $co_date Fecha de la creación de la orden de trabajo.
   * @param int $mr_code Código de la solicitud de mantenimiento.
   * @param string $asset_name Nombre del activo al cuál se generó orden de trabajo.
   *
   * @return boolean
   */
  public function send_new_comment_email_notification($to, $co_creator, $co_date, $mr_code, $asset_name) {
    $this->load->library('email');

    $subject = '¡Se ha realizado un nuevo comentario en una solicitud de mantenimiento!';

    $params = array(
      'charset' => strtolower(config_item('charset')),
      'subject' => $subject,
      'user' => $co_creator,
      'co_date' => $co_date,
      'maint_request' => $mr_code,
      'asset' => $asset_name
    );

    $body = $this->load->view('mantenimiento/notify_new_comment', $params, TRUE);

    $result = $this->email->from('info@estradavelasquez.com', 'Notificaciones EVPIU')
        ->to($to)
        ->subject($subject)
        ->message($body)
        ->send();

    if ($result) {
      return $result;
    } else {
      throw new Exception(lang('comment_email_notification_not_sended'));
    }
  }

  /**
   * Petición AJAX para finalizar una solicitud de mantenimiento.
   *
   * @return string JSON
   */
  public function xhr_finish_request() {
    $request_code = $this->input->post('request_code');

    try {
      $finish_request = $this->Solicitudes_mdl->finish_request($request_code);

      $data = new stdClass();
      $data->success = $finish_request;
      $data->message = lang('successfully_finished_request');

      // Obtiene información de la solicitud
      $get_request = $this->get_maintenance_request($request_code);

      // Obtiene el código del activo de la solicitud de mantenimiento
      $asset_code = $get_request->CodActivo;

      // Obtiene información del activo de la solicitud
      $get_asset = $this->Activos_mdl->get_asset($asset_code);

      // Se obtiene el id del usuario que realizó la solicitud de mantenimiento, de esta forma se averigua
      // el email al cuál enviar la notificación de la finalización de la solicitud.
      $applicant_user_id = modules::run('terceros/usuarios/get_user_id_from_username', $get_request->CodSolicitante);
      $applicant_user_email = $this->ion_auth->user($applicant_user_id)->row()->email;

      // Parámetros incluidos en la notificación de correo electrónico.
      $params = array(
        'finished_date' => $get_request->BeautyEndDate,
        'request_code' => $request_code,
        'asset' => $get_asset->NomActivo,
        'maintenance_cost' => $get_asset->CostoMantenimiento
      );

      // Envía una notificación de solicitud finalizada al solicitante del mantenimiento.
      $this->send_finished_request_email_notification($applicant_user_email, $params);

      header('Content-Type: application/json');
      echo json_encode($data);
    } catch (BDException $e) {
      $data = new stdClass();
      $data->success = FALSE;
      // Emite un SQL Error en la transacción de la finalización de la solicitud
      $data->message = sprintf(lang('_sql_transaction_error'), __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage());

      header('Content-Type: application/json');
      echo json_encode($data);
    } catch (Exception $e) {
      $data = new stdClass();
      $data->success = FALSE;
      $data->message = $e->getMessage();

      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

  /**
   * Envía una notificación de correo electrónico para informar que se
   * finalizó solicitud de mantenimiento.
   *
   * @param string $to Destinatario de la notificación de correo electrónico.
   * @param array $email_data Datos de la notificación de correo electrónico.
   *
   * @return boolean
   */
  public function send_finished_request_email_notification($to, $email_data) {
    $this->load->library('email');

    $subject = '¡Se ha finalizado correctamente tu solicitud de mantenimiento!';

    $params = array(
      'charset' => strtolower(config_item('charset')),
      'subject' => $subject,
      'finished_date' => $email_data['finished_date'],
      'request_code' => $email_data['request_code'],
      'asset' => $email_data['asset'],
      'maintenance_cost' => $email_data['maintenance_cost']
    );

    $body = $this->load->view('mantenimiento/notify_finished_request', $params, TRUE);

    $result = $this->email->from('info@estradavelasquez.com', 'Notificaciones EVPIU')
        ->to($to)
        ->subject($subject)
        ->message($body)
        ->send();

    if ($result) {
      return $result;
    } else {
      throw new Exception(lang('finished_request_notification_not_sended'));
    }
  }
}
