
<?php

/*
*   ___________________________________________________________
*  |                                                           |
*  | RP-Orte für die Waldlichtung: Wasserfall, Tropfsteinhöhle |
*  | Autor: Silva                                              |
*  | Erstellt für Eranya (http://lotgd.hopto.org)              |
*  | Anpassung: Umzug tiefer in den Wald                       |
*  |___________________________________________________________|
*
*/

require_once 'common.php';

// Textfarben
define('DRIPSTONECOLORTEXT','`z');
define('DRIPSTONECOLORBOLD','`F');
define('WATERFALLCOLORTEXT','`ú');
define('WATERFALLCOLORBOLD','`í');

checkday();
addcommentary();

// RP-Ort Tropfsteinhöhle
if ($_GET['op'] == 'dripstone')
{
        page_header('Die Tropfsteinhöhle');
        $tout = "`c`b".DRIPSTONECOLORBOLD."Die Tropfsteinhöhle`b`c`n".DRIPSTONECOLORTEXT."
                 Schnellen Schrittes gehst du hinüber zum Wasserfall und entdeckst sofort den schmalen Sims, der es dir
                 erlaubt, zum Eingang der Höhle zu gelangen. Allerdings sind diese paar Meter ein nasses Vergnügen -
                 als du die Höhle erreichst, ist deine Kleidung klamm, teilweise sogar ganz durchnässt. Der Anblick,
                 der sich dir aber dann bietet, entschädigt dich für alle Unannehmlichkeiten: Die Höhle entpuppt sich als
                 Tropfsteinhöhle, deren Stalagmiten sich mannshoch vor dir auftürmen und deren Stalaktiten tief von der
                 Decke hinabhängen. Manche von ihnen haben sogar den Boden erreicht und dienen so als natürliche Säulen,
                 die wirken, als würden sie die hohe Höhlendecke noch zusätzlich stützen.`n`n";
        $commentary_section = 'dripstone';
        addnav('Zurück');
        addnav('H?Höhle verlassen','waterfall.php');
        addnav('S?Zurück zur Stadt','village.php');
}
// RP-Ort Wasserfall
else
{
        page_header('Der Wasserfall');
        $tout = "`c`b".WATERFALLCOLORBOLD."Der Wasserfall`b`c`n".WATERFALLCOLORTEXT."
                 Du folgst einem schmalen Pfad, der dich zu einem von Efeu überzogenen Berghang führt. Ein kleiner
                 Wasserfall bahnt sich hier seinen Weg durch die Felsen, das Wasser fällt auf halber Strecke
                 kerzengerade hinab in einen kleinen See. Um diesen herum ist der Boden mit Gras und allen möglichen
                 anderen Pflanzensorten bewachsen, erst einige Meter weiter beginnt erneut der Wald.`n
                 Als du dir den Wasserfall näher anschaust, erkennst du verschwommen einen Eingang in der Felswand
                 direkt dahinter. Wahrscheinlich führt dieser zu einer versteckten Höhle.`n`n";
        $commentary_section = 'waterfall';
        addnav('Höhle');
        addnav('H?Zur Höhle','waterfall.php?op=dripstone');
        addnav('Zurück');
        addnav('W?Zurück in den Wald','woods.php');
        addnav('S?Zurück zur Stadt','village.php');
}
// Ausgabe Text und Schreibfeld
output($tout,true);
if (!empty($commentary_section))
{
      viewcommentary($commentary_section,'Sagen',15,'sagt');
}

page_footer();

?>

