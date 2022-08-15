
<?php
// Name: waldlichtung.php <-- umgeschrieben von tempel.php
// Autor: Azura für http://lotgd.drachenserver.de (mail: alexander-glatho@web.de)
// Erstellungsdatum: 1.12.05 - 7.12.05
// // Beschreibung:
//                Führt neuen Beruf Magier ein, zur Speicherung wird Var profession (Wertebereich von 61-63) genutzt.
//                Magier bilden das gegenstück zu Priestern und können "böse" heiraten vornehmen
//                Im Wald als neuer Punkt zu finden
//                Flirten findet nach wie vor im Garten statt auch alle anderen Sachen bleiben vorerst dem Tempel vorbehalten
//                Neues Heiratssytem:
//                        - Bei >= 5 Flirts im Garten Verlobung
//                        - Priester muss Heirat starten (Vorsicht: Darf nicht gleichzeitig einer der zu Verheiratenden sein)
//                        - Priester schließt Heirat ab, Weiteres gleichbleibend
//                        Statusvar: 1 = im Gange, 2 = verheiratet, 3 = abgeschlossen
//
// 22.02.06 Bugfix und Anpassungen by Maris(Maraxxus@gmx.de)

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
require_once(LIB_PATH.'profession.lib.php');

addcommentary();
checkday();

page_header("Die Waldlichtung");

define("SCHNELLHOCHZ_KOSTEN",3000);
define("SCHNELLHOCHZ_ERLAUBT",0);
define("STATUS_START",1);
define("STATUS_VERHEIRATET",2);
define("STATUS_ABGESCHLOSSEN",3);

function show_rules()
{

    output("`4I. `&Den Anweisungen des Erzmagierss bzw der Erzmagierin ist Folge zu leisten. Sie repräsentieren die oberste Autorität des Zirkels!`n");
    output("`4II. `&Es ist verboten dem Wald und Tieren grundlos Schaden zuzufügen!`n");
    output("`4III. `&Es ist verboten den Ritualplatz zu stören oder laufende Rituale zu unterbrechen!`n");
    output("`4IV. `&Das Tragen von Waffen im Kreis ist nur dem Wächter erlaubt! Die Entweihung der heiligen Stätte wird mit Flüchen bestraft!`n");
    output("`4V. `&Wer einem Magier das Leben nimmt hat die Konsequenzen dafür zu tragen! Ebenso ist es keinem Magier erlaubt, einen Bürger der Stadt zu töten!`n");
    output("`4VI. `&Es ist verboten den Altar und die geweihten Gegenstände darauf ohne Erlaubnis zu berühren.`n");
    output("`4VII. `&Sobald der Kreis geschlossen ist, darf dieser nur noch betreten oder verlassen werden wenn der ritualführende Magier dies erlaubt.`n");
}

