<?
/* ------------------------------------------------------------------------------------------------
phpRealty config file
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-07
Version: 0.05
Modifications: 01-04-2008 by Bogdan Thazer - bogdan2ky@gmail.com
Modifications: 09-07-2008 by John Carlson from version 0.03 - 0.05
------------------------------------------------------------------------------------------------ */

// database connection information
$database_type = "mysql";
$database_server = "localhost";
$database_user = "php-realty.com";
$database_password = "raven925";
$dbase = "php_realty_com_v03";
$table_prefix = "phprealty_";

// other global site variables
define('WWW',"http://php-realty.com/phprealtyv05/"); // with trailing slash
define('REL',$_SERVER['DOCUMENT_ROOT']."/phprealtyv05/"); // adjust this to the directory of phpRealty if it is not your document_root. WITH TRAILING SLASH "/"
define('TEMPS',REL."templates/default/"); // change to the folder that you use for your template, with trailing slash
define('WTEMPS',WWW."templates/default/"); // change to the folder that you use for your template, with trailing slash
define('SITENAME',"phpRealty v0.05"); // site name
define('LANG',"templates/languages/english.inc.php"); // path to language file. there are 2 ways to set this:
					       // ex1:LANG = "templates/languages/english.inc.php" OR 
					       // ex2:LANG = "/home/user/path/to/phprealty/templates/languages/english.inc.php"
						   
$from_name = '';
$from = '';
$subject = '';


/* ##########################  DON'T CHANGE BELOW HERE #######################*/
// more global variables.. 
define('MGR',REL."manager/");
define('INC',REL."manager/includes/");
define('IMGDIR',REL."assets/images/");
define('IMGWWW',WWW."assets/images/");

if(!$sitehost = str_replace("www.","",$_SERVER['HTTP_HOST'])){
	$sitehost = $_SERVER['HTTP_HOST'];
}

if(isset($_GET['a']) && ($_GET['a'] == 2 || $_GET['a'] == 21)){
	define('IN_SYSTEM','true');
}else{
	define('IN_SYSTEM','false');
}

// create an installation specific site id and session name
$site_id = str_replace("`","",$dbase)."_" . $table_prefix;

if(IN_SYSTEM == "true")
{
  $site_sessionname = $site_id . "mgr";
}
else
{
  $site_sessionname = $site_id . "web";
}

if(!function_exists("startCMSSession")){
  function startCMSSession(){
    global $site_sessionname, $sitehost;
	ini_set('session.cookie_lifetime', '7200');
    session_name($site_sessionname);
    session_start();
  }
}

?>
