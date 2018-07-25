<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @description Peticiones AJAX del módulo de Marcas
 *
 * Este controlador se utiliza para gestionar las peticiones AJAX
 * que se necesitan llamar desde JavaScript para el módulo de Marcas.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class XHR extends MX_Controller {
	public function __construct() {
		parent::__construct();
  }
  
	/**
	 * @description Obtiene todas las marcas de la plataforma.
	 *
	 * @return json
	 */
	public function xhr_get_all_Marks() {
    try {
      $this->load->model('EVPIU/Marcas_model', 'Marcas_mdl');
      $marks = $this->Marcas_mdl->get_All_Marks('FechaCreacion', 'desc');

      echo json_encode($marks);
    } catch (Exception $e) {
      $exception_data = new stdClass();
      $exception_data->exception = $e->getMessage();
      $exception_data->data = array();

      echo json_encode($exception_data);
    }
	}
}