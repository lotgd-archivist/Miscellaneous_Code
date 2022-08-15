
<?php
/*
*   _____________________________________________________________
*  |
*  | RP-Ort: Felder & Scheune
*  | Autor: Silva
*  | Texte: Silva, Sefira
*  | Erstellt für Eranya (http://eranya.de/)
*  |_____________________________________________________________
*
*/
require_once 'common.php';
// Konstanten für Farbcodes festlegen
define('FIELDSCOLORTEXT','`F');
define('FIELDSCOLORBOLD','`h');
define('F_BARNCOLORTEXT','`³');
define('F_BARNCOLORBOLD','`;');
define('F_EXCOLORTEXT','`O');
define('F_EXCOLORBOLD','`7');
// Das Übliche (;
checkday();
addcommentary();
page_header('Außerhalb');
// Orte
$filename = basename(__FILE__);
switch($_GET['op'])
{
        // Felder
        case '':
                $out = FIELDSCOLORBOLD."`c`bDie Felder`b`c`n".
                       FIELDSCOLORTEXT."Du lässt das letzte Haus ".(getsetting('townname','Eranya'))."s hinter dir, 
                       passierst die schützende Stadtmauer und
                       folgst dem Weg vor die Tore Eranyas. Viele der ansässigen Bauern haben hier ihre Felder angelegt,
                       auf denen sie alljährlich alle möglichen Sorten an Getreide und Gemüse anpflanzen; entsprechend
                       riecht es hier auch, als würde man die Nase in einen Topf voller Gemüsebrühe stecken.`n".
                       FIELDSCOLORTEXT."Ein Stück weiter vor dir teilt sich der Weg schließlich entzwei: Links führt er
                       als unbefestigter Pfad zu einer nahen Scheune, die wohl einem der hiesigen Bauern gehören muss; rechts
                       verschwindet er wenig später zwischen den Büschen und Bäumen des Waldes. Würdest du dem Verlauf der
                       Stadtmauer folgen, kämst du schnell zum Richtplatz - von dem aus an manchen Tagen das Geschrei der
                       Geier bis zum Stadttor hinüber dringt.`n`n";
                $commentary_section = 'fields';
                addnav('Die Felder');
                addnav('R?Zum Richtplatz',$filename.'?op=execution');
                //addnav('c?Zur Scheune',$filename.'?op=barn');
                addnav('W?In den Wald','forest.php');
                if (getsetting('pvp',1))
        {
                addnav('PvP');
                addnav('+?Spieler töten','pvp.php');
        }
                if ($session['user']['dragonkills']>0)
                {
                        addnav('Sonstiges');
                        addnav('Für länger verreisen','vacation.php');
                }
                addnav('Zurück');
                addnav('S?Zurück in die Stadt','village.php');
                addnav('Logout');
                addnav('-?In den Feldern schlafen','login.php?op=logout',true);
        break;
        // Scheune
        case 'barn':
                $out = F_BARNCOLORBOLD."`c`bDie Scheune`b`c`n".
                       F_BARNCOLORTEXT."Am Rande eines der vielen Felder erkennst du eine alte Scheune, und neugierig
                       lenkst du deine Schritte dorthin. Als du schon ganz nahe bist, kannst du sehen, dass das große,
                       zweiflügelige Scheunentor einen Spalt offen steht, und natürlich nutzt du die Gelegenheit nun.
                       Ein lautes Knarren ertönt, als du das Tor weiter aufschiebst, und du siehst dich erst einmal um,
                       ob nicht von irgendwoher ein aufgebrachter Bauer auftaucht, denn immerhin dringst du hier in
                       fremde Gefilde ein. Aber alles ist ruhig, und so lenkst du deinen Blick ins düstere Innere. Es
                       dauert einen Moment, bis sich deine Augen an das Dunkel gewöhnt haben, aber dann erkennst an
                       einer der Holzwände verschiedene Arbeitsgeräte, eine Sichel, eine Egge, einen Spaten und noch
                       ein paar Dinge, die du nicht gleich identifizieren kannst. All die Werkzeuge sehen noch recht
                       gut erhalten aus, was darauf schließen lässt, dass sie noch benutzt werden. Mutiger geworden
                       gehst du nun weiter in die Scheune hinein. Hinten in einer Ecke steht ein alter Karren, an einem
                       Balken, daneben hängt das Geschirr für die Zugtiere. Ein Stück weiter lehnt eine Leiter, und als
                       du den Blick an dieser hinauf streifen lässt, bemerkst du, dass es dort oben noch einen
                       Zwischenboden zu geben scheint, der wohl für die Heulagerung genutzt wird.`n`n";
                $commentary_section = 'fields_barn';
                addnav('Zurück');
                addnav('Scheune verlassen',$filename);
        break;
        // Richtplatz
        case 'execution':
                $out = F_EXCOLORBOLD."`c`bDer Richtplatz`b`c`n".
                       F_EXCOLORTEXT."Mit bedächtigen Schritten näherst du dich dem Richtplatz. Wahrlich, dies ist alles andere als ein schöner Ort,
                       an dem schon die Stimmung allein verrät, was hier von Zeit zu Zeit auf der Tagesordnung steht. Wer hier als Verbrecher
                       hergeschleift wird, erlebt den nächsten Sonnenaufgang nicht mehr. Eine traurige Wahrheit, die auch schon so manch Unschuldiger
                       hat einsehen müssen. Langsam passierst du den hoch in den Himmel ragenden Galgen und gehst hinüber zum Scheiterhaufen, um den
                       herum das Gras schon längst aufgehört hat, durch die dicke Ascheschicht hindurch zu wachsen. Ein kleiner Blumenstrauß liegt
                       etwas entfernt auf der dreckigen Erde. Wohl ein Andenken an einen geliebten Menschen, der nun nicht mehr unter den
                       Lebenden weilt.`n`n";
                $commentary_section = 'fields_execution';
                addnav('Zurück');
                addnav('z?Richtplatz verlassen',$filename);
                addnav('S?Zurück in die Stadt','village.php');
        break;
        // Debug
        default:
                $out = "`^Hm, diese Op: ".$_GET['op']." gibt es wohl nicht. Schicke diesen Satz bitte mit Angabe
                        des Links oben in deiner Adressleiste an einen Admin.";
                addnav('Zurück',$filename);
        break;
}
// Text- und Schreibfeldausgabe
output($out);
if (!empty($commentary_section)) {viewcommentary($commentary_section,'Sagen',20,'sagt');}
// page footer
page_footer();
?>

