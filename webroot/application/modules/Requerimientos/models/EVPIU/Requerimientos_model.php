<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Requerimientos
 * 
 * Este modelo se relaciona con la tabla de Requerimientos.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */
class Requerimientos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'V_Requerimientos';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Obtiene toda la información de todos los requerimientos existentes.
	 *
	 * @param string $order
	 *
	 * @return bool|object
	 */
	public function get_Requerimientos($order = 'asc') {
		$this->db_evpiu->order_by('NroRequerimiento', $order);

		$reqs = $this->db_evpiu->get($this->_table);

		if ($reqs->num_rows() > 0) {
			return $reqs->result();
		}

    return FALSE;
	}

	/**
	 * Obtiene todos los requerimientos asignados a un vendedor en específico.
	 *
	 * @param int $vendor_id
	 * @param string $order
	 *
	 * @return bool|object
	 */
	public function get_Requerimientos_by_vendor($vendor_id = NULL, $order = 'asc') {
		if (!isset($vendor_id)) {
			return FALSE;
		}

		$this->db_evpiu->order_by('NroRequerimiento', $order);

		$reqs = $this->db_evpiu->get_where($this->_table, array('CodVendedor' => $vendor_id));

		if ($reqs->num_rows() > 0) {
			return $reqs->result();
		}

    return FALSE;
	}

	/**
	 * Obtiene todos los requerimientos asignados a un diseñador en específico.
	 *
	 * @param int $designer_id
	 * @param string $order
	 *
	 * @return bool|object
	 */
	public function get_Requerimientos_by_designer($designer_id = NULL, $order = 'asc') {
		if (!isset($designer_id)) {
			return FALSE;
		}

		$this->db_evpiu->order_by('NroRequerimiento', $order);

		$reqs = $this->db_evpiu->get_where($this->_table, array('CodDisenador' => $designer_id));

		if ($reqs->num_rows() > 0) {
			return $reqs->result();
		}

    return FALSE;
	}
}