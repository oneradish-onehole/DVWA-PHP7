<?php

$conn = mysqli_connect( $_DVWA[ 'db_server' ], $_DVWA[ 'db_user' ], $_DVWA[ 'db_password' ],$_DVWA['db_database']);


if (!function_exists('mysql_result')) {
    function mysql_result($result, $number, $field=0) {
        mysqli_data_seek($result, $number);
        $row = mysqli_fetch_array($result);
        return $row[$field];
    }
}

if( isset( $_GET[ 'Change' ] ) ) {
	// Checks to see where the request came from
    error_log($_SERVER['SERVER_NAME']);
	#if( preg_match( $_SERVER[ 'SERVER_NAME' ], $_SERVER[ 'HTTP_REFERER' ] ) ) {
	if( preg_match("/".$_SERVER[ 'SERVER_ADDR' ].'/', $_SERVER[ 'HTTP_REFERER' ] ) ) {
		// Get input
		$pass_new  = $_GET[ 'password_new' ];
		$pass_conf = $_GET[ 'password_conf' ];

		// Do the passwords match?
		if( $pass_new == $pass_conf ) {
			// They do!
			$pass_new = mysqli_real_escape_string($conn, $pass_new );
			$pass_new = md5( $pass_new );

			// Update the database
			$insert = "UPDATE `users` SET password = '$pass_new' WHERE user = '" . dvwaCurrentUser() . "';";
			$result = mysqli_query($conn,$insert ) or die( '<pre>' . mysqli_error($conn) . '</pre>' );

			// Feedback for the user
			$html .= "<pre>Password Changed.</pre>";
		}
		else {
			// Issue with passwords matching
			$html .= "<pre>Passwords did not match.</pre>";
		}
	}
	else {
		// Didn't come from a trusted source
		$html .= "<pre>That request didn't look correct.</pre>";
	}

	mysqli_close($conn);
}

?>
