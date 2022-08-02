<?php

/*
geschrieben
von Dweo Dao
für palanparth.de
kontakt unter questbraxel@web.de
*/


require_once "common.php";
popup_header("Dein Status");

if ($_POST['saverp']!=''){
$session['user']['statusrp']=$_POST['saverp'];}


output("`^`c`b`GDein Status`b`c`n
`GHier kannst du den RP-Relevanten Status deines Charakters eingeben, der in der Kämpferliste erscheinen soll.`n`n
Klicke auf '`tSpeichern`G' um deinen eigenen Status zu ändern.`n`n");
$statusrp=$session['user']['statusrp'];
output("`GCharakter-Status:`n\"`t$statusrp`G\"`n");
output("<form action='status.php' method='POST'><input name='saverp' id='saverp'><input type='submit' class='button' value='Speichern'></form>",true);
output("<script language='JavaScript'>document.getElementById('bet').focus();</script>`n",true);
addnav("","status.php",true);


popup_footer();
?> 