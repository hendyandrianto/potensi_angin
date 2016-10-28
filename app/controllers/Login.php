<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	public function __construct(){
  		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
  		if($this->session->userdata('login')==TRUE){
  			redirect("dashboard","refresh");
  		}else{
  			return FALSE;
  		}
 	}
	public function index(){
		$this->load->view('login_view');
	}
	public function do_login(){
		$this->form_validation->set_rules('username', 'username', 'htmlspecialchars|trim|required|min_length[6]');
		$this->form_validation->set_rules('password', 'password', 'htmlspecialchars|trim|required|min_length[6]');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$username = strip_tags($this->input->post('username'));
			$pass = strip_tags(md5($this->input->post('password')));
			$ceklogin = $this->db->get_where('tbl_user',array('username'=>$username,
				'password'=>$pass));
			if(count($ceklogin->result())>0){
				$row = $ceklogin->row();
				$newdata = array('nama'=>$row->nama,
					'username'=>$row->username,
					'login'=>TRUE);
				$this->session->set_userdata($newdata);
				redirect("dashboard","refresh");
			}else{
				?>
				<script>
					alert("Username dan Password Tidak dikenal");
					window.location.href = '<?php echo base_url();?>login';
				</script>
				<?php
			}
        }else{
            $this->index();
        }
	}
	public function generate_cuaca(){
		$ckdata = $this->db->get('tbl_daerah')->result();
		if(count($ckdata)>0){
			foreach ($ckdata as $key) {
				$lon = $key->lon;
				$lat = $key->lat;
				if (!$data = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$lon."&appid=c53e5a0c56f87de67393af41b30c05aa")) {
				    $error = error_get_last();
				    return false;
				} else { 
					$url="http://api.openweathermap.org/data/2.5/weather?lat=".$lat. "&lon=".$lon."&appid=c53e5a0c56f87de67393af41b30c05aa";
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
			 		echo "<br>";
			 		echo "Kota / Provinsi <b>".$key->nama_daerah . "</b> Sedang Mengalami  <b>".$translate.' </b><br>';
				 	echo "Temperatur Maksimal .<b>".$data['main']['temp_max'] .'° </b><br>';
				 	echo "Temperatur Minimal .<b>".$data['main']['temp_min'] .'° </b><br>';
				 	echo "Tekanan Angin  .<b>".$data['main']['pressure'] .'° </b><br>';
				 	echo "Kecapatan Angin  .<b>".$data['wind']['speed'] .'° </b><br>'; 	
				 	echo "<img src='".base_url()."images/icon/$img'>";
				 	$simpandata = array('tanggal'=>date("Y-m-d"),
				 		'id_daerah'=>$key->id,
				 		'waktu'=>date("H:i:s"),
				 		'cuaca'=>$translate,
				 		'temp_max'=>$data['main']['temp_max'],
				 		'temp_min'=>$data['main']['temp_min'],
				 		'tekanan_angin'=>$data['main']['pressure'],
				 		'kecepatan_angin'=>$data['wind']['speed'],
				 		'foto'=>$img);
				 	$this->db->insert('tbl_cuaca',$simpandata);
				}
			}
		}
	}
}
