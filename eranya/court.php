
<?php
// Richter-Addon : Ergänzung zu Dorfamt u. Stadtwache
// Benötigt : [profession] (shortint, unsigned) in [accounts]
//             Tabellen [crimes],[cases]

// by Maris (Maraxxus@gmx.de)

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
require_once(LIB_PATH.'profession.lib.php');

page_header("Der Gerichtshof");

if (!isset($session))
{
    exit();
}

$op = ($_GET['op']) ? $_GET['op'] : "court";

if ($_GET['op']=="newsdelete")
{
    $sql = "DELETE FROM crimes WHERE newsid='$_GET[newsid]'";
    db_query($sql);
    $return = $_GET['return'];
    $return = preg_replace("'[?&]c=[[:digit:]-]*'","",$return);
    $return = substr($return,strrpos($return,"/")+1);
    redirect($return);
}

switch ($op)
{

case 'bewerben':

    output("`&Du holst tief Luft und öffnest langsam die schwere Eichentüre. Ein betagter Mann mit dichtem Backenbart sitzt hinter einem Tisch aus dunklem Holz und ist gerade in seine Arbeit vertieft. Als die Geräusche deiner Schritte auf dem Holzboden zu ihm dringen blickt er auf. \"`#Wen haben wir denn hier?`&\" fragt er mit einem sadistischem Grinsen. Nachdem du dich vorgestellt und ihm dein Anliegen mitgeteilt hast kneift er die Augen zusammen.`n`n");
    $maxamount = getsetting("numberofjudges",10);
    $reqdk = getsetting("judgereq",50);

    $sql = "SELECT profession FROM accounts WHERE profession=".PROF_JUDGE_HEAD." OR profession=".PROF_JUDGE;
    $result = db_query($sql) or die(db_error(LINK));
    if ((db_num_rows($result)) < $maxamount)
    {

        if (($session['user']['profession']==PROF_JUDGE_ENT) || ($session['user']['profession']==24))
        {
            output("\"`# ".($session['user']['name'])."! So sehr ich Euren Wunsch nachempfinden kann wieder richten zu dürfen muss ich Euch jedoch enttäuschen. Ihr hattet Eure Chance! Und nun verlasst mein Büro!`&\"");
        }
        else
        {
            output("\"`# ".($session['user']['name'])."!`# Ich hoffe Ihr wisst worauf Ihr Euch hier einlasst? Das Amt des Richters ist hart und entbehrungsreich. Und an Euch werden besondere Forderungen gestellt : Ihr müsst sowohl ruhmreich wie auch von höchstem Ansehen sein und in Eurem Verhalten ein Vorbild!`&\"`n`n");

            if (($session['user']['dragonkills']) >= $reqdk)
            {
                if ($session['user']['reputation']>=50)
                {
                    output("\"`#Ich sehe, ich sehe... Ihr seid sowohl ruhmreich, wie auch von allerhöchstem Ansehen! Das ist gut, sehr gut. Meinetwegen könnt Ihr sofort anfangen. Doch wisset, dass Ihr als Richter nicht nur Rechte, sondern auch Pflichten habt. Es ist Euch strengstens untersagt mit zwielichtigen Gesellen Kontakte zu knüpfen, auch nicht zur Täuschung! Jedes Eurer Urteile muss gerecht und nachvollziehbar sein! Geschenke anzunehmen ist Euch strengstens untersagt!`n Dem obersten Richter habt Ihr Folge zu leisten! Sollte man Euch bei irgendeinem Verstoß oder irgendeiner Unehrenhaftigkeit erwischen, seid Ihr für lange Zeit Richter gewesen! Sind wir uns da einige?`nAlso, wollt Ihr noch immer ?`&\"");
                    addnav("Ja, Richter werden","court.php?op=bewerben_ok");
                }
                else
                {
                    output("\"`#Ruhmreich seid mehr als es von Nöten wäre, doch fürchte ich, dass Euch die Leute nicht trauen würden, wenn Ihr plötzlich in Richterrobe daher kämet. Tut mal etwas für Euer Ansehen und versucht es dann noch einmal!`&\"");
                }
            }
            else
            {
                output("\"`#Ihr seid zwar ruhmreich, doch wie es mir scheint nicht ruhmreich genug. Ihr solltet noch mehr Ruhm im Kampf gegen den Drachen erlangen und es dann noch einmal versuchen!`&\"");
            }
        }
        // Kein entlassener
    }
    // Noch nicht zu viele
    else
    {
        output("\"`#Es tut mir sehr leid, aber die Stadt hat zur Zeit genügend Richter. Versucht es doch später noch einmal!`&\"");
    }

    addnav("Zurück","dorfamt.php");

    break;

case 'bewerben_ok':

    output("`&Du überreichst dem alten Mann dein Bewerbungsschreiben. Dieser verstaut es unter einem hohen Stapel Pergamenten und meint: \"Wir werden auf dich zurückkommen!\"");
    $session['user']['profession']=PROF_JUDGE_NEW;
    $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_JUDGE_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
    $res = db_query($sql);
    if (db_num_rows($res))
    {
        $w = db_fetch_assoc($res);
        systemmail($w['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& hat sich als Richter beworben. Du solltest die Bewerbung überprüfen und eine Entscheidung treffen.");
    }

    addnav("Zurück","dorfamt.php");

    break;

case 'bewerben_abbr':

    $session['user']['profession'] = 0;
    output("Du ziehst deine Bewerbung zurück.");
    addnav("Zurück","dorfamt.php");

    break;

case 'aufn':

    $pid = (int)$_GET['id'];

    $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_JUDGE_HEAD." OR profession=".PROF_JUDGE.")";
    $res = db_query($sql);
    $p = db_fetch_assoc($res);

    if ($p['anzahl'] >= getsetting("numberofjudges",10))
    {
        output("Es gibt bereits ".$p['anzahl']." Richter! Mehr sind zur Zeit nicht möglich.");
        addnav("Zurück","court.php?op=listj");
    }
    else
    {

        $sql = "UPDATE accounts SET profession = ".PROF_JUDGE."
WHERE acctid=".$pid;
        db_query($sql) or die(db_error(LINK));

        $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
        $res = db_query($sql);
        $p = db_fetch_assoc($res);

        systemmail($pid,"Du wurdest aufgenommen!",$session['user']['name']."`& hat deine Bewerbung zum Richter angenommen. Damit bist du vom heutigen Tage an offiziell Hüter für Recht und Ordnung!");

        $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute offiziell das ehrenvolle Amt eines Richters zugewiesen!',newsdate=NOW(),accountid=".$pid;
        db_query($sql) or die(db_error(LINK));

        addhistory('`2Aufnahme ins Richteramt',1,$pid);

        addnav("Willkommen!","court.php?op=listj");

        output("Der neue Richter ist jetzt aufgenommen!");
    }

    break;

case 'abl':

    $pid = (int)$_GET['id'];

    $sql = "UPDATE accounts SET profession = 0
WHERE acctid=".$pid;
    db_query($sql) or die(db_error(LINK));

    systemmail($pid,"Deine Bewerbung wurde abgelehnt!",$session['user']['name']."`& hat deine Bewerbung als Richter abgelehnt.");

    addnav("Zurück","court.php?op=listj");

    break;

case 'entlassen':

    $pid = (int)$_GET['id'];

    $sql = "UPDATE accounts SET profession = 0
WHERE acctid=".$pid;
    db_query($sql) or die(db_error(LINK));

    $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
    $res = db_query($sql);
    $p = db_fetch_assoc($res);

    systemmail($pid,"Du wurdest entlassen!",$session['user']['name']."`& hat dich als Richter entlassen!");

    $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute vom Amt eines Richters enthoben!',newsdate=NOW(),accountid=".$pid;
    db_query($sql) or die(db_error(LINK));

    addhistory('`$Entlassung aus dem Richteramt',1,$pid);

    addnav("Weiter","court.php?op=listj");

    output("Der Richter wurde entlassen!");

    break;

case 'leave':

    output("`&Mit schlotternden Knien betrittst du das Zimmer, in dem der ältere Herr mit dem Backenbart wie gewohnt hinter seinem Schreibtisch sitzt. Als du eintrittst und ihm die Hand reichst bittet er dich Platz zu nehmen und schau dich erwartungsvoll an.`nWillst du wirklich dein Richteramt aufgeben?");
    addnav("Ja, austreten!","court.php?op=leave_ok");
    addnav("NEIN. Dabei bleiben","dorfamt.php");

    break;

case 'leave_ok':

    output("`&Du bittest um deine Entlassung und der ältere Herr erledigt sichtlich schweren Herzens alle Formalitäten \"`#Wirklich schade, dass Ihr geht! Ich danke Euch vielmals für die treuen Dienste, die Ihr der Stadt geleistet habt und werde Euch nie vergessen! Beachtet, dass Eure Entlassung erst mit Beginn des morgigen Tages wirksam wird. Für heute seid Ihr jedoch beurlaubt.`&\"");
    addnews("".$session['user']['name']."`@ hat das Richteramt niedergelegt. Die Gaunerwelt atmet auf.");
    $session['user']['profession'] = PROF_JUDGE_ENT;

    addhistory('`2Aufgabe des Richteramts');

    addnav("Zurück ins Zivilleben","dorfamt.php");

    break;

case 'court':

    addcommentary();

    output("`c`&".$profs[PROF_JUDGE_HEAD][4]." `bDer Gerichtshof von ".getsetting('townname','Atrahor')."`b`c`n");
    output("`ßDieser Teil des Gebäudes ist dem Gerichtswesen zugeteilt. Mehrere Türen sind links und rechts des breiten Ganges zu erkennen und auf
            großen Holztäfelchen steht geschrieben, was sich dahinter verbirgt.`n
            Manche Türen sind für dich verschlossen, andere zugänglich.");
    addnav("Öffentliches");
    addnav("Verhandlungsraum","court.php?op=thecourt");
    addnav("Liste der Richter","court.php?op=listj");
    addnav("Gesetzbuch","court.php?op=civilcode");
    addnav("Gerichtsschreiber");
    addnav("Zum Gerichtsschreiber","court.php?op=schreiber");
    if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD
    || su_check(SU_RIGHT_DEBUG))
    {

        addnav("Arbeit");
        addnav("Verdächtige Taten","court.php?op=news");
        addnav("Aktuelle Fälle","court.php?op=cases");
        //addnav("Kopfgeldliste","court.php?op=listh");
        addnav("Schwarzes Brett","court.php?op=board");
        addnav("Beratungsraum","court.php?op=judgesdisc");
        if ($session['user']['profession'] == PROF_JUDGE_HEAD || su_check(SU_RIGHT_DEBUG))
        {
            addnav('Massenmail','court.php?op=massmail');
        }
        addnav("Archiv");
        addnav("Urteile","court.php?op=archiv");
        addnav("Handbuch für Jungrichter","court.php?op=faq");
    }
    addnav('Anträge');
    if ($session['user']['profession']==0)
    {
        addnav("Richter werden","court.php?op=bewerben",false,false,false,false);
    }
    if ($session['user']['profession']==25)
    {
        addnav("Bewerbung zurückziehen","court.php?op=bewerben_abbr",false,false,false,false);
    }
    if (($session['user']['profession']==21) || ($session['user']['profession']==22))
    {
        addnav("Entlassung erbitten","court.php?op=leave",false,false,false,false);
    }
    
    addnav('Sonstiges');
    if(getsetting('court_rproom_active',0) == 1 || $session['user']['profession'] == PROF_JUDGE_HEAD || su_check(SU_RIGHT_DEBUG)) {
        $str_rproom_name = getsetting('court_rproom_name','');
        $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
        $str_rproom_active = (getsetting('court_rproom_active',0) == 1 ? '' : ' (gesperrt)');
        addnav($str_rproom_name.$str_rproom_active,'court.php?op=rproom',false,false,false,false);
    }
    addnav('Beschreibung aller Berufsgruppen','library.php?op=book&bookid=51');
    addnav("Zurück");
    addnav("Gericht verlassen","dorfamt.php");
    output("`n`n");
    //$bool_showform = ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $session['user']['profession']==PROF_JUDGE_NEW || $session['user']['superuser']);
    viewcommentary("court","Sprechen:",30,"spricht"); //,false,$bool_showform);

    break;

case 'massmail': // Massenmail (im wohnviertel by mikay)
{
        $str_out .= "`c`b`2Posthörnchenkobel unter dem Dach des Gerichts.`b`c`n`n";

        addnav('Abbrechen','court.php?op=court');

        $sql='SELECT acctid, name, login, profession
                FROM accounts
                WHERE profession='.PROF_JUDGE.'
                OR profession='.PROF_JUDGE_HEAD.'
                OR profession='.PROF_JUDGE_NEW.'
                AND acctid!='.(int)$session['user']['acctid'].'
                ORDER BY profession DESC';
        $result=db_query($sql);
        $users=array();
        $keys=0;

        while($row=db_fetch_assoc($result))
        {
                $profs[0][0]='Zivilist';
                if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';

                $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" onclick="chk()" '.($row['profession']!=PROF_JUDGE_NEW ? 'checked':'').'> '.$row['name'].'<br>';
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

                $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler haben eine Hörnchenpost erhalten und deine Kosten betragen '.$gemcost.' Edelsteine.<br><br>';
                $session['user']['gems']-=$gemcost;
        }
        elseif ($session['user']['gems']<$gemcost AND $mailsends>0)
        {
                $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler hätten eine Hörnchenpost erhalten, wenn deine Kosten nicht '.$gemcost.' Edelsteine betragen würden. Leider kannst du dies nicht bezahlen.<br><br>';
        }

        if ($keys>0)
        {
                $str_out .= form_header('court.php?op=massmail')
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
                                        <span id="but" style="visibility:hidden;"><input type="submit" value="Posthörnchen auf die Reise schicken!" class="button"><br></span>
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
                $str_out .= '`c`bEs wurden noch keine Richter ernannt - und ja, explosive Hörnchenpost an missliebige Nachbarn sind gegen das Gesetz.`b`c';
        }
        output($str_out);
        break;
} // END massmail

case 'board':

    output("`&Du stellst dich vor das große Brett und schaust ob eine neue Mitteilung vorliegt.`n");
    //addcommentary();
    // if (($session['user']['profession']==2) || ($session['user']['superuser']>1))
    //{
        output("`tDu kannst eine Notiz hinterlassen oder entfernen.`n`n");

        if ($_GET['board_action'] == "add")
        {

            board_add('richter');

            redirect("court.php?op=board&ret=$_GET[ret]");

        }
        else
        {

            board_view_form('Hinzufügen','');

            board_view('richter',2,'','',true,true,true);
        }

        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }

        break;

    case 'listj':

        $admin = ($session['user']['profession'] == PROF_JUDGE_HEAD || su_check(SU_RIGHT_DEBUG)) ? true : false;

        output("<span style='color: #9900FF'>",true);
        $sql = "SELECT name,acctid,loggedin,dragonkills,login,level,profession,activated,laston FROM accounts WHERE profession=21 OR profession=22 OR profession=23 OR profession=25
ORDER BY profession DESC, level DESC";
        $result = db_query($sql) or die(db_error(LINK));
        output("`&Folgende Helden sind Richter:`n`n");
        output("<table border='0' cellpadding='5' cellspacing='2' bgcolor='#999999'><tr class='trhead'><td>Name</td><td>Level</td><td>Funktion</td><td>",true);
        if ($admin)
        {
            output('Aktionen',true);
        }
        output("</td><td>Status</td></tr>",true);
        $lst=0;
        $dks=0;
        for ($i=0; $i<db_num_rows($result); $i++)
        {
            $row = db_fetch_assoc($result);
            $lst+=1;
            $dks+=$row['dragonkills'];
            if($session['user']['prefs']['popupbio'] == 1)
                {
                    $str_biolink = "<a href='bio_popup.php?char=".rawurlencode($row['login'])."' target='_blank' onClick='".popup_fullsize('bio_popup.php?char='.rawurlencode($row['login'])).";return:false;'>".$row['name']."</a>";
                }
                else
                {
                    $str_biolink = "<a href='bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."'>".$row['name']."</a>";
                    addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
                }
            output("<tr class='".($lst%2?"trlight":"trdark")."'><td><a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>".$str_biolink."</td><td>$row[level]</td><td>",true);
            if ($row['profession']==PROF_JUDGE)
            {

                output("`#Richter`&</td><td>",true);
                if ($admin)
                {
                    output('<a href="court.php?op=entlassen&id='.$row['acctid'].'">Entlassen</a>',true);
                    addnav("","court.php?op=entlassen&id=".$row['acctid']);
                }
            }
            if ($row['profession']==PROF_JUDGE_HEAD)
            {
                output("`4Oberster Richter`&</td><td>",true);
            }
            if ($row['profession']==PROF_JUDGE_ENT)
            {
                output("`6Entlassung läuft`&</td><td>",true);
            }
            if ($row['profession']==PROF_JUDGE_NEW)
            {

                output("`@Bittet um Aufnahme`&</td><td>",true);
                if ($admin)
                {
                    output('<a href="court.php?op=aufn&id='.$row['acctid'].'">Aufnehmen</a>`n',true);
                    addnav("","court.php?op=aufn&id=".$row['acctid']);
                    output('<a href="court.php?op=abl&id='.$row['acctid'].'">Ablehnen</a>',true);
                    addnav("","court.php?op=abl&id=".$row['acctid']);
                }

            }
            output("</td><td>",true);
            if (user_get_online(0,$row))
            {
                output("`@online`&",true);
            }
            else
            {
                output("`4offline`&",true);
            }
            output("</td></tr>",true);
        }
        db_free_result($result);
        output("</table>",true);
        output("</span>",true);
        output("<big>`n`@Gemeinsame Drachenkills der Richter: `^$dks`n`n`&<small>",true);

        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }

        break;

    case 'listh':

        output("<span style='color: #9900FF'>",true);
        output("`&Die Kopfgeldliste:`n`n");

        $sql = "SELECT name,acctid,location,bounty,laston,alive,housekey,loggedin,login,level,activated,restatlocation FROM accounts WHERE bounty>0
ORDER BY bounty DESC";
        $result = db_query($sql) or die(db_error(LINK));

        output("<table border='0' cellpadding='4' cellspacing='1' bgcolor='#999999'><tr class='trhead'><td>Kopfgeld</td><td>Level</td><td>Name</td><td>Ort</td><td>Lebt?</td></tr>",true);
        $lst=0;

        for ($i=0; $i<db_num_rows($result); $i++)
        {
            $row = db_fetch_assoc($result);

            $lst+=1;
            if($session['user']['prefs']['popupbio'] == 1)
                {
                    $str_biolink = "<a href='bio_popup.php?char=".rawurlencode($row['login'])."' target='_blank' onClick='".popup_fullsize('bio_popup.php?char='.rawurlencode($row['login'])).";return:false;'>".$row['name']."</a>";
                }
                else
                {
                    $str_biolink = "<a href='bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."'>".$row['name']."</a>";
                    addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
                }
            output("<tr class='".($lst%2?"trlight":"trdark")."'><td>".($row['bounty'])."</td><td>".($row['level'])."</td><td>".$str_biolink,true);
            output("</td><td>",true);

            if ($row['location'] == USER_LOC_FIELDS)
            {
                output(user_get_online(0,$row)?"`@online":"`3Die Felder",true);
            }

            if ($row['location']==USER_LOC_INN)
            {
                output("`3Zimmer in Kneipe`0",true);
            }
            if ($row['location']==USER_LOC_PRISON)
            {
                output("`3Im Kerker`0",true);
            }
            if ($row['location']==USER_LOC_HOUSE)
            {
                $loc=$row['restatlocation'];
                output("Haus Nr. $loc",true);
            }
            output("</td><td>",true);
            if ($row['alive'])
            {
                output("`@lebt`&",true);
            }
            else
            {
                output("`4tot`&",true);
            }
            output("</td></tr>",true);
        }
        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }

        db_free_result($result);
        output("</table>",true);
        output("</span>",true);

        break;

    case 'civilcode':
    
        output("`c`&".$profs[PROF_JUDGE_HEAD][4]." `bDer Gerichtshof von ".getsetting('townname','Atrahor')."`b`c`n");
        output("`ßVor dir aufgeschlagen, liegt das Gesetzbuch Eranyas. Alle Urteile, die die ehrenwerten Richter der Stadt tagtäglich fällen, basieren auf den folgenden niedergeschriebenen
                Gesetzen:`n
                `n
               ".get_extended_text('gesetzbuch'));
        addnav('Zurück');
        addnav('Zum Gerichtshof','court.php?op=court');
        break;

    case 'news':

        $daydiff = ($_GET['daydiff']) ? $_GET['daydiff'] : 0;
        $min = $daydiff-1;

        $sql = "SELECT newstext,newsdate,newsid,accountid FROM crimes WHERE (DATEDIFF(NOW(),newsdate) <= ".$daydiff." AND DATEDIFF(NOW(),newsdate) > ".$min.")
ORDER BY newsid DESC
LIMIT 0,200";

        /** If you are using mysql < ver 4.1.1 try using the following query :
SELECT newstext,newsdate FROM news WHERE
(newstext LIKE '%freigesprochen%' OR newstext LIKE '%verurteilt%')
AND (UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(newsdate)) <= 86400
ORDER BY newsid DESC
LIMIT 0,200 **/

        $res = db_query($sql);

        output('`&Die verdächtigen Taten von '.(($daydiff==0)?'heute':(($daydiff==1)?'gestern':'vor '.$daydiff.' Tagen')).':`n
                `n
                <table style="border: none;"><tr><td style="vertical-align: top;">`&(Hinweise: </td><td>`ß"Ermitteln" - zeigt Straftaten an, die der Charakter aus der Meldung begangen hat, einschließlich vergangener Taten;`n
                "Löschen" - löscht die Straftat aus dem Register.`&)</td></tr></table>`n`n');


        for ($i=0; $i<db_num_rows($res); $i++)
        {
            $row = db_fetch_assoc($res);
            output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
            output($row['newstext']."`n");

            output("`&[ <a href='court.php?op=inspect&accountid=".$row['accountid']."&daydiff=".$daydiff."'>Ermitteln</a> `&]&nbsp;",true);
            addnav("","court.php?op=inspect&accountid=".$row['accountid']."&daydiff=".$daydiff);


            output("`&[ <a href='court.php?op=newsdelete&newsid=".$row['newsid']."&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Löschen</a> `&]&nbsp;",true);
            addnav("","court.php?op=newsdelete&newsid=".$row['newsid']."&return=".URLEncode($_SERVER['REQUEST_URI']));


        }
        if (db_num_rows($res)==0)
        {
            output("`n`1`b`c Keine offenen Fälle an diesem Tag. `c`b`0`n`n");
        }

        addnav('Neuste Meldungen');
        addnav("Liste aktualisieren","court.php?op=news");
        addnav('Zeitraum');
        addnav("Heute","court.php?op=news");
        addnav("Gestern","court.php?op=news&daydiff=1");
        addnav("Vor 2 Tagen","court.php?op=news&daydiff=2");
        addnav("Vor 3 Tagen","court.php?op=news&daydiff=3");

        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }

        break;

    case 'inspect':

        $sql = "SELECT newstext,newsdate,newsid FROM crimes WHERE accountid=".$_GET['accountid']."
ORDER BY newsid DESC
LIMIT 0,200";
        $res = db_query($sql);

        output("`&Eine genauere Betrachtung bringt folgendes Ergebnis :`n");

        for ($i=0; $i<db_num_rows($res); $i++)
        {
            $row = db_fetch_assoc($res);
            output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
            output("$row[newstext]`n");

        }
        addnav("Anklage erheben","court.php?op=accuse&ret=$_GET[ret]&suspect=".$_GET['accountid']."&daydiff=".$daydiff);
        addnav("Zurück","court.php?op=news&ret=$_GET[ret]");

        break;

    case 'caseinfo':

        $sql = "SELECT * FROM cases WHERE accountid=".$_GET['who']."
                ORDER BY newsid DESC
                LIMIT 0,200";
        $res = db_query($sql);
        output("`&Ein Blick in die Strafakte der Person zeigt dir, dass für folgende Tatbestände Anklage erhoben wurde:`n`n");
        for ($i=0; $i<db_num_rows($res); $i++)
        {
            $row = db_fetch_assoc($res);
            output("`&~ ".$row['newstext']."`n`n");
        }
        if ($row['court']==0)
        {
            output("`n`n`&Das Verfahren wurde eröffnet von:`n");
            $sql2 = "SELECT name FROM accounts WHERE acctid=$row[judgeid]";
            $res2 = db_query($sql2);
            $row2 = db_fetch_assoc($res2);
            output($row2['name']);
            if ($session['user']['profession'] != 22)
            {
                output("`n`n`&Ein anderer Richter muss das Urteil verkünden.");
            }

            if ((($session['user']['acctid']!=$row['judgeid']) && ($session['user']['acctid']!=$row['accountid'])) || $session['user']['profession'] == 22)
            {
                addnav('RP-Prozess');
                addnav('Prozess planen','court.php?op=preprozess&ret='.$_GET['ret'].'&who='.$row['accountid']);
                addnav('Prozess führen','court.php?op=prozess&ret='.$_GET['ret'].'&who='.$row['accountid']);
                addnav('Prozess öffentlich führen','court.php?op=prozess&public=1&ret='.$_GET['ret'].'&who='.$row['accountid']);
                addnav('Aktenlage');
                addnav('Verurteilen','court.php?op=guilty&suspect='.$_GET['who'].'&ret='.$_GET['ret']);
                addnav('Freisprechen','court.php?op=notguilty&suspect='.$_GET['who'].'&ret='.$_GET['ret']);
                // Nav-Erklärung
                output('`n`n`n`n
                        `&(Hinweise:`n
                        `n
                        `ßDie Anordnung eines `&RP-Prozesses `ßbedeutet, dass ein RP mit allen beteiligten Charakteren angesetzt wird, in dem der angeklagte Charakter
                        sich vor Gericht verantworten muss. Hierzu werden die teilnehmenden Charaktere vom Richter einberufen, sie erhalten also eine
                        Systemnachricht mit einer Vorladung. Die Option des RP-Prozesses dient - wie der Name schon sagt - dem RP und sollte im Vorfeld
                        sorgfältig geplant werden, ehe der Prozess angesetzt wird. Die Spieler der beteiligten Charaktere (Angeklagter, Zeugen, Richter, ggf.
                        Geschworene) sind in die Planung miteinzubeziehen. Insbesondere die Frage, ob ein Prozess öffentlich geführt wird, d.h. ob auch
                        Außenstehende an dem RP teilnehmen dürfen, sollte mit allen Teilnehmenden besprochen werden.`n
                        Falls es sich im Rahmen der Anklage lediglich um Klickspiel-Vergehen handelt, reicht es, den Charakter entweder `&freizusprechen `ßoder `&zu
                        verurteilen`ß. Im Fall von letzterem entscheidet der Richter anschließend anhand der Anzahl und der Schwere der Straftaten, wie hoch das
                        Strafmaß angesetzt wird, d.h. für wie viele Spieltage ein Charakter in den Kerker muss. Anschließend ist es die Aufgabe der Stadtwachen,
                        den Verurteilten zu verhaften, auf dass er die ihm auferlegte Strafe im Kerker absitzt.`n
                        Die `&Höchststrafe `ßliegt derzeit bei `&'.getsetting("maxsentence",5).' Tagen`ß.`n
                        `n
                        `&Es ist die Pflicht eines jeden Richters, fair und unbestechlich zu urteilen!`n`n');
            }
        }
        elseif ($row['court']==1)
        {
            $persons=array('judge'=>$session['user']['login'], 'counsel'=>'niemand', 'attestor'=>'niemand', 'public'=>'nein');
            if($row['persons']) $persons=unserialize($row['persons']);
            $form = array('RP-Prozessvorbereitung,title',
                          'judge'=>'Richter:', 
                          'counsel'=>'Verteidigung:', 
                          'attestor'=>'Zeugen:',
                          'public'=>'öffentlich?');
            output('<form action="court.php?op=preprozess&who='.$row['accountid'].'" method="POST">');
            addnav('','court.php?op=preprozess&who='.$row['accountid']);
            showform($form,$persons);
            output('</form><hr>');
            addcommentary(false);
            viewcommentary('preprozess'.$row['accountid'],"Planen:",30,"sagt");
            addnav('RP-Prozess');
            addnav("Prozess führen","court.php?op=prozess&ret=$_GET[ret]&who=".$row['accountid']."");
            addnav("Prozess öffentlich führen","court.php?op=prozess&public=1&ret=$_GET[ret]&who=".$row['accountid']);
 
        }
        else
        {
            $persons=array('judge'=>'undefiniert', 'counsel'=>'niemand', 'attestor'=>'niemand');
            if($row['persons']) $persons=unserialize($row['persons']);
            output('`n`n`&Es läuft bereits ein Prozess zu diesem Fall!`n`n
             `n`ßVorsitz: '.$persons['judge'].'
             `n`ßVerteidigung: '.$persons['counsel'].'
             `n`ßZeugen: '.$persons['attestor'].'
             `n`ßÖffentlich: '.$persons['public'].'`0');
        }
        addnav("Sonstiges");
        if ($_GET['proc']==1)
        {
            addnav('Zurück','court.php?op=thecourt2&accountid='.$_GET['who']);
        }
        else
        {
            addnav("Zurück","court.php?op=cases&ret=$_GET[ret]");
        }

        break;

    case 'preprozess': //setzt angegebenen Prozess in den Planungszustand
        $persons=array('judge'=>$session['user']['login'], 'counsel'=>'niemand', 'attestor'=>'niemand', 'public'=>'nein');
        if(isset($_POST['judge']))
        {
            $persons['judge']=$_POST['judge'];
            $persons['counsel']=$_POST['counsel'];
            $persons['attestor']=$_POST['attestor'];
            $persons['public']=$_POST['public'];
        }
        db_query('update cases SET court=1, persons="'.addslashes(serialize($persons)).'" WHERE accountid='.$_GET['who']);
        redirect('court.php?op=caseinfo&ret='.$_GET['ret'].'&who='.$_GET['who']);
        addnav("Zurück","court.php");
        break;

    case 'accuse':

        $sql = "SELECT newstext,newsdate,newsid FROM crimes WHERE accountid=".$_GET['suspect']."
ORDER BY newsid DESC
LIMIT 0,200";
        $res = db_query($sql);

        output("`&Die Verbrechen wurde soeben zur Anklage gebracht.`n");



        for ($i=0; $i<db_num_rows($res); $i++)
        {
            $row = db_fetch_assoc($res);


            addtocases("$row[newstext]",$_GET['accountid']);
            $sql = "DELETE FROM crimes WHERE newsid='$row[newsid]'";
            db_query($sql);

        }

        redirect('court.php?op=news&daydiff='.$_GET['daydiff']);

        addnav("Zurück","court.php?op=news&daydiff=$_GET[daydiff]");

        break;

    case 'cases':

        $sql = "SELECT newsid,accountid,judgeid,court,name FROM cases
                                LEFT JOIN accounts ON accountid = acctid
                                GROUP BY accountid
                                ORDER BY court ASC, newsid DESC
                                LIMIT 0,200";
        $res = db_query($sql);
        $int_count = db_num_rows($res);

        if ($int_count==0) {
            output("`n`1`b`c Zurzeit werden keine Fälle verhandelt. Vielleicht lohnt sich ja ein Blick in die Liste der verdächtigen Taten? `c`b`0");
        } else {
            output('`&Derzeit wird '.$int_count.' Verbrechern der Prozess gemacht.`n
                    Durch einen Klick auf den jeweiligen Charakternamen werden die Optionen zur weiteren Verfahrensweise angezeigt.`n`n');
        }

        for ($i=0; $i<$int_count; $i++)
        {
            $row = db_fetch_assoc($res);
            output("`&~ <a href='court.php?op=caseinfo&ret=$_GET[ret]&who=$row[accountid]'>".($row['name']?$row['name']:$row['accountid'].'`4 (User gelöscht)`0')."</a>".($row['judgeid'] == $session['user']['acctid'] ? ' (Von dir angeklagt)':'').($row['court'] ? ' (Prozess '.($row['court']==1 ? 'in Planung':'läuft').')':'')."`n`n",true);
            addnav("","court.php?op=caseinfo&ret=$_GET[ret]&who=$row[accountid]");
        }

        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }


        break;

    case 'guilty':
        Output("`ßWie lautet dein Strafmaß? Die Höchststrafe liegt derzeit bei `&".getsetting("maxsentence",5)." Tagen`ß.`n");

        $suspect=$_GET['suspect'];
        $ret=$_GET['ret'];
        $proc=$_GET['proc'];

        output('<form method="POST" action="court.php?op=guilty2&ret='.$ret.'&suspect='.$suspect.'&proc='.$proc.'">',true);
        output('`n<input type="text" name="count" id="count"><input type="hidden" name="count2"> <input type="submit" value="Tage Haft"></form>',true);
        addnav('','court.php?op=guilty2&ret='.$ret.'&suspect='.$suspect.'&proc='.$proc.'');
        output("<script language='javascript'>document.getElementById('count').focus();</script>",true);

        if ($_GET['proc']!=1)
        {
            addnav("Zurück","court.php?op=caseinfo&ret=$_GET[ret]&who=$_GET[suspect]");
        }
        else
        {
            addnav("Zurück","court.php?op=thecourt2&ret=$_GET[ret]&accountid=$_GET[suspect]");
        }
        break;

    case 'guilty2':

        $count = $_POST['count'];
        //   $count = abs((int)$_GET[count] + (int)$_POST[count]);
        $maxsentence=getsetting("maxsentence",5);
        if ($count>$maxsentence)
        {
            output("Na, wir wollen es mal nicht übertreiben. Findest du nicht, dass ".$maxsentence." Tage ausreichend wären ?");
        }
        else
        {
            $sql2 = "SELECT name,acctid FROM accounts WHERE acctid=$_GET[suspect]";
            $res2 = db_query($sql2);
            $row2 = db_fetch_assoc($res2);
            $sql3 = "SELECT sentence FROM account_extra_info WHERE acctid=$_GET[suspect]";
            $res3 = db_query($sql3);
            $row3 = db_fetch_assoc($res3);

            $count2=$count+$row3['sentence'];
            if ($count2>$maxsentence)
            {
                $count2=$maxsentence;
            }

            output("`&Alles klar! ".$count." Tage Haft. Die Stadtwachen wurden informiert. ".$row2['name']." `&soll nun für ".$count2." `&Tage hinter Gitter!");
            addnews("`#".($session['user']['sex']?"Richterin ":"Richter ").$session['user']['name']." `&hat `@".$row2['name']."`& zu ".$count." `&Tagen Kerker verurteilt!");

            $mailtext="`@{$session['user']['name']}
            `& hat dich für deine Vergehen zu ".$count." Tagen Kerker verurteilt!`nDiese Strafe wird zu eventuell anderen Strafen hinzugerechnet, jedoch kann deine Haft dadurch nicht länger als ".$maxsentence." Tage werden.`nDeine Vergehen im Einzelnen :`n`n";

            $sql3 = "SELECT newstext FROM cases WHERE accountid=".$row2['acctid']."
ORDER BY newsid DESC
LIMIT 0,200";
            $res3 = db_query($sql3);

            for ($j=0; $j<db_num_rows($res3); $j++)
            {
                $row3 = db_fetch_assoc($res3);
                $mailtext=$mailtext.$row3['newstext']."`n";
            }

            systemmail($row2['acctid'],"`\$Du wurdest verurteilt!`0",$mailtext);

            $sql = "DELETE FROM cases WHERE accountid='$_GET[suspect]'";
            db_query($sql);
            $sql = "UPDATE account_extra_info SET sentence=$count2 WHERE acctid='$_GET[suspect]'";
            db_query($sql);

            if ($_GET['proc']==1)
            {
                $roomname="court".$_GET['suspect'];
                                
                insertcommentary(1,'/msg`^Das Hohe Gericht verurteilt '.$row2['name'].'`^ zu '.$count.' Tagen Kerker und beendet den Prozess.',$roomname);

            }
        }

        if ($_GET['proc']==1)
        {

//            item_delete(' tpl_id="vorl" AND value1='.$_GET['suspect']);
            db_query('UPDATE items SET owner = "0" WHERE value1='.$_GET['suspect']);

        }
        if ($_GET['ret']==1)
        {
            addnav("Zurück","court.php?op=cases");
        }
        else
        {
            addnav("Zurück","court.php?op=cases");
        }
        break;

    case 'notguilty':
        output("Du entscheidest zugunsten des Angeklagten.");

        $sql2 = "SELECT name FROM accounts WHERE acctid=$_GET[suspect]";
        $res2 = db_query($sql2);
        $row2 = db_fetch_assoc($res2);

        addnews("`#".($session['user']['sex']?"Richterin ":"Richter ").$session['user']['name']." `&hat `@".$row2['name']."`& freigesprochen!");

        $sql = "DELETE FROM cases WHERE accountid='$_GET[suspect]'";
        db_query($sql);

        if ($_GET['proc']==1)
        {
            $roomname="court".$_GET['suspect'];
            
            insertcommentary(1,'/msg`@Das Hohe Gericht spricht '.$row2['name'].'`@ in allen Anklagepunkten frei und beendet den Prozess.',$roomname);

        }

        if ($_GET['proc']==1)
        {
//            item_delete(' tpl_id="vorl" AND value1='.$_GET['suspect']);
            db_query('UPDATE items SET owner = "0" WHERE value1='.$_GET['suspect']);
        }

        if ($_GET['ret']==1)
        {
            addnav("Zurück","court.php?op=cases");
        }
        else
        {
            addnav("Zurück","court.php?op=cases");
        }
        break;

    case 'archiv':

        $daydiff = ($_GET['daydiff']) ? $_GET['daydiff'] : 0;
        $min = $daydiff-1;

        $sql = "SELECT newstext,newsdate FROM news WHERE
(newstext LIKE '%freigesprochen%' OR newstext LIKE '%verurteilt%')
AND (DATEDIFF(NOW(),newsdate) <= ".$daydiff." AND DATEDIFF(NOW(),newsdate) > ".$min.")
ORDER BY newsid DESC
LIMIT 0,200";
        $res = db_query($sql);

        output("`&Dies sind die von den ehrenwerten Richtern gefällten Urteile von ".(($daydiff==0)?"heute":(($daydiff==1)?"gestern":"vor ".$daydiff." Tagen")).":`n");

        while ($n = db_fetch_assoc($res))
        {

            output('`n`n'.$n['newstext']);

        }

        if (db_num_rows($res)==0)
        {
            output("`n`1`b`c Keine Urteile an diesem Tag. `c`b`0");
        }

        addnav('Aktuelle Urteile');
        addnav("Liste aktualisieren","court.php?op=archiv");
        addnav('Zeitraum');
        addnav("Heute","court.php?op=archiv");
        addnav("Gestern","court.php?op=archiv&daydiff=1");
        addnav("Vor 2 Tagen","court.php?ret=$_GET[ret]&op=archiv&daydiff=2");
        addnav("Vor 3 Tagen","court.php?ret=$_GET[ret]&op=archiv&daydiff=3");
        addnav("Vor 4 Tagen","court.php?ret=$_GET[ret]&op=archiv&daydiff=4");
        addnav("Vor 5 Tagen","court.php?ret=$_GET[ret]&op=archiv&daydiff=5");
        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }
        break;

    case 'faq':
        output(get_extended_text('judge_policy'));

        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }
        break;

    case 'schreiber':
        output("`&In einem viel zu kleinen Raum sitzt ein karges Männlein hinter einem kleinen Tisch, der meterhoch mit Unterlagen zugestellt ist. Irgendwo dazwischen steht eine kleine eiserne Kassette auf dem Tisch, die ein paar Goldmünzen enthält. Der Schreiber schaut dich an als du eintrittst.`n
                `n
                `i`7(Wichtig: Mit einer Anklage teilst du den städtischen Richtern mit, dass du eine RP-Gerichtsverhandlung mit deinem Charakter als Kläger und einem anderen Charakter als
                Angeklagten durchführen möchtest. Auch braucht es möglichst einen Zeugen, der aussagen möchte. Für eine Gerichtsverhandlung muss also Zeit aufgebracht werden. Deshalb ist es
                ratsam, sich mindestens mit dem Angeklagten und dem/den Zeugen, ggf. auch mit dem Richter, vorher abzusprechen, ehe eine Anzeige vorgenommen wird.)`i`n`n");
        addnav('Gerichtsschreiber');
        addnav("Anzeige erstatten","court.php?op=anzeige&ret=".$_GET['ret']);
        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }
        break;

    case 'anzeige':
        output("`&Der Schreiberling schaut dich an. \"`#Na, wer hat Euch denn Schlimmes angetan?`&\" fragt er.`n`n");

        if ($_GET['who']=="")
        {
            addnav("Äh.. niemand!","court.php?op=schreiber&ret=$_GET[ret]");
            if ($_GET['subop']!="search")
            {
                output("<form action='court.php?op=anzeige&ret=$_GET[ret]&subop=search' method='POST'><input name='name'><input type='submit' class='button' value='Suchen'></form>",true);
                addnav("","court.php?op=anzeige&ret=$_GET[ret]&subop=search");
            }
            else
            {
                addnav("Neue Suche","court.php?op=anzeige&ret=$_GET[ret]");
                $search = str_create_search_string($_POST['name']);
                $sql = "SELECT name,alive,location,sex,level,reputation,laston,loggedin,login FROM accounts WHERE (locked=0 AND name LIKE '$search') ORDER BY level DESC";
                $result = db_query($sql) or die(db_error(LINK));
                $max = db_num_rows($result);
                if ($max > 50)
                {
                    output("`n`n\"`#Geht es vielleicht ein bisschen genauer ?`&`n");
                    $max = 50;
                }
                output("<table border=0 cellpadding=0><tr><td>Name</td><td>Level</td></tr>",true);
                for ($i=0; $i<$max; $i++)
                {
                    $row = db_fetch_assoc($result);
                    output("<tr><td><a href='court.php?op=anzeige&ret=$_GET[ret]&who=".rawurlencode($row['login'])."'>$row[name]</a></td><td>$row[level]</td></tr>",true);
                    addnav("","court.php?op=anzeige&ret=$_GET[ret]&who=".rawurlencode($row['login']));
                }
                output("</table>",true);
            }
        }
        else
        {

            $sql = "SELECT acctid,login,name FROM accounts WHERE login=\"$_GET[who]\"";
            $result = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>0)
            {
                $row = db_fetch_assoc($result);
                $costs=$session['user']['level']*100;

                output("`&Der Schreiber nickt. \"`&Ja, der Name ".($row['name'])." `& ist mir ein Begriff... Die Gebühren für eine Anzeige liegen für Euch bei `^".$costs." Gold.`#\"`&`n`n");
                if ($costs>$session['user']['gold'])
                {
                    output("`&`n`qDu schaust in deinen Beutel und stellst fest, dass du nicht genug Gold dabei hast.`n`QUntertänigst entschuldigst du dich beim Gerichtsdiener und verlässt das Gebäude.`n`n");
                    addnav("Tut mir leid!","village.php");
                }
                else
                {
                    output("`n`&Wie lautet deine Anzeige? Bitte beschreibe den Tathergang ausführlich!");
                    output("<form action='court.php?op=anzeige2&ret=$_GET[ret]&who=".rawurlencode($row['login'])."' method='POST'><textarea name='text' id='text' class='input' cols='50' rows='10'></textarea><br><input type='submit' class='button' value='diktieren'></form>",true);
                    output("<script language='JavaScript'>document.getElementById('text').focus();</script>",true);
                    addnav("","court.php?op=anzeige2&ret=$_GET[ret]&who=".rawurlencode($row['login'])."");
                    addnav("Abbrechen","court.php?ret=$_GET[ret]&op=schreiber");
                }
            }
            else
            {
                output("\"`#Ich kenne niemanden mit diesem Namen.`&\"");
            }
        }

        break;

    case 'anzeige2':

        $text = $_POST['text'];

        $sql = "SELECT acctid,login,name FROM accounts WHERE login=\"$_GET[who]\"";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);

        output("`&Die Anzeige lautet:`n`n");
        $pretext="`&Anzeige von ".$session['user']['name']." `&gegen ".$row['name']." `&: ";
        $text2=$pretext.$text;
        output($text2);
        output("`n`n`&Zufrieden?");
        addnav("Sehr gut!","court.php?op=anzeige3&ret=$_GET[ret]&who=$row[acctid]&text=".rawurlencode($text)."");
        addnav("Nein, nochmal!","court.php?op=anzeige&ret=$_GET[ret]&who=".rawurlencode($row['login'])."");

        break;

    case 'anzeige3':

        $sql = "SELECT acctid,login,name FROM accounts WHERE acctid=\"$_GET[who]\"";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);

        $text = $_GET['text'];
        $pretext="`&Anzeige von ".$session['user']['name']." `&gegen ".$row['name']."`&: ";
        $text=$pretext.$text;
        output("`&Du hast zu Protokoll gegeben:`n");
        output($text);

        $buy=$session['user']['level']*100;
        if ($buy>$session['user']['gold'])
        {
            output("`&`n`nWas glaubst du wo du hier bist? Die Mühlen der Justiz mahlen sicherlich nicht umsonst. Also besorg dir ein wenig Kleingeld bevor du wiederkommst.`nDer Gerichtsdiener befördert dich mit einem Tritt nach draussen.");
            addnav("Autsch!","village.php");
        }
        else
        {
            output('`&`n`n');
            $text=ereg_replace("\ {2,}"," ",$text);
            if(strlen($text)>150)
            {
                output('`&Der Schreiberling sieht dich an: `#"Wenn das so ist brauchst Du nur die halbe Gebühr bezahlen."`&`n');
                $buy*=.5;
            }
            output("Du bezahlst deine $buy Goldmünzen und sie versinken leise klirrend in der eisernen Kassette auf des Schreiberlings Tisch.`n");
            $session['user']['gold']-=$buy;

            $sql = "INSERT INTO crimes(newstext,newsdate,accountid) VALUES ('".addslashes($text)."',NOW(),".$row['acctid'].")";
            db_query($sql) or die(db_error($link));

            addnav('Das wäre erledigt!');
            if ($_GET['ret']==1) {
                addnav("Hehe...","court.php?op=judgesdisc");
            } else {
                addnav("Hehe...","court.php");
            }
        }


        break;

    case 'thecourt':
