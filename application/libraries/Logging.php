<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Logging {
    
    function __construct( $namespace = false ) {
	$this->CI		= get_instance();
	$this->namespace	= $namespace;
	$this->ns_width		= 70;
    }

    public function ns( $namespace ) {
	return new logging( $namespace );
    }

    private function clip_namespace( $namespace, $width ) {
	$clipped_ns		= trim( substr( $namespace, 0, $width-3 ) ). "...";
	return $clipped_ns;
    }

    private function message( $type, $args ) {

	$traceback		= debug_backtrace();

	if( ! $this->namespace ) {
	    $source		= $traceback[2];
	    $class		= isset( $source["class"] )
		? $source["class"] . "::"
		: "";
	    $func		= isset( $source["function"] )
		? $source["function"]
		: "";

	    $namespace		= $class . $func ."()";
	}

	$userid			= null;
	$level			= count( $traceback ) - 5;

	if( ! $userid ) {
	    $userid		= $this->CI->input->ip_address();
	}
	else {
	    $userid		= "userid: ". $userid;
	}
	if( ( $level-1 ) >= 0 ) {
	    $prefix		=  str_repeat( "    ", $level-1 ) . "`--" . " ";
	    $mprefix		=  str_repeat( "..", $level );
	}
	else {
	    $prefix		= "";
	    $mprefix		= "";
	}
	
	$message		= call_user_func_array( "sprintf", $args );
	$nsw			= $this->ns_width;

	$namespace		= strlen($namespace) > $nsw
	    ? $this->clip_namespace( $namespace, $nsw )
	    : $namespace;
	$message		= sprintf( "%-15s | %-".$nsw.".".$nsw."s | %s",
					   $userid, $prefix.$namespace, $mprefix.$message );

	$log_message		= log_message( $type, $message );

	return sprintf( "%-10s%s", "$type:", $message );
    }
    
    public function critical() {
	$type			= strtoupper( __FUNCTION__ );
	return $this->message( $type, func_get_args() );
    }

    public function error() {
	$type			= strtoupper( __FUNCTION__ );
	return $this->message( $type, func_get_args() );
    }
    
    public function warn() {
	$type			= "WARNING";
	return $this->message( $type, func_get_args() );
    }

    public function warning() {
	$type			= strtoupper( __FUNCTION__ );
	return $this->message( $type, func_get_args() );
    }

    public function debug() {
	$type			= strtoupper( __FUNCTION__ );
	return $this->message( $type, func_get_args() );
    }

    public function info() {
	$type			= strtoupper( __FUNCTION__ );
	return $this->message( $type, func_get_args() );
    }

}
