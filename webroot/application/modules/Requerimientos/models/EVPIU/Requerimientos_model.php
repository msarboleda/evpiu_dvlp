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
	 * @param array $data Datos de un formulario que contiene información del
	 *        requerimiento para agregar a la base de datos.
	 * @param array $additional_data Datos que no se pueden obtener del formulario
	 *        y se necesitan capturar.
	 *
	 * @return string Número de requerimiento que se almacenó.
	 * 		boolean FALSE En caso de que los datos que se mandan como parámetro
	 *        no tengan una estructura de array o el array de datos este vacío.
	 */
	public function add_Request($data = array(), $additional_data = array()) {
		if (!is_array($data) || empty($data)) {
			return FALSE;
		}

		if (!is_array($additional_data) || empty($additional_data)) {
			return FALSE;
		}

		if (empty($data['base_product'])) {
			$CodBase = null;
		} else {
			$CodBase = $data['base_product'];
		}

		$attributes = array(
			'NroRequerimiento'     => $this->get_Last_Request_number() + 1,
			'CodVendedor'          => $this->ion_auth->user()->row()->Vendedor,
			'CodCliente'           => $data['Cliente'],
			'CodMarca'             => $data['Marca'],
			'CodParametro'         => $data['Parametro'],
			'CodEspesor'           => $data['Espesor'],
			'CodRelieve'           => $data['Relieve'],
			'CodPrimario'          => $additional_data['Primario'],
			'FechaCreacion'        => date('Y-m-d H:i:s'),
			'Estado'               => $additional_data['Estado'],
			'Descripcion'          => $data['Comentarios'],
			'Creo'                 => $this->ion_auth->user()->row()->username,
			'Renderizar'           => $data['requires_rendering'],
			'RequiereBase'         => $data['applied_art'],
			'CodBase'              => $CodBase,
		);

			
		$this->db_evpiu->insert($this->_table, $attributes);
		$id = $this->db_evpiu->insert_id();

		if (isset($id)) {
			return $this->find_Request_by_id($id)->NroRequerimiento;
		}

		return FALSE;
	}

	/**
	 * Obtiene toda la información de un requerimiento filtrado por su id.
	 *
	 * @param int $id Número de requerimiento que se desea consultar.
	 *
	 * @return object Información de un requerimiento específico.
	 *    boolean En caso de no definir un id de requerimiento para
	 *    consultar.
	 */
	public function find_Request_by_id($id) {
		if (isset($id) && !empty($id)) {
			return $this->db_evpiu->select()
				->from($this->_table)
				->where('idRequerimiento', $id)
				->get()
				->row();
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
  
		if ($query->num_rows() > 0) {
			return $query->row('NroRequerimiento');  
		}

		return FALSE;
	}
}