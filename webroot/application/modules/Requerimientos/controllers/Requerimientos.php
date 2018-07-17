<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Requerimientos
 */
class Requerimientos extends MX_Controller {
	// Estado: Por revisión
	public $per_review_state = 1;

	// Estado: Por corrección
	public $per_correction_state = 2;

	// Estado: Por plano
	public $per_plane_state = 3;

	// Estado: Asignado
	public $assigned_state = 4;

	// Estado: En proceso
	public $in_process_state = 5;

	// Estado: Cerrado
	public $closed_state = 6;

	// Estado: Anulado
	public $canceled_state = 7;

	// Estado: Pospuesto
	public $postponed_state = 8;

	// Tipo de archivo: Soporte de Requerimiento
	public $file_type_request_support = 1;

	public function __construct() {
		parent::__construct();

		$this->load->model('Auth/EVPIU/ModulosxCategoriasxGrupos_model');
		$this->load->model('Users_model', 'Users_mdl');
		$this->load->library(array('header', 'verification_roles'));
		$this->load->config('form_validation', TRUE);
		$this->load->helper(array('language', 'load', 'form'));
		$this->lang->load('requerimientos');
		$this->form_validation->set_error_delimiters('', '<br>');
	}

	/**
	 * Página principal de Requerimientos
	 * 
	 * Se encarga de mostrar la lista de requerimientos dependiendo
	 * del rol del usuario actual.
	 */
	public function index() {
		if ($this->verification_roles->is_vendor() || $this->verification_roles->is_designer() || $this->verification_roles->is_design_coord()) {
			$header_data = $this->header->show_Categories_and_Modules();
			$header_data['module_name'] = lang('index_heading');

			$this->load->model('Requerimientos/EVPIU/Estados_model', 'Estados_mdl');
			$view_data['status_reqs_select'] = $this->Estados_mdl->fill_EstadosRequerimientos_select();

			add_js('themes/elaadmin/js/lib/datatables/datatables.min.js');
			add_js('themes/elaadmin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');

			// Establecer un mensaje si hay un error de datos flash
			$view_data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$view_data['Estados'] = array(
				'name'  => 'status_filter',
				'id'    => 'status_filter',
			);

			if ($this->verification_roles->is_vendor()) { // El usuario actual es un vendedor
				add_js('dist/custom/js/requerimientos/index.js');

				$this->load->view('headers' . DS . 'header_main_dashboard', $header_data);
				$this->load->view('requerimientos' . DS . 'index', $view_data);
				$this->load->view('footers'. DS . 'footer_main_dashboard');
			} elseif ($this->verification_roles->is_design_coord() || $this->ion_auth->is_admin()) { // El usuario actual es un coordinador de diseño
				add_js('dist/custom/js/requerimientos/index_to_design_coord.js');

				$this->load->view('headers' . DS . 'header_main_dashboard', $header_data);
				$this->load->view('requerimientos' . DS . 'index_to_design_coord', $view_data);
				$this->load->view('footers'. DS . 'footer_main_dashboard');
			} elseif ($this->verification_roles->is_designer()) { // El usuario actual es un diseñador
				add_js('dist/custom/js/requerimientos/index_to_designer.js');

				$this->load->view('headers' . DS . 'header_main_dashboard', $header_data);
				$this->load->view('requerimientos' . DS . 'index_to_designer', $view_data);
				$this->load->view('footers'. DS . 'footer_main_dashboard');
			}
		}	else {
			redirect('auth');
		}
	}

