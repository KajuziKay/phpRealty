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
?>
			<div id="post-1" class="post">
				<h2 class="title"><a href="<? echo $mgrURL; ?>"><? echo SITENAME; ?> <?=$lang['admintab'];?></a></h2>
				
				<div class="entry">
					<div class="user"><?=$lang['adminhi'];?>, <? echo $_SESSION['fullname']; ?></div>
					<p><?=$lang['adminhow'];?></p>
				</div>
				<div class="hr">
					<hr />
				</div>
			</div>
