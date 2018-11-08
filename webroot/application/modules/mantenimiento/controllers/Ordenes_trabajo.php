<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Ordenes de Trabajo
 *
 * Descripción de la clase
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Ordenes_trabajo extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->model('Mantenimiento/evpiu/Solicitudes_model', 'Solicitudes_mdl');
    $this->load->model('Mantenimiento/evpiu/Estados_solicitudes_model', 'EstSolicitudes_mdl');
    $this->load->model('Mantenimiento/evpiu/Ordenes_trabajo_model', 'OrdenesT_mdl');
    $this->load->library(array('header', 'verification_roles', 'messages'));
    $this->load->helper(array('language', 'load'));
    $this->lang->load('ordenes_trabajo');
    $this->form_validation->set_error_delimiters('', '<br>');
  }

  /**
   * Lista de ordenes de trabajo para el gestor y técnicos de mantenimiento.
   *
   */
  public function index() {
    $header_data = $this->header->show_Categories_and_Modules();
    $header_data['module_name'] = lang('index_heading');
    $user_id = $this->ion_auth->user()->row()->id;

    // Se muestran los datos necesarios dependiendo del rol del usuario.
    switch ($user_id) {
      case $this->ion_auth->is_admin($user_id):
      case $this->verification_roles->is_maint_req_manager($user_id):
      case $this->verification_roles->is_maint_technician($user_id):
        $view_name = 'work_orders_index';

        add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
        add_js('themes/elaadmin/js/lib/datatables/datatables.min.js');
        add_js('themes/elaadmin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');
        add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');
        add_js('dist/custom/js/mantenimiento/work_orders_index.js');
        break;
      default:
        redirect('auth');
        break;
    }

    $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
    $this->load->view('mantenimiento'. DS . $view_name);
    $this->load->view('footers'. DS .'footer_main_dashboard');
  }

  /**
   * Visualizar una orden de trabajo.
   *
   * @param int $wo_code Código de la orden de trabajo.
   *
   */
  public function view_work_order($wo_code) {
    $header_data = $this->header->show_Categories_and_Modules();
    $header_data['module_name'] = lang('vwo_heading');
    $user_id = $this->ion_auth->user()->row()->id;

    try {
      // Se obtiene la información del encabezado de la orden de trabajo
      $wo_data = $this->get_work_order($wo_code);

      // Nombre de usuario del encargado de la orden de trabajo
      $wo_manager_username = $wo_data->CodEncargado;

      try {
        // Id de usuario del encargado de la orden de trabajo
        $wo_manager_user_id = modules::run('terceros/usuarios/get_user_id_from_username', $wo_manager_username);
      } catch (Exception $e) {
        $this->messages->add($e->getMessage(), 'danger');
      }

      // Indica que se puede mostrar la información del encabezado de
      // la orden de trabajo en la vista
      $view_data['show_work_order'] = TRUE;
    } catch (Exception $e) {
      // Indica que no se puede mostrar la orden de trabajo
      $view_data['show_work_order'] = FALSE;
      $this->messages->add(lang('get_work_order_no_results'), 'danger');
    }

    // Se muestran diferentes datos dependiendo del rol del usuario.
    switch ($user_id) {
      // Es administrador o gestor de mantenimiento de la plataforma
      case $this->ion_auth->is_admin($user_id):
      case $this->verification_roles->is_maint_req_manager($user_id):
        $view_name = 'requests_manager_view_work_order';

        // En caso de diligenciar el formulario de asignación de tareas
        if ($this->input->post('work_type')) {
          // Regla de 'form_validation' para el formulario de asignar tareas.
          $task_rule = 'ordenes_trabajo/assign_tasks';

          // Asignación de tareas
          if ($this->form_validation->run($task_rule) === TRUE) {
            $new_task = array(
              'CodOt' => $wo_code,
              'TipoTrabajo' => $this->input->post('work_type'),
              'Tecnico' => $this->input->post('maint_tech'),
              'Creo' => $this->ion_auth->user()->row()->username,
              'FechaCreacion' => date('Y-m-d H:i:s'),
              'Descripcion' => $this->input->post('task')
            );

            // Datos del evento a registrar
            $work_order_event = array(
              'wo_code' => $wo_code,
              'additional' => $this->input->post('task')
            );

            // Verificación al añadir tarea a técnico
            try {
              $this->OrdenesT_mdl->add_task_to_maint_technician($new_task, $work_order_event);
              $this->messages->add(lang('successfully_assigned_task'), 'success');
              redirect('mantenimiento/ordenes_trabajo/view_work_order/' . $wo_code);
            } catch (Exception $e) {
              $err_message = sprintf(lang('_sql_transaction_error'), __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage());
              $this->messages->add($err_message, 'danger');
            }
          }
        }

        // En caso de diligenciar el formulario de actualización de descripción
        if ($this->input->post('wo_description')) {
          // Regla de form_validation para el formulario de actualizar descripción.
          $update_desc_rule = 'ordenes_trabajo/update_description';

          if ($this->form_validation->run($update_desc_rule) === TRUE) {
            $wo_description = $this->input->post('wo_description');

            try {
              $this->OrdenesT_mdl->update_description($wo_code, $wo_description);
              $this->messages->add(lang('successfully_wo_info_updated'), 'success');
              redirect('mantenimiento/ordenes_trabajo/view_work_order/' . $wo_code);
            } catch (Exception $e) {
              $err_message = sprintf(lang('_sql_transaction_error'), __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage());
              $this->messages->add($err_message, 'danger');
            }
          }
        }

        // Se obtiene la información detallada de la orden de trabajo
        try {
          $wo_details_data = $this->OrdenesT_mdl->get_work_order_details($wo_code);

          // Indica que se puede mostrar la información detallada de la orden de trabajo
          $view_data['show_work_order_details'] = TRUE;
          $view_data['work_order_details'] = $wo_details_data;
        } catch (Exception $e) {
          // Indica que no se puede mostrar la información detallada de la orden de trabajo
          $view_data['show_work_order_details'] = FALSE;
          $this->messages->add($e->getMessage(), 'danger');
        }

        // Se pobla el selector de tipos de trabajo para la asignación de tareas
        try {
          $work_types = $this->ci_populate_all_work_types();
          $view_data['work_types'] = $work_types;
        } catch (Exception $e) {
          $view_data['work_types'] = array('' => $e->getMessage());
        }

        // Se pobla el selector de técnicos de mantenimiento para la asignación de tareas
        try {
          $maint_techs = modules::run('terceros/usuarios/ci_populate_all_maintenance_technicians');
          $view_data['maint_techs'] = $maint_techs;
        } catch (Exception $e) {
          $view_data['maint_techs'] = array('' => $e->getMessage());
        }

        // Se obtienen los datos del histórico de la orden de trabajo
        try {
          $wo_history_data = $this->OrdenesT_mdl->get_work_order_history($wo_code);

          // Indica que se puede mostrar el histórico de la orden de trabajo
          $view_data['show_work_order_historical'] = TRUE;
          $view_data['work_order_historical'] = $wo_history_data;
        } catch (Exception $e) {
          // Indica que no se puede mostrar el histórico de la orden de trabajo
          $view_data['show_work_order_historical'] = FALSE;
          $this->messages->add($e->getMessage(), 'danger');
        }

        // Estados de la orden de trabajo en los que se puede actualizar la descripción
        // de la orden de trabajo
        $view_data['update_desc_allowed_states'] = array(
          $this->OrdenesT_mdl->_in_review_state,
          $this->OrdenesT_mdl->_in_assignment_state
        );

        // Estados de la orden de trabajo en los que se puede asignar tareas a los
        // técnicos de mantenimiento
        $view_data['assign_tasks_allowed_states'] = array(
          $this->OrdenesT_mdl->_in_review_state,
          $this->OrdenesT_mdl->_in_assignment_state,
          $this->OrdenesT_mdl->_started_state,
        );

        // Estados de la orden de trabajo en los que se pueden mostrar las acciones
        // sobre la orden de trabajo
        $view_data['show_actions_allowed_states'] = array(
          $this->OrdenesT_mdl->_in_assignment_state,
          $this->OrdenesT_mdl->_started_state
        );

        // Estado de la orden de trabajo en el que se puede mostrar el botón de reporte
        // de conclusión de tarea
        $view_data['rep_conclusion_allowed_state'] = $this->OrdenesT_mdl->_started_state;

        // Estado de la orden de trabajo en el que se puede mostrar el botón de iniciar
        // la orden de trabajo
        $view_data['start_wo_allowed_state'] = $this->OrdenesT_mdl->_in_assignment_state;

        // Estado de la orden de trabajo en el que se puede mostrar el botón de finalizar
        // la orden de trabajo
        $view_data['complete_wo_allowed_state'] = $this->OrdenesT_mdl->_started_state;

        // Envia los datos de la orden de trabajo a la vista
        $view_data['work_order'] = $wo_data;

        // Datos de errores de validación de: form_validation
        $view_data['valid_errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
        add_css('dist/custom/css/mantenimiento/view_work_order.css');
        add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');
        add_js('dist/custom/js/mantenimiento/view_manager_work_order.js');

        // Obtiene todos los mensajes de notificación de la aplicación
        $view_data['app_messages'] = $this->messages->get();
        break;
      // Es el encargado de la orden de trabajo
      case $user_id == $wo_manager_user_id:
        $view_name = 'manager_view_work_order';

        // En caso de diligenciar el formulario de asignación de tareas
        if ($this->input->post('work_type')) {
          // Regla de 'form_validation' para el formulario de asignar tareas.
          $task_rule = 'ordenes_trabajo/assign_tasks';

          // Asignación de tareas
          if ($this->form_validation->run($task_rule) === TRUE) {
            $new_task = array(
              'CodOt' => $wo_code,
              'TipoTrabajo' => $this->input->post('work_type'),
              'Tecnico' => $this->input->post('maint_tech'),
              'Creo' => $this->ion_auth->user()->row()->username,
              'FechaCreacion' => date('Y-m-d H:i:s'),
              'Descripcion' => $this->input->post('task')
            );

            // Datos del evento a registrar
            $work_order_event = array(
              'wo_code' => $wo_code,
              'additional' => $this->input->post('task')
            );

            // Verificación al añadir tarea a técnico
            try {
              $this->OrdenesT_mdl->add_task_to_maint_technician($new_task, $work_order_event);
              $this->messages->add(lang('successfully_assigned_task'), 'success');
              redirect('mantenimiento/ordenes_trabajo/view_work_order/' . $wo_code);
            } catch (Exception $e) {
              $err_message = sprintf(lang('_sql_transaction_error'), __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage());
              $this->messages->add($err_message, 'danger');
            }
          }
        }

        // Se obtiene la información detallada de la orden de trabajo
        try {
          $wo_details_data = $this->OrdenesT_mdl->get_work_order_details($wo_code);

          // Indica que se puede mostrar la información detallada de la orden de trabajo
          $view_data['show_work_order_details'] = TRUE;
          $view_data['work_order_details'] = $wo_details_data;
        } catch (Exception $e) {
          // Indica que no se puede mostrar la información detallada de la orden de trabajo
          $view_data['show_work_order_details'] = FALSE;
          $this->messages->add($e->getMessage(), 'danger');
        }

        // Se pobla el selector de tipos de trabajo para la asignación de tareas
        try {
          $work_types = $this->ci_populate_all_work_types();
          $view_data['work_types'] = $work_types;
        } catch (Exception $e) {
          $view_data['work_types'] = array('' => $e->getMessage());
        }

        // Se pobla el selector de técnicos de mantenimiento para la asignación de tareas
        try {
          $maint_techs = modules::run('terceros/usuarios/ci_populate_all_maintenance_technicians');
          $view_data['maint_techs'] = $maint_techs;
        } catch (Exception $e) {
          $view_data['maint_techs'] = array('' => $e->getMessage());
        }

        // Se obtienen los datos del histórico de la orden de trabajo
        try {
          $wo_history_data = $this->OrdenesT_mdl->get_work_order_history($wo_code);

          // Indica que se puede mostrar el histórico de la orden de trabajo
          $view_data['show_work_order_historical'] = TRUE;
          $view_data['work_order_historical'] = $wo_history_data;
        } catch (Exception $e) {
          // Indica que no se puede mostrar el histórico de la orden de trabajo
          $view_data['show_work_order_historical'] = FALSE;
          $this->messages->add($e->getMessage(), 'danger');
        }

        // Estados de la orden de trabajo en los que se puede asignar tareas a los
        // técnicos de mantenimiento
        $view_data['assign_tasks_allowed_states'] = array(
          $this->OrdenesT_mdl->_in_review_state,
          $this->OrdenesT_mdl->_in_assignment_state,
          $this->OrdenesT_mdl->_started_state,
        );

        // Estados de la orden de trabajo en los que se pueden mostrar las acciones
        // sobre la orden de trabajo
        $view_data['show_actions_allowed_states'] = array(
          $this->OrdenesT_mdl->_in_assignment_state,
          $this->OrdenesT_mdl->_started_state
        );

        // Estado de la orden de trabajo en el que se puede mostrar el botón de reporte
        // de conclusión de tarea
        $view_data['rep_conclusion_allowed_state'] = $this->OrdenesT_mdl->_started_state;

        // Estado de la orden de trabajo en el que se puede mostrar el botón de iniciar
        // la orden de trabajo
        $view_data['start_wo_allowed_state'] = $this->OrdenesT_mdl->_in_assignment_state;

        // Estado de la orden de trabajo en el que se puede mostrar el botón de finalizar
        // la orden de trabajo
        $view_data['complete_wo_allowed_state'] = $this->OrdenesT_mdl->_started_state;

        // Envia los datos de la orden de trabajo a la vista
        $view_data['work_order'] = $wo_data;

        // Datos de errores de validación de: form_validation
        $view_data['valid_errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
        add_css('dist/custom/css/mantenimiento/view_work_order.css');
        add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');
        add_js('dist/custom/js/mantenimiento/view_manager_work_order.js');

        // Obtiene todos los mensajes de notificación de la aplicación
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
   * Obtiene la información de una orden de trabajo en específico.
   *
   * @param int $work_order_code Código de la orden de trabajo.
   *
   * @return object
   */
  public function get_work_order($work_order_code) {
    try {
      return $this->OrdenesT_mdl->get_work_order($work_order_code);
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
   * Petición AJAX para generar una orden de trabajo con base a los datos
   * de una solicitud de mantenimiento.
   *
   * @return string JSON
   */
  public function xhr_generate_work_order_from_maintenance_request() {
    $maint_request_code = $this->input->post('maint_request_code');
    $maint_technician = $this->input->post('maint_technician');
    $maint_type = $this->input->post('maint_type');
    $maint_description = $this->input->post('maint_description');

    try {
      // Inserta un encabezado de orden de trabajo con base a una solicitud de mantenimiento.
      $generated_work_order_code = $this->OrdenesT_mdl->generate_work_order_from_maintenance_request($maint_request_code, $maint_technician, $maint_type, $maint_description);

      // Se reporta el evento de creación de orden de trabajo al histórico de la solicitud
      $created_concept = $this->Solicitudes_mdl->_work_order_created_concept;
      modules::run('mantenimiento/solicitudes/add_event_to_history', $created_concept, $maint_request_code, $generated_work_order_code);

      // Se reporta el evento de creación de orden de trabajo al histórico de la orden de trabajo
      $wo_created_concept = $this->OrdenesT_mdl->_created_concept;
      $this->OrdenesT_mdl->add_event_to_history($wo_created_concept, $generated_work_order_code);

      $maint_request_data = modules::run('mantenimiento/solicitudes/get_maintenance_request', $maint_request_code);

      // Únicamente cuando una solicitud de mantenimiento se encuentre en estado de revisión
      // se debe reportar el evento de aprobación de la solicitud y el cambio de estado
      // de la solicitud.
      if ($maint_request_data->CodEstado === $this->EstSolicitudes_mdl->_in_revision_state) {
        // Se reporta el evento de aprobación al histórico de la solicitud
        $approved_concept = $this->Solicitudes_mdl->_approved_concept;
        modules::run('mantenimiento/solicitudes/add_event_to_history', $approved_concept, $maint_request_code);

        // Se actualiza el estado de la solicitud a 'aprobada'
        $approved_state = $this->EstSolicitudes_mdl->_approved_state;
        modules::run('mantenimiento/solicitudes/update_maintenance_request_state', $maint_request_code, $approved_state);
      }

      $work_order_data = modules::run('mantenimiento/ordenes_trabajo/get_work_order', $generated_work_order_code);

      // Se obtiene el id del usuario que realizó la solicitud de mantenimiento, de esta forma se averigua
      // el email al cuál enviar la notificación de la generación de orden de trabajo para esta solicitud.
      $applicant_user_id = modules::run('terceros/usuarios/get_user_id_from_username', $maint_request_data->CodSolicitante);
      $applicant_user_email = $this->ion_auth->user($applicant_user_id)->row()->email;

      // Parámetros incluidos en la notificación de correo electrónico.
      $wo_params = array(
        'work_order_creator' => $work_order_data->NomCreo,
        'work_order_date' => $work_order_data->BeautyCreationFullDate,
        'maint_request_code' => $maint_request_code,
        'asset_name' => $maint_request_data->NomActivo
      );

      modules::run('mantenimiento/solicitudes/send_new_work_order_email_notification', $applicant_user_email, $wo_params['work_order_creator'], $wo_params['work_order_date'], $wo_params['maint_request_code'], $wo_params['asset_name']);

      $data = new stdClass();
      $data->work_order_code = $generated_work_order_code;
      $data->message = sprintf(lang('generate_work_order_from_mr_success'), $generated_work_order_code);

      header('Content-Type: application/json');
      echo json_encode($data);
    } catch (Exception $e) {
      $data = new stdClass();
      $data->has_error = true;
      $data->message = $e->getMessage();

      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

  /**
   * Petición AJAX para obtener todos los tipos de mantenimiento
   * de una orden de trabajo.
   *
   * @return string JSON
   */
  public function xhr_get_all_work_orders() {
    try {
      $work_orders = $this->OrdenesT_mdl->get_all_work_orders();

      header('Content-Type: application/json');
      echo json_encode($work_orders);
    } catch (Exception $e) {
      $data = new stdClass();
      $data->message = $e->getMessage();
      $data->data = array();

      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

  /**
   * Petición AJAX para obtener todos los tipos de mantenimiento
   * de una orden de trabajo.
   *
   * @return string JSON
   */
  public function xhr_get_all_maintenance_types() {
    try {
      $maintenance_types = $this->OrdenesT_mdl->get_all_maintenance_types();

      header('Content-Type: application/json');
      echo json_encode($maintenance_types);
    } catch (Exception $e) {
      $data = new stdClass();
      $data->message = $e->getMessage();
      $data->content = array();

      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

  /**
   * Petición AJAX para reportar la conclusión de una tarea
   * de una orden de trabajo.
   *
   * @return string JSON
   */
  public function xhr_report_task_conclusion() {
    $wo_code = $this->input->post('wo_code');
    $task_data = array(
      'task_id' => $this->input->post('task_id'),
      'task_description' => $this->input->post('task_description'),
      'task_cost' => $this->input->post('task_cost')
    );

    try {
      $task_conclusion = $this->OrdenesT_mdl->report_task_conclusion($wo_code, $task_data);

      $data = new stdClass();
      $data->success = $task_conclusion;
      $data->message = lang('successfully_conclusion_task');

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
   * Petición AJAX para iniciar una orden de trabajo.
   *
   * @return string JSON
   */
  public function xhr_start_work_order() {
    $wo_code = $this->input->post('wo_code');

    try {
      $start_work_order = $this->OrdenesT_mdl->start_work_order($wo_code);

      $data = new stdClass();
      $data->success = $start_work_order;
      $data->message = lang('successfully_started_work_order');

      header('Content-Type: application/json');
      echo json_encode($data);
    } catch (Exception $e) {
      $data = new stdClass();
      $data->success = FALSE;
      $data->message = sprintf(lang('_sql_transaction_error'), __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage());

      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

  /**
   * Petición AJAX para finalizar una orden de trabajo.
   *
   * @return string JSON
   */
  public function xhr_finish_work_order() {
    $wo_code = $this->input->post('wo_code');

    try {
      $finish_work_order = $this->OrdenesT_mdl->finish_work_order($wo_code);

      $data = new stdClass();

      if ($finish_work_order === TRUE) {
        $data->success = $finish_work_order;
        $data->message = lang('successfully_finished_work_order');
      }

      if ($finish_work_order === FALSE) {
        $data->success = $finish_work_order;
        $data->message = lang('unfinished_works');
      }

      header('Content-Type: application/json');
      echo json_encode($data);
    } catch (Exception $e) {
      $data = new stdClass();
      $data->success = FALSE;
      $data->message = sprintf(lang('_sql_transaction_error'), __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage());

      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

  /**
   * Rellena un form_dropdown() del helper form de CodeIgniter
   * con todos los tipos de trabajos.
   *
   * @return array
   */
  public function ci_populate_all_work_types() {
    try {
      $query = $this->OrdenesT_mdl->get_all_work_types();

      foreach ($query as $work_type) {
        $work_types[$work_type->CodTipoTrabajo] = $work_type->Descripcion;
      }

      $work_types[''] = 'Selecciona un tipo de trabajo...';

      return $work_types;
    } catch (Exception $e) {
      throw $e;
    }
  }
}
