
<?php
/******
 * ballsaal.php - RP-Ort
 * Autor: Silva
 * erstellt für Eranya (http://eranya.de/)
 ******/
require_once 'common.php';
// Das Übliche
page_header('Der Ballsaal');
addcommentary();
checkday();
$filename = basename(__FILE__);
// Textfarben
define('BALLROOMCOLORBOLD','`h');
define('BALLROOMCOLORTEXT','`+');
// Beschreibung
switch($_GET['op'])
{
    // Ballsaal
    case '':
        $tout = BALLROOMCOLORBOLD."`c`bDer Ballsaal`b`c`n".BALLROOMCOLORTEXT."
                 Ein großer Saal ist für das Fest geschmückt worden, Kübel mit hellen Blumen stehen aufgereiht an der
                 Wand. Die hohen Fenstertüren stehen offen und geben den Weg auf den weitläufigen Balkon frei. Von
                 dort aus hört man das Orchester, das drinnen die Tanzenden begleitet, kaum.`n
                 Die Tische für jene, die sich am Buffet bedient haben, stehen im hinterem Teil des Saales und sind
                 mit weißen Laken überdeckt und auch hier finden sich Blumenvasen wieder.";
        $commentary_section = 'ballroom';
        addnav('Der Ballsaal');
        addnav('Auf den Balkon',$filename.'?op=balcony');
        addnav('Zurück');
        addnav('Zum Ausgang','dorfamt.php');
        addnav('In die Stadt','village.php');
    break;
    // Balkon
    case 'balcony':
        $tout = BALLROOMCOLORBOLD."`c`bAuf dem Balkon`b`c`n".BALLROOMCOLORTEXT."
                 Als du durch die Fenstertüren auf den Balkon trittst, ist es deutlich dunkler und leiser, als im
                 Ballsaal. Draußen kann man die Sterne beobachten, auf die Dächer der Stadt herabblicken und die Ruhe - eventuell
                 die Zweisamkeit - genießen.";
        $commentary_section = 'ballroom_balcony';
        addnav('Zurück ins Haus',$filename);
    break;
    // Debug
    default:
        $tout = "Hm, hier bist du wohl falsch. Sende folgenden Satz bitte per Anfrage ans Team:`n
                 op: ".$_GET['op']." nicht vorhanden; ".$filename.".";
        addnav('Zurück',$filename);
    break;
}
// Abschluss
output('`n`n'.$tout,true);
if(!empty($commentary_section)) {output('`n`n');viewcommentary($commentary_section,'Hinzufügen',15,'sagt');}
page_footer();
?>

