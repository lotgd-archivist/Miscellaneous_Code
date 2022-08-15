
<?php
// Hauptsitz der Händlergilde

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
require_once(LIB_PATH.'profession.lib.php');

page_header("Der Hauptsitz der Händlergilde");

if (!isset($session)) exit();

$op = ($_GET['op']) ? $_GET['op'] : "hq";

function show_merch_list($admin_mode=0)
{
    global $session;

    $sql = "SELECT a.name,a.profession,a.acctid,a.login,a.loggedin,a.laston,a.activated FROM accounts a
WHERE a.profession=".PROF_MERCH_HEAD." OR a.profession=".PROF_MERCH;
    $sql .= ($admin_mode>=1) ? " OR a.profession=".PROF_MERCH_NEW : "";
    $sql .= " ORDER BY profession ASC, name";

    $res = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($res) == 0)
    {
        output("`n`iEs gibt keine Händler!`i`n");
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

            case PROF_MERCH_HEAD:
                output('`bGildenmeister/in`b');
                if ($admin_mode>=4)
                {

                    output('`n<a href="merchants.php?op=master_deg&id='.$p['acctid'].'">Titel abnehmen</a>',true);
                    addnav("","merchants.php?op=master_deg&id=".$p['acctid']);
                }
                break;

            case PROF_MERCH:
                output('Händler/in');
                if ($admin_mode>=3)
                {
                    output('`n<a href="merchants.php?op=entlassen&id='.$p['acctid'].'">Entlassen</a>',true);
                    addnav("","merchants.php?op=entlassen&id=".$p['acctid']);

                    if ($admin_mode>=4)
                    {
                        output('`n<a href="merchants.php?op=master&id='.$p['acctid'].'">Ernennung zum Gildenmeister</a>',true);
                        addnav("","merchants.php?op=master&id=".$p['acctid']);
                    }
                }
                break;

            case PROF_MERCH_NEW:
                output('Bewerber/in');
                if ($admin_mode>=3)
                {
                    output('`n<a href="merchants.php?op=aufn&id='.$p['acctid'].'">Aufnehmen</a>',true);
                    addnav("","merchants.php?op=aufn&id=".$p['acctid']);

                    output('`n<a href="merchants.php?op=abl&id='.$p['acctid'].'">Ablehnen</a>',true);
                    addnav("","merchants.php?op=abl&id=".$p['acctid']);
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
    // END händler vorhanden

}
// END show_merch_list

/*function show_rules()
{

    output("`4I. `&Als Diener der Gesellschaft ist der Zirkel dem Wohl der Gemeinschaft verpflichtet und jedes seiner Mitglieder hat diese Aufgabe wahrzunehmen und in ihrem Interesse zu agieren.`n");
    output("`4II. `&Die Zuständigkeit des Zirkels und dessen Befugnisse konzentrieren sich darauf, übernatürliche Phänomene aufzuspüren, zu untersuchen, einzuordnen und - dies ist als höchste Priorität anzusehen, sollte die Notwendigkeit bestehen -, unschädlich zu machen.`n");
    output("`4III. `&Die Mitglieder des Zirkels haben sich wie alle anderen Einwohner der Stadt dem Gesetz unterzuordnen. Brechen sie die so gesetzten Regeln des Zusammenlebens, haben sie sich dafür zu verantworten.`n");
    output("`4IV. `&Es steht dem Erzmagier - als oberste Autorität des Zirkels - frei, Mitglieder nach eigenem Ermessen einzuberufen.`n");
    output("`4V. `&Der Zirkel arbeitet in Kooperation mit den anderen Ämtern der Stadt. Reger Austausch mit deren Würdenträgern ist demzufolge erwünscht.`n");    
}*/
// END show_rules

$merch = 0;
if (su_check(SU_RIGHT_DEBUG))
{
    $merch = 4;
}
else if ($session['user']['profession'] == PROF_MERCH_NEW)
{
    $merch = 1;
}
else if ($session['user']['profession'] == PROF_MERCH)
{
    $merch = 2;
}
else if ($session['user']['profession'] == PROF_MERCH_HEAD)
{
    $merch = 3;
}

switch($op) {
                
        case 'bewerben':
        
                $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_MERCH." OR profession=".PROF_MERCH_HEAD.")";
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                if ($session['user']['dragonkills'] < 1)
                {
                    output("Du musst mindestens einen grünen Drachen getötet haben - Denn für fahrende Händler ist in diesen Hohen Hallen leider kein Platz.");
                    addnav("Zurück","merchants.php?op=hq");
                }
                else if($session['user']['reputation'] < 50)
                {
                    output("Dein Ruhm genügt, allerdings trauen die Leute Dir nicht. Tu etwas für Dein Ansehen, dann kannst Du es erneut veruschen.");
                    addnav("Zurück","merchants.php?op=hq");
                }
                else
                {
                    output("Nach reiflicher Überlegung beschließt Du, ein Mitglied der Händlergilde zu werden. Bitte bestätige Deine Entscheidung hier erneut, um ins Register der Stadt aufgenommen zu werden:");
                    //show_rules();
                    addnav("Ja!","merchants.php?op=bewerben_ok&id=".$session['user']['acctid']);
                    addnav("Zurück","dorfamt.php");
                }
                break;
                
        case 'bewerben_ok':
        
                $session['user']['profession'] = PROF_MERCH_NEW;

                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_MERCH_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
                $res = db_query($sql);
                if (db_num_rows($res))
                {
                    $p=db_fetch_assoc($res);
                    systemmail($p['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& würde gern der Händlergilde beitreten. Du solltest die Bewerbung überprüfen und entsprechend handeln.");
                }

                output("Du reichst deine Bewerbung bei der Händlergilde ein, die diese gewissenhaft prüfen und Dir dann Bescheid geben wird!`n");
                addnav("Zurück","dorfamt.php");                 
                
                break;
                
        case 'bewerben_abbr':
                
                $session['user']['profession'] = 0;
                output("Du ziehst deine Bewerbung zurück.");
                addnav("Zurück","dorfamt.php");
                
                break;
                
        case 'aufn':
                
                $pid = (int)$_GET['id'];

                $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_MERCH." OR profession=".PROF_MERCH_HEAD.")";
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                // Für Debugzwecke
                if ($session['user']['acctid'] == $pid)
                {
                    $session['user']['profession'] = 71;
                }

                $sql = "UPDATE accounts SET profession = ".PROF_MERCH."
        WHERE acctid=".$pid;
                db_query($sql) or die(db_error(LINK));

                $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                systemmail($pid,"Du wurdest registriert!",$session['user']['name']."`& hat dich in die Händlergilde aufgenommen. Damit bist du vom heutigen Tage an offiziell Mitglied dieser Gemeinschaft!");

                $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute in die Händlergilde aufgenommen!',newsdate=NOW(),accountid=".$pid;
                db_query($sql) or die(db_error(LINK));

                addhistory('`2Aufnahme in die Händlergilde',1,$pid);

                addnav("Willkommen!","merchants.php?op=merch_list_admin");

                output("".($session['user']['sex']?"Die neue Händlerin":"Der neue Händler")." ist jetzt aufgenommen!");
                
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

                systemmail($pid,"Deine Bewerbung wurde abgelehnt!",$session['user']['name']."`& hat Deine Bewerbung zur Aufnahme in die Händlergilde abgelehnt.");

                addnav("Zurück","merchants.php?op=merch_list_admin");
                break;
        
        case 'entlassen':
                
            output("Diesen Händler wirklich entlassen?`n");
            addnav("Ja!","merchants.php?op=entlassen_ok&id=".$_GET['id']);
            addnav("Nein, zurück!","merchants.php?op=merch_list_admin");
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

            systemmail($pid,"Du wurdest verstossen!",$session['user']['name']."`& hat Dich aus der Händlergilde verstossen.");

            $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
            $res = db_query($sql);
            $p = db_fetch_assoc($res);

            $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute aus der Händlergilde entlassen!',newsdate=NOW(),accountid=".$pid;
            db_query($sql) or die(db_error(LINK));

            addhistory('`$Entlassung aus der Händlergilde',1,$pid);

            output("Händler wurde entlassen!`n");
            addnav("Zurück","merchants.php?op=merch_list_admin");
            break;            
        
        case 'leave':
                
            if($_GET['what'] == 'confirm') {
                $session['user']['profession'] = 0;

                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_MERCH_HEAD." ORDER BY loggedin DESC,RAND() LIMIT 1";
                $res = db_query($sql);
                if (db_num_rows($res))
                {
                    $p=db_fetch_assoc($res);
                    systemmail($p['acctid'],"`&Austritt aus der Händlergilde`0","`&".$session['user']['name']."`& hat die Händlergilde heute verlassen.");
                }

                addnews($session['user']['name']." `&ist seit dem heutigen Tage nicht mehr bei der Händlergilde registriert!");

                addhistory('`2Austritt aus der Händlergilde');

                output("`3Etwas wehmütig legst du die Insignien ab und bist ab sofort wieder ein normaler Bürger.`n");
                addnav('Weiter');
            } else {
                output('`^Möchtest du deine Mitgliedschaft bei der Händlergilde wirklich beenden?');
                addnav('Austritt');
                addnav('Ja, möchte ich','merchants.php?op=leave&what=confirm');
                addnav('Zurück');
            }
            addnav("Zum Hauptquartier","merchants.php?op=hq");
            addnav("Zum Stadtamt","dorfamt.php");
            break;
            
        case 'master':
                $pid = (int)$_GET['id'];
            
                // Für Debugzwecke
                if ($session['user']['acctid'] == $pid)
                {
                    $session['user']['profession'] = 71;
                }
            
                $sql = "UPDATE accounts SET profession = ".PROF_MERCH_HEAD."
                WHERE acctid=".$pid;
                db_query($sql) or die(db_error(LINK));
            
                systemmail($pid,"Du wurdest befördert!",$session['user']['name']."`& hat dich zum Gildenmeister ernannt.");
            
                addhistory('`2Ernennung zum Gildenmeister',1,$pid);
            
                addnav("Hallo Chef!","merchants.php?op=merch_list_admin");
                break;
            
            case 'master_deg':
                $pid = (int)$_GET['id'];
            
                // Für Debugzwecke
                if ($session['user']['acctid'] == $pid)
                {
                    $session['user']['profession'] = PROF_MERCH;
                }
            
                $sql = "UPDATE accounts SET profession = ".PROF_MERCH."
                WHERE acctid=".$pid;
                db_query($sql) or die(db_error(LINK));
            
                systemmail($pid,"Du wurdest herabgesetzt!",$session['user']['name']."`& hat Dir den Rang des Gildenmeisters entzogen.");
            
                addhistory('`2Herabsetzung zum normalen Händler',1,$pid);
            
                addnav("Das wars dann!","merchants.php?op=merch_list_admin");
                break;            
        
        case 'hq':
                
                addcommentary();
                if (($session['user']['profession']==PROF_MERCH) || ($session['user']['profession']==PROF_MERCH_HEAD) || (su_check(SU_RIGHT_DEBUG)) ) {
                        output("`c`&".$profs[PROF_MERCH_HEAD][4]." `bDer Hauptsitz der Händlergilde`b`c`n
                                `uWendet man sich vom Zentrum des Stadtamtes in Richtung Westen, muss man nur einen schmalen, schmucklosen Gang durchqueren,
                                bevor man sich vor einer hohen, goldenen Flügeltür wiederfindet, die ein detailliert gearbeitetes Relief präsentiert: Ein starker
                                Hengst, gespannt vor eine riesige Kutsche, die über und über beladen ist mit Fässern, Stoffrollen und breiten Truhen. Im Zentrum der 
                                Kutsche prangt das Symbol einer Waage, deren Schalen auf der einen Seite echte Muscheln tragen, auf der anderen eine Münze.`n
                                Die Bedeutung des Handels - und insbesondere des Seehandels - für Eranya wird deutlich, wenn man durch diese Tür in eine hohe Halle tritt, 
                                die jenen der anderen Institutionen nicht unähnlich ist. Hier jedoch sind keine Teppiche oder Rüstungen an den Wänden, sondern Karten: Landkarten,
                                Seekarten, Zeichnungen von Gebirgen und Flüssen. Unzählige Steintafeln und ausgerollte Pergamentbögen liegen aus, auf denen die sichersten Handelsrouten
                                und die florierendsten Waren angepriesen werden. Boten eilen durch die Halle, lesen oder verändern den aktuellen Stand der Informationen und sputen sich,
                                um das Wichtigste rechtzeitig zu ihren Hohen Herren zu tragen. Denn Zeit ist schließlich Geld! Hier schlägt das Herz des Handels der Stadt, weshalb auch
                                einige fleißige Stadtwachen zugegen sind, die jeden Besucher genaustens beobachten.`n",true);
                        addnav('Mitglieder');
                        addnav("Mitgliedsregister","merchants.php?op=merch_list_admin");
                        addnav("Schwarzes Brett","merchants.php?op=board");
                        addnav("Wechselstube","merchants.php?op=merchants_intern");

                        if ($session['user']['profession'] == PROF_MERCH_HEAD || su_check(SU_RIGHT_DEBUG)) {
                                addnav('Massenmail','merchants.php?op=massmail');
                        }
                        
                        /*addnav('Regeln');
                        addnav("Regeln des Zirkels","merchants.php?op=merch_rules");*/
                        if ($session['user']['profession']==PROF_MERCH|| $session['user']['profession']==PROF_MERCH_HEAD) {
                            addnav('Anträge');
                            addnav("Entlassung erbitten","merchants.php?op=leave",false,false,false,false);
                        }
                } else {
                        output("`b`c`&Der Hauptsitz der Händlergilde`b`c`n
                                `uWendet man sich vom Zentrum des Stadtamtes in Richtung Westen, muss man nur einen schmalen, schmucklosen Gang durchqueren,
                                bevor man sich vor einer hohen, goldenen Flügeltür wiederfindet, die ein detailliert gearbeitetes Relief präsentiert: Ein starker
                                Hengst, gespannt vor eine riesige Kutsche, die über und über beladen ist mit Fässern, Stoffrollen und breiten Truhen. Im Zentrum der 
                                Kutsche prangt das Symbol einer Waage, deren Schalen auf der einen Seite echte Muscheln tragen, auf der anderen eine Münze.`n
                                Die Bedeutung des Handels - und insbesondere des Seehandels - für Eranya wird deutlich, wenn man durch diese Tür in eine hohe Halle tritt, 
                                die jenen der anderen Institutionen nicht unähnlich ist. Hier jedoch sind keine Teppiche oder Rüstungen an den Wänden, sondern Karten: Landkarten,
                                Seekarten, Zeichnungen von Gebirgen und Flüssen. Unzählige Steintafeln und ausgerollte Pergamentbögen liegen aus, auf denen die sichersten Handelsrouten
                                und die florierendsten Waren angepriesen werden. Boten eilen durch die Halle, lesen oder verändern den aktuellen Stand der Informationen und sputen sich,
                                um das Wichtigste rechtzeitig zu ihren Hohen Herren zu tragen. Denn Zeit ist schließlich Geld! Hier schlägt das Herz des Handels der Stadt, weshalb auch
                                einige fleißige Stadtwachen zugegen sind, die jeden Besucher genaustens beobachten.`n",true);
                        addnav('Mitglieder');
                        addnav("Mitgliedsregister","merchants.php?op=merch_list");
                        /*addnav('Regeln');
                        addnav("Regeln des Zirkels","merchants.php?op=merch_rules");*/
                }
                if($session['user']['profession'] == 0) {
                    addnav('Anträge');
                    addnav("Händler werden","merchants.php?op=bewerben");
                } elseif($session['user']['profession'] == PROF_MERCH_NEW) {
                    addnav('Anträge');
                    addnav("Bewerbung zurückziehen","merchants.php?op=bewerben_abbr",false,false,false,false);
                }

                addnav('Sonstiges');
                if(getsetting('merch_rproom_active',0) == 1 || $session['user']['profession'] == PROF_MERCH_HEAD || su_check(SU_RIGHT_DEBUG)) {
                    $str_rproom_name = getsetting('merch_rproom_name','');
                    $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
                    $str_rproom_active = (getsetting('merch_rproom_active',0) == 1 ? '' : ' (gesperrt)');
                    addnav($str_rproom_name.$str_rproom_active,'merchants.php?op=rproom',false,false,false,false);
                }
                addnav('Beschreibung aller Berufsgruppen','library.php?op=book&bookid=51');
                addnav('Zurück');
                addnav("Händlergilde verlassen","dorfamt.php");
                output("`n");
                viewcommentary("merchants","Plaudern:",30,"plaudert");
                        
                break;
                
        case 'merch_rules':
            
            $show_ooc = true;
            
            output("Für die Ewigkeit bestimmt sind hier die Regeln der Händler festgehalten:`n`n");
            show_rules();
            
            addnav("Zurück","merchants.php?op=hq");
            break;
                
        case 'merchants_intern':
                
                if ($session['user']['profession']==PROF_MERCH || $session['user']['profession']==PROF_MERCH_HEAD || su_check(SU_RIGHT_COMMENT)) addcommentary();
                output("`c`&".$profs[PROF_MERCH_HEAD][4]." `bDie Wechelstube für Mitglieder`0`c`b");
                output('`n`7...',true);

                require_once(LIB_PATH.'board.lib.php');
                $int_pollrights = (($session['user']['profession'] == PROF_MERCH_HEAD) ? 2 : 1);
                if(poll_view('merch_chief',$int_pollrights,$int_pollrights))
                {
                        output('`n`^~~~~~~~~`0`n`n',true);
                }
                output('`c');

                if($session['user']['profession'] == PROF_MERCH_HEAD || su_check(SU_RIGHT_DEBUG))
                {
                    addnav('Internes');
                    addnav ('f?Umfrage erstellen','merchants.php?op=poll&pollsection=chief');
                }
                addnav('Zurück');
                addnav("Zurück zum Hauptsitz","merchants.php?op=hq");
                output("`n`n");
                viewcommentary("merchants_intern","Sagen:",30,"sagt");
                        
                break;
                
        case 'board':
        
            output ("`&Du stellst dich vor das große Brett und schaust ob eine neue Mitteilung vorliegt.`n");
        
            board_view('merch',($merch>=2)?2:0,'Folgendes Notizen sind zu finden:','Es sind keine Notizen hinterlegt..');
        
            output("`n`n");
        
            if ($merch >= 2)
            {
        
                board_view_form("Flüstern","`&Hier kannst du eine Notiz schreiben:");
                if ($_GET['board_action'] == "add")
                {
                    board_add('merch');
                    redirect("merchants.php?op=board");
                }
            }
        
            addnav("Zurück","merchants.php?op=hq");
        
            break;    
        
        case 'poll': //Umfrage erstellen
        {
                require_once(LIB_PATH.'board.lib.php');
                output('`c`b`2Umfragen der Händlergilde`b`c`n`n');
                poll_add('merch_'.$_GET['pollsection'],100,1);
                if(!empty($session['polladderror'])) {
                        if($session['polladderror'] == 'maxpolls')
                        {
                                output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
                        }
                }
                else
                {
                        redirect('merchants.php?op=merchants_intern');
                }

                if($_GET['pollsection'] == 'private')
                {
                        output('`8Du möchtest also im Hinterzimmer des Hauptquartiers eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

                }
                else
                {
                        output('`8Du möchtest also eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und für alle gut sichtbar platziert zu werden:`n`n');
                }
                addnav('Zurück zur Wechselstube','merchants.php?op=merchants_intern');

                poll_show_addform();
                break;
        }

        case 'massmail': // Massenmail (im wohnviertel by mikay)
        {
                $str_out .= "`c`b`2Posthörnchenkobel unter dem Dach des Hauptquartiers.`b`c`n`n";

                addnav('Abbrechen','merchants.php?op=hq');

                $sql='SELECT acctid, name, login, profession
                FROM accounts
                WHERE profession='.PROF_MERCH.'
                OR profession='.PROF_MERCH_HEAD.'
                OR profession='.PROF_MERCH_NEW.'
                AND acctid!='.(int)$session['user']['acctid'].'
                ORDER BY profession DESC';
                $result=db_query($sql);
                $users=array();
                $keys=0;

                while($row=db_fetch_assoc($result))
                {
                        $profs[0][0]='Zivilist';
                        if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';
        
                        $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" onclick="chk()" '.($row['profession']!=PROF_MERCH_NEW ? 'checked':'').'> '.$row['name'].'<br>';
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
        
                        $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler haben ein Posthörnchen erhalten und deine Kosten betragen '.$gemcost.' Edelsteine.<br><br>';
                        $session['user']['gems']-=$gemcost;
                }
                elseif ($session['user']['gems']<$gemcost AND $mailsends>0)
                {
                        $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler hätten ein Posthörnchen erhalten, wenn deine Kosten nicht '.$gemcost.' Edelsteine betragen würden. Leider kannst du dies nicht bezahlen.<br><br>';
                }

                if ($keys>0)
                {
                        $str_out .= form_header('merchants.php?op=massmail')
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
                        $str_out .= '`c`bEs wurden noch keine Händler ernannt - und ja, Bombenhörnchen an missliebige Nachbarn sind gegen das Gesetz.`b`c';
                }
                output($str_out);
                break;
        } // END massmail

        case 'merch_list_admin':
            output("Auf einer Schriftrolle befindet sich eine Liste aller Händler:`n`n");
            show_merch_list($merch);
            addnav("Zurück","merchants.php?op=hq");
            break;       

        case 'merch_list':
            output("In Stein gemeißelt erkennst Du eine Liste aller Händler:`n`n");
            show_merch_list();
        
            if ($session['user']['profession'] == 0)
            {
                addnav("Als Händler bewerben","merchants.php?op=bewerben");
            }
            if ($session['user']['profession'] == PROF_MERCH_NEW)
            {
                addnav("Bewerbung zurückziehen","merchants.php?op=bewerben_abbr");
            }
            addnav("Zurück","merchants.php?op=hq");
            break;

        case 'rproom':
            // Daten abrufen: Ortsname...
            $str_rproom_name = getsetting('merch_rproom_name','');
            $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
            // .. und Ortsbeschreibung
            $str_rproom_desc = getsetting('merch_rproom_desc','');
            $str_rproom_desc = (strlen($str_rproom_desc) > 14 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
            // end
            page_header(Strip_appoencode($str_rproom_name,3));
            output('`c`b'.$str_rproom_name.'`0`b`c`n'.$str_rproom_desc.'`n`n');
            addcommentary();
            viewcommentary('merch_rproom','Sagen',15,'sagt');
            if($session['user']['profession'] == PROF_MERCH_HEAD || su_check(SU_RIGHT_DEBUG)) {
                addnav('Verwaltung');
                addnav('Ort verwalten','merchants.php?op=rproom_edit');
            }
            addnav('Zurück');
            addnav('Zum Eingangsbereich','merchants.php');
            break;
        
        case 'rproom_edit':
            // Daten abrufen: Ortsname...
            $str_rproom_name = getsetting('merch_rproom_name','');
            $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
            // .. und Ortsbeschreibung
            $str_rproom_desc = getsetting('merch_rproom_desc','');
            $str_rproom_desc = (strlen($str_rproom_desc) > 1 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
            // end
            page_header(strip_appoencode($str_rproom_name,3));
            if($_GET['act'] == 'save') {
                $bool_nochmal = false;
                // Ortsbeschreibung
                if($_POST['rproom_name'] != $str_rproom_name) {
                    if(strlen(trim($_POST['rproom_name'])) >= 2) {
                        $_POST['rproom_name'] = html_entity_decode(strip_appoencode(strip_tags($_POST['rproom_name']),2));
                        savesetting('merch_rproom_name',$_POST['rproom_name']);
                    } else {
                        output('`4Fehler! Der Ortsname muss mindestens 2 Zeichen lang sein.`n`n');
                        $bool_nochmal = true;
                    }
                }
                // Ortsbeschreibung
                if($_POST['rproom_desc'] != $str_rproom_desc) {
                    if (strlen(trim($_POST['rproom_desc'])) >= 15) {
                        $_POST['rproom_desc'] = html_entity_decode(closetags(strip_tags($_POST['rproom_desc'],'`i`b`c')));
                        savesetting('merch_rproom_desc',$_POST['rproom_desc']);
                    } else {
                        output('`4Fehler! Die Ortsbeschreibung muss mindestens 15 Zeichen lang sein.`n`n');
                        $bool_nochmal = true;
                    }
                }
                // Speichervorgang nicht erfolgreich?
                if($bool_nochmal) {
                    addnav('Nochmal','merchants.php?op=rproom_edit');
                    addnav('Zurück');
                } else {
                    savesetting('merch_rproom_active',$_POST['rproom_active']);
                    redirect('merchants.php?op=rproom');
                }
            } else {
                $form = array('rproom_active'=>'RP-Ort für die Öffentlichkeit zugänglich?,bool'
                        ,'rproom_name'=>'Name des RP-Orts:'
                        ,'rproom_name_prev'=>'Vorschau:,preview,rproom_name'
                        ,'rproom_desc'=>'Ortsbeschreibung:,textarea,45,8'
                        ,'rproom_desc_prev'=>'Vorschau:,preview,rproom_desc');
                $data = array('rproom_active'=>getsetting('merch_rproom_active',0)
                        ,'rproom_name'=>$str_rproom_name
                        ,'rproom_desc'=>$str_rproom_desc);
                output("<form action='merchants.php?op=rproom_edit&act=save' method='POST'>",true);
                showform($form,$data,false,'Speichern');
                output("</form>",true);
                addnav('','merchants.php?op=rproom_edit&act=save');
                addnav('Zurück');
                addnav('Zum RP-Ort','merchants.php?op=rproom');
            }
            addnav('Zum Eingangsbereich','merchants.php');
            break;            
                            
        default:
                break;
}

page_footer();
?>

