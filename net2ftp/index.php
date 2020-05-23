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
//  | This program is distributed in the hope that it will be useful,               |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of                |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                 |
//  | GNU General Public License for more details.                                  |
//  |                                                                               |
//  | You should have received a copy of the GNU General Public License             |
//  | along with this program; if not, write to the Free Software                   |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA     |
//  |                                                                               |
//   -------------------------------------------------------------------------------


// -------------------------------------------------------------------------
// Run the script to the end, even if the user hits the stop button
// -------------------------------------------------------------------------
ignore_user_abort();


// -------------------------------------------------------------------------
// PHP 4.0.6 to 4.2.3 upgrade
// Variables
// -------------------------------------------------------------------------

/* 
// index.php
$input_ftpserver = $_POST['input_ftpserver'];
$input_ftpserverport = $_POST['input_ftpserverport'];
$input_username = $_POST['input_username'];
$input_password = $_POST['input_password'];
$net2ftp_password_encrypted = $_POST['net2ftp_password_encrypted'];

// Function printLoginForm
$net2ftpcookie_ftpserver = $_COOKIE['net2ftpcookie_ftpserver'];
$net2ftpcookie_ftpserverport = $_COOKIE['net2ftpcookie_ftpserverport'];
$net2ftpcookie_username = $_COOKIE['net2ftpcookie_username'];

// Function browse and manage
$state = $_POST['state'];
$manage = $_POST['manage'];
$directory = $_POST['directory'];
$entry = $_POST['entry'];
$selectedEntries = $_POST['selectedEntries'];
$newNames= $_POST['newNames'];
$dirorfile= $_POST['dirorfile'];
$formresult= $_POST['formresult'];
$chmodStrings = $_POST['chmodStrings'];
$targetDirectories = $_POST['targetDirectories'];
$copymovedelete = $_POST['copymovedelete'];
$text = $_POST['text'];
for ($i=1; $i<=7; $i=$i+1) {
	$uploadedFilesArray["$i"]["temppathname"] = $_POST['userfile$i'];
	$uploadedFilesArray["$i"]["name"]         = $_POST['userfile$i_name'];
	$uploadedFilesArray["$i"]["size"]         = $_POST['userfile$i_size'];
} // end for
for ($i=1; $i<=2; $i=$i+1) {
	$uploadedZipFilesArray["$i"]["temppathname"] = $_POST['userzipfile$i'];
	$uploadedZipFilesArray["$i"]["name"]         = $_POST['userzipfile$i_name'];
	$uploadedZipFilesArray["$i"]["size"]         = $_POST['userzipfile$i_size'];
} // end for
*/

// -------------------------------------------------------------------------
// Includes
// -------------------------------------------------------------------------
require_once("settings.inc.php");							// General parameters
require_once("layout_server.inc.php");						// net2ftp-specific parameters

require_once($application_rootdir . "/includes/html.inc.php");		// Functions
require_once($application_rootdir . "/includes/filesystem.inc.php");
require_once($application_rootdir . "/includes/errorhandling.inc.php");
require_once($application_rootdir . "/includes/database.inc.php");
require_once($application_rootdir . "/includes/authorizations.inc.php");
require_once($application_rootdir . "/includes/browse.inc.php");
require_once($application_rootdir . "/includes/manage.inc.php");

require_once($application_rootdir . "/includes/httpheaders.inc.php");	// Send HTTP headers

// -------------------------------------------------------------------------
// When the user logs in: clean the input and log the login
// Note: The logging can be activated or not activated, depending on a setting in the settings.inc.php file
// -------------------------------------------------------------------------	
if (strlen($input_ftpserver) > 1 && strlen($input_username) > 1 && strlen($input_password) > 1) {
	$net2ftp_ftpserver = cleanFtpserver($input_ftpserver);
	$net2ftp_ftpserverport = trim($input_ftpserverport);
	$net2ftp_username = trim($input_username);
	$net2ftp_password_encrypted = encryptPassword(trim($input_password));

	$resultArray = logLogin($input_ftpserver, $input_username);
	$result = getResult($resultArray);
	if ($result == false) { printErrorMessage($resultArray, "exit"); }
}

// -------------------------------------------------------------------------
// At each page request, check the authorization
// Note: This check can be performed or not performed, depending on a setting in the settings.inc.php file
// -------------------------------------------------------------------------	
	$resultArray = checkAuthorization($net2ftp_ftpserver, $net2ftp_username);
	$result = getResult($resultArray);
	if ($result == false) { printErrorMessage($resultArray, "exit"); }


// -------------------------------------------------------------------------
// Block the output to the browser
// If no errors occur, the page will be shown as usually, otherwise it will be replaced by a nice error message
// This way, no functions have to be called with a @ in front, and debugging is made easier
// -------------------------------------------------------------------------
ob_start();


// -------------------------------------------------------------------------
// Begin HTML output
// -------------------------------------------------------------------------
HtmlBegin($myname);


// -------------------------------------------------------------------------
// Set default state and directory if needed
// -------------------------------------------------------------------------
if (strlen($state) < 1) { $state= "printloginform"; }

if (strlen($directory) < 1) { $directory = ""; $printdirectory = "/"; }
else { $directory = cleanDirectory($directory); }


// ------------------------------------------------------------------------
// Main switch; functions are in include files "functions_somename.inc.php"
// -------------------------------------------------------------------------
switch ($state) {
	case "printloginform":
		printLoginForm();
	break;
	case "printdetails":
		printDetails();
	break;
	case "printscreenshots":
		printScreenshots();
	break;
	case "printdownload":
		printDownload();
	break;
	case "browse":
		browse($directory);
	break;
	case "manage":
// 423 upgrade: remove
		for ($i=1; $i<=7; $i=$i+1) {
			$uploadedFilesArray["$i"]["temppathname"] = ${"userfile$i"};
			$uploadedFilesArray["$i"]["name"]         = ${"userfile" . $i . "_name"};
			$uploadedFilesArray["$i"]["size"]         = ${"userfile" . $i . "_size"};
		} // end for
		for ($i=1; $i<=2; $i=$i+1) {
			$uploadedZipFilesArray["$i"]["temppathname"] = ${"userzipfile$i"};
			$uploadedZipFilesArray["$i"]["name"]         = ${"userzipfile" . $i . "_name"};
			$uploadedZipFilesArray["$i"]["size"]         = ${"userzipfile" . $i . "_size"};
		} // end for
		manage($manage, $directory, $entry, $selectedEntries, $newNames, $dirorfile, $formresult, $chmodStrings, $targetDirectories, $copymovedelete, $text, $uploadedFilesArray, $uploadedZipFilesArray);
	break;
	case "logout":
		printLoginForm();
	break;
	case "feedback":
		printFeedbackForm($formresult);
	break;
	default:
		$resultArray[message] = "Unexpected state string. Exiting."; 
		printErrorMessage($resultArray, "exit");
	break;
} // End switch


// -------------------------------------------------------------------------
// End HTML output
// -------------------------------------------------------------------------
HtmlEnd();

// -------------------------------------------------------------------------
// Send the output to the browser
// Note: in case there is an error and the script is exited, the ob_flush function is called by the error handler
// -------------------------------------------------------------------------
ob_end_flush();

?>
