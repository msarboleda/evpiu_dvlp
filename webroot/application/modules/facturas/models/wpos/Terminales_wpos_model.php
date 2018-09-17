<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Terminales WinPOS
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Terminales_wpos_model extends CI_Model {
	public function __construct() {
    parent::__construct();
    
    $this->db_wpos = $this->load->database('WINPOS', true);
  }
  
  /**
   * Obtiene los datos de una terminal
   * 
   * @param int $terminal Código de la terminal del punto
   * de venta de donde se obtendrán las facturas.
   * 
   * @return object
   */
  public function find_terminal($terminal) {
    if (!isset($terminal) || empty($terminal)) {
      throw new \InvalidArgumentException('El parámetro de terminal está vacío.');
    }

    $this->db_wpos->where('idTerminal', $terminal);

    $query = $this->db_wpos->get('Terminal');

    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      throw new Exception('No se obtuvo ningun resultado para la terminal solicitada.');
    }
  }
}