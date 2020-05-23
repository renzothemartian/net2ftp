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
function printLoginForm() {

// --------------
// This function prints the login forms
// It is shown when the user arrives for the first time, and when he logs out (and maybe wants to log in again)
// --------------

global $net2ftpcookie_ftpserver, $net2ftpcookie_username;
global $myname;

// ---------------------------------------
// Table
// ---------------------------------------
	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" style=\"margin-top: 50px;\">\n";

// ----------------
// Row 1, left: login form
// ----------------
	echo "<tr>\n";
	echo "<td valign=\"top\" width=\"400\">\n";
	echo "<table align=\"center\">\n";
	echo "<form action=\"" . printPHP_SELF() . "\" method=\"post\">\n";

	echo "<tr>\n";
	echo "<td valign=\"top\">FTP server:</td>\n";
	echo "<td>\n";
	echo "<input type=\"text\" id=\"input\" name=\"input_ftpserver\" value=\"$net2ftpcookie_ftpserver\"> port \n";
	if ($net2ftpcookie_ftpserverport != "") {
		echo "<input type=\"text\" id=\"input\" size=\"3\" maxlength=\"5\" name=\"input_ftpserverport\" value=\"$net2ftpcookie_ftpserverport\">\n";
	}
	else {
		echo "<input type=\"text\" id=\"input\" size=\"3\" maxlength=\"5\" name=\"input_ftpserverport\" value=\"21\">\n";
	}
	echo "<div style=\"font-size: 65%;\">\n";
	echo "Example: ftp.server.com or 192.123.45.67\n";
	echo "</div>\n";

	echo "</td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td>Username:</td>\n";
	echo "<td><input type=\"text\" id=\"input\" name=\"input_username\" value=\"$net2ftpcookie_username\"></td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td>Password:</td>\n";
	echo "<td><input type=\"password\" id=\"input\" name=\"input_password\"></td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";

	echo "<tr><td colspan=\"3\">\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"browse\">\n";
	echo "<input type=\"hidden\" name=\"cookiesetonlogin\" value=\"yes\">\n";
	echo "<div style=\"text-align: center; margin-top: 10px;\">\n";
	echo "<input type=\"submit\" id=\"button\" value=\"Login\"><br><br><br><br>\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";


	echo "</form>\n";
	echo "</table>\n";
	echo "</td>\n\n";


// ----------------
// Row 1, right: description
// ----------------
	echo "<td valign=\"top\" width=\"400\">\n";
	printDescription();
	echo "</td>\n\n";
	echo "</tr>\n\n";

// ----------------
// Row 2: spacing
// ----------------
	echo "<tr height=\"30\">\n\n";
	echo "<td colspan=\"2\">\n\n";
	echo "</td>\n\n";
	echo "</tr>\n\n";


// ----------------
// Row 3 and 4
// ----------------
	echo "<tr>\n\n";

	echo "<td valign=\"top\">\n\n";
	echo "<a href=\"" . printPHP_SELF() . "?state=printdetails\"     style=\"font-size: 120%;\">Details</a><br>\n";
	echo "<div style=\"font-size: 80%; margin-left: 20px;\">Read about the technical details</div><br>\n";
	echo "</td>\n\n";

	echo "<td valign=\"top\">\n\n";
	echo "<a href=\"" . printPHP_SELF() . "?state=printdownload\"    style=\"font-size: 120%;\">Download</a><br>\n";
//	echo "<div                                                       style=\"font-size: 120%;\">Download</div>\n";
	echo "<div style=\"font-size: 80%; margin-left: 20px;\">Install $myname on your own web server<br>(PHP required, works under safemode)</div>\n";
	echo "<div style=\"font-size: 80%; margin-left: 20px;\">Beta version for developers</div><br>\n";
	echo "</td>\n\n";

	echo "</tr>\n\n";
	echo "<tr>\n\n";

	echo "<td valign=\"top\">\n\n";
	echo "<a href=\"" . printPHP_SELF() . "?state=printscreenshots\" style=\"font-size: 120%;\">Screenshots</a><br>\n";
	echo "<div style=\"font-size: 80%; margin-left: 20px;\">View some screenshots of the application</div><br>\n";
	echo "</td>\n\n";

	echo "<td valign=\"top\">\n\n";
	echo "<a href=\"forum\"                                         style=\"font-size: 120%;\">User forum</a><br>\n";
	echo "<div style=\"font-size: 80%; margin-left: 20px;\">You have a question? Contact other users and the developers</div><br>\n";
	echo "</td>\n";

	echo "</tr>\n";

// ----------------
// Row 5: spacing
// ----------------
	echo "<tr height=\"30\">\n\n";
	echo "<td colspan=\"2\">\n\n";
	echo "</td>\n\n";
	echo "</tr>\n\n";
	echo "</table>\n\n\n";

// ---------------------------------------
// Terms of Use
// ---------------------------------------
	echo "<table align=\"center\">\n";
	echo "<tr><td>\n";
	echo "<div style=\"text-align: center; font-size: 80%\">\n";
	echo "By using this website, you agree to these Terms of Use:<br><br>\n";
	echo "<textarea rows=\"5\" cols=\"50\" onfocus=\"this.blur()\" readonly>\n";
	printTermsOfUse();
	echo "</textarea>\n";
	echo "</div>\n";
	echo "</td></tr>\n";
	echo "</table>\n\n\n";
	echo "<br><br><br>\n";


} // End function printLoginForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************











// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printLogoutForm() {

// --------------
// This function prints the login forms
// It is shown when the user arrives for the first time, and when he logs out (and maybe wants to log in again)
// --------------

//global $net2ftp_ftpserver, $net2ftp_username;

//	echo "<form action=\"" . printPHP_SELF() . "\" method=\"post\" style=\"margin-top: 30px; margin-bottom: 15px; text-align: center;\">\n";
//	echo "<input type=\"hidden\" name=\"state\" value=\"logout\">\n";
//	echo "Logged in as <b>$net2ftp_username</b> on <b>$net2ftp_ftpserver</b> <input type=\"submit\" id=\"button\" value=\"Logout\">\n";
//	echo "</form>\n";

//	echo "<div style=\"margin-top: 30px; margin-bottom: 15px; text-align: center;\">\n";
//	echo "Logged in as <b>$net2ftp_username</b> on <b>$net2ftp_ftpserver</b>\n";
//	echo "</div>\n";

} // End function printLogoutForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printFeedbackForm($formresult) {

// --------------
// This function prints the feedback form
// --------------

global $mydomain, $email_feedback;
global $name, $subject, $email, $messagebody, $REMOTE_ADDR;


if ($formresult== "") { $formresult = "form"; }

switch ($formresult) {

// -------------------------------------------------------------------------
// formormail: form
// -------------------------------------------------------------------------
	case "form":
		echo "<form action=\"" . printPHP_SELF() . "\" method=post>\n";
		echo "<center>\n";
		echo "If you want to send us some feedback, please use the form below.<br>Do not forget to mention your email address if you want us to reply to you.<br><br>\n";
		echo "<table>\n";
		echo "<tr><td>Name:</td><td><input type=\"text\" name=\"name\"></td></tr>\n";
		echo "<tr><td>Subject:</td><td><input type=\"text\" name=\"subject\"></td></tr>\n";
		echo "<tr><td>Email address:</td><td><input type=\"text\" name=\"email\"></td></tr>\n";
		echo "</table>\n";
		echo "<textarea rows=\"10\" cols=\"45\" name=\"messagebody\"></textarea><br><br>\n";
		echo "<input type=\"hidden\" name=\"state\" value=\"feedback\">\n";
		echo "<input type=\"hidden\" name=\"formresult\" value=\"result\">\n";
		echo "<input type=\"button\" id=\"button\" value=\"Send\" onClick=\"this.form.submit();\">\n";
		echo "</center>\n";
		echo "</form>\n";
	break;
// -------------------------------------------------------------------------
// formormail: result
// -------------------------------------------------------------------------
	case "result":

// bool mail(string to, string subject, string message, string [additional_headers]);

	   	$to = $email_feedback;

	   	$message = "";
	   	$message = $message . "Name: $name\n";
		$message = $message . "Subject: $subject\n";
		$message = $message . "Email: $email\n";
		$message = $message . "IP address: $REMOTE_ADDR\n";
		$message = $message . "Time: $currenttime\n";
		$message = $message . "\nMessagebody:\n$messagebody\n";

		$headers = "From: feedbackform@$mydomain\nReply-To: feedbackform@$mydomain\nX-Mailer: PHP/" . phpversion();

		$mybool = mail($to, $subject, $message, $headers);

		if ($mybool == 1) {
			echo "<center>\n";

			echo "<p>\n";
			echo "<b>Your message has been sent.</b>\n";
			echo "</p>\n";

			echo "<p>\n";
			echo "Name: " . $name . "<br>\n";
			echo "Subject: " . $subject . "<br>\n";
			echo "Email address: " . $email . "<br><br>\n";
			echo "<u>Message:</u> <br>" . $messagebody . "<br>\n";
			echo "</p>\n";

			echo "<input type=\"button\" id=\"longbutton\" onClick=\"window.close();\" value=\"Close window\">\n";

			echo "</center>\n";
		}
		else {
			$resultArray[message] = "Due to a technical problem, your message could not be sent. You may send the message via email to <a href=\"mailto:$email_feedback\">$email_feedback</a>."; 
			printErrorMessage($resultArray, "");
			echo "<u>Message:</u> <br>" . $messagebody . "<br>\n";
		}

	break;

	default:
		$resultArray[message] = "Unexpected formresult string."; 
		printErrorMessage($resultArray, "");
	break;
} // End switch



} // End function printFeedbackForm
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************



// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function encryptPassword($password) {

// --------------
// This function takes a clear-text password and returns an encrypted password
// --------------

// 1 - Convert pw to nr

	for ($i=0; $i<strlen($password); $i=$i+1) {
		$ascii_number = ord($password[$i]);
		if ($ascii_number < 10) { $ascii_number = "00" . $ascii_number; }
		elseif ($ascii_number < 100) { $ascii_number = "0" . $ascii_number; }
		$password_ascii_number = $password_ascii_number . $ascii_number;
	}


