
<?php

require_once "common.php";

page_header("Land der Schatten");

addcommentary();

$session['user']['standort'] = "in den Schatten";

if ($session['user']['alive']) redirect("village.php");
if ($session['user']['acctid']==getsetting("hasegg",0)) addnav("Benutze das goldene Ei","newday.php?resurrection=egg");

addnav("Der Friedhof","graveyard.php");
addnav("Zum Höllenrad","hoellenrad.php");
addnav("Die Hölle","thehell.php");
addnav("Sonstiges");
addnav("Einwohnerliste","list.php?ret=shades");
addnav("In Ruhmeshalle spuken","heldengasse_ruhmeshalle.php");
addnav("Zurück zu den News","news.php");


output("`\$Du wandelst jetzt unter den Toten, du bist nur noch ein Schatten. Überall um dich herum sind die Seelen der in alten Schlachten und bei  
gelegentlichen Unfällen gefallenen Kämpfer. Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben.`n`n
Im Dorf dürfte es jetzt etwa `^".getgametime()."`\$ sein, aber hier herrscht die Ewigkeit und Zeit gibt es mehr als genug.`n`n
Die verlorenen Seelen flüstern ihre Qualen und plagen deinen Geist mit ihrer Verzweiflung:`n");

if($session['user']['prefs']['comlim']){
    viewcommentary("shade","Hinzufügen:",$session['user']['prefs']['comlim']);
}else{
    viewcommentary("shade","Hinzufügen:");
}

checkday();


page_footer();

?>

