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
// Location of net2ftp on the server
// -------------------------------------------------------------------------
$server_rootdir = "/var/www/php/net2ftp";   // <-- The directory in which the net2ftp application files reside
$application_extension = "";                // (Do not use this, it is only for development purposes)

// Nothing to change:
$application_rootdir = $server_rootdir . $application_extension;
$application_tempdir = $application_rootdir. "/temp";
$application_tempzipdir = $application_rootdir. "/temp";


// -------------------------------------------------------------------------
// Database settings, used to log the actions of the users (no password logging)
// -------------------------------------------------------------------------
$use_database = "yes";
$dbusername = "enter_username_here";
$dbpassword = "enter_password_here";
$dbname = "enter_database_name_here";
$dbserver = "enter_database_server_here";


// -------------------------------------------------------------------------
// General settings
// -------------------------------------------------------------------------
$myname = "net2ftp";
$mydomain = "mycompany.com";
$email_feedback = "net2ftp@$mydomain";


// -------------------------------------------------------------------------
// Logging
// -------------------------------------------------------------------------
$log_access = "yes";
$log_login = "yes";
$log_error = "yes";


// -------------------------------------------------------------------------
// Authorizations
// -------------------------------------------------------------------------
$check_authorization = "yes";

// ---------------------------------
// Allowed FTP servers: either set it to ALL, or else provide a list of allowed servers
// ---------------------------------
$net2ftp_allowed_ftpservers[1] = "ALL";
//$net2ftp_allowed_ftpservers[1] = "ftp.mycompany.com";
//$net2ftp_allowed_ftpservers[2] = "192.168.1.1";
//$net2ftp_allowed_ftpservers[2] = "ftp.mydomain2.org";

// ---------------------------------
// Banned FTP servers
// ---------------------------------
$net2ftp_banned_ftpservers[1] = "ftp.download-music-for-free.com";

// ---------------------------------
// Banned IP addresses
// ---------------------------------
$net2ftp_banned_addresses[1] = "10.0.0.1";


// -------------------------------------------------------------------------
// Stylesheet that is used
// -------------------------------------------------------------------------
$client_css = "skin1-default.css";
$client_imagesdir = "images/";

?>