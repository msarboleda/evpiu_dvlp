<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Clientes
 *
 * Descripción del controlador.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @author Martin Arboleda Montoya <maarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Clientes extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->model('Terceros/maxestrada/Customer_master_model', 'Clientes_max_mdl');
    $this->load->model('Terceros/estradav/Clientes_dms_model', 'Clientes_dms_mdl');
    $this->load->library(array('header', 'verification_roles', 'messages'));
    $this->load->helper(array('language', 'load', 'form'));
    $this->lang->load('clientes');
  }

  /**
   * Muestra todos los clientes según el vendedor actual.
   *
   * @return void
   */
  public function index() {
    $header_data = $this->header->show_Categories_and_Modules();
    $header_data['module_name'] = lang('index_heading');
    $user_id = $this->ion_auth->user()->row()->id;

    add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
    add_js('themes/elaadmin/js/lib/datatables/datatables.min.js');
    add_js('themes/elaadmin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');
    add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');

    // Se muestran los datos necesarios dependiendo del rol del usuario.
    switch ($user_id) {
      case $this->ion_auth->is_admin($user_id):
      case $this->verification_roles->is_vendor($user_id):
        $view_name = 'clientes/index';
        add_js('dist/custom/js/terceros/customers_index.js');
        break;
      default:
        redirect('auth');
        break;
    }

    $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
    $this->load->view('terceros'. DS . $view_name);
    $this->load->view('footers'. DS .'footer_main_dashboard');
  }

  /**
   * Busca el vendedor asignado de un cliente de DMS.
   *
   * @param int $nit NIT del Cliente.
   *
   * @return object
   */
  public function find_vendor_assigned_to_dms_customer($nit) {
    try {
      return $this->Clientes_dms_mdl->find_vendor_assigned_to_customer($nit);
    } catch (InvalidArgumentException $e) {
      return 'Argumento inválido: ' . $e->getMessage();
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
   * Verifica que un cliente está creado en DMS.
   *
   * @param int $nit NIT del Cliente.
   *
   * @return boolean
   */
  public function check_created_dms_client($nit) {
    try {
      return $this->Clientes_dms_mdl->check_created_client($nit);
    } catch (InvalidArgumentException $e) {
      return 'Argumento inválido: ' . $e->getMessage();
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
   * Busca el tipo de un cliente de DMS.
   *
   * @param int $nit NIT del Cliente.
   *
   * @return object
   */
  public function find_dms_customer_type($nit) {
    try {
      return $this->Clientes_dms_mdl->find_customer_type($nit);
    } catch (InvalidArgumentException $e) {
      return 'Argumento inválido: ' . $e->getMessage();
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
   * Interpreta el código de tipo de cliente y devuelve
   * su valor correspondiente.
   *
   * @param string $type Código del tipo de cliente.
   *
   * @return string|null
   */
  public function get_readable_customer_type(string $type) {
    switch($type) {
      case 'CI':
        $readable_type = 'COMERCIALIZADORA INTERNACIONAL';
        break;
      case 'EX':
        $readable_type = 'EXTERIOR';
        break;
      case 'PN':
        $readable_type = 'PERSONA NATURAL';
        break;
      case 'RC':
        $readable_type = 'RÉGIMEN COMÚN';
        break;
      case 'ZF':
        $readable_type = 'ZONA FRANCA';
        break;
      default:
        $readable_type = null;
        break;
    }

    return $readable_type;
  }

  /**
   * Interpreta el código del estado del cliente y devuelve
   * su valor correspondiente.
   *
   * @param string $status Código de estado del cliente.
   *
   * @return string|null
   */
  public function get_readable_customer_status(string $status) {
    if ($status === 'R') {
      $readable_status = 'LIBERADO';
    } else if ($status === 'H') {
      $readable_status = 'RETENIDO';
    } else {
      $readable_status = null;
    }

    return $readable_status;
  }
}
