<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Clasificaciones
 *
 * Descripción de la clase
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Clasificaciones extends MX_Controller {
  public function __construct() {
    parent::__construct();
    
    $this->load->model('Mantenimiento/evpiu/Clasificaciones_model', 'Clasificaciones_mdl');
  }

  /**
   * Poblar un control <select> con todas las clasificaciones 
   * existentes de activos.
   * 
   * @return object
   */
  public function populate_classifications() {
    try {
      return $this->Clasificaciones_mdl->populate_classifications();
    } catch (Exception $e) {
      return FALSE;
    }
  }
}