	/**
	 * Página principal de Nuevo Requerimiento
	 *
	 * Este formulario permite crear un nuevo requerimiento a un vendedor.
	 */
	public function new_request() {
		if ($this->verification_roles->is_vendor() || $this->ion_auth->is_admin()) {
			$header_data = $this->header->show_Categories_and_Modules();
			$header_data['module_name'] = lang('new_request_heading');

			$rule = NULL;

			// En dado caso que el requerimiento se vaya a aplicar a un producto base
			// se debe adjuntar en las reglas de validación el requerimiento del campo
			// de producto base.
			if ($this->input->post('applied_art')) {
				$rule = 'new_request_with_applied_art_validation';
			} else {
				$rule = 'requerimientos/new_request';
			}
			
			if ($this->form_validation->run($rule) === TRUE) {
				$base_product_code = $this->store_Base_Product($this->input->post());

				if ($base_product_code) {
					$store_request = $this->store_Request($base_product_code, $this->input->post());

					if ($store_request) {
						if (isset($_FILES['supports']) && !empty($_FILES['supports']['name'][0])) {
							$uploaded_supports = $this->upload_Request_Supports($store_request, $_FILES['supports']);
						}

						$this->session->set_flashdata('message', sprintf(lang('NR_successfully_created_requirement'), $store_request));
						redirect('requerimientos');
					} else {
						$this->session->set_flashdata('message', lang('NR_wrongly_created_requirement'));
					}
				} else {
					$this->session->set_flashdata('message', lang('NR_wrongly_created_base_product'));
				}
			}
			
			$this->load->model('Requerimientos/MAXEstrada/Customer_Master_model', 'Clientes_mdl');
			$this->load->model('Requerimientos/EVPIU/Parametros_model', 'Parametros_mdl');

			$view_data['vendors_select']      = $this->Users_mdl->fill_Vendedores_select();
			$view_data['customers_select']    = $this->Clientes_mdl->fill_Clientes_from_Vendor_select($this->ion_auth->user()->row()->Vendedor);
			$view_data['params_select']       = $this->Parametros_mdl->fill_Parametros_select();

			// Establecer un mensaje si hay un error de datos flash
			$view_data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			add_css('themes/elaadmin/css/lib/select2/select2.min.css');
			add_css('dist/custom/css/requerimientos/new_request.css');
			add_css('dist/custom/css/requerimientos/file_upload.css');
			add_js('themes/elaadmin/js/lib/select2/select2.full.min.js');
			add_js('themes/elaadmin/js/lib/select2/i18n/es.js');
			add_js('dist/custom/js/requerimientos/file_upload.js');
			add_js('dist/custom/js/requerimientos/new_request.js');

			$this->load->view('headers'.DS.'header_main_dashboard', $header_data);
			$this->load->view('requerimientos'.DS.'new_request', $view_data);
			$this->load->view('footers'.DS.'footer_main_dashboard');
		}	else {
			redirect('auth');
		}
	}

	/**
	 * Genera la estructura del código y descripción de un producto base.
	 *
	 * @param array $data Código de línea, sublínea, característica, material y tamaño.
	 *
	 * @return array En caso de que se genere la estructura correctamente.
	 *		boolean En caso de que no se genere la estructura adecuadamente.
	 */
	public function generate_Product_Structure($data = array()) {
		if (!isset($data) || empty($data)) {
			return FALSE;
		}

		$this->load->model('EVPIU/ProductosBase_model', 'ProductosBase_mdl');

		return $this->ProductosBase_mdl->generate_Base_Product_Structure($data);
	}

	/**
	 * Almacena un producto base en la base de datos.
	 *
	 * @param array $base_product_data Código de línea, sublínea, característica, material y tamaño.
	 *
	 * @return array En caso de que se genere la estructura correctamente.
	 *		boolean En caso de que no se genere la estructura adecuadamente.
	 */
	public function store_Base_Product($base_product_data = array()) {
		if (is_array($base_product_data) && !empty($base_product_data)) {
			$product_structure = $this->generate_Product_Structure($base_product_data);

			if ($product_structure) {
				$this->load->model('EVPIU/ProductosBase_model', 'ProductosBase_mdl');

				if ($this->ProductosBase_mdl->duplicated_Base_Product($product_structure['product_code'])) {
					return $product_structure['product_code'];
				} else {
					$add_base_product = $this->ProductosBase_mdl->add_Base_Product($base_product_data, $product_structure);

					if ($add_base_product) {
						$this->load->library('base_product');

						$this->send_Base_Cost_Notification($product_structure);

						// El producto necesita plano
						if ($this->base_product->check_Flat_Requirement_from_Product($base_product_data['Material'])) {
							$this->send_Flat_Requirement_Notification($product_structure);
						}

						return $add_base_product;
					}
				}
			}
		}

		return FALSE;
	}

