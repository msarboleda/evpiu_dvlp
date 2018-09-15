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
}