<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Prioridades
 *
 * Descripción de la clase
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Prioridades extends MX_Controller {
  public function __construct() {
    parent::__construct();
    
    $this->load->model('Mantenimiento/evpiu/Prioridades_model', 'Prioridades_mdl');
  }

  /**
   * Poblar un control <select> con todas las prioridades 
   * existentes de los activos.
   * 
   * @return object
   */
  public function populate_priorities() {
    try {
      return $this->Prioridades_mdl->populate_priorities();
    } catch (Exception $e) {
      return FALSE;
    }
  }
}