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
}