<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para Productos Base
 * 
 * Este modelo se relaciona con la tabla de productos base;
 * Tiene funciones dedicadas exclusivamente a la tabla definida
 * dentro del constructor, principalmente se busca retornar todo
 * tipo de dato relacionado con esta tabla.
 *
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class ProductosBase_model extends CI_Model {
	// Estado del producto base: Habilitado
	public $base_product_enabled_state = 1;

	// Estado del producto base: Deshabilitado
	public $base_product_disabled_state = 0;

	public function __construct() {
		parent::__construct();

		$this->_table = 'ProductosBase';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * Verifica que un producto base es o no existente.
	 *
	 * Este método se encarga de realizar una verificación en los
	 * productos base existentes, para evitar que los nuevos
	 * productos base no se repitan.
	 *
	 * @param string $base_product_code Código del producto base.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function duplicated_Base_Product($base_product_code = NULL) {
		if (empty($base_product_code)) {
			return FALSE;
		}

		return $this->db_evpiu->where('CodPrimario', $base_product_code)
						->limit(1)
						->count_all_results($this->_table) > 0;
	}

	/**
	 * Genera la estructura del código de un producto base.
	 *
	 * @param array $data Datos del producto base.
	 *
	 * @return string Código del producto base.
	 * 		boolean FALSE En caso de que el parámetro tenga un mal formato o esté vacío. 
	 */
	public function generate_Base_Product_Code($data = array()) {
		if (!isset($data) || empty($data)) {
			return FALSE;
		}

		if (is_array($data) && !empty($data)) {
			$this->load->model('EVPIU/Sublineas_model', 'Sublineas_mdl');
			
			$Assembly = $this->Sublineas_mdl->find_Subline($data['Linea'], $data['Sublinea'])->Ensamblado;

			switch ($Assembly) {
				case 0:
					$CodAssemble = '4';
					break;
				case 1:
					$CodAssemble = '3';
					break;
				default:
					$CodAssemble = NULL;
					break;
			}

			if ($CodAssemble === NULL) {
				return FALSE;
			} else {
				$product_code = $CodAssemble.$data['Linea'].$data['Sublinea'].$data['Material'].$data['Tamano'];
			}

			return $product_code;
		}
	}

	/**
	 * Genera la estructura de la descripción de un producto base.
	 *
	 * @param array $data Datos del producto base. 
	 *
	 * @return string Descripción del producto base.
	 *		boolean FALSE En caso de que el parámetro tenga un mal formato o esté vacío.
	 */
	public function generate_Base_Product_Description($data = array()) {
		if (!isset($data) || empty($data)) {
			return FALSE;
		}

		if (is_array($data) && !empty($data)) {
			$this->load->model('EVPIU/Lineas_model', 'Lineas_mdl');
			$this->load->model('EVPIU/Sublineas_model', 'Sublineas_mdl');
			$this->load->model('EVPIU/Caracteristicas_model', 'Caracteristicas_mdl');
			$this->load->model('EVPIU/Materiales_model', 'Materiales_mdl');
			$this->load->model('EVPIU/Tamanos_model', 'Tamanos_mdl');

			$line_alias     = $this->Lineas_mdl->find_Line($data['Linea'])->Abreviatura;
			$subline_alias  = $this->Sublineas_mdl->find_Subline($data['Linea'], $data['Sublinea'])->Abreviatura;
			$feature_alias  = $this->Caracteristicas_mdl->find_Feature($data['Linea'], $data['Sublinea'], $data['Caracteristica'])->Abreviatura;
			$material_alias = $this->Materiales_mdl->find_Material($data['Material'])->Abreviatura;
			$size_alias     = $this->Tamanos_mdl->find_Size($data['Linea'], $data['Sublinea'], $data['Material'], $data['Tamano'])->Denominacion;

			if (empty($feature_alias)) {
				$product_description = $line_alias.' '.$subline_alias.' '.$material_alias.' '.$size_alias;
			} else {
				$product_description = $line_alias.' '.$subline_alias.' '.$feature_alias.' '.$material_alias.' '.$size_alias;
			}

			return $product_description;
		}
		
		return FALSE;
	}

	/**
	 * Genera la estructura completa de un producto base.
	 *
	 * @param array $data Datos del producto base. 
	 *
	 * @return array Código y Descripción del producto base.
	 *		boolean FALSE En caso de que el parámetro tenga un mal formato o esté vacío.
	 */
	public function generate_Base_Product_Structure($data = array()) {
		if (!isset($data) || empty($data)){
			return FALSE;
		}

		$base_product_structure = array(
			'product_code'        => $this->generate_Base_Product_Code($data),
			'product_description' => $this->generate_Base_Product_Description($data),
		);

		return $base_product_structure;
	}

	/**
	 * Agrega un producto base a la tabla de la base de datos.
	 *
	 * Este método se encarga de almacenar un producto base en
	 * en la respectiva tabla de este modelo en la base de datos.
	 *
	 * @param array $data Información acerca del producto base.
	 * @param array $additional_data Código y descripción del producto base.
	 *
	 * @return int En caso de que la inserción sea exitosa.
	 *		boolean En caso de que la inserción no sea correcta.
	 */
	public function add_Base_Product($data = array(), $additional_data = array()) {
		if (!is_array($data) || empty($data)) {
			return FALSE;
		}

		if (!is_array($additional_data) || empty($additional_data)) {
			return FALSE;
		}

		$this->load->model('EVPIU/Marcas_model', 'Marcas_mdl');
		$this->load->library('base_product');

		$generic_mark = $this->Marcas_mdl->find_Mark($data['Marca'])->Generico;
		$flat_requirement = $this->base_product->check_Flat_Requirement_from_Product($data['Material']);
					
		$attributes = array(
			'CodPrimario' => $additional_data['product_code'],
			'DescPrimaria' => utf8_decode($additional_data['product_description']),
			'CodLinea' => $data['Linea'],
			'CodSublinea' => $data['Sublinea'],
			'CodCaracteristica' => $data['Caracteristica'],
			'CodMaterial' => $data['Material'],
			'CodTamano' => $data['Tamano'],
			'CodEspesor' => $data['Espesor'],
			'RequierePlano' => $flat_requirement,
			'Estado' => $this->base_product_enabled_state,
			'Generico' => $generic_mark,
			'Origen' => $data['origen'],
			'Creo' => $this->ion_auth->user()->row()->username,
			'FechaCreacion' => date('Y-m-d H:i:s'),
		); 

		$this->db_evpiu->insert($this->_table, $attributes);
		$id = $this->db_evpiu->insert_id();

		return (isset($id)) ? $additional_data['product_code'] : FALSE;
	}

	/**
	 * Organiza los productos base en un formato para llenar controles Select
	 * con el plugin 'Select2'.
	 *
	 * Este método se encarga de listar los productos base existentes, 
	 * y luego organizar la información en un formato utilizado para
	 * mostrarse en un plugin con nombre 'Select2'.
	 *
	 * @param string $term Término de la búsqueda en el control <select>.
	 * @param string $order Orden ascendente o descendente para mostrar los resultados.
	 *
	 * @return array En caso de que la consulta arroje resultados.
	 *		boolean En caso de que la consulta no arroje resultados.
	 */
	public function fill_remote_Productos_Base_select($term = NULL, $order = 'asc') {
		if (!isset($term)) {
			return FALSE;
		}

		$this->db_evpiu->select('CodPrimario, DescPrimaria');
		$this->db_evpiu->like('DescPrimaria', $term);
		$this->db_evpiu->order_by('DescPrimaria', $order);

		$query = $this->db_evpiu->get($this->_table); 

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$prods_base = array();

			foreach ($result as $row) {
				$prods_base[] = array('id' => $row['CodPrimario'], 'text' => $row['DescPrimaria']);    
			}
		} else {
			$prods_base[] = array('id' => NULL, 'text' => 'Este producto base no se encuentra disponible.');
		}

		return $prods_base;
	}
}