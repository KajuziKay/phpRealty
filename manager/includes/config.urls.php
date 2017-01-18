<?
/* ------------------------------------------------------------------------------------------------
phpRealty site index page.
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05
------------------------------------------------------------------------------------------------ */
// create URLs for the system.

// create urls ahead of time with makeUrl()

$config['homeURL'] = $phprealty->makeUrl(1,false,'');	// home url
$config['loginURL'] = $phprealty->makeUrl(2,false,'');	// login
$config['logoutURL'] = $phprealty->makeUrl(2,false,'?logout=1');	// logout
$config['mgrURL'] = $phprealty->makeUrl(21,false,'');	// manager home url
$config['addUserURL'] = $phprealty->makeUrl(21,false,'?p=2');	// add user
$config['listUserURL'] = $phprealty->makeUrl(21,false,'?p=3');	// list / manage users
$config['addPropURL'] = $phprealty->makeUrl(21,false,'?p=4');	// add property
$config['listPropURL'] = $phprealty->makeUrl(21,false,'?p=5');	// list / manage properties
$config['propImgURL'] = $phprealty->makeUrl(21,false,'?p=9'); // image management
$config['featURL'] = $phprealty->makeUrl(21,false,'?p=6');	// list featured properties
$config['homeFeatURL'] = $phprealty->makeUrl(21,false,'?p=7');	// manage home features
$config['commFeatURL'] = $phprealty->makeUrl(21,false,'?p=8');	// manage community features
$config['listURL'] = $phprealty->makeUrl(3,false,'');	// list properties
$config['viewPropURL'] = $phprealty->makeUrl(31,false,'');		// view property
$config['searchURL'] = $phprealty->makeUrl(4,false,'');	// search page
$config['listFeatURL'] = $phprealty->makeUrl(5,false,'');	// list featured homes
$config['aboutURL'] = $phprealty->makeUrl(6,false,'');	// about us
$config['contactURL'] = $phprealty->makeUrl(7,false,'');	// contact us
$config['MGR'] = MGR;
$config['INC'] = INC;
$config['WWW'] = WWW;
$config['REL'] = REL;
$config['SITENAME'] = SITENAME;
$config['from_name'] = $from_name;
$config['from'] = $from;
$config['subject'] = $subject;
$config['language'] = LANG;
?>
