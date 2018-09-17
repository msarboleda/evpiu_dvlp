<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de importación de facturas
 *
 * Esta clase se utiliza para realizar procedimientos relacionados
 * con la importación de facturas de diferentes entidades.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @author Martin Arboleda Montoya <maarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Importacion_facturas extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->model('Facturas/estradav/Facturas_dms_model', 'Facturas_dms_mdl');
    $this->load->model('Facturas/wpos/Facturas_wpos_model', 'Facturas_wpos_mdl');
    $this->load->library(array('header', 'verification_roles', 'messages'));
    $this->load->helper(array('language', 'load', 'form'));
    $this->lang->load('importacion_facturas');
  }

  /**
   * Página principal de importación de facturas
   */
  public function index() {
    echo 'Index';
  }

  /**
   * Importa las facturas de la aplicación WinPOS 
   * a la aplicación DMS.
   */
  public function import_invoices_from_winpos_to_dms() {
    if ($this->verification_roles->is_invoice_import_manager() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('IIWD_heading');

      $view_data['latest_invoices'] = $this->get_latest_invoices_loaded_from_sale_points();
      $view_data['messages'] = $this->messages->get();

      add_css('dist/vendor/pickadate.js/themes/classic.css');
      add_css('dist/vendor/pickadate.js/themes/classic.date.css');
      add_css('dist/custom/css/facturas/import_invoices_from_winpos_to_dms.css');
      add_js('dist/vendor/pickadate.js/picker.js');
      add_js('dist/vendor/pickadate.js/picker.date.js');
      add_js('dist/vendor/pickadate.js/translations/date_es_ES.js');
      add_js('dist/custom/js/facturas/import_invoices_from_winpos_to_dms.js');
      
      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('facturas'. DS .'import_invoices_from_winpos_to_dms', $view_data);
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }

  /**
   * Obtiene las últimas facturas cargadas de cada punto
   * de venta.
   * 
   * @return mixed $latest_invoices
   * @return boolean False
   */
  public function get_latest_invoices_loaded_from_sale_points() {
    try {
      $latest_invoices = $this->Facturas_dms_mdl->get_latest_invoices_loaded_from_sale_points();

      if (!empty($latest_invoices)) {
        $this->load->library('Date_Utilities');
  
        foreach ($latest_invoices as $last_invoice) {
          $last_invoice->fecha = ucfirst($this->date_utilities->format_date('%B %d, %Y', $last_invoice->fecha));
        }
  
        return $latest_invoices;
      }
    } catch (Exception $e) {
      $this->messages->add($e->getMessage(), 'danger');
      return FALSE;
    }
  }


  /**
   * Obtiene las facturas de una terminal de
   * un punto de venta en una fecha específica.
   * 
   * @param int $terminal Código de la terminal del punto
   * de venta de donde se obtendrán las facturas.
   * @param string $fecha Fecha para obtener las facturas.
   * 
   * @return mixed|boolean
   */
  public function get_invoices_from_sale_point_on_date($terminal, $fecha) {
    try {
      return $this->Facturas_wpos_mdl->get_invoices_from_sale_point_on_date($terminal, $fecha);
    } catch (InvalidArgumentException $e) {
      $this->messages->add('Argumento inválido: ' . $e->getMessage(), 'danger');
      return FALSE;
    } catch (Exception $e) {
      $this->messages->add('Error: ' . $e->getMessage(), 'danger');
      return FALSE;
    }
  }

  /**
   * Genera la estructura de una factura normal de WinPOS.
   * 
   * @param object $invoice Factura a la que se cambiará la estructura.
   * @param boolean $with_retefuente Añade un medio de pago auxiliar
   * a la nueva estructura.
   * 
   * @return object
   */
  private function generate_invoice_structure($invoice, $with_payment_method_aux = FALSE) {
    $invoice_structure = new stdClass();
    $invoice_structure->sw = $invoice->sw;
    $invoice_structure->anulada = $invoice->Anulado;
    $invoice_structure->documento = $invoice->documento;
    $invoice_structure->tipo_documento = $invoice->tipo;
    $invoice_structure->numero = $invoice->numero;
    $invoice_structure->nit = $invoice->IdentificacionAuxiliar;
    $invoice_structure->nit_digito = $invoice->Nit;
    $invoice_structure->cliente = $invoice->Nombre . ' ' . $invoice->Apellidos;
    $invoice_structure->medio_pago = $invoice->idMedioPago;
    $invoice_structure->modelo = $invoice->modelo;
    $invoice_structure->condicion = $invoice->condicion;
    $invoice_structure->concepto = $invoice->concepto;
    $invoice_structure->valor_mercancia = $invoice->valor_mercancia;
    $invoice_structure->valor_bruto = $invoice->ValorBruto;
    $invoice_structure->valor_aplicado = $invoice->valor_aplicado;
    $invoice_structure->valor_mp = $invoice->ValorMP;
    $invoice_structure->iva = $invoice->iva;
    $invoice_structure->descuento = $invoice->TotalDescuento;
    $invoice_structure->valor_total = $invoice->valor_total;
    $invoice_structure->fecha = $invoice->Fecha;
    $invoice_structure->vencimiento = $invoice->vencimiento;
    $invoice_structure->vendedor_wpos = $invoice->Vendedor;
    $invoice_structure->bodega = $invoice->bodega;
    $invoice_structure->duracion = $invoice->duracion;
    $invoice_structure->exportada = $invoice->Exportado;
    $invoice_structure->terminado = $invoice->Terminado;
    $invoice_structure->terminal = $invoice->idTerminal;
    $invoice_structure->notas = $invoice->notas;
    $invoice_structure->rete_fuente = 0;

    // En caso de que el medio de pago sea retención en la fuente (6)
    // se debe sobrescribir el valor de rete_fuente en la nueva estructura.
    if ($invoice_structure->medio_pago === 6) {
      $invoice_structure->rete_fuente = $invoice->rete_fuente;
    }

    // En caso de que la factura posea dos medios de pago, permite
    // añadir un medio de pago auxiliar del segundo medio de pago de la factura.
    if ($with_payment_method_aux === TRUE) {
      $invoice_structure->medio_pago_aux = $invoice->medio_pago_aux;
    }
    
    return $invoice_structure;
  }

  /**
   * Genera una estructura corta de una factura de WinPOS con más de
   * dos medios de pago.
   * 
   * @param int $number Número de la factura.
   * @param boolean $state Indica si la factura está anulada o no.
   * 
   * @return object
   */
  private function generate_manual_invoice_structure($number, $state){
    $manual_invoice = new stdClass();
    $manual_invoice->numero = $number;
    $manual_invoice->anulada = $state;
    $manual_invoice->is_manual_invoice_msg = lang('manual_invoice_error');
    $manual_invoice->is_manual_invoice_status = TRUE;

    return $manual_invoice;
  }

  /**
   * Procesa una factura de WinPOS con dos medios de pago.
   * 
   * Manipula la información de los dos medios de pago de una factura
   * y fusiona esos datos en un solo objeto.
   * 
   * @param object $first_pay_method Factura con el primer medio de pago.
   * @param object $second_pay_method Factura con el segundo medio de pago.
   * 
   * @return object
   */
  private function process_invoice_with_double_payment_method($first_pay_method, $second_pay_method) {
    $first_pay_method->modelo = $second_pay_method->modelo;
    $first_pay_method->valor_total = $second_pay_method->ValorMP;
    $first_pay_method->iva += $second_pay_method->iva;
    $first_pay_method->TotalDescuento += $second_pay_method->TotalDescuento;
    $first_pay_method->valor_aplicado = $second_pay_method->ValorMP;
    $first_pay_method->medio_pago_aux = $second_pay_method->idMedioPago;
    $first_pay_method->rete_fuente = 0;

    if ($first_pay_method->idMedioPago === 6) {
      $first_pay_method->rete_fuente = $first_pay_method->ValorMP;
    }

    return $first_pay_method;
  }

  /**
   * Define la cuenta de venta para una factura de WinPOS.
   * 
   * @param int $vendor Código de vendedor asociado a la factura.
   * @param int $customer_type Tipo de cliente asociado a la factura.
   * @param int $iva IVA de la factura.
   * 
   * @return int
   */
  public function set_sale_account_to_wpos_invoice($vendor, $customer_type, $iva) {
    switch ($vendor) {
      case 8: // Punto de venta Bogotá
        $sale_account = 41209540;
        break;
      case 16: // Punto de venta Itagui
        $sale_account = 41209530;
        break;
      case 38: // Punto de venta Cali
        $sale_account = 41209535;
        break;
      case 55: // Punto de venta Dosquebradas
        $sale_account = 41209550;
        break;
      default:
        if ($customer_type == 1)
          $sale_account = 41209505;
        if ($customer_type == 2 && $iva >= 1)
          $sale_account = 41209515;
        if ($customer_type == 2 && $iva == 0)
          $sale_account = 41209510;
        if ($customer_type == 4)
          $sale_account = 41209521;
        break;
    }

    return $sale_account;
  }

  /**
   * Define la cuenta por cobrar para una factura de WinPOS.
   *
   * @param int $payment_method Código de medio de pago asociado a la factura.
   * @param int $vendor Código de vendedor asociado a la factura.
   * @param int $customer_type Tipo de cliente asociado a la factura.
   * 
   * @return int
   */
  public function set_receivable_account_to_wpos_invoice($payment_method, $vendor, $customer_type) {
    if ($payment_method === 7) {
      switch ($vendor) {
        case 8: // Punto de venta Bogotá
          $receivable_account = 13050605;
          break;
        case 16: // Punto de venta Itagui
          $receivable_account = 13050601;
          break;	
        case 38: // Punto de venta Cali
          $receivable_account = 13050603;	
          break;
        default:
          if ($customer_type === '1') { // Cuentas por cobrar a RC o PN
            $receivable_account = 13050505;
          }

          if ($customer_type === '2') { // Cuentas por cobrar a CI
            $receivable_account = 13052005;
          }

          if ($customer_type === '4') { // Cuentas por cobrar a ZF
            $receivable_account = 13050505;
          }
          break;
      }
    } else {
      $receivable_account = 11050505;
    }

    return $receivable_account;
  }

  /**
   * Asigna un código de vendedor a una terminal de
   * punto de venta.
   * 
   * @param int $terminal Código de la terminal del punto
   * de venta.
   * 
   * @return int
   */
  public function set_vendor_code_to_terminal($terminal) {
    switch ($terminal) {
      case 620: // Punto de venta Cali
        $vendor = 38;
        break;
      case 630: // Punto de venta Itagui
        $vendor = 16;
        break;
      case 650: // Punto de venta Bogotá
        $vendor = 8;
        break;
    }

    return $vendor; 
  }

  /**
   * Busca una terminal de un punto de venta de WinPOS por medio de su código.
   * 
   * @param int $terminal Código de la terminal del punto
   * de venta.
   * 
   * @return boolean|string
   */
  public function find_terminal($terminal) {
    try {
      $this->load->model('Facturas/wpos/Terminales_wpos_model', 'Terminales_wpos_mdl');
      return $this->Terminales_wpos_mdl->find_terminal($terminal);
    } catch (InvalidArgumentException $e) {
      return 'Argumento inválido: ' . $e->getMessage();
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
   * Elimina todos los datos de la tabla de documentos_MAX
   * 
   * @return boolean
   */
  public function delete_all_data_from_documentos_max() {
    return $this->Facturas_dms_mdl->delete_all_data_from_documentos_max();
  }

  /**
   * Elimina todos los datos de la tabla de movimientos_MAX
   * 
   * @return boolean
   */
  public function delete_all_data_from_movimiento_max() {
    return $this->Facturas_dms_mdl->delete_all_data_from_movimiento_max();
  }

  /**
   * Reporta una factura anulada en DMS.
   * 
   * @param object $invoice Factura a ser reportada.
   * 
   * @return boolean|string
   */
  public function report_dms_voided_invoice($invoice) {
    try {
      return $this->Facturas_dms_mdl->add_voided_invoice($invoice);
    } catch (InvalidArgumentException $e) {
      return 'Argumento inválido: ' . $e->getMessage();
    } catch (TypeError $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
   * Reporta una factura correcta en DMS.
   * 
   * @param object $invoice Factura a ser reportada.
   *
   * @return boolean|string
   */
  public function report_dms_success_invoice($invoice) {
    try {
      return $this->Facturas_dms_mdl->add_success_invoice($invoice);
    } catch (InvalidArgumentException $e) {
      return 'Argumento inválido: ' . $e->getMessage();
    } catch (TypeError $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
   * Reporta la imputación contable de una factura.
   * 
   * @param object $invoice Factura a ser reportada.
   */
  public function report_accounting_imputation($invoice) {
    try {
      return $this->Facturas_dms_mdl->add_accounting_imputation($invoice);
    } catch (InvalidArgumentException $e) {
      return 'Argumento inválido: ' . $e->getMessage();
    } catch (TypeError $e) {
      return 'Error: ' . $e->getMessage();
    }
  }
}