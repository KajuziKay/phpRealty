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
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">

<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
        
        
<?php
// init
  $notice = "";
  $notice1 = "";
  $notice2 = "";
  $notice3 = "";
  // default values
  $dbhost       = "localhost";
  $dbuser       = "";
  $dbpass       = "";
  $dbname       = "";

// if form has been submitted
if (isset($_POST['go'])){
    $setup = 1;
    // validate fields
    if ($_POST['dbhost'] == "" OR $_POST['dbuser'] == "" OR $_POST['dbname'] == ""){
        $notice2 = "All fields need to be filled in.";
        $setup = 0;
    }
    
    
    // save values to stop user needing to re-enter
    $dbhost       = $_POST['dbhost'];
    $dbuser       = $_POST['dbuser'];
    $dbpass       = $_POST['dbpass'];
    $dbname       = $_POST['dbname'];
    
    // if validation passed then go on to setup
    if ($setup == 1){
        include("test_config.php");
        if ($test == "passed"){
            // save a session so that the step will run
            $_SESSION['phprealty_installer'] = "003";
            echo '<br /><form action="step2.php" method="post">
				  <input type="submit" name="submit" value=" Next " />
				  <input type="hidden" value="'.$dbhost.'" name="dbhost" />
				  <input type="hidden" value="'.$dbname.'" name="dbname" />
				  <input type="hidden" value="'.$dbuser.'" name="dbuser" /> 
			          <input type="hidden" value="'.$dbpass.'" name="dbpass" />
				<br />';
            $alldone = 1;
        }
    } else {
        $notice = "ERROR: There was a problem with the install. See below for further information.";
    }
    
}



// error messages
  $notice = ($notice == "") ? "" : '<div class="notice">'.$notice.'</div>';
  $notice1 = ($notice1 == "") ? "" : '<div class="notice">'.$notice1.'</div>';
  $notice2 = ($notice2 == "") ? "" : '<div class="notice">'.$notice2.'</div>';
  $notice3 = ($notice3 == "") ? "" : '<div class="notice">'.$notice3.'</div>';
  
if (!isset($alldone)){
?>


              <?php echo $notice ?>
              <div id="border">
              <h2>Step one - Connecting to the MySQL Database</h2>
              <br />
              Fill in the settings below. Then click done to move onto to the next steps.
              <br />
              <br />
              
              <form action="" method="post">
              
              <b>MySQL Database</b>
              <font color="red"><?php echo $notice2 ?></font>
              <table id="control-menu2">
              <tr align="left">
                <td>  <b>Database host :</b></td>
                <td>  <input type="text" size ="40" value="<?php echo $dbhost ?>" name="dbhost" /></td>
              </tr>
              <tr align="left">
                <td>  <b>Database name :</b></td>
                <td>  <input type="text" size ="40" value="<?php echo $dbname ?>" name="dbname" /></td>
              </tr>
              <tr align="left">
                <td>  <b>Database username :</b></td>
                <td>  <input type="text" size ="40" value="<?php echo $dbuser ?>" name="dbuser" /></td>
              </tr>
              <tr align="left">
                <td>  <b>Database password :</b></td>
                <td>  <input type="text" size ="40" value="<?php echo $dbpass ?>" name="dbpass" /></td>
              </tr> 
              </table>
              <br />
              
              <input type="hidden" name="go" value="go" />
              <input type="submit" name="submit" value=" Done " />
              </form>
            </div>
              <br />
              <br />
        </div>
<?php 
}
?>
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
 
