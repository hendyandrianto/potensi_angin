<?php if ( ! defined('BASEPATH')) 
exit('No direct script access allowed');
class Kabupaten_model extends CI_Model { 
	function __construct(){ 
		parent::__construct(); 
	} /*==================================== data_kabupaten ============================================*/ 
	function data_kabupaten($aColumns, $sWhere, $sOrder, $sLimit){ 
		$query = $this->db->query("SELECT id_kab,nama_pro,nama_kab FROM ( SELECT a.*, CONCAT_WS('|', a.id_kab) AS add_data FROM view_kabupaten a ) A 
			$sWhere 
			$sOrder 
			$sLimit "); 
		return $query; 
		$query->free_result(); 
	} 
	function data_kabupaten_total($sIndexColumn){ 
		$query = $this->db->query(" SELECT $sIndexColumn FROM ( SELECT a.*, CONCAT_WS('|', a.id_kab) AS add_data FROM view_kabupaten a ) A "); 
		return $query; 
	}
}