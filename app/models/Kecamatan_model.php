<?php if ( ! defined('BASEPATH')) 
exit('No direct script access allowed');
class Kecamatan_model extends CI_Model { 
	function __construct(){ 
		parent::__construct(); 
	} /*==================================== data_kecamatan ============================================*/ 
	function data_kecamatan($aColumns, $sWhere, $sOrder, $sLimit){ 
		$query = $this->db->query("SELECT id_kec,nama_pro,nama_kab,nama_kec FROM ( SELECT a.*, CONCAT_WS('|', a.id_kec) AS add_data FROM view_kecamatan a ) A 
			$sWhere 
			$sOrder 
			$sLimit "); 
		return $query; 
		$query->free_result(); 
	} 
	function data_kecamatan_total($sIndexColumn){ 
		$query = $this->db->query(" SELECT $sIndexColumn FROM ( SELECT a.*, CONCAT_WS('|', a.id_kec) AS add_data FROM view_kecamatan a ) A "); 
		return $query; 
	}
}