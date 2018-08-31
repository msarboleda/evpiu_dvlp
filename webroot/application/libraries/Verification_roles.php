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
class Verification_roles {
	protected $CI;

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

	/**
	 * @param int|string $id
	 *
	 * @return bool Es un vendedor
	 */
	public function is_vendor($id = FALSE) {
		$member_group = 'vendors';

		return $this->CI->ion_auth->in_group($member_group, $id);
	}

	/**
	 * @param int|string $id
	 *
	 * @return bool Es un diseñador
	 */
	public function is_designer($id = FALSE) {
		$member_group = 'designers';

		return $this->CI->ion_auth->in_group($member_group, $id);
	}

	/**
	 * @param int|string $id
	 *
	 * @return bool Es un coordinador de diseño
	 */
	public function is_design_coord($id = FALSE) {
		$member_group = 'design_coord';

		return $this->CI->ion_auth->in_group($member_group, $id);
	}

	/**
	 * @param int|string $id
	 *
	 * @return bool Es un gestor de planos
	 */
	public function is_flat_manager($id = FALSE) {
		$member_group = 'flat_manager';

		return $this->CI->ion_auth->in_group($member_group, $id);
	}

	/**
	 * @param int|string $id
	 *
	 * @return bool Es un gestor de importación de facturas
	 */
	public function is_invoice_import_manager($id = FALSE) {
		$member_group = 'invoice_imp_manager';

		return $this->CI->ion_auth->in_group($member_group, $id);
	}
}