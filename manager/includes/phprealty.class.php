<?
/* ------------------------------------------------------------------------------------------------
phpRealty class and functions for the main site management.
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05

The class below is based of the main Class and functions mainly from
Etomite CMS which can be found at http://www.etomite.com

------------------------------------------------------------------------------------------------ */

class phprealty {
var $db, $config, $sql, $type, $css, $html, $dumpSQL, $queryCode, $executedQueries, $queryTime;

  function phprealty(){
    $this->dbConfig['host'] = $GLOBALS['database_server'];
    $this->dbConfig['dbase'] = $GLOBALS['dbase'];
    $this->dbConfig['user'] = $GLOBALS['database_user'];
    $this->dbConfig['pass'] = $GLOBALS['database_password'];
    $this->dbConfig['table_prefix'] = $GLOBALS['table_prefix'];
    $this->db = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix'];
    $this->WWW = WWW;
    $this->REL = REL;
    $this->TEMPS = TEMPS;
    $this->INC = INC;
    $this->SITENAME = SITENAME;
	$this->IMGDIR = IMGDIR;
	$this->IMGWWW = IMGWWW;
	$this->MGR = MGR;
  }
  
//##################  TEMPLATE ENGINE  ########################
// here you can set variables that are static, variables that won't change or something you want on all pages.
// they just need to be added to the get() function below.
// You could even include a file with variables here

	function construct($path="",$a_type="") {
		if($a_type!=""){
		$this->type=$a_type;
		$filename=$path.$this->type;
		$handle=fopen($filename,'r');
		$this->html=fread($handle,filesize($filename));
		$this->css=$path.$this->css;
		fclose($handle);
		}
	}
	
	function replace($before,$after) {
		if ($before{0}!="<!--")
			$before="<!--".$before."-->";
		$this->html=str_replace($before,$after,$this->html);
	}
	
	function get() {
	//this line below is not needed but if you want to set something then set it here. such as a static variable from above.
		$this->html = $this->addNotice($this->html);
		return $this->html=$this->parseSnippets($this->html,$_REQUEST);
	}

	function parseSnippet($content,$param="") {
	global $phprealty, $config;
	// extract config variables
	extract($config);
	extract($param);
	 ob_start();
	 $content = str_replace('<'.'?php','<'.'?',$content);
	 eval('?'.'>'.trim($content).'<'.'?');
	 $content = ob_get_contents();
	 ob_end_clean();
	 return $content;
	}
	
	function parseSnippets($contents){
		    preg_match_all('<!--(.*?)-->', $contents, $matches);
		    $matchCount=count($matches[1]);
			for($i=0; $i<$matchCount; $i++) {
				if(file_exists($this->MGR."static/".$matches[1][$i].".php")){
					$snippet = file_get_contents($this->MGR."static/".$matches[1][$i].".php");
					$snippet = $this->parseSnippet($snippet,$_REQUEST);
					$before = "<!--".$matches[1][$i]."-->";
					$contents = str_replace($before,$snippet,$contents);
					//$return .= $matches[1][$i]."<br>";
				}// end if file exists
			}
			return $contents;

	}// end parseSnippets
	
//##################  END TEMPLATE ENGINE  ########################
	
  function getMicroTime() {
     list($usec, $sec) = explode(" ", microtime());
     return ((float)$usec + (float)$sec);
  }

  function dbConnect() {
  // function to connect to the database
    $tstart = $this->getMicroTime();
    if(@!$this->rs = mysql_connect($this->dbConfig['host'], $this->dbConfig['user'], $this->dbConfig['pass'])) {
			  //$this->messageQuit("Failed to create the database connection!");
			  $tend = $this->getMicroTime();
			  $totaltime = $tend-$tstart;
			  if($this->dumpSQL) {
				$this->queryCode .= "<fieldset style='text-align:left;padding:5px;'><legend>Database connection</legend>".sprintf("Database connection was created in %2.4f s", $totaltime)."</fieldset><br />";
			  }
			  $this->queryTime = $this->queryTime+$totaltime;
    } else {
      mysql_select_db($this->dbConfig['dbase']) or die(mysql_error());
			  /*
			  $tend = $this->getMicroTime();
			  $totaltime = $tend-$tstart;
			  if($this->dumpSQL) {
				$this->queryCode .= "<fieldset style='text-align:left;padding:5px;'><legend>Database connection</legend>".sprintf("Database connection was created in %2.4f s", $totaltime)."</fieldset><br />";
			  }
			  $this->queryTime = $this->queryTime+$totaltime;
			  */
    }
  }

