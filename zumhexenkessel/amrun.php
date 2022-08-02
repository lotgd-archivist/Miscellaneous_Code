<?php

require_once "common.php";
//require_once "likes.php";
require_once "news.php";

addcommentary();
checkday();

//$codes = "T`Ú`Û`E`t`h`û`&`X`x`Y";
$picture = "Amrun";
//define('TOWN_ID', 0);

//if (isset($_GET['stadt'])) $session['user']['stadt'] = (int)$_GET['stadt'];
//if ($session['user']['stadt'] != TOWN_ID && !empty(TOWN_FILES[$session['user']['stadt']])) redirect(TOWN_FILES[$session['user']['stadt']]);
//$session['user']['stadt'] = TOWN_ID;

//$session['user']['badguy'] = "";
//$session['user']['specialinc'] = "";
//$session['user']['specialmisc'] = "";

page_header("Piratennest von ".TOWNS[TOWN_ID]);
setPageFirstTitle(colorText("Piratennest von ".TOWNS[TOWN_ID], $codes, "fb", 1));
showimage($picture);
setPageDescription(colorText("Die Siedlung, in der du dich befindest, gleicht nichts was du bisher gesehen hast. ".
"Nahe am Ufer gelegen, bildet ein altes Piratenschiff wohl den zentralen Mittelpunkt der kleinen Siedlung. ".
"Du kannst erkennen, dass es mit dicken Bohlen aufrecht gehalten wird und wie eine lange Rampe auf das Deck des Schiffes hinauf führt. ".
"Um das Schiff herum wurden zahlreiche Häuser errichtet, manche so nah am Schiff, dass es aussieht, als würden sie sich daran anlehnen. ".
"Aus einem der windschiefen Hütten klingt das laute Gröhlen Betrunkener, so dass du darauf schließt die örtliche Taverne gefunden zu haben. ".
"Zum Ufer hin erkennst du einige schlichtere Hütten und den Steg, an welchem im Moment allerdings nur eine kleine Nussschale anliegt. ".
"Mit etwas Improvisation und Geschick könnte hier jedoch auch sicherlich ein größeres Schiff festmachen ".
"Zur Rückseite hin wird das Piratennest durch hohe Palisaden gegen den Dschungel geschützt, welcher sich über den Rest der gesamten Insel zu erstrecken scheint. ".
"Am Hauptmast des Piratenschiffs hängt noch immer ein zerfleddertes Segel, das im Wind weht und auf welchem die Piraten immer die aktuellsten Neuigkeiten verkünden.", $codes));

// Besondere Ereignisse
ShowSpecialEvents();

//setLikes();
//getNewsList(TOWN_ID);

output("`n`OWir schreiben den `Y".getgamedate()."`O und es ist `Y".getgametime()."`O Uhr.`0");
output("`n`ODas heutige Wetter: `Y".$settings['weather_0']."`O.`0`n`n");

//viewcommentary("Piratennest von ".TOWNS[TOWN_ID]);

addnav("Dorfrand");
addnav("Palisade","palisade.php");
addnav("Hafen","hafen.php");
addnav("Totenacker","totenacker.php");
addnav("Versteckte Bucht","versteckte-bucht.php");
addnav("Zuckerrohrplantagen","zuckerrohrplantagen.php");

addnav("Dorfmitte");
addnav("Piratenschiff","trainingsschiff.php");
addnav("Schatztruhe","schatztruhe.php");
addnav("Schwarzmarkt","schwarzmarkt.php");
addnav("Taverne zum blutigen Säbel","taverne.php");

addnav("Sonstiges");
if (getAllow("mods_superuser")) addnav("X?Admin Grotte","superuser.php");
addnav(".?Einwohnerregister (OOC)","list.php");
addnav("Tägliche Nachrichten","news.php");
if (getAllow("mods_newday")) addnav("Neuer Tag","newday.php");

page_footer();

?> 