<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Generate extends CI_Controller {
	public function __construct(){
  		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
  		
 	}
	public function index(){
		$ckdata = $this->db->get('tbl_daerah')->result();
		if(count($ckdata)>0){
			foreach ($ckdata as $key) {
				$lon = $key->lon;
				$lat = $key->lat;
				if (!$data = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$lon."&appid=c53e5a0c56f87de67393af41b30c05aa&units=metric")) {
				    $error = error_get_last();
				    return false;
				} else { 
					$url="http://api.openweathermap.org/data/2.5/weather?lat=".$lat. "&lon=".$lon."&appid=c53e5a0c56f87de67393af41b30c05aa&units=metric";
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
		}else{

		}
	}
}
