
<?php
// Name: tempel.php
// Autor: tcb / Talion für http://lotgd.drachenserver.de (mail: t@ssilo.de)
// Erstellungsdatum: 5.5.05 - 17.5.05
// Erfordert Mods in Dateien: gardens.php, rock.php, beggar.php, dorfamt.php, bio.php, newday.php, configure.php
// Beschreibung:
//                Führt neuen Beruf Priester ein, zur Speicherung wird Var profession (Wertebereich von 11-13) genutzt.
//                Priester können verheiraten, scheiden, Flüche aufheben, Kopfgeldträger verfluchen, bekommen Bonus auf mystische Künste
//                Tempel-Location im Garten: Bettelstein hierherverlegt, Erlösung von Kopfgeld gegen Gems möglich, Heiratslocation
//                Neues Heiratssytem:
//                        - Bei >= 5 Flirts im Garten Verlobung
//                        - Priester muss Heirat starten (Vorsicht: Darf nicht gleichzeitig einer der zu Verheiratenden sein)
//                        - Priester schließt Heirat ab, Weiteres gleichbleibend
//                        Statusvar: 1 = im Gange, 2 = verheiratet, 3 = abgeschlossen
// Änderungen:
//

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
require_once(LIB_PATH.'profession.lib.php');

/* $races_chance = array('wwf' => 70, 'vmp' => 40, 'dmn' => 10);

$chance = 100;
if( isset($races_chance[$session['user']['race']]) && $session['user']['superuser'] == 0 ) { $chance = $races_chance[$session['user']['race']]; }
$arr_race = race_get($session['user']['race']);
if($chance < 100 && $_GET['op'] == '' && strpos($g_ret_page,'tempel') === false ) {
        output('`gAls du dich der Pforte des heiligen Tempels näherst, spürst du ein ungutes Ziehen in der linken unteren Bauchgegend.`n
                        Du rätselst, was das sein könnte. Hast du etwa verdorbenes Elfenfleisch gegessen? Oder gar zu viel Blut getrunken? Nein? Hm..
                        Da! Die Antwort schießt dir durch den Kopf:`n
                        DU bist ja ein '.$arr_race['colname'].'`g! Und deine Rasse verhindert dir wohl den Zutritt zu diesem Tempel der guten Götter. Mist..`n
                        Oder willst du es trotzdem versuchen?! Vielleicht hast du ja Glück..
                ');
        addnav('Einen Versuch ist es wert!','tempel.php?op=try_enter');
        addnav('Den Göttern ist nicht zu trauen..','gardens.php');
        page_footer();
        exit;
}

if($_GET['op'] == 'try_enter') {

        output('`gForschen Schrittes stößt du das Portal auf und trittst in den Tempel des Guten! Ungläubig siehst du dich um. Schön hier, denkst du dir.
                ');

        if( e_rand(1,100) > $chance ) {

                output('<h2>WHAM!</h2>
                                Ein `bBlitz`b muss deinen Schädel in zwei Hälften gespalten haben, oder war es eher ein überdimensionaler `bHammer`b?`n
                                Das einzige was feststeht: ES war definitv verdammt `btödlich`b.`n
                                Du verlierst 10% deiner Erfahrung, bekommst allerdings 5 Gefallen bei Jarcath für deine wagemutige Aktion!
                                ');
                $session['user']['hitpoints'] = 0;
                $session['user']['experience'] *= 0.9;
                $session['user']['deathpower'] += 5;

                addnews($session['user']['name'].'`g wurde bei seinem Versuch, den Tempel des Guten zu schänden, von göttlichem Unmut getroffen.');

                addnav('Jarcath, mein Freund!','shades.php');

        }
        else {

                addnav('Weiter gehts.','tempel.php');

        }

        page_footer();
        exit;
}*/

addcommentary();
checkday();

define("SCHNELLHOCHZ_KOSTEN",8000);
define("SCHNELLSCHEIDNG_KOSTEN",6000);
define("SCHNELLHOCHZ_ERLAUBT",1);
define("STATUS_START",1);
define("STATUS_VERHEIRATET",2);
define("STATUS_ABGESCHLOSSEN",3);
define('KERZE_PREIS',500);

define('TEMPELCOLORHEAD','`I');
define('TEMPELCOLORTEXT','`I');
define('TEMPELCOLORRULES','`&');
define('TEMPELCOLORMAUSOLEUM','`ç');
define('TEMPELCOLORGRAVEYARD','`O');
define('TEMPELCOLORSACRISTY','`°');


/* function show_rules () {

        output("`4I. ".TEMPELCOLORRULES."Die Priesterkaste und das Amt des Priesters ist in Ehren zu halten. Keinesfalls darf irgendeine Aktion ergriffen werden, die die unbefleckte Ehre der Priester beschmutzen würde!`n");
        output("`4II. ".TEMPELCOLORRULES."Den Anweisungen des Hohepriesters ist Folge zu leisten. Er repräsentiert die oberste Autorität des Priesterstands!`n");
        output("`4III. ".TEMPELCOLORRULES."Alle Gesetze dieser Stadt gelten in besonderem Maße für Priester!`n`0");
        output("`4IV. ".TEMPELCOLORRULES."Wer einen Priester bei einem Einbruch angreift und tötet, muss damit rechnen, für einige Tage verflucht zu werden!`n`0");
        output("`4V. ".TEMPELCOLORRULES."Priester dürfen hilflosen Schutzsuchenden und Personen, die durch besonderen Edelmut hervorragen, einen Segen erteilen!`n`0");
        output("`4VI. ".TEMPELCOLORRULES."Auf der anderen Seite ist es ihnen erlaubt, rücksichtslose und blinde Barbarei mit Flüchen zu ahnden!`n`0");
        output("`4VII. ".TEMPELCOLORRULES."Niemals jedoch sollen Priester ihre persönlichen Angelegenheiten mit ihrer Berufung mischen!`n`0");

}

function show_priest_list ($admin_mode=0) {

        global $session;

        $sql = "SELECT a.name,a.profession,a.acctid,a.login,a.loggedin,a.activated,a.laston FROM accounts a
                        WHERE a.profession=".PROF_PRIEST_HEAD." OR a.profession=".PROF_PRIEST;
        $sql .= ($admin_mode>=1) ? " OR a.profession=".PROF_PRIEST_NEW : "";
        $sql .= " ORDER BY profession DESC, name";

        $res = db_query($sql) or die (db_error(LINK));

        if(db_num_rows($res) == 0) {
                output("`n`iEs gibt keine Priester/innen!`i`n");
        }
        else {

                output('<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999"><tr class="trhead"><td>Nr.</td><td>Name</td><td>Funktion</td><td>Status</td></tr>',true);

                for($i=1; $i<=db_num_rows($res); $i++) {

                        $p = db_fetch_assoc($res);

                        if($session['user']['prefs']['popupbio'] == 1)
                        {
                                $link = "bio_popup.php?char=".rawurlencode($p['login']);
                                $str_link = '<a href="'.$link.'" target="_blank" onClick="'.popup_fullsize($link).';return:false;">'.$p['name'].'</a>';
                        }
                        else
                        {
                                $link = "bio.php?char=".rawurlencode($p['login']) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                                addnav("",$link);
                                $str_link = '<a href="'.$link.'">'.$p['name'].'</a>';
                        }

                        output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td><a href="mail.php?op=write&to='.rawurlencode($p['login']).'" target="_blank" onClick="'.popup("mail.php?op=write&to=".rawurlencode($p['login']) ).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>'.$str_link.'</td><td>`7',true);

                        switch( $p['profession'] ) {

                                case PROF_PRIEST_HEAD:
                                        output('`bHohepriester/in`b');
                                        if($admin_mode>=4) {
                                                output('`n<a href="tempel.php?op=entlassen&id='.$p['acctid'].'">Entlassen</a>',true);
                                                addnav("","tempel.php?op=entlassen&id=".$p['acctid']);

                                                output('`n<a href="tempel.php?op=hohep_deg&id='.$p['acctid'].'">Degradieren</a>',true);
                                                addnav("","tempel.php?op=hohep_deg&id=".$p['acctid']);
                                        }
                                        break;

                                case PROF_PRIEST:
                                        output('Priester/in');
                                        if($admin_mode>=3) {
                                                output('`n<a href="tempel.php?op=entlassen&id='.$p['acctid'].'">Entlassen</a>',true);
                                                addnav("","tempel.php?op=entlassen&id=".$p['acctid']);

                                                if($admin_mode>=4) {
                                                        output('`n<a href="tempel.php?op=hohep&id='.$p['acctid'].'">Zum Hohepriester machen</a>',true);
                                                        addnav("","tempel.php?op=hohep&id=".$p['acctid']);
                                                }
                                        }
                                        break;

                                case PROF_PRIEST_NEW:
                                        output('Novize/in');
                                        if($admin_mode>=3) {
                                                output('`n<a href="tempel.php?op=aufnehmen&id='.$p['acctid'].'">Aufnehmen</a>',true);
                                                addnav("","tempel.php?op=aufnehmen&id=".$p['acctid']);

                                                output('`n<a href="tempel.php?op=ablehnen&id='.$p['acctid'].'">Ablehnen</a>',true);
                                                addnav("","tempel.php?op=ablehnen&id=".$p['acctid']);

                                                if($admin_mode>=4) {
                                                        output('`n<a href="tempel.php?op=hohep&id='.$p['acctid'].'">Zum Hohepriester machen</a>',true);
                                                        addnav("","tempel.php?op=hohep&id=".$p['acctid']);
                                                }
                                        }
                                        break;

                                default:
                                        break;
                        }

                        output('</td><td>'.(user_get_online(0,$p)?'`@online`&':'`4offline`&').'</td></tr>',true);

                }        // END for

                output('</table>',true);

        }        // END priester vorhanden

}        // END show_priest_list */

