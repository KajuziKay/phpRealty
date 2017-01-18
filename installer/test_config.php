<?php
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

// init
  $testdb     = "";
  
// let the user know we are testing and that it may take upto a min.
  echo "<b>Performing configuration test.</b><br />This may take a minute<br /><br />MySQL DB test....................... ";
  flush();


// ---- test mysql settings ------------------------------------------------------------
    @mysql_connect($dbhost,$dbuser ,$dbpass);
    @mysql_select_db($dbname) or $testdb = "failed";
    
    if ($testdb == "failed"){
        $notice2 = "MySQL settings are incorrect. Failed to select db during test.";
        echo "failed";
    } else {
        echo "passed";
    }
    flush();
     
    if ($testdb != "failed"){
        $test = "passed";
    }
    
    echo "<br /><br />finished test<br /><br />";
?>
