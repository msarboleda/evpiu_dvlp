<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo de Marcas
 * 
 * Este modelo se relaciona con la tabla de Marcas;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Marcas_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'Marcas';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Devuelve toda la información de una marca.
	 *
	 * @param string $mark_code Código de la marca.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function find_Mark($mark_code = NULL, $order = 'asc') {
		if (!isset($mark_code)) {
			return FALSE;
		}

		$this->db_evpiu->select();
		$this->db_evpiu->where('CodMarca', $mark_code);
		$this->db_evpiu->order_by('NomMarca', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$row = $query->row();

			return $row;
		}

    return FALSE;
	}

	/**
	 * Organiza las marcas en un formato para llenar controles Select
	 * con el plugin 'Select2'.
   *
	 * Este método se encarga de listar las marcas existentes, 
	 * y luego organizar la información en un formato utilizado para
	 * mostrarse en un plugin con nombre 'Select2'.
	 *
	 * @param string $term Término de la búsqueda en el control <select>.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function fill_remote_Marcas_select($term = NULL, $order = 'asc') {
		if (!isset($term)) {
			return FALSE;
		}

		$this->db_evpiu->select('CodMarca, NomMarca');
		$this->db_evpiu->like('NomMarca', $term);
		$this->db_evpiu->order_by('NomMarca', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$marcas = array();

			foreach ($result as $row) {
				$marcas[] = array('id' => $row['CodMarca'], 'text' => $row['NomMarca']);    
			}
		} else {
   		$marcas[] = array('id' => '0', 'text' => 'Esta marca no se encuentra disponible.');
		}

		return $marcas;
	}

	/**
	 * Verifica si la descripción de una marca ya existe.
	 * 
	 * @param string $mark_description Descripción de la marca a verificar.
	 * 
	 * @return boolean True or False
	 */
	public function duplicated_Mark_description($mark_description) {
		if (!isset($mark_description) || empty($mark_description)) {
			return FALSE;
		}

		return $this->db_evpiu->where('NomMarca', trim($mark_description))
						->limit(1)
						->count_all_results($this->_table) > 0;
	}
}