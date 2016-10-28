<?php if ( ! defined('BASEPATH')) 
exit('No direct script access allowed');
class Kelurahan_model extends CI_Model { 
	function __construct(){ 
		parent::__construct(); 
	} /*==================================== data_kelurahan ============================================*/ 
	function data_kelurahan($aColumns, $sWhere, $sOrder, $sLimit){ 
		$query = $this->db->query("SELECT kecamatan ,nama_provinsi,nama_kota,nama_kecamatan,nama_daerah FROM ( SELECT a.*, CONCAT_WS('|', a.kecamatan) AS add_data FROM view_kelurahan a ) A 
			$sWhere 
			$sOrder 
			$sLimit "); 
		return $query; 
		$query->free_result(); 
	} 
	function data_kelurahan_total($sIndexColumn){ 
		$query = $this->db->query(" SELECT $sIndexColumn FROM ( SELECT a.*, CONCAT_WS('|', a.kecamatan) AS add_data FROM view_kelurahan a ) A "); 
		return $query; 
	}
}