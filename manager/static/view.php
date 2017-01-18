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
// check if they have a valid property id
if(!isset($_GET['propID']) || !is_numeric($_GET['propID']) || empty($_GET['propID'])){
	echo "Sorry that Property is no longer in our system!";
	return;
}//

	// include currency converter / formater
	include(INC."curr_conv.class.php");
	$cf = new CurrencyFormatter();
	//use = $cf->formatWithSymbol($price, "AUD");	


// lets grab all the information for the property
if(!$result = $phprealty->getIntTableRows($fields="*", $from="property", $where="id=".$_GET['propID'], $limit="1")){
	echo "Sorry that Property is no longer in our system!";
}else{
	// get images for property
	$iresult = $phprealty->getIntTableRows($fields="fn", $from="prop_img", $where="p_id=".$_GET['propID'], $sort="def", $dir="DESC");
	$a = count($iresult);
	if($a > 0){
		// create the javascript functions and preload
	?>
	<script language="javascript" type="text/javascript">
	<?
		$z = 0;
		foreach($iresult as $ires){
	?>
		var pic<? echo $z; ?>_new = new Image();
		pic<? echo $z; ?>_new.src="assets/images/th_<? echo $ires['fn']; ?>";
	<?
		$z++;
		}
	?>
	// change image function
		function changeIMG(num,num2,num3,num4) {
				if(document.images)
				{
				document.images[num].src = eval(num2 + "_new.src");
				document.images[num].width = num3;
				document.images[num].height = num4;
				}
		}//end function
	// change image link for full size		
		function changeLink(){
				var str = document.images['imgMain'].src;
				Msrc = str.replace("th_","");
				window.open(Msrc,'Listing','width=800,height=600,resizable=yes,scrollbars=yes');
		}	
	</script>
	<?	
	}// end if image count is larger than 0
}
?>
<div id="propView">
	<div id="propImg">
		<div id="mainImg">
			<?
				if($a > 0){
				//show the default image
			?>
				<a href='#' onclick="javascript:changeLink(); return false"><img src="<? echo $phprealty->IMGWWW."th_".$iresult[0]['fn']; ?>" alt="Property Image" border="0" id="imgMain" name="imgMain" /></a>
			<?
				}else{
			?>
				<img src="<? echo $phprealty->WWW."assets/no_img.png"; ?>" alt="<?=$lang['noimg'];?>" border="0" />
			<?
			}
			?>
		</div>
		<div id="linksImg">
			<?
				if($a>0){
				// show extra image links
				$b=0;
				while($b < $a){
				$size = getimagesize($phprealty->IMGDIR."th_".$iresult[$b]['fn']);
			?>
				<a href="#" onclick="changeIMG('imgMain','pic<? echo $b; ?>',<? echo round($size[0]); ?>,<? echo round($size[1]); ?>); return false;" title="<?=$lang['showimg'];?>"><?=$lang['imgtxt'];?> <? echo $b+1; ?></a><br />
			<?
				$b++;
				unset($size);
				}// end while
				}
			?>
		</div>
		<div style="clear:both;text-align:center;"><? if($a > 0){ ?><?=$lang['imgclickhint'];?><? } ?></div>
	</div><!-- end propImg -->
	<div id="propTitle">
		<? echo stripslashes(ucfirst($result[0]['title'])); if($result[0]['featured']==1){ echo " <span style=\"font-size:14px;font-style:oblique;\">(FEATURED)</span>"; } ?><br />
		<strong><?=$lang['addr'];?>:</strong><br /><? echo stripslashes(ucwords($result[0]['address'])); ?><br />
		<? echo stripslashes(ucwords($result[0]['city'])); ?>, <? echo stripslashes(ucwords($result[0]['state'])); ?> <? echo stripslashes(ucfirst($result[0]['zip'])); ?><br />
		<strong><?=$lang['listprice'];?>:</strong> <? echo $cf->formatWithSymbol($result[0]['price'],"USD"); ?><br />
		<strong><?=$lang['listtype'];?>:</strong> <? echo ucwords($result[0]['type']); ?><br  />
		<strong><?=$lang['listphone'];?>:</strong> <? echo $result[0]['mls']; ?>
	</div><!-- end propTitle -->
	<div style="clear:both;">&nbsp;</div><!-- end clear -->
	<div id="propDesc">
		<strong><?=$lang['listdesc'];?>:</strong><br />
		<? echo nl2br(stripslashes(ucfirst($result[0]['full_desc']))); ?>
		<br /><br />
	</div><!-- end propDesc -->
	<div id="propAttributes">
		<div id="Att1">
			<? 
			if(!empty($result[0]['beds'])){ echo "<strong>".$lang['listbeds'].":</strong> ".$result[0]['beds']."<br />"; }
			if(!empty($result[0]['baths'])){ echo "<strong>".$lang['listbaths'].":</strong> ".$result[0]['baths']."<br />"; }
			if(!empty($result[0]['floors'])){ echo "<strong>".$lang['listfloors'].":</strong> ".$result[0]['floors']."<br />"; }
			if(!empty($result[0]['year'])){ echo "<strong>".$lang['listbuildyear'].":</strong> ".$result[0]['year']."<br />"; }
			?>
		</div>
		<div id="Att2">
			<? 
			if(!empty($result[0]['garage'])){ echo "<strong>".$lang['listgarage'].":</strong> ".$result[0]['garage']."<br />"; }
			if(!empty($result[0]['tax'])){ echo "<strong>".$lang['listtaxes'].":</strong> ".$cf->formatWithSymbol($result[0]['tax'],"USD")."<br />"; }
			if(!empty($result[0]['sqfeet'])){ echo "<strong>".$lang['listsqfeet'].":</strong> ".$result[0]['sqfeet']."<br />"; }
			if(!empty($result[0]['lot_w']) && !empty($result[0]['lot_l'])){ echo "<strong>".$lang['listlotsize'].":</strong> ".$result[0]['lot_w']."x".$result[0]['lot_l']."<br />"; }
			?>
		</div>
		<div id="propSep">&nbsp;</div>
		<div id="propFeat">
			<? if(!empty($result[0]['features'])){ ?>
			<strong><?=$lang['homefeat'];?>:</strong><br />
			<? echo str_replace(",",", ",$result[0]['features']); }?><br /><br />
			
			<? if(!empty($result[0]['comm_feat'])){ ?>
			<strong><?=$lang['comunfeat'];?>:</strong><br />
			<? echo str_replace(",",", ",$result[0]['comm_feat']); }?><br />
			
		</div>
	</div><!-- end property attributes -->

</div><!-- end propView -->
