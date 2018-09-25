<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo: Archivos de Activos
 * 
 * Descripción del modelo
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Activos_archivos_model extends CI_Model {
	public $_table = 'act_Archivos';
	public $file_type_asset_document = 6;

	public function __construct() {
		parent::__construct();

		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Reporta un documento anexado a un activo en la base de datos.
	 *
	 * Este método se encarga de guardar los detalles de un documento que se cargó
	 * a un activo.
	 *
	 * @param array $data Detalles del archivo anexado.
	 *
	 * @return int
	 */
	public function add_document($data) {
		if (is_array($data) && !empty($data)) {
			$this->db_evpiu->insert($this->_table, $data);
			$id = $this->db_evpiu->insert_id();

			if (isset($id)) {
				return $id;
			}
		}

		return FALSE;
	}
}