
<?php
/*********************************************
Lots of Code from: lonnyl69 - Big thanks for help!
By: Kevin Hatfield - Arune v1.0
Written for Fishing Add-On - Poseidon Pool
06-19-04 - Public Release

Translation and simple modifications by deZent deZent@onetimepad.de


ALTER TABLE accounts ADD wormprice int(11) unsigned not null default '0';
ALTER TABLE accounts ADD minnowprice int(11) unsigned not null default '0';
ALTER TABLE accounts ADD wormavail int(11) unsigned not null default '0';
ALTER TABLE accounts ADD minnowavail int(11) unsigned not null default '0';
ALTER TABLE accounts ADD trades int(11) unsigned not null default '0';
ALTER TABLE accounts ADD worms int(11) unsigned not null default '0';
ALTER TABLE accounts ADD minnows int(11) unsigned not null default '0';
ALTER TABLE accounts ADD fishturn int(11) unsigned not null default '0';
add to newday.php
$session['user']['trades'] = 10;
if ($session[user][dragonkills]>1)$session[user][fishturn] = 3;
if ($session[user][dragonkills]>3)$session[user][fishturn] = 4;
if ($session[user][dragonkills]>5)$session[user][fishturn] = 5;
Now in village.php:
addnav("Poseidon Pool","pool.php");

translated into german by deZent

Texte von Syïela
Ortsbeschreibung von Keanna
Umwandlung vom Waldsee in einen Fluss
Rückänderung auf einen See
********************************************/

define('POOLCOLORHEADER','`Û');
define('POOLCOLORTEXT','`w');
define('POOLCOLORBORDER','`9');

require_once "common.php";

checkday();
addcommentary();

music_set('poseidonpool');

$show_invent = true;

page_header("Weiher");
output("`c`b".POOLCOLORHEADER."Der Weiher`0`b`c`n");
if ($_GET[op] == "" ){
        redirect("pool.php?op=chat");
}
if ($_GET[op] == "quit" ){
        redirect("kiosk.php");
}
if ($_GET[op] == "chat" ){
    output(POOLCOLORTEXT."Unweit des Stadtplatzes öffnen sich die Gassen wieder für einen kleinen Platz. 
    Der sanfte Wind auf deiner Haut riecht nach Salz und das Kreischen der Seevögel über deinem Kopf lässt dich 
    erahnen wie nah du dem Hafen schon sein musst. Wie auch der Marktplatz und der Stadtplatz ist der Boden hier 
    gepflastert. Schmale, zwei- oder dreistöckige Häuser mit verzierten Giebeln umsäumen den Platz. Du entdeckst 
    ein paar Geschäfte, die wohl am Markt oder Hafen keinen Platz mehr gefunden haben, sowie das Gasthaus Zur goldenen Gans. 
    Bei gutem Wetter räumen die Wirtsleute einige Tische und Bänke vor ihr Geschäft. Besonders an lauen Abenden kann man 
    hier gut sitzen und den Blick auf den kleinen Weiher genießen, der unterirdisch gespeist wird und im Herzen von Eranya liegt.
    `nEine breite Brücke führt an dieser Stelle ans andere Ufer, wo das Hafenviertel beginnt. 
    Neben der Brücke führt ein schmaler Steg auf das Wasser hinaus, wo sogar ein paar alte Bote auf den sanften Wellen dümpeln. Zudem entdeckst
    du dort eine kleine, windschiefe Hütte,    die dir erst jetzt auffällt. Ein selbst geschriebenes Schild über der Eingangstür 
    gibt es als Angerbedarf zu erkennen. Daneben ist ein kleiner Fisch aufgezeichnet.`n`n");
    viewcommentary("waldsee", "Sagen", 25, "sagt");
}
if ($session['user']['dragonkills']>1)
{
        addnav("Am Weiher");
        addnav("Angelladen","bait.php");
        addnav("Zum Steg","fish.php");
}
addnav("Zurück zur Stadt");
addnav("Zurück zur Stadt","village.php");
addnav("Zum Hafen","harbor.php");
page_footer();
?> 
