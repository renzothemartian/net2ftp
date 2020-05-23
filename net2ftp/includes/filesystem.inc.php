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
function ftp_open() {

// --------------
// This function opens an ftp connection and chdirs to $directory
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $net2ftp_ftpserver, $net2ftp_ftpserverport, $net2ftp_username, $net2ftp_password_encrypted;

	$net2ftp_password = decryptPassword($net2ftp_password_encrypted);

// Check if port nr is filled in
	if ($net2ftp_ftpserverport < 1 || $net2ftp_ftpserverport > 65535 || $net2ftp_ftpserverport == "") { $net2ftp_ftpserverport = 21; }

// Set up basic connection
	$conn_id = ftp_connect("$net2ftp_ftpserver", $net2ftp_ftpserverport);
	if ($conn_id == false) { return putResult(false, "", "ftp_connect", "ftp_open > ftp_connect: net2ftp_ftpserver=$net2ftp_ftpserver.", "Unable to connect to FTP server <b>$net2ftp_ftpserver</b> on port <b>$net2ftp_ftpserverport</b>.<br><br>Are you sure this is the address of the FTP server? This is often different from that of the HTTP (web) server. Please contact your ISP helpdesk or system administrator for help.<br><br>"); }

// Login with username and password
	$login_result = ftp_login($conn_id, $net2ftp_username, $net2ftp_password);
	if ($login_result == false) { return putResult(false, "", "ftp_login", "ftp_open > ftp_login: conn_id=$conn_id; net2ftp_username=$net2ftp_username.", "Unable to login to FTP server <b>$net2ftp_ftpserver</b> with username <b>$net2ftp_username</b>.<br><br>Are you sure your username and password are correct? Please contact your ISP helpdesk or system administrator for help.<br><br>"); }

	return putResult(true, $conn_id, "", "", "");

} // End function ftp_open
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_close($conn_id) {

// --------------
// This function closes an ftp connection
// --------------
	$success1 = ftp_quit($conn_id);
	if ($success1 == false) { return putResult(false, "", "ftp_quit", "ftp_close > ftp_quit: conn_id=$conn_id.", "Unable to disconnect from the FTP server<br>"); }

	return putResult(true, true, "", "", "");

} // End function ftp_close
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_myrename($conn_id, $directory, $selectedEntry, $newName) {

// --------------
// This function renames a directory
// --------------

// Rename directory

	$success1 = ftp_rename($conn_id, "$directory/$selectedEntry", "$directory/$newName");
	if ($success1 == false) { return putResult(false, "", "ftp_rename", "ftp_myrename > ftp_rename: conn_id=$conn_id; old=$directory/$selectedEntry; new=$directory/$newName.", "Unable to rename directory or file <b>$ftp_old</b> into <b>$ftp_new</b><br>"); }

	return putResult(true, true, "", "", "");

} // End function ftp_myrename
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_mychmod($conn_id, $directory, $selectedEntry, $chmodOctal) {

// --------------
// This function chmods a directory or file
// --------------


// Rename directory
	$success1 = ftp_site($conn_id, "chmod 0$chmodOctal $directory/$selectedEntry");
	if ($success1 == false) { return putResult(false, "", "ftp_site", "ftp_mychmod > ftp_site: conn_id=$conn_id; directory=$directory; selectedEntry=$selectedEntry; chmodOctal=$chmodOctal.", "Unable to execute site command <b>chmod 0$chmodOctal $selectedEntry</b>"); }

	return putResult(true, true, "", "", "");

} // End function ftp_myrename
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_mydelete($directoryorfile) {

// --------------
// This function deletes a remote directory or a file
// NOT IN USE ANY MORE
// --------------

// Open connection
//	$resultArray = ftp_open();
//	$conn_id = getResult($resultArray);
//	if ($conn_id == false)  { return putResult(false, "", "ftp_open", "ftp_mydelete > $resultArray[drilldown]", $resultArray[message]); }

// Rename directory
//	$success1 = ftp_delete($conn_id, $directoryorfile);
//	if ($success1 == false) { return putResult(false, "", "ftp_delete", "ftp_mydelete > ftp_delete: conn_id=$conn_id; directoryorfile=$directoryorfile.", "Unable to delete the directory <b>$directoryorfile</b><br>"); }

// Close connection
//	$resultArray = ftp_close($conn_id);
//	$success2 = getResult($resultArray);
//	if ($success2 == false) { return putResult(false, "", "ftp_close", "ftp_mydelete > $resultArray[drilldown]", $resultArray[message]); }

//	return putResult(true, true, "", "", "");

} // End function ftp_mydelete
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_newdirectory($conn_id, $directory) {

// --------------
// This function creates a new remote directory
// --------------

// Create new directory
	$success1 = ftp_mkdir($conn_id, $directory);
	if ($success1 == false) { return putResult(false, "", "ftp_newdirectory", "ftp_newdirectory > ftp_mkdir: conn_id=$conn_id; directory=$directory.", "Unable to create the directory <b>$directory</b><br>"); }

	return putResult(true, true, "", "", "");

} // End function ftp_newdirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_readfile($directory, $file) {

// --------------
// This function opens a remote text file and it returns a string
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;

	$source = $directory . "/" . $file;