	/**
	 * Almacena un requerimiento en la base de datos.
	 *
	 * @param array $product_code Código del producto base.
	 * @param array $request_data Datos del requerimiento.
	 *
	 * @return int Número de requerimiento que se almacenó.
	 *    boolean FALSE Los datos enviados no tenían un formato correcto o estaban vacíos.
	 */
	public function store_Request($product_code, $request_data = array()) {
		if (!isset($product_code) || empty($product_code)) {
			return FALSE;
		}

		if (is_array($request_data) && !empty($request_data)) {
			$this->load->model('Requerimientos/EVPIU/Requerimientos_model', 'Reqs_mdl');
			$this->load->library('base_product');

			// El producto necesita plano
			if ($this->base_product->check_Flat_Requirement_from_Product($this->input->post('Material'))) {
				$state_to_save = $this->per_plane_state;
			} else { // El producto no necesita plano
				$state_to_save = $this->per_review_state;
			}

			$additional_data = array(
				'Primario' => $product_code,
				'Estado'   => $state_to_save,
			);

			$add_request = $this->Reqs_mdl->add_Request($request_data, $additional_data);

			if ($add_request) {
				return $add_request;
			}
		}
	
		return FALSE;
	}

	/**
	 * Envía una notificación de correo electrónico adaptada con información
	 * relacionada a un requerimiento.
	 *
	 * @param string $to Correo electrónico a quien se enviará la notificación.
	 * @param string $subject Asunto del correo electrónico.
	 * @param string $html_message Vista HTML con el contenido del mensaje.
	 * @param array $additional_data Datos adicionales para anexar al mensaje.
	 *
	 * @return boolean TRUE En caso de que el correo electrónico se envie correctamente.
	 *    FALSE En caso de que el correo electrónico no se haya enviado.
	 */
	public function send_Notification_Email_Request($to, $subject, $html_message, $additional_data = array()) {
		if (!isset($to) || empty($to) || !isset($subject) || empty($subject) 
			|| !isset($html_message) || empty($html_message)) {
			return FALSE;
		}

		$this->load->library('array_utilities');

		if (!$this->array_utilities->is_fully_loaded_array($additional_data)) {
			return FALSE;
		}

		$this->load->library('email');

		$vendor_id = $this->ion_auth->user()->row()->id;
		$vendor_name = $this->ion_auth->user($vendor_id)->row()->first_name.' '.$this->ion_auth->user($vendor_id)->row()->last_name;

		$message_data = array(
			'charset' => strtolower(config_item('charset')),
			'subject' => $subject,
			'user' => $vendor_name,
			'product_code' => $additional_data['product_code'],
			'product_description' => $additional_data['product_description'],
		);

		$body = $this->load->view('requerimientos'.DS.$html_message, $message_data, TRUE);

		$result = $this->email->from('info@estradavelasquez.com', 'Notificaciones EVPIU')
		    ->to($to)
		    ->subject($subject)
		    ->message($body)
		    ->send();

		return $result;
	}


	/**
	 * Envía una notificación de correo electrónico adaptado para
	 * comunicar el requerimiento de un costo base para un producto base.
	 *
	 * @param array $data Código y descripción del producto que necesita costo base.
	 */
	public function send_Base_Cost_Notification($data = array()) {
		$this->load->model('V_users_groups_model', 'V_users_groups_mdl');

		$get_Costs_Manager_email = $this->V_users_groups_mdl->return_Users_Email_from_Group('costs_manager');
		$costs_manager_email = $get_Costs_Manager_email->email;
		$base_cost_subject = 'Notificación de Costo Base';

		$this->send_Notification_Email_Request($costs_manager_email, $base_cost_subject, 'email_base_cost_alert', $data);
	}

