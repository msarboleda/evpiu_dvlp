<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Librería de Utilidades para los Arreglos de PHP
 *
 * Esta librería posee funciones útiles para los Arreglos de PHP.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Array_Utilities {
	/**
	 * Se encarga de comprobar si un arreglo es una matriz multidimensional
	 *
	 * @param $array array Arreglo a comprobar
	 *
	 * @return bool TRUE or FALSE
	 */
	public function is_multidimensional_array($array) {
    rsort( $array );
    return isset( $array[0] ) && is_array( $array[0] );
	} 

	/**
	 * Se encarga de comprobar si un arreglo está totalmente lleno.
	 *
	 * @param $array array Arreglo a comprobar
	 *
	 * @return bool TRUE or FALSE
	 */
	public function is_fully_loaded_array($array) {
		if (is_array($array) && !empty($array)) {
			foreach($array as $value) {
				if (empty($value)) {
					return FALSE;
				}
			}
			
			return TRUE;
		}

		return FALSE;
	}
}