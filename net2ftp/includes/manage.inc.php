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
function manage($manage, $directory, $entry, $selectedEntries, $newNames, $dirorfile, $formresult, $chmodStrings, $targetDirectories, $copymovedelete, $text, $uploadedFilesArray, $uploadedZipFilesArray) {

// --------------
// This function allows to manage a file: view/edit/rename/delete
// The real action is done in subfunctions
// --------------

// Check that at least one entry was chosen
	if (is_array($selectedEntries) == false && ($manage == "renamedirectory" || $manage == "chmoddirectory" || $manage == "copydirectory" || $manage == "movedirectory" || $manage == "deletedirectory" || $manage == "renamefile" || $manage == "chmodfile" || $manage == "copyfile" || $manage == "movefile" || $manage == "deletefile")) {
		$resultArray[message] = "Please select at least one directory or file !";
  		printErrorMessage($resultArray, "exit");
	}

	switch ($manage) {

// Directories
		case "renamedirectory":
			renameentry($directory, $selectedEntries, $newNames, "directory", $formresult);	
		break;
		case "chmoddirectory":
			chmodentry($directory, $selectedEntries, $chmodStrings, "directory", $formresult);
		break;
		case "copydirectory":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "copy", "directory", $formresult);
		break;
		case "movedirectory":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "move", "directory", $formresult);
		break;
		case "deletedirectory":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "delete", "directory", $formresult);
		break;
		case "newdirectory":
			newdirectory($directory, $newNames, $formresult);
		break;

