
<?
#####################################
#                                   #
#            Osterspezial           #
#            für den Wald           #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#        von Sefan Freihagen        #
#     Laserian, Amon Chan und mfs   #
#           Texte von Daray         #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################
require_once "common.php";
page_header("Kleiner Hase");

if (!isset($session)) exit();

if ($_GET[op]==""){
   output("`n`c`@Der `&kleine `^Hase`c");
   output("`n`n`@Langsamen Schrittes näherst du dich einer kleinen Lichtung, auf der du ein
   niedliches kleines Häschen erblickst. Es tut sich friedlich und still an dem grünen Gras
   gütlich und beachtet dich nicht. Kleine rote Augen stehen in wunderschönem Kontrast zu
   dem schneeweißen Fell. Der Duft der Frühlingsblumen schwängert die Luft...`n`n
   Was willst du tun?`n`n
   <a href='forest.php?op=beobachten'>Stehenbleiben und den Hasen noch eine Weile beobachten?</a>`n`n
   <a href='forest.php?op=naehern'>Dich dem Häschen nähern?</a>`n`n
   <a href='forest.php'>Dich umdrehen und wieder im Wald verschwinden?</a>`n`n",true);
   addnav("","forest.php?op=beobachten");
   addnav("","forest.php?op=naehern");
   addnav("","forest.php?op=zurueck");
   addnav("Häschen Beobachten","forest.php?op=beobachten");
   addnav("Sich dem Häschen nähern","forest.php?op=naehern");
   addnav("Umdrehen und gehen","forest.php?op=zurueck");
   $session[user][specialinc] = "klhase.php";
}
if ($_GET[op]=="zurueck"){
   output("`@Das ist dir nicht geheuer und du verschwindest wieder von hier.");
   addnav("Wald","forest.php");
}
if ($_GET[op]=="beobachten"){
   output("`n`n`@Der Hase hoppelt nach einer Weile weiter und du kannst etwas Glitzerndes auf
   dem Boden entdecken. Freudig erkennst du, dass du einen Edelstein gefunden hast.`n`n");
   $session['user']['gems']+=1;
   addnav("Zurück zum Wald","forest.php");
   }

if ($_GET[op]=="naehern"){
   $lpminus=e_rand(1,15);
   $lpabzug=($lpminus*1.20);
   output("`n`n`@Du hörst ein knirschendes, schmatzendes Geräusch und erkennst, dass du auf
   ein Osternest getreten bist. Ehe du dich versiehst ist der Hase bei dir und verpasst dir
   einen Tritt...`n`n
   Du verlierst `^".$lpabzug." Lebenspunkte.`@`n`n");
   $session['user']['hitpoints']-=$lpabzug;
   addnav("Zurück zum Wald","forest.php");
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

