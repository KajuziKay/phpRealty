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
$noform = '';
$errors = '';
$uname = '';
$pass = '';
$disabled = '';
$fname = '';
$lname = '';
$email = '';
$phone = '';
$mobile = '';
$fax = '';
$homepage = '';
$info = '';
$editID = '';

extract($config);

// include validation class
include(MGR."includes/validate.class.php");
// initialize validator class
$validator = new Validator();

if((!isset($_GET['editID']) || !$_GET['editID'] || !is_numeric($_GET['editID'])) && isset($_POST['submit'])){
	// new user
	// error checking
	$validator->addRule(new RuleNotNull('uname',$lang['adminusrerradd1'].'<br>'));
	if($_REQUEST['uname']!='' || $_REQUEST['uname']!=NULL){
	$validator->addRule(new checkUname('uname',$lang['adminusrerradd2'].'<br>'));
	$validator->addRule(new CheckLen('uname','5','14',$lang['adminusrerradd3'].'</br>'));
	}
	$validator->addRule(new RuleNotNull('fname',$lang['adminusrerradd4'].'<br>'));
	$validator->addRule(new RuleNotNull('lname', $lang['adminusrerradd5'].'<br>'));
	$validator->addRule(new RuleNotNull('password', $lang['adminusrerradd6'].'<br>'));
	if($_REQUEST['password']!='' || $_REQUEST['password']!=NULL){
	$validator->addRule(new CheckLen('password','5','14',$lang['adminusrerradd7'].'</br>'));
	}
	$validator->addRule(new RuleNotNull('password2', $lang['adminusrerradd8'].'<br>'));
	$validator->addRule(new ConfirmPass('password','password2', $lang['adminusrerradd9'].'<br>'));
	if($_REQUEST['password2']!='' || $_REQUEST['password2']!=NULL){
	$validator->addRule(new CheckLen('password2','5','14',$lang['adminusrerradd10'].'</br>'));
	}
	$validator->addRule(new RuleNotNull('email', $lang['adminusrerradd11'].'<br>'));
	$validator->addRule(new EmailValid('email', $lang['adminusrerradd12'].'<br>')); 
	if($phone != ""){
	$validator->addRule(new checkPhone('phone',$lang['adminusrerradd13'].'<br>'));
	}
	if($mobile != ""){
	$validator->addRule(new checkPhone('mobile',$lang['adminusrerradd14'].'<br>'));
	}
	if($fax != ""){
	$validator->addRule(new checkPhone('fax',$lang['adminusrerradd15'].'<br>'));
	}
	
	// check validation
	$validator->validate($_POST);
		// extract values from request
		extract($_POST);
		if($validator->isValid()){
			// lets do the insert
			$date = date("h:ia D, M jS, Y");
			$fname = ucfirst($fname);
			$lname = ucfirst($lname);
			$uname = strtolower($uname);
			$password = md5(strtolower($password));
			// insert record into db

			$fields = array ("uname"=>$uname,"fname"=>$fname,"lname"=>$lname,"password"=>$password,"email"=>$email,"phone"=>$phone,"mobile"=>$mobile,"fax"=>$fax,"homepage"=>$homepage,"info"=>$info,"mod_date"=>$date);
			$into = "user";
			$result = $phprealty->putIntTableRow($fields, $into);
			if(!$result){
				// there was an error
				$errors = $lang['adminusrerradd16'];
			}else{
				$_POST = array();
				$errors = $lang['adminusraddok'];
				$uname="";
				$fname="";
				$lname="";
				$password="";
				$password2="";
				$email="";
				$phone="";
				$mobile="";
				$fax="";
				$homepage="";
				$info="";
				$mod_date = "";
				$noform = true;
			}
			
		}else{
			// form values were not valid
			$errormsg = '<strong>'.$lang['errors'].':</strong><br> '.implode('',$validator->getErrorMsg());
			$errors = $errormsg;
			$visible = "";
		}


}elseif(isset($_GET['editID']) && is_numeric($_GET['editID'])){
	// we are doing an edit action
	// check to see if form was submitted
	
	$addUserURL .= "&editID=".$_GET['editID'];

	if(isset($_REQUEST['submit'])){
		// form submitted 
		// error checking
			$validator->addRule(new RuleNotNull('fname', $lang['adminusrerradd4'].'<br>'));
			$validator->addRule(new RuleNotNull('lname', $lang['adminusrerradd5'].'<br>'));
			if($_REQUEST['password']!='' || $_REQUEST['password']!=NULL){
			$validator->addRule(new RuleNotNull('password', $lang['adminusrerradd6'].'<br>'));
			$validator->addRule(new CheckLen('password','5','14', $lang['adminusrerradd7'].'</br>'));
			$validator->addRule(new RuleNotNull('password2', $lang['adminusrerradd8'].'<br>'));
			$validator->addRule(new ConfirmPass('password','password2', $lang['adminusrerradd9'].'<br>'));
			if($_REQUEST['password2']!='' || $_REQUEST['password2']!=NULL){
			$validator->addRule(new CheckLen('password2','5','14', $lang['adminusrerradd10'].'</br>'));
			}
			}// end if for password
			$validator->addRule(new RuleNotNull('email', $lang['adminusrerradd11'].'<br>'));
			$validator->addRule(new EmailValid('email', $lang['adminusrerradd12'].'<br>')); 
			if($phone != ""){
			$validator->addRule(new checkPhone('phone', $lang['adminusrerradd13'].'<br>'));
			}
			if($mobile != ""){
			$validator->addRule(new checkPhone('mobile', $lang['adminusrerradd14'].'<br>'));
			}
			if($fax != ""){
			$validator->addRule(new checkPhone('fax', $lang['adminusrerradd15'].'<br>'));
			}
			
	// check validation
	$validator->validate($_POST);
		// extract values from request
		extract($_POST);
		if($validator->isValid()){
			// lets do the insert
			$date = date("h:ia D, M jS, Y");
			$fname = ucfirst($fname);
			$lname = ucfirst($lname);
			if($_REQUEST['password']){ 
			$password = md5(strtolower($password)); 
			// insert record into db
			$fields = array ("fname"=>$fname,"lname"=>$lname,"password"=>$password,"email"=>$email,"phone"=>$phone,"mobile"=>$mobile,"fax"=>$fax,"homepage"=>$homepage,"info"=>$info,"mod_date"=>$date);
			}else{
			$fields = array ("fname"=>$fname,"lname"=>$lname,"email"=>$email,"phone"=>$phone,"mobile"=>$mobile,"fax"=>$fax,"homepage"=>$homepage,"info"=>$info,"mod_date"=>$date);
			}
			$into = "user";
			$result = $phprealty->updIntTableRows($fields, $into, $where='id='.$_GET['editID'], $sort="", $dir="", $limit="");
			if(!$result){
				// there was an error
				$errors = $lang['adminusrupnok'];
			}else{
				$_POST = array();
				$errors = $lang['adminusrupok'];
				$uname="";
				$fname="";
				$lname="";
				$password="";
				$password2="";
				$email="";
				$phone="";
				$mobile="";
				$fax="";
				$homepage="";
				$info="";
				$mod_date = "";
				$noform = true;
			}
		}else{
			// form values were not valid
			$errormsg = '<strong>'.$lang['errors'].':</strong><br> '.implode('',$validator->getErrorMsg());
			$errors = $errormsg;
			$visible = "";
		}
	}else{
		// get user info to display
		$result = $phprealty->getIntTableRows($fields="*", $from="user", $where="id=".$_GET['editID'], $sort="", $dir="", $limit="1", $push=true);
		if(count($result) < 1){
			$error = $lang['adminusrinvalid'];
		}else{
			$editID = $_GET['editID'];
			extract($result[0]);
			$password = "";
			$visible = 'style="display:none;"';
		}
	}
	
}else{
	// first time through. Show the form
	$visible = 'style="display:none;"';
}

