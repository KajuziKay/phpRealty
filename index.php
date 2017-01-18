<?
/* ------------------------------------------------------------------------------------------------
phpRealty site index page.
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05
Modifications: 01-04-2008 by Bogdan Thazer - bogdan2ky@gmail.com
Modifications: 09-07-2008 by John Carlson from version 0.03 - 0.05
Modifications: 09-07-2008 by John Carlson from version 0.03 - 0.05
------------------------------------------------------------------------------------------------ */
ob_start(); // start content
// include phprealty class and config
//define("IN_SYSTEM",false);
require("manager/includes/config.inc.php");

require(INC."phprealty.class.php");

include(LANG);
if (is_readable(LANG)){
// start phprealty class
$phprealty = new phprealty;

// debug for mysql true if you want to get sql errors with the query sent to the system
$phprealty->dumpSQL = true;

// set template path and file name
$phprealty->construct(TEMPS,"theme.html");
// start session function
startCMSSession();

// include urls
include(INC."config.urls.php");

// lets get the page action for the content source
$action = isset($_REQUEST['a']) ? $_REQUEST['a'] : 1;

// PAGE SOURCE CHOOSER
switch($action){
/********************************************************************/
/*  content management - show the requested frame          */
/********************************************************************/
	case 1:
		// home page view
		$snippet = @file_get_contents(TEMPS."index.php");
		break;
		
	case 2:
		// member login / logout
		$snippet = @file_get_contents(MGR."admin/man_login.php");
		break;
		
	case 21:
		// main page for member area
		if($phprealty->checkLogin()==true){
			$snippet = @file_get_contents(MGR."admin/index.php");
		}else{
			header("Location: index.php?a=2");
		}
		break;

	case 3:
		// view listings
			$snippet = @file_get_contents(TEMPS."p_list.php");
			break;
			
	case 31:
		// view propertys
			$snippet = @file_get_contents(TEMPS."p_view.php");
			break;
			
	case 4:
		// search properties
			$snippet = @file_get_contents(TEMPS."p_search.php");
			break;
			
	case 5:
		// list featured listings
			$snippet = @file_get_contents(TEMPS."f_list.php");
			break;
			
	case 6:
		// about us page
			$snippet = @file_get_contents(TEMPS."about.php");
			break;
		
	default :
		// this is a backup just incase the action does not get set above
		// default if no action was sent lets show a not valid error.
		$snippet = $notfound."<br />";
		$snippet .= $err404;
		break;

}// end switch for frame source

$phprealty->replace("title",SITENAME);		// set title to site
$phprealty->replace("WWW",WWW);		// set the url to the start of the site
$phprealty->parseSnippet(LANG,$_REQUEST);
$content = $phprealty->parseSnippet($snippet,$_REQUEST);		// run snippet content
$content = $phprealty->parseSnippets($content,$_REQUEST);
$phprealty->replace("content",$content);		// replace content section based on above actions
//$navigation = @file_get_contents(MGR."static/nav.php");
//$navigation = $phprealty->parseSnippet($navigation,$_REQUEST); 	// run navigation content
//$phprealty->replace("navigation",$navigation);		// replace navigation content
$phprealty->replace("TEMPS",WTEMPS);		// replace urls to point to the correct directory

if($phprealty->dumpSQL){
echo $phprealty->queryCode;
}

print_r($phprealty->get());		// output the page

ob_end_flush(); // ouput content
} else {
	echo '<br /> <center><strong>ERROR! while reading language file.<br />Check file existence and properties (CHMOD)<br /></strong></center>';}
?>
