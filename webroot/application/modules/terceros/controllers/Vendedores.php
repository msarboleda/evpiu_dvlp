<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Vendedores
 *
 * Descripción del controlador.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @author Martin Arboleda Montoya <maarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Vendedores extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Terceros/estradav/Vendedores_dms_model', 'Vendedores_dms_mdl');
  }

  /**
   * Busca un vendedor en DMS por medio de su código.
   * 
   * @param int $vendor_code Código del vendedor.
   * 
   * @return object
   */
  public function find_dms_vendor($vendor_code) {
    try {
      return $this->Vendedores_dms_mdl->find_vendor($vendor_code);
    } catch (InvalidArgumentException $e) {
      return 'Argumento inválido: ' . $e->getMessage();
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }
}