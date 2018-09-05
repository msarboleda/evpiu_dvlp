<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Librería Header
 *
 * Esta librería permite cargar e intercambiar datos en el Header del Dashboard.
 * 
 * @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
 * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
 */

class Header {
	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model('evpiu/Modulosxcategoriasxgrupos_model', 'ModulosxCategoriasxGrupos');
	}

	/**
	 * @return array Categorias y módulos permitidos a los grupos del usuario actual.
	 *	or
	 * @return false En caso de que el parámetro sea nulo o no sea un array
	 */
	public function cargarCategorias_Modulos() {
		// Usuario actual
		$usuario_actual = $this->CI->ion_auth->user()->row();

		// Grupos a los que pertenece el usuario
		$grupos_usuario = $this->CI->ion_auth->get_users_groups($usuario_actual->id)->result();

		// Asignación de id's de grupos hacia un arreglo
		foreach ($grupos_usuario as $key => $grupo_usuario) {
			$grupos_usuarios_ids[$key] = $grupo_usuario->id;
		}

		// Consulta de Categorías y Cantidad de módulos para grupos específicos
		$categorias = $this->consultarCantModulos_x_Categorias_x_Grupos($grupos_usuarios_ids);

		// Envío de información para el sidebar del header
		if (isset($categorias) && is_array($categorias)) {
			$modulos = $this->consultarModulosxCategorias_x_Grupos($grupos_usuarios_ids);

			if (isset($modulos) && is_array($modulos)) {
				$data['Categorias'] = $categorias;
				$data['Modulos'] = $modulos;

				return $data;
			}

			return false;
		} 

		return false;
	}

	/**
	 * Devuelve todas las categorías y módulos asignados a un usuario 
	 * de la plataforma en la base de datos.
	 *
	 * @return array Categorías y módulos asignados.
	 *		error_view En caso de que el usuario no posea categorías o
	 *							 módulos asignados.
	 */
	public function show_Categories_and_Modules() {
		$header_data['Categorias'] = $this->cargarCategorias_Modulos()['Categorias'];
		$header_data['Modulos'] = $this->cargarCategorias_Modulos()['Modulos'];

		if (!$header_data['Categorias'] || !$header_data['Modulos']) {
			return show_error('Ocurrió un error en la carga de sus aplicaciones asignadas.');
		}

		return $header_data;
	}

	/**
	 * @return array Módulos filtrados por Categoría permitidos a grupos
	 *	or
	 * @return false En caso de que el parámetro sea nulo o no sea un array
	 */
  public function consultarCantModulos_x_Categorias_x_Grupos($grupos){
  	if (!isset($grupos) || !is_array($grupos)) {
  		return false;
  	} 

    $Categorias_Modulos = $this->CI->ModulosxCategoriasxGrupos->consultarCantModulos_x_Categorias_x_Grupos($grupos);
    return $Categorias_Modulos;
  }

  /**
	 * @return array Módulos de cada Categoría permitidos a grupos
	 *	or
	 * @return false En caso de que el parámetro sea nulo o no sea un array
	 */
	public function consultarModulosxCategorias_x_Grupos($grupos) {
		if (!isset($grupos) || !is_array($grupos)) {
  		return false;
  	} 

		$Modulos = $this->CI->ModulosxCategoriasxGrupos->consultarModulosxCategorias_x_Grupos($grupos);
		return $Modulos;
	}  
}