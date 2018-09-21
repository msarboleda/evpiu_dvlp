<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Estados de Activos
 *
 * Descripción de la clase
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Estados_activos extends MX_Controller {
  public function __construct() {
    parent::__construct();
    
    $this->load->model('Mantenimiento/evpiu/Estados_activos_model', 'Estados_mdl');
  }

  /**
   * Poblar un control <select> con todos los estados de 
   * de activos existentes.
   * 
   * @return object
   */
  public function populate_assets_states() {
    try {
      return $this->Estados_mdl->populate_assets_states();
    } catch (Exception $e) {
      return FALSE;
    }
  }
}