// --------------------
// Step 1/4: Create a temporary filename
	$tempfilename = tempnam($application_tempdir, "ftpread");
	if ($tempfilename == false)  { return putResult(false, "", "tempnam", "ftp_readfile > tempnam: application_tempdir=$application_tempdir.", "Unable to create the temporary file<br>"); }

// --------------------
// Step 2/4: Copy remote file to the temporary file
// Open connection
	$resultArray = ftp_open();
	$conn_id = getResult($resultArray);
	if ($conn_id == false)  { return putResult(false, "", "ftp_open", "ftp_readfile > $resultArray[drilldown]", $resultArray[message]); }

// Get file
	$success1 = ftp_get($conn_id, $tempfilename, $source, "FTP_ASCII");
	if ($success1 == false) { return putResult(false, "", "ftp_get", "ftp_readfile > ftp_get: conn_id=$conn_id; tempfilename=$tempfilename, source=$source.", "Unable to get file <b>$source</b><br>"); }

// Close connection
	$resultArray = ftp_close($conn_id);
	$success2 = getResult($resultArray);
	if ($success2 == false) { return putResult(false, "", "ftp_close", "ftp_readfile > $resultArray[drilldown]", $resultArray[message]); }

// --------------------
// Step 3/4: Read temporary file
	$handle = fopen($tempfilename, "r"); // Open the file for reading only
	if ($handle == false) { return putResult(false, "", "fopen", "ftp_readfile > fopen: tempfilename=$tempfilename.", "Unable to open the temporary file<br>"); }

	clearstatcache(); // for filesize

	$string = fread($handle, filesize($tempfilename));
	if ($string == false) { return putResult(false, "", "fread", "ftp_readfile > fread: handle=$handle; tempfilename=$tempfilename.", "Unable to read the temporary file<br>"); }

	$success3 = fclose($handle);
	if ($success3 == false) { return putResult(false, "", "fclose", "ftp_readfile > fclose: handle=$handle", "Unable to close the temporary file<br>"); }

// --------------------
// Step 4/4: Delete temporary file
	$success4 = unlink($tempfilename);
	if ($success4 == false) { return putResult(false, "", "unlink", "ftp_readfile > unlink: tempfilename=$tempfilename.", "Unable to delete the temporary file<br>"); } 

	return putResult(true, $string, "", "", "");

} // End function ftp_readfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_writefile($directory, $file, $string) {

// --------------
// This function writes a string to a remote text file.
// If it already existed, it will be overwritten without asking for a confirmation.
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;

	$target= $directory . "/" . $file;

// Step 1/4: Create a temporary filename
	$tempfilename = tempnam($application_tempdir, "ftpwrite");
	if ($tempfilename == false)  { return putResult(false, "", "tempnam", "ftp_writefile > tempnam: application_tempdir=$application_tempdir.", "Unable to create the temporary file<br>"); }

// Step 2/4: Write the string to the temporary file
	$handle = fopen($tempfilename, "w");
	if ($handle == false) { return putResult(false, "", "fopen", "ftp_writefile > fopen: tempfilename=$tempfilename.", "Unable to open the temporary file<br>"); }

	$success1 = fwrite($handle, $string);
	if ($success1 == false) { return putResult(false, "", "fwrite", "ftp_writefile > fwrite: handle=$handle; string=$string.", "Unable to write to the temporary file<br>"); }

	$success2 = fclose($handle);
	if ($success2 == false) { return putResult(false, "", "fclose", "ftp_writefile > fclose: handle=$handle.", "Unable to write to the temporary file<br>"); }

// Step 3/4: Copy temporary file to remote file
// Open connection
	$resultArray = ftp_open();
	$conn_id = getResult($resultArray);
	if ($conn_id == false)  { return putResult(false, "", "ftp_open", "ftp_writefile > $resultArray[drilldown]", $resultArray[message]); }

// Put file
	$success3 = ftp_put($conn_id, $target, $tempfilename, "FTP_ASCII");
	if ($success3 == false) { return putResult(false, "", "ftp_get", "ftp_writefile > ftp_put: conn_id=$conn_id; target=$target; tempfilename=$tempfilename.", "Unable to put file <b>$target</b><br>"); }

// Close connection
	$resultArray = ftp_close($conn_id);
	$success4 = getResult($resultArray);
	if ($success4 == false) { return putResult(false, "", "ftp_close", "ftp_writefile > $resultArray[drilldown]", $resultArray[message]); }

// Step 4/4: Delete temporary file
	$success5 = unlink($tempfilename);
	if ($success5 == false) { return putResult(false, "", "unlink", "ftp_writefile > unlink: tempfilename=$tempfilename.", "Unable to delete the temporary file<br>"); } 

	return putResult(true, true, "", "", "");

} // End function ftp_writefile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_copymovedeletedirectory($conn_id, $directory, $entry, $targetdirectory, $targetentry, $copymovedelete, $divelevel) {

