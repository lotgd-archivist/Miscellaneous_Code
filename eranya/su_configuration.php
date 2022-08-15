
<?php
/**
 * su_configuration.php: Verwaltung der Spieleinstellungen
 * @author LOGD-Core / Drachenserver-Team
 * @version DS-E V/2
*/

require_once 'common.php';

su_check(SU_RIGHT_GAMEOPTIONS,true);

loadsettings();

output('`c`&`bSpieleinstellungen`b`c`n`n');

/**
 * Settings have to be saved
 */
if ($_GET['op']=='save')
{
    if ($_POST['blockdupeemail']==1) {$_POST['requirevalidemail']=1;}
    if ($_POST['requirevalidemail']==1) {$_POST['requireemail']=1;}

    output('Übernehme Änderungen...`n`n');

    $str_log = '';

    $arr_back = unserialize(urldecode(stripslashes($_POST['settings_back'])));
    unset($_POST['settings_back']);

    reset($_POST);
        foreach ($_POST as $key=>$val)
        {

                // Wenn jemand versucht, zu schummeln ; )
                if($key == 'sugroups') {
                        su_check(SU_RIGHT_RIGHTS,true);
                        continue;
                }

                $val = stripslashes($val);
                if($arr_back[$key] != $val) {

                        // Fürst ändern
                        if($key == 'townname') {
                                $str_oldtitle = 'Fürst von '.addslashes($settings[$key]);
                                $str_newtitle = '`&Fürst von '.addslashes($val);
                                $sql = 'SELECT acctid FROM account_extra_info WHERE ctitle LIKE "%'.$str_oldtitle.'%"';
                                $arr_acc = db_fetch_assoc(db_query($sql));

                                if(!empty($arr_acc['acctid'])) {
                                        $sql = 'UPDATE account_extra_info SET ctitle = "'.$str_newtitle.'" WHERE acctid='.$arr_acc['acctid'];
                                        db_query($sql);

                                        user_set_name($arr_acc['acctid']);
                                }
                        }

                savesetting($key,$val);
                $str_log .= $key.' => "'.$val.'"; ';
                }
    }

    if(!empty($str_log)) {
            systemlog('`3Veränderte Spieleinstellungen:`n'.$str_log,$session['user']['acctid']);
            output($str_log);
    }
    else {
            output('Keine Veränderungen vorgenommen, nichts gespeichert!');
    }
    addnav('Zurück zu den Spieleinstellungen',basename(__FILE__));
}



page_header('Spieleinstellungen');
addnav('G?Zurück zur Grotte','superuser.php');
addnav('W?Zurück zum Weltlichen',$session['su_return']);
addnav('',$REQUEST_URI);


$time = (strtotime(date('1981-m-d H:i:s',strtotime(date('r').'-'.getsetting('gameoffsetseconds',0).' seconds'))))*getsetting('daysperday',4) % strtotime('1981-01-01 00:00:00');
$time = gametime();


$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
$today = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time));
$dayduration = ($tomorrow-$today) / getsetting('daysperday',4);
$secstotomorrow = $tomorrow-$time;
$secssofartoday = $time - $today;
$realsecstotomorrow = round($secstotomorrow / getsetting('daysperday',4),0);
$realsecssofartoday = round($secssofartoday / getsetting('daysperday',4),0);
$enum='enum';

for ($i=0;$i<=86400;$i+=900)
{
    $enum.=",$i,".((int)($i/60/60)).":".($i/60 %60);
}

$weather_enum = 'radio';
foreach($weather as $id=>$w)
{
        $w['name'] = str_replace(',','',$w['name']);
        $weather_enum.=','.$id.','.$w['name'].'.';
}

