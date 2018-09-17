<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Vendedores DMS
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @author Martin Arboleda Montoya <maarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Vendedores_dms_model extends CI_Model {
	public function __construct() {
    parent::__construct();
    
		$this->db_wpos = $this->load->database('DMS', true);
  }
  
  /**
   * Busca un información de un vendedor por medio de su código.
   * 
   * @param int $vendor_code Código del vendedor.
   * 
   * @return object
   */
  public function find_vendor($vendor_code) {
    if (!isset($vendor_code) || empty($vendor_code)) {
      throw new \InvalidArgumentException('El parámetro de código de vendedor está vacío.');
    }

    $this->db_wpos->where('concepto_6', 1);
    $this->db_wpos->where('nit', $vendor_code);

    $query = $this->db_wpos->get('terceros');

    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      throw new Exception('La consulta para este vendedor no obtuvo resultados.');
    }
  }
}