<?php

// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ait-pro.com for this  code 
// @since 2.1
// doesn't work when file is included by script :-(
/*
if ( !function_exists('add_action') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

if ( !current_user_can('manage_options') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}
*/
// 
//
echo "<h2>Send package to FTP site</h2>";

// set up variables
$host = get_option('snapshot_ftp_host');
$user = get_option('snapshot_ftp_user');
$pass = get_option('snapshot_ftp_pass');
$subdir = get_option('snapshot_ftp_subdir');
$remotefile = $subdir.'/'.$filename;
$localfile = WP_CONTENT_DIR .'/uploads/' . $filename;

// see if port option is blank and set it to 21 if it isn't
if (!get_option('snapshot_ftp_port')) {
	$port == '21';
} else {
$port = get_option('snapshot_ftp_port');
}
// extra security
// @since 2.1
// doesn't work when file is included by script :-(
// If in WP Dashboard or Admin Panels
// if ( is_admin() ) {
// If user has WP manage options permissions
// if ( current_user_can('manage_options')) {
// connect to host ONLY if the 2 security conditions are valid / met
$conn = ftp_connect($host,$port);
// }
// }

// @since 1.6
// new passive FTP connection to avoid timeouts
// thanks to Kara for this code ;-)

if (!$conn) {
  echo 'Could not connect to ftp server. This will be local backup.<br />';
}
else {
echo "Connected to $host.<br />";
// log in to host
$result = @ftp_login($conn, $user, $pass);
if (!$result) {
 echo "Could not log on as $user. This will be local backup.<br />";
}
else {
echo "Logged in as $user<br />";
// Switch to passive mode
ftp_pasv($conn, true);
// upload file
echo 'Uploading package to FTP repository...<br />';
if (!$success = ftp_put($conn, $remotefile, $localfile, FTP_BINARY)) {
 echo 'Error: Could not upload file. This will be local backup.<br />';
} 
else {
   echo 'File was uploaded successfully <br />';
             }
      }
}
// close connection to host
ftp_quit($conn);

// echo "... Done!";

?>