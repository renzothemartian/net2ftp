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
// HTML parameters
// -------------------------------------------------------------------------
// What should be shown on the browse page? 		// Used in functions_browse.inc.php, browse()

$browse_dir_size = 1; // Set to either 0 or 1!
$browse_dir_owner = 1; 
$browse_dir_group = 1;
$browse_dir_permissions = 1;
$browse_dir_mtime = 1;

$browse_file_size = 1;
$browse_file_owner = 1;
$browse_file_group = 1;
$browse_file_permissions = 1;
$browse_file_mtime = 1;

//http://www.devguru.com/Technologies/html/quickref/color_chart.html
$browse_heading_fontcolor = "#000000";  // Heading
$browse_heading_bgcolor   = "#CCCCFF";
$browse_rows_fontcolor1   = "#000000";  // Odd rows
$browse_rows_bgcolor1     = "#FFFFFF";
$browse_rows_fontcolor2   = "#000000";  // Even rows
$browse_rows_bgcolor2     = "#E0E0E0";
$browse_border_color      = "#000000";
$browse_cursor_bgcolor    = "#9999FF";
$browse_cursor_fontcolor  = "#000000";



// Size of the textarea in which the files are edited
$edit_nrofcolumns = "118";					// Used in functions_manage_file.inc.php, edit()
$edit_nrofrows = "37";
$edit_fontsize = "11px";
// http://developer.apple.com/internet/fonts/fonts_gallery.html
$edit_fontfamily = "Courier";



// -------------------------------------------------------------------------
// Upload settings
// -------------------------------------------------------------------------
// In Bytes
$max_upload_size = "500000";					// Used in manage > uploadfile
									//   both on client side (HTML) and server side (PHP)
?>