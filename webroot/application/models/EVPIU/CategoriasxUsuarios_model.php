<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model CategoriasxUsuarios
 * 
 * Este modelo se relaciona con la tabla de Categorias por Usuarios.
 * Tiene la funcionalidad de mostrar todo tipo de dato relacionado con
 * esta tabla.
 */
class CategoriasxUsuarios_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'CIEV_V_CategoriasXUsuarios';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * @return array Categorias de aplicaciones permitidas a un usuario 
	 * ordenadas por el nombre de la categoría en forma ascendente
	 *	or
	 * @return false En caso de que el parámetro sea nulo
	 */
	public function consultarCategorias_x_Usuario($usuario) {
		$this->db_evpiu->select();
		$this->db_evpiu->from($this->_table);
    $this->db_evpiu->where('username', $usuario);
    $this->db_evpiu->order_by('NombreCategoria', 'ASC');
    $query = $this->db_evpiu->get();

    if ($query->num_rows() > 0){
      return $query->result();
    }

    return false;
	}
}