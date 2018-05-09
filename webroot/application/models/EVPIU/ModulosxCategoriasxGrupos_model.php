<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model ModulosxCategoriasxGrupos
 * 
 * Este modelo se relaciona con la tabla de Módulos por Categorías
 * por Grupos.
 * Tiene la funcionalidad de mostrar todo tipo de dato relacionado con
 * esta tabla.
 */
class ModulosxCategoriasxGrupos_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->_table = 'V_ModulosxCategoriasxGrupos';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * @return array Cantidad de módulos permitidos a grupos 
	 * filtrados por su categoría.
	 *	or
	 * @return false En caso de que el Query no tenga ningún resultado
	 */
	public function consultarCantModulos_x_Categorias_x_Grupos($grupos) {
		$this->db_evpiu->select('CodCategoria, NomCategoria, COUNT(CodModulo) as Modulos');
		$this->db_evpiu->from($this->_table);
  	$this->db_evpiu->where_in('Grupo', $grupos);
    $this->db_evpiu->group_by('CodCategoria, NomCategoria');
    $query = $this->db_evpiu->get();

    if ($query->num_rows() > 0){
      return $query->result();
    }

    return false;
	}
}