// 2 - Do stuff with nr

// Method 1: pwe = a.pw + b
//	$password_encrypted = 2*($password_ascii_number) + 891;

// Method 2: number per number
	for ($i=0; $i<strlen($password_ascii_number); $i=$i+3) { 
		$number_unencrypted = substr($password_ascii_number, $i, 3);
		$number_encrypted = 999 - $number_unencrypted;
		$password_encrypted = $password_encrypted . "$number_encrypted";
	}

// No "encryption"
//	$password_encrypted = $password_ascii_number; // to comment later on!

	return $password_encrypted;

} // End function encryptPassword
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function decryptPassword($password_encrypted) {

// --------------
// This function takes an encrypted password and returns the clear-text password
// --------------

// 1 - Undo stuff to nr

// Method 1: pwe = a.pw + b
//	$password_ascii_number = round(1/2*($password_encrypted - 891)); // 097098 is converted to 97098 ==> add 0 or 00 in front
//	$pwn_length = strlen($password_ascii_number);
//	if     ($pwn_length - 3*floor($pwn_length/3) == 1) { $password_ascii_number = "00" . $password_ascii_number; }
//	elseif ($pwn_length - 3*floor($pwn_length/3) == 2) { $password_ascii_number = "0" . $password_ascii_number; }

// Method 2: number per number
	for ($i=0; $i<strlen($password_encrypted); $i=$i+3) { 
		$number_encrypted = substr($password_encrypted, $i, 3);
		$number_unencrypted = 999 - $number_encrypted;
		if ($number_unencrypted < 10) { $number_unencrypted = "00" . $number_unencrypted; }
		elseif ($number_unencrypted < 100) { $number_unencrypted = "0" . $number_unencrypted; }
		$password_ascii_number = $password_ascii_number . "$number_unencrypted";
	}

// No "encryption"
//	$password_ascii_number = $password_encrypted; // to comment later on!

// 2 - Convert nr to pw

	for ($j=0; $j<strlen($password_ascii_number); $j=$j+3) {
		$ascii_letter = chr(substr($password_ascii_number, $j, 3));
		$password = $password . $ascii_letter;
	}

	return $password;
//	return $password_ascii_number;

} // End function decryptPassword
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printLoginInfo() {

// --------------
// This function prints the ftpserver, username and login information
// --------------

global $net2ftp_ftpserver, $net2ftp_ftpserverport, $net2ftp_username, $net2ftp_password_encrypted;

	echo "<input type=\"hidden\" name=\"net2ftp_ftpserver\" value=\"$net2ftp_ftpserver\">\n";
	echo "<input type=\"hidden\" name=\"net2ftp_ftpserverport\" value=\"$net2ftp_ftpserverport\">\n";
	echo "<input type=\"hidden\" name=\"net2ftp_username\" value=\"$net2ftp_username\">\n";
	echo "<input type=\"hidden\" name=\"net2ftp_password_encrypted\" value=\"$net2ftp_password_encrypted\">\n";

} // End function printLoginInfo
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printBack($directory) {

// --------------
// This function prints the "back" link on different pages
// --------------

	echo "<div style=\"text-align: center;\">\n";
	echo "<form name=\"stateForm\" action=\"" . printPHP_SELF() . "\" method=\"post\">\n";
	printLoginInfo();
	echo "<input type=\"hidden\" name=\"directory\" value=\"$directory\">\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"browse\">\n";
	echo "<input type=\"submit\" id=\"button\" value=\"Back\">\n";
	echo "</form>\n";
	echo "</div>\n";

} // End function printBack
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function printPHP_SELF() {

// --------------
// This function prints $PHP_SELF, the name of the script itself
// --------------

// -------------------------------------------------------------------------
// Global variables (declared as global in functions)
// -------------------------------------------------------------------------
	global $PHP_SELF;

	return $PHP_SELF;

// 423upgrade
//	return $_SERVER['PHP_SELF'];

} // End function printPHP_SELF
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 
function printDescription() {

// -------------- 
// This function prints the description of the service offered
// -------------- 


// ------------------------------------------------------------------------- 
// Globals
// ------------------------------------------------------------------------- 
global $myname;

// ------------------------------------------------------------------------- 
// Print Description
// ------------------------------------------------------------------------- 

echo "<div style=\"font-size: 120%; font-weight: bold;\">Manage websites using a browser!</div>\n";
echo "<ul>\n";
echo "<li> Navigate the FTP server\n";
echo "<li> Upload - download\n";
echo "<li> Copy - move - delete\n";
echo "<li> Rename - chmod\n";
echo "<li> Edit text files, view them with syntax highlighting\n";
echo "</ul>\n";

} // End function printDescription
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 
function printTermsOfUse() {

// -------------- 
// This function prints the terms of use
// -------------- 



// ------------------------------------------------------------------------- 
// Globals
// ------------------------------------------------------------------------- 
global $myname, $email_feedback;

// ------------------------------------------------------------------------- 
// Print Terms Of Use
// ------------------------------------------------------------------------- 
echo "Disclaimer For Interactive Services\n\n";

echo "$myname maintains the interactive portion(s) of their Web site as a service free of charge. By using any interactive services provided herein, you are agreeing to comply with and be bound by the terms, conditions and notices relating to its use.\n\n";

echo "1.  As a condition of your use of this Web site and the interactive services contained therein, you represent and warrant to $myname that you will not use this Web site for any purpose that is unlawful or prohibited by these terms, conditions, and notices.\n\n";

echo "2.  This Web site contains one or more of the following interactive services: bulletin boards, chat areas, news groups, forums, communities and/or other message or communication facilities.  You agree to use such services only to send and receive messages and material that are proper and related to the particular service, area, group, forum, community or other message or communication facility. In addition to any other terms or conditions of use of any bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities, you agree that when using one, you will not:\n";
echo "Publish, post, upload, distribute or disseminate any inappropriate, profane, derogatory, defamatory, infringing, improper, obscene, indecent or unlawful topic, name, material or information.\n";
echo "Upload files that contain software or other material protected by intellectual property laws or by rights of privacy of publicity unless you own or control such rights or have received all necessary consents.\n"; 
echo "Upload files that contain viruses, corrupted files, or any other similar software or programs that may damage the operation of another's computer.\n";
echo "Advertise any goods or services for any commercial purpose.\n";
echo "Offer to sell any goods or services for any commercial purpose.\n";
echo "Conduct or forward chain letters or pyramid schemes.\n";
echo "Download for distribution in any manner any file posted by another user of a forum that you know, or reasonably should know, cannot be legally distributed in such manner.\n"; 
echo "Defame, abuse, harass, stalk, threaten or otherwise violate the legal rights (such as rights of privacy and publicity) of others.\n";
echo "Falsify or delete any author attributions, legal or other proper notices, proprietary designations, labels of the origin, source of software or other material contained in a file that is uploaded.\n"; 
echo "Restrict or inhibit any other user from using and enjoying any of the bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities.\n\n";

echo "3. $myname has no obligation to monitor the bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities. However, $myname reserves the right at all times to disclose any information deemed by $myname necessary to satisfy any applicable law, regulation, legal process or governmental request, or to edit, refuse to post or to remove any information or materials, in whole or in part.\n\n";

echo "4. You acknowledge that communications to or with bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities are not private communications, therefore others may read your communications without your knowledge. You should always use caution when providing any personal information about yourself or your children. $myname does not control or endorse the content, messages or information found in any bulletin board services, chat areas, news groups, forums, communities and/or other message or communication facilities and, specifically disclaims any liability with regard to same and any actions resulting from your participation. To the extent that there are moderators, forum managers or hosts, none are authorized $myname spokespersons, and their views do not necessarily reflect those of $myname.\n\n";

echo "5. The information, products, and services included on this Web site may include inaccuracies or typographical errors. Changes are periodically added to the information herein. $myname may make improvements and/or changes in this Web site at any time. Advice received via this Web site should not be relied upon for personal, legal or financial decisions and you should consult an appropriate professional for specific advice tailored to your situation.\n\n";

echo "6. $myname MAKES NO REPRESENTATIONS ABOUT THE SUITABILITY, RELIABILITY, TIMELINESS, AND ACCURACY OF THE INFORMATION, PRODUCTS, AND SERVICES CONTAINED ON THIS WEB SITE FOR ANY PURPOSE. ALL SUCH INFORMATION, PRODUCTS, AND SERVICES ARE PROVIDED \"AS IS\" WITHOUT WARRANTY OF ANY KIND.\n\n";

echo "7. $myname HEREBY DISCLAIMS ALL WARRANTIES AND CONDITIONS WITH REGARD TO THE INFORMATION, PRODUCTS, AND SERVICES CONTAINED ON THIS WEB SITE, INCLUDING ALL IMPLIED WARRANTIES AND CONDITIONS OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT.\n\n";

echo "8. IN NO EVENT SHALL $myname BE LIABLE FOR ANY DIRECT, INDIRECT, PUNITIVE, INCIDENTAL, SPECIAL, CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER INCLUDING, WITHOUT LIMITATION, DAMAGES FOR LOSS OF USE, DATA OR PROFITS, ARISING OUT OF OR IN ANY WAY CONNECTED\n";
echo "WITH THE USE OR PERFORMANCE OF THIS WEB SITE,\n";
echo "WITH THE DELAY OR INABILITY TO USE THIS WEB SITE,\n";  
echo "WITH THE PROVISION OF OR FAILURE TO PROVIDE SERVICES, OR\n";  
echo "FOR ANY INFORMATION, SOFTWARE, PRODUCTS, SERVICES AND RELATED GRAPHICS OBTAINED THROUGH THIS WEB SITE, OR OTHERWISE ARISING OUT OF THE USE OF THIS WEB SITE, WHETHER BASED ON CONTRACT, TORT, STRICT LIABILITY OR OTHERWISE, EVEN IF $myname HAS BEEN ADVISED OF THE POSSIBILITY OF DAMAGES.\n\n"; 

echo "9. DUE TO THE FACT THAT CERTAIN JURISDICTIONS DO NOT PERMIT OR RECOGNIZE AN EXCLUSION OR LIMITATION OF LIABILITY FOR CONSEQUENTIAL OR INCIDENTAL DAMAGES, THE ABOVE LIMITATION MAY NOT APPLY TO YOU. IF YOU ARE DISSATISFIED WITH ANY PORTION OF THIS WEB SITE, OR WITH ANY OF THESE TERMS OF USE, YOUR SOLE AND EXCLUSIVE REMEDY IS TO DISCONTINUE USING THIS WEB SITE.\n\n";

echo "10. $myname reserves the right in its sole discretion to deny any user access to this Web site, any interactive service herein, or any portion of this Web site without notice, and the right to change the terms, conditions, and notices under which this Web site is offered.\n\n";

echo "11. This agreement is governed by the laws of the Kingdom of Belgium. You hereby consent to the exclusive jurisdiction and venue of courts of Brussels, Belgium. in all disputes arising out of or relating to the use of this Web site. Use of this Web site is unauthorized in any jurisdiction that does not give effect to all provisions of these terms and conditions, including without limitation this paragraph. You agree that no joint venture, partnership, employment, or agency relationship exists between you and $myname as a result of this agreement or use of this Web site. The performance of this agreement by $myname is subject to existing laws and legal process, and nothing contained in this agreement is in derogation of its right to comply with governmental, court and law enforcement requests or requirements relating to your use of this Web site or information provided to or gathered with respect to such use. If any part of this agreement is determined to be invalid or unenforceable pursuant to applicable law including, but not limited to, the warranty disclaimers and liability limitations set forth above, then the invalid or unenforceable provision will be deemed superseded by a valid, enforceable provision that most closely matches the intent of the original provision and the remainder of the agreement shall continue in effect.\n\n";

echo "12. This agreement constitutes the entire agreement between the user and $myname with respect to this Web site and it supersedes all prior or contemporaneous communications and proposals, whether electronic, oral or written with respect to this Web site. A printed version of this agreement and of any notice given in electronic form shall be admissible in judicial or administrative proceedings based upon or relating to this agreement to the same extent and subject to the same conditions as other business documents and records originally generated and maintained in printed form. Fictitious names of companies, products, people, characters and/or data mentioned herein are not intended to represent any real individual, company, product or event. Any rights not expressly granted herein are reserved.\n\n";

echo "13. $myname is located in Brussels, email: $email_feedback.\n\n";

echo "14. All contents of this Web site are: Copyright © $myname.\n\n";

} // End printTermsOfUse

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 

