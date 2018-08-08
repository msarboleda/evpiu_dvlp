<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reportes de Clientes
 *
 * Esta clase posee reportes útiles sobre los clientes
 * de la compañía.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Clientes extends MX_Controller {
  public function __construct() {
    parent::__construct();
  }

  /**
   * Página principal de Reporte de Clientes
   */
  public function index() {
    echo 'Hello world';
  }
}