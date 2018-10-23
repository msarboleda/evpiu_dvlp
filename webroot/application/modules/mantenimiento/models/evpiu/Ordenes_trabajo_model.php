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
  // Tabla de encabezados de ordenes de trabajo
  public $_work_order_header_table = 'mant_EncabezadoOrdenesTrabajo';
  // Vista que fusiona el encabezado y detalle de las ordenes de trabajo
  public $_work_order_view_table = 'V_mant_OrdenesTrabajo';
  // Tabla de tipos de mantenimiento de las ordenes de trabajo
  public $_maintenance_types_table = 'mant_TiposMantenimiento';

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
}