function printDetails() {

// ------------------------------------------------------------------------- 
// Globals
// ------------------------------------------------------------------------- 
global $myname;


// ------------------------------------------------------------------------- 
// How it works
// ------------------------------------------------------------------------- 

	echo "<div id=\"header21\">How it works</div>\n";

	echo "The normal way to connect to your FTP server is to use an FTP client and to communicate via the FTP protocol. This is however not always possible:\n";
	echo "<ul>\n";
	echo "<li>you may be behind a corporate firewall at work, which may block the FTP communications;\n";
	echo "<li>you may be on holiday and connecting to the internet via a CyberCafe, where you may not install an FTP client.\n";
	echo "</ul><br>\n";
	echo "With $myname, you connect to $myname using the HTTP protocol and a web browser, and $myname establishes a FTP connection with your FTP server.<br><br>\n";
	echo "You don't have to worry about keeping the connection alive, for example when you are coding a script for a long period of time, because no session information is kept on the $myname servers, and each time you connect to $myname, a new connection is made to your FTP server.<br><br>\n";
	echo "$myname also provides additional features, on top of the regular FTP features: the possibility to <b>edit code using your web browser</b>, and to view the code with <b>syntax highlighting</b>.<br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Data security
// ------------------------------------------------------------------------- 

	echo "<div id=\"header21\">Data security</div>\n";

	echo "<div id=\"header31\">On the internet</div>\n";
	echo "Password - As with regular FTP, your password is sent in clear text over the network.<br><br>\n";
	echo "Program Code - Idem. As with regular FTP, data is sent in clear text.<br><br>\n";
	echo "In the future, encrypted HTTPS connections might be offered on $myname. The password and code will be protected up to the $myname servers, but they will still be unencrypted from the $myname servers to your FTP server -- as is the case with regular FTP.<br><br><br>\n";

	echo "<div id=\"header31\">At $myname</div>\n";
	echo "Password - $myname does not log passwords.<br><br>\n";
	echo "Program Code - Once the data files are uploaded to the FTP server, they are erased from the $myname servers. While in transit, those files are inaccessible from the internet.<br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Data integrity
// ------------------------------------------------------------------------- 

	echo "<div id=\"header21\">Data integrity</div>\n";

	echo "<div id=\"header31\">Bugs</div>\n";
	echo "PLEASE MAKE A BACKUP OF YOUR DATA.<br>\n";
	echo "Although $myname has been tested intensively, there might still be some undiscovered bugs. If you think you found one, please <a href=\"" . printPHP_SELF() . "?state=feedback\" target=\"_blank\">contact us</a>.<br><br>\n";

	echo "<div id=\"header31\">FTP tranmission mode</div>\n";
	echo "When data is transferred using FTP, this can be done using the ASCII mode or the BINARY mode.<br>\n";
	echo "$myname makes this decision automatically based on the filename extension:\n";
	echo "<ul>\n";
	echo "	<li> The default mode is ASCII.\n";
	echo "	<li> The BINARY mode is used for:\n";
	echo "	<ul>\n";
	echo "		<li> images: png, jpg, jpeg, gif, bmp, tif, tiff;\n";
	echo "		<li> executables: exe, com, bin;\n";
	echo "		<li> MS Office documents: doc, xls, ppt, mdb, vsd, mpp;\n";
	echo "		<li> archives: zip, tar, gz, arj, arc;\n";
	echo "		<li> and others: mov, mpg, mpeg, ram, rm, qt, swf, fla, pdf, ps, wav.\n";
	echo "	</ul>\n";
	echo "</ul>\n";
	echo "If you would like other extensions to be transmitted using the BINARY mode, please <a href=\"" . printPHP_SELF() . "?state=feedback\" target=\"_blank\">contact us</a>.<br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Abuse
// ------------------------------------------------------------------------- 

	echo "<div id=\"header21\">Abuse</div>\n";

	echo "For every connection to $myname, these data are logged: time, browser IP address, target FTP server and FTP username. This is to prevent the abuse of $myname or FTP servers from $myname. The use of $myname is a priviledge, not a right, and users may be banned at the sole discretion of the $myname webmasters.<br><br>\n";
	echo "If you want your FTP server not to be accessible via $myname, please <a href=\"" . printPHP_SELF() . "?state=feedback\" target=\"_blank\">contact us</a>.<br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

} // End printDetails

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 

