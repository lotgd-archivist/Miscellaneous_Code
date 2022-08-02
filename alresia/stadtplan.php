<?php 
require_once "common.php"; 
checkday();

if ($session['user']['alive']){ }else{
    redirect("shades.php");
}


page_header("Stadtplan"); 
addnav("Zurück zum Brunnenplatz","village.php"); 
output("`c`b`9S`ot`ma`Êd`kt`&p`Êl`ma`9n`b`c`n`n"); 


output("`mDu schlägst den Stadtplan Alresias auf, den du irgendwo einmal aufgeklaubt hast. Hier kannst du direkt sehen, welche Wege du einschlagen musst um dein Ziel zu erreichen.  `n`n`n");

output("`e`c`bUnder construction!`c`b`n`n`n`n`n");


output("<center><table>",true); 
$bgcolor=($i%2==1?"trlight":"trdark"); 
//Erste Reihe
//Nuvola
output('<tr class="$bgcolor"><td VALIGN="TOP">`B<u>`bNuvola:</u>`b</td>',true); 
output('<td VALIGN="TOP">',true); 


output('<a href="village.php">`CBrunnenplatz`n</a>',true); 
addnav("","village.php"); 

output('<a href="nhouses.php">`CWohnviertel`n</a>',true); 
addnav("","nhouses.php");

output('<a href="nuvola_regierungsviertel.php">`CRegierungsviertel`n`n`n</a>',true); 
addnav("","nuvola_regierungsviertel.php");

output('<a href="nuvola_vergnuegungsviertel.php">`CVergnügungviertel`n`n`n</a>',true); 
addnav("","nuvola_vergnuegungsviertel.php");

output('<a href="tempel_solys.php">`CTempel des Solys`n</a>',true); 
addnav("","");

output('<a href="uni.php">`CUniversität`n</a>',true); 
addnav("","");

output('<a href="library.php">`CBibliothek`n</a>',true); 
addnav("","library.php");

output('<a href="nuvola_gipfelpfad.php">`CGipfelpfad`n</a>',true); 
addnav("","nuvola_gipfelpfad.php");

output('<a href="stadtwache.php">`CStadtwache`n</a>',true); 
addnav("","stadtwache.php");

output('<a href="zuckerwattenstand.php">`CZuckerwattenstand`n</a>',true); 
addnav("","zuckerwattenstand.php");

output('<a href="inn.php">`CSchenke zum Eberkopf`n</a>',true); 
addnav("","inn.php");



output('</td><td VALIGN="TOP">',true); 

output('<a href="nuvola_ratshaus.php">`n`n`hRatshaus`n</a>',true); 
addnav("","nuvola_ratshaus.php"); 
output('<a href="nuvola_schloss.php">`hSchloss`n</a>',true); 
addnav("","nuvola_schloss.php"); 


output('<a href="nuvola_restaurant.php">`n`hRestaurant `iZum Glockenturm`i`n</a>',true); 
addnav("","nuvola_restaurant.php"); 


/*

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



*/

 
//Sílanna
output('</tr><tr class="$bgcolor"><td VALIGN="TOP">`B<u>`bSílanna:</u>`b</td>',true); 
output('<td VALIGN="TOP">',true);
 
output('<a href="silanna_village.php">`CSonnenplatz`n`n</a>',true); 
addnav("","silanna_village.php"); 

output('<a href="nhouses3.php">`CWohnviertel</a>',true); 
addnav("","nhouses3.php"); 

output('<a href="forest.php">`CWald`n</a>',true); 
addnav("","forest.php");

output('<a href="stables.php">`CKamorans Ställe`n</a>',true); 
addnav("","stables.php");

output('<a href="lodge.php">`CJägerhütte`n</a>',true); 
addnav("","lodge.php");

output('<a href="minx.php">`CMinx Tempel`n</a>',true); 
addnav("","minx.php");

output('<a href="krankenhaus.php">`CKrankenhaus`n</a>',true); 
addnav("","");