$setup = array(

    'Servereinstellungen,title',
        'server_name'=>'Der Name des Servers|?Der logische Name des Servers. Wir ehren unsere liebe Dame Charly mit diesem Setting',
        'defaultlanguage'=>'Voreingestellte Sprache (z. Zt nur de),enum,en,English,dk,Danish,de,Deutsch,es,Espanol,fr,French',
        'locale'=>'Einstellung für lokal unterschiedl. Darstellungen (Zeit etc.; Keine = Server-Default),text,10|?Der Wert für explizit deutsche Darstellung lautet de_DE',
        'forum'=>'Link (URL) zum Forum',
        'gameadminemail'=>'Admin Email',
        'petitionemail'=>'Anfragen Email (Absender)',
        'paypalemail'=>'E-Mail Adresse für den PayPal Account des Admins',
        'LOGINTIMEOUT'=>'Sekunden Inaktivität bis zum automatischen Logout,int',

    'Spieleinstellungen,title',
        'wartung'=>'Wartungsmodus an,bool|?Um einzelne Accounts für den Wartungsmodus freizuschalten, kannst du die Rechtesektion im Usereditor verwenden.',
        'blocknewchar'=>'Neuanmeldungen sperren?,bool',
        'loginbanner'=>'Login Banner (unterhalb der Login-Aufforderung; 255 Zeichen)',
        'impressum'=>'Server betrieben von: (255 Zeichen)',
        'defaultskin'=>'Standardskin ( + .htm(l) )',
        'townname'=>'Name der Stadt:',
        'teamname'=>'Bezeichnung des Administrationsteams:',
        'soap'=>'Userbeiträge säubern (filtert Gossensprache und trennt Wörter mit über 45 Zeichen),bool',
        'maxonline'=>'Maximal gleichzeitig online (0 für unbegrenzt),int',
        'emailonmail'=>'Email-Benachrichtigung bei Hörnchenpost-Eingang zulassen?,bool',
        'automaster'=>'Meister jagt säumige Lehrlinge,bool',
        'multimaster'=>'Meister kann mehrmals pro Spieltag herausgefordert werden?,bool',
        'beta'=>'Beta-Features für alle Spieler aktivieren?,bool',
        'limithp'=>'Lebenpunkte maximal Level*12+5*DPinHP+x*DK (0=deaktiviert),int',
        'autofight'=>'Automatische Kampfrunden ermöglichen,bool',
        'witchvisits'=>'Erlaubte Besuche bei der Hexe,int',
        'dailyspecial'=>'Heutiges besonderes Ereignis',
        'enable_commentemail'=>'User dürfen Chatmitschnitte an ihre Mail senden,bool',
        'enable_modcall'=>'"Mod rufen"-Button unter Chats anbieten,bool',
        'ci_goldpresse'=>'Goldpresse aktiv,bool',

    'Die Schenke,title',
        'maxales'=>'Maximale Anzahl Ale die bei einer "Runde" spendiert werden kann,int',
        'paidales'=>'Ale das als "Runde" spendiert wurde (Wert-1),int',
        'dragonmind_game'=>'Dragonmind Spiel aktivieren,bool',
        'memory_game'=>'Memory Spiel aktivieren,bool',

    'Büro des Stadtrats,title',
        'council'=>'Stadtrat,viewonly',
        'taxrate'=>'Derzeitiger Steuersatz,int',
        'mintaxes'=>'Mindeststeuersatz,int',
        'maxtaxes'=>'Höchstmöglicher Steuersatz,int',
        'taxprison'=>'Derzeitige Anzahl Kerkertage für Steuerhinterziehung,int',
        'maxprison'=>'Höchststrafe für Steuerhinterziehung,int',
        'callvendormax'=>'Stadtrat kann wie oft in einer Amtsperiode den Wanderhändler holen,int',
        'beggarmax'=>'Maximales Fassungsvermögen des Bettelsteins,int',
        'maxbudget'=>'Maximale Größe der Staatskasse,int',
        'maxamtsgems'=>'Maximale Anzahl an Edelsteinen in den Tresoren,int',
        'lurevendor'=>'Kosten um den Wanderhändler anzulocken,int',
        'freeorkburg'=>'Kosten um die Orkburg freizulegen,int',

    'Account-Erstellung,title',
        'newplayerstartgold'=>'Gold mit dem ein neuer Char startet,int',
        'requireemail'=>'E-Mail Adresse beim Anmelden verlangen,bool',
        'requirevalidemail'=>'E-Mail Adresse bestätigen lassen,bool',
        'blockdupeemail'=>'Nur ein Account pro E-Mail Adresse,bool',
        'spaceinname'=>'Erlaube Leerzeichen in Benutzernamen,bool',
        'specialkeys'=>'Erlaube Sonderzeichen in Benutzernamen,bool',
        'criticalchars'=>'Zeichen die nicht in Namen vorkommen dürfen (regulärer Ausdruck /[..Eingegebene Zeichen..]/)!),text,100',
        'allletter_up_allow'=>'Namen nur in Großbuchstaben erlauben,bool',
        'firstletter_up'=>'Erster Buchstabe immer in Großschreibung,bool',
        'name_casechange'=>'Änderung der Groß-/Kleinschreibung des Namens in Jägerhütte erlauben,bool',
        'nameminlen'=>'Mindestlänge für Login in Zeichen (Ohne Farbcodes)',
        'namemaxlen'=>'Maximallänge für Login in Zeichen (Ohne Farbcodes)',
        'titleminlen'=>'Mindestlänge für eigenen Titel in Zeichen (Ohne Farbcodes)',
        'titlemaxlen'=>'Maximallänge für eigenen Titel in Zeichen (Mit Farbcodes)',
        'name_maxcolors'=>'Maximalanzahl an Farbcodes im Namen,int',
        'title_maxcolors'=>'Maximalanzahl an Farbcodes in eigenem Titel,int',
        'selfdelete'=>'Erlaube den Spielern ihren Charakter zu löschen,bool',
        'avatare'=>'Erlaube den Spielern Avatare zu verlinken,bool',
        'refererdp'=>'DP für eine Anwerbung,int',
        'refererminlvl'=>'Mindestlvl für Anwerbungs-DP,int',
        'referermindk'=>'MindestDK für Anwerbungs-DP,int',
        'race_change_allowed'=>'Rassenwechsel in der Schenke erlauben,bool',
        'unaccepted_namechange'=>'Abgelehnte Namen werden geändert zu -unzulässiger Name xxx-,bool',
    'Schnupperzugang,divider',
        'demouser_public'=>'Test-Zugang verfügbar?,bool',
        'demouser_acctid'=>'Account-ID des Testzugangs (Vorsicht!),int,|?Hier bietet sich die ID eines gelöschten Spielers an um Inkonsistenzen mit der DB zu vermeiden.',
        'demouser_last_IP'=>'IP des letzten Nutzers,viewonly',

    'Berufe,title',
        'numberofguards'=>'Maximale Zahl an Stadtwachen',
        'numberofpriests'=>'Maximale Zahl an Priestern',
        'numberofmages'=>'Maximale Zahl an Magiern',
        'numberofjudges'=>'Maximale Zahl an Richtern',
        'guardreq'=>'Nötige DKs um Stadtwache zu werden',
        'judgereq'=>'Nötige DKs um Richter zu werden',
        'priestreq'=>'Nötige DKs um Priester / Hexer zu werden',
        'guard_max_imprison'=>'Max. Anzahl an Stadtwacheneinkerkerungen pro Spieltag und Wache',

    'Stadtfest,title',
        'lastparty'=>'Wann war das letzte Bürgerfest',
        'min_party_level'=>'Wieviel Geld muss für eine Party vorhanden sein,int',
        'amtskasse'=>'Gold in der Amtskasse,int',
        'party_duration'=>'Wieviele Spieltage soll das Stadtfest dauern (1;2;0.5;...),int',

    'Mods/Events-Einstellungen,title',
        'rebirth_dks'=>'Nötige DKs für Erneuerung',
        'wallchangetime'=>'Geschmiere an der Mauer kann erst nach x Sekunden geändert werden,int',
        'maxsentence'=>'Höchststrafe in Spieltagen',
        'locksentence'=>'Spieltage im Kerker ab denen es Sicherheitsverwahrung gibt',
        'user_rename'=>'Preis in DP für Namensänderung (auch nach Erneuerung/Wiedergeburt)',
        'deathjackpot'=>'Derzeitiger Stand des Tot-o-Lotto Jackpots,int',
        'deathjackpotmax'=>'Maximaler Stand des Tot-o-Lotto Jackpots,int',
    'Junior-Juni,divider',
        'juniorjuni_aktiv'=>'Aktion Junior-Juni aktiv?,bool',
        'juniorjuni_referraldp'=>'Anzahl DP für angeworbenen Charakter (Junior-Juni),int|?Sobald der Charakter das 5. Level erreicht hat, bekommt er im Rahmen der Junior-Juni-Aktion x DP gutgeschrieben als Starthilfe',
    'Bibliothek,divider',
        'libdp'=>'Max. vergebbare Donationpoints pro angenommenem Buch,int',
        'funda_editiersperre_dauer'=>'Dauer der Editiersperre bei FundA-Beiträgen (in min),int',
    'Halloween-Special,divider',
        'halloween_special'=>'Halloween-Special aktiv,bool|?Wenn aktiv, können Kürbismünzen und Hexentaler gesammelt werden',
        'halloween_special_coins'=>'Kürbismünzen- und Hexentaler-Verkauf aktiv,bool|?Wenn aktiv, kann beides bei Scytha verkauft werden und wird in der Vitalinfo angezeigt',
        'halloween_special_lodge'=>'Verändern/Umfärben von Titel und Namen kostenlos anbieten? <b>Achtung!</b>,bool|?<b>Achtung!!</b> Nur aktivieren, wenn ursprüngliche Namen und Titel vorher über das entsprechende tool in der Grotte gespeichert wurden',
    
    'RP/Kommentare/Bios,title',
        'commentsizelimit'=>'Maximale Länge eines Kommentars,int',
        'maxcolors'=>'Maximale Anzahl erlaubter Farbwechsel (0 = unbegrenzt),int',
        'comedit_maxdays'=>'Zeitlimit für Editierbarkeit (in Tagen),int',
        'comedit_maxposts'=>'Anzahl der Kommentare die editierbar sind,int|?Das System rechnet vom zuletzt gesetzten Kommentar rückwärts. Es zählen nur solche, die ohne Unterbrechung (!) von ein und demselben User geschrieben worden sind.',
    'RP-Belohnung,divider',
        'rpdon_dpcomment'=>'DP für einen Kommentar ausr. Länge,int|?Angabe von Dezimalstellen möglich; 0: deaktiviert.',
        'rpdon_minlen'=>'Mindestlänge für Kommentar (Wörteranzahl),int',
        'rpdon_mincomments'=>'Mindestanzahl an Kommentaren ehe Belohnung erfolgt,int',
        'rpdon_sections'=>'Chat sections in denen Kommentare `bnicht`b gezählt werden,textarea,40,5|?Durch Kommata getrennt; interne Bezeichner(!). section,section,...',
    'Bio-Verwaltung,divider',
        'longbiomaxlength'=>'Maximale Zeichenanzahl d. Charbio,int',
        'longbio_moreletters'=>'Extra Zeichen für Charbio,int|?für x RPP beim Zaven freischaltbar',
        'discbiomaxlength'=>'Maximale Zeichenanzahl d. Knappenbio,int',
        'mountbiomaxlength'=>'Maximale Zeichenanzahl d. 1. X-Charbio,int',
        'xcharbiomaxlength'=>'Maximale Zeichenanzahl d. 2. X-Charbio,int',
        'addbiomaxlength'=>'Maximale Zeichenanzahl d. zusätzl. Textfelds,int',

    'Neue Spieltage,title',
        'fightsforinterest'=>'Höchste Anzahl an übrigen Waldkämpfen um Zinsen zu bekommen,int',
        'maxinterest'=>'Maximaler Zinssatz (%),int',
        'mininterest'=>'Minimaler Zinssatz (%),int',
        'daysperday'=>'Spieltage pro Kalendertag,int',
        'dispnextday'=>'Zeit zum nächsten Spieltag in Vital Info anzeigen?,bool',
        'specialtybonus'=>'Zusätzliche Einsätze der Spezialfertigkeit pro Spieltag,int',
        'activategamedate'=>'Spieldatum aktiv,bool',
        'gamedateformat'=>'Datumsformat (zusammengesetzt aus: %Y; %y; %m; %n; %d; %j)',
        'gametimeformat'=>'Zeitformat',
        'rpyear'=>'Jahreszahl für das RP-Datum (Stadtplatz - Marktplatz - Hafen),int',

    'Wald,title',
    'Waldkampf-Einstellungen,divider',
        'turns'=>'Waldkämpfe pro Spieltag,int',
        'dropmingold'=>'Waldkreaturen lassen mindestens 1/4 des möglichen Goldes fallen,bool',
        'lowslumlevel'=>'Mindestlevel bei dem perfekte Kämpfe eine Extrarunde geben,int',
        'forestbal'=>'Prozentsatz der pro perfektem Kampf auf Monsterstärke aufgeschlagen wird',
        'forestdkbal'=>'Prozentsatz mit dem Drachenpunkteeinfluss auf Monsterstärke multipliziert wird',
        'foresthpbal'=>'Zahl durch die max. LP geteilt werden ehe sie auf DP-Einfluss addiert werden',
    'Waldgegner und Grüner Drache,divider',
        'forest_fightagainstchars'=>'Schmankerl: Sollen Waldmonster/Grüner Drache durch Charaktere/Admins ersetzt werden?,bool|?<u>Wald:</u> Man kämpft gegen Charaktere desselben Levels wie eigener Char (oder gegen reguläre Waldmonster wenn kein anderer Char dasselbe Level hat) - Multis inklusive<br><u>Grüner Drache samt Sprössliche:</u> wird ersetzt durch Admins & Mods',
        'forest_fightagainstspecialmonsters'=>'Schmankerl: Sollen Waldmonster durch spezielle Monster ersetzt werden?,bool|?<u>Wald:</u> Man kämpft gegen vorher über den Waldmonster-Editor festgelegte Monster, die sonst nicht im Wald auftauchen',

    'Schloss,title',
        'castle_turns_wk'=>'Anzahl an WKs die man für eine Schlossrunde erhält,int',
        'wk_castle_turns'=>'Anzahl an WKs die eine Schlossrunde kostet,int',
        'castle_turns'=>'Schlossrunden pro Spieltag ,int',
        'castlegemdesc'=>'Abweichung vom max. Edelsteingewinn / Runde über dem max.,int',
        'castlegolddesc'=>'Abweichung vom max. Goldgewinn / Runde über dem max.,int',

    'Gilden,title',
    'Gildengründung,divider',
        'dgguildmax'=>'Max. Anzahl an Gilden,int',
        'dgguildfoundgems'=>'Gems zur Gründung,int',
        'dgguildfoundgold'=>'Gold zur Gründung,int',
        'dgguildfound_k'=>'DKs zur Gründung,int',
        'dgstartgold'=>'Startgold,int',
        'dgstartgems'=>'Startgems,int',
        'dgstartpoints'=>'StartGP,int',
        'dgstartregalia'=>'Startinsignien,int',
    'Mitglieder & Allgemeines,divider',
        'dgmaxmembers'=>'Max. Mitgliederzahl ohne Ausbauten,int',
        'dgminmembers'=>'Min. Mitgliederzahl,int',
        'dgmindkapply'=>'Mindestanzahl an DKs für Mitgliedschaft,int',
        'dgplayerfights'=>'Max. Kämpfe eines Spielers gegen Gildenwachen pro Spieltag,int',
        'dgimmune'=>'Spieltage Immunität für eine neu gegründete Gilde,int',
        'dgbiomax'=>'Max. Zeichenanzahl der Bio,int',
    'Steuern,divider',
        'dgtaxdays'=>'Alle x Spieltage Steuern,int',
        'dgmaxtaxfails'=>'x mal Steuern nicht zahlen damit Gilde aufgelöst,int',
        'dgtaxgold'=>'Basis-Goldkosten der Steuer,int',
        'dgtaxgems'=>'Basis-Gemkosten der Steuer,int',
    'Gildenfinanzen,divider',
        'dgmaxgemstransfer'=>'Max. Edelsteinauszahlung pro Lvl,int',
        'dgmaxgoldtransfer'=>'Max. Goldauszahlung pro Lvl,int',
        'dgmaxgoldin'=>'Max. Goldeinzahlung pro Spieltag,int',
        'dgmaxgemsin'=>'Max. Gemeinzahlung pro Spieltag,int',
        'dgtrsmaxgold'=>'Max Gold in Schatzkammer,int',
        'dgtrsmaxgems'=>'Max Gems in Schatzkammer,int',
        'dgminmembertribute'=>'Mindesttribut der Mitglieder in %,int',
        'dggpgoldcost'=>'Kosten eines GP in Gold,int',
        'dgminregaliaval'=>'Min. Preis / Insignie in GP,int',
        'dgmaxregaliaval'=>'Max. Preis / Insignie in GP (sinkt pro halbe Insignie die im Durchschnitt mehr verkauft wurde um 1),int',
    'Sonstiges,divider',
        'dg_invent_out_price'=>'Faktor mit dem der Wert eines Items beim Auslagern aus Gildeninventar multipliziert wird um die Gebühr zu ermitteln,int',
        'dggetcompoints'=>'Wahrscheinlichkeit für 1 GP pro Post an öffentlichen RP-Orten - Chance: 1:X (0 = deaktiviert); X = ,int',

    'Kopfgeld,title',
        'bountymin'=>'Mindestbetrag pro Level der Zielperson,int',
        'bountymax'=>'Maximalbetrag pro Level der Zielperson,int',
        'bountylevel'=>'Mindestlevel um Opfer sein zu können,int',
        'bountyfee'=>'Gebühr für Erik Dinivan in Prozent,int',
        'maxbounties'=>'Anzahl an Kopfgeldern die ein Spieler pro Spieltag aussetzen darf,int',

    'Handelseinstellungen,title',
    'Bank / Geldtransfer,divider',
        'borrowperlevel'=>'Maximalwert den ein Spieler pro Level leihen kann (Bank),int',
        'maxinbank'=>'+/- Maximalbetrag für den noch Zinsen bezahlt/verlangt werden,int',
        'bankmaxgemstrf'=>'Max. Anzahl an Gemüberweisungen / Spieltag,int',
        'allowgoldtransfer'=>'Erlaube Überweisungen (Gold und Edelsteine),bool',
        'transferperlevel'=>'Maximalwert den ein Spieler pro Level empfangen oder nehmen kann,int',
        'mintransferlev'=>'Mindestlevel für Überweisungen (bei 0 DKs),int',
        'bankgemtrflvl'=>'Minimallvl um Edelsteinüberweisungen empfangen zu können,int',
        'maxtransferout'=>'Menge die ein Spieler an andere überweisen kann (Wert x Level),int',
        'innfee'=>'Gebühr für Expressbezahlung in der Kneipe (x oder x%),int',
    'Wanderhändler,divider',
        'vendor_grlagertruhe_inhalt'=>'In die große Lagertruhe passen x zusätzl. Items,int|?Ganze Zahl eintragen.<br><b>Wichtig!</b> Bei Änderung muss auch die Itembeschreibung über den Item-Editor angepasst werden.',
        'vendor_kllagertruhe_inhalt'=>'In die kleine Lagertruhe passen x zusätzl. Items,int|?Ganze Zahl eintragen.<br><b>Wichtig!</b> Bei Änderung muss auch die Itembeschreibung über den Item-Editor angepasst werden.',
    'Sonstiges,divider',
        'selledgems'=>'Edelsteine die Scytha vorrätig hat,int',
        'paidgold'=>'Gold das in Bettlergasse spendiert wurde,int',

    'Häuser,title',
        'housemaxgemsout'=>'Max. Anzahl an Edelsteinen / Spieltag aus Haus entnehmbar,int',
        'newhouses'=>'Bauen neuer Häuser möglich ?,bool',
        'maxhouses'=>'Maximale Anzahl an Häusern ?,int',
        'housegetdks'=>'Min. DKs für Häuserbau / kauf?,int',
        'housekeylvl'=>'Min. Lvl (bei 0 DKs) für Schlüsselvergabe?,int',
        'houseextdks'=>'Min. DKs für Hausausbau?,int',
        'houseextsellenabled'=>'Ausgebaute Häuser zum Verkauf anbieten?,bool',
        'housegetdks'=>'Min. DKs für Häuserbau / kauf?,int',
        'housekeylvl'=>'Min. Lvl (bei 0 DKs) für Schlüsselvergabe?,int',
        'houseextdks'=>'Min. DKs für Hausausbau?,int',
        'houseextsellenabled'=>'Ausgebaute Häuser zum Verkauf anbieten?,bool',
        'housetrsshare'=>'Bei Schlüsselabnahme Teil aus Hausschatz an Betroffenen?,bool',
        'housedesclen'=>'Max. Länge für Hausbeschreibung?,int',

    'Hörnchenpost + E-Mail,title',
    'Hörnchenpost,divider',
        'mailsizelimit'=>'Maximale Anzahl an Zeichen in einer Nachricht,int',
        'inboxlimit'=>'Anzahl an Nachrichten in der Inbox,int',
        'modinboxlimit'=>'Dergleichen für MODs,int',
        'oldmail'=>'Alte Nachrichten automatisch löschen nach x Spieltagen. x =,int',
        'modoldmail'=>'Dergleichen für MODs,int',
        'show_yom_contacts'=>'Zeige das Adressbuch in der YOM an,bool',
        'max_yom_contacts'=>'Maximale Anzahl an YOM Kontakten,int',
        'message2mail_activated'=>'Dürfen YoMs per Mail archiviert werden?,bool',
    'E-Mail-Einstellungen,divider',
        'mail_define_mta'=>'Mail Transfer Agent,int|?0 = Mail<br>1 = SMTP<br>2 = QMail<br>3 = Sendmail',
        'mail_external_mail_smtp_encryption'=>'SMTP-Verschlüsselungsprotokoll,text|?SSL oder TLS',
        'mail_external_smtp_host'=>'SMTP-Host,text|?z.B. ssl://smtp.gmail.com',
        'mail_external_mail_server_port'=>'SMTP-Port,int|?SSL: Port 465<br>TLS: Port 587',
        'mail_enable_smtp_auth'=>'SMTP-Authentifizierung aktiv?,bool',       
        'mail_external_mail_server_user'=>'Loginname für das externe E-Mail-Konto',
        'mail_external_mail_server_pass'=>'Loginpasswort für das externe E-Mail-Konto',

    'PvP,title',
        'pvp'=>'Spieler gegen Spieler aktivieren,bool',
        'pvpday'=>'Spielerkämpfe pro Spieltag,int',
        'pvpimmunity'=>'Spieltage die neue Spieler vor PvP sicher sind,int',
        'pvpminexp'=>'Mindest-Erfahrungspunkte für PvP-Opfer,int',
        'pvpattgain'=>'Prozentsatz der Erfahrung des Opfers den der Angreifer bei Sieg bekommt,int',
        'pvpattlose'=>'Prozentsatz an Erfahrung den der Angreifer bei Niederlage verliert,int',
        'pvpdefgain'=>'Prozentsatz an Erfahrung des Angreifers den der Verteiger bei einem Sieg gewinnt,int',
        'pvpdeflose'=>'Prozentsatz an Erfahrung den der Verteidiger bei Niederlage verliert,int',
        'pvpmindkxploss'=>'DKs Unterschied zwischen Täter und Opfer bis zu dem noch 0% XP abgezogen werden,int',

    'Inhalte löschen (0 für nie löschen),title',
        'lastcleanup'=>'Datetime der letzten Säuberung',
        'cleanupinterval'=>'Sekunden zwischen Säuberungen,int',
        'expirecontent'=>'Tage die Kommentare und News aufgehoben werden,int',
        'expiretrashacct'=>'Tage die Accounts gespeichert werden die nie eingeloggt waren,int',
        'expirenewacct'=>'Tage die Level 1 Accounts ohne Drachenkill aufgehoben werden,int',
        'expirevacationacct'=>'Tage die Accounts im Urlaubsmodus aufgehoben werden,int',
        'expireoldacct'=>'Tage die alle anderen Accounts aufgehoben werden,int',
        'expire_sendmail_before'=>'Anzahl der Tage vor der Löschung an dem eine Erinnerungsmail geschrieben wird,int',
        'vacation_ban_time'=>'Tage Mindestabwesenheit für Urlaubsmodus (Systembann),int',

    'Nützliche Informationen,title',
        'weather'=>'Heutiges Wetter:,'.$weather_enum,
        'newplayer'=>'Neuster Spieler',
        'Letzter neuer Spieltag: '.date('h:i:s a',strtotime(date('r').'-$realsecssofartoday seconds')).',viewonly',
        'Nächster neuer Spieltag: '.date('h:i:s a',strtotime(date('r').'+$realsecstotomorrow seconds')).',viewonly',
        'Aktuelle Spielzeit: '.date('G:i').',viewonly',
        'Spieltag-Länge: '.($dayduration/60/60).' Stunden,viewonly',
        'Aktuelle Serveruhrzeit: '.date('Y-m-d h:i:s a').',viewonly',
        'gameoffsetseconds'=>'Offset der Spieltage,$enum',
        'gamedate'=>'aktuelles Spieldatum (Y-m-d)',
        'exsearch_limit'=>'x Suchen für die Offlinesuche,int|?Wie oft darf in der Spielerliste gesucht werden wenn man nicht online ist.',
        'exsearch_time'=>'x Minuten Wartezeit bei Offlinesuche,int|?Wie lange muss man warten bevor man wieder die Spielerliste offline durchsuchen darf.',

    'LoGD-Netz Einstellungen (file wrappers müssen in der PHP Konfiguration aktiviert sein!!),title',
        'logdnet'=>'Beim LoGD-Netz eintragen?,bool',
        'serverurl'=>'Server URL',
        'serverdesc'=>'Serverbeschreibung (255 Zeichen)',
        'logdnetserver'=>'LoGD-Netz Zentralserver (Default: http://lotgd.net/)',
        'logdnetserver_domain'=>'LoGD-Netz Zentralserver Domain (Default: lotgd.net)',

    'Forum + IRC Einstellungen,title',
        'ci_active'=>'Passierschein aktiv,bool',
        'ci_dk'=>'Anzahl der Dk für Passierschein?,int',
        'ci_su'=>'Superuser level >=,enum,0,0,1,1,2,2,3,3,4,4,5,5',
        'ci_dk_mail_active'=>'Mail bei Drachenkill?,bool',
        'ci_dk_mail_head'=>'Überschrift der Mail (Betreff)',
        'ci_dk_mail_text'=>'Text der Mail,textarea,30,5',
        'ci_std_pw_active'=>'Standard Passwort,bool',
        'ci_std_pw'=>'Standard Passwort (Klartext)',
        'ci_std_pwc'=>'Standard Passwort (so wie es eingetragen wird)',

    'irc_active'=>'IRC aktiv,bool',
        'irc_su_minlevel'=>'Superuser minLevel|?numerischer Wert wie in security.php definiert',
        'irc_appletserver'=>'Server wo pJirc liegt|?z.B. http://irc.bbox.ch/',
        'irc_server'=>'IRC-Server|?z.B. irc.bbox.ch',
        'irc_server_port'=>'Port (6667)',
        'irc_channel'=>'IRC-Raumname (#atrahor)',
        'irc_cpw'=>'Raum-Passwort|?muß angegeben werden um den Raum zu betreten wenn Raum auf +k steht',


    'RSS Einstellungen,title',
        'rss_enable_motd_feed'=>'Automatisch RSS feed für MOTD erstellen?,bool',
        'rss_item_count'=>'Default Anzahl der RSS Items,int',
        'rss_title'=>'Titel des RSS Feeds',
        'rss_description'=>'Beschreibung des RSS Feeds',
        'rss_link'=>'Wohin linken die RSS Items',
        'rss_image'=>'Link zu einem Bild das den Feed beschreibt',
        'rss_file_abs_path'=>'Absoluter Pfad zum MOTD RSS File|?',
        'rss_file_rel_path'=>'Relativer Pfad zum MOTD RSS File|?relativer Pfad vom Spiel aus gesehen, also bswp'
);



if ($_GET['op']=='')
{

        $output .= '<form action="su_configuration.php?op=save" method="POST">
                                <input type="hidden" name="settings_back" value="'.urlencode(serialize($settings)).'">';
    addnav('','su_configuration.php?op=save');

    showform($setup,$settings);

    $output .= '</form>';
}

page_footer();
?>

