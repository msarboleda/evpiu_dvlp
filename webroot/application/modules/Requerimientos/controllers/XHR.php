<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador: Requerimientos_XHR
 *
 * Este controlador se utiliza para gestionar las peticiones Ajax
 * que se necesitan en el módulo de Requerimientos.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class XHR extends MX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('Requerimientos/MAXEstrada/Customer_Master_model', 'Clientes_mdl');

	/**
	 * Genera a los clientes de un vendedor, adaptados para que se muestren
	 * en un Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de consultar los clientes de un vendedor y 
	 * organizarlos en un formato adaptado para el plugin 'Select2'.
	 *
	 * @return string Múltiples clientes en etiquetas <option>.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function xhr_Customers_from_Vendor_select() {
		if (isset($_POST['Vendedor']) && !empty($_POST['Vendedor'])) {
			$Vendedor = $this->input->post('Vendedor');

			$customers_select_data = $this->Clientes_mdl->fill_Clientes_from_Vendor_select($Vendedor);
			
			if (!empty($customers_select_data)) {
				foreach ($customers_select_data as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		}
		return FALSE;
	}

	/**
	 * Genera a los clientes del vendedor actual, adaptados para que se muestren
	 * en un Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de consultar los clientes del vendedor actual y 
	 * organizarlos en un formato adaptado para el plugin 'Select2'.
	 *
	 * @return string Múltiples clientes en etiquetas <option>.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function xhr_Customers_from_Current_Vendor_select() {
		$vendor_code = $this->ion_auth->user()->row()->Vendedor;

		$customers_select_data = $this->Clientes_mdl->fill_Clientes_from_Vendor_select($vendor_code);
		
		if (!empty($customers_select_data)) {
			foreach ($customers_select_data as $key => $value) {
				echo '<option value="'.$key.'">'.$value.'</option>';
			}
		}

		return FALSE;
	}
}