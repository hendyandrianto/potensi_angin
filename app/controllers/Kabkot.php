<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kabkot extends CI_Controller {
	public function __construct(){
  		parent::__construct();
  		date_default_timezone_set('Asia/Jakarta');
  		$this->load->model('kabupaten_model');
 	}
	public function index(){
		$this->_content();
	}
	public function _content(){
		$isi['kelas'] = "ref_data";
		$isi['namamenu'] = "Data Wilayah";
		$isi['page'] = "kabkot";
		$isi['link'] = 'kabkot';
		$isi['actionhapus'] = 'hapus';
		$isi['actionedit'] = 'edit';
		$isi['halaman'] = "Data Kab / Kota";
		$isi['judul'] = "Halaman Data Kab / Kota";
		$isi['content'] = "kabupaten_view";
		$this->load->view("dashboard_view",$isi);
	}
	public function get_data(){
        if($this->input->is_ajax_request()){
			$aColumns = array('id_kab','nama_pro','nama_kab','id_kab');
	        $sIndexColumn = "id_kab";
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
	        $rResult = $this->kabupaten_model->data_kabupaten($aColumns, $sWhere, $sOrder, $sLimit);
	        $iFilteredTotal = 10;
	        $rResultTotal = $this->kabupaten_model->data_kabupaten_total($sIndexColumn);
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
		$isi['namamenu'] = "Data Wilayah";
		$isi['page'] = "kabkot";
		$isi['link'] = 'kabkot';
		$isi['tombolsimpan'] = "Simpan";
		$isi['tombolbatal'] = "Batal";
		$isi['action'] = "proses_add";
		$isi['halaman'] = "Add Data Kab / Kota";
		$isi['judul'] = "Halaman Add Data Kab / Kota";
		$isi['option_propinsi'][''] = "Pilih Propinsi";
		$ckpropinsi = $this->db->get('tbl_propinsi')->result();
		if(count($ckpropinsi)>0){
			foreach ($ckpropinsi as $row) {
				$isi['option_propinsi'][$row->id] = $row->name;
			}
		}else{
			$isi['option_propinsi'][''] = "Data Propinsi Belum Tersedia";
		}
		$isi['content'] = "form_kabupaten";
		$this->load->view("dashboard_view",$isi);
	}
	public function proses_add(){
		$this->form_validation->set_rules('nama', 'nama kabupaten', 'htmlspecialchars|trim|required|min_length[1]|max_length[15]|is_unique[tbl_kabupaten.name]');
		$this->form_validation->set_rules('propinsi', 'pilih propinsi', 'htmlspecialchars|trim|required|min_length[1]|max_length[15]');
		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$nama = $this->angin->anti($this->input->post('nama'));
			$propinsi = $this->angin->anti($this->input->post('propinsi'));
			$kode = $this->angin->anti($this->session->userdata('kode'));
			$tgl = date("Y-m-d H:i:s");
			$simpan = array('province_Id'=>$propinsi,
				'name'=>$nama);
			$this->db->insert('tbl_kabupaten',$simpan);
			redirect('kabkot','refresh');
        }else{
            $this->add();
        }
	}
	public function hapus($kode=Null){
        if($this->input->is_ajax_request()){
			$ckdata = $this->db->get_where('tbl_kecamatan',array('regency_Id'=>$this->angin->anti($kode)))->result();
			if(count($ckdata)>0){
				$data['say'] = "NotOk";
			}else{
				$this->db->where('id',$kode);
				if($this->db->delete('tbl_kabupaten')){
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
			$ckdata = $this->db->get_where('tbl_kabupaten',array('id'=>$this->angin->anti($kode)))->result();
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
		$ckdata = $this->db->get_where('view_kabupaten',array('Id_kab'=>$this->angin->anti($kode)))->result();
		if(count($ckdata)>0){
			foreach ($ckdata as $key) {
				$isi['default']['nama'] = $key->nama_kab;
				$isi['option_propinsi'][$key->id_pro] = $key->nama_pro;
			}
			$this->session->set_userdata('idna',$kode);
			$ckpropinsi = $this->db->get('tbl_propinsi')->result();
			if(count($ckpropinsi)>0){
				foreach ($ckpropinsi as $row) {
					$isi['option_propinsi'][$row->id] = $row->name;
				}
			}else{
				$isi['option_propinsi'][''] = "Data Propinsi Belum Tersedia";
			}
			$isi['kelas'] = "ref_data";
			$isi['namamenu'] = "Data Wilayah";
			$isi['page'] = "kabkot";
			$isi['link'] = 'kabkot';
			$isi['tombolsimpan'] = "Edit";
			$isi['tombolbatal'] = "Batal";
			$isi['action'] = "../proses_edit";
			$isi['halaman'] = "Edit Data Kab / Kota";
			$isi['judul'] = "Halaman Edit Data Kab / Kota";
			$isi['content'] = "form_kabupaten";
			$this->load->view("dashboard_view",$isi);
		}else{
			redirect('error','refresh');
		}
	}
	public function proses_edit(){
		$this->form_validation->set_rules('nama', 'nama kabupaten', 'htmlspecialchars|trim|required|min_length[1]|max_length[15]');
		$this->form_validation->set_rules('propinsi', 'pilih propinsi', 'htmlspecialchars|trim|required|min_length[1]|max_length[15]');
		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$nama = $this->angin->anti($this->input->post('nama'));
			$propinsi = $this->angin->anti($this->input->post('propinsi'));
			$simpan = array('province_Id'=>$propinsi,
				'name'=>$nama);
			$this->db->where('Id',$this->session->userdata('idna'));
			$this->db->update('tbl_kabupaten',$simpan);
			$this->session->unset_userdata('idna');
			redirect('kabkot','refresh');
        }else{
        	redirect('kabkota/edit/'.$this->session->userdata('idna'),'refresh');
        }
	}
}
