
<?php
// Stadtwachen-Addon : Änderungen in houses.php, bio.php, pvp.php, inn.php, configuration.php, dorfamt.php
// Benötigt [profession] (shortint, unsigned) in [User]
// by Maris (Maraxxus@gmx.de)
// 28.5.05: mod by tcb: Verlagert aus Dorfamt in eigene Datei, Bewerbungssystem, Schwarzes Brett verändert

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
require_once(LIB_PATH.'profession.lib.php');

page_header("Die Stadtwache");

if (!isset($session)) exit();

$op = ($_GET['op']) ? $_GET['op'] : "hq";

switch($op) {
                
        case 'bewerben':
        
                output("`&Mit zittrigen Händen nimmst du die Klinke einer schweren Eichentür in die Hand und stößt sie auf. Ein alter Mann mit Backenbart sitzt hinter einem Schreibtisch und mustert dich eindringlich. \"`#Name! Rang!`&\" ruft er dir scharf entgegen. Nachdem du ihm gesagt hast was er wissen wollte kneift er die Augen zusammen.`n`n");
                $maxamount = getsetting("numberofguards",10);
                $reqdk = getsetting("guardreq",30);
                
                $sql = "SELECT profession FROM accounts WHERE profession=".PROF_GUARD_HEAD." OR profession=".PROF_GUARD_SUB." OR profession=".PROF_GUARD;
                $result = db_query($sql) or die(db_error(LINK));
                if ((db_num_rows($result)) < $maxamount) {
                    
                    output("\"`# ".($session['user']['name'])."!`# Ich hoffe Ihr wisst worauf Ihr Euch hier einlasst? Der Dienst in der Stadtwache ist hart und entbehrungsreich. Und an Euch werden besondere Forderungen gestellt : Ihr müsst sowohl ruhmreich wie auch von höchstem Ansehen sein und in Eurem Verhalten ein Vorbild!`&\"`n");
                    
                    if (($session['user']['dragonkills']) >= $reqdk) {
                        if ($session['user']['reputation']>=50) {
                            output ("\"`#Ich sehe, ich sehe... Ihr seid sowohl ruhmreich, wie auch von allerhöchstem Ansehen! Das ist gut, sehr gut. Meinetwegen könnt Ihr sofort anfangen. Doch wisset, dass Ihr als Stadtwache nicht nur Recht, sondern auch Pflichten habt. Es ist Euch strengstens untersagt mit zwielichtigen Gesellen Kontakte zu knüpfen, auch nicht zur Täuschung! Ihr müsst Euch weiterhin mit Kopfgeldern zufrieden geben und dürft keine Beute an Euren Gegnern machen! Eurem Hauptmann habt Ihr Folge zu leisten! Sollte man Euch bei irgendeinem Verstoß oder irgendeiner Unehrenhaftigkeit erwischen, seid Ihr für lange Zeit Stadtwache gewesen! Sind wir uns da einige?`nAlso, wollt Ihr noch immer ?`&\"");
                            addnav("Ja, Wache werden","wache.php?op=bewerben_ok");
                        }
                        else {
                            output ("\"`#Ruhmreich seid mehr als es von Nöten wäre, doch fürchte ich, dass Euch die Leute nicht trauen würden, wenn Ihr plötzlich in Uniform daher kämet. Tut mal etwas für Euer Ansehen und versucht es dann noch einmal!`&\"");
                        }
                    }
                    else {
                        output ("\"`#Ihr seid zwar ruhmreich, doch wie es mir scheint nicht ruhmreich genug. Ihr solltet noch mehr Ruhm im Kampf gegen den Drachen erlangen und es dann noch einmal versuchen!`&\"");
                    }
                }        // Noch nicht zu viele 
                else {
                        output ("\"`#Es tut mir sehr leid, aber die Stadt hat zur Zeit genügend Stadtwachen. Versucht es doch später noch einmal!`&\"");
                }
                
                addnav("Zurück","dorfamt.php");
                        
                break;
                
        case 'bewerben_ok':
                
                output("`&Du überreichst dem alten Mann dein Bewerbungsschreiben. Dieser verstaut es unter einem hohen Stapel Pergamenten und meint: \"Wir werden auf dich zurückkommen!\"");
                $session['user']['profession']=PROF_GUARD_NEW;
                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_GUARD_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
                $res = db_query($sql);
                if(db_num_rows($res)) {
                        $w = db_fetch_assoc($res);
                        systemmail($w['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& hat sich für die Stadtwache beworben. Du solltest seine Bewerbung überprüfen und ihn gegegebenfalls einstellen.");
                }
                
                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_GUARD_SUB." ORDER BY loggedin DESC, RAND() LIMIT 1";
                $res = db_query($sql);
                if(db_num_rows($res)) {
                        $w = db_fetch_assoc($res);
                        systemmail($w['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& hat sich für die Stadtwache beworben. Du solltest seine Bewerbung in Rücksprache mit deinem Hauptmann überprüfen und ihn gegegebenfalls einstellen.");
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
                
                $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_GUARD_HEAD." OR profession=".PROF_GUARD_SUB." OR profession=".PROF_GUARD.")";
                $res = db_query($sql);
                $p = db_fetch_assoc($res);
                
                if($p['anzahl'] >= getsetting("numberofguards",10)) {
                        output("Es gibt bereits ".$p['anzahl']." Wachen! Mehr sind zur Zeit nicht möglich.");
                        addnav("Zurück","wache.php?op=listg");
                }
                else {
                
                        $sql = "UPDATE accounts SET profession = ".PROF_GUARD."  
                                        WHERE acctid=".$pid;
                        db_query($sql) or die (db_error(LINK));
                        
                        $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
                        $res = db_query($sql);
                        $p = db_fetch_assoc($res);
                        
                        systemmail($pid,"Du wurdest aufgenommen!",$session['user']['name']."`& hat deine Bewerbung zur Aufnahme in die Stadtwache angenommen. Damit bist du vom heutigen Tage an offiziell Hüter für Recht und Ordnung!");
                        
                        $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute offiziell in die ehrenvolle Gemeinschaft der Stadtwachen aufgenommen!',newsdate=NOW(),accountid=".$pid;
                        db_query($sql) or die (db_error(LINK));
                        
                        addhistory('`2Aufnahme in die Stadtwache',1,$pid);
                        
                        addnav("Willkommen!","wache.php?op=listg");
                        
                        output("Die neue Stadtwache ist jetzt aufgenommen!");
                }
                
                break;
                
        case 'abl':
                
                $pid = (int)$_GET['id'];
                
                $sql = "UPDATE accounts SET profession = 0  
                                WHERE acctid=".$pid;
                db_query($sql) or die (db_error(LINK));
                                                        
                systemmail($pid,"Deine Bewerbung wurde abgelehnt!",$session['user']['name']."`& hat deine Bewerbung zur Aufnahme in die Stadtwache abgelehnt.");
                        
                addnav("Zurück","wache.php?op=listg");
                
                break;
        
        case 'entlassen':
                
                $pid = (int)$_GET['id'];
        
                $sql = "UPDATE accounts SET profession = 0  
                                WHERE acctid=".$pid;
                db_query($sql) or die (db_error(LINK));
                        
                $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
                $res = db_query($sql);
                $p = db_fetch_assoc($res);
                        
                systemmail($pid,"Du wurdest entlassen!",$session['user']['name']."`& hat dich aus der Stadtwache entlassen!");
                        
                $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute aus der ehrenvollen Gemeinschaft der Stadtwachen entlassen!',newsdate=NOW(),accountid=".$pid;
                db_query($sql) or die (db_error(LINK));
                
                addhistory('`$Entlassung aus der Stadtwache',1,$pid);
                        
                addnav("Weiter","wache.php?op=listg");
                        
                output("Die Wache wurde entlassen!");
                                
                break;
                
        case 'degradieren':
        
                $pid = (int)$_GET['id'];
                $sql = "UPDATE accounts SET profession = ".PROF_GUARD."  
                                        WHERE acctid=".$pid;
                db_query($sql) or die (db_error(LINK));
                        
                systemmail($pid,"Du wurdest degradiert!",$session['user']['name']."`& hat dir andere Verantwortlichkeiten zugewiesen!");
                        
                $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
                $res = db_query($sql);
                $p = db_fetch_assoc($res);
                
                addnav("Weiter","wache.php?op=listg");
                        
                output(addslashes($p['name'])." `& wurde degradiert!");
        
                break;
        
        case 'upgrade':
                
                $pid = (int)$_GET['id'];
                $sql = "UPDATE accounts SET profession = ".PROF_GUARD_SUB."  
                                        WHERE acctid=".$pid;
                db_query($sql) or die (db_error(LINK));
                        
                systemmail($pid,"Du wurdest befördert!",$session['user']['name']."`& hat dich zum Vize-Hauptmann befördert. Herzlichen Glückwunsch!");
                        
                $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
                $res = db_query($sql);
                $p = db_fetch_assoc($res);
                        
                $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute zum Vize-Hauptmann befördert!',newsdate=NOW(),accountid=".$pid;
                db_query($sql) or die (db_error(LINK));
                
                addhistory('`$Beförderung zum Vize-Hauptmann der Stadtwache',1,$pid);
                
                addnav("Weiter","wache.php?op=listg");
                        
                output(addslashes($p['name'])." `& wurde befördert!");
        
                break;
        
        case 'leave':
                
                output ("`&Mit zitternden Knien betrittst du das Zimmer, in der der ältere Herr mit dem Backenbart wie gewohnt hinter seinem Schreibtisch sitzt. Als du eintrittst und ihm Meldung machst bittet er dich Platz zu nehmen und schau dich erwartungsvoll an.`nWillst du wirklich die Stadtwache verlassen?");
                addnav("Ja, austreten!","wache.php?op=leave_ok");
                addnav("NEIN. Dabei bleiben","dorfamt.php");
                
                break;
                
        case 'leave_ok':
                
                output ("`&Du bittest um deine Entlassung und der ältere Herr erledigt sichtlich schweren Herzens alle Formalitäten \"`#Wirklich schade, dass Ihr geht! Ich danke Euch vielmals für die treuen Dienste, die Ihr der Stadt geleistet habt und werde Euch nie vergessen! Beachtet, dass Eure Entlassung erst mit Beginn des morgigen Tages wirksam wird. Für heute seid Ihr jedoch beurlaubt.`&\"");
                addnews("".$session['user']['name']."`@ hat die Stadtwache verlassen. Die Gaunerwelt atmet auf.");
                
                addhistory('`2Austritt aus der Stadtwache');
                
                $session['user']['profession'] = 0;
                
                addnav("Zurück ins Zivilleben","dorfamt.php");                
                
                break;
        
        case 'hq':
                
                addcommentary();
                if (($session['user']['profession']==PROF_GUARD) || ($session['user']['profession']==PROF_GUARD_HEAD) ||($session['user']['profession']==PROF_GUARD_SUB) || (su_check(SU_RIGHT_DEBUG)) )
                {
                        output("`c`&".$profs[PROF_GUARD_HEAD][4]." `bDas Hauptquartier der Stadtwachen`b`c`n
                                `ôDu betrittst vornehme Räumlichkeiten, die dir ein gewisses Gefühl von Ehrfurcht und auch Respekt vermitteln. An den
                                Wänden hängen Schwerter und Trophäen. Ritterrüstungen säumen den holzvertäfelten Raum. Ein großer runder, edler
                                Eichentisch steht genau in der Mitte des Hauptraumes. Umhänge und Rüstungsteile, achtlos über Stühle gehängt, kannst du
                                aus den Augenwinkeln erkennen. Ein großer Kupferstich an der Stirnwand des Hauptraumes erinnert dich an deine Pflichten
                                als Wächter dieser Stadt:`n`n
                                `c`#\"Ehre, Gerechtigkeit, Ritterlichkeit, Beständigkeit und Disziplin sollen den Wächter der Stadt
                                ".getsetting('townname','Atrahor')."`n
                                zu einem Symbol der Sicherheit für ihre Bürger machen!\"`c`ô",true);
                        addnav('Mitglieder');
                        addnav("Rekrutierungsliste","wache.php?op=listg");
                        addnav("Schwarzes Brett","wache.php?op=board");
                        addnav("Pausenraum","wache.php?op=ooc");
                        addnav('Ganovenjagd');
                        //addnav("Kopfgeldliste","wache.php?op=listh");
                        addnav("Urteile","wache.php?op=sentences");
                        // addnav("Letzte Neuigkeiten","wache.php?op=news");
                        if ($session['user']['profession'] == PROF_GUARD_HEAD || $session['user']['profession'] == PROF_GUARD_SUB || su_check(SU_RIGHT_DEBUG))
                        {
                                addnav('Massenmail','wache.php?op=massmail');
                        }
                }
                else
                {
                        output("`c`&".$profs[PROF_GUARD_HEAD][4]." `bDas Hauptquartier der Stadtwachen`b`c`n
                                `ôDu betrittst vornehme Räumlichkeiten, die dir ein gewisses Gefühl von Ehrfurcht und auch Respekt vermitteln. An den
                                Wänden hängen Schwerter und Trophäen. Ritterrüstungen säumen den holzvertäfelten Raum. Ein großer runder, edler
                                Eichentisch steht genau in der Mitte des Hauptraumes. Umhänge und Rüstungsteile, achtlos über Stühle gehängt, kannst du
                                aus den Augenwinkeln erkennen. Ein großer Kupferstich an der Stirnwand des Hauptraumes erinnert an den Verhaltenskodex,
                                dem sich jeder Wächter verpflichtet hat:`n`n
                                `c`#\"Ehre, Gerechtigkeit, Ritterlichkeit, Beständigkeit und Disziplin sollen den Wächter der Stadt
                                ".getsetting('townname','Atrahor')."`n
                                zu einem Symbol der Sicherheit für ihre Bürger machen!\"`c`ô",true);
                        addnav('Mitglieder');
                        addnav("Stadtwachen auflisten","wache.php?op=showg");
                }
                addnav('Anträge');
                if ($session['user']['profession']==0)
                {
                    addnav("Stadtwache werden","wache.php?op=bewerben");
                }
                if ($session['user']['profession']==PROF_GUARD_NEW)
                {
                    addnav("Bewerbung zurückziehen","wache.php?op=bewerben_abbr");
                }
                if (($session['user']['profession']==PROF_GUARD) || ($session['user']['profession']==PROF_GUARD_HEAD) || ($session['user']['profession']==PROF_GUARD_SUB))
                {
                    addnav("Entlassung erbitten","wache.php?op=leave");
                }
                addnav('Sonstiges');
                if(getsetting('wache_rproom_active',0) == 1 || $session['user']['profession'] == PROF_GUARD_HEAD || $session['user']['profession'] == PROF_GUARD_SUB || su_check(SU_RIGHT_DEBUG)) {
                    $str_rproom_name = getsetting('wache_rproom_name','');
                    $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
                    $str_rproom_active = (getsetting('wache_rproom_active',0) == 1 ? '' : ' (gesperrt)');
                    addnav($str_rproom_name.$str_rproom_active,'wache.php?op=rproom',false,false,false,false);
                }
                addnav('Beschreibung aller Berufsgruppen','library.php?op=book&bookid=51');
                addnav('Zurück');
                addnav("Hauptquartier verlassen","dorfamt.php");
                output("`n");
                viewcommentary("guards","Melden:",30,"meldet");
                        
                break;
                
        case 'ooc':
                
                if ($session['user']['profession']==PROF_GUARD || $session['user']['profession']==PROF_GUARD_HEAD || $session['user']['profession'] == PROF_GUARD_SUB || su_check(SU_RIGHT_COMMENT)) addcommentary();
                output("`c`&".$profs[PROF_GUARD_HEAD][4]." `bDas Hauptquartier der Stadtwachen`0`c`b`n");
                output('`ôDu ziehst dich zurück in das Hinterzimmer, wo du ungestört mit deinen Kollegen diskutieren kannst. Hier steht immer ein Kessel mit heißem Tee, den der Hauptmann für die erschöpften Recken bereitstellt. Mehrere Tische und Stühle laden zum Rasten ein.',true);

                require_once(LIB_PATH.'board.lib.php');
                output('`0`c`n`^~~~~~~~~`0`n');
                $int_pollrights = (($session['user']['profession'] == PROF_GUARD_HEAD) ? 2 : 1);
                if(poll_view('guard_chief',$int_pollrights,$int_pollrights))
                {
                        output('`n`^~~~~~~~~`0`n`n',true);
                }
                output('`c');

                addnav('Mitglieder');
                addnav("Schwarzes Brett","wache.php?op=board");
                addnav('Handbuch für Wachen','wache.php?op=faq');
                if($session['user']['profession'] == PROF_GUARD_HEAD || $session['user']['profession'] == PROF_GUARD_SUB || su_check(SU_RIGHT_DEBUG))
                {
                        addnav ('f?Umfrage erstellen','wache.php?op=poll&pollsection=chief');
                }
                addnav('Ganovenjagd');
                addnav("Urteile","wache.php?op=sentences");
                addnav('Zurück');
                addnav("Zurück zum HQ","wache.php?op=hq");
                output("`n`n");
                viewcommentary("guardsooc","Sagen:",30,"sagt");
                        
                break;
                
        case 'board':
                
                output ("`&Du stellst dich vor das große Brett und schaust ob eine neue Mitteilung vorliegt.`n");

                output ("`tDu kannst eine Notiz hinterlassen oder entfernen.`n`n");
                
                if($_GET['board_action'] == "add") {
                        
                        board_add('wache');
                        
                        redirect("wache.php?op=board");
                        
                }
                else {
                                                                
                        board_view_form('Hinzufügen','');
                                                
                        board_view('wache',2,'','',true,true,true);
                }
                                
                addnav("Zurück","wache.php?op=hq");                
                
                break;
        
        case 'poll': //Umfrage erstellen
        {
                require_once(LIB_PATH.'board.lib.php');
                output('`c`b`2Umfragen der Stadtwache`b`c`n`n');
                poll_add('guard_'.$_GET['pollsection'],100,1);
                if(!empty($session['polladderror'])) {
                        if($session['polladderror'] == 'maxpolls')
                        {
                                output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
                        }
                }
                else
                {
                        redirect('wache.php?op=ooc');
                }

                if($_GET['pollsection'] == 'private')
                {
                        output('`8Du möchtest also im Hinterzimmer des Hauptquartiers eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

                }
                else
                {
                        output('`8Du möchtest also eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und für alle gut sichtbar platziert zu werden:`n`n');
                }
                addnav('Zurück zum Pausenraum','wache.php?op=ooc');

                poll_show_addform();
                break;
        }

        case 'massmail': // Massenmail (im wohnviertel by mikay)
        {
                $str_out .= "`c`b`2Taubenschlag unter dem Dach des Hauptquartiers.`b`c`n`n";

                addnav('Abbrechen','wache.php?op=hq');

                $sql='SELECT acctid, name, login, profession
                        FROM accounts
                        WHERE profession='.PROF_GUARD.'
                        OR profession='.PROF_GUARD_HEAD.'
                        OR profession='.PROF_GUARD_SUB.'
                        OR profession='.PROF_GUARD_NEW.'
                        AND acctid!='.(int)$session['user']['acctid'].'
                        ORDER BY profession DESC';
                $result=db_query($sql);
                $users=array();
                $keys=0;

                while($row=db_fetch_assoc($result))
                {
                        $profs[0][0]='Zivilist';
                        if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';

                        $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" onclick="chk()" '.($row['profession']!=PROF_GUARD_NEW ? 'checked':'').'> '.$row['name'].'<br>';
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
                        $str_out .= form_header('wache.php?op=massmail')
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
                        $str_out .= '`c`bEs wurden noch keine Stadtwachen ernannt - und ja, Bombentauben an missliebige Nachbarn sind gegen das Gesetz.`b`c';
                }
                output($str_out);
                break;
        } // END massmail

        case 'listg':
                $admin = ($session['user']['profession'] == PROF_GUARD_HEAD || $session['user']['profession'] == PROF_GUARD_SUB || su_check(SU_RIGHT_DEBUG)) ? true : false;
                
                $sub_available = false;
                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_GUARD_SUB." ORDER BY loggedin DESC, RAND() LIMIT 1";
                $res = db_query($sql);
                if(db_num_rows($res)) {
                    $sub_available = true;
                }
                
                output("<span style='color: #9900FF'>",true);
                $sql = "SELECT name,acctid,loggedin,dragonkills,login,level,profession FROM accounts WHERE profession=".PROF_GUARD." OR profession=".PROF_GUARD_SUB." OR profession=".PROF_GUARD_HEAD." OR profession=".PROF_GUARD_NEW."
                                ORDER BY profession DESC, level DESC";
                                $result = db_query($sql) or die(db_error(LINK));
                output ("`&Folgende Helden haben sich der Stadtwache angeschlossen:`n`n");
                output("<table border='0' cellpadding='5' cellspacing='2' bgcolor='#999999'><tr class='trhead'><td>Name</td><td>Level</td><td>Funktion</td>");
                if($admin) {output('<td>Aktionen</td>',true);}
                output("<td>Status</td></tr>",true);
                $lst=0;
                $dks=0;
                for ($i=0;$i<db_num_rows($result);$i++){
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
                        output("<tr class='".($lst%2?"trlight":"trdark")."'><td>
                                  <a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>
                                  ".$str_biolink."</td>
                                  <td>$row[level]</td>",true);
                        if ($row['profession']==PROF_GUARD) {
                                output("<td>`#Stadtwache`&</td>",true);
                                if($admin) {
                                    output('<td>');
                                    if(!$sub_available){                                        
                                        output('<a href="wache.php?op=upgrade&id='.$row['acctid'].'">Befördern</a>',true);
                                        addnav("","wache.php?op=upgrade&id=".$row['acctid']);
                                        output('<br />');
                                    }
                                    output('<a href="wache.php?op=entlassen&id='.$row['acctid'].'">Entlassen</a>',true);
                                    output('</td>');
                                    addnav("","wache.php?op=entlassen&id=".$row['acctid']);
                                }
                        } elseif ($row['profession']==PROF_GUARD_HEAD) {
                                output("<td>`4Hauptmann`&</td>",true);
                                if($admin) output("<td></td>");
                        } elseif ($row['profession']==PROF_GUARD_SUB) {
                                output("<td>`4Vize-Hauptmann`&</td>",true);
                                if($admin && $session['user']['profession'] != PROF_GUARD_SUB) {
                                    output('<td><a href="wache.php?op=degradieren&id='.$row['acctid'].'">Degradieren</a></td>',true);
                                    addnav("","wache.php?op=degradieren&id=".$row['acctid']);
                                }
                        } elseif ($row['profession']==PROF_GUARD_NEW) {
                        
                                output("<td>`@Bittet um Aufnahme`&</td>",true);
                                if($admin) {
                                        output('<td><a href="wache.php?op=aufn&id='.$row['acctid'].'">Aufnehmen</a>`n',true);
                                        addnav("","wache.php?op=aufn&id=".$row['acctid']);
                                        output('<a href="wache.php?op=abl&id='.$row['acctid'].'">Ablehnen</a></td>',true);
                                        addnav("","wache.php?op=abl&id=".$row['acctid']);
                                        }
                                
                                }
                        output("<td>",true);
                        if ($row['loggedin']) { output("`@online`&",true);} else { output("`4offline`&",true);}
                        output("</td></tr>",true); 
                }
                db_free_result($result);
                output("</table>",true);
                output("</span>",true);
                output("<big>`n`@Gemeinsame Drachenkills der Stadtwache : `^$dks`n`n`&<small>",true);
                addnav("Zurück","wache.php?op=hq");                
                
                break;
                
        case 'showg': //listg für Normalsterbliche
                output("<span style='color: #9900FF'>",true);
                $sql = "SELECT name,acctid,loggedin,dragonkills,login,level,profession FROM accounts WHERE profession=".PROF_GUARD." OR profession=".PROF_GUARD_SUB." OR profession=".PROF_GUARD_HEAD."
                                ORDER BY profession DESC, level DESC";
                                $result = db_query($sql) or die(db_error(LINK));
                output ("`&Folgende Helden haben sich der Stadtwache angeschlossen:`n`n");
                output("<table border='0' cellpadding='5' cellspacing='2' bgcolor='#999999'><tr class='trhead'><td>Name</td><td>Level</td><td>Funktion</td><td>Status</td></tr>",true);
                $lst=0;
                $dks=0;
                for ($i=0;$i<db_num_rows($result);$i++){
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
                        if ($row['profession']==PROF_GUARD) {output("`#Stadtwache`&</td><td>",true);}
                        if ($row['profession']==PROF_GUARD_HEAD) {output("`4Hauptmann`&</td><td>",true);}
                        if ($row['profession']==PROF_GUARD_SUB) {output("`4Vize-Hauptmann`&</td><td>",true);}
                        if ($row['profession']==PROF_GUARD_NEW) {output("`@Bittet um Aufnahme`&</td><td>",true);}
                        if ($row['loggedin']) { output("`@online`&",true);} else { output("`4offline`&",true);}
                        output("</td></tr>",true); 
                }
                db_free_result($result);
                output("</table>",true);
                output("</span>",true);
                output("<big>`n`@Gemeinsame Drachenkills der Stadtwache : `^$dks`n`n`&<small>",true);
                addnav("Zurück","wache.php");
                
                break;
                
        case 'listh':
                
                output("<span style='color: #9900FF'>",true);
                output ("`&Die Kopfgeldliste:`n
                         `n
                         `i(`CAchtung! `&Ein Abgleich mit der Liste aller Urteile wird erwartet, da Stadtwachen keine anderen Charaktere `bohne`b Haftbefehl
                         festnehmen dürfen.)`i`n`n");
                
                $sql = "SELECT name,acctid,location,bounty,laston,alive,housekey,loggedin,login,level,activated,restatlocation FROM accounts WHERE bounty>0
                                ORDER BY bounty DESC";
                $result = db_query($sql) or die(db_error(LINK));
                
                output("<table border='0' cellpadding='4' cellspacing='1' bgcolor='#999999'><tr class='trhead'><td>Kopfgeld</td><td>Level</td><td>Name</td><td>Ort</td><td>Lebt?</td></tr>",true);
                $lst=0;
                
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        $loggedin=user_get_online(0,$row);
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
                        
                        if ($row['location']==USER_LOC_FIELDS) output($loggedin?"`@online":"`3Die Felder",true);
                        
                        if ($row['location']==USER_LOC_INN) output("`3Zimmer in Kneipe`0",true);
                        if ($row['location']==USER_LOC_PRISON) output("`3Im Kerker`0",true);
                        if ($row['location']==USER_LOC_HOUSE){
                                                $loc=$row['restatlocation'];
                        output ("Haus Nr. $loc",true);
                }
                output("</td><td>",true);
                if ($row['alive']) { output("`@lebt`&",true);} else { output("`4tot`&",true);}
                output("</td></tr>",true);
                }
                addnav("Zurück","wache.php?op=hq");
                db_free_result($result);
                output("</table>",true);
                output("</span>",true);                
                
                break;

        case 'faq': //Handbuch für Jungstadtwachen
        {
                output(get_extended_text('guard_policy'));
                addnav("Zurück","wache.php?op=ooc");
                break;
        }
        
        case 'sentences':

                output("<span style='color: #9900FF'>",true);
                output ("`&Die Richter haben folgende Urteile verhängt:`n`n");

        $sql = "SELECT account_extra_info.acctid,accounts.bounty,sentence,restatlocation,location,loggedin,activated,laston,alive,name,level,login,dragonkills
                        FROM account_extra_info 
                        LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid 
                        WHERE sentence>0
                                ORDER BY sentence DESC";
                $result = db_query($sql) or die(db_error(LINK));

                output("<table border='0' cellpadding='4' cellspacing='1' bgcolor='#999999'><tr class='trhead'><td>Strafe</td><td>DKs</td><td>Level</td><td>Name</td><td>Ort</td><td>Lebt?</td><td>Kopfgeld</td></tr>",true);
                $lst=0;
                
                $count = db_num_rows($result);
                
                for ($i=0;$i<$count;$i++){
                        $row = db_fetch_assoc($result);
                                                        
                        $loggedin=user_get_online(0,$row);
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
                        output("<tr class='".($lst%2?"trlight":"trdark")."'><td>".($row['sentence'])." Tage</td><td>".$row['dragonkills']."</td><td>".($row['level'])."</td><td>".$str_biolink,true);
                        output("</td><td>",true);

                        if ($row['location']==USER_LOC_FIELDS) output($loggedin?"`@online":"`3Die Felder",true);
                        if ($row['location']==USER_LOC_INN) output("`3Zimmer in Kneipe`0",true);
                        if ($row['location']==USER_LOC_PRISON) output("`3Im Kerker`0",true);
                        if ($row['location']==USER_LOC_HOUSE){
                                                $loc = $row['restatlocation'];
                                                
                             $sql="SELECT status FROM houses WHERE houseid=$loc ";
                                                $result3 = db_query($sql) or die(db_error(LINK));
                                                $row3 = db_fetch_assoc($result3);
                        $loc2= $row3['status'];
            if (($loc2<30) || ($loc2>39))
            { output ("Haus Nr. $loc",true); }
            else
            // Versteck, Refugium etc..
            { output ("untergetaucht",true); }
                }
                output("</td><td>",true);
                if ($row['alive']) { output("`@lebt`&",true);} else { output("`4tot`&",true);}
                output("</td><td>".$row['bounty']."</td></tr>",true);
                }
                addnav("Zurück","wache.php?op=hq");
                db_free_result($result);
                output("</table>",true);
                output("</span>",true);

                break;
        
        case 'news':
                
                $daydiff = ($_GET['daydiff']) ? $_GET['daydiff'] : 0;
                $min = $daydiff-1;
                        
                $sql = "SELECT newstext,newsdate FROM news WHERE 
                                        (newstext LIKE '%geflohen%' OR newstext LIKE '%einbruch%' OR newstext LIKE '%Zimmer in der Kneipe%' OR newstext LIKE '%in einem fairen Kampf in den Feldern%' OR newstext LIKE '%eine gerechte Strafe erhalten%')
                                        AND (DATEDIFF(NOW(),newsdate) <= ".$daydiff." AND DATEDIFF(NOW(),newsdate) > ".$min.")
                                        ORDER BY newsid DESC
                                        LIMIT 0,200";
                $res = db_query($sql);
                
                output("`&Die verdächtigen Taten von ".(($daydiff==0)?"heute":(($daydiff==1)?"gestern":"vor ".$daydiff." Tagen")).":`n");
                
                while($n = db_fetch_assoc($res)) {
                        
                        output('`n`n'.$n['newstext']);
                        
                }
                                                
                addnav("Aktualisieren","wache.php?op=news");
                addnav("Heute","wache.php?op=news");
                addnav("Gestern","wache.php?op=news&daydiff=1");
                addnav("Vor 2 Tagen","wache.php?op=news&daydiff=2");
                addnav("Vor 3 Tagen","wache.php?op=news&daydiff=3");
                addnav("Zurück","wache.php");
                
                break;
        case 'rproom':
            // Daten abrufen: Ortsname...
            $str_rproom_name = getsetting('wache_rproom_name','');
            $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
            // .. und Ortsbeschreibung
            $str_rproom_desc = getsetting('wache_rproom_desc','');
            $str_rproom_desc = (strlen($str_rproom_desc) > 14 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
            // end
            page_header(Strip_appoencode($str_rproom_name,3));
            output('`c`b'.$str_rproom_name.'`0`b`c`n'.$str_rproom_desc.'`n`n');
            addcommentary();
            viewcommentary('wache_rproom','Sagen',15,'sagt');
            if($session['user']['profession'] == PROF_GUARD_HEAD || $session['user']['profession'] == PROF_GUARD_SUB || su_check(SU_RIGHT_DEBUG)) {
                addnav('Verwaltung');
                addnav('Ort verwalten','wache.php?op=rproom_edit');
            }
            addnav('Zurück');
            addnav('Zum Eingangsbereich','wache.php?op=hq');
            break;
        
        case 'rproom_edit':
            // Daten abrufen: Ortsname...
            $str_rproom_name = getsetting('wache_rproom_name','');
            $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
            // .. und Ortsbeschreibung
            $str_rproom_desc = getsetting('wache_rproom_desc','');
            $str_rproom_desc = (strlen($str_rproom_desc) > 1 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
            // end
            page_header(Strip_appoencode($str_rproom_name,3));
            if($_GET['act'] == 'save') {
                $bool_nochmal = false;
                // Ortsbeschreibung
                if($_POST['rproom_name'] != $str_rproom_name) {
                    if(strlen(trim($_POST['rproom_name'])) >= 2) {
                        $_POST['rproom_name'] = html_entity_decode(strip_appoencode(strip_tags($_POST['rproom_name']),2));
                        savesetting('wache_rproom_name',$_POST['rproom_name']);
                    } else {
                        output('`4Fehler! Der Ortsname muss mindestens 2 Zeichen lang sein.`n`n');
                        $bool_nochmal = true;
                    }
                }
                // Ortsbeschreibung
                if($_POST['rproom_desc'] != $str_rproom_desc) {
                    if (strlen(trim($_POST['rproom_desc'])) >= 15) {
                        $_POST['rproom_desc'] = html_entity_decode(closetags(strip_tags($_POST['rproom_desc']),'`i`b`c'));
                        savesetting('wache_rproom_desc',$_POST['rproom_desc']);
                    } else {
                        output('`4Fehler! Die Ortsbeschreibung muss mindestens 15 Zeichen lang sein.`n`n');
                        $bool_nochmal = true;
                    }
                }
                // Speichervorgang nicht erfolgreich?
                if($bool_nochmal) {
                    addnav('Nochmal','wache.php?op=rproom_edit');
                    addnav('Zurück');
                } else {
                    savesetting('wache_rproom_active',$_POST['rproom_active']);
                    redirect('wache.php?op=rproom');
                }
            } else {
                $form = array('rproom_active'=>'RP-Ort für die Öffentlichkeit zugänglich?,bool'
                        ,'rproom_name'=>'Name des RP-Orts:'
                        ,'rproom_name_prev'=>'Vorschau:,preview,rproom_name'
                        ,'rproom_desc'=>'Ortsbeschreibung:,textarea,45,8'
                        ,'rproom_desc_prev'=>'Vorschau:,preview,rproom_desc');
                $data = array('rproom_active'=>getsetting('wache_rproom_active',0)
                        ,'rproom_name'=>$str_rproom_name
                        ,'rproom_desc'=>$str_rproom_desc);
                output("<form action='wache.php?op=rproom_edit&act=save' method='POST'>",true);
                showform($form,$data,false,'Speichern');
                output("</form>",true);
                addnav('','wache.php?op=rproom_edit&act=save');
                addnav('Zurück');
                addnav('Zum RP-Ort','wache.php?op=rproom');
            }
            addnav('Zum Eingangsbereich','wache.php');
            break;            
                
        default:
                break;
}

page_footer();
?>