//Richter bekommen alle Prozesse
        if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || su_check(SU_RIGHT_DEBUG)) {
            $res= item_list_get(' i.tpl_id="vorl" ',' GROUP BY value1 ORDER BY value1 DESC LIMIT 0,200 ');
        } else {
          if(item_get(' i.tpl_id="vorl" AND i.owner='.$session['user']['acctid'],false)) { //auf eigene Vorladung prüfen
            $res= item_list_get(' i.tpl_id="vorl" AND (i.owner="'.$session['user']['acctid'].'" OR value2="1") GROUP BY value1 ORDER BY value1 DESC LIMIT 0,200 ');
          } else { //Abfrage ob public
            $res= item_list_get(' i.tpl_id="vorl" AND value2="1" GROUP BY value1 ORDER BY value1 DESC LIMIT 0,200 ');
          }
        }
        if (db_num_rows($res)) {
            output("`&Zu welchem Prozess möchtest du gehen ?`n`n");
                        $int_count = db_num_rows($res);
            for ($i=0; $i<$int_count; $i++) {
                $row = db_fetch_assoc($res);
                $sql2 = "SELECT name FROM accounts WHERE acctid=".$row['value1']." ORDER BY name DESC";
                $res2 = db_query($sql2);
                $row2 = db_fetch_assoc($res2);
                output(create_lnk('&raquo; `&'.strip_appoencode($row2['name'],3),"court.php?op=entrymsg&ret=".$_GET['ret']."&accountid=".$row['value1']).'`n',true);
            }

            addnav("Zurück","court.php");

        } else {

            if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || su_check(SU_RIGHT_DEBUG) ) {
                output("`&Derzeit werden hier keine Fälle verhandelt und du bist gewiss nicht gekommen um den Boden zu schrubben...`n`n");
                addnav('Zurück');
                if ($_GET['ret']==1) {
                    addnav("Zum Beratungsraum","court.php?op=judgesdisc");
                } else {
                    addnav("Zum Gerichtshof","court.php");
                }
            } else {
                output("`&Du hast keine Vorladung und die Verhandlungen sind nicht öffentlich.`nWas willst du also hier?`n`n");
                addnav('Zurück');
                addnav("Zum Gerichtshof","court.php");
            }
        }
        break;

    case 'thecourt2':
        output("`&Du öffnest die schwere Eichentür und betrittst den Gerichtssaal. Stühle und Bänke sind im hinteren Teil des großen Raumes ordentlich aufgestellt worden, eine Absperrung trennt diesen Teil von der Richterkanzel. Türen im hinteren Teil des Raumes führen zum Archiv und zum Besprechungsraum. Du stellst fest, dass der Saal sehr gepflegt und der Boden gut poliert ist.`n`n");

        $roomname="court".$_GET['accountid'];

        $accountid=substr($roomname,5);

        addcommentary();
