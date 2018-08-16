<?php defined('BASEPATH') OR exit('No esta permitido el acceso directo a este directorio');

/**
 * Modelo de Pedidos
 * 
 * Este modelo se relaciona con la tabla de Pedidos y solo aplica para tratar la tabla de EncabezadoPedidos
 * dentro del constructor, se busca retornar todo tipo de dato relacionado con esta tabla.
 *
 * @author Martin Arboleda Montoya <sistemas@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Pedidos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'V_EncabezadoPedidos';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * @description Obtiene todos los pedidos registrados en la tabla encabezado pedidos. 
	 * 
	 * @param string $order_column 
	 * @param string $order 
	 * 
	 * @return object
	 * @return Exception
	 */
	public function get_All_Pedidos($order_column = 'NroPedido', $order = 'desc') {
		$this->db_evpiu->order_by($order_column, $order);

		$query = $this->db_evpiu->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			throw new Exception('La consulta de pedidos no obtuvo resultados.');
		}
	}
}