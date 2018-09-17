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
            $row->terminal = 630;
            break;
          case 'FP3':
            $row->punto_venta = 'Cali';
            $row->terminal = 620;
            break;
          case 'FP5':
            $row->punto_venta = 'Bogota';
            $row->terminal = 650;
            break;
          case 'FP6':
            $row->punto_venta = 'Dosquebradas';
            $row->terminal = 660;
        }
      }

      return $result;
    } else {
      throw new Exception('La consulta para obtener las últimas facturas de los puntos de venta cargadas no obtuvo resultados.');
    }
  }

  /**
   * Elimina todos los datos de la tabla de documentos_MAX.
   * 
   * @return boolean
   */
  public function delete_all_data_from_documentos_max() {
    return $this->db_dms->empty_table('documentos_MAX');
  }

  /**
   * Elimina todos los datos de la tabla de movimiento_MAX.
   * 
   * @return boolean
   */
  public function delete_all_data_from_movimiento_max() {
    return $this->db_dms->empty_table('movimiento_MAX');
  }

  /**
   * Reporta una factura anulada.
   * 
   * @param object $invoice Factura a ser reportada.
   * 
   * @return boolean
   */
  public function add_voided_invoice($invoice = '') {
    if (empty($invoice)) {
      throw new \InvalidArgumentException('El contenido del parámetro no puede ser vacío.');
    }

    if (!is_object($invoice)) {
      throw new \TypeError('El parámetro debe tener una estructura de object.'); 
    }

    $voided_invoice = array(
      'sw' => $invoice->sw,
      'tipo' => $invoice->tipo_documento,
      'numero' => $invoice->numero,
      'nit' => 3050,
      'fecha' => $invoice->fecha,
      'anulado' => $invoice->anulada,
      'usuario' => $this->ion_auth->user()->row()->username,
      'pc' => gethostname(),
      'fecha_hora' => date('Y-m-d H:i:s'),
      'bodega' => $invoice->bodega
    );

    return $this->db_dms->insert('documentos_MAX', $voided_invoice);
  }
}