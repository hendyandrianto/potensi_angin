<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller {
	public function __construct(){
  		parent::__construct();
  		date_default_timezone_set('Asia/Jakarta');
  		$this->load->model('user_model');
 	}
	public function index(){
		$this->_content();
	}
	public function _content(){
		$isi['kelas'] = "ref_data";
		$isi['namamenu'] = "Data User";
		$isi['page'] = "user";
		$isi['link'] = 'user';
		$isi['actionhapus'] = 'hapus';
		$isi['actionedit'] = 'edit';
		$isi['halaman'] = "Data User";
		$isi['judul'] = "Halaman Data User";
		$isi['content'] = "user_view";
		$this->load->view("dashboard_view",$isi);
	}
	public function get_data(){
        if($this->input->is_ajax_request()){
			$aColumns = array('id','nama','username','id');
	        $sIndexColumn = "id";
	        $sLimit = "";
	        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
	            $sLimit = "LIMIT ".$this->angin->anti( $_GET['iDisplayStart'] ).", ".
	                $this->angin->anti( $_GET['iDisplayLength'] );
	        }
	        $numbering = $this->angin->anti( $_GET['iDisplayStart'] );
	        $page = 1;
	        if ( isset( $_GET['iSortCol_0'] ) ){
	            $sOrder = "ORDER BY  ";
	            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
	                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
	                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
	                        ".$this->angin->anti( $_GET['sSortDir_'.$i] ) .", ";
	                }
	            }
	            $sOrder = substr_replace( $sOrder, "", -2 );
	            if ( $sOrder == "ORDER BY" ){
	                $sOrder = "";
	            }
	        }
	        $sWhere = "";
	        if ( $_GET['sSearch'] != "" ){
	            $sWhere = "WHERE (";
	            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
	                $sWhere .= $aColumns[$i]." LIKE '%".$this->angin->anti( $_GET['sSearch'] )."%' OR ";
	            }
	            $sWhere = substr_replace( $sWhere, "", -3 );
	            $sWhere .= ')';
	        }
	        for ( $i=0 ; $i<count($aColumns) ; $i++ ){
	            if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
	                if ( $sWhere == "" ){
	                    $sWhere = "WHERE ";
	                }
	                else{
	                    $sWhere .= " AND ";
	                }
	                $sWhere .= $aColumns[$i]." LIKE '%".$this->angin->anti($_GET['sSearch_'.$i])."%' ";
	            }
	        }
	        $rResult = $this->user_model->data_user($aColumns, $sWhere, $sOrder, $sLimit);
	        $iFilteredTotal = 10;
	        $rResultTotal = $this->user_model->data_user_total($sIndexColumn);
	        $iTotal = $rResultTotal->num_rows();
	        $iFilteredTotal = $iTotal;
	        $output = array(
	            "sEcho" => intval($_GET['sEcho']),
	            "iTotalRecords" => $iTotal,
	            "iTotalDisplayRecords" => $iFilteredTotal,
	            "aaData" => array()
	        );
	        foreach ($rResult->result_array() as $aRow){
	            $row = array();
	            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
	                if($i < 1)
	                    $row[] = $numbering+$page.'|'.$aRow[ $aColumns[$i] ];
	                else
	                    $row[] = $aRow[ $aColumns[$i] ];
	            }
	            $page++;
	            $output['aaData'][] = $row;
	        }
	        echo json_encode( $output );
	    }else{
	    	redirect("error","refresh");
	    }
	}
	public function add(){
		$isi['kelas'] = "ref_data";
		$isi['namamenu'] = "Data User";
		$isi['page'] = "user";
		$isi['link'] = 'user';
		$isi['cek'] = 'add';
		$isi['tombolsimpan'] = "Simpan";
		$isi['tombolbatal'] = "Batal";
		$isi['action'] = "proses_add";
		$isi['cek'] = "add";
		$isi['halaman'] = "Add Data User";
		$isi['judul'] = "Halaman Add Data User";
		$isi['content'] = "form_user";
		$this->load->view("dashboard_view",$isi);
	}
	public function proses_add(){
		$this->form_validation->set_rules('username', 'username', 'htmlspecialchars|trim|required|min_length[6]|max_length[50]|is_unique[tbl_user.username]');
		$this->form_validation->set_rules('password', 'password', 'htmlspecialchars|trim|required|min_length[6]|max_length[50]');
		$this->form_validation->set_rules('nama', 'nama user', 'htmlspecialchars|trim|required|min_length[6]|max_length[50]');

		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$nama = $this->angin->anti($this->input->post('nama'));
			$username = $this->angin->anti($this->input->post('username'));
			$password = $this->angin->anti($this->input->post('password'));
			$simpan = array('username'=>$username,
				'nama'=>$nama,
				'password'=>md5($password));
			$this->db->insert('tbl_user',$simpan);
			redirect('user','refresh');
        }else{
            $this->add();
        }
	}
	public function cekdata($kode=Null){
        if($this->input->is_ajax_request()){
			$ckdata = $this->db->get_where('tbl_user',array('id'=>$this->angin->anti($kode)))->result();
			if(count($ckdata)>0){
				$data['say'] = "ok";
			}else{
				$data['say'] = "NotOk";
			}
			if('IS_AJAX'){
			    echo json_encode($data);
			}
		}else{
			redirect("error","refresh");
		}
	}
	public function edit($kode=Null){
		$ckdata = $this->db->get_where('tbl_user',array('id'=>$kode));
		if(count($ckdata->result())>0){
			$row = $ckdata->row();
			$isi['default']['username'] = $row->username;
			$isi['default']['nama'] = $row->nama;
			$this->session->set_userdata('idna',$kode);
			$isi['cek'] = 'edit';
			$isi['kelas'] = "ref_data";
			$isi['namamenu'] = "Data User";
			$isi['page'] = "user";
			$isi['link'] = 'user';
			$isi['tombolsimpan'] = "Edit";
			$isi['tombolbatal'] = "Batal";
			$isi['action'] = "../proses_edit";
			$isi['halaman'] = "Edit Data User";
			$isi['judul'] = "Halaman Edit Data User";
			$isi['content'] = "form_user";
			$this->load->view("dashboard_view",$isi);
		}else{
			redirect('error','refresh');
		}
	}
	public function proses_edit(){
		$this->form_validation->set_rules('username', 'username', 'htmlspecialchars|trim|required|min_length[6]|max_length[50]');
		$this->form_validation->set_rules('password', 'password', 'htmlspecialchars|trim|required|min_length[6]|max_length[50]');
		$this->form_validation->set_rules('nama', 'nama user', 'htmlspecialchars|trim|required|min_length[6]|max_length[50]');

		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$nama = $this->angin->anti($this->input->post('nama'));
			$username = $this->angin->anti($this->input->post('username'));
			$password = $this->angin->anti($this->input->post('password'));
			$simpan = array('username'=>$username,
				'nama'=>$nama,
				'password'=>md5($password));
			$this->db->where('id',$this->session->userdata('idna'));
			$this->db->update('tbl_user',$simpan);
			$this->session->unset_userdata('idna');
			redirect('user','refresh');
        }else{
        	redirect('user/edit/'.$this->session->userdata('idna'),'refresh');
        }
	}
	
}
