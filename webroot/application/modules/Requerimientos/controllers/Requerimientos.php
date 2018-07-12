<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Requerimientos
 */
class Requerimientos extends MX_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->helper('language', 'load');
		$this->load->model('Auth/EVPIU/ModulosxCategoriasxGrupos_model');
		$this->load->model('Requerimientos/EVPIU/Requerimientos_model', 'Reqs_mdl');
		// Libreria para cargar datos en el Header
		$this->load->model('Requerimientos/EVPIU/Estados_model', 'Estados_mdl');
		$this->load->library('header');
		// Libreria para identificar roles del usuario actual
		$this->load->library('verification_roles');
		// Inicio y final del contenido de los errores de form_validation.
		$this->form_validation->set_error_delimiters('', '<br>');

		$this->lang->load('requerimientos');
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
			redirect('auth', 'refresh');
		}
	}

	/**
	 * Método para peticiones Ajax que consultan todos los requerimientos
	 *
	 * @return json_object
	 */
	public function ajax_get_all_Requerimientos() {
		// Es una consulta bastante amplia, por lo cual se aumenta el buffer para el driver sqlsrv
		ini_set('sqlsrv.ClientBufferMaxKBSize', '50240');

		$reqs = $this->Reqs_mdl->get_Requerimientos('desc');

		foreach ($reqs as $req) {
			// Se formatea cada fecha de creación a un formato en Español / Colombia.
			$req->FechaCreacion = ucfirst(strftime('%B %d, %Y', strtotime($req->FechaCreacion)));
		}

		echo json_encode($reqs);
	}

	/**
	 * Método para peticiones Ajax que consultan todos los requerimientos
	 * de un vendedor, filtrando por su código.
	 *
	 * @return json_object
	 */
	public function ajax_get_Requerimientos_by_vendor() {
		$vendor_id = $this->ion_auth->user()->row()->Vendedor;
		$reqs = $this->Reqs_mdl->get_Requerimientos_by_vendor($vendor_id, 'desc');

		foreach ($reqs as $req) {
			// Se formatea cada fecha de creación a un formato en Español / Colombia.
			$req->FechaCreacion = ucfirst(strftime('%B %d, %Y', strtotime($req->FechaCreacion)));
		}

		echo json_encode($reqs);
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
		}

		echo json_encode($reqs);
	}
}