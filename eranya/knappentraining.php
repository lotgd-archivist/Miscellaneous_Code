
<?php
require_once('common.php');
require_once(LIB_PATH.'disciples.lib.php');
page_header('Knappentraining');
// Knappendetails aus DB abrufen:
$row_d = get_disciple(0,'name,state,sex');
// end
// Funktion: Neue Knappeneigenschaft in die DB eintragen
// @param string NPC-Name
function train_disc($str_npc) {
    global $session,$arr_training,$row_d;
    db_query('UPDATE disciples SET state = '.$arr_training[$str_npc][1].', oldstate = '.$arr_training[$str_npc][1].' WHERE master = '.$session['user']['acctid']);          # Knappen aktualisieren
    item_delete('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0',1);      # Item löschen
    debuglog('`rverändert Knappeneigenschaft von '.get_disciple_stat($row_d['state'],true).' zu '.$arr_training[$str_npc][2]);   # Eintrag in debuglog
}
// Funktion: Knappen-Sprechfarbe ermitteln
// @return Knappen-Sprechfarbe
function get_disc_tcol() {
    global $session;
    $row = db_fetch_assoc(db_query("SELECT addchars_details FROM account_bios WHERE acctid = ".$session['user']['acctid']));
    $arr_addchars_det = mb_unserialize($row['addchars_details']);
    $str_disc_tcol = get_fc($arr_addchars_det['disc']['discbio_comtalkcolor']);
    return (!$str_disc_tcol ? '`#' : $str_disc_tcol);
}
// end
$str_npc = (isset($_GET['npc']) ? $_GET['npc'] : 'none');
$str_op = (isset($_GET['op']) ? $_GET['op'] : '');
switch($str_npc) {
    // Zerindo -> heilkundiger Knappe
    case 'academy':
        if($str_op == 'traindisc') {
            output('`|Du reichst '.$row_d['name'].' `|die Seite - und tatsächlich, damit ist das Buch wieder vollständig! Begeistert beginnt '.$row_d['name'].'
                    `|zu lesen und ist schon bald völlig vertieft. Schmunzelnd lässt du '.($row_d['sex'] ? 'sie' : 'ihn').' erst einmal allein und
                    kümmerst dich um deine eigenen Angelegenheiten.`n
                    Als du wenig später zurückkehrst, stellt '.$row_d['name'].' `|gerade das Buch wieder zurück ins Regal. '.($row_d['sex'] ? 'Ihr' : 'Sein').'
                    zufriedener Gesichtsausdruck erklärt sich nur wenig später von selbst: Da beginnt '.$row_d['name'].' `|nämlich,
                    in aller Ausführlichkeit darüber zu berichten, was '.($row_d['sex'] ? 'sie' : 'er').' nun über das Heilen weiß.`n
                    `n
                    Dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' hat sich Wissen im Bereich der Heilkunde angelesen und kann dir damit nun im Kampf zur Seite stehen!');
            train_disc($str_npc);
        } else {
            output('`|Gemeinsam mit '.$row_d['name'].' `|wanderst du durch die Gänge der Akademie. In regelmäßigen Abständen gehen zu eurer Linken und Rechten
                    Räume ab, die jedoch weder du noch dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' sonderlich interessant finden. Insbesondere '.$row_d['name'].' `|kann
                    '.($row_d['sex'] ? 'ihre' : 'seine').' Langeweile kaum verbergen - bis ihr plötzlich an einer Tür vorbeikommt, durch die man in einen Raum
                    voller Bücherregale blicken kann. Prompt bleibt '.$row_d['name'].' `|stehen und bekommt große Augen, und noch ehe du '.($row_d['sex'] ? 'sie' : 'ihn').'
                    aufhalten kannst, biegt '.($row_d['sex'] ? 'sie' : 'er').' ab und betritt den Raum. Du folgst überrascht und staunst kurz darauf sogar noch mehr,
                    als du verfolgst, wie '.$row_d['name'].' `|sich nach kurzem Umschauen einem sehr alt wirkenden Buch zuwendet. Du schaust '.($row_d['sex'] ? 'ihr' : 'ihm').'
                    über die Schulter und liest: "Einführung in die Heilkunde". Interessante Wahl - was auch '.$row_d['name'].' `|so zu sehen scheint, denn
                    '.($row_d['sex'] ? 'sie' : 'er').' beginnt bereits, wissbegierig in dem Buch zu blättern.`n
                    Du willst dich gerade abwenden und dein'.($row_d['sex'] ? 'e Knappin' : 'en Knappen').' in Ruhe lesen lassen, als '.($row_d['sex'] ? 'sie' : 'er').' plötzlich einen Laut
                    der Überraschung von sich gibt. Mitten im Buch fehlt eine Seite! Und nach '.$row_d['name'].'`|s Miene zu urteilen, scheint es eine
                    wichtige Seite zu sein.`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('Enttäuscht stellt '.$row_d['name'].' `|das Buch wieder zurück und verlässt mit dir zusammen den Raum. Allerdings nimmt
                        '.($row_d['sex'] ? 'sie' : 'er').' dir vorher noch das Versprechen ab, nach der Seite suchen zu gehen.');
            } else {                  # sonst: Link setzen
                output('`nGerade will '.$row_d['name'].' `|das Buch enttäuscht wieder zurückstellen, da erinnerst du dich daran, dass ihr beide ja neulich
                        eine Buchseite gefunden habt. Einen Versuch ist es wert, hm?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0die Buchseite reichen','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zur Akademie','academy.php');
    break;
    // Schmied -> geschickter Knappe
    case 'blacksmith':
        if($str_op == 'traindisc') {
            output('`SAuch wenn du dir sicher bist, dass `oL`ãé`vo`án`Sa`Îr `Smit dem seltenen Erz so überhaupt gar nicht vorhat, ein Amulett herzustellen, kramst du
                    es aus deinem Beutel hervor und lässt es in die Handfläche deine'.($row_d['sex'] ? 'r Knappin' : 's Knappen').' fallen. Du siehst `oL`ãé`vo`án`Sa`Îrs `SSchmunzeln breiter werden,
                    dann verschwinden er und '.$row_d['name'].' `Sin den Nebenraum der Schmiede.`n
                    Als dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' einige Zeit später wieder zu dir stößt, präsentiert '.($row_d['sex'] ? 'sie' : 'er').' dir stolz das Ergebnis
                    '.($row_d['sex'] ? 'ihr' : 'sein').'er Arbeit: ein sauber verarbeitetes Amulett - aus Holz - mitsamt der Triskele als Muster. Das
                    Schmuckstück kann sich sehen lassen, es ähnelt der Vorlage des Schmieds fast haargenau.`n
                    `n
                    Offensichtlich besitzt '.$row_d['name'].' `Sdurchaus geschickte Finger. Wenn das mal keine nützliche Entdeckung ist...');
            train_disc($str_npc);
        } else {
            $str_disc_tcol = get_disc_tcol();
            output('`SWährend du vor einem Regal mit kunstvoll verarbeiteten Schwertern - wohl eine Lieferung für Yaris - stehen bleibst, fällt
                    '.$row_d['name'].'`Ss Blick auf ein filigran verarbeitetes Amulett aus Holz, in dem drei Spiralen symmetrisch zueinander angeordnet sind.
                    Vorsichtig hebt '.($row_d['sex'] ? 'sie' : 'er').' das handtellergroße Kleinod auf und dreht es ein paar Mal hin und her, ehe
                    '.($row_d['sex'] ? 'sie' : 'er').' dich fragt, worum es sich bei dem Muster handelt. `v"Um eine Triskele"`S, ist es dann allerdings
                    `oL`ãé`vo`án`Sa`Îr `Sselbst, der die Antwort gibt und dessen Blick bereits auf '.$row_d['name'].' `Sruht, seitdem
                    '.($row_d['sex'] ? 'sie' : 'er').' das wertvolle Stück in die Hand genommen hat.
                    `v"Sie schützt ihren Träger vor Unglück und bösen Mächten." `SAuf diese Worte hin wandern '.$row_d['name'].'`Ss
                    Brauen in die Höhe, und noch einmal dreht '.($row_d['sex'] ? 'sie' : 'er').' das seltsam geformte Schmuckstück in der Hand.
                    '.$str_disc_tcol.'"Ist es schwer, ein solches Amulett herzustellen?"`S, fragt '.($row_d['sex'] ? 'sie' : 'er').' dann geradeheraus.
                    `oL`ãé`vo`án`Sa`Îr `Sist erst überrascht, doch dann schmunzelt er. `v"Man braucht Gefühl, Geduld, ein Quäntchen Magie - und geschickte
                    Finger"`S, erklärt er - und ergänzt dann mit einem Schulterzucken: `v"Und natürlich ausreichend Silber." `SFür ein Amulett aus Holz.
                    Natürlich. Du schmunzelst mit.`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output(''.$row_d['name'].' `Sallerdings schmunzelt nicht, sondern wirkt plötzlich richtig geschäftig. '.$str_disc_tcol.'"Gut, dann komme ich wieder,
                        wenn ich das Silber besorgt habe"`S, meint '.($row_d['sex'] ? 'sie' : 'er').' und nickt knapp. `oL`ãé`vo`án`Sa`Îr `Sund du werft euch einen kurzen
                        Blick zu. Doch da `oL`ãé`vo`án`Sa`Îr `Slediglich erneut mit den Schultern zuckt, belässt du es dabei und wendest dich lieber wieder
                        `oL`ãé`vo`án`Sa`Îrs `SWaren zu.');
            } else {                  # sonst: Link setzen
                output('`n`SDoch '.$row_d['name'].' `Swirft dir nur einen vielsagenden Blick zu. '.($row_d['sex'] ? 'Ihr' : 'ihm').' ist es ernst. Und zufällig weiß
                       '.($row_d['sex'] ? 'sie' : 'er').', dass du ein Stück Silbererz dein eigen nennst. Schon wird in stummer Bitte die offene Hand in deine
                       Richtung gestreckt. Wie reagierst du?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0das Silber überlassen','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zur Schmiede','blacksmith.php');
    break;
    // Handelsschiff Aurica -> kräftiger Knappe
    case 'v_ship':
        if($str_op == 'traindisc') {
            output('`²Mit leuchtenden Augen nimmt '.$row_d['name'].' `²die Spinatblätter entgegen und macht sich auf die Suche nach dem Schiffskoch. Erst einige Zeit
                    später stößt '.($row_d['sex'] ? 'sie' : 'er').' wieder zu dir - mitsamt zufriedenem Grinsen und rundgefuttertem Bauch. Und als du
                    '.$row_d['name'].' `²wenig später damit beauftragst, deine vielen Anschaffungen nach Hause zu tragen, stellst du zum ersten Mal fest, dass
                    '.($row_d['sex'] ? 'sie' : 'er').' wohl wirklich an Stärke dazugewonnen hat.`n
                    `n
                    Dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' ist nun viel kräftiger als vorher!');
            train_disc($str_npc);
        } else {
            output('`²Trotz all des Trubels lasst '.$row_d['name'].'`² und du es euch nicht nehmen, stehen zu bleiben und den Seemännern um euch herum bei ihrer Arbeit zuzusehen.
                    Scheinbar unermüdlich rollen sie schwere Fässer von einem Ort zum anderen oder tragen Kisten an Deck und von dort über die Planke hinab zum
                    Pier. Insbesondere '.$row_d['name'].'`² ist so fasziniert, dass '.($row_d['sex'] ? 'sie' : 'er').' irgendwann gar nicht mehr auf
                    '.($row_d['sex'] ? 'ihre' : 'seine').' Umgebung achtet - und prompt von einem
                    hochgewachsenen Seemann angerempelt wird. Sofort hält der Mann inne und zieht '.$row_d['name'].'`² ohne Mühe wieder zurück auf die Beine:
                    `="Vorsicht, '.($row_d['sex'] ? 'Mädchen' : 'Junge').', so ein '.($row_d['sex'] ? 'zierliches Ding' : 'Hänfling').' wie du wird hier schnell
                    mal übersehen." '.$row_d['name'].' `²seufzt und nickt niedergeschlagen. Dein Mitgefühl meldet sich, und deshalb fragst du den Seemann, ob
                    es wirklich nur an der täglichen harten Arbeit liegt, dass die Besatzung des Schiffs so kräftig und stark ist. Zu deiner Überraschung zucken
                    die Mundwinkel des Mannes leicht, ehe er den Kopf schüttelt. `="Nicht nur. Wir essen auch stets von dem Wunderkraut, das der Koch uns
                    zubereitet." `²Wunderkraut? Interessiert horcht dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' auf. Doch mehr, als dass es sich dabei um einen Brei aus grünem, gekochtem
                    Blattgemüse handelt, kann dir der Matrose nicht sagen.`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('Du dankst dem Seemann und versprichst '.$row_d['name'].'`², zukünftig nach dem Blattgemüse Ausschau zu halten. Bis dahin geht ihr der
                        arbeitenden Besatzung aber lieber aus den Füßen.');
            } else {                  # sonst: Link setzen
                output('`nDer Hinweis allerdings reicht dir völlig - und '.$row_d['name'].' `²ebenso. Ihr seid euch absolut sicher, dass es sich bei dem Blattgemüse
                        um Spinat handelt. Und wie der Zufall es so will, führst du in deinem Beutel auch gerade ein paar der Blätter mit. Möchtest du sie
                        dein'.($row_d['sex'] ? 'er Knappin' : 'em Knappen').' zur Verfügung stellen?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0den Spinat geben','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zum Schiff','v_ship.php');
    break;
    // Ophelía -> hübscher Knappe
    case 'ophelia':
        if($str_op == 'traindisc') {
            output('`aOphelía klatscht begeistert in die Hände, nimmt dir das Stück Gurke aus der Hand und zieht '.$row_d['name'].' `aanschließend mit sich mit in die
                    Schenkenküche. Du schüttelst nur den Kopf und suchst dir einen Platz am Tresen; was auch immer Ophelía vor hat, es wird mit Sicherheit
                    ein Weilchen dauern. Tatsächlich siehst du sie und '.$row_d['name'].' `aerst zwei Ale später - und staunst nicht schlecht: '.$row_d['name'].'`as
                    Gesichtshaut ist nicht nur sauber, sondern wirkt so gepflegt wie seit langem nicht mehr. Du wirfst Ophelía einen anerkennenden Blick zu,
                    den diese mit einem zufriedenen Grinsen erwidert, ehe sie sich in ihren Feierabend verabschiedet. '.$row_d['name'].' `aunterdessen
                    nutzt die nächsten Minuten, um dir im Detail zu erzählen, was '.($row_d['sex'] ? 'sie' : 'er').' alles über die richtige Hautpflege gelernt hat.`n
                    `n
                    Du musst zugeben, dass dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' nun weit hübscher anzusehen ist als vorher!');
            train_disc($str_npc);
        } else {
            output('`aGemeinsam mit '.$row_d['name'].' `astattest du Ophelía einen Besuch ab. Die hübsche Kellnerin hat gerade Feierabend und ist schon drauf und dran,
                    sich in die Küche zurückzuziehen, um die Schenke über den Hinterausgang zu verlassen. Sie begrüßt dich mit einem Lächeln, doch als ihr Blick
                    dann an dir vorbei zu '.$row_d['name'].' `aschweift, werden ihre Augen mit einem Mal groß. `V"Ach, du meine Güte! '.$row_d['name'].'`V! Wie siehst du denn
                    aus?" `aMissbilligend schnalzt sie mit der Zunge. `V"Läufst du immer so herum? Dabei könnte man so viel aus dir machen... Zu schade, dass
                    ausgerechnet heute sämtliches Gemüse zum Kochen gebraucht worden ist." `aGemüse? '.$row_d['name'].' `ablickt ratlos zu dir.`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('`aDu kannst dir nur leider auch keinen Reim auf das machen, was Ophelía da erzählt. Wie sollte Gemüse dabei helfen, das eigene Aussehen zu
                        verbessern? Du nimmst dir vor, darüber später noch einmal nachzudenken, während du Ophelía einen schönen Feierabend wünschst.');
            } else {                  # sonst: Link setzen
                output('`n`aZuerst weißt du auch nicht so recht, wovon Ophelía da spricht, doch dann erinnerst du dich an das Stück Gurke, das du durch Zufall
                        heute Morgen in deinen Beutel gepackt hast. Möchtest du die Gurke Ophelía und '.$row_d['name'].' `azur Verfügung stellen?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0samt Gurke in Ophelías Obhut geben','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zur Schenke','inn.php');
    break;
    // Petersen -> stolzer Knappe
    case 'petersen':
        if($str_op == 'traindisc') {
            output('`xDu nickst, und daraufhin greift '.$row_d['name'].' `xin '.($row_d['sex'] ? 'ihren' : 'seinen').' Beutel und zieht
                   '.($row_d['sex'] ? 'ihre' : 'seine').' jüngst erlegte Beute heraus: eine tote Qualle. Das glibberige Tier sorgt bei Petersen zuerst für einen
                   verdutzten Gesichtsausdruck, doch dann brüllt der Jäger los vor Lachen, dass sich gleich mehrere andere Anwesende zu eurer kleinen Gruppe
                   herumdrehen. `4"Das ist wahrlich ein außergewöhnliches Getier, das du mir da präsentierst, '.($row_d['sex'] ? 'Mädchen' : 'Junge').'"`x, lacht
                   Petersen und klopft '.$row_d['name'].' `xgrinsend auf die Schulter. Diese'.($row_d['sex'] ? '' : 'r').' scheint durch das Lob geradezu in die
                   Höhe zu wachsen, vor allem, als auch einige andere Jäger '.($row_d['sex'] ? 'ihren' : 'seinen').' Fang begutachten und
                   '.($row_d['sex'] ? 'ihr' : 'ihm').' unter Lachen Anerkennung zollen.`n
                   `n
                   Dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' hat Stolz entwickelt und an Selbstvertrauen hinzugewonnen!');
            train_disc($str_npc);
        } else {
            output('`xVöllig selbstverständlich bewegst du dich zwischen all den gestandenen Jägern hindurch, hebst hier und da grüßend die Hand und nickst auch
                    Petersen zu, der dich mit einem knappen Nicken zurückgrüßt. Anders als sonst geht sein Blick dann allerdings an dir vorbei, und plötzlich
                    hebt sich eine seiner Brauen. Du siehst selbst hinter dich und bemerkst, wie sich '.$row_d['name'].' `xgerade vor einem anderen Jäger wegduckt
                    und sich - mit eingezogenem Kopf - alle Mühe gibt, schnell wieder zu dir aufzuschließen. Du siehst, wie Petersen den Kopf schüttelt, dann
                    erhebt sich der alte Jäger aus seinem Sessel und geht langsam auf dich und dein'.($row_d['sex'] ? 'e Knappin' : 'en Knappen').' zu. `4"He, Kleine'.($row_d['sex'] ? '' : 'r').'"`x,
                    spricht er '.$row_d['name'].' `xan, woraufhin diese'.($row_d['sex'] ? '' : 'r').' zu dem Mann aufblickt. `4"In
                    meinem Heim treffen sich die besten Jäger der Stadt - Männer und Frauen, die jegliches Getier erlegen können, und sei es auch noch so wild,
                    schnell oder außergewöhnlich." `xEin abschätziger Blick trifft dein'.($row_d['sex'] ? 'e Knappin' : 'en Knappen').'. `4"In deinem jungen Alter hatte ich schon mein erstes
                    Wildschwein erlegt. Was hast du im Vergleich vorzuweisen?"`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output($row_d['name'].' `xstammelt etwas Unverständliches und zieht den Kopf nur noch weiter ein. Daraufhin wendet sich Petersen an dich. `4"Das
                        hier ist kein Ort für Möchtegern-Jäger"`x, brummt er und kehrt zu seinem Sessel zurück. Seufzend schickst du '.$row_d['name'].' `xwieder
                        vor die Tür. Du nimmst dir vor, mit '.($row_d['sex'] ? 'ihr' : 'ihm').' in naher Zukunft etwas ganz besonders Außergewöhnliches jagen zu gehen
                        und die erlegte Beute Petersen dann zu zeigen.');
            } else {                  # sonst: Link setzen
                output('`n'.$row_d['name'].' `xsieht zu dir hin. Da gibt es tatsächlich etwas, das '.($row_d['sex'] ? 'sie' : 'er').' neulich beim Strand ganz
                        allein erlegt hat - sogar mit bloßen Händen! Soll '.($row_d['sex'] ? 'sie' : 'er').' Petersen '.($row_d['sex'] ? 'ihre' : 'seine').' Beute zeigen?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav('Die von '.$row_d['name'].'`0 erlegte Qualle zeigen','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zur Jägerhütte','lodge.php');
    break;
    // Riyad -> vorlauter Knappe
    case 'riyad':
        if($str_op == 'traindisc') {
            output('`qZu Riyads großem Erstaunen ziehst du kurzerhand das von Elfenhand gefertigte Kunstwerk hervor und drückst es ihm in die Hände. Dem Händler
                    steht für seltene Sekunden der Mund offen, doch dann nickt er und lächelt, wobei diesmal das Lächeln seine Augen nicht so recht erreichen
                    will. `t"Abgemacht"`q, bestätigt er und wendet sich '.$row_d['name'].' `qzu, welche'.($row_d['sex'] ? 'r' : 'm').' er - nun wieder breit grinsend - den Arm um die Schultern legt. Die
                    beiden beginnen ein Gespräch, in das sie schon bald völlig vertieft sind. Da du gewissermaßen außen vor bleibst, kümmerst du dich kurzerhand
                    um andere Angelegenheiten.`n
                    Erst später stößt dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' wieder zu dir und erzählt dir begeistert von all dem Wissen, das der Händler mit '.($row_d['sex'] ? 'ihr' : 'ihm').'
                    geteilt hat. Lächelnd hörst du zu und gratulierst dir gedanklich selbst für diesen cleveren Einfall. Wenig später allerdings kommen dir
                    die ersten Zweifel an Riyads Lehren, nämlich dann, als '.$row_d['name'].' `qbereits zum dritten Mal '.($row_d['sex'] ? 'ihre' : 'seine').' Meinung zum Besten gibt, obwohl
                    '.($row_d['sex'] ? 'sie' : 'ihn').' niemand danach gefragt hat. Oh, je...');
            train_disc($str_npc);
        } else {
            output('`qDa du Riyads Reden bereits in- und auswendig kennst, hörst du schon gar nicht mehr hin, als er erneut seine - mehr oder weniger - wertvollen
                    Waren lautstark anpreist. Stattdessen konzentrierst du dich darauf, Wertvolles von Schund zu unterscheiden, und legst dich schließlich auf
                    ein alt wirkendes Medallion fest, als... dir auffällt, dass dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' gar nicht mehr an deiner Seite ist. Du schaust dich um und entdeckst
                    '.$row_d['name'].' `qin der kleinen Menschentraube, die sich vor Riyad versammelt hat. '.($row_d['sex'] ? 'Sie' : 'Er').' klebt
                    förmlich an den Lippen des Händlers. Das bringt dich auf eine Idee...`n
                    Schon seit einer Weile fällt dir mehr und mehr auf, dass '.$row_d['name'].' `qlieber stumm bleibt, als '.($row_d['sex'] ? 'ihre' : 'seine').' Meinung kundzutun.
                    Anscheinend fehlt '.($row_d['sex'] ? 'ihr' : 'ihm').' der nötige Mut. Also ziehst du Riyad beim nächsten günstigen Moment zur Seite und
                    fragst ihn, ob er '.$row_d['name'].' `qunter seine Fittiche nehmen könnte. Zuerst ist der Händler überrascht, doch dann grinst er breit.
                    `t"Ihr habt Euch an den Richtigen gewandt"`q, antwortet er dir mit einem Zwinkern. `t"Schließlich gibt es in ganz Eranya keinen besseren
                    Redner als mich. Allerdings.." `q- du ahnst schon, was nun kommt - `t"..wird es mich viel Mühe kosten,
                    Eure'.($row_d['sex'] ? 'r Begleiterin' : 'm Begleiter').' unter die Arme zu greifen. Und selbstverständlich viel Zeit, die mir für andere,
                    `iwichtige`i Erledigungen fehlen würde. Hmm..." `qEine kurze Kunstpause folgt, dann klatscht Riyad plötzlich mit einem: `t"Ich hab\'s!" `qin
                    die Hände. `t"Ich lehre Eure'.($row_d['sex'] ? ' Knappin' : 'n Knappen').' - und Ihr besorgt mir die Elfenkunst, die ich eigentlich schon längst in meine Sammlung aufnehmen
                    wollte. Was meint Ihr?"`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('`qEine `iElfenkunst`i? Überrascht lässt du deinen Goldbeutel los, den du bereits gezückt hattest. Damit hast du nun nicht unbedingt
                        gerechnet. Doch nun gut, wenn dies der Handel ist, den Riyad dir vorschlägt, wirst du ihm wohl seine Elfenkunst beschaffen müssen.');
            } else {                  # sonst: Link setzen
                output('`n`qNun liegt es bei dir, breit zu grinsen, denn zufällig trägst du gerade eine Elfenkunst mit dir herum. Wie praktisch. Aber solltest du
                        sie wirklich Riyad überlassen?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav('Riyad die Elfenkunst geben','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zum Wagenstand','vendor.php');
    break;
    // Aeris -> verträumter Knappe
    case 'aeris':
        if($str_op == 'traindisc') {
            output('`rAeris\' Augen leuchten auf und schimmern verdächtig, als '.$row_d['name'].' `rihr das Gänseblümchen überreicht. `M"Danke"`r, flüstert sie und strahlt
                    die Blume an. `M"Es ist schon lange her, dass mir jemand Blumen geschenkt hat..." `rKurz verliert sich ihr Blick, doch dann lächelt sie
                    wieder und hält '.$row_d['name'].' `rdas Gänseblümchen vors Gesicht. `M"Duftet sie nicht herrlich? Kein Wunder, dass Gänseblümchen als Boten des
                    Frühlings gelten. Allein der Anblick, wenn ganze Wiesen aufleuchten in Weiß und Gelb... So schön! Und wusstest du, dass das Gänseblümchen
                    magische Fähigkeiten besitzt? Ja, wirklich, man kann mit ihm die Zukunft voraussagen. Oder sich bei schwierigen Fragen Rat einholen, wenn
                    man nicht mehr weiter weiß..." `rWie bei einer Wasserquelle sprudeln die Worte nur so aus dem Mädchen heraus, während sie ihr umfassendes
                    Wissen und ihre Begeisterung für Blütenpflanzen mit '.$row_d['name'].' `rteilt. Da du hieran gerade wenig Interesse hast, lässt du die beiden allein und
                    wendest dich anderen Dingen zu.`n
                    Als du später wieder zu Aeris und '.$row_d['name'].' `rzurückkehrst, drückt die Händlerin '.($row_d['sex'] ? 'ihr' : 'ihm').' gerade ein frisch
                    gepflücktes Gänseblümchen in die Hand. Begeistert dankt '.$row_d['name'].' `rihr und verabschiedet sich, um mit dir zu gehen, wobei schon
                    wenige Schritte später das erste Blütenblatt zu Boden segelt. Bald schon ist es eine regelrechte Spur, die dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' hinterlässt,
                    während '.($row_d['sex'] ? 'sie' : 'er').' `igedankenverloren`i eine stumme Frage nach der anderen an das Gänseblümchen-Orakel stellt. Na,
                    hoffentlich bleibt das nun nicht so...');
            train_disc($str_npc);
            $session['user']['reputation'] += 2;
        } else {
            $str_disc_tcol = get_disc_tcol();
            output('`rGrübelnd stehst du vor der Auswahl an Blumen. Welche soll es werden? Du bist ganz vertieft in diese Frage, als '.$row_d['name'].' `rdich plötzlich
                    sachte anschubst und rechts von sich deutet. Aeris steht mit gedankenverlorenem Blick vor ihrem riesigen Korb mit Gänseblümchen, ein
                    trauriges Lächeln auf den Lippen. Mit einem Mal hebt sie die Hand und streicht vorsichtig über eins der weißen Blütenblätter.
                    '.$str_disc_tcol.'"Wer schenkt eigentlich dem Blumenmädchen Blumen?"`r, fragt '.$row_d['name'].' `rneben dir - und du musst zugeben, dass das eine
                    wirklich gute Frage ist...`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('`rAuch später noch stimmt Aeris\' Anblick '.$row_d['name'].' `rund dich nachdenklich. Ihr beschließt, die Augen offen zu halten.
                        Vielleicht entdeckt ihr ja auf euren Streifzügen eine schöne Blume, die man zur Abwechslung einmal an das Blumenmädchen verschenken
                        könnte?');
            } else {                  # sonst: Link setzen
                output('`n'.$str_disc_tcol.'"Hast du vorhin nicht ein Gänseblümchen gepflückt"`r, erinnert dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' dich dann plötzlich. Du nickst und
                        holst das Pflänzchen vorsichtig aus deinem Beutel hervor. Dir ist sofort klar, was '.$row_d['name'].' `rdamit vorhat. Willst du es
                        '.($row_d['sex'] ? 'ihr' : 'ihm').' geben?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0die Blume geben','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zum Marktplatz','market.php');
    break;
    // Bibliothek -> neunmalkluger Knappe
    case 'lib':
        if($str_op == 'traindisc') {
            output('`tNachdem du '.$row_d['name'].' `tso vorzüglich versorgt hast, gehst du los, um auch deinen Magen zu füllen.
                    Als du später zurück kehrst, findest du '.$row_d['name'].' `talleine inmitten eines Berges aus Büchern, die um
                    '.($row_d['sex'] ? 'sie' : 'ihn').' herum verstreut liegen. Die Bibliothekarin wirft dir über den Rand ihrer kleinen Brille einen scharfen
                    Blick zu und so beginnst du lieber, die Bücher zurück in ihre Regale zu stellen. Um '.$row_d['name'].' `tdas letzte Buch abzunehmen, musst du
                    beinahe Gewalt anwenden. Während du '.($row_d['sex'] ? 'sie' : 'ihn').' behutsam aus der Drachenbibliothek nach Hause führst, redet
                    '.($row_d['sex'] ? 'sie' : 'er').' pausenlos von all '.($row_d['sex'] ? 'ihrem' : 'seinem').' neu erworbenen Wissen.`n
                    `n
                    Es scheint, als wäre '.$row_d['name'].' `ttatsächlich klüger geworden, genau genommen richtig neunmalklug!');
            train_disc($str_npc);
        } else {
            output('`tVoller Hoffnung durchstreifst du diesen Hort des Wissens, um endlich die Antworten auf die Fragen zu finden, mit denen '.$row_d['name'].'`t
                    dich seit einer Weile löchert. '.($row_d['sex'] ? 'Sie' : 'Er').' folgt dir dabei auf dem Fuße und betrachtet mit leuchtenden Augen all
                    die Bücherregale. Am Rande des Lesesaals hat sich um einen großen Tisch eine Gruppe anderer Knappen nieder gelassen, die gemeinsam lesen,
                    Wissen austauschen und leise scherzen. Wäre das nicht perfekt für '.$row_d['name'].'`t? Begeistert marschiert dein'.($row_d['sex'] ? 'e
                    Knappin' : ' Knappe').' los und will sich zu den anderen an den Tisch setzen, die auch sofort bereitwillig zur Seite rücken. Du willst
                    dich gerade davon machen, da fällt dir auf, dass es ja schon Mittagszeit ist und ihr noch gar nichts gegessen habt. Auf leeren Magen lernt
                    es sich nicht gut, du solltest '.$row_d['name'].'`t wenigstens etwas zum Knabbern da lassen.`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('`tDa du allerdings nichts Passendes bei dir trägst, gehst du mit '.($row_d['sex'] ? 'ihr' : 'ihm').' erst einmal zurück in die Stadt,
                        eure Mägen füllen. Die Bibliothek hat auch morgen noch geöffnet.');
            } else {                  # sonst: Link setzen
                output('`n`tDu wühlst in deiner Tasche herum und findest ein Beutelchen mit Acolytenfutter - wie treffend! Ein paar Flusen, die an den Nüssen kleben,
                        werden '.$row_d['name'].' `tschon nichts ausmachen.');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].'`0 das Acolytenfutter geben','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zur Bibliothek','library.php');
    break;
    // Bäcker -> dicklicher Knappe
    case 'baecker':
        if($str_op == 'traindisc') {
            output('`^Natürlich zögerst du nicht lang, wühlst in deinem Beutel herum und förderst ein kleines Glas Honig zutage, das nicht nur die Miene
                    deine'.($row_d['sex'] ? 'r Knappin' : 's Knappen').' aufhellt. Du überreichst es '.$row_d['name'].' `^und siehst dann zu, wie '.($row_d['sex'] ? 'sie' : 'er').' zusammen mit dem Bäcker
                    hinter den Tresen und anschließend in den Nebenraum verschwindet. Schon auf dem Weg dorthin beginnen die beiden, über Sinn und Unsinn
                    verschiedener Verzierungsmöglichkeiten zu unterhalten.`n
                    Als du einige Stunden später wiederkommst, findest du '.$row_d['name'].' `^im Nebenraum wieder. '.($row_d['sex'] ? 'Ihr' : 'Sein').' Mund ist völlig verschmiert, und
                    das nicht nur vom Honig, dessen mittlerweile leeres Glas auf einem Tisch steht, in guter Gesellschaft einiger anderer leerer Gläser
                    und Döschen. Dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' hält sich träge den Bauch, der unter dem Hemd schon leicht spannt. Doch '.($row_d['sex'] ? 'ihr' : 'sein').'
                    Gesichtsausdruck spricht von solcher Zufriedenheit, dass du es dabei belässt.`n
                    In den folgenden Tagen wirst du dir allerdings der Folgen bewusst, die der Aufenthalt in der Bäckersküche für dein'.($row_d['sex'] ? 'e Knappin' : 'en Knappen').' hatte.
                    Nicht nur gibt es für '.($row_d['sex'] ? 'sie' : 'ihn').' kaum noch ein anderes Thema, nein, '.($row_d['sex'] ? 'sie' : 'er').' lässt es sich auch nicht
                    nehmen, von nun an regelmäßig bei seinem neuen Freund vorbeizuschauen. Die Besuche machen sich schon bald bemerkbar...`n
                    `n
                    Dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' hat ganz schön zugelegt!');
            train_disc($str_npc);
        } else {
            output('`^Wie das duftet... Da läuft einem glatt das Wasser im Mund zusammen. Selbst '.$row_d['name'].' `^bestaunt die Auswahl an Backwaren, die auf Tischen und
                    in Körben angeboten wird. Insbesondere die Süßwarenecke scheint es '.($row_d['sex'] ? 'ihr' : 'ihm').' angetan zu haben:
                    Mit leuchtenden Augen bleibt '.($row_d['sex'] ? 'sie' : 'er').' vor den Leckereien stehen und beugt sich sogar etwas vor, um sich die Torten
                    aus nächster Nähe anzusehen. Der Bäcker schmunzelt nur, geschmeichelt von dieser offenkundigen Bewunderung seiner kleinen Kunstwerke.
                    Als '.$row_d['name'].' `^ihn dann sogar fragt, wie genau er die besonders raffinierte Verzierung eines Zitronenkuchens bewerkstelligt hat, lacht er
                    auf und schüttelt den Kopf. `q"Bis ich dir das erklärt habe, '.($row_d['sex'] ? 'Mädchen' : 'Junge').', habe ich es dir schon dreimal gezeigt."
                    `^Dann allerdings stockt der Bäcker, und zu '.$row_d['name'].'`^s großer Enttäuschung erinnert er sich, dass ihm gerade erst der Honig ausgegangen
                    ist.`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('Kein Honig, keine Verzierung. Da kann man wohl nichts machen, hm?');
            } else {                  # sonst: Link setzen
                output('`nDein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' will sich schon mit dem Gedanken abfinden, dann wohl doch keinen Einblick in die Arbeitswelt eines Bäckers werfen zu
                        können, da erinnerst du dich, dass du doch gerade ein Gläschen Honig mit dir herumträgst. Möchtest du es '.$row_d['name'].' `^geben?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0den Honig geben','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zum Bäcker','baecker.php');
    break;
    // Marek -> nichtsnutziger Knappe
    case 'marek':
        if($str_op == 'traindisc') {
            output('`aDu nickst in Richtung Tresen, woraufhin '.$row_d['name'].' `avortritt und Marek den Topf zeigt, den
                   '.($row_d['sex'] ? 'sie' : 'er').' neulich beim Angeln gefunden habt.
                    Marek klappt kurzzeitig der Mund auf, doch dann fängt er sich, nickt eifrig und verschwindet mit '.$row_d['name'].' `ain die Schenkenküche.`n
                    Als du einige Stunden später wiederkommst, um '.$row_d['name'].' `aabzuholen, findest du '.($row_d['sex'] ? 'sie' : 'ihn').' und Marek in der
                    Küche - sturzbetrunken. Diesmal klappt `idir`i der Mund auf, dann schnappst du dir dein'.($row_d['sex'] ? 'e Knappin' : 'en Knappen').' und schleifst
                    '.($row_d['sex'] ? 'sie' : 'ihn').' aus dem Raum. '.$row_d['name'].' `aerzählt dir, dass '.($row_d['sex'] ? 'sie' : 'er').' von Marek zwar
                    nicht gezeigt bekommen hat, wie man Bier braut, dafür aber, wie man einen möglichst großen Bogen um Arbeit aller Art macht. Das rächt sich
                    bereits jetzt, denn '.$row_d['name'].' `aist auf einmal ist nicht mehr bereit, dir zur Hand zu gehen oder sonstwie eine Hilfe zu sein.`n
                    `n
                    Dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' hat sich zu einem vollkommenen Nichtsnutz entwickelt!');
            train_disc($str_npc);
        } else {
            output('`aDu wartest, bis Marek Zeit für dich hat, dann verwickelst du ihn in ein kurzes Gespräch und fragst, ob es etwas gibt, dass er
                    '.$row_d['name'].' `abeibringen könnte. Sofort nickt Marek und erzählt ausgiebig von seinen Bierbrauerkünsten, für die seine
                    Schenke in ganz Lyneros berühmt ist. Zufällig sucht er auch gerade einen Auszubildenden, an den er sein kostbares Wissen weitergeben kann. Deine
                    Augen leuchten auf bei diesem Angebot. Zugegeben, das hiesige Bier hast du jetzt nicht unbedingt als Spitzenklasse in Erinnerung,
                    aber was zählt schon eine Meinung im Vergleich zu ganz Lyneros? Doch gerade, als du '.$row_d['name'].' `ain Mareks Obhut übergeben willst,
                    schüttelt dieser den Kopf. `V"Ich würde '.$row_d['name'].' `Vwirklich gern ausbilden, aber mein Kochtopf hat ein Loch. Ich muss ihn erst
                    austauschen, ehe ich neues Bier brauen kann."`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('`aDamit lässt dich Marek stehen und kümmert sich um einen anderen Gast.');
            } else {                  # sonst: Link setzen
                output('`n`aWelch Zufall! Hat '.$row_d['name'].' `anicht neulich einen passenden Topf beim Angeln gefunden?');
                 addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0den Topf zeigen lassen','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zur Schenke','inn.php');
    break;
    // Thoya -> treuer Knappe
    case 'thoya':
        if($str_op == 'traindisc') {
            $str_disc_tcol = get_disc_tcol();
            output('`5Begeistert nimmt '.$row_d['name'].' `5die Perle entgegen und läuft zu Thoya zurück, um ihr das wertvolle Stück in die Hand zu drücken.
                    Die Rüstungsverkäuferin hält die Perle kurz gegen das Licht, dann setzt sie sie vorsichtig in eine halbfertige Brosche ein - und nickt
                    zufrieden. Nun ist es an ihr, '.$row_d['name'].' `5etwas in die Hand zu geben, was du als aus bunten Schnüren geflochtenes Armbändchen identifizierst.
                    Strahlend kehrt dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' zu dir zurück, ergreift kurzerhand dein Handgelenk und bindet dir das Armband um: '.$str_disc_tcol.'"Givro eterna
                    amicizia." `5Gerührt betrachtest du das Freundschaftsbändchen, ehe du '.$row_d['name'].'`5 '.
                    ($session['user']['sex'] ? 'umarmst' : 'zum Dank auf die Schulter klopfst').'.`n
                    `n
                    Wahrlich, du hast in dein'.($row_d['sex'] ? 'er Knappin' : 'em Knappen').' eine'.($row_d['sex'] ? ' treue Freundin und Begleiterin' : 'n treuen Freund und Begleiter').' gefunden.');
            train_disc($str_npc);
            item_add($session['user']['acctid'],'frndbnd',array('tpl_description'=>$str_disc_tcol.'Ein Freundschaftsbändchen von '.$row_d['name'].$str_disc_tcol.'.'));
        } else {
            output('`5Während du dich in Thoyas Wagen umsiehst, gesellt sich '.$row_d['name'].'`5 zu der bunt gekleideten Frau ans Fenster. Von ihrer Unterhaltung bekommst du nur
                    wenig mit, bis dich '.$row_d['name'].'`5 plötzlich antippt und fragt, ob du zufällig etwas Kleines, Kugelförmiges mit dir trägst, das du entbehren
                    könntest.');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('`nVerblüfft kramst du in deinem Beutel herum, doch dann musst du leider den Kopf schütteln. Zuerst ist dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' sichtlich geknickt,
                        doch als Thoya aus dem Hintergrund verspricht, `ies`i noch eine Weile aufzuheben, hellt sich '.($row_d['sex'] ? 'ihre' : 'seine').'
                        Miene wieder auf. Du schaust zwischen der Rüstungsverkäuferin und '.$row_d['name'].' `5hin und her und fragst, worum es denn gerade geht. Doch
                        dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' winkt nur ab, und auch Thoya lächelt lediglich, während sie sich wieder anderem zuwendet.');
            } else {                  # sonst: Link setzen
                output('`n`nVerblüfft kramst du in deinem Beutel herum und findest tatsächlich eine kleine Perle, die du letztens am Strand aus einer Muschel
                        herausgelöst hast. Möchtest du sie dein'.($row_d['sex'] ? 'er Knappin' : 'em Knappen').' zur Verfügung stellen?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0die Perle geben','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zum Laden','armor.php');
    break;
    // Silas -> hinterhältiger Knappe
    case 'silas':
        if($str_op == 'traindisc') {
            output('`aDu drückst '.$row_d['name'].' `adas Bierfass in die Hände und siehst dann zu, wie '.($row_d['sex'] ? 'sie' : 'er').' es an Silas weitergibt.
                    Dessen Augen leuchten sofort auf, und schnell schiebt er das Fass unter seinen Hocker, sodass es vom Tresen aus nur noch schwer zu sehen ist.
                    Anschließend zeigt er '.$row_d['name'].'`a, wie es ihm immer wieder gelingt, seinen Zuhörern ein paar Goldmünzen abzuluchsen. Der Trick kostet
                    dein'.($row_d['sex'] ? 'e Knappin' : 'en Knappen').' dank zahlreicher Demonstrationen noch weitere zehn Goldstücke, doch als '.($row_d['sex'] ? 'sie' : 'er').'
                    schließlich zu dir zurückkehrt, reicht '.($row_d['sex'] ? 'ihr' : 'sein').' Grinsen von einem Ohr zum anderen.`n
                    `n
                    Dank Silas\' `iLehren`i weiß dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' nun, wie man mit Geschick und wenig Skrupeln seine Ziele erreicht. Eine zweifelhafte Eigenschaft...
                    Aber vielleicht erweist sie sich ja doch irgendwann mal als ganz nützlich..?');
            train_disc($str_npc);
        } else {
            output('`aGerade, als du dein'.($row_d['sex'] ? 'er Knappin' : 'em Knappen').' den Rücken zuwendest, hörst du Silas rufen: `F"Sieh nur, ein dreiköpfiger Affe!" `aDir schwant Übles, doch als
                    du dich herumdrehst, um '.$row_d['name'].' `azu warnen, ist es schon zu spät; grinsend zeigt Silas ih'.($row_d['sex'] ? 'r' : 'm').' die zwei
                    Goldmünzen, die er ih'.($row_d['sex'] ? 'r' : 'm').' gerade geklaut hat. Zuerst ist '.$row_d['name'].' `amehr als verdutzt, doch dann
                    leuchten '.($row_d['sex'] ? 'ihre' : 'seine').' Augen plötzlich auf,
                    und mit einem Mal ist es Silas, der verdutzt drein schaut, als '.$row_d['name'].' `aihn plötzlich förmlich anbettelt,
                    ih'.($row_d['sex'] ? 'r' : 'm').' diesen Trick zu zeigen. `F"Gut, ich zeige ihn dir"`a, meint der Barde schließlich, `F"aber nur,
                    wenn du mir vorher etwas von Mareks Bier besorgst. Der alte Geizkragen verlangt doch tatsächlich seit neustem Gold dafür! Dabei ist meine
                    Kehle ganz trocken vom vielen Arbeiten..."`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('`aDu verkneifst dir ein Schmunzeln. Immerhin, mit seinem Angebot ist der Barde noch recht moderat geblieben. So ein bisschen Bier werdet
                        du und '.$row_d['name'].' `aMarek sicherlich abschwatzen können - oder es wird sich ein anderer Weg finden lassen. Für diesmal jedoch
                        sorgst du dafür, dass dein'.($row_d['sex'] ? 'e enttäuschte Knappin' : ' enttäuschter Knappe').' auf andere Gedanken kommt, indem du '.($row_d['sex'] ? 'sie' : 'ihn').' mit einer
                        willkürlichen anderen Aufgabe betraust.');
            } else {                  # sonst: Link setzen
                output('`n`aBei den Worten des Barden musst du automatisch an das kleine, volle Bierfass denken, das du Marek neulich abgeknöpft hast.
                        Auch '.$row_d['name'].' `ascheint in diese Richtung zu denken, denn im nächsten Moment trifft dich '.($row_d['sex'] ? 'ihr' : 'sein').'
                        bittfragender Blick. Möchtest du '.($row_d['sex'] ? '' : '').' das Fass geben?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0das Bierfass geben','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zur Schenke','inn.php?op=silas');
    break;
    // Scytha -> listiger Knappe
    case 'scytha':
        $str_disc_tcol = get_disc_tcol();
        if($str_op == 'traindisc') {
            output('`5Du verziehst keine Miene, als du '.$row_d['name'].' `5deinen Beutel reichst und anschließend beobachtest, wie
                   '.($row_d['sex'] ? 'sie': 'er').' den Klumpen `iKatzengold`i, den ihr neulich im Wald gefunden habt, herausfischt und Scytha in die Hand
                    drückt. Die Augen der Händlerin leuchten auf, doch auch sie lässt sich sonst nichts anmerken, als der versprochene Edelstein seinen Besitzer
                    wechselt. '.$str_disc_tcol.'"Besten Dank, und bis zum nächsten Mal"`5, verabschiedet sich '.$row_d['name'].' `5daraufhin von Scytha - ein
                    Wink mit dem Zaunpfahl in deine Richtung. Nach knappen Abschiedsworten auch von deiner Seite verlasst ihr beide schleunigst das Zelt.`n
                    `n
                    Draußen lässt '.$row_d['name'].' `5grinsend den Edelstein in deine Hand fallen. Wer hätte gedacht, dass dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' andere Leute derart übers Ohr hauen
                    kann? Das wird dir sicherlich noch von Nutzen sein.');
            train_disc($str_npc);
            $session['user']['gems']++;
            $session['user']['reputation'] -= 2;
        } else {
            $costs = 4000 - 3*getsetting("selledgems",0);   # (Rechnung aus gypsy.php)
            output('`5Während du dich umsiehst, geht '.$row_d['name'].' `5zielstrebig auf Scytha zu und fragt sie nach ihrem derzeitigen Preis für einen Edelstein.
                    `6"'.$costs.' Gold"`5, ist die knappe Antwort, woraufhin dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' dir mit hochgezogenen Brauen einen kurzen Blick zuwirft.`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('`5Gerade willst du deinen Goldbeutel zücken, da bemerkst du, wie '.$row_d['name'].' `5dir versteckt zu verstehen gibt, nicht zu reagieren.
                       '.$str_disc_tcol.'"Ein fairer Preis"`5, meint '.($row_d['sex'] ? 'sie': 'er').' stattdessen zu Scytha. '.$str_disc_tcol.'"Wir werden das
                        nötige Gold beschaffen und dann wiederkommen." `5Scytha zuckt mit den Schultern und sieht euch beiden nach, als ihr das Zelt
                        verlasst.`n
                        Draußen zieht dich '.$row_d['name'].' `5zur Seite und meint, dass er neulich etwas im Wald gesehen hat, das wie Gold aussieht, aber keins
                        ist. Du grinst, als du '.($row_d['sex'] ? 'ihren': 'seinen').' Plan begreifst. Einen Versuch ist es wert. Nun muss nur noch etwas
                        dieses falschen Golds in eure Hände fallen...');
            } else {                  # sonst: Link setzen
                output('`5Dank der langen Zeit, die '.$row_d['name'].' `5und du nun schon miteinander verbracht habt, verstehst du '.($row_d['sex'] ? 'ihren': 'seinen').'
                        Blick sofort: Dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' hat etwas vor. Und gerade streckt '.($row_d['sex'] ? 'sie ihre' : 'er seine').' Hand in deine Richtung in
                        stummer Bitte um deinen Beutel. Willst du mitziehen?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0den Beutel reichen','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zum Stadtplatz','village.php');
    break;
    // Trainingslager -> flink Knappe
    case 'train':
        if($str_op == 'traindisc') {
            output('`ôAuf dein Nicken hin ziehen Bryden und '.$row_d['name'].' `ôdavon, um sich eine freie Ecke für ihr Training zu suchen. So ganz wohl ist dir ja nicht dabei,
                    dein'.($row_d['sex'] ? 'e Knappin' : 'en Knappen').' mit einem `irostigen`i Dolch kämpfen zu lassen... Doch die Tatsache, dass es niemand Geringeres als Bryden selbst ist, welcher sich
                    deine'.($row_d['sex'] ? ' Begleiterin' : 'n Begleiter').' vornimmt, zerstreut deine Zweifel wieder.`n
                    Kurze Zeit später stößt '.$row_d['name'].' `ôwieder zu dir, von einem Ohr zum anderen grinsend. Mit etwas Abstand stößt dann auch Bryden zu euch, doch als
                    du ihn fragst, wie die Einheit gelaufen ist, schüttelt er nur den Kopf und senkt etwas die Stimme:
                    `&"Dein'.($row_d['sex'] ? 'e Begleiterin' : ' Begleiter').' ist wahrlich kein Kämpfer, '.$session['user']['name'].'`&. Es liegt
                    '.($row_d['sex'] ? 'ihr' : 'ihm').' scheinbar nicht im Blut. Ich habe '.($row_d['sex'] ? 'ihr' : 'ihm').' nun ein paar Tricks
                    gezeigt, wie '.($row_d['sex'] ? 'sie' : 'er').' im Falle eines Kampfes mit heiler Haut davonkommen kann. Mehr konnte ich nicht tun."
                    `ôDu nickst und dankst Bryden für seine Mühen. Dann beobachtest du '.$row_d['name'].' `ôeine Weile.. und forderst '.($row_d['sex'] ? 'sie' : 'ihn').'
                    schließlich kurzerhand zum Kampf heraus. Zuerst verdutzt, zieht '.($row_d['sex'] ? 'sie' : 'er').' dann allerdings
                    '.($row_d['sex'] ? 'ihren' : 'seinen').' Dolch - und nur Momente später staunst du nicht schlecht. Zwar gelingt es '.$row_d['name'].' `ôkein einziges Mal,
                    bei dir einen Treffer zu landen, doch andersherum musst du dich doch merklich anstrengen, um dein'.($row_d['sex'] ? 'e Knappin' : 'en Knappen').' bei '.($row_d['sex'] ? 'ihren' : 'seinen').'
                    flinken Ausweichschritten doch noch zu erwischen.`n
                    `n
                    Das Training mit Bryden hat somit trotz allem etwas gebracht: Dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' ist nun viel flinker als vorher!');
            train_disc($str_npc);
        } else {
            output('`ôMit gezogenem Schwert stürzt du dich in das Training. Währenddessen bleibt '.$row_d['name'].' `ôam Rand zurück und sieht zu, wie du
                    Gegner um Gegner zu Boden streckst. Selbst aus der Entfernung kannst du das sehnsüchtige Leuchten in '.($row_d['sex'] ? 'ihren' : 'seinen').'
                    Augen erkennen - und nicht nur du. Auch Bryden wirft mit einem Mal einen Blick in '.$row_d['name'].'`ôs Richtung, ehe er zu dem Platz hinüberschlendert,
                    auf dem du gerade deinen aktuellen Gegner überwältigt hast. `&"Na, '.($row_d['sex'] ? 'Mädchen' : 'Junge').', wieso
                    versuchst dich nicht auch einmal an einem Kampf? Ich wäre gerade frei." `ôDu staunst nicht schlecht über das Angebot - und dein'.($row_d['sex'] ? 'e Knappin' : ' Knappe').' erst!`n');
            // Schauen, ob Item vorhanden ist:
            $int_itemcount = item_count('tpl_id = "'.$arr_training[$str_npc][0].'" AND owner = '.$session['user']['acctid'].' AND deposit1 = 0');
            if($int_itemcount == 0) { # Falls Item fehlt: Hinweis geben
                output('Doch leider folgt die Ernüchterung auf dem Fuße, als '.$row_d['name'].' `ôrealisiert, dass '.($row_d['sex'] ? 'sie' : 'er').'
                        ja gar keine Waffe besitzt. Bisher war alles, was ihr im Wald gefunden habt, nicht für '.($row_d['sex'] ? 'ihre' : 'seine').'
                        kleinen Hände geeignet gewesen. Mit hängenden Schultern dankt '.$row_d['name'].' `ôBryden für das Angebot und sieht dir dann weiter beim Trainieren zu.');
            } else {                  # sonst: Link setzen
                output('`nOhne zu zögern, nimmt '.$row_d['name'].' `ôdas Angebot an und zieht begeistert den rostigen Dolch, den ihr erst kürzlich bei euren Streifzügen
                        aufgesammelt habt. Dann blickt '.($row_d['sex'] ? 'sie' : 'er').' zu dir - gestattest du '.($row_d['sex'] ? 'ihr' : 'ihm').'
                        diese Trainingseinheit?');
                addnav('Knapp'.($row_d['sex'] ? 'in' : 'e'));
                addnav($row_d['name'].' `0die Erlaubnis erteilen','knappentraining.php?npc='.$str_npc.'&op=traindisc');
            }
        }
        addnav('Zurück');
        addnav('Zum Trainingslager','train.php');
    break;
    // Debug
    default:
        output('`&Leider ist ein Fehler aufgetreten. Bitte sende die folgende Meldung via Anfrage an das E-Team:`n
                `n
                `^fehlender npc: '.$str_npc.' in knappentraining.php');
        addnav('Zurück');
        addnav('Zum Stadtplatz','village.php');
    break;
}
page_footer();
?>

