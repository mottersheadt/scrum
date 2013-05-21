<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class MY_Log extends CI_Log {
    
    protected $_log_path;
    protected $_threshold	= 1;
    protected $_date_fmt	= 'Y-m-d H:i:s';
    protected $_enabled		= TRUE;
    protected $_levels = array(
			       'CRITICAL'	=> 0,
			       'ERROR'		=> 1,
			       'WARNING'	=> 2,
			       'INFO'		=> 3,
			       'DEBUG'		=> 4
			       );
    protected $_leveltext = array(
				  'CRITICAL'	=> "FATAL",
				  'ERROR'	=> "ERROR",
				  'WARNING'	=> "WARN",
				  'INFO'	=> "INFO",
				  'DEBUG'	=> "DEBUG"
				  );

    public function write_log($level = 'error', $msg, $php_error = FALSE)
    {
	if ($this->_enabled === FALSE) {
	    return FALSE;
	}
	
	$level = strtoupper($level);
	
	if ( ! isset($this->_levels[$level])
	     OR ($this->_levels[$level] > $this->_threshold) ) {
	    return FALSE;
	}
	
	$filepath = $this->_log_path.'log-'.date('Y-m-d').".".$this->_file_ext;
	$message  = '';
	
	if ( ! file_exists($filepath)) {
	    $message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
	}
	
	if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
	    return FALSE;
	}

	$level		= substr( $this->_leveltext[$level], 0, 5 );
	$time		= explode( " ", microtime() );

	$message .= date( "Y-m-d H:i:s" ) . substr( $time[0], 1, 7 )
	    . ' --> '	. $level
	    . ' '	. ( ( strlen($level) == 4 ) ? ' -' : '-')
	    . ' '	. $msg	. "\n";
	
	flock($fp, LOCK_EX);
	fwrite($fp, $message);
	flock($fp, LOCK_UN);
	fclose($fp);
	
	@chmod($filepath, FILE_WRITE_MODE);

	/* // e-Mail the really bad errors */
	/* if( in_array( $level, array( "ERROR", "FATAL" ) ) ) { */

	/*     $from		= "From: MMG Support <errors@majestyebooks.com>"; */
	/*     $to			= "matthew@webheroes.ca";  */

	/*     $subject		= substr( $message, 0, 50 ); */
	/*     $msg		= $message; */

	/*     mail( $to, $subject, $message, $from ); */
	/* } */
	
	return TRUE;
    }
}
