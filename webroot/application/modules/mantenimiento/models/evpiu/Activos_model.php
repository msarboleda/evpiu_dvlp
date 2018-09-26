<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Activos
 *
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Activos_model extends CI_Model {
  public $_table = 'Activos';
  public $_master_view_table = 'V_Activos';
  public $_files_table = 'act_Archivos';

	public function __construct() {
    parent::__construct();

    $this->db_evpiu = $this->load->database('EVPIU', true);
    $this->load->helper('language');
    $this->lang->load('activos');
  }

  /**
	 * Obtiene todos los activos existentes.
	 *
	 * @return object
	 */
	public function get_all_assets() {
    $this->load->library('Date_Utilities');
		$query = $this->db_evpiu->get($this->_master_view_table);

		if ($query->num_rows() > 0) {
      $results = $query->result();

      foreach ($results as $result) {
        $result->UltimaRevision = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->UltimaRevision));
      }

      return $results;
		} else {
			throw new Exception(lang('get_all_assets_no_results'));
		}
  }

  /**
   * Obtiene la información de un activo existente.
   *
   * @param $asset_code Código del activo.
   *
   * @return object
   */
  public function get_asset($asset_code) {
    $this->db_evpiu->where('CodActivo', $asset_code);
    $query = $this->db_evpiu->get($this->_master_view_table);

    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      throw new Exception(lang('get_asset_no_results'));
    }
  }

  /**
   * Obtiene los documentos anexados a un activo existente.
   *
   * @param $asset_code Código del activo.
   *
   * @return object
   */
  public function get_asset_files($asset_code) {
    $this->load->library('Date_Utilities');
    $this->db_evpiu->where('CodActivo', $asset_code);
    $query = $this->db_evpiu->get($this->_files_table);

    if ($query->num_rows() > 0) {
      $results = $query->result();

      foreach ($results as $result) {
        $result->FechaCreacion = ucfirst($this->date_utilities->format_date('%B %d, %Y', $result->FechaCreacion));
      }

      return $results;
    } else {
      throw new Exception(lang('asset_files_no_results'));
    }
  }

  /**
   * Actualiza la información de un activo específico.
   *
   * @param string $asset_code Código del activo.
   * @param array $data Nuevos datos para el activo.
   *
   * @return boolean
   */
  public function update_asset($asset_code, $data) {
    $formatted_data = array(
      'NombreActivo' => $data['nom_activo'],
      'idClasificacion' => $data['clasif_sel'],
      'Responsable' => $data['resp_sel'],
      'idEstado' => $data['est_sel'],
      'idPlanta' => $data['plant_sel'],
      'idPrioridad' => $data['prior_sel'],
      'UltimaRevision' => $data['ult_revis'],
      'CostoMantenimiento' => $data['cost_mant'],
      'FichaTecnica' => $data['ficha_tecnica'],
      'Funcionalidad' => $data['funcionalidad'],
    );

    $this->db_evpiu->where('CodigoActivo', $asset_code);
    return $this->db_evpiu->update($this->_table, $formatted_data);
  }

  /**
   * Verificar si un activo existe en la base de datos.
   *
   * @param string $asset_code Código del activo.
   *
   * @return boolean
   */
  public function check_asset_if_exists($asset_code) {
    try {
      $check_if_exists = $this->get_asset($asset_code);

      if (is_object($check_if_exists) && !empty($check_if_exists)) {
        return TRUE;
      }
    } catch (Exception $e) {
      return FALSE;
    }
  }

  /**
   * Agrega un activo a la base de datos.
   *
   * @param array $data
   *
   * @return boolean
   */
  public function add_asset($data) {
    $check_if_exists = $this->check_asset_if_exists($data['cod_activo']);

    if ($check_if_exists === TRUE) {
      throw new Exception(lang('asset_already_exists'));
    }

    $formatted_data = array(
      'CodigoActivo' => strtoupper($data['cod_activo']),
      'NombreActivo' => ucfirst($data['nom_activo']),
      'idClasificacion' => $data['clasif_sel'],
      'Responsable' => $data['resp_sel'],
      'idEstado' => $data['est_sel'],
      'idPlanta' => $data['plant_sel'],
      'idPrioridad' => $data['prior_sel'],
      'UltimaRevision' => $data['ult_revis_submit'],
      'FichaTecnica' => $data['ficha_tecnica'],
      'Funcionalidad' => $data['funcionalidad'],
      'CostoMantenimiento' => 0 // Sin valor por defecto
    );

    return $this->db_evpiu->insert($this->_table, $formatted_data);
  }

  /**
   * Obtiene los valores necesarios para poblar un control
   * <select> con todos los activos existentes.
   *
   * @return object
   */
  public function populate_assets() {
    $this->db_evpiu->select('CodigoActivo, NombreActivo');
    $this->db_evpiu->order_by('NombreActivo', 'asc');
    $query = $this->db_evpiu->get($this->_table);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      throw new Exception(lang('populate_assets_no_results'));
    }
  }
}
