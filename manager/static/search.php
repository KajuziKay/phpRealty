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
// search form and results
extract($config);
$kw = '';
$beds = '';
$baths = '';
$floors = '';
$state = '';
$city = '';
extract($_REQUEST);
?>

<form method="get" name="search" action="<? echo $searchURL; ?>">
<input type="hidden" name="a" id="a" value="<? echo $_REQUEST['a']; ?>">
<strong><?=$lang['searchkey'];?>:</strong> <input type="text" name="kw" id="kw" size="45" value="<? echo $kw; ?>" />&nbsp;<input type="submit" name="submit" value="<?=$lang['srchgo'];?>" /><br /><br />
<fieldset>
<table width="100%" border="0">
	<tr>
		<td width="50%"><strong><?=$lang['srchcrt1'];?>:</strong> <input type="text" maxlength="2" size="4" name="beds" id="beds" value="<? echo $beds; ?>" /></td>
		<td width="50%"><strong><?=$lang['srchcrt2'];?>:</strong> <input type="text" maxlength="2" name="baths" id="baths" value="<? echo $baths; ?>" size="4" /></td>
	</tr>
	<tr>
		<td><strong><?=$lang['srchcrt3'];?>:</strong> <input type="text" maxlength="2" size="4" name="floors" id="floors" value="<? echo $floors; ?>" /></td>
		<td><strong><?=$lang['srchcrt4'];?>:</strong> <select name="price" id="price"><option value="0" selected="selected"><?=$lang['srchcrt7'];?></option><option value="1"><?=$lang['srchcrtprice1'];?></option><option value="2"><?=$lang['srchcrtprice2'];?></option><option value="3"><?=$lang['srchcrtprice3'];?></option><option value="4"><?=$lang['srchcrtprice4'];?></option><option value="5"><?=$lang['srchcrtprice5'];?></option><option value="6"><?=$lang['srchcrtprice6'];?></option></select></td>
	</tr>
	<tr>
		<td><strong><?=$lang['srchcrt5'];?>:</strong> <input type="text" name="state" id="state" value="<? echo $state; ?>" /></td>
		<td><strong><?=$lang['srchcrt6'];?>:</strong> <input type="text" name="city" id="city" value="<? echo $city; ?>" /></td>
	</tr>
</table>
</fieldset>
</form>
<hr size="2" style="visibility:visible; display:block;" />
<?

