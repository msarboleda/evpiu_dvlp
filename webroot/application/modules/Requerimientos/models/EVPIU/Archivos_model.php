<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para Archivos de Requerimientos
 * 
 * Este modelo se relaciona con la tabla de archivos de requerimientos;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Archivos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'req_Archivos';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Inserta los datos de un tipo de archivo guardado en el servidor y lo vincula
	 * a un código en la base de datos por el cual se pueda identificar su origen.
	 *
	 * Este método se encarga de guardar los datos de un archivo que se cargó
	 * al servidor y asignar un código, ya sea de requerimiento, propuesta o arte
	 * dependiendo del módulo que se utilizó para guardar el archivo.
	 *
	 * @param string $file_type Tipo de archivo que se va a insertar.
	 * @param array $data Datos del archivo que se van a insertar.
	 *
	 * @return int En caso de que el Query inserte datos correctamente.
	 *		boolean En caso de que el Query no inserte los datos correctamente.
	 */
	public function add_File($file_type, $data = array()) {
		if (!isset($file_type) || empty($file_type)) {
			return FALSE;
		}

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