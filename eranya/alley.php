
<?php

/*
*   _________________________________________________________
*  |                                                         
*  | RP-Orte für das Wohnviertel: Gasse, verlassenes Haus    
*  | Zusatz: Schatzsuche im verlassenen Haus (mit Gegner)    
*  | Autor: Silva                                            
*  | Erstellt für Eranya (http://eranya.de/)    
*  |_________________________________________________________
*
*/

require_once 'common.php';

define('ALLEYCOLORTEXT','`d');
define('ALLEYCOLORBOLD','`q');
define('ABANDONEDHOUSECOLORTEXT','`Q');
define('ABANDONEDHOUSECOLORBOLD','`d');

checkday();
addcommentary();

switch($_GET['op'])
{
        // Gasse
        case '':
                page_header('Eine Gasse im Wohnviertel');
                $tout = ALLEYCOLORBOLD."`c`bEine Gasse im Wohnviertel`b`c`n
                        ".ALLEYCOLORTEXT."Auf dem Weg durch das Wohnviertel gelangst du mehr zufällig in diese Gasse.
                        Sie unterscheidet sich kaum von den anderen in diesem Viertel, nur eines ist anders: Die
                        meisten Häuser stehen leer, manchen sieht man es gar an, dass sie nicht mehr bewohnt werden,
                        obwohl die Gasse insgesamt keinen schlechten Eindruck macht.`n
                        ".ALLEYCOLORTEXT."Weiter hinten kannst du den Hafen erkennen, an manchen Tagen sogar das
                        Geschrei der Möwen vernehmen.`n`n";
                $commentary_section = 'alley';
                addnav('Gasse');
                addnav('V?Verlassenes Haus','alley.php?op=abandoned_house');
                addnav('N?Nachtbar "Al Anochecer"','tittytwister.php');
                addnav('H?Zum Hafen','harbor.php');
                /*addnav('Privater Ort');
                addnav('Dunkler Hinterhof','privplaces.php?rport=byard');*/
                addnav('Zurück');
                addnav('b?Zum Stadtbrunnen','well.php');
                addnav('W?Zum Wohnviertel','houses.php');
                addnav('S?Zum Stadtplatz','village.php');
        break;
        // verlassenes Haus
        case 'abandoned_house':
                page_header('Verlassenes Haus');
                // Hat User schon nach Schätzen gesucht?
                $sql = "SELECT acctid,alleypick FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                // end
                switch($_GET['act'])
                {
                        case '':
                                $tout = ABANDONEDHOUSECOLORBOLD."`c`bVerlassenes Haus`b`c`n".ABANDONEDHOUSECOLORTEXT."
                                        Zufällig bemerkst du ein Haus, dessen Tür mit Brettern vernagelt ist. Dennoch kannst du einige 
                                        Fenster erkennen, deren Scheiben längst gesprungen sind, durch die du ins Innere gelangen könntest. 
                                        Vielleicht würdest du auch einfach die Bretter wegreißen können, immerhin macht das gesamte Haus keinen 
                guten Eindruck, der Putz bröckelt von den Wänden, die kaum mehr einen ansehnlichen Eindruck machen. 
                Trotzdem wäre es doch sicher interessant, einen Blick hineinzuwerfen...`n
                                        ".ABANDONEDHOUSECOLORTEXT."Als du dir deinen Weg hinein gesucht hast, stehst du in einem 
                einzigen, recht großen Raum, in dem kaum Möbel zu finden sind, ein Bett und ein Stuhl sind alles, was du 
                sehen kannst. Und eine weitere Tür, die 
                                        ".($row['alleypick']?"allerdings verschlossen ist":"in den nächsten Raum führt").".`n`n";
                                $commentary_section = 'verlassenes_haus';
                                if ($row['alleypick'] == 0)
                                {
                                        addnav('Haus');
                                        addnav('N?Nächste Tür öffnen','alley.php?op=abandoned_house&act=look1');
                                }
                                addnav('Zurück');
                                addnav('d?Nach draußen','alley.php');
                        break;
                        // Hoch in den zweiten Stock
                        case 'look1':
                                // Zusatzkontrolle - doppelt hält besser (;
                                if ($row['alleypick'] == 1)
                                {
                                        redirect('alley.php?op=abandoned_house');
                                }
                                else
                                {
                                        $tout = ABANDONEDHOUSECOLORTEXT."Du trittst an die verschlossene Tür heran und drückst
                                                sie auf. Dahinter befindet sich ein weiterer kleiner Raum, von dem aus eine
                                                Wendeltreppe hinauf in den zweiten Stock führt. Dein Schatzsucherinstinkt meldet
                                                sich und raunt dir zu, dass du dich dort oben doch gut nach irgendwelchen
                                                Wertsachen umschauen könntest...";
                                        addnav('o?Auf nach oben!','alley.php?op=abandoned_house&act=look2');
                                        addnav('H?Hmm, lieber nicht','alley.php?op=abandoned_house');
                                }
                        break;
                        // Zweiter Stock
                        case 'look2':
                                output(ABANDONEDHOUSECOLORTEXT."
                                        Der Gedanke an mögliche Schätze siegt, und so näherst du dich der Treppe und
                                        gehst hinauf in den zweiten Stock. Oben findest du einen weiteren Raum vor, der
                                        ebenfalls kaum möbliert ist - diesmal jedoch entdeckst du bei genauem Hinsehen
                                        eine Truhe, die hinten an der gegenüberliegenden Wand steht. ");
                                // User verliert fast alle HP
                                if (e_rand(1,4) == 1)
                                {
                                        output("Ohne zu zögern machst du dich daran, den Raum zu durchqueren, doch
                                                 plötzlich hörst du es verdächtig unter dir knacken. Sofort bleibst du
                                                 stehen - doch zu spät: Im nächsten Moment brechen die Dielen unter dir
                                                 weg und du fällst mit ihnen in die Tiefe.`n`n
                                                 `^Glücklicherweise stand genau unter dir das Bett. Du landest zwar so
                                                 hart, dass dieses zu Bruch geht, doch bist du dafür mit dem Leben
                                                 davongekommen.");
                                        $session['user']['hitpoints'] = 1;
                                        addnav('Weiter','alley.php?op=abandoned_house');
                                }
                                // User stirbt
                                elseif (e_rand(1,4) == 2)
                                {
                                        output("Ohne zu zögern machst du dich daran, den Raum zu durchqueren, doch
                                                 plötzlich hörst du es verdächtig unter dir knacken. Sofort bleibst du
                                                 stehen - doch zu spät: Im nächsten Moment brechen die Dielen unter dir
                                                 weg und du fällst mit ihnen in die Tiefe.`n`n
                                                 `4Du bist tot. Du verlierst 10% deiner Erfahrung und all dein Gold.");
                                        addnews($session['user']['name']." `4hat sich in ein einsturzgefährdetes Haus gewagt 
                                                        und ist nun tot.");
                                        killplayer(100,10);
                                }
                                // Kampf mit anderem Schatzsucher
                                else
                                {
                                        output("Sogleich machst du dich daran, den Raum zu durchqueren, doch
                                                 plötzlich hörst du es neben dir knacken. Instinktiv lässt du
                                                 dich zur Seite fallen, wirbelst im Fall noch herum - und
                                                 entgehst so um Haaresbreite der Schwertschneide, die dich wohl
                                                 andernfalls zweigeteilt hätte. Da hatte wohl jemand die
                                                 gleiche Idee gehabt wie du - und steht dir nun mit grimmiger
                                                 Miene gegenüber, bereit zum Kampf.");
                                        // Badguy
                                        $badguy = array(
                                                    "creaturename"=>"`qSchatzsucher`0",
                                                    "creaturelevel"=>$session['user']['level']+1,
                                                    "creatureweapon"=>"Breitschwert",
                                                    "creatureattack"=>$session['user']['attack']+3,
                                                    "creaturedefense"=>$session['user']['defence']+3,
                                                    "creaturehealth"=>800+$session['user']['maxhitpoints'],
                                                    "diddamage"=>0);
                                        $session['user']['badguy'] = createstring($badguy);
                                        addnav('Kämpfen!','alley_battle.php?op=fight');
                                }
                                $sql = "UPDATE account_extra_info SET alleypick=1 WHERE acctid = ".$session['user']['acctid'];
                                db_query($sql) or die(sql_error($sql));
                        break;
                        // Truhe öffnen
                        case 'look3':
                                $tout = ABANDONEDHOUSECOLORTEXT."
                                        Nun ist der Weg frei. Schnell durchquerst du den Raum und machst dich anschließend an dem
                                        Schloss der Truhe zu schaffen. Als dieses sich endlich öffnet und du den Deckel anheben kannst,
                                        findest du...`n`n";
                                switch(e_rand(1,6))
                                {
                                        // Gold
                                        case 1:
                                        case 2:
                                        case 3:
                                                $tout .= "`^3500 Goldstücke";
                                                $session['user']['gold'] += 3500;
                                        break;
                                        // Edelsteine
                                        case 4:
                                        case 5:
                                                $tout .= "`#10 Edelsteine";
                                                $session['user']['gems'] += 10;
                                        break;
                                        // beides
                                        case 6:
                                                $tout .= "`^3500 Goldstücke ".ABANDONEDHOUSECOLORTEXT."und `#10 Edelsteine";
                                                $session['user']['gold'] += 3500;
                                                $session['user']['gems'] += 10;
                                        break;
                                }
                                $tout .= ABANDONEDHOUSECOLORTEXT.", die du glücklich einsteckst. Dann kehrst du wieder ins
                                         Erdgeschoss zurück.`n`n";
                                $sql = "UPDATE account_extra_info SET alleypick=1 WHERE acctid = ".$session['user']['acctid'];
                                db_query($sql) or die(sql_error($sql));
                                addnav('E?Ins Erdgeschoss','alley.php?op=abandoned_house');
                        break;
                }
        break;
        // Debug
        default:
                page_header();
                $tout = "Nanu! Was machst du denn hier?! Schnell zurück zum Spiel!";
                addnav('Zurück','alley.php');
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

