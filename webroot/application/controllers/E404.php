<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E404 extends CI_Controller { 
 	public function index() {
 		$this->data['heading'] = '404 PAGE NOT FOUND';
 		$this->data['message'] = 'The page you requested was not found.';

    $this->load->view('errors/html/error_404', $this->data);
 	}
}