// --------------
// This function copies/moves/deletes a remote directory to a remote directory
// $ftpmode is used to specify whether ALL files are to be transferred in ASCII or BINARY mode
// $copymovedelete is used to specify whether to delete the source -- in case of move or delete, or not -- in case of copy
//
// sourcedirectory = /test
// subdirectorytomove = /d1
// targetdirectory = /test/target
// ==> /test/d1 will be copied/moved to /test/target/d1
//
// ---------
// | Steps |
// ---------
// 1 -- copy/move, divelevel 0    create targetdirectory/targetentry
//
// 2 -- all                       get a list of all subdirectories and files in /directory/entry
//
// 3 --                           for all the entries, do
//                                   directory
//                                      copy/move     create targetdirectory/targetentry/dirfilename
//                                      all           recursive algorithm: do the same with sourcedirectory="$directory/$entry", targetdirectory="$targetdirectory/$targetentry", entry="dirfilename"
//                                      move/delete   delete directory/entry/dirfilename
//                                   file
//                                      copy/move     copy or move directory/entry/dirfilename to local tempdir
//                                      copy/move     move from local tempdir to targetdirectory/targetentry/dirfilename 
//                                      delete        delete directory/entry/dirfilename 
//
// 4 -- move/delete, divelevel 0  delete directory/entry
// --------------


// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;

// -------------------------------------------------------------------------
// Print text: begin
// -------------------------------------------------------------------------
	echo "<br><ul>\n";
	echo "<li><u>Processing directory <b>$directory/$entry</b></u><br>\n";

// -------------------------------------------------------------------------
// Create new subdirectory $targetdirectory/$subdirectorytomove
// -------------------------------------------------------------------------
	if ($divelevel == 0 && $copymovedelete != "delete") {
		$success1 = ftp_mkdir($conn_id, "$targetdirectory/$targetentry");
//		if ($success1 == false) { return putResult(false, "", "ftp_mkdir1", "ftp_copymovedeletedirectory > ftp_mkdir: conn_id=$conn_id; targetdirectory/targetentry=$targetdirectory/$targetentry.", "Unable to create the subdirectory <b>$targetdirectory/$targetentry</b>. Either it already exists, either the parent directory does not exist. Continuing the copy/move process. Level=$divelevel.<br>"); }
		if ($success1 == false) { printWarningMessage("Unable to create the subdirectory <b>$targetdirectory/$targetentry</b>. Either it already exists, either the parent directory <b>$targetdirectory</b> does not exist. Continuing the copy/move process...<br>"); }
		if ($success1 == true) { echo "Created target directory <b>$targetdirectory/$targetentry</b><br>"; }
	}

// -------------------------------------------------------------------------
// Get nice list of all subdirectories and files
// -------------------------------------------------------------------------
	$nicelist= ftp_getlist($conn_id, "$directory/$entry");

// -------------------------------------------------------------------------
// For all the subdirectories and files...
// -------------------------------------------------------------------------
	for ($i=1; $i<=count($nicelist); $i++) {
		$dirfileindicator = $nicelist[$i][0];
		$dirfilename = $nicelist[$i][1];
		$dirfilesize = $nicelist[$i][2];
		$dirfileowner = $nicelist[$i][3];
		$dirfilegroup = $nicelist[$i][4];
		$dirfilepermissions = $nicelist[$i][5];
		$dirfilemtime = $nicelist[$i][6];

// ------------------------------
// Subdirectory: create new remote subdirectory
// ------------------------------
		if ($dirfileindicator == "d") {
			if ($copymovedelete == "copy" || $copymovedelete == "move") { 
				$success2 = ftp_mkdir($conn_id, "$targetdirectory/$targetentry/$dirfilename");
//				if ($success2 == false) { return putResult(false, "", "ftp_mkdir2", "ftp_copymovedeletedirectory > ftp_mkdir: conn_id=$conn_id; targetdirectory/targetentry/dirfilename=$targetdirectory/$targetentry/$dirfilename. Level=$divelevel.", "Unable to create the subdirectory <b>$targetdirectory/$targetentry/$dirfilename</b><br>"); }
				if ($success2 == false) { printWarningMessage("Unable to create the subdirectory <b>$targetdirectory/$targetentry/$dirfilename</b>. It may already exist. Continuing the copy/move process...<br>"); }
				if ($success2 == true) { echo "<br>Created target subdirectory <b>$targetdirectory/$targetentry/$dirfilename</b>.<br>"; }
			}

                        //--------------------------
			$divelevel = $divelevel +1;
			$resultArray = ftp_copymovedeletedirectory($conn_id, "$directory/$entry", $dirfilename, "$targetdirectory/$targetentry", $dirfilename, $copymovedelete, $divelevel);
			$success3 = getResult($resultArray);
//			if ($success3 == false) { return putResult(false, "", "ftp_copymovedeletedirectory", "ftp_copymovedeletedirectory > $resultArray[drilldown] Level=$divelevel.", "Unable to $copymovedelete the directory <b>$directory/$entry/$dirfilename</b><br>"); }
			if ($success3 == false) { printWarningMessage($resultArray); }
			$divelevel = $divelevel -1;
                        //--------------------------

			if ($copymovedelete == "move" || $copymovedelete == "delete") { 
				$success4 = ftp_delete($conn_id, "$directory/$entry/$dirfilename");
//				if ($success4 == false) { return putResult(false, "", "ftp_delete4", "ftp_copymovedeletedirectory > ftp_delete: conn_id=$conn_id; directory/entry/dirfilename=$directory/$entry/$dirfilename. Level=$divelevel.", "Unable to delete the directory <b>$targetdirectory/$targetentry/$dirfilename</b>. A possible reason is that it is not empty.<br>"); }
				if ($success4 == false) { printWarningMessage ("Unable to delete the subdirectory <b>$targetdirectory/$targetentry/$dirfilename</b>. It may not be empty.<br>"); }
				if ($success4 == true) { echo "<br>Deleted subdirectory <b>$directory/$entry/$dirfilename</b>.<br>"; }
 			}
		}
// ------------------------------
// File:
// 1 - Get remote file to local temporary directory
// 2 - Put local file to remote target directory; choose move so the local file is deleted
// 3 - If move: delete remote file
// ------------------------------
		elseif ($dirfileindicator == "-") {
			$dirfilenameArray[0] = $dirfilename;
			$ftpmodeArray = ftpAsciiBinary($dirfilenameArray);
			$ftpmode = $ftpmodeArray[0];
			$resultArray = ftp_copymovedeletefile($conn_id, "$directory/$entry", $dirfilename, "$targetdirectory/$targetentry", $dirfilename, $ftpmode, $copymovedelete);
			$success5 = getResult($resultArray);
// Message is printed in function
//			if ($success5 == false) { printWarningMessage($resultArray); }

		}

	} // End for
