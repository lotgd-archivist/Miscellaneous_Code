
<?php
require_once "common.php";
page_header("Wegweiser");
addnav("Zurück ins Dorf","village.php");
output("`c`b`BDe`Cr W`hegwe`Cis`Ber`b`c`n`n");

output("`BDu erreichst die große Anschlagtafel, wo die Dorfverwaltung nun endlich einmal einen Stadtplan angebracht hat. `nIn seltsam aussehenden Lettern steht ein kleiner Hinweis, der wie folgt lautet: `tBerühre den jeweiligen Standort, Fremder, und die Magie wird dich sofort an den gewünschten Ort tragen.`n`n`n");

output("<center><table>",true);
$bgcolor=($i%2==1?"trlight":"trdark");

//Die Umgebung
output('<tr class="$bgcolor"><td VALIGN="TOP">`B<u>`bDie Umgebung:</u>`b</td>',true);
output('<td VALIGN="TOP">',true);
output('<a href="forest.php">`CDüsterer Wald`n</a>',true);
addnav("","forest.php");

//Waldlichtung
output('<a href="lichtung.php">`CWaldlichtung`n`n</a>',true);
addnav("","lichtung.php");

//Sümpfe
output('<a href="suempfe.php">`CModrige Sümpfe`n`n`n`n`n</a>',true);
addnav("","suempfe.php");

output('<a href="felder.php">`CDie Felder`n</a>',true);
addnav("","felder.php");
output('<a href="waldsiedlung.php">`CWaldsiedlung`n</a>',true);
addnav("","waldsiedlung.php");
output('<a href="waldsee.php">`CWaldsee`n</a>',true);
addnav("","waldsee.php");
output('</td><td VALIGN="TOP">',true);

output('<a href="wasserfall.php">`n`hWasserfall`n</a>',true);
addnav("","wasserfall.php");
output('<a href="kapelle.php">`hWaldkapelle`n</a>',true);
addnav("","kapelle.php");
output('<a href="wstueck.php">`hDunkles Waldstück`n</a>',true);
addnav("","wstueck.php");
output('<a href="train.php">`hTrainingslager`n</a>',true);
addnav("","train.php");
output('<a href="pvparena.php">`hDie Arena`n</a>',true);
addnav("","pvparena.php");
output('<a href="herocamp.php">`hLager der Helden`n</a>',true);
addnav("","herocamp.php");
output('<a href="turm.php">`hTurm der Meister`n`n`n</a>',true);
addnav("","turm.php");
output('<a href="klippen.php">`hKlippen`n`n</a>',true);
addnav("","klippen.php");


output('</td><td VALIGN="TOP">',true);
output('<a href="nebelsee.php">`n`tNebelverhangener See</a>',true);
addnav("","nebelsee.php");
output('<a href="kathedrale.php">`n`n`tDunkle Kathedrale</a>',true);
addnav("","kathedrale.php");
output('<a href="kloster.php">`n`n`n`n`n`n`n`tKloster</a>',true);
addnav("","kloster.php");


output('</td><td VALIGN="TOP">',true);
output('<a href="katakomben.php">`n`n`n`zKatakomben</a>',true);
addnav("","katakomben.php");


//Das Dorf
output('</tr><tr class="$bgcolor"><td VALIGN="TOP">`B<u>`bDas Dorf Alvion:</u>&nbsp;&nbsp;&nbsp;`b</td>',true);
output('<td VALIGN="TOP">',true);
//Wohnviertel
output('<a href="wohnviertel.php">`CDie Dorfsiedlung`n</a>',true);
addnav("","wohnviertel.php");
//Rathausplatz
output('<a href="rathausplatz.php">`CDer Rathausplatz`n`n`n`n`n`n</a>',true);
addnav("","rathausplatz.php");
//Vergnügungsviertel
output('<a href="vergnueviertel.php">`CVergnügungsviertel`n`n`n</a>',true);
addnav("","vergnueviertel.php");
//Träumergasse
output('<a href="traumer.php">`CTräumergasse`n`n`n`n</a>',true);
addnav("","traumer.php");
if ($session["user"]["rp_only"])output("`n");
//Marktplatz
output('<a href="marktplatz.php">`CMarktplatz`n`n`n`n`n`n</a>',true);
addnav("","marktplatz.php");
if ($session["user"]["rp_only"])output("`n");
//Versteckte Gassen
output('<a href="kunstplatz.php">`CPlatz der alten Künste`n`n`n</a>',true);
addnav("","kunstplatz.php");
if (!$session["user"]["rp_only"])output("`n`n");
output('<a href="gildenstrasse.php">`CGildenstraße`n</a>',true);
addnav("","gildenstrasse.php");
output('<a href="goettertempel.php">`CTempel der Toleranz</a>',true);
addnav("","goettertempel.php");

