<?php
/* *******************
Die Arena Ställe
by Fly
email: easykamikaze@lycos.de
******************* */
// überarbeitet von Tidus (www.kokoto.de)
require_once "common.php";
page_header("Arena Ställe von Kokoto");

if ($_GET['op']=="in"){
     output("`6Du streichelst Dein(e/n) ".$playermount['mountname']."`6 und überreichst es dann Eilinel.");
     $session['savemount']=$session['bufflist']['mount']['rounds'];
	unset($session['bufflist']['mount']);
     }
else if ($_GET['op']=="out"){
     output("`6Eilinel überreicht Dir Dein(e/n) ".$playermount['mountname']." `6und Du bist froh es wieder bei Dir zu haben.");
     $session['bufflist']['mount']=unserialize($playermount['mountbuff']);
     $session['bufflist']['mount']['rounds']=$session['savemount'];
     $session['savemount']=0;

     }
else
    {

    output("`6 Neben der Arena befinden sich einige kleine Ställe. Eilinel füttert gerade einige Tiere. Als sie Dich sieht, kommt sie auf Dich zu und fragt Dich, was sie für Dich tun kann.");
    if ($session['user']['hashorse']>0 && $session['bufflist']['mount']['rounds']>0) addnav("Tier unterstellen","arenastables.php?op=in");
    else if ($session['user']['hashorse']>0 && $session['savemount']>0) addnav("Tier abholen","arenastables.php?op=out");
    else if ($session['user']['hashorse']>0) output("`n`^Du solltest Dein(e/n) ".$playermount['mountname']."`^ stärken, bevor Du es abgeben kannst.");
    else {output("`n`^ Du besitzt kein Tier und kannst es somit auch nicht abgeben!");}
    }
addnav("zur Arena zurück","pvparena.php");

page_footer();
?>