function printDownload() {

// ------------------------------------------------------------------------- 
// Globals
// ------------------------------------------------------------------------- 
global $myname, $mydomain;

	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" style=\"margin-top: 50px;\">\n";

// ------------------------------------------------------------------------- 
// Top: introduction
// ------------------------------------------------------------------------- 
	echo "<tr><td colspan=\"2\">\n";
	echo "<div id=\"header21\">Introduction</div>\n";
	echo "Although $myname is quite stable -- you use it here at $mydomain -- it is not yet \"polished\" enough to be easily installable on different platforms and on different versions and setups of PHP.<br><br>\n";
	echo "Feel free to download and use the development version, but do not expect it to work out-of-the-box. Let the Community know (via the user forum) what difficulties you encountered.<br><br>\n";
	echo "If you are a <b>PHP developer</b> or if you are a talented <b>designer</b>, feel free to make suggestions on how to improve the application!<br><br>\n";

	echo "</td></tr>\n";
	echo "<tr><td valign=\"top\" width=\"390\">\n";

// ------------------------------------------------------------------------- 
// Left: stable
// ------------------------------------------------------------------------- 
	echo "<div id=\"header21\">Stable version</div>\n";
	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n";
	echo "<tr><td>No stable version yet</td>   <td></td>   <td></td>   <td></td></tr>\n";
	echo "</tr></table>\n";



	echo "</td><td valign=\"top\" width=\"390\">\n";

// ------------------------------------------------------------------------- 
// Right: development
// ------------------------------------------------------------------------- 
	echo "<div id=\"header21\">Development version</div>\n";
	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n";
//	echo "<tr><td>Version 0.1</td>   <td><a href=\"download/net2ftp_v0.1.zip\">Download</a></td>   <td><a href=\"download/_CHANGES_v0.1\" target=\"_blank\">Changelog</a></td>   <td><a href=\"download/_TODO_v0.1\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "<tr><td>Version 0.1</td>   <td><a href=\"download/net2ftp_v0.1.zip\">Download</a></td>   <td><a href=\"download/_CHANGES_v0.1\" target=\"_blank\">Changelog</a></td>   <td><a href=\"download/_TODO_v0.1\" target=\"_blank\">Todo</a></td></tr>\n";
	echo "</tr></table>\n";


	echo "</td></tr>\n";
	echo "</table>\n";

	echo "<br><br><br>\n";
	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

} // End printDownload

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







