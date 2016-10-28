<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller {
	public function __construct(){
  		parent::__construct();
  		date_default_timezone_set('Asia/Jakarta');
 	}
 	public function get_tempat(){
		$response = array();
		$response["list_tempat"] = array();
        $ckdata = $this->db->query("SELECT a.id,a.nama_daerah,a.nama_provinsi,a.nama_kota,a.nama_kecamatan,b.name as nama_kelurahan,a.lon,a.lat,c.cuaca,c.temperatur,c.kecepatan_angin FROM view_daerah a JOIN tbl_kelurahan b ON a.kelurahan = b.id JOIN view_cuaca c ON a.id = c.id GROUP BY c.id ORDER BY a.nama_daerah ASC")->result_array();
        if(count($ckdata)>0){
			foreach ($ckdata as $row) {
				$h['id'] = $row['id'];
				$h['temperatur'] = $row['temperatur'] . " °C";
				$h['kecepatan'] = $row['kecepatan_angin'] . " m/s";
				$h['nama_daerah'] = $row['nama_daerah'];
				$h['provinsi']	= $row['nama_provinsi'];
				$h['kota'] = $row['nama_kota'];
				$h['kecamatan'] = $row['nama_kecamatan'];
				$h['cuaca'] = $row['cuaca'];
				$h['kelurahan'] = $row['nama_kelurahan'];
				$h['lon'] = $row['lon'];
				$h['lat'] = $row['lat'];
			 	array_push($response["list_tempat"], $h);
			}
			$response["success"] = "1";
		}else{
			$response["success"] = "2";
		}
		if('IS_AJAX'){
			echo json_encode($response);
		}  	
	}
 	public function list_daerah(){
		$response = array();
		$response["data_daerah"] = array();
		$sql=$this->db->query("SELECT a.id,a.nama_daerah,a.nama_provinsi,a.nama_kota,a.nama_kecamatan,b.name as nama_kelurahan,a.lon,a.lat FROM view_daerah a JOIN tbl_kelurahan b ON a.kelurahan = b.id ORDER BY a.nama_daerah ASC")->result_array();
		foreach ($sql as $row) {
			$h['id'] = $row['id'];
			$h['nama_daerah'] = $row['nama_daerah'];
			$h['provinsi']	= $row['nama_provinsi'];
			$h['kota'] = $row['nama_kota'];
			$h['kecamatan'] = $row['nama_kecamatan'];
			$h['kelurahan'] = $row['nama_kelurahan'];
			$h['lon'] = $row['lon'];
			$h['lat'] = $row['lat'];
		 	array_push($response["data_daerah"], $h);
			$response["success"] = "1";
		}
		if('IS_AJAX'){
			echo json_encode($response);
		}  	
	}
	public function get_daerah($id=NULL){
		$now = date("Y-m-d");
		$response = array();
		$response["data_daerah"] = array();
		$sql = $this->db->query("SELECT * FROM view_cuaca WHERE id = '$id' AND tanggal = '$now' GROUP BY HOUR(waktu) ORDER BY waktu ASC")->result_array();
		foreach ($sql as $row) {
			$h['kecepatan'] = $row['kecepatan_angin'];
			$h['id'] = $row['id'];
			$h['cuaca'] = $row['cuaca'];
			$h['temperatur']      = $row['temperatur'];
			$h['waktu']      = date("H",strtotime($row['waktu'])) . ":00";
			$h['foto'] = "http://192.168.43.123/angin/images/icon/" . $row['foto'];
		 	array_push($response["data_daerah"], $h);
			$response["success"] = "1";
			
		}
		if('IS_AJAX'){
			echo json_encode($response);
		}  	
	}
	public function get_grafik($id=NULL){
		$ckdata = $this->db->query("SELECT * FROM view_cuaca WHERE id = '$id' ORDER BY tanggal,waktu DESC ");
		if(count($ckdata->result())>0){
			$row = $ckdata->row();
			$tgl = date("Y-m-d");
			$hadir = $this->db->query("SELECT IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=1 GROUP BY a.tanggal),0) AS '01:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=2 GROUP BY a.tanggal),0) AS '02:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=3 GROUP BY a.tanggal),0) AS '03:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=4 GROUP BY a.tanggal),0) AS '04:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=5 GROUP BY a.tanggal),0) AS '05:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=6 GROUP BY a.tanggal),0) AS '06:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=7 GROUP BY a.tanggal),0) AS '07:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=8 GROUP BY a.tanggal),0) AS '08:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=9 GROUP BY a.tanggal),0) AS '09:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=10 GROUP BY a.tanggal),0) AS '10:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=11 GROUP BY a.tanggal),0) AS '11:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=12 GROUP BY a.tanggal),0) AS '12:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=13 GROUP BY a.tanggal),0) AS '13:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=14 GROUP BY a.tanggal),0) AS '14:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=15 GROUP BY a.tanggal),0) AS '15:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=16 GROUP BY a.tanggal),0) AS '16:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=17 GROUP BY a.tanggal),0) AS '17:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=18 GROUP BY a.tanggal),0) AS '18:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=19 GROUP BY a.tanggal),0) AS '19:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=20 GROUP BY a.tanggal),0) AS '20:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=21 GROUP BY a.tanggal),0) AS '21:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=22 GROUP BY a.tanggal),0) AS '22:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=23 GROUP BY a.tanggal),0) AS '23:00',IFNULL((SELECT a.kecepatan_angin FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=0 GROUP BY a.tanggal),0) AS '00:00' FROM view_cuaca d WHERE d.tanggal = '$tgl' GROUP BY d.tanggal")->result_array();
			if(count($hadir)>0){
				foreach ($hadir as $row) {
					$isi['grafikkecepatanangin'][]=(float)$row['01:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['02:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['03:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['04:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['05:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['06:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['07:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['08:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['09:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['10:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['11:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['12:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['13:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['14:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['15:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['16:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['17:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['18:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['19:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['20:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['21:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['22:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['23:00'];
					$isi['grafikkecepatanangin'][]=(float)$row['00:00'];
				}
			}else{
				$isi['grafikkecepatanangin']= "";			
			}

			$kelembaban = $this->db->query("SELECT IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=1 GROUP BY a.tanggal),0) AS '01:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=2 GROUP BY a.tanggal),0) AS '02:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=3 GROUP BY a.tanggal),0) AS '03:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=4 GROUP BY a.tanggal),0) AS '04:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=5 GROUP BY a.tanggal),0) AS '05:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=6 GROUP BY a.tanggal),0) AS '06:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=7 GROUP BY a.tanggal),0) AS '07:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=8 GROUP BY a.tanggal),0) AS '08:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=9 GROUP BY a.tanggal),0) AS '09:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=10 GROUP BY a.tanggal),0) AS '10:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=11 GROUP BY a.tanggal),0) AS '11:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=12 GROUP BY a.tanggal),0) AS '12:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=13 GROUP BY a.tanggal),0) AS '13:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=14 GROUP BY a.tanggal),0) AS '14:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=15 GROUP BY a.tanggal),0) AS '15:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=16 GROUP BY a.tanggal),0) AS '16:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=17 GROUP BY a.tanggal),0) AS '17:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=18 GROUP BY a.tanggal),0) AS '18:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=19 GROUP BY a.tanggal),0) AS '19:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=20 GROUP BY a.tanggal),0) AS '20:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=21 GROUP BY a.tanggal),0) AS '21:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=22 GROUP BY a.tanggal),0) AS '22:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=23 GROUP BY a.tanggal),0) AS '23:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=24 GROUP BY a.tanggal),0) AS '00' FROM view_cuaca d WHERE d.tanggal = '$tgl' GROUP BY d.tanggal")->result_array();
			if(count($kelembaban)>0){
				foreach ($kelembaban as $row) {
					$isi['grafikkelembaban'][]=(float)$row['01:00'];
					$isi['grafikkelembaban'][]=(float)$row['02:00'];
					$isi['grafikkelembaban'][]=(float)$row['03:00'];
					$isi['grafikkelembaban'][]=(float)$row['04:00'];
					$isi['grafikkelembaban'][]=(float)$row['05:00'];
					$isi['grafikkelembaban'][]=(float)$row['06:00'];
					$isi['grafikkelembaban'][]=(float)$row['07:00'];
					$isi['grafikkelembaban'][]=(float)$row['08:00'];
					$isi['grafikkelembaban'][]=(float)$row['09:00'];
					$isi['grafikkelembaban'][]=(float)$row['10:00'];
					$isi['grafikkelembaban'][]=(float)$row['11:00'];
					$isi['grafikkelembaban'][]=(float)$row['12:00'];
					$isi['grafikkelembaban'][]=(float)$row['13:00'];
					$isi['grafikkelembaban'][]=(float)$row['14:00'];
					$isi['grafikkelembaban'][]=(float)$row['15:00'];
					$isi['grafikkelembaban'][]=(float)$row['16:00'];
					$isi['grafikkelembaban'][]=(float)$row['17:00'];
					$isi['grafikkelembaban'][]=(float)$row['18:00'];
					$isi['grafikkelembaban'][]=(float)$row['19:00'];
					$isi['grafikkelembaban'][]=(float)$row['20:00'];
					$isi['grafikkelembaban'][]=(float)$row['21:00'];
					$isi['grafikkelembaban'][]=(float)$row['22:00'];
					$isi['grafikkelembaban'][]=(float)$row['23:00'];
					$isi['grafikkelembaban'][]=(float)$row['00'];
				}
			}else{
				$isi['grafikkelembaban']= "";			
			}
			$temperatur = $this->db->query("SELECT IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=1 GROUP BY a.tanggal),0) AS '01:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=2 GROUP BY a.tanggal),0) AS '02:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=3 GROUP BY a.tanggal),0) AS '03:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=4 GROUP BY a.tanggal),0) AS '04:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=5 GROUP BY a.tanggal),0) AS '05:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=6 GROUP BY a.tanggal),0) AS '06:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=7 GROUP BY a.tanggal),0) AS '07:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=8 GROUP BY a.tanggal),0) AS '08:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=9 GROUP BY a.tanggal),0) AS '09:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=10 GROUP BY a.tanggal),0) AS '10:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=11 GROUP BY a.tanggal),0) AS '11:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=12 GROUP BY a.tanggal),0) AS '12:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=13 GROUP BY a.tanggal),0) AS '13:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=14 GROUP BY a.tanggal),0) AS '14:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=15 GROUP BY a.tanggal),0) AS '15:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=16 GROUP BY a.tanggal),0) AS '16:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=17 GROUP BY a.tanggal),0) AS '17:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=18 GROUP BY a.tanggal),0) AS '18:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=19 GROUP BY a.tanggal),0) AS '19:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=20 GROUP BY a.tanggal),0) AS '20:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=21 GROUP BY a.tanggal),0) AS '21:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=22 GROUP BY a.tanggal),0) AS '22:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=23 GROUP BY a.tanggal),0) AS '23:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=24 GROUP BY a.tanggal),0) AS '00' FROM view_cuaca d WHERE d.tanggal = '$tgl' GROUP BY d.tanggal")->result_array();
			if(count($temperatur)>0){
				foreach ($temperatur as $row) {
					$isi['grafiktemperatur'][]=(float)$row['01:00'];
					$isi['grafiktemperatur'][]=(float)$row['02:00'];
					$isi['grafiktemperatur'][]=(float)$row['03:00'];
					$isi['grafiktemperatur'][]=(float)$row['04:00'];
					$isi['grafiktemperatur'][]=(float)$row['05:00'];
					$isi['grafiktemperatur'][]=(float)$row['06:00'];
					$isi['grafiktemperatur'][]=(float)$row['07:00'];
					$isi['grafiktemperatur'][]=(float)$row['08:00'];
					$isi['grafiktemperatur'][]=(float)$row['09:00'];
					$isi['grafiktemperatur'][]=(float)$row['10:00'];
					$isi['grafiktemperatur'][]=(float)$row['11:00'];
					$isi['grafiktemperatur'][]=(float)$row['12:00'];
					$isi['grafiktemperatur'][]=(float)$row['13:00'];
					$isi['grafiktemperatur'][]=(float)$row['14:00'];
					$isi['grafiktemperatur'][]=(float)$row['15:00'];
					$isi['grafiktemperatur'][]=(float)$row['16:00'];
					$isi['grafiktemperatur'][]=(float)$row['17:00'];
					$isi['grafiktemperatur'][]=(float)$row['18:00'];
					$isi['grafiktemperatur'][]=(float)$row['19:00'];
					$isi['grafiktemperatur'][]=(float)$row['20:00'];
					$isi['grafiktemperatur'][]=(float)$row['21:00'];
					$isi['grafiktemperatur'][]=(float)$row['22:00'];
					$isi['grafiktemperatur'][]=(float)$row['23:00'];
					$isi['grafiktemperatur'][]=(float)$row['00'];
				}
			}else{
				$isi['grafiktemperatur']= "";			
			}
			$this->load->view("grafik",$isi);
		}else{
			redirect("_404","refresh");
		}
		
	}
}
