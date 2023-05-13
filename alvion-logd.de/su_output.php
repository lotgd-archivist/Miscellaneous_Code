
<?php
/*
* Version:    03.01.2015
* Author:    Linus
* Email:    webmaster@alvion-logd.de
* Zweck:    Admintool
*
*/

require_once "common.php";
require_once "func/isnewday.php";
isnewday(2);

page_header("HTML-Output!");

output("`n`n`n");

output("`2Al`@vio`2ns `2Wä`@ld`2er`n",true);


addnav("Z?Zurück","superuser.php");
// addnav("W?Zurück zum Weltlichen","village.php");

page_footer();


