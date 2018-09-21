<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Estados de Activos
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Estados_activos_model extends CI_Model {
  public $_table = 'EstadoActivos';

	public function __construct() {
    parent::__construct();
    
    $this->db_evpiu = $this->load->database('EVPIU', true);
    $this->load->helper('language');
    $this->lang->load('estados_activos');
  }

  /**
   * Obtiene los valores necesarios para poblar un control
   * <select> de todos los estados de activos existentes.
   * 
   * @return object
   */
  public function populate_assets_states() {
    $this->db_evpiu->select('idEstado, NombreEstado');
    $this->db_evpiu->order_by('NombreEstado', 'asc');
    $query = $this->db_evpiu->get($this->_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('populate_assets_states_no_results'));
    }
  }
}