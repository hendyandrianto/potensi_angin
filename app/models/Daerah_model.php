<?php if ( ! defined('BASEPATH')) 
exit('No direct script access allowed');
class Daerah_model extends CI_Model { 
	function __construct(){ 
		parent::__construct(); 
	} /*==================================== data_daerah ============================================*/ 
	function data_daerah($aColumns, $sWhere, $sOrder, $sLimit){ 
		$query = $this->db->query("SELECT id,nama_daerah,alamat FROM ( SELECT a.*, CONCAT_WS('|', a.id) AS add_data FROM tbl_daerah a ) A 
			$sWhere 
			ORDER BY nama_daerah ASC
			$sLimit "); 
		return $query; 
		$query->free_result(); 
	} 
	function data_daerah_total($sIndexColumn){ 
		$query = $this->db->query(" SELECT $sIndexColumn FROM ( SELECT a.*, CONCAT_WS('|', a.id) AS add_data FROM tbl_daerah a ) A "); 
		return $query; 
	}
}