// ------------------------------


// -------------------------------------------------------------------------
// Delete the directory source directory/subdirectorytomove
// -------------------------------------------------------------------------
	if ($divelevel == 0 && $copymovedelete != "copy") {
		$success8 = ftp_delete($conn_id, "$directory/$entry");
		if ($success8 == false) { printWarningMessage("Unable to delete the source directory <b>$directory/$entry</b> either because it does not exist, or because it is not empty."); }
	}

// -------------------------------------------------------------------------
// Print text: end
// -------------------------------------------------------------------------
	if ($divelevel == 0) {
		echo "<br><div style=\"color: green; font-weight: bold;\">The directory <b>$directory/$entry</b> has been processed.<br> Please read the messages above. The error and warning messages are in red.</div><br>\n";
	}
	echo "</ul>\n";

	return putResult(true, true, "", "", "");

} // End function ftp_copymovedeletedirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************













// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_copymovedeletefile($conn_id, $directory, $entry, $targetdirectory, $targetentry, $ftpmode, $copymovedelete) {

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;

// -------------------------------------------------------------------------
// Copy or move
// -------------------------------------------------------------------------

	if ($copymovedelete != "delete") {
//    Get file from remote sourcedirectory to local temp directory
		//             ftp_getfile($conn_id, $localtargetdir, $localtargetfile, $remotesourcedir, $remotesourcefile, $ftpmode, $copymove);
		$resultArray = ftp_getfile($conn_id, $application_tempdir, "$entry.txt", $directory, $entry, $ftpmode, $copymovedelete);
		$success1 = getResult($resultArray);
		if ($success1 == false) { printWarningMessage($resultArray); }

//    Put file from local temp directory to remote targetdirectory; move instead of copy to delete the temporary file
		//             ftp_putfile($conn_id, $localsourcedir, $localsourcefile, $remotetargetdir, $remotetargetfile, $ftpmode, $copymove);
		$resultArray = ftp_putfile($conn_id, $application_tempdir, "$entry.txt", $targetdirectory, $targetentry, $ftpmode, "move");
		$success2 = getResult($resultArray);
		if ($success2 == false) { printWarningMessage($resultArray); }


// If ftp_putfile fails (success2 == false), the function ftp_putfile returns an error message and does not delete the temporary file.
// In case the file was copied, a copy exists in the source directory.
// In case the file was moved, the only copy is in the temporary directory, and so this has to be moved back to the source directory.
		if ($success2 == false && $copymovedelete == "move") { 
			$resultArray = ftp_putfile($conn_id, $application_tempdir, "$entry.txt", $directory, $entry, $ftpmode, "move");
			$success3 = getResult($resultArray);			
		}

	} // End copy or move

// -------------------------------------------------------------------------
// Delete
// -------------------------------------------------------------------------

	elseif ($copymovedelete == "delete") {
		$success4 = ftp_delete($conn_id, "$directory/$entry");
		if ($success4 == false) { $resultArray[message] = "Unable to delete the file <b>$directory/$entry</b>"; printWarningMessage($resultArray); }
	} // End delete


	if ($copymovedelete == "copy" && $success2 == true)       { echo "<br>The file <b>$directory/$entry</b> was successfully copied to <b>$targetdirectory/$targetentry</b> using FTP mode <b>$ftpmode</b>.<br>\n"; }
	elseif ($copymovedelete == "move" && $success2 == true)   { echo "<br>The file <b>$directory/$entry</b> was successfully moved to <b>$targetdirectory/$targetentry</b> using FTP mode <b>$ftpmode</b>.<br>\n"; }
	elseif ($copymovedelete == "delete" && $success4 == true) { echo "<br>The file <b>$directory/$entry</b> was successfully deleted.<br>\n"; }

	return putResult(true, true, "", "", "");

} // End function ftp_copymovedeletefile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************











// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_getfile($conn_id, $localtargetdir, $localtargetfile, $remotesourcedir, $remotesourcefile, $ftpmode, $copymove) {

// --------------
// This function copies or moves a remote file to a local file
// $ftpmode is used to specify whether the file is to be transferred in ASCII or BINARY mode
// $copymove is used to specify whether to delete (move) or not (copy) the local source
//
// True or false is returned
//
// The opposite function is ftp_putfile
// --------------

	$remotesource = $remotesourcedir . "/" . $remotesourcefile;
	$localtarget = $localtargetdir . "/" . $localtargetfile;

