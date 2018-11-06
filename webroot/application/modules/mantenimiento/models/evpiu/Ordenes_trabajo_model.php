<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Ordenes de Trabajo
 *
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Ordenes_trabajo_model extends CI_Model {
  /**
   * Tabla de encabezados de ordenes de trabajo
   *
   * @var string $_work_order_header_table
   */
  public $_work_order_header_table = 'mant_EncabezadoOrdenesTrabajo';

  /**
   * Tabla de tareas asignadas a ordenes de trabajo
   *
   * @var string $_work_order_details_table
   */
  public $_work_order_details_table = 'mant_DetallesOrdenesTrabajo';

  /**
   * Tabla de histórico de ordenes de trabajo
   *
   * @var string $_timeline_wo_table
   */
  public $_timeline_wo_table = 'mant_HistoricoOrdenesTrabajo';

  /**
   * Tabla de tipos de mantenimiento de las ordenes de trabajo
   *
   * @var string $_maintenance_types_table
   */
  public $_maintenance_types_table = 'mant_TiposMantenimiento';

  /**
   * Tabla de tipos de trabajos de las ordenes de trabajo
   *
   * @var string $_work_types_table
   */
  public $_work_types_table = 'mant_TiposTrabajos';

  /**
   * Vista de ordenes de trabajo
   *
   * @var string $_work_order_view_table
   */
  public $_work_order_view_table = 'V_mant_OrdenesTrabajo';

  /**
   * Vista de tareas asignadas a ordenes de trabajo
   *
   * @var string $_work_order_details_view_table
   */
  public $_work_order_details_view_table = 'V_mant_DetallesOrdenesTrabajo';

  /**
   * Vista de histórico de ordenes de trabajo
   *
   * @var string $_timeline_wo_view_table
   */
  public $_timeline_wo_view_table = 'V_mant_HistoricoOrdenesTrabajo';

  /**
   * Concepto de orden de trabajo creada
   *
   * @var int $_created_concept
   */
  public $_created_concept = 1;

  /**
   * Concepto de orden de trabajo actualizada
   *
   * @var int $_updated_concept
   */
  public $_updated_concept = 2;

  /**
   * Concepto de tarea de orden de trabajo asignada a técnico
   *
   * @var int $_assigned_task_concept
   */
  public $_assigned_task_concept = 3;

  /**
   * Concepto de orden de trabajo iniciada
   *
   * @var int $_started_concept
   */
  public $_started_concept = 4;

  /**
   * Concepto de orden de trabajo finalizada
   *
   * @var int $_completed_concept
   */
  public $_completed_concept = 5;

  /**
   * Concepto de tarea de orden de trabajo concluida
   *
   * @var int $_conclusion_task_concept
   */
  public $_conclusion_task_concept = 7;

  /**
   * Estado de orden de trabajo en revisión
   *
   * @var int $_in_review_state
   */
  public $_in_review_state = 1;

  /**
   * Estado de orden de trabajo en asignación de tareas
   *
   * @var int $_in_assignment_state
   */
  public $_in_assignment_state = 2;

  /**
   * Estado de orden de trabajo iniciada
   *
   * @var int $_started_state
   */
  public $_started_state = 3;

  /**
   * Estado de orden de trabajo cerrada
   *
   * @var int $_completed_state
   */
  public $_completed_state = 4;

  public function __construct() {
    parent::__construct();

    $this->db_evpiu = $this->load->database('EVPIU', true);
    $this->load->model('Mantenimiento/evpiu/Activos_model', 'Activos_mdl');
    $this->load->model('Mantenimiento/evpiu/Solicitudes_model', 'Solicitudes_mdl');
    $this->load->model('Mantenimiento/evpiu/Estados_ordenes_trabajo_model', 'EstOrdenesT_mdl');
    $this->load->helper('language');
    $this->lang->load('ordenes_trabajo');
  }

  /**
   * Obtiene todas las ordenes de trabajo existentes.
   *
   * @return object
   */
  public function get_all_work_orders() {
    $this->load->library('Date_Utilities');
    $query = $this->db_evpiu->get($this->_work_order_view_table);

    if ($query->num_rows() > 0) {
      $results = $query->result();

      foreach ($results as $result) {
        $result->BeautyCreationDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaCreacion));
        $result->BeautyStartDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaInicio));
        $result->BeautyUpdateDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaActualizacion));
        $result->BeautyEndDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaFin));
      }

      return $results;
    } else {
      throw new Exception(lang('get_all_work_orders_no_results'));
    }
  }

  /**
   * Obtiene toda la información de una orden de trabajo en específico.
   *
   * @param int $work_order_code Código de la orden de trabajo.
   *
   * @return object
   */
  public function get_work_order($work_order_code) {
    $this->load->library('Date_Utilities');

    $this->db_evpiu->where('CodOt', $work_order_code);
    $query = $this->db_evpiu->get($this->_work_order_view_table);

    if ($query->num_rows() > 0) {
      $row = $query->row();
      $row->BeautyCreationFullDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $row->FechaCreacion));
      $row->BeautyUpdateFullDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $row->FechaActualizacion));
      $row->BeautyStartFullDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $row->FechaInicio));
      $row->BeautyEndFullDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $row->FechaFin));

      return $row;
    } else {
      throw new Exception(lang('get_work_order_no_results'));
    }
  }

  /**
   * Obtiene todas las tareas asignadas de una orden de trabajo
   * en específico.
   *
   * @param int $work_order_code Código de la orden de trabajo.
   *
   * @return object
   */
  public function get_work_order_details(int $work_order_code) {
    $this->load->library('Date_Utilities');

    $this->db_evpiu->where('CodOt', $work_order_code);
    $this->db_evpiu->order_by('idItem', 'asc');
    $query = $this->db_evpiu->get($this->_work_order_details_view_table);

    if ($query->num_rows() > 0) {
      $results = $query->result();

      foreach ($results as $result) {
        $result->BeautyCreationDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $result->FechaCreacion));
        $result->BeautyEndDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $result->FechaFinalizacion));
      }

      return $results;
    } else {
      throw new Exception(lang('get_work_orders_details_no_results'));
    }
  }

  /**
   * Genera una orden de trabajo a partir de una solicitud de mantenimiento.
   *
   * @param int $maint_request_code Código de la solicitud de mantenimiento.
   * @param string $maint_technician Usuario del encargado de la solicitud.
   * @param int $maint_type Código del tipo de mantenimiento.
   * @param string $maint_description Descripción de la solicitud de mantenimiento.
   *
   * @return int
   */
  public function generate_work_order_from_maintenance_request(int $maint_request_code, string $maint_technician, int $maint_type, string $maint_description) {
    $formatted_data = array(
      'CodSolicitud' => $maint_request_code,
      'Estado' => $this->EstOrdenesT_mdl->_in_review_state,
      'Encargado' => $maint_technician,
      'TipoMantenimiento' => $maint_type,
      'Creo' => $this->ion_auth->user()->row()->username,
      'FechaCreacion' => date('Y-m-d H:i:s'),
      'Descripcion' => $maint_description
    );

    $this->db_evpiu->insert($this->_work_order_header_table, $formatted_data);
    $insert_id = $this->db_evpiu->insert_id();

    if (!empty($insert_id)) {
      return $insert_id;
    } else {
      throw new Exception(lang('generate_work_order_from_mr_error'));
    }
  }

  /**
   * Obtiene todos los tipos de mantenimiento de una orden de trabajo.
   *
   * @return object
   */
  public function get_all_maintenance_types() {
    $this->db_evpiu->select('idTipoMantenimiento, Descripcion');
    $this->db_evpiu->order_by('Descripcion', 'asc');
    $query = $this->db_evpiu->get($this->_maintenance_types_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('get_all_maintenance_types_no_results'));
    }
  }

  /**
   * Obtiene todos los tipos de trabajo de una orden de trabajo.
   *
   * @return object
   */
  public function get_all_work_types() {
    $this->db_evpiu->select('CodTipoTrabajo, Descripcion');
    $this->db_evpiu->order_by('Descripcion', 'asc');
    $query = $this->db_evpiu->get($this->_work_types_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('get_all_work_types_no_results'));
    }
  }

  /**
   * Agrega una nueva tarea de orden de trabajo.
   *
   * @param array $task_data Datos requeridos para agregar una tarea.
   *
   * @return boolean
   */
  public function insert_task(array $task_data) {
    return $this->db_evpiu->insert($this->_work_order_details_table, $task_data);
  }

  /**
   * Actualiza la información de una tarea de orden de trabajo.
   *
   * @param int $task_id Código de la orden de trabajo.
   * @param array $data Datos específicos para actualizar una tarea.
   *
   * @return boolean
   */
  public function update_task(int $task_id, array $task_data) {
    return $this->db_evpiu->where('idItem', $task_id)
                          ->update($this->_work_order_details_table, $task_data);
  }

  /**
   * Actualiza la información de una orden de trabajo.
   *
   * @param int $work_order_code Código de la orden de trabajo.
   * @param array $data Datos específicos para actualizar una orden de trabajo.
   *
   * @return boolean
   */
  public function update_work_order(int $work_order_code, array $wo_data) {
    return $this->db_evpiu->where('CodOt', $work_order_code)
                          ->update($this->_work_order_header_table, $wo_data);
  }

  /**
   * Asigna una tarea a un técnico de mantenimiento en específico.
   *
   * Además es una transacción que reporta los conceptos de asignación
   * en el histórico de la orden de trabajo y datos de actualización de
   * la orden de trabajo.
   *
   * @param array $task_data Datos de la tarea a asignar.
   * @param array $event_data Datos del evento para reportar en el histórico
   * de la orden de trabajo.
   *
   * @return int|boolean
   */
  public function add_task_to_maint_technician(array $task_data, array $event_data) {
    $wo_code = $event_data['wo_code'];
    // Datos de la presente orden de trabajo
    $wo_data = $this->get_work_order($wo_code);

    $this->db_evpiu->trans_begin();

    // Se agrega una tarea con un técnico de mantenimiento asignado
    $insert_task = $this->insert_task($task_data);

    if (!$insert_task) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    // Se obtiene el id de la tarea que se acabó de agregar
    $task_id = $this->db_evpiu->insert_id();

    // Se reporta el concepto de asignación de tarea a técnico en el histórico
    // de la orden de trabajo
    $add_event_history = $this->add_event_to_history($this->_assigned_task_concept, $wo_code, $event_data['additional']);

    if (!$add_event_history) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    // Quien y cuando se actualizó la orden de trabajo
    $update_data = array(
      'Actualizo' => $this->ion_auth->user()->row()->username,
      'FechaActualizacion' => date('Y-m-d H:i:s')
    );

    // En la primera asignación de una tarea en la orden de trabajo, se cambia su estado.
    if ($wo_data->CodEstado === $this->_in_review_state) {
      $update_data['Estado'] = $this->_in_assignment_state;
    }

    // Actualización de datos de la orden de trabajo
    $update_work_order = $this->update_work_order($wo_code, $update_data);

    if (!$update_work_order) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    if ($this->db_evpiu->trans_status() === TRUE) {
      $this->db_evpiu->trans_commit();
      return $task_id;
    } else {
      $this->db_evpiu->trans_rollback();
      return FALSE;
    }
  }

  /**
   * Actualiza la descripción de una orden de trabajo.
   *
   * Es una transacción que actualiza la descripción de una orden de trabajo
   * específica y a la vez reporta un concepto de actualización en su histórico.
   *
   * @param int $wo_code Código de la orden de trabajo.
   * @param string $wo_description Nueva descripción de la orden de trabajo.
   *
   * @return boolean
   */
  public function update_description(int $wo_code, string $wo_description) {
    $this->db_evpiu->trans_begin();

    $update_data = array(
      'Descripcion' => $wo_description,
      'Actualizo' => $this->ion_auth->user()->row()->username,
      'FechaActualizacion' => date('Y-m-d H:i:s')
    );

    // Actualización de descripcion de la orden de trabajo
    $update_work_order = $this->update_work_order($wo_code, $update_data);

    if (!$update_work_order) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    // Se reporta el concepto de actualización de orden de trabajo
    $add_event_history = $this->add_event_to_history($this->_updated_concept, $wo_code, $wo_description);

    if (!$add_event_history) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    if ($this->db_evpiu->trans_status() === TRUE) {
      $this->db_evpiu->trans_commit();
      return TRUE;
    } else {
      $this->db_evpiu->trans_rollback();
      return FALSE;
    }
  }

  /**
   * Reporta la conclusión y el costo de una tarea de orden de trabajo.
   *
   * @param int $wo_code Código de la orden de trabajo.
   * @param array $task_data Datos de operación de la tarea para concluir.
   *
   * @return boolean
   */
  public function report_task_conclusion(int $wo_code, array $task_data) {
    $this->db_evpiu->trans_begin();

    $update_data = array(
      'CostoMat' => $task_data['task_cost'],
      'DetalleOperacion' => $task_data['task_description'],
      'Finalizada' => 1,
      'FechaFinalizacion' => date('Y-m-d H:i:s')
    );

    // Conclusión de tarea de la orden de trabajo
    $update_task = $this->update_task($task_data['task_id'], $update_data);

    if (!$update_task) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    // Se reporta el concepto de conclusión de tarea de la orden de trabajo
    $add_event_history = $this->add_event_to_history($this->_conclusion_task_concept, $wo_code);

    if (!$add_event_history) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    if ($this->db_evpiu->trans_status() === TRUE) {
      $this->db_evpiu->trans_commit();
      return TRUE;
    } else {
      $this->db_evpiu->trans_rollback();
      throw new Exception(lang('not_successfully_conclusion_task'));
    }
  }

  /**
   * Obtiene el histórico de una orden de trabajo en específico.
   *
   * @param int $wo_code Código de la orden de trabajo.
   *
   * @return object
   */
  public function get_work_order_history(int $wo_code) {
    $this->load->library('Date_Utilities');

    $this->db_evpiu->where('idOrdenTrabajo', $wo_code);
    $this->db_evpiu->order_by('Fecha', 'desc');
    $query = $this->db_evpiu->get($this->_timeline_wo_view_table);

    if ($query->num_rows() > 0) {
      $results = $query->result();

      foreach ($results as $result) {
        $result->BeautyEventDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $result->Fecha));
      }

      return $results;
    } else {
      throw new Exception(lang('get_work_orders_history_no_results'));
    }
  }

  /**
   * Comprueba si todas las tareas asignadas en una orden de
   * trabajo ya se encuentran concluidas.
   *
   * @param int $wo_code Código de la orden de trabajo
   *
   * @return boolean
   */
  public function check_assigned_tasks_completion(int $wo_code) {
    $this->db_evpiu->select('Finalizada')
                   ->where('CodOt', $wo_code);

    $query = $this->db_evpiu->get($this->_work_order_details_table);

    if ($query->num_rows() > 0) {
      $results = $query->result();

      $completed_tasks = TRUE;

      foreach ($results as $result) {
        if ($result->Finalizada === 1) {
          $completed_tasks = TRUE;
        } else {
          $completed_tasks = FALSE;
          break;
        }
      }

      return $completed_tasks;
    } else {
      throw new Exception(lang('get_assigned_tasks_no_results'));
    }
  }

  /**
   * Obtiene los costos de los trabajos asignados en una orden de trabajo.
   *
   * @param int $wo_code Código de la orden de trabajo.
   *
   * @return object
   */
  public function get_work_order_costs(int $wo_code) {
    $this->db_evpiu->select('CostoMat')
                   ->where('CodOt', $wo_code);

    $query = $this->db_evpiu->get($this->_work_order_details_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('get_work_order_costs_no_results'));
    }
  }

  /**
   * Inicia una orden de trabajo.
   *
   * @param int $wo_code Código de la orden de trabajo.
   *
   * @return boolean
   */
  public function start_work_order(int $wo_code) {
    $this->db_evpiu->trans_begin();

    // Datos de modificación de la orden de trabajo
    $update_wo_data = array(
      'Estado' => $this->_started_state,
      'Actualizo' => $this->ion_auth->user()->row()->username,
      'FechaActualizacion' => date('Y-m-d H:i:s'),
      'FechaInicio' => date('Y-m-d H:i:s')
    );

    // Actualiza los datos de la orden de trabajo
    $update_work_order = $this->update_work_order($wo_code, $update_wo_data);

    if (!$update_work_order) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    // Datos de modificación de la solicitud de mantenimiento
    $update_mr_data = array(
      'Estado' => $this->Solicitudes_mdl->_in_process_state,
      'Actualizo' => $this->ion_auth->user()->row()->username,
      'FechaActualizacion' => date('Y-m-d H:i:s')
    );

    // Obtiene datos de la orden de trabajo actual
    $get_work_order = $this->get_work_order($wo_code);

    // Obtiene el código de la solicitud de mantenimiento de esta orden de trabajo
    $maint_request_code = $get_work_order->CodSolicitud;

    // Actualiza los datos de la solicitud de mantenimiento
    $update_maint_request = $this->Solicitudes_mdl->update_maintenance_request($maint_request_code, $update_mr_data);

    if (!$update_maint_request) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    // Obtiene datos de la solicitud de mantenimiento vinculada a la orden de trabajo
    $get_maintenance_request = $this->Solicitudes_mdl->get_maintenance_request($maint_request_code);

    // Obtiene el código del activo de la solicitud de mantenimiento
    $asset_code = $get_maintenance_request->CodActivo;

    // Nuevo estado del activo de la solicitud de mantenimiento
    $update_asset_data = array(
      'idEstado' => $this->Activos_mdl->_in_repair_state
    );

    // Actualiza el estado del activo
    $update_asset = $this->Activos_mdl->update_asset_new_version($asset_code, $update_asset_data);

    if (!$update_asset) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    // Reporta el comienzo de la orden de trabajo en su histórico
    $add_wo_event_history = $this->add_event_to_history($this->_started_concept, $wo_code);

    if (!$add_wo_event_history) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    // Reportar el comienzo de la solicitud de mantenimiento en su histórico
    $add_mr_event_history = $this->Solicitudes_mdl->add_event_to_history($this->Solicitudes_mdl->_started_concept, $maint_request_code);

    if (!$add_mr_event_history) {
      $error_message = $this->db_evpiu->error()['message'];
      $error_code = $this->db_evpiu->error()['code'] + 0;
      throw new Exception($error_message, $error_code);
    }

    if ($this->db_evpiu->trans_status() === TRUE) {
      $this->db_evpiu->trans_commit();
      return TRUE;
    } else {
      $this->db_evpiu->trans_rollback();
      throw new Exception(lang('not_successfully_start_work_order'));
    }
  }

  /**
   * Finaliza una orden de trabajo.
   *
   * @param int $wo_code Código de la orden de trabajo.
   *
   * @return boolean
   */
  public function finish_work_order(int $wo_code) {
    $completed_tasks = $this->check_assigned_tasks_completion($wo_code);

    if ($completed_tasks) {
      $this->db_evpiu->trans_begin();

      // Obtiene todos los costos de las tareas asignadas en la orden de trabajo
      $work_order_costs = $this->get_work_order_costs($wo_code);

      // Acumulador de costo total de la orden de trabajo
      $costs_total = 0;

      // Calcula el costo total de la orden de trabajo
      foreach ($work_order_costs as $cost) {
        $costs_total += $cost->CostoMat;
      }

      // Datos para actualizar la orden de trabajo
      $update_wo_data = array(
        'Estado' => $this->_completed_state,
        'Costo' => $costs_total,
        'Actualizo' => $this->ion_auth->user()->row()->username,
        'FechaActualizacion' => date('Y-m-d H:i:s'),
        'FechaFin' => date('Y-m-d H:i:s')
      );

      // Actualiza los datos de la orden de trabajo
      $update_work_order = $this->update_work_order($wo_code, $update_wo_data);

      if (!$update_work_order) {
        $error_message = $this->db_evpiu->error()['message'];
        $error_code = $this->db_evpiu->error()['code'] + 0;
        throw new Exception($error_message, $error_code);
      }

      // Reporta la finalización de la orden de trabajo en su histórico
      $add_wo_event_history = $this->add_event_to_history($this->_completed_concept, $wo_code);

      if (!$add_wo_event_history) {
        $error_message = $this->db_evpiu->error()['message'];
        $error_code = $this->db_evpiu->error()['code'] + 0;
        throw new Exception($error_message, $error_code);
      }

      // Obtiene datos de la orden de trabajo actual
      $get_work_order = $this->get_work_order($wo_code);

      // Obtiene el código de la solicitud de mantenimiento de esta orden de trabajo
      $maint_request_code = $get_work_order->CodSolicitud;

      // Reporta la finalización de orden de trabajo en el histórico de la solicitud de mantenimiento
      $add_mr_event_history = $this->Solicitudes_mdl->add_event_to_history($this->Solicitudes_mdl->_work_order_finished_concept, $maint_request_code, $wo_code);

      if (!$add_mr_event_history) {
        $error_message = $this->db_evpiu->error()['message'];
        $error_code = $this->db_evpiu->error()['code'] + 0;
        throw new Exception($error_message, $error_code);
      }

      if ($this->db_evpiu->trans_status() === TRUE) {
        $this->db_evpiu->trans_commit();
        return TRUE;
      } else {
        $this->db_evpiu->trans_rollback();
        throw new Exception(lang('not_successfully_finish_work_order'));
      }
    } else {
      return FALSE;
    }
  }

  /**
   * Establece un mensaje para cada evento de una orden de trabajo.
   *
   * @param int $concept_code Código del concepto del evento.
   * @param string $additional Información adicional para conceptos específicos.
   *
   * @return string
   */
  private function set_event_message(int $concept_code, string $additional = '') {
    switch ($concept_code) {
      case $this->_created_concept:
        $message = lang('created_work_order_concept');
        break;
      case $this->_updated_concept:
        $message = sprintf(lang('updated_work_order_concept'), $additional);
        break;
      case $this->_assigned_task_concept:
        $message = sprintf(lang('assigned_task_work_order_concept'), $additional);
        break;
      case $this->_started_concept:
        $message = lang('started_work_order_concept');
        break;
      case $this->_completed_concept:
        $message = lang('completed_work_order_concept');
        break;
      case $this->_conclusion_task_concept:
        $message = lang('conclusion_task_work_order_concept');
        break;
      default:
        $message = NULL;
        break;
    }

    if ($message === NULL) {
      throw new Exception(lang('not_established_work_order_concept'));
    }

    return $message;
  }

  /**
   * Añade un evento al histórico de una orden de trabajo.
   *
   * @param int $concept_code Código del concepto del evento.
   * @param int $wo_code Código de la orden de trabajo.
   * @param string $additional Información adicional para conceptos específicos.
   *
   * @return int|boolean
   */
  public function add_event_to_history(int $concept_code, int $wo_code, string $additional = '') {
    try {
      // Si se añade un evento con el concepto de actualización, se anexan comentarios
      // a la descripción del evento.
      switch ($concept_code) {
        case $this->_updated_concept:
        case $this->_assigned_task_concept:
          $event_message = $this->set_event_message($concept_code, $additional);
          break;
        default:
          $event_message = $this->set_event_message($concept_code);
          break;
      }

      $event_data = array(
        'idOrdenTrabajo' => $wo_code,
        'idConcepto' => $concept_code,
        'DescEvento' => $event_message,
        'Usuario' => $this->ion_auth->user()->row()->username,
        'Fecha' => date('Y-m-d H:i:s')
      );

      $this->db_evpiu->insert($this->_timeline_wo_table, $event_data);
      $insert_id = $this->db_evpiu->insert_id();

      if (!empty($insert_id)) {
        return $insert_id;
      } else {
        return FALSE;
      }
    } catch (Exception $e) {
      throw $e;
    }
  }
}
