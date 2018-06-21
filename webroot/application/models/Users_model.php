<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Usuarios
 * 
 * Este modelo se relaciona con la tabla de Usuarios.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class Users_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'users';
	}

	/**
	 * Devuelve toda la información de un vendedor.
	 *
	 * @param string $vendor_code Código de vendedor.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function find_Vendor($vendor_code = NULL) {
		if (!isset($vendor_code)) {
			return FALSE;
		}

		$this->db->select();
		$this->db->where('Vendedor', $vendor_code);

		$query = $this->db->get($this->_table); 

		if ($query->num_rows() > 0) {
			$row = $query->row();

			return $row;
		}

    return FALSE;
	}

	/**
	 * Organiza los asesores comerciales activos perfectamente 
	 * para que se muestren en el plugin 'Select2'
	 *
	 * @param string $order
	 *
	 * @return array|bool
	 */
	public function fill_Vendedores_select($order = 'asc') {
		$this->db->select('Vendedor, first_name, last_name');
		$this->db->where('Vendedor !=', NULL);
		$this->db->where('active', 1);
		$this->db->order_by('first_name', $order);

		$query = $this->db->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();

			$vendedores[''] = 'Seleccione un asesor comercial';

			foreach ($result as $row) {
				$vendedores[$row['Vendedor']] = $row['first_name']." ".$row['last_name'];
			}

			// Información del usuario actual
			$actual_user = $this->ion_auth->user()->row();
			// Elimina el usuario actual de la lista
			unset($vendedores[$actual_user->Vendedor]);

			return $vendedores;
		}

    return FALSE;
	}
}