//Verhandlungsraum 
        $bool_showform = ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $session['user']['profession']==PROF_JUDGE_NEW || item_get(' i.tpl_id="vorl" AND i.owner='.$session['user']['acctid'].' AND value1='.$_GET['accountid'],false) || $session['user']['superuser']);
        viewcommentary($roomname,"Sagen:",30,"sagt",false,$bool_showform);

        //(wer || wer darf?) && Vorladung Besitzer= Angeklagter?
        if (($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || su_check(SU_RIGHT_DEBUG)) && item_get(' i.tpl_id="vorl" AND i.owner= '.$accountid,false) )
        {
            addnav("Zeugen vorladen");
            addnav("Vorladen","court.php?op=witn&ret=$_GET[ret]&accountid=$_GET[accountid]");
            addnav("Anklageschrift");
            addnav("Lesen","court.php?op=caseinfo&ret=$_GET[ret]&who=$_GET[accountid]&proc=1");
            if ($session['user']['acctid']!=$_GET['accountid'])
            {
                addnav("Prozess beenden");
                addnav("Schuldig","court.php?op=guilty&ret=$_GET[ret]&proc=1&suspect=$accountid");
                addnav("Nicht schuldig","court.php?op=notguilty&ret=$_GET[ret]&proc=1&suspect=$accountid");
            }
            addnav("Prozesspause");
            addnav("Saal verlassen","court.php?op=leavemsg&ret=$_GET[ret]&accountid=$_GET[accountid]");
        }
        else
        {
            addnav("Raus hier","court.php?op=leavemsg&ret=$_GET[ret]&accountid=$_GET[accountid]");
        }
        break;

    case 'judgesdisc':

        output("`&Hier im kleinen Hinterzimmer des großes Verhandlungsraumes kannst du dich mit den anderen Richtern treffen. Ungestört von Plebs und Pöbel könnt ihr hier wichtige Fälle diskutieren oder einfach nur mal kurz ausspannen.`nEin großer runder Tisch in der Mitte des Raumes bietet allen Richtern Platz und sieht sehr gemütlich aus.`n`n");
        if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || su_check(SU_RIGHT_DEBUG) )
        {
            addcommentary();
        }
        viewcommentary("judges","Deine Meinung sagen:",30,"meint");

        addnav("Öffentliches");
        addnav("Verhandlungsraum","court.php?op=thecourt&ret=1");
        addnav("Liste der Richter","court.php?op=listj&ret=1");
        addnav("Gerichtsschreiber");
        addnav("Zum Gerichtsschreiber","court.php?op=schreiber&ret=1");
        addnav("Arbeit");
        addnav("Verdächtige Taten","court.php?op=news&ret=1");
        addnav("Aktuelle Fälle","court.php?op=cases&ret=1");
        //addnav("Kopfgeldliste","court.php?op=listh&ret=1");
        addnav("Schwarzes Brett","court.php?op=board&ret=1");
        addnav("Archiv");
        addnav("Urteile","court.php?op=archiv&ret=1");
        addnav("Handbuch für Jungrichter","court.php?op=faq&ret=1");
        addnav("Sonstiges");
        addnav("Zurück","court.php");
        break;

    case 'prozess': //Prozess eröffnen

        $sql = "SELECT name FROM accounts WHERE acctid=".$_GET['who'];
        $res = db_query($sql);
        $row = db_fetch_assoc($res);

        $item['tpl_value1'] = $_GET['who'];
        $item['tpl_value2'] = $_GET['public'];

        $item['tpl_description'] = '`&Du wirst zum Gericht befohlen! Es betrifft das Verfahren gegen `4dich.`& Solltest du dem nicht nachkommen, droht dir eine harte Strafe.';

        item_add($_GET['who'], 'vorl', $item );

        systemmail($_GET['who'],"`4Vorladung!`2",$item['tpl_description']);

        output($row['name']."`& hat eine Vorladung erhalten und wird sich (hoffentlich) bald im Gerichtssaal einfinden.`n");

        $sql = "UPDATE cases SET court=2 WHERE accountid=".$_GET['who'];
        db_query($sql) or die(sql_error($sql));
        $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'court".$_GET['who']."',".$session['user']['acctid'].",'/x ---Prozess eröffnet am ".getgamedate()." von Richter ".$session['user']['login']."---')";
        db_query($sql) or die(db_error(LINK));

        addnav('Zurück');
        if ($_GET['ret']==1) {
            addnav("Zum Beratungsraum","court.php?op=judgesdisc");
        } else {
            addnav("Zum Gerichtshof","court.php");
        }

        break;

    case 'witn':
        output("`&Wen möchtest du zu diesem Prozess vorladen?`n`n");

        if ($_GET['who']=="")
        {
            addnav("Niemanden!","court.php?op=thecourt2&accountid=$_GET[accountid]");
            if ($_GET['subop']!="search")
            {
                output("<form action='court.php?op=witn&ret=$_GET[ret]&accountid=$_GET[accountid]&subop=search' method='POST'><input name='name'><input type='submit' class='button' value='Suchen'></form>",true);
                addnav("","court.php?op=witn&ret=$_GET[ret]&accountid=$_GET[accountid]&subop=search");
            }
            else
            {
                addnav("Neue Suche","court.php?op=witn&ret=$_GET[ret]&accountid=$_GET[accountid]");
                $search = str_create_search_string($_POST['name']);
                $sql = "SELECT name,alive,location,sex,level,reputation,laston,loggedin,login FROM accounts WHERE (locked=0 AND name LIKE '$search') ORDER BY IF(login='".addslashes(stripslashes($_POST['name']))."',1,0) DESC, level DESC";
                $result = db_query($sql) or die(db_error(LINK));
                $max = db_num_rows($result);
                if ($max > 50)
                {
                    output("`n`n`&Zu viele Suchergebnisse`&`n");
                    $max = 50;
                }
                output("<table border=0 cellpadding=0><tr><td>Name</td><td>Level</td></tr>",true);
                for ($i=0; $i<$max; $i++)
                {
                    $row = db_fetch_assoc($result);
                    output("<tr><td><a href='court.php?op=witn&ret=$_GET[ret]&accountid=$_GET[accountid]&who=".rawurlencode($row['login'])."'>$row[name]</a></td><td>$row[level]</td></tr>",true);
                    addnav("","court.php?op=witn&ret=$_GET[ret]&accountid=$_GET[accountid]&who=".rawurlencode($row['login']));
                }
                output("</table>",true);
            }
        }
        else
        {

            $sql = "SELECT acctid,login,name FROM accounts WHERE login=\"$_GET[who]\"";
            $result = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>0)
            {
                $row = db_fetch_assoc($result);


                output($row['name']." `& als Zeugen vorladen ?`n`n");

                addnav("Ja","court.php?op=witn2&ret=$_GET[ret]&accountid=$_GET[accountid]&who=$row[acctid]");
                addnav("Nein","court.php?op=thecourt2&ret=$_GET[ret]&accountid=$_GET[accountid]");
            }
            else
            {
                output("\"`#Name wurde nicht gefunden.`&\"");
            }
        }

        break;

    case 'witn2':

        $sql = "SELECT name FROM accounts WHERE acctid=$_GET[accountid]";
        $res = db_query($sql);
        $row = db_fetch_assoc($res);

        $sql2 = "SELECT name FROM accounts WHERE acctid=$_GET[who]";
        $res2 = db_query($sql2);
        $row2 = db_fetch_assoc($res2);

        $item['tpl_value1'] = $_GET['accountid'];
        $item['tpl_description'] = '`&Du wirst zum Gericht befohlen! Es betrifft das Verfahren gegen '.$row['name'].'`&. Solltest du dem nicht nachkommen, droht dir eine harte Strafe.';

        item_add($_GET['who'], 'vorl', $item );

        systemmail($_GET['who'],"`4Vorladung!`2",$item['tpl_description']);

        output($row2['name']."`& hat eine Vorladung erhalten und wird sich (hoffentlich) bald im Gerichtssaal einfinden.`n");

        $roomname="court".$_GET['accountid'];
        
        insertcommentary(1,'/msg `&'.$row2['name'].'`& wird vom Hohen Gericht als Zeuge vorgeladen!',$roomname);

        addnav("Zurück","court.php?op=thecourt2&ret=$_GET[ret]&accountid=$_GET[accountid]");
        break;

    case 'entrymsg':
        $roomname="court".$_GET['accountid'];
