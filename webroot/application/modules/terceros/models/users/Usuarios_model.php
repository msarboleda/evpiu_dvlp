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
  public $_users_groups_view_table = 'V_users_groups';

	public function __construct() {
    parent::__construct();

    $this->db_evpiu = $this->load->database('users', true);
    $this->load->helper('language');
    $this->lang->load('usuarios');
  }

  /**
   * Obtiene el id de un usuario con base al nombre de usuario.
   *
   * @param string $username Nombre del usuario.
   *
   * @return int
   */
  public function get_user_id_from_username($username) {
    $this->db_evpiu->select('id');
    $this->db_evpiu->where('username', $username);
    $query = $this->db_evpiu->get($this->_table);

    if ($query->num_rows() > 0) {
      return $query->row()->id;
    } else {
      throw new Exception(lang('get_user_id_from_username_no_results'));
    }
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

  /**
   * Obtiene todos los usuarios de la plataforma que se encuentran
   * dentro del grupo de técnicos de mantenimiento.
   *
   * @return object
   */
  public function get_all_maintenance_technicians() {
    $this->db_evpiu->select('usuario, nombre_usuario');
    $this->db_evpiu->where('grupo', 'maint_technician');
    $this->db_evpiu->order_by('nombre_usuario', 'asc');
    $query = $this->db_evpiu->get($this->_users_groups_view_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('get_maintenance_technicians_no_results'));
    }
  }

  /**
	 * Obtiene los correos electrónicos de todos los usuarios de un grupo
   * de la plataforma.
	 *
	 * @param string $group Grupo del cuál obtener los correos electrónicos
   * de los usuarios.
	 *
	 * @return object
	 */
	public function get_emails_from_users_group($group) {
		$this->db_evpiu->select('email');
		$this->db_evpiu->where('grupo', $group);

		$query = $this->db_evpiu->get($this->_users_groups_view_table);

		if ($query->num_rows() > 1) {
			return $query->result();
    }

    if ($query->num_rows() === 1) {
			return $query->row('email');
		}

    throw new Exception(lang('get_emails_from_users_group_no_results'));
	}
}