// Get file
	$success1 = ftp_get($conn_id, $localtarget, $remotesource, $ftpmode);
	if ($success1 == false) { return putResult(false, "", "ftp_get", "ftp_getfile > ftp_get: conn_id=$conn_id; localtarget=$localtarget; remotesource=$remotesource.", "Unable to copy remote file <b>$remotesource</b> to local file using FTP mode <b>$ftpmode</b><br>"); }

// Copy ==> do nothing
// Move ==> delete remote source file
	if ($copymove != "copy") {
		$success2 = ftp_delete($conn_id, $remotesource);
		if ($success2 == false) { return putResult(false, "", "ftp_delete", "ftp_getfile > ftp_delete: conn_id=$conn_id; remotesource=$remotesource.", "Unable to delete file <b>$remotesource</b><br>"); }
	}

	return putResult(true, true, "", "", "");

} // End function ftp_getfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_putfile($conn_id, $localsourcedir, $localsourcefile, $remotetargetdir, $remotetargetfile, $ftpmode, $copymove) {

// --------------
// This function copies or moves a local file to a remote file
// $ftpmode is used to specify whether the file is to be transferred in ASCII or BINARY mode
// $copymove is used to specify whether to delete (move) or not (copy) the local source
//
// True or false is returned
//
// The opposite function is ftp_getfile
// --------------

	$localsource = $localsourcedir . "/" . $localsourcefile;
	$remotetarget = $remotetargetdir . "/" . $remotetargetfile;

// In the function ftp_put, use FTP_BINARY without the double quotes, otherwhise ftp_put assumes FTP_ASCII
// DO NOT REMOVE THIS OR THE BINARY FILES WILL BE CORRUPTED (when copying, moving, uploading,...)
	if ($ftpmode == "FTP_BINARY") { $ftpmode = FTP_BINARY; } 

// Put local file to remote file
// int ftp_put (int ftp_stream, string remote_file, string local_file, int mode)
	$success1 = ftp_put($conn_id, $remotetarget, $localsource, $ftpmode);
	if ($success1 == false) { return putResult(false, "", "ftp_put", "ftp_putfile > ftp_put: conn_id=$conn_id; remotetarget=$remotetarget; localsource=$localsource.", "Unable to copy the local file to the remote file <b>$remotetarget</b> using FTP mode <b>$ftpmode</b><br>"); }
// If ftp_put fails, this function returns an error message and does not delete the temporary file.
// In case the file was copied, a copy exists in the source directory.
// In case the file was moved, the only copy is in the temporary directory, and so this has to be moved back to the source directory.

// Copy ==> do nothing
// Move ==> delete local source file
	if ($copymove != "copy") {
		$success2 = unlink($localsource);
		if ($success2 == false) { return putResult(false, "", "unlink", "ftp_putfile > unlink: localsource=$localsource.", "Unable to delete the local file<br>"); }
	}

	return putResult(true, true, "", "", "");

} // End function ftp_putfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_downloadfile($directory, $entry) {

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $application_tempdir;


// -------------------------------------------------------------------------
// Note !!
// This function can handle multiple files (array selectedEntries), but it is always used to handle only one (entry)...
// -------------------------------------------------------------------------
$selectedEntries[0] = $entry;


// -------------------------------------------------------------------------
// Get remote file from FTP server to temp file
// -------------------------------------------------------------------------

// Parse the filenames to see in which FTP mode the files should be transferred
	$ftpModes = ftpAsciiBinary($selectedEntries);


// -------------------------------------------------------------------------
// Get files
// -------------------------------------------------------------------------

// Open connection
	$resultArray = ftp_open();
	$conn_id = getResult($resultArray);
	if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }

	for ($k=0; $k<count($selectedEntries); $k++) {
//                     ftp_getfile($conn_id, $localtargetdir, $localtargetfile, $remotesourcedir, $remotesourcefile, $ftpmode, $copymove)
		$resultArray = ftp_getfile($conn_id, $application_tempdir, "$selectedEntries[$k].txt", $directory, $selectedEntries[$k], $ftpModes[$k], "copy");
		$success1 = getResult($resultArray);

	} // end for

// Close connection
	$resultArray = ftp_close($conn_id);
	$success2 = getResult($resultArray);
	if ($success2 == false) { printErrorMessage($resultArray, ""); }

