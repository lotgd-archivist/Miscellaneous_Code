<?php

/*Diesen Text nicht verändern
  Mod geschrieben von Andarrius http://www.dardanos-logd.de
  Bei Fragen, meldet euch einfach, soweit ich kann werde ich weiter helfen.
  4.11.2008
  Andarrius



SQL - Befehle
ALTER TABLE `accounts` ADD `aufbauii` VARCHAR( 25 ) NOT NULL ;
ALTER TABLE `accounts` ADD `loeschen` VARCHAR( 25 ) NOT NULL ;


Einbau anleitung:
öffne configuration.php

suche:
        "avatare"=>"Erlaube den Spielern Avatare zu verlinken,bool",

Füge dannach ein:
        "Server Event by Andarrius,title",
        "feuer"=>"Eimer Wasser um Feuer zu löschen,int",
        "stein"=>"Steine die zum Wiederaufbau benötigt werden,int",
speicher und Hochladen


 öffne village.php

suche:
addnav("Wohnviertel","houses.php");  ODER addnav("Wohnviertel","nhouses.php");

ersetzte durch:
if ($stein<=1){addnav("Wohnviertel","houses.php");}else{
if ($feuer<=1){addnav("Wohnviertel aufbau","wvfeuer.php?op=aufbau");
                }else{
                addnav("FEUER!!!","wvfeuer.php");
                }}


 ODER (Je nach dem was ihr für ein Wohnviertel ihr habt)
ersetzte durch:
if ($stein<=1){addnav("Wohnviertel","houses.php");}else{
if ($feuer<=1){addnav("Wohnviertel aufbau","wvfeuer.php?op=aufbau");
                }else{
                addnav("FEUER!!!","wvfeuer.php");
        }}


suche (den ersten addnav auf eurem DP)

Füge davor ein

$feuer = getsetting("feuer","0");
$stein = getsetting("stein","0");

Speichern und Hochladen.


Achtet auf den Link zu eurem Wohnviertel, nhouse.php oder house.php!!!!
speicher und Hochladen

öffne newday.php

suche:
if (count($session['user']['dragonpoints']) <$session['user']['dragonkills']&&$_GET['dk']!=""){

Füge davor ein:
$session['user']['loeschen']=0;
$session['user']['aufbauii']=0;
speicher und Hochladen


Ladet die datei wvfeuer.php in euer root Verzeichniss hoch.

Wenn ihr das Feuer anpassen wollt, nehmt in der Village.php statt das Wohnviertel etwas beliebiges, und ändert die output befehle in der wvfeuer entsprechend um, und schon habt ihr euer Feur z.B. in der Handelsgasse.


Und jetzt viel spaß mit dem Event, es kann ganz einfach in den Spieleinstellungen gestartet werden.
und hier könnt ihr euch austoben ;-)
Andarrius 03.05.2010


Update:   Wenn ihr wollt das das standart Gewitter ein Feuer auslösen kann, so müsst ihr noch folgendes tun:

öffne configuration.php

suche "feuer"=>"Eimer Wasser um Feuer zu löschen,int",

 füge DAVOR ein:
        "feuerevent"=>"Soll ein Gewittersturm das Feur auslösen können?,enum,0,Nein,1,Ja",
speichern und hochladen


öffne setnewday.php
suche           $clouds="Gewittersturm";

füge DANNACH ein:
  Erweiterung Feuer im Wohnviertel
      if (($fevent)==1){
            if (e_rand(1,100)>=90){
      savesetting("feuer",$feuer = 20);
          savesetting("stein",$stein = 45);
                addnews("`GEin Gewittersturm zieht heran, Blitze schlagen ins Wohnviertel ein und es fängt an zu Brennen!!!");
                }}else{addnews("`3Ein Gewittersturm zieht über das Dorf, richtet aber keinen Schaden an.");}

  Ende Erweiterung Feuer im Wohnviertel

suche: $clouds="Wechselhaft und kühl, mit sonnigen Abschnitten";

füge VOR  switch(e_rand(1,9)){
        case 1:
        $clouds="Wechselhaft und kühl, mit sonnigen Abschnitten";


 DAS HIER EIN  -->    $fevent = getsetting("feuerevent");

Speichern und Hochladen.


Nun könnt ihr über die Spieleinstellungen dem System sagen ob ein Feuer bei Gewitter aus brechen kann, oder nicht.



    ########################################################################
    ########################################################################
    ###                                                                  ###
    ###  ####    ######  ######  ####    ######  ###   #  ######  #####  ###
    ###  ## ##   ##  ##  ##  ##  ## ##   ##  ##  ####  #  ##  ##  ##     ###
    ###  ##  ##  ######  #####   ##  ##  ######  ## ## #  ##  ##  #####  ###
    ###  ## ##   ##  ##  ##  ##  ## ##   ##  ##  ##  ###  ##  ##     ##  ###
    ###  ####    ##  ##  ##  ##  ####    ##  ##  ##   ##  ######  #####  ###
    ###                                                                  ###
    ########################################################################
    ########################################################################  //By Andarrius ©Andarrius2010

*/
require_once "common.php";
addcommentary();
checkday();


