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
// Run the script to the end, even if the user hits the stop button
// -------------------------------------------------------------------------
//ignore_user_abort();

// -------------------------------------------------------------------------
// Global variables (declared as global in functions)
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
// Includes
// -------------------------------------------------------------------------
require_once("../settings.inc.php");							// General parameters

require_once($application_rootdir . "/includes/html.inc.php");			// Functions
require_once($application_rootdir . "/includes/filesystem.inc.php");
require_once($application_rootdir . "/includes/database.inc.php");
require_once($application_rootdir . "/includes/authorizations.inc.php");
require_once($application_rootdir . "/includes/errorhandling.inc.php");

htmlbegin($myname . "<br>Admin");

// ----------------------------------------------------------------------------------------------------
// Form
// ----------------------------------------------------------------------------------------------------

if ($formresult != "result") {

	echo "<table align=\"center\">\n";
	echo "<tr>\n";
	echo "<td>\n";

	echo "Select the criteria to filter the logs:<br><br>\n";
	echo "<form name=\"FilterForm\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"formresult\" value=\"result\">\n";

// Date
	$today = date("Y-m-d");
	$oneweekago = date("Y-m-d", time() - 3600*24*7);
	echo "Date from: <input type=\"text\" name=\"datefrom\" value=\"$oneweekago\">  to: <input type=\"text\" name=\"dateto\" value=\"$today\"><br>";
	echo "<br><br><div style=\"text-align: center;\"><input type=\"submit\" id=\"button\" value=\"Submit\"></div>\n";
	echo "</form>\n";

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";


} // End if

// ----------------------------------------------------------------------------------------------------
// Result
// ----------------------------------------------------------------------------------------------------

elseif ($formresult == "result") {

// ----------------------------------------------------------------------------------------------------
// For both form and result, get field names from DB
// ----------------------------------------------------------------------------------------------------
$resultArray = connect2db();
$mydb = getResult($resultArray);
if ($mydb == false)  { printErrorMessage($resultArray); exit(); }

$fields_net2ftp_logAccess = mysql_list_fields($dbname, "net2ftp_logAccess", $mydb);
$columns_net2ftp_logAccess = mysql_num_fields($fields_net2ftp_logAccess);

$fields_net2ftp_logLogin = mysql_list_fields($dbname, "net2ftp_logLogin", $mydb);
$columns_net2ftp_logLogin = mysql_num_fields($fields_net2ftp_logLogin);

$fields_net2ftp_logError= mysql_list_fields($dbname, "net2ftp_logError", $mydb);
$columns_net2ftp_logError = mysql_num_fields($fields_net2ftp_logError);


// Form querystring and query DB
// ----------------------------

// Queries used when user logs in
// $sqlquerystring = "INSERT INTO net2ftp_logLoginVALUES('$date', '$time', '$input_ftpserver', '$input_username', '$REMOTE_ADDR', '$REMOTE_PORT', '$HTTP_USER_AGENT', '$HTTP_REFERER')";
// $sqlquerystring = "SELECT * FROM net2ftp_usersBanned WHERE remote_addr = '$REMOTE_ADDR'";
// $sqlquerystring = "SELECT * FROM net2ftp_ftpserversBanned WHERE ftpserver = '$input_ftpserver'";
// Query 1 - old
//	$sqlquery1 = "SELECT * FROM net2ftp_logAccess WHERE ";
//	for ($i = 0; $i < $columns_net2ftp_logAccess; $i++) {
//		$queryparametername = mysql_field_name($fields_net2ftp_logAccess, $i);
//		$queryparametervalue = $$queryparametername;
//		if ($queryparametervalue != "") { $sqlquery1 = $sqlquery1 . "$queryparametername=$queryparametervalue, "; $nrofnonblanks++; }
//	}
//
//	// Remove the last ", "      from the query string if $nrofnonblanks > 0
//	// Remove the last " WHERE " from the query string in the other case
//	if ($nrofnonblanks > 0) { $sqlquery1 = substr($sqlquery1, 0, strlen($sqlquery1)-2); }
//	else { $sqlquery1 = substr($sqlquery1, 0, strlen($sqlquery1)-7); }



// Query 1
	$sqlquery1 = "SELECT * FROM net2ftp_logAccess WHERE date BETWEEN '$datefrom' AND '$dateto' ORDER BY date DESC, time DESC;";
	$result1 = mysql_query("$sqlquery1") or die("Unable to execute SQL SELECT query (stats1) <br> $sqlquery1");
	$nrofrows1 = mysql_num_rows($result1);

// Query 2
	$sqlquery2 = "SELECT * FROM net2ftp_logLogin WHERE date BETWEEN '$datefrom' AND '$dateto' ORDER BY date DESC, time DESC;";
	$result2 = mysql_query("$sqlquery2") or die("Unable to execute SQL SELECT query (stats2) <br> $sqlquery2");
	$nrofrows2 = mysql_num_rows($result2);

// Query 3
	$sqlquery3 = "SELECT * FROM net2ftp_logError WHERE date BETWEEN '$datefrom' AND '$dateto' ORDER BY date DESC, time DESC;";
	$result3 = mysql_query("$sqlquery3") or die("Unable to execute SQL SELECT query (stats3) <br> $sqlquery3");
	$nrofrows3 = mysql_num_rows($result3);




// Show results
// ----------------------------

printLogs($columns_net2ftp_logAccess, $nrofrows1, $sqlquery1, $fields_net2ftp_logAccess, $result1);
printLogs($columns_net2ftp_logLogin, $nrofrows2, $sqlquery2, $fields_net2ftp_logLogin, $result2);
printLogs($columns_net2ftp_logError, $nrofrows3, $sqlquery3, $fields_net2ftp_logError, $result3);

} // End if elseif (form or result)


