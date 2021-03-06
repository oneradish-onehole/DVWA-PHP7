<?php
$conn = mysqli_connect( $_DVWA[ 'db_server' ], $_DVWA[ 'db_user' ], $_DVWA[ 'db_password' ],$_DVWA['db_database']);

if (!function_exists('mysql_result')) {
    function mysql_result($result, $number, $field=0) {
        mysqli_data_seek($result, $number);
        $row = mysqli_fetch_array($result);
        return $row[$field];
    }
}

if( isset( $_GET[ 'Login' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	// Sanitise username input
	$user = $_GET[ 'username' ];
	$user = stripslashes( $user );
	$user = mysqli_real_escape_string($conn,$user );

	// Sanitise password input
	$pass = $_GET[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = mysqli_real_escape_string($conn,$pass );
	$pass = md5( $pass );

	// Check database
	$query  = "SELECT * FROM `users` WHERE user = '$user' AND password = '$pass';";
	$result = mysqli_query($conn,$query ) or die( '<pre>' . mysql($conn) . '</pre>' );

	if( $result && mysqli_num_rows( $result ) == 1 ) {
		// Get users details
		$avatar = mysql_result( $result, 0, "avatar" );

		// Login successful
		$html .= "<p>Welcome to the password protected area {$user}</p>";
		$html .= "<img src=\"{$avatar}\" />";
	}
	else {
		// Login failed
		sleep( rand( 0, 3 ) );
		$html .= "<pre><br />Username and/or password incorrect.</pre>";
	}

	mysqli_close($conn);
}

// Generate Anti-CSRF token
generateSessionToken();

?>
