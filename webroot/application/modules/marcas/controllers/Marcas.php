<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Marcas
 *
 * Esta clase posee funciones útiles para las marcas de los productos
 * de la compañía.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Marcas extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->library(array('header', 'verification_roles'));
    $this->load->helper(array('language', 'load', 'form'));
    $this->lang->load('marcas');
  }

  /**
   * Página principal de Marcas
   */
  public function index() {
    if ($this->verification_roles->is_vendor() || $this->ion_auth->is_admin()) {
      $this->add_Index_libraries();

      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('index_heading');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('marcas'. DS .'index');
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }

  /**
   * Funcionalidad para crear una nueva marca
   */
  public function new_mark() {
    if ($this->verification_roles->is_vendor() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('NM_heading');

      $this->load->config('form_validation', TRUE);
      $this->form_validation->set_error_delimiters('', '<br>');
      $this->load->library('messages');

      if ($this->form_validation->run('marcas/new_mark') === TRUE) {
        $messages = $this->store_Mark($this->input->post());
      }

      if (validation_errors()) {
        $view_data['validation_errors'] = validation_errors();
      } else if (isset($messages)) {
        $view_data['messages'] = $messages;
      } else {
        $view_data = null;
      }
      
      add_css('dist/custom/css/marcas/new_mark.css');
      add_js('dist/custom/js/marcas/new_mark.js');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('marcas'. DS .'new_mark', $view_data);
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }

  /**
   * Almacena una marca en la base de datos.
   * 
   * @param array $data Datos de una marca para almacenar.
   * 
   * @return array Mensaje de resultado.
   * @return bool False En caso de que los datos tengan un
   * formato incorrecto o estén vacíos.
   */
  public function store_Mark($data = array()) {
    if (is_array($data) && !empty($data)) {
      $this->load->model('evpiu/Marcas_model', 'Marcas_mdl');

      if ($this->Marcas_mdl->duplicated_Mark_description($data['Nombre'])) {
        $this->messages->add("Esta marca ya existe.", "warning");
      } else {
        $insert_mark_code = $this->Marcas_mdl->add_Mark($data);

        if ($insert_mark_code) {
          $this->messages->add("La marca se ha creado correctamente.", "success");
        } else {
          $this->messages->add("Ocurrió un error almacenando la marca.", "danger");
        } 
      }

      return $this->messages->get();
    }

    return FALSE;
  }

  /**
   * @description Añade las librerias CSS y JS principales del
   * método Index.
   */
  public function add_Index_libraries() {
    $this->lang->load('index');

    add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
    add_js('themes/elaadmin/js/lib/datatables/datatables.min.js');
    add_js('themes/elaadmin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');
    add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');
    add_js('dist/custom/js/marcas/index.js');
  }
}