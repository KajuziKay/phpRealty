<?
/* ------------------------------------------------------------------------------------------------
phpRealty site index page.
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05
------------------------------------------------------------------------------------------------ */
if(!isset($_GET['propID']) ||  !is_numeric($_GET['propID'])){
	$error = "You need a valid Property ID!<br><a href='".$listPropURL."'>List Properties</a>";
	echo "<script language='javascript'> window.alert('You need a valid Property ID!'); window.location='".$mgrURL."'; </script>";
}

// delete image from property.
if(isset($_GET['delID']) && is_numeric($_GET['delID'])){
	// this is a delete action
	// get the file names first
	if($result = $phprealty->getIntTableRows($fields='fn', $from='prop_img', $where='id='.$_GET['delID'], $push=true)){
	
		if($phprealty->dbQuery("DELETE FROM ".$phprealty->db."prop_img WHERE id='".$_GET['delID']."'")){
			// unlink the images
			@unlink($phprealty->IMGDIR.$result[0]['fn']);
			@unlink($phprealty->IMGDIR."th_".$result[0]['fn']);
			$error = $lang['adminremlisting'];
		}else{
			$error = $lang['adminremlistingerr'];
		}
	}else{
		$error = "Error: Could not remove Property! Image not found!";
	}// end if for getting file name
}

// make image default
// first update other images.. to make them not default
if(isset($_GET['defID']) && is_numeric($_GET['defID'])){
if($result = $phprealty->updIntTableRows($fields=array("def"=>0), $into="prop_img", $where="p_id=".$_GET['propID'])){
	// set property images to 0 so now lets update the selected image to default
	if($result = $phprealty->updIntTableRows($fields=array("def"=>1), $into="prop_img", $where="p_id=".$_GET['propID']." AND id=".$_GET['defID'])){
		// it is set
		$error = $lang['adminrealimg1'];
	}else{
		$error = $lang['adminrealimg2'];
	}
}// end if for clear default
}// end if for if default ID


// add image
if(isset($_FILES['pImg']) && isset($_POST['submit'])){
	// lets add the image
	//first lets upload the image	
	// make a datetime prefix to add to the name
	$pre = date("Ymdhis")."_";
	if($phprealty->saveThumbnail($phprealty->IMGDIR, $_FILES['pImg']['tmp_name'], $pre.$_FILES['pImg']['name'], $prefix="th_", 200, 200,$w=false)){
		// thumb saved so lets do the full image
		if($phprealty->saveThumbnail($phprealty->IMGDIR, $_FILES['pImg']['tmp_name'], $pre.$_FILES['pImg']['name'], $prefix="", 800, 800,$w=false)){
			// large image saved lets do the database insert
			if(!$result = $phprealty->putIntTableRow($fields=array("p_id"=>$_GET['propID'], "fn"=>$pre.$_FILES['pImg']['name']), $into="prop_img")){
				// there was an error
				$error = $lang['adminrealimg3'];
			}else{
				// insert was successful
				$error = $lang['adminrealimg4'];
			}
		}// end if for save of larg image
	}// end if for save of thumb image
}

// action URL
$propImgURL .= "&propID=".$_GET['propID'];
// get property information for a heading
if(!$result = $phprealty->getIntTableRows($fields="title,address,city,state,zip", $from="property", $where="id=".$_GET['propID'],$sort="", $dir="", $limit="", $push=true)){
	// there was an error
	$error = $lang['adminrealimg5']."<br><a href='".$listPropURL."'>".$lang['reallist']."</a>";
	echo "<script language='javascript'> window.alert('".$lang['adminrealimg5']."'); window.location='".$mgrURL."'; </script>";
}else{
	// got the property
	extract($result[0]);
}

