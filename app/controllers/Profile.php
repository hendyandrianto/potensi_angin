<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile extends CI_Controller {
	public function __construct(){
  		parent::__construct();
  		date_default_timezone_set('Asia/Jakarta');
 	}
	public function index(){
		$this->_content();
	}
	public function _content(){
		$ckdata = $this->db->get_where('tbl_user',array('username'=>$this->session->userdata('username')));
		if(count($ckdata->result())>0){
			$row = $ckdata->row();
			$isi['default']['nama'] = $row->nama;
			$isi['default']['username'] = $row->username;
			$isi['namamenu'] = "Data Profile";
			$isi['page'] = "profile";
			$isi['link'] = 'profile';
			$isi['halaman'] = "Data Profile";
			$isi['judul'] = "Halaman Data Profile";
			$isi['content'] = "profile_view";
			$this->load->view("dashboard_view",$isi);
		}else{
			redirect("_404","refresh");
		}
	}
	public function proses_edit(){
		$this->form_validation->set_rules('username', 'username', 'htmlspecialchars|trim|required|min_length[6]|max_length[50]');
		if($this->input->post('password')!=""){
			$this->form_validation->set_rules('password', 'password', 'htmlspecialchars|trim|required|min_length[6]');
		}
		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$username = $this->input->post('username');
			$nama = $this->input->post('nama');
			$password = $this->input->post('password');
			if($password!=""){
				$simpan = array('nama'=>$nama,
					'username'=>$username,
					'password'=>md5($password));
			}else{
				$simpan = array('nama'=>$nama,
					'username'=>$username);
			}
			$this->db->where('username',$this->session->userdata('username'));
			$this->db->update('tbl_user',$simpan);
			redirect('profile','refresh');
        }else{
        	$this->index();
        }
	}
}
