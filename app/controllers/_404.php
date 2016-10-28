<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class _404 extends CI_Controller {
	public function index(){
		$this->load->view('error_view');
	}
}
