
<?php

/*
*   _________________________________________________________
*  |                                                         
*  | RP-Orte: Hafen, Strand + Erweiterung, Leuchtturm
*  | Zusatz: Hafenkneipe (ähnlich dem Eberkopf);             
*  | Autor: Silva                                            
*  | Erstellt für Eranya (http://eranya.de/)            
*  |_________________________________________________________
*
*/

require_once 'common.php';
require_once(LIB_PATH.'board.lib.php');

define('HARBORCOLORTEXT','`Ô');
define('HARBORCOLORBOLD','`&');
define('HARBORCOLORTIME','`&');
define('HARBORCOLORWEATHER','`&');
define('PUBCOLORTEXT','`w');
define('PUBCOLORBOLD','`j');
define('PUBCOLORTIME','`Ô');
define('PUBCOLORTALK','`Y');
define('BEACHCOLORTEXT','`h');
define('BEACHCOLORBOLD','`I');
define('DURDLEDOORCOLORTEXT','`=');
define('DURDLEDOORCOLORBOLD','`°');
define('PIERCOLORTEXT','`o');
define('PIERCOLORBOLD','`s');
define('SEACOLORTEXT','`ú');
define('SEACOLORBOLD','`í');
define('LIGHTHOUSECOLORTEXT','`p');
define('LIGHTHOUSECOLORBOLD','`è');
define('KONTORCOLORTEXT', '`t');
define('KONTORCOLORBOLD', '`Y');

$commentary_nocuts = false;

checkday();
addcommentary();

