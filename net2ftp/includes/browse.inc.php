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
function browse($directory) {

// --------------
// This function shows the subdirectories and files in a particular directory
// From this page it is possible to go to subdirectories, or view/edit/rename/delete files
// --------------

// Open connection
	$resultArray = ftp_open();
	$conn_id = getResult($resultArray);
	if ($conn_id == false)  { printErrorMessage($resultArray, "exit"); }

// Check if directory exists
	if (strlen($directory)>0 && @ftp_chdir($conn_id, $directory) == false) { $resultArray[message]="The directory <b>$directory</b> does not exist.<br> If you tried to follow a symlink, enter the target directory in the Location input box and press ENTER."; printErrorMessage($resultArray, "exit"); }

// Get raw list of directories and files
// Parse the raw list and return a nice list
	$nicelist= ftp_getlist($conn_id, $directory);

// Close connection
	$resultArray = ftp_close($conn_id);
	$success2 = getResult($resultArray);
	if ($success2 == false) { printErrorMessage($resultArray, ""); }

// Print directory textbox
	printLocationActions($directory);
	echo "<p> </p>\n";

// Print list of directories and files
	printdirfilelist($directory, $nicelist, "directories");
	printdirfilelist($directory, $nicelist, "files");

} // End function browse
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function ftp_getlist($conn_id, $directory) {

// --------------
// This function performs a ftp_rawlist and returns it in an array
//
// !!!!!!!!!! Used in these functions: browse, ftp_copymovedeletedirectory !!!!!!!!!!
//
// --------------

	$rawlist = ftp_rawlist($conn_id, "$directory");

	for($i=0; $i<count($rawlist); $i++) {
		$nicelist[$i] = ftp_scanline($conn_id, $rawlist[$i]);
	} // End for

	return $nicelist;

} // End function ftp_getlist
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function ftp_scanline($conn_id, $rawlistline) {

// --------------
// This function scans an ftp_rawlist line string and returns its parts (directory/file, name, size,...) using ereg()
//
//
// Original script comes from; http://beben.lanparty.de/smint/ftp_rekdiranalys.phps
// Has been adapted.
// --------------

// ereg() doc comes from php.net
/*
mholdgate@wakefield.co.uk
11-Jan-2002 11:51 

^                Start of String
$                End of string

n*               Zero or more of 'n'
n+               One or more of 'n'
n?               A possible 'n'

n{2}             Exactly two of 'n'
n{2,}            At least 2 or more of 'n'
n{2,4}           From 2 to 4 of 'n'

()               Parenthesis to group expressions
(n|a)            Either 'n' or 'a'

.                Any single character

[1-6]            A number between 1 and 6
[c-h]            A lower case character between c and h
[D-M]            An upper case character between D and M
[^a-z]           Absence of lower case a to z
[_a-zA-Z]        An underscore or any letter of the alphabet

^.{2}[a-z]{1,2}_?[0-9]*([1-6]|[a-f])[^1-9]{2}a+$

A string beginning with any two characters
Followed by either 1 or 2 lower case alphabet letters
Followed by an optional underscore
Followed by zero or more digits
Followed by either a number between 1 and 6 or a character between a and f (Lowercase)
Followed by a two characters which are not digits between 1 and 9
Followed by one or more n characters at the end of a string
*/
// $regs can contain a maximum of 10 elements !! (regs[0] to regs[9])
// To specify what you really want back from ereg, use (). Only what is within () will be returned. See below.


// -----------------------------------------------------------------
//
// ftp.redhat.com:
//drwxr-xr-x    6 0        0            4096 Aug 21  2001 pub (one or more spaces between entries)
//
// ftp.suse.com:
//drwxr-xr-x   2 root     root         4096 Jan  9  2001 bin
//
//-rw-r--r--    1 suse     susewww       664 May 23 16:24 README.txt
//
// ftp.belnet.be:
//-rw-r--r--   1 BELNET   Mirror        162 Aug  6  2000 HEADER.html
//drwxr-xr-x  53 BELNET   Archive      2048 Nov 13 12:03 mirror
//
// ftp.microsoft.com:
//-r-xr-xr-x   1 owner    group               0 Nov 27  2000 dirmap.htm
//
// ftp.sourceforge.net:
//-rw-r--r--   1 root     staff    29136068 Apr 21 22:07 ls-lR.gz
//
// ftp.nec.com:
//dr-xr-xr-x  12 other        512 Apr  3  2002 pub
//
// ftp.intel.com
//drwxr-sr-x   11 root     ftp          4096 Sep 23 16:36 pub
//
//
// --------------------------
	if (ereg("([-dl])([rwxst-]{9})[ ]+([0-9]+)[ ]+([a-zA-Z0-9_-]+)[ ]+([a-zA-Z0-9_ -]+)[ ]+([0-9]+)[ ]+([a-zA-Z]+[ ]+[0-9]+)[ ]+([0-9: ]+)[ ]+(.*)", $rawlistline, $regs) == true) {
//                  permissions            number      owner               group               size        month        day         year/hour    filename
		$nicelistline[0] = "$regs[1]";		// Directory ==> d, File ==> -
		$nicelistline[1] = "$regs[9]";		// Filename
		$nicelistline[2] = "$regs[6]";		// Size
		$nicelistline[3] = "$regs[4]";		// Owner
		$nicelistline[4] = "$regs[5]";		// Group
		$nicelistline[5] = "$regs[2]";		// Permissions
		$nicelistline[6] = "$regs[7] $regs[8]";	// Mtime -- format depends on what FTP server returns (year, month, day, hour, minutes... see above)
	}
// -----------------------------------------------------------------
// discount-hosting.com sites (OpenBSD):
//-rw-r--r-- 1 548 30 53 Dec 10 20:57 picture.gif  (one or more spaces between entries)
// --------------------------
//	elseif (ereg("([-d])([rwxst-]{9})[ ]+([0-9]+)[ ]+([0-9]+)[ ]+([0-9]+)[ ]+([0-9]+)[ ]+([a-zA-Z]+[ ]+[0-9]+)[ ]+([0-9]+:[0-9]+)[ ]+(.*)", $rawlistline, $regs) == true) {
//		$nicelistline[0] = "$regs[1]";		// Directory ==> d, File ==> -
//		$nicelistline[1] = "$regs[9]";		// Filename
//		$nicelistline[2] = "$regs[6]";		// Size
//		$nicelistline[3] = "$regs[4]";		// Owner
//		$nicelistline[4] = "$regs[5]";		// Group
//		$nicelistline[5] = "$regs[2]";		// Permissions
//		$nicelistline[6] = "$regs[7] $regs[8]";	// Mtime MMM DD HH:MM
//	}
// -----------------------------------------------------------------

	return $nicelistline;

} // End function ftp_scanline
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printstateform($formName) {

// --------------
// This function prints *the beginning* of an HTML form and some javascript
// The form has to be closed afterwards with this tag: </form>
// The checkboxes contain the array of selected directories/files
// --------------

// -------- Form --------------------------------
// ----------------------------------------------
	echo "<form name=\"$formName\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
	printLoginInfo();
	echo "<input type=\"hidden\" name=\"state\">\n";
	echo "<input type=\"hidden\" name=\"manage\">\n";
	echo "<input type=\"hidden\" name=\"directory\">\n";
	echo "<input type=\"hidden\" name=\"entry\">\n";

// -------- Javascript --------------------------
// ----------------------------------------------
	echo "<script language=\"JavaScript\"><!--\n";

	echo "function submit$formName(directory, entry, state, manage) {\n";

	echo "	document.$formName.state.value=state;\n";
	echo "	document.$formName.manage.value=manage;\n";
	echo "	document.$formName.directory.value=directory;\n";
	echo "	document.$formName.entry.value=entry;\n";

	echo "	document.$formName.submit(); \n";
	echo "}\n"; // End javascript function submit$formName

	echo "//--></script>\n";

} // End function printstateform
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printdirfilelist($directory, $nicelist, $directoriesorfiles) {

// --------------
// This function uses an array of directories or files to print a nice looking page ;-)
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $browse_dir_size,  $browse_dir_owner,  $browse_dir_group,  $browse_dir_permissions,  $browse_dir_mtime;
	global $browse_file_size, $browse_file_owner, $browse_file_group, $browse_file_permissions, $browse_file_mtime;

	global $browse_heading_fontcolor, $browse_heading_bgcolor;
	global $browse_rows_fontcolor1, $browse_rows_bgcolor1;
	global $browse_rows_fontcolor2, $browse_rows_bgcolor2;
	global $browse_border_color, $browse_cursor_bgcolor, $browse_cursor_fontcolor;


//	$dir_colspan =  1 + 1 + $browse_dir_size  + $browse_dir_owner  + $browse_dir_group  + $browse_dir_permissions  + $browse_dir_mtime + 5; 	// name, ..., action column
//	$file_colspan = 1 + 1 + $browse_file_size + $browse_file_owner + $browse_file_group + $browse_file_permissions + $browse_file_mtime + 8;	// name, ..., action column
	$dir_colspan =  1 + 1 + $browse_dir_size  + $browse_dir_owner  + $browse_dir_group  + $browse_dir_permissions  + $browse_dir_mtime; 		// name, ...
	$file_colspan = 1 + 1 + $browse_file_size + $browse_file_owner + $browse_file_group + $browse_file_permissions + $browse_file_mtime + 3;	// name, ..., 2 action column


// ---------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------
// ------------------------------- First rows --------------------------------------
// ---------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------

//	echo "\n\n<table align=\"center\" width=\"90%\" border=\"2\" frame=\"box\" rules=\"none\">\n";
	echo "\n\n<table align=\"center\" style=\"margin-top: 25px; width: 90%; border: 2px solid $browse_border_color;\">\n";

	echo "<script language=\"JavaScript\"><!--\n";
	echo "function CheckAll(myform) {\n";
	echo "   for (var i = 0; i < myform.elements.length; i++) {\n";
	echo "       if (myform.elements[i].type == 'checkbox') {\n";
	echo "           myform.elements[i].checked = !(myform.elements[i].checked);\n";
	echo "       }\n";
	echo "   }\n";
	echo "}\n";
	echo "//--></script>\n";

// ------------------------------- Subdirectories: first rows ----------------------
// ---------------------------------------------------------------------------------
	if ($directoriesorfiles=="directories") {

	printstateform("ListOfDirectoriesForm");

// First row
		echo "<tr style=\"color: $browse_heading_fontcolor; background-color: $browse_heading_bgcolor;\">\n";
		echo "<td id=\"tdheader1\" colspan=\"";
		echo $dir_colspan; // Span all columns
		echo "\">\n";
		echo "<div style=\"font-size: 160%; text-align: center;\">Subdirectories</div>\n";
		echo "</td>\n";
		echo "</tr>\n";
// Second row: go up to higher directory
		echo "<tr style=\"color: $browse_heading_fontcolor; background-color: $browse_heading_bgcolor;\">\n";

		echo "<td id=\"tdheader1\" ";
	// Colspan
		echo "colspan=\"" . $dir_colspan . "\" ";
	// Style
		echo "style=\"cursor:hand; font-size: 120%;\" ";
	// onMouseOver / out
		echo "onMouseOver=\"this.style.background='" . $browse_cursor_bgcolor . "';\" ";
		echo "onMouseOut =\"this.style.background='" . $browse_heading_bgcolor . "';\" ";
	// onClick
		echo "onClick=\"submitListOfDirectoriesForm('" . upDir($directory) . "', '', 'browse', '');\" ";
	// Title
		echo "title=\"Go to the subdirectory ";
		if (upDir($directory) == "") { echo "/"; }
		elseif (upDir($directory) != "") { echo upDir($directory); }
		echo "\">\n";

		echo "Up</td>\n";
		echo "</tr>\n";

// Third row: actions
		echo "<tr style=\"color: $browse_row_fontcolor1; background-color: $browse_row_bgcolor1;\">\n";
		echo "<td id=\"tditem1\" colspan=\"" . $dir_colspan . "\" style=\"text-align: right;\">";
		echo "Transform selected subdirectories: ";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Copy\"   onClick=\"submitListOfDirectoriesForm('$directory', '', 'manage', 'copydirectory');\"   title=\"Copy the selected directories\">\n";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Move\"   onClick=\"submitListOfDirectoriesForm('$directory', '', 'manage', 'movedirectory');\"   title=\"Move the selected directories\">\n";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Delete\" onClick=\"submitListOfDirectoriesForm('$directory', '', 'manage', 'deletedirectory');\" title=\"Delete the selected directories\">\n";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Rename\" onClick=\"submitListOfDirectoriesForm('$directory', '', 'manage', 'renamedirectory');\" title=\"Rename the selected directories\">\n";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Chmod\"  onClick=\"submitListOfDirectoriesForm('$directory', '', 'manage', 'chmoddirectory');\"  title=\"Chmod the selected directories &#13; (Functionality only provided on Unix/Linux!/BSD servers)\">\n";
		echo "</td>\n";
		echo "</tr>\n";

// Forth row: title
		echo "<tr style=\"color: $browse_heading_fontcolor; background-color: $browse_heading_bgcolor;\">\n";
                                              echo "<td id=\"tdheader1\"><a style=\"text-decoration: underline;\" onClick=\"CheckAll(document.ListOfDirectoriesForm);\"  title=\"Click to check or uncheck all rows\">All</a></td>\n";
                                              echo "<td id=\"tdheader1\">Name</td>\n";
        	if ($browse_dir_size==1)        { echo "<td id=\"tdheader1\">Size</td>\n"; }
        	if ($browse_dir_owner==1)       { echo "<td id=\"tdheader1\">Owner</td>\n"; }
		if ($browse_dir_group==1)       { echo "<td id=\"tdheader1\">Group</td>\n"; }
		if ($browse_dir_permissions==1) { echo "<td id=\"tdheader1\">Perms</td>\n"; }
		if ($browse_dir_mtime==1)       { echo "<td id=\"tdheader1\">Mod Time</td>\n"; }

//		echo "<td id=\"tdheader1\" colspan=\"5\">\n";
//		echo "Action\n";
//		echo "</td>\n";
		echo "</tr>\n";
	}
// ------------------------------- Files: first rows -------------------------------
// ---------------------------------------------------------------------------------
	elseif ($directoriesorfiles=="files") {

	printstateform("ListOfFilesForm");

// First row
		echo "<tr style=\"color: $browse_heading_fontcolor; background-color: $browse_heading_bgcolor;\">\n";
		echo "<td id=\"tdheader1\" colspan=\"";
		echo $file_colspan; // Span all columns
		echo "\">\n";
		echo "<div style=\"font-size: 160%; text-align: center;\">Files</div>\n";
		echo "</td>\n";
		echo "</tr>\n";

// Second row: actions
		echo "<tr style=\"color: $browse_row_fontcolor1; background-color: $browse_row_bgcolor1;\">\n";
		echo "<td id=\"tditem1\" colspan=\"" . $file_colspan . "\" style=\"text-align: right;\">";
		echo "Transform selected files: ";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Copy\"   onClick=\"submitListOfFilesForm('$directory', '',   'manage', 'copyfile');\"       title=\"Copy the selected files\">\n";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Move\"   onClick=\"submitListOfFilesForm('$directory', '',   'manage', 'movefile');\"       title=\"Move the selected files\">\n";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Delete\" onClick=\"submitListOfFilesForm('$directory', '',   'manage', 'deletefile');\"     title=\"Delete the selected files\">\n";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Rename\" onClick=\"submitListOfFilesForm('$directory', '',   'manage', 'renamefile');\"     title=\"Rename the selected files\">\n";
		echo "<input type=\"button\" id=\"smallbutton\" value=\"Chmod\"  onClick=\"submitListOfFilesForm('$directory', '',   'manage', 'chmodfile');\"      title=\"Chmod the selected files &#13; (Functionality only provided on Unix/Linux!/BSD servers)\">\n";
//		echo "<input type=\"button\" id=\"smallbutton\" value=\"Download\" onClick=\"submitListOfFilesForm('$directory', '', 'manage', 'downloadfile');\"   title=\"Download the selected files\">\n";
		echo "</td>\n";
		echo "</tr>\n";

// Third row
		echo "<tr style=\"color: $browse_heading_fontcolor; background-color: $browse_heading_bgcolor;\">\n";
                                               echo "<td id=\"tdheader1\"><a style=\"text-decoration: underline;\" onClick=\"CheckAll(document.ListOfFilesForm);\" title=\"Click to check or uncheck all rows\">All</a></td>\n";
		                                   echo "<td id=\"tdheader1\">Name</td>\n";
		if ($browse_file_size==1)        { echo "<td id=\"tdheader1\">Size</td>\n"; }
		if ($browse_file_owner==1)       { echo "<td id=\"tdheader1\">Owner</td>\n"; }	
		if ($browse_file_group==1)       { echo "<td id=\"tdheader1\">Group</td>\n"; }
		if ($browse_file_permissions==1) { echo "<td id=\"tdheader1\">Perms</td>\n"; }
		if ($browse_file_mtime==1)       { echo "<td id=\"tdheader1\">Mod Time</td>\n"; }
		echo "<td id=\"tdheader1\" colspan=\"3\">\n";
		echo "Actions\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
// ---------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------
// ------------------------------- Other rows --------------------------------------
// ---------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------
	$rowcolor = 0; // To create alternating colors for different rows
	for ($i=1; $i<=count($nicelist); $i++) {
		$dirfilename = $nicelist[$i][1];
		$dirfilesize = $nicelist[$i][2];
		$dirfileowner = $nicelist[$i][3];
		$dirfilegroup = $nicelist[$i][4];
		$dirfilepermissions = $nicelist[$i][5];
		$dirfilemtime = $nicelist[$i][6];
// ------------------------------- Subdirectories: other rows ----------------------
// ---------------------------------------------------------------------------------
		if ($directoriesorfiles=="directories" && ($nicelist[$i][0]=="d" || $nicelist[$i][0]=="l") && ($dirfilename != "." && $dirfilename != "..")) {
			$rowcolor = $rowcolor + 1;
			if ($rowcolor % 2 == 1) { echo "<tr style=\"color: $browse_rows_fontcolor1; background-color: $browse_rows_bgcolor1;\" onMouseOver=\"this.style.fontColor='$browse_cursor_fontcolor'; this.style.backgroundColor='$browse_cursor_bgcolor';\" onMouseOut =\"this.style.fontColor='$browse_rows_fontcolor1'; this.style.backgroundColor='$browse_rows_bgcolor1';\">\n"; }
			if ($rowcolor % 2 == 0) { echo "<tr style=\"color: $browse_rows_fontcolor2; background-color: $browse_rows_bgcolor2;\" onMouseOver=\"this.style.fontColor='$browse_cursor_fontcolor'; this.style.backgroundColor='$browse_cursor_bgcolor';\" onMouseOut =\"this.style.fontColor='$browse_rows_fontcolor2'; this.style.backgroundColor='$browse_rows_bgcolor2';\">\n"; }

// Checkbox
			if ($rowcolor % 2 == 1) { echo "<td id=\"tditem1\" title=\"Select the subdirectory $dirfilename\" style=\"text-align: center;\"><input type=\"checkbox\" name=\"selectedEntries[]\" value=\"" . $dirfilename . "\"></td>\n"; }
			if ($rowcolor % 2 == 0) { echo "<td id=\"tditem1\" title=\"Select the subdirectory $dirfilename\" style=\"text-align: center;\"><input type=\"checkbox\" name=\"selectedEntries[]\" value=\"" . $dirfilename . "\"></td>\n"; }

// Link: subdirectory
			if ($nicelist[$i][0]=="d") {
				echo "<td id=\"tditem1\" onClick=\"submitListOfDirectoriesForm('$directory/$dirfilename', '', 'browse', '');\" title=\"Go to the subdirectory $directory/$dirfilename\" style=\"cursor:hand\">" . $dirfilename . "</td>\n";
			}
// Link: symlink
			elseif ($nicelist[$i][0]=="l") {
				if (ereg("(.*)[ ]*->[ ]*(.*)", $dirfilename, $regs) == true) {
					$symlinkname = "$regs[1]";
					$symlinkdir = "$regs[2]";
				}
				if ($directory != "") { $realpath = "$directory/$symlinkdir"; }
				else { $realpath = $symlinkdir; }
				echo "<td id=\"tditem1\" onClick=\"submitListOfDirectoriesForm('$realpath', '', 'browse', '');\" title=\"Symlink $dirfilename\" style=\"cursor:hand\">" . $dirfilename . "</td>\n";
			}

// Properties: subdirectory and symlink are the same
			if ($browse_dir_size==1)        { echo "<td id=\"tditem1\">$dirfilesize</td>\n"; }
			if ($browse_dir_owner==1)       { echo "<td id=\"tditem1\">$dirfileowner</td>\n"; }
			if ($browse_dir_group==1)       { echo "<td id=\"tditem1\">$dirfilegroup</td>\n"; }
			if ($browse_dir_permissions==1) { echo "<td id=\"tditem1\">$dirfilepermissions</td>\n"; echo "<input type=\"hidden\" name=\"chmodStrings[]\" value=\"$dirfilepermissions\">\n"; }
			if ($browse_dir_mtime==1)       { echo "<td id=\"tditem1\">$dirfilemtime</td>\n"; }      
// Actions: directories
//			if ($nicelist[$i][0]=="d") {
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'copydirectory');\"   title=\"Copy the directory $dirfilename\" style=\"cursor:hand\">Copy</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'movedirectory');\"   title=\"Move the directory $dirfilename\" style=\"cursor:hand\">Move</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'deletedirectory');\" title=\"Delete the directory $dirfilename\" style=\"cursor:hand\">Delete</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'renamedirectory');\" title=\"Rename the directory $dirfilename\" style=\"cursor:hand\">Rename</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'chmoddirectory');\"  title=\"Chmod the directory $dirfilename &#13; (Only on Unix/Linux/BSD servers)\" style=\"cursor:hand\">Chmod</td>\n";
//			}
// Actions: symlink
//			elseif ($nicelist[$i][0]=="l") {
//				echo "<td id=\"tditem1\" colspan=\"5\" style=\"text-align: center\"> - symlink - </td>\n";
//			}

			echo "</tr>\n";
		}

// ------------------------------- Files: other rows -------------------------------
// ---------------------------------------------------------------------------------
		elseif ($directoriesorfiles=="files" && $nicelist[$i][0]=="-") {
			$rowcolor = $rowcolor + 1;
			if ($rowcolor % 2 == 1) { echo "<tr style=\"color: $browse_rows_fontcolor1; background-color: $browse_rows_bgcolor1;\" onMouseOver=\"this.style.fontColor='$browse_cursor_fontcolor'; this.style.backgroundColor='$browse_cursor_bgcolor';\" onMouseOut =\"this.style.fontColor='$browse_rows_fontcolor1'; this.style.backgroundColor='$browse_rows_bgcolor1';\">\n"; }
			if ($rowcolor % 2 == 0) { echo "<tr style=\"color: $browse_rows_fontcolor2; background-color: $browse_rows_bgcolor2;\" onMouseOver=\"this.style.fontColor='$browse_cursor_fontcolor'; this.style.backgroundColor='$browse_cursor_bgcolor';\" onMouseOut =\"this.style.fontColor='$browse_rows_fontcolor2'; this.style.backgroundColor='$browse_rows_bgcolor2';\">\n"; }

// Checkbox
			if ($rowcolor % 2 == 1) { echo "<td id=\"tditem1\" title=\"Select the file $dirfilename\" style=\"text-align: center;\"><input type=\"checkbox\" name=\"selectedEntries[]\" value=\"" . $dirfilename . "\"></td>\n"; }
			if ($rowcolor % 2 == 0) { echo "<td id=\"tditem1\" title=\"Select the file $dirfilename\" style=\"text-align: center;\"><input type=\"checkbox\" name=\"selectedEntries[]\" value=\"" . $dirfilename . "\"></td>\n"; }

// Link
			echo "<td id=\"tditem1\" title=\"View the file $dirfilename from your HTTP web server &#13; (Note: This link may not work if you don't have your own domain name.)\" style=\"cursor:hand\" onClick=\"window.open('" . printURL($directory, $dirfilename, no) . "');\">" . $dirfilename . "</td>\n";

// Properties
			if ($browse_file_size==1)        { echo "<td id=\"tditem1\">$dirfilesize</td>\n"; }
			if ($browse_file_owner==1)       { echo "<td id=\"tditem1\">$dirfileowner</td>\n"; }
			if ($browse_file_group==1)       { echo "<td id=\"tditem1\">$dirfilegroup</td>\n"; }
			if ($browse_file_permissions==1) { echo "<td id=\"tditem1\">$dirfilepermissions</td>\n"; echo "<input type=\"hidden\" name=\"chmodStrings[]\" value=\"$dirfilepermissions\">\n"; }
			if ($browse_file_mtime==1)       { echo "<td id=\"tditem1\">$dirfilemtime</td>\n"; }

// Actions
			$fileType = getFileType($dirfilename);
// TEXT
			if ($fileType == "TEXT") {
				echo "<td id=\"tditem1\" onClick=\"submitListOfFilesForm('$directory', '$dirfilename', 'manage', 'view');\"       title=\"View the highlighted source code of file $dirfilename\" style=\"cursor:hand\">View</td>\n";
				echo "<td id=\"tditem1\" onClick=\"submitListOfFilesForm('$directory', '$dirfilename', 'manage', 'edit');\"       title=\"Edit the source code of file $dirfilename\" style=\"cursor:hand\">Edit</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'copyfile', '');\"   title=\"Copy the file $dirfilename\" style=\"cursor:hand\">Copy</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'movefile', '');\"   title=\"Move the file $dirfilename\" style=\"cursor:hand\">Move</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'deletefile', '');\" title=\"Delete the file $dirfilename\" style=\"cursor:hand\">Delete</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'renamefile', '');\" title=\"Rename the file $dirfilename\" style=\"cursor:hand\">Rename</td>\n";
//				echo "<td id=\"tditem1\" onClick=\"submitStateForm('$directory', '$dirfilename', 'manage', 'chmodfile', '$dirfilepermissions');\" title=\"Chmod the file $dirfilename &#13; (Only on Unix/Linux/BSD servers)\" style=\"cursor:hand\">Chmod</td>\n";
				echo "<td id=\"tditem1\" onClick=\"submitListOfFilesForm('$directory', '$dirfilename', 'manage', 'downloadfile', '');\"   title=\"Download the file $dirfilename\" style=\"cursor:hand\">Download</td>\n";
			} // end if TEXT
// IMAGE, EXECUTABLE, OFFICE, ARCHIVE, OTHER
			else {
				echo "<td id=\"tditem1\"></td>\n";
				echo "<td id=\"tditem1\"></td>\n";
				echo "<td id=\"tditem1\" onClick=\"submitListOfFilesForm('$directory', '$dirfilename', 'manage', 'downloadfile', '');\"   title=\"Download the file $dirfilename\" style=\"cursor:hand\">Download</td>\n";
			} // end if else

			echo "</tr>\n";
		} // End if elseif

	} // End for

	if ($rowcolor == 0) { // There are no subdirectories or files
		$rowcolor = $rowcolor + 1; // =1
		if ($rowcolor % 2 == 1) { echo "<tr style=\"color: $browse_rows_fontcolor1; background-color: $browse_rows_bgcolor1;\">\n"; }
		if ($rowcolor % 2 == 0) { echo "<tr style=\"color: $browse_rows_fontcolor2; background-color: $browse_rows_bgcolor2;\">\n"; }
		echo "<td id=\"tditem1\" style=\"text-align: center;\" colspan=\"$dir_colspan\"><br>";
		if ($directoriesorfiles=="directories") { echo "No subdirectories"; }
		if ($directoriesorfiles=="files")       { echo "No files"; }
		echo "<br> <br></td>\n\n";
		echo "</tr>\n";
	}

	echo "</form>\n"; // This closes or the form "ListOfDirectoriesForm", or the form "ListOfFilesForm"

	echo "</table>\n\n\n";

} // End function printdirfilelist
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function printLocationActions($directory) {

// --------------
// This function prints the ftp server and a text box with the directory
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
global $net2ftp_ftpserver;


// -------------------------------------------------------------------------
// Print Go To
// -------------------------------------------------------------------------

	if (strlen($directory)>0) { $printdirectory = $directory; }
	else                      { $printdirectory = "/"; }


// Form
// ------------------------------------

// Directory
	echo "<form name=\"GotoForm\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
	echo "<div style=\"font-size: 120%; text-align: center; margin-top: 50px; margin-bottom: 20px;\">\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"browse\">\n";
	printLoginInfo();
	echo "<a title=\"Enter the directory in the textbox and press ENTER\">$net2ftp_ftpserver</a>\n";
	echo "<input type=\"text\" name=\"directory\" value=\"$printdirectory\" size=\"40\">\n";
	echo "</div>\n";
	echo "</form>\n";

// Actions
	echo "<form>\n";
	echo "<div style=\"text-align: center; margin-top: 20px; margin-bottom: 50px;\">\n";
	echo "<input type=\"button\" id=\"button\" onClick=\"submitListOfDirectoriesForm('$directory', '', 'browse', '');\"             title=\"Refresh this page to see the latest changes\" value=\"Refresh\">\n";
	echo "<input type=\"button\" id=\"button\" onClick=\"submitListOfDirectoriesForm('$directory', '', 'manage', 'newdirectory');\" title=\"Make a new subdirectory in directory $printdirectory\" value=\"New subdir\">\n";
	echo "<input type=\"button\" id=\"button\" onClick=\"submitListOfDirectoriesForm('$directory', '', 'manage', 'newfile');\"      title=\"Create a new file in directory $printdirectory\" value=\"New file\">\n";
	echo "<input type=\"button\" id=\"button\" onClick=\"submitListOfDirectoriesForm('$directory', '', 'manage', 'uploadfile');\"   title=\"Upload new files in directory $printdirectory\" value=\"Upload files\">\n";
	echo "<input type=\"button\" id=\"button\" onClick=\"submitListOfDirectoriesForm('', '', 'printloginform', '');\"               title=\"Logout from $myname\" value=\"Logout\">\n";
	echo "</form>\n";


} // end printLocationActions
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function printURL($directory, $file, $htmltags) {

// --------------
// This function prints the URL of the files in the Browse view
// Given the FTP server (ftp.name.com),
//       the directory and file (/directory/file.php)
// It has to return
//       http://www.name.com/directory/file.php
// --------------

	global $net2ftp_ftpserver, $net2ftp_username;

// tags indicates whether the url should be returned enclosed in HTML tags or not

// -------------------------------------------------------------------------
// "ftp.membres.lycos.fr" -----> "http://membres.lycos.fr/username"
// -------------------------------------------------------------------------
	if (ereg("ftp.membres.lycos.fr", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://membres.lycos.fr/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "ftpperso.free.fr" -----> "http://username.free.fr"
// -------------------------------------------------------------------------
	elseif (ereg("ftpperso.free.fr", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://" . $net2ftp_username . ".free.fr" . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "web.wanadoo.be" -----> "http://web.wanadoo.be/username"
// -------------------------------------------------------------------------
	elseif (ereg("web.wanadoo.be", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://web.wanadoo.be/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "perso-ftp.wanadoo.fr" -----> "http://perso.wanadoo.fr/username"
// -------------------------------------------------------------------------
	elseif (ereg("perso-ftp.wanadoo.fr", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://perso.wanadoo.fr/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "ftp.wanadoo.es" -----> "http://perso.wanadoo.es/username"
// -------------------------------------------------------------------------
	elseif (ereg("ftp.wanadoo.es", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://perso.wanadoo.es/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// wanadoo uk
// "uploads.webspace.freeserve.net" -----> "http://www.username.freeserve.co.uk"
// -------------------------------------------------------------------------
	elseif (ereg("uploads.webspace.freeserve.net", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://www." . $net2ftp_username . ".freeserve.co.uk" . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "home.wanadoo.nl" -----> "http://home.wanadoo.nl/username"
// -------------------------------------------------------------------------
	elseif (ereg("home.wanadoo.nl", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://home.wanadoo.nl/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "home.planetinternet.be" -----> "http://home.planetinternet.be/~username"
// -------------------------------------------------------------------------
	elseif (ereg("home.planetinternet.be", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://home.planetinternet.be/~" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "home.planet.nl" -----> "http://home.planet.nl/~username"
// -------------------------------------------------------------------------
	elseif (ereg("home.planet.nl", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://home.planet.nl/~" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "users.skynet.be" -----> "http://users.skynet.be/username"
// -------------------------------------------------------------------------
	elseif (ereg("users.skynet.be", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://users.skynet.be/" . $net2ftp_username . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "ftp.xs4all.nl/WWW/directory" -----> "http://www.xs4all.nl/~username/directory"
// -------------------------------------------------------------------------
	elseif (ereg("ftp.xs4all.nl", $net2ftp_ftpserver, $regs)) {
		if (strlen($directory) < 4) { 
			if ($htmltags == "no") { return "javascript: alert('This file is not accessible from the web');"; }
			else { return "<a title=\"This file is not accessible from the web\" onClick=\"alert('This file is not accessible from the web');\">$file</a>"; }
		}
		else {
			// Transform directory from /WWW/dir to /dir  --> remove the first 4 characters
			$directory = substr($directory, 4);
			$URL = "http://www.xs4all.nl/~" . $net2ftp_username . $directory . "/" . $file;

			if ($htmltags == "no") { return $URL; }
			else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
		} // end if else strlen
	}

// -------------------------------------------------------------------------
// "ftp.server.com/file" -----> "http://www.server.com/file"
// -------------------------------------------------------------------------
	elseif (ereg("ftp.(.+)(.{2,4})", $net2ftp_ftpserver, $regs)) { 
		$URL = "http://www." . $regs[1] . $regs[2] . $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}

// -------------------------------------------------------------------------
// "http://192.168.0.1/file" can be determined using "192.168.0.1/file":
// -------------------------------------------------------------------------
	else { 
		$URL = "http://" . $net2ftp_ftpserver. $directory . "/" . $file;

		if ($htmltags == "no") { return $URL; }
		else { return "<a href=\"" . $URL . "\" target=\"_blank\" title=\"Execute $file in a new window\">$file</a>"; }
	}


} // end printURL
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



?>