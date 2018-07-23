<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Marcas
 *
 * Esta clase posee funciones útiles para las marcas de los productos
 * de la compañía.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Marcas extends MX_Controller {
  public function __construct() {
    parent::__construct();
  }

  /**
   * Página principal de Marcas
   */
  public function index() {
    echo 'Hello world';
  }
}