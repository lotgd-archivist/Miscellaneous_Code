
<?php

if (!isset($session)) exit();
switch(e_rand(1,3)){
case 1:
output("`qDu begegnest einem `5Lila Drachen`q. Er lädt dich in seine Höhle zu einem Tee ein. Natürlich gehst du mit, weil du Angst hast. Du verbringst ein paar schöne Stündchen in der Höhle des Drachens, mit Tee trinken und plaudern.");
addnav("Zurück zum Wald","forest.php");
addnews("`q{$session[user][name]} `qwurde vom `5Lila Drachen`q zum Tee eingeladen.");
break;


case 2:
output("`qDu begegnest einem `5Lila Drachen`q. Du schaust ihn an und nuschelst leise`n`QWas für eine Schande für das Geschlecht der Drachen`n`qDas hat er leider gehört. Als er versucht einen Feuerball nach dir zuspucken, kommt nur ein kleiner Windhauch aus seinem Mund. Er läuft rot an und stampft deprimiert den Weg entlang.");
addnav("Zurück zum Wald","forest.php");
addnews("`q{$session[user][name]} `qhat den `5Lila Drachen`q in Verlegenheit gebracht.");
break;



case 3:
output("`qDu begegnest einem `5Lila Drachen`q. Er schaut dich kurz an und meint dann`n`5Guten Morgen!`n`qEr geht weiter. Verwirrt schaust du ihn an und fragst dich, seit wann eine so mächtige Kreatur einem einen Guten Morgen wünscht.");
addnav("Zurück zum Wald","forest.php");
addnews("`qDer `5Lila Drache `qhat {$session[user][name]}`q einen Guten Morgen gewünscht.");
break;
}
?>

