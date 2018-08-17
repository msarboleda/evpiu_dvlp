<?php defined('BASEPATH') OR exit('No se permite el acceso directamente a este script');

/**
 * @description Peticiones AJAX del módulo de Pedidos
 *
 * Este controlador se utiliza para gestionar las peticiones AJAX que se invocan desde JS para el módulo de Pedidos.
 * 
 * @author Martin Abraham Arboleda <sistemas@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class XHR extends MX_Controller {
  public function __construct() {
    parent::__construct();
  }
  
	/**
	 * @description Obtiene todos los pedidos creados.
	 *
	 * @return json
	 */
  public function xhr_get_all_Pedidos() {
    try {
      $this->load->model('EVPIU/Pedidos_model', 'Pedidos_mdl');
      $pedidos = $this->Pedidos_mdl->get_All_Pedidos();
   
      if (!empty($pedidos)) {
        $this->load->library('Object_Utilities');
        $this->load->library('Date_Utilities');
     
        foreach ($pedidos as $pedido) {
          // Se formatea cada fecha de creación a un formato en Español / Colombia.
          $pedido->FechaPedido = ucfirst($this->date_utilities->format_date('%B %d, %Y', $pedido->FechaPedido));
          $pedido = $this->object_utilities->trim_object_data($pedido);
        }

        echo json_encode($pedidos);
      }
    } 
    catch (Exception $e) {
      $exception_data = new stdClass();
      $exception_data->exception = $e->getMessage();
      $exception_data->data = array();

      echo json_encode($exception_data);
    }
  }
}