?>

<div id="post-1" class="post">
<h2 class="title"><a href="<? echo $addUserURL; ?>"><?=$lang['adminusradd'];?></a></h2>

<div class="entry">
<?
if($noform==true){
?>
	  <div class="error" <? echo $visible; ?>><? echo $errors; ?></div>
<?
}else{
?>
<form name="user_ins" method="post" action="<? echo $addUserURL; ?>">
<input type="hidden" name="edit_id" value="<? echo $editID; ?>" />
<?
if(isset($_GET['editID'])){
$disabled = 'disabled="disabled"';
?>
<input type="hidden" name="uname" value="<? echo $uname; ?>" />
<? } ?>
	  <div class="error" <? echo $visible; ?>><? echo $errors; ?></div>
	  <table width="100%" border="0" cellspacing="5" cellpadding="0">
        <tr>
          <td width="30%" valign="top"><strong><?=$lang['adminusradd0'];?>:</strong> * </td>
          <td valign="top"><input name="uname" type="text" id="uname" value="<? echo $uname; ?>" size="30" maxlength="14" <? echo $disabled; ?>> 
		  <? if(isset($_GET['editID'])){ ?>
		  <span style="color:#ff0000;font-style:oblique;"><?=$lang['adminusernoch'];?></span>
		  <? }else{ ?>
		  <span style="font-size:10px;"><?=$lang['adminusraddminmax'];?></span>
		  <? } ?>
		  </td>
        </tr>
        <tr>
          <td width="30%" valign="top"><strong><?=$lang['adminusradd1'];?>:</strong> * </td>
          <td valign="top"><input name="fname" type="text" id="fname" value="<? echo $fname; ?>" size="30" maxlength="25"></td>
        </tr>
        <tr>
          <td valign="top"><strong><?=$lang['adminusradd2'];?>:</strong> * </td>
          <td valign="top"><input name="lname" type="text" id="lname" value="<? echo $lname; ?>" size="30" maxlength="25"></td>
		 </tr>
		 <tr>
          <td valign="top"><strong><?=$lang['adminusradd3'];?>:</strong> <? if(!isset($_GET['editID']) || !$_GET['editID']){ ?>*<? } ?> </td>
          <td valign="top"><input name="password" type="password" id="password" value="" size="30"> <span style="font-size:10px;"><?=$lang['adminusraddminmax'];?></span></td>
        </tr>
        <tr>
          <td valign="top"><strong><?=$lang['adminusradd4'];?>:</strong>  <? if(!isset($_GET['editID']) || !$_GET['editID']){ ?>*<? } ?> </td>
          <td valign="top"><input name="password2" type="password" id="password2" value="" size="30"> <span style="font-size:10px;"><?=$lang['adminusraddminmax'];?></span></td>
        </tr>
        <tr>
          <td valign="top"><strong><?=$lang['adminusrmail'];?>:</strong> * </td>
          <td valign="top"><input name="email" type="text" id="email" value="<? echo $email; ?>" size="30" maxlength="100"></td>
        </tr>
        <tr>
          <td valign="top"><strong><?=$lang['adminusradd5'];?>:</strong> </td>
          <td valign="top"><input name="phone" type="text" id="phone" value="<? echo $phone; ?>" size="12" maxlength="12"> 
          (ex. xxx-xxx-xxxx) </td>
        </tr>
        <tr>
          <td valign="top"><strong><?=$lang['adminusradd6'];?>:</strong> </td>
          <td valign="top"><input name="mobile" type="text" id="mobile" value="<? echo $mobile; ?>" size="12" maxlength="12">             (ex. xxx-xxx-xxxx)</td>
        </tr>
        <tr>
          <td valign="top"><strong><?=$lang['adminusradd7'];?>:</strong></td>
          <td valign="top"><input name="fax" type="text" id="fax" value="<? echo $fax; ?>" size="12" maxlength="12">             (ex. xxx-xxx-xxxx)</td>
        </tr>
        <tr>
          <td valign="top"><strong><?=$lang['adminusradd8'];?>:</strong></td>
          <td valign="top"><input name="homepage" type="text" id="homepage" value="<? echo $homepage; ?>" size="30" maxlength="150"> 
          (<?=$lang['negation'];?> http://) </td>
        </tr>
        <tr>
          <td valign="top"><strong><?=$lang['adminusradd9'];?>:</strong></td>
          <td valign="top"><textarea name="info" cols="35" rows="6" id="info"><? echo $info; ?></textarea></td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><?=$lang['admintickrequired'];?></td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><input type="submit" name="submit" value="<?=$lang['submit'];?>"></td>
          </tr>
      </table>
</form>
<?
}
?>
</div>
</div>
