
<?php
/* * *
 * Eranyas Schmiede
 * vorerst nur einfacher RP-Ort; Erweiterung möglich
 * erstellt für http://eranya.de
 * * */
require_once('common.php');
page_header('Eranyas Schmiede');
addcommentary();
// Farben
define('BLACKSMITHCOLORHEAD','`á');
define('BLACKSMITHCOLORTEXT','`S');
define('BLACKSMITHCOLORTALK','`v');
define('BLACKSMITHNAME','`oL`ãé`vo`án`Sa`Îr `ÂEi`Îs`Se`án`vh`ãu`of');
// end
$str_filename = basename(__FILE__);
$str_tout = '';
$str_comsection = '';
$op = (isset($_GET['op']) ? $_GET['op'] : '');
switch($op)
{
        case '':
                $str_tout .= BLACKSMITHCOLORHEAD."`c`bEranyas Schmiede`b`c`n
                              ".BLACKSMITHCOLORTEXT."Die Luft ist fast unerträglich heiß in dem großen, einen Raum, in dem die Schmiede Eranyas
                              untergebracht worden ist. Knisternd schickt der riesige, steinerne Ofen die Hitze in jede noch so kleine Ecke; wenn man
                              direkt davor steht, flimmert sogar die Luft. Ein großer Amboss und ein Tisch mit einer ganzen Reihe von Werkzeugen steht
                              nicht weit entfernt, weiter hinten auch ein paar seltsam wirkende Gerätschaften, deren Nutzen sich allerdings auf den
                              ersten Blick allein nicht erschließen lassen. Da wirst du wohl schon den Meister des Hauses,
                              ".BLACKSMITHNAME.BLACKSMITHCOLORTEXT.", fragen müssen - oder einen seiner Gehilfen.`n";
                $str_comsection = 'blacksmith_entrance';
                addnav('Die Schmiede');
                addnav('N?In den Nebenraum',$str_filename.'?op=backroom');
                knappentraining_link('blacksmith');
                addnav('Zurück');
                addnav('W?Zum Waffenladen','weapons.php');
                addnav('R?Zum Rüstungsladen','armor.php');
                addnav('M?Zum Marktplatz','market.php');
                
        break;
        case 'backroom':
                $str_tout .= BLACKSMITHCOLORHEAD."`c`bEranyas Schmiede - Nebenraum`b`c`n
                              ".BLACKSMITHCOLORTEXT."Der - um Längen kleinere - Raum grenzt direkt an die große Schmiedehalle an und ist entsprechend gewärmt, wenn auch bei
                              Weitem nicht ganz so heiß temperiert wie diese. Hier werden wohl diverse Feinarbeiten geleistet - oder auch mal Pause
                              gemacht von der anstrengenden Arbeit, wie der Tisch mit seinen willkürlich darum herum verteilten Stühlen zeigt.`n";
                $str_comsection = 'blacksmith_backroom';
                addnav('Zurück');
                addnav('Z?Zum Hauptraum',$str_filename);
                addnav('M?Zum Marktplatz','market.php');
        break;
        default:
                $str_tout .= '`&Oh, wie hast du dich denn hierher verlaufen? Scheint ein Fall für die Käfersammler zu sein. Sende bitte folgende Nachricht
                              via Anfrage an das Adminteam:`n
                              `n
                              `^fehlende op: '.$op.' in '.$str_filename;
                addnav('Zurück',$str_filename);
        break;
}
// Ausgabe
output($str_tout,true);
if(!empty($str_comsection)) viewcommentary($str_comsection,'Sagen',15,'sagt');
// end
page_footer();
?>

