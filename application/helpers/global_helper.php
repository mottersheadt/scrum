<?php

    function debug( $arg )
    {
	echo '<h2>Debugging arg</h2>';
	echo '<pre>';
	print_r( $arg );
	echo '</pre>';
    }

    function filename_from_url( $url )
    {
	if( $url === null ) {
	    $log->warn( "No url was specified. Exiting with status false" );
	    return false;
	}

	$path				= parse_url( $url )['path'];
	$filename			= basename( $path );

	return $filename;
    }

    function starts_with( $str, $start )
    {
	return !strncmp($str, $start, strlen($start));
    }

    function ends_with( $str, $ends )
    {
	$length = strlen($ends);
	if ($length == 0) {
	    return true;
	}

	return (substr($str, -$length) === $ends);
    }

    function current_url( $path = null, $query = null )
    {
	$http			= 'http';
	$http		       .= isset( $_SERVER["HTTPS"])
	    ? "s://" : "://";
	$host			= $_SERVER["SERVER_NAME"];
	$host		       .= $_SERVER["SERVER_PORT"] != "80"
	    ? ":".$_SERVER["SERVER_PORT"] : "";

	$url			= $http.$host.$_SERVER["SCRIPT_NAME"];

	if( $path ) {
	    // If string doesn't start with / add it.
	    if( ! starts_with( $path, "/" ) ) {
		$path		= "/".$path;
	    }

	    $url	       .= $path;
	}
	else {
	    $url	       .= $_SERVER["PATH_INFO"];
	}

	if( ! $query === false ) {
	    $url		       .= $_SERVER["QUERY_STRING"]
		? "?".$_SERVER["QUERY_STRING"]
		: $query === true
		    ? "?"
		    : "";
	}
	
	return $url;
    }

    function go_back()
    {
	if(
	   isset( $_SERVER["HTTP_REFERER"] )
	   && ( $_SERVER["HTTP_REFERER"] != current_url() )
	   ) {
	    redirect( $_SERVER["HTTP_REFERER"] );
	} else {
	    redirect( base_url( 'index.php/inventory' ) );
	}
    }

?>