<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase de Activos
 *
 * Descripción de la clase
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Activos extends MX_Controller {
  public function __construct() {
    parent::__construct();

    $this->load->model('Auth/evpiu/Modulosxcategoriasxgrupos_model');
    $this->load->model('Mantenimiento/evpiu/Activos_model', 'Activos_mdl');
    $this->load->library(array('header', 'verification_roles', 'messages'));
    $this->load->helper(array('language', 'load', 'form'));
    $this->lang->load('activos');
    $this->load->config('form_validation', TRUE);
    $this->form_validation->set_error_delimiters('', '<br>');
  }

  /**
   * Página principal de Activos
   */
  public function index() {
    if ($this->verification_roles->is_assets_viewer() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('index_heading');

      add_css('themes/elaadmin/css/lib/sweetalert2/sweetalert2.min.css');
      add_js('themes/elaadmin/js/lib/datatables/datatables.min.js');
      add_js('themes/elaadmin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');
      add_js('themes/elaadmin/js/lib/sweetalert2/sweetalert2.min.js');
      add_js('dist/custom/js/mantenimiento/index.js');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('mantenimiento'. DS .'index');
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }

  /**
   * Petición AJAX para obtener todos los activos existentes.
   *
   * @return string JSON
   */
  public function xhr_get_all_assets() {
    try {
      $assets = $this->Activos_mdl->get_all_assets();
      echo json_encode($assets);
    } catch (Exception $e) {
      $exception_data = new stdClass();
      $exception_data->exception = $e->getMessage();
      $exception_data->data = array();
      echo json_encode($exception_data);
    }
  }

  /**
   * Visualizar un activo.
   */
  public function view_asset() {
    if ($this->verification_roles->is_assets_viewer() || $this->ion_auth->is_admin()) {
      $asset_code = $this->input->get('ac');

      if (!empty($asset_code)) {
        $header_data = $this->header->show_Categories_and_Modules();
        $header_data['module_name'] = lang('view_asset_heading');

        $this->load->library('Date_Utilities');

        $asset_data = $this->Activos_mdl->get_asset($asset_code);
        $asset_data->UltimaRevision = ucfirst($this->date_utilities->format_date('%B %d, %Y', $asset_data->UltimaRevision));

        try {
          $asset_files = $this->Activos_mdl->get_asset_files($asset_code);
        } catch (Exception $e) {
          $asset_files = $e->getMessage();
        }

        $view_data['asset'] = $asset_data;
        $view_data['files'] = $asset_files;

        add_css('dist/vendor/lightbox2/css/lightbox.min.css');
        add_js('dist/vendor/lightbox2/js/lightbox.min.js');

        $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
        $this->load->view('mantenimiento'. DS .'view_asset', $view_data);
        $this->load->view('footers'. DS .'footer_main_dashboard');
      }
    } else {
      redirect('auth');
    }
  }

  /**
   * Edita la información de un activo.
   *
   * @param $asset_code Código del activo.
   */
  public function edit_asset($asset_code) {
    if ($this->verification_roles->is_assets_manager() || $this->ion_auth->is_admin()) {
      if (isset($asset_code)) {
        if (!empty($asset_code)) {
          $header_data = $this->header->show_Categories_and_Modules();
          $header_data['module_name'] = lang('edit_asset_heading');

          $this->load->library('Date_Utilities');

          $asset_data = $this->Activos_mdl->get_asset($asset_code);
          $asset_data->formatted_ult_revision = ucfirst($this->date_utilities->format_date('%B %d, %Y', $asset_data->UltimaRevision));

          try {
            $asset_files = $this->Activos_mdl->get_asset_files($asset_code);
          } catch (Exception $e) {
            $asset_files = $e->getMessage();
          }

          if ($this->form_validation->run('activos/edit_asset') === TRUE) {
            $updated = $this->update_asset($asset_code, $this->input->post());

            if ($updated) {
              if (isset($_FILES['attach'])) {
                if (!empty($_FILES['attach']['name'][0])) {
                  $this->attach_asset_documents($asset_code);
                }
              }

              header('Refresh:0');
              exit;
            }
          }

          $view_data['asset'] = $asset_data;
          $view_data['files'] = $asset_files;
          $view_data['classifications'] = modules::run('mantenimiento/clasificaciones/populate_classifications');
          $view_data['responsibles'] = modules::run('terceros/usuarios/populate_users');
          $view_data['states'] = modules::run('mantenimiento/estados_activos/populate_assets_states');
          $view_data['plants'] = modules::run('mantenimiento/plantas/populate_plants');
          $view_data['priorities'] = modules::run('mantenimiento/prioridades/populate_priorities');
          $view_data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

          add_css('themes/elaadmin/css/lib/select2/select2.min.css');
          add_css('dist/vendor/lightbox2/css/lightbox.min.css');
          add_css('dist/custom/css/file_upload.css');
          add_js('themes/elaadmin/js/lib/select2/select2.full.min.js');
          add_js('themes/elaadmin/js/lib/select2/i18n/es.js');
          add_js('dist/vendor/lightbox2/js/lightbox.min.js');
          add_js('dist/custom/js/file_upload.js');
          add_js('dist/custom/js/mantenimiento/edit_asset.js');

          $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
          $this->load->view('mantenimiento'. DS .'edit_asset', $view_data);
          $this->load->view('footers'. DS .'footer_main_dashboard');
        }
      }
    } else {
      redirect('auth');
    }
  }

  /**
   * Actualiza los datos de un activo específico.
   *
   * @param string $asset_code Código del activo.
   * @param array $data Nuevos datos para el activo.
   *
   * @return boolean
   */
  public function update_asset($asset_code, $data) {
    return $this->Activos_mdl->update_asset($asset_code, $data);
  }

  /**
   * Anexa documentos a un activo.
   *
   * Los documentos de cada activo se almacenan en una carpeta específica
   * de cada activo y con sus nombres de archivos encriptados.
   *
   * @param string $asset_code Código del activo.
   *
   * @return boolean
   */
  public function attach_asset_documents($asset_code) {
    $assets_path = $this->config->item('physical_assets_path');
    $restrict_file_path = $assets_path.'index.html';
    $assets_documents_path = $assets_path.'uploads/Mantenimiento/Anexos/'.strtoupper($asset_code);

    if (!file_exists($assets_documents_path)) {
      mkdir($assets_documents_path, 0755);
      copy($restrict_file_path, $assets_documents_path.'/index.html');
    }

    $config['upload_path'] = $assets_documents_path;
    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
    $config['file_ext_tolower'] = TRUE;
    $config['max_size'] = '5120';
    $config['multi'] = 'ignore'; // Ignora los archivos que obtengan error y sigue subiendo
    $config['encrypt_name'] = TRUE;

    $this->load->library('upload', $config);
    $do_upload = $this->upload->do_upload('attach');

    if ($do_upload) {
      $uploaded_documents = $this->upload->data();
      $db_stored_asset_documents = $this->save_stored_asset_documents($asset_code, $uploaded_documents);
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * Reporta en la base de datos todos los documentos anexados a un activo.
   *
   * @param string $asset_code int Número del requerimiento que se vincula a los soportes.
   * @param array $uploaded_documents array Detalles de los archivos anexados.
   *
   * @return void
   */
  public function save_stored_asset_documents($asset_code, $uploaded_documents) {
    $this->load->model('Mantenimiento/evpiu/Activos_archivos_model', 'ActArchivos_mdl');
    $this->load->library('array_utilities');

    // En caso de que solo un documento se haya subido, el arreglo de los documentos
    // subidos se debe convertir a multidimensional para que el ciclo pueda obtener
    // el nombre de archivo del documento.
    if (!$this->array_utilities->is_multidimensional_array($uploaded_documents)) {
      $old_uploaded_documents = $uploaded_documents;
      $uploaded_documents = array();
      $uploaded_documents[] = $old_uploaded_documents;
    }

    foreach ($uploaded_documents as $uploaded_document) {
      $uploaded_document_data = array(
        'idTipoArchivo' => $this->ActArchivos_mdl->file_type_asset_document,
        'NomArchivo' => $uploaded_document['file_name'],
        'CodActivo' => strtoupper($asset_code),
        'Usuario' => $this->ion_auth->user()->row()->username,
        'Extension' => $uploaded_document['file_ext'],
        'FechaCreacion' => date('Y-m-d H:i:s'),
      );

      $this->ActArchivos_mdl->add_document($uploaded_document_data);
    }
  }

  /**
   * Añade un activo a la base de datos.
   *
   */
  public function add_asset() {
    if ($this->verification_roles->is_assets_manager() || $this->ion_auth->is_admin()) {
      $header_data = $this->header->show_Categories_and_Modules();
      $header_data['module_name'] = lang('add_asset_heading');

      if ($this->form_validation->run('activos/add_asset') === TRUE) {
        try {
          $added = $this->save_asset($this->input->post());

          if ($added === TRUE) {
            if (isset($_FILES['attach'])) {
              if (!empty($_FILES['attach']['name'][0])) {
                $asset_code = $this->input->post('cod_activo');
                $this->attach_asset_documents($asset_code);
              }
            }

            redirect('mantenimiento/activos');
          } else {
            $this->messages->add(lang('asset_not_added_to_db'), 'danger');
          }
        } catch (Exception $e) {
          $this->messages->add($e->getMessage(), 'danger');
        }
      }

      $view_data['classifications'] = modules::run('mantenimiento/clasificaciones/populate_classifications');
      $view_data['responsibles'] = modules::run('terceros/usuarios/populate_users');
      $view_data['states'] = modules::run('mantenimiento/estados_activos/populate_assets_states');
      $view_data['plants'] = modules::run('mantenimiento/plantas/populate_plants');
      $view_data['priorities'] = modules::run('mantenimiento/prioridades/populate_priorities');
      $view_data['valid_errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
      $view_data['app_errors'] = $this->messages->get();

      add_css('themes/elaadmin/css/lib/select2/select2.min.css');
      add_css('dist/vendor/pickadate.js/themes/classic.css');
      add_css('dist/vendor/pickadate.js/themes/classic.date.css');
      add_css('dist/custom/css/file_upload.css');
      add_js('themes/elaadmin/js/lib/select2/select2.full.min.js');
      add_js('themes/elaadmin/js/lib/select2/i18n/es.js');
      add_js('dist/vendor/pickadate.js/picker.js');
      add_js('dist/vendor/pickadate.js/picker.date.js');
      add_js('dist/vendor/pickadate.js/translations/date_es_ES.js');
      add_js('dist/custom/js/file_upload.js');
      add_js('dist/custom/js/mantenimiento/add_asset.js');

      $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
      $this->load->view('mantenimiento'. DS .'add_asset', $view_data);
      $this->load->view('footers'. DS .'footer_main_dashboard');
    } else {
      redirect('auth');
    }
  }

  /**
   * Agrega un activo a la base de datos.
   *
   * @param array $data Nuevos datos para el activo.
   *
   * @return boolean
   */
  public function save_asset($data) {
    try {
      return $this->Activos_mdl->add_asset($data);
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
   * Verificar si un activo existe en la base de datos.
   *
   * @param string $asset_code Código del activo.
   *
   * @return boolean
   */
  public function check_if_exists($asset_code) {
    return $this->Activos_mdl->check_asset_if_exists($asset_code);
  }
}