htmlend();








// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 

function printLogs($nrofcolumns, $nrofrows, $sqlquery, $fields, $result) {

// Call the function like this:
//    printLogs($columns_net2ftp_ftpserversBanned, $nrofrows5, $sqlquery5, $fields_net2ftp_ftpserversBanned, $result3)

$nrofcolumns_withindex = $nrofcolumns + 1;

	echo "<table border=\"1\">\n";
// ------------------------------------------------------------------------- 
// First row: title
// ------------------------------------------------------------------------- 
	echo "<tr><td colspan=\"$nrofcolumns_withindex\" id=\"#tdheader1\" style=\"font-size: 120%;\">$sqlquery</td></tr>\n";
// ------------------------------------------------------------------------- 
// Second row: header
// ------------------------------------------------------------------------- 
	echo "<tr>\n";
	echo "<td id=\"tdheader1\"><b>Index</b></td>\n";
	for ($i = 0; $i < $nrofcolumns; $i=$i+1) {
		$resultparametername = mysql_field_name($fields, $i);
		$resultparametervalue = mysql_result($result, 0, $resultparametername);
		echo "<td id=\"tdheader1\"><b>$resultparametername</b></td>\n";
	} // End for (row, loop on columns)
	echo "</tr>\n";
// ------------------------------------------------------------------------- 
// Other rows: data
// ------------------------------------------------------------------------- 
	for($j=0; $j<$nrofrows; $j=$j+1) {
		echo "<tr>\n";
		echo "<td id=\"tditem1\">$j</td>\n";
		for ($i = 0; $i < $nrofcolumns; $i=$i+1) {
			$resultparametername = mysql_field_name($fields, $i);
			$resultparametervalue = mysql_result($result, $j, $resultparametername);
			echo "<td id=\"tditem1\">$resultparametervalue</td>\n";
		} // End for (row, loop on columns)
		echo "</tr>\n";
	} // End for (show results, loop on rows)

	echo "</table>\n";

	echo "<p> &nbsp; </p>\n\n\n";

} // End printLogs

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************


?>