page_header("Das Wohnviertel brennt!!!");


switch($_GET['op'])
{
case "":
$feuer = getsetting("feuer","");
output("völlig hecktisch rennst du, nachdem du die Rauchsäulen bemerkt hast in das Wohnviertel. Alles, aber auch alles steht hier in Flammen, und es wird sicher nicht leicht werden all diese Flammen zu löschen. Du blickst dich um und siehst einen Eimer, du weiß noch ganz genau der Brunnen auf dem Dorfplatz bietet immer ausreichend Wasser. Eilig schnappst du dir also den Eimer und rennst los um Wasser zu holen.");
output("Es müssen noch ".$feuer." Eimer Wasser ins Feuer gegossen werden.");
if ($session['user']['loeschen']>=5){output("`n`n`c`4`bDeine Lungen sind voller Rauch, versuchs lieber Morgen wieder.`c`b");}
output("`n`n`n`n`n`n`n`n");
if ($session['user']['loeschen']<=4){
if (($feuer)>0) {
addnav("Löschen","wvfeuer.php?op=loeschen");}else{addnav("Wohnviertel Aufbauen","wvfeuer.php?op=aufbau");}}
addnav("Zurück ins Dorf","village.php");
        addcommentary();
        viewcommentary("wv-feuer","`vHinzufügen",15);
break;

case "loeschen":
$feuer = getsetting("feuer","");
output("du schnappst den Eimer und kippst das Wasser ins Feuer....Du musst jedoch Feststellen das es nicht wirklich viel gebracht hat, Du wirst noch sehr viel Hilfe brauchen.");
        addnews("`8".$session[user][name]."`8 hilft das Feuer im Wohnviertel zu bekämpfen!!!");
$session['user']['turns']--;
$session['user']['loeschen']++;
if($feuer==1){
            savesetting("feuer",'0');
        }else{
            savesetting("feuer",$feuer-1);
        }

if (($feuer)>0){addnav("zurück zum Feuer","wvfeuer.php");
addnav("zurück ins Dorf","village.php");}

break;

case "aufbau":
$stein = getsetting("stein","");
output("Du trittst näher an das heran was die Flammen über gelassen haben, es ist vieles zerstört worden, doch es kann wieder aufgebaut werden. Du lässt kurz den Blick schweifen um einen Überblick zu bekommen und erahnst das noch ".$stein. " steine benötigt werden ehe das Wohnviertel wieder das ist, was es einmal war. Neuen mutes und voller Kraft machst du dich an die Arbeit.");
if ($session['user']['aufbauii']>=10){output("`n`n`c`4`bDu bist zu erschöpft um heute noch Steine zu schleppen, versuchs lieber Morgen wieder.`c`b");}
output("`n`n`n`n`n`n`n`n");

if ($session['user']['aufbauii']<=9){
if (($stein)>0) {
addnav("Steine heran holen","wvfeuer.php?op=steine");}else{addnav("Wohnviertel","houses.php?location=1");}}
addnav("Zurück ins Dorf","village.php");


        viewcommentary("wv-aufbau","`vHinzufügen",25);
break;

case "steine":
$stein = getsetting("stein","");
output("Mit einem weiteren Stein stärkst du das neue Mauerwerk des Wohnviertels, und wieder einen schritt weiter im Aufbau des Wohnviertels.");
        addnews("`8".$session[user][name]."`8 hilft das Wohnviertel wieder aufzubauen!!!");
$session['user']['turns']--;
$session['user']['aufbauii']++;
if($stein==1){
            savesetting("stein",'0');
        }else{
            savesetting("stein",$stein-1);
        }

if ($stein>0){
addnav("zurück zum Aufbau","wvfeuer.php?op=aufbau");
addnav("zurück ins Dorf","village.php");
}else{addnav("Ins Wohnviertel","houses.php?location=1");}
break;

        }
page_footer();
?> 