<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Usuarios
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Usuarios_model extends CI_Model {
  public $_table = 'users';

	public function __construct() {
    parent::__construct();
    
    $this->db_evpiu = $this->load->database('users', true);
    $this->load->helper('language');
    $this->lang->load('usuarios');
  }

  /**
   * Obtiene los valores necesarios para poblar un control
   * <select> de todos los usuarios existentes de la plataforma.
   * 
   * @return object
   */
  public function populate_users() {
    $this->db_evpiu->select("username, first_name + ' ' + last_name as Nombre");
    $this->db_evpiu->order_by('Nombre', 'asc');
    $query = $this->db_evpiu->get($this->_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('populate_users_no_results'));
    }
  }
}