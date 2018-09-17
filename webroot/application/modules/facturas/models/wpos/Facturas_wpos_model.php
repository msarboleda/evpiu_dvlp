<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Facturas WinPOS
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @author Martin Arboleda Montoya <sistemas@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Facturas_wpos_model extends CI_Model {
	public function __construct() {
    parent::__construct();
    
		$this->db_wpos = $this->load->database('WINPOS_Ventas', true);
  }
  
  /**
   * Obtiene las últimas facturas cargadas de cada punto
   * de venta en una fecha específica.
   * 
   * @param int $terminal Código de la terminal del punto
   * de venta de donde se obtendrán las facturas.
   * @param string $fecha Fecha para obtener las facturas.
   * 
   * @return object
   */
  public function get_invoices_from_sale_point_on_date($terminal, $fecha) {
    if (!isset($terminal) || empty($terminal)) {
      throw new \InvalidArgumentException('El parámetro de terminal está vacío.');
    }

    if (!isset($fecha) || empty($fecha)) {
      throw new \InvalidArgumentException('El parámetro de fecha está vacío.');
    }

    $this->db_wpos->where('idTerminal', $terminal);
    $this->db_wpos->where('Fecha', $fecha);
    $this->db_wpos->where('terminado', 1);

    $query = $this->db_wpos->get('VentasImportarDMS');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception('No se obtuvieron facturas para este punto de venta en la fecha solicitada.');
    }
  }
}