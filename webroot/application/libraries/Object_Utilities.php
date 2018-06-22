<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Librería de Utilidades para los Objetos de PHP
 *
 * Esta librería posee funciones útiles para los Objetos de PHP.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Object_Utilities {
	/**
	 * Se encarga de limpiar todos los campos de un objeto.
	 *
	 * @return object Devuelve el objeto con todos los campos limpios.
	 *		false En caso de que el parámetro no sea un objeto.
	 */
	public function trim_object_data($object) {
		if (is_object($object)) {
			foreach($object as &$prop) {
				$prop = trim($prop);
			}

			return $object;
		}

		return FALSE;
	}  
}