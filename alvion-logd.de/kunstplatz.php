
<?php
require_once "common.php";
addcommentary();
checkday();
page_header("Platz der alten Künste");
//if (getsetting("vendor",0)==1)addnav("Wanderhändler","vendor.php");
if (!$session['user']['rp_only']) addnav("Alchemistenhütte","alchemist.php");
if (!$session['user']['rp_only']) addnav("J?Jägerhütte","lodge.php");
addnav("Z?Zigeunerzelt","gypsy.php");
addnav("Friedhof","friedhof.php");
addnav("Seltsamer Felsen","rock.php");
addnav("Zum Dorf","village.php");

 addnav("","nebelgasse.php");

page_header("Platz der alten Künste");
$session['user']['standort']="Platz der alten Künste";

output("`c`b`|Pla`´tz d`ßer alt`ßen `´Kün`|ste`b`n`n`n");
output("<img src='./images/versteckt.gif'>`n`n`n`c",true);
output("`|Von deiner Neu`´gier gepackt, k`ßannst du einfach n`´icht widerstehen un`|d betrittst die ver`´steckte Gasse, die du e`ßben gefunden hast. Na`´ch einigen Metern erö`|ffnet sich vor dir e`´in recht großer Pla`ßtz, der jedoch nich`´t mit dem Dorfpla`|tz konkurrieren kann. A`´n einigen Stellen erk`ßennst du seltsame S`´teinsäulen mit mer`|kwürdigen Symbole`´n und Mustern d`|arauf. Ob sie woh`´l eine besondere B`ßedeutung haben? S`´chulterzuckend bl`|ickst du dich we`´iter um und erkennst a`ßuf ein Mal einige g`´rotesk wirkende Ge`|stalten in selts`´amen Gewändern. E`ßs klingt so, als wären si`´e in einen Sings`|ang verfallen und e`´s scheint auch so, a`ßls würden sie nic`´ht mehr auf ihre U`|mgebung achte`´n. Direkt hinter ih`ßnen kannst du au`´ch ein Zigeun`´erzelt erblicken u`|nd noch weiter hinte`´n treiben sich ei`ßnige Bewohner auf d`´em Friedhof he`|rum.`n`n`n");

viewcommentary("Platz_der_Kuenste","Hinzufügen",25,"sagt",1,1);

page_footer();
?>