// -------------------------------------------------------------------------
// Transfer temp file to browser
// -------------------------------------------------------------------------

	for ($k=0; $k<count($selectedEntries); $k++) {

		$fileType = getFileType($selectedEntries[$k]);

// --------------------
// Headers, see http://www.php.net/manual/en/function.header.php
// --------------------
// Content-type, for a complete list, see http://www.isi.edu/in-notes/iana/assignments/media-types/media-types
// Content-disposition: http://www.w3.org/Protocols/HTTP/Issues/content-disposition.txt

		if ($fileType == "TEXT") {
			header("Content-type: text/plain"); 
			header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); 
		}
		elseif ($fileType == "IMAGE") {
			if (ereg("(.*).jpg", $selectedEntries[$k], $regs) == true)     { header("Content-type: image/jpeg"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).png", $selectedEntries[$k], $regs) == true) { header("Content-type: image/png");  header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).gif", $selectedEntries[$k], $regs) == true) { header("Content-type: image/gif");  header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
		}
		elseif ($fileType == "ARCHIVE") {
			if (ereg("(.*).zip", $selectedEntries[$k], $regs) == true)     { header("Content-type: application/zip"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			else {
				header("Content-type: application/octet-stream");
				header("Content-Disposition: inline; filename=\"$selectedEntries[$k]\"");
			}
		}
		elseif ($fileType == "OFFICE") {
			if (ereg("(.*).doc", $selectedEntries[$k], $regs) == true)     { header("Content-type: application/msword"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).xls", $selectedEntries[$k], $regs) == true) { header("Content-type: application/vnd.ms-excel"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).ppt", $selectedEntries[$k], $regs) == true) { header("Content-type: application/vnd.ms-powerpoint"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			elseif (ereg("(.*).mpp", $selectedEntries[$k], $regs) == true) { header("Content-type: application/vnd.ms-project"); header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); }
			else {
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\"");
			}
		}
		else { 
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"$selectedEntries[$k]\""); 
		}

// Size (allows the progress to be shown in the download popup of the browser)
		header("Content-Length: ". filesize("$application_tempdir/$selectedEntries[$k].txt")); 

// --------------------
// Send file
// --------------------
		$handle = fopen("$application_tempdir/$selectedEntries[$k].txt" , "r"); 
		fpassthru($handle);

	} // End for

// -------------------------------------------------------------------------
// Delete temp files
// -------------------------------------------------------------------------
	for ($k=0; $k<count($selectedEntries); $k++) {
		$success2 = unlink("$application_tempdir/$selectedEntries[$k].txt");
	} // End for

} // End function ftp_downloadfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function acceptFiles($uploadedFilesArray, $application_tempdir) {

// --------------
// This PHP function takes files that were just uploaded with HTTP POST, verifies if the size is smaller than
// a certain value, and moves them (move_uploaded_file) to a certain directory
// --------------


// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $max_upload_size;

	$j = 1; // Index of the files that have been treated correctly
	for ($i=1; $i<=sizeof($uploadedFilesArray); $i++) {

// -------------------------------------------------------------------------
// 1 -- Get the data from the filesArray (for each file, its location, name, size, ftpmode
// -------------------------------------------------------------------------
		$userfile_temp = $uploadedFilesArray["$i"]["temppathname"];
		$userfile_name = $uploadedFilesArray["$i"]["name"];
		$userfile_size = $uploadedFilesArray["$i"]["size"];

		if ($userfile_size > 0) {

// -------------------------------------------------------------------------
// 2 -- check size of the file
// -------------------------------------------------------------------------
			if ($userfile_size > $max_upload_size) { echo "<li> File nr $i $userfile_name ($userfile_size Bytes) is too big (>$max_upload_size). Breaking."; break; }

// -------------------------------------------------------------------------
// 3 -- upload and copy the file; if a file with the same name already exists, it is overwritten with the new file
// -------------------------------------------------------------------------
			$success2 = move_uploaded_file($userfile_temp, "$application_tempdir/$userfile_name.txt");
			if ($success2 == false) { echo "<li> File nr $i $userfile_name could not be moved\n"; }

			else { echo "<li> File $i <b>$userfile_name</b> is OK\n"; }

// -------------------------------------------------------------------------
// 4 -- if everything went fine, put file in acceptedFilesArray
// -------------------------------------------------------------------------
			if ($success2 == true) {
				$acceptedFilesArray[$j] = $userfile_name;
				$j = $j + 1;
			} // End if success2

		} // End if userfile_size

	} // End for

	return putResult(true, $acceptedFilesArray, "", "", "");

} // End function acceptFiles
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function unzipFiles($archiveFilesArray, $application_tempdir) {

// --------------
// This PHP function takes an array of archives, and unzips the files to the specified directory
// --------------

	$j = 1; // Index of the files that have been treated correctly
	for ($i=1; $i<=sizeof($archiveFilesArray); $i++) {

// -------------------------------------------------------------------------
// 1 -- Open zip file
// -------------------------------------------------------------------------
		echo "<li> Unzipping archive $i <b>$archiveFilesArray[$i]</b>\n";
		echo "<ul>\n";

		$zip = zip_open($archiveFilesArray[$i]);

		if ($zip) {

   			while ($zip_entry = zip_read($zip)) {
			echo "<li> Unzipped file <b>" . zip_entry_name($zip_entry)  . "</b>\n";

//				echo "Name:               " . zip_entry_name($zip_entry) . "\n";
//				echo "Actual Filesize:    " . zip_entry_filesize($zip_entry) . "\n";
//				echo "Compressed Size:    " . zip_entry_compressedsize($zip_entry) . "\n";
//				echo "Compression Method: " . zip_entry_compressionmethod($zip_entry) . "\n";
				if (zip_entry_open($zip, $zip_entry, "r")) {
//					echo "File Contents:\n";
					$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
//					echo "$buf\n";

// Filename of the zip file on the PHP server: zipfilename + .txt
					$tempfilename = zip_entry_name($zip_entry);

// Write the buffer to a file
					$handle = fopen("$application_tempdir/$tempfilename.txt", "w");
					if ($handle == false) { return putResult("false", "", "fopen", "unzipFiles > fopen: tempfilename=$tempfilename.", "Unable to open the temporary file<br>"); break; }

					$success1 = fwrite($handle, $buf);
					if ($success1 == false) { return putResult("false", "", "fwrite", "unzipFiles > fwrite: handle=$handle; string=$string.", "Unable to write to the temporary file<br>"); break; }

					if ($success2 == true) {
						$unzippedFilesArray[$j] = $tempfilename;
						$j = $j + 1;
					}

					$success2 = fclose($handle);
					if ($success2 == false) { return putResult("false", "", "fclose", "unzipFiles > fclose: handle=$handle.", "Unable to write to the temporary file<br>"); }
				
				} // End if

				zip_entry_close($zip_entry);

			} // end while

		}// end if

		zip_close($zip);
		echo "</ul>\n";

	} // End for

	return putResult(true, $unzippedFilesArray, "", "", "");

} // End function unzipFiles

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function ftp_uploadfiles($ftpFilesArray, $application_tempdir, $targetDir) {

//function ftp_uploadfiles($conn_id, $applicationTempDir, $targetDir, $filesArray)

// --------------
// This PHP function takes a file that was uploaded from a client computer via a browser to the web server, 
// and puts it on another FTP server
// --------------

// Determine which FTP mode should be used
	$ftpModes = ftpAsciiBinary($ftpFilesArray);

// Open connection
	$resultArray = ftp_open();
	$conn_id = getResult($resultArray);
	if ($conn_id == false) { return putResult("false", "", "ftp_open", "ftp_uploadfiles > $resultArray[drilldown]", "$resultArray[message]"); }

// Put files
	for ($i=1; $i<=sizeof($ftpFilesArray); $i++) {

		$resultArray = ftp_putfile($conn_id, "$application_tempdir", "$ftpFilesArray[$i].txt", $targetDir, $ftpFilesArray[$i], $ftpModes[$i], "move");
		$success2 = getResult($resultArray);
		if ($success2 == false) { echo "<li> File $i <b>$ftpFilesArray[$i]</b> could not be transferred to the FTP server\n";}
		if ($success2 == true)  { echo "<li> File $i <b>$ftpFilesArray[$i]</b> has been transferred to the FTP server using FTP mode <b>$ftpModes[$i]</b>\n"; }

	} // End for

// Close connection
	$resultArray = ftp_close($conn_id);
	$success2 = getResult($resultArray);
	if ($success2 == false) { return putResult("false", "", "ftp_close", "ftp_uploadfiles > $resultArray[drilldown]", "$resultArray[message]"); }


	return putResult(true, true, "", "", "");

} // End function ftp_uploadfiles
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function upDir($directory) {

// --------------
// This function takes a directory string and returns the parent directory string
// --------------
// directory = /david/cv
// parts = Array ( [0] => [1] => david [2] => cv ) 
// count($parts) = 3

	$parts = explode("/", $directory);

	$parentdirectory = "";
	for ($i=1; $i<count($parts)-1; $i++) {
		$parentdirectory = $parentdirectory . "/" . $parts[$i];
	}

	return $parentdirectory;

} // End function upDir
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function stripDirectory($directory) {

// --------------
// Returns the directory in the format home/dh1234/test (NO leading /, NO trailing /)
// --------------

	$directory = trim($directory);

	$firstchar = substr($directory, 0, 1);
	$lastchar  = substr($directory, strlen($directory)-1, 1);

// Remove a / in front if needed
	if ($firstchar == "/") { $directory= substr($directory, 1, strlen($directory)-1); }
// Remove a / at the end if needed
	if ($lastchar  == "/") { $directory= substr($directory, 0, strlen($directory)-1); }

	return $directory;

} // end stripDirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function glueDirectories($part1, $part2) {

