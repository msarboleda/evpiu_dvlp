<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Facturas DMS
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Facturas_dms_model extends CI_Model {
	public function __construct() {
    parent::__construct();
    
		$this->db_dms = $this->load->database('DMS', true);
  }
  
  /**
   * Obtiene las últimas facturas cargadas de cada punto
   * de venta.
   * 
   * @return mixed $result
   * @return Exception
   */
  public function get_latest_invoices_loaded_from_sale_points() {
    $this->db_dms->select('tipo, MAX(numero) as "factura", MAX(fecha) as "fecha"');
    $this->db_dms->where_in('tipo', array('FP2', 'FP3', 'FP5', 'FP6'));
    $this->db_dms->group_by('tipo');

    $query = $this->db_dms->get('documentos');

    if ($query->num_rows() > 0) {
      $result = $query->result();

      foreach ($result as $row) {
        switch ($row->tipo) {
          case 'FP2':
            $row->punto_venta = 'Itagui';
            break;
          case 'FP3':
            $row->punto_venta = 'Cali';
            break;
          case 'FP5':
            $row->punto_venta = 'Bogota';
            break;
          case 'FP6':
            $row->punto_venta = 'Dosquebradas';
        }
      }

      return $result;
    } else {
      throw new Exception('La consulta para obtener las últimas facturas de los puntos de venta cargadas no obtuvo resultados.');
    }
  }
}