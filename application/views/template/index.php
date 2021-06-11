<?php
$this->load->view('template/head');
$this->load->view('template/menu');
$this->load->get_section('sidebar');
$this->load->get_section('theme-switcher');
echo $output;
$this->load->view('template/footer');
?>