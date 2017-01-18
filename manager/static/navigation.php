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
?>
<ul>
	<li><a href="<? echo $homeURL; ?>"><?=$lang['home'];?></a></li>
	<li><a href="<? echo $listURL; ?>"><?=$lang['listings'];?></a></li>
	<li><a href="<? echo $searchURL; ?>"><?=$lang['search'];?></a></li>
	<li><a href="<? echo $listFeatURL; ?>"><?=$lang['featured'];?></a></li>
	<li><a href="<? echo $aboutURL; ?>"><?=$lang['about'];?></a></li>
	
	<?
	/* <li><a href="<? echo $contactURL; ?>"><?=$lang['contact'];?></a></li> */ // put back above for the contact us page.
	if(!$phprealty->checkLogin()){
	?>
	<li><a href="<? echo $loginURL; ?>"><?=$lang['login'];?></a></li>
	<? } ?>
</ul>
<?
// if the user is logged in show them the admin menu
if($phprealty->checkLogin()==true){
?>
<h2><?=$lang['admintag'];?></h2>
<ul>
	<li><a href="<? echo $mgrURL; ?>"><?=$lang['adminhome'];?></a></li>
	<li><a href="<? echo $addUserURL; ?>"><?=$lang['addusr'];?></a></li>
	<li><a href="<? echo $listUserURL; ?>"><?=$lang['listusr'];?></a></li>
	<li><a href="<? echo $addPropURL; ?>"><?=$lang['realadd'];?></a></li>
	<li><a href="<? echo $listPropURL; ?>"><?=$lang['reallist'];?></a></li>
	<li><a href="<? echo $featURL; ?>"><?=$lang['realffeat'];?></a></li>
	<li><a href="<? echo $homeFeatURL; ?>"><?=$lang['homefeat'];?></a></li>
	<li><a href="<? echo $commFeatURL; ?>"><?=$lang['comunfeat'];?></a></li>
	<li><a href="<? echo $logoutURL; ?>"><?=$lang['logout'];?></a></li>
</ul>
<?
}
?>


