<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Prioridades
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Prioridades_model extends CI_Model {
  public $_table = 'Prioridades';

	public function __construct() {
    parent::__construct();
    
    $this->db_evpiu = $this->load->database('EVPIU', true);
    $this->load->helper('language');
    $this->lang->load('prioridades');
  }

  /**
   * Obtiene los valores necesarios para poblar un control
   * <select> de todas las prioridades existentes.
   * 
   * @return object
   */
  public function populate_priorities() {
    $this->db_evpiu->select('idPrioridad, NombrePrioridad');
    $this->db_evpiu->order_by('NombrePrioridad', 'asc');
    $query = $this->db_evpiu->get($this->_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('populate_priorities_no_results'));
    }
  }
}