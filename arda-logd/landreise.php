<?php 
//Originalgerüst ist nach Idee und Script von 
//*-------------------------* 
//| Wanderweg | 
//| by Amerilion | 
//| first seen at | 
//| http://www.mekkelon.de.vu | 
//*-------------------------* 
//Symia-Pack Version 1.0 
// sanela.php
//modifiziert und erweitert by adminator 13.10.2006 als 
//*--------------------------------* 
//| Landreise.php Erweiterung V 0.8| 
//*--------------------------------* 
//Texte by Suki, adminator 
//für http://www.celticdruid.net/logd fochalan Edition 
// 
//Installation: Recht einfach, landreise.php ins root und beliebig 
//ausserhalb oder innerhalb der Städte verlinken. 
// Tip: Wäre ganz gut, wenn man bei Unterbrechungen auch wieder 
// in den Städten anfangen kann :-) 
// Die Städte solltet ihr den Euren anpassen, auch Mods wie z.B. Forest etc. 
// Alles kann man halt auch nicht standartisieren. 
// 
//Retitle.php ist nun nicht mehr nötig (hoff ich)... 
// 
//Beispiel: 
// addnav("Rundreise machen","landreise.php") 
// 
// Die Ortsnamen etc. sollten ans eigene Spiel angepasst werden. 
// 
// Es sind bis auf Kämpfe alles mögliche in diesem Miniadventure, 
// sogar ein kleines Labyrint (ca. 70 Möglichkeiten). 
// Probierts mal aus, Comments willkommen. 
//Wenn noch Bugs drin sind, bitte nicht erschlagen. 
// 
// Stand 13.10.06 by adminator 

