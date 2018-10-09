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
}
