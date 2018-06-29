<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Librería de Productos Base
 *
 * Esta librería posee funciones útiles para toda la informació que se relaciona
 * con los productos base de la compañía.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Base_Product {
	protected $ci;

	public function __construct() {
		$this->ci =& get_instance();
	}

	/**
	 * Verifica si un producto base necesita plano o no.
	 *
	 * @param array $material_code Código de material a ser verificado.
	 *
	 * @return array En caso de que se genere la estructura correctamente.
	 *		boolean En caso de que no se genere la estructura adecuadamente.
	 */
	public function check_Flat_Requirement_from_Product($material_code = NULL) {
		if (!isset($material_code) || empty($material_code)) {
			return FALSE;
		}

		$this->ci->load->model('EVPIU/Materiales_model', 'Materiales_mdl');
		$materials = $this->ci->Materiales_mdl->get_Materials_with_Flat_Requirement(TRUE);

		foreach ($materials as $key => $material) {
			$materials_array[] = $material['CodMaterial'];
		}

		if (in_array($material_code, $materials_array)) {
			return TRUE;
		}

		return FALSE;
	}  
}