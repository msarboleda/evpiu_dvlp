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
   * @param object $data Nuevos datos para el activo.
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
}