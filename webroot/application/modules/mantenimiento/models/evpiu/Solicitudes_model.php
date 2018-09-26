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

	public function __construct() {
    parent::__construct();

    $this->db_evpiu = $this->load->database('EVPIU', true);
    $this->load->helper('language');
    $this->lang->load('solicitudes');
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
