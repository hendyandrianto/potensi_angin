<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	public function __construct(){
  		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
  		if($this->session->userdata('login')==TRUE){
  			return TRUE;
  		}else{
  			redirect("login","refresh");
  		}
 	}
	public function index(){
		$isi['halaman'] = "Halaman Dashboard";
		$isi['content'] = "dashboard_content";
		$isi['link'] = "dashboard";
		$this->load->library('googlemaps');
		$config['zoom'] = 'auto';
		$this->googlemaps->initialize($config);
		$now = date("Y-m-d");
		$ckdata = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' GROUP BY nama_daerah ORDER BY tanggal,waktu DESC ")->result();
		// if(count($ckdata)>0){
		foreach ($ckdata as $row) {
			$marker = array();
			$lat = $row->lat;
			$lon = $row->lon;
			$marker['position'] = $lat . "," . $lon;
			$marker['infowindow_content'] = "Nama Daerah : <b>" . $row->nama_daerah. "</b><br/>" . "Informasi Cuaca Terakhir : <b>" . $row->cuaca . "</b><br/>" . "Temperatur : <b>" . substr($row->temperatur, 0,2) . " °C" . "</b><br/>Kecepatan Angin : <b>" . $row->kecepatan_angin . " m/s" . "</b><br/>" . "<div><img width='154' height='155' src='".base_url()."images/icon/$row->foto'></div>";
			$marker['icon'] = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
			$marker['image'] = "http://angin.coder-01.com/angin/images/icon/$row->foto";
			$this->googlemaps->add_marker($marker);
		}
		$isi['map'] = $this->googlemaps->create_map();
		// }
		$this->load->view('dashboard_view',$isi);
	}
	public function generate_cuaca(){
		$ckdata = $this->db->get('tbl_daerah')->result();
		if(count($ckdata)>0){
			foreach ($ckdata as $key) {
				$lon = $key->lon;
				$lat = $key->lat;
				// http://openweathermap.org
				if (!$data = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$lon."&appid=[API_KEY]&units=metric")) {
				    $error = error_get_last();
				    return false;
				} else { 
					$url="http://api.openweathermap.org/data/2.5/weather?lat=".$lat. "&lon=".$lon."&appid=[API_KEY]&units=metric";
					$json=$data;
			 		$data=json_decode($json,true);
			   		$img="na.png";
			 		$translate="none";
			 		if(!empty($data['weather'][0]['main'])){ 
				  		if(strtoupper($data['weather'][0]['main'])=="RAIN"){
				 			$img="hujan_sedang.gif";
				 			$translate="HUJAN";
				 		}
				 		if(strtoupper($data['weather'][0]['main'])=="CLOUDS"){
				 			$img="berawan_cerah.gif";
				 			$translate="BERAWAN";
				 		}
				 		if(strtoupper($data['weather'][0]['main'])=="CLEAR"){
				 			$img="32.png";
				 			$translate="CERAH";
				 		}
				 		if(strtoupper($data['weather'][0]['main'])=="THUNDERSTORM"){
				 			$img="0.png";
				 			$translate="BADAI PETIR";
				 		}
				 		if(strtoupper($data['weather'][0]['main'])=="SHOWER RAIN"){
				 			$img="hujan_ringan.gif";
				 			$translate="HUJAN KECIL";
				 		}
				 		if(strtoupper($data['weather'][0]['main'])=="SCATERRED CLOUDS"){
				 			$img="25.png";
				 			$translate="CERAH";
				 		}
			 		}
				 	$simpandata = array('tanggal'=>date("Y-m-d"),
				 		'id_daerah'=>$key->id,
				 		'waktu'=>date("H:i:s"),
				 		'cuaca'=>$translate,
				 		'temperatur'=>$data['main']['temp'],
				 		'temp_max'=>$data['main']['temp_max'],
				 		'temp_min'=>$data['main']['temp_min'],
				 		'kelembaban'=>$data['main']['humidity'],
				 		'tekanan_angin'=>$data['main']['pressure'],
				 		'kecepatan_angin'=>$data['wind']['speed'],
				 		'foto'=>$img);
				 	$this->db->insert('tbl_cuaca',$simpandata);
				}
			}
		}
	}
	public function log_out(){
		$this->session->sess_destroy();
		redirect('login','refresh');
	}
}
