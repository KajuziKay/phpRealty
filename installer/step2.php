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
session_start();
if (isset($_SESSION['phprealty_installer'])){
    if ($_SESSION['phprealty_installer'] == 003){
    
    
    
// includes
    require('parse_sql.php');
    
// init
// if form has been submitted
if (isset($_POST['finish'])){
    $setup = 1;

    if($setup == 1){
	@mysql_connect($_POST['dbhost'],$_POST['dbuser'] ,$_POST['dbpass']);
    	@mysql_select_db($_POST['dbname']) or die("<b>Something broke? try running the installation again.");
        parse_mysql_dump("sql/phprealty.sql");
                     
        header("location: alldone.html");
        exit(); 
                       
    }
    
}
    
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">

<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="install_style.css" />
    <title>phpRealty v0.03 DB installer</title>
	<style type="text/css">
	<!--//
	body {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	}
	//-->
	</style>
  </head>
  
  <body>
  
  <center>
        <div id="content">
            
              <div id="border">
              <h2>Step two - One click away!</h2>
              <br />
              By here clicking the "Finish" button the phpRealty database will be 
				installed, <u>note</u> that any previous DB installation will be overwriten!<br />
				Thus old data will be lost!
              <br />
              <form action="" method="post">
              <br />
                                  <input type="hidden" value="<?=$_POST['dbhost'];?>" name="dbhost" />
                                  <input type="hidden" value="<?=$_POST['dbname'];?>" name="dbname" />
                                  <input type="hidden" value="<?=$_POST['dbuser'];?>" name="dbuser" />
                                  <input type="hidden" value="<?=$_POST['dbpass'];?>" name="dbpass" />
              <input type="hidden" name="finish" value="install" />
              <input type="submit" name="submit" value=" Finish " />
              </form>
              
            </div>
              <br />
              <br />
        </div>
        
      </div>
      
<!-- // footer -->
      </td>
    </tr>
  </table>
      <div id="footer">
          &copy; 2008 <a href="" targe="_blank">phpRealty</a> - Realestate Listings Software. All rights reserved.<br /> 
          Version 0.03
          <br /> 
      </div>
  </body>
</html>
 
<?php 
} 
} else {
  echo "<h2>WARNING:</h2> Hmmm. Looks like you are trying to hack this site. A photo of you has been taken, your finger prints have been scanned and we are currently checking your goverment file. <br /><br />
  Seriously though. You've teleported in the wrong place, mabe jumped some required steps to complete this install?";
}
?>
