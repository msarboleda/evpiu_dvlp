<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Usuarios
 *
 * Descripción de la clase
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Usuarios extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Terceros/users/Usuarios_model', 'Usuarios_mdl');
  }

  /**
   * Poblar un control <select> con todos los usuarios 
  /**
   * Poblar un control <select> con todos los usuarios
   * existentes de la plataforma.
   *
   * @return object
   */
  public function populate_users() {
    try {
      return $this->Usuarios_mdl->populate_users();
    } catch (Exception $e) {
      return FALSE;
    }
  }
}
