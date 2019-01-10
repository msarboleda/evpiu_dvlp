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

    $this->load->model('Terceros/estradav/Clientes_dms_model', 'Clientes_dms_mdl');
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
}
