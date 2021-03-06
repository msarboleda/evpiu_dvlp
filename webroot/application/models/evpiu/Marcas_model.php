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
	 * @description Obtiene todas las marcas existentes. 
	 * 
	 * @param string $order_column 
	 * @param string $order 
	 * 
	 * @return object
	 * @return Exception
	 */
	public function get_All_Marks($order_column = 'NomMarca', $order = 'desc') {
		$this->db_evpiu->order_by($order_column, $order);

		$query = $this->db_evpiu->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			throw new Exception('La consulta de marcas no obtuvo resultados.');
		}
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
	 * Crea una marca.
	 * 
	 * @param array $data Datos para almacenar de una marca.
	 * 
	 * @return string En caso de que la marca se haya creado.
	 * @return boolean En caso de datos con el formato incorrecto o la
	 * marca sea duplicada.
	 */
	public function add_Mark($data = array()) {
		if (!is_array($data) || empty($data)) {
			return FALSE;
		}

		if (!$this->duplicated_Mark_description($data['Nombre'])) {
			$mark_name = strtoupper($data['Nombre']);
			$find = strpos($mark_name, strtoupper('generico'));
			
			if ($find !== false) {
				$is_generic_mark = true;
			} else {
				$is_generic_mark = $data['Generico'];
			}

			$to_database = array(
				'CodMarca'      => $this->get_Last_Mark_code()+1,
				'NomMarca'      => $mark_name,
				'Generico'      => $is_generic_mark,
				'Creo'          => $this->ion_auth->user()->row()->username,
				'FechaCreacion'	=> date('Y-m-d H:i:s'),
				'Comentarios'   => $data['Comentarios'],
			);

			$this->db_evpiu->insert($this->_table, $to_database);
			$id = $this->db_evpiu->insert_id();

			if (isset($id)) {
				return $id;
			}
		}

		return FALSE;
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

	/**
	 * Obtiene el último código de marca almacenado en la base de datos.
	 * 
	 * @return string Último número de marca almacenado en la base de datos.
	 * @return boolean False En caso de que la consulta no arroje resultados. 
	 */
	public function get_Last_Mark_code() {
		$this->db_evpiu->select('CodMarca');
		$this->db_evpiu->from($this->_table);
		$this->db_evpiu->order_by('CodMarca', 'desc');
		$this->db_evpiu->limit(1);
		$query = $this->db_evpiu->get();
  
		if ($query->num_rows() > 0) {
			return $query->row('CodMarca');  
		}

		return FALSE;
	}
}