?>
<script language="javascript">
<!--//

	/************************************************************************************************************
	(C) www.dhtmlgoodies.com, October 2005
	
	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	
	
	Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.
	
	Thank you!
	
	Updated:	April, 6th 2006, Using iframe in IE in order to make the tooltip cover select boxes.
	
	www.dhtmlgoodies.com
	Alf Magne Kalleland
	
	************************************************************************************************************/	
	var dhtmlgoodies_tooltip = false;
	var dhtmlgoodies_tooltipShadow = false;
	var dhtmlgoodies_shadowSize = 0;
	var dhtmlgoodies_tooltipMaxWidth = 800;
	var dhtmlgoodies_tooltipMinWidth = 100;
	var dhtmlgoodies_iframe = false;
	var tooltip_is_msie = (navigator.userAgent.indexOf('MSIE')>=0 && navigator.userAgent.indexOf('opera')==-1 && document.all)?true:false;
	function showTooltip(e,tooltipTxt)
	{
		tooltipTxt = "<a href='#' onclick='hideTooltip(); return false;'><div><img src='"+tooltipTxt+"' border='0' alt='Property Image' /></div><div align='center' style='font-weight:bold;'>CLICK TO CLOSE</div></a>";
		
		var bodyWidth = Math.max(document.body.clientWidth,document.documentElement.clientWidth) - 20;
	
		if(!dhtmlgoodies_tooltip){
			dhtmlgoodies_tooltip = document.createElement('DIV');
			dhtmlgoodies_tooltip.id = 'dhtmlgoodies_tooltip';
			dhtmlgoodies_tooltipShadow = document.createElement('DIV');
			dhtmlgoodies_tooltipShadow.id = 'dhtmlgoodies_tooltipShadow';
			
			document.body.appendChild(dhtmlgoodies_tooltip);
			document.body.appendChild(dhtmlgoodies_tooltipShadow);	
			
			if(tooltip_is_msie){
				dhtmlgoodies_iframe = document.createElement('IFRAME');
				dhtmlgoodies_iframe.frameborder='5';
				dhtmlgoodies_iframe.style.backgroundColor='#FFFFFF';
				dhtmlgoodies_iframe.src = '#'; 	
				dhtmlgoodies_iframe.style.zIndex = 100;
				dhtmlgoodies_iframe.style.position = 'absolute';
				document.body.appendChild(dhtmlgoodies_iframe);
			}
			
		}
		
		dhtmlgoodies_tooltip.style.display='block';
		dhtmlgoodies_tooltipShadow.style.display='block';
		if(tooltip_is_msie)dhtmlgoodies_iframe.style.display='block';
		
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		if(navigator.userAgent.toLowerCase().indexOf('safari')>=0)st=0; 
		var leftPos = e.clientX + 10;
		
		dhtmlgoodies_tooltip.style.width = null;	// Reset style width if it's set 
		dhtmlgoodies_tooltip.innerHTML = tooltipTxt;
		dhtmlgoodies_tooltip.style.left = leftPos + 'px';
		dhtmlgoodies_tooltip.style.top = e.clientY + 10 + st + 'px';

		
		dhtmlgoodies_tooltipShadow.style.left =  leftPos + dhtmlgoodies_shadowSize + 'px';
		dhtmlgoodies_tooltipShadow.style.top = e.clientY + 10 + st + dhtmlgoodies_shadowSize + 'px';
		
		if(dhtmlgoodies_tooltip.offsetWidth>dhtmlgoodies_tooltipMaxWidth){	/* Exceeding max width of tooltip ? */
			dhtmlgoodies_tooltip.style.width = dhtmlgoodies_tooltipMaxWidth + 'px';
		}
		
		var tooltipWidth = dhtmlgoodies_tooltip.offsetWidth;		
		if(tooltipWidth<dhtmlgoodies_tooltipMinWidth)tooltipWidth = dhtmlgoodies_tooltipMinWidth;
		
		
		dhtmlgoodies_tooltip.style.width = tooltipWidth + 'px';
		dhtmlgoodies_tooltipShadow.style.width = dhtmlgoodies_tooltip.offsetWidth + 'px';
		dhtmlgoodies_tooltipShadow.style.height = dhtmlgoodies_tooltip.offsetHeight + 'px';		
		
		if((leftPos + tooltipWidth)>bodyWidth){
			dhtmlgoodies_tooltip.style.left = (dhtmlgoodies_tooltipShadow.style.left.replace('px','') - ((leftPos + tooltipWidth)-bodyWidth)) + 'px';
			dhtmlgoodies_tooltipShadow.style.left = (dhtmlgoodies_tooltipShadow.style.left.replace('px','') - ((leftPos + tooltipWidth)-bodyWidth) + dhtmlgoodies_shadowSize) + 'px';
		}
		
		if(tooltip_is_msie){
			dhtmlgoodies_iframe.style.left = dhtmlgoodies_tooltip.style.left;
			dhtmlgoodies_iframe.style.top = dhtmlgoodies_tooltip.style.top;
			dhtmlgoodies_iframe.style.width = dhtmlgoodies_tooltip.offsetWidth + 'px';
			dhtmlgoodies_iframe.style.height = dhtmlgoodies_tooltip.offsetHeight + 'px';
		
		}
				
	}
	
	function hideTooltip()
	{
		dhtmlgoodies_tooltip.style.display='none';
		dhtmlgoodies_tooltipShadow.style.display='none';		
		if(tooltip_is_msie)dhtmlgoodies_iframe.style.display='none';		
	}
	
