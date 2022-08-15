
<?php
/* * *
 *    Private RP-Orte für je max. 2 User
 *    erstellt für: http://eranya.de
 *    Autor: Silva
 *    ---------------------------------------------------------------------------------------------
 *    Verlinkung allg.: addnav('ORTSNAME','privplaces.php?rport=ORTSNAME-KÜRZEL');
 *    Bisher vorhanden: orchids.php -> addnav('Die heiße Quelle','privplaces.php?rport=spring');
 *                      inn.php -> addnav('Schenkenzimmer','privplaces.php?rport=room');
 *                      alley.php -> addnav('Dunkler Hinterhof','privplaces.php?rport=byard');
 *                      woods.php -> addnav('Zum Hügelgrab','privplaces.php?rport=grave');
 * * */

require_once('common.php');
$str_filename = basename(__FILE__);

// Welcher RP-Ort?
if(empty($session['rport'])) {
    if(strlen($_GET['rport']) < 2) {
        page_header();
        output("`&Ups, nun ist es zu einem Fehler gekommen. Schicke bitte folgende Info ans E-Team, zusammen mit einer kurzen Beschreibung, was du unmittelbar vor
                dieser Meldung getan hast:`n
                `n
                `^\"kein get 'rport' in ".$str_filename."\"");
        addnav('Zurück','village.php');
        page_footer();
        exit;
    } else {
        $session['rport'] = $_GET['rport'];
    }
} elseif(strlen($_GET['rport']) > 1 && $session['rport'] != $_GET['rport']) {
    $session['rport'] = $_GET['rport'];
}
// Orte-Details (Name & Beschreibung)
$arr_orteinfos = array('spring'=>array('name'=>'`{Bei der heißen Quelle',
                                     'desc'=>'`|Ein Stück weiter gabelt sich der Weg erneut und führt dich tiefer in den Wald hinein, weg von der Stadt und auch fort
                                              vom Meer. Eine ganze Weile lang bist du unterwegs, als der Boden plötzlich ansteigt und sich der Wald mit einem Mal
                                              teilt, um den Blick auf eine weite Lichtung freizugeben, in deren Mitte ein kleiner See liegt. Ein See? Nein,
                                              vielmehr eine kleine Ansammlung von Wasser, von der aus ein dünnes Rinnsal weiter in den Wald hinein verläuft.
                                              Du siehst dich um, doch eine Quelle kannst du nicht erblicken. Ob sie wohl unterirdisch liegt? Schnell näherst du
                                              dich dem See und tauchst testweise die Hand ins Wasser. Tatsächlich, es ist erstaunlich warm. Eine Thermalquelle
                                              mitten im Wald - völlig abgelegen von der Stadt. Das lädt doch geradezu zu einem Bad ein, meinst du nicht?',
                                     'ret'=>'orchids.php'
                                    )
                      ,'room'=>array('name'=>'`YIm Schenkenzimmer',
                                     'desc'=>'`ÈWahrlich, dieses Zimmer wird allem gerecht, was Marek den Leuten stets verspricht. Neben einem Stuhl ohne Tisch,
                                              einem Schrank ohne Bügel und einem Fenster mit fast blinden Scheiben steht hier außerdem noch ein Bett. Immerhin
                                              scheint dieses frisch bezogen und insektenfrei zu sein - das Mindeste, was man für den nicht unbedingt niedrigen
                                              Preis erwarten kann.',
                                     'ret'=>'inn.php'
                                    )
                      ,'byard'=>array('name'=>'`ÿDunkler Hinterhof',
                                     'desc'=>'`ÝEine unscheinbare Tür führt dich auf direktem Wege in einen kleinen Hinterhof, der anscheinend nach Norden
                                              ausgerichtet, denn er liegt immer im Schatten - was auch das ganze Moos auf den Pflastersteinen erklären mag. In
                                              einer Ecke erkennst du zwei leere Blumenkübel, von denen ein dritter umgedreht nahe der Hauswand steht - wohl
                                              als improvisierte Sitzgelegenheit. Die Geräusche der Gasse und umliegenden Häuser werden von hohen Mauern verschluckt. Fast
                                              erhält der Hinterhof dadurch etwas Gespenstisches... Und das selbst am Tag. Welch sonderbarer Ort...',
                                     'ret'=>'alley.php'
                                    )
                      ,'grave'=>array('name'=>'`ÇDas Hügelgrab',
                                     'desc'=>'`GFast könnte man den kleinen Hügel für eine Laune der Natur halten, der sich mitten im Wald zwischen den Bäumen
                                              erhebt. Er ist gerade so hoch, dass ein hochgewachsener Mann nicht mehr über ihn hinüber schauen kann, und kommt auf
                                              einen Durchmesser von guten zehn Schritten. Gras und Blumen, durchzogen von Moos und allerlei Unkraut,
                                              überwuchern den von Menschenhand aufgehäuften Erdwall, bei dem lediglich die schmale und überraschend niedrige
                                              Holztür verrät, dass hier wohl jemand seine letzte Ruhe gefunden hat. Wer es ist, verrät weder Schild noch Symbol -
                                              lediglich eine kleine Sonne ziert das riesige, alte Schloss, das Grabräuber davon abhalten soll, das Innere zu
                                              betreten.',
                                     'ret'=>'woods.php'
                                    )
                      );
$arr_rport = $arr_orteinfos[$session['rport']];
$str_ret = (strlen($arr_rport['ret']) > 2 ? $arr_rport['ret'] : 'village.php');
// end
page_header('Privater RP-Ort');
// RP-Ort auch im array vorhanden? -> sicherheitshalber kontrollieren
if(strlen($_GET['rport']) > 1 && !array_key_exists($_GET['rport'],$arr_orteinfos)) {
    output("`&Ups, nun ist es zu einem Fehler gekommen. Schicke bitte folgende Info ans E-Team, zusammen mit einer kurzen Beschreibung, was du unmittelbar vor
            dieser Meldung getan hast:`n
            `n
            `^\"kein get 'rport' im Orte-Array in ".$str_filename."\"");
    addnav('Zurück',$str_ret);
    page_footer();
    exit;
}
// end
$op = (isset($_GET['op']) ? $_GET['op'] : '');
switch($op) {
    case '':
        $sql = db_query("SELECT * FROM items WHERE tpl_id='privplce_e' AND description='".$session['rport']."' AND owner=".$session['user']['acctid']." LIMIT 1");
        // Wenn noch keine Einladung verschickt -> Weiterleitung
        if(db_num_rows($sql) == 0) {
            redirect($str_filename.'?op=invite');
        // sonst RP-Ort anzeigen
        } else {
            $row = db_fetch_assoc($sql);
            // Name des Mitspielers aus DB holen
            $int_partner = ($row['value1'] == $session['user']['acctid'] ? $row['value2'] : $row['value1']);
            $row2 = db_fetch_assoc(db_query("SELECT a.name,i.owner FROM accounts a LEFT JOIN items i ON a.acctid=i.owner
                                                    WHERE a.acctid=".$int_partner." AND i.tpl_id='privplce_e' AND description='".$session['rport']."' AND i.owner=".$int_partner));
            // Beschreibung & Kommentarfeld
            addcommentary();
            output("`c`b".$arr_rport['name']."`b`c`n".$arr_rport['desc']."`n`n`i"
                   .(!empty($row2['name']) ? "Du teilst diesen RP-Ort mit ".$row2['name'] : "`4Dein Mitspieler hat diesen RP-Ort bereits verlassen.")
                   ."`i`n`n");
            viewcommentary('p_'.$session['rport'].'_'.$row['value1'].'_'.$row['value2']);
            addnav('Verwalten');
            addnav('Einladung zurückgeben',$str_filename.'?op=del');
        }
    break;
    case 'invite':
        // Char ausgesucht? Dann einladen, ...
        if((int)$_POST['uid'] > 0) {
            $int_id = $_POST['uid'];
            $int_rowcount = db_num_rows(db_query("SELECT owner FROM items WHERE tpl_id='privplce_e' AND description='".$session['rport']."' AND owner=".$int_id." LIMIT 1"));
            if($int_rowcount > 0) {
                output("Dieser Spieler hat bereits von einem anderen Spieler eine Einladung zu diesem RP-Ort erhalten und kann deshalb nicht erneut eingeladen
                        werden.");
                addnav('Zurück',$str_filename.'?op=invite');
            } else {
                $row2 = db_fetch_assoc(db_query("SELECT acctid,name FROM accounts WHERE acctid = ".$int_id));
                systemmail($row2['acctid'],"`%Einladung zu einem privaten RP-Ort","`&".$session['user']['name']." `7hat dir eine Einladung zum RP-Ort \"".$arr_rport['name']."`7\" zukommen lassen.
                           An diesem Ort seid ihr beide ganz unter euch, niemand wird eure Zweisamkeit stören.`n");
                $item_tpl['tpl_value1'] = $session['user']['acctid'];
                $item_tpl['tpl_value2'] = $row2['acctid'];
                $item_tpl['tpl_description'] = $session['rport'];
                item_add($session['user']['acctid'],'privplce_e',$item_tpl);
                item_add($row2['acctid'],'privplce_e',$item_tpl);
                output("Die Einladung wurde an ".$row2['name']." übermittelt, und auch du hast deine Einladung erhalten.");
                addnav('Weiter');
                addnav('Zum RP-Ort',$str_filename);
            }
        // ... sonst Char-Auswahl
        } else {
            $tout = 'Mit wem möchtest du dir diesen RP-Ort teilen? Wähle weise, denn mehr als zwei Personen haben hier nicht Platz.`n
                     Euch beiden wird anschließend eine Einladung zugeschickt.`n`n';
            if($_GET['act'] == 'search') {
                    $count = strlen($_POST['username']);
                    $search = "%";
                    for ($x=0;$x<$count;$x++){
                            $search .= substr($_POST['username'],$x,1)."%";
                    }
                    $where = (is_numeric($_POST['username']) ? 'acctid='.$_POST['username'] : 'name LIKE "'.$search.'" AND acctid!='.$session['user']['acctid']);
                    $sql = 'SELECT acctid,name FROM accounts WHERE '.$where.' AND locked=0 ORDER BY login ASC';
                    $result = db_query($sql);
                    $int_result_count = db_num_rows($result);
                    if($int_result_count == 0) {
                        $tout .= '`7Diese Person konnte nicht gefunden werden.`n';
                    } else {
                        $tout .= '<form action="'.$str_filename.'?op=invite" method="post">
                                  <select name="uid">';
                        for ($i=0;$i<$int_result_count;$i++)
                        {
                            $row = db_fetch_assoc($result);
                            $tout .= '<option value="'.$row['acctid'].'">'.$row['name'].'</option>';
                        }
                        $tout .= '</select>`n`n
                                  <input type="submit" class="button" value="Einladen">
                                  </form>';
                        addnav('',$str_filename.'?op=invite');
                    }
                    addnav('Korrektur');
                    addnav('Neue Suche',$str_filename.'?op=invite');
            } else {
                    // Suche
                    $tout .= "<form action='".$str_filename."?op=invite&act=search' method='post'>Charakter suchen: <input type='text' name='username'> <input class='button' type='submit' value='Suchen'></form>`n";
                    addnav('',$str_filename.'?op=invite&act=search');
                    // end
            }
            output($tout);
        }
    break;
    case 'del':
        $sql = db_query("SELECT * FROM items WHERE value1=".$session['user']['acctid']." AND description = '".$session['rport']."'");
        $int_count = db_num_rows($sql);
        // Auftrag bestätigt? Dann Einladung (+ ggf. Kommentare) aus DB löschen
        if($_GET['confirm'] == 1) {
            output("`@Du hast ab sofort keinen Zutritt mehr zu diesem RP-Ort.");
            if($int_count == 1) {
                $row = db_fetch_assoc($sql);
                // Kommentare löschen
                db_query("DELETE FROM commentary WHERE section = 'privat_".$session['rport']."_".$row['value1']."_".$row['value2']."'");
                // Einladung löschen
                db_query("DELETE FROM items WHERE tpl_id = 'privplce_e' AND description = '".$session['rport']."' AND owner = ".$session['user']['acctid']);
                output(" `@Alle Kommentare wurden gelöscht.");
            } else {
                // Einladung
                db_query("DELETE FROM items WHERE tpl_id = 'privplce_e' AND description = '".$session['rport']."' AND owner = ".$session['user']['acctid']);
            }
        // sonst Hinweis, dass RP-Ort mitsamt Kommentaren unwiderruflich gelöscht wird
        } else {
            output("`^Hast du dir das wirklich gut überlegt? Du wirst anschließend keine Möglichkeit mehr haben, deine RPs zu speichern.");
            if($int_count == 1) {
                output(" `bAlle`b Kommentare dieses RP-Orts werden gelöscht.");
            }
            addnav('Zurückgeben');
            addnav('Ja, Einladung zurückgeben',$str_filename.'?op=del&confirm=1');
            addnav('Nein, zurück',$str_filename);
        }
    break;
    case 'leave':
        unset($session['rport']);
        redirect($str_ret);
    break;
    default:
        output("`&Nanu, was machst du denn hier? Sende bitte folgenden Satz via Anfrage an das Adminteam:`n
                `n
                `^op: ".$op." in ".$str_filename." nicht vorhanden.");
        addnav('Alles auf Anfang',$str_filename);
    break;
}
addnav('Zurück');
addnav('Ort verlassen',$str_filename.'?op=leave');

page_footer();
?>

