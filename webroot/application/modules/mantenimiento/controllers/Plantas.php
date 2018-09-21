<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Plantas
 *
 * Descripción de la clase
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Plantas extends MX_Controller {
  public function __construct() {
    parent::__construct();
    
    $this->load->model('Mantenimiento/evpiu/Plantas_model', 'Plantas_mdl');
  }

  /**
   * Poblar un control <select> con todas las plantas 
   * existentes de activos.
   * 
   * @return object
   */
  public function populate_plants() {
    try {
      return $this->Plantas_mdl->populate_plants();
    } catch (Exception $e) {
      return FALSE;
    }
  }
}