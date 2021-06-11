<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->_init();
	}
	function _init()
	{
		$this->output->set_template('index');
	}

	public function index()
	{
		$this->load->view('home/index.php');
	}
}