	/**
	 * Envía una notificación de correo electrónico adaptado para
	 * comunicar el requerimiento de un plano.
	 *
	 * @param array $data Código y descripción del producto que necesita plano.
	 */
	public function send_Flat_Requirement_Notification($data = array()) {
		$this->load->model('V_users_groups_model', 'V_users_groups_mdl');

		$get_Flat_Manager_email = $this->V_users_groups_mdl->return_Users_Email_from_Group('flat_manager');
		$flat_manager_email = $get_Flat_Manager_email->email;
		$flat_subject = 'Notificación de Requerimiento de Plano';

		$this->send_Notification_Email_Request($flat_manager_email, $flat_subject, 'email_flat_requirement_alert', $data);
	}

	/**
	 * Almacena los soportes de un requerimiento al servidor en una carpeta específica
	 * de cada requerimiento y con sus nombres de archivos encriptados.
	 *
	 * @param $request_id int Número del requerimiento que se vincula a los soportes.
	 * @param $request_supports $_FILES Soportes de requerimiento para almacenar.
	 *
	 * @return bool TRUE or FALSE En caso de un error.
	 */
	public function upload_Request_Supports($request_id, $request_supports = array()) {
		$assets_path = $this->config->item('physical_assets_path');
		$request_supports_path = $assets_path.'uploads/Requerimientos/Soportes/'.$request_id;

		if (!file_exists($request_supports_path)) {
			mkdir($request_supports_path, 0755);
		}

		$config['upload_path']        = $request_supports_path;
		$config['allowed_types']      = 'jpg|jpeg|png|pdf';
		$config['file_ext_tolower']   = TRUE;
		$config['max_size']           = '5120';
		$config['multi']              = 'ignore'; // Ignora los archivos que obtengan error y sigue subiendo
		$config['encrypt_name']       = TRUE;

		$this->load->library('upload', $config);

		$do_upload = $this->upload->do_upload('supports');

	 	if ($do_upload) {
			$uploaded_supports = $this->upload->data();
			$db_stored_request_supports = $this->save_Stored_Request_Supports($request_id, $uploaded_supports);

			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Método para reportar en la base de datos todos los soportes de requerimiento
	 * que se almacenaron en el servidor y se vincularon a un requerimiento.
	 *
	 * @param $request_id int Número del requerimiento que se vincula a los soportes.
	 * @param $uploaded_supports array Datos de los soportes subidos del requerimiento.
	 */
	public function save_Stored_Request_Supports($request_id, $uploaded_supports) {
		$this->load->model('Requerimientos/EVPIU/Archivos_model', 'ArchivosReq_mdl');
		$this->load->library('array_utilities');

		// En caso de que solo un soporte haya sido subido, el arreglo de los soportes
		// subidos se debe convertir a multidimensional para que el 'foreach' de 
		// abajo pueda tomar su nombre de archivo.
		if (!$this->array_utilities->is_multidimensional_array($uploaded_supports)) {
			$old_uploaded_supports = $uploaded_supports;
			$uploaded_supports = array();
			$uploaded_supports[] = $old_uploaded_supports;
		}

		foreach ($uploaded_supports as $uploaded_support) {
			$uploaded_support_data = array(
				'idTipoArchivo' => $this->file_type_request_support,
				'NomArchivo' => $uploaded_support['file_name'],
				'NroRequerimiento' => $request_id,
				'Usuario' => $this->ion_auth->user()->row()->username,
				'FechaCreacion' => date('Y-m-d H:i:s'),
			); 
	    
			$this->ArchivosReq_mdl->add_File($this->file_type_request_support, $uploaded_support_data);
		}
	}
}