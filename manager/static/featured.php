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
<div id="post-1" class="fList">
<h2 class="title"><a href="<? echo $featURL; ?>"><?=$lang['realfeat'];?></a></h2>

<?
if($result = $phprealty->getIntTableRows($fields="*", $from="property", $where="featured=1", $sort="id", $dir="DESC", $limit="", $push=true)){
	// include currency converter / formater
	include(INC."curr_conv.class.php");
	$cf = new CurrencyFormatter();
	//use = $cf->formatWithSymbol($price, "AUD");	

	// lets randomize the array 
	$a = count($result);
	shuffle($result);
	$c = 3;	// the max listings to display
	if($a < $c){ $c = $a; }
	$b = 0;
	while($b < $c){
?>
<div id="a_list">
	<div class="titleTD"><a href="<? echo $viewPropURL."&propID=".$result[$b]['id']; ?>" title="<?=$lang['propview'];?>"><? echo ucwords($result[$b]['title'])." - ".$cf->formatWithSymbol($result[$b]['price'],"USD"); ?></a></div>
	<div class="addTD"><? echo ucwords($result[$b]['address'])."<br />".ucwords($result[$b]['city']).", ".ucwords($result[$b]['state'])." ".$result[$b]['zip']; ?></div>
	<div class="descTD"><blockquote><? echo substr(ucfirst($result[$b]['full_desc']),0,250); ?></blockquote></div>
	<hr size="2" color="#dedede" style="visibility:visible;display:block;margin:5px 0px;" />
</div>
<?	
		$b++;
	}// end while
}// end featured list
?>
</div>
