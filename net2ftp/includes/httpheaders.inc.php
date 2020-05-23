<?php

//   -------------------------------------------------------------------------------
//  |                  net2ftp: a web based FTP client                              |
//  |                Copyright (c) 2003 by David Gartner                            |
//  |                                                                               |
//  | This program is free software; you can redistribute it and/or                 |
//  | modify it under the terms of the GNU General Public License                   |
//  | as published by the Free Software Foundation; either version 2                |
//  | of the License, or (at your option) any later version.                        |
//  |                                                                               |
//   -------------------------------------------------------------------------------



// -------------------------------------------------------------------------
// Don't cache any page
// -------------------------------------------------------------------------
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//header("Cache-Control: no-cache");
//header("Cache-Control: post-check=0,pre-check=0");
//header("Cache-Control: max-age=0");
//header("Pragma: no-cache");


// -------------------------------------------------------------------------
// Set cookie with login information; see printLoginForm()
// -------------------------------------------------------------------------

// 423upgrade
//$input_ftpserver = $_POST['input_ftpserver'];
//$input_username = $_POST['input_username'];

if ($cookiesetonlogin == "yes") {
	setcookie ("net2ftpcookie_ftpserver", $input_ftpserver, time()+60*60*24*30);
	setcookie ("net2ftpcookie_username", $input_username, time()+60*60*24*30);
}


// -------------------------------------------------------------------------
// Delete cookie with login information; see printLogoutForm()
// -------------------------------------------------------------------------
//if ($cookiedeleteonlogout == "yes") {
//	setcookie ("net2ftpcookie_password", "", time()+60*60*24*30);
//}


// -------------------------------------------------------------------------
// If user wants to download a file then nothing may be written because headers are sent
// -------------------------------------------------------------------------

// 423upgrade
//$state = $_POST['state'];
//$manage = $_POST['manage'];
//$directory = $_POST['directory'];
//$entry = $_POST['entry'];

if ($state == "manage" && $manage == "downloadfile") {
	ftp_downloadfile($directory, $entry);
	exit();
}

?>