switch($_GET['op'])
{
        // Hafen
        case '':
                $show_invent = true;
                page_header('Der Hafen');
                $tout = HARBORCOLORBOLD."`c`bDer Hafen`b`c`n".HARBORCOLORTEXT."
                        Das Geschrei von Möwen empfängt dich, als du dich dem Hafen ".getsetting('townname','Eranya')."s
                        näherst. Er wurde in einer kleinen Bucht errichtet, die inmitten der sonst steil abfallenden Kliffküste sichelförmig ins
                        Landesinnere ragt. Sie bietet jenen Schiffen Schutz, die gerade an den Piers vor Anker liegen und so den Launen
                        des Meeres zeitweise zu entkommen gedenken, um ihre Waren zu entladen oder neue in sich aufzunehmen.
                        Entsprechend geschäftig ist das Treiben an diesem Ort, wo Seemänner und Geschäftsleute aller Art
                        zusammen kommen, um möglichst gewinnbringende Geschäfte abzuwickeln.`n
                        ".HARBORCOLORTEXT."Ein Stück weit vom Kai entfernt zieht sich ein kleiner Streifen Sandstrand bis zu
                        einer felsigen Erhöhung, auf der sich ein Leuchtturm majestätisch in den Himmel reckt. Dahinter erstrecken sich 
                        glitzernd die tiefblauen Weiten des Meeres.`n
                        Eine breite, etwa brusthohe Mauer führt den gesamten Kai entlang; dahinter geht es steil bergab, bis schließlich das Meer seinen Platz einfordert.
                        Die Steine der Mauer sind an vielen Stellen moosig und hier und dort auch rissig. Dennoch kann man das Zeichen gut erkennen, welches in
                        regelmäßigen Abständen in sie eingeritzt worden ist: ein Anker, den eine Welle umkreist. In der Nähe des Kais steht außerdem ein seltsam leuchtender
                        Felsen, auf dem ständig neue Nachrichten aufflackern,
                        wie von Zauberhand:`0";
                $sql = "SELECT * FROM news ORDER BY newsid DESC LIMIT 1";
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $tout .= '`n`n`c`i'.$row['newstext'].'`i`c`n';
                if (getsetting('activategamedate','0')==1) $tout .= HARBORCOLORTEXT.'Wir schreiben den '.HARBORCOLORTIME.''.rpdate().''
                                                                    .HARBORCOLORTEXT.' im Zeitalter des Drachen.`n';
                $w = get_weather();
                $tout .= HARBORCOLORTEXT.'Die magische Sonnenuhr zeigt '.HARBORCOLORTIME.''.date('G:i').''.HARBORCOLORTEXT.'. Das heutige Wetter: '
                         .HARBORCOLORWEATHER.''.$w['name'].''.HARBORCOLORTEXT.'.`n`n';
                         
                // Zusatz v. Zephyr - Überschwemmung des Hafens
                $tout .= '`c~~~`c`n`ÄDoch von dem sicheren Hafen ist nun nicht mehr viel zu erkennen. Nach einer dramatischen Katastrophe, die unzählige Menschenleben forderte, 
                ist das rege Treiben verstummt: Eine Flutwelle, so hoch, dass keiner einen Zweifel daran hegt, sie wäre keines natürlichen Ursprungs, hat diesen Teil der Stadt überschwemmt.
                Die Gebäude, die unmittelbar von den Wassermassen getroffen wurden, sind nun unbewohnt und stark beschädigt. Einige Wenige versuchen sich an den Aufräumarbeiten. 
                Der Kai ist übersät mit Kerzen und Blumen, die als Andenken an die vielen Toten aufgestellt wurden. Der Handelsverkehr ist vorerst zum Erliegen gekommen, 
                denn die Anlegemöglichkeiten der Schiffe sind mit dem Rückgang des Wassers in den Tiefen des Meeres verschwunden.';
                $tout .= '`n`n`n';
                $commentary_section = 'harbor';
                $commentary_nocuts = true;
                addnav('Hafen');
                addnav('P?Joes Pub','harbor.php?op=pub');
                // Schnapper Mod by Romulus
                if ($_GET['op']!='schnapper')
                {
                    if (e_rand(1,10)<=3)
                    {
                        addnav('c?Schnapper, der Händler','schnapper.php');
                    }
                }
                addnav('H?Handelsschiff','v_ship.php');
                addnav('F?Fischhändler','fishmonger.php');
                addnav('d?Handelskontor', 'harbor.php?op=kontor');
                addnav('e?Zu den Piers','harbor.php?op=pier');
                /*include_once(LIB_PATH.'profession.lib.php');
                addnav('Städtische Ämter');
                addnav($profs[PROF_DDL_CAPTAIN][3].$profs[PROF_DDL_CAPTAIN][4].' Hafenwacht`0','expedition.php');*/
                // end
                addnav('Umgebung');
                addnav('t?Zum Strand','harbor.php?op=beach');
                addnav('L?Zum Leuchtturm','harbor.php?op=lighthouse');
                addnav('Wohnviertel');
                addnav('o?Ins Wohnviertel','houses.php');
                addnav('g?Zur Wohnviertelgasse','alley.php');
                addnav('Sonstiges');
                addnav('i?Einwohnerliste','list.php');
                addnav('N?Neuigkeiten','news.php');
                addnav('Wegweiser','guide.php');
                // Link zu ehemaligen Hafenwacht-Aktionen
                if($session['user']['expedition'] == 1 || su_check(SU_RIGHT_DEBUG)) {
                    addnav('Besonderes');
                    addnav('Erkundung','expedition.php?op=explore');
                    addnav('Schatzsuche','expedition.php?op=search');
                    addnav('Küste auskundschaften','expedition.php?op=claim');
                }
                addnav('Zurück');
                addnav('S?Zum Stadtplatz','village.php');
                addnav('M?Zum Marktplatz','market.php');
                if(su_check(SU_RIGHT_GROTTO))
                {
                        addnav('Admin-Grotte');
                        addnav('X?`bAdmin-Grotte`b','superuser.php');
                }
        break;
        // Pier
        case 'pier':
                page_header('Am Pier');
                $tout = PIERCOLORBOLD."`c`bAm Pier`b`c`n".PIERCOLORTEXT."
                            Du schlenderst am Kai entlang und biegst dann ab zu einem der Piers, die als Anlegestellen für die
                            Schiffe dienen. Dabei ziert jeden Pfahl, um welchen die faustdicken Taue geschnürt werden, dasselbe Zeichen:
                            ein Anker, den eine Welle umkreist. Auch heute sind ein paar Schiffe dort festgetaut - und vom kleinen Boot bis hin zum prächtigen
                            Segelschiff ist alles dabei. Beeindruckt betrachtest du sie und siehst auch hin und wieder zu den
                            Möwen, die um die hohen Masten kreisen oder sich am Ende der Piers sammeln, um im Wasser nach Fischen
                            Ausschau zu halten.`n`n";
                $commentary_section = 'pier';
                /*addnav('Aufs Meer hinaus');
                addnav('In See stechen','harbor.php?op=sea');*/
                addnav('Zurück');
                addnav('Zurück zum Hafen','harbor.php');
        break;
        // Meer
        case 'sea':
                page_header('Auf hoher See');
                $tout = SEACOLORBOLD."`c`bAuf hoher See`b`c`n".SEACOLORTEXT."
                        Blau, nur Blau, so weit das Auge reicht. Bis zum Horizont erstrecken sich die Wassermassen, die gleichzeitig mehrere Hundert Meter in die
                        Tiefe gehen - ein schweigendes, nasses Grab für die, die sich das Meer bereits genommen hat. Einzig die Möwen, die hin und wieder bis zu
                        diesem Punkt fliegen, sind ein Lichtblick für müde Seefahrer, denn sie bedeuten Land. Land, das sich wenige Dutzend Kilometer entfernt
                        befindet - mitsamt einer Handelsstadt, deren Hafen Sicherheit verspricht.`n`n";
                $commentary_section = 'meer';
                addnav('Zurück');
                addnav('Zum Pier','harbor.php?op=pier');
                addnav('Zurück zum Hafen','harbor.php');
        break;
        // Hafenkneipe
        case 'pub':
                page_header('Joes Pub');
                switch($_GET['what'])
                {
                        // Kneipe Eingang
                        case '':
                                /*if (e_rand(1,20)==1) //Zufallsevent Prügelei
                                {
                                        $badguy = array(
                                        "creaturename" => 'Rüder Pirat',
                                        "creatureweapon" => 'harter Faustschlag',
                                        "creaturelevel" => $session['user']['level'],
                                        "creatureattack" => $session['user']['attack']+1,
                                        "creaturedefense" => $session['user']['defence'],
                                        "creaturehealth" => $session['user']['maxhitpoints']
                                        );
                                        $session['user']['badguy'] = $badguy;
                                        $session['user']['badguy']=createstring($badguy);
                                        output(PUBCOLORTEXT.'Direkt neben dir bricht ein Streit zwischen einer leichten Dame und einem Kerl mit Augenklappe los.
                                                Letzterer versetzt der Frau einen rüden Stoß und hebt schon die Hand, um ihr augenscheinlich ins Gesicht zu
                                                schlagen. Gerade noch erkennst du am Gürtel einen kleinen Totenkopf. Ein Pirat! Willst du der Dame zu Hilfe eilen?');
                                        //addnav('v?Den Kerl verprügeln','harbor.php?op=fight');
                                        addnav('k?Besser keinen Streit','harbor.php?op=pub');
                                } else {*/
                                        output(PUBCOLORBOLD."`c`bJoes Pub`b`c`n".PUBCOLORTEXT."
                                                Joes Pub entpuppt sich als rustikale Kneipe, die vor allem den sich vorübergehend in
                                                Eranya aufhaltenden Seemännern als Ort der Entspannung dient. Viele von ihnen sitzen
                                                an runden Tischen zusammen und erzählen über ihre letzten Fahrten und Abenteuer, was hin
                                                und wieder zu dröhnendem Gelächter oder dem Schlag einer Faust auf den Tisch führt. Auch
                                                werden Meinungsverschiedenheiten lautstark ausgetragen - doch wagt es keiner, im Pub
                                                des alten Joe eine Prügelei anzufangen.`n
                                                ".PUBCOLORTEXT."Über dem Regal hinter dem Tresen zeigt eine Uhr die aktuelle
                                                Uhrzeit an: ".PUBCOLORTIME.date('G:i').PUBCOLORTEXT.".");
                                        $commentary_section = 'joes_pub';
                                        output("`n`n");
                                        addnav('Joes Pub');
                                        addnav('Zum Tresen','harbor.php?op=pub&what=old_joe');
                                        addnav('Besonderes');
                                        addnav('Sudoku','sudoku2.php');
                                        addnav('Hasenjagd','bunnyhunt.php');
                                        if($session['user']['schillerstr'] == 'joe') {
                                            addnav('Glaskugel-Quest');
                                            addnav('Joe nach dem Piraten fragen','quest_glaskugel.php?op=joe');
                                        }
                                        addnav('Zurück');
                                        addnav('Pub verlassen','harbor.php');
                                //}
                        break;
                        // Old Joe
                        case 'old_joe':
                                $tout = PUBCOLORTEXT."Du näherst dich dem Tresen und lässt dich auf einen der Hocker
                                        nieder. Dir gegenüber trocknet der alte Joe gerade einen der abgespülten
                                        Bierhumpen ab und mustert dich, als du dich mit den Unterarmen auf dem
                                        Tresen aufstützt. \"".PUBCOLORTALK.($session['user']['sex']?'N Mädel in meinem
                                        Pub? Das ist selten':'Moin, Bursche').PUBCOLORTEXT."\", begrüßt er dich und
                                        grinst dich an. \"".PUBCOLORTALK."Was darf's'n sein?".PUBCOLORTEXT."\"`n`n";
                                $commentary_section = 'joes_pub_tresen';
                                addnav('Zurück');
                                addnav('Zurück','harbor.php?op=pub');
                                // kommt noch
                                // addnav('Poker','poker.php');
                        break;
                }
        break;
        // Strand
        case 'beach':
                page_header('Am Strand');
                $tout = BEACHCOLORBOLD."`c`bAm Strand`b`c`n".BEACHCOLORTEXT."
                        Du lässt den Hafen hinter dir und folgst dem Weg hinab zum Strand. Schon bald wird der
                        grasbewachsene Boden von feinkörnigem, weichen Sand abgelöst und ist nur noch hier und da von
                        einzelnen Grasbüscheln gespickt. Überall lassen sich kleine Muscheln finden, die wohl von den
                        ständig heranrollenden Wellen an Land gespült worden sind. Auch kleine Krebse suchen sich
                        hin und wieder ihren Weg über den Sand - doch sind sie außer dir wohl die einzigen Lebewesen,
                        die es an dieses Fleckchen Erde verschlagen hat.`n`n";
                $commentary_section = 'beach';
                /*addnav('Strand');
                addnav('Zur Felsenbrücke','harbor.php?op=durdledoor');*/
                addnav('Schatzsuche');
                addnav('Nach Strandgut suchen','beach.php');
                addnav('Zurück');
                addnav('H?Zurück zum Hafen','harbor.php');
        break;
        // Felsenbrücke
        case 'durdledoor':
                page_header('Die Felsenbrücke');
                $tout = DURDLEDOORCOLORBOLD."`c`bDie Felsenbrücke`b`c`n".DURDLEDOORCOLORTEXT."
                        Du wanderst eine Weile am Strand entlang. Mehr und mehr rücken dabei die für diese Gegend typischen Felsenhänge
                        näher an das Meer heran, und auch der Sand unter deinen Füßen wird grobkörniger, sodass du nicht mehr bei jedem Schritt
                        einsinkst.`n
                        Schließlich tritt das abrupte Ende des Strands in dein Sichtfeld: Eine hohe Felswand schneidet den Strandweg und reicht
                        mehrere Dutzend Meter weit ins Wasser hinein. Ihre vom Meer umschlossene Spitze ist dabei geformt wie ein Haken, den man
                        in den Boden geschlagen hat. Ob das wohl das Werk der Wellen ist, die sich immerfort sanft an dem grauen Gestein brechen?`n`n";
                $commentary_section = 'felsenbruecke';
                addnav('Zurück');
                addnav('H?Zurück zum Hafen','harbor.php');
        break;
        // Leuchtturm
        case 'lighthouse':
                page_header('Der Leuchtturm');
                $tout = LIGHTHOUSECOLORBOLD."`c`bDer Leuchtturm`b`c`n".LIGHTHOUSECOLORTEXT."
                Ein schmaler Pfad führt vom Hafen weg, am Strand entlang und über die Klippen. 
                Schon aus der Ferne ist zu erkennen, wohin er führt: Ein alter Leuchtturm thront auf einer felsigen Erhöhung. 
                Man sagt, dass er eines der ersten Gebäude war, die hier in Eranya errichtet wurden, damit die Seefahrer 
                den Weg in das kleine Dorf am Meer auch sicher finden. Noch heute leuchtet jede Nacht ein helles Licht in der Kuppel, 
                das die Position der Küstenlinie zeigt. Möwen kreisen stets über diesem Ort, denn er ist von einem Mann bewohnt, 
                der in alter Tradition das Feuer immer dann entzündet, wenn Tageszeit oder Wetter es verlangen. 
                Ein Gemüsegarten schmiegt sich an den Rand des hohen Gebäudes. Die Pflanzen sind klein und wirken etwas zu trocken, 
                obwohl sich beim Stecken Mühe gegeben wurde. Die Tür ist verwittert, aber fest verschlossen. Der Turm selbst scheint 
                drei oder vier Etagen zu besitzen  Von außen ist das nicht gut zu erkennen. In einer Spirale ziehen sich winzige Fenster 
                um das Gemäuer, deren Scheiben so von Salz verkrustet sind, dass ein Blick ins Innere kaum möglich ist.`n`n";
                $commentary_section = 'leuchtturm';
                                            addnav('Der Leuchturm');
                                            addnav('Ins Innere','harbor.php?op=lighthouse_inside');
                addnav('Zurück');
                addnav('Zurück zum Hafen','harbor.php');
    break;
    case 'lighthouse_inside':
                page_header('Im Inneren des Leuchtturms');
                $tout = LIGHTHOUSECOLORBOLD."`c`bIm Inneren`b`c`n".LIGHTHOUSECOLORTEXT."
                        Der Turm ist Arbeitsplatz und Wohnort gleichermaßen, denn die Pflichten des Wärters erlauben es nicht, 
                        dass er sich über lange Zeit von hier entfernt. Hinter der stabilen Tür befindet sich sofort ein Treppenaufgang, 
                        der in eine Küche führt. Die Möbel sind alt, aber sauber. In einer Ecke gibt es einen Kamin, dessen Abzug direkt 
                        an Ort und Stelle über einen Schacht nach außen führt. Daneben gibt es einen Tisch mit zwei Stühlen sowie zwei 
                        große Vorratsschränke. Unter der Decke hängen zudem allerlei getrocknete Lebensmittel und Kräuter. Über die Treppe 
                        geht es weiter nach oben und vorbei an einer Tür, die den Blick in ein kleines Schlafgemach verwehrt. Anders ist es 
                        eine Etage darüber: Ohne Einschränkungen befindet man sich in einer Kammer, die wohl als Aufenthaltsraum dient. Auch 
                        hier gibt es Stühle, die sich um einen Tisch reihen, auf dem verstreute Spielkarten liegen. Die Wand ist bedeckt mit 
                        alten Landkarten und Bücherregalen. Außerdem gibt es einen gemütlichen Sessel, dessen Polster allerdings schon bessere 
                        Zeiten gesehen haben. Über die Treppe geht es noch weiter nach oben: Und hier erreicht man nun das Herzstück des Leuchtturms. 
                        Eine große, eherne Schale befindet sich in der Mitte des Raumes, der von allen Seiten verglast ist. Holz, Kohle und riesige 
                        tönerne Gefäße mit einer brennbaren Flüssigkeit stehen am Rand, ebenso ein Tisch, auf dem dutzende Karten und Pergamentrollen 
                        ausgebreitet sind. Außerdem liegt dort eine Packung Streichhölzer. `n`n";
                $commentary_section = 'leuchtturm_inside';
                addnav('Zurück');
                addnav('Nach draußen','harbor.php?op=lighthouse');
    break;
    // Kontor
        case 'kontor':
                page_header('Das Handelskontor');
                $tout = KONTORCOLORBOLD."`c`bDas Handelskontor`b`c`n".KONTORCOLORTEXT."
                        Das Zentrum des Hilfsprojekts - Kontor 7 - wurde an vielen Stellen für seine neue Aufgabe umgebaut. 
                        Noch immer ist zwar nicht zu übersehen, worum es sich bei dem großen Gebäude handelt, denn einige 
                        Kisten und Gerätschaften wurden einfach in die hinteren Ecken verbannt und auch von der Decke 
                        hängen noch die typischen großen Flaschenzüge. Dennoch hat sich viel verändert. Im Großen und Ganzen 
                        teilt sich die riesige Halle in vier Bereiche. Rechts des großen Eingangstores, das die meiste Zeit 
                        verschlossen ist und nur durch die integrierte Tür passiert werden kann, befindet sich 
                        die provisorisch gebaute Küche. Es ist alles vorhanden, was man zur Verpflegung größerer Personengruppen 
                        braucht und alles ist irgendwie größer, als man es von zu Hause kennt. Links sind die Matratzenlager 
                        aufgebaut. Nichts Besonderes, aber immerhin ein trockener, warmer Ort zum Schlafen und die meisten die 
                        hierher kommen haben ohnehin nicht mehr viel, das sie irgendwo unterbringen müssten. Weiter hinten befinden 
                        sich das Krankenlager, abgeteilt durch große Tücher, und die Waschräume. Im hintersten Teil des Kontors 
                        liegen jene Räume, die jetzt als Lager genutzt werden - gut bewacht. Und die Treppe hinauf zu den Büroräumen 
                        ist mit einer Kette verhangen. Es ist laut und einigermaßen stickig. Stünde nicht immer mal wieder für ein 
                        paar Minuten die Tür offen, wäre es trotz der frostigen Temperaturen, wohl kaum auszuhalten. 
                        An die siebzig Menschen haben hier inzwischen ihr vorübergehendes Zuhause gefunden und täglich kommen ein paar neue 
                        dazu und andere verlassen das Kontor. In einem der Lagerräume herrscht mehr Betrieb als sonst, da langsam aber 
                        sicher die Vorbereitungen getroffen werden, um alsbald den Wiederaufbau am Hafen vorantreiben zu können. Solange 
                        sich allerdings nicht mehr Investoren oder großzügige Spender finden, würde der Aufbau mit den bislang vorhandenen 
                        Mitteln wohl sehr lange dauern.`n`n";
                  $commentary_section = 'kontor';
                                            addnav('Das Handelskontor');
                                            addnav('Dunkle Ecke','harbor.php?op=kontor_inside');
                  addnav('Zurück');
                  addnav('Zurück zum Hafen', 'harbor.php');
        break;
    case 'kontor_inside':
                page_header('Versteckt zwischen Kisten');
                $tout = KONTORCOLORBOLD."`c`bVersteckt zwischen Kisten`b`c`n".KONTORCOLORTEXT."
                        Der Betriebsamkeit des Kontors zum Trotz finden sich doch hier und dort ruhige Ecken, 
                        an denen ein müder -oder fauler- Arbeiter ein kurzes Nickerchen halten könnte, 
                        ohne sofort entdeckt zu werden. Schließlich stapeln sich noch immer überall Baumaterialien, 
                        Fässer und Kisten aller Größe und Form, um zu einem späteren Zeitpunkt verbraucht oder verräumt 
                        zu werden und bilden somit kleine Nischen und versteckte Winkel, in denen wer-weiß-was geschehen könnte.`n`n";
                $commentary_section = 'kontor_inside';
                addnav('Zurück');
                addnav('Nach draußen','harbor.php?op=kontor');
        break;
        // Debug
        default:
                page_header();
                $tout = "Nanu! Was machst du denn hier?! Schnell zurück zum Spiel!";
                addnav('Zurück','harbor.php');
        break;
}
// Ausgabe Text und Schreibfeld
output($tout,true);
if (!empty($commentary_section))
{
        viewcommentary($commentary_section,'Sagen',15,'sagt');
}
if($commentary_nocuts) {
        output('`i`7(Info: Dieser RP-Ort kann nicht durch einen cut für andere Spieler gesperrt werden.)`i`n`n');
}

page_footer();
?>

