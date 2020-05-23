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



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function HtmlBegin($pagetitle) {

// -------------------------------------------------------------------------
// Global variables (declared as global in functions)
// -------------------------------------------------------------------------
global $starttime;
global $state, $manage, $myname;
global $client_css;
global $net2ftp_ftpserver, $directory, $entry;

// -------------------------------------------------------------------------
// Timer: start
// -------------------------------------------------------------------------
	$starttime = microtime();

// -------------------------------------------------------------------------
// Log access
// -------------------------------------------------------------------------
	$page = printPHP_SELF();
	logAccess($page);

// -------------------------------------------------------------------------
// HTML begin
// -------------------------------------------------------------------------
	echo "<html>\n\n\n";
// Head
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<meta name=\"keywords\" content=\"$myname, ftp, web, net, connect, user, gui, interface, web2ftp, net2ftp, edit, editor, online, code, php, upload, download, copy, move, delete, recursive, rename, chmod, syntax, highlighting\">\n";
	echo "<meta name=\"description\" content=\"Manage websites using a browser. Edit code, upload/download files, copy/move/delete directories recursively, rename files and directories -- without installing any software.\">\n";
	if ($manage == "view" || $manage == "edit") { echo "<title>--> $pagetitle --> $net2ftp_ftpserver$directory/$entry</title>\n"; }
	else                                        { echo "<title>--> $pagetitle --> $net2ftp_ftpserver$directory</title>\n"; }	
// Include stylesheet
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$client_css\">\n";
	echo "</head>\n\n\n";

// Body
	echo "<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>\n";
	if ($state=="manage" && ($manage=="edit" || $manage=="newfile")) {
		// Do not print anything
	}
	else {
		echo "<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\">\n";
//		echo "<tr>\n";
//		echo "<td id=\"tdmiddle\">\n";
		echo "<table border=\"0\" align=\"center\" cellspacing=\"0\" celpadding=\"0\"><tr><td id=\"tdbackground\">  <!-- Table for background color-->\n";
		echo "<table border=\"0\" width=\"790\" cellspacing=\"0\" celpadding=\"0\"> <!-- Table with colums and content -->\n";
		echo "<tr>\n";
		echo "<td id=\"tdleft1\" width=\"20\">\n";
		echo "</td>\n";
		echo "<td id=\"tdleft2\" width=\"30\">\n";
		echo "</td>\n";
		echo "<td id=\"tdmiddle\">\n";
		echo "<div id=\"header11\">" . $pagetitle . "</div>\n\n\n";
	}
}
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function HtmlEnd() {

global $starttime;
global $state, $manage;


// -------------------------------------------------------------------------
// Timer: stop
// -------------------------------------------------------------------------
	$endtime = microtime();
	if ($state=="manage" && ($manage=="edit" || $manage=="newfile")) {
		// Do not print anything
	}
	else {
		//timer($starttime, $endtime);
	}

// -------------------------------------------------------------------------
// Feedback
// -------------------------------------------------------------------------
	if (($state=="manage" && ($manage=="edit" || $manage=="newfile")) || ($state=="printloginform") || ($state=="feedback")) {
		// Do not print anything
	}
	else {
		echo "<div style=\"text-align: center; margin-top: 30px; margin-bottom: 10px; font-size: 80%;\">\n";
		echo "<a href=\"" . printPHP_SELF() . "?state=feedback\" target=\"_blank\">Comments? Questions? Send us some feedback!</a>\n";
		echo "</div>\n";
	}

// -------------------------------------------------------------------------
// HTML end
// -------------------------------------------------------------------------
	if ($state=="manage" && ($manage=="edit" || $manage=="newfile")) {
		// Do not print anything
	}
	else {

//   -------------------------------------------------------------------------------
// IMPORTANT: YOU ARE NOT ALLOWED TO REMOVE NOR CHANGE/EDIT THE COPYRIGHT NOTE
// IN THE FOOTER OF EACH PAGE.  

		echo "<div style=\"text-align: center; margin-top: 20px; margin-bottom: 10px; font-size: 80%;\">\n";
		echo "Powered by net2ftp &copy; <a href=\"http://www.net2ftp.com\">net2ftp.com</a>. net2ftp is free software, released under the <a href=\"http://www.gnu.org\" target=\"_blank\">GNU/GPL license</a>.\n";
		echo "</div>\n";
//   -------------------------------------------------------------------------------

		echo "\n\n</td>\n";
		echo "</tr>\n";
		echo "</table> <!-- Table with colums and content -->\n";
		echo "</td></tr></table> <!-- Table for background color-->\n";
		echo "<p>&nbsp;</p>\n";
		echo "<p>&nbsp;</p>\n";
	}

	echo "</body>\n\n\n";
	echo "</html>\n";
}
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function timer($starttime, $endtime) {

// --------------
// This function calculates the time between starttime and endtime, and prints it
// It is used in to print the execution time on each page
// --------------
	list($start_usec, $start_sec) = explode(' ', $starttime);
	$starttime = ((float)$start_usec + (float)$start_sec); 
	list($end_usec, $end_sec) = explode(' ', $endtime);
	$endtime   = ((float)$end_usec + (float)$end_sec); 
	$time_taken         = ($endtime - $starttime)*1000; // to convert from sec to millisec
	$time_taken         = number_format($time_taken, 2);  // optional
	echo "<div style=\"text-align: center; margin-top: 30px; font-size: 80%;\">\n";
	echo "Page created in <b>" . $time_taken . "</b> milliseconds on <b>" . mytime() . "</b>\n";
	echo "</div>\n";
} // End function timer
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function mytime() {
	$datetime = date("Y-m-d H:i:s");                          
	return $datetime;
}
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printTitle($title) {

// --------------
// This function prints the a title
// --------------

	echo "<div id=\"header21\">\n";
	echo "$title\n";
	echo "</div>\n";

} // End function printTitle
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************


?>