require_once "common.php"; 
addcommentary(); 
checkday(); 
$gd=e_rand(1,1500); 
$gs=e_rand(1,10); 
$tu=e_rand(1,3); 
$hp=e_rand(1,50); 
$av=e_rand(1,3); 
$t=round($session['user']['turns']*0.2); 
page_header("die Landreise"); 
output("`c`b`8Die Landreise`c`b`n`n"); 
if($_GET['op']==""){ 
output("`c`2Du hast die letzten Häuser bald schon hinter dir gelassen und betrachtest dir"); 
output("die Landschaft.`n Weite Baumalleen wechseln mit kleinen Hainen ab und es geht über"); 
output("Stock und Stein auf manchmal ausgebauten und weniger ausgebauten Strassen und Pfaden"); 
output("quer durch das Land, Wälder, Auen, Felder und Gebirge.`n`n Du freust dich, das du endlich"); 
output("einmal die Zeit findest, das Land zu bereisen und dich umzusehen! Vielleicht findest"); 
output("Du ja auch mal ein Plätzchen, wo es sich zu leben lohnt und du die Reise verläsßt`c`n`n"); 
addnav("Weiter","landreise.php?op=1"); 
//modifikation by adminator - su zugriffe 14.10.06 Begin 
/*if ($session[user][superuser]>=3) addnav("`b_-_-_-_-_-_-_`b"); 
if ($session[user][superuser]>=3) addnav("`bSUPERVISOR`b"); 
if ($session[user][superuser]>=3) addnav(" `bSU-Aktion`b "); 
if ($session[user][superuser]>=1) addnav("`bNeuer Tag`b","newday.php",true); 
if ($session[user][superuser]>=2) addnav("`bAdmingrotte`b","superuser.php",true); 
if ($session[user][superuser]>=3) addnav("`bUsereditor`b","user.php",true); 
if ($session[user][superuser]>=3) addnav(" `bSU-Aktion`b "); 
if ($session[user][superuser]>=3) addnav("`bFochalan`b","village.php",true); 
if ($session[user][superuser]>=3) addnav("`bFochalan-Dorf`b","dorfrand.php",true); 
if ($session[user][superuser]>=3) addnav("`bFochalanTor`b","stadttor.php",true); 
if ($session[user][superuser]>=3) addnav("`bFocha-Aussen`b","eingang.php",true); 
if ($session[user][superuser]>=3) addnav("`bLandreise`b","landreise.php",true); 
if ($session[user][superuser]>=3) addnav("`bForst`b","forest.php",true); 
if ($session[user][superuser]>=3) addnav("`bNecron`b","necron.php",true); 
if ($session[user][superuser]>=3) addnav("`bSymia`b","sanela.php",true); 
if ($session[user][superuser]>=3) addnav("`bKyralajis`b","Kyralajis.php",true); 
if ($session[user][superuser]>=3) addnav("`bZwergenstadt`b","zwergenstadt.php",true); 
if ($session[user][superuser]>=3) addnav("`bAleinad`b","Aleinad.php",true); 
//modifikation by adminator - su zugriffe 14.10.06 End */

}elseif($_GET['op']=="1"){ 
page_header("die Landreise"); 
switch(e_rand(1,70)){ 
case 1: 
output("`c`2Du kommst nach langer Zeit tatsächlich an ein Wegschild Symia."); 
output("Da du etwas hungrig geworden bist, überlegst du ernsthaft ob du die"); 
output("Reise nicht einfach mal unterbrechen solltest und hier pauseieren kannst."); 
output("`n`n`^Du gingst offenbar einen Umweg durch die Felder und brauchtest"); 
output(" ".$t." Runden.`c"); 
$session['user']['turns']-=$t; 
addnav("Nach Symia gehen!","sanela.php"); 
addnav("Lieber Weitergehen","landreise.php?op=1"); 
break; 
case 2: 
output("`c`2Du triffst auf räubernde Wandalen, die dir den Weg versperren.`n`n`4Du verlierst ".$gd." Gold`c"); 
$session['user']['gold']-=$gd; 
addnav("Ärgerlich gehst du weiter","landreise.php?op=1"); 
addnav("Zum Schlafen in die Felder","login.php?op=logout",true); 
break; 
case 3: 
output("`c`6Du gelangst in die Wüste, auf der Suche nach einer `1Wasserstelle"); 
output("`6triffst du auf einen 200 Meter langen Wüstenwurm der dich mit"); 
output("gefletschten Zähnen quer über die Dünen jagt bis du ihn am Gebirge"); 
output("gerade noch abhängen kannst. `4Du verlierst 3 Waldkämpfe."); 
$session['user']['turns']-=3; 
addnav("Lieber Weitergehen","landreise.php?op=1"); 
break; 
case 4: 
output("`c`2Eine kranke Frau bittet dich am Wegrand um ein Almosen.`n"); 
output("Man sieht ihr an, das sie hungert und friert ..., dennoch sieht "); 
output("man ihr an, das sie eigentlich recht hübsch ist. `n`n`7Wieviel gibst du ihr?"); 
addnav("Nichts","landreise.php?op=goldnix"); 
addnav("1 Goldstück","landreise.php?op=goldgold"); 
addnav("1 Edelstein","landreise.php?op=goldgems"); 
addnav("Dich als Ehemann","landreise.php?op=golddich"); 
break; 
case 5: 
output("`c`7Ein Unwetter zieht auf, du läufst zu einer Hütte neben deinem Weg"); 
output("die plätzlich aus dem dichter werdenden Regen am Wegesrand auftaucht!`n`n"); 
output("Verblüfft stellst du fest, das du dich in Symia befindest.`n`"); 
addnav("Nach Symia?","sanela.php"); 
//addnav("In die Hütte!","lodge.php"); 
addnav("Regen macht mir nichts!","landreise.php?op=1"); 
break; 
case 6: 
output("`c`2Nachdem du schon einige Wege hinter dir hast findest du an einer Lichtung ein einladendes Plätzchen"); 
output("zum Ausruhen an einem ruhigen kleinen `1Waldsee`22. `nDu lässt dich erleichtert direkt am Ufer"); 
output("nieder und geniest die einsame Stille. `n`n`2Gedankenverloren fährst du mit den Fingerspitzen über die"); 
output("spiegelglatte Wasseroberfläche, so dass sie sich sanft kräuselt und verspielt plätschert.`n`n"); 
output("Mit den nassen Fingern streichst du dir erschöpft die Haare aus dem Gesicht und lauschst"); 
output("weiter dem beruhigenden Wasserplätschern ? bis du in der Erkenntnis erstarrst das"); 
output("du bereits lange aufgehört hast am Wasser zu spielen.`n`n"); 
addnav("Nachsehen was plätschert?","landreise.php?op=wasser"); 
addnav("mit unguten Gefühl weiter...","landreise.php?op=1"); 
break; 
case 7: 
output("`c`7Du kommst in das Brockengebirge. Große Berge säumen die Wege und als du so"); 
output("die Landschaft betrachtest fällt dir an einem Fels ein"); 
output("`6goldenes`2 Blinken auf. Schnall mal nachgesehen und so"); 
output("stößt du im Gebirge unerwartet auf eine `6Goldader`2."); 
output("Du erhältst `6 500 `2 Goldstücke und einen Charmepunkt."); 
$session['user']['gold']+=500; 
$session['user']['charm']+=1; 
addnav("Du gehst erfreut weiter","landreise.php?op=1"); 
break; 
case 8: 
output("`c`7Du trottest den steinigen Weg entlang und verfluchst die Wackersteine auf dem Weg.`n`n"); 
output("Weiter passiert stundenlang nichts aufregendes, nur Feld weit und breit...willst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Du stampfst in die Felder","dorftor.php"); 
addnav("Doch ein wenig durchhalten","landreise.php?op=1"); 
break; 
case 9: 
output("`c`2Du vermisst auf einmal deinen Geldbeutel, der muß wohl unbemerkt abgefallen sein. `4Darin waren ".$gs." Edelsteine."); 
output("`2Du siehst dich um und suchst, aber offenbar findest du ihn nicht - also hat ihn wohl ein anderer Wanderer eingesteckt..."); 
$session['user']['gems']-=$gs; 
addnav("Ärgerlich gehst du weiter","landreise.php?op=1"); 
addnav("Du stampfst in die Felder","dorftor.php"); 
break; 

case 10: 
output("`cDu siehst einen strahlenden Ritter mit weißem Pferd auf dich zureiten und...`n`n... erwachst frisch und ausgeruht!"); 
output("Du bekommst ".$hp." Lebenspunkte."); 
$session['user']['hitpoints']+=$hp; 
addnav("Du gehst erfrischt weiter","landreise.php?op=1"); 
break; 
case 11: 
output("`c`2Ein paar heldenhafte Ritter der Kokusnuss möchten dich als Page einstellen,"); 
output("sie erzählen dir wirres Zeug über Brieftauben und Kühe und das fasziniert dich!"); 
output("Gehst du freiwllig als Page für ein ruhmreiches Heldenleben mit?"); 
addnav("Nein.","landreise.php?op=1"); 
//addnav("Klar.","heldenweg.php?op=h2"); 
break; 
case 12: 
output("`c`2Das Land wird hügeliger und bald darauf sind am Horzont `7große Berge`2 erkennbar, die aus dem Dunst ragen."); 
output("Du kommst überraschend an einen Bergpfad mit Wegschild Kyralais.`n`n`^Da es bergauf ging brauchtest Du"); 
output(" ".$t." Runden."); 
$session['user']['turns']-=$t; 
//addnav("Kyralajis besuchen","Kyralajis.php");
addnav("Über die Berge","landreise.php?op=1"); 
break; 
case 13: 
output("`c`7Das Land ist felsig und große Berge, die aus dem Dunst ragen, säumen den Pfad.`n"); 
output("Du kommst überraschend an eine Abzweigung, einen kleinen Bergpfad mit"); 
output("`cunleserlichen Wegschild das dir gerade mal bis an den Bauchnabel geht"); 
output("`n`n`^Da es dauernd bergauf ging brauchtest Du"); 
output(" ".$t." Runden, bevor du vor einer kleinen Siedlung stehst."); 
addnav("In die Zwergenstadt","zwergenstadt.php"); 
addnav("Lieber Weitergehen","landreise.php?op=1"); 
break; 
case 14: 
output("`c`2Mitten auf einer Wiese findest du einen alten Brunnen den"); 
output("du dir näher ansehen willst. Als du dich über den Rand lehnst,"); 
output("um hinunter zu sehen, löst sich ein Stein und du fällst hinein.`n`n"); 
output("Als du aufwachst liegst du vor dem Haus von Frau Holle, die"); 
output("weder Arbeit noch Zeit für dich hat, selbst die Gold und Pech"); 
output("Bottiche am Ausgangstor sind leer.`n`n"); 
output("Zum Trost schüttet sie einen Eimer warmen `1Wassers`2 über dich und"); 
output("besprüht dich mit Deodorant. Frisch gewaschen und duftend stehst"); 
output("du wieder im Wald."); 
$session['user']['clean']==0; 
addnav("Frisch gewaschen gehts weiter","landreise.php?op=1"); 
break; 
case 15: 
output("`c`2Du kommst an eine alte Eiche. Eigentlich ein schöner Ort um auszuruhen.`n`n"); 
output("Irgendjemand hat Kommentare in die Eiche geritzt!"); 
output("`n`n`%`@Du liest`n"); 
viewcommentary("eiche","Hinzufügen",25); 
addnav("Du reist nach dem Einritzen weiter","landreise.php?op=1");
break; 
case 16: 
output("`c`2Du vermisst auf einmal deinen Geldbeutel, der muß wohl unbemerkt abgefallen sein. `4Darin waren ".$gd." Goldstücke."); 
output("`2Du siehst dich um und suchst, aber offenbar findest du ihn nicht - also hat ihn wohl ein anderer Wanderer eingesteckt..."); 
$session['user']['gold']-=$gd; 
addnav("Verärgert gehst du weiter","landreise.php?op=1"); 
break; 
case 17: 
output("`c`2du erkennst nach stundenlangen [zensiert] Zeit einige Hütten, und stolperst auf diese zu. Enttäuscht bemerkst"); 
output("du, dass es die Häuser von Arda sind.`n`n`^Du bist im Kreis gelaufen und verlierst alle Runden."); 
$session['user']['turns']=0; 
addnav("Zum Stadttor","dorftor.php"); 
break; 
case 18: 
output("`c`2Der Wald taucht vor dir auf. Das muß der `bKönigswald`b sein! Darin befinden sich"); 
output("bestimmt Monster und Geheimnisse, anders als auf der Reise. `n`nDu überlegst:"); 
addnav("Die Reise im Wald unterbrechen","forest.php"); 
addnav("Doch besser weitergehen","landreise.php?op=1"); 
break; 
case 19: 
output("`c`6Die Pest ist ausgebrochen`2 sagt dir ein entgegenkommender Wanderer."); 
output("Daraufhin meidest du ein paar Tage alle Siedlungen und Häuser, sogar"); 
output("entgegenkommenden Reisenden gehst du mit einem beherzten Sprung in die Büsche"); 
output("aus dem Weg. Dummerweise treibt dich der Hunger doch dann in die Städte.`n`n"); 
output("Du verlierst ".$tu." Runden, weil du den Menschen aus dem Weg gehst!"); 
$session['user']['turns']-=$tu; 
addnav("Du schleichst weiter","landreise.php?op=1"); 
break; 
case 20: 
output("`c`2Du kämpfest dich durch ein paar dichte Büche und schliesslich liegt"); 
output("die Heide vor dir. In der Heide lauern Moorlöcher und Gefahren,"); 
output("also bleibt die Wahl darum zu laufen, das kostet 10 Runden"); 
output("oder aber hindurch. `n`nWas tust du?"); 
addnav("Du gehst herum","landreise.php?op=1",$session['user']['turns']-=10); 
addnav("Du gehst hinein","landreise.php?op=heide0"); 
break; 
case 21: 
output("`c`2Du trottest den staubigen Pfad in der Heide entlang. Zwischen einigen Büschen regt sich etwas..."); 
output("Das könnten natürlich der berüchtigte Ali Baba und seine 40 Räuber sein oder der"); 
output("böse Saruman der dich verzaubern will, oder gar der Geist von Joda... grün und..."); 
output("Deine Phantasie malt sich schreckliches aus.`n`n Du bist unsicher...willst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("In die Felder (Logout)","login.php?op=logout",true); 
addnav("Lieber Weitergehen","landreise.php?op=1"); 
break; 
case 22: 
output("`c`2Ein dichter, großer Wald ragt vor dir auf. Das muß doch endlich der Auenwald sein!"); 
output("Du hast schon viel von seinen Reichtümern gehört, denn darin befinden sich"); 
output("bestimmt mehr Monster und noch mehr Geheimnisse, anders als auf der Reise.`n`n"); 
output("Du überlegst ob du die Reise hier unterbrechen solltest:"); 
addnav("Zum Wald","forest.php"); 
addnav("Lieber Weitergehen","landreise.php?op=1"); 
break; 
case 23: 
output("`c`7Ene Sonnenfinsternis verdunkelt das Land und es wird Nacht. Leider kannst du nicht weitergehen und gewinnst so ".$tu." Runden."); 
$session['user']['turns']+=$tu; 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Schlafen im Feld (Logout)","login.php?op=logout",true); 
addnav("Im Dunkeln tapsen","landreise.php?op=1"); 
break; 
case 24: 
output("`c`2Neugier Forscherdrang und unendlicher Leichtsinn verschlägt dich in die"); 
output("Gegend die von vielen als Moddermoor bezeichnet wird, warum dem so ist"); 
output("wird dir schnell klar und so versuchst du einen plötzlichen Anfall von"); 
output("Übelkeit zu unterdrücken.`n`n"); 
output("Was tust du?"); 
addnav("Du gehst herum","landreise.php?op=1",$session['user']['turns']-=10); 
addnav("Du gehst todesmutig hinein","landreise.php?op=heide0"); 
break; 
case 25: 
output("`c`2Du kommst an einen Hinkelstein. Eigentlich wäre dies"); 
output("ein schöner und heiliger Ort um etwas auszuruhen."); 
output("Irgendjemand hat einen Wunsch in den Stein gemeißelt!"); 
output("`n`n`%`@Du liest`n"); 
viewcommentary("menhir","Hinzufügen",25); 
addnav("Einritzen und weitergehn","landreise.php?op=1"); 
addnav("Dranlehnen und schlafen (Logout)","login.php?op=logout",true); 
break; 
case 26: 
output("`c`6An einer alten Eiche findest du einen beschädigten Bienenkorb,"); 
output("die Bienenkönigin bittet dich verblüffenderweise darum ihn"); 
output("für sie hoch in die Krone des Baumes zu tragen und dort mit"); 
output("Honig zu befestigen um sie und ihren Hofstaat vor Übergriffen"); 
output("zu schützen. Weil du gerade deine soziale Phase hast, willigst du"); 
output("ein und hilfst. Als Dank verrät sie dir, dass unter einer Wurzel 3"); 
output("Edelsteine versteckt sind"); 
$session['user']['gems']+=3; 
addnav("Honigbeschmiert weiter","landreise.php?op=1"); 
break; 
case 27: 
output("`c`2Irgendein Wanderer hat wohl ein Beutelchen verloren. Du öffnest es und findest ".$gd." Goldstücke."); 
output("Du siehst dich um, aber offenbar kommt der Besitzer nicht - also schnell eingesteckt..."); 
$session['user']['gold']+=$gd; 
addnav("Rasch weiter.","landreise.php?op=1"); 
break; 
case 28: 
output("`c`2Du gelangst in einen Teil des Waldes in dem du bisher noch nicht gewesen"); 
output("bist, ohne zu wissen wie es dich dorthin verschlagen hat und wie weit du"); 
output("von deinem Weg abgekommen bist. Das Dickicht wird immer dichter und der"); 
output("einstige Weg ist nunmehr nur ein Trampelpfad der sich zwischen den riesigen"); 
output("Bäumen zu verlieren scheint..."); 
addnav("weiter","landreise.php?op=tiefwald"); 
break; 
case 29: 
output("`c`2Irgendein Wanderer hat wohl ein Beutelchen verloren. Du öffnest es und findest ".$gd." Goldstücke."); 
output("Du siehst dich um, aber offenbar kommt der Besitzer nicht - also schnell eingesteckt..."); 
$session['user']['gold']+=$gd; 
addnav("Rasch weiter.","landreise.php?op=1"); 
break;
case 30: 
output("`c`7Du läufst einen steilen Bergpfad entlang und pfeifst gelangweilt ein Lied"); 
output("Weiter passiert nichts aufregendes...wären da nicht die schroffen Abgründe, bei denen"); 
output("Du ab und an ein wenig ausrutschst. Willst du vielleicht"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Weiterkraxeln","landreise.php?op=1"); 
break; 
case 31: 
output("`c`2Du bemerkst nach langer Zeit das du dich in einem großen Auenwald verirrtest. Nach eingen qualvollen Tagen und noch mehr Sucherei"); 
output("in der du im undurchdringlichen Dickicht feststeckst fällst du über eine Baumwurzel und verstauchst dir dein Bein.`n`n Fluchend willst du:"); 
addnav("besser umkehren?","landreise.php?op=kehrum"); 
addnav("Mühsam Weitergehen!","landreise.php?op=biszumende"); 
break; 
case 32: 
output("`c`2Du gelangst an eine `6sonnendurchflutete`2 Lichtung, die Zeit scheint hier"); 
output("langsamer zu fließen und zarte Blütenblätter schweben sanft durch"); 
output("die Luft. Der Ort wirkt magisch so dass du gebannt stehen bleibst"); 
output("und dich fasziniert umschaust.`n`n"); 
output("Während du dort ruhig verweilst erkennst du dass nicht weit von dir"); 
output("ein Einhorn hilflos nahe einer großen Rosenhecke steht und sich dort"); 
output("verfangen hat.`n`n Eilst du -das hilflose Ding beachtend- lieber..."); 
addnav("weg und verschwindest","landreise.php?op=einhorn1"); 
addnav("sofort hin, es befreien","landreise.php?op=einhorn2"); 
break; 
case 33: 
output("`c`2Wieder ein Wäldchen! Das muß doch endlich der Zwergenwald sein! Darin befinden sich"); 
output("bestimmt `6Gold und Edelsteine`2, anders als auf der dämlichen Reise. `n`nDu überlegst:"); 
addnav("Zum Wald - Reise unterbrechen","forest.php"); 
addnav("Ach Wälder...","landreise.php?op=1"); 
break; 
case 34: 
output("`c`2Du begenest einer alten Frau die dich bittet ihre Katze oben aus der Baumkrone zu retten."); 
output("Du bist gerade sehr in Eile aber mit vielen entschuldigenden Worten"); 
addnav("hilfst du dennoch","landreise.php?op=katze"); 
addnav("läufst Du weiter","landreise.php?op=katzene"); 
break; 
case 35: 
output("`c`7Ein Bergwald taucht vor dir auf. Das muß der berühmte Kyralajisswald sein! Darin befinden sich"); 
output("bestimmt unermessliche Schätze und Dinge von Zwergen, anders als auf dem Weg. `n`nDu überlegst:"); 
addnav("Im Wald suchen","forest.php"); 
addnav("Zwerge ... ach nee ","landreise.php?op=1"); 
break; 
case 36: 
output("`c`2Irgendein Wanderer hat wohl ein Beutelchen verloren. Du öffnest es und findest ".$gs." Edelsteine."); 
output("Du siehst dich um, aber offenbar kommt der Besitzer nicht - also schnell eingesteckt..."); 
$session['user']['gems']+=$gs; 
addnav("Schnell Weitergehen","landreise.php?op=1"); 
break; 
case 37: 
output("`c`2 feuerspeiende Phoenix versperren den Weg, du kannst ihnen grade noch so entkommen, doch dein"); 
output("Gewand trägt Brandflecken und schützt dich weniger!"); 
$session['user']['armorvalue']+=$av; 
addnav("Schnell Weitergehen","landreise.php?op=1"); 
break; 
case 38: 
output("`c`7Du trottest den steinigen Weg entlang und siehst einen Wanderer weit vor dir.`n`n"); 
output("Weiter passiert stundenlang nichts aufregendes, der Wanderer ist plötzlich weg!...willst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Du stampfst in die Felder","dorftor.php"); 
addnav("Doch ein wenig durchhalten","landreise.php?op=1"); 
break; 
case 39: 
output("`c`2Du trottest einen Waldpfad entlang und beobachtest ein paar Rehe die am Waldrand äsen"); 
output("Weiter passiert im Wald gerade nichts aufregendes...`n`nWillst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Durch den Wald","landreise.php?op=1"); 
break; 
case 40: 
output("`c`2Du vermisst auf einmal deinen Geldbeutel, der muß wohl unbemerkt abgefallen sein. Darin waren ".$gd." Goldstücke und ".$gs." Edelsteine"); 
output("Du siehst dich um und suchst, aber offenbar findest du ihn nicht - also hat ihn wohl ein anderer Wanderer eingesteckt..."); 
$session['user']['gold']-=$gd; 
$session['user']['gold']-=$gs; 
addnav("Ärgerlich gehst du weiter","landreise.php?op=1"); 
break; 
case 41: 
output("`c`7Ein Steinbruch vor dir auf. Du wolltest doch immer mal Steine hauen? Na Prima!"); 
output("`c`2Doch gibt es da bestimmt auch wertvolle Rohstoffe für die Reise. `n`nDu überlegst die Reise hier zu unterbrechen:"); 
//addnav("Zum Steinbruch","forest.php"); 
addnav("Zum Wald","forest.php"); 
addnav("Reise fortsetzen","landreise.php?op=1"); 
break; 
case 42: 
output("`c`2 3 fiese Hexen kommen auf ihren Besen herabgestürzt und rufen singend"); 
output("`3'Die Macht der Drei kann keiner entzweien'`2. Wirklich charmant! Das hörst du und deine"); 
output("`cWaffe leider nicht gern, sie geht leider etwas kaputt und wird stumpfer!"); 
$session['user']['weapondmg']-=1; 
addnav("Ärgerlich gehst du weiter","landreise.php?op=1"); 
break; 
case 43: 
output("`c`7Du trottest einen schmalen Wildnispfad entlang.`n`n"); 
output("Weiter passiert stundenlang nichts aufregendes, nur Feld weit und breit...willst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Du stampfst in die Felder","dorftor.php"); 
addnav("Doch ein wenig durchhalten","landreise.php?op=1"); 
break; 
case 44: 
output("`c`7Du trottest den schlammigen Weg entlang..`n`n"); 
output("Weiter passiert stundenlang nichts aufregendes, nur Feld weit und breit...willst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Du stampfst in die Felder","dorftor.php"); 
addnav("Doch ein wenig durchhalten","landreise.php?op=1"); 
break; 
case 45: 
output("`c`2Du erreichst endlich über einen verborgenen Pfad die ersten Häuser Ardas!"); 
output("Dein Magen sagt dir, das es Zeit wäre etwas zu essen. `n`nMöchtest du nun"); 
addnav("nach Arda essen?","village.php"); 
addnav("Du mampfst auf dem Weg?","landreise.php?op=1"); 
break; 
case 46: 
output("`c`1Du erreichst ein Flussufer! An jenem ist ein Fischer mit seinem Boot. `n`nMöchtest du nun"); 
addnav("ihn fragen ob er dich mitnimmt?","landreise.php?op=fischer"); 
addnav("besser mal umkehren?","landreise.php?op=umkehr"); 
addnav("Oder lieber gehen?","landreise.php?op=1"); 
break; 
case 47: 
case 39: 
output("`c`2Du schlägst dich durch dichten Dschungel, Lianen versperren den Weg."); 
output("Weiter passiert im Wald gerade nichts aufregendes...`n`nWillst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Durch den Wald","landreise.php?op=1"); 
break; 
case 48: 
output("`c`2Du schlägst dich durch dichten Dschungel, Lianen versperren den Weg."); 
output("Weiter passiert im Wald gerade nichts aufregendes...`n`nWillst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Durch den Wald","landreise.php?op=1"); 
break; 
case 39: 
output("`c`2Du gehst über Wildpfade und durchquerst eine große Wiese und einen Wald."); 
output("Weiter passiert im Wald gerade nichts aufregendes...`n`nWillst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Durch den Wald","landreise.php?op=1"); 
break; 
case 49: 
output("`c`2Du kommst an eine Höhle. Sie bietet dir Schutz und Sicherheit."); 
output("Irgendjemand hat Kommentare in die Höhle geritzt!"); 
output("`c`n`n`%`@Du liest`n"); 
viewcommentary("hoehle","Hinzufügen und weitergehen",25); 
addnav("Nach Einritzen Weitergehen","landreise.php?op=1"); 
addnav("Darin Schlafen (Logout)","login.php?op=logout",true); 
break; 
case 50: 
output("`c`2Du schlägst dich durch dichten Dschungel, Lianen versperren den Weg."); 
output("Weiter passiert im Wald gerade nichts aufregendes...`n`nWillst du"); 
addnav("umkehren?","landreise.php?op=kehrum"); 
addnav("Durch den Wald","landreise.php?op=1"); 
break; 
case 51: 
output("`c`b`8Rocky Horror`c`b`n`n"); 
output("`c`1Ein Regenschauer überrascht dich und du flüchtest dich eilig zu einem alten Mann der"); 
output("`cSchutz unter einem Baum gesucht hat. `c`2Ihr kommt ins Gespräch und er erzählt dir von"); 
output("der Sage dass am Ende des Regenbogens `6Gold`2 versteckt sein soll. "); 
output("Fasziniert starrst du in den Himmel, hörst seinen restlichen Ausführungen kaum noch"); 
output("zu und stürmst euphorisch los, als der Regenschauer endet und am Himmel sich"); 
output("tatsächlich ein `4Re`6ge`2nb`3og`1en `2abzeichnet"); 
output("Völlig außer Atem gelangst du an dein Ziel, der Topf voll Gold scheint zum greifen nah,"); 
output("als zwischen dir und dem Schatz ein sehr erzürnter Kobold auftaucht und ein"); 
output("unverständliches Kauderwelsch in etwa wie `7Rocky Horrorrrr`2 brabbelt.`n`n "); 
output("Verblüfft bleibst du stehen, als dich eine schimmernde Wolke einhüllt und als du wieder"); 
output("sehen kannst ist der Regenbogen, samt Schatz und Kobold verschwunden. `n`n Aber du"); 
output("fühlst dich irgendwie anders? du läufst schnell nach Haus und "); 
if ($session['user']['sex']!='1') 
{ 
$session['user']['sex']=1; 
$sex="1"; 
}else{ 
$session['user']['sex']=0; 
$sex="0"; 
} 
output("In einer nach Parfüm riechenden rosa Wolke schwebend gehst du"); 
if ($session['user']['sex']!='1') 
{ 
output(" als `4`bMann`b`2 "); 
}else{ 
output(" als `4`bFrau`b`2 "); 
} 
$sql = "UPDATE accounts SET sex='".$sex."' WHERE acctid='".$session['user']['acctid']."'"; 
(db_query($sql)); 
output("fluchend davon."); 
// Routine aus Retitle.php 
$sql = "SELECT name,title,dragonkills,acctid,sex,ctitle FROM accounts WHERE acctid=".$session[user][acctid].""; 
$result = db_query($sql); 
for ($i=0;$i<db_num_rows($result);$i++){ 
$row = db_fetch_assoc($result); 
//if ($i==0) echo "x".nl2br(output_array($titles)); 
$newtitle = $titles[(int)$row['dragonkills']][(int)$row['sex']]; 
if ($row['ctitle'] == "") { 
$oname = $row['name']; 
if ($row['title']!=""){ 
$n = $row['name']; 
$x = strpos($n,$row['title']); 
if ($x!==false){ 
$regname=substr($n,$x+strlen($row['title'])); 
$row['name'] = substr($n,0,$x).$newtitle.$regname; 
}else{ 
$row['name'] = $newtitle." ".$row['name']; 
} 
}else{ 
$row[name] = $newtitle." ".$row['name']; 
} 
} 
output("`@Ändere `^$oname`@ auf `^{$row['name']} `@($newtitle-{$row['dragonkills']}[{$row['sex']}]({$row['ctitle']}`@))`n"); 
if ($session['user']['acctid']==$row['acctid']){ 
$session['user']['title']=$newtitle; 
$session['user']['name']=$row['name']; 
}else{ 
$sql = "UPDATE accounts SET name='".addslashes($row['name'])."', title='".addslashes($newtitle)."', ctitle='".addslashes($newtitle)."' WHERE acctid='{$row['acctid']}'"; 
//output("`0$sql`n"); 
(db_query($sql)); 
} 
} 

// 

addnav("...weiter","landreise.php?op=1",true); 
break; 
case 52: 
output("`c`1Du erreichst ein Flussufer! Da die Hänge steil sind überlegst du nun"); 
addnav("umkehren?","landreise.php?op=umkehr"); 
addnav("Oder am Fluss weiter?","landreise.php?op=1"); 
break; 
case 53: 
case 54: 
case 55: 
output("`c`1Du erreichst den Ocean! An jenem ist ein Fischer mit seinem Boot der gerade"); 
output("sein Boot ein wenig flickt und untersucht.`n`n Möchtest du nun"); 
addnav("den Fischer fragen ob er dich mitnimmt?","landreise.php?op=fischernecron"); 
addnav("umkehren?","landreise.php?op=umkehr"); 
addnav("Oder lieber Weitergehen?","landreise.php?op=1"); 
break; 
case 56: 
case 57: 
output("`c`1Nach einem kleinen Regenschauer gelangst du unvermutet an das Ende des"); 
output("`4Re`6ge`2nb`3og`1ens und so auch an den sagenumwobenen Topf voll `6Gold`2, der gerade unbewacht"); 
output("ist. Leider kannst du ihn nicht im Ganzen heben, also nimmst du soviel `6Gold`2 mit"); 
output("wie du tragen kannst und eilst davon."); 
$session['user']['gold']+=1000; 
addnav("Fröhlich gehst du weiter","landreise.php?op=1"); 
break; 
case 58: 
case 59: 
case 60: 
output("`c`7Du kommst an einen alten Felsen. Eigentlich ein schöner Ort um sich darauf auszuruhen."); 
output("Irgendjemand hat Kommentare in den Felsen geritzt!"); 
output("`n`n`%`@Du liest`n"); 
viewcommentary("felsen","Hinzufügen und weitergehen",25); 
addnav("Auf dem Fels Schlafen (Logout)","login.php?op=logout",true); 
addnav("Einritzen und Weitergehen","landreise.php?op=1"); 
break; 
case 61: 
output("`c`3Dir erscheinen die Geister der Weihnacht. Der Geist der vergangenen Weihnacht hat heute einen"); 
output("ausgesprochen guten Tag und schenkt dir darum auch gerne etwas mehr."); 
output("Dazu gibt er dann beiläufig Kommentare ab und du denkst bei dir, das es"); 
output("besser wäre keine Geister zu reizen sondern es hinzunehmen. `n `n"); 
output("...Der Geist der vergangenen Weihnacht schenkt dir Charm und...`n"); 
$session['user']['charm']+=1; 
case 62: 
output("`2...Der Geist der kommenden Weihnacht schenkt dir ".$gd." Goldstücke und...`n"); 
$session['user']['gold']+=$gd; 
case 63: 
output("`2...Der Geist der vorletzten Weihnacht schenkt dir ".$gs." Edelsteine und...`n"); 
$session['user']['gems']+=$gs; 
case 64: 
output("`2...Der Geist der vergessenen Weihnacht schenkt dir ".$t." Runden und...`n"); 
$session['user']['turns']+=$t; 
case 65: 
output("`2......Der Geist der ausgefallenen Weihnacht schenkt dir einen Toaster! Verblüfft schaust du den Toaster an,"); 
output("doch Du kannst mit so einem Ding nichts anfangen. Er runzelt die Stirn"); 
output("und murmelt etwas von ...primitive Kultur... und reißt dir das Ding aus"); 
output("der Hand. Er verschwindet mit einem Plopp und du starrst auf deine leeren Hände!`n"); 
addnav("Weitergehen","landreise.php?op=1"); 
break; 
case 66: 
output("`c`2Dir erscheinen die Geister der Weihnacht. Der Geist der kommenden Weihnacht hat heute einen"); 
output("ausgesprochen miesen Tag und nimmt dir und anderen darum auch gerne etwas mehr."); 
output("ab. Dazu gibt er dann beiläufig Kommentare ab und du denkst bei dir, das es"); 
output("besser wäre keine Geister zu reizen sondern es hinzunehmen. `n `n"); 
output("...Der Geist der vergangenen Weihnacht nimmt dir Charm und...`n"); 
$session['user']['charm']-=1; 
case 67: 
output("`2...Der Geist der kommenden Weihnacht nimmt dir ".$gd." Goldstücke und...`n"); 
$session['user']['gold']-=$gd; 
case 68: 
output("`2...Der Geist der vorletzten Weihnacht nimmt dir ".$gs." Edelsteine und...`n"); 
$session['user']['gems']-=$gs; 
case 69: 
case 70: 
output("`2...Der Geist der vergessenen Weihnacht nimmt dir ".$t." Runden und...`n"); 
$session['user']['gold']-=$t; 
output("`2......Der Geist der ausgefallenen Weihnacht schenkt dir einen Toaster! Verblüfft schaust du den Toaster an,"); 
output("`cdoch Du kannst mit so einem Ding nichts anfangen. Er runzelt die Stirn"); 
output("`cund murmelt etwas von ...primitive Kultur... und reißt dir das Ding aus"); 
output("`cder Hand. Er verschwindet mit einem Plopp und du starrst auf deine leeren Hände!`n"); 
addnav("Weitergehen","landreise.php?op=1"); 

break; 
} 
}elseif($_GET['op']=="umkehr") 
{ 
page_header("die Landreise"); 
output("`c`b`8Umkehren`c`b`n`n"); 
output("`c`2Muss heute nicht dein Tag sein, du kommst aber immerhin in bewohntes Gebiet."); 
output("Also gehst du zurück nimmst du den linken Abzweig, also willst du.."); 
addnav("Nach Arda","dorftor.php"); 
addnav("Weitergehen","landreise.php?op=1"); 
addnav("den Kobold (du weißt schon wegen was) anflehn","landreise.php?op=bidde"); 
}elseif($_GET['op']=="kehrum") 
{ 
page_header("die Landreise"); 
output("`c`b`8Umkehren`c`b`n`n"); 
output("`c`2Muss heute nicht dein Tag sein, du kommst aber immerhin an eine Stelle die dir bekannt vorkommt."); 
output("Also gehst du zurück nimmst du den rechten Abzweig, der führt in flacheres Land..."); 
addnav("Weitergehen","landreise.php?op=1"); 
addnav("den Kobold (du weißt schon wegen was) anflehn","landreise.php?op=bidde"); 
}elseif($_GET['op']=="fischer") 
{ 
page_header("die Landreise"); 
output("`c`b`8Der Fischer aus Symia`c`b`n`n"); 
output("`c`2Der Fischer bietet dir an dich erstmal bis Symia mitzunehmen.Möchtest du"); 
addnav("Ablehnen","landreise.php?op=1"); 
addnav("Annehmen","sanelastrand.php"); 
}elseif($_GET['op']=="fischernecron") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Symiafischer`c`b`n`n"); 
output("`c`2Der Fischer bietet dir an dich übers Meer zurück bis Symia mitzunehmen. Möchtest du"); 
addnav("Ablehnen","landreise.php?op=1"); 
addnav("Annehmen","necron_hafen.php"); 
}elseif($_GET['op']=="biszumende") 
{ 
page_header("die Landreise"); 
output("`c`b`8Endlich Tod!`c`b`n`n"); 
output("`c`2 Du gehst weiter und verirrst dich vollkommen`n`n`^Du bist tot.`nDu verlierst all dein Gold.`n"); 
$session['user']['alive']=false; 
$session['user']['hitpoints']=0; 
$session['user']['gold']=0; 
addnav("Tägliche News","news.php"); 
}elseif($_GET['op']=="tiefwald") 
{ 
page_header("die Landreise"); 
output("`c`b`8Im tiefen Wald!`c`b`n`n"); 
output("`c`2Du wechselst die Richtung, in der Hoffnung den Rückweg zu finden"); 
output("und gelangst so nur noch tiefer in den Wald, bis du dich erschöpft"); 
output(" an einen Felsen lehnst. Minutenlang ringst du so nach Luft, bis du"); 
output("die Augen öffnest und an der immer mehr einnehmenden Dunkelheit"); 
output("erkennst das die Nacht hereinbricht."); 
addnav("Weiter","landreise.php?op=tiefwald2"); 
}elseif($_GET['op']=="tiefwald2") 
{ 
page_header("die Landreise"); 
output("`c`b`8Im tiefen Wald, Nachts!`c`b`n`n"); 
output("`c`7Wolfgeheul schrillt durch die Stille und knackendes Unterholz in deiner"); 
output("unmittelbaren Nähe verrät dir das du nicht alleine bist. Erst als wenige"); 
output(" Meter vor dir `6`bgoldgelbe`b`7 Augen aufblitzen hast du Gewissheit."); 
output("Du stürmst los, kämpfst dich panisch vorwärts und verlierst dabei"); 
output("all dein Geld und zerfetzt deine Kleidung an den Ästen und Bäumen so das"); 
output("du schließlich halbnackt und zerkratzt am Rande der Stadt wieder"); 
output("aus dem Wald kriechst, den du sobald nicht mehr betreten wirst?."); 
$session['user']['armorvalue']-=$av; 
$session['user']['hitpoints']-=$hp; 
if ($session['user']['hitpoints']<1) {
$session['user']['alive']=false; 
}
$session['user']['gold']-=$gd; 
addnav("Weiter","dorftor.php"); 
}elseif($_GET['op']=="wasser") 
{ 
page_header("die Landreise"); 
output("`c`b`8Am grünen Wasser...`c`b`n`n"); 
output("`c`1Bevor du die Situation begreifst, siehst du im Augenwinkel eine Hand aus dem"); 
output("Wasser schnellen die dich mit einem eiskalten Griff in die Tiefe zieht und die"); 
output("Dunkelheit dich einhüllt. Wenn es dich beruhigt: Du stirbst in den Armen einer"); 
output("schönen Nixe!"); 
$session['user']['alive']=false; 
$session['user']['hitpoints']=0; 
addnav("Tägliche News","news.php"); 
}elseif($_GET['op']=="goldnix") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Bettlerin...`c`b`n`n"); 
output("`c`7Du gibst ihr lachend nichts und sie bedankt sich mit einem Fluch"); 
output("bei dir, der dich innerlich erstarren lässt."); 
$session['user']['charm']-=1; 
addnav("Weiter","landreise.php?op=1"); 
}elseif($_GET['op']=="goldgold") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Bettlerin...`c`b`n`n"); 
output("`c`2Du gibst ihr ein Goldstück und sie bedankt sich mit einem Lächeln"); 
output("Bei dir, das dich innerlich erstrahlen lässt."); 
$session['user']['gold']-=1; 
$session['user']['charm']+=1; 
addnav("Weiter","landreise.php?op=1"); 
}elseif($_GET['op']=="goldgems") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Bettlerin...`c`b`n`n"); 
output("`c`2Du gibst ihr einen Edelstein und sie bedankt sich mit einem Lächeln"); 
output("Bei dir, das dich innerlich erstrahlen lässt."); 
$session['user']['gems']-=1; 
$session['user']['charm']+=1; 
addnav("Weiter","landreise.php?op=1"); 
}elseif($_GET['op']=="golddich") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Bettlerin...`c`b`n`n"); 
output("`c`2Du küsst Sie und schon seid ihr ein Paar, so wäre alles schön aber..."); 
output("`7...so enden nur Märchen. Sie steckt dich an und ein paar Tage später"); 
output("`7stirbt sie in deinen Armen auf eurer Hütte in den Highlands"); 
output("Etwas später stirbst du auch, denn es kann nur einen geben..."); 
$session['user']['alive']=false; 
$session['user']['hitpoints']=0; 
addnav("Tägliche News","news.php"); 
}elseif($_GET['op']=="katze") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Katze...`c`b`n`n"); 
output("`c`2Als Dank für deine Hilfe schenkt dir die Alte drei Haselnüsse. Etwas"); 
output("irritiert und enttäuscht nimmst du sie an, bedankst dich aber dennoch."); 
output("Als du schon längst weiter gezogen bist, willst du sie dir genauer"); 
output("ansehen und holst sie aus deiner Tasche, dabei fallen sie dir aus der"); 
output("Hand und zerbrechen.`n`n"); 
output("Du bückst dich nach ihnen herunter, doch anstelle der Haselnüsse"); 
output("liegen auf wundersame Weise drei glitzernde Edelsteine auf dem Boden."); 
$session['user']['gems']+=3; 
$session['user']['charm']+=1; 
addnav("Weiter","landreise.php?op=1"); 
}elseif($_GET['op']=="katzene") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Katze...`c`b`n`n"); 
output("`c`2Nach einiger Entfernung bringt dich dein schlechtes Gewissen dazu"); 
output("stehen zubleiben und zurückzublicken, doch die Alte ist verschwunden"); 
output("und so ziehst du tiefgrübelnd und mit schlechtem Gewissen weiter."); 
$session['user']['charm']-=1; 
addnav("Weiter","landreise.php?op=1"); 
}elseif($_GET['op']=="einhorn1") 
{ 
page_header("die Landreise"); 
output("`c`b`8Das Einhorn...`c`b`n`n"); 
output("`c`2Weil die Zeit an diesem Ort nicht nur langsamer verlief sondern"); 
output(" sogar etwas rückläufig war, hast du genug für 3 Weitere Waldkämpfe."); 
$session['user']['turns']+=3; 
addnav("Weiter","landreise.php?op=1"); 
}elseif($_GET['op']=="einhorn2") 
{ 
page_header("die Landreise"); 
output("`c`b`8Das Einhorn...`c`b`n`n"); 
output("`c`2Als du zu ihm gelangst und es ehrfürchtig berührst, weicht die"); 
output("anmutige Gestalt und ein finsterer Furcht einflößender Dämon"); 
output(" bäumt sich vor dir auf und verschlingt dich. Du bist tod."); 
$session['user']['alive']=false; 
$session['user']['hitpoints']=0; 
addnav("Tägliche News","news.php"); 
}elseif($_GET['op']=="heide0") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Kaum ein paar Schritte hast du hinter Dir, da schliesst sich auch"); 
output("auch schon hinter dir die Lücke. Einen festen Weg wirst du hier"); 
output("im Heidekraut wohl kaum finden, auch der Nebel macht dir zu schaffen."); 
output("da vor dir ein größeres schwarzes Moorauge auftaucht denkst du"); 
addnav("Weiter gehts immer","landreise.php?op=heidel0g"); 
addnav("Lieber Links","landreise.php?op=heidel1"); 
}elseif($_GET['op']=="heidel0g") 
{ 
page_header("die Landreise"); 
output("`c`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Also gehst du zum `1Moorauge, einem See`2, der `7dunkel`7 vor dir liegt"); 
output("Willst du ihn links entlang umrunden oder eher rechts?"); 
addnav("Links","landreise.php?op=heidel2"); 
addnav("Rechts","landreise.php?op=heider1"); 
addnav("Lieber zurück?","landreise.php?op=heidel0g"); 
//modifikation by adminator - su zugriffe 14.10.06 Begin 
/*
if ($session[user][superuser]>=3) addnav("`b_-_-_-_-_-_-_`b"); 
if ($session[user][superuser]>=3) addnav("`bSUPERVISOR`b"); 
if ($session[user][superuser]>=3) addnav(" `bSU-Aktion`b "); 
if ($session[user][superuser]>=1) addnav("`bNeuer Tag`b","newday.php",true); 
if ($session[user][superuser]>=2) addnav("`bAdmingrotte`b","superuser.php",true); 
if ($session[user][superuser]>=3) addnav("`bUsereditor`b","user.php",true); 
if ($session[user][superuser]>=3) addnav(" `bSU-Aktion`b "); 
if ($session[user][superuser]>=3) addnav("`bFochalan`b","village.php",true); 
if ($session[user][superuser]>=3) addnav("`bFochalan-Dorf`b","dorfrand.php",true); 
if ($session[user][superuser]>=3) addnav("`bFochalanTor`b","dorftor.php",true); 
if ($session[user][superuser]>=3) addnav("`bFocha-Aussen`b","eingang.php",true); 
if ($session[user][superuser]>=3) addnav("`bLandreise`b","landreise.php",true); 
if ($session[user][superuser]>=3) addnav("`bForst`b","forest.php",true); 
if ($session[user][superuser]>=3) addnav("`bNecron`b","necron.php",true); 
if ($session[user][superuser]>=3) addnav("`bSymia`b","sanela.php",true); 
if ($session[user][superuser]>=3) addnav("`bKyralajis`b","Kyralajis.php",true); 
if ($session[user][superuser]>=3) addnav("`bZwergenstadt`b","zwergenstadt.php",true); 
if ($session[user][superuser]>=3) addnav("`bArtep`b","artep.php",true); 
//modifikation by adminator - su zugriffe 14.10.06 End */

}elseif($_GET['op']=="heidel1") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Ein paar Meter weiter versperren dichte Büsche den Weg,"); 
output("Rechts und links von dir ist der Boden schon weich und morastisch.`n`n"); 
output("Hier geht es offensichtlich nicht weiter"); 
addnav("Zurück","landreise.php?op=heidel0g"); 
}elseif($_GET['op']=="heidel2") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Du gehst also links am See entlang da bemerkst du rechts einen Steg"); 
output("Wo ein Steg ist ist auch meist ein Boot denkst du dir... `n`nwohin also?"); 
addnav("Zum Steg","landreise.php?op=heidel2r1"); 
addnav("Weiter den See entlang","landreise.php?op=heidel20"); 
}elseif($_GET['op']=="heidel20") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Du gehst unbeirrt um das grosse Moorauge, doch nach links biegt ein"); 
output("Pfad ab, nach rechts am See geht es über einen Knüppeldamm weiter..."); 
addnav("Links","landreise.php?op=heidel2l"); 
addnav("Weiter","landreise.php?op=heidel2r2"); 
}elseif($_GET['op']=="heider1") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Du gehst unbeirrt also rechts um das `1grosse Moorauge`2, einen tiefen See"); 
output("doch nach einigen Metern biegt rechts ein gutbebauter Weg ab"); 
output("nach links geht es etwas näher an den See heran... `n`nwohin gehst du"); 
addnav("Rechts","landreise.php?op=heide0"); 
addnav("Links","landreise.php?op=heider10"); 
}elseif($_GET['op']=="heider10") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`1Das Moorauge`2 blubbert verdächtig links neben Dir und `6das Schilf`2"); 
output("lichtet sich links verdächtig. Willst du nun ..."); 
addnav("Links, näher an den See","landreise.php?op=heider1l1"); 
addnav("Weiter geradeaus","landreise.php?op=heider101"); 
//modifikation by adminator - su zugriffe 14.10.06 Begin 
/*
if ($session[user][superuser]>=3) addnav("`b_-_-_-_-_-_-_`b"); 
if ($session[user][superuser]>=3) addnav("`bSUPERVISOR`b"); 
if ($session[user][superuser]>=3) addnav(" `bSU-Aktion`b "); 
if ($session[user][superuser]>=1) addnav("`bNeuer Tag`b","newday.php",true); 
if ($session[user][superuser]>=2) addnav("`bAdmingrotte`b","superuser.php",true); 
if ($session[user][superuser]>=3) addnav("`bUsereditor`b","user.php",true); 
if ($session[user][superuser]>=3) addnav(" `bSU-Aktion`b "); 
if ($session[user][superuser]>=3) addnav("`bFochalan`b","village.php",true); 
if ($session[user][superuser]>=3) addnav("`bFochalan-Dorf`b","dorfrand.php",true); 
if ($session[user][superuser]>=3) addnav("`bFochalanTor`b","dorftor.php",true); 
if ($session[user][superuser]>=3) addnav("`bFocha-Aussen`b","eingang.php",true); 
if ($session[user][superuser]>=3) addnav("`bLandreise`b","landreise.php",true); 
if ($session[user][superuser]>=3) addnav("`bForst`b","forest.php",true); 
if ($session[user][superuser]>=3) addnav("`bNecron`b","necron.php",true); 
if ($session[user][superuser]>=3) addnav("`bSymia`b","sanela.php",true); 
if ($session[user][superuser]>=3) addnav("`bKyralajis`b","Kyralajis.php",true); 
if ($session[user][superuser]>=3) addnav("`bZwergenstadt`b","zwergenstadt.php",true); 
if ($session[user][superuser]>=3) addnav("`bArtep`b","artep.php",true); 
//modifikation by adminator - su zugriffe 14.10.06 End */

}elseif($_GET['op']=="heider1l1") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Vor dir wird der Boden sehr schlammig und saugt an deinen Füssen,"); 
output("Du kommst an das Moorauge so nicht heran, also bleibt dir nur..."); 
addnav("Zurück","landreise.php?op=heider10"); 
}elseif($_GET['op']=="heider101") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2So umrundest du langsam rechts das `1Moorauge`2 und kommst nun an eine feste,"); 
output("lichte Stelle direkt am `1Wasser`2. `n`nDu überlegst dir deine Möglichkeisten:"); 
addnav("Über den See schwimmen","landreise.php?op=heidel2r1"); 
addnav("Ganz Rechts in die Büsche","landreise.php?op=heider1r1"); 
addnav("Rechts auf dem Weg bleiben","landreise.php?op=heider1r2"); 
}elseif($_GET['op']=="heider1r1") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Du begiebst dich nun auf einen stundenlagen Weg, der dich ins Schwarzmoor"); 
output("führt. Irgendwann siehst du ein, das der Boden unter dir eher schwankt"); 
output("und es eindeutig klüger wäre umzukehren, als im Moor umzukommen."); 
addnav("Zurückgehen","landreise.php?op=heider101"); 

}elseif($_GET['op']=="heider1r2") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("Sicher war es die letzte Zeit etwas sumpfig, aber nun wird das `6Schilf`2 höher und du"); 
output("zweifelst langsam an deiner Orientierung... beim heiseren Schrei eines Reihers denkst du..."); 
addnav("Soll ich weiter","landreise.php?op=heider1r"); 
addnav("Oder lieber zurück","landreise.php?op=heider101"); 

}elseif($_GET['op']=="heider1r") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`6Das wars! Das Schilf ist dichter als mancher Weidenkorb und du stehst"); 
output("`1knöchelhoch`6 im Wasser. `n`n Vielleicht war die letzte Abzweigung besser..."); 
addnav("Nochmal dort probieren","landreise.php?op=heider1r2"); 
addnav("Besser ganz zurück","landreise.php?op=heide0"); 
}elseif($_GET['op']=="heidel2r1") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`1Ein fester Steg! Und sogar ein Fischerboot ist noch da...sicher hat das"); 
output("Boot schon bessere Zeiten erlebt, aber Holz schwimmt doch immer, oder? "); 
output("`n`nDu überlegst dir deine Möglichkeisten:"); 
addnav("Über den See schwimmen!","landreise.php?op=heider101"); 
addnav("Ins Boot hüpfen.","landreise.php?op=heidel2r1b"); 
addnav("Zurück auf den Weg?","landreise.php?op=heidel2"); 
}elseif($_GET['op']=="heidel2r1b") 
{ 
page_header("die Landreise"); 
output("`c`c`b`8Die Heide...`c`b`n`n"); 
output("`c`1Tja, es ist zwar ein Boot aber dummerweise nicht dicht genug. Ein paar"); 
output("Planken sind lose und so sinkt es mit dir herunter bis auf den flachen "); 
output("Grund, Du stehst im Wasser. `n`nWas nun?"); 
addnav("Über den See schwimmen!","landreise.php?op=heider101"); 
addnav("Zurück auf den Weg?","landreise.php?op=heidel2"); 
}elseif($_GET['op']=="heidel2l") 
{ 
page_header("die Landreise"); 
output("`c`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Weil du dem Gestank nach verfaulten Eiern momentan"); 
output("aber mehr Aufmerksamkeit schenkst als dem Weg den du einschlägst,"); 
output("befindest du dich nach kurzer Zeit mitten im `7Moor`2 und drohst"); 
output("immer mehr einzusacken."); 
addnav("Lieber Zurück","landreise.php?op=heidel20"); 
}elseif($_GET['op']=="heidel2r2") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Du bist kaum über den Knüppeldamm gegangen, als dir seltsames widerfährt:"); 
output("`7Dein Blick wandert um dich und du starrst auf eine skeletierte Hand"); 
output("die direkt neben dir aus dem Moor ragt.`2Fast starr vor Angst wendest"); 
output("du dich ab und da ein `3Licht`2 in der Ferne schimmert bewegst du dich"); 
output("natürlich langsam kriechend darauf zu, oder etwa nicht?"); 
addnav("Weiter","landreise.php?op=heidel2r20"); 
addnav("Lieber Zurück","landreise.php?op=heidel20"); 
}elseif($_GET['op']=="heidel2r20") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`7Die Gegend wird etwas lichter, doch du bist vom Nebel umgeben!"); 
output("Irgendwo jault ein großer Hund auf, du hast sicher schon von ihm"); 
output("gehört und möchtest nur noch aus dieser Heide und dem Moor hinaus-"); 
addnav("Links","landreise.php?op=heidel2r2l"); 
addnav("Rechts","landreise.php?op=heidel2r2r"); 
addnav("Lieber Zurück","landreise.php?op=heidel2r2"); 
//modifikation by adminator - su zugriffe 14.10.06 Begin 
/*
if ($session[user][superuser]>=3) addnav("`b_-_-_-_-_-_-_`b"); 
if ($session[user][superuser]>=3) addnav("`bSUPERVISOR`b"); 
if ($session[user][superuser]>=3) addnav(" `bSU-Aktion`b "); 
if ($session[user][superuser]>=1) addnav("`bNeuer Tag`b","newday.php",true); 
if ($session[user][superuser]>=2) addnav("`bAdmingrotte`b","superuser.php",true); 
if ($session[user][superuser]>=3) addnav("`bUsereditor`b","user.php",true); 
if ($session[user][superuser]>=3) addnav(" `bSU-Aktion`b "); 
if ($session[user][superuser]>=3) addnav("`bFochalan`b","village.php",true); 
if ($session[user][superuser]>=3) addnav("`bArda-Dorf`b","dorfrand.php",true); 
if ($session[user][superuser]>=3) addnav("`bArdaTor`b","dorftor.php",true); 
if ($session[user][superuser]>=3) addnav("`bFocha-Aussen`b","eingang.php",true); 
if ($session[user][superuser]>=3) addnav("`bLandreise`b","landreise.php",true); 
if ($session[user][superuser]>=3) addnav("`bForst`b","forest.php",true); 
if ($session[user][superuser]>=3) addnav("`bNecron`b","necron.php",true); 
if ($session[user][superuser]>=3) addnav("`bSymia`b","sanela.php",true); 
if ($session[user][superuser]>=3) addnav("`bKyralajis`b","Kyralajis.php",true); 
if ($session[user][superuser]>=3) addnav("`bZwergenstadt`b","zwergenstadt.php",true); 
if ($session[user][superuser]>=3) addnav("`bArtep`b","artep.php",true); 
//modifikation by adminator - su zugriffe 14.10.06 End */

}elseif($_GET['op']=="heidel2r2l") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`7Der Nebel wird dichter, doch vor dir raschelt etwas und du hörst ein"); 
output("tiefes sonores Grunzen. das unschwer als Schwein zu erkennen ist."); 
output("Nach einiger Zeit ruhigen Wartens raschelt es und du kannst nun..."); 
addnav("Rechts gehen","landreise.php?op=heidel2r2lr"); 
addnav("Links gehen","landreise.php?op=heidel2r2ll"); 
addnav("Zurück gehen","landreise.php?op=heidel2r20"); 
}elseif($_GET['op']=="heidel2r2lr") 
{ 
page_header("die Landreise"); 
output("`c`c`b`8Die Heide...`c`b`n`n"); 
output("`c`7Ein toter Baum ragt vor dir auf. Du möchtest ihn überklettern "); 
output("und siehst im letzten Moment gerade noch, das er auf der anderen"); 
output("Seite im Moor liegt. Glück gehabt! Aufatmend drehst du um..."); 
addnav("Zurück","landreise.php?op=heidel2r2l"); 
}elseif($_GET['op']=="heidel2r2ll") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`2Das Grün um dich das du fälschlicherweise als Wiese erkannt"); 
output("hast entpuppt sich lediglich als dünner Moosfilm unter dem"); 
output("eine widerwärtige braune Schlammmasse wabert und dich zu"); 
output("verschlingen droht. Beißende Gase und bestialischer Gestank"); 
output("rauben dir die Luft zum atmen während dir die Suppe bis zur"); 
output("Brust steht.`n`n Je mehr du dagegen ankämpfst desto tiefer sackst"); 
output("du ab, so dass dich letztlich schiere Panik überkommt und du"); 
output("hastig um dich tastest um dich daran heraus zu ziehen.`n`n"); 
output("Da bekommst du plötzlich etwas zu fassen und setzt all deine"); 
output("Kraft ein um deiner Falle zu entfliehen. Nass bis auf die"); 
output("Knochen und schlammbedeckt liegst du auf einem fauligen alten"); 
output("Baumstamm und ringst nach Luft."); 
addnav("Zurück","landreise.php?op=heidel2r2l"); 
}elseif($_GET['op']=="heidel2r2r") 
{ 
page_header("die Landreise"); 
output("`c`b`8Die Heide...`c`b`n`n"); 
output("`c`6`bGepackt!`b"); 
output("`2Nach endlos langen Stunden stolperst du in lichteres Gelände und"); 
output("selbst die Vögel zwitscher wieder. Rechts und links sind Wiesen."); 
output("Es dämmert langsam und so überlegst du ernsthaft:"); 
addnav("Einschlafen (Logout)","login.php?op=logout",true); 
addnav("Weiter mit der Reise","landreise.php?op=1"); 
}elseif($_GET['op']=="bidde") 
{ 
page_header("die Landreise"); 
output("`c`b`8Rocky Horror`c`b`n`n"); 
output("Dein Flehen hat der Kobold gehört und mit einem Plopp taucht er vor dir auf"); 
output("unverständliches Kauderwelsch in etwa wie `7Elvis lebt`2 brabbelt.`n`n "); 
output("Erleichtert bleibst du stehen, als dich eine schimmernde Wolke einhüllt und als du wieder"); 
output("sehen kannst ist der Regenbogen, samt Schatz und Kobold verschwunden. `n`n Aber du"); 
output("fühlst dich irgendwie anders? du läufst schnell nach Haus und "); 
if ($session['user']['sex']!='1') 
{ 
$session['user']['sex']=1; 
$sex="1"; 
}else{ 
$session['user']['sex']=0; 
$sex="0"; 
} 
output("In einer nach Parfüm riechenden rosa Wolke schwebend gehst du"); 
if ($session['user']['sex']!='1') 
{ 
output(" als `4`bFrau`b`2 "); 
}else{ 
output(" als `4`bMann`b`2 "); 
} 
$sql = "UPDATE accounts SET sex='".$sex."' WHERE acctid='".$session['user']['acctid']."'"; 
(db_query($sql)); 
output("lächelnd davon."); 
// Routine aus Retitle.php 
$sql = "SELECT name,title,dragonkills,acctid,sex,ctitle FROM accounts WHERE acctid=".$session[user][acctid].""; 
$result = db_query($sql); 
for ($i=0;$i<db_num_rows($result);$i++){ 
$row = db_fetch_assoc($result); 
//if ($i==0) echo "x".nl2br(output_array($titles)); 
$newtitle = $titles[(int)$row['dragonkills']][(int)$row['sex']]; 
if ($row['ctitle'] == "") { 
$oname = $row['name']; 
if ($row['title']!=""){ 
$n = $row['name']; 
$x = strpos($n,$row['title']); 
if ($x!==false){ 
$regname=substr($n,$x+strlen($row['title'])); 
$row['name'] = substr($n,0,$x).$newtitle.$regname; 
}else{ 
$row['name'] = $newtitle." ".$row['name']; 
} 
}else{ 
$row[name] = $newtitle." ".$row['name']; 
} 
} 

output("`@Ändere `^$oname`@ auf `^{$row['name']} `@($newtitle-{$row['dragonkills']}[{$row['sex']}]({$row['ctitle']}`@))`n"); 
if ($session['user']['acctid']==$row['acctid']){ 
$session['user']['title']=$newtitle; 
$session['user']['name']=$row['name']; 
}else{ 
$sql = "UPDATE accounts SET name='".addslashes($row['name'])."', title='".addslashes($newtitle)."', ctitle='".addslashes($newtitle)."' WHERE acctid='{$row['acctid']}'"; 
//output("`0$sql`n"); 
(db_query($sql)); 
} 
} 

// 

addnav("(3)...weiter","landreise.php?op=1",true); 
} 
page_footer(); 
?>