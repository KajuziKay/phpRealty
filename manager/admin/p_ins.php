<?
/* ------------------------------------------------------------------------------------------------
phpRealty site index page.
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05
------------------------------------------------------------------------------------------------ */
// extract config variables
$error = '';
$title = '';
$feat_check = '';
$address = '';
$city = '';
$state = '';
$zip = '';
$price = '';
$beds = '';
$baths = '';
$floors = '';
$garage = '';
$year = '';
$sqfeet = '';
$lot_w = '';
$lot_l = '';
$tax = '';
$mls = '';
$home_check = '';
$farm_check = '';
$land_check = '';
$commercial_check = '';
$rental_check = '';
$active_check = '';
$nactive_check = '';
$full_desc = '';
$notes = '';
$comm_list = '';
$feat_list = '';
$editID = '';

extract($config);

// include validation class
include(MGR."includes/validate.class.php");
// initialize validator class
$validator = new Validator();

// make home features list
	if($result = $phprealty->getIntTableRows($fields="*", $from="features", $where="", $sort="name", $dir="ASC", $limit="", $push=true)){
		$a = count($result);
		$b = 0;
		$feat_list = "";
		while($b < $a){
				if(!empty($_POST['features'])){
				if(in_array($result[$b]['name'],$_POST['features'])){
					${$result[$b]['name']."_feature"}="checked";
				}
				}else{
					${$result[$b]['name']."_feature"}='';
				}
			$feat_list .= "<input type='checkbox' name='features[]' value='".$result[$b]['name']."' ".${$result[$b]['name']."_feature"}."> ".ucwords($result[$b]['name'])."<br>\n";
			$b++;
		} // end while
	} // end if for home features display

// make community features list
	if($result2 = $phprealty->getIntTableRows($fields="*", $from="comm_feat", $where="", $sort="name", $dir="ASC", $limit="", $push=true)){
		$a = count($result2);
		$b = 0;
		$comm_list = "";
		while($b < $a){
				if(!empty($_POST['features2'])){
				if(in_array($result2[$b]['name'],$_POST['features2'])){
					${$result2[$b]['name']."_feature"}="checked";
				}
				}
			$comm_list .= "<input type='checkbox' name='features2[]' value='".$result2[$b]['name']."' ".${$result2[$b]['name']."_feature"}."> ".ucwords($result2[$b]['name'])."<br>\n";
			$b++;
		} // end while
	} // end if for home features display
	
	if(isset($_POST['featured']) && $_POST['featured']==1){ $feat_check = "checked"; }
	if(isset($_POST['type']) && $_POST['type']=="Home"){ $home_check = "selected='selected'"; }
	if(isset($_POST['type']) && $_POST['type']=="Farm"){ $farm_check = "selected='selected'"; }
	if(isset($_POST['type']) && $_POST['type']=="Land"){ $land_check = "selected='selected'"; }
	if(isset($_POST['type']) && $_POST['type']=="Commercial"){ $commercial_check = "selected='selected'"; }
	if(isset($_POST['type']) && $_POST['type']=="Rental"){ $rental_check = "selected='selected'"; }
	if(isset($_POST['status']) && $_POST['status']=="1"){ $active_check = "selected='selected'"; }else{ $nactive_check = "selected='selected'"; }

