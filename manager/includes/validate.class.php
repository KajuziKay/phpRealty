<?
/* ------------------------------------------------------------------------------------------------
phpRealty site index page.
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05
------------------------------------------------------------------------------------------------ */
// Form Validation Class

class Validator {

var $chain = array();
var $errorMsg = array();

function addRule(&$rule) {
    if (is_array($rule)) {
        $this->chain = array_merge($this->chain, $rule);
    } else {
        $this->chain[] = $rule;
    }
}
    
function validate ($request) {
    $this->errorMsg = array();
    foreach ($this->chain as $rule) {
        if (! $rule->isValid($request)) {
            $this->errorMsg[] = $rule->getErrorMsg();
        }
    }
    return $this->isValid();
}

function isValid() {
    return empty($this->errorMsg);
}

function getErrorMsg() {
    return $this->errorMsg;
}

} // end class Validator



class Rule {
    var $field;
    var $errorMsg;

    function getErrorMsg() {
      return $this->errorMsg;
    }
}

class RuleNotNull extends Rule {

    function RuleNotNull($field, $errorMsg) {
        $this->field    = $field;
        $this->errorMsg = $errorMsg;
    }
    
    function isValid($request) {
        $value = $request[$this->field];
        return ($value != '');
    }
}//end not null class

class EmailValid extends Rule {

	function EmailValid($field, $errorMsg) {
		$this->field = $field;
		$this->errorMsg = $errorMsg;
	}
	
	function isValid($request) {
	$value = $request[$this->field];
	if(!eregi("^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*$",$value)){
	return false;
	}else{
	return true;
	}
	}

}//end email validation class

class StateValid extends Rule {

	function StateValid($field, $errorMsg) {
		$this->field = $field;
		$this->errorMsg = $errorMsg;
	}
	
	function isValid($request) {
	$value = $request[$this->field];
	if(!preg_match('/^[A-Z]{2}$/', $value)){
	return false;
	}else{
	return true;
	}
	}

}//end state validation class

class ConfirmPass extends Rule {
	var $field2;

	function ConfirmPass($field, $field2, $errorMsg) {
		$this->field = $field;
		$this->field2 = $field2;
		$this->errorMsg = $errorMsg;
	}
	
	function isValid($request) {
	$p1 = $request[$this->field];
	$p2 = $request[$this->field2];
	if($p1 == $p2){
	return true;
	}else{
	return false;
	}
	}

}//end password validation class

class CheckLen extends Rule {
	var $field2, $field3;

	function CheckLen($field, $field2, $field3, $errorMsg) {
		$this->field = $field; // field
		$this->field2 = $field2; // minlength
		$this->field3 = $field3; // maxlength
		$this->errorMsg = $errorMsg;
	}
	
	function isValid($request) {
	$f = $request[$this->field];
	$min = $this->field2;
	$max = $this->field3;
	$len = strlen($f);
	
	if($len >= $min && $len <= $max){
	return true;
	}else{
	return false;
	}
	}

}//check length of field


class checkUname extends Rule {

	function checkUname($field, $errorMsg) {
		$this->field = $field;
		$this->errorMsg = $errorMsg;
	}//end check function
	
	function isValid($request) {
	global $phprealty;
	$u = $request[$this->field];
	if($result = $phprealty->getIntTableRows($fields="uname",$from="user",$where="uname='".$u."'",$sort='',$dir='',$limit='',$push=true)){
	return false ;
	}else{
	return true;
	}
	}//end valid function

}//end username check validation class

class checkPhone extends Rule {

	function checkPhone($field, $errorMsg) {
		$this->field = $field;
		$this->errorMsg = $errorMsg;
	}//end check function
	
	function isValid($request) {
	$p = $request[$this->field];
		if (!preg_match('/\d{3}-\d{3}-\d{4}$/', $p)) {
		return false;
		}else{
		return true;
		}
	}//end valid function

}//end phone number check validation class
