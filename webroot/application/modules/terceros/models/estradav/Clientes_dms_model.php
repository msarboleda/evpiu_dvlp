<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Clientes DMS
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Clientes_dms_model extends CI_Model {
	public function __construct() {
    parent::__construct();
    
		$this->db_dms = $this->load->database('DMS', true);
  }
  
  /**
   * Busca el vendedor asignado de un cliente de DMS.
   * 
   * @param int $nit NIT del Cliente.
   * 
   * @return object
   */
  public function find_vendor_assigned_to_customer($nit) {
    if (!isset($nit) || empty($nit)) {
      throw new \InvalidArgumentException('El parámetro de nit está vacío.');
    }

    $this->db_dms->select('vendedor, NombreVendedor');
    $this->db_dms->where('nit', $nit);

    $query = $this->db_dms->get('V_CIEV_Clientes');

    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      throw new Exception('No se obtuvo ningun resultado del cliente solicitado.');
    }
  }

  /**
   * Verifica que un cliente está creado en DMS.
   * 
   * @param int $nit NIT del Cliente.
   * 
   * @return boolean
   */
  public function check_created_client($nit) {
    if (!isset($nit) || empty($nit)) {
      throw new \InvalidArgumentException('El parámetro de nit está vacío.');
    }
    
    $this->db_dms->where('nit', $nit);

    $query = $this->db_dms->get('V_CIEV_Clientes');

    if ($query->num_rows() > 0) {
      return TRUE;
    } else {
      throw new Exception('No se obtuvo ningun resultado del cliente solicitado.');
    }
  }

  /**
   * Busca el tipo de un cliente de DMS.
   * 
   * @param int $nit NIT del Cliente.
   * 
   * @return object
   */
  public function find_customer_type($nit) {
    if (!isset($nit) || empty($nit)) {
      throw new \InvalidArgumentException('El parámetro de nit está vacío.');
    }

    $this->db_dms->select('TipoCliente');
    $this->db_dms->where('nit', $nit);

    $query = $this->db_dms->get('V_CIEV_Clientes');

    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      throw new Exception('No se obtuvo ningun resultado del cliente solicitado.');
    }
  }
}