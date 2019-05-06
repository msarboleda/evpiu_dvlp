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
    $this->form_validation->set_error_delimiters('<small class="color-danger">', '</small>');
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
   * Actualiza la información de un cliente
   *
   * @param string $customer_id Código del cliente.
   *
   * @return void
   */
  public function update_customer(string $customer_id) {
    $header_data = $this->header->show_Categories_and_Modules();
    $header_data['module_name'] = lang('update_heading');
    $user_id = $this->ion_auth->user()->row()->id;

    add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
    add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');

    // Se muestran los datos necesarios dependiendo del rol del usuario.
    switch ($user_id) {
      case $this->ion_auth->is_admin($user_id):
      case $this->verification_roles->is_vendor($user_id):
        try {
          $is_my_customer = $this->is_my_customer($customer_id, $this->ion_auth->user()->row()->Vendedor);

          if ($is_my_customer) {
            if ($this->input->post('update_action')) {
              if ($this->form_validation->run('clientes/update_customer')) {
                $customer_data = [
                  'EMAIL1_23' => $this->input->post('customer_email'),
                  'CNTCT_23' => $this->input->post('customer_contact'),
                  'PHONE_23' => $this->input->post('customer_phone'),
                  'TELEX_23' => $this->input->post('customer_mobile'),
                  'ADDR1_23' => $this->input->post('customer_address_one'),
                  'ADDR2_23' => $this->input->post('customer_address_two'),
                  'ModifiedBy' => $this->ion_auth->user()->row()->username,
                ];

                $updated = $this->Clientes_max_mdl->update_customer($customer_id, $customer_data);

                if ($updated) {
                  $customer = $this->Clientes_max_mdl->get_customer($customer_id);
                  $customer_state = trim($customer->STATE_23);
                  $customer_city = trim($customer->CITY_23);
                  // Ciudades excluidas de notificación de actualización de datos en Coordinadora
                  $excluded_cities = array('BELLO', 'MEDELLIN', 'ENVIGADO', 'ITAGUI', 'SABANETA', 'CALDAS', 'LA ESTRELLA');

                  // Envía notificación para actualizar información en Coordinadora, sí el cliente no pertenece a las ciudades excluidas o no es de Antioquia
                  if (($customer_state == 'ANTIOQUIA' && !in_array($customer_city, $excluded_cities)) || ($customer_state != 'ANTIOQUIA')) {
                    $this->send_customer_update_notification($customer);
                  }

                  $this->messages->add('Los datos del cliente han sido actualizados satisfactoriamente.', 'success');
                } else {
                  $this->messages->add('Ocurrió un error al actualizar los datos del cliente.', 'danger');
                }
              }
            }

            try {
              $customer = $this->find_customer($customer_id);

              // Almacena el valor textual del código de tipo de cliente
              $customer->CUSTYP_23 = $this->get_readable_customer_type($customer->CUSTYP_23);

              // Almacena el valor textual del código del estado del cliente
              $customer->R_STATUS_23 = $this->get_readable_customer_status($customer->STATUS_23);

              $view_data['customer'] = $customer;
              $view_data['customer_data_empty'] = false;
            } catch (Exception $e) {
              $this->messages->add($e->getMessage(), 'danger');
              $view_data['customer_data_empty'] = true;
            }
          } else {
            $this->messages->add('Este cliente no está asociado a tu código de vendedor, por lo tanto no puedes modificar su información.', 'danger');
            $view_data['customer_data_empty'] = true;
          }
        } catch (Exception $e) {
          $this->messages->add($e->getMessage(), 'danger');
          $view_data['customer_data_empty'] = true;
        }

        $view_data['messages'] = $this->messages->get();
        $view_name = 'clientes/update';
        break;
      default:
        redirect('auth');
        break;
    }

    $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
    $this->load->view('terceros'. DS . $view_name, $view_data);
    $this->load->view('footers'. DS .'footer_main_dashboard');
  }

  /**
   * Obtiene toda la información de un cliente y elimina
   * los espacios en blanco del inicio y final de cada
   * valor del objeto de cliente.
   *
   * @param string $customer_id Código del cliente.
   *
   * @return object
   */
  public function find_customer(string $customer_id) {
    try {
      $this->load->library('Object_Utilities');

      $db_customer = $this->Clientes_max_mdl->get_customer($customer_id);
      $customer = $this->object_utilities->trim_object_data($db_customer);

      return $customer;
    } catch (Exception $e) {
      throw $e;
    }
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

  /**
   * Verifica sí el cliente le pertenece a un vendedor.
   *
   * @param string $customer_id Código del cliente.
   * @param string $vendor_id Código del vendedor.
   *
   * @return boolean
   */
  public function is_my_customer(string $customer_id, string $vendor_id) {
    try {
      $customer_vendor = $this->Clientes_max_mdl->get_customer_vendor($customer_id);
      $real_customer_vendor_id = trim($customer_vendor->SLSREP_23);
    } catch (Exception $e) {
      throw $e;
    }

    if ($vendor_id == $real_customer_vendor_id) {
      return true;
    }

    return false;
  }

  /**
   * Envía una notificación de correo electrónico para informar que se
   * actualizó la información de un cliente a los usuarios de Facturación.
   *
   * @param array $customer Código del usuario que creó la orden de trabajo.
   *
   * @return boolean
   */
  public function send_customer_update_notification($customer) {
    $this->load->library('email');
    $this->load->model('Terceros/users/Usuarios_model', 'Usuarios_evpiu_mdl');

    $subject = '¡Se ha actualizado la información de un cliente!';
    $user_modifier = $this->Usuarios_evpiu_mdl->get_name_from_username($customer->ModifiedBy);
    $customer->MODIFIER_NAME = $user_modifier->first_name . ' ' . $user_modifier->last_name;

    $params = array(
      'charset' => strtolower(config_item('charset')),
      'subject' => $subject,
      'customer' => $customer
    );

    $body = $this->load->view('terceros/clientes/notify_customer_update', $params, TRUE);

    $result = $this->email->from('info@estradavelasquez.com', 'Notificaciones EVPIU')
        ->to('aealvarez@estradavelasquez.com, smsanchez@estradavelasquez.com')
        ->subject($subject)
        ->message($body)
        ->send();

    if ($result) {
      return $result;
    } else {
      throw new Exception(lang('cus_update_notif_not_sended'));
    }
  }
}
