<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Model_pendaftaran extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	public function cek_kode_ujian($key)
	{
		$get_row = $this->db->query("Select * from cbt_jadwal_ujian where kode_soal='$key'");

		return $get_row;
	}
	public function cek_validasi_peserta($nim, $kode)
	{
		$cek_data = $this->db->select("a.*, b.nim, b.nama_mahasiswa")
				->from("cbt_peserta a")
				->from("3_1_biodata_mahasiswa b")
				->where("a.id_mahasiswa=b.id_mahasiswa")
				->where("b.nim", $nim)
				->where("a.kd_soal", $kode)
				->get()->row();

		//$cek_data = $this->db->query("select * from peserta_h where NIM='$nim' AND KODESOAL='$kode'");
		//$exec = $cek_data->result();
		//$jml_rows = $cek_data->num_rows();
		if(empty($cek_data==0)) //data belum ada
		{
			$ket=1;
		}
		else
		{
			$ket=2;
		}
		return $ket;
	}
	public function update_status_peserta($kdPeserta, $data){
		$this->db->where("IDPSRT", $kdPeserta);
		$query = $this->db->update("peserta_h", $data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	public function cek_status_peserta($nim, $kode)
	{
		$cek_data = $this->db->select("a.*, b.nim, b.nama_mahasiswa")
				->from("cbt_peserta a")
				->from("3_1_biodata_mahasiswa b")
				->where("a.id_mahasiswa=b.id_mahasiswa")
				->where("b.nim", $nim)
				->where("a.kd_soal", $kode)
				->get()->row();

		//$cek_data = $this->db->query("select STATUS from peserta_h where NIM='$nim' AND KODESOAL='$kode'");
		//$exec = $cek_data->result();

		return $cek_data;
	}
	public function insert_registrasi($data)
	{
		$this->db->insert("peserta_h", $data);
	}
	// public function update_status_peserta($id){
	// 	$this->db->query("UPDATE peserta_h SET STATUS = 2 WHERE IDPSRT = '$id'");
	// }
	public function get_id_peserta($nim, $kode)
	{
		$src_data = $this->db->query("Select * from peserta_h where NIM='$nim' AND KODESOAL='$kode'");
		$exec = $src_data->result();
		$row = $exec[0];
		return $row->IDPSRT;
	}
	public function get_data_peseta($key)
	{
		$src_data = $this->db->query("Select a.*, b.TANGGAL, b.WAKTU, b.WAKTUMULAI, b.JAM_UJIAN, c.MATKUL, d.* from peserta_h a, soal_head b, matakuliah c, perguruan_tinggi d where a.KODESOAL=b.KODESOAL AND b.IDPERTI=d.ID AND b.IDMATKUL=c.IDMK AND a.IDPSRT='$key'");
		$exec = $src_data->row();
		$row = $exec;
		return $row;
	}
	public function get_soal1($key){
		$query = $this->db->query("SELECT a.IDPSRT, a.KODESOAL, a.IDSOAL, b.* FROM peserta_h a LEFT JOIN soal_detail b ON a.IDSOAL = b.IDH WHERE a.IDPSRT = '$key'");
		return $query->result();
	}
	public function set_psrt_detail($data){
		$this->db->insert("peserta_d", $data);
	}
	public function check_psrt_detail($idPsrt, $kdSoal){
		$query = $this->db->query("SELECT * FROM peserta_d WHERE IDPSRT='$idPsrt' AND KDSOAL='$kdSoal'");
		return $query->result();
	}
	public function update_jawaban($id_peserta, $kd_soal, $data)
	{
		$this->db->where('IDPSRT', $id_peserta);
		$this->db->where('KDSOAL', $kd_soal);
		$this->db->update('peserta_d', $data);
	}
	public function get_soal($key)
	{
		$src_data = $this->db->query("select a.IDH, b.* from soal_head a, soal_detail b where a.IDH=b.IDH and a.KODESOAL='$key'");
		return $src_data->result_array();
	}
	public function get_soal_head($key){
		$query = $this->db->query("SELECT * FROM soal_head WHERE IDH='$key'");
		return $query->row();
	}
	public function show_detail_soal($key)
	{
		$list_data = $this->db->query("Select * from soal_detail where IDD='$key'");
		$exec = $list_data->result();
		$row = $exec[0];
		return $row;
	}
	public function cek_soal($id_peserta, $kd_soal, $id_soal)
	{
		$query_cek = $this->db->query("Select * from peserta_d where IDPSRT='$id_peserta' and KDSOAL='$kd_soal' and IDSOAL='$id_soal'");
		$jml_data = $query_cek->num_rows();
		return $jml_data;
	}
	public function insert_data($data)
	{
		$this->db->insert("peserta_d", $data);
	}
	public function update_data($id_peserta, $kd_soal, $id_soal, $data)
	{
		$this->db->where('IDPSRT', $id_peserta);
		$this->db->where('KDSOAL', $kd_soal);
		$this->db->where('IDSOAL', $id_soal);
		$this->db->update('peserta_d', $data);
	}
	public function cek_jawaban($id_soal, $id_peserta)
	{
		$query_cek = $this->db->query("Select * from peserta_d where IDSOAL='$id_soal' AND IDPSRT = '$id_peserta'");
		$jml_data = $query_cek->num_rows();
		if($jml_data==0)
		{
			$hsl="0";
		}
		else
		{
			$exec = $query_cek->result();
			$row = $exec[0];
			$hsl = $row->JAWAB;
		}
		return $hsl;
	}

	// BARU
	public function cek_hasil($id_peserta){
		$query = $this->db->query("SELECT a.JAWAB FROM peserta_d a WHERE a.IDPSRT ='$id_peserta'");

		return $query->result();
	}
}
