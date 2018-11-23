<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Estados de solicitudes de mantenimiento
 *
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Estados_solicitudes_model extends CI_Model {
  public $_table = 'mant_EstadosSolicitudes';

  // Estado de solicitud en revisión
  public $_in_revision_state = 1;

  // Estado de solicitud aprobada
  public $_approved_state = 2;

  // Estado de solicitud en proceso
  public $_in_process_state = 3;

  // Estado de solicitud anulada
  public $_cancelled_state = 4;

  // Estado de solicitud completada
  public $_completed_state = 5;

  // Estado de solicitud planeada
  public $_planned_state = 6;

  public function __construct() {
    parent::__construct();
  }
}