// Files
		case "view":
			view($directory, $entry);
		break;
		case "edit":
			edit($directory, $entry, $text, $formresult);
		break;
		case "renamefile":
			renameentry($directory, $selectedEntries, $newNames, "file", $formresult);
		break;
		case "chmodfile":
			chmodentry($directory, $selectedEntries, $chmodStrings, "file", $formresult);
		break;
		case "copyfile":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "copy", "file", $formresult);
		break;
		case "movefile":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "move", "file", $formresult);
		break;
		case "deletefile":
			copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, "delete", "file", $formresult);
		break;
		case "downloadfile":
			downloadfile($directory, $entry, $formresult);
		break;
		case "newfile":
			edit($directory, $newNames[0], $text, $formresult);
		break;
		case "uploadfile":
			uploadfile($directory, $uploadedFilesArray, $uploadedZipFilesArray, $formresult);
		break;
		default:
			$resultArray[message] = "Unexpected manage string. Exiting."; 
  			printErrorMessage($resultArray, "exit");
  		break;

		} // End switch

} // End function manage
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function renameentry($directory, $selectedEntries, $newNames, $dirorfile, $formresult) {

// --------------
// This function allows to rename a directory or file $entry to $newentry
// --------------

// -------------------------------------------------------------------------
// Initial checks
// -------------------------------------------------------------------------

	if ($dirorfile != "directory") { $dirorfile = "file"; }

	if ($dirorfile == "directory") { printTitle("Rename directory"); }
	elseif ($dirorfile == "file")  { printTitle("Rename file"); }

	printBack($directory);

	echo "<table align=\"center\">\n";
	echo "<tr>\n";
	echo "<td>\n";

// -------------------------------------------------------------------------
// Form
// -------------------------------------------------------------------------

	if ($formresult != "result") {
		echo "<form name=\"RenameForm\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\">\n";
		if ($dirorfile == "directory") { echo "<input type=\"hidden\" name=\"manage\" value=\"renamedirectory\">\n"; }
		elseif ($dirorfile == "file")  { echo "<input type=\"hidden\" name=\"manage\" value=\"renamefile\">\n"; }
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\">\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\">\n";

		for ($k=0; $k<count($selectedEntries); $k++) {
			echo "<input type=\"hidden\" name=\"selectedEntries[]\" value=\"$selectedEntries[$k]\">\n";
			echo "Old name: <b>$selectedEntries[$k]</b><br>\n";
			echo "New name: <input type=\"text\" name=\"newNames[]\" value=\"$selectedEntries[$k]\"><br><br>\n";
		} // End for

		echo "<div style=\"text-align: center;\"><input type=\"submit\" id=\"button\" value=\"Save\"></div>\n";
		echo "</form>\n";
	}

// -------------------------------------------------------------------------
// Result
// -------------------------------------------------------------------------

	elseif ($formresult == "result") {

// Open connection
	$resultArray = ftp_open();
	$conn_id = getResult($resultArray);
	if ($conn_id == false) { return putResult(false, "", "ftp_open", "renameentry > $resultArray[drilldown]", $resultArray[message]); exit(); }

// Rename files
		for ($k=0; $k<count($selectedEntries); $k++) {
			if (strstr($selectedEntries[$k], "..") != false) {
				echo "The new filename may not contain any dots. The file was not renamed to <b>$selectedEntries[$k]</b>.<br>";
				break;
			}
			$resultArray = ftp_myrename($conn_id, $directory, $selectedEntries[$k], $newNames[$k]);	// filesystem.inc.php
			$success = getResult($resultArray);
			if ($success ==	false) { printErrorMessage($resultArray, ""); break; }
			else { echo "<b>$selectedEntries[$k]</b> was successfully renamed to <b>$newNames[$k]</b><br>"; }
		} // End for

// Close connection
	$resultArray = ftp_close($conn_id);
	$success2 = getResult($resultArray);
	if ($success2 == false) { return putResult(false, "", "ftp_close", "renameentry > $resultArray[drilldown]", $resultArray[message]); }

	} // End if elseif (form or result)

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";

} // End function renameentry
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function chmodentry($directory, $selectedEntries, $chmodStrings, $dirorfile, $formresult) {

// --------------
// This function allows to chmod a directory or file
// The initial permissions are contained in chmodstring, and are coming from the browse view
// The permissions to be set are contained in chmodoctal
// --------------

// -------------------------------------------------------------------------
// Initial checks
// -------------------------------------------------------------------------

	if ($dirorfile != "directory") { $dirorfile = "file"; }

	if ($dirorfile == "directory") { printTitle("Chmod directory"); }
	elseif ($dirorfile == "file")  { printTitle("Chmod file"); }

	printBack($directory);

	echo "<table align=\"center\">\n";
	echo "<tr>\n";
	echo "<td>\n";

// -------------------------------------------------------------------------
// Form
// -------------------------------------------------------------------------

	if ($formresult != "result") {

		echo "Permissions:\n";
		echo "<ul>\n";
		echo "<li> - = no permission<br>\n";
		echo "<li> r = read<br>\n";
		echo "<li> w=write<br>\n";
		echo "<li> x=execute<br>\n";
		echo "<li> Any other letter will be interpreted as \"-\".<br>\n";
		echo "</ul>\n";
		echo "The first 3 letters set the permissions for the Owner, the next 3 letters are for the Group and the last 3 letters are for the Others.<br>\n";

		echo "<form name=\"ChmodForm\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\">\n";
		if ($dirorfile == "directory") { echo "<input type=\"hidden\" name=\"manage\" value=\"chmoddirectory\">\n"; }
		elseif ($dirorfile == "file")  { echo "<input type=\"hidden\" name=\"manage\" value=\"chmodfile\">\n"; }
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\">\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\">\n";

		for ($k=0; $k<count($selectedEntries); $k++) {
			echo "<input type=\"hidden\" name=\"selectedEntries[]\" value=\"$selectedEntries[$k]\">\n";
			echo "<b>$selectedEntries[$k]</b> set permissions to: <input type=\"text\" name=\"chmodStrings[]\" value=\"$chmodStrings[$k]\" size=\"9\" maxlength=\"9\" style=\"font-size: 16;\"><br><br>\n";
		} // End for

		echo "<div style=\"text-align: center;\"><input type=\"submit\" id=\"button\" value=\"Save\"></div>\n";
		echo "</form>\n";
	}

// -------------------------------------------------------------------------
// Result
// -------------------------------------------------------------------------

	elseif ($formresult == "result") {

// Open connection
	$resultArray = ftp_open();
	$conn_id = getResult($resultArray);
	if ($conn_id == false) { return putResult(false, "", "ftp_open", "chmodentry > $resultArray[drilldown]", $resultArray[message]); exit(); }

// Chmod entries
		for ($k=0; $k<count($selectedEntries); $k++) {

			if (substr($chmodStrings[$k], 0, 1) == "r") { $owner_read = "1"; }
			if (substr($chmodStrings[$k], 1, 1) == "w") { $owner_write = "2"; }
			if (substr($chmodStrings[$k], 2, 1) == "x") { $owner_execute = "4"; }

			if (substr($chmodStrings[$k], 3, 1) == "r") { $group_read = "1"; }
			if (substr($chmodStrings[$k], 4, 1) == "w") { $group_write = "2"; }
			if (substr($chmodStrings[$k], 5, 1) == "x") { $group_execute = "4"; }

			if (substr($chmodStrings[$k], 6, 1) == "r") { $other_read = "1"; }
			if (substr($chmodStrings[$k], 7, 1) == "w") { $other_write = "2"; }
			if (substr($chmodStrings[$k], 8, 1) == "x") { $other_execute = "4"; }

			$ownerOctal = $owner_read + $owner_write + $owner_execute;
			$groupOctal = $group_read + $group_write + $group_execute;
			$otherOctal = $other_read + $other_write + $other_execute;
			
			$chmodOctal = $ownerOctal . $groupOctal . $otherOctal;

			if ($chmodOctal > 777 || $chmodOctal < 0) {
				$resultArray[message] = "The chmod nr <b>$chmodOctal</b> is out of the range 000-777. Please try again.\n";
				printErrorMessage($resultArray, "exit");
			}

			$resultArray = ftp_mychmod($conn_id, $directory, $selectedEntries[$k], $chmodOctal);	// filesystem.inc.php
			$success = getResult($resultArray);
			if ($success ==	false) { printErrorMessage($resultArray, ""); }
			else { echo "The permissions on <b>$directory/$selectedEntries[$k]</b> were successfully changed to <b>$chmodStrings[$k]</b>.<br>"; }

		} // End for

// Close connection
	$resultArray = ftp_close($conn_id);
	$success2 = getResult($resultArray);
	if ($success2 == false) { return putResult(false, "", "ftp_close", "chmodentry > $resultArray[drilldown]", $resultArray[message]); }

	} // End if elseif (form or result)

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";


} // End function chmodentry
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function copymovedeleteentry($directory, $selectedEntries, $targetDirectories, $newNames, $copymovedelete, $dirorfile, $formresult) {

// --------------
// This function allows to copy or move a directory or file
// --------------

// -------------------------------------------------------------------------
// Initial checks
// -------------------------------------------------------------------------
	if (($copymovedelete != "move" && $copymovedelete != "delete")) { $copymovedelete = "copy"; }
	if ($dirorfile != "directory") { $dirorfile = "file"; }

	if ($copymovedelete == "copy" && $dirorfile == "directory") { printTitle("Copy directories"); }
	elseif ($copymovedelete == "copy" && $dirorfile == "file") { printTitle("Copy files"); }
	elseif ($copymovedelete == "move" && $dirorfile == "directory") { printTitle("Move directories"); }
	elseif ($copymovedelete == "move" && $dirorfile == "file") { printTitle("Move files"); }
	elseif ($copymovedelete == "delete" && $dirorfile == "directory") { printTitle("Delete directories"); }
	elseif ($copymovedelete == "delete" && $dirorfile == "file") { printTitle("Delete files"); }

	printBack($directory);

	echo "<table align=\"center\">\n";
	echo "<tr>\n";
	echo "<td>\n";

// -------------------------------------------------------------------------
// Show form
// -------------------------------------------------------------------------
	if ($formresult != "result") {
// Hidden stuff
		echo "<form name=\"CopyMoveDeleteForm\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\">\n";
		echo "<input type=\"hidden\" name=\"manage\" value=\"$copymovedelete$dirorfile\">\n";
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\">\n";
		echo "<input type=\"hidden\" name=\"copymovedelete\" value=\"$copymovedelete\">\n";
		echo "<input type=\"hidden\" name=\"dirorfile\" value=\"$dirorfile\">\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\">\n";
// Title and text
//		if ($copymovedelete == "copy") { echo "Copy $dirorfile<br><br>\n"; }
//		elseif ($copymovedelete == "move")   { echo "Move $dirorfile<br><br><br>\n"; }
		if ($copymovedelete == "delete" && $dirorfile == "directory") { echo "Are you sure you want to delete these directories</b>?<br>Note that all its subdirectories and files will also be deleted!<br><br>\n"; }
		elseif ($copymovedelete == "delete" && $dirorfile == "file") { echo "Are you sure you want to delete these files?<br><br>\n"; }

// Header directory and button to copy text to all target directory textboxes -- only for copy/move
		if ($copymovedelete != "delete") {
			echo "<input type=\"text\" name=\"headerDirectory\" value=\"$directory\">\n";
			echo "<input type=\"button\" id=\"extralongbutton\" value=\"Set all targetdirectories\" onClick=\"CopyToAll(document.CopyMoveDeleteForm)\"><br>\n";
			echo "<div style=\"font-size: 80%\">To set a common target directory, enter that target directory in the textbox above and click on the button \"Set all targetdirectories\". Note: the target directory must already exist before anything can be copied into it.</div><br><br>\n";
			echo "<script language=\"JavaScript\"><!--\n";
			echo "function CopyToAll(myform) {\n";
			echo "   for (var i = 0; i < myform.elements.length; i++) {\n";
			echo "       if (myform.elements[i].name.indexOf('targetDirectories') >= 0) {\n";
			echo "           myform.elements[i].value = myform.headerDirectory.value;\n";
			echo "       }\n";
			echo "   }\n";
			echo "}\n";
			echo "//--></script>\n";
		} // End if

		for ($k=0; $k<count($selectedEntries); $k++) {
// Basic, for both copy/move as for delete
			echo "<input type=\"hidden\" name=\"selectedEntries[]\" value=\"$selectedEntries[$k]\">\n";
			if ($copymovedelete == "copy") { echo "Copy $dirorfile <b>$selectedEntries[$k]</b> to:<br>\n"; }
			elseif ($copymovedelete == "move") { echo "Move $dirorfile <b>$selectedEntries[$k]</b> to:<br>\n"; }
			elseif ($copymovedelete == "delete") { echo "Delete $dirorfile <b>$selectedEntries[$k]</b><br>\n"; }
// Options
//    Copy or move: ask for options
			if ($copymovedelete != "delete") {
				echo "Target directory: <input type=\"text\" name=\"targetDirectories[]\" value=\"$directory\"> (directory must exist)<br>\n";
				echo "Target name: <input type=\"text\" name=\"newNames[]\" value=\"$selectedEntries[$k]\"><br><br><br>\n";
			}
//    Delete: no targetdirectory and ftpmode are not applicable
			else {
				echo "<input type=\"hidden\" name=\"targetDirectories[]\" value=\"\">\n";
				echo "<input type=\"hidden\" name=\"newNames[]\" value=\"\">\n";
			}
		} // End for

// Submit buttons
		echo "<div style=\"text-align: center; margin-top: 20px;\">\n"; 
		if ($copymovedelete == "copy")       { echo "<input type=\"submit\" id=\"button\" value=\"Copy\">\n"; }
		elseif ($copymovedelete == "move")   { echo "<input type=\"submit\" id=\"button\" value=\"Move\">\n"; }
		elseif ($copymovedelete == "delete") { echo "<input type=\"submit\" id=\"button\" value=\"Delete\">\n"; }
		echo "</div>\n";
		echo "</form>\n";
	}

// -------------------------------------------------------------------------
// Show result
// -------------------------------------------------------------------------
	elseif ($formresult == "result") {

	if ($dirorfile == "file") {
		$ftpModes = ftpAsciiBinary($selectedEntries);
	}
// Open connection
		$resultArray = ftp_open();
		$conn_id = getResult($resultArray);
		if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }

// ------------------------------
		for ($k=0; $k<count($selectedEntries); $k++) {

// Check entries
			if ("$directory/$selectedEntries[$k]" == "$targetDirectories[$k]") { 
				echo "Directory <b>$directory/$selectedEntries[$k]</b> may not be copied or moved into itself -- this would create an infinite loop!<br>\n";
				break;
			}

// Copy/Move/Delete
			if ($dirorfile == "directory") { $resultArray = ftp_copymovedeletedirectory($conn_id, $directory, $selectedEntries[$k], $targetDirectories[$k], $newNames[$k], $copymovedelete, "0"); }
			elseif ($dirorfile == "file")  { $resultArray = ftp_copymovedeletefile($conn_id, $directory, $selectedEntries[$k], $targetDirectories[$k], $newNames[$k], $ftpModes[$k], $copymovedelete); }
			$success1 = getResult($resultArray);

// Do not print message below, function always returns true; read messages from function...
//			if ($success1 == true && $copymovedelete == "copy")   { echo "<br>The $dirorfile <b>$directory/$selectedEntries[$k]</b> was successfully copied to <b>$targetDirectories[$k]/$newNames[$k]</b>.<br>\n"; }
//			elseif ($success1 == true && $copymovedelete == "move")   { echo "<br>The $dirorfile <b>$directory/$selectedEntries[$k]</b> was successfully moved to <b>$targetDirectories[$k]/$newNames[$k]</b>.<br>\n"; }
//			elseif ($success1 == true && $copymovedelete == "delete") { echo "<br>The $dirorfile <b>$directory/$selectedEntries[$k]</b> was successfully deleted.<br>\n"; }

		} // End for
// ------------------------------

// Close connection
		$resultArray = ftp_close($conn_id);
		$success2 = getResult($resultArray);
		if ($success2 == false) { printErrorMessage($resultArray, ""); }

	} // End if elseif (form or result)

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

} // End function copymovedeleteentry 
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
 function newdirectory($directory, $newNames, $formresult) {

// --------------
// This function allows to make a new directory
// --------------

	printTitle("Make new directory");
	printBack($directory);
	echo "<table align=\"center\">\n";
	echo "<tr>\n";
	echo "<td>\n";

	if (strlen($directory) > 0) { $printdirectory = $directory; }
	else                        { $printdirectory = "/"; }

// -------------------------------------------------------------------------
// Show form
// -------------------------------------------------------------------------

	if ($formresult != "result") {
		echo "The new directories will be created in <b>$printdirectory</b>.<br>\n";
		echo "<form name=\"NewForm\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\">\n";
		echo "<input type=\"hidden\" name=\"manage\" value=\"newdirectory\">\n";
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\">\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\">\n";
		for ($k=0; $k<5; $k++) {
			echo "New directory name: <input type=\"text\" name=\"newNames[]\"><br><br>\n";
		} // End for
		echo "<div style=\"text-align: center;\"><input type=\"submit\" id=\"button\" value=\"Create\"></div>\n";
		echo "</form>\n";
	}

// -------------------------------------------------------------------------
// Show result
// -------------------------------------------------------------------------

	elseif ($formresult == "result") {

// Open connection
		$resultArray = ftp_open();
		$conn_id = getResult($resultArray);
		if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }

		for ($k=0; $k<count($newNames); $k++) {
			if (strlen($newNames[$k]) > 0) {
// Create new directories
				$newsubdir = glueDirectories($directory, $newNames[$k]);		// filesystem.inc.php
				$resultArray = ftp_newdirectory($conn_id, $newsubdir);
				$success = getResult($resultArray);
				if ($success == false)  { printErrorMessage($resultArray, ""); }
				else { echo "Directory <b>$newNames[$k]</b> was successfully created.<br>"; }
			} // End if
		} // End for

// Close connection
		$resultArray = ftp_close($conn_id);
		$success2 = getResult($resultArray);
		if ($success2 == false) { printErrorMessage($resultArray, ""); }


	} // End if elseif (form or result)

	echo "</tr>\n";
	echo "</td>\n";
	echo "</table>\n";

} // End function newdirectory
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function view($directory, $entry) {

// --------------
// This function allows to view a file
// --------------

	printTitle("View file $entry");
	printBack($directory);

	$resultArray = ftp_readfile($directory, $entry); // see filesystem.inc.php
	$text = getResult($resultArray); // see filesystem.inc.php
	if ($text == false)  { printErrorMessage($resultArray, "exit"); }

	printCode($directory, $entry, $text);

} // End function view
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function edit($directory, $entry, $text, $formresult) {

// --------------
// This function allows to edit a file in a regular textarea
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $manage;
global $application_rootdir;

// -------------------------------------------------------------------------
// First step: show edit form
// -------------------------------------------------------------------------
	if ($formresult != "result") {
		if ($manage == "edit") { 
			$resultArray = ftp_readfile($directory, $entry); // see filesystem.inc.php
			$text_fromfile = getResult($resultArray); // see filesystem.inc.php
			if ($text_fromfile == false)  { echo printErrorMessage($resultArray, "exit"); }
		}
		elseif ($manage == "newfile") { 
			$handle = fopen("$application_rootdir/template.txt", "r"); // Open the local template file for reading only
			if ($handle == false) { echo "Unable to open the temporary file"; exit(); }

			clearstatcache(); // for filesize

			$text_fromfile = fread($handle, filesize("$application_rootdir/template.txt"));
			if ($text_fromfile == false) { echo "Unable to read the temporary file"; exit(); }


			$success1 = fclose($handle);
//			if ($success1 == false) { echo "Unable to close the temporary file"; }

		}
		printEditForm($directory, $entry, $text_fromfile, "notsavedyet");
	} 
// -------------------------------------------------------------------------
// Second step: save to remote file, and show View/Edit screen
// -------------------------------------------------------------------------
	elseif ($formresult == "result") {
		// http://www.php.net/manual/en/configuration.php#ini.magic-quotes-gpc (by the way: gpc = get post cookie)
		// if (magic_quotes_gpc == 1), then PHP converts automatically " --> \", ' --> \'
		// Has only to be done when getting info from get post cookie
		if (get_magic_quotes_gpc() == 1) {
			$quote = "'";
			$doublequote = "\"";
			$backslash = "\\";
			$text = trim($text);
			$text = str_replace("$backslash$quote", $quote, $text);
			$text = str_replace("$backslash$doublequote", $doublequote, $text);
			$text = str_replace("$backslash$backslash", $backslash, $text);
		}

		if (strlen($entry)<1) { $resultArray[message] = "Please specify a filename.\n"; printErrorMessage($resultArray, "exit"); }

		$resultArray = ftp_writefile($directory, $entry, $text); // see filesystem.inc.php
		$success_save = getResult($resultArray);
		if ($success_save == false)  { printErrorMessage($resultArray, "exit"); }

		printEditForm($directory, $entry, $text, $success_save);
	}

	echo "</div>\n";

} // End function edit
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printCode($directory, $entry, $text) {

// --------------
// This function prints the code
// --------------

	echo "<div id=\"code\">\n";
	echo "<!-- -------------------- Start of code -------------------- -->\n";
	highlight_string($text);
	echo "<!-- -------------------- End  of code  -------------------- -->\n";
	echo "</div>\n";

} // End function printCode
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printEditForm($directory, $entry, $text, $success_save) {

// --------------
// This function prints the form containing the textarea in which text is edited
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $manage;
global $edit_nrofcolumns, $edit_nrofrows, $edit_fontsize, $edit_fontfamily;

	$text = htmlspecialchars($text, ENT_QUOTES);

	if (strlen($directory) > 0) { $printdirectory = $directory; }
	else                        { $printdirectory = "/"; }

	echo "<form name=\"EditForm\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
	printLoginInfo();
	echo "<input type=\"hidden\" name=\"state\" value=\"manage\">\n";
	echo "<input type=\"hidden\" name=\"manage\" value=\"edit\">\n";
	echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\">\n";
	echo "<input type=\"hidden\" name=\"entry\" value=\"$entry\">\n";
	echo "<input type=\"hidden\" name=\"formresult\" value=\"result\">\n";

	echo "<table width=100% height=100% cellspacing=0 cellpadding=2 border=0>\n";

// Row 1, Col1: Directory and Filename
//------------------------------------
	echo "<tr>\n";
	echo "<td valign=\"top\" align=\"left\">\n";
  // Edit ==> print filename
	if ($manage == "edit") {
		echo "<table>\n";
		echo "<tr><td>Directory:</td><td><b>$printdirectory</b></td></tr>\n";
		echo "<tr><td>File:</td><td><b>$entry</b></td></tr>\n";
		echo "</table>\n";
	}
  // Newfile ==> print new filename textbox
	elseif ($manage == "newfile") { 
		echo "<table>\n";
		echo "<tr><td>Directory:</td><td><b>$printdirectory</b></td></tr>\n";
		echo "<tr><td>New file name:</td><td> <input id=\"input\" type=\"text\" name=\"entry\"></td></tr>\n"; 
		echo "</table>\n";
	}
	echo "</td>\n";

// Row 1, Col2
//---------------------------------------
	echo "<td valign=\"top\" align=\"center\">\n";
	echo "</td>\n";

// Row 1, Col3: Buttons and saving-status
//---------------------------------------
	echo "<td valign=\"top\" align=\"right\">\n";
	echo "<input type=\"button\" id=\"button\" value=\"Save\" onClick=\"this.form.submit();\"                                         title=\"Save this file\"> &nbsp;\n";
	echo "<input type=\"button\" id=\"button\" value=\"Open\" onClick=\"window.open('" . printURL($directory, $entry, no) . "');\"    title=\"Open this file in a new window\"> &nbsp;\n";
	echo "<input type=\"button\" id=\"button\" value=\"Back\" onClick=\"document.EditForm.state.value='browse'; this.form.submit();\" title=\"Cancel and go back to the browse view\">\n";
	echo "<br>\n";
	if ($success_save === "notsavedyet") { echo "<div style=\"font-size: 70%;\">This file has not yet been saved</div>\n"; }
	elseif ($success_save === true)      { echo "<div style=\"font-size: 70%;\">This file was saved on <b>" . mytime() . "</b></div>\n"; }
	elseif ($success_save === false)     { echo "<div style=\"font-size: 70%;\"><b>This file could not be saved</b></div>\n"; }
	echo "</td>\n";

	echo "</tr>\n";

// Row 2:       Textarea
//----------------------
	echo "<tr>\n";
	echo "<td colspan=\"3\" valign=\"top\" align=\"left\">\n";
	echo "<div style=\"margin-left: 0px; text-align: left;\">\n";
	echo "\n\n<!-- -------------------- Start of code -------------------- -->\n";
	echo "<textarea name=\"text\" rows=\"$edit_nrofrows\" cols=\"$edit_nrofcolumns\" style=\"font-size: $edit_fontsize; font-family: $edit_fontfamily\" wrap=\"off\">\n";
	echo "$text\n";
	echo "</textarea>\n";
	echo "<!-- -------------------- End  of code  -------------------- -->\n\n\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";


	echo "</table>\n";
	echo "</form>\n";

} // End function printEditForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
// function newfile()
//
//    is now implemented using the edit() function
//
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function uploadfile($directory, $uploadedFilesArray, $uploadedZipFilesArray, $formresult) {

// --------------
// This function allows to upload a file to a directory
// --------------

// Detailed explanation of uploadedFiles, acceptedFiles, ... --> see below at ELSE statement

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $max_upload_size, $application_tempdir;

	printTitle("Upload files");
	printBack($directory);
	echo "<div class=\"normal\">\n";

	if (strlen($directory) > 0) { $printdirectory = $directory; }
	else                        { $printdirectory = "/"; }

// -------------------------------------------------------------------------
// Form
// -------------------------------------------------------------------------

	if ($formresult != "result") {
		echo "The new files will be uploaded in directory <b>$printdirectory</b>.<br> They will overwrite any existing files which have the same name.<br><br>\n";
		$max_upload_size_MB = $max_upload_size / 1000;

		echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"" . printPHP_SELF() . "\" style=\"text-align: center;\">\n";
		printLoginInfo();
		echo "<input type=\"hidden\" name=\"state\" value=\"manage\">\n";
		echo "<input type=\"hidden\" name=\"manage\" value=\"uploadfile\">\n";
		echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\">\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\">\n";
		echo "<input type=\"hidden\" name=\"max_file_size\" value=\"$max_upload_size\">\n"; // in bytes, advisory to browser, easy to circumvent; see also below, in PHP code!
		echo "File 1: <input type=\"file\" id=\"uploadinputbutton\" name=\"userfile1\"><br>\n";
		echo "File 2: <input type=\"file\" id=\"uploadinputbutton\" name=\"userfile2\"><br>\n";
		echo "File 3: <input type=\"file\" id=\"uploadinputbutton\" name=\"userfile3\"><br>\n";
		echo "File 4: <input type=\"file\" id=\"uploadinputbutton\" name=\"userfile4\"><br>\n";
		echo "File 5: <input type=\"file\" id=\"uploadinputbutton\" name=\"userfile5\"><br><br>\n";
// ZIP ADD
//		echo "<div style=\"text-align: left;\">The ZIP files below will be decompressed in directory <b>$printdirectory</b></div><br>\n";
//		echo "ZIP file 1: <input type=\"file\" id=\"uploadinputbutton\" name=\"userzipfile1\"><br>\n";
//		echo "ZIP file 2: <input type=\"file\" id=\"uploadinputbutton\" name=\"userzipfile2\"><br><br>\n";
		echo "<input type=\"submit\" id=\"button\" value=\"Upload\">\n";
		echo "</form>\n";

		echo "<u>Restrictions:</u>\n";
		echo "<div style=\"font-size: 80%\">\n";
		echo "<ul>\n";
		echo "	<li> The maximum size of one file is <b>$max_upload_size_MB kB;</b>\n";
// ZIP ADD
//		echo "	<li> The maximum execution time is 30 seconds (upload, unzipping and transfer to the FTP server);\n";
		echo "	<li> The maximum execution time is 30 seconds (upload and transfer to the FTP server) for the 5 files;\n";
		echo "	<li> The FTP transfer mode (ASCII or BINARY) will be automatically determined, based on the filename extension.\n";
		echo "	<ul>\n";
		echo "		<li> The default mode is ASCII.\n";
		echo "		<li> BINARY is used for images (png, jpg, jpeg and gif), executables (exe and com), MS Office documents (doc, xls, ppt, mdb, vsd and mpp) and archives (zip, tar, arj and arc).\n";
		echo "	</ul>\n";
		echo "</ul>\n";
		echo "</div><br>\n";

	} // End if (show form, show result)


// -------------------------------------------------------------------------
// Result
// -------------------------------------------------------------------------

	else {

// function acceptFiles
//	1. Check size of the files and archives
//	2. Perform function move_uploaded_file to move the files from the 
//	   server tempdir /temp/fx8302 to application tempdir /home/dh1234/application/temp/filename
// function unzipFiles
// 	3. Unzip the archives
// function ftp_uploadfiles
// 	4. For all the files (uploaded and unzipped), check what FTP mode should be used
// 	5. Perform function ftp_putfile to move the files from the application tempdir /home/dh1234/application/temp/filename
//       to the FTP server

// -------------------------------------------------------------------------
// uploadedFiles --> acceptedFiles 1-7                    acceptedFiles 1-5  = ftpFiles  n+5
//                   acceptedFiles 6-7 = archiveFiles --> unzippedFiles n
// -------------------------------------------------------------------------

// uploadedFilesArray["n"]["temppathname"] contains the temporary filename of the file
// uploadedFilesArray["n"]["name"] contains the filename of the file on the client computer
// uploadedFilesArray["n"]["size"] contains the size of the file

// uploadedZipFilesArray["n"]["temppathname"] contains the temporary filename of the file
// uploadedZipFilesArray["n"]["name"] contains the filename of the file on the client computer
// uploadedZipFilesArray["n"]["size"] contains the size of the file

// acceptedFilesArray["n"], acceptedZipFilesArray["n"], unzippedFilesArray["n"], ftpFiles["n"] contain the filename without path

// -------------------------------------------------------------------------


// -------------------------------------------------------------------------
// acceptFiles
// -------------------------------------------------------------------------
		if (sizeof($uploadedFilesArray) > 0 || sizeof($uploadedZipFilesArray) > 0) {
			echo "<b><u>Checking files:</u></b> <br>\n";
			echo "<ul>\n";

			if ($uploadedFilesArray["1"]["size"] > 0 || $uploadedFilesArray["2"]["size"] > 0 || $uploadedFilesArray["3"]["size"] > 0 || $uploadedFilesArray["4"]["size"] > 0 || $uploadedFilesArray["5"]["size"] > 0) {
				$resultArray = acceptFiles($uploadedFilesArray, $application_tempdir);
				$acceptedFilesArray = getResult($resultArray);
				if ($acceptedFilesArray == false)  { printErrorMessage($resultArray, "exit"); }
			}

			if ($uploadedZipFilesArray["1"]["size"] > 0 || $uploadedZipFilesArray["2"]["size"] > 0) {
				$resultArray = acceptFiles($uploadedZipFilesArray, $application_tempdir);
				$acceptedZipFilesArray = getResult($resultArray);
				if ($acceptedZipFilesArray == false)  { printErrorMessage($resultArray, "exit"); }
			}

			echo "</ul>\n";

		} // End if

// -------------------------------------------------------------------------
// unzipFiles
// -------------------------------------------------------------------------

//		if (sizeof($acceptedZipFilesArray) > 0) {
//			echo "<b><u>Unzipping archives:</u></b> <br>\n";
//			echo "<ul>\n";

//			$resultArray = unzipFiles($acceptedZipFilesArray, $application_tempdir);
//			$unzippedFilesArray = getResult($resultArray);
//			if ($unzippedFilesArray == false)  { printErrorMessage($resultArray, ""); }

//			echo "</ul>\n";
//		}

// -------------------------------------------------------------------------
// Tranfer both uploaded and unzipped files to the FTP server
// -------------------------------------------------------------------------
		if (sizeof($acceptedFilesArray) > 0 && sizeof($unzippedFilesArray) > 0) {
			$ftpFilesArray = array_merge($acceptedFilesArray, $unzippedFilesArray);
		}
		elseif (sizeof($acceptedFilesArray) > 0) {
			$ftpFilesArray = $acceptedFilesArray;
		}
		elseif (sizeof($unzippedFilesArray) > 0) {
			$ftpFilesArray = $unzippedFilesArray;
		}

// -------------------------------------------------------------------------
// ftp_uploadfiles
// -------------------------------------------------------------------------
		if (sizeof($ftpFilesArray) > 0) {
			echo "<b><u>Transferring files to the FTP server:</u></b> <br>\n";
			echo "<ul>\n";

			$resultArray = ftp_uploadfiles($ftpFilesArray, $application_tempdir, $directory);
			$result3 = getResult($resultArray);
			if ($result3 == false)  { printErrorMessage($resultArray, ""); }

			echo "</ul>\n";
		}
		else {
			echo "There are no files to transfer to the FTP server.";
		} // end if else




	} // End else (show form, show result)


	echo "</div>\n";

} // End function uploadfile
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




?>