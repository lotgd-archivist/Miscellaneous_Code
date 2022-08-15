
<?php

/*
 * RP-Ort & kleine Spielerei mit Gold
 */

define('WELLCOLORTEXT','`F');

require_once("common.php");
addcommentary();
checkday();

page_header("Der Stadtbrunnen");

addnav('Der Gasse folgen');
addnav('Wohnviertelgasse','alley.php');
addnav('Brunnen');
if ($session['user']['gold']>1) {
    addnav("G?1 Gold hineinwerfen","well.php?op=throwgold");
}
addnav('E?Zur alten Eiche','schatzsuche.php');
addnav('Zurück');
addnav("W?Zurück ins Wohnviertel","houses.php");
addnav("S?Zurück in die Stadt","village.php");
addnav("M?Zurück zum Marktplatz","market.php");

output(WELLCOLORTEXT.'Du schlenderst an den Häusern vorbei und folgst dem gepflasterten Weg, bis dieser sich an
       einer Stelle etwas erweitert und so genug Platz für einen Brunnen bietet. Scheinbar wird diese Stelle
       von den Bürgern der Stadt auch als Treffpunkt genutzt, denn du kannst einige andere dort am Brunnen stehen
       und sich unterhalten sehen. Die Gasse, der du hierher gefolgt bist, geht dabei hinter dem Brunnen weiter
       und führt tiefer ins Wohnviertel hinein.`n`n');

if ($_GET['op']=="throwgold" && !isset($_GET['comscroll']) && $_POST['section']==""){
        output("".WELLCOLORTEXT."Du wirfst eines deiner Goldstücke hinein und zählst die Sekunden bis zum Platsch. Nach `^".(e_rand(1,10)/2)."".WELLCOLORTEXT." Sekunden hörst du es.`n`n");
        $session['user']['gold']--;
}

viewcommentary("well","Mit Umstehenden reden:",25,"sagt");

page_footer();
?>

