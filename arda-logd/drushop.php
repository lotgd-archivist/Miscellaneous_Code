<?php

/* ********************
Codierung von Ray
Ideen von Ray
ICQ: 230406044
******************** */
/***************************** Informationen ***********************************
Benötigt die SQL einträge von berufe.php:

ALTER TABLE `accounts` ADD `blaukraut` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `rotkraut` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `gelbkraut` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `kraut` INT (12) DEFAULT '0' NOT NULL;
*******************************************************************************/
############################## Konfiguration ###################################
$nk = "Normales Kraut";
$bk = "Blaues Kraut";
$rk = "Rotes Kraut";
$gk = "Gelbes Kraut";

################################################################################
require_once "common.php";
page_header ("Kräuterladen");


if ($_GET['op']==""){
output("`#Du betritts den Kräuterladen der Druiden und guckst dich so gleich um.`n");
output("Im ganzen Laden gibt es Kräuter welche Kräuter willst du?");

addnav("Kräuter");
addnav("Normales Kraut","drushop.php?op=$nk");
//addnav("Blaues Kraut","drushop.php?op=$bk");
//addnav("Rotes Kraut","drushop.php?op=$rk");
//addnav("Gelbes Kraut","drushop.php?op=$gk");
addnav("Sonstiges");
addnav("Zurück","center.php");

}else if ($_GET['op']=="$nk"){
output("`#Wie viele $nk willst du kaufen?.");

addnav("Aktionen");
addnav("5 Kräuter (1000)","drushop.php?op=nk5");
addnav("10 Kräuter (2000)","dushop.php?op=nk10");
addnav("Sonstiges");
addnav("Zurück","drushop.php");

}else if ($_GET['op']=="$bk"){
output("`#Wie viele $bk willst du kaufen?.");

addnav("Aktionen");
addnav("5 Kräuter (1000)","drushop.php?op=bk5");
addnav("10 Kräuter (2000)","dushop.php?op=bk10");
addnav("Sonstiges");
addnav("Zurück","drushop.php");

}else if ($_GET['op']=="$rk"){
output("`#Wie viele $rk willst du kaufen?.");

addnav("Aktionen");
addnav("5 Kräuter (1000)","drushop.php?op=rk5");
addnav("10 Kräuter (2000)","dushop.php?op=rk10");
addnav("Sonstiges");
addnav("Zurück","drushop.php");

}else if ($_GET['op']=="$gk"){
output("`#Wie viele $gk willst du kaufen?.");

addnav("Aktionen");
addnav("5 Kräuter (1000)","drushop.php?op=gk5");
addnav("10 Kräuter (2000)","dushop.php?op=gk10");
addnav("Sonstiges");
addnav("Zurück","drushop.php");

}else if ($_GET['op']=="nk5"){
if ($session['user']['gold']>999){
output("`#Du bezahlst sogleich und bekomsmt deine 5 $nk.");

$session['user']['blaukraut']+=5;
$session['user']['gold']-=1000;

addnav("Zum Dorfmarkt","center.php");
}else{
output("`#Du hast nicht genügend Gold.");

addnav("Zurück","drushop.php");
    }
}else if ($_GET['op']=="nk10"){
if ($session['user']['gold']>1999){
output("`#Du bezahlst sogleich und bekomsmt deine 10 $nk.");

$session['user']['blaukraut']+=10;
$session['user']['gold']-=2000;

addnav("Zum Dorfmarkt","center.php");
}else{
output("`#Du hast nicht genügend Gold.");

addnav("Zurück","drushop.php");
    }
}else if ($_GET['op']=="bk5"){
if ($session['user']['gold']>999){
output("`#Du bezahlst sogleich und bekomsmt deine 5 $bk.");

$session['user']['kraut']+=5;
$session['user']['gold']-=1000;

addnav("Zum Dorfmarkt","center.php");
}else{
output("`#Du hast nicht genügend Gold.");

addnav("Zurück","drushop.php");
    }
}else if ($_GET['op']=="bk10"){
if ($session['user']['gold']>1999){
output("`#Du bezahlst sogleich und bekomsmt deine 10 $bk.");

$session['user']['kraut']+=10;
$session['user']['gold']-=2000;

addnav("Zum Dorfmarkt","center.php");
}else{
output("`#Du hast nicht genügend Gold.");

addnav("Zurück","drushop.php");
    }
}else if ($_GET['op']=="rk5"){
if ($session['user']['gold']>999){
output("`#Du bezahlst sogleich und bekomsmt deine 5 $rk.");

$session['user']['rotkraut']+=5;
$session['user']['gold']-=1000;

addnav("Zum Dorfmarkt","center.php");
}else{
output("`#Du hast nicht genügend Gold.");

addnav("Zurück","drushop.php");
    }
}else if ($_GET['op']=="rk10"){
if ($session['user']['gold']>1999){
output("`#Du bezahlst sogleich und bekomsmt deine 10 $rk.");

$session['user']['rotkraut']+=10;
$session['user']['gold']-=2000;

addnav("Zum Dorfmarkt","center.php");
}else{
output("`#Du hast nicht genügend Gold.");

addnav("Zurück","drushop.php");
    }
}else if ($_GET['op']=="gk5"){
if ($session['user']['gold']>999){
output("`#Du bezahlst sogleich und bekomsmt deine 5 $gk.");

$session['user']['gelbkraut']+=5;
$session['user']['gold']-=1000;

addnav("Zum Dorfmarkt","center.php");
}else{
output("`#Du hast nicht genügend Gold.");

addnav("Zurück","drushop.php");
    }
}else if ($_GET['op']=="gk10"){
if ($session['user']['gold']>1999){
output("`#Du bezahlst sogleich und bekomsmt deine 10 $gk.");

$session['user']['gelbkraut']+=10;
$session['user']['gold']-=2000;

addnav("Zum Dorfmarkt","center.php");
}else{
output("`#Du hast nicht genügend Gold.");

addnav("Zurück","drushop.php");
    }
}

page_footer();
?> 