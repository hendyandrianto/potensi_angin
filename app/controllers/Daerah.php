<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Daerah extends CI_Controller {
	public function __construct(){
  		parent::__construct();
  		date_default_timezone_set('Asia/Jakarta');
  		$this->load->model('daerah_model');
 	}
	public function index(){
		$this->_content();
	}
	public function _content(){
		$isi['kelas'] = "ref_data";
		$isi['namamenu'] = "Data Daerah";
		$isi['page'] = "daerah";
		$isi['link'] = 'daerah';
		$isi['actionhapus'] = 'hapus';
		$isi['actionedit'] = 'edit';
		$isi['halaman'] = "Data Daerah Berpotensi";
		$isi['judul'] = "Halaman Data Daerah Berpotensi";
		$isi['content'] = "daerah_view";
		$this->load->view("dashboard_view",$isi);
	}
	public function get_data(){
        if($this->input->is_ajax_request()){
			$aColumns = array('id','nama_daerah','alamat','id');
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
	        $rResult = $this->daerah_model->data_daerah($aColumns, $sWhere, $sOrder, $sLimit);
	        $iFilteredTotal = 10;
	        $rResultTotal = $this->daerah_model->data_daerah_total($sIndexColumn);
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
		$isi['page'] = "daerah";
		$isi['link'] = 'daerah';
		$isi['cek'] = 'add';
		$isi['tombolsimpan'] = "Simpan";
		$isi['tombolbatal'] = "Batal";
		$isi['action'] = "proses_add";
		$isi['cek'] = "add";
		$isi['halaman'] = "Add Data Daerah Berpotensi";
		$isi['judul'] = "Halaman Add Data Daerah Berpotensi";
		$isi['option_propinsi'][''] = "Pilih Propinsi";
		$isi['option_kota'][''] = "Pilih Kab / Kota";
		$isi['option_kecamatan'][''] = "Pilih Kecamatan";
		$isi['option_desa'][''] = "Pilih Desa / Kelurahan";
		$ckpropinsi = $this->db->get('tbl_propinsi')->result();
		if(count($ckpropinsi)>0){
			foreach ($ckpropinsi as $row) {
				$isi['option_propinsi'][$row->id] = $row->name;
			}
		}else{
			$isi['option_propinsi'][''] = "Data Propinsi Belum Tersedia";
		}
		$isi['content'] = "form_daerah";
		$this->load->view("dashboard_view",$isi);
	}
	public function getKabkot($kode){
        if($this->input->is_ajax_request()){
			$return = "";
			$kodex = $this->angin->anti($kode);
			$data = $this->db->query("SELECT * FROM tbl_kabupaten WHERE province_Id = '$kodex'")->result();
			if(count($data)>0){
				$return = "<option value=''class=\"form-control selectpicker\" data-size=\"100\" id=\"kelurahan\" data-parsley-required=\"true\" data-live-search=\"true\" data-style=\"btn-white\"> Pilih Kab/Kota </option>";
				foreach ($data as $key) {
					$return .= '<option class="form-control selectpicker" data-size="100" id="kelurahan" data-parsley-required="true" data-live-search="true" data-style="btn-white" value="' .$key->id.'">' . $key->name . '</option>';
				}
			}else{
				$return .= '<option class="form-control selectpicker" data-size="100" id="kelurahan" data-parsley-required="true" data-live-search="true" data-style="btn-white" value="">Data Kelurahan Tidak Ditemukan</option>';
			}
			print $return;
		}else{
			redirect("_404","refresh");
		}
	}
	public function getKec($kode){
        if($this->input->is_ajax_request()){
			$return = "";
			$kodex = $this->angin->anti($kode);
			$data = $this->db->query("SELECT * FROM tbl_kecamatan WHERE regency_id = '$kodex'")->result();
			if(count($data)>0){
				$return = "<option value=''class=\"form-control selectpicker\" data-size=\"100\" id=\"kecamatan\" data-parsley-required=\"true\" data-live-search=\"true\" data-style=\"btn-white\"> Pilih Kecamatan </option>";
				foreach ($data as $key) {
					$return .= '<option class="form-control selectpicker" data-size="100" id="kecamatan" data-parsley-required="true" data-live-search="true" data-style="btn-white" value="' .$key->id.'">' . $key->name . '</option>';
				}
			}else{
				$return .= '<option class="form-control selectpicker" data-size="100" id="kecamatan" data-parsley-required="true" data-live-search="true" data-style="btn-white" value="">Data Kelurahan Tidak Ditemukan</option>';
			}
			print $return;
		}else{
			redirect("_404","refresh");
		}
	}
	public function getKel($kode){
        if($this->input->is_ajax_request()){
			$return = "";
			$kodex = $this->angin->anti($kode);
			$data = $this->db->query("SELECT * FROM tbl_kelurahan WHERE district_id = '$kodex'")->result();
			if(count($data)>0){
				$return = "<option value=''class=\"form-control selectpicker\" data-size=\"100\" id=\"kelurahan\" data-parsley-required=\"true\" data-live-search=\"true\" data-style=\"btn-white\"> Pilih Kelurahan </option>";
				foreach ($data as $key) {
					$return .= '<option class="form-control selectpicker" data-size="100" id="kelurahan" data-parsley-required="true" data-live-search="true" data-style="btn-white" value="' .$key->id.'">' . $key->name . '</option>';
				}
			}else{
				$return .= '<option class="form-control selectpicker" data-size="100" id="kelurahan" data-parsley-required="true" data-live-search="true" data-style="btn-white" value="">Data Kelurahan Tidak Ditemukan</option>';
			}
			print $return;
		}else{
			redirect("_404","refresh");
		}
	}
	public function proses_add(){
		$this->form_validation->set_rules('daerah', 'nama daerah', 'htmlspecialchars|trim|required|min_length[1]|max_length[50]|is_unique[tbl_daerah.nama_daerah]');
		$this->form_validation->set_rules('propinsi', 'provinsi', 'htmlspecialchars|trim|required|min_length[1]');
		$this->form_validation->set_rules('kota', 'kota', 'htmlspecialchars|trim|required|min_length[1]');
		$this->form_validation->set_rules('kecamatan', 'kecamatan', 'htmlspecialchars|trim|required|min_length[1]');
		$this->form_validation->set_rules('desa', 'desa', 'htmlspecialchars|trim|required|min_length[1]');
		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$nama = $this->angin->anti($this->input->post('daerah'));
			$propinsi = $this->angin->anti($this->input->post('propinsi'));
			$kota = $this->angin->anti($this->input->post('kota'));
			$kecamatan = $this->angin->anti($this->input->post('kecamatan'));
			$kelurahan = $this->angin->anti($this->input->post('desa'));
			$lat = $this->angin->anti($this->input->post('lintangO'));
			$lon = $this->angin->anti($this->input->post('bujurO'));
			$alamat = $this->angin->anti($this->input->post('alamatbaru'));
			$simpan = array('nama_daerah'=>$nama,
				'alamat'=>$alamat,
				'provinsi'=>$propinsi,
				'kota'=>$kota,
				'kecamatan'=>$kecamatan,
				'kelurahan'=>$kelurahan,
				'lon'=>$lon,
				'lat'=>$lat);
			$this->db->insert('tbl_daerah',$simpan);
			redirect('daerah','refresh');
        }else{
            $this->add();
        }
	}
	public function hapuscuaca($kode=NULL){
		$this->db->where('id_daerah',$kode);
		$this->db->delete('tbl_cuaca');
	}
	public function hapus($kode=Null){
        if($this->input->is_ajax_request()){
        	$this->db->hapuscuaca($kode);
			$this->db->where('id',$kode);
			if($this->db->delete('tbl_daerah')){
				$data['say'] = "ok";
			}else{
				$data['say'] = "NotOk";
			}
		}else{
			redirect("error","refresh");
		}
	}
	public function cekdata($kode=Null){
        if($this->input->is_ajax_request()){
			$ckdata = $this->db->get_where('tbl_daerah',array('id'=>$this->angin->anti($kode)))->result();
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
		$ckdata = $this->db->get_where('view_daerah',array('id'=>$this->angin->anti($kode)));
		if(count($ckdata->result())>0){
			$this->session->set_userdata('idna',$kode);
			$row = $ckdata->row();
			$isi['default']['daerah'] = $row->nama_daerah;
			$isi['default']['lintangO'] = $row->lat;
			$isi['default']['bujurO'] = $row->lon;
			$isi['default']['alamatbaru'] = $row->alamat;
			$isi['option_propinsi'][$row->provinsi] = $row->nama_provinsi;
			$ckprov = $this->db->get_where('tbl_propinsi',array('id'=>$row->provinsi));
			if(count($ckprov->result())>0){
				$xxxx = $ckprov->row();
				$name = $xxxx->name;
			}else{
				$name = "Provinsi Tidak Tersedia";
			}
			$isi['option_propinsi'][$row->provinsi] = $name;
			$ckkota = $this->db->get_where('tbl_kabupaten',array('id'=>$row->kota));
			if(count($ckkota->result())>0){
				$sxxxx = $ckkota->row();
				$namekota = $sxxxx->name;
			}else{
				$namekota = "Kab/Kota Tidak Tersedia";
			}
			$isi['option_kota'][$row->kota] = $namekota;
			$ckkec = $this->db->get_where('tbl_kecamatan',array('id'=>$row->kecamatan));
			if(count($ckkec->result())>0){
				$sxsxxx = $ckkec->row();
				$namekec = $sxsxxx->name;
			}else{
				$namekec = "Kecamatan Tidak Tersedia";
			}
			$isi['option_kecamatan'][$row->kecamatan] = $namekec;
			$ckkel = $this->db->get_where('tbl_kelurahan',array('id'=>$row->kelurahan));
			if(count($ckkel->result())>0){
				$sxsxxxx = $ckkel->row();
				$namekel = $sxsxxxx->name;
			}else{
				$namekel = "Kelurahan Tidak Tersedia";
			}
			$isi['option_desa'][$row->kelurahan] = $namekel;
			$skota = $this->db->get_where('tbl_kabupaten',array('province_id'=>$row->provinsi))->result();
			if(count($skota)>0){
				foreach ($skota as $key) {
					$isi['option_kota'][$key->id] = $key->name;
				}
			}else{
				$isi['option_kota'][""] = "";
			}
			$skec = $this->db->get_where('tbl_kecamatan',array('regency_id'=>$row->kota))->result();
			if(count($skec)>0){
				foreach ($skec as $key) {
					$isi['option_kecamatan'][$key->id] = $key->name;
				}
			}else{
				$isi['option_kecamatan'][""] = "";
			}
			$skel = $this->db->get_where('tbl_kelurahan',array('district_id'=>$row->kecamatan))->result();
			if(count($skel)>0){
				foreach ($skel as $key) {
					$isi['option_desa'][$key->id] = $key->name;
				}
			}else{
				$isi['option_desa'][""] = "";
			}
			$ckpropinsi = $this->db->get('tbl_propinsi')->result();
			if(count($ckpropinsi)>0){
				foreach ($ckpropinsi as $row) {
					$isi['option_propinsi'][$row->id] = $row->name;
				}
			}else{
				$isi['option_propinsi'][''] = "Data Propinsi Belum Tersedia";
			}
			$isi['cek'] = 'edit';
			$isi['kelas'] = "ref_data";
			$isi['namamenu'] = "Data Wilayah";
			$isi['page'] = "daerah";
			$isi['link'] = 'daerah';
			$isi['tombolsimpan'] = "Edit";
			$isi['tombolbatal'] = "Batal";
			$isi['action'] = "../proses_edit";
			$isi['halaman'] = "Edit Data Daerah Berpotensi";
			$isi['judul'] = "Halaman Edit Data Daerah Berpotensi";
			$isi['content'] = "form_daerah";
			$this->load->view("dashboard_view",$isi);
		}else{
			redirect('error','refresh');
		}
	}
	public function proses_edit(){
		$this->form_validation->set_rules('daerah', 'nama daerah', 'htmlspecialchars|trim|required|min_length[1]|max_length[50]');
		$this->form_validation->set_rules('propinsi', 'provinsi', 'htmlspecialchars|trim|required|min_length[1]');
		$this->form_validation->set_rules('kota', 'kota', 'htmlspecialchars|trim|required|min_length[1]');
		$this->form_validation->set_rules('kecamatan', 'kecamatan', 'htmlspecialchars|trim|required|min_length[1]');
		$this->form_validation->set_rules('desa', 'desa', 'htmlspecialchars|trim|required|min_length[1]');
		$this->form_validation->set_message('is_unique', '%s sudah ada sebelumnya');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		$this->form_validation->set_message('min_length', '%s minimal %s karakter');
		$this->form_validation->set_message('max_length', '%s maximal %s karakter');
		if ($this->form_validation->run() == TRUE){
			$nama = $this->angin->anti($this->input->post('daerah'));
			$propinsi = $this->angin->anti($this->input->post('propinsi'));
			$kota = $this->angin->anti($this->input->post('kota'));
			$kecamatan = $this->angin->anti($this->input->post('kecamatan'));
			$kelurahan = $this->angin->anti($this->input->post('desa'));
			$lat = $this->angin->anti($this->input->post('lintangO'));
			$lon = $this->angin->anti($this->input->post('bujurO'));
			$alamat = $this->angin->anti($this->input->post('alamatbaru'));
			$simpan = array('nama_daerah'=>$nama,
				'alamat'=>$alamat,
				'provinsi'=>$propinsi,
				'kota'=>$kota,
				'kecamatan'=>$kecamatan,
				'kelurahan'=>$kelurahan,
				'lon'=>$lon,
				'lat'=>$lat);
			$this->db->where('id',$this->session->userdata('idna'));
			$this->db->update('tbl_daerah',$simpan);
			$this->session->unset_userdata('idna');
			redirect('daerah','refresh');
        }else{
        	redirect('daerah/edit/'.$this->session->userdata('idna'),'refresh');
        }
	}
	public function detil($id=NULL){
		$isi['kelas'] = "ref_data";
		$isi['namamenu'] = "Data Daerah";
		$isi['page'] = "daerah";
		$isi['id'] = $id;
		$this->load->library('googlemaps');
		$config['zoom'] = 'auto';
		$this->googlemaps->initialize($config);
		$ckdata = $this->db->query("SELECT * FROM view_cuaca WHERE id = '$id' ORDER BY tanggal,waktu DESC ");
		if(count($ckdata->result())>0){
			$row = $ckdata->row();
			$isi['daerah'] = $row->nama_daerah;
			$marker = array();
			$lat = $row->lat;
			$lon = $row->lon;
			$marker['position'] = $lat . "," . $lon;
			$marker['infowindow_content'] = "Nama Daerah : <b>" . $row->nama_daerah. "</b><br/>" . "Informasi Cuaca Terakhir : <b>" . $row->cuaca . "</b><br/>" . "Alamat Lengkap : " . $row->alamat . "<br/>" . "<div><img width='154' height='155' src='".base_url()."images/icon/$row->foto'></div>";
			$marker['icon'] = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
			$marker['image'] = "http://localhost/angin/images/icon/$row->foto";
			$this->googlemaps->add_marker($marker);
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

			$kelembaban = $this->db->query("SELECT IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=1 GROUP BY a.tanggal),0) AS '01:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=2 GROUP BY a.tanggal),0) AS '02:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=3 GROUP BY a.tanggal),0) AS '03:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=4 GROUP BY a.tanggal),0) AS '04:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=5 GROUP BY a.tanggal),0) AS '05:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=6 GROUP BY a.tanggal),0) AS '06:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=7 GROUP BY a.tanggal),0) AS '07:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=8 GROUP BY a.tanggal),0) AS '08:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=9 GROUP BY a.tanggal),0) AS '09:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=10 GROUP BY a.tanggal),0) AS '10:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=11 GROUP BY a.tanggal),0) AS '11:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=12 GROUP BY a.tanggal),0) AS '12:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=13 GROUP BY a.tanggal),0) AS '13:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=14 GROUP BY a.tanggal),0) AS '14:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=15 GROUP BY a.tanggal),0) AS '15:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=16 GROUP BY a.tanggal),0) AS '16:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=17 GROUP BY a.tanggal),0) AS '17:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=18 GROUP BY a.tanggal),0) AS '18:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=19 GROUP BY a.tanggal),0) AS '19:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=20 GROUP BY a.tanggal),0) AS '20:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=21 GROUP BY a.tanggal),0) AS '21:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=22 GROUP BY a.tanggal),0) AS '22:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=23 GROUP BY a.tanggal),0) AS '23:00',IFNULL((SELECT a.kelembaban FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=24 GROUP BY a.tanggal),0) AS '24:00' FROM view_cuaca d WHERE d.tanggal = '$tgl' GROUP BY d.tanggal")->result_array();
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
					$isi['grafikkelembaban'][]=(float)$row['24:00'];
				}
			}else{
				$isi['grafikkelembaban']= "";			
			}
			$temperatur = $this->db->query("SELECT IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=1 GROUP BY a.tanggal),0) AS '01:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=2 GROUP BY a.tanggal),0) AS '02:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=3 GROUP BY a.tanggal),0) AS '03:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=4 GROUP BY a.tanggal),0) AS '04:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=5 GROUP BY a.tanggal),0) AS '05:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=6 GROUP BY a.tanggal),0) AS '06:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=7 GROUP BY a.tanggal),0) AS '07:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=8 GROUP BY a.tanggal),0) AS '08:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=9 GROUP BY a.tanggal),0) AS '09:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=10 GROUP BY a.tanggal),0) AS '10:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=11 GROUP BY a.tanggal),0) AS '11:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=12 GROUP BY a.tanggal),0) AS '12:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=13 GROUP BY a.tanggal),0) AS '13:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=14 GROUP BY a.tanggal),0) AS '14:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=15 GROUP BY a.tanggal),0) AS '15:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=16 GROUP BY a.tanggal),0) AS '16:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=17 GROUP BY a.tanggal),0) AS '17:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=18 GROUP BY a.tanggal),0) AS '18:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=19 GROUP BY a.tanggal),0) AS '19:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=20 GROUP BY a.tanggal),0) AS '20:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=21 GROUP BY a.tanggal),0) AS '21:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=22 GROUP BY a.tanggal),0) AS '22:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=23 GROUP BY a.tanggal),0) AS '23:00',IFNULL((SELECT a.temp_max FROM view_cuaca a WHERE a.id = '$id' AND a.tanggal = '$tgl' AND HOUR(a.waktu)=24 GROUP BY a.tanggal),0) AS '24:00' FROM view_cuaca d WHERE d.tanggal = '$tgl' GROUP BY d.tanggal")->result_array();
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
					$isi['grafiktemperatur'][]=(float)$row['24:00'];
				}
			}else{
				$isi['grafiktemperatur']= "";			
			}
			$isi['map'] = $this->googlemaps->create_map();
			$this->session->set_userdata("idna",$id);
			$isi['link'] = 'daerah';
			$isi['actionhapus'] = 'hapus';
			$isi['actionedit'] = 'edit';
			$isi['halaman'] = "Daerah Berpotensi";
			$isi['judul'] = "Halaman Daerah Berpotensi";
			$isi['content'] = "detil_daerah";
			$this->load->view("dashboard_view",$isi);
		}else{
			redirect("_404","refresh");
		}
		
	}
	public function get_cuaca($now=NULL){
		$id = $this->session->userdata('idna');
		$datajam1 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '1' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam1->result())>0){
            $key = $datajam1->row();
            echo "<br>";
            echo "<b>1." . "&nbsp; Jam : 01:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>1. Data Tanggal Tersebut Pada Jam 01:00:00 Tidak Ditemukan</h5>";
        }
        $datajam2 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '2' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam2->result())>0){
            $key = $datajam2->row();
            echo "<br>";
            echo "<b>2." . "&nbsp; Jam : 02:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>2. Data Tanggal Tersebut Pada Jam 02:00:00 Tidak Ditemukan</h5>";
        }
        $datajam3 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '3' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam3->result())>0){
            $key = $datajam3->row();
            echo "<br>";
            echo "<b>3." . "&nbsp; Jam : 03:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>3. Data Tanggal Tersebut Pada Jam 03:00:00 Tidak Ditemukan</h5>";
        }
        $datajam4 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '4' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam4->result())>0){
            $key = $datajam4->row();
            echo "<br>";
            echo "<b>4." . "&nbsp; Jam : 04:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>4. Data Tanggal Tersebut Pada Jam 04:00:00 Tidak Ditemukan</h5>";
        }
        $datajam5 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '5' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam5->result())>0){
            $key = $datajam5->row();
            echo "<br>";
            echo "<b>5." . "&nbsp; Jam : 05:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>5. Data Tanggal Tersebut Pada Jam 05:00:00 Tidak Ditemukan</h5>";
        }
        $datajam6 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '6' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam6->result())>0){
            $key = $datajam6->row();
            echo "<br>";
            echo "<b>6." . "&nbsp; Jam : 06:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>6. Data Tanggal Tersebut Pada Jam 06:00:00 Tidak Ditemukan</h5>";
        }
        $datajam7 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '7' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam7->result())>0){
            $key = $datajam7->row();
            echo "<br>";
            echo "<b>7." . "&nbsp; Jam : 07:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>7. Data Tanggal Tersebut Pada Jam 07:00:00 Tidak Ditemukan</h5>";
        }
        $datajam8 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '8' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam8->result())>0){
            $key = $datajam8->row();
            echo "<br>";
            echo "<b>8." . "&nbsp; Jam : 08:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>8. Data Tanggal Tersebut Pada Jam 08:00:00 Tidak Ditemukan</h5>";
        }
        $datajam9 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '9' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam9->result())>0){
            $key = $datajam9->row();
            echo "<br>";
            echo "<b>9." . "&nbsp; Jam : 09:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>9. Data Tanggal Tersebut Pada Jam 09:00:00 Tidak Ditemukan</h5>";
        }
        $datajam10 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '10' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam10->result())>0){
            $key = $datajam10->row();
            echo "<br>";
            echo "<b>10." . "&nbsp; Jam : 10:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>10. Data Tanggal Tersebut Pada Jam 10:00:00 Tidak Ditemukan</h5>";
        }
        $datajam11 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '11' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam11->result())>0){
            $key = $datajam11->row();
            echo "<br>";
            echo "<b>11." . "&nbsp; Jam : 111:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>11. Data Tanggal Tersebut Pada Jam 11:00:00 Tidak Ditemukan</h5>";
        }
        $datajam12 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '12' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam12->result())>0){
            $key = $datajam12->row();
            echo "<br>";
            echo "<b>12." . "&nbsp; Jam : 12:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>12. Data Tanggal Tersebut Pada Jam 12:00:00 Tidak Ditemukan</h5>";
        }
        $datajam13 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '13' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam13->result())>0){
            $key = $datajam13->row();
            echo "<br>";
            echo "<b>13." . "&nbsp; Jam : 13:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>13. Data Tanggal Tersebut Pada Jam 13:00:00 Tidak Ditemukan</h5>";
        }
        $datajam14 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '14' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam14->result())>0){
            $key = $datajam14->row();
            echo "<br>";
            echo "<b>14." . "&nbsp; Jam : 14:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>14. Data Tanggal Tersebut Pada Jam 14:00:00 Tidak Ditemukan</h5>";
        }
        $datajam15 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '15' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam15->result())>0){
            $key = $datajam15->row();
            echo "<br>";
            echo "<b>15." . "&nbsp; Jam : 15:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>15. Data Tanggal Tersebut Pada Jam 15:00:00 Tidak Ditemukan</h5>";
        }
        $datajam16 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '16' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam16->result())>0){
            $key = $datajam16->row();
            echo "<br>";
            echo "<b>16." . "&nbsp; Jam : 16:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>16. Data Tanggal Tersebut Pada Jam 16:00:00 Tidak Ditemukan</h5>";
        }
        $datajam17 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '17' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam17->result())>0){
            $key = $datajam17->row();
            echo "<br>";
            echo "<b>17." . "&nbsp; Jam : 17:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>17. Data Tanggal Tersebut Pada Jam 17:00:00 Tidak Ditemukan</h5>";
        }
        $datajam18 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '18' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam18->result())>0){
            $key = $datajam18->row();
            echo "<br>";
            echo "<b>18." . "&nbsp; Jam : 18:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>18. Data Tanggal Tersebut Pada Jam 18:00:00 Tidak Ditemukan</h5>";
        }
        $datajam19 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '19' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam19->result())>0){
            $key = $datajam19->row();
            echo "<br>";
            echo "<b>19." . "&nbsp; Jam : 19:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>19. Data Tanggal Tersebut Pada Jam 19:00:00 Tidak Ditemukan</h5>";
        }
        $datajam20 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '20' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam20->result())>0){
            $key = $datajam20->row();
            echo "<br>";
            echo "<b>20." . "&nbsp; Jam : 20:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>20. Data Tanggal Tersebut Pada Jam 20:00:00 Tidak Ditemukan</h5>";
        }
        $datajam21 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '21' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam21->result())>0){
            $key = $datajam21->row();
            echo "<br>";
            echo "<b>21." . "&nbsp; Jam : 21:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>21. Data Tanggal Tersebut Pada Jam 21:00:00 Tidak Ditemukan</h5>";
        }
        $datajam22 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '22' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam22->result())>0){
            $key = $datajam22->row();
            echo "<br>";
            echo "<b>22." . "&nbsp; Jam : 22:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>22. Data Tanggal Tersebut Pada Jam 22:00:00 Tidak Ditemukan</h5>";
        }
        $datajam23 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '23' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam23->result())>0){
            $key = $datajam23->row();
            echo "<br>";
            echo "<b>23." . "&nbsp; Jam : 23:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>23. Data Tanggal Tersebut Pada Jam 23:00:00 Tidak Ditemukan</h5>";
        }
        $datajam24 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '24' GROUP BY id ORDER BY waktu DESC");
        if(count($datajam24->result())>0){
            $key = $datajam24->row();
            echo "<br>";
            echo "<b>24." . "&nbsp; Jam : 24:00:00</b>";
            echo "<br>";
            echo "Cuaca  <b>".$key->cuaca.' </b><br>';
            echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
            echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
            echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
            echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
            echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
            echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
            echo "<img src='".base_url()."images/icon/$key->foto'>";
        }else{
            echo "<h5>24. Data Tanggal Tersebut Pada Jam 24:00:00 Tidak Ditemukan</h5>";
        }
	}
}
