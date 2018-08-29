<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Clientes
 * 
 * Este modelo se relaciona con la tabla de Clientes.
 * Tiene la funcionalidad de retornar todo tipo de dato relacionado con
 * esta tabla.
 */

class Customer_master_model extends CI_Model {
  public function __construct() {
    parent::__construct();

    $this->_table = 'Customer_Master';
    $this->db_maxestrada = $this->load->database('MAXEstrada', true);
  }

  /**
   * @description Obtiene los clientes del vendedor actual en la plataforma.
   *
   * @param string $vendor_code Código de vendedor.
   * @param string $order_column Columna para ordenar los resultados.
   * @param string $order Orden para mostrar los resultados.
   *
   * @return object
   * @return Exception
   */
  public function getCustomersDataFromCurrentVendor($order_column = 'NAME_23', $order = 'asc') {
    $this->db_maxestrada->where('SLSREP_23', $this->ion_auth->user()->row()->Vendedor);
    $this->db_maxestrada->order_by($order_column, $order);

    $query = $this->db_maxestrada->get($this->_table); 

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception('La consulta de clientes no obtuvo resultados.');
    }
  }
}