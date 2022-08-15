
<?php
// Magierhauptquartier: Umbau der waldlichtung.php, Umzug ins Stadtamt, Magier können nicht länger Verheiraten / Scheiden 

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
require_once(LIB_PATH.'profession.lib.php');

page_header("Der Magierzirkel");

if (!isset($session)) exit();

$op = ($_GET['op']) ? $_GET['op'] : "hq";

function show_mage_list($admin_mode=0)
{
    global $session;

    $sql = "SELECT a.name,a.profession,a.acctid,a.login,a.loggedin,a.laston,a.activated FROM accounts a
WHERE a.profession=".PROF_MAGE_HEAD." OR a.profession=".PROF_MAGE;
    $sql .= ($admin_mode>=1) ? " OR a.profession=".PROF_MAGE_NEW : "";
    $sql .= " ORDER BY profession ASC, name";

    $res = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($res) == 0)
    {
        output("`n`iEs gibt keine Magier!`i`n");
    }
    else
    {
        output('<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999"><tr class="trhead"><td>Nr.</td><td>Name</td><td>Funktion</td><td>Status</td></tr>',true);

        for ($i=1; $i<=db_num_rows($res); $i++)
        {

            $p = db_fetch_assoc($res);
            if($session['user']['prefs']['popupbio'] == 1)
            {
                $link = "bio_popup.php?char=".rawurlencode($p['login']);
                $str_link = '<a href="'.$link.'" target="_blank" onClick="'.popup_fullsize($link).';return:false;">';
            }
            else
            {
                $link = "bio.php?char=".rawurlencode($p['login']) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                addnav("",$link);
                $str_link = '<a href="'.$link.'">';
            }

            output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td><a href="mail.php?op=write&to='.rawurlencode($p['login']).'" target="_blank" onClick="'.popup("mail.php?op=write&to=".rawurlencode($p['login']) ).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>'.$str_link.$p['name'].'</a></td><td>`7',true);

            switch ($p['profession'] )
            {

            case PROF_MAGE_HEAD:
                output('`bErzmagier/in`b');
                if ($admin_mode>=4)
                {

                    output('`n<a href="mage.php?op=hohep_deg&id='.$p['acctid'].'">Grad abnehmen</a>',true);
                    addnav("","mage.php?op=hohep_deg&id=".$p['acctid']);
                }
                break;

            case PROF_MAGE:
                output('Magier/in');
                if ($admin_mode>=3)
                {
                    output('`n<a href="mage.php?op=entlassen&id='.$p['acctid'].'">Verstossen</a>',true);
                    addnav("","mage.php?op=entlassen&id=".$p['acctid']);

                    if ($admin_mode>=4)
                    {
                        output('`n<a href="mage.php?op=hohep&id='.$p['acctid'].'">Weihe zum Erzmagier</a>',true);
                        addnav("","mage.php?op=hohep&id=".$p['acctid']);
                    }
                }
                break;

            case PROF_MAGE_NEW:
                output('Schüler/in');
                if ($admin_mode>=3)
                {
                    output('`n<a href="mage.php?op=aufn&id='.$p['acctid'].'">Initiieren</a>',true);
                    addnav("","mage.php?op=aufn&id=".$p['acctid']);

                    output('`n<a href="mage.php?op=abl&id='.$p['acctid'].'">Ablehnen</a>',true);
                    addnav("","mage.php?op=abl&id=".$p['acctid']);
                }
                break;

                default:
                break;
            }

            output('</td><td>'.(user_get_online(0,$p)?'`@online`&':'`4offline`&').'</td></tr>',true);

        }
        // END for

        output('</table>',true);

    }
    // END magier vorhanden

}
// END show_mage_list

function show_rules()
{

    output("`4I. `&Als Diener der Gesellschaft ist der Zirkel dem Wohl der Gemeinschaft verpflichtet und jedes seiner Mitglieder hat diese Aufgabe wahrzunehmen und in ihrem Interesse zu agieren.`n");
    output("`4II. `&Die Zuständigkeit des Zirkels und dessen Befugnisse konzentrieren sich darauf, übernatürliche Phänomene aufzuspüren, zu untersuchen, einzuordnen und - dies ist als höchste Priorität anzusehen, sollte die Notwendigkeit bestehen -, unschädlich zu machen.`n");
    output("`4III. `&Die Mitglieder des Zirkels haben sich wie alle anderen Einwohner der Stadt dem Gesetz unterzuordnen. Brechen sie die so gesetzten Regeln des Zusammenlebens, haben sie sich dafür zu verantworten.`n");
    output("`4IV. `&Es steht dem Erzmagier - als oberste Autorität des Zirkels - frei, Mitglieder nach eigenem Ermessen einzuberufen.`n");
    output("`4V. `&Der Zirkel arbeitet in Kooperation mit den anderen Ämtern der Stadt. Reger Austausch mit deren Würdenträgern ist demzufolge erwünscht.`n");    
}
// END show_rules

