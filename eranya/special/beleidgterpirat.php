
<?php
/*
 New Code By Mr edah
 www.edahnien.de & www.grueblz.com
 Umsetzung altes skript by Tiger313
 Idee: Wolf
 Fragen / antworten von Monkey island edit by Tiger313
 Neue Fragen By Comunities of
 www.gruebelz.com
 www.edahnien.de
 www.radirap.de.vu
 www.fargoth.de


Changelog:
SQL nicht mehr benötigt!
Überarbeiteter Code!
*/
// Edahniens easy color edit
define('WBEPICOLORSTANDART','`ù'); #stadartfarbe
define('WBEPICOLORPI','`C'); #farbe fuer priat
define('WBEPICOLORPISAY','`w'); #farbe wenn pirat was sagt
define('WBEPICOLORSTANDART2','`7'); #standart 2
define('WBEPICOLORPUNKTE','`q'); #punktemeldung
define('WBEPICOLORZAHL','`^'); #farbe fuer zahlen

if (!isset($session)) exit();
switch($_GET['op'])
{
    case "";
       $_SESSION['session']['bpirat']='0';
       $_SESSION['session']['buser']='0';
       $out .=''.WBEPICOLORSTANDART.' Auf deiner Suche kommt dir auf einmal ein '.WBEPICOLORPI.'WIRKLICH furchteinflößender Pirat '.WBEPICOLORSTANDART.'entgegen,
               der ziemlich verärgert zu sein scheint. Du versuchst, an ihm vorbei zu gehen, ohne ihn direkt anzuschauen, doch als du ihn dabei versehentlich
               leicht berührst, dreht der Pirat durch! Er fordert dich zum Kampf heraus, und sofort ziehst du dein Schwert, doch der '.WBEPICOLORPI.'Pirat
               '.WBEPICOLORSTANDART.'lacht dich nur aus und meint:`n';
       $out .=''.WBEPICOLORPISAY.'"Was willst du mit dem Zahnstocher?! Nur Schwächlinge wie du brauchen Waffen. Die mächtigste Waffe sind `bWORTE`b, also
               kämpfe mit ihnen oder gar nicht!"'.WBEPICOLORSTANDART.'`n`n';
       $out .=''.WBEPICOLORSTANDART.' Wie entscheidest du dich?`n';
       addnav("Kämpfen!","forest.php?op=kampf2");
       addnav("Lieber fliehen","forest.php?op=wald");
       $session['user']['specialinc']="beleidgterpirat.php";
       $_SESSION['session']['richtigeaw']='';
    break;
    case 'wald';
       $out .=''.WBEPICOLORSTANDART.'Du ignorierst den Piraten und gehst zurück in den Wald.';
       $session['user']['turns'] --;
       $session['user']['specialinc']='';
       addnews("".WBEPICOLORSTANDART2." ".$session['user']['name']."".WBEPICOLORSTANDART2." war zu `ifeige`i, gegen den ".WBEPICOLORPISAY."Piraten ".WBEPICOLORSTANDART2." in einem Kampf ohne Waffen anzutreten.");
    break;
     case'kampf2';
          if ($_SESSION['session']['richtigeaw']==''){
               $out .=''.WBEPICOLORSTANDART.' Du steckst dein Schwert wieder weg und machst dich bereit.`n';
               $out .=''.WBEPICOLORSTANDART.' Der Pirat stellt sich dir gegenüber auf, räuspert sich noch mal und fängt an!`n`n';
          } else {
               if ($_SESSION['session']['richtigeaw']==$_GET['id']){
                        $_SESSION['session']['buser']++;
                        $session['user']['specialinc']="beleidgterpirat.php";
                        $out .=''.WBEPICOLORPISAY.'"Argh, Punkt für dich!"`n`n';
                        $out .="".WBEPICOLORPUNKTE."Neuer Stand:`n
                                Pirat: ".WBEPICOLORZAHL."".$_SESSION['session']['bpirat']."".WBEPICOLORPUNKTE." Punkt".($_SESSION['session']['bpirat'] == 1 ? "" : "e")."! `n
                                Du: ".WBEPICOLORZAHL."".$_SESSION['session']['buser']."".WBEPICOLORPUNKTE." Punkt".($_SESSION['session']['buser'] == 1 ? "" : "e")."!`n`n`n";
                        if ($_SESSION['session']['buser'] == 5) {
                            addnav("Weiter","forest.php?op=ergebnis");
                            output($out);
                            page_footer();
                            exit;
                        }
               } else {
                        $_SESSION['session']['bpirat']++;
                        $session['user']['specialinc']="beleidgterpirat.php";
                        $out .=''.WBEPICOLORPISAY.'"HaHaHa, Punkt für mich!"`n`n';
                        $out .="".WBEPICOLORPUNKTE."Neuer Stand:`n
                                Pirat: ".WBEPICOLORZAHL."".$_SESSION['session']['bpirat']."".WBEPICOLORPUNKTE." Punkt".($_SESSION['session']['bpirat'] == 1 ? "" : "e")."! `n
                                Du: ".WBEPICOLORZAHL."".$_SESSION['session']['buser']."".WBEPICOLORPUNKTE." Punkt".($_SESSION['session']['buser'] == 1 ? "" : "e")."!`n`n`n";
                        if ($_SESSION['session']['bpirat'] == 5) {
                            addnav("Weiter","forest.php?op=ergebnis");
                            output($out);
                            page_footer();
                            exit;
                        }
               }
          }
          $session['user']['specialinc']="beleidgterpirat.php";
          $fragen = array(1 =>'Mein Schwert wird dich aufspießen wie ein Schaschlik!',
                       2 =>'Deine Fuchtelei hat nichts mit Fechtkunst zu tun!',
                       3 =>'Niemand wird mich verlieren sehen, auch du nicht!',
                       4 =>'Meine Großmutter hat mehr Kraft als du Wicht!',
                       5 =>'Nach diesem Spiel trägst du den Arm in Gips.',
                       6 =>'Argh ... Ich zerreiße deine Hand in eine Million Fetzen.',
                       7 =>'Argh ... Hey, schau mal da drüben!',
                       8 =>'Argh ... Ich werde deine Knochen zu Brei zermalmen.',
                       9 =>'Ich kenne Läuse mit stärkeren Muskeln.',
                       10 =>'Alle Welt fürchtet die Kraft meiner Faust.',
                       11 =>'Ungh ... Gibt es auf dieser Welt eine größere Memme als dich?',
                       12 =>'Ungh ... Du bist das hässlichste Wesen, das ich jemals sah ... Grr!',
                       13 =>'Ungh ... Viele Menschen sagen, meine Kraft ist unglaublich.',
                       14 =>'Ungh ... Ich hab\' mit diesen Armen schon Kraken bezwungen.',
                       15 =>'Ungh, ha ... Sehe ich da Spuren von Angst in deinem Gesicht?',
                       16 =>'Ich hatte mal einen Hund, der war klüger als du.',
                       17 =>'Du hast die Manieren eines Bettlers.',
                       18 =>'Jeder hier kennt dich als unerfahrenen Dummkopf.',
                       19 =>'Du kämpfst wie ein dummer Bauer.',
                       20 =>'Meine Narbe im Gesicht stammt aus einem harten Kampf.',
                       21 =>'Menschen fallen mir zu Füßen, wenn ich komme.',
                       22 =>'Dein Schwert hat schon bessere Zeiten gesehen.',
                       23 =>'Du bist kein Gegner für mein geschultes Gehirn.',
                       24 =>'Trägst du immer noch Windeln?',
                       25 =>'An deiner Stelle würde ich zur Landratte werden.',
                       26 =>'Alles, was du sagst, ist dumm.',
                       27 =>'Hast du eine Idee, wie du hier lebend herauskommst?',
                       28 =>'Mein Schwert wird dich in 1000 Stücke reißen.',
                       29 =>'Niemand wird sehen, dass ich so schlecht kämpfe wie du.',
                       30 =>'Nach dem letzten Kampf war meine Hand blutüberströmt.',
                       31 =>'Kluge Gegner laufen weg, bevor sie mich sehen.',
                       32 =>'Überall in der Gegend kennt man meine Klinge.',
                       33 =>'Bis jetzt wurde jeder Gegner von mir eliminiert!',
                       34 =>'Du bist so häßlich wie ein Affe im Negligé!',
                       35 =>'Dich zu töten, wäre eine legale Beseitigung!',
                       36 =>'Warst du schon immer so hässlich oder bist du mutiert?',
                       37 =>'Ich spieß\' dich auf wie eine Sau am Buffet!',
                       38 =>'Wirst du laut Testament eingeäschert oder einbalsamiert?',
                       39 =>'Ein jeder hat vor meiner Schwertkunst kapituliert!',
                       40 =>'Ich werde dich richten - und es gibt kein Plädoyer!',
                       41 =>'Himmel bewahre! Für einen Hintern wäre dein Gesicht eine Beleidigung!'
                       );
          $antworten = array(1 =>'Dann mach damit nicht rum wie mit einem Staubwedel.',
                       2 =>'Doch, doch, du hast sie nur nie gelernt.',
                       3 =>'Du kannst so schnell davonlaufen?',
                       4 =>'Dafür hab\' ich in der Hand nicht die Gicht!',
                       5 =>'Das sind große Worte für \'nen Kerl ohne Grips!',
                       6 =>'Grrrgh! Ich wusste gar nicht, dass du so weit zählen kannst. Argh!',
                       7 =>'Ja, ja, ich weiß, ein dreiköpfiger Affe...',
                       8 =>'Ungh! Ich werde mich wehren, bis die Griffel dir qualmen!',
                       9 =>'Argh! Behalt sie für dich, sonst bekomm\' ich noch Pusteln!',
                       10 =>'Ungh ... Wobei mir vor allem vor deinem Atem graust.',
                       11 =>'Ungh! Sie sitzt mir gegenüber, also was fragst du mich?',
                       12 =>'Ungh! Mit Ausnahme von deiner Frau, so viel ist klar!',
                       13 =>'Argh! Unglaublich erbärmlich, das sag\' ab jetzt ich.',
                       14 =>'Ungh! Und Babys wohl auch, na, der Witz ist gelungen.',
                       15 =>'Das ist ein Lachen, du schwächlicher Wicht!',
                       16 =>'Er muß dir das Fechten beigebracht haben.',
                       17 =>'Ich wollte, dass du dich wie zu Hause fühlst.',
                       18 =>'Zu schade, dass dich überhaupt keiner kennt.',
                       19 =>'Ich schaudere, ich schaudere.',
                       20 =>'Also mal wieder in der Nase gebohrt, wie?',
                       21 =>'Auch bevor sie deinen Atem riechen.',
                       22 =>'Und du wirst deine rostige Klinge nie wieder sehen.',
                       23 =>'Vielleicht solltest du es endlich mal benutzen.',
                       24 =>'Wieso, die könntest du viel eher brauchen.',
                       25 =>'Hattest du das nicht vor kurzem getan?',
                       26 =>'Ich wollte, dass du dich wie zu Hause fühlst.',
                       27 =>'Wieso, die könntest du viel eher brauchen.',
                       28 =>'Dann mach damit nicht rum wie mit einem Staubwedel.',
                       29 =>'Du kannst so schnell davonlaufen?',
                       30 =>'Also mal wieder in der Nase gebohrt, wie?',
                       31 =>'Auch bevor sie deinen Atem riechen.',
                       32 =>'Zu schade, dass dich überhaupt keiner kennt.',
                       33 =>'Das war ja auch leicht, dein Atem hat sie paralysiert!',
                       34 =>'Hoffentlich zerrst du mich nicht ins Separée!',
                       35 =>'Dich zu töten wäre dann eine legale Reinigung!',
                       36 =>'Da hat sich wohl dein Spiegelbild in meinem Säbel reflektiert!',
                       37 =>'Wenn ich mit DIR fertig bin, bist du nur noch Filet!',
                       38 =>'Sollt\' ich in deiner Nähe sterben, möcht\' ich, dass man mich desinfiziert!',
                       39 =>'Dein Geruch allein reicht aus, und ich wär\' kollabiert!',
                       40 =>'Dass ich nicht lache! Du und welche Armee?',
                       41 =>'In Formaldehyd aufbewahrt trügest du bei zu meiner Erheiterung.'
                       );
          // code By Eliwood
          $schonausgwaehltefragen_id = array();
          $schonausgwaehltefragenundantworten = array();
          $awtotal = count($antworten);
          $zustellendeaw = 4; # 3 Fragen stellen
          for($c = 1; $c <= $zustellendeaw; $c++) {
                    while(1) {
                        $fragennummer = mt_rand(1,$awtotal);
                        $_SESSION['session']['richtigeaw'] = $fragennummer;
                        if(!isset($schonausgwaehltefragen_id[$fragennummer])) {
                            $schonausgwaehltefragen_id[$fragennummer] = true;
                            $schonausgwaehltefragenundantworten[$antworten[$fragennummer]] = "<a href=forest.php?op=kampf2&id=".$fragennummer.">`z".$antworten[$fragennummer]."</a>";
                            addnav("","forest.php?op=kampf2&id=".$fragennummer);
                            break;
                        }
                    }
          }
          shuffle($schonausgwaehltefragenundantworten);
          $out .="`b ".WBEPICOLORPISAY."\"".$fragen[$fragennummer]."\"`b`n`n";
          foreach($schonausgwaehltefragenundantworten as $antwort ) {
               $out .=" $antwort `n";
          }

          $session['user']['specialinc']="beleidgterpirat.php";
     break;
     case'ergebnis';
        $out .='`n`n`c'.WBEPICOLORSTANDART2.'Der `^Sieger '.WBEPICOLORSTANDART2.'steht fest.`nEs ist...`n`n';
        $session['user']['turns'] --;
        $session['user']['specialinc']="";
        if ($_SESSION['session']['bpirat']<5){
            //debuglog('Pirat besiegt');
            $expplu = round($session['user']['experience'] * 0.15);
            $session['user']['experience']+=$expplu;
            $out .="`b`&".$session['user']['name'].WBEPICOLORSTANDART2."!`b`n`nDu gewinnst ".WBEPICOLORZAHL."$expplu ".WBEPICOLORSTANDART2."Erfahrung`n
                    und fühlst dich `bunbesiegbar`b!`c`n";
            $buff = array("name"=>"`6Gestärktes Ego`0","rounds"=>10,"wearoff"=>"`5`bDein Hochgefühl vergeht wieder.`b`0","defmod"=>1.8,"roundmsg"=>"Dich kann nichts umhauen, dein Ego ist enorm!","activate"=>"offense");
            $session['bufflist']['pirat']=$buff;
            addnews("".WBEPICOLORSTANDART2."".$session['user']['name']."".WBEPICOLORSTANDART2." hat die Begegnung mit dem ".WBEPICOLORPISAY."Piraten ".WBEPICOLORSTANDART2."glänzend überstanden".WBEPICOLORSTANDART2."");
        } else {
            //debuglog('Pirat verloren');
            $expplu = round($session['user']['experience'] * 0.10);
            $session['user']['experience']-=$expplu;
            $session['user']['hitpoints'] = 1;
            $out .="`b".WBEPICOLORPISAY."Der Pirat!`b`n`n".WBEPICOLORSTANDART2."Du verlierst deswegen ".WBEPICOLORZAHL."$expplu ".WBEPICOLORSTANDART2."Erfahrung`n
                    und `bfast`b dein Leben!`c`n";
            $buff = array("name"=>"`6Demotivation`0","rounds"=>10,"wearoff"=>"`5`bDie Motivation kehrt zurück!`b`0","defmod"=>0.7,"roundmsg"=>"Du hast einfach keinen Bock mehr und willst nur noch nach Hause.","activate"=>"offense");
            $session['bufflist']['pirat']=$buff;
            addnews(WBEPICOLORSTANDART2."Ein ".WBEPICOLORPISAY."WIRKLICH furchteinflößender Pirat ".WBEPICOLORSTANDART2."hat ".$session['user']['name']."".WBEPICOLORSTANDART2." fast zu Tode beleidigt.".WBEPICOLORSTANDART2."");
        }
        unset($_SESSION['session']['bpirat']);
        unset($_SESSION['session']['buser']);
     break;
}
output($out);
?>

