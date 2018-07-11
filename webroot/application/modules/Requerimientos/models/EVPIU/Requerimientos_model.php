<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para Requerimientos
 * 
 * Este modelo se relaciona con la tabla de requerimientos;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Requerimientos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Requerimientos';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Agrega un requerimiento a la base de datos.
	 *
	 * @param array $data Datos de un requerimiento para agregar a la base
	 *        de datos.
	 *
	 * @return string Número de requerimiento que se almacenó.
	 * 		boolean FALSE En caso de que los datos que se mandan como parámetro
	 *        no tengan una estructura de array o el array de datos este vacío.
	 */
	public function add_Request($data = array()) {
		if (is_array($data) && !empty($data)) {
			$this->db_evpiu->insert($this->_table, $data);
			$id = $this->db_evpiu->insert_id();

			if (isset($id)) {
				return $this->db_evpiu->select('NroRequerimiento')
					->from($this->_table)
					->where('idRequerimiento', $id)
					->get()
					->row('NroRequerimiento');
			}
		}

		return FALSE;	
	}

	/**
	 * Obtiene el último número de requerimiento almacenado en la base de datos.
	 *
	 * @return string Último número de requerimiento almacenado en la base de datos.
	 *    boolean FALSE En caso de que la consulta no arroje resultados.
	 */
	public function get_Last_Request_number() {
		$this->db_evpiu->select('NroRequerimiento');
		$this->db_evpiu->from($this->_table);
		$this->db_evpiu->order_by('NroRequerimiento', 'desc');
		$this->db_evpiu->limit(1);
		$query = $this->db_evpiu->get();
  
		if ($query->num_rows() > 1) {
			return $query->row('NroRequerimiento');  
		}

		return FALSE;
	}
}