// ************************************************************************************** 
// ************************************************************************************** 
// **                                                                                  ** 
// **                                                                                  ** 

function printScreenshots() {

global $client_imagesdir;

// ------------------------------------------------------------------------- 
// Table of Contents
// ------------------------------------------------------------------------- 

	echo "<a name=\"toc\">\n";
	echo "<div id=\"header21\">Table of Contents</div>\n";

	echo "<div style=\"margin-left: 50px;\">\n";
	echo "<ul>\n";
	echo "<li> <a href=\"#browse\"    style=\"font-size: 110%; font-weight: bold;\">Browse</a> The true heart of the application, from which all actions can be triggered\n";
	echo "<li> <a href=\"#edit\"      style=\"font-size: 110%; font-weight: bold;\">Edit</a> Edit text files right from your browser\n";
	echo "<li> <a href=\"#view\"      style=\"font-size: 110%; font-weight: bold;\">View</a> View text files with syntax highlighting, handy for HTML, Javascript, PHP and other languages\n";
	echo "<li> <a href=\"#upload\"    style=\"font-size: 110%; font-weight: bold;\">Upload</a> <a href=\"#download\" style=\"font-size: 110%; font-weight: bold;\">Download</a> Transfer files between your computer and the FTP server\n";
	echo "<li> <a href=\"#copy\"      style=\"font-size: 110%; font-weight: bold;\">Copy, Move, Delete</a> Manipulate single files, or entire directories recursively\n";
	echo "<li> <a href=\"#chmod\"     style=\"font-size: 110%; font-weight: bold;\">Chmod</a> Chmod directories or files (only on Linux/BSD/Unix based FTP servers)\n";
	echo "</ul>\n";
	echo "</div><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Browse view
// ------------------------------------------------------------------------- 

	echo "<a name=\"browse\">\n";
	echo "<img src=\"$client_imagesdir/browse1.png\" border=\"3\"><br><br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Edit files
// ------------------------------------------------------------------------- 

	echo "<a name=\"edit\">\n";
	echo "<img src=\"$client_imagesdir/edit1.png\" border=\"3\" width=\"900\"><br><br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// View a file
// ------------------------------------------------------------------------- 

	echo "<a name=\"view\">\n";
	echo "<img src=\"$client_imagesdir/view1.png\" border=\"3\"><br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Upload files
// ------------------------------------------------------------------------- 

	echo "<a name=\"upload\">\n";
	echo "<img src=\"$client_imagesdir/uploadfile1.png\" border=\"3\"><br><br><br>\n";
	echo "<img src=\"$client_imagesdir/uploadfile2.png\" border=\"3\"><br><br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Download files
// ------------------------------------------------------------------------- 

	echo "<a name=\"download\">\n";
	echo "<img src=\"$client_imagesdir/download1.png\" border=\"3\"><br><br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Copy, move or delete directories or files
// ------------------------------------------------------------------------- 

	echo "<a name=\"copy\">\n";
	echo "<img src=\"$client_imagesdir/copymovedelete1.png\" border=\"3\"><br><br><br>\n";
	echo "<img src=\"$client_imagesdir/copymovedelete2.png\" border=\"3\"><br><br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

// ------------------------------------------------------------------------- 
// Chmod directories or files
// ------------------------------------------------------------------------- 

	echo "<a name=\"chmod\">\n";
	echo "<img src=\"$client_imagesdir/chmod1.png\" border=\"3\"><br><br><br>\n";

	echo "<a href=\"" . printPHP_SELF() . "\" style=\"font-size: 110%; font-weight: bold;\">Back to the login page</a><br><br><br><br>\n";

} // End printScreenshots

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function checkAuthorization($ftpserver, $username) {

// --------------
// This function 
//    checks if the FTP server is in the list of those that may be accessed
//    checks if the FTP server is in the list of those that may NOT be accessed
//    checks if the IP address is in the list of banned IP addresses
// If all is OK, then the user may continue...
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $check_authorization, $net2ftp_allowed_ftpservers, $net2ftp_banned_ftpservers, $net2ftp_banned_addresses;
	global $REMOTE_ADDR;
	global $myname;

	if ($check_authorization == "yes") {

// -------------------------------------------------------------------------
// Check if the FTP server is in the list of those that may be accessed
// -------------------------------------------------------------------------
		if ($net2ftp_allowed_ftpservers[1] != "ALL") {       // net2ftp_allowed_servers contains either "ALL", either a list of allowed servers
			$result1 = array_search($ftpserver, $net2ftp_allowed_ftpservers);
			if ($result1 == false) { return putResult(false, "", "checkAuthorization", "checkAuthorization > Check 1, allowed FTP servers", "The FTP server <b>$ftpserver</b> is not in the list of allowed FTP servers. Please <a href=\"" . printPHP_SELF() . "?state=feedback\" target=\"_blank\">contact us</a> if you have any question.<br>"); }
		}

// -------------------------------------------------------------------------
// Check if the FTP server is in the list of those that may NOT be accessed
// -------------------------------------------------------------------------
		$result2 = array_search($ftpserver, $net2ftp_banned_ftpservers);
		if ($result2 != false) { return putResult(false, "", "checkAuthorization", "checkAuthorization > Check 2, banned FTP servers", "The FTP server <b>$ftpserver</b> is in the list of banned FTP servers. Please <a href=\"" . printPHP_SELF() . "?state=feedback\" target=\"_blank\">contact us</a> if you have any question.<br>"); }

// -------------------------------------------------------------------------
// Check if the IP address is in the list of banned IP addresses
// -------------------------------------------------------------------------
		$result3 = array_search($REMOTE_ADDR, $net2ftp_banned_addresses);
		if ($result3 != false) { return putResult(false, "", "checkAuthorization", "checkAuthorization > Check 3, banned IP addresses", "Your IP address ($REMOTE_ADDR) is in the list of banned IP addresses. Please <a href=\"" . printPHP_SELF() . "?state=feedback\" target=\"_blank\">contact us</a> if you have any question.<br>"); }

	} // end if check_authorization

// -------------------------------------------------------------------------
// If everything is OK, return true
// -------------------------------------------------------------------------
	return putResult(true, true, "", "", "");

} // end checkAuthorization

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function logAccess($page) {

// --------------
// This function logs user accesses to the site
// Used in the function HtmlBegin(), see file html.inc.php
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $log_access;
	global $myname;
	global $REMOTE_ADDR, $REMOTE_PORT, $HTTP_USER_AGENT, $HTTP_REFERER;
	global $state, $manage, $directory, $file;

// -------------------------------------------------------------------------
// Check if the logging of Errors is ON or OFF
// -------------------------------------------------------------------------
	if ($log_access == "yes") {

// -------------------------------------------------------------------------
// Connect to the DB
// -------------------------------------------------------------------------
		$resultArray = connect2db();
		$mydb = getResult($resultArray);
		if ($mydb == false) { return putResult(false, "", "logAccess", "logAccess > $resultArray[drilldown]", $resultArray[message]); }

// -------------------------------------------------------------------------
// Log the accesses
// -------------------------------------------------------------------------
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$sqlquerystring = "INSERT INTO net2ftp_logAccess VALUES('$date', '$time', '$REMOTE_ADDR', '$REMOTE_PORT', '$HTTP_USER_AGENT', '$page', '$state', '$manage', '$directory', '$file', '$HTTP_REFERER')";
		$result1 = @mysql_query($sqlquerystring);
		if ($result1 == false) { return putResult(false, "", "logAccess", "logAccess > sqlquery 1", "Unable to execute the SQL query 1<br>"); }
//		$affectedofrows = @mysql_affected_rows($mydb);

	} // end if logAccesses

// -------------------------------------------------------------------------
// If everything is OK, return true, let the user in
// -------------------------------------------------------------------------
	return putResult(true, true, "", "", "");

} // end logAccess()
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************






// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function logLogin($input_ftpserver, $input_username) {

// --------------
// This function logs user logins to the site
// Used in the index.php page
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $log_login;
	global $myname;
	global $REMOTE_ADDR, $REMOTE_PORT, $HTTP_USER_AGENT;

// -------------------------------------------------------------------------
// Connect to the DB
// -------------------------------------------------------------------------
	$resultArray = connect2db();
	$mydb = getResult($resultArray);
	if ($mydb == false) { return putResult(false, "", "logLogin", "logLogin > $resultArray[drilldown]", $resultArray[message]); }

// -------------------------------------------------------------------------
// Check if the logging of Logins is ON or OFF
// -------------------------------------------------------------------------
	if ($log_login == "yes") {

// -------------------------------------------------------------------------
// Log the Logins
// -------------------------------------------------------------------------
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$sqlquerystring = "INSERT INTO net2ftp_logLogin VALUES('$date', '$time', '$input_ftpserver', '$input_username', '$REMOTE_ADDR', '$REMOTE_PORT', '$HTTP_USER_AGENT')";
		$result1 = @mysql_query($sqlquerystring);
		if ($result1 == false) { return putResult(false, "", "logLogin", "logLogin > sqlquery 1", "Unable to execute the SQL query 1<br>"); }
//		$affectedofrows = @mysql_affected_rows($mydb);

	} // end if logLogins 

