<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Estados de ordenes de trabajo
 *
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Estados_ordenes_trabajo_model extends CI_Model {
  public $_table = 'mant_EstadosOrdenesTrabajo';

  // Estado de orden de trabajo en revisión
  public $_in_review_state = 1;

  // Estado de orden de trabajo asignada
  public $_assigned_state = 2;

  // Estado de orden de trabajo iniciada
  public $_started_state = 3;

  // Estado de orden de trabajo cerrada
  public $_closed_state = 4;

  // Estado de orden de trabajo anulada
  public $_canceled_state = 5;

  public function __construct() {
    parent::__construct();
  }
}
