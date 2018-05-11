<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E404 extends MX_Controller { 
	public $data;

 	public function index() {
 		$this->data['heading'] = 'Página no encontrada';
 		$this->data['message'] = 'La página que solicitaste no fue encontrada.';

  	$this->load->view('error_404', $this->data);
 	}
}