// -------------------------------------------------------------------------
// If everything is OK, return true, let the user in
// -------------------------------------------------------------------------
	return putResult(true, true, "", "", "");

} // end logLogin()
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
function logError($message, $cause, $drilldown, $debug1, $debug2, $debug3, $debug4, $debug5) {

// --------------
// This function logs user accesses to the site
// Used in the function printErrorMessage(), see file errorhandling.inc.php
// --------------

// -------------------------------------------------------------------------
// Global variables
// -------------------------------------------------------------------------
	global $log_error;
	global $net2ftp_ftpserver, $net2ftp_username;
	global $state, $manage, $directory;
	global $REMOTE_ADDR, $REMOTE_PORT, $HTTP_USER_AGENT;

// -------------------------------------------------------------------------
// Check if the logging of Errors is ON or OFF
// -------------------------------------------------------------------------
	if ($log_error == "yes") {

// -------------------------------------------------------------------------
// Connect to the DB
// -------------------------------------------------------------------------
		$resultArray = connect2db();
		$mydb = getResult($resultArray);
		if ($mydb == false) { return putResult(false, "", "logError", "logError > $resultArray[drilldown]", $resultArray[message]); }

// -------------------------------------------------------------------------
// Log the Errors
// -------------------------------------------------------------------------
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$sqlquerystring = "INSERT INTO net2ftp_logError VALUES('$date', '$time', '$net2ftp_ftpserver', '$net2ftp_username', '$message', '$cause', '$drilldown', '$state', '$manage', '$directory', '$debug1', '$debug2', '$debug3', '$debug4', '$debug5', '$REMOTE_ADDR', '$REMOTE_PORT', '$HTTP_USER_AGENT')";
		$result1 = @mysql_query($sqlquerystring);
		if ($result1 == false) { return putResult(false, "", "loguser", "loguser > sqlquery 1", "Unable to execute the SQL query 1<br>"); }
//		$affectedofrows = @mysql_affected_rows($mydb);

	} // end if logErrors

// -------------------------------------------------------------------------
// If everything is OK, return true, let the user in
// -------------------------------------------------------------------------
	return putResult(true, true, "", "", "");

} // end logError()
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************

?>
