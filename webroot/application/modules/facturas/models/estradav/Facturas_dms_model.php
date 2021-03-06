<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Facturas DMS
 *
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @author Martin Arboleda Montoya <sistemas@estradavelasquez.com>
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
   * Elimina todos los datos de las tablas de importación de facturas
   *
   * @return boolean
   */
  public function delete_all_data_from_import_tables() {
    $del_docs_max = $this->db_dms->empty_table('documentos_MAX');
    $del_movs_max = $this->db_dms->empty_table('movimiento_MAX');

    if ($del_docs_max === TRUE && $del_movs_max === TRUE) {
      return TRUE;
    } else {
      throw new Exception('Los datos de alguna tabla de importación no se pudieron borrar.');
    }
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
      'condicion' => '01',
      'vencimiento' => $invoice->fecha,
      'valor_total' => 0,
      'iva' => 0,
      'retencion' => 0,
      'retencion_causada' => 0,
      'retencion_iva' => 0,
      'retencion_ica' => 0,
      'descuento_pie' => 0,
      'fletes' => 0,
      'iva_fletes' => 0,
      'costo' => 0,
      'vendedor' => $invoice->codigo_vendedor_dms,
      'valor_aplicado' => 0,
      'anulado' => $invoice->anulada,
      'modelo' => $invoice->modelo,
      'documento' => $invoice->documento,
      'notas' => 'Factura anulada en WinPOS',
      'usuario' => substr($this->ion_auth->user()->row()->username, 0, 10),
      'pc' => gethostname(),
      'fecha_hora' => date('Y-m-d H:i:s'),
      'retencion2' => 0,
      'retencion3' => 0,
      'bodega' => $invoice->bodega,
      'impoconsumo' => 0,
      'descuento2' => 0,
      'duracion' => $invoice->duracion,
      'concepto' => $invoice->concepto,
      'impuesto_deporte' => 0,
      'valor_mercancia' => 0,
      'exportado' => $invoice->exportada
    );

    return $this->db_dms->insert('documentos_MAX', $voided_invoice);
  }

  /**
   * Reporta una factura correcta.
   *
   * @param object $invoice Factura a ser reportada.
   *
   * @return boolean
   */
  public function add_success_invoice($invoice = '') {
    if (empty($invoice)) {
      throw new \InvalidArgumentException('El contenido del parámetro no puede ser vacío.');
    }

    if (!is_object($invoice)) {
      throw new \TypeError('El parámetro debe tener una estructura de object.');
    }

    $success_invoice = array(
      'sw' => $invoice->sw,
      'tipo' => $invoice->tipo_documento,
      'numero' => $invoice->numero,
      'nit' => $invoice->nit,
      'fecha' => $invoice->fecha,
      'condicion' => $invoice->condicion,
      'vencimiento' => $invoice->vencimiento,
      'valor_total' => round($invoice->valor_total),
      'iva' => round($invoice->iva),
      'retencion' => round($invoice->rete_fuente),
      'retencion_causada' => 0,
      'retencion_iva' => 0,
      'retencion_ica' => 0,
      'descuento_pie' => round($invoice->descuento),
      'fletes' => 0,
      'iva_fletes' => 0,
      'costo' => 0,
      'vendedor' => $invoice->codigo_vendedor_dms,
      'valor_aplicado' => round($invoice->valor_aplicado),
      'anulado' => $invoice->anulada,
      'modelo' => $invoice->modelo,
      'documento' => $invoice->documento,
      'notas' => $invoice->notas,
      'usuario' => substr($this->ion_auth->user()->row()->username, 0, 10),
      'pc' => gethostname(),
      'fecha_hora' => date('Y-m-d H:i:s'),
      'retencion2' => 0,
      'retencion3' => 0,
      'bodega' => $invoice->bodega,
      'impoconsumo' => 0,
      'descuento2' => 0,
      'duracion' => $invoice->duracion,
      'concepto' => $invoice->concepto,
      'impuesto_deporte' => 0,
      'valor_mercancia' => round($invoice->valor_mercancia),
      'exportado' => $invoice->exportada
    );

    return $this->db_dms->insert('documentos_MAX', $success_invoice);
  }

  /**
   * Genera la estructura del valor de la mercancía para
   * la imputación contable de una factura.
   *
   * @param object $invoice Factura a tratar.
   *
   * @return array
   */
  private function generate_goods_value_structure($invoice) {
    $structure = array(
      'tipo' => $invoice->tipo_documento,
      'numero' => $invoice->numero,
      'seq' => 1,
      'cuenta' => $invoice->cuenta_venta,
      'centro' => 0,
      'nit' => $invoice->nit,
      'fec' => $invoice->fecha,
      'valor' => round(-$invoice->valor_mercancia),
      'base' => 0,
      'documento' => $invoice->documento
    );

    return $structure;
  }

  /**
   * Genera la estructura del valor del iva para la imputación
   * contable de una factura.
   *
   * @param object $invoice Factura a tratar.
   *
   * @return array
   */
  private function generate_iva_value_structure($invoice) {
    $structure = array(
      'tipo' => $invoice->tipo_documento,
      'numero' => $invoice->numero,
      'seq' => 2,
      'cuenta' => 24080507,
      'centro' => 0,
      'nit' => $invoice->nit,
      'fec' => $invoice->fecha,
      'valor' => round(-$invoice->iva),
      'base' => round($invoice->valor_mercancia),
      'documento' => $invoice->documento
    );

    return $structure;
  }

  /**
   * Genera la estructura del valor total para
   * la imputación contable de una factura.
   *
   * @param object $invoice Factura a tratar.
   *
   * @return array
   */
  private function generate_total_value_structure($invoice) {
    $structure = array(
      'tipo' => $invoice->tipo_documento,
      'numero' => $invoice->numero,
      'seq' => 3,
      'cuenta' => $invoice->cuenta_cobrar,
      'centro' => 0,
      'nit' => $invoice->nit,
      'fec' => $invoice->fecha,
      'valor' => round($invoice->valor_total),
      'base' => 0,
      'documento' => $invoice->documento
    );

    // El NIT debe de ser 0 cuando la cuenta por cobrar sea esta
    if ($structure['cuenta'] === 11050505) {
      $structure['nit'] = 0;
    }

    return $structure;
  }

  /**
   * Genera la estructura del valor de la retención en la fuente
   * para la imputación contable de una factura.
   *
   * @param object $invoice Factura a tratar.
   *
   * @return array
   */
  private function generate_retefuente_value_structure($invoice) {
    $structure = array(
      'tipo' => $invoice->tipo_documento,
      'numero' => $invoice->numero,
      'seq' => 4,
      'cuenta' => 13551505,
      'centro' => 0,
      'nit' => $invoice->nit,
      'fec' => $invoice->fecha,
      'valor' => round($invoice->rete_fuente),
      'base' => round($invoice->valor_mercancia),
      'documento' => $invoice->documento
    );

    return $structure;
  }

  /**
   * Reporta toda la imputación contable de una factura.
   *
   * @param object $invoice Factura a ser reportada.
   *
   * @return boolean
   */
  public function add_accounting_imputation($invoice = '') {
    if (empty($invoice)) {
      throw new \InvalidArgumentException('El contenido del parámetro no puede ser vacío.');
    }

    if (!is_object($invoice)) {
      throw new \TypeError('El parámetro debe tener una estructura de object.');
    }

    $goods_value_data = $this->generate_goods_value_structure($invoice);
    $iva_value_data = $this->generate_iva_value_structure($invoice);
    $total_value_data = $this->generate_total_value_structure($invoice);

    $this->db_dms->trans_begin();
    $this->db_dms->insert('movimiento_MAX', $goods_value_data);
    $this->db_dms->insert('movimiento_MAX', $iva_value_data);
    $this->db_dms->insert('movimiento_MAX', $total_value_data);

    // En caso de que el medio de pago de la factura sea retención en la fuente (6)
    // se debe anexar a la imputación contable el valor de la retención.
    if ($invoice->medio_pago === 6) {
      $retefuente_value_data = $this->generate_retefuente_value_structure($invoice);
      $this->db_dms->insert('movimiento_MAX', $retefuente_value_data);
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db_dms->trans_rollback();
      throw new Exception('Error al ejecutar la transacción de imputación contable de la factura.');
    } else {
      $this->db_dms->trans_commit();
      return TRUE;
    }
  }
}