  function dbQuery($query) {
  // function to query the database
    // check the connection and create it if necessary
    if(empty($this->rs)) {
      $this->dbConnect();
    }
    $tstart = $this->getMicroTime();
    if(@!$result = mysql_query($query, $this->rs)) {
      //$this->messageQuit("Execution of a query to the database failed", $query);
			  $tend = $this->getMicroTime();
			  $totaltime = $tend-$tstart;
			  $this->queryTime = $this->queryTime+$totaltime;
			  if($this->dumpSQL) {
				$this->queryCode .= "<fieldset style='text-align:left'><legend>Query ".($this->executedQueries+1)." - ".sprintf("%2.4f s", $totaltime)."</legend>".mysql_error()."<br />".$query."</fieldset><br />";
			  }
			  $this->executedQueries = $this->executedQueries+1;
    } else {
			/*
			  $tend = $this->getMicroTime();
			  $totaltime = $tend-$tstart;
			  $this->queryTime = $this->queryTime+$totaltime;
			  if($this->dumpSQL) {
				$this->queryCode .= "<fieldset style='text-align:left'><legend>Query ".($this->executedQueries+1)." - ".sprintf("%2.4f s", $totaltime)."</legend>".$query."</fieldset><br />";
			  }
			  */
			  $this->executedQueries = $this->executedQueries+1;
      if(count($result) > 0) {
        return $result;
      } else {
        return false;
      }
    }
  }

  function recordCount($rs) {
  // function to count the number of rows in a record set
    return mysql_num_rows($rs);
  }

  function fetchRow($rs, $mode='assoc') {
    if($mode=='assoc') {
      return mysql_fetch_assoc($rs);
    } elseif($mode=='num') {
      return mysql_fetch_row($rs);
    } elseif($mode=='both') {
      return mysql_fetch_array($rs, MYSQL_BOTH);
    } else {
      //$this->messageQuit("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'.");
	  return false;
    }
  }

  function affectedRows($rs) {
    return mysql_affected_rows($this->rs);
  }

  function insertId($rs) {
    return mysql_insert_id($this->rs);
  }

  function dbClose() {
  // function to close a database connection
    mysql_close($this->rs);
  }
  
  function getFormVars($method="",$prefix="",$trim="",$REQUEST_METHOD) {
  // function to retrieve form results into an associative $key=>$value array
  // This function is intended to be used to retrieve an associative $key=>$value array of form data which can be sent directly to the putIntTableRow() or putExttableRow() functions. This function performs no data validation. By utilizing $prefix it is possible to // retrieve groups of form results which can be used to populate multiple database tables. This funtion does not contain multi-record form capabilities.
  // $method = form method which can be POST or GET and is not case sensitive: $method="POST"
  // $prefix = used to specifiy prefixed groups of form variables so that a single form can be used to populate multiple database // tables. If $prefix is omitted all form fields will be returned: $prefix="frm_"
  // $trim = boolean value ([true or 1]or [false or 0]) which tells the function whether to trim off the field prefixes for a group // resultset
  // $RESULT_METHOD is sent so that if $method is omitted the function can determine the form method internally. This system variable cannot be assigned a user-specified value.
  // Returns FALSE if form method cannot be determined
    $results = array();
    $method = strtoupper($method);
    if($method == "") $method = $REQUEST_METHOD;
    if($method == "POST") $method = &$_POST;
    elseif($method == "GET") $method = &$_GET;
    elseif($method == "FILES") $method = &$_FILES;
    else return false;
    reset($method);
    foreach($method as $key=>$value) {
      if(($prefix != "") && (substr($key,0,strlen($prefix)) == $prefix)) {
        if($trim) {
          $pieces = explode($prefix, $key,2);
          $key = $pieces[1];
          $results[$key] = $value;
        }
        else $results[$key] = $value;
      }
      elseif($prefix == "") $results[$key] = $value;
    }
    return $results;
  }
  
