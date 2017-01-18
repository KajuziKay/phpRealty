<?
/* ------------------------------------------------------------------------------------------------
phpRealty site index page.
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05
------------------------------------------------------------------------------------------------ */
$error = '';
extract($config); // extract config variables
if(isset($_GET['delID']) && is_numeric($_GET['delID'])){
	// this is a delete action
	if($phprealty->dbQuery("DELETE FROM ".$phprealty->db."user WHERE id='".$_GET['delID']."'")){
		$error = $lang['adminusrdel'];
	}else{
		$error = $lang['adminusrdelerr'];
	}
}
?>
<div id="post-1" class="post">
<h2 class="title"><a href="<? echo $listUserURL; ?>"><?=$lang['adminusrlist'];?></a></h2>

<div class="entry">
<? if($error){ ?>
<div class="error"><? echo $error; ?></div>
<? } ?>
<table id="a_list" border="0" cellpadding="3" cellspacing="3" width="100%">
<tr class="headTR">
	<td class="headTD"><?=$lang['adminusrwho'];?></td>
	<td class="headTD"><?=$lang['adminusrname'];?></td>
	<td class="headTD"><?=$lang['adminusrmail'];?></td>
</tr>
<?
// lets get the list of users
if(!$result = $phprealty->getIntTableRows($fields="*", $from="user", $where="", $sort="uname", $dir="ASC", $limit="", $push=true)){
	// no results found
?>
	<tr><td colspan="3"><p><?=$lang['adminusrnobody'];?></p></td></tr>
<?
}else{
	// found results
	// lets build the list
	$a = count($result);
	$b=0;
	while($b<$a){
?>
	<tr>
		<td class="unameTD"><a href="<? echo $phprealty->makeUrl(21,false,'?p=2&editID='.$result[$b]['id']); ?>" title="<?=$lang['edit'];?>"><? echo $result[$b]['uname']; ?></a> <a href="<? echo $phprealty->makeUrl(21,false,'?p=3&delID='.$result[$b]['id']); ?>" style="color:#ff0000;font-style:oblique;" title="<?=$lang['delete'];?>">X</a></td>
		<td class="nameTD"><? echo ucfirst($result[$b]['lname']).", ".ucfirst($result[$b]['fname']); ?></td>
		<td class="emailTD"><a href="mailto:<? echo $result[$b]['email']; ?>"><? echo $result[$b]['email']; ?></a></td>
	</tr>
<?	
	$b++;
	}// end while
}// end if for result

?>

</table>
</div>
</div>