output('</td><td VALIGN="TOP">',true);

//Rathausplatz
output('<a href="rathaus.php">`h`nRathaus`n</a>',true);
addnav("","rathaus.php");
output('<a href="library.php">`hBibliothek`n</a>',true);
addnav("","library.php");
output('<a href="houseshop.php">`hBauamt`n</a>',true);
addnav("","houseshop.php");
output('<a href="jail.php">`hPranger`n</a>',true);
addnav("","jail.php");
output('<a href="kerker.php">`hGefängnis`n</a>',true);
addnav("","kerker.php");
output('<a href="hof.php">`hHalle der Helden`n</a>',true);
addnav("","hof.php");

//Vergnügungsviertel
output('<a href="inn.php">`hTaverne des Walddrachen`n</a>',true);
addnav("","inn.php");
output('<a href="dancehouse.php">`hTanzsaal`n</a>',true);
addnav("","dancehouse.php");
output('<a href="frdnhaus.php">`hFreudenhaus`n</a>',true);
addnav("","frdnhaus.php");

//Träumergasse
output('<a href="brunnen.php">`hVerzauberter Brunnen`n</a>',true);
addnav("","brunnen.php");
output('<a href="gardens.php">`hDie Gärten`n</a>',true);
addnav("","gardens.php");
output('<a href="gartenrp.php">`hDie Rosengärten`n</a>',true);
addnav("","gartenrp.php");
if ($session["user"]["rp_only"])output("`n");
output('<a href="allee.php">`hAllee zum Obstgarten`n</a>',true);
addnav("","allee.php");



//Marktplatz
output('<a href="vendor.php">`hWarenhändler`n</a>',true);
addnav("","vendor.php");
output('<a href="weapons.php">`hMigthyEs Waffen`n</a>',true);
addnav("","weapons.php");
output('<a href="armor.php">`hPegasus Rüstungen`n</a>',true);
addnav("","armor.php");
output('<a href="stables.php">`hMericks Ställe`n</a>',true);
addnav("","stables.php");

if ($session['user']['rp_only']){
output('<a href="midcha.php">`hMidas Charmeshop`n</a>',true);
addnav("","midcha.php");
}
output('<a href="newgiftshop.php">`hGeschenkeladen`n</a>',true);
addnav("","newgiftshop.php");
output('<a href="blumenmaedchen.php">`hAeris Blumengeschäft`n</a>',true);
addnav("","blumenmaedchen.php");


//Versteckte Gassen
if (!$session['user']['rp_only']){
output('<a href="alchemist.php">`hAlchemistenhütte`n</a>',true);
addnav("","alchemist.php");
output('<a href="lodge.php">`hJägerhütte`n</a>',true);
addnav("","lodge.php");
}

output('<a href="gypsy.php">`hZigeunerzelt`n</a>',true);
addnav("","gypsy.php");
output('<a href="friedhof.php">`hFriedhof`n</a>',true);
addnav("","friedhof.php");

output('<a href="rock.php">`hSeltsamer Felsen`n</a>',true);
addnav("","rock.php");


output('</td><td VALIGN="TOP">',true);

if ($session['user']['rp_only']!=0){
output('`n`n`n`n`n`n`n`n`n`n`n`n<a href="rosengeist.php">`tRosenlabyrinth`n</a>',true);
addnav("","rosengeist.php");
output('<a href="lodge.php">`tTempel der Götter`n</a>',true);
addnav("","lodge.php");
}

if (!$session["user"]["rp_only"])output("`n`n`n`n`n`n`n`n`n`n`n`n`n");
output('<a href="obstgarten.php">`tObstgarten</a>',true);
addnav("","obstgarten.php");



output("</td></tr></table></center>`n`n`n`n`n",true);






//output("`n`n`n`v`c`$ Dieser Wegweiser befindet sich zur Zeit in überarbeitung!`c");
page_footer();
?>

