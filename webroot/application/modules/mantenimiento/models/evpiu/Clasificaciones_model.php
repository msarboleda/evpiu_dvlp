<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Clasificaciones
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Clasificaciones_model extends CI_Model {
  public $_table = 'ClasificacionActivos';

	public function __construct() {
    parent::__construct();
    
    $this->db_evpiu = $this->load->database('EVPIU', true);
    $this->load->helper('language');
    $this->lang->load('clasificaciones');
  }

  /**
   * Obtiene los valores necesarios para poblar un control
   * <select> de todas las clasificaciones de activos existentes.
   * 
   * @return object
   */
  public function populate_classifications() {
    $this->db_evpiu->select('idClasificacion, Nombre');
    $this->db_evpiu->order_by('Nombre', 'asc');
    $query = $this->db_evpiu->get($this->_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('populate_classifications_no_results'));
    }
  }
}