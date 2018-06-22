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
	}

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

	/**
	 * Genera a las líneas de productos, adaptadas para que se muestren
	 * en un Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de consultar las líneas de productos y 
	 * organizarlas en un formato adaptado para el plugin 'Select2'.
	 *
	 * @return string Múltiples líneas de productos en etiquetas <option>.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function xhr_Lines_select() {
		if (isset($_POST['Parametro']) && !empty($_POST['Parametro'])) {
			$this->load->model('Requerimientos/EVPIU/V_LineasxParametro_model', 'LineasxParam_mdl');
			$Parametro = $this->input->post('Parametro');

			$lines_select_data = $this->LineasxParam_mdl->fill_Lineas_x_Parametro_select($Parametro);

			if (!empty($lines_select_data)) {
				foreach ($lines_select_data as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		}

		return FALSE; 	
	}

	/**
	 * Genera a las sublíneas de productos, adaptadas para que se muestren
	 * en un Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de consultar las sublíneas de productos y 
	 * organizarlas en un formato adaptado para el plugin 'Select2'.
	 *
	 * @return string Múltiples sublíneas de productos en etiquetas <option>.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function xhr_Sublines_select() {
		if (isset($_POST['Linea']) && !empty($_POST['Linea'])) {
			$Linea = $this->input->post('Linea');

			$sublineas_select_data = $this->Sublineas_mdl->fill_Sublineas_select($Linea);

			if (!empty($sublineas_select_data)) {
				foreach ($sublineas_select_data as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		}

		return FALSE;	
	}

	/**
	 * Genera a las características de productos, adaptadas para que se muestren
	 * en un Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de consultar las características de productos y 
	 * organizarlas en un formato adaptado para el plugin 'Select2'.
	 *
	 * @return string Múltiples características de productos en etiquetas <option>.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function xhr_Features_select() {
		if (isset($_POST['Linea']) && isset($_POST['Sublinea']) && !empty($_POST['Linea']) && !empty($_POST['Sublinea'])) {
			$Linea = $this->input->post('Linea');
			$Sublinea = $this->input->post('Sublinea');

			$caracteristicas_select_data = $this->Caracteristicas_mdl->fill_Caracteristicas_select($Linea, $Sublinea);

			if (!empty($caracteristicas_select_data)) {
				foreach ($caracteristicas_select_data as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		}

		return FALSE;
	}

	/**
	 * Genera a los materiales de productos, adaptados para que se muestren
	 * en un Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de consultar los materiales de productos y 
	 * organizarlos en un formato adaptado para el plugin 'Select2'.
	 *
	 * @return string Múltiples materiales de productos en etiquetas <option>.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function xhr_Materials_select() {
		if (isset($_POST['Linea']) && isset($_POST['Sublinea']) && !empty($_POST['Linea']) && !empty($_POST['Sublinea'])) {
			$this->load->model('Requerimientos/EVPIU/V_MaterialesxSublinea_model', 'vMaterialesxSublinea_mdl');
			$Linea = $this->input->post('Linea');
			$Sublinea = $this->input->post('Sublinea');

			$materiales_select_data = $this->vMaterialesxSublinea_mdl->fill_Materiales_x_Sublinea_select($Linea, $Sublinea);

			if (!empty($materiales_select_data)) {
				foreach ($materiales_select_data as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		}

		return FALSE;	
	}

	/**
	 * Genera a los tamaños de productos, adaptados para que se muestren
	 * en un Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de consultar los tamaños de productos y 
	 * organizarlos en un formato adaptado para el plugin 'Select2'.
	 *
	 * @return string Múltiples tamaños de productos en etiquetas <option>.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function xhr_Sizes_select() {
		if (isset($_POST['Linea']) && isset($_POST['Sublinea']) && isset($_POST['Material']) 
			&& !empty($_POST['Linea']) && !empty($_POST['Sublinea']) && !empty($_POST['Material'])) {
			$this->load->model('Requerimientos/EVPIU/V_Tamanos_model', 'vTamanos');
			$Linea = $this->input->post('Linea');
			$Sublinea = $this->input->post('Sublinea');
			$Material = $this->input->post('Material');

			$tams_select_data = $this->vTamanos->fill_Tamanos_select($Linea, $Sublinea, $Material);

			if (!empty($tams_select_data)) {
				foreach ($tams_select_data as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		}

		return FALSE; 
	}

	/**
	 * Genera a los espesores de productos, adaptados para que se muestren
	 * en un Select con el plugin 'Select2'.
	 *
	 * Este método se encarga de consultar los espesores de productos y 
	 * organizarlos en un formato adaptado para el plugin 'Select2'.
	 *
	 * @return string Múltiples espesores de productos en etiquetas <option>.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function xhr_Thicknesses_select() {
		if (isset($_POST['Material']) && !empty($_POST['Material'])) {
			$this->load->model('Requerimientos/EVPIU/Espesores_model', 'Espesores');
			$Material = $this->input->post('Material');

			$espesores_select_data = $this->Espesores->fill_Espesores_select($Material);

			if (!empty($espesores_select_data)) {
				foreach ($espesores_select_data as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		}

		return FALSE;
	}
}