function show_mage_list($admin_mode=0)
{
    global $session;

    $sql = "SELECT a.name,a.profession,a.acctid,a.login,a.loggedin,a.laston,a.activated FROM accounts a
WHERE a.profession=".PROF_MAGE_HEAD." OR a.profession=".PROF_MAGE;
    $sql .= ($admin_mode>=1) ? " OR a.profession=".PROF_MAGE_NEW : "";
    $sql .= " ORDER BY profession DESC, name";

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

                    output('`n<a href="waldlichtung.php?op=hohep_deg&id='.$p['acctid'].'">Grad abnehmen</a>',true);
                    addnav("","waldlichtung.php?op=hohep_deg&id=".$p['acctid']);
                }
                break;

            case PROF_MAGE:
                output('Magier/in');
                if ($admin_mode>=3)
                {
                    output('`n<a href="waldlichtung.php?op=entlassen&id='.$p['acctid'].'">Verstossen</a>',true);
                    addnav("","waldlichtung.php?op=entlassen&id=".$p['acctid']);

                    if ($admin_mode>=4)
                    {
                        output('`n<a href="waldlichtung.php?op=hohep&id='.$p['acctid'].'">Weihe zum Erzmagier</a>',true);
                        addnav("","waldlichtung.php?op=hohep&id=".$p['acctid']);
                    }
                }
                break;

            case PROF_MAGE_NEW:
                output('Schüler/in');
                if ($admin_mode>=3)
                {
                    output('`n<a href="waldlichtung.php?op=aufnehmen&id='.$p['acctid'].'">Initiieren</a>',true);
                    addnav("","waldlichtung.php?op=aufnehmen&id=".$p['acctid']);

                    output('`n<a href="waldlichtung.php?op=ablehnen&id='.$p['acctid'].'">Ablehnen</a>',true);
                    addnav("","waldlichtung.php?op=ablehnen&id=".$p['acctid']);
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

function show_flirt_list($admin_mode=0,$married=0)
{
    global $session;

    $link = calcreturnpath();
    $link .= "&";

    $ppp = 30;

    $count_sql = "SELECT COUNT(*) AS anzahl FROM accounts a WHERE ";

    if ($married < 2)
    {

        $sql = "SELECT a.name AS name_a,a.acctid AS acctid_a,b.name AS name_b,b.acctid AS acctid_b, a.login AS login_a, b.login AS login_b FROM accounts a,accounts b
WHERE
a.marriedto=b.acctid AND
a.sex=1 AND b.sex=0 AND ";
        if ($married)
        {
            $sql .= "( a.charisma = 4294967295 AND b.charisma = 4294967295 )";
            $count_sql .= "a.charisma=4294967295 AND a.marriedto>0 AND a.marriedto<4294967295";
        }
        else
        {
            $sql .= "( a.charisma = 999 AND b.charisma = 999 )";
            $count_sql .= "a.charisma=999 AND a.marriedto>0 AND a.marriedto<4294967295";
        }

        $sql .= "ORDER BY name_a, name_b";

    }
    else
    {
        $sql = "SELECT a.sex,a.name AS name_a,a.acctid AS acctid_a, a.login AS login_a FROM accounts a
WHERE a.marriedto=4294967295 ";
        $sql .= "ORDER BY name_a";
        $count_sql .= "a.marriedto=4294967295";
    }

    $count_res = db_query($count_sql) or die(db_error(LINK));
    $c = db_fetch_assoc($count_res);

    if ($c['anzahl'] == 0)
    {
        output("`iEs gibt keine Paare!`i");
    }
    else
    {

        // wegen Paaren
        if ($married < 2)
        {
            $c['anzahl'] = floor($c['anzahl'] * 0.5);
        }

        $page = max((int)$_GET['page'],1);

        $last_page = ceil($c['anzahl'] / $ppp);

        for ($i=1; $i<=$last_page; $i++)
        {

            $offs_max = min($i * $ppp,$c['anzahl']);
            $offs_min = ($i-1) * $ppp + 1;

            addnav("Seite ".$i." (".$offs_min." - ".$offs_max.")",$link."page=".$i);

        }

        $offs_min = ($page-1) * $ppp;

        $sql .= " LIMIT ".$offs_min.",".$ppp;

        $res = db_query($sql) or die(db_error(LINK));

        output('<table border="0" cellpadding="3"><tr class="trhead"><td>Nr.</td>',true);
        if ($married < 2)
        {
            output('<td><img src="images/female.png" alt="weiblich"> Name</td><td><img src="images/male.png" alt="männlich"> Name</td>',true);
        }
        else
        {
            output('<td> Spieler</td><td> NPC</td>',true);
        }
        output((($admin_mode)?'<td>Aktionen</td>':'').'</tr>',true);

        while ($p = db_fetch_assoc($res))
        {

            $offs_min++;

            if($session['user']['prefs']['popupbio'] == 1)
            {
                    $link_a = "bio_popup.php?char=".rawurlencode($p['login_a']);
                    $str_link_a = '<a href="'.$link_a.'" target="_blank" onClick="'.popup_fullsize($link_a).';return:false;">'.$p['name_a'].'</a>';
                    $link_b = "bio_popup.php?char=".rawurlencode($p['login_b']);
                    $str_link_b = '<a href="'.$link_b.'" target="_blank" onClick="'.popup_fullsize($link_b).';return:false;">'.$p['name_b'].'</a>';
            }
            else
            {
                    $link_a = "bio.php?char=".rawurlencode($p['login_a']) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                    $str_link_a = '<a href="'.$link_a.'">'.$p['name_a'].'</a>';
                    addnav("",$link_a);
                    $link_b = "bio.php?char=".rawurlencode($p['login_b']) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                    $str_link_b = '<a href="'.$link_b.'">'.$p['name_b'].'</a>';
                    addnav("",$link_b);
            }
            $mail_a = ($admin_mode>=2) ? '<a href="mail.php?op=write&to='.rawurlencode($p['login_a']).'" target="_blank" onClick="'.popup("mail.php?op=write&to=".rawurlencode($p['login_a']) ).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>' : '';
            $mail_b = ($admin_mode>=2) ? '<a href="mail.php?op=write&to='.rawurlencode($p['login_b']).'" target="_blank" onClick="'.popup("mail.php?op=write&to=".rawurlencode($p['login_b']) ).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>' : '';

            output('<tr class="'.(($offs_min%2)?'trdark':'trlight').'"><td>'.$offs_min.'</td>',true);
            output('<td>'.$mail_a.$str_link_a.'</td>',true);
            if ($married < 2)
            {
                output('<td>'.$mail_b.$str_link_b.'</td>',true);
            }
            else
            {
                output('<td>'.(($p['sex']==0)?'Ophelía':'Silas').'</td>',true);
            }

            if ($admin_mode>=2)
            {
                output('<td>',true);
                if (!$married)
                {
                    if (getsetting("mage_status",0) == 0 || getsetting("mage_status",0) == STATUS_ABGESCHLOSSEN)
                    {
                        output('<a href="waldlichtung.php?op=hochz&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Hochzeit beginnen</a>',true);
                        addnav("","waldlichtung.php?op=hochz&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                        output('`n<a href="waldlichtung.php?op=trennung&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Verlobung lösen</a>',true);
                        addnav("","waldlichtung.php?op=trennung&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                    }
                    else if (getsetting("mage_id1",0) == $p['acctid_a'] || getsetting("mage_id2",0) == $p['acctid_b'])
                    {
                        output('`iHochzeit im Gange`i',true);
                    }

                }
                else
                {
                    if ($married==2)
                    {
                        output('<a href="waldlichtung.php?op=scheidung&id1='.$p['acctid_a'].'&npc=1">Trennen</a>',true);
                        addnav("","waldlichtung.php?op=scheidung&id1=".$p['acctid_a']."&npc=1");
                    }
                    else
                    {
                        output('<a href="waldlichtung.php?op=scheidung&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Trennen</a>',true);
                        addnav("","waldlichtung.php?op=scheidung&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                    }

                }
                output('</td>',true);
            }

            output('</tr>',true);

        }
        // END for

        output('</table>',true);

    }
    // END paare vorhanden

}
// END show_flirt_list

function make_mage_commentary($msg,$author=0)
{

    $sql = "INSERT INTO commentary SET section='mage',author=".$author.",comment='".addslashes($msg)."',postdate=NOW()";
    db_query($sql) or die(db_error(LINK));

}
// END make_temple_commentary


$op = (isset($_GET['op'])) ? $_GET['op'] : '';
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

switch ($op)
{

case '':

    output("`&Die Waldlichtung ist von Ästen und Laub freigeräumt. Mit jungen Zweigen ist die
Form eines großen Kreises auf dem Boden angedeutet, in dessen Mitte ein steinerner Altar aufgebaut ist.`n
Auf dem Altar befinden sich drei schwarze Kerzen und ein Weihrauchbehältnis, außerdem eine Schale mit frischem Wasser und eine Schale mit Meersalz. Ein seltsamer Zauber umgibt diesen Ort mit Stille und Frieden. Es scheint als vergehe die Zeit hier in einem anderen Maße als außerhalb der Lichtung.
`n`n");

    if (getsetting("mage_status",0) > 0)
    {

        $sql = "SELECT name,acctid FROM accounts
WHERE acctid=".getsetting('mage_id1',0)." OR acctid=".getsetting('mage_id2',0)." ORDER BY sex";
        $res = db_query($sql);
        $p1 = db_fetch_assoc($res);
        $p2 = db_fetch_assoc($res);

        if (getsetting("mage_status",0) == STATUS_START)
        {
            output("`c`i`&Heute wird hier das Ritual der Hochzeit von ".$p1['name']."`& und ".$p2['name']."`& begangen!");
        }
        else if (getsetting("mage_status",0) == STATUS_VERHEIRATET || getsetting("mage_status",0) == STATUS_ABGESCHLOSSEN)
        {
            output("`c`i`&".$p1['name']."`& und ".$p2['name']."`& haben gerade geheiratet! Herzlichen Glückwunsch!");
        }
        output("`i`c`n`n");
    }

    viewcommentary("mage","Leise sprechen:",25,"raunt");

    if ($mage >= 2)
    {
        addnav("Magier");
        addnav("Tor zur Zwischenwelt","waldlichtung.php?op=secret");
        if (getsetting("mage_status",0) == 0)
        {
        addnav("Aufräumen","waldlichtung.php?op=sauber");
        }
        if (getsetting('mage_mage_id',0) == $session['user']['acctid'])
        {
            addnav("Aktionen");

            if (getsetting('mage_status',0) == STATUS_START)
            {
                addnav("`bVerheiraten`b","waldlichtung.php?op=hochz_ok&heirat=1");
            }
            else if (getsetting('mage_status',0) == STATUS_VERHEIRATET)
            {
                addnav("`bZeremonie abschließen`b","waldlichtung.php?op=hochz_ende");
            }
            else if (getsetting('mage_status',0) == STATUS_ABGESCHLOSSEN)
            {
                //        addnav("`bAufräumen`b","tempel.php?op=sauber");
            }

        }

    }
    else
    {
    addnav("Mystisches");
    addnav("Tor zur Zwischenwelt","waldlichtung.php?op=secret");
    }

    addnav("Waldlichtung");
    addnav("Liste der Magier","waldlichtung.php?op=mage_list");
    addnav("Ehepaare","waldlichtung.php?op=married_list_public");
    addnav("Regeln");
    addnav("Die Regeln der Magier","waldlichtung.php?op=rules");
    if ($session['user']['charisma']==999 && SCHNELLHOCHZ_ERLAUBT)
    {
        addnav("Schnellhochzeit (".SCHNELLHOCHZ_KOSTEN." Gold)","waldlichtung.php?op=hochz_schnell");
    }

    addnav("Verschiedenes");
    addnav("W?Zum Wasserfall","waterfall.php");
    addnav("Zurück in den Wald","forest.php");
    addnav("S?Zurück in die Stadt","village.php");

    break;

case 'rules':
    $show_ooc = true;

    output("Für die Ewigkeit bestimmt sind hier die Regeln der Magier festgehalten:`n`n");
    show_rules();

    addnav("Zurück","waldlichtung.php");
    break;

case 'mage_list':
    output("In Stein gemeißelt erkennst Du eine Liste aller Magier:`n`n");
    show_mage_list();

    if ($session['user']['profession'] == 0)
    {
        addnav("Ich will Magier werden!","waldlichtung.php?op=bewerben");
    }
    if ($session['user']['profession'] == PROF_MAGE_NEW)
    {
        addnav("Bewerbung zurückziehen","waldlichtung.php?op=bewerben_abbr");
    }
    addnav("Zurück","waldlichtung.php");
    break;

case 'mage_list_admin':
    output("Auf einer Schriftrolle befindet sich eine Liste aller Magier:`n`n");
    show_mage_list($mage);
    addnav("Zurück","waldlichtung.php?op=secret");
    break;

case 'secret':
if ($mage >= 2)
{
    output("`2Du schlüpfst durch ein magisches Tor und betrittst die Zwischenwelt, einen Raum ausserhalb der Realität und jeder Vorstellungskraft. Verschwommen kannst du die Waldlichtung ausserhalb dieses geschützten Kreises erkennen. Ein Hauch von Heiligkeit umgibt dich. Nur Magier haben zu diesem besonderen Ort Zutritt.`n`n");
    viewcommentary("mage_secret","Sprechen:",25,"spricht");

    addnav("Magischer Spiegel");
    addnav("Liste der Magier","waldlichtung.php?op=mage_list_admin");
    addnav("Liste der Verlobten","waldlichtung.php?op=flirt_list");
    addnav("Liste der Verheirateten","waldlichtung.php?op=married_list");
    addnav("Liste der Silas-/Ophelía-Opfer","waldlichtung.php?op=married_list_npc");
    addnav("Zur Trauerweide","waldlichtung.php?op=board");
    addnav("Aktionen");
    addnav("Flüche/Segen","waldlichtung.php?op=fluch_liste_auswahl");
    addnav("Verfluchen/Segnen","waldlichtung.php?op=fluch");
    addnav("Aufräumen","waldlichtung.php?op=sauber");
    if ($mage >= 3)
    {
            addnav('Massenmail','waldlichtung.php?op=massmail');
    }
    addnav("Verschiedenes");
    addnav("Zurück zum Ritualplatz","waldlichtung.php");
    addnav("Zurück in den Wald","forest.php");
}
else
{
     output("`7Du schleichst durch die Büsche und Sträucher und näherst dich dem geheimen Ort, an dem sich die Magier in eine andere Welt zurückzuziehen pflegen.`n
     Die Luft knistert und eine seltsame Spannung breitet sich in dir aus, als du dich dem Tor näherst. Doch da du nicht dem Zirkel angehörst, bleibt dir der Durchgang verperrt und du kannst nur das Weite suchen, bevor man dich noch entdeckt.`0`n`n");
     addnav("Zurück zum Ritualplatz","waldlichtung.php");
}
break;
case 'massmail': // Massenmail (im wohnviertel by mikay)
{
        $str_out .= "`c`b`2Taubenschlag zwischen den Sphären.`b`c`n`n";

        addnav('Abbrechen','waldlichtung.php?op=secret');

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
                $str_out .= form_header('waldlichtung.php?op=massmail')
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
                                        <span id="but" style="visibility:hidden;"><input type="submit" value="Tauben auf die Reise schicken!" class="button"><br></span>
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
                $str_out .= '`c`bEs wurden noch keine Magier ernannt - und ja, Bombentauben an missliebige Nachbarn sind gegen das Gesetz.`b`c';
        }
        output($str_out);
        break;
} // END massmail
case 'bewerben':

    $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_MAGE." OR profession=".PROF_MAGE_HEAD.")";
    $res = db_query($sql);
    $p = db_fetch_assoc($res);

    if ($session['user']['dragonkills'] < getsetting('priestreq',15))
    {
        output("Du musst mindestens ".getsetting('priestreq',15)."mal den grünen Drachen getötet haben, um Magier werden zu können!");
        addnav("Zurück","waldlichtung.php?op=mage_list");
    }
    else if ($p['anzahl'] >= getsetting("numberofmages",3))
    {
        output("Es gibt bereits ".$p['anzahl']." Magier. Mehr werden zur Zeit nicht benötigt!");
        addnav("Zurück","waldlichtung.php?op=mage_list");
    }
    else
    {
        output("Nach reiflicher Überlegung beschließt Du, ein Magier werden zu wollen. Weiterhin gelten für den Magierzirkel die folgenden, unverletzbaren Regeln:`n`n");
        show_rules();
        output("`nAls Magier wärst Du daran unbedingt gebunden!`nSteht Dein Entschluss immer noch fest?");
        addnav("Ja!","waldlichtung.php?op=bewerben_ok&id=".$session['user']['acctid']);
        addnav("Nein, zurück!","waldlichtung.php?op=mage_list");
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
    addnav("Zurück","waldlichtung.php?op=mage_list");
    break;

case 'bewerben_abbr':
    $session['user']['profession'] = 0;

    output("Du hast deine Bewerbung erfolgreich zurückgenommen!`n");
    addnav("Zurück","waldlichtung.php?op=mage_list");
    break;

case 'aufh':
    $session['user']['profession'] = 0;

    $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_MAGE_HEAD." ORDER BY loggedin DESC,RAND() LIMIT 1";
    $res = db_query($sql);
    if (db_num_rows($res))
    {
        $p=db_fetch_assoc($res);
        systemmail($p['acctid'],"`&Austritt!`0","`&".$session['user']['name']."`& hat den Zirkel heute verlassen.");
    }

    addnews($session['user']['name']." `&ist seit dem heutigen Tage nicht mehr im Zirkel der Magier!");

    addhistory('`2Aufgabe des Magierdaseins');

    output("Etwas wehmütig legst Du die Insignien ab und bist ab sofort wieder ein normaler Bürger!`n");
    addnav("Zur Waldlichtung","waldlichtung.php");
    addnav("Zum Wald","forest.php");
    break;

case 'entlassen':
    output("Diesen Magier wirklich entlassen?`n");
    addnav("Ja!","waldlichtung.php?op=entlassen_ok&id=".$_GET['id']);
    addnav("Nein, zurück!","waldlichtung.php?op=mage_list_admin");
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
    addnav("Zurück","waldlichtung.php?op=mage_list_admin");
    break;

case 'aufnehmen':
    $pid = (int)$_GET['id'];

    $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_MAGE." OR profession=".PROF_MAGE_HEAD.")";
    $res = db_query($sql);
    $p = db_fetch_assoc($res);

    if ($p['anzahl'] >= getsetting("numberofmages",3))
    {
        output("Es gibt bereits ".$p['anzahl']." Magier! Mehr sind zur Zeit nicht möglich.");
        addnav("Zurück","waldlichtung.php?op=mage_list_admin");
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

        addnav("Willkommen!","waldlichtung.php?op=mage_list_admin");

        output("".($session['user']['sex']?"Die neue Magierin":"Der neue Magier")." ist jetzt aufgenommen!");
    }
    break;

case 'ablehnen':
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

    addnav("Zurück","waldlichtung.php?op=mage_list_admin");
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

    addnav("Hallo Chef!","waldlichtung.php?op=mage_list_admin");
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

    addnav("Das wars dann!","waldlichtung.php?op=mage_list_admin");
    break;

case 'hochz':

    if (getsetting("mage_status",0) != 0 && getsetting("mage_status",0) != STATUS_ABGESCHLOSSEN)
    {
        output("Gerade jetzt findet ein Hochzeitsritual statt! Du willst doch da nicht stören?");
        addnav("Zurück","waldlichtung.php?op=married_list_admin");
    }
    else
    {



            if ($_GET['id1'] && $_GET['id2'])
            {
                savesetting("mage_id1",(int)$_GET['id1']);
                // Partner 1
                savesetting("mage_id2",(int)$_GET['id2']);
                // Partner 2
            }

            savesetting("mage_status",1);
            // Status
            savesetting("mage_mage_id",$session['user']['acctid']);

            output("Du eröffnest die Zeremonie!");

            make_mage_commentary(": `geröffnet die Zeremonie!",$session['user']['acctid']);

            addnav("Los gehts!","waldlichtung.php");

    }

    break;

case 'hochz_ok':

    if (getsetting('mage_id1',0) == getsetting('mage_mage_id',0) || getsetting('mage_id1',0) == getsetting('mage_mage_id',0))
    {

        output("Du kannst dich nicht selbst verheiraten! Frage einen anderen Magier, ob er das für Dich übernimmt.");

    }
    else
    {

        //                        hochz(getsetting('mage_id1',0),getsetting('mage_id2',0),true);

        $sql = "SELECT name,acctid,guildid,guildfunc FROM accounts
WHERE acctid=".getsetting('mage_id1',0)." OR acctid=".getsetting('mage_id2',0)." ORDER BY sex";
        $res = db_query($sql);
        $p1 = db_fetch_assoc($res);
        $p2 = db_fetch_assoc($res);

        // Hier evtl. LOCK TABLE...

        $sql = "UPDATE accounts SET charisma = 4294967295, charm=charm+1, donation=donation+1, gems=gems+1
WHERE acctid=".getsetting('mage_id1',0)." OR acctid=".getsetting('mage_id2',0);
        db_query($sql) or die(db_error(LINK));

        $sql = "INSERT INTO news SET newstext = '`%".$p1['name']." `&und `%".$p2['name']."`& haben heute feierlich den Bund der Ehe geschlossen!!!',newsdate=NOW(),accountid=".$p1['acctid'];
        db_query($sql) or die(db_error(LINK));

        systemmail($p1['acctid'],"`&Verheiratet!`0","`& Du und `&".$p2['name']."`& habt im Rahmen eines Rituals auf der Waldlichtung geheiratet!`nGlückwunsch!`nAls Geschenk erhält jeder von euch einen Edelstein.");
        systemmail($p2['acctid'],"`&Verheiratet!`0","`& Du und `&".$p1['name']."`& habt im Rahmen eines Rituals auf der Waldlichtung geheiratet!`nGlückwunsch!`nAls Geschenk erhält jeder von euch einen Edelstein.");

        addhistory('`vHeirat mit '.$p1['name'],1,$p2['acctid']);
        addhistory('`vHeirat mit '.$p2['name'],1,$p1['acctid']);

        savesetting("mage_status",2);
        // Status
        make_mage_commentary(": `gerklärt ".$p1['name']."`g und ".$p2['name']."`g offiziell zu Mann und Frau!",$session['user']['acctid']);

        // Gildensystem
        require_once(LIB_PATH.'dg_funcs.lib.php');
        $state = 0;
        if (($p1['guildid']  && $p1['guildfunc'] != DG_FUNC_APPLICANT) )
        {
            $guild1 = &dg_load_guild($p1['guildid'],array('treaties','points'));
        }
        if (($p2['guildid']  && $p2['guildfunc'] != DG_FUNC_APPLICANT) )
        {
            $guild2 = &dg_load_guild($p2['guildid'],array('treaties','points'));
        }
        if ($guild1 && $guild2)
        {
            $state = dg_get_treaty($guild2['treaties'][$p1['guildid']]);
        }

        $points = ($state == 1 ? $dg_points['wedding_friendly'] : ($state == 0 ? $dg_points['wedding_neutral'] : 0) );

        if ($guild1)
        {
            $guild1['points'] += $points;
        }
        if ($guild2)
        {
            $guild2['points'] += $points;
        }

        dg_save_guild();
        // END Gildensystem


    }

    redirect('waldlichtung.php');
    break;

case 'hochz_ende':

    make_mage_commentary(": `gschließt die Zeremonie ab.",$session['user']['acctid']);

    savesetting("mage_status",3);
    savesetting("mage_mage_id","0");
    // Status

    redirect('waldlichtung.php');
    break;

case 'hochz_schnell':

    if ($session['user']['gold'] < SCHNELLHOCHZ_KOSTEN)
    {

        output("Du verfügst leider nicht über genug Gold, weswegen die Magier dein Gesuch zurückweisen!");

    }
    else
    {

        output("Willst Du wirklich diesen Schritt gehen? Bedenke auch, dass eine Schnellhochzeit nicht die Vorteile einer Zeremonie des Magierzirkels bietet!");
        addnav("Ja, ich will!","waldlichtung.php?op=hochz_schnell_ok");
    }

    addnav("Zum Ritualplatz","waldlichtung.php");

    break;

case 'hochz_schnell_ok':

    $session['user']['gold'] -= SCHNELLHOCHZ_KOSTEN;

    $sql = "SELECT name,acctid FROM accounts
WHERE acctid=".$session['user']['marriedto'];
    $res = db_query($sql);
    $p = db_fetch_assoc($res);

    $sql = "UPDATE accounts SET charisma = 4294967295
WHERE acctid=".$p['acctid'];
    db_query($sql) or die(db_error(LINK));
    $session['user']['charisma'] = 4294967295;

    addnews("`%".$session['user']['name']." `&und `%".$p['name']."`& haben heute mehr oder weniger feierlich den Bund der Ehe geschlossen!!!");

    systemmail($session['user']['acctid'],"`&Verheiratet!`0","`& Du und `&".$p['name']."`& habt im Rahmen eines Rituals geheiratet!`nGlückwunsch!");
    systemmail($p['acctid'],"`&Verheiratet!`0","`& Du und `&".$session['user']['name']."`& habt im Rahmen eines Rituals geheiratet!`nGlückwunsch!");

    output("Du hast ".$p['name']."`0 geheiratet. Herzlichen Glückwunsch! Auch wenn das Ritual etwas kurz war...");

    addnav("Zum Ritualplatz","waldlichtung.php");
    addnav("Zum Wald","forest.php");

    break;

case 'scheidung':

    if (!$_GET['npc'])
    {

        $id1 = (int)$_GET['id1'];
        $id2 = (int)$_GET['id2'];

        $sql = "SELECT name,acctid FROM accounts
WHERE acctid=".$id1." OR acctid=".$id2." ORDER BY sex";
        $res = db_query($sql);
        $p1 = db_fetch_assoc($res);
        $p2 = db_fetch_assoc($res);

        // Hier evtl. LOCK TABLE...

        $sql = "UPDATE accounts SET charisma = 0, marriedto=0
WHERE acctid=".$id1." OR acctid=".$id2;
        db_query($sql) or die(db_error(LINK));

        $sql = "INSERT INTO news SET newstext = '`%".$p1['name']." `&und `%".$p2['name']."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!', newsdate=NOW(),accountid=".$p1['acctid'];
        db_query($sql) or die(db_error(LINK));

        addhistory('`tScheidung von '.$p1['name'],1,$p2['acctid']);
        addhistory('`tScheidung von '.$p2['name'],1,$p1['acctid']);

        systemmail($p1['acctid'],"`&Scheidung!`0","`& Du und `&".$p2['name']."`& habt Euch getrennt und Eure Bindung aufgelöst!");
        systemmail($p2['acctid'],"`&Scheidung!`0","`& Du und `&".$p1['name']."`& habt Euch getrennt und Eure Bindung aufgelöst!");

        make_mage_commentary(": `gerklärt ".$p1['name']."`g und ".$p2['name']."`g als geschieden!",$session['user']['acctid']);
    }
    else
    {

        $id = (int)$_GET['id1'];

        $sql = "SELECT name,acctid,sex FROM accounts
WHERE acctid=".$id;
        $res = db_query($sql);
        $p = db_fetch_assoc($res);

        $sql = "UPDATE accounts SET charisma = 0, marriedto=0
WHERE acctid=".$id;
        db_query($sql) or die(db_error(LINK));

        $npc_name = (($p['sex']==0)?"Ophelía":"Silas");

        $sql = "INSERT INTO news SET newstext = '`%".$p['name']." `&und `%".$npc_name."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!', newsdate=NOW(),accountid=".$p['acctid'];
        db_query($sql) or die(db_error(LINK));

        systemmail($p['acctid'],"`&Scheidung!`0","`& Du und `&".$npc_name."`& habt Euch getrennt und Eure Bindung aufgelöst!");
        make_mage_commentary(": `gerklärt ".$p['name']."`g und ".$npc_name."`g als geschieden!",$session['user']['acctid']);

    }

    output("Erfolgreich geschieden!");

    addnav("Zurück","waldlichtung.php?op=secret");

    break;

case 'trennung':

    $id1 = (int)$_GET['id1'];
    $id2 = (int)$_GET['id2'];

    $sql = "SELECT name,acctid FROM accounts
WHERE acctid=".$id1." OR acctid=".$id2." ORDER BY sex";
    $res = db_query($sql);
    $p1 = db_fetch_assoc($res);
    $p2 = db_fetch_assoc($res);

    $sql = "UPDATE accounts SET charisma = 0, marriedto=0
WHERE acctid=".$id1." OR acctid=".$id2;
    db_query($sql) or die(db_error(LINK));

    //$sql = "INSERT INTO news SET newstext = '`%".$p1['name']." `&und `%".$p2['name']."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!', newsdate=NOW(),accountid=".$p1['acctid'];
    //db_query($sql) or die(db_error(LINK));

    systemmail($p1['acctid'],"`&Trennung!`0","`& Du und `&".$p2['name']."`& habt Euch getrennt und Eure Verlobung aufgelöst!");
    systemmail($p2['acctid'],"`&Trennung!`0","`& Du und `&".$p1['name']."`& habt Euch getrennt und Eure Verlobung aufgelöst!");

    make_mage_commentary(": `gerklärt ".$p1['name']."`gs und ".$p2['name']."`gs Verlobung als aufgelöst!",$session['user']['acctid']);

    output("Verlobung gelöst!");

    addnav("Zurück","waldlichtung.php?op=secret");

    break;

case 'flirt_list':
    show_flirt_list($mage);

    addnav("Zurück","waldlichtung.php?op=secret");
    break;

case 'married_list':
    show_flirt_list($mage,1);

    addnav("Zurück","waldlichtung.php?op=secret");
    break;

case 'married_list_npc':
    show_flirt_list($mage,2);

    addnav("Zurück","waldlichtung.php?op=secret");
    break;

case 'married_list_public':
    show_flirt_list(0,1);

    addnav("Zurück","waldlichtung.php");
    break;

case 'fluch':

    output("Als Magier kannst Du respektlosen Individuen einen Fluch aufzwingen, der sie beim Kampf beeinträchtigt. Oder einen Segen, je nachdem. Beides verschwindet von selbst nach einiger Zeit.`n
Nutze dies Weise, denn die Götter dulden keinen Missbrauch!`n`n");

    if (!$_POST['name'])
    {
        output('<form action="waldlichtung.php?op=fluch" method="POST">',true);
        output('<input type="text" size="20" name="name">',true);
        output('<input type="submit" size="20" name="ok" value="Suchen">',true);
        output('</form>',true);
        addnav("","waldlichtung.php?op=fluch");
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
            output('<form action="waldlichtung.php?op=fluch_ok" method="POST">',true);
            output('<select name="id" size="1">',true);
            while ($p = db_fetch_assoc($res))
            {
                output("<option value=\"".$p['acctid']."\">".preg_replace("'[`].'","",$p['name'])."</option>",true);
            }
            output('</select> `n',true);
            output('<select name="buff" size="1"><option value="hf1">Fluch</option><option value="hf2">Schlimmer Fluch</option><option value="hs1">Segen</option></select>`n',true);
            output('<input type="submit" size="20" name="ok" value="Los!">',true);
            output('</form>',true);
            addnav("","waldlichtung.php?op=fluch_ok");

        }

    }

    addnav("Zurück","waldlichtung.php?op=secret");

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
    addnav("Zurück","waldlichtung.php?op=secret");

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

            output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td>'.$p['name'].'</td><td><a href="waldlichtung.php?op=fluch_liste&id='.$p['acctid'].'">Genauer betrachten</a></td>',true);

            output('</tr>',true);

            addnav("","waldlichtung.php?op=fluch_liste&id=".$p['acctid']);

        }
        // END for

        output('</table>',true);

    }
    // END flüche vorhanden

    output('',true);

    addnav("Zurück","waldlichtung.php?op=secret");

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

        output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td>'.$p['fluchname'].'</td><td>'.(($p['hvalue'] == 0) ? 'unbegrenzt':$p['hvalue']).'</td><td><a href="waldlichtung.php?op=fluch_del&id='.$p['id'].'">Aufheben</a></td>',true);

        output('</tr>',true);

        addnav("","waldlichtung.php?op=fluch_del&id=".$p['id']);

    }
    // END for

    output('</table>',true);

    addnav("Zurück","waldlichtung.php?op=fluch_liste_auswahl");

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

    addnav("Zurück","waldlichtung.php?op=fluch_liste_auswahl");

    break;

case 'board':

    output("`&Du schreitest unter den mächtigen Baum, der seine Äste bis fast auf den Boden hängen lässt und betrittst scheinbar eine andere Welt.`nGeschützt von den schirmenden Zweigen der Trauerweide schwirren Feen, kaum mehr als winzige Lichtpunkte, um den mächtigen Stamm des Baues herum.`nSie flüstern dir Neuigkeiten ins Ohr und nehmen jedes deiner Worte wissbegierig auf, um es weiter zu erzählen.`n`n");

    board_view('mage',($mage>=2)?2:0,'Folgendes wird dir zugeflüstert:','Die Feen scheinen stumm zu sein.');

    output("`n`n");

    if ($mage >= 2)
    {

        board_view_form("Flüstern","`&Hier kannst du einer Fee etwas zuflüstern:");
        if ($_GET['board_action'] == "add")
        {
            board_add('mage');
            redirect("waldlichtung.php?op=board");
        }
    }

    addnav("Zurück","waldlichtung.php?op=secret");

    break;

case 'sauber':        // Kommentare entfernen

output("`2Du denkst dir, dass es mal wieder an der Zeit wäre die Lichtung von den Ereignissen der Vergangenheit zu bereinigen, um das nächste Ritual vorbereiten zu können. Alle Ereignisse geraten damit in Vergessenheit.`nIst es das was du willst?");
addnav("Ja, aufräumen!","waldlichtung.php?op=sauber_ok");
addnav("Nein, zurück!","waldlichtung.php");
break;

case 'sauber_ok':
    savesetting("mage_id1","0");
    savesetting("mage_id2","0");
    savesetting("mage_status","0");
    savesetting("mage_mage_name"," ");
    savesetting("mage_mage_id","0");

    // Sicherung
    $sql = "UPDATE commentary SET section='mage_s' WHERE section='mage'";
    db_query($sql);
    // Sicherung Ende

    redirect("waldlichtung.php");
    break;

    default:
    output("Hier dürfte ich gar nicht sein.. op:".$op.",is_mage:".$mage);
    addnav("Zurück in den Wald","forest.php");
    break;
}
page_footer();

// END waldlichtung.php
?>

