<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Librería de Utilidades para Fechas de PHP
 *
 * Esta librería posee funciones útiles para las Fechas de PHP.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Date_Utilities {
	public function __construct() {
		setlocale(LC_TIME, 'es_CO.utf8');
		date_default_timezone_set('America/Bogota');
	}

	/**
	 * Se encarga de formatear una fecha en lenguaje español.
	 *
	 * @param $format Formato que la fecha debe de tomar.
	 * @param $date Fecha a formatear.
	 *
	 * @return object Devuelve una fecha con un formato en específico.
	 *		false En caso de que el parámetro no sea un objeto.
	 */
	public function format_date($format, $date) {
		if (isset($format) && isset($date)) {
			return strftime($format, strtotime($date));
		}
	}  
}