function show_flirt_list ($admin_mode=0,$married=0) {

        global $session;

        $link = calcreturnpath();
        $link .= "&";

        $ppp = 30;

        $count_sql = "SELECT COUNT(*) AS anzahl FROM accounts a WHERE ";

        $str_search = '';

        if(!empty($_POST['search']))
        {
                $str_search = str_create_search_string($_POST['search']);
        }

        if($married < 2) {

                if(!empty($str_search)) {
                        $str_search = ' AND (a.name LIKE "'.$str_search.'" OR b.name LIKE "'.$str_search.'") ';
                }

                $sql = "SELECT a.name AS name_a,a.acctid AS acctid_a,b.name AS name_b,b.acctid AS acctid_b, a.login AS login_a, b.login AS login_b FROM accounts a,accounts b
                                        WHERE
                                        a.marriedto=b.acctid AND
                                        a.sex=1 AND b.sex=0 ".$str_search;
                if($married) {
                        $sql .= "AND ( a.charisma = 4294967295 AND b.charisma = 4294967295 )";
                        $count_sql .= "a.charisma=4294967295 AND a.marriedto>0 AND a.marriedto<4294967295";
                }
                else {
                        $sql .= "AND ( a.charisma = 999 AND b.charisma = 999 )";
                        $count_sql .= "a.charisma=999 AND a.marriedto>0 AND a.marriedto<4294967295";
                }

                $sql .= "ORDER BY name_a, name_b";

        }
        else {
                if(!empty($str_search)) {
                        $str_search = ' AND (a.name LIKE "'.$str_search.'") ';
                }

                $sql = "SELECT a.sex,a.name AS name_a,a.acctid AS acctid_a, a.login AS login_a FROM accounts a
                                        WHERE a.marriedto=4294967295 ".$str_search;
                $sql .= "ORDER BY name_a";
                $count_sql .= "a.marriedto=4294967295";
        }

        $count_res = db_query($count_sql) or die (db_error(LINK));
        $c = db_fetch_assoc($count_res);

        if($c['anzahl'] == 0) {
                output("`iEs gibt keine Paare!`i");
        }
        else {

                // wegen Paaren
                if($married < 2) {$c['anzahl'] = floor($c['anzahl'] * 0.5);}

                $page = max((int)$_GET['page'],1);

                $last_page = ceil($c['anzahl'] / $ppp);

                for($i=1; $i<=$last_page; $i++) {

                        $offs_max = min($i * $ppp,$c['anzahl']);
                        $offs_min = ($i-1) * $ppp + 1;

                        addnav("Seite ".$i." (".$offs_min." - ".$offs_max.")",$link."page=".$i);

                }

                $offs_min = ($page-1) * $ppp;

                $sql .= " LIMIT ".$offs_min.",".$ppp;

                $res = db_query($sql) or die (db_error(LINK));

                $str_searchlnk = $link;
                addnav('',$str_searchlnk);

                output('<table border="0" cellpadding="3">
                                <tr class="trhead" colspan="10">
                                        <form method="POST" action="'.$str_searchlnk.'">
                                                <input type="text" name="search" maxlenghth="50" value="'.stripslashes($_POST['search']).'"> <input type="submit" value="Suchen">
                                        </form>
                                </tr>
                                <tr class="trhead"><td>Nr.</td>',true);
                if($married < 2) {
                        output('<td><img src="images/female.png" alt="weiblich"> Name</td><td><img src="images/male.png" alt="männlich"> Name</td>',true);
                }
                else {
                        output('<td> Spieler</td><td> NPC</td>',true);
                }
                output( (($admin_mode)?'<td>Aktionen</td>':'').'</tr>',true);

                while($p = db_fetch_assoc($res)) {

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
                        if($married < 2) {output('<td>'.$mail_b.$str_link_b.'</td>',true);}
                        else {output('<td>'.(($p['sex']==0)?'Ophelía':'Silas').'</td>',true);}

                        if($admin_mode>=2) {
                                output('<td>',true);
                                if(!$married) {
                                        if(getsetting("temple_status",0) == 0 || getsetting("temple_status",0) == STATUS_ABGESCHLOSSEN) {
                                                output('<a href="tempel.php?op=hochz&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Hochzeit beginnen</a>',true);
                                                addnav("","tempel.php?op=hochz&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                                                output('`n<a href="tempel.php?op=trennung&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Verlobung lösen</a>',true);
                                                addnav("","tempel.php?op=trennung&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                                        }
                                        elseif(getsetting("temple_id1",0) == $p['acctid_a'] || getsetting("temple_id2",0) == $p['acctid_b']) {
                                                output('`iHochzeit im Gange`i',true);
                                        }

                                }
                                else {
                                        if($married==2) {
                                                output('<a href="tempel.php?op=scheidung&id1='.$p['acctid_a'].'&npc=1">Trennen</a>',true);
                                                addnav("","tempel.php?op=scheidung&id1=".$p['acctid_a']."&npc=1");
                                        }
                                        else {
                                                output('<a href="tempel.php?op=scheidung&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Trennen</a>',true);
                                                addnav("","tempel.php?op=scheidung&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                                        }

                                }
                                output('</td>',true);
                        }

                        output('</tr>',true);

                }        // END for

                output('</table>',true);

        }        // END paare vorhanden

}        // END show_flirt_list

function make_temple_commentary ($msg,$author=0) {

        $sql = "INSERT INTO commentary SET section='temple',author=".$author.",comment='".addslashes($msg)."',postdate=NOW()";
        db_query($sql) or die (db_error(LINK));

}        // END make_temple_commentary

$op = (isset($_GET['op']) ? $_GET['op'] : '');
$priest = 0;
if(su_check(SU_RIGHT_DEBUG)) {$priest = 4;}
//elseif($session['user']['profession'] == PROF_PRIEST_NEW) {$priest = 1;}
//elseif($session['user']['profession'] == PROF_PRIEST) {$priest = 2;}
//elseif($session['user']['profession'] == PROF_PRIEST_HEAD) {$priest = 3;}

switch ($op) {

        case '':

                $show_invent = true;
                
                page_header('Geweihter Boden');
                
                // Vorhänge: Hinweis auf Götter anzeigen
                switch(e_rand(0,12)) {
                    // Vilia: Leben, Licht - grün
                    case 0:
                        $str_godcolor = '`2';
                        $str_godoutput = $str_godcolor.'ein breites, grünes Band, das sich zu einem Endlosknoten formt. Ob damit der Lauf des Lebens gemeint ist?';
                    break;
                    // Bylwen: Natur - braun
                    case 1:
                        $str_godcolor = '`Z';
                        $str_godoutput = $str_godcolor.'den Umriss eines Bären, dessen Tatze zum nächsten Schritt erhoben ist.';
                    break;
                    // Yldur: Meer - türkisblau
                    case 2:
                        $str_godcolor = '`3';
                        $str_godoutput = $str_godcolor.'einen Anker, der von einer Welle umkreist wird.';
                    break;
                    // Avantador: Gerechtigkeit, Wahrheit - golden
                    case 3:
                        $str_godcolor = '°#daa520;';
                        $str_godoutput = $str_godcolor.'eine mit goldenem Garn aufgestickte Waage, welche in perfekter Balance steht.';
                    break;
                    // Irune: Frieden, Gleichgewicht - grau
                    case 4:
                        $str_godcolor = '`)';
                        $str_godoutput = $str_godcolor.'einen weißen und einen schwarzen Fisch, welche sich gegenseitig verfolgen und so einen Kreis bilden. Das
                                          Auge des weißen Fisches ist schwarz, das des schwarzen Fisches weiß.';
                    break;
                    // Ciardha: Vielfalt - gelb
                    case 5:
                        $str_godcolor = '`^';
                        $str_godoutput = $str_godcolor.'eine gespreizte Hand, die aus vielen, bunten Flicken zusammengesetzt ist. Selbst deine Lieblingsfarbe
                                          ist darunter.';
                    break;
                    // Thoroka: Kampf, Macht - dunkelrot
                    case 6:
                        $str_godcolor = '`4';
                        $str_godoutput = $str_godcolor.'ein Schwert mit breiter Klinge und schlichtem Griff. Seine Spitze zeigt gerade gen Himmel.';
                    break;
                    // Rhav: Heilung, Schlaf - lila
                    case 7:
                        $str_godcolor = '`V';
                        $str_godoutput = $str_godcolor.'zwei Schlangen, die sich symmentrisch um einen in die Höhe ragenden, geflügelten Stab winden.';
                    break;
                    // Ferreth: Handel, Handwerk, Arbeit - blau
                    case 8:
                        $str_godcolor = '`9';
                        $str_godoutput = $str_godcolor.'ein Pferd, das einen vollen Wagen zieht. Der Hals des Tieres ist gebogen und einer der Vorderhufe zum
                                          nächsten Schritt erhoben.';
                    break;
                    // Zhudesh: Liebe, Glück - pink
                    case 9:
                        $str_godcolor = '`M';
                        $str_godoutput = $str_godcolor.'ein Kleeblatt mit vier herzförmigen Blättern. Auf den ersten Blick wirkt das Symbol perfekt
                                          symmetrisch, doch je länger du es betrachtest, umso deutlicher werden die feinen Unterschiede in Größe und Gestaltung
                                          jedes Blattes.';
                    break;
                    // Sanatras: Weisheit, Wissen - mintgrün
                    case 10:
                        $str_godcolor = '`*';
                        $str_godoutput = $str_godcolor.'ein aufgeschlagenes Buch, dessen linke Seite mit krakeliger, enger Schrift beschrieben ist, während die
                                          rechte Seite ein Abbild der Welt zeigt.';
                    break;
                    // Hilox: Missordnung - rot
                    case 11:
                        $str_godcolor = '`C';
                        $str_godoutput = $str_godcolor.'einen Wirbel, dessen schmale, rote Linien sich in Kurven vom Mittelpunkt aus in alle Richtungen strecken.';
                    break;
                    // Jarcath: Tod, Schicksal - schwarz
                    case 12:
                        $str_godcolor = '`Â';
                        $str_godoutput = $str_godcolor.'einen fliegenden, schwarzen Raben im Profil mit ausgebreiteten Schwingen und rotem Auge. Sein Federkleid ist
                                          zerzaust und der Schnabel wie zum Schrei geöffnet.';
                    break;
                }
                // end Hinweis auf Götter
                
                output(TEMPELCOLORHEAD.'`c`bGeweihter Boden`b`c`n'.TEMPELCOLORTEXT.'
                        Du folgst einem breiten Kiesweg, der von niedrigen Hecken gesäumt wird. Die Flusskiesel unter deinen Füßen knirschen leise, als du dich dem
                        Götterhaus näherst. Vor dir erstreckt sich ein großer, halbkreisförmiger Vorplatz. Das Gebäude am Rand dieses Platzes ist aus hellem
                        Sandstein erbaut, der hier und da schon leichte Anzeichen von Verwitterung zeigt. Dereinst wurden Reliefs mit religiösen Darstellungen in
                        den Stein gemeißelt, die inzwischen von Wind und Wetter abgeschliffen wurden und unter Kletterrosen halb verborgen liegen. Ein zierlicher
                        Turm ragt empor, in dessen Spitze eine Glocke einmal zur vollen Stunde geläutet wird.`n
                        Im schmalen Eingangsbereich des Gebäudes empfängt dich zuerst schummriges Zwielicht, doch dann eröffnet sich vor dir ein rund angelegter
                        Saal, der durch diverse bodentiefe Buntglasfenster hell erleuchtet ist. Diese werden umrahmt von schweren Vorhängen, welche allesamt von
                        dick geflochtenen Bändern zusammengehalten werden. Als du näher trittst, erkennst du, dass jeden Vorhang '.$str_godcolor.'dasselbe Symbol
                        '.TEMPELCOLORTEXT.'ziert, das sich auch im zugehörigen Fenster wiederfinden lässt. Im Fall des Fensters, das du gerade betrachtest,
                        handelt es sich um '.$str_godoutput.'`n
                       '.TEMPELCOLORTEXT.'An der Seite entdeckst du auf einem schmalen Tisch eine gefüllte Waschschüssel und ein Tablett mit schmalen Kerzen, neben dem
                        ein Holzkästchen mit Schlitz im Deckel zu Spenden einlädt. Weiter hinten führt zudem eine Tür in einen quadratischen Hof, in dem du neben Kräuterbeeten
                        auch ein paar Obstbäume entdeckst. Er ist von einem Kreuzgang umgeben, von dem neben der Tür zum Götterhaus noch einige weitere abgehen. Du
                        vermutest, dass sich hinter dem schweren Holz die Räume der Priester befinden.`n`n');

                viewcommentary("temple","Leise sprechen:",25,"sagt",false,true,false,false,true);

                /*if(getsetting("temple_status",0) > 0) {

                        $sql = "SELECT name,acctid FROM accounts
                                        WHERE acctid=".getsetting('temple_id1',0)." OR acctid=".getsetting('temple_id2',0)." ORDER BY sex";
                        $res = db_query($sql);
                        $p1 = db_fetch_assoc($res);
                        $p2 = db_fetch_assoc($res);

                        if(getsetting("temple_status",0) == STATUS_START) {
                                output("`c`i`&Heute wird hier das wunderschöne Fest der Hochzeit von ".$p1['name']."`& und ".$p2['name']."`& begangen!");
                        }
                        elseif(getsetting("temple_status",0) == STATUS_VERHEIRATET || getsetting("temple_status",0) == STATUS_ABGESCHLOSSEN) {
                                output("`c`i`&".$p1['name']."`& und ".$p2['name']."`& haben gerade geheiratet! Herzlichen Glückwunsch!");
                        }
                        output("`i`c`n");
                }
                output('`n');

                if($priest >= 2) {
                        addnav("Priester");
                        addnav("B?Zum Beratungshaus","tempel.php?op=secret");

                        if(getsetting('temple_priest_id',0) == $session['user']['acctid']) {
                                addnav("Aktionen");

                                if(getsetting('temple_status',0) == STATUS_START) {
                                        addnav("`bVerheiraten`b","tempel.php?op=hochz_ok&heirat=1");
                                        }
                                elseif(getsetting('temple_status',0) == STATUS_VERHEIRATET) {
                                        addnav("`bZeremonie abschließen`b","tempel.php?op=hochz_ende");
                                        }
                                elseif(getsetting('temple_status',0) == STATUS_ABGESCHLOSSEN) {
                                //        addnav("`bAufräumen`b","tempel.php?op=sauber");
                                        }

                        }

                }

                addnav("Tempelanlagen");
                addnav("Opfern","tempel.php?op=opfer");
                addnav("Liste der Priester","tempel.php?op=priest_list");
                addnav("Liste der Diener","tempel.php?op=servant_list&public=1");
                addnav("Ehepaare","tempel.php?op=married_list_public");
                addnav("Schwarzes Brett","tempel.php?op=board");*/
                
                // Anstelle der Priester -> Sanatorium (Heilergemeinschaft)
                //addnav('Gemeinschaft der Heiler');
                //addnav('Zum Sanatorium','healer_prof.php');
                // end
                if($session['user']['charisma'] == 999 && SCHNELLHOCHZ_ERLAUBT) {
                    addnav("Eheschließung");
                    addnav("Hochzeit feiern (".SCHNELLHOCHZ_KOSTEN." Gold)","tempel.php?op=hochz_schnell",false,false,false,false);
                    addnav("Trennung");
                    addnav("Verlobung auflösen","tempel.php?op=trennung_schnell",false,false,false,false);
                } elseif($session['user']['marriedto'] > 0 && $session['user']['charisma'] == 4294967295 && SCHNELLHOCHZ_ERLAUBT) {
                    addnav("Scheidung");
                    addnav("Scheidung einreichen (".SCHNELLSCHEIDNG_KOSTEN." Gold)","tempel.php?op=scheidung_schnell",false,false,false,false);
                }

                addnav("Kopfgeld");
                addnav('U?Um Erlösung bitten','tempel.php?op=bounty_del');
                
                addnav('Götterkult');
                addnav('t?Zur Steintafel','tempel.php?op=plate');
                if($session['user']['gold'] >= KERZE_PREIS) {
                    addnav('K?Eine Kerze kaufen ('.KERZE_PREIS.' Gold)','tempel.php?op=buycandle');
                } else {
                    addnav('Eine Kerze kaufen ('.KERZE_PREIS.' Gold)','');
                }
                
                addnav('Weitere Orte');
                addnav('S?Zur Sakristei','tempel.php?op=sacristy');
                addnav('F?Zum Friedhof','tempel.php?op=graveyard');
                  addnav('Die Räume der Priester','tempel.php?op=tempel_inside');

                addnav("Zurück");
                addnav("g?In den Stadtgarten","gardens.php");
                addnav("S?In die Stadt","village.php");

                break;

        case 'secret':

                page_header('Beratungshaus');

                output("".TEMPELCOLORTEXT."Hier gibt es nur einen großen, runden Raum: In ihm befinden sich einige kleine Schreibtische,
                        Regale voll gestopft mit Büchern und anderen Dingen, die wohl Heilmittel sind, und der große
                        Schreibtisch der Hohepriesterin.`n
                        Dies hier ist ein Versammlungsort, an dem die Priester ungestört ihrer Schriftarbeit
                        nachgehen oder sich beraten können.`n`n");
                viewcommentary("temple_secret","Sprechen:",25,"spricht");

                addnav("Registratur");

                addnav("Liste der Priester","tempel.php?op=priest_list_admin");
                addnav("Liste der Verlobten","tempel.php?op=flirt_list");
                addnav("Liste der Verheirateten","tempel.php?op=married_list");
                addnav("Liste der Silas/Ophelía-Opfer","tempel.php?op=married_list_npc");
                addnav("Liste der Tempeldiener","tempel.php?op=servant_list");
                addnav("Zum schwarzen Brett","tempel.php?op=board");
                addnav("Die goldenen Regeln der Priester","tempel.php?op=rules");

                addnav("Aktionen");

                addnav("Flüche/Segen","tempel.php?op=fluch_liste_auswahl");
                addnav("Verfluchen/Segnen","tempel.php?op=fluch");
                if(getsetting("temple_status",0) == 0) {addnav("Aufräumen","tempel.php?op=sauber");}

                if($session['user']['profession'] == 11) {addnav("Kündigen","tempel.php?op=aufh");}

                //if(getsetting("temple_spenden",0) >= 50) {addnav("Wunder wirken!","tempel.php?op=wunder");}
                if ($session['user']['profession'] == PROF_PRIEST_HEAD || su_check(SU_RIGHT_DEBUG))
                {
                        addnav('Massenmail','tempel.php?op=massmail');
                }

                addnav("Zurück");

                addnav("Zur Halle","tempel.php");
                addnav("Zurück in die Stadt","village.php");
                break;
                
        case 'massmail': // Massenmail (im wohnviertel by mikay)
        {
                page_header('Beratungshaus');
                
                $str_out .= "`c`b`2Posthörnchenkobel unter dem Dach der Tempel.`b`c`n`n";

                addnav('Abbrechen','tempel.php?op=secret');

                $sql='SELECT acctid, name, login, profession
                        FROM accounts
                        WHERE profession='.PROF_PRIEST.'
                        OR profession='.PROF_PRIEST_HEAD.'
                        OR profession='.PROF_PRIEST_NEW.'
                        AND acctid!='.(int)$session['user']['acctid'].'
                        ORDER BY profession DESC';
                $result=db_query($sql);
                $users=array();
                $keys=0;

                while($row=db_fetch_assoc($result))
                {
                        $profs[0][0]='Zivilist';
                        if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';

                        $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" onclick="chk()" '.($row['profession']!=PROF_PRIEST_NEW ? 'checked':'').'> '.$row['name'].'<br>';
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
                        $str_out .= form_header('tempel.php?op=massmail')
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
                        $str_out .= '`c`bEs wurden noch keine Priester ernannt - und ja, explosive Hörnchenpost an missliebige Nachbarn sind gegen das Gesetz.`b`c';
                }
                output($str_out);
                break;
        } // END massmail

        case 'rules':

                page_header('Beratungshaus');

                $show_ooc = true;

                output("".TEMPELCOLORTEXT."Für die Ewigkeit bestimmt sind hier die Regeln der Priester festgehalten:`n`n");
                show_rules();

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");
                break;

        case 'priest_list':
        
                page_header('Geweihter Boden');

                output("".TEMPELCOLORTEXT."In Stein gemeißelt erkennst du eine Liste aller Priester/innen:`n`n");
                show_priest_list();

                if($session['user']['profession'] == 0) {addnav("Ich will Priester/in werden!","tempel.php?op=bewerben");}
                if($session['user']['profession'] == PROF_PRIEST_NEW) {addnav("Bewerbung zurückziehen","tempel.php?op=bewerben_abbr");}
                addnav("Zur Halle","tempel.php");
                break;

        case 'priest_list_admin':
        
                page_header('Beratungshaus');
        
                output("".TEMPELCOLORTEXT."Auf einer Schriftrolle befindet sich eine Liste aller Priester/innen:`n`n");
                show_priest_list($priest);
                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");
                break;

        case 'bewerben':
        
                page_header('Beratungshaus');

                $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_PRIEST." OR profession=".PROF_PRIEST_HEAD.")";
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                if($session['user']['dragonkills'] < getsetting('priestreq',15)) {
                        output("".TEMPELCOLORTEXT."Du musst mindestens ".getsetting('priestreq',15)."mal den grünen Drachen getötet haben, um Priester werden zu können!");
                        addnav('Zurück');
                        addnav("Zurück","tempel.php?op=priest_list");
                }
                elseif($p['anzahl'] >= getsetting("numberofpriests",3)) {
                        output("".TEMPELCOLORTEXT."Es gibt bereits ".$p['anzahl']." Priester. Mehr werden zur Zeit nicht benötigt!");
                        addnav('Zurück');
                        addnav("Zurück","tempel.php?op=priest_list");
                }
                else {
                        output("".TEMPELCOLORTEXT."Nach reiflicher Überlegung beschließt du, das Amt des Priesters anzustreben. Weiterhin gelten
                                für den Priesterstand die folgenden, unverletzbaren Regeln:`n`n");
                        show_rules();
                        output("`nAls Priester wärst du daran unbedingt gebunden!`nSteht dein Entschluss immer noch fest?");
                        addnav("Ja!","tempel.php?op=bewerben_ok&id=".$session['user']['acctid']);
                        addnav("Nein, zurück!","tempel.php?op=priest_list");
                }
                break;

        case 'bewerben_ok':
        
                page_header('Beratungshaus');
        
                $session['user']['profession'] = PROF_PRIEST_NEW;

                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_PRIEST_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
                $res = db_query($sql);
                if(db_num_rows($res)) {
                        $p=db_fetch_assoc($res);
                        systemmail($p['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& hat sich für den Posten des Priesters beworben. Du solltest seine Bewerbung überprüfen und ihn gegegebenfalls einstellen.");
                        }

                output("".TEMPELCOLORTEXT."Du reichst deine Bewerbung bei den Priestern ein, die diese gewissenhaft prüfen und dir dann Bescheid geben werden!`n");
                addnav('Zurück');
                addnav("Zurück","tempel.php?op=priest_list");
                break;

        case 'bewerben_abbr':
        
                page_header('Beratungshaus');
        
                $session['user']['profession'] = 0;

                output("".TEMPELCOLORTEXT."Du hast deine Bewerbung erfolgreich zurückgenommen!`n");
                addnav('Zurück');
                addnav("Zurück","tempel.php?op=priest_list");
                break;

        case 'aufh':
        
                page_header('Beratungshaus');
        
                $session['user']['profession'] = 0;

                $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_PRIEST_HEAD." ORDER BY loggedin DESC,RAND() LIMIT 1";
                $res = db_query($sql);
                if(db_num_rows($res)) {
                        $p=db_fetch_assoc($res);
                        systemmail($p['acctid'],"`&Kündigung!`0","`&".$session['user']['name']."`& hat beschlossen, fortan kein Priester mehr zu sein.");
                        }

                addnews($session['user']['name']." `&ist seit dem heutigen Tage kein".($session['user']['sex'] ? 'e Priesterin':' Priester')." mehr!");

                addhistory('`2Aufgabe des Priesteramts');

                output("".TEMPELCOLORTEXT."Etwas wehmütig legst du die Insignien ab und bist ab sofort wieder ein normaler Bürger!`n");
                addnav('Zurück');
                addnav("Zur Halle","tempel.php");
                addnav("Zur Stadt","village.php");
                break;

        case 'entlassen':
        
                page_header('Beratungshaus');
        
                output("".TEMPELCOLORTEXT."Diesen Priester wirklich entlassen?`n");
                addnav("Ja!","tempel.php?op=entlassen_ok&id=".$_GET['id']);
                addnav("Nein, zurück!","tempel.php?op=priest_list_admin");
                break;

        case 'entlassen_ok':
        
                page_header('Beratungshaus');
        
                $pid = (int)$_GET['id'];

                // Für Debugzwecke
                if($session['user']['acctid'] == $pid) {$session['user']['profession'] = 0;}

                $sql = "UPDATE accounts SET profession = 0
                                WHERE acctid=".$pid;
                db_query($sql) or die (db_error(LINK));

                systemmail($pid,"Du wurdest entlassen!",$session['user']['name']."`& hat dich aus dem Priesterstand entlassen.");

                $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute aus der ehrenvollen Gemeinschaft der Priester entlassen!',newsdate=NOW(),accountid=".$pid;
                db_query($sql) or die (db_error(LINK));

                addhistory('`$Entlassung aus dem Priesteramt',1,$pid);

                output("".TEMPELCOLORTEXT."Priester wurde entlassen!`n");
                addnav('Zurück');
                addnav("Zurück","tempel.php?op=priest_list_admin");
                break;

        case 'aufnehmen':
        
                page_header('Beratungshaus');
        
                $pid = (int)$_GET['id'];

                $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_PRIEST." OR profession=".PROF_PRIEST_HEAD.")";
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                if($p['anzahl'] >= getsetting("numberofpriests",3)) {
                        output("".TEMPELCOLORTEXT."Es gibt bereits ".$p['anzahl']." Priester! Mehr sind zur Zeit nicht möglich.");
                        addnav('Zurück');
                        addnav("Zurück","tempel.php?op=priest_list_admin");
                }
                else {

                        // Für Debugzwecke
                        if($session['user']['acctid'] == $pid) {$session['user']['profession'] = 11;}

                        $sql = "UPDATE accounts SET profession = ".PROF_PRIEST."
                                        WHERE acctid=".$pid;
                        db_query($sql) or die (db_error(LINK));

                        $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
                        $res = db_query($sql);
                        $p = db_fetch_assoc($res);

                        systemmail($pid,"Du wurdest aufgenommen!",$session['user']['name']."`& hat deine Bewerbung zur Aufnahme in die Priesterkaste angenommen. Damit bist du vom heutigen Tage an offiziell Mitglied dieser ehrenwerten Kaste!");

                        $sql = "INSERT INTO news SET newstext = '".addslashes($p['name'])." `&wurde heute offiziell in die ehrenvolle Gemeinschaft der Priester aufgenommen!',newsdate=NOW(),accountid=".$pid;
                        db_query($sql) or die (db_error(LINK));

                        addhistory('`2Aufnahme ins Priesteramt',1,$pid);

                        addnav("Willkommen!","tempel.php?op=priest_list_admin");

                        output("".TEMPELCOLORTEXT."Der neue Priester ist jetzt aufgenommen!");
                }
                break;

        case 'ablehnen':
        
                page_header('Beratungshaus');
        
                $pid = (int)$_GET['id'];

                // Für Debugzwecke
                if($session['user']['acctid'] == $pid) {$session['user']['profession'] = 0;}

                $sql = "UPDATE accounts SET profession = 0
                                WHERE acctid=".$pid;
                db_query($sql) or die (db_error(LINK));

                systemmail($pid,"Deine Bewerbung wurde abgelehnt!",$session['user']['name']."`& hat deine Bewerbung zur Aufnahme in die Priesterkaste abgelehnt.");

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=priest_list_admin");
                break;

        case 'hohep':
        
                page_header('Beratungshaus');
        
                $pid = (int)$_GET['id'];

                // Für Debugzwecke
                if($session['user']['acctid'] == $pid) {$session['user']['profession'] = 12;}

                $sql = "UPDATE accounts SET profession = ".PROF_PRIEST_HEAD."
                                WHERE acctid=".$pid;
                db_query($sql) or die (db_error(LINK));

                systemmail($pid,"Du wurdest befördert!",$session['user']['name']."`& hat dich zum Hohepriester ernannt.");

                addnav("Hallo Chef!","tempel.php?op=priest_list_admin");
                break;

        case 'hohep_deg':
        
                page_header('Beratungshaus');
        
                $pid = (int)$_GET['id'];

                // Für Debugzwecke
                if($session['user']['acctid'] == $pid) {$session['user']['profession'] = PROF_PRIEST;}

                $sql = "UPDATE accounts SET profession = ".PROF_PRIEST."
                                WHERE acctid=".$pid;
                db_query($sql) or die (db_error(LINK));

                systemmail($pid,"Du wurdest degradiert!",$session['user']['name']."`& hat dir die Hohepriesterwürden entzogen.");

                addnav("Das wars dann!","tempel.php?op=priest_list_admin");
                break;

        case 'sauber':
        
                page_header('Beratungshaus');
        
                savesetting("temple_id1","0");
                savesetting("temple_id2","0");
                savesetting("temple_status","0");
                savesetting("temple_priest_name"," ");
                savesetting("temple_priest_id","0");

                // Sicherung
                $sql = "UPDATE commentary SET section='temple_s' WHERE section='temple'";
                db_query($sql);
                // Sicherung Ende

                redirect("tempel.php");
                break;

        case 'hochz':
        
                page_header('Beratungshaus');

                if(getsetting("temple_status",0) != 0 && getsetting("temple_status",0) != STATUS_ABGESCHLOSSEN) {
                        output("".TEMPELCOLORTEXT."Gerade jetzt findet eine Hochzeit statt! Du willst doch da nicht stören?");
                        addnav('Zurück');
                        addnav("Zurück","tempel.php?op=married_list_admin");
                }
                else {



                                if($_GET['id1'] && $_GET['id2']) {
                                        savesetting("temple_id1",(int)$_GET['id1']);        // Partner 1
                                        savesetting("temple_id2",(int)$_GET['id2']);        // Partner 2
                                }

                                savesetting("temple_status",1);        // Status
                                savesetting("temple_priest_id",$session['user']['acctid']);

                                output("Du eröffnest die Zeremonie!");

                                make_temple_commentary(": `geröffnet die Zeremonie!",$session['user']['acctid']);

                                addnav("Los gehts!","tempel.php");

                }

                break;

        case 'hochz_ok':
        
                page_header('Beratungshaus');

                if(getsetting('temple_id1',0) == getsetting('temple_priest_id',0) || getsetting('temple_id1',0) == getsetting('temple_priest_id',0)) {

                        output("".TEMPELCOLORTEXT."Du kannst dich nicht selbst verheiraten! Frage einen anderen Priester, ob er das für dich übernimmt.");

                }
                else {

//                        hochz(getsetting('temple_id1',0),getsetting('temple_id2',0),true);

                        $sql = "SELECT name,acctid,guildid,guildfunc FROM accounts
                                        WHERE acctid=".getsetting('temple_id1',0)." OR acctid=".getsetting('temple_id2',0)." ORDER BY sex";
                        $res = db_query($sql);
                        $p1 = db_fetch_assoc($res);
                        $p2 = db_fetch_assoc($res);

                        // Hier evtl. LOCK TABLE...

                        $sql = "UPDATE accounts SET charisma = 4294967295, charm=charm+1, donation=donation+1, gems=gems+1
                                        WHERE acctid=".getsetting('temple_id1',0)." OR acctid=".getsetting('temple_id2',0);
                        db_query($sql) or die (db_error(LINK));

                        $sql = "INSERT INTO news SET newstext = '`%".addslashes($p1['name'])." `&und `%".addslashes($p2['name'])."`& haben heute feierlich den Bund der Ehe geschlossen!',newsdate=NOW(),accountid=".$p1['acctid'];
                        db_query($sql) or die (db_error(LINK));

                        systemmail($p1['acctid'],"`&Verheiratet!`0","`& Du und `&".$p2['name']."`& habt im Rahmen einer feierlichen und wunderschönen Zeremonie im Tempel geheiratet!`nGlückwunsch!`nAls Geschenk erhält jeder von euch einen Edelstein.");
                        systemmail($p2['acctid'],"`&Verheiratet!`0","`& Du und `&".$p1['name']."`& habt im Rahmen einer feierlichen und wunderschönen Zeremonie im Tempel geheiratet!`nGlückwunsch!`nAls Geschenk erhält jeder von euch einen Edelstein.");

                        addhistory('`vHeirat mit '.$p1['name'],1,$p2['acctid']);
                        addhistory('`vHeirat mit '.$p2['name'],1,$p1['acctid']);

                        savesetting("temple_status",2);        // Status
                        make_temple_commentary(": `gerklärt ".$p1['name']."`g und ".$p2['name']."`g offiziell zu Mann und Frau!",$session['user']['acctid']);

                        // Gildensystem
                        require_once(LIB_PATH.'dg_funcs.lib.php');
                        $state = 0;
                        if( ($p1['guildid']  && $p1['guildfunc'] != DG_FUNC_APPLICANT) ) {
                                $guild1 = &dg_load_guild($p1['guildid'],array('treaties','points'));
                        }
                        if( ($p2['guildid']  && $p2['guildfunc'] != DG_FUNC_APPLICANT) ) {
                                $guild2 = &dg_load_guild($p2['guildid'],array('treaties','points'));
                        }
                        if($guild1 && $guild2) {$state = dg_get_treaty($guild2['treaties'][$p1['guildid']]);}

                        $points = ($state == 1 ? $dg_points['wedding_friendly'] : ($state == 0 ? $dg_points['wedding_neutral'] : 0) );

                        if($guild1) {$guild1['points'] += $points;}
                        if($guild2) {$guild2['points'] += $points;}

                        dg_save_guild();
                        // END Gildensystem


                }

                redirect('tempel.php');
                break;

        case 'hochz_ende':
        
                page_header('Beratungshaus');

                make_temple_commentary(": `gschließt die Zeremonie ab.",$session['user']['acctid']);

                savesetting("temple_status",3);
                savesetting("temple_priest_id","0");        // Status

                redirect('tempel.php');
                break;

        case 'hochz_schnell':
        
                page_header('Geweihter Boden');

                if($session['user']['gold'] < SCHNELLHOCHZ_KOSTEN) {

                        output("".TEMPELCOLORTEXT."Du verfügst leider nicht über genug Gold, weswegen die Priester deinen Antrag zurückweisen!");

                }
                else {

                        output("".TEMPELCOLORTEXT."Willst Du wirklich diesen Schritt gehen und deine".($session['user']['sex'] ? "m Verlobten" : "r Verlobten")." mit Avantadors und Irunes
                                Segen die ewige Treue schwören?");
                        addnav("Ja, ich will!","tempel.php?op=hochz_schnell_ok");
                }

                addnav('Zurück');
                addnav("Zur Halle","tempel.php");

                break;

        case 'hochz_schnell_ok':
        
                page_header('Geweihter Boden');

                $session['user']['gold'] -= SCHNELLHOCHZ_KOSTEN;

                $sql = "SELECT name,acctid FROM accounts
                                WHERE acctid=".$session['user']['marriedto'];
                $res = db_query($sql);
                $p = db_fetch_assoc($res);

                $sql = "UPDATE accounts SET charisma = 4294967295
                                        WHERE acctid=".$p['acctid'];
                db_query($sql) or die (db_error(LINK));
                $session['user']['charisma'] = 4294967295;

                addnews("`%".$session['user']['name']." `&und `%".$p['name']."`& haben heute im kleinen Kreis feierlich den Bund der Ehe geschlossen!");
                
                addhistory('`tHochzeit mit '.$session['user']['name'],1,$p['acctid']);
                addhistory('`tHochzeit mit '.$p['name'],1,$session['user']['acctid']);

                systemmail($session['user']['acctid'],"`&Verheiratet!`0","`& Du und `&".$p['name']."`& habt im Rahmen einer kleinen Feier geheiratet!`nGlückwunsch!");
                systemmail($p['acctid'],"`&Verheiratet!`0","`& Du und `&".$session['user']['name']."`& habt im Rahmen einer kleinen Feier geheiratet!`nGlückwunsch!");

                output("".TEMPELCOLORTEXT."Du hast ".$p['name'].TEMPELCOLORTEXT." geheiratet. Herzlichen Glückwunsch!");

                addnav('Zurück');
                addnav("Zum Gotteshaus","tempel.php");
                addnav("Zur Stadt","village.php");

                break;

        case 'scheidung':
        
                page_header('Beratungshaus');

                if(!$_GET['npc']) {

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
                        db_query($sql) or die (db_error(LINK));

                        $str_newstext = "`%".addslashes($p1['name'])." `&und `%".addslashes($p2['name'])."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!";
                        $sql = "INSERT INTO news SET newstext = '".$str_newstext."', newsdate=NOW(),accountid=".$p1['acctid'];
                        db_query($sql) or die (db_error(LINK));

                        addhistory('`tScheidung von '.$p1['name'],1,$p2['acctid']);
                        addhistory('`tScheidung von '.$p2['name'],1,$p1['acctid']);

                        systemmail($p1['acctid'],"`&Scheidung!`0","`& Du und `&".$p2['name']."`& habt euch getrennt und eure Ehe anulliert!");
                        systemmail($p2['acctid'],"`&Scheidung!`0","`& Du und `&".$p1['name']."`& habt euch getrennt und eure Ehe anulliert!");

                        make_temple_commentary(": `gerklärt ".$p1['name']."`g und ".$p2['name']."`g als geschieden!",$session['user']['acctid']);
                }
                else {

                        $id = (int)$_GET['id1'];

                        $sql = "SELECT name,acctid,sex FROM accounts
                                        WHERE acctid=".$id;
                        $res = db_query($sql);
                        $p = db_fetch_assoc($res);

                        $sql = "UPDATE accounts SET charisma = 0, marriedto=0
                                        WHERE acctid=".$id;
                        db_query($sql) or die (db_error(LINK));

                        $npc_name = (($p['sex']==0)?"Ophelía":"Silas");

                        $sql = "INSERT INTO news SET newstext = '`%".addslashes($p['name'])." `&und `%".$npc_name."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!', newsdate=NOW(),accountid=".$p['acctid'];
                        db_query($sql) or die (db_error(LINK));

                        systemmail($p['acctid'],"`&Scheidung!`0","`& Du und `&".$npc_name."`& habt euch getrennt und eure Ehe anulliert!");
                        make_temple_commentary(": `gerklärt ".$p['name']."`g und ".$npc_name."`g als geschieden!",$session['user']['acctid']);

                }

                output("".TEMPELCOLORTEXT."Erfolgreich geschieden!");

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");

                break;

        case 'scheidung_schnell':

                page_header('Geweihter Boden');
                
                if($session['user']['marriedto'] != 4294967295) {
                    $p = db_fetch_assoc(db_query("SELECT name,acctid FROM accounts WHERE acctid=".$session['user']['marriedto']));
                } else {
                    $p['name'] = ($session['user']['sex'] == 0 ? "Ophelía" : "Silas");
                }

                if($_GET['act'] == 'confirm') {      # Ehe mit anderem Char aufheben
                    if($session['user']['marriedto'] != 4294967295) {

                        // Hier evtl. LOCK TABLE...

                        db_query("UPDATE accounts SET charisma = 0, marriedto=0 WHERE acctid=".$p['acctid']) or die (db_error(LINK));
                        $session['user']['charisma'] = 0;
                        $session['user']['marriedto'] = 0;

                        $str_newstext = "`%".addslashes($session['user']['name'])." `&und `%".addslashes($p['name'])."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!";
                        db_query("INSERT INTO news SET newstext = '".$str_newstext."', newsdate=NOW(),accountid=".$session['user']['acctid']) or die (db_error(LINK));

                        addhistory('`tScheidung von '.$session['user']['name'],1,$p['acctid']);
                        addhistory('`tScheidung von '.$p['name'],1,$session['user']['acctid']);

                        systemmail($p['acctid'],"`&Scheidung!`0","`& Du und `&".$session['user']['name']."`& habt euch getrennt und eure Ehe annulliert!");

                        output(TEMPELCOLORTEXT."Deine Ehe mit ".$p['name'].TEMPELCOLORTEXT." wurde annulliert, ihr seid offiziell geschieden.");
                    } else {      # Ehe mit Ophelía / Silas aufheben
                        $session['user']['charisma'] = 0;
                        $session['user']['marriedto'] = 0;

                        $sql = "INSERT INTO news SET newstext = '`%".addslashes($session['user']['name'])." `&und `%".$p['name']."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!', newsdate=NOW(),accountid=".$p['acctid'];
                        db_query($sql) or die (db_error(LINK));

                        output(TEMPELCOLORTEXT."Deine Ehe mit ".$p['name'].TEMPELCOLORTEXT." wurde annulliert, ihr seid offiziell geschieden.");
                    }
                    addnav('Zurück');
                    addnav("Zum Heiligtum","tempel.php");
                } else {
                    if($session['user']['gold'] < SCHNELLSCHEIDNG_KOSTEN) {
                        output(TEMPELCOLORTEXT."Leider kannst du die Scheidung nicht bezahlen. Dir fehlen die nötigen `^".SCHNELLSCHEIDNG_KOSTEN.TEMPELCOLORTEXT." Gold.");
                        addnav('Mist!');
                        addnav('Zurück','tempel.php');
                    } else {
                        output(TEMPELCOLORTEXT."Du bist drauf und dran, deine derzeitige Ehe mit ".$p['name'].TEMPELCOLORTEXT." für nichtig zu erklären. Möchtest du diesen Schritt
                                wirklich tun?");
                        addnav('Bestätigen');
                        addnav('Ja, Scheidung einreichen','tempel.php?op=scheidung_schnell&act=confirm');
                        addnav('z?Nein, zurück','tempel.php');
                    }
                }

                break;

        case 'trennung':
        
                page_header('Beratungshaus');

                $id1 = (int)$_GET['id1'];
                $id2 = (int)$_GET['id2'];

                $sql = "SELECT name,acctid FROM accounts
                                WHERE acctid=".$id1." OR acctid=".$id2." ORDER BY sex";
                $res = db_query($sql);
                $p1 = db_fetch_assoc($res);
                $p2 = db_fetch_assoc($res);

                $sql = "UPDATE accounts SET charisma = 0, marriedto=0
                                WHERE acctid=".$id1." OR acctid=".$id2;
                db_query($sql) or die (db_error(LINK));

                //$sql = "INSERT INTO news SET newstext = '`%".$p1['name']." `&und `%".$p2['name']."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!', newsdate=NOW(),accountid=".$p1['acctid'];
                //db_query($sql) or die (db_error(LINK));

                systemmail($p1['acctid'],"`&Trennung!`0","`& Du und `&".$p2['name']."`& habt euch getrennt und eure Verlobung annulliert!");
                systemmail($p2['acctid'],"`&Trennung!`0","`& Du und `&".$p1['name']."`& habt euch getrennt und eure Verlobung annulliert!");

                make_temple_commentary(": `gerklärt ".$p1['name']."`gs und ".$p2['name']."`gs Verlobung als aufgelöst!",$session['user']['acctid']);

                output("".TEMPELCOLORTEXT."Verlobung gelöst!");

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");

                break;

        case 'trennung_schnell':

                page_header('Geweihter Boden');
                
                $p = db_fetch_assoc(db_query("SELECT name,acctid FROM accounts WHERE acctid=".$session['user']['marriedto']));
                
                if($_GET['act'] == 'confirm') {

                    db_query("UPDATE accounts SET charisma = 0, marriedto=0 WHERE acctid=".$p['acctid']) or die (db_error(LINK));
                    $session['user']['charisma'] = 0;
                    $session['user']['marriedto'] = 0;

                    systemmail($p['acctid'],"`&Trennung!`0","`& Du und `&".$session['user']['name']."`& habt euch getrennt und eure Verlobung annulliert!");

                    output("".TEMPELCOLORTEXT."Deine Verlobung mit ".$p['name'].TEMPELCOLORTEXT." wurde annulliert!");

                    addnav('Zurück');
                    addnav("Zum Heiligtum","tempel.php");
                } else {
                    output(TEMPELCOLORTEXT."Du bist drauf und dran, deine Verlobung mit ".$p['name'].TEMPELCOLORTEXT." für nichtig zu erklären. Möchtest du diesen Schritt
                            wirklich tun?");
                    addnav('Bestätigen');
                    addnav('Ja, Verlobung annullieren','tempel.php?op=trennung_schnell&act=confirm');
                    addnav('z?Nein, zurück','tempel.php');
                }

                break;

        case 'flirt_list':
        
                page_header('Beratungshaus');
        
                show_flirt_list($priest);

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");
                break;

        case 'married_list':
        
                page_header('Beratungshaus');
        
                show_flirt_list($priest,1);

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");
                break;

        case 'married_list_npc':
        
                page_header('Beratungshaus');
        
                show_flirt_list($priest,2);

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");
                break;

        case 'married_list_public':
        
                page_header('Geweihter Boden');
        
                show_flirt_list(0,1);

                addnav('Zurück');
                addnav("Zur Halle","tempel.php");
                break;

        case 'opfer':
        
                page_header('Geweihter Boden');

                output("".TEMPELCOLORTEXT."Hier kannst du in Meditation versinken, die Götter um ein Geschenk bitten und dafür ein Opfer bringen. Sie werden dir entweder permanente Lebenskraft, Edelsteine oder Gold abnehmen - je nachdem, wonach ihnen der Sinn steht.`nWie viele Runden willst du meditieren?");

                addnav("Wie lange?");
                if($session['user']['turns'] >= 2) addnav("... 2 Runden","tempel.php?op=opfer_ok&runden=2");
                if($session['user']['turns'] >= 5) addnav("... 5 Runden","tempel.php?op=opfer_ok&runden=5");
                if($session['user']['turns'] >= 10) addnav("... 10 Runden","tempel.php?op=opfer_ok&runden=10");
                if($session['user']['castleturns']) addnav('... 1 Schlossrunde','tempel.php?op=gardenmaze');
                addnav("Weg hier!");

                addnav('Zurück');
                addnav("... Zurück!","tempel.php");

                break;

        case 'opfer_ok':
        
                page_header('Geweihter Boden');

                $runden = $_GET['runden'];
                $glueck = e_rand ( 0, ( 20 - $runden ) );
                if($glueck == 0) { $glueck = 2; }
                elseif($glueck > 0 && $glueck < 10) {$glueck = 1;}
                else {$glueck = 0.1;}
                $was = e_rand(1,7);
                $menge = e_rand(1,10);
                $msg = "";
                $val1 = 0;
                $val_gold = 0;

                $session['user']['turns'] -= $runden;

                output("".TEMPELCOLORTEXT."Du atmest ruhig ein und aus, ein und aus... fühlst deine Entspannung wachsen. Schließlich bist du den Göttern ganz nah und bietest ihnen ein Opfer. Sie nehmen dir...");

                switch($was) {

                        case 1:
                                $menge = ceil($menge * 0.5);

                                if( ($session['user']['maxhitpoints']-$menge) > $session['user']['level'] * 10 ) {

                                        $session['user']['maxhitpoints'] -= $menge;
                                        debuglog("Opferte ".$menge." LP im Tempel!");

                                        $val1 = ceil($runden * $menge * 0.4 * e_rand(1,2) * $glueck);
                                        $val1 = min($val1,min($session['user']['level']+10,20));
                                        $val_gold = $val1 * 200;

                                        $item = array('tpl_name'=>"Göttliche Rüstung",'tpl_description'=>"Eine Rüstung mit ".$val1." Verteidigung, die du von den Göttern als Dank für dein Opfer erhalten hast.",'tpl_value1'=>$val1,'tpl_gold'=>$val_gold);

                                        item_add($session['user']['acctid'],'rstdummy',$item);

                                        $msg = "`^".$menge.TEMPELCOLORTEXT." permanente Lebenskraft.`nVor deinen Füßen liegt nun eine neue, schimmernde Rüstung mit ".$val1." Verteidigung!";

                                }
                                else {
                                        $msg = "`^".$menge.TEMPELCOLORTEXT." permanente Lebenskraft, die du leider nicht hast! Unbefriedigt erhebst du dich.";
                                        $menge = 0;
                                }

                                break;

                        case 2:
                        case 3:

                                if( $menge <= $session['user']['gems'] ) {

                                        $session['user']['gems'] -= $menge;
                                        debuglog("Opferte ".$menge." Edelsteine im Tempel!");

                                        $val1 = ceil($runden * $menge * 0.2 * e_rand(1,2) * $glueck);
                                        $val1 = min($val1, min($session['user']['level']+10,20) );
                                        $val_gold = $val1 * 200;

                                        $item = array('tpl_name'=>"Göttliche Waffe",'tpl_description'=>"Eine Waffe mit ".$val1." Angriff, die du von den Göttern als Dank für dein Opfer erhalten hast.",'tpl_value1'=>$val1,'tpl_gold'=>$val_gold);

                                        item_add($session['user']['acctid'],'waffedummy',$item);

                                        $msg = "`^".$menge.TEMPELCOLORTEXT." Edelsteine!`nVor deinen Füßen liegt eine neue, glänzende Waffe mit ".$val1." Angriff!";

                                }
                                else {
                                        $msg = "`^".$menge.TEMPELCOLORTEXT." Edelsteine, die du leider nicht hast! Unbefriedigt erhebst du dich.";
                                        $menge = 0;
                                }


                                break;

                        case 4:
                        case 5:

                                $menge *= 500;

                                if( $menge <= $session['user']['gold'] ) {

                                        $session['user']['gold'] -= $menge;

                                        $val1 = ceil($runden * $menge * 0.001 * e_rand(1,3) * $glueck) * 0.01;
                                        $val1 = min(max($val1,1.1),1.6);
                                        $val_gold = floor($val1 * 1500);

                                        $item = array('tpl_value1'=>$val1,'tpl_gold'=>$val_gold);

                                        item_add($session['user']['acctid'],'gtlschtzzb',$item);

                                        $msg = "`^".$menge.TEMPELCOLORTEXT." Gold!`nVor deinen Füßen liegt ein seltener Zauberspruch!";

                                }
                                else {
                                        $msg = "`^".$menge.TEMPELCOLORTEXT." Gold, das du leider nicht hast! Unbefriedigt erhebst du dich.";
                                        $menge = 0;
                                }

                                break;

                        case 6:
                        case 7:
                                $msg = "gar nichts. Sie halten dich für \"zu gierig\". Was immer das heißen mag...";
                                $menge = 0;
                                break;

                }

                if($menge > 0) {

                        if($glueck < 1) { $msg.= "`nHeute ist wohl nicht dein Glückstag.. Die Götter scheinen von deiner Ernsthaftigkeit nicht überzeugt gewesen zu sein!`n";        }
                        elseif($glueck > 1) { $msg.= "`nDu musst der Liebling der Götter sein!`n";        }
                }

                output($msg);

                if($session['user']['turns'] >= 2) {addnav("Nochmal meditieren","tempel.php?op=opfer");}
                addnav('Zurück');
                addnav("Zur Halle","tempel.php");

                break;
        case 'gardenmaze':
        
                page_header('Geweihter Boden');
        
                output(''.TEMPELCOLORTEXT.'Du atmest ruhig ein und aus, ein und aus... fühlst deine Entspannung wachsen. Schließlich bist du den Göttern ganz nah und bietest ihnen ein Opfer.`nSie nehmen dir 10% deiner Lebenskraft und führen dich an einen verlassenen Ort.');
                $session['user']['hitpoints']*=.9;
                addnav("Weiter","abandoncastle.php?choose=2");
                break;

        case "wunder":
        
                page_header('Geweihter Boden');
        
                output("");

                addnav("Alle von den Toten erwecken!","tempel.php?op=wunder_ok&wunder=auferstehung");
                addnav("Sofortiges Stadtfest!","tempel.php?op=wunder_ok&wunder=auferstehung");
                addnav("Sehr gute Stimmung für alle!","tempel.php?op=wunder_ok&wunder=auferstehung");
                addnav("!","tempel.php?op=wunder_ok&wunder=auferstehung");

                break;

        case 'wunder_ok':
        
                page_header('Geweihter Boden');

                switch($_GET['wunder']) {

                        case '':

                                break;

                        default:
                                break;

                        }

                break;

        case 'fluch':
        
                page_header('Beratungshaus');

                output("".TEMPELCOLORTEXT."Als Priester kannst du allen Helden einen Fluch aufzwingen, der sie beim Kampf beeinträchtigt. Oder einen Segen, je nachdem. Beides verschwindet von selbst nach einiger Zeit.`n`n");

                if(!$_POST['name']) {
                        output('<form action="tempel.php?op=fluch" method="POST">',true);
                        output('<input type="text" size="20" name="name">',true);
                        output('<input type="submit" size="20" name="ok" value="Suchen">',true);
                        output('</form>',true);
                        addnav("","tempel.php?op=fluch");
                }
                else {

                        $ziel = rawurldecode($_POST['name']);

                        $name = str_create_search_string($ziel);

            $sql = "SELECT acctid,name FROM accounts WHERE name LIKE '".$name."' AND locked=0";
                        $res = db_query($sql);

                        if(!db_num_rows($res)) {
                                output("`iKeine Übereinstimmung gefunden!`i");
                        }
                        elseif(db_num_rows($res) >= 100) {
                                output("`iZu viele Übereinstimmungen! Grenze deinen Suchbegriff etwas ein.`i");
                        }
                        else {
                                output('<form action="tempel.php?op=fluch_ok" method="POST">',true);
                                output('<select name="id" size="1">',true);
                                while($p = db_fetch_assoc($res)){
                                        output("<option value=\"".$p['acctid']."\">".preg_replace("'[`].'","",$p['name'])."</option>",true);
                }
                                output('</select> `n',true);
                                output('<select name="buff" size="1"><option value="f1">Fluch</option><option value="f2">Schlimmer Fluch</option><option value="s1">Segen</option></select>`n',true);
                                output('<input type="submit" size="20" name="ok" value="Los!">',true);
                                output('</form>',true);
                                addnav("","tempel.php?op=fluch_ok");

                        }

                }

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");

                break;

        case 'fluch_ok':
        
                page_header('Beratungshaus');

                if($_POST['buff'] == "f1") {

                        $name = "Fluch der Tempelpriester";
                        $desc = "Die Tempelpriester haben dich wegen eines bestimmten Grunds verflucht..";
                        $buff = array("name"=>$name,"rounds"=>500,"wearoff"=>"Der Fluch lässt nach!",
                        "atkmod"=>0.8,"defmod"=>0.8,"roundmsg"=>"Der Fluch behindert dich!","activate"=>"offense,defense");
                        $sql = "INSERT INTO items SET gold=5000,gems=30,name='".$name."',description='".$desc."',hvalue=4,owner=".(int)$_POST['id'].",class='Fluch',buff='".serialize($buff)."'";

                        item_add((int)$_POST['id'],'tmplflch1');

                        systemmail((int)$_POST['id'],"`4Verflucht!",$session['user']['name']." `4hat dich für deine Freveltaten in seiner Eigenschaft als Priester mit dem Fluch der Tempelpriester belegt!");
                        output("".TEMPELCOLORTEXT."Du begibst dich in eine tiefe Trance. Nachdem du eine dem Opfer ähnelnde Stoffpuppe misshandelt hast, fühlst du die Energie des Fluches!`n`n");
                }

                elseif($_POST['buff'] == "f2") {

                        $name = "Schlimmer Fluch der Tempelpriester";
                        $desc = "Die Tempelpriester haben dich wegen eines bestimmten Grunds verflucht..";
                        $buff = array("name"=>$name,
                        "rounds"=>500,
                        "wearoff"=>"Der Fluch lässt nach!","atkmod"=>0.5,"defmod"=>0.5,"roundmsg"=>
                        "Der Fluch behindert dich!","activate"=>"offense,defense");
                        $sql = "INSERT INTO items SET gold=10000,gems=40,name='".$name."',description='".$desc."',hvalue=4,owner=".(int)$_POST['id'].",class='Fluch',buff='".serialize($buff)."'";

                        item_add((int)$_POST['id'],'tmplflch2');

                        systemmail((int)$_POST['id'],"`4Verflucht!",$session['user']['name']." `4hat dich für deine Freveltaten in seiner Eigenschaft als Priester mit dem schlimmen Fluch der Tempelpriester belegt!");
                        output("".TEMPELCOLORTEXT."Du begibst dich in eine tiefe Trance. Nachdem du eine dem Opfer ähnelnde Stoffpuppe misshandelt hast, fühlst du die Energie des Fluches!`n`n");
                }

                elseif($_POST['buff'] == "s1") {

                        $name = "Segen der Tempelpriester";
                        $desc = "Die Tempelpriester gewähren dir diesen Segen..";
                        $buff = array("name"=>$name,"rounds"=>120,"wearoff"=>"Der Segen lässt nach!","atkmod"=>1.15,"defmod"=>1.15,"roundmsg"=>"Der Segen stärkt dich!","activate"=>"offense,defense");
                        $sql = "INSERT INTO items SET name='".$name."',description='".$desc."',owner=".(int)$_POST['id'].",class='Geschenk',hvalue=4,buff='".serialize($buff)."'";

                        item_add((int)$_POST['id'],'tmplsgn');

                        systemmail((int)$_POST['id'],"`@Gesegnet!",$session['user']['name']." `@hat dich in seiner Eigenschaft als Priester mit einem göttlichen Segen bedacht!");
                        output("".TEMPELCOLORTEXT."Du begibst dich in eine tiefe Trance. Nachdem du eine der Person ähnelnde Stoffpuppe gestreichelt hast, fühlst du die Energie des Segens!`n`n");
                }

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");

                break;

        case 'fluch_liste_auswahl':
        
                page_header('Beratungshaus');

                $sql = "SELECT a.name, a.acctid FROM items i
                                INNER JOIN accounts a ON a.acctid = i.owner
                                LEFT JOIN items_tpl it ON it.tpl_id=i.tpl_id
                                WHERE (it.curse>0 OR i.tpl_id='tmplflch1' OR i.tpl_id='tmplflch2' OR i.tpl_id='tmplsgn')
                                GROUP BY i.owner ORDER BY a.name";

                $res = db_query($sql);

                output("".TEMPELCOLORTEXT."Du schaust in den magischen Spiegel und erkennst auf einer langen Liste sämtliche Helden, denen Flüche oder Segen anhängen:`n`n");

                if(db_num_rows($res) == 0) {
                        output("`iEs gibt keine Verfluchten oder Gesegneten!`i");
                }
                else {

                        output('<table border="0"  cellpadding="3"><tr class="trhead"><td>Nr.</td><td>Name</td><td>Aktionen</td></tr>',true);

                        for($i=1; $i<=db_num_rows($res); $i++) {

                                $p = db_fetch_assoc($res);

                                output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td>'.$p['name'].'</td><td><a href="tempel.php?op=fluch_liste&id='.$p['acctid'].'">Erscheinungen anzeigen</a></td>',true);

                                output('</tr>',true);

                                addnav("","tempel.php?op=fluch_liste&id=".$p['acctid']);

                        }        // END for

                        output('</table>',true);

                }        // END flüche vorhanden

                output('',true);

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=secret");

                break;

        case 'fluch_liste':
        
                page_header('Beratungshaus');

                $sql = "SELECT a.name, a.acctid, i.id, i.name AS fluchname, i.hvalue FROM items i
                                INNER JOIN accounts a ON i.owner = a.acctid
                                LEFT JOIN items_tpl it ON it.tpl_id=i.tpl_id
                                WHERE (it.curse>0 OR i.tpl_id='tmplflch1' OR i.tpl_id='tmplflch2' OR i.tpl_id='tmplsgn') AND i.owner=".(int)$_GET['id']." ORDER BY i.name";

                $res = db_query($sql);

                output("".TEMPELCOLORTEXT."Bald darauf werden diese Flüche und Segen sichtbar:`n`n");

                output('<table border="0" cellpadding="3"><tr class="trhead"><td>Nr.</td><td>Name</td><td>Tage verbleibend</td><td>Aktionen</td></tr>',true);

                for($i=1; $i<=db_num_rows($res); $i++) {

                        $p = db_fetch_assoc($res);

                        output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td>'.$p['fluchname'].'</td><td>'.(($p['hvalue'] == 0) ? 'unbegrenzt':$p['hvalue']).'</td><td><a href="tempel.php?op=fluch_del&id='.$p['id'].'">Aufheben</a></td>',true);

                        output('</tr>',true);

                        addnav("","tempel.php?op=fluch_del&id=".$p['id']);

                }        // END for

                output('</table>',true);

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=fluch_liste_auswahl");

                break;

        case 'fluch_del':
        
                page_header('Beratungshaus');

                $sql = "SELECT i.name,i.id,i.owner FROM items i WHERE i.id=".(int)$_GET['id'];

                $i = item_get(' id='.(int)$_GET['id'],false);

                $sql = "DELETE FROM items WHERE id=".$i['id'];

                item_delete(' id='.(int)$_GET['id']);

                output("".TEMPELCOLORTEXT."Du konzentrierst dich auf den Fluch oder Segen und spürst bereits nach kurzer Zeit, wie er schwächer und schwächer wird. Schließlich weißt du:`nEr ist Vergangenheit!");

                if($i['tpl_id'] == "tmplsgn") {
                        systemmail($i['owner'],"Segen aufgehoben!",$session['user']['name']." `@hat in seiner Eigenschaft als Priester den Segen von dir genommen.");
                }
                else {
                        systemmail($i['owner'],"Fluch aufgehoben!",$session['user']['name']." `@hat dich in seiner Eigenschaft als Priester von deinem schrecklichen Fluch \"".$i['name']."\" befreit.");
                }

                addnav('Zurück');
                addnav("Zurück","tempel.php?op=fluch_liste_auswahl");

                break;

        case 'bounty_del':
        
                page_header('Geweihter Boden');

                $gemcount = floor($session['user']['bounty'] * 0.001) * $session['user']['level'];
                $gemcount = min( max($gemcount, 3) , 50);

                if($_GET['act'] == 1) {

                        if($session['user']['gems'] < $gemcount) {
                                output("".TEMPELCOLORTEXT."Leider hast du nicht so viele Edelsteine.");
                        }
                        else {

                                $session['user']['gems'] -= $gemcount;

                                if(e_rand(1,2)==1) {

                                        output("".TEMPELCOLORTEXT."Die Götter erlassen dir deine Sünden (Kopfgeld verfallen)!");
                                        $session['user']['bounty'] = 0;

                                }
                                else {

                                        output("".TEMPELCOLORTEXT."Die Götter gewähren dir keine Entlastung!");

                                }

                        }
                }

                else {

                        if($session['user']['bounty'] == 0) {
                                output("".TEMPELCOLORTEXT."Auf dich ist kein Kopfgeld ausgesetzt. Was willst du also hier?");
                        }
                        else {
                                output("".TEMPELCOLORTEXT."Willst du für `^".$gemcount.TEMPELCOLORTEXT."Edelsteine um Erlösung deiner Sünden (Kopfgeld in Höhe von `^".$session['user']['bounty']."`& Gold) bitten? Wisse jedoch, dass auf die Götter kein Verlass ist..");
                                addnav('Erlösung');
                                addnav("Um Erlösung bitten","tempel.php?op=bounty_del&act=1");
                        }
                }

                addnav('Zurück');
                addnav("Zur Halle","tempel.php");

                break;

        case 'board':
        
                page_header('Geweihter Boden');

                output("".TEMPELCOLORTEXT."Neugierig trittst du näher an die Stellwand heran. Du erkennst Pergamente, die über bald anstehende Hochzeiten informieren.`n`n");

                board_view('tempel',($priest>=2)?2:0,'An der Wand sind folgende Nachrichten zu lesen:','Es scheinen keine Nachrichten vorhanden zu sein.');

                output("`n`n");

                if($priest >= 2) {

                        board_view_form("Aufhängen","`&Hier kannst du als Priester eine Nachricht hinterlassen:`n");
                        if($_GET['board_action'] == "add") {
                                board_add('tempel');
                                redirect("tempel.php?op=board");
                        }
                }

                addnav('Zurück');
                addnav("Zur Halle","tempel.php");

                break;

        case 'graveyard':

                page_header('Friedhof');

                output('`b`c'.TEMPELCOLORGRAVEYARD.'Auf dem Friedhof`b`c`n
                        '.TEMPELCOLORGRAVEYARD.'Im Schatten des Götterhauses - und damit nach Nordosten ausgerichtet - findest du einen einsamen, mit
                        Unkraut überwucherten Friedhof. Sein Alter ist ihm deutlich anzusehen; kaum einer
                        der vielen Grabsteine, die die Gräber der Toten markieren, ist noch in einem guten Zustand. So mancher liegt sogar umgekippt auf dem
                        Gesicht oder ist ganz und gar in Stücke zerbrochen. Dagegen gibt es jedoch auch das eine oder andere ausgehobene Loch im Boden, in welchem wohl
                        demnächst ein gerade erst gestorbener Bewohner '.getsetting('townname','Satu Mare').'s beerdigt werden soll.
                        `nIm hinteren Teil des Friedhofs ragen mehrere Mausoleen finster in den düsteren Himmel.
                        Die kleineren zu beiden Seiten scheinen abgeschlossen zu sein, doch beim größten in der Mitte steht die schwere Steintür einen
                        Spalt breit offen. Hineinschauen kannst du von hier draußen jedoch nicht. Alles, was du lediglich sehen kannst, ist kalte Schwärze.`n`n');

                viewcommentary("friedhof","Flüstern:",15,"flüstert");

                addnav('Mausoleum');
                addnav('Mausoleum betreten','tempel.php?op=mausoleum');
                addnav('Zurück');
                addnav('Zum Götterhaus','tempel.php');

                break;

        case 'mausoleum':

                page_header('Im Mausoleum');

                output('`b`c'.TEMPELCOLORMAUSOLEUM.'Im Mausoleum`b`c`n
                        '.TEMPELCOLORMAUSOLEUM.'Nachdem du das schwere Steintor des größten Mausoleums aufgeschoben hast,
                        erwartet dich eine große, aber kahle Kammer aus Marmor. Kälte umfängt dich, genauso wie es die undurchdringliche Dunkelheit tut,
                        die hier vorherrscht. Die Decke des Mausoleums ist in der Dunkelheit nicht auszumachen, und erst, als du das Mausoleum mit
                        hallenden Schritten halb durchquert hast, kannst du an der hinteren Wand ein steinernes Grab erkennen, das jedoch nicht verrät, wer
                        dort seine letzte Ruhe gefunden hat. Auf dem staubigen Deckel kannst du zwei Kerzenstummel
                        erkennen, an einer spinnt bereits eine Spinne ihr Netz. Insgesamt scheint hier schon lange niemand mehr gewesen zu sein.`n`n');

                viewcommentary("jarcath","Sagen:",25,"spricht");

                addnav('Zurück');
                addnav('Zum Friedhof','tempel.php?op=graveyard');
                addnav('Zum Götterhaus','tempel.php');

                break;

        case 'plate':

                page_header('Die Dreizehn');
                // Götter-Beschreibungstexte
                $str_vilia_desc = "`b`2Vilia - Göttin des Lebens, Lichtbringerin`b<br><br>`2Vilia ist Jarcaths Halbschwester sowie die ältere Schwester von Bylwen und Yldur. Sie besitzt einen kleinen Garten, in dem die unterschiedlichsten Tiere und Pflanzen leben und gedeihen. Um diese kümmert sich die Göttin mit viel Geduld und Hingabe und scheut dabei keine noch so große Anstrengung, um ihren Schützlingen ein Leben unter bestmöglichen Bedingungen zu ermöglichen.<br>Nicht viel vermag die Ausgeglichenheit der Göttin ins Wanken zu bringen; doch reißt Vilia dennoch einmal der Geduldsfaden, ist ihre Zerstörungswut gewaltig und sie findet erst dann wieder zu ihrer inneren Ruhe zurück, wenn die Schuldigen in ihren Augen keinen Schaden mehr anrichten können. Dies passierte zuletzt, als Vilia auf die Welt blickte, während diese von Jarcath beherrscht wurde: Der Anblick ließ den Zorn der Göttin überschäumen, doch da sie allein auf keinen Sieg über ihren Halbbruder hoffen konnte, bat sie Yldur und Bylwen um Hilfe, um Jarcath in die Unterwelt zu verbannen. Es folgte ein erbitterter Kampf, in dem Jarcath den drei Geschwistern schließlich unterlag und in die Unterwelt floh. Dadurch konnte das Licht seinen Weg in die Welt finden und Leben entstehen. Vilia gilt seitdem als Wächterin über die Welt der Lebenden, da sie fortwährend die Taten ihres Halbbruders beobachtet, stets bereit, ihn ein weiteres Mal zu vertreiben, sollte er die Welt zurückerobern wollen.<br>Vilia, Bylwen und Yldur stehen gemeinsam für die Beschaffenheit der Welt sowie für alles, was auf und in ihr lebt.<br><br>`i`2(Symbol: ein grüner Unendlichkeitsknoten)`i";
                $str_bylwen_desc = "`b`ZBylwen - Göttin der Natur`b<br><br>`ZBylwen ist die einzige Gottheit, deren Urform die eines Tieres ist: Als riesiger Braunbär durchstreift sie Wiesen und Wälder und wacht über das natürliche Gleichgewicht der Welt. Sie gebietet über all jene Elemente, die das Land formen und prägen, und nutzt ihre Stärke, um - ähnlich ihrer Schwester Vilia - das Leben in seiner ursprünglichen Form zu bewahren.<br>Bylwen halft einst auf Vilias Bitten hin, Jarcath in die Unterwelt zu verbannen, doch seitdem ist sie keinem Ruf der anderen Götter mehr gefolgt und bleibt stattdessen für sich. Lediglich Sanatras akzeptiert sie in ihrer Nähe, sollte er die Mühe auf sich nehmen, sie aufzusuchen und auf ihren Streifzügen zu begleiten.<br>Bylwen steht gemeinsam mit ihren Geschwistern Vilia und Yldur für die Beschaffenheit der Welt sowie für alles, was auf und in ihr lebt.<br><br>`i`Z(Symbol: der Umriss eines Bären, dessen Tatze zum nächsten Schritt erhoben ist)`i";
                $str_yldur_desc = "`b`3Yldur - Gott des Meeres`b<br><br>`3Das Meer ist das Spiegelbild für die Launen des Yldur: Mal erstreckt es sich ruhig bis zum Horizont und ermöglicht den Blick in weite Ferne, mal tobt und wütet es ohne Rücksicht, schickt Stürme aufs Land und reißt Unvorsichtige in die ewige Tiefe. Ebenso schwankend ist auch Yldurs Gemüt, doch trotz seines jähzornigen Charakters gilt der Gott als Freund der Irdischen, mit denen er seinen Reichtum an Wasser und Nahrung teilt. Man sagt allerdings, dass auf dem Grund des Meeres die wahren Schätze Yldurs verborgen liegen, beschützt von den unterschiedlichsten Kreaturen, sodass nur die Mutigsten Hoffnung auf Erfolg haben können, sollten sie sich auf Schatzsuche begeben.<br>Yldur steht gemeinsam mit seinen Geschwistern Vilia und Bylwen für die Beschaffenheit der Welt sowie für alles, was auf und in ihr lebt.<br><br>`i`3(Symbol: ein Anker umkreist von einer Welle)`i";
                $str_avantador_desc = "`b°#daa520;Avantador - Gott der Wahrheit und Gerechtigkeit`b<br><br>°#daa520;Avantador ist aufbrausend und liebt Debatten aller Art. Dabei kann es sich um ein die Welt betreffendes Problem oder um ein banales Thema handeln, an dem sich die Geister scheiden - in beiden Fällen diskutiert der Gott mit (gut vernehmbarer) Hingabe. Ein gegebenes Versprechen wird unter allen Umständen eingehalten, weshalb insbesondere Ferreth den Gott der Gerechtigkeit und Wahrheit als Gleichgesinnten wertschätzt.<br>Avantador ist mit Irune vermählt; zusammen repräsentieren sie die eheliche Harmonie, die entsteht, wenn sich zwei Wesen gegenseitig ergänzen (weshalb es bei Hochzeiten Brauch ist, um den Segen beider Götter zu bitten). Treten Avantador und Irune gemeinsam auf, weicht Avantador nicht von Irunes Seite und schenkt ihr seine Aufmerksamkeit in dem Maße, wie sie die ihre ihrer Umgebung zukommen lässt.<br><br>`i°#daa520;(Symbol: eine goldene Waage)`i";
                $str_irune_desc = "`b`)Irune - Göttin des Friedens und Gleichgewichts`b<br><br>`)Irune ist Ciardhas Schwester und besitzt ein ruhiges, besonnenes Gemüt. Sie ist eine aufmerksame Beobachterin und neigt dazu, die kleinen und großen Wünsche anderer bereits zu erkennen, noch ehe diese sie aussprechen. Ihre Fähigkeit macht Irune zu einer außergewöhnlich guten Gastgeberin, die sich darauf versteht, die Harmonie zwischen anderen Wesen aufrecht zu erhalten, selbst wenn sie noch so unterschiedlich sein sollten. Die Gefahr, sich selbst dabei aus den Augen zu verlieren, wird durch ihren Ehegatten Avantador gebannt, welcher das Wohl seiner Frau stets im Blick behält und deshalb mit ihr zusammen die eheliche Harmonie verkörpert, die entsteht, wenn sich zwei Wesen gegenseitig ergänzen (weshalb es bei Hochzeiten Brauch ist, um den Segen beider Götter zu bitten).<br>Aus Mitleid belegte Irune einst Rhav mit einem Zauber, der diese ihr Gegenstück finden lassen sollte, sodass sie nicht mehr länger allein bliebe. Dadurch fanden Rhav und Thoroka zueinander, die seitdem eine Ehe der Extreme führen. Um nie wieder einen ähnlichen Fehler zu begehen, bleibt Irune seither stumm.<br><br>`i`)(Symbol: ein schwarzer und ein weißer Fisch, welche sich gegenseitig verfolgen)`i";
                $str_ciardha_desc = "`b`^Ciardha - Göttin der Vielfalt`b<br><br>`^Ciardha ist quirlig, impulsiv und experimentierfreudig. Sie ist fasziniert von jenen Dingen, die sie nicht versteht, doch im Gegensatz zu Sanatras, der aus der Ferne beobachtet, sucht sie den direkten Kontakt, probiert aus und lernt die Welt auf diese Weise besser kennen. Ihre Neugier lässt sie gelegentlich Grenzen überschreiten, weshalb es ihrer Schwester Irune immer wieder zufällt, die Harmonie wiederherzustellen, die Ciardha durch ihre ungestüme Art ins Wanken gebracht hat.<br><br>`i`^(Symbol: eine gespreizte Hand aus bunten Flicken)`i";
                $str_thoroka_desc = "`b`4Thoroka - Gott des Kampfes und der Macht`b<br><br>`4Thorokas Stärke ist gleichzeitig seine größte Schwäche: Seine gute Auffassungsgabe und sein Gefühl für taktisches Vorgehen ermöglichen es ihm, Entscheidungen schnell zu treffen und entsprechend zügig zu handeln, doch gleichzeitig sind die Folgen umso verheerender, sollte sich der Gott des Kampfes und der Macht einmal irren und vorschnell agieren. Zudem ist Thoroka als Hitzkopf bekannt, der keiner Auseinandersetzung aus dem Weg geht - jedoch stets unter der Voraussetzung, dass es sich um einen Gegner handelt, den der Gott als seiner würdig einstuft. Er besitzt ein ausgeprägtes Ehrgefühl und verurteilt jene, die sich - insbesondere im Kampf - unehrenhaft verhalten. Ist man einmal bei ihm in Ungnade gefallen, wird es schwer, den Gott dazu zu bringen, seine Meinung wieder zu ändern.<br>Thoroka und Rhav sind wie Feuer und Wasser: Immer wieder kommt es zwischen ihnen zu Streit, insbesondere dann, wenn Rhav in Thorokas Handlungen eingreift, um Irdische zu retten, die durch sein Tun zu Schaden kommen würden, selbst wenn diese es nach Thorokas Ansicht aufgrund ihres Verhaltens verdient hätten. Ihre Ehe steht für die (intensive) Anziehung zwischen Gegensätzen, da die beiden Götter trotz aller Differenzen stets wieder zueinander finden.<br><br>`i`4(Symbol: ein gen Himmel gerichtetes Schwert)`i";
                $str_rhav_desc = "`b`VRhav - Göttin der Heilung und des Schlafs`b<br><br>`VRhav besitzt einen eigentümlichen Charakter, der sich dadurch äußert, dass sie Lebewesen als Objekte, nicht als Subjekte wahrnimmt. Sie kann auf ein umfangreiches Wissen über die Anatomien sämtlicher Lebewesen dieser Welt zurückgreifen, welches sie einsetzt, um das Leid anderer zu mindern und ihnen Ruhe und neue Kraft zu schenken. Rhav unterscheidet nicht zwischen Opfern und Tätern - ihre Aufmerksamkeit gilt einzig dem Leid eines Wesens und der Frage, wie man diesem entgegenwirken kann. Hierin findet sich der Grund für ihre - in den Augen anderer Gottheiten absonderliche - Freundschaft zu Jarcath, womit Rhav die einzige aller Götter ist, die Jarcath nicht wie einen Verstoßenen behandelt. Diese Freundschaft entstand, als Rhav Jarcath nach dem Kampf gegen seine Geschwister schwer verletzt fand und gesund pflegte.<br>Thoroka und Rhav sind wie Feuer und Wasser: Immer wieder kommt es zwischen ihnen zu Streit, insbesondere dann, wenn Rhav in Thorokas Handlungen eingreift, um Irdische zu retten, die durch sein Tun zu Schaden kommen würden, selbst wenn diese es nach Thorokas Ansicht aufgrund ihres Verhaltens verdient hätten. Ihre Ehe steht für die (intensive) Anziehung zwischen Gegensätzen, da die beiden Götter trotz aller Differenzen stets wieder zueinander finden.<br><br>`i`V(Symbol: ein geflügelter Stab, um den sich zwei Schlangen winden)`i";
                $str_ferreth_desc = "`b`9Ferreth - Gott des Handels, Handwerks und der Arbeit`b<br><br>`9Ferreth denkt rational und agiert geradlinig; seinem Charakter wird deshalb oftmals ein gewisses Maß an Kälte nachgesagt. Ein ins Auge gefasstes Ziel wird so lange verfolgt, bis es erreicht ist - diese Hartnäckigkeit ist gleichzeitig Stärke wie Schwäche des Gottes, lässt ihn jedoch hohes Ansehen bei Avantador genießen, selbst wenn diesem bewusst ist, dass Ferreth auch zu Listen greift, um seine Ziele zu erreichen.<br>Ferreths Aufmerksamkeit richtet sich auf das Wesentliche und Materielle. Er wurde u.a. deshalb einst von Zhudesh verflucht und ist seitdem blind.<br><br>`i`9(Symbol: ein Pferd vor einem vollbeladenen Wagen)`i";
                $str_zhudesh_desc = "`b`MZhudesh - Göttin der Liebe und des Glücks`b<br><br>`MMan sagt Zhudesh einen sprunghaften Charakter nach, der sich vor allem dadurch äußert, dass sie beiden Geschlechtern zugetan ist und deshalb mal als Frau, mal als Mann unter den Irdischen wandelt, um diese zu verführen. Sie besitzt ein Gespür für Rhetorik und liebt Schönes in all seinen Formen und Farben, weshalb sie auch als Göttin der Künste verehrt wird.<br>Ihre Neigung, sich über bedeutungsvolle Gesten auszudrücken, lieferte u.a. die Ursache für ihren Zwist mit Ferreth: Zhudesh unternahm einst einen Annäherungsversuch und schenkte dem Gott eines ihrer seltenen vierblättrigen Kleeblätter, doch Ferreth erkannte die Geste nicht und warf das Kleeblatt weg, da er darin nichts von Wert sah. Daraufhin rächte sich Zhudesh und verfluchte Ferreth mit Blindheit: `i`M\"Solange du die wahren Schätze dieser Welt verkennst, bist du ihres Anblicks nicht würdig.\"`i<br><br>`i`M(Symbol: ein Kleeblatt bestehend aus vier unterschiedlich geformten Blättern)`i";
                $str_sanatras_desc = "`b`*Sanatras - Gott des Wissens und der Weisheit`b<br><br>`*Sanatras bleibt oft für sich und ist damit kaum in das Beziehungsgeflecht der anderen Götter involviert. Auf diese Weise ist es ihm möglich, die Welt samt ihrer Wunder und Magie zu beobachten und von ihr zu lernen, wobei er jede seiner Erkenntnisse in Büchern festhält. Man sagt, dass die dadurch entstandene Bibliothek mittlerweile alles Wissen der Welt umfasst und deshalb so riesig ist, dass sich jeder außer Sanatras selbst in ihr verlaufen würde.<br>Der Gott sucht hin und wieder Bylwens Gesellschaft, da sie es ihm mit ihrer unkomplizierten, schweigsamen Art ermöglicht, sich wieder zu erden und wirre Gedanken zu ordnen. Die anderen Götter sehen in Sanatras einen Sonderling, doch gleichzeitig schätzen sie seine objektive Sicht auf die Dinge und suchen öfters seinen Rat. Selbst Hilox zollt dem Gott Respekt, da dieser als einziger in der Lage ist, den komplexen und oft verworrenen Gedankengängen des Gottes der Missordnung zu folgen.<br><br>`i`*(Symbol: ein aufgeschlagenes Buch, das links mit Text, rechts mit einem Abbild der Welt versehen ist)`i";
                $str_hilox_desc = "`b`CHilox - Gott der Missordnung`b<br><br>`CDas Herz eines Ritters und der Verstand eines Diebes - diese Kombination aus Tugend und Niedertracht macht Hilox zur wohl ambivalentesten Erscheinung unter den Gottheiten. Seine Loyalität gilt dem Verband der dreizehn Götter, dem er mit seinen unkonventionellen Ideen und seinem Tun zuarbeitet; doch gleichzeitig schreckt der Gott vor keinem Mittel und keinem Opfer zurück, welche es ihm ermöglichen, seine gesteckten Ziele zu erreichen. Seine Gedanken folgen oft verworrenen Pfaden, weswegen der Gott unter seinesgleichen als unberechenbar gilt und gemieden wird. Anders als Jarcath jedoch wird Hilox nicht aus dem Kreis der Götter ausgeschlossen, da ihm seine Skrupellosigkeit Wege eröffnet, die den anderen Göttern versperrt bleiben.<br>Von allen Gottheiten ist lediglich Sanatras in der Lage, Hilox\' komplexen Gedankengängen folgen zu können. Dadurch verdient sich der Gott immer wieder den Respekt des Gottes der Missordnung; jedoch würde Hilox selbst Sanatras ohne Reue hintergehen, würde dadurch der Erfolg seines Vorhabens gewährleistet sein.<br><br>`i`C(Symbol: ein geschwungener Wirbel aus schmalen, roten Linien)`i";
                $str_jarcath_desc = "`b`ÂJarcath - Gott des Todes und Schicksals`b<br><br>`ÂJarcath ist Vilias Halbbruder. Er herrschte zu Anfang der Zeit allein über die Welt, die unter seiner Regentschaft dunkel, trist und leblos war. Seit der Niederlage gegen Vilia, Bylwen und Yldur ist ihm der Zugang zur Welt der Lebenden verwehrt. Jedoch gelingt es Jarcath immer wieder, sich dem aufmerksamen Blick seiner Halbschwester zu entziehen und kurzzeitig in die lichte Welt zu gelangen, deren Wälder er dann durchstreift und studiert in der Hoffnung, einen Weg zu finden, wie er sich gegen seine Verbannung auflehnen und Vergeltung üben kann.<br>Jarcath kennt in seinem Handeln kein Erbarmen; jene, die seine Aufmerksamkeit auf sich ziehen, bestehen entweder seinen Test und verdienen sich seine Anerkennung - oder aber sie werden von ihm in die Unterwelt geholt und müssen dort ausharren, bis der Gott ihrer überdrüssig geworden ist.<br>Seine Vertreibung hatte zur Folge, dass sich auch die anderen Götter von Jarcath abwandten und ihn seitdem meiden. Lediglich Rhav bildet mit ihrer Freundschaft zu Jarcath eine Ausnahme: Sie fand ihn einst schwer verletzt in der Unterwelt und pflegte ihn gesund, wofür der Gott ihr bis heute dankbar ist. Den anderen Göttern hingegen begegnet er mit provozierender Verachtung.<br><br>`i`Â(Symbol: ein fliegender, schwarzer Rabe im Profil mit rotem Auge)`i";
                // end Beschreibungstexte
                // JS
                $str_js = "<script type='text/javascript' language='javascript'>
                           <!--
                           var gods_desc = {'vilia':'".$str_vilia_desc."','bylwen':'".$str_bylwen_desc."','yldur':'".$str_yldur_desc."','avantador':'".$str_avantador_desc."','irune':'".$str_irune_desc."','ciardha':'".$str_ciardha_desc."','thoroka':'".$str_thoroka_desc."','rhav':'".$str_rhav_desc."','ferreth':'".$str_ferreth_desc."','zhudesh':'".$str_zhudesh_desc."','sanatras':'".$str_sanatras_desc."','hilox':'".$str_hilox_desc."','jarcath':'".$str_jarcath_desc."'};
                           function showGodDesc(god) {
                             $('#god_desc_div').fadeOut('fast', function(){
                               $('#god_desc_div').html(gods_desc[god]);
                             });
                             $('#god_desc_div').fadeIn('slow');
                           }
                           //-->
                           </script>
                           ";
                // end JS
                output($str_js.'`b`c'.TEMPELCOLORHEAD.'Eranyas Götterkult der Dreizehn`b`c`n
                        '.TEMPELCOLORTEXT.'Dein Blick fällt auf eine graue Steintafel, welche zwischen zwei etwas weiter auseinander stehenden Fenstern an die Wand angebracht worden ist. In sie
                        wurde der Ring der Dreizehn eingemeißelt, die dem Glauben der Stadt zufolge das Leben ihrer Bürger beeinflussen und bewachen. Im Gegenzug gaben ihnen die
                        Einheimischen Namen und erzählen sich seit jeher Geschichten über sie. Jedoch sind diese dreizehn Götter nur einige von unzählig vielen, welche sich über die gesamte
                        Welt verteilen; wie sonst lässt es sich schließlich erklären, dass fortwährend Wesen nach Eranya kommen, die von fremden Göttern sprechen, gleichzeitig aber noch
                        nie von den hierorts verehrten Gottheiten gehört haben?`n
                        `n
                        `i(Ein Klick auf eines der Felder im Ring blendet eine kurze Beschreibung zur Gottheit ein.)`i`n
                        `n`n
                        <table><tr><td style="vertical-align: top;">
                        <div style="width: 540px; height: 540px; background-image: url(images/gods/steinkreis.png); position: relative;">
                          <img src="images/gods/vilia.png" alt="vilia" onClick="javascript:showGodDesc(\'vilia\');return false;"
                               style="position: absolute; left: 280px; top: 25px;">
                          <img src="images/gods/bylwen.PNG" alt="bylwen" onClick="javascript:showGodDesc(\'bylwen\');return false;"
                               style="position: absolute; right: 80px; top: 87px;">
                          <img src="images/gods/yldur.PNG" alt="yldur" onClick="javascript:showGodDesc(\'yldur\');return false;"
                               style="position: absolute; right: 30px; top: 182px;">
                          <img src="images/gods/avantador.PNG" alt="avantador" onClick="javascript:showGodDesc(\'avantador\');return false;"
                               style="position: absolute; right: 14px; top: 258px;">
                          <img src="images/gods/irune.PNG" alt="irune" onClick="javascript:showGodDesc(\'irune\');return false;"
                               style="position: absolute; right: 41px; bottom: 132px;">
                          <img src="images/gods/ciardha.png" alt="ciardha" onClick="javascript:showGodDesc(\'ciardha\');return false;"
                               style="position: absolute; right: 124px; bottom: 56px;">
                          <img src="images/gods/thoroka.PNG" alt="thoroka" onClick="javascript:showGodDesc(\'thoroka\');return false;"
                               style="position: absolute; right: 226px; bottom: 26px;">
                          <img src="images/gods/rhav.PNG" alt="rhav" onClick="javascript:showGodDesc(\'rhav\');return false;"
                               style="position: absolute; left: 137px; bottom: 41px;">
                          <img src="images/gods/ferreth.PNG" alt="ferreth" onClick="javascript:showGodDesc(\'ferreth\');return false;"
                               style="position: absolute; left: 56px; bottom: 111px;">
                          <img src="images/gods/zhudesh.PNG" alt="zhudesh" onClick="javascript:showGodDesc(\'zhudesh\');return false;"
                               style="position: absolute; left: 23px; bottom: 207px;">
                          <img src="images/gods/sanatras.PNG" alt="sanatras" onClick="javascript:showGodDesc(\'sanatras\');return false;"
                               style="position: absolute; left: 31px; top: 164px;">
                          <img src="images/gods/hilox.PNG" alt="hilox" onClick="javascript:showGodDesc(\'hilox\');return false;"
                               style="position: absolute; left: 100px; top: 78px;">
                          <img src="images/gods/jarcath.PNG" alt="jarcath" onClick="javascript:showGodDesc(\'jarcath\');return false;"
                               style="position: absolute; left: 173px; top: 30px;">
                        </div>
                        </td><td style="min-width: 30em; max-width: 55em;">
                        <div id="god_desc_div" style="margin: 3em; text-align: justify; display: none;"></div>
                        </td></tr></table>
                        `n');

                addnav('Zurück');
                addnav('Zur Halle','tempel.php');

                break;

        case 'sacristy':
        
                page_header('In der Sakristei');

                output(TEMPELCOLORSACRISTY."`c`bIn der Sakristei`b`c`n
                        Durch eine halb offen stehende Eichenholztür gelangst du in einen quadratischen Nebenraum, der sicherlich größer wirken würde, wäre er nicht so zugestellt. Vier große
                        Schränke nehmen zu deiner Rechten die Wandfläche komplett ein, und auch dir gegenüber stehen zwei große Aufbewahrungskisten direkt unter drei breiten Fenstern. Von
                        außen sind die Fenster vergittert. Der Grund hierfür steht in der hinteren linken Ecke: Dort entdeckst du einen weiteren, mit Stahl verstärkten Schrank, der jedoch
                        kleiner ausfällt als die anderen drei. Dafür gibt das große Schloss an den Flügeltüren jedem Betrachter ohne Umschweife zu verstehen, dass im Schrank aufbewahrten
                        Gegenstände nicht für unwissende Hände - oder gar neugierige Blicke - bestimmt sind.`n
                        Links neben dir steht ein breiter Tisch, auf dem unter anderem eine mit klarem Wasser gefüllte Waschschüssel steht. Gleich daneben liegen zwei sorgfältig gefaltete
                        Handtücher bereit. Zwei Holzstühle stehen neben dem Tisch mit der Lehne an die Wand. All dies erweckt den Eindruck, als würden die Priester dieses Hauses auf Ordnung
                        großen Wert legen.`n`n");

                viewcommentary("sacristy","Sagen:",15,"sagt");

                addnav('Zurück');
                addnav('Zur Halle','tempel.php');

                break;
        case 'tempel_inside':
                page_header('Zu den Räumen der Priester');
                output(TEMPELCOLORHEAD."`c`bZu den Räumen der Priester`b`c`n".TEMPELCOLORTEXT."
                        Die privaten Zellen der Priesterschaft sind genau so, wie man sie sich vorstellt. 
                        Die Einrichtung ist karg, jedoch von anständiger Qualität. Es gibt ein Bett, eine Kleidertruhe 
                        und einen Waschtisch samt Dreibein. Die Wände werden höchstens von Symbolen der Gottheiten, 
                        die hier verehrt werden, geschmückt. Ansonsten gibt es nichts, was auf die Bewohner der Zimmer 
                        hindeutet. Neben den Schlafräumen gibt es noch einen etwas größeren Saal, in welchem Besucher 
                        des Komplexes empfangen werden können. Auch dessen Einrichtung ist mit einem langen Tisch und 
                        entsprechenden Bänken zu beiden Seiten, wie einigen Lesepulten äußerst spartanisch gehalten, doch 
                        durch große Bogenfenster fällt viel Licht herein, sodass eine friedliche und freundliche Atmosphäre herrscht.`n`n");
                viewcommentary("tempel_inside","Sagen:",15,"sagt");
                addnav('Zurück');
                addnav('Nach draußen','tempel.php');
        break;

        case 'buycandle':

                page_header('Im Schein der Kerze');
                
                output(TEMPELCOLORTEXT."Du wirfst ".KERZE_PREIS." Gold in die Spendenkasse und zündest eine der bereitgestellten Kerzen an. Im warmen Schein der Flamme
                        dankst du den Göttern für jedes Wunder, das dir bereits widerfahren ist.`n`n");
                $session['user']['gold'] -= KERZE_PREIS;
                
                switch(e_rand(1,4)) {
                    // Keine Antwort:
                    case 1:
                    case 2:
                    case 3:
                        output(TEMPELCOLORTEXT."Eine Weile lang betrachtest du die brennende Kerze, doch dann wendest du dich wieder anderem zu.");
                    break;
                    // Die Götter antworten:
                    case 4:
                        output(TEMPELCOLORTEXT."Plötzlich verstärkt sich der Schein so sehr, dass du überrascht die Hand vors Gesicht hebst, um deine Augen zu schützen. Du willst
                                auch schon einen Schritt zurücktreten, doch da ist der Moment auch schon vorbei. Langsam lässt du die Hand wieder sinken und...`n`n");
                        switch(e_rand(1,13)) {
                            case 1: # Vilia                 + Blumentopf
                                output(TEMPELCOLORTEXT."..findest einen leeren Blumentopf neben dem Kerzentablett. `2Etwa ein Gruß von Vilia?");
                                item_add($session['user']['acctid'],'blmntpf');
                            break;
                            case 2: # Bylwen                + 1 WK
                                output(TEMPELCOLORTEXT."..findest einen Apfel neben dem Kerzentablett. `ZEtwa ein Geschenk von Bylwen? ".TEMPELCOLORTEXT."Du isst ihn und fühlst, wie dich neue Kraft durchströmt.");
                                $session['user']['turns']++;
                            break;
                            case 3: # Yldur                 + Lachs
                                output(TEMPELCOLORTEXT."..findest einen Lachs neben dem Kerzentablett. `3Etwa ein Gruß von Yldur?");
                                item_add($session['user']['acctid'],'fsh_lax');
                            break;
                            case 4: # Avantador             - 2 WK
                                output(TEMPELCOLORTEXT."..findest ein Buch neben dem Kerzentablett. Es handelt sich um einen spannenden Krimi, den du nicht mehr aus der Hand legen kannst, kaum dass du anfängst, darin zu lesen. Du vertrödelst einige Zeit, doch °#daa520;dieses Geschenk des Avantador war es allemal wert.");
                                $session['user']['turns'] -= 2;
                                $session['user']['turns'] = max(0,$session['user']['turns']);
                            break;
                            case 5: # Irune                 + 10% HP
                                output(TEMPELCOLORTEXT."..findest ein Glas mit Wasser neben dem Kerzentablett. Erst jetzt merkst du, dass du tatsächlich durstig bist. Du leerst das Glas in einem Zug und fühlst dich gleich viel besser. `3In Gedanken dankst du Irune für ihr Geschenk.");
                                $session['user']['hitpoints'] *= 1.1;
                            break;
                            case 6: # Ciardha               nix
                                output(TEMPELCOLORTEXT."..entdeckst, dass die Kerze, die du entzündest hast, auf einmal kunterbunt eingefärbt ist. `^Das ist wohl Ciardhas Art, dir Hallo zu sagen.");
                            break;
                            case 7: # Thoroka               - 25% HP + rostiger Dolch
                                output(TEMPELCOLORTEXT."..findest einen rostigen Dolch neben dem Kerzentablett. Irritiert greifst du danach und überprüfst die Klinge.. Autsch! So rostig ist sie wohl doch nicht. `4Etwas anderes hättest du von Thorokas Geschenken aber auch nicht erwartet.");
                                item_add($session['user']['acctid'],'rostdolch');
                                $session['user']['hitpoints'] *= 0.75;
                            break;
                            case 8: # Rhav                  + Beutel Heilkräuter
                                output(TEMPELCOLORTEXT."..findest einen Beutel mit Heikräutern neben dem Kerzentablett. `VEtwa ein Gruß von Rhav?");
                                item_add($session['user']['acctid'],'hlkrter');
                            break;
                            case 9: # Ferreth               + 1 ES
                                output(TEMPELCOLORTEXT."..findest einen Edelstein neben dem Kerzentablett. `9Etwa ein Gruß von Ferreth?");
                                $session['user']['gems']++;
                            break;
                            case 10: # Zhudesh              + 1 CP
                                output(TEMPELCOLORTEXT."..findest eine Glasscherbe neben dem Kerzentablett, in der du dich spiegelst. Du nutzt die Gelegenheit und wischst dir den Schmutz vom Gesicht, wodurch du gleich viel besser aussiehst. `MWenn das mal keine kleine Aufmerksamkeit von Zhudesh gewesen ist...");
                                $session['user']['charm']++;
                            break;
                            case 11: # Sanatras            + 1% XP
                                output(TEMPELCOLORTEXT."..findest eine Pergamentrolle neben dem Kerzentablett. Sie enthält eine Anleitung zu einer Atemtechnik, die sich bestimmt auch gut für den Kamof eignet. `*Etwa ein Geschenk von Sanatras?");
                                $session['user']['experience'] *= 1.01;
                            break;
                            case 12: # Hilox  `C           -
                                output(TEMPELCOLORTEXT."..findest eine rot bemalte Wackelkopffigur neben dem Kerzentablett, die etwa so groß ist wie dein Handteller. Von wem dieses Geschenk stammt, steht außer Frage - doch der Sinn will sich dir nicht erschließen. `CSchulterzuckend steckst du den grinsenden Miniatur-Hilox ein.");
                                item_add($session['user']['acctid'],'figurhilox');
                            break;
                            case 13: # Jarcath              - 1 ES
                                output(TEMPELCOLORTEXT."..hörst ein heiseres Lachen, gefolgt von wildem Flügelschlagen. Gerade noch siehst du einen `ÂRaben".TEMPELCOLORTEXT." durch die offen stehende Eingangstür hinausfliegen.");
                                if($session['user']['gems'] > 0) {
                                    output(" ..Moment! Hat er da etwa einen deiner Edelsteine im Schnabel? `ÂVerfluchter Jarcath!");
                                    $session['user']['gems']--;
                                }
                            break;
                        }
                    break;
                }

                addnav('Zurück');
                addnav('Zur Halle','tempel.php');

                break;

        default:
                output("`&Oh, was machst du denn hier? Sende bitte folgende Meldung via Anfrage an das E-Team:`n
                        `n
                        `^op:".$op.", is_priest:".$priest."`n
                        `n
                        `&Und nun schnell zurück zum Spiel!");
                addnav('Zurück');
                addnav("Zurück in die Stadt","village.php");
                break;

        }

page_footer();

// END tempel.php
?>