if((!isset($_GET['editID']) || !$_GET['editID'] || !is_numeric($_GET['editID'])) && isset($_POST['submit'])){
	// new property
	// error checking
		$validator->addRule(new RuleNotNull('title','TITLE required.<br>'));
		$validator->addRule(new RuleNotNull('address','ADDRESS required.<br>'));
		$validator->addRule(new RuleNotNull('city', 'CITY required.<br>'));
		$validator->addRule(new RuleNotNull('state', 'STATE required.<br>'));
		$validator->addRule(new RuleNotNull('price', 'PRICE required.<br>'));
		$validator->addRule(new RuleNotNull('full_desc', 'FULL DESCRIPTION required.<br>'));
		
		
		$validator->validate($_POST);
			if($validator->isValid()){
				extract($_POST);
				if(!empty($features)){$features = implode(',',$_POST['features']);}
				if(!empty($features2)){$features2 = implode(',',$_POST['features2']);}
				$title = ucwords($_POST['title']);
				$address = ucwords($_POST['address']);
				$city = ucwords($_POST['city']);
				$state = ucwords($_POST['state']);
				$full_desc = addslashes($full_desc);
				$notes = addslashes($notes);
								
				$fields = array("title"=>$title, "address"=>$address, "city"=>$city, "state"=>$state, "zip"=>$zip, "price"=>$price, "beds"=>$beds, "baths"=>$baths, "floors"=>$floors, "garage"=>$garage, "year"=>$year, "sqfeet"=>$sqfeet, "lot_w"=>$lot_w, "lot_l"=>$lot_l, "tax"=>$tax, "status"=>$status, "mls"=>$mls, "type"=>$type, "full_desc"=>$full_desc, "notes"=>$notes, "features"=>$features, "comm_feat"=>$features2, "featured"=>$featured);
				$into = "property";
				
				if($result = $phprealty->putIntTableRow($fields,$into)){
					$error = $lang['adminaddrealok'];
					$features = "";
					$features2 = "";
					$title = "";
					$add = "";
					$city = "";
					$state = "";
					$noform = true;
				}else{
					$error = $lang['adminaddrealnok'];
				}
				
			}else{
				// there were errors
					$errormsg = '<strong>'.$lang['errors'].':</strong><br> '.implode('',$validator->getErrorMsg());
					$error = $errormsg;
			}
	
}elseif(isset($_GET['editID']) && is_numeric($_GET['editID'])){
	// edit properrty
	$editID = $_GET['editID'];
	$addPropURL .= "&editID=".$_GET['editID'];

	// check to see if form was submitted
	if(isset($_REQUEST['submit'])){
		// lets try to update the property
	// error checking
		$validator->addRule(new RuleNotNull('title','TITLE required.<br>'));
		$validator->addRule(new RuleNotNull('address','ADDRESS required.<br>'));
		$validator->addRule(new RuleNotNull('city', 'CITY required.<br>'));
		$validator->addRule(new RuleNotNull('state', 'STATE required.<br>'));
		$validator->addRule(new RuleNotNull('price', 'PRICE required.<br>'));
		$validator->addRule(new RuleNotNull('full_desc', 'FULL DESCRIPTION required.<br>'));
		
		
		$validator->validate($_POST);
			if($validator->isValid()){
				extract($_POST);
				if(!empty($features)){$features = implode(',',$_POST['features']);}
				if(!empty($features2)){$features2 = implode(',',$_POST['features2']);}
				$title = ucwords($_POST['title']);
				$address = ucwords($_POST['address']);
				$city = ucwords($_POST['city']);
				$state = ucwords($_POST['state']);
				$full_desc = addslashes($full_desc);
				$notes = addslashes($notes);
								
				$fields = array("title"=>$title, "address"=>$address, "city"=>$city, "state"=>$state, "zip"=>$zip, "price"=>$price, "beds"=>$beds, "baths"=>$baths, "floors"=>$floors, "garage"=>$garage, "year"=>$year, "sqfeet"=>$sqfeet, "lot_w"=>$lot_w, "lot_l"=>$lot_l, "tax"=>$tax, "status"=>$status, "mls"=>$mls, "type"=>$type, "full_desc"=>$full_desc, "notes"=>$notes, "features"=>$features, "comm_feat"=>$features2, "featured"=>$featured);
				$into = "property";
				
				if(!$result = $phprealty->updIntTableRows($fields, $into, $where="id=".$_GET['editID'], $sort="", $dir="", $limit="")){
					// there was an error updating the property
					$error = $lang['adminupdrealnok'];
				}else{
					// property updated
					$_POST = array();
					$error = $lang['adminupdrealok'];
					$features = "";
					$features2 = "";
					$title = "";
					$add = "";
					$city = "";
					$state = "";
					$noform = true;
				}
			}else{
				// display validation errors
				$errormsg = '<strong>'.$lang['errors'].':</strong><br> '.implode('',$validator->getErrorMsg());
				$errors = $errormsg;
			}
	}else{
		// pull id information
		if(!$result = $phprealty->getIntTableRows($fields="*", $from="property", $where="id=".$_GET['editID'], $sort="", $dir="", $limit="", $push=true)){
			$error = $lang['adminrealgone'];
			$noform = true;
		}else{
			extract($result[0]);
			if($featured==1){ $feat_check="checked"; }
			if($features){$features=explode(',',$features);}
			if($comm_feat){$features2=explode(',',$comm_feat);}
			if($type=="Home"){ $home_check = "selected='selected'"; }
			if($type=="Farm"){ $farm_check = "selected='selected'"; }
			if($type=="Land"){ $land_check = "selected='selected'"; }
			if($type=="Commercial"){ $commercial_check = "selected='selected'"; }
			if($type=="Rental"){ $rental_check = "selected='selected'"; }
			if($status=="1"){ $active_check = "selected='selected'"; }else{ $nactive_check = "selected='selected'"; }
				// make home features list
					if($result = $phprealty->getIntTableRows($fields="*", $from="features", $where="", $sort="name", $dir="ASC", $limit="", $push=true)){
						$a = count($result);
						$b = 0;
						$feat_list = "";
						while($b < $a){
								if(!empty($features)){
								if(in_array($result[$b]['name'],$features)){
									${$result[$b]['name']."_feature"}="checked";
								}
								}
							$feat_list .= "<input type='checkbox' name='features[]' value='".$result[$b]['name']."' ".${$result[$b]['name']."_feature"}."> ".ucwords($result[$b]['name'])."<br>\n";
							$b++;
						} // end while
					} // end if for home features display
				
				// make community features list
					if($result2 = $phprealty->getIntTableRows($fields="*", $from="comm_feat", $where="", $sort="name", $dir="ASC", $limit="", $push=true)){
						$a = count($result2);
						$b = 0;
						$comm_list = "";
						while($b < $a){
								if(!empty($features2)){
								if(in_array($result2[$b]['name'],$features2)){
									${$result2[$b]['name']."_feature"}="checked";
								}
								}
							$comm_list .= "<input type='checkbox' name='features2[]' value='".$result2[$b]['name']."' ".${$result2[$b]['name']."_feature"}."> ".ucwords($result2[$b]['name'])."<br>\n";
							$b++;
						} // end while
					} // end if for home features display
			

		}// end if for result
	}// end if for submit

}else{
	// first time through just show the form
	$noform = false;
}