//-->
</script>
<style type="text/css">
	#dhtmlgoodies_tooltip{
		background-color:#FFF;
		border:1px solid #000;
		position:absolute;
		display:none;
		z-index:20000;
		padding:2px;
		font-size:0.9em;
		-moz-border-radius:6px;	/* Rounded edges in Firefox */
		font-family: "Trebuchet MS", "Lucida Sans Unicode", Arial, sans-serif;
		
	}
	#dhtmlgoodies_tooltipShadow{
		position:absolute;
		background-color:#555;
		display:none;
		z-index:10000;
		opacity:0.7;
		filter:alpha(opacity=70);
		-khtml-opacity: 0.7;
		-moz-opacity: 0.7;
		-moz-border-radius:6px;	/* Rounded edges in Firefox */
	}
</style>
<div id="post-1" class="post">
<h2 class="title"><a href=""><?=$lang['adminrealimg6'];?></a></h2>
<div class="entry">
<? if($error){ ?>
<div class="error"><? echo $error; ?></div>
<? } ?>
<div class="manImgTitle"><?=$lang['adminrealimg7'];?>: <? echo ucwords($title)." - ".ucwords($address).", ".ucwords($city).", ".ucwords($state)." ".$zip; ?></div>
<p>
<form method="post" name="imgManage" action="<? echo $propImgURL; ?>" enctype="multipart/form-data">
	<input type="file" name="pImg" size="35"><br /><input type="submit" name="submit" value="<?=$lang['submit'];?>">
</form>
</p>
<hr size="2" color="#DEDEDE" style="display:block;margin-bottom:5px;" />
<div id="imgL" name="imgL" align="center" style="padding-top:5px;position:absolute;left:200px;top:450px;display:none;visibility:hidden;border:#000000 solid 1px;z-index:100;background-color:#ffffff;height:100px;width:100px;"></div>
<?
// get current image list
// use the 300px wide image for display 
unset($result);
if($result = $phprealty->getIntTableRows($fields="*", $from="prop_img", $where="p_id=".$_GET['propID'], $sort="id", $dir="", $limit="", $push=true)){
	// got the list lets start to output them
	$a = count($result);
	$b = 0;
	$c = 1;
	while($b < $a){
	$size = getimagesize($phprealty->IMGDIR.$result[$b]['fn']);
?>
	<div style="float:left;text-align:center;margin:0px 5px;"><div><a href="#" onclick='showTooltip(event,"<? echo $phprealty->IMGWWW.$result[$b]['fn']; ?>"); return false'><img src="<? echo $phprealty->IMGWWW; ?>th_<? echo $result[$b]['fn']; ?>" border="0" alt="<?=$lang['imgtxt'];?>" /></a></div><div align="center"><a href="<? echo $phprealty->makeUrl(21,false,'?p=9&propID='.$_GET['propID'].'&delID='.$result[$b]['id']); ?>" title="<?=$lang['del'];?>"><?=$lang['rem'];?></a> &nbsp;&nbsp;<a href="<? echo $phprealty->makeUrl(21,false,'?p=9&propID='.$_GET['propID'].'&defID='.$result[$b]['id']); ?>" title="<?=$lang['cdef'];?>"><?=$lang['mdef'];?></a> &nbsp;<? if($result[$b]['def'] == 1){ ?><br /><span style='color:#00ff00;font-weight:bold;'><?=$lang['cdef'];?></span><? } ?></div></div>
<?
		$b++;
		if($c==1){$c=2; }elseif($c==2){ $c=1; echo "<div style='clear:both'>&nbsp;</div>\n"; }
	}// end while
	if($c==2){echo "<div style='clear:both'>&nbsp;</div>\n"; }
}
?>
</div>
</div>