// --------------
// Returns the 2 dirs glued together in the format /home/dh1234/test (leading /, NO trailing /)
// --------------

	$part1 = stripDirectory($part1);
	$part2 = stripDirectory($part2);

	if (strlen($part1)>0 && strlen($part2)>0) {
		return $part1 . "/" . $part2;
	}

	elseif ((strlen($part1)<1 || $part1 == "/")   &&   (strlen($part2)>0)) {
		return "/" . $part2;
	}
	elseif ((strlen($part2)<1 || $part2 == "/")   &&   (strlen($part1)>0)) {
		return "/" . $part1;
	}
	else {
		return "";
	}
} // end glueDirectories
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function ftpAsciiBinary($filenameArray) {

// --------------
// Checks the extension of a file to see if it should be transferred in ASCII or Binary mode
//
//	Default: FTP_ASCII
//	Exceptions: FTP_BINARY (see list)
//	No extension: FTP_ASCII
//	A file with more than 1 dot: the last extension is taken into account
//
// --------------

	for ($k=0; $k<=count($filenameArray); $k++) {
// k=0 to k<=count so that this function would be able to handle arrays both from 0 to n-1 and from 1 to n.

		if (ereg("(.*)[\.]([^\.]*)", $filenameArray[$k], $regs) == true) {

			// Any character
			// Followed by a dot
			// Followed by any character except a dot

			$first = "$regs[1]";
			$last = "$regs[2]";
		}

		if ($last == "png"  || 
		$last == "jpg"  || 
		$last == "jpeg" || 
		$last == "gif"  ||
		$last == "bmp"  ||
		$last == "tif"  ||
		$last == "tiff" ||

		$last == "exe"  || 
		$last == "com"  ||
		
		$last == "doc"  || 
		$last == "xls"  || 
		$last == "ppt"  || 
		$last == "mdb"  || 
		$last == "vsd"  || 
		$last == "mpp"  ||

		$last == "zip"  || 
		$last == "tar"  || 
		$last == "gz"   || 
		$last == "arj"  || 
		$last == "arc"  ||
		$last == "bin"  || 

		$last == "mov"  || 
		$last == "mpg"  || 
		$last == "mpeg" ||
		$last == "ram"  ||
		$last == "rm"   ||
		$last == "qt"   ||

		$last == "swf"  ||
		$last == "fla"  ||

		$last == "pdf"  ||
		$last == "ps"   ||

		$last == "wav" )	{ $ftpModes[$k] = "FTP_BINARY"; }
		else 			{ $ftpModes[$k] = "FTP_ASCII"; }

	} // End for

	return $ftpModes;

} // end ftpAsciiBinary

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function getFileType($filename) {

// --------------
// Checks the extension of a file to determine what should be done with it in the View and Edit functions
// Default: TEXT
// Exceptions (see list below): IMAGE, EXECUTABLE, OFFICE, ARCHIVE
// --------------

	if (ereg("(.*)[\.]([^\.]*)", $filename, $regs) == true) {

		// Any character
		// Followed by a dot
		// Followed by any character except a dot

		$first = "$regs[1]";
		$last = "$regs[2]";
	}

	if (	$last == "png"  || 
		$last == "jpg"  || 
		$last == "jpeg" || 
		$last == "gif"  ||
		$last == "bmp"  ||
		$last == "tif"  ||
		$last == "tiff"     ) { return "IMAGE"; }

	elseif ($last == "exe"  || 
		$last == "com"      ) { return "EXECUTABLE"; }

	elseif ($last == "doc"  || 
		$last == "xls"  || 
		$last == "ppt"  || 
		$last == "mdb"  || 
		$last == "vsd"  || 
		$last == "mpp"      ) { return "OFFICE"; }

	elseif ($last == "zip"  || 
		$last == "tar"  || 
		$last == "gz"   || 
		$last == "arj"  || 
		$last == "arc"      ) { return "ARCHIVE"; }

	elseif ($last == "bin"  || 

		$last == "mov"  || 
		$last == "mpg"  || 
		$last == "mpg"  ||
		$last == "ram"  ||
		$last == "rm"   ||
		$last == "qt"   ||

		$last == "swf"  ||
		$last == "fla"  ||

		$last == "pdf"  ||
		$last == "ps"   ||

		$last == "wav"       ) { return "OTHER"; }

	else 			      { return "TEXT"; }


} // end getFileType

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function cleanFtpserver($ftpserver) {

// --------------
// Input: " ftp://something.domainname.com:123/directory/file "
// Output: "something.domainname.com"
// --------------

// Remove unvisible characters in the beginning and at the end
	$cleaned = trim($ftpserver);

// Remove possible "ftp://"
	if (ereg("[ftpFTP]{2,4}[:]{1}[/\\]{1,2}(.*)", $cleaned, $regs) == true) {
		$cleaned = "$regs[1]";
	}

// Remove a possible port nr ":123"
	if (ereg("(.*)[:]{1}[0-9]+", $cleaned, $regs) == true) {
		$cleaned = "$regs[1]";
	}

// Remove a possible trailing / or \ 
// Remove a possible directory and file "/directory/file"
	if (ereg("([^/^\\]*)[/\\]{1,}.*", $cleaned, $regs) == true) {
		// Any characters except / and except \
		// Followed by at least one / or \
		// Followed by any characters
		$cleaned = "$regs[1]";
	}

	return $cleaned;

} // end cleanFTPserver

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function cleanDirectory($directory) {

// --------------
// Input: "/dir1/dir2/dir3/../../dir4/dir5"
// Output: "/dir1/dir4/dir5"
// --------------

// Nothing to do if the directory is the root directory
	if ($directory == "" || $directory == "/") { return $directory; }

// Remove leading and trailing "/"
	$directory = stripDirectory($directory);

// Break down into parts
// directoryparts[0] contains the first part, directoryparts[1] the second,...
	$directoryparts = explode("/", $directory);

// Start from the end
// If you encounter N times a "..", do not take into account the next N parts which are not ".."
// Example: "/dir1/dir2/dir3/../../dir4/dir5"  ---->  "/dir1/dir4/dir5"
	$dubbledotcounter = 0;
	$newdirectory = "";
	for ($i=sizeof($directoryparts)-1; $i>=0; $i = $i - 1) {
		if ($directoryparts[$i] == "..") { $doubledotcounter = $doubledotcounter + 1; }
		else {  
			if ($doubledotcounter == 0) { $newdirectory = $directoryparts[$i] . "/" . $newdirectory; }    // Add the new part in front
			elseif ($doubledotcounter > 0) { $doubledotcounter = $doubledotcounter - 1; }                 // Don't add the part, and reduce the counter by 1
		}
	}

	$newdirectory = "/" . stripDirectory($newdirectory);

	return $newdirectory;

} // end cleanDirectory

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



?>