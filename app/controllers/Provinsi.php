<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Provinsi extends CI_Controller {
	public function __construct(){
  		parent::__construct();
  		if($this->session->userdata('login')==TRUE){
	  		$this->load->model('propinsi_model');
  			return TRUE;
  		}else{
  			redirect("login","refresh");
  		}
 	}
	public function index(){
		$this->_content();
	}
	public function _content(){
		$isi['halaman'] = "Data Provinsi";
		$isi['content'] = "provinsi_view";
		$isi['link'] = "provinsi";
		$this->load->view('dashboard_view',$isi);
	}
	public function get_data(){
        if($this->input->is_ajax_request()){
			$aColumns = array('Kode','Provinsi','kabkot','Kecamatan','desa','Kode');
	        $sIndexColumn = "Kode";
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
	        $rResult = $this->propinsi_model->data_propinsi($aColumns, $sWhere, $sOrder, $sLimit);
	        $iFilteredTotal = 10;
	        $rResultTotal = $this->propinsi_model->data_propinsi_total($sIndexColumn);
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
		$isi['halaman'] = "Add Data Provinsi";
		$isi['link'] = 'provinsi';
		$isi['tombolsimpan'] = "Simpan";
		$isi['tombolbatal'] = "Batal";
		$isi['action'] = "proses_add";
		$isi['judul'] = "Halaman Add Data Propinsi";
		$isi['content'] = "form_propinsi";
		$this->load->view("dashboard_view",$isi);
	}
	public function proses_add(){
		$this->form_validation->set_rules('nama', 'nama propinsi', 'htmlspecialchars|trim|required|min_length[1]|max_length[15]|is_unique[tbl_propinsi.name]');
		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$nama = $this->angin->anti($this->input->post('nama'));
			$simpan = array('name'=>$nama);
			$this->db->insert('tbl_propinsi',$simpan);
			redirect('provinsi','refresh');
        }else{
            $this->add();
        }
	}
	public function hapus($kode=Null){
        if($this->input->is_ajax_request()){
			$ckdata = $this->db->get_where('tbl_kabupaten',array('province_Id'=>$this->angin->anti($kode)))->result();
			if(count($ckdata)>0){
				$data['say'] = "NotOk";
			}else{
				$this->db->where('Id',$kode);
				if($this->db->delete('tbl_propinsi')){
					$data['say'] = "ok";
				}else{
					$data['say'] = "NotOk";
				}
			}
			if('IS_AJAX'){
			    echo json_encode($data);
			}
		}else{
			redirect("error","refresh");
		}
	}
	public function cekdata($kode=Null){
        if($this->input->is_ajax_request()){
			$ckdata = $this->db->get_where('tbl_propinsi',array('Id'=>$this->angin->anti($kode)))->result();
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
		$ckdata = $this->db->get_where('tbl_propinsi',array('Id'=>$this->angin->anti($kode)))->result();
		if(count($ckdata)>0){
			foreach ($ckdata as $key) {
				$isi['default']['nama'] = $key->name;
			}
			$this->session->set_userdata('idna',$kode);
			$isi['link'] = 'propinsi';
			$isi['tombolsimpan'] = "Edit";
			$isi['tombolbatal'] = "Batal";
			$isi['action'] = "../proses_edit";
			$isi['halaman'] = "Edit Data Propinsi";
			$isi['content'] = "form_propinsi";
			$this->load->view("dashboard_view",$isi);
		}else{
			redirect('error','refresh');
		}
	}
	public function proses_edit(){
		$this->form_validation->set_rules('nama', 'nama propinsi', 'htmlspecialchars|trim|required|min_length[1]|max_length[15]');
		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$nama = $this->angin->anti($this->input->post('nama'));
			$kode = $this->angin->anti($this->session->userdata('kode'));
			$tgl = date("Y-m-d H:i:s");
			$edit = array('name'=>$nama);
			$this->db->where('Id',$this->session->userdata('idna'));
			$this->db->update('tbl_propinsi',$edit);
			$this->session->unset_userdata('idna');
			redirect('provinsi','refresh');
        }else{
        	redirect('provinsi/edit/'.$this->session->userdata('idna'),'refresh');
        }
	}
}
