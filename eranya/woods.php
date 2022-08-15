
<?php

/*
*   ______________________________________________________________________________________________
*  |
*  | RP-Orte für den Wald: Kleine Lichtung, Klippe, Schlucht, Tempelruine (+ Torbogen), Waldhütte, Wasserfall, Waldlichtung
*  |         (inkl. kleinem Extra bei Klippe)
*  | Autor: Silva
*  | Erstellt für Eranya (http://eranya.de)
*  |______________________________________________________________________________________________
*
*/

require_once 'common.php';

// Textfarben
define('WOODSCOLORTEXT','`2');
define('WOODSCOLORBOLD','`G');
define('CLIFFCOLORTEXT','`Y');
define('CLIFFCOLORBOLD','`t');
define('RAVINECOLORTEXT','`ß');
define('RAVINECOLORBOLD','`=');
define('GLADECOLORTEXT','`D');
define('GLADECOLORBOLD','`u');
define('RUINCOLORTEXT','`¢');
define('RUINCOLORBOLD','`+');
define('ARCHWAYCOLORTEXT','`T');
define('ARCHWAYCOLORBOLD','`ß');
define('HUTCOLORTEXT','`ó');
define('HUTCOLORBOLD','`z');

checkday();
addcommentary();

switch($_GET['op'])
{
        // RP-Ort Wald
        case '':
                page_header('Im Alten Forst');
                $tout = "`c`b".WOODSCOLORBOLD."Im Alten Forst`b`c`n".WOODSCOLORTEXT."
                         Mit leisen Schritten suchst du dir deinen Weg durch die Büsche. Hin und wieder musst du dabei
                         Gebrauch von deiner Waffe machen, doch insgesamt kommst du recht gut voran, was dich schnell
                         immer tiefer in den Wald hineinführt. Bald schon wird die Umgebung nur noch von vereinzelten
                         Strahlen der Sonne erhellt, die hier und dort einen Spalt im dichten Astgeflecht der Bäume
                         gefunden hat. Ansonsten herrscht dämmriges Zwielicht. Tiere huschen hier und dort an dir
                         vorbei oder verschwinden raschelnd im Unterholz, wenn sie dich bemerken, und ab und zu siehst
                         du auch einen Bewohner der Stadt durch die Büsche schleichen, auf der Suche nach etwas, gegen
                         das es sich zu kämpfen lohnt.`n
                         ".WOODSCOLORTEXT."Kurze Zeit später erreichst du einen ausgetretenen Pfad, der nach Norden
                         zu einer kleinen Anhöhe führt. Ein Baumstamm, welcher bereits an einigen
                         Stellen mit Moos bedeckt ist, bietet einen Platz zum Ausruhen. Nun kannst du auch etwas weiter
                         entfernt eine Lücke zwischen den Bäumen und Büschen entdecken, durch welche hell die Sonne
                         scheint. Scheinbar ist der Wald dort zu Ende. Gleichzeitig fällt dir auf, dass der Boden zu
                         deinen Füßen immer härter und felsiger geworden ist. Wahrscheinlich gibt es hier irgendwo
                         in der Nähe eine Schlucht.`n`n";
                $commentary_section = 'woods';
                addnav('Weiter');
                //addnav('Z?Zur Lichtung','woods.php?op=glade');
                addnav('K?Zur Klippe','woods.php?op=cliff');
                //addnav('c?Zur Schlucht','woods.php?op=ravine');
                addnav('T?Zur Tempelruine','woods.php?op=ruin');
                //addnav('W?Zur Waldhütte','woods.php?op=hut');
                addnav('u?Zum Sumpf','swamp.php');
                //addnav('m?Zum Wasserfall','waterfall.php');
                addnav('Privater Ort');
                addnav('Zum Hügelgrab','privplaces.php?rport=grave');
                if(date('n') == 12 && date('d') <= 24)
                {
                        addnav('Besonderes');
                        addnav('A?Adventskalender','advent.php');
                }
                addnav('Zurück');
                addnav('W?Zurück zum Wald','forest.php');
                addnav('S?Zurück zur Stadt','village.php');
        break;
        // RP-Ort Klippe
        case 'cliff':
                page_header('Die Klippe');
                $tout = "`c`b".CLIFFCOLORBOLD."Die Klippe`b`c`n
                         ".CLIFFCOLORTEXT."Du arbeitest dich bis zum Waldrand vor und trittst kurz darauf hinaus in die
                         Sonne. Die ungewohnte Helligkeit zwingt dich dazu, kurz stehen zu bleiben und zu warten, bis
                         sich deine Augen an das Licht gewöhnt haben; dann aber siehst du dich aufmerksam um.`n
                         ".CLIFFCOLORTEXT."Das erste, was du erblickst, ist das Meer. Als langer blauer Streifen zieht es sich am
                         Horizont entlang und schickt seine feuchte, salzige Luft zu dir hinüber. Sogar den Hafen kannst
                         du von hier aus sehen, mit all den Schiffen, die dort gerade vor Anker liegen. Dann wandert dein Blick
                         nach unten - und du stellst erschrocken fest, dass es nur wenige Meter vor dir steil bergab geht.
                         Wie abgeschnitten verläuft eine Felswand mehrere Dutzend Meter in die Tiefe, ab und an
                         unterbrochen von kleinen, schmalen Vorsprüngen oder herausstehenden Wurzeln. Von unten kannst du
                         leise das Rauschen der Wellen hören und zusehen, wie sie sich stetig an den Felsen brechen.`n
                         Zu deiner Rechten ragt ein etwa brusthoher Felsbrocken aus dem Boden heraus. Zuerst bestaunst du dieses
                         ungewöhnliche Naturphänomen, doch beim zweiten Blick erkennst du, dass der Felsen manuell an diese Stelle
                         gesetzt wurde. Nun siehst du auch die breite Kuhle, die in den Stein geschlagen worden ist. Daneben
                         wurde der Umriss eines voranschreitenden Bären eingeritzt. Ist dies etwa ein Opferstein? Nun, in jedem
                         Fall scheint die Kuhle einem Eichhörnchen als Nussversteck zu dienen.`n`n";
                $commentary_section = 'cliff';
                addnav('Klippe');
                addnav('K?Klippe untersuchen','woods.php?op=look');
                addnav('Zurück');
                addnav('W?Zurück zum Wald','woods.php');
                addnav('S?Zurück zur Stadt','village.php');
        break;
        // Kleines Extra: Klippe untersuchen
        case 'look':
                page_header('Die Klippe');
                if ($_GET['act'] == 'climb')
                {
                       $sql = "SELECT cliffpick FROM account_extra_info WHERE acctid=".$session['user']['acctid'];
                       $result = db_query($sql) or die(db_error(LINK));
                       $row = db_fetch_assoc($result);
                       
                       if ($row['cliffpick'] == 1)
                       {
                              $tout = "`^Du hast dein Glück heute schon einmal herausgefordert und lässt es deshalb lieber
                                       bleiben.`n";
                              addnav('K?Zurück zur Klippe','woods.php?op=cliff');
                       }
                       else
                       {
                              $tout = CLIFFCOLORTEXT."Deine Neugier siegt, und so lässt du dich vorsichtig über den Rand der
                                       Klippe gleiten und kletterst Stück für Stück hinab in die Tiefe.`n
                                       ".CLIFFCOLORTEXT."Anfangs klappt der Abstieg noch ganz gut, doch je tiefer du kommst,
                                       umso poröser werden die Felsen, an denen du dich festhältst. Und dann passiert es: Einer
                                       der Felsen gibt unter deinem Gewicht nach, du verlierst das Gleichgewicht und...`n`n";
                              $chance = e_rand(1,4);
                              // tödlicher Absturz
                              if ($chance == 3)
                              {
                                     $tout .= "`^... stürzt in die Tiefe. Du bekommst noch mit, wie dein Körper auf die Felsen im
                                               Wasser auftrifft, dann wird es schwarz um dich...`n
                                               `4Du bist tot.`n";
                                     addnews($session['user']['name']." `&kam beim Sturz von einer Klippe ums Leben.");
                                     killplayer(0,0,0,"","");
                                     addnav('Zu den Schatten','shades.php');
                              }
                              // Edelstein finden
                              else if ($chance == 4)
                              {
                                     $tout .= CLIFFCOLORTEXT."... rutschst an der Felswand entlang nach unten. Glücklicherweise war
                                               der Vorsprung schon in greifbarer Nähe, sodass du dort sicher landen kannst.`n
                                               ".CLIFFCOLORTEXT."Das funkelnde Etwas stellt sich tatsächlich als `#Edelstein
                                               ".CLIFFCOLORTEXT."heraus, welchen du auch gleich einsteckst. Dann machst du dich
                                               wieder auf den Weg nach oben.`n";
                                     $session['user']['gems'] += 1;
                                     addnav('K?Zur Klippe','woods.php?op=cliff');
                              }
                              // User geht leer aus
                              else
                              {
                                     $tout .= CLIFFCOLORTEXT."... rutschst an der Felswand entlang nach unten. Instinktiv suchen deine
                                               Hände neuen Halt - und finden einen Fels, der dein Gewicht hält. Zitternd klammerst
                                               du dich an diesem fest und verharrst erst einmal einige Momente an Ort und Stelle.
                                               Erst, als das Zittern wieder etwas nachlässt, wagst du dich weiter. Der Sturz war dir
                                               eine Lehre; so schnell wie möglich kletterst du wieder nach oben - bis du schließlich
                                               wieder festen Boden unter den Füßen spürst.";
                                     addnav('K?Zur Klippe','woods.php?op=cliff');
                              }
                              $sql = "UPDATE account_extra_info SET cliffpick=1 WHERE acctid=".$session['user']['acctid'];
                              db_query($sql) or die(db_error(LINK));
                       }
                }
                else
                {
                       $tout = CLIFFCOLORTEXT."Vorsichtig trittst du näher an den Rand der Klippe heran und wagst
                                einen Blick in die Tiefe. Fast wird dir schwindelig aufgrund der Höhe, in der du dich
                                befindest, doch du reißt dich zusammen und suchst die Felsvorsprünge nach etwas Wertvollem
                                ab. Und tatsächlich! Auf einem siehst du etwas im Sonnenlicht glitzern. Von der
                                Größe her könnte es ein Edelstein sein... doch genau kannst du es nicht sehen.`n
                                ".CLIFFCOLORTEXT."Was wirst du tun? Hinabklettern und nachschauen? Oder lässt du es
                                lieber bleiben?`n";
                       addnav('Klippe');
                       addnav('H?Hinabklettern','woods.php?op=look&act=climb');
                       addnav('Zurück');
                       addnav('K?Zurück zur Klippe','woods.php?op=cliff');
                }
        break;
        // RP-Ort Schlucht
        case 'ravine':
                page_header('Die Schlucht');
                $tout = "`c`b".RAVINECOLORBOLD."Die Schlucht`b`c`n
                         ".RAVINECOLORTEXT."Von der Lichtung aus setzt du deinen Weg nach Norden fort. Je weiter du
                         gehst, umso mehr fällt dir auf, dass die Bewaldung immer spärlicher wird und stattdessen hin
                         und wieder größere Felsen den Weg versperren. Bald schon scheint über dir wieder die Sonne,
                         und kurz darauf siehst du sie auch schon vor dir: die Schlucht. Steil und kantig ragen deren
                         Felswände in die Höhe und bilden einen schmalen Pass, der gerade noch breit genug ist, dass
                         man alleine ohne Probleme hindurchgehen kann. Zwei steile Trampelpfade führen zudem hinauf zu
                         den grasbewachsenen Hängen.`n`n";
                $commentary_section = 'ravine';
                addnav('Zurück');
                addnav('W?Zurück zum Wald','woods.php');
                addnav('S?Zurück zur Stadt','village.php');
        break;
        // RP-Ort Waldlichtung
        case 'glade':
                page_header('Die Waldlichtung');
                $tout = "`c`b".GLADECOLORBOLD."Die Waldlichtung`b`c`n
                         ".GLADECOLORTEXT."Die Waldlichtung ist von Ästen und Laub freigeräumt. Mit verrotteten Zweigen ist 
                         die Form eines großen Kreises auf dem Boden angedeutet, in dessen Mitte ein dunkler Fleck prangt - Fast so, 
                         als sei dort einst ein steinerner Altar aufgebaut gewesen. Ein seltsamer Zauber umgibt diesen Ort noch immer
                         mit Stille und Frieden und schmale hölzerne Bänke ruhen im Schatten der hohen Baumkronen. Eifrige Eichhörnchen 
                         huschen von Stamm zu Stamm und unweit der sonnenbeschienenen Fläche glitzern die sanften Augen eines Rehs, das 
                         neugierig die leisen Wanderer beobachtet.
                         `nEs scheint als vergehe die Zeit hier in einem anderen Maße als außerhalb der Lichtung.`n`n";
                $commentary_section = 'glade';
                addnav('Zurück');
                addnav('W?Tiefer in den Wald','woods.php');
                addnav('S?Zurück zur Stadt','village.php');
        break;        
        // Alte Tempelruine
        case 'ruin':
                page_header('Die Tempelruine');
                $tout = "`c`b".RUINCOLORBOLD."Die Tempelruine`b`c`n
                         ".RUINCOLORTEXT."Du verlässt die festen Pfade und schlägst stattdessen einen Weg ein, der dich tiefer in den Alten Forst führt. Bald schon
                         merkst du, wie sich die Pflanzen um dich herum verdichten und das Dickicht immer undurchsichtiger wird, bis es schließlich
                         ganz die Sicht verdeckt. Gut, dass du genau weißt, wohin du musst - andernfalls hättest du dich wahrscheinlich schon längst
                         verlaufen.`n
                         Es vergeht noch eine ganze Weile, ehe sich das Buschwerk endlich wieder lichtet und nach und nach die Sicht auf eine alte
                         Ruine freigibt, die, umringt von hohen Bäumen und umschlossen von Schlingpflanzen aller Art, einen düsteren und
                         geheimnisvollen Eindruck erweckt. Mit deinem Ziel nun direkt vor Augen, legst du noch ein wenig an Tempo zu und steuerst
                         zielsicher auf einen breiten, wenn auch gut versteckten Spalt im Mauerwerk zu, der es dir ermöglicht, ins Innere zu gelangen. Kaum
                         angekommen, eröffnet sich dir eine weite, dunkle Halle, deren Decke an einer Seite bereits halb eingestürzt ist - was wohl
                         die überall herumliegenden Steine erklärt.`n`n";
                $commentary_section = 'ruine';
                /*addnav('Tempelruine');
                addnav('Zum alten Torbogen','woods.php?op=archway');*/
                addnav('Zurück');
                addnav('W?Zurück zum Wald','woods.php');
                addnav('S?Zurück zur Stadt','village.php');
        break;
        // Torbogen
        case 'archway':
                page_header('Der alte Torbogen');
                $tout = "`c`b".ARCHWAYCOLORBOLD."Der alte Torbogen`b`c`n
                         ".ARCHWAYCOLORTEXT."Nicht weit von der Tempelruine entfernt steht ein weiteres Überbleibsel längst vergangener Zeiten: ein alter
                         Torbogen, der sich einsam dem Himmel entgegenreckt, allen Rissen und bröckelndem Putz zum Trotz. Fremde Schriftzeichen sind in den inneren
                         Steinbogen eingemeißelt worden - was sie wohl bedeuten? Sie gleichen nichts, was du bisher gesehen hast. Vermutlich liegt es an
                         ihnen, dass den Torbogen etwas Mysteriöses umgibt - oder aber daran, dass, obwohl ringsherum der Alte Forst in seiner vollen Pracht floriert,
                         der Bogen rund um den Torbogen lediglich mit eng stehenden Grashalmen bedeckt ist, zwischen denen höchstens hier und da eine kleine Blume ihren
                         Kopf herausstreckt.`n`n";
                $commentary_section = 'torbogen';
                addnav('Zurück');
                addnav('R?Zurück zur Ruine','woods.php?op=ruin');
                addnav('W?Zurück zum Wald','woods.php');
                addnav('S?Zurück zur Stadt','village.php');
        break;
        // Waldhütte
        case 'hut':
                page_header('Die Waldhütte');
                $tout = "`c`b".HUTCOLORBOLD."Die Waldhütte`b`c`n
                         ".HUTCOLORTEXT."Mitten im Wald entdeckst du eine kleine Waldhütte, die wohl für Wanderer zur Zwischenrast errichtet worden ist. Es
                         handelt sich um ein einfaches Blockhaus, in dessen Türrahmen der Umriss zweier Fische geritzt worden ist, die sich gegenseitig verfolgen.
                         Das Innere der Hütte besteht aus lediglich zwei Räumen, einem Wohnraum mit Feuerstelle und einem Bad.
                         Alles weist bereits Spuren des Alters auf: Die Metallvorrichtung für den Kochtopf hat hier und da rostige Stellen, der schmale Holztisch
                         sieht abgegriffen aus und einer der Holzstühle wackelt ein bisschen. Dafür sieht die Eckbank stabil und gemütlich aus, und im hinteren
                         Bereich gibt es ausreichend Platz, dass zwei bis drei Leute dort ihr Nachtlager aufschlagen können. Was will man mehr?`n`n";
                $commentary_section = 'waldhuette';
                addnav('Zurück');
                addnav('W?Zurück zum Wald','woods.php');
                addnav('S?Zurück zur Stadt','village.php');
        break;
        // Debug
        default:
                page_header();
                $tout = "Nanu! Was machst du denn hier?! Schnell zurück zum Spiel!";
                addnav('Zurück','woods.php');
        break;
}
// Ausgabe Text und Schreibfeld
output($tout,true);
if (!empty($commentary_section))
{
      viewcommentary($commentary_section,'Sagen',15,'sagt');
}

page_footer();

?>

