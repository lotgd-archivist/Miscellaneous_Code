
<?php
require_once "common.php";

page_header("Land der Schatten");
if ($session[user][locate]!=24){
    $session[user][locate]=24;
    redirect("shades.php");
}
addcommentary();
checkday();

if ($session['user']['alive']) redirect("village.php");
if ($session[user][acctid]==getsetting("hasegg",0)) addnav("Benutze das goldene Ei","newday.php?resurrection=egg");
output("`\$Du wandelst jetzt unter den Toten, du bist nur noch ein Schatten. Überall um dich herum sind die Seelen der in alten Schlachten und bei  
gelegentlichen Unfällen gefallenen Kämpfer. Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden hat.`n");
output("Du bemerkst eine ruhelos umherstreifende Seele, die wie in Trance immer wieder `6".getgametime()."`$ vor sich hin murmelt.`n`n");
output("Sie flüstern ihre Qualen und plagen deinen Geist mit ihrer Verzweiflung:`n");
viewcommentary("shade","Verzweifeln",25,"jammert");
addnav("Der Friedhof","graveyard.php");
addnav("Aktualisieren","shades.php");

addnav("Zurück zu den News","news.php");
if ($session[user][superuser]>=2){
  addnav("Admin Grotte","superuser.php");
}
page_footer();
?>