if(isset($_REQUEST['submit']) && !empty($_REQUEST['submit'])){

// build the search query this could get messy
$sql = "SELECT * FROM ".$phprealty->db."property ";
if(!empty($kw)){
$sql2 .= "(title LIKE '%$kw%' OR full_desc LIKE '%$kw%') ";
}
if(!empty($beds)){
	if(!empty($sql2)){
		$sql2 .= "AND beds='$beds' ";
	}else{
		$sql2 .= "beds='$beds' ";
	}
}

if(!empty($baths)){
	if(!empty($sql2)){
		$sql2 .= "AND baths='$baths' ";
	}else{
		$sql2 .= "baths='$baths' ";
	}
}

if(!empty($floors)){
	if(!empty($sql2)){
		$sql2 .= "AND floors='$floors' ";	
	}else{
		$sql2 .= "floors='$floors' ";	
	}
}

if(!empty($state)){
	if(!empty($sql2)){
		$sql2 .= "AND state='$state' ";
	}else{
		$sql2 .= "state='$state' ";
	}
}

if(!empty($city)){
	if(!empty($sql2)){
		$sql2 .= "AND city='$city' ";
	}else{
		$sql2 .= "city='$city' ";
	}
}

if(!empty($price) && $price > 0 && $price <= 6){
	// first get the correct amounts
	switch($price){
		case 1:
			$psql = "price BETWEEN 0 and 50000";
			break;
		case 2:
			$psql = "price BETWEEN 50000 and 100000";
			break;
		case 3:
			$psql = "price BETWEEN 100000 and 200000";
			break;
		case 4:
			$psql = "price BETWEEN 200000 and 500000";
			break;
		case 5:
			$psql = "price BETWEEN 500000 and 750000";
			break;
		case 6:
			$psql = "price >= 750000";
			break;
	}

	if(!empty($sql2)){
		$sql2 .= "AND ".$psql." ";
	}else{
		$sql2 .= $psql." ";
	}
}

// query string to get num rows to set the limit
$sql3 = "SELECT id FROM ".$phprealty->db."property ";
if(!empty($sql2)){
	$sql3 .= "WHERE ".$sql2;
}

if(!empty($sql2)){
$sql .= "WHERE ".$sql2;
}

	if(!$result = $phprealty->dbQuery($sql3)){
		echo $lang['nomatch'];	
	}else{
	//echo $sql3;
		$nums = mysql_num_rows($result);
		
		$a = mysql_num_rows($result);

		// include the pagination script
		include(INC."PaginateIt.php");
		
		//$PaginateIt = new PaginateIt();
		$PaginateIt->SetItemsPerPage(6);
		$PaginateIt->SetItemCount($a);
		$PaginateIt->SetLinksFormat( '<<', ' | ', '>>' );
		$res = $phprealty->dbQuery($sql." ".$PaginateIt->GetSqlLimit());
		if(mysql_num_rows($res) < 1){
			echo $lang['nomatch'];
		}else{
		// include currency converter / formater
		include(INC."curr_conv.class.php");
		$cf = new CurrencyFormatter();
?>

<div class="SearchTitle"><?=$lang['srchrez'];?></div>
<div id="listings">
<?		
		while($res2 = mysql_fetch_array($res)){
?>
	<div class="<? echo ((++$i%2==0)?'evenTR':'oddTR'); ?>" style="padding:10px;margin:10px 0px;">
		<div class="imgL">
<?
	// lets see if there is an image for this listing
	if($iresult = $phprealty->getIntTableRows($fields="*", $from="prop_img", $where="p_id=".$res2['id']." AND def=1", $sort="", $dir="", $limit="1", $push=true)){
?>
	<a href="<? echo $viewPropURL."&propID=".$res2['id']; ?>" title="<?=$lang['propview'];?>"><img src="<? echo $phprealty->IMGWWW."th_".$iresult[0]['fn']; ?>" border="0" /></a>
<?		
	}else{
?>
	<a href="<? echo $viewPropURL."&propID=".$res2['id']; ?>" title="<?=$lang['propview'];?>"><img src="<? echo $phprealty->WWW; ?>assets/no_img.png" border="0" /></a>
<?
	}
?>
	</div><!-- end img div -->
	<div class="infoL">
		<div class="titleL"><a href="<? echo $viewPropURL."&propID=".$res2['id']; ?>" title="<?=$lang['propview'];?>"><? echo ucwords($res2['title'])." - ".$cf->formatWithSymbol($res2['price'],"USD"); if($res2['featured']==1){ ?>&nbsp;<span style="font-size:14px;font-style:oblique;"><?=$lang['realfeat'];?></span><? } ?></a></div>
		<div class="descL"><strong><?=$lang['addr'];?>:</strong><br /><? echo ucwords($res2['address'])."<br />".ucwords($res2['city']).", ".ucwords($res2['state'])." ".$res2['zip']; ?><br />
		<strong><?=$lang['summ'];?>:</strong> <br /><? echo substr($res2['full_desc'],0,250); ?>... <a href="<? echo $viewPropURL."&propID=".$res2['id']; ?>" title="<?=$lang['propview'];?>"><?=$lang['clickinfo'];?></a></div>
	</div><!-- end info div -->
	<div style="clear:both;">&nbsp;</div>
	</div>
<?
		}// end while
?>
</div>
<?
		}// end if for while query 
		
	}// end if for query
}// end if for submit

?>
<? if(isset($_REQUEST['submit']) && !empty($_REQUEST['submit']) && $a > 0){ ?>
<div id="Plinks">
<? echo $PaginateIt->GetPageLinks()." ".$lang['page']."</div>"; }?>
