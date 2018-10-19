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
   * Obtiene el id de un usuario con base al nombre de usuario.
   *
   * @param string $username Nombre del usuario.
   *
   * @return int
   */
  public function get_user_id_from_username($username) {
    try {
      return $this->Usuarios_mdl->get_user_id_from_username($username);
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
   * Petición AJAX para obtener el nombre de usuario
   * actual en la plataforma.
   *
   */
  public function xhr_get_current_username() {
    $data = new stdClass();
    $data->username = $this->ion_auth->user()->row()->username;
    header('Content-Type: application/json');
    echo json_encode($data);
  }

  /**
   * Petición AJAX para obtener todos los técnicos de
   * mantenimiento de la plataforma.
   *
   */
  public function xhr_get_all_maintenance_technicians() {
    try {
      $maintenance_technicians = $this->Usuarios_mdl->get_all_maintenance_technicians();
      header('Content-Type: application/json');
      echo json_encode($maintenance_technicians);
    } catch (Exception $e) {
      $data = new stdClass();
      $data->message = $e->getMessage();
      $data->content = array();

      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

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
