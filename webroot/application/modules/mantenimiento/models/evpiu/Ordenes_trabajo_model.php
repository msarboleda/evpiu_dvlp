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

}