//        $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'$roomname',".$session['user']['acctid'].",'/me `&betritt den Gerichtssaal.`V')";
//        db_query($sql) or die(db_error(LINK));
        redirect("court.php?op=thecourt2&ret=$_GET[ret]&accountid=$_GET[accountid]");
        break;

    case 'leavemsg':
        $roomname="court".$_GET['accountid'];
//        $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'$roomname',".$session['user']['acctid'].",'/me `&verlässt den Gerichtssaal.`V')";
//        db_query($sql) or die(db_error(LINK));

        if ($_GET['ret']==1)
        {
            redirect("court.php?op=judgesdisc");
        }
        else
        {
            redirect("court.php");
        }
        break;
        
    case 'rproom':
        // Daten abrufen: Ortsname...
        $str_rproom_name = getsetting('court_rproom_name','');
        $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
        // .. und Ortsbeschreibung
        $str_rproom_desc = getsetting('court_rproom_desc','');
        $str_rproom_desc = (strlen($str_rproom_desc) > 14 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
        // end
        page_header(strip_appoencode($str_rproom_name,3));
        output('`c`b'.$str_rproom_name.'`0`b`c`n'.$str_rproom_desc.'`n`n');
        addcommentary();
        viewcommentary('court_rproom','Sagen',15,'sagt');
        if($session['user']['profession'] == PROF_JUDGE_HEAD || su_check(SU_RIGHT_DEBUG)) {
            addnav('Verwaltung');
            addnav('Ort verwalten','court.php?op=rproom_edit');
        }
        addnav('Zurück');
        addnav('Zum Eingangsbereich','court.php');
        break;
        
    case 'rproom_edit':
        // Daten abrufen: Ortsname...
        $str_rproom_name = getsetting('court_rproom_name','');
        $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
        // .. und Ortsbeschreibung
        $str_rproom_desc = getsetting('court_rproom_desc','');
        $str_rproom_desc = (strlen($str_rproom_desc) > 1 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
        // end
        page_header($str_rproom_name);
        if($_GET['act'] == 'save') {
            $bool_nochmal = false;
            // Ortsname
            if($_POST['rproom_name'] != $str_rproom_name) {
                if(strlen(trim($_POST['rproom_name'])) >= 2) {
                    $_POST['rproom_name'] = html_entity_decode(strip_appoencode(strip_tags($_POST['rproom_name']),2));
                    savesetting('court_rproom_name',$_POST['rproom_name']);
                } else {
                    output('`4Fehler! Der Ortsname muss mindestens 2 Zeichen lang sein.`n`n');
                    $bool_nochmal = true;
                }
            }
            // Ortsbeschreibung
            if($_POST['rproom_desc'] != $str_rproom_desc) {
                if (strlen(trim($_POST['rproom_desc'])) >= 15) {
                    $_POST['rproom_desc'] = html_entity_decode(closetags(strip_tags($_POST['rproom_desc']),'`i`b`c'));
                    savesetting('court_rproom_desc',$_POST['rproom_desc']);
                } else {
                    output('`4Fehler! Die Ortsbeschreibung muss mindestens 15 Zeichen lang sein.`n`n');
                    $bool_nochmal = true;
                }
            }
            // Speichervorgang nicht erfolgreich?
            if($bool_nochmal) {
                addnav('Nochmal','court.php?op=rproom_edit');
                addnav('Zurück');
            } else {
                savesetting('court_rproom_active',$_POST['rproom_active']);
                redirect('court.php?op=rproom');
            }
        } else {
            $form = array('rproom_active'=>'RP-Ort für die Öffentlichkeit zugänglich?,bool'
                    ,'rproom_name'=>'Name des RP-Orts:'
                    ,'rproom_name_prev'=>'Vorschau:,preview,rproom_name'
                    ,'rproom_desc'=>'Ortsbeschreibung:,textarea,45,8'
                    ,'rproom_desc_prev'=>'Vorschau:,preview,rproom_desc');
            $data = array('rproom_active'=>getsetting('court_rproom_active',0)
                    ,'rproom_name'=>$str_rproom_name
                    ,'rproom_desc'=>$str_rproom_desc);
            output("<form action='court.php?op=rproom_edit&act=save' method='POST'>",true);
            showform($form,$data,false,'Speichern');
            output("</form>",true);
            addnav('','court.php?op=rproom_edit&act=save');
            addnav('Zurück');
            addnav('Zum RP-Ort','court.php?op=rproom');
        }
        addnav('Zum Eingangsbereich','court.php');
        break;                

        default:
        break;

}

page_footer();
?>

