<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de importación de facturas
 *
 * Esta clase se utiliza para realizar procedimientos relacionados
 * con la importación de facturas de diferentes entidades.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @author Martin Arboleda Montoya <maarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Importacion_facturas extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->library(array('header', 'verification_roles'));
    $this->load->helper(array('language', 'load', 'form'));
  }

  /**
   * Página principal de importación de facturas
   */
  public function index() {
    echo 'Index';
  }
}