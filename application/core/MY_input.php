<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_input extends CI_Input {

    public function __construct()
    {
        parent::__construct();
    }

    public function request( $key = null, $xss = false ) {
	    
	$value			= $this->post( $key, $xss );
	    
	if( ! $value )
	    $value		= $this->get( $key, $xss );
	    
	return $value;
    }

}
