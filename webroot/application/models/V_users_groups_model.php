<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para la Vista de Grupos de Usuarios.
 * 
 * Este modelo se relaciona con la vista de grupos de usuarios;
 * Tiene funciones dedicadas exclusivamente a la vista definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta vista.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class V_users_groups_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'V_users_groups';
	}

	/**
	 * Devuelve los correos electrónicos de los usuarios dentro de un grupo.
	 *
	 * @param string $group Grupo de usuarios.
	 *
	 * @return object Correos electrónicos de los usuarios del grupo.
	 * 		boolean En caso de que la consulta no devuelva resultados.
	 */
	public function return_Users_Email_from_Group($group = '') {
		if (!isset($group) || empty($group)) {
			return FALSE;
		}

		$this->db->select('email');
		$this->db->where('grupo', $group);

		$query = $this->db->get($this->_table); 

		if ($query->num_rows() > 1) {
			return $query->result();
		} else if ($query->num_rows() === 1) {
			return $query->row();
		}

    return FALSE;
	}
}