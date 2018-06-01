<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Clientes
 * 
 * Este modelo se relaciona con la tabla de Clientes.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class Customer_Master_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Customer_Master';
		$this->db_maxestrada = $this->load->database('MAXEstrada', true);
	}

	/**
	 * Organiza las sublíneas de productos existentes perfectamente 
	 * para que se muestren en el plugin 'Select2'
	 *
	 * @param string $vendor_code
	 * @param string $order
	 *
	 * @return array|bool
	 */
	public function fill_Clientes_from_Vendor_select($vendor_code = NULL, $order = 'asc') {
		if (!isset($vendor_code)) {
			return FALSE;
		}

		$this->db_maxestrada->select('CUSTID_23, NAME_23');
		$this->db_maxestrada->where('SLSREP_23', $vendor_code);
		$this->db_maxestrada->order_by('NAME_23', $order);

		$query = $this->db_maxestrada->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();

			$clientes_vendedor[''] = 'Selecciona un Cliente';

			foreach ($result as $row) {
				$clientes_vendedor[$row['CUSTID_23']] = $row['NAME_23'];
			}

			return $clientes_vendedor;
		}

    return FALSE;
	}
}