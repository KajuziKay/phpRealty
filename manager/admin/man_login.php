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
------------------------------------------------------------------------------------------------ */

extract($config);

$error = '';
$show = '';

if(isset($_REQUEST['logout']) && $_REQUEST['logout']==1){
	$_SESSION = array();
	session_destroy();
	$error = "YOU HAVE BEEN LOGGED OUT!";
	$show = true;	
}

if((isset($_REQUEST['uname']) && !empty($_REQUEST['uname'])) && (isset($_REQUEST['pass']) && !empty($_REQUEST['pass']))){
	$uname = $_REQUEST['uname'];
	$pass = $_REQUEST['pass'];
	// we can try to log in the user now
	if(!$phprealty->userLogin($uname,$pass)){
		// username and password did not match
		$error = $lang['error'].'&nbsp;'.$lang['adminloginerr'];
		$show = true;
	}else{
		header("Location: ".$mgrURL);
	}
}// end if for the form being show
else{
	$uname = '';
	$pass = '';
	if(isset($_REQUEST['submit'])){
	$error = $lang['error'].'&nbsp;'.$lang['adminloginerr2'];
	}
	$show = true;
}

if($show==true){
?>
			<div id="post-1" class="post">
				<h2 class="title"><a href="#"><?=$lang['adminhi'];?> to phpRealty v0.03!</a></h2>
				<div class="entry">
			<? if($error != "" || $error != NULL){ ?>
			<div class="error"><?=$error; ?></div>
			<? } ?>
			<table width="500" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td valign="top"><h3><!--title--> <?=$lang['adminlogin1'];?></h3><?=$lang['adminlogin2'];?></td>
					<td>
						<form method="post" action="<?=$_SERVER['PHP_SELF']; ?>?a=2" name="man_login" id="man_login">
						<table width="100%" border="0">
							<tr>
								<td><strong><?=$lang['adminlogin3'];?>:</strong></td>
								<td><input type="text" name="uname" id="uname" size="20" maxlength="14" value="<?=$uname; ?>"  /></td>
							</tr>
							<tr>
								<td><strong><?=$lang['adminlogin4'];?>:</strong></td>
								<td><input type="password" name="pass" id="pass" size="20" maxlength="9" value="" /></td>
							</tr>
							<tr>
								<td colspan="2"><input type="submit" name="submit" value="<?=$lang['adminlogingo'];?>" /></td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
			</table>
				</div>
				<div class="hr">
					<hr />
				</div>
			</div>

<?
}// end if for showing the form
?>
