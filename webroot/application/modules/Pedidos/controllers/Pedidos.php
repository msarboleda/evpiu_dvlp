<?php  defined('BASEPATH') OR exit('No se permite el acceso directo a este Script');
 /**
  * Clase de Pedidos
  *
  * Esta clase implementa el módulo de pedidos de Estrada Velasquez, es dependiente de Requerimientos
  * 
  * @author Martin Arboleda Montoya <sistemas@estradavelasquez.com>
  * @copyright 2018 CI Estrada Velasquez y Cia S.A.S
  */
  class Pedidos extends MX_Controller {
    public function __construct() {
      parent::__construct();

      $this->load->model('Auth/EVPIU/ModulosxCategoriasxGrupos_model');
      $this->load->library(array('header', 'verification_roles'));
      $this->load->helper(array('language', 'load', 'form'));
    }

    public function index() {
      if ($this->verification_roles->is_vendor() || $this->ion_auth->is_admin()) {
        $header_data = $this->header->show_Categories_and_Modules();
        $this->lang->load('index');
        $header_data['module_name'] = lang('index_heading');

        $this->load->view('headers'. DS .'header_main_dashboard', $header_data);
        $this->load->view('footers'. DS .'footer_main_dashboard');
      } else {
        redirect('auth');
      }
    }
  }