  function loginAdmin($uname,$pass){
	// lets login the admin
	$sql = "SELECT * FROM ".$this->db."admin WHERE uname='$uname' AND password='".md5($pass)."'";
	if($result = $this->dbQuery($sql)){
		// admin is logged in lets start the session info
		$limit = $this->recordCount($result);
		if($limit<1){
			return false;
		}
			$row=$this->fetchRow($result);
			session_register('valid','shortname','fullname','uid','uname');
			$_SESSION['valid'] = 1;
			$_SESSION['shortname'] = $uname;
			$_SESSION['fullname'] = $row['fname']." ".$row['lname'];
			$_SESSION['uid'] = $row['id'];		
			$_SESSION['uname'] = $uname;
			return true;
		
	}else{
		return false;
	}
  }// end loginAdmin function
  
  function userLogin($uname,$pass){
	// lets login the admin
	$sql = "SELECT * FROM ".$this->db."user WHERE uname='".$uname."' AND password='".md5($pass)."'";
	if($result = $this->dbQuery($sql)){
		// admin is logged in lets start the session info
		$limit = $this->recordCount($result);

		if($limit<1){
			return false;
		}
			$row=$this->fetchRow($result);
			session_register('valid','shortname','fullname','fname','lname','uid','uname');
			$_SESSION['valid'] = 1;
			$_SESSION['shortname'] = $uname;
			$_SESSION['fullname'] = $row['fname']." ".$row['lname'];
			$_SESSION['fname'] = $row['fname'];
			$_SESSION['lname'] = $row['lname'];
			$_SESSION['uid'] = $row['id'];		
			$_SESSION['uname'] = $uname;
			return true;
		
	}else{
		return false;
	}
  }// end userLogin function

  
  function checkLogin(){
  	if(!isset($_SESSION['valid']) || $_SESSION['valid']!=1 || !isset($_SESSION['uid'])){
		return false;
	}else{
		return true;
	}// end if for validated session
  }

  function makeUrl($id, $m=false, $args='') {
	// this is an easier way to create variables for urls with arguments
	// id is the switch id number, $m is either true|false this tells
	// the function if it is a main site url or a member manager url
	// default is false
	if(!is_numeric($id) && $id!="") {
     	return "`$id` is not numeric and may not be passed to makeUrl()";
	}else{
		if($m==false){
			$url = $this->WWW."index.php?a=$id";
		}else{
			$url = $this->MHOME."index.php?manaction=$id";
		}
	}
	// make sure only the first argument parameter is preceded by a "?"
	if(strlen($args)&&strpos($url, "?")) $args="&amp;".substr($args,1);
	return $url.$args;
  }
  
  function formatICON($img="",$args=""){
  // this function takes an image and formats it based on the arguments
  // $args = a string such as: border="0" or style="text_decoration:none"
  	if(!$img || $img==""){
	return false;
	}else{
	//format the args to make sure that all quotes are double quotes
	$args = str_replace('"',"",$args);
	return '<img src="'.$img.'" '.$args.' />';
	}// end if
  
  }// end format image 

//##################  EXTRA DATABASE FUNCTIONS  ########################

function getIntTableRows($fields="*", $from="", $where="", $sort="", $dir="ASC", $limit="", $push=true, $addPrefix=true) {
  // function to get rows from ANY internal database table
  // This function works much the same as the getDocuments() function. The main differences are that it will accept a table name and can use a LIMIT clause.
  // $fields = a comma delimited string: $fields="name,email,age"
  // $from = name of the internal phpRealty table which data will be selected from without database name or table prefix ($from="user_messages")
  // $where = any optional WHERE clause: $where="parent=10 AND published=1 AND type='document'"
  // $sort = field you wish to sort by: $sort="id"
  // $dir = ASCending or DESCending sort order
  // $limit = maximum results returned: $limit="3" or $limit="10,3"
  // $push = ( true = [default] array_push results into a multi-demensional array | false = return MySQL resultset )
  // $addPrefix = whether to check for and/or add $this->dbConfig['table_prefix'] to the table name
  // Returns FALSE on failure.
    if($from=="") return false;
    // added multi-table abstraction capability
    if(is_array($from)) {
      $tbl = "";
      foreach ($from as $_from) $tbl .= $this->db.$_from.", ";
      $tbl = substr($tbl,0,-2);
    } else {
      $tbl = (strpos($from,$this->dbConfig['table_prefix']) === 0 || !$addPrefix)
              ? $this->dbConfig['dbase'].".".$from
              : $this->db.$from;
    }
    $where = ($where != "") ? "WHERE $where" : "";
    $sort = ($sort != "") ? "ORDER BY $sort $dir" : "";
    $limit = ($limit != "") ? "LIMIT $limit" : "";
    $sql = "SELECT $fields FROM $tbl $where $sort $limit;";
    $result = $this->dbQuery($sql);
    if(!$push) return $result;
    $resourceArray = array();
    for($i=0;$i<@$this->recordCount($result);$i++)  {
      array_push($resourceArray,@$this->fetchRow($result));
    }
    return $resourceArray;
  }
  
