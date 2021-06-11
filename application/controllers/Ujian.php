<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->model('model_pendaftaran');
	}
	function _init()
	{
		$this->output->set_template('index');
	}
	public function index()
	{
		$this->_init();
		$this->load->view('registration/new');
		// $this->load->view('registration/wait_page');
	}
	public function simpan_data()
	{
		date_default_timezone_set("Asia/Makassar");
		$nim = $this->input->post('nim');
		$nama = $this->input->post('nama');
		$kode = $this->input->post('kode');
		$data['NIM'] = $nim;
		$data['NMLGKP'] = $nama;
		$data['KODESOAL'] = $kode;
		$data['STATUS'] = 1;
		$data['TANGGAL'] = date("Y-m-d");
		//periksa kode
		$hsl_cek_kode = $this->model_pendaftaran->cek_kode_ujian($kode);
		$hsl_cek_val_peserta = $this->model_pendaftaran->cek_validasi_peserta($nim, $kode);
		$hsl_cek_sts_peserta = $this->model_pendaftaran->cek_status_peserta($nim, $kode);
		$dataSoal = $hsl_cek_kode->row();

		date_default_timezone_set("Asia/Makassar");
		$date = date('Y-m-d H:i:s', time());

		if($hsl_cek_kode->num_rows() < 1){
			$tglUjian = "";
			$jamUjian = "";
		}else{
			$tglUjian = $dataSoal->TANGGAL;
			$jamUjian = $dataSoal->JAM_UJIAN;
		}


		$different = $date >= $tglUjian." ".$jamUjian;

		if($hsl_cek_kode->num_rows() < 1)
		{
			$psn = "Kode Ujian Yang Anda Masukkan Salah";
			$err = 1;
			$id_peserta="";
			$encIdPeserta = "";
		}
		else
		{
			if($hsl_cek_val_peserta==2)
			{
				if($hsl_cek_sts_peserta[0]->STATUS == 0){ // Baru Melakukan Registrasi
					if($different){
						$id_peserta = $this->model_pendaftaran->get_id_peserta($nim, $kode);
						$encIdPeserta = newEncode($id_peserta);
						$dataUpdate['STATUS'] = 1;
						$this->model_pendaftaran->update_status_peserta($id_peserta, $dataUpdate);
						$psn = "Anda berhasil melakukan registrasi. Selamat bekerja.";
						$err = 2;
					}else{
						$psn = "Maaf. Ujian Belum Berlangsung.";
						$err = 1;
						$id_peserta = "";
						$encIdPeserta = "";
					}
				}elseif($hsl_cek_sts_peserta[0]->STATUS == 1){
					// Telah Melakukan Registrasi Namun Belum Selesai
					if($different){
						$psn = "Silahkah Lanjutkan Pekerjaan Anda.";
						$err = 2;
						$id_peserta = $this->model_pendaftaran->get_id_peserta($nim, $kode);
						$encIdPeserta = newEncode($id_peserta);
					}else{
						$psn = "Maaf. Ujian Belum Berlangsung.";
						$err = 1;
						$id_peserta = "";
						$encIdPeserta = "";
					}
				}elseif($hsl_cek_sts_peserta[0]->STATUS == 2){
					// Telah Registrasi dan Telah Selesai Mengerjakan Ujian
					$psn = "Maaf. Anda sudah terdaftar dan telah selesai mengerjakan ujian untuk kode ujian yang anda masukkan. Periksan kembali kode ujian Anda.";
					$err = 1;
					$id_peserta = "";
					$encIdPeserta = "";
				}
			}
			else
			{
				$psn = "Maaf. Anda tidak terdaftar pada ujian dengan kode tersebut.";
				$err = 1;
				$id_peserta="";
				$encIdPeserta = "";
			}
		}
		// echo $psn."-".$err."-".$encIdPeserta;
		echo $psn."-".$err."-".$encIdPeserta;
	}
	public function Lembar_Soal()
	{
		// $key = decode($this->uri->segment(3));
		$key = newDecode($this->uri->segment(3));
		$data['id_data'] = $key;
		$data['dt'] = $this->model_pendaftaran->get_data_peseta($key);
		$data['soal'] = $this->model_pendaftaran->get_soal1($key);
		if(count($data['dt']) > 0 ){
			$data['jawaban'] = $this->model_pendaftaran->check_psrt_detail($data['dt']->IDPSRT, $data['dt']->KODESOAL);
			$data['dt_d'] = $this->model_pendaftaran->get_soal($data['dt']->KODESOAL);
			$data['psrtDetail'] = $this->model_pendaftaran->check_psrt_detail($data['dt']->IDPSRT, $data['dt']->KODESOAL);
		}
		date_default_timezone_set("Asia/Makassar");
		$date = date('Y-m-d H:i:s', time());
		$tglUjian = $data['dt']->TANGGAL;
		$jamUjian = $data['dt']->JAM_UJIAN;

		$different = $date >= $tglUjian." ".$jamUjian." ";

		if($data['dt']->STATUS == 1){
			if($different){
				$this->load->view("registration/lembar_soal", $data);
			}else{
				// redirect("Ujian/halaman_tunggu");
			}
		}else{
			// redirect("Ujian");
		}
		//$this->_init();
	}
	public function Lihat_Pilihan()
	{
		$key = $this->uri->segment(3);
		$id_peserta = $this->uri->segment(4);
		$nom_soal = $this->uri->segment(5);
		$data['dt_pilihan'] = $this->model_pendaftaran->show_detail_soal($key);
		$data['id_peserta'] = $id_peserta;
		$data['nom_soal'] = $nom_soal;
		$this->load->view("registration/isi_soal", $data);
	}
	public function Simpan_jawaban()
	{
		$kd_soal = $this->input->post('kd_soal');
		$id_peserta = $this->input->post('id_peserta');
		$jawaban = $this->input->post('jwb');
		// echo $jawaban;
		$cek_data = $this->model_pendaftaran->check_psrt_detail($id_peserta, $kd_soal);
		$jawab = "";
		if(count($cek_data) > 0){
			$jawab = $cek_data[0]->JAWAB;
			if($jawab == ""){
				$data['JAWAB'] = $jawaban;
				// echo $jawaban;
				$this->model_pendaftaran->update_jawaban($id_peserta, $kd_soal, $data);
			}else{
				$jwbDbArr = explode(',', $jawab);
				$jawabanArr = explode('=', $jawaban);
				$no = 1;
				$newData = "";
				$isDuplicate = false;
				foreach($jwbDbArr as $a){
					$jwbDetail = explode('=', $a);
					if($jwbDetail[0] == $jawabanArr[0]){
						$isDuplicate = true;
						if($no == count($jwbDbArr)){
							$newData = $newData.$jawaban;
						}else{
							$newData = $newData.$jawaban.',';
						}
					}else{
						if($no == count($jwbDbArr)){
							$newData = $newData.$a;
						}else{
							$newData = $newData.$a.',';
						}
					}
					$no++;
				}
				if(!$isDuplicate){
					$newData = $newData.','.$jawaban;
				}
				$data['JAWAB'] = $newData;
				$this->model_pendaftaran->update_jawaban($id_peserta, $kd_soal, $data);
			}
		}

		// $cek_data = $this->model_pendaftaran->cek_soal($id_peserta, $kd_soal, $id_soal);
		// if($cek_data==0)
		// {
		// 	$data['IDPSRT'] = $id_peserta;
		// 	$data['KDSOAL'] = $kd_soal;
		// 	$data['IDSOAL'] = $id_soal;
		// 	$data['JAWAB'] = $jawaban;
		// 	$this->model_pendaftaran->insert_data($data);
		// }
		// else
		// {
		// 	$data['JAWAB'] = $jawaban;
		// 	$this->model_pendaftaran->update_data($id_peserta, $kd_soal, $id_soal, $data);
		// }
	}

	// BARU
	public function Cek_hasil(){
		$idPeserta = $this->input->post('idp');
		$kodeSoal = $this->input->post('kds');
		$cek_hasil = $this->model_pendaftaran->cek_hasil($idPeserta);
		$jwb = $cek_hasil[0]->JAWAB;
		$jwbArr = explode(',', $jwb);

		$no = 0;
		$nilai = 0;
		$benar = 0;
		$salah = 0;
		$soal = $this->model_pendaftaran->get_soal($kodeSoal);
		$jumlahSoal = count($soal);
		$soalTerjawab = count($jwbArr);
		$soalTdkTerjawab = $jumlahSoal - $soalTerjawab;
		$idHead = $soal[0]['IDH'];

		$getSoalHead = $this->model_pendaftaran->get_soal_head($idHead);
		// var_dump($getSoalHead);
		$aMax = $getSoalHead->A_2;
		$aMin = $getSoalHead->A_1;
		$bMax = $getSoalHead->B_2;
		$bMin = $getSoalHead->B_1;
		$cMax = $getSoalHead->C_2;
		$cMin = $getSoalHead->C_1;
		$dMax = $getSoalHead->D_2;
		$dMin = $getSoalHead->D_1;

		foreach($soal as $d){
			foreach($jwbArr as $e){
				$jwbArrDetail = explode('=', $e);
				// var_dump($jwbArrDetail);
				if($jwbArrDetail[0] == $d['IDD']){
					if($jwbArrDetail[1] == $d['JAWABAN']){
						$benar = $benar + 1;
						// echo $benar;
					}else{
						$salah = $salah + 1;
						// echo $salah;
					}
				}
			}
			$no++;
		}

		date_default_timezone_set("Asia/Makassar");
		$nilai = ($benar / $jumlahSoal) * 100;
		$nilaiHuruf = "";

		if($nilai >= $aMin && $nilai <= $aMax){
			$nilaiHuruf = "A";
		}elseif($nilai >= $bMin && $nilai <= $bMax){
			$nilaiHuruf = "B";
		}elseif($nilai >= $cMin && $nilai <= $cMax){
			$nilaiHuruf = "C";
		}elseif($nilai >= $dMin && $nilai <= $dMax){
			$nilaiHuruf = "D";
		}

		$dataUjian['STATUS'] = 2;
		$dataUjian['NILAI'] = $nilai;
		$dataUjian['TANGGAL'] = date("Y-m-d");
		$updatePeserta = $this->model_pendaftaran->update_status_peserta($idPeserta, $dataUjian);

		echo $nilai."-".$soalTerjawab."-".$soalTdkTerjawab."-".$nilaiHuruf;
	}

	public function set_temp_soal(){
		$tempSoal = $this->input->post('data');
		$data['IDPSRT'] = $this->input->post('id_psrt');
		$data['IDSOAL'] = $tempSoal;
		$data['JAWAB'] = "";
		$data['KDSOAL'] = $this->input->post('kd_soal');
		$cPsrtDetail = $this->model_pendaftaran->check_psrt_detail($data['IDPSRT'], $data['KDSOAL']);
		echo count($cPsrtDetail);
		if(count($cPsrtDetail) == 0){
			$this->model_pendaftaran->set_psrt_detail($data);
		}
	}

	public function halaman_tunggu()
	{
		$this->_init();
		$this->load->view('registration/wait_page');
	}

}
function decode($key)
{
	$encoded = $key;   // <-- encoded string from the request
	$decoded = "";
	for( $i = 0; $i < strlen($encoded); $i++ ) {
	    $b = ord($encoded[$i]);
	    $a = $b ^ 123;  // <-- must be same number used to encode the character
	    $decoded .= chr($a);
	}
	return $decoded;
}

function newDecode($key){
	date_default_timezone_set("Asia/Makassar");
	$newKey = date("Y-m-d");
	$decIdRaw = rawurldecode($key);
	$decId64 = base64_decode($decIdRaw);
	$expId = explode('|', $decId64);
	if($expId[1] == $newKey){
		return $expId[0];
	}else{
		return 0;
	}
}

function newEncode($key){
	date_default_timezone_set("Asia/Makassar");
	$newKey = date("Y-m-d");
	$finalKey = $key."|".$newKey;
	$encId64 = base64_encode($finalKey);
	$encIdRaw = rawurlencode($encId64);
	return $encIdRaw;
}
