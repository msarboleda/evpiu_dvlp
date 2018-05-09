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

		$this->_table = 'V_ModulosxCategoriasxGrupos V';
		$this->db_evpiu = $this->load->database('EVPIU', true);
	}

	/**
	 * @return array Cantidad de módulos permitidos a grupos 
	 * filtrados por su categoría.
	 *	or
	 * @return false En caso de que el Query no tenga ningún resultado
	 */
	public function consultarCantModulos_x_Categorias_x_Grupos($grupos) {
		$this->db_evpiu->select('V.CodCategoria, V.NomCategoria, COUNT(V.CodModulo) as Modulos, C.Icono');
		$this->db_evpiu->from($this->_table);
  	$this->db_evpiu->where_in('Grupo', $grupos);
  	$this->db_evpiu->join('Categorias C', 'V.CodCategoria = C.CodCategoria', 'inner');
    $this->db_evpiu->group_by('V.CodCategoria, V.NomCategoria, C.Icono');
    $query = $this->db_evpiu->get();

    if ($query->num_rows() > 0){
      return $query->result();
    }

    return false;
	}
}