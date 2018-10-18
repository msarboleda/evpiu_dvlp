<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Solicitudes
 *
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Solicitudes_model extends CI_Model {
  public $_table = 'mant_Solicitudes';
  public $_master_view_table = 'V_Solicitudes_Mantenimiento';
  public $_timeline_mr_table = 'mant_HistoricoSolicitudes';
  public $_timeline_mr_view_table = 'V_mant_HistoricoSolicitudes';

  // Concepto de solicitud creada
  public $_created_concept = 1;

  // Concepto de solicitud actualizada
  public $_updated_concept = 2;

  // Concepto de orden de trabajo creada para la solicitud
  public $_work_order_created_concept = 3;

  // Concepto de solicitud iniciada
  public $_started_concept = 4;

  // Concepto de solicitud finalizada
  public $_completed_concept = 5;

  // Concepto de solicitud anulada
  public $_canceled_concept = 6;

  // Concepto de solicitud aprobada
  public $_approved_concept = 7;

	public function __construct() {
    parent::__construct();

    $this->db_evpiu = $this->load->database('EVPIU', true);
    $this->load->helper('language');
    $this->lang->load('solicitudes');
  }

  /**
   * Obtiene todas las solicitudes de mantenimiento existentes.
   *
   * @return object
   */
  public function get_all_maintenance_requests() {
    $this->load->library('Date_Utilities');
    $query = $this->db_evpiu->get($this->_master_view_table);

    if ($query->num_rows() > 0) {
      $results = $query->result();

      foreach ($results as $result) {
        $result->BeautyDamageDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaIncidente));
        $result->BeautyRequestDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaSolicitud));
      }

      return $results;
    } else {
      throw new Exception(lang('get_all_maintenance_requests_no_results'));
    }
  }

  /**
   * Obtiene las solicitudes de mantenimiento que ha realizado
   * un usuario de la plataforma.
   *
   * @param string $user Usuario de la plataforma.
   *
   * @return object
   */
  public function get_user_maintenance_requests($user) {
    $this->load->library('Date_Utilities');

    $this->db_evpiu->where('CodSolicitante', $user);
    $query = $this->db_evpiu->get($this->_master_view_table);

    if ($query->num_rows() > 0) {
      $results = $query->result();

      foreach ($results as $result) {
        $result->BeautyDamageDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaIncidente));
        $result->BeautyRequestDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaSolicitud));
      }

      return $results;
    } else {
      throw new Exception(lang('get_user_maintenance_requests_no_results'));
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
    $this->load->library('Date_Utilities');

    $this->db_evpiu->where('CodSolicitud', $maint_request_code);
    $query = $this->db_evpiu->get($this->_master_view_table);

    if ($query->num_rows() > 0) {
      $row = $query->row();
      $row->BeautyDamageDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $row->FechaIncidente));
      $row->BeautyRequestDate = ucfirst($this->date_utilities->format_date('%B %d, %Y', $row->FechaSolicitud));

      return $row;
    } else {
      throw new Exception(lang('get_maintenance_request_no_results'));
    }
  }

  /**
   * Agrega una solicitud de mantenimiento a la base de datos.
   *
   * @param array $data
   *
   * @return int|boolean
   */
  public function add_request_maintenance($data) {
    $real_damage_date = $data['damage_date'] . ' ' . $data['damage_time'];

    $formatted_data = array(
      'CodActivo' => strtoupper($data['damaged_asset']),
      'Solicitante' => $this->ion_auth->user()->row()->username,
      'FechaIncidente' => $real_damage_date,
      'Fecha' => date('Y-m-d H:i:s'),
      'Estado' => 1,
      'Descripcion' => $data['damage_description']
    );

    $this->db_evpiu->insert($this->_table, $formatted_data);
    $insert_id = $this->db_evpiu->insert_id();

    if (!empty($insert_id)) {
      return $insert_id;
    } else {
      throw new Exception(lang('add_rm_error'));
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
    $this->load->library('Date_Utilities');

    $this->db_evpiu->where('CodSolicitud', $maint_request_code);
    $this->db_evpiu->order_by('Fecha', 'desc');
    $query = $this->db_evpiu->get($this->_timeline_mr_view_table);

    if ($query->num_rows() > 0) {
      $results = $query->result();

      foreach ($results as $result) {
        $result->BeautyEventDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $result->Fecha));
      }

      return $results;
    } else {
      throw new Exception(lang('get_maintenance_request_history_no_results'));
    }
  }

  /**
   * Establece un mensaje para cada evento de una solicitud de mantenimiento.
   *
   * @param int $concept_code Código del concepto del evento.
   *
   * @return string
   */
  private function set_event_message($concept_code, $comments = '') {
    switch ($concept_code) {
      case $this->_created_concept:
        $message = lang('created_maint_request_event');
        break;
      case $this->_updated_concept:
        $message = sprintf(lang('com_added_maint_request_event'), $comments);
        break;
      case $this->_work_order_created_concept:
        $message = sprintf(lang('wo_created_maint_request_event'), $comments);
        break;
      case $this->_started_concept:
        $message = lang('started_maint_request_event');
        break;
      case $this->_completed_concept:
        $message = lang('completed_maint_request_event');
        break;
      case $this->_canceled_concept:
        $message = lang('canceled_maint_request_event');
        break;
      case $this->_approved_concept:
        $message = lang('approved_maint_request_event');
        break;
      default:
        $message = NULL;
        break;
    }

    if ($message === NULL) {
      throw new Exception(lang('concept_not_established_maint_request'));
    }

    return $message;
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
      // Si se añade un evento con el concepto de actualización, se anexan comentarios
      // a la descripción del evento.
      switch ($concept_code) {
        case $this->_updated_concept:
        case $this->_work_order_created_concept:
          $event_message = $this->set_event_message($concept_code, $comments);
          break;
        default:
          $event_message = $this->set_event_message($concept_code);
          break;
      }

      $formatted_data = array(
        'idSolicitud' => $maint_request_code,
        'idConcepto' => $concept_code,
        'Descripcion' => $event_message,
        'Usuario' => $this->ion_auth->user()->row()->username,
        'Fecha' => date('Y-m-d H:i:s')
      );

      $this->db_evpiu->insert($this->_timeline_mr_table, $formatted_data);
      $insert_id = $this->db_evpiu->insert_id();

      if (!empty($insert_id)) {
        return $insert_id;
      } else {
        throw new Exception(lang('add_event_to_history_error'));
      }
    } catch (Exception $e) {
      throw $e;
    }
  }
}