  function putIntTableRow($fields="", $into="") {
  // function to put a row into ANY internal database table
  // INSERT's a new table row into ANY internal phpRealty database table. No data validation is performed.
  // $fields = a $key=>$value array: $fields=("name"=>$name,"email"=$email,"age"=>$age)
  // $into = name of the internal phpRealty table which will receive the new data row without database name or table prefix: $into="user_messages"
  // Returns FALSE on failure.
    if(($fields=="") || ($into=="")){
      return false;
    } else {
      $tbl = $this->db.$into;
      $sql = "INSERT INTO $tbl SET ";
      foreach($fields as $key=>$value) {
        $sql .= "`".$key."`=";
        if (is_numeric($value)) $sql .= $value.",";
        else $sql .= "'".$value."',";
      }
      $sql = rtrim($sql,",");
	  $sql = trim($sql);
      $sql .= ";";
      $result = $this->dbQuery($sql);
      return $result;
    }
  }

  function updIntTableRows($fields="", $into="", $where="", $sort="", $dir="ASC", $limit="") {
  // function to update a row into ANY internal database table
  // $fields = a $key=>$value array: $fields=("name"=>$name,"email"=$email,"age"=>$age)
  // $into = name of the internal phpRealty table which will receive the new data row without database name or table prefix: $into="user_messages"
  // $where = any optional WHERE clause: $where="parent=10 AND published=1 AND type='document'"
  // $sort = field you wish to sort by: $sort="id"
  // $dir = ASCending or DESCending sort order
  // $limit = maximum results returned: $limit="3" or $limit="10,3"
  // Returns FALSE on failure.
    if(($fields=="") || ($into=="")){
      return false;
    } else {
      $where = ($where != "") ? "WHERE $where" : "";
      $sort = ($sort != "") ? "ORDER BY $sort $dir" : "";
      $limit = ($limit != "") ? "LIMIT $limit" : "";
      $tbl = $this->db.$into;
      $sql = "UPDATE $tbl SET ";
      foreach($fields as $key=>$value) {
        $sql .= "`".$key."`=";
        if (is_numeric($value)) $sql .= $value.",";
        else $sql .= "'".$value."',";
      }
      $sql = rtrim($sql,",");
      $sql .= " $where $sort $limit;";
      $result = $this->dbQuery($sql);
      return $result;
    }
  }

//##################  END EXTRA DATABASE FUNCTIONS  ########################

//##################  NOTICE FUNCTION  ##############################

