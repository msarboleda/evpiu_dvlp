<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Listar Planos
 *
 * Esta clase sirve para listar los planos de los
 * productos de la compañía.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Get extends MX_Controller {
  public function __construct() {
    parent::__construct();
  }

  /**
   * Lista todos los planos de productos
   */
  public function index() {
    echo 'Hello world';
  }
}