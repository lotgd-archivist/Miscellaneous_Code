
<?php
/* * * * *
 * Suche nach Strandgut
 * Autor: Silva
 * erstellt für eranya.de
 * * * * */
require_once 'common.php';
define('BEACHCOLORTEXT','`I');
page_header('Nach Strandgut suchen');
// Angelrunden abfragen
$sql = "SELECT fishturn FROM account_extra_info WHERE acctid=".$session['user']['acctid'];
$result = db_query($sql) or die(db_error(LINK));
$rowf = db_fetch_assoc($result);
$fishturn = $rowf['fishturn'];
// end
// Nach Strandgut suchen
switch($_GET['op'])
{
        case '':
                $tout = BEACHCOLORTEXT."Ob es wohl stimmt, was man sich über die unendlichen Schätze des Meeres erzählt? ";
                if($fishturn > 0)
                {
                    $tout .= "Finde es heraus!`n
                              `n
                              Angelrunden: ".$fishturn."`n`n";
                    addnav('Suchen');
                    addnav('Strand absuchen ('.$fishturn.' Runden übrig)','beach.php?op=search');
                } else {
                    $tout .= "Vielleicht ja, aber du wirst das heute wohl kaum herausfinden können. Dafür fehlt die dir nötige Zeit und Geduld, weshalb du dich
                              doch wieder auf den Weg zurück Richtung Stadt machst. Vielleicht solltest du in ein paar Stunden wieder hierher zurückkommen?`n`n
                              `i`7(Deine Angelrunden für den Tag sind verbraucht. Komm morgen wieder, um hier dein Glück zu versuchen!)`i`n`n";
                }
        break;
        case 'search':
                $tout = BEACHCOLORTEXT."Du suchst den Strand sorgfältig nach Dingen ab, die dir wertvoll erscheinen, und findest...`n`n";
                $found = e_rand(1,35);
                switch($found)
                {
                        // Tod I
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                                $tout .= "...eine RIESIGE Kiste, die noch im Wasser treibt. Das wäre der Fang deines Lebens! Sofort wirfst
                                          du dich ins kalte Nass, trotz des starken Winds.. und der hohen Wellen...`n
                                          Tja, keine kluge Entscheidung. Eine besonders hohe Welle erfasst dich, reißt dich mit - und zieht dich unter
                                          Wasser. Du verlierst prompt die Orientierung - und ertrinkst jämmerlich.`n`n
                                          Diese unüberlegte Aktion kostet dich 5% deiner Erfahrung.`n`n";
                                $session['user']['experience']*=0.95;
                                $session['user']['hitpoints']=0;
                                $fishturn=0;
                                addnews($session['user']['name'].' `5ist beim Suchen nach Strandgut jämmerlich ertrunken.');
                                addnav('Hallo, Jarcath');
                                addnav('Verdammt!','shades.php');
                        break;
                        // Kein Fund I
                        case 5:
                        case 6:
                                $tout .= "... nichts. Nur feinen, weißen und völlig wertlosen Sand.`n`n";
                                $fishturn--;
                        break;
                        // Krebs
                        case 7:
                                $tout .= "... einen kleinen Krebs - der sich schmerzhaft an deinen großen Zeh gehängt hat. Na, das wird dir das Kerlchen
                                          aber büßen! Einmal gut geschüttelt, und schon kannst du ihn ohne Probleme in deinen Beutel stecken.`n`n";
                                $itemnew = item_get_tpl('tpl_id="fsh_crab"');
                                if( is_array($itemnew) )
                                {
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Muschel
                        case 8:
                                $tout .= "... eine fingergroße Muschel. Sie ist fest verschlossen, doch das macht dir nichts. Der Fischhändler wird
                                          dir bestimmt trotzdem etwas dafür geben.`n`n";
                                $itemnew = item_get_tpl('tpl_id="fsh_shell"');
                                if( is_array($itemnew) )
                                {
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Kein Fund II
                        case 9:
                        case 10:
                                $tout .= "... eine Kiste. Leider fehlt ihr bereits der Deckel - und innen drin herrscht nur noch gähnende Leere. Da war wohl
                                          jemand schneller als du. Schade.`n`n";
                                $fishturn--;
                        break;
                        // Treibholz
                        case 11:
                                $tout .= "... ein Stück Holz. Es ist völlig durchnässt und mit einer klebrigen Sandschicht überzogen - doch um nicht
                                          gänzlich mit leeren Händen dazustehen, packst du es kurzerhand in deinen Beutel.`n`n";
                                $itemnew = item_get_tpl('tpl_id="fsh_wood"');
                                if( is_array($itemnew) )
                                {
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Leere Flasche
                        case 12:
                                $tout .= "... eine leere Flasche. Was die Leute alles ins Meer werfen...`n`n";
                                $itemnew = item_get_tpl('tpl_id="emptybttle"');
                                if( is_array($itemnew) )
                                {
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Müll
                        case 13:
                        case 14:
                                $itemnew = item_get_tpl('tpl_id="fsh_trash"');
                                if( is_array($itemnew) )
                                {
                                        $rand = e_rand(1,3);
                                        if($rand == 1)
                                        {
                                                $tout .= "... einen mit Löchern übersäten Fes. Hmm, einen dieser Form, Farbe und Größe hast du schonmal
                                                          irgendwo gesehen...`n`n";
                                                $itemnew['tpl_name'] = 'Löchriger Fes';
                                                $itemnew['tpl_description'] = 'Der hat bestimmt mal Ismail gehört.';
                                        }
                                        elseif($rand == 2)
                                        {
                                                $tout .= "... eine Flasche, der der Boden fehlt. Was die Leute alles ins Meer werfen...`n`n";
                                                $itemnew['tpl_name'] = 'Flasche ohne Boden';
                                                $itemnew['tpl_description'] = 'In die kann man wohl nichts mehr füllen. Und scharfkantig ist sie auch noch...';
                                        }
                                        else
                                        {
                                                $tout .= "... eine zerbrochene Angelrute. Schade um das gute Stück. Aber vielleicht ist sie ja auch so noch
                                                          etwas wert.`n`n";
                                                $itemnew['tpl_name'] = 'Zerbrochene Angelrute';
                                                $itemnew['tpl_description'] = 'Mit der wirst du wohl nichts mehr anfangen können. Sie taugt höchstens noch als Feuerholz.';
                                        }
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Aquamarin
                        case 15:
                        case 16:
                                $tout .= "... einen Edelstein, der so blau leuchtet wie das weite Meer. Sein Funkeln fängt deinen Blick sofort ein -
                                          kaum zu glauben, dass du solch großes Glück hattest!`n`n";
                                $itemnew = item_get_tpl('tpl_id="fsh_gem"');
                                if( is_array($itemnew) )
                                {
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Frachtkiste I
                        case 17:
                                $tout .= "... eine Kiste, die gerade von einer Welle an Land gespült wird. Schnell ziehst du sie ein wenig weiter vom
                                          Wasser weg und rüttelst und ziehst einige Male am Deckel, bis dieser schlussendlich nachgibt - und du in die
                                          Kiste hineinschauen kannst. Darin befindet sich ";
                                // zufällig ausgewähltes Item
                                $sql = db_query('SELECT COUNT(*) AS cnt FROM items_tpl WHERE tpl_class=3 OR tpl_class=24');
                                $res = db_fetch_assoc($sql);
                                $cnt = e_rand(1,($res['cnt']-1));
                                $sql2 = db_query('SELECT * FROM items_tpl WHERE tpl_class= 3 OR tpl_class=24 LIMIT '.$cnt.',1');
                                $itemnew = db_fetch_assoc($sql2);
                                if( is_array($itemnew) )
                                {
                                        $tout .= '`F'.$itemnew['tpl_name'].BEACHCOLORTEXT.", was du natürlich sofort in deinen Beutel steckst.`n`n";
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Kein Fund III
                        case 18:
                        case 19:
                        case 20:
                                $tout .= "... nichts. Nur feinen, weißen und völlig wertlosen Sand.`n`n";
                                $fishturn--;
                        break;
                        // Tod II
                        case 21:
                        case 22:
                        case 23:
                        case 24:
                                $tout .= "...nichts. Hrm! Wütend trittst du gegen den nächstbesten Ast, der aus dem Wasser ragt. ..Und seltsam nachgibt, als
                                          dein Fuß dagegen stößt. Ein Zucken, ein kurzer Ruck - und spätestens dann wird dir klar, dass das eben
                                          definitiv `ikein`i Ast war, an dem du deine Wut ausgelassen hast. ..Hast du schonmal von dem Kraken gehört?
                                          Bisher dachte man immer, er wäre nur eine schreckliche Legende... Tja, nun weißt du es besser.`n
                                          Du bist tot.`n`n";
                                $session['user']['hitpoints']=0;
                                $fishturn=0;
                                addnews($session['user']['name'].' `5hat sich mit dem Kraken angelegt und ist nun tot.');
                                addnav('Hallo, Jarcath');
                                addnav('Argh!','shades.php');
                        break;
                        // Qualle
                        case 25:
                        case 26:
                                $tout .= "... eine tote Qualle - auf die du unachtsamerweise getreten bist. Nicht nur, dass du kurzzeitig das Gefühl
                                          hast, in Pudding getreten zu sein - nein. Dein Fuß beginnt auch prompt zu brennen. Super.`n
                                          Trotzdem steckst du die Qualle ein. Vielleicht ist sie ja ein paar Goldstücke wert...`n`n";
                                $itemnew = item_get_tpl('tpl_id="fsh_jllfsh"');
                                if( is_array($itemnew) )
                                {
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Perle
                        case 27:
                        case 28:
                        case 29:
                                $tout .= "... eine handgroße Muschel. Sie ist schon ansatzweise offen - weswegen es dir keine Mühe bereitet, sie ganz
                                          aufzubrechen.. und eine schimmernde Perle herauszuholen.`n`n";
                                $itemnew = item_get_tpl('tpl_id="fsh_pearl"');
                                if( is_array($itemnew) )
                                {
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Frachtkiste II
                        case 30:
                                $tout .= "... eine Kiste, die gerade von einer Welle an Land gespült wird. Schnell ziehst du sie ein wenig weiter vom
                                          Wasser weg und rüttelst und ziehst einige Male am Deckel, bis dieser schlussendlich nachgibt - und du in die
                                          Kiste hineinschauen kannst. Darin befindet sich ";
                                // zufällig ausgewähltes Item
                                $sql = db_query('SELECT COUNT(*) AS cnt FROM items_tpl WHERE (tpl_class=3 OR tpl_class=24) AND tpl_gems > 0');
                                $res = db_fetch_assoc($sql);
                                $cnt = e_rand(1,$res['cnt']);
                                $sql2 = db_query('SELECT * FROM items_tpl WHERE (tpl_class= 3 OR tpl_class=24) AND tpl_gems > 0 LIMIT '.$cnt.',1');
                                $itemnew = db_fetch_assoc($sql2);
                                if( is_array($itemnew) )
                                {
                                        $tout .= $itemnew['tpl_name'].BEACHCOLORTEXT.", was du natürlich sofort in deinen Beutel steckst.`n`n";
                                        item_add( $session['user']['acctid'], 0, $itemnew);
                                }
                                $fishturn--;
                        break;
                        // Kein Fund IV
                        case 31:
                        case 32:
                        case 33:
                        case 34:
                        case 35:
                        default:
                                $tout .= "... eine Kiste. Leider fehlt ihr bereits der Deckel - und innen drin herrscht nur noch gähnende Leere. Da war wohl
                                          jemand schneller als du. Schade.`n`n";
                                $fishturn--;
                        break;
                }
                db_query("UPDATE account_extra_info SET fishturn=".$fishturn." WHERE acctid = ".$session['user']['acctid']);
                if($fishturn > 0)
                {
                        addnav('Suchen');
                        addnav('Strand absuchen ('.$fishturn.' Runden übrig)','beach.php?op=search');
                }
        break;
        default:
                $tout = "`&Nanu! Was machst du denn hier? Hm, anscheinend ist etwas schief gegangen. Schicke bitte folgenden Satz via Anfrage ans
                         Admin-Team:`n
                         `n
                         `^op: ".$_GET['op']." in beach nicht vorhanden.";
        break;
}
if($session['user']['hitpoints'] > 0)
{
        addnav('Zurück');
        addnav('Zum Strand','harbor.php?op=beach');
        addnav('Zum Hafen','harbor.php');
}
// end
// Textausgabe & Abschluss
output($tout,true);
page_footer();
?>