  function addNotice($content, $type="text/html") {
    /*
      PLEASE READ!

      This function places a copyright message and a link to phpRealty in the page about to be
      sent to the visitor's browser. The message is placed just before your </body> or </BODY>
      tag, and if phpRealty can't find either of these, it will simply paste the message onto
      the end of the page.

      I've not obfuscated this notice, or hidden it away somewhere deep in the code, to give
      you the chance to alter the markup on the P tag, should you wish to do so. You can even
      remove the message as long as:
      1 - the "phpRealty is Copyright..." message stays (doesn't have to be visible) and,
      2 - the link remains in place (must be visible, and must be a regular HTML link). You
        are allowed to add a target="_blank" attribute to the link if you wish to do so.

      Should you decide to remove the entire message and the link, I will probably refuse to
      give you any support you request, unless you have a very good reason for removing the
      message. Donations or other worthwhile contributions are usually considered to be a good
      reason. ;) If in doubt, contact me through the site at http://phprealty.budissy.com

      Leaving this message and the link intact will show your appreciation of the hours
      I've spent building the system and providing support to it's users, and the hours I will
      be spending on it in future.

      Removing this message, in my opinion, shows a lack of appreciation, and a lack of
      community spirit. The term 'free-loading' comes to mind. :)

      Thanks for understanding, and thanks for not removing the message and link!
        - John
    */

    if($type == "text/html"){
      $notice = "\n<!--\n\n".
          "\tI kindly request you leave the copyright notice and the link \n".
          "\tto phprealty.budissy.com intact to show your appreciation of the time\n".
          "\tI and the contributors to the phpRealty project have (freely) \n".
          "\tspent on the system. Removal of the copyright notice and\n".
          "\tthe link, without the permission of the author, may affect\n".
          "\tor even cause us to deny any support requests you make. By \n".
          "\tleaving this link intact, you show your support of the project,\n".
          "\tand help to increase interest, traffic and use of phpRealty, \n".
          "\twhich will ultimately benefit all who use the system. To save\n".
          "\tbandwidth, you may remove this message, as long as the link\n".
          "\tand copyright notice stay in place.\n\n".
          "\tphpRealty is Copyright 2007 and Trademark of the phpRealty Project. \n\n".
          "-->\n\n".
          "<!--<div id='phpRealtyNotice'>\n".
          "\tManaged by the <a href='http://phprealty.budissy.com' title='Managed by phpRealty Listings Software'>phpRealty Listings Software</a>.\n".
          "</div>-->\n\n".
          "\t<!-- The phpRealty Listings Software can be found at http://phprealty.budissy.com -->\n\n";
    }

    // insert the message into the document
    if(strpos($content, "</body>")>0) {
      $content = str_replace("</body>", $notice."</body>", $content);
    } elseif(strpos($content, "</BODY>")>0) {
      $content = str_replace("</body>", $notice."</BODY>", $content);
    } else {
      $content .= $notice;
    }
    return $content;
  }
  
//#####################  END NOTICE  ####################################

//##################  IMAGE SAVE FUNCTION  ########################
function saveThumbnail($saveToDir, $imagePath, $imageName, $prefix="", $max_x, $max_y,$w=false) {
/* ###############################################################
save thumbnail function allows you to resize and upload an image to the system
$saveToDir = directory to save images to
$imagePath = temporary file path on the server
$imageName = actual image file name
$prefix = a prefix to the original image name default is none
$max_x = max width of finished image
$max_y = max height of finished image
$w = use water mark or not true|false
###############################################################*/

   preg_match("'^(.*)\.(gif|jpe?g|png|swf)$'i", $imageName, $ext);
   if(file_exists($saveToDir.$prefix.$imageName)){
   		$stop = true;
   }else{
   switch (strtolower($ext[2])) {
       case 'jpg' : 
       case 'jpeg': $im  = imagecreatefromjpeg ($imagePath);
	   				 imagealphablending($im, TRUE);
                     break;
       case 'gif' : $im  = imagecreatefromgif  ($imagePath);
                     break;
	   case 'png' : $im = imagecreatefrompng($imagePath);
	   				 break;
       default    : $stop = true;
                     break;
   }
   }// end if for if file exists
   
   if (!isset($stop)) {
       $x = imagesx($im);
       $y = imagesy($im);
	   
	   if($w==true){
	   // lets add the watermark to the image
	   $watermark = imagecreatefrompng($this->TEMPS."watermark.png");
	   $wx = imagesx($watermark);
	   $wy = imagesy($watermark);
	   $dest_x = $x - $wx - 5;  
	   $dest_y = $y - $wy - 5;
	   imagecopy($im, $watermark, $dest_x, $dest_y, 0, 0, $wx, $wy);
	   imagedestroy($watermark);
	   // end watermark
	   }// end watermark section
	   
	   if($x < $max_x && $y < $max_y){
			$save = imagecreatetruecolor($x, $y);	   
		}else{
      		 if (($max_x/$max_y) < ($x/$y)) {
           	$save = imagecreatetruecolor($x/($x/$max_x), $y/($x/$max_x));
      		 }
       		else {
           		$save = imagecreatetruecolor($x/($y/$max_y), $y/($y/$max_y));
			   }
	   }

       imagecopyresampled($save, $im, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y);
	   
	   switch (strtolower($ext[2])) {
       case 'jpg' :	 imagejpeg($save,"{$saveToDir}{$prefix}{$ext[1]}.jpg",85);
	   				 break;
       case 'jpeg': imagejpeg($save,"{$saveToDir}{$prefix}{$ext[1]}.jpg",85);
                     break;
       case 'gif' : imagegif($save, "{$saveToDir}{$prefix}{$ext[1]}.gif");
                     break;
	   case 'png' : imagepng($save, "{$saveToDir}{$prefix}{$ext[1]}.png");
	   				 break;
   		}
	   
       imagedestroy($im);
       imagedestroy($save);
       
       return true;
   }else{
   		return false;
   }
}// end image save function
//##################  END IMAGE SAVE FUNCTION  ########################


}// end phprealty class

?>
