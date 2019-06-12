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
   * Muestra las facturas de la terminal de un punto de venta
   * en una fecha específica.
   *
   */
  public function show_invoices_from_sale_point_on_date() {
    if ($this->verification_roles->is_invoice_import_manager() || $this->ion_auth->is_admin()) {
      $terminal = $this->input->get('t');
      $fecha = $this->input->get('d');

      if (!empty($terminal) && !empty($fecha)) {
        $header_data = $this->header->show_Categories_and_Modules();
        $header_data['module_name'] = lang('SISP_heading');
        $orig_invoices = $this->get_invoices_from_sale_point_on_date($terminal, $fecha);

        if (!empty($orig_invoices)) {
          // Número de veces que se repite cada factura, en teoría cuenta los medios de
          // pago de una factura.
          $repeated_invoices = array_count_values(array_column($orig_invoices, 'numero'));

          foreach ($orig_invoices as $curr_key => $orig_invoice) {
            // Cantidad de medios de pago de la factura actual
            $repeated_times = $repeated_invoices[$orig_invoice->numero];

            switch ($repeated_times) {
              // Procesando las facturas manuales (Facturas con más de dos medios de pago)
              case $repeated_times > 2:
                if ($curr_key > 0) {
                  // En caso de que el número de factura actual sea igual al anterior,
                  // simplemente se ignora y continua con la próxima iteración.
                  if ($orig_invoice->numero === $orig_invoices[$curr_key-1]->numero) {
                    continue 2;
                  }
                }

                // Se genera la estructura para mostrar el error de factura manual,
                // se añade a las facturas procesadas para mostrarse en la vista
                // y se continua con la próxima iteración.
                $processed_invoice = $this->generate_manual_invoice_structure($orig_invoice->numero, $orig_invoice->Anulado);
                $processed_invoices[] = $processed_invoice;
                continue 2;
                break;
              // Procesando las facturas con dos medios de pago
              case $repeated_times === 2:
                if ($curr_key > 0) {
                  if ($orig_invoice->numero === $orig_invoices[$curr_key-1]->numero) {
                    continue 2;
                  }
                }

                // Se envía la información de la factura actual y la próxima, ya que su
                // número de factura es igual. Luego se genera una fusión para formar la
                // factura en un solo objeto.
                $orig_invoice = $this->process_invoice_with_double_payment_method($orig_invoice, $orig_invoices[$curr_key+1]);
                $inv_structure = $this->generate_invoice_structure($orig_invoice, TRUE);
                break;
              // Procesando las facturas correctas
              default:
                $inv_structure = $this->generate_invoice_structure($orig_invoice);
                break;
            }

            // La factura debe tener un NIT de cliente asociado
            if (isset($inv_structure->nit) && !empty($inv_structure->nit)) {
              $customer_created_on_dms = modules::run('terceros/clientes/check_created_dms_client', $inv_structure->nit);

              // El cliente debe estar creado en DMS para importar la factura
              if ($customer_created_on_dms === TRUE) {
                // Se consulta el vendedor asignado a un cliente
                $assigned_vendor_data = modules::run('terceros/clientes/find_vendor_assigned_to_dms_customer', $inv_structure->nit);
                $inv_structure->codigo_vendedor_dms = $assigned_vendor_data->vendedor;
                $inv_structure->nombre_vendedor_dms = $assigned_vendor_data->NombreVendedor;

                // En caso de que el vendedor de la factura pertenezca a un punto de venta, se respeta
                // la venta al punto de venta que realizó la transacción.
                $sale_points_vendors = array(3, 8, 16, 38);

                if (in_array($inv_structure->codigo_vendedor_dms, $sale_points_vendors)) {
                  $inv_structure->codigo_vendedor_dms = $this->set_vendor_code_to_terminal($terminal);
                  $dms_vendor_data = modules::run('terceros/vendedores/find_dms_vendor', $inv_structure->codigo_vendedor_dms);
                  $inv_structure->nombre_vendedor_dms = $dms_vendor_data->nombres;
                }

                if ($inv_structure->anulada) {
                  // Ya que es una factura anulada, simplemente se almacena con la
                  // mayoría de valores nulos y se envía la notificación del proceso.
                  $reported_invoice = $this->report_dms_voided_invoice($inv_structure);

                  if ($reported_invoice === TRUE) {
                    $inv_structure->void_invoice_msg = lang('void_invoice_successfully_reported');
                    $inv_structure->void_invoice_status = TRUE;
                  } else {
                    $inv_structure->void_invoice_msg = lang('void_invoice_no_reported') . ' ' . $reported_invoice;
                    $inv_structure->void_invoice_status = FALSE;
                  }
                } else {
                  // Se consulta el tipo de cliente de la factura actual
                  $customer_type = modules::run('terceros/clientes/find_dms_customer_type', $inv_structure->nit)->TipoCliente;
                  // Define la cuenta de venta de la factura
                  $inv_structure->cuenta_venta = $this->set_sale_account_to_wpos_invoice($inv_structure->codigo_vendedor_dms, $customer_type, $inv_structure->iva);

                  // En caso de que la factura tenga doble medio de pago se debe enviar
                  // un medio de pago auxiliar para poder definir la cuenta por cobrar
                  // de la factura.
                  if ($repeated_times === 2) {
                    $inv_structure->cuenta_cobrar = $this->set_receivable_account_to_wpos_invoice($inv_structure->medio_pago_aux, $inv_structure->codigo_vendedor_dms, $customer_type);
                  } else {
                    $inv_structure->cuenta_cobrar = $this->set_receivable_account_to_wpos_invoice($inv_structure->medio_pago, $inv_structure->codigo_vendedor_dms, $customer_type);
                  }

                  // Se reporta la factura correcta en la base de datos.
                  $reported_success_invoice = $this->report_dms_success_invoice($inv_structure);
                  // Se reporta la imputación contable de la factura en la base de datos.
                  $reported_accounting_imputation = $this->report_accounting_imputation($inv_structure);

                  // Evalúa el resultado de la inserción de la factura en la base de datos
                  // para enviar una notificación al usuario.
                  if ($reported_success_invoice === TRUE) {
                    $inv_structure->success_invoice_msg = lang('normal_invoice_successfully_reported');
                    $inv_structure->success_invoice_status = TRUE;
                  } else {
                    $inv_structure->success_invoice_msg = lang('normal_invoice_no_reported') . ' '. $reported_success_invoice;
                    $inv_structure->success_invoice_status = FALSE;
                  }

                  // Evalúa el resultado de la inserción de la imputación contable de
                  // la factura en la base de datos para enviar una notificación al usuario.
                  if ($reported_accounting_imputation === TRUE) {
                    $inv_structure->acc_imputation_msg = lang('accounting_imputation_successfully_reported');
                    $inv_structure->acc_imputation_status = TRUE;
                  } else {
                    $inv_structure->acc_imputation_msg = lang('accounting_imputation_no_reported'). ' ' . $reported_accounting_imputation;
                    $inv_structure->acc_imputation_status = FALSE;
                  }
                }

                // Se anexa como una factura procesada lista para mostrar.
                $processed_invoices[] = $inv_structure;
              } else { // El cliente no está creado en DMS
                // Se debe cancelar el proceso ya que el cliente tiene que estar
                // creado en DMS. Además se eliminan los datos ingresados de las
                // facturas anteriores en la base de datos para volver a comenzar
                // con el proceso.
                $this->delete_all_data_from_import_tables();
                $inv_structure->customer_not_created_on_dms_msg = lang('customer_not_created_on_dms') . ' ' . $customer_created_on_dms;
                $processed_invoices[] = $inv_structure;
                break;
              }
            } else { // El cliente tiene un problema con el NIT en WinPOS.
              // Se debe cancelar el proceso ya que el cliente tiene que poseer
              // un NIT correcto. Además se eliminan los datos ingresados de las
              // facturas anteriores en la base de datos para volver a comenzar
              // con el proceso.
              // NOTA: Generalmente pasa porque la identificación auxiliar del
              // cliente en WinPOS tiene el digito de verificación.
              $this->delete_all_data_from_import_tables();
              $inv_structure->nit_error = lang('customer_nit_does_not_exist_on_winpos') . ' ' . $inv_structure->cliente . ' (' . $inv_structure->nit . ')';
              $processed_invoices[] = $inv_structure;
              break;
            }
          }

          $view_data['invoices'] = $processed_invoices;
          $view_data['count_invoices'] = count($processed_invoices);
        }

        $view_data['terminal_data'] = $this->find_terminal($terminal);
        $view_data['messages'] = $this->messages->get();

        add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
        add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');
        add_js('dist/custom/js/facturas/show_invoices_from_sale_point.js');

        $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
        $this->load->view('facturas'. DS .'show_invoices_from_sale_point', $view_data);
        $this->load->view('footers'. DS .'footer_main_dashboard');
      } else {
        redirect('facturas/importacion_facturas/import_invoices_from_winpos_to_dms');
      }
    } else {
      redirect('auth');
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
    $first_pay_method->valor_total = $second_pay_method->valor_total;
    $first_pay_method->iva = $second_pay_method->iva;
    $first_pay_method->TotalDescuento = $second_pay_method->TotalDescuento;
    $first_pay_method->valor_aplicado = $second_pay_method->valor_aplicado;
    $first_pay_method->medio_pago_aux = $second_pay_method->idMedioPago;
    $first_pay_method->rete_fuente = 0;

    if ($first_pay_method->idMedioPago === 6) {
      $first_pay_method->valor_total -= $first_pay_method->ValorMP;
      $first_pay_method->valor_aplicado -= $first_pay_method->ValorMP;
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
      case 660: // Punto de venta Dosquebradas
        $vendor = 55;
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
   * Elimina todos los datos de las tablas de importación de facturas
   *
   * @return boolean
   */
  public function delete_all_data_from_import_tables() {
    try {
      return $this->Facturas_dms_mdl->delete_all_data_from_import_tables();
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
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
