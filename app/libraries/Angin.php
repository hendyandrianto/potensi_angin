<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Angin{
   protected $CI;
   function __construct(){
      $this->CI =& get_instance();
   }
   
   function anti($inp) {
        if(is_array($inp))
            return array_map(__METHOD__, $inp);
        if(!empty($inp) && is_string($inp)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
        }
        return $inp;
   } 
}