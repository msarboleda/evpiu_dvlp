<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Clientes
 *
 * Este modelo obtiene información de los clientes del
 * software MAX.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2019 CI Estrada Velasquez y Cia S.A.S
 */

class Customer_master_model extends CI_Model {
  public function __construct() {
    parent::__construct();

    $this->_table = 'Customer_Master';
    $this->db_maxestrada = $this->load->database('MAXEstrada', true);
  }

  /**
   * Obtiene los datos de un cliente.
   *
   * @param string $customer_id Código del cliente.
   *
   * @return object
   */
  public function get_customer(string $customer_id) {
    $this->db_maxestrada->where('CUSTID_23', $customer_id);

    $query = $this->db_maxestrada->get($this->_table);

    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      throw new Exception('No se obtuvieron resultados del cliente solicitado.');
    }
  }
}