output('<a href="garden.php">`CGarten`n</a>',true); 
addnav("","garden.php");

output('<a href="rock.php">`CSeltsamer Felsen`n</a>',true); 
addnav("","rock.php");




//Ordamant

output('</tr><tr class="$bgcolor"><td VALIGN="TOP">`B<u>`bOrdamant:</u>`b</td>',true); 
output('<td VALIGN="TOP">',true);

output('<a href="nhouses4.php">`CWohnviertel`n</a>',true); 
addnav("","nhouses4.php");

output('<a href="ordamant_strand.php">`CStrand`n</a>',true); 
addnav("","ordamant_strand.php");

output('<a href="ordamant_felsenbucht.php">`CFelsenbucht`n</a>',true); 
addnav("","ordamant_felsenbucht.php");


output('<a href="ordamant_delfinschule.php">`CDelfinschule`n</a>',true); 
addnav("","ordamant_delfinschule.php");

output('<a href="ordamant_hafen.php">`CHafen`n</a>',true); 
addnav("","ordamant_hafen.php");

output('<a href="ordamant_korallenriff.php">`CKorallenriff`n</a>',true); 
addnav("","ordamant_korallenriff.php");

output('<a href="ordamant_kneipe.php">`CKneipe `iThe Locker`i`n</a>',true); 
addnav("","ordamant_kneipe.php");

output('<a href="ordamant_beobachtungsdeck.php">`CBeobachtungsdeck`n</a>',true); 
addnav("","ordamant_beobachtungsdeck.php");

output('<a href="ordamant_aussichtsplattform.php">`CAussichtsplattform`n</a>',true); 
addnav("","ordamant_aussichtsplattform.php");





//Essos
output('<tr class="$bgcolor"><td VALIGN="TOP">`B<u>`bEssos:</u>`b</td>',true); 
output('<td VALIGN="TOP">',true); 

output('<a href="nhouses2.php">`CWohnviertel`n</a>',true); 
addnav("","nhouses2.php");

output('<a href="waisen.php">`CWaisenhaus`n</a>',true); 
addnav("","waisen.php");

output('<a href="essos_schmiedegasse.php">`CVergnügungsviertel`n`n`n</a>',true); 
addnav("","essos_schmiedegasse.php");

output('<a href="armor.php">`CDagnas Rüstungen`n</a>',true); 
addnav("","armor.php");

output('<a href="weapons.php">`CDuncans Waffen`n</a>',true); 
addnav("","weapons.php");

output('<a href="essos_schmiede.php">`CSchmiede`n</a>',true); 
addnav("","essos_schmiede.php");


output('<a href="essos_kupfergasse.php">`CKupfergasse`n</a>',true); 
addnav("","essos_kupfergasse.php");

output('<a href="pfandleihhaus.php">`CPfandleihhaus`n</a>',true); 
addnav("","pfandleihhaus.php");




output('<a href="essos_altehandelsstrasse.php">`CAlte Handelsstraße`n</a>',true); 
addnav("","essos_altehandelsstrasse.php");

output('<a href="gypsy.php">`CZigeunerzelt`n</a>',true); 
addnav("","gypsy.php");


//Die Umgebung 

output('<tr class="$bgcolor"><td VALIGN="TOP">`B<u>`bDie Umgebung:</u>`b</td>',true); 
output('<td VALIGN="TOP">',true); 

output('<a href="essos_oase.php">`COase`n</a>',true); 
addnav("","essos_oase.php");

output('<a href="essos_duenenlandschaft.php">`CDünenlandschaft`n</a>',true); 
addnav("","essos_duenenlandschaft.php");

//Zweite Reihe
output('</td><td VALIGN="TOP">',true);































output("</td></tr></table></center>`n`n`n`n`n",true); 



output("`n`n`n`c<table><tr><td><center><img src='./images/alresia.png'></center></td></tr></table>`c`n", true);
output("`n`n");



//output("`n`n`n`v`c`$ Dieser Wegweiser befindet sich zur Zeit in überarbeitung!`c"); 
page_footer(); 
?>