$mage = 0;
if (su_check(SU_RIGHT_DEBUG))
{
    $mage = 4;
}
else if ($session['user']['profession'] == PROF_MAGE_NEW)
{
    $mage = 1;
}
else if ($session['user']['profession'] == PROF_MAGE)
{
    $mage = 2;
}
else if ($session['user']['profession'] == PROF_MAGE_HEAD)
{
    $mage = 3;
}

switch($op) {
                
        case 'bewerben':
        
                $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_MAGE." OR profession=".PROF_MAGE_HEAD.")";
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                if ($session['user']['dragonkills'] < getsetting('priestreq',15))
                {
                    output("Du musst mindestens ".getsetting('priestreq',15)."x den grünen Drachen getötet haben, um Magier werden zu können!");
                    addnav("Zurück","mage.php?op=hq");
                }
                else if ($p['anzahl'] >= getsetting("numberofmages",3))
                {
                    output("Es gibt bereits ".$p['anzahl']." Magier. Mehr werden zur Zeit nicht benötigt!");
                    addnav("Zurück","dorfamt.php");
                }
                else
                {
                    output("Nach reiflicher Überlegung beschließt Du, ein Magier werden zu wollen. Weiterhin gelten für den Magierzirkel die folgenden, unverletzbaren Regeln:`n`n");
                    show_rules();
                    output("`nAls Magier wärst Du daran unbedingt gebunden!`nSteht Dein Entschluss immer noch fest?");
                    addnav("Ja!","mage.php?op=bewerben_ok&id=".$session['user']['acctid']);
                    addnav("Zurück","dorfamt.php");
                }
                break;
                
        case 'bewerben_ok':
        
                $session['user']['profession'] = PROF_MAGE_NEW;

                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_MAGE_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
                $res = db_query($sql);
                if (db_num_rows($res))
                {
                    $p=db_fetch_assoc($res);
                    systemmail($p['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& würde gern dem Zirkel beitreten. Du solltest die Bewerbung überprüfen und entsprechend handeln.");
                }

                output("Du reichst deine Bewerbung bei den Magiern ein, die diese gewissenhaft prüfen und Dir dann Bescheid geben werden!`n");
                addnav("Zurück","dorfamt.php");                 
                
                break;
                
        case 'bewerben_abbr':
                
                $session['user']['profession'] = 0;
                output("Du ziehst deine Bewerbung zurück.");
                addnav("Zurück","dorfamt.php");
                
                break;
                
        case 'aufn':
                
                $pid = (int)$_GET['id'];

                $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_MAGE." OR profession=".PROF_MAGE_HEAD.")";
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                if ($p['anzahl'] >= getsetting("numberofmages",3))
                {
                    output("Es gibt bereits ".$p['anzahl']." Magier! Mehr sind zur Zeit nicht möglich.");
                    addnav("Zurück","mage.php?op=mage_list_admin");
                }
                else
                {

                    // Für Debugzwecke
                    if ($session['user']['acctid'] == $pid)
                    {
                        $session['user']['profession'] = 41;
                    }

                    $sql = "UPDATE accounts SET profession = ".PROF_MAGE."
            WHERE acctid=".$pid;
                    db_query($sql) or die(db_error(LINK));

                    $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
                    $res = db_query($sql);
                    $p = db_fetch_assoc($res);

                    systemmail($pid,"Du wurdest initiiert!",$session['user']['name']."`& hat dich in den Zirkel eingeweiht. Damit bist du vom heutigen Tage an offiziell Mitglied dieser Gemeinschaft!");

                    $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute in den Magierzirkel initiiert!',newsdate=NOW(),accountid=".$pid;
                    db_query($sql) or die(db_error(LINK));

                    addhistory('`2Aufnahme in den Magierzirkel',1,$pid);

                    addnav("Willkommen!","mage.php?op=mage_list_admin");

                    output("".($session['user']['sex']?"Die neue Magierin":"Der neue Magier")." ist jetzt aufgenommen!");
                }
                break;
                
        case 'abl':
                
                $pid = (int)$_GET['id'];

                // Für Debugzwecke
                if ($session['user']['acctid'] == $pid)
                {
                    $session['user']['profession'] = 0;
                }

                $sql = "UPDATE accounts SET profession = 0
            WHERE acctid=".$pid;
                db_query($sql) or die(db_error(LINK));

                systemmail($pid,"Deine Bewerbung wurde abgelehnt!",$session['user']['name']."`& hat Deine Bewerbung zur Aufnahme in den Magierzirkel abgelehnt.");

                addnav("Zurück","mage.php?op=mage_list_admin");
                break;
        
        case 'entlassen':
                
            output("Diesen Magier wirklich entlassen?`n");
            addnav("Ja!","mage.php?op=entlassen_ok&id=".$_GET['id']);
            addnav("Nein, zurück!","mage.php?op=mage_list_admin");
            break;
            
        case 'entlassen_ok':
            $pid = (int)$_GET['id'];

            // Für Debugzwecke
            if ($session['user']['acctid'] == $pid)
            {
                $session['user']['profession'] = 0;
            }

            $sql = "UPDATE accounts SET profession = 0
        WHERE acctid=".$pid;
            db_query($sql) or die(db_error(LINK));

            systemmail($pid,"Du wurdest verstossen!",$session['user']['name']."`& hat Dich aus dem Magierzirkel verstossen.");

            $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
            $res = db_query($sql);
            $p = db_fetch_assoc($res);

            $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute aus dem Magierzirkel entlassen!',newsdate=NOW(),accountid=".$pid;
            db_query($sql) or die(db_error(LINK));

            addhistory('`$Entlassung aus dem Magierzirkel',1,$pid);

            output("Magier wurde entlassen!`n");
            addnav("Zurück","mage.php?op=mage_list_admin");
            break;            
        
        case 'leave':
                
            if($_GET['what'] == 'confirm') {
                $session['user']['profession'] = 0;

                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_MAGE_HEAD." ORDER BY loggedin DESC,RAND() LIMIT 1";
                $res = db_query($sql);
                if (db_num_rows($res))
                {
                    $p=db_fetch_assoc($res);
                    systemmail($p['acctid'],"`&Austritt aus dem Magierzirkel`0","`&".$session['user']['name']."`& hat den Zirkel heute verlassen.");
                }

                addnews($session['user']['name']." `&ist seit dem heutigen Tage nicht mehr im Zirkel der Magier!");

                addhistory('`2Aufgabe des Magierdaseins');

                output("`3Etwas wehmütig legst du die Insignien ab und bist ab sofort wieder ein normaler Bürger.`n");
                addnav('Weiter');
            } else {
                output('`^Möchtest du dein Amt als Mitglied der Magier wirklich niederlegen?');
                addnav('Austritt');
                addnav('Ja, möchte ich','mage.php?op=leave&what=confirm');
                addnav('Zurück');
            }
            addnav("Zum Hauptquartier","mage.php?op=hq");
            addnav("Zum Stadtamt","dorfamt.php");
            break;
            
        case 'hohep':
                $pid = (int)$_GET['id'];
            
                // Für Debugzwecke
                if ($session['user']['acctid'] == $pid)
                {
                    $session['user']['profession'] = 42;
                }
            
                $sql = "UPDATE accounts SET profession = ".PROF_MAGE_HEAD."
                WHERE acctid=".$pid;
                db_query($sql) or die(db_error(LINK));
            
                systemmail($pid,"Du wurdest in einen neuen Grad initiiert!",$session['user']['name']."`& hat dich zum Erzmagier geweiht.");
            
                addhistory('`2Weihe zum Erzmagier',1,$pid);
            
                addnav("Hallo Chef!","mage.php?op=mage_list_admin");
                break;
            
            case 'hohep_deg':
                $pid = (int)$_GET['id'];
            
                // Für Debugzwecke
                if ($session['user']['acctid'] == $pid)
                {
                    $session['user']['profession'] = PROF_MAGE;
                }
            
                $sql = "UPDATE accounts SET profession = ".PROF_MAGE."
                WHERE acctid=".$pid;
                db_query($sql) or die(db_error(LINK));
            
                systemmail($pid,"Du wurdest herabgesetzt!",$session['user']['name']."`& hat Dir den Erzmagiergrad entzogen.");
            
                addhistory('`2Herabsetzung zum normalen Magier',1,$pid);
            
                addnav("Das wars dann!","mage.php?op=mage_list_admin");
                break;            
        
        case 'hq':
                
                addcommentary();
                if (($session['user']['profession']==PROF_MAGE) || ($session['user']['profession']==PROF_MAGE_HEAD) || (su_check(SU_RIGHT_DEBUG)) ) {
                        output("`c`&".$profs[PROF_MAGE_HEAD][4]." `bDas Hauptquartier des Magierzirkels`b`c`n
                                `ÙIm Ostflügel des Stadtamtes, etwas abgetrennt von Gericht und Stadtwache, befinden sich die Räumlichkeiten der Magier. Im Boden ist ein prächtiges Mosaik
                                eingelassen, das einen mächtigen Schutzzauber beinhaltet und jedem verwehrt an diesem Ort dunkle Magie zu wirken. Auch in das dunkle Holz der Türen, die links
                                und rechts vom Gang abgehen, sind schützende Runen eingeschnitzt. An der Wand hängen Gemälde von allen, die das Amt des Erzmagiers seit Gründung des Zirkels
                                bekleidet haben, beleuchtet von magischen Feuern, die kein Holz verbrauchen und nie erlischen.`n
                                Hinter einer schmalen, unscheinbaren Tür verbirgt sich der Eingang zu einer engen Wendeltreppe. Folgt man ihr nach oben, gelangt man auf das Dach des
                                Stadtamtes, von wo aus man einen weitreichenden Blick über die Stadt hat und des Nachts ausgezeichnet die Sternbilder studieren kann. Was sich am unteren Ende
                                der Treppe befindet, weiß allein das Oberhaupt der Magier.`n",true);
                        addnav('Mitglieder');
                        addnav("Rekrutierungsliste","mage.php?op=mage_list_admin");
                        addnav("Schwarzes Brett","mage.php?op=board");
                        addnav("Symposium","mage.php?op=mage_intern");

                        if ($session['user']['profession'] == PROF_MAGE_HEAD || su_check(SU_RIGHT_DEBUG)) {
                                addnav('Massenmail','mage.php?op=massmail');
                        }
                        
                        addnav('Regeln');
                        addnav("Regeln des Zirkels","mage.php?op=mage_rules");
                        if ($session['user']['profession']==PROF_MAGE || $session['user']['profession']==PROF_MAGE_HEAD) {
                            addnav('Anträge');
                            addnav("Entlassung erbitten","mage.php?op=leave",false,false,false,false);
                        }
                } else {
                        output("`b`c`&Das Hauptquartier des Magierzirkels`b`c`n
                                `ÙIm Ostflügel des Stadtamtes, etwas abgetrennt von Gericht und Stadtwache, befinden sich die Räumlichkeiten der Magier. Im Boden ist ein prächtiges Mosaik
                                eingelassen, das einen mächtigen Schutzzauber beinhaltet und jedem verwehrt an diesem Ort dunkle Magie zu wirken. Auch in das dunkle Holz der Türen, die links
                                und rechts vom Gang abgehen, sind schützende Runen eingeschnitzt. An der Wand hängen Gemälde von allen, die das Amt des Erzmagiers seit Gründung des Zirkels
                                bekleidet haben, beleuchtet von magischen Feuern, die kein Holz verbrauchen und nie erlischen.`n
                                Hinter einer schmalen, unscheinbaren Tür verbirgt sich der Eingang zu einer engen Wendeltreppe. Folgt man ihr nach oben, gelangt man auf das Dach des
                                Stadtamtes, von wo aus man einen weitreichenden Blick über die Stadt hat und des Nachts ausgezeichnet die Sternbilder studieren kann. Was sich am unteren Ende
                                der Treppe befindet, weiß allein das Oberhaupt der Magier.`n",true);
                        addnav('Mitglieder');
                        addnav("Magier auflisten","mage.php?op=mage_list");
                        addnav('Regeln');
                        addnav("Regeln des Zirkels","mage.php?op=mage_rules");
                }
                if($session['user']['profession'] == 0) {
                    addnav('Anträge');
                    addnav("Magier werden","mage.php?op=bewerben");
                } elseif($session['user']['profession'] == PROF_MAGE_NEW) {
                    addnav('Anträge');
                    addnav("Bewerbung zurückziehen","mage.php?op=bewerben_abbr",false,false,false,false);
                }

                addnav('Sonstiges');
                if(getsetting('mage_rproom_active',0) == 1 || $session['user']['profession'] == PROF_MAGE_HEAD || su_check(SU_RIGHT_DEBUG)) {
                    $str_rproom_name = getsetting('mage_rproom_name','');
                    $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
                    $str_rproom_active = (getsetting('mage_rproom_active',0) == 1 ? '' : ' (gesperrt)');
                    addnav($str_rproom_name.$str_rproom_active,'mage.php?op=rproom',false,false,false,false);
                }
                addnav('Beschreibung aller Berufsgruppen','library.php?op=book&bookid=51');
                addnav('Zurück');
                addnav("Magierquartier verlassen","dorfamt.php");
                output("`n");
                viewcommentary("mages","Plaudern:",30,"plaudert");
                        
                break;
                
        case 'mage_rules':
            
            $show_ooc = true;
            
            output("Für die Ewigkeit bestimmt sind hier die Regeln der Magier festgehalten:`n`n");
            show_rules();
            
            addnav("Zurück","mage.php?op=hq");
            break;
                            
            case 'fluch':

                output("Als Magier kannst Du respektlosen Individuen einen Fluch aufzwingen, der sie beim Kampf beeinträchtigt. Oder einen Segen, je nachdem. Beides verschwindet von selbst nach einiger Zeit.`n
            Nutze dies Weise, denn die Götter dulden keinen Missbrauch!`n`n");

                if (!$_POST['name'])
                {
                    output('<form action="mage.php?op=fluch" method="POST">',true);
                    output('<input type="text" size="20" name="name">',true);
                    output('<input type="submit" size="20" name="ok" value="Suchen">',true);
                    output('</form>',true);
                    addnav("","mage.php?op=fluch");
                }
                else
                {

                    $ziel = stripslashes(rawurldecode($_POST['name']));

                    $name = str_create_search_string($ziel);

                    $sql = "SELECT acctid,name FROM accounts WHERE name LIKE '".$name."' AND locked=0";
                    $res = db_query($sql);

                    if (!db_num_rows($res))
                    {
                        output("`iKeine Übereinstimmung gefunden!`i");
                    }
                    else if (db_num_rows($res) >= 100)
                    {
                        output("`iZu viele Übereinstimmungen! Grenze deinen Suchbegriff etwas ein.`i");
                    }
                    else
                    {
                        output('<form action="mage.php?op=fluch_ok" method="POST">',true);
                        output('<select name="id" size="1">',true);
                        while ($p = db_fetch_assoc($res))
                        {
                            output("<option value=\"".$p['acctid']."\">".preg_replace("'[`].'","",$p['name'])."</option>",true);
                        }
                        output('</select> `n',true);
                        output('<select name="buff" size="1"><option value="hf1">Fluch</option><option value="hf2">Schlimmer Fluch</option><option value="hs1">Segen</option></select>`n',true);
                        output('<input type="submit" size="20" name="ok" value="Los!">',true);
                        output('</form>',true);
                        addnav("","mage.php?op=fluch_ok");

                    }

                }

                addnav("Zurück","mage.php?op=mage_intern");

                break;

            case 'fluch_ok':

                if ($_POST['buff'] == "hf1")
                {

                    item_add((int)$_POST['id'],'hxflch1');
                    systemmail((int)$_POST['id'],"`4Verflucht!",$session['user']['name']." `4hat Dich für Deine Freveltaten mit dem Fluch der Magier belegt!");
                    output("Du begibst Dich in eine tiefe Trance. Nachdem Du eine dem Opfer ähnelnde Stoffpuppe misshandelt hast, fühlst du die Energie des Fluches!`n`n");
                }
                else if ($_POST['buff'] == "hf2")
                {

                    item_add((int)$_POST['id'],'hxflch2');
                    systemmail((int)$_POST['id'],"`4Schlimm verflucht!",$session['user']['name']." `4hat Dich für Deine Freveltaten mit dem schlimmen Fluch der Magier belegt!");
                    output("Du begibst Dich in eine tiefe Trance. Nachdem Du ein Dutzend Nadeln in eine dem Opfer ähnelnde Stoffpuppe gestossen hast, fühlst du die Energie des Fluches!`n`n");
                }
                else if ($_POST['buff'] == "hs1")
                {

                    item_add((int)$_POST['id'],'hxsgn');
                    systemmail((int)$_POST['id'],"`@Gesegnet!",$session['user']['name']." `@hat Dich im Namen der Magier mit einem Segen bedacht!");
                    output("Du begibst Dich in eine tiefe Trance. Nachdem Du eine der Person ähnelnde Stoffpuppe gestreichelt hast, fühlst du die Energie des Segens!`n`n");
                }

                output("`&Der Zauber wurde ausgesprochen!`n");
                addnav("Zurück","mage.php?op=mage_intern");

                break;

            case 'fluch_liste_auswahl':


                $sql = "SELECT a.name, a.acctid FROM items i
                    INNER JOIN accounts a ON a.acctid = i.owner
                LEFT JOIN items_tpl it ON it.tpl_id=i.tpl_id
                WHERE (it.curse>0 OR i.tpl_id='hxflch1' OR i.tpl_id='hxflch2' OR i.tpl_id='hxsgn') GROUP BY i.owner ORDER BY a.name";

                $res = db_query($sql);

                output("Du schaust in die magische Wasserschale und erkennst sämtliche Helden, denen Flüche oder Segen anhängen:`n`n");

                if (db_num_rows($res) == 0)
                {
                    output("`iEs gibt derzeit keine von Magiern Verfluchten oder Gesegneten!`i");
                }
                else
                {

                    output('<table border="0"  cellpadding="3"><tr class="trhead"><td>Nr.</td><td>Name</td><td>Aktionen</td></tr>',true);

                    for ($i=1; $i<=db_num_rows($res); $i++)
                    {

                        $p = db_fetch_assoc($res);

                        output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td>'.$p['name'].'</td><td><a href="mage.php?op=fluch_liste&id='.$p['acctid'].'">Genauer betrachten</a></td>',true);

                        output('</tr>',true);

                        addnav("","mage.php?op=fluch_liste&id=".$p['acctid']);

                    }
                    // END for

                    output('</table>',true);

                }
                // END flüche vorhanden

                output('',true);

                addnav("Zurück","mage.php?op=mage_intern");

                break;

            case 'fluch_liste':

                $sql = "SELECT a.name, a.acctid, i.id, i.name AS fluchname, i.hvalue FROM items i
                    INNER JOIN accounts a ON i.owner = a.acctid
                    LEFT JOIN items_tpl it ON it.tpl_id = i.tpl_id
                WHERE(it.curse>0 OR i.tpl_id='hxflch1' OR i.tpl_id='hxflch2' OR i.tpl_id='hxsgn') AND i.owner=".(int)$_GET['id']." ORDER BY i.name";

                $res = db_query($sql);

                output("Bald darauf werden diese Flüche und Segen sichtbar:`n`n");

                output('<table border="0" cellpadding="3"><tr class="trhead"><td>Nr.</td><td>Name</td><td>Tage verbleibend</td><td>Aktionen</td></tr>',true);

                for ($i=1; $i<=db_num_rows($res); $i++)
                {

                    $p = db_fetch_assoc($res);

                    output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td>'.$p['fluchname'].'</td><td>'.(($p['hvalue'] == 0) ? 'unbegrenzt':$p['hvalue']).'</td><td><a href="mage.php?op=fluch_del&id='.$p['id'].'">Aufheben</a></td>',true);

                    output('</tr>',true);

                    addnav("","mage.php?op=fluch_del&id=".$p['id']);

                }
                // END for

                output('</table>',true);

                addnav("Zurück","mage.php?op=fluch_liste_auswahl");

                break;

            case 'fluch_del':

                $sql = "SELECT i.name,i.id,i.owner FROM items i WHERE i.id=".(int)$_GET['id'];

                $res = db_query($sql) or die(db_error(LINK));
                $i = db_fetch_assoc($res);

                $sql = "DELETE FROM items WHERE id=".$i['id'];
                $res = db_query($sql) or die(db_error(LINK));

                output("Du konzentrierst Dich auf den Fluch oder Segen und spürst bereits nach kurzer Zeit, wie er schwächer und schwächer wird. Schließlich weißt Du:`nEr ist Vergangenheit!");

                if ($i['name'] == "Segen der Magier")
                {
                    systemmail($i['owner'],"Segen aufgehoben!",$session['user']['name']." `@hat im Namen der Magier den Segen von Dir genommen.");
                }
                else
                {
                    systemmail($i['owner'],"Fluch aufgehoben!",$session['user']['name']." `@hat dich im Namen der Magier von Deinem schrecklichen Fluch \"".$i['name']."\" befreit.");
                }

                addnav("Zurück","mage.php?op=fluch_liste_auswahl");

                break;
                
                
        case 'mage_intern':
                
                if ($session['user']['profession']==PROF_MAGE || $session['user']['profession']==PROF_MAGE_HEAD || su_check(SU_RIGHT_COMMENT)) addcommentary();
                output("`c`&".$profs[PROF_MAGE_HEAD][4]." `bDas Hauptquartier der Magier`0`c`b");
                output('`n`7Durch eine unauffällige Tür gelangt man in einen, der Öffentlichkeit nicht zugänglichen, Raum. An der Wand stehen zahleiche Regale, gefüllt mit verschiedensten Nachschlagewerken und Enzyklopädien zum Lernen und Schmökern. Einige mit aufwendigen Schnitzmustern verzierte Schreibtische stehen in der einen Ecke, in der anderen thront ein bequemes Sofa auf einem kleinen Podest, damit sich die erschöpften Magier erholen können.
Wer auf das Dach will, um die Sterne zu studieren, muss durch eine weitere Tür treten und die vielen Stufen einer Wendeltreppe hinaufsteigen.',true);

                require_once(LIB_PATH.'board.lib.php');
                output('`0`c`n`^~~~~~~~~`0`n');
                $int_pollrights = (($session['user']['profession'] == PROF_MAGE_HEAD) ? 2 : 1);
                if(poll_view('mage_chief',$int_pollrights,$int_pollrights))
                {
                        output('`n`^~~~~~~~~`0`n`n',true);
                }
                output('`c');
                
                addnav("Magisches");
                addnav("Flüche/Segen","mage.php?op=fluch_liste_auswahl");
                addnav("Verfluchen/Segnen","mage.php?op=fluch");

                if($session['user']['profession'] == PROF_MAGE_HEAD || su_check(SU_RIGHT_DEBUG))
                {
                    addnav('Internes');
                    addnav ('f?Umfrage erstellen','mage.php?op=poll&pollsection=chief');
                }
                addnav('Zurück');
                addnav("Zurück zum HQ","mage.php?op=hq");
                output("`n`n");
                viewcommentary("mages_intern","Sagen:",30,"sagt");
                        
                break;
                
        case 'board':
        
            output ("`&Du stellst dich vor das große Brett und schaust ob eine neue Mitteilung vorliegt.`n");
        
            board_view('mage',($mage>=2)?2:0,'Folgendes Notizen sind zu finden:','Es sind keine Notizen hinterlegt..');
        
            output("`n`n");
        
            if ($mage >= 2)
            {
        
                board_view_form("Flüstern","`&Hier kannst du eine Notiz schreiben:");
                if ($_GET['board_action'] == "add")
                {
                    board_add('mage');
                    redirect("mage.php?op=board");
                }
            }
        
            addnav("Zurück","mage.php?op=hq");
        
            break;    
        
        case 'poll': //Umfrage erstellen
        {
                require_once(LIB_PATH.'board.lib.php');
                output('`c`b`2Umfragen des Zirkels`b`c`n`n');
                poll_add('mage_'.$_GET['pollsection'],100,1);
                if(!empty($session['polladderror'])) {
                        if($session['polladderror'] == 'maxpolls')
                        {
                                output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
                        }
                }
                else
                {
                        redirect('mage.php?op=mage_intern');
                }

                if($_GET['pollsection'] == 'private')
                {
                        output('`8Du möchtest also im Hinterzimmer des Hauptquartiers eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

                }
                else
                {
                        output('`8Du möchtest also eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und für alle gut sichtbar platziert zu werden:`n`n');
                }
                addnav('Zurück zum Symposium','mage.php?op=mage_intern');

                poll_show_addform();
                break;
        }

        case 'massmail': // Massenmail (im wohnviertel by mikay)
        {
                $str_out .= "`c`b`2Posthörnchenkobel unter dem Dach des Hauptquartiers.`b`c`n`n";

                addnav('Abbrechen','mage.php?op=hq');

                $sql='SELECT acctid, name, login, profession
                FROM accounts
                WHERE profession='.PROF_MAGE.'
                OR profession='.PROF_MAGE_HEAD.'
                OR profession='.PROF_MAGE_NEW.'
                AND acctid!='.(int)$session['user']['acctid'].'
                ORDER BY profession DESC';
                $result=db_query($sql);
                $users=array();
                $keys=0;

                while($row=db_fetch_assoc($result))
                {
                        $profs[0][0]='Zivilist';
                        if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';
        
                        $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" onclick="chk()" '.($row['profession']!=PROF_MAGE_NEW ? 'checked':'').'> '.$row['name'].'<br>';
                        $keys++;
                        $lastprofession=$row['profession'];
        
                        if ($_POST['title']!='' && $_POST['maintext']!='' && in_array($row['acctid'],$_POST['msg']))
                        {
                                $users[]=$row['acctid'];
                        }
                }

                $mailsends=count($users);

                if ($mailsends<=5)
                {
                        $gemcost=1;
                }
                elseif ($mailsends<=15)
                {
                        $gemcost=2;
                }
                elseif ($mailsends<=25)
                {
                        $gemcost=3;
                }
                elseif ($mailsends>25)
                {
                        $gemcost=4;
                }
                $gemcost=0;

                if ($session['user']['gems']>=$gemcost AND $mailsends>0)
                {
                        foreach($users as $id)
                        {
                                systemmail($id, $_POST['title'], $_POST['maintext'], $session['user']['acctid']);
                        }
        
                        $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler haben eine Taube erhalten und deine Kosten betragen '.$gemcost.' Edelsteine.<br><br>';
                        $session['user']['gems']-=$gemcost;
                }
                elseif ($session['user']['gems']<$gemcost AND $mailsends>0)
                {
                        $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler hätten eine Taube erhalten, wenn deine Kosten nicht '.$gemcost.' Edelsteine betragen würden. Leider kannst du dies nicht bezahlen.<br><br>';
                }

                if ($keys>0)
                {
                        $str_out .= form_header('mage.php?op=massmail')
                        .$sendresult.'
                        <table border="0" cellpadding="0" cellspacing="10">
                                <tr>
                                        <td><b>Betreff:</b></td>
                                        <td><input type="text" name="title" id="title" value="" onkeydown="chk()" onfocus="chk()"></td>
                                </tr>
                                <tr>
                                        <td valign="top"><b>Nachricht:</b></td>
                                        <td><textarea name="maintext" id="maintext" rows="15" cols="50" class="input" onkeydown="chk()" onfocus="chk()"></textarea></td>
                                </tr>
                                <tr>
                                        <td valign="top"><b>Senden an:</b></td>
                                        <td>'.$residents.'
                                                `bKosten bis jetzt:`b <span id="cost">0</span> Edelstein(e)!
                                        </td>
                                </tr>
                                <tr>
                                        <td></td>
                                        <td>
                                                <span id="but" style="visibility:hidden;"><input type="submit" value="Hörnchen auf die Reise schicken!" class="button"><br></span>
                                                <span id="msg">Bitte verfasse nun deine Botschaft und wähle die Empfänger!</span></td>
                                </tr>
                        </table>
                        </form>
                        <script type="text/javascript">
                        var els = document.getElementsByName("msg[]");
                        function chk () {
                                var ok = false;
                                var c = 0;
                                for(i=0;i<els.length;i++) {
                                        if(els[i].checked) {
                                                ok = true;
                                                c++;
                                        }
                                }
        
                                if(!document.getElementById("title").value && !document.getElementById("maintext").value) {
                                        ok = false;
                                }
        
                                document.getElementById("msg").style.visibility = (ok ? "hidden" : "visible");
                                document.getElementById("but").style.visibility = (ok ? "visible" : "hidden");
        
                                if(c <= 3) {
                                        c = 1;
                                }
                                else if(c <= 10) {
                                        c = 2;
                                }
                                else if(c <= 25) {
                                        c = 3;
                                }
                                else {
                                        c = 4;
                                }
                                c = 0;
        
                                document.getElementById("cost").innerHTML = c;
                        }
                        </script>
                        ';
                }
                else
                {
                        $str_out .= '`c`bEs wurden noch keine Magier ernannt - und ja, Bombenhörnchen an missliebige Nachbarn sind gegen das Gesetz.`b`c';
                }
                output($str_out);
                break;
        } // END massmail

        case 'mage_list_admin':
            output("Auf einer Schriftrolle befindet sich eine Liste aller Magier:`n`n");
            show_mage_list($mage);
            addnav("Zurück","mage.php?op=hq");
            break;       

        case 'mage_list':
            output("In Stein gemeißelt erkennst Du eine Liste aller Magier:`n`n");
            show_mage_list();
        
            if ($session['user']['profession'] == 0)
            {
                addnav("Als Magier bewerben","mage.php?op=bewerben");
            }
            if ($session['user']['profession'] == PROF_MAGE_NEW)
            {
                addnav("Bewerbung zurückziehen","mage.php?op=bewerben_abbr");
            }
            addnav("Zurück","mage.php?op=hq");
            break;

        case 'rproom':
            // Daten abrufen: Ortsname...
            $str_rproom_name = getsetting('mage_rproom_name','');
            $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
            // .. und Ortsbeschreibung
            $str_rproom_desc = getsetting('mage_rproom_desc','');
            $str_rproom_desc = (strlen($str_rproom_desc) > 14 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
            // end
            page_header(Strip_appoencode($str_rproom_name,3));
            output('`c`b'.$str_rproom_name.'`0`b`c`n'.$str_rproom_desc.'`n`n');
            addcommentary();
            viewcommentary('mage_rproom','Sagen',15,'sagt');
            if($session['user']['profession'] == PROF_MAGE_HEAD || su_check(SU_RIGHT_DEBUG)) {
                addnav('Verwaltung');
                addnav('Ort verwalten','mage.php?op=rproom_edit');
            }
            addnav('Zurück');
            addnav('Zum Eingangsbereich','mage.php');
            break;
        
        case 'rproom_edit':
            // Daten abrufen: Ortsname...
            $str_rproom_name = getsetting('mage_rproom_name','');
            $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
            // .. und Ortsbeschreibung
            $str_rproom_desc = getsetting('mage_rproom_desc','');
            $str_rproom_desc = (strlen($str_rproom_desc) > 1 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
            // end
            page_header(strip_appoencode($str_rproom_name,3));
            if($_GET['act'] == 'save') {
                $bool_nochmal = false;
                // Ortsbeschreibung
                if($_POST['rproom_name'] != $str_rproom_name) {
                    if(strlen(trim($_POST['rproom_name'])) >= 2) {
                        $_POST['rproom_name'] = html_entity_decode(strip_appoencode(strip_tags($_POST['rproom_name']),2));
                        savesetting('mage_rproom_name',$_POST['rproom_name']);
                    } else {
                        output('`4Fehler! Der Ortsname muss mindestens 2 Zeichen lang sein.`n`n');
                        $bool_nochmal = true;
                    }
                }
                // Ortsbeschreibung
                if($_POST['rproom_desc'] != $str_rproom_desc) {
                    if (strlen(trim($_POST['rproom_desc'])) >= 15) {
                        $_POST['rproom_desc'] = html_entity_decode(closetags(strip_tags($_POST['rproom_desc'],'`i`b`c')));
                        savesetting('mage_rproom_desc',$_POST['rproom_desc']);
                    } else {
                        output('`4Fehler! Die Ortsbeschreibung muss mindestens 15 Zeichen lang sein.`n`n');
                        $bool_nochmal = true;
                    }
                }
                // Speichervorgang nicht erfolgreich?
                if($bool_nochmal) {
                    addnav('Nochmal','mage.php?op=rproom_edit');
                    addnav('Zurück');
                } else {
                    savesetting('mage_rproom_active',$_POST['rproom_active']);
                    redirect('mage.php?op=rproom');
                }
            } else {
                $form = array('rproom_active'=>'RP-Ort für die Öffentlichkeit zugänglich?,bool'
                        ,'rproom_name'=>'Name des RP-Orts:'
                        ,'rproom_name_prev'=>'Vorschau:,preview,rproom_name'
                        ,'rproom_desc'=>'Ortsbeschreibung:,textarea,45,8'
                        ,'rproom_desc_prev'=>'Vorschau:,preview,rproom_desc');
                $data = array('rproom_active'=>getsetting('mage_rproom_active',0)
                        ,'rproom_name'=>$str_rproom_name
                        ,'rproom_desc'=>$str_rproom_desc);
                output("<form action='mage.php?op=rproom_edit&act=save' method='POST'>",true);
                showform($form,$data,false,'Speichern');
                output("</form>",true);
                addnav('','mage.php?op=rproom_edit&act=save');
                addnav('Zurück');
                addnav('Zum RP-Ort','mage.php?op=rproom');
            }
            addnav('Zum Eingangsbereich','mage.php');
            break;            
                            
        default:
                break;
}

page_footer();
?>

