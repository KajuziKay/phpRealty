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
$i = '';
$b = '';

extract($config); // extract config variables
if(isset($_GET['fID']) && is_numeric($_GET['fID'])){
	// this is a delete action
	if($phprealty->updIntTableRows($fields=array("featured"=>0), $into="property", $where="id=".$_GET['fID'])){
		$error = $lang['adminreallist1'];
	}else{
		$error = $lang['adminreallist2'];
	}
}
?>
<div id="post-1" class="post">
<h2 class="title"><a href="<? echo $featURL; ?>"><?=$lang['realffeat'];?></a></h2>

<div class="entry">
<? if($error){ ?>
<div class="error"><? echo $error; ?></div>
<? } ?>
<table id="a_list" border="0" cellpadding="3" cellspacing="3" width="100%">
<tr class="headTR">
	<td class="headTD"><?=$lang['adminlistreal2'];?></td>
	<td class="headTD"><?=$lang['addr'];?></td>
	<td class="headTD"><?=$lang['adminlistreal6'];?></td>
</tr>

<?
if(!$result = $phprealty->getIntTableRows($fields="*", $from="property", $where="featured=1", $sort="id", $dir="DESC", $limit="", $push=true)){
?>
<tr><td colspan="4"><p><?=$lang['adminlistrealerr1'];?></p></td></tr>
<?
}else{
	$a=count($result);
	
	// include the pagination script
	include(INC."PaginateIt.php");
	
	//$PaginateIt = new PaginateIt();
	$PaginateIt->SetItemsPerPage(6);
	$PaginateIt->SetItemCount($a);
	$PaginateIt->SetLinksFormat( '<<', ' | ', '>>' );
	$result = $PaginateIt->GetCurrentCollection($result);
	
	// include currency converter / formater
	include(INC."curr_conv.class.php");
	$cf = new CurrencyFormatter();
	//use = $cf->formatWithSymbol($price, "AUD");	
	foreach($result as $res){
?>
<tr class="<? echo ((++$i%2==0)?'evenTR':'oddTR'); ?>">
	<td class="titleTD" valign="top"><a href="<? echo $phprealty->makeUrl(21,false,'?p=4&editID='.$res['id']); ?>" title="<?=$lang['edit'];?>"><? echo ucwords($res['title']); ?></a>
	<?
		if($res['featured']==1){
	?>
		&nbsp;<span style="font-size:14px;font-style:oblique;"><?=$lang['realfeat'];?></span>
	<? } ?>
	</td>
	<td class="addTD" valign="top"><? echo ucwords($res['address'])."<br />".ucwords($res['city']).", ".ucwords($res['state'])." ".$res['zip']; ?></td>
	<td class="actTD" valign="top"><a href="<? echo $phprealty->makeUrl(21,false,'?p=4&editID='.$res['id']); ?>" title="<?=$lang['edit'];?>"><?=$lang['edit'];?></a> &nbsp;<a href="<? echo $phprealty->makeUrl(21,false,'?p=5&delID='.$res['id']); ?>" title="<?=$lang['delete'];?>"><?=$lang['delete'];?></a> &nbsp;<a href="<? echo $phprealty->makeUrl(21,false,'?p=9&propID='.$res['id']); ?>" title="MANAGE IMAGES">Images</a> &nbsp;<a href="<? echo $phprealty->makeUrl(21,false,'?p=6&fID='.$res['id']); ?>" title="REMOVE FEATURED ATTRIBUTE">Not Featured</a></td>
</tr>
<?
	$b++;
	}// end while

}// end if for result
?>
</table>
<div id="Plinks"><? echo $PaginateIt->GetPageLinks(); ?> <?=$lang['page'];?></div>
</div>
</div>
