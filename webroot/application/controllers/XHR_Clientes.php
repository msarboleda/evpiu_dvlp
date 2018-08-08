<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Peticiones Ajax para el Reporte de Clientes
 *
 * Este controlador se utiliza para gestionar las peticiones AJAX
 * que se necesitan llamar desde JavaScript para el Reporte de Clientes.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class XHR_Clientes extends MX_Controller {
  public function __construct() {
    parent::__construct();
  }
  
  /**
   * @description Obtiene los clientes del vendedor actual en la plataforma.
   *
   * @return json
   */
  public function xhr_getCustomersDataFromCurrentVendor() {
    try {
      $this->load->model('MAXEstrada/Customer_Master_model', 'Clientes_mdl');
      $customers = $this->Clientes_mdl->getCustomersDataFromCurrentVendor();

      echo json_encode($customers);
    } catch (Exception $e) {
      $exception_data = new stdClass();
      $exception_data->exception = $e->getMessage();
      $exception_data->data = array();

      echo json_encode($exception_data);
    }
  }
}