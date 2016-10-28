<?php if ( ! defined('BASEPATH')) 
exit('No direct script access allowed');
class Propinsi_model extends CI_Model { 
	function __construct(){ 
		parent::__construct(); 
	} /*==================================== data_propinsi ============================================*/ 
	function data_propinsi($aColumns, $sWhere, $sOrder, $sLimit){ 
		$query = $this->db->query(" SELECT Kode,Provinsi,kabkot,Kecamatan,desa FROM ( SELECT a.*, CONCAT_WS('|', a.Kode) AS add_data FROM view_statistic a ) A 
			$sWhere 
			$sOrder 
			$sLimit "); 
		return $query; 
		$query->free_result(); 
	} 
	function data_propinsi_total($sIndexColumn){ 
		$query = $this->db->query(" SELECT $sIndexColumn FROM ( SELECT a.*, CONCAT_WS('|', a.Kode) AS add_data FROM view_statistic a ) A "); 
		return $query; 
	}
}