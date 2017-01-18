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
// lets get the active property listings..
extract($config);	// get config urls
$i='';

?>
<div id="listings">

<?
// lets grab the properties
if($result = $phprealty->getIntTableRows($fields="*", $from="property", $where="status=1", $sort="id", $dir="DESC", $limit="", $push=true)){
	if(count($result)>0){
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
	<div class="<? echo ((++$i%2==0)?'evenTR':'oddTR'); ?>" style="padding:10px;margin:10px 0px;">
		<div class="imgL">
<?
	// lets see if there is an image for this listing
	if($iresult = $phprealty->getIntTableRows($fields="*", $from="prop_img", $where="p_id=".$res['id']." AND def=1", $sort="", $dir="", $limit="1", $push=true)){
?>
	<a href="<? echo $viewPropURL."&propID=".$res['id']; ?>" title="<?=$lang['propview'];?>"><img src="<? echo $phprealty->IMGWWW."th_".$iresult[0]['fn']; ?>" border="0" /></a>
<?		
	}else{
?>
	<a href="<? echo $viewPropURL."&propID=".$res['id']; ?>" title="<?=$lang['propview'];?>"><img src="<? echo $phprealty->WWW; ?>assets/no_img.png" border="0" /></a>
<?
	}
?>
	</div><!-- end img div -->
	<div class="infoL">
		<div class="titleL"><a href="<? echo $viewPropURL."&propID=".$res['id']; ?>" title="<?=$lang['propview'];?>"><? echo ucwords($res['title'])." - ".$cf->formatWithSymbol($res['price'],"USD"); if($res['featured']==1){ ?>&nbsp;<span style="font-size:14px;font-style:oblique;">(<?=$lang['realfeat'];?>)</span><? } ?></a></div>
		<div class="descL"><strong><?=$lang['addr'];?>:</strong><br /><? echo ucwords($res['address'])."<br />".ucwords($res['city']).", ".ucwords($res['state'])." ".$res['zip']; ?><br />
		<strong><?=$lang['summ'];?>:</strong> <br /><? echo substr($res['full_desc'],0,250); ?>... <a href="<? echo $viewPropURL."&propID=".$res['id']; ?>" title="<?=$lang['propview'];?>"><?=$lang['clickinfo'];?></a></div>
	</div><!-- end info div -->
	<div style="clear:both;">&nbsp;</div>
	</div>
<?
	}// end foreach
	}else{ // no listings
		echo $lang['noreals'];
	}
}else{
	// no listings
		echo $lang['noreals'];
}
?>
<div id="Plinks"><? 
if(count($result)>0){
echo $PaginateIt->GetPageLinks(); 
}
?> <?=$lang['page'];?></div>
</div>
