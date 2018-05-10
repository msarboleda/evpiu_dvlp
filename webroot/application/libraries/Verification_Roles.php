<?php
/**
 * Nombre:    Verificación de Roles
 *
 * Creado:  10.05.2018
 *
 * Descripción:  Esta librería permite identificar cuando un usuario pertenece a un grupo en específico.
 *
 * Dependencias:
 * 
 * @package    CodeIgniter-Ion-Auth
 * @author     Ben Edmunds
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Verification_Roles
 */
class Verification_Roles {
	/**
	 * __construct
	 */
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model('ion_auth_model');
	}

	/**
	 * @param int|string|bool $id
	 *
	 * @return bool Si el usuario pertenece al grupo de 'members'
	 */
	public function is_member($id = FALSE) {
		$member_group = 'members';

		return $this->CI->ion_auth->in_group($member_group, $id);
	}
}