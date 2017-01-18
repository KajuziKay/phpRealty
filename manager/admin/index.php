<?
/* ------------------------------------------------------------------------------------------------
phpRealty site index page.
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05
------------------------------------------------------------------------------------------------ */
// lets get the page action for the content source
$action = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;

// PAGE SOURCE CHOOSER
switch($action){
/********************************************************************/
/*  content management - show the requested frame          */
/********************************************************************/
	case 1:
		// main page view
		$snippet = file_get_contents(MGR."admin/welcome.php");
		break;
		
	case 2:
		// add user
		$snippet = file_get_contents(MGR."admin/u_ins.php");
		break;
		
	case 3:
		// list / manage users
			$snippet = file_get_contents(MGR."admin/u_list.php");
			break;
			
	case 4:
		// add property
			$snippet = file_get_contents(MGR."admin/p_ins.php");
			break;
			
	case 5:
		// add property
			$snippet = file_get_contents(MGR."admin/p_list.php");
			break;
			
	case 6:
		// view featured listings
			$snippet = file_get_contents(MGR."admin/f_list.php");
			break;
			
	case 7:
		// manage home features
			$snippet = file_get_contents(MGR."admin/hf.php");
			break;
		
	case 8:
		// manage community features
			$snippet = file_get_contents(MGR."admin/cf.php");
			break;
	case 9:
		// image management for properties
			$snippet = file_get_contents(MGR."admin/img_ins.php");
			break;

	default :
		// this is a backup just incase the action does not get set above
		// default if no action was sent lets show a not valid error.
		$snippet = "<h2>Page not Found</h2>";
		$snippet .= "<h2>You are not supposed to be here. Please go back and try again!</h2>";
		break;

}// end switch for frame source

$output = $phprealty->parseSnippet($snippet,$_REQUEST);
print_r($output);
?>
