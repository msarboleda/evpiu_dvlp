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
  // Tabla de solicitudes de mantenimiento
  public $_table = 'mant_Solicitudes';
  // Vista de las solicitudes de mantenimiento
  public $_master_view_table = 'V_Solicitudes_Mantenimiento';
  // Tabla del histórico de las solicitudes de mantenimiento
  public $_timeline_mr_table = 'mant_HistoricoSolicitudes';
  // Vista del histórico de las solicitudes de mantenimiento
  public $_timeline_mr_view_table = 'V_mant_HistoricoSolicitudes';

  // Concepto de solicitud creada
  public $_created_concept = 1;

  // Concepto de solicitud actualizada
  public $_updated_concept = 2;

  // Concepto de orden de trabajo creada para la solicitud
  public $_work_order_created_concept = 3;

  // Concepto de orden de trabajo finalizada
  public $_work_order_finished_concept = 8;

  // Concepto de solicitud iniciada
  public $_started_concept = 4;

  // Concepto de solicitud finalizada
  public $_completed_concept = 5;

  // Concepto de solicitud anulada
  public $_canceled_concept = 6;

  // Concepto de solicitud aprobada
  public $_approved_concept = 7;

  // Estado de solicitud en revisión
  public $_in_review_state = 1;

  // Estado de solicitud aprobada
  public $_approved_state = 2;

  // Estado de solicitud en proceso
  public $_in_process_state = 3;

  // Estado de solicitud anulada
  public $_canceled_state = 4;

  // Estado de solicitud finalizada
  public $_completed_state = 5;

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
   * Actualiza el estado de una solicitud de mantenimiento.
   *
   * @param int $maint_request_code Código de la solicitud de mantenimiento.
   * @param int $new_state Código del nuevo estado de la solicitud.
   *
   * @return boolean
   */
  public function update_maintenance_request_state(int $maint_request_code, int $new_state) {
    $formatted_data = array(
      'Estado' => $new_state,
      'Actualizo' => $this->ion_auth->user()->row()->username,
      'FechaActualizacion' => date('Y-m-d H:i:s')
    );

    $this->db_evpiu->where('idSolicitud', $maint_request_code);
    $updated = $this->db_evpiu->update($this->_table, $formatted_data);

    if ($updated) {
      return $updated;
    } else {
      throw new Exception(lang('update_mr_error'));
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
   * Obtiene información de un evento del histórico de las solicitudes de
   * mantenimiento.
   *
   * @param int $event_code Código de evento a consultar.
   *
   * @return object
   */
  public function get_event_from_maintenance_request_history($event_code) {
    $this->load->library('Date_Utilities');

    $this->db_evpiu->where('CodEvento', $event_code);
    $query = $this->db_evpiu->get($this->_timeline_mr_view_table);

    if ($query->num_rows() > 0) {
      $row = $query->row();

      $row->BeautyDate = ucfirst($this->date_utilities->format_date('%B %d, %Y %r', $row->Fecha));

      return $row;
    } else {
      throw new Exception(lang('get_event_no_results'));
    }
  }

  /**
   * Actualiza los datos de una solicitud de mantenimiento.
   *
   * @param int $mr_code Código de la solicitud de mantenimiento.
   * @param array $mr_data Datos que actualizarán la solicitud de mantenimiento.
   *
   * @return boolean
   */
  public function update_maintenance_request(int $mr_code, array $mr_data) {
    return $this->db_evpiu->where('idSolicitud', $mr_code)
                          ->update($this->_table, $mr_data);
  }

  /**
   * Finaliza una solicitud.
   *
   * @param int $request_code Código de la solicitud
   *
   * @return type
   */
  public function finish_request(int $request_code) {
    $this->load->model('Mantenimiento/evpiu/Activos_model', 'Activos_mdl');
    $this->load->model('Mantenimiento/evpiu/Ordenes_trabajo_model', 'OrdenesT_mdl');

    try {
      $completed_work_orders = $this->OrdenesT_mdl->check_completed_work_orders($request_code);

      if ($completed_work_orders) {
        $this->db_evpiu->trans_begin();

        try {
          // Obtiene datos de la solicitud
          $get_request = $this->get_maintenance_request($request_code);

          // Obtiene el código del activo de la solicitud
          $asset_code = $get_request->CodActivo;

          try {
            // Obtiene datos del activo
            $get_asset = $this->Activos_mdl->get_asset($asset_code);

            // Obtiene el costo de mantenimiento del activo
            $asset_cost = $get_asset->CostoMantenimiento;

            try {
              // Obtiene los costos de las ordenes de trabajo vinculadas a la solicitud
              $request_costs = $this->OrdenesT_mdl->get_request_costs($request_code);

              // Acumulador para calcular el total de la solicitud
              $request_total_cost = 0;

              foreach ($request_costs as $cost) {
                $request_total_cost += $cost->Costo;
              }

              // Nuevo costo acumulado del activo
              $maintenance_cost = $asset_cost + $request_total_cost;

              // Datos para actualizar la solicitud
              $update_request_data = array(
                'Estado' => $this->_completed_state,
                'Actualizo' => $this->ion_auth->user()->row()->username,
                'FechaActualizacion' => date('Y-m-d H:i:s'),
                'FechaCierre' => date('Y-m-d H:i:s')
              );

              // Actualiza los datos de la solicitud
              $update_request = $this->update_maintenance_request($request_code, $update_request_data);

              if (!$update_request) {
                $error_message = $this->db_evpiu->error()['message'];
                $error_code = $this->db_evpiu->error()['code'] + 0;
                throw new BDException($error_message, $error_code);
              }

              // Reporta la finalización de la solicitud en su histórico
              $report_completed_request = $this->add_event_to_history($this->_completed_concept, $request_code);

              if (!$report_completed_request) {
                $error_message = $this->db_evpiu->error()['message'];
                $error_code = $this->db_evpiu->error()['code'] + 0;
                throw new Exception($error_message, $error_code);
              }

              // Datos para actualizar el activo
              $update_asset_data = array(
                'idEstado' => $this->Activos_mdl->_good_state,
                'UltimaRevision' => date('Y-m-d'),
                'CostoMantenimiento' => $maintenance_cost
              );

              // Actualiza los datos del activo
              $update_asset = $this->Activos_mdl->update_asset_new_version($asset_code, $update_asset_data);

              if (!$update_asset) {
                $error_message = $this->db_evpiu->error()['message'];
                $error_code = $this->db_evpiu->error()['code'] + 0;
                throw new BDException($error_message, $error_code);
              }

              if ($this->db_evpiu->trans_status() === TRUE) {
                $this->db_evpiu->trans_commit();
                return TRUE;
              } else {
                $this->db_evpiu->trans_rollback();
                throw new Exception('Error al ejecutar la transacción de finalización de solicitud.');
              }
            } catch (Exception $e) {
              throw $e;
            }
          } catch (Exception $e) {
            throw $e;
          }
        } catch (Exception $e) {
          throw $e;
        }
      } else {
        throw new Exception(lang('unfinished_work_orders'));
      }
    } catch (Exception $e) {
      throw $e;
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
        $message = sprintf(lang('wo_created_work_order_event'), $comments);
        break;
      case $this->_work_order_finished_concept:
        $message = sprintf(lang('wo_finished_work_order_event'), $comments);
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
        case $this->_work_order_finished_concept:
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
