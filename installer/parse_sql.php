<?
/* ------------------------------------------------------------------------------------------------
phpRealty install pages
Copyright 2007 John Carlson
Created By: John Carlson
Contact: johncarlson21@gmail.com
Date Created: 08-23-2007
Version: 0.05
Modifications: 01-04-2008 by Bogdan Thazer - bogdan2ky@gmail.com
Modifications: 09-07-2008 by John Carlson from version 0.03 - 0.05
------------------------------------------------------------------------------------------------ */
// parse sql dump
 function parse_mysql_dump($url, $ignoreerrors = false) {
  $file_content = file($url);
  //print_r($file_content);
  $query = "";
  foreach($file_content as $sql_line) {
    $tsl = trim($sql_line);
    if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
      $query .= $sql_line;
      if(preg_match("/;\s*$/", $sql_line)) {
        $result = mysql_query($query);
        if (!$result && !$ignoreerrors) die(mysql_error());
        $query = "";
      }
    }
  }
 }
?>
