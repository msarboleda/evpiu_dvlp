<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Plantas
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Plantas_model extends CI_Model {
  public $_table = 'Plantas';

	public function __construct() {
    parent::__construct();
    
    $this->db_evpiu = $this->load->database('EVPIU', true);
    $this->load->helper('language');
    $this->lang->load('plantas');
  }

  /**
   * Obtiene los valores necesarios para poblar un control
   * <select> de todas las plantas existentes.
   * 
   * @return object
   */
  public function populate_plants() {
    $this->db_evpiu->select('idPlanta, NombrePlanta');
    $this->db_evpiu->order_by('NombrePlanta', 'asc');
    $query = $this->db_evpiu->get($this->_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('populate_plants_no_results'));
    }
  }
}