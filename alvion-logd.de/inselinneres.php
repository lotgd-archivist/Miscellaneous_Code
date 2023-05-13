
<?php
require_once "common.php";
addcommentary();
checkday();
page_header("Die Insel");
$session['user']['standort']="Insel";
/*
addnav("Trödler","vendor.php");
addnav("Möbelhändler","moebel.php");
addnav("W?MightyEs Waffen","weapons.php");
addnav("P?Pegasus Rüstungen","armor.php");
addnav("M?Mericks Ställe","stables.php");
if ($session['user']['rp_only']) addnav("Midas Charmeshop","midcha.php");
addnav("Geschenkeladen","newgiftshop.php");
addnav("Aeris Blumengeschäft","blumenmaedchen.php");

$realdatum = time();
$datum = date('m-d',$realdatum);
// Datum festlegen und welcher Tag gerade ist
if ($datum >= '02-10' && $datum <= '02-14'){
        addnav("Valentinsladen","valentineshop.php");
}
*/

addnav("B?Die A**** Bank","affenbank.php");
navhead("Zurück");
addnav("Zum Strand","inselstrand.php");

$str_out="`=`c`bDie Insel`b`c`nWieder ein mal bist du unterwegs am Strand, als dir ein seltsames Tier auffällt, das du wohl noch nie gesehen hast. Doch hat es auch dich bemerkt und flüchtet, so schnell es seine zwei Beine tragen. Neugierig geworden, folgst du ihm und gerätst so immer dichter ins Innere der Insel, wo Palmen wachsen und Sträucher, die du auch noch nicht kennst. Weiter und weiter geht die Verfolgung, bis du überrascht stehen bleibst, und dieses Tier schon vergessen hast: vor dir breitet sich eine Ansammlung von Hütten aus! Die Insel scheint also bewohnt zu sein.`n";
$str_out.="Doch da, hinter einer der Hütten, taucht plötzlich der überlange Schwanz dieses Tieres wieder auf und erneut verfolgst du es. Wild und sehr laut kreischend hüpft das Tier auf das mit großen Palmenblättern gedeckte Dach einer der Hütten und scheint dich von dort oben anzulachen. Jetzt endlich erkennst du: es ist ein Affe!`n`n`n";
output($str_out);
viewcommentary("insel","Hinzufügen",25,"sagt",1,1);

page_footer();