?>

<div id="post-1" class="post">
<h2 class="title"><a href="<? echo $addPropURL; ?>"><? if(isset($_GET['editID'])){ ?><?=$lang['adminaddreal1'];?><? }else{ ?><?=$lang['adminaddreal1'];?><? } ?></a></h2>

<div class="entry">

<? if($error){ ?>
<div class="error"><? echo $error; ?></div>
<? } ?>

<? if(!$noform || $noform==false){ ?>

<form name="user_ins" method="post" action="<? echo $addPropURL; ?>">
<input type="hidden" name="edit_id" value="<? echo $editID; ?>">
	  <table width="100%" border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td width="120" valign="top"><?=$lang['adminlistreal2'];?>: * </td>
          <td valign="top"><input name="title" type="text" id="title" value="<? echo $title; ?>" size="30"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['realfeat'];?>:</td>
          <td valign="top"><input name="featured" type="checkbox" id="featured" value="1" <? echo $feat_check; ?>></td>
        </tr>
        <tr>
          <td width="120" valign="top"><?=$lang['addr'];?>: * </td>
          <td valign="top"><input name="address" type="text" id="address" value="<? echo $address; ?>" size="30"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['city'];?>: * </td>
          <td valign="top"><input name="city" type="text" id="city" value="<? echo $city; ?>" size="30"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['state'];?>: * </td>
          <td valign="top"><input name="state" type="text" id="state" value="<? echo $state; ?>" size="30"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['zip'];?>:  * </td>
          <td valign="top"><input name="zip" type="text" id="zip" value="<? echo $zip; ?>" size="8" maxlength="5"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['listprice'];?>: * </td>
          <td valign="top"><input name="price" type="text" id="price" value="<? echo $price; ?>" size="30"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal4'];?>: </td>
          <td valign="top"><input name="beds" type="text" id="beds" value="<? echo $beds; ?>" size="8" maxlength="2"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal5'];?>: </td>
          <td valign="top"><input name="baths" type="text" id="baths" value="<? echo $baths; ?>" size="8" maxlength="2"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal6'];?>:</td>
          <td valign="top"><input name="floors" type="text" id="floors" value="<? echo $floors; ?>" size="8" maxlength="2"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal7'];?>: </td>
          <td valign="top"><input name="garage" type="text" id="garage" value="<? echo $garage; ?>" size="30"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal8'];?>:</td>
          <td valign="top"><input name="year" type="text" id="year" value="<? echo $year; ?>" size="8" maxlength="4"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal9'];?>: </td>
          <td valign="top"><input name="sqfeet" type="text" id="sqfeet" value="<? echo $sqfeet; ?>" size="8" maxlength="5"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal10'];?>:</td>
          <td valign="top"><input name="lot_w" type="text" id="lot_w" value="<? echo $lot_w; ?>" size="4" maxlength="4"> 
            <?=$lang['adminaddreal11'];?> X 
              <input name="lot_l" type="text" id="lot_l" value="<? echo $lot_l; ?>" size="4" maxlength="4"> 
              <?=$lang['adminaddreal12'];?></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['listtaxes'];?>:</td>
          <td valign="top"><input name="tax" type="text" id="tax" value="<? echo $tax; ?>" size="10" maxlength="10"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['listphone'];?>:</td>
          <td valign="top"><input name="mls" type="text" id="mls" value="<? echo $mls; ?>" size="30"></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['listtype'];?>:</td>
          <td valign="top"><select name="type" id="type">
            <option value="Home" <? echo $home_check; ?>><?=$lang['adminrealtype1'];?></option>
            <option value="Farm" <? echo $farm_check; ?>><?=$lang['adminrealtype2'];?></option>
            <option value="Land" <? echo $land_check; ?>><?=$lang['adminrealtype3'];?></option>
            <option value="Commercial" <? echo $commercial_check; ?>><?=$lang['adminrealtype4'];?></option>
            <option value="Rental" <? echo $rental_check; ?>><?=$lang['adminrealtype5'];?></option>
          </select></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal14'];?>:</td>
          <td valign="top"><select name="status" id="status">
            <option value="1" <? echo $active_check; ?>><?=$lang['adminaddreal14'];?></option>
            <option value="0"<? echo $nactive_check; ?>><?=$lang['adminaddreal15'];?></option>
          </select></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal16'];?>: * </td>
          <td valign="top"><textarea name="full_desc" cols="30" rows="6" id="full_desc"><? echo $full_desc; ?></textarea></td>
        </tr>
        <tr>
          <td valign="top"><?=$lang['adminaddreal17'];?>:<br>
            (<?=$lang['adminaddreal18'];?>) </td>
          <td valign="top"><textarea name="notes" cols="30" rows="6" id="notes"><? echo $notes; ?></textarea></td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="50%" valign="top"><br><strong><?=$lang['adminaddreal19'];?>:</strong><br>
			  	<?
					// feature list feat_list
					echo $feat_list;
				?>
			  </td>
              <td width="50%" valign="top"><br><strong><?=$lang['adminaddreal20'];?>:</strong><br>
			  	<?
					// comm list comm_list
					echo $comm_list;
				?>
			  </td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td colspan="2" valign="top"><?=$lang['admintickrequired'];?></td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><input type="submit" name="submit" value="<?=$lang['submit'];?>"></td>
          </tr>
      </table>
</form>
<? } ?>
</div>
</div>
