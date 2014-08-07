<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/smarty/Smarty.class.php');  
class CI_Smarty extends Smarty{  
        function __construct() {  
                parent::__construct();  
                $this->template_dir =  APPPATH."views";
                $this->compile_dir = APPPATH."views_c"; 
                $this->cache_dir = APPPATH."cache";  
                $this->caching = 0;   
                $this->debugging = false;  
                $this->compile_check = true; 
                $this->force_compile = true;
                //$this->allow_php_templates= true; 
                $this->left_delimiter = "{{";
		$this->right_delimiter = "}}";
       }  
}
