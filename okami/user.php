
<?php
/**
* user.php: Zentrales Werkzeug für Superuser, um Spieleraccounts zu bearbeiten und zu verwalten
* @author Standardrelease by MightyE / Anpera, überarbeitet by talion <t [-[at]-] ssilo.de>
* @version DS-E V/2
*/

require_once 'common.php';
require_once(LIB_PATH.'dg_funcs.lib.php');
require_once(LIB_PATH.'profession.lib.php');

$access_control->su_check(access_control::SU_RIGHT_EDITORUSER,true);

function editnav ()
{
    global $row, $access_control;

    addnav('Aktionen');
    if ($_GET['returnpetition']!='')
    {
        addnav('Zurück zur Anfrage','su_petitions.php?op=view&id='.$_GET['returnpetition']);
    }
    //addnav('In memoriam: 2. Aktualisieren-Button');
    //addnav('Aktualisieren','user.php?op=edit&userid='.$_GET['userid']);

    //addnav('Kontrolle');
    addnav('Verbannen','su_bans.php?op=edit_ban&ids[]='.$row['acctid']);
    addnav('Letzten Output anzeigen','user.php?op=lasthit&userid='.$_GET['userid'],false,true);
    if($access_control->su_check(access_control::SU_RIGHT_DEBUGLOG))
    {
        addnav('Debug-Log anzeigen','su_logs.php?op=search&type=debuglog&account_id='.$_GET['userid']);
    }
    if($access_control->su_check(access_control::SU_RIGHT_MAILBOX)) {
        addnav('Mailbox zeigen','su_mails.php?op=search&to_id='.$_GET['userid']);
    }
    if($access_control->su_check(access_control::SU_RIGHT_COMMENT)) {
        addnav('Kommentare','su_comment.php?account_id='.$_GET['userid'],false,true);
    }
    if($access_control->su_check(access_control::SU_RIGHT_EDITORITEMS)) {
        addnav('Inventar','su_item.php?what=items&acctid='.$_GET['userid']);
    }
    if ($row['house'] && $access_control->su_check(access_control::SU_RIGHT_EDITORHOUSES) ){
        addnav("Zum Hausmeister","su_houses.php?op=edit&id=".$row['house']);
    }
    if($access_control->su_check(access_control::SU_RIGHT_UPLOADCONTROL)) {
        addnav('Uploads','su_pic_control.php?op=single&id='.$_GET['userid']);
    }
    addnav('Knappeneditor','user.php?op=disciple&userid='.$_GET['userid']);
    addnav('Runeneditor','user.php?op=runes&userid='.$_GET['userid']);
    addnav('Specialseditor','user_special.php?op=edit&userid='.$_GET['userid']);

    //Ein User kann nur dann übertragen/geupdated werden wenn ein Standard Passwort existiert
    //Und die CI angeschaltet wurde
    if(getsetting('ci_active',0) == true && getsetting('ci_std_pw_active',0) == true)
    {
        if( $row['incommunity'] == 0 )
        {
            addnav('Ins Forum übertragen', 'user.php?op=forum&userid='.$_GET['userid'].'&name='.urlencode($row['login']).'&pass='.urlencode(getsetting('ci_std_pw','')).'&mail='.urlencode($row['emailaddress']));
        }
        else
        {
            addnav('Forendaten aktualisieren',"user.php?op=forum_update&userid=".$_GET['userid']."&ci_id=".$row['incommunity']."&name=".urlencode($row['login'])."&pass=".urlencode(getsetting('ci_std_pw',''))."&mail=".urlencode($row['emailaddress']));
        }
    }
    else
    {
        addnav('Forum nicht aktiv oder kein standard Passwort vorhanden');
    }
}

page_header('Usereditor');
$_SESSION['last_user_editor_edit'] = ((int)$_SESSION['last_user_editor_edit']>0)?(int)$_SESSION['last_user_editor_edit']:$session['user']['acctid'];
$output .= '
    <form action="user.php?op=search" method="POST">
        Suche in allen Feldern: ' 
        //. '<input name="q" id="q">' 
        .'<br />'
        . jslib_autocomplete_name('q', true, true)
        //. '<input type="submit" class="button">
    .'</form>
    <br />
    <div class="trhead">'.plu_mi('petition_search',0,false).'Spezialoptionen:</div>
    <div id="'.plu_mi_unique_id('petition_search').'" style="display:none;">
        <ul>
            <li>'.create_lnk('Selbst editieren','user.php?op=edit&userid='.$session['user']['acctid'],true,true).'
            <li>'.create_lnk('Letzten Eintrag erneut editieren (Acctid: '.(int)$_SESSION['last_user_editor_edit'].')','user.php?op=edit&userid='.(int)$_SESSION['last_user_editor_edit']).'
        </ul>
    </div>
    <hr />
';
define('JSLIB_NO_FOCUS_NEEDED',1);
$output .=
    '<script type="text/javascript">
        LOTGD.m_on_document_loaded.push( function(){document.getElementById("q").focus();} );
    </script>';
addnav('','user.php?op=search');
grotto_nav();
if ($access_control->su_check(access_control::SU_RIGHT_EDITORUSER))
{
    //addnav('Mechanik');
    //addnav("Account-Tabellen abgleichen","user.php?op=extratable");
    //addnav("bestdragonage kopieren","user.php?op=copydata");
    //addnav("Überflüssige Tabellen löschen","user.php?op=delextra");
    //addnav("Benutzereditor","user.php");
    //if($session['user']['acctid'] == 2310) {addnav('Forum','user.php?op=forum_all');}
    //addnav('Seiten');
    /*$sql = "SELECT count(*) AS count FROM accounts";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $page=0;
    while ($row[count]>0){
        $page++;
    //    addnav("$page Seite $page","user.php?page=".($page-1)."&sort=$_GET[sort]");
        $row[count]-=100;
    }*/
}

$str_op = ($_REQUEST['op'] ? $_REQUEST['op'] : '');

switch($str_op) {

    case 'search':

        if(is_int($_POST['q']))
        {
            $_POST['q'] = str_create_search_string($_POST['q']);
            $sql = "SELECT acctid FROM accounts WHERE ";
            $where="            
            lastip LIKE '".$_POST['q']."' OR
            acctid LIKE '".$_POST['q']."'";
            $result = db_query($sql.$where);
        }
        else 
        {
            $_POST['q'] = str_create_search_string($_POST['q']);
            $sql = "SELECT acctid FROM accounts WHERE ";
            $where="
            login LIKE '".$_POST['q']."' OR
            acctid LIKE '".$_POST['q']."' OR
            name LIKE '".$_POST['q']."%' OR
            emailaddress LIKE '".$_POST['q']."' OR
            lastip LIKE '".$_POST['q']."' OR
            uniqueid LIKE '".$_POST['q']."'";
            $result = db_query($sql.$where);
        }
        

        $int_count = db_num_rows($result);
        if ($int_count<=0)
        {
            output('`$Keine Ergebnisse gefunden`0');

            $where='';
        }
        elseif ($int_count>100){
            output('`$Zu viele Ergebnisse gefunden. Bitte Suche einengen.`0');

            $where='';
        }
        elseif ($int_count==1){
            //$row = db_fetch_assoc($result);
            //redirect('user.php?op=edit&userid=$row[acctid]');

            $_GET['page']=0;
        }
        else{
            $_GET['page']=0;
        }

    break;    // END search

    case 'lasthit':

        $output='';
        $sql = "SELECT output FROM accounts WHERE acctid=".(int)$_GET['userid'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);

        //Kein Javascript im iFrame und keine verschachtelten iframes!
        $row['output'] = strip_selected_tags($row['output'],array('script','iframe'),true,true);
        echo $row['output'];

        exit();

    break; // END lasthit

    case 'logout_all':

        if($_GET['act'] == 'ok') {

            user_update(
                array
                (
                    'loggedin'=>0,
                    'where'=>'superuser=0 AND loggedin=1'
                )
            );
            //GESAMTEN Memorycache leeren!
            $mem_cache->flush();
            output(db_affected_rows().' Spieler erfolgreich ausgeloggt!');

        }
        else {

            $sql = "SELECT COUNT(*) AS a FROM accounts WHERE loggedin=1 AND superuser=0";
            $count = mysql_fetch_row(db_query($sql));

            output($count[0].' Spieler wirklich ausloggen?`n`n'.create_lnk('Ab ins Körbchen!','user.php?op=logout_all&act=ok'),true);

        }

    break;    // END logout all

    case 'edit':

        $_SESSION['last_user_editor_edit'] = (int)$_GET['userid'];
        $result = db_query("SELECT * FROM accounts WHERE acctid=".(int)$_GET['userid']);
        $row = db_fetch_assoc($result);
        
        $result2 = db_query("SELECT * FROM account_extra_info WHERE acctid=".(int)$_GET['userid']);
        $row2 = db_fetch_assoc($result2);

        // FORMULAR-ARRAY erstellen

        if($access_control->su_check(access_control::SU_RIGHT_RIGHTS)) {
            $arr_grps = user_get_sugroups();

            $sugroups = '';
            if(is_array($arr_grps)) {
                foreach($arr_grps as $lvl=>$grp) {

                    $sugroups .= ','.$lvl.','.$grp[0].'/'.$grp[1].(0 == $lvl ? ' (Standard-Spieler)' : '');

                }
            }

            $ugrp = array();

            // Wenn dieser User einer Gruppe angehört
            if(isset($arr_grps[$row['superuser']])) {
                $ugrp = $arr_grps[$row['superuser']];
                $ugrp_rights = $ugrp[2];
            }

            $surights = array('Superuser-Rechte,title');
            $str_dependence = '';
            foreach($access_control as $r=>$v) {

                $str_dependence = '';

                // Titel
                if(is_string($v)) {
                    $surights[] = $v.',title,2';
                }
                else {

                    if(!empty($v['dependent'])) {
                        $str_dependence = '|?(Abhängig von: '.$access_control[$v['dependent']]['desc'].')';
                    }

                    $surights['surights['.$r.']'] = $v['desc'].($ugrp[0] ? '`nGruppe: '.($ugrp_rights[$r] ? '`@Ja`0' : '`$Nein`0') : '').',enum,-1,Gruppeneinstellung,0,Nein,1,Ja'.$str_dependence;
                }

            }

        }

        $mounts=',0,Keins';
        $sql = 'SELECT mountid,mountname,mountcategory FROM mounts ORDER BY mountcategory';
        $result = db_query($sql);
        while ($m = db_fetch_assoc($result)){
            $mounts.=','.$m['mountid'].','.$m['mountcategory'].': '.strip_appoencode($m['mountname'],3);
        }

        $specialties=',0,Keins';
        $sql = 'SELECT specname,category,specid FROM specialty ORDER BY category, specname';
        $result = db_query($sql);
        while ($m = db_fetch_assoc($result)){
            $specialties.=','.$m['specid'].','.$m['category'].': '.strip_appoencode($m['specname'],3);
        }

        $professions = ',0,Keiner';
        $joblist = ',0,Keiner';

        foreach($profs as $k=>$p) {

            $professions .= ','.$k.','.$p[0].'/'.$p[1];

        }

        foreach($jobs as $k2=>$p2) {

            $joblist .= ','.$k2.','.$p2[0].'/'.$p2[1];

        }

        $guildfuncs = '';

        foreach($dg_funcs as $k=>$f) {

            $guildfuncs .= ','.$k.','.$f[0].'/'.$f[1];

        }

        $races=',,Unbekannt';
        $sql = 'SELECT name,id FROM races WHERE active=1 ORDER BY name ASC';
        $result = db_query($sql);
        while ($m = db_fetch_assoc($result)){
            $races.=','.$m['id'].','.$m['name'];
        }

        $userinfo = array(
            'Accountdaten &amp; Namen,title',
            'acctid'=>'User ID,viewonly|?Die Accountid, unter der der Account in der DB gespeichert ist.',
            'name'=>'Voller Name,viewonly|?Zum Ändern des Gesamtnamens bitte die einzelnen Bestandteile (Login, Farbname, Titel, eigener Titel) editieren.',
            'login'=>'Login|?Loginname des Accounts.',
            'title'=>'Regulärer Titel',
            'ctitle'=>'Eigener Titel',
            'ctitle_backup'=>'Eigener Titel - Backup',
            'cname'=>'Eigener (farbiger) Name',
            'csign'=>'Besonderes Signum vor dem Namen (max. 3 Zeichen)',
            'namecheckday'=>'Namensprüfungsalter',
            'namecheck'=>'Name geprüft von (acctid); 16777215=ok',
            'newpassword'=>'Neues Passwort',
            'emailaddress'=>'Email-Adresse',
            'loggedin'=>'Eingeloggt,bool',
            'banoverride'=>'Verbannungen übergehen,bool',
            'specialinc'=>'aktuelles SpecialEvent',
            'superuser'=>'Superuser,'.($access_control->su_check(access_control::SU_RIGHT_RIGHTS) ? 'enum'.$sugroups : 'viewonly'),
            'superuser_id_switch' => 'ID des zugehörigen Superuser Chars,int|?Die ID des Admincharakters die bei einem Superuser Invoke geladen wird!',
            'incommunity'=>'Community ID (0=nicht eingetragen),int',

            'Charakterdaten,title',
            'sex'=>'Geschlecht,enum,0,Männlich,1,Weiblich',
            'race'=>'Rasse,enum'.$races,
            'specialty'=>'Spezialgebiet,enum'.$specialties,
            'birthday'=>'Ankunftsdatum (Format: YYYY-MM-DD)',
            'char_birthdate'=>'Geburtsdatum des Spielers|?Anleitung s. Profil',
            'charclass'=>'Charakterklasse',
            'profession'=>'Amt,enum'.$professions,
            'job'=>'Beruf,enum'.$joblist,
            'marriedto'=>'Partner-ID (4294967295 = Violet/Seth),int',
            'charisma'=>'Flirts (4294967295 = verheiratet mit Partner),int',
            'expedition'=>'Zutritt zur Expedition?,bool',
            'long_bio'=>'Bio,textarea,60,30',
            'ext_mount'=>'Tier-Bio'.($row2['ext_mount'] == NULL ? ' (nicht vorhanden),viewonly':',textarea,60,30'),
            'ext_disciple'=>'Knappen-Bio'.($row2['ext_disciple'] == NULL ? ' (nicht vorhanden),viewonly':',textarea,60,30'),
            'bio_extra_info'=>'Extra-Info'.($row2['bio_extra_info'] == NULL ? ' (nicht vorhanden),viewonly':',textarea,60,30'),
      
            'guildid'=>'GildenID,int',
            'guildrank'=>'Gildenrang (1-'.count($dg_default_ranks).'),int',
            'guildfunc'=>'Funktion in der Gilde,enum'.$guildfuncs,

            'Werte,title',
            'dragonkills'=>'Heldentaten,int',
            'level'=>'Level,int',
            'experience'=>'Erfahrung,int',
            'hitpoints'=>'Lebenspunkte (aktuell),int',
            'maxhitpoints'=>'Maximale Lebenspunkte,int',
            'alive'=>'Lebendig,bool|?Wirkt nur, wenn LP > 0!',
            'deathpower'=>'Gefallen bei Ramius,int',
            'gravefights'=>'Grabkämpfe übrig,int',
            'soulpoints'=>'Seelenpunkte (HP im Tod),int',
            'turns'=>'Runden übrig,int',
            'castleturns'=>'Schlossrunden übrig,int',
            'maze'=>'aktuelle Schlosskarte,int',
            'fishturn'=>'Angelrunden,int',
            'playerfights'=>'Spielerkämpfe übrig,int',
            'attack'=>'Angriffswert (inkl. Waffenschaden),int',
            'defence'=>'Verteidigung (inkl. Rüstung),int',
            'spirits'=>'Stimmung (nur Anzeige),enum,'.RP_RESURRECTION.',RP-Wiedererweckung,-6,Wiedererweckt,-2,Sehr schlecht,-1,Schlecht,0,Normal,1,Gut,2,Sehr gut',
            'resurrections'=>'Auferstehungen,int',
            'reputation'=>'Ansehen (-50 - +50),int',
            'sentence'=>'Zu x Tagen Haft verurteilt,int',
            'imprisoned'=>'Haftstrafe in Tagen,int',
            'daysinjail'=>'Verbrachte Tage im Kerker,int',
            'charm'=>'Charme,int',
            'sympathy'=>'Sympatiepunkte,int',
            'battlepoints'=>'Arenapunkte,int',
            'gladiatorfights'=>'Gladiatorkämpfe vor DK übrig,int',
            'age'=>'Tage seit Level 1,int',
            'dragonage'=>'Alter bei der letzten Heldentat,int',
            'marks'=>'Male,bitflag,Mal der Erde,Mal der Luft,Mal des Feuers,Mal des Wassers,Mal des Geistes,Pakt mit Blutgott',
            'conf_bits'=>'Konfigurationsflags,bitflag,Chaching deaktiviert <i>(nicht empfohlen)<i>,Darf keine Sympathiepunkte bekommen',

            'Ausstattung &amp; Besitz,title',
            'gems'=>'Edelsteine,int',
            'gemsinbank'=>'Gems auf der Bank,int',
            'gold'=>'Bargold,int',
            'goldinbank'=>'Gold auf der Bank,int',
            'minnows'=>'Fliegen im Beutel,int',
            'worms'=>'Würmer im Beutel,int',
            'boatcoupons'=>'Bootscoupons im Beutel,int',
            'weapon'=>'Name der Waffe',
            'weapondmg'=>'Waffenschaden,int',
            'weaponvalue'=>'Kaufwert der Waffe,int',
            'armor'=>'Name der Rüstung',
            'armordef'=>'Verteidigungswert,int',
            'armorvalue'=>'Kaufwert der Rüstung,int',
            'house'=>'Haus-ID,int',
            'hashorse'=>'Tier,enum'.$mounts,
            'xmountname'=>'Name des Tieres',

            'Aktueller Spieltag / Übrige Aktionen,title',
            'seenlover'=>'Geflirtet,bool',
            'seendragon'=>'Bosse heute versucht,bool',
            'seenmaster'=>'Meister befragt,bool',
            'fedmount'=>'Tier gefüttert,bool',
            'seenbard'=>'Barden gehört,bool',
            'usedouthouse'=>'Plumpsklo besucht,bool',
            'treepick'=>'Baum des Lebens besucht,bool',
            'boughtroomtoday'=>'Zimmer für heute bezahlt,bool',
            'hadnewday'=>'Rastbonus erhalten,enum,0,Nein,1,Ja,2,Wiedererweckt',
            'witch'=>'Hexenbesuche,int',
            'cage_action'=>'Käfigkämpfe heute angezettelt,int',
            'rouletterounds'=>'übrige Rouletterunden (Zehner = Todeszähler),int',
            'gotfreeale'=>'Frei-Ale (MSB: getrunken - LSB: spendiert),int',
            'goldin'=>'Goldeingang heute,int',
            'goldout'=>'Goldausgang heute,int',
            'gemsin'=>'Gemeingang heute,int',
            'gemsout'=>'Gemausgang heute,int',
            'guildtransferred_gold'=>'Gildentransfer (gold),int',
            'guildtransferred_gems'=>'Gildentransfer (gems),int',
            'guildfights'=>'Gildenkämpfe heute,int',
            'temple_servant'=>'Tempeldienertage(x20=heute geleistet),int',
            'drunkenness'=>'Betrunken (0-100),int',
            'pvpflag'=>'Letzter PvP-Kampf ('.PVP_IMMU.' = Immu an)',
            'last_crime'=>'Letzte Straftat',
            'balance_forest'=>'Waldbalance|?-10 / +20, > 0 verstärkt Werte der Waldmonster, < 0 verringert sie.',
            'balance_dragon'=>'Bossbalance|?-10 / +20, > 0 verstärkt Werte der Bosse, < 0 verringert sie.',
            'location'=>'Aufenthaltsort,enum,0,Felder,1,Kneipe,2,Haus,3,Kerker,'.USER_LOC_VACATION.',Urlaubsmodus',

            'Freischaltungen / DP,title',
            'rename_weapons'=>'individuelle Waffe/Rüstung,bitflag,Waffe umbenennen,Rüstung färben',
            'has_long_bio'=>'Verlängerte Bio gekauft,bool',
            'hasxmount'=>'Tier getauft,bool',
            'trophyhunter'=>'Präparierset gekauft,bool',

            'Spezielle Ruhmeshalleneinträge,title',
            'bestdragonage'=>'Jüngstes Alter bei einer Heldentat,int',
            'beerspent'=>'Anzahl spendierter Ales,int',
            'disciples_spoiled'=>'Anzahl verheizter Knappen,int',
            'timesbeaten'=>'Verpügelt worden,int',
            'runaway'=>'Aus dem Kampf geflohen,int',
            'virgator_level'=>'Virgator-Level,int',
            'exchangequest'=>'Tauschquest-Level,int',
            'hunterlevel'=>'Jagd-Level,int',
            
            'Kindersystem,title',
      'ssstatus'=>'Schwanger?,enum,0,Nein,1,Ja',
      'ssmonat'=>'Schwangerschafts Monat (90 Tage),int',
      'sserzeug'=>'ID des Vaters,int',

      'Rp Einstellungen,title',
      'rpchar'=>'RPChara,enum,0,Nein,1,Ja',
      'orientierung'=>'Die Orientierung des Users,enum,0,Unbekannt,1,Hetero,2,Bi,3,Homo',

            'Weitere Infos,title',
            'laston'=>'Zuletzt Online,viewonly',
            'lasthit'=>'Letzter neuer Tag,viewonly',
            'lastmotd'=>'Datum der letzten MOTD,viewonly',
            'lastip'=>'Letzte IP,viewonly',
            'uniqueid'=>'Unique ID,viewonly',
            'allowednavs'=>'Zulässige Navigation,viewonly',
            'dragonpoints'=>'Eingesetzte Heldenpunkte,viewonly',
            'bufflist'=>'Spruchliste,viewonly',
            'prefs'=>'Einstellungen,viewonly',
            'donationconfig'=>'Spendenkäufe,viewonly',
            'ext_profile'=>'Profilerweiterungen,viewonly',
            
            'User Einstellungen,title',
            'prefs[output_compression]'=>'Output compression,bool|?Mehr Belastung für den Server, weniger Datentraffic',
            'Maileinstellungen,divider',
            'prefs[emailonmail]'=>'Email bei Brieftaube versenden,bool',
            'prefs[systemmail]'=>'Email bei systemmails versenden,bool',
            'prefs[dirtyemail]'=>'Kein Wortfilter bei Brieftauben,bool',
            'prefs[forward_yom_to_superuser]'=>'Mail an Superuser weiterleiten,int|?-1 setzt das setting außer Kraft, sonst ID eingeben',
            'Farbeinstellungen,divider',
            'prefs[commenttalkcolor]'=>'Kommentarfarbe,color_pick,1,$',
            'prefs[commentemotecolor]'=>'Emote farbe,color_pick,1,$',
            'Chateinstellungen,divider',
            'prefs[chat_show_rest]'=>'Restzeichenanzeige,bool',
            'prefs[preview]'=>'Chat Previews einblenden,bool',
            'prefs[timestamps]'=>'Zeit vor Posts anzeigen,bool',
            'prefs[nav_help_enabled]'=>'Hilfetext bei Navigationslinks einschalten,bool',
            'prefs[sx0]'=>'Shortcut 1',
            'prefs[sx1]'=>'Shortcut 2',
            'prefs[sx2]'=>'Shortcut 3',
            
            'Features an/abschalten,divider',
            'prefs[nosounds]'=>'Sounds abschalten,bool',
            'prefs[nocolors]'=>'Farben abschalten,bool',
            'prefs[noimg]'=>'Navigationsbilder deaktivieren,bool',
            'prefs[nopngfix]'=>'PNG Fix deaktivieren,bool',
            'prefs[tutorial_disabled]'=>'Tutorial deaktivieren,bool',
            'prefs[htmleditor_enabled]'=>'HTML Editor aktivieren,bool',
            'prefs[birthdate_disp]'=>'Geburtsdatum in Bio einblenden,bool',
            'prefs[notall2bank]'=>'Nicht alles zur Bank bringen,int',
            'prefs[taxfrombank]'=>'Steuerbankeinzug verwenden,bool',
            'prefs[hide_who_is_here]'=>'Wer ist hier Leiste verbergen,bool',
            'prefs[nohotkeys]'=>'Hotkeys deaktivieren,bool',
            'prefs[quicknav_enabled]'=>'Soll das Quicknav Feld angezeigt werden?,bool',
            
            );

        $extrainfo = array(
        );

        // END Formular-Array

        // Speichern
        if($_GET['act'] == 'save') {
            $sql1    = "UPDATE `accounts` SET `acctid` = '" . $row['acctid'] . "',";
            $sql2    = "UPDATE account_extra_info SET `acctid` = '" . $row['acctid'] . "',";
            $log    = '';

            // Rassenänderung: Boni zurücksetzen
            if($row['race'] != $_POST['race']) {
                $arr_change = $_POST;
                // Bisherige Rasse!
                $str_newrace = $_POST['race'];
                $arr_change['race'] = $row['race'];
                // Alte Boni abnehmen
                race_set_boni(true,true,$arr_change);
                // Neue Boni verteilen
                $arr_change['race'] = $str_newrace;
                race_set_boni(true,false,$arr_change);
                $_POST = $arr_change;
            }

            // Auf gleiche Logins checken
            if( $_POST['login'] != $row['login'] && db_num_rows(db_query('SELECT acctid FROM accounts WHERE LOWER(login)="'.addstripslashes(strtolower($_POST['login'])).'"')) ) {
                $_POST['login']    = $row['login'];
            }

            // Bei Namensänderung ein bißchen aufpassen
            // cname und Login müssen bis auf Farbcodes identisch sein
            if(strip_appoencode($_POST['cname'],3) != $_POST['login']) {

                // Ansonsten Farbname weg
                $_POST['cname'] = '';

            }

            // Wenn Login geändert: Forum nicht vergessen
            if($row2['incommunity'] && $_POST['login'] != $row['login']) {
                require_once(LIB_PATH.'communityinterface.lib.php');

                ci_rename($row2['incommunity'], stripslashes($_POST['login']));
            }

            // Jetzt noch Gesamtnamen korrekt setzen
            // Muss vor saveuser kommen, da beim Sessionuser noch Änderungen vorgenommen werden!
            if(substr($_POST['name'],0,34) != 'Neuling mit unzulässigem Namen Nr.') {
                $_POST['name'] = user_set_name($_GET['userid'],false,$_POST);
            }

            // Sonderrechte speichern
            if($access_control->su_check(access_control::SU_RIGHT_RIGHTS)) {
                foreach($_POST['surights'] as $key=>$r) {
                    if($r == -1) {
                        unset($_POST['surights'][$key]);
                    }
                }

                if(sizeof($_POST['surights']) > 0) {
                    $_POST['surights'] = addslashes(serialize(user_set_surights($_POST['surights'],$ugrp_rights)));
                }
                else {
                    $_POST['surights'] = '';
                }
                // Zu Formular-Schablone hinzufügen
                $userinfo['surights'] = true;
            }
            
            if(sizeof($_POST['prefs']) > 0) 
            {
                $arr_user_prefs = unserialize($row['prefs']);
                foreach ($arr_user_prefs as $key => $val)
                {
                    if(!array_key_exists($key,$_POST['prefs']))
                    {
                        $_POST['prefs'][$key] = $arr_user_prefs[$key];    
                    }
                    elseif ($_POST['prefs'][$key] != $val)
                    {
                        $log .= '[Einstellung] ' . $key . ' = ' . $_POST['prefs'][$key] . ' (davor: ' . $arr_user_prefs[$key] . '),`n';
                    }
                }
                $_POST['prefs'] = serialize($_POST['prefs']);
            }
            else
            {
                unset($_POST['prefs']);
            }

            foreach($_POST as $key=>$val)
            {
                if(isset($row[$key]))
                {
                    if ($key=="newpassword" )
                    {
                        if (!empty($val))
                        {
                            $sql1    .= "password = MD5('$val'),";
                            $log    .= 'Neues Passwort,`n';
                        }
                    }
                    elseif ($row[$key] != stripslashes($val))
                    {
                        $sql1    .= $key . " = '".addstripslashes($val)."',";
                        if ($key == 'prefs') continue;
                        $log    .= $key . ' = ' . $val . ' (davor: ' . stripslashes($row[$key]) . '),`n';
                    }
                }
                elseif (isset($row2[$key]) && $row2[$key] != stripslashes($val))
                {
                    $sql2    .= $key . " = '".addstripslashes($val)."',";
                    $log    .= $key . ' = ' . $val . ' (davor: ' . stripslashes($row2[$key]) . '),`n';
                }

            }
            $sql1=substr($sql1,0,strlen($sql1)-1);
            $sql2=substr($sql2,0,strlen($sql2)-1);
            $sql1.=' WHERE acctid='.$_GET['userid'];
            $sql2.=' WHERE acctid='.$_GET['userid'];

            if (strlen($log)>3) $log = substr($log, 0, strlen($log)-3);
            debuglog('Useredit - Editierte User`n(Setzte: ' . $log . ')',$_GET['userid']);
            systemlog('Useredit - Editierte User',$session['user']['acctid'],$_GET['userid']);

            //we must manually redirect so that our changes go in to effect *after* our user save.
            addnav('','su_petitions.php?op=view&id='.$_GET['returnpetition']);
            addnav('','user.php');

            saveuser();

            db_query($sql1);
            db_query($sql2);
            
            global $mem_cache;
            $mem_cache->delete('session_'.$_GET['userid']);

            if (!empty($_GET['returnpetition'])){
                header('Location: su_petitions.php?op=view&id='.$_GET['returnpetition']);
            }
            else{
                header('Location: user.php');
            }

            exit();
        }
        // END Speichern

        if(!is_array($surights))
        {
            $surights=array();
        }
        
        $userinfo = array_merge($userinfo,$extrainfo,$surights);
        debuglog('`&Benutzer '.$row['name'].'`& im Usereditor geöffnet.');

        $row['surights'] = unserialize(stripslashes($row['surights']));


        foreach($access_control as $r=>$v) {

            if(isset($row['surights'][$r])) {
                $row['surights['.$r.']'] = $row['surights'][$r];
                unset($row['surights'][$r]);
            }
            else {
                $row['surights['.$r.']'] = -1;
            }

        }

        $row2['long_bio'] = preg_replace('/\r\n|\r|\n/', '', $row2['long_bio']); // Zeilenumbrüche raus
        
        $arr_user_prefs = unserialize($row['prefs']);
        
        foreach($arr_user_prefs as $r=>$v) 
        {
            $row['prefs['.$r.']'] = $arr_user_prefs[$r];
        }
        
        $row = adv_array_merge($row,$row2,$arr_user_prefs);

        output("<form action='user.php?op=special&amp;userid=$_GET[userid]".($_GET['returnpetition']!=""?"&amp;returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
        addnav("","user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
        output("`n`c".$row[name]."`c`n<input type='submit' class='button' name='newday' value='Neuen Tag gewähren'>",true);
        output("<input type='submit' class='button' name='fixnavs' value='Defekte Navs reparieren'>",true);
        if(!empty($row['emailvalidation'])) {
            output("<input type='submit' class='button' name='clearvalidation' value='E-Mail als gültig markieren'>",true);
        }
        if($access_control->su_check(access_control::SU_RIGHT_DEV)) //auf Wunsch der Admins, die Knöpfe braucht man sowieso nur bei Fehlern
        {
            output("<input type='submit' class='button' name='reset_values' value='ATK+DEF Reset (!)'>",true);
            output("<input type='submit' class='button' name='reset_dragonpoints' value='Heldenpunkte Reset (!)'>",true);
        }
        if($access_control->su_check(access_control::SU_RIGHT_ANONYMIZE_USER))
        {
            output("<input type='submit' class='button' name='anonymize_user' value='User anonymisieren (!)' onClick='return confirm(\"Achtung, der User wird anonymisiert und ist für das System praktisch unbrauchbar!\")'>",true);
        }
        
        output("</form>",true);

        output("<form action='user.php?op=edit&amp;act=save&amp;userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
        addnav("","user.php?op=edit&act=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
        addnav("","user.php?op=edit&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");

        editnav();


        output("<input type='submit' class='button' value='Speichern'>",true);
        showform($userinfo,$row);
        output("</form>",true);
        if($_GET['userid'] != $session['user']['acctid']) {
            output("<iframe src='user.php?op=lasthit&userid={$_GET['userid']}' width='100%' height='400'>Dein Browser muss iframes unterstützen, um die letzte Seite des Users anzeigen zu können. Benutze den Link im Menü stattdessen.</iframe>",true);
        }
        addnav("","user.php?op=lasthit&userid=".$_GET['userid']);

    break;    // END edit

    case 'special':

        if ($_POST['newday']!='')
        {
            user_update(
                array
                (
                    'lasthit'=>date('Y-m-d H:i:s',strtotime(date('r').'-'.(86500/getsetting('daysperday',4)).' seconds'))
                ),
                (int)$_GET['userid']
            );
        }
        elseif($_POST['fixnavs']!='')
        {
            user_update(
                array
                (
                    'allowednavs'=>'',
                    'output'=>'',
                    'restorepage'=>'',
                    'specialinc'=>'',
                    'pqtemp'=>'',
                    'specialmisc'=>'',
                ),
                (int)$_GET['userid']
            );
        }
        elseif($_POST['clearvalidation']!='')
        {
            user_update(
                array
                (
                    'emailvalidation'=>'',
                ),
                (int)$_GET['userid']
            );
        }
        elseif ($_POST['reset_values'])
        {

            $sql = 'SELECT dragonpoints,weapondmg,armordef,level,race FROM accounts WHERE acctid='.(int)$_GET['userid'];
            $arr_tmp = db_fetch_assoc(db_query($sql));

            $arr_dp = unserialize($arr_tmp['dragonpoints']);

            $arr_tmp['attack'] = $arr_tmp['weapondmg'] + $arr_tmp['level'];
            $arr_tmp['defence'] = $arr_tmp['armordef'] + $arr_tmp['level'];

            if(is_array($arr_dp)) {
                foreach($arr_dp as $key=>$val)
                {
                    if ($val=='atk' || $val == 'at')
                    {
                        $arr_tmp['attack']++;
                    }
                    if ($val=='def' || $val == 'de')
                    {
                        $arr_tmp['defence']++;
                    }
                }
            }

            if(!empty($arr_tmp['race'])) {
                $arr_race = race_get($arr_tmp['race'],true);
                race_set_boni(true,false,$arr_tmp);
            }

            debuglog('setzte ATK (='.$arr_tmp['attack'].') + DEF (='.$arr_tmp['defence'].') zurück für',$_GET['userid']);
            
            user_update(
                array
                (
                    'attack'=>$arr_tmp['attack'],
                    'defence'=>$arr_tmp['defence'],
                ),
                (int)$_GET['userid']
            );
        }
        elseif ($_POST['reset_dragonpoints']) {

            $sql = 'SELECT dragonpoints,attack,defence,maxhitpoints,level,weapondmg,armordef FROM accounts WHERE acctid='.$_GET['userid'];
            $arr_tmp = db_fetch_assoc(db_query($sql));

            $arr_dp = unserialize($arr_tmp['dragonpoints']);

            if(is_array($arr_dp)) {
                foreach ($arr_dp as $key=>$val) {

                    if($val == 'atk' || $val == 'at') {
                        $arr_tmp['attack']--;
                    }
                    if($val == 'def' || $val == 'de') {
                        $arr_tmp['defence']--;
                    }
                    if($val == 'hp') {
                        $arr_tmp['maxhitpoints'] -= 5;
                    }

                }
            }

            $arr_tmp['attack'] = max($arr_tmp['attack'],$arr_tmp['level']+$arr_tmp['weapondmg']);
            $arr_tmp['defence'] = max($arr_tmp['defence'],$arr_tmp['level']+$arr_tmp['armordef']);
            $arr_tmp['maxhitpoints'] = max($arr_tmp['maxhitpoints'],5*$arr_tmp['level']);

            debuglog('setzte Heldenpunkte zurück, ATK(='.$arr_tmp['attack'].'), DEF (='.$arr_tmp['defence'].'), HP (='.$arr_tmp['maxhitpoints'].') für',$_GET['userid']);

            $arr_tmp['dragonpoints'] = array();

            // User kurz ausloggen..
            user_update(
                array
                (
                    'loggedin'=>0,
                    'dragonpoints'=>$arr_tmp['dragonpoints'],
                    'attack'=>$arr_tmp['attack'],
                    'defence'=>$arr_tmp['defence'],
                    'maxhitpoints'=>$arr_tmp['maxhitpoints'],
                    'lasthit'=>'0000-00-00 00:00:00'
                ),
                (int)$_GET['userid']
            );
        }
        elseif ($_POST['anonymize_user'])
        {
            $rand = md5(microtime());
            
            // User kurz ausloggen..
            user_update(
                array
                (
                    'emailaddress'=>$rand,
                    'lastip'=>$rand,
                    'lasthit'=>'0000-00-00 00:00:00',
                    'uniqueid'=>$temp,
                    'output'=>'',
                    'password'=>$rand
                ),
                (int)$_GET['userid']
            );
            debuglog('Anonymisierte User',$_GET['userid']);
        }


        if (empty($_GET['returnpetition']))
        {
            $str_lnk = 'user.php?op=edit&userid='.$_GET['userid'];
        }
        else
        {
            $str_lnk = 'su_petitions.php?op=view&id='.$_GET['returnpetition'];
        }
        // Von Hand weiterleiten
        addnav('',$str_lnk);
        saveuser();

        header('Location:'.$str_lnk);
        exit();

    break;    // END special

    // Knappeneditor
    case 'disciple':

        $int_uid = (int)$_GET['userid'];
        $int_did = (int)$_POST['id'];

        addnav('Zurück zum Useredit','user.php?op=edit&userid='.$int_uid);

        if($int_did>0) {

      /* Nase voll von dem Scheiß auf dem DP 
          
            // Feststellen, ob unser Knappe der stärkste im Lande ist
            $bool_bestone = false;
            $sql = 'SELECT level, id FROM disciples WHERE level > '.(int)$_POST['level'].' AND state > 0 ORDER BY level DESC LIMIT 1';
            $res = db_query($sql);

            // Gibt keinen stärkeren
            if(!db_num_rows($res)) {
                $bool_bestone = true;

                // Alle anderen zurücksetzen
                db_query('UPDATE disciples SET best_one = 0');
            }
            else {
                // Stärkeren zum besten Knappen erheben
                $arr_best = db_fetch_assoc($res);

                db_query('UPDATE disciples SET best_one = 1 WHERE id='.$arr_best['id']);
            }*/

            $sql = ($int_did == -1 ? 'INSERT INTO ' : 'UPDATE ');
            $sql .= ' disciples
                    SET name="'.$_POST['name'].'",state='.$_POST['state'].',oldstate='.$_POST['oldstate'].',level='.$_POST['level'].',master='.$int_uid; // best_one='.($bool_bestone ? 1 : 0).', - entfernt wg. DP, siehe oben.
            $sql .= ($int_did > -1 ? ' WHERE id='.$int_did : '');
            db_query($sql);

            if(db_affected_rows()) {
                output('`@`b`cKnappe erfolgreich editiert!`c`b`0`n`n');
            }
            else {
                output('`$`b`cKnappe NICHT editiert!`c`b`0`n`n');
            }
        }

        $sql = 'SELECT * FROM disciples WHERE master='.$int_uid;
        $res = db_query($sql);

        if(db_num_rows($res) == 0) {
            $arr_data = array('id'=>-1);
        }
        else {
            $arr_data = db_fetch_assoc($res);
        }

        $str_state_enum = ',-1,tot,0,inaktiv';
        for($i=1;$i<=22;$i++) {
            $str_state_enum .= ','.$i.','.get_disciple_stat($i);
        }

        $arr_form = array(

                            'name'=>'Name des Knappen:',
                            'state'=>'Aktueller Status des Knappen:,enum'.$str_state_enum,
                            'oldstate'=>'Status-Backup:,enum'.$str_state_enum,
                            'level'=>'Level des Knappen:,enum_order,0,100',
                            'id'=>'ID des Knappen,hidden'
                            );

        $str_lnk = 'user.php?op=disciple&userid='.$int_uid;
        addnav('',$str_lnk);
        output('<form method="POST" action="'.htmlentities($str_lnk).'">',true);

        showform($arr_form,$arr_data,false,'Speichern');

        output('</form>',true);


    break;

    // Runeneditor
    case 'runes':

        $int_uid = (int)$_GET['userid'];

        addnav('Zurück');
        addnav('Zum Useredit','user.php?op=edit&userid='.$int_uid);

        // Runeninfos abrufen
        $res = db_query('SELECT * FROM runes_extrainfo');

        if(!db_num_rows($res)) {
            output('Runen? Was ist das?!');
            page_footer();
            exit();
        }

        $str_out = '';

        if(isset($_POST['save'])) {

            $arr_tmp['runes_ident'] = array();
            if( is_array($_POST['runes']) ){
                foreach ($_POST['runes'] as $id) {
                    $arr_tmp['runes_ident'][$id] = true;
                }
            }

            $arr_tmp['runes_ident'] = serialize($arr_tmp['runes_ident']);

            user_set_aei($arr_tmp,$int_uid);

            $str_out .= '`n`@Erfolgreich gespeichert!`0`n`n';

        }

        $lres = db_query('    SELECT DISTINCT i.value2 AS id
                            FROM items i
                            LEFT JOIN items_tpl it ON it.tpl_id = i.tpl_id
                            LEFT JOIN account_extra_info aei ON aei.acctid = i.owner
                            WHERE it.tpl_class = "'.getsetting('runes_classid',19).'"
                            AND i.tpl_id <> "r_dummy"
                            AND NOT INSTR( aei.runes_ident, CONCAT( ":", it.tpl_value2, ";" ) )
                            AND i.owner = '.$int_uid.'');
        $lost = array();
        while( $l = db_fetch_assoc($lres) ){
            $lost[ $l['id'] ] = true;
        }

        $arr_tmp = user_get_aei('runes_ident',$int_uid);
        $arr_tmp['runes_ident'] = unserialize($arr_tmp['runes_ident']);

        $str_lnk = 'user.php?op=runes&userid='.$int_uid;
        addnav('',$str_lnk);
        $str_out .= '`n`c`bIdentifizierte Runen dieses Benutzers:`b`n`n
                    `$`b*`b`& = Besitzt Rune im identifizierten Zustand, hat aber das Wissen nicht!`0
                    <form method="POST" action="'.htmlentities($str_lnk).'"><input type="hidden" value="1" name="save">';
        $str_out .= '<table>';
        $int_row = 0;
        $bool_lostthis=false;
        while($r = db_fetch_assoc($res))
        {
            if( !$int_row )
            {
                $str_out .= '<tr>';
            }
            $bool_lostthis = $lost[ $r['id'] ];
            $str_out .= '<td><input type="checkbox" name="runes[]" value="'.$r['id'].'" '.($arr_tmp['runes_ident'][$r['id']] ? 'checked="checked"':'').' /></td><td>'.($bool_lostthis?'`$`b':'`&').$r['name'].' (id: '.$r['id'].')'.($bool_lostthis?'`b':'').'`0</td>';
            $int_row++;
            if( $int_row==4 )
            {
                $str_out .= '</tr>'; $int_row=0;
            }

        }

        $str_out .= '</table><input type="submit" value="Speichern!" />
                    </form>`n`n`c'.plu_mi('runes_check',0,false).'`bCheck:`b`n<div style="text-align: left; width: 250px; display:none;" id="'.plu_mi_unique_id('runes_check').'">'.nl2br(str_replace(' ','&nbsp;',print_r($arr_tmp['runes_ident'],true))).'</div>
                    ';
        output($str_out,true);

    break;

    case 'forum':

        $aUser = array();
        $aUser[ 0 ] = array(    'id'    => $_GET['userid'],
                                'name'    => urldecode($_GET['name']),
                                'pass'    => urldecode($_GET['pass']),
                                'mail'    => urldecode($_GET['mail'])
                            );
        include_once(LIB_PATH.'communityinterface.lib.php');
        $ret = ci_importusers($aUser);
        if( !empty($ret) ){
            $str_mailbody = 'Dir wurde durch den Passierschein A38 Zugang zum Forum gewährt.`n
            Dein Username lautet `b'.$_GET['name'].'`b und Dein Passwort lautet `b'.$_GET['pass'].'`b.`n
            Bitte ändere es in Deinem Forenprofil so schnell wie möglich auf einen neuen Wert ab.';
            systemmail($_GET['userid'],'Forenzugangsdaten (Passierschein A38)',$str_mailbody);
            redirect("user.php?op=edit&userid=".$_GET['userid']."&msg=ok");
        }
        else{
            redirect("user.php?op=edit&userid=".$_GET['userid']."&msg=fail");
        }

    break;    // END forum

    case 'forum_update':
        $aUser =         array(    'ci_id'    => $_GET['ci_id'],
                                'name'    => urldecode($_GET['name']),
                                'pass'    => urldecode($_GET['pass']),
                                'mail'    => urldecode($_GET['mail'])
                            );
        include_once(LIB_PATH.'communityinterface.lib.php');
        $ret = ci_updateuser($aUser);
        if( $ret == 0 ){
            $str_mailbody = 'Dir wurde durch den Passierschein A38 Zugang zum Forum gewährt.`n
            Dein Username lautet `b'.$_GET['name'].'`b und Dein Passwort lautet `b'.$_GET['pass'].'`b.`n
            Bitte ändere es in Deinem Forenprofil so schnell wie möglich auf einen neuen Wert ab.';
            systemmail($_GET['userid'],'Forenzugangsdaten (Passierschein A38)',$str_mailbody);
            redirect("user.php?op=edit&userid=".(int)$_GET['userid']."&msg=ok");
        }
        else{
            redirect("user.php?op=edit&userid=".(int)$_GET['userid']."&msg=fail");
        }

    break;    // END forum

    case 'forum_all':

        $sql = 'SELECT accounts.acctid AS id,login AS name,password AS pass,emailaddress AS mail FROM accounts LEFT JOIN account_extra_info USING(acctid) WHERE incommunity > 0';
        $res = db_query($sql);

        while($a = db_fetch_assoc($res)) {
            $aUser[] = $a;
        }

        include_once(LIB_PATH."communityinterface.lib.php");
        $ret = ci_importusers($aUser);
        if( !empty($ret) ){
            redirect("user.php?op=edit&userid=".(int)$_GET['userid']."&msg=ok");
        }
        else{
            redirect("user.php?op=edit&userid=".(int)$_GET['userid']."&msg=fail");
        }

    break;    // END forum

    case 'logoff':

        $id = (int)$_GET['userid'];
        user_update(
            array
            (
                'loggedin'=>0,
                'lasthit'=>0
            ),
            $id
        );

        addnav('User Info bearbeiten','user.php?op=edit&userid=$id');

        output("Der User wurde ausgelogged!");

    break;    // END logoff

    default:    // Standardanzeige



    break;    // END default

}    // END Main-Switch (op)

if (isset($_GET['page']))
{
    $str_output = '';
    $order = 'acctid';
    if (!empty($_GET['sort']))
    {
        $order = $_GET['sort'];
    }
    $offset=(int)$_GET['page']*100;
    $sql = "SELECT acctid,login,name,level,laston,lastip,uniqueid,emailaddress,activated FROM accounts ".($where>""?"WHERE $where ":"")."ORDER BY \"$order\" LIMIT $offset,100";
    $result = db_query($sql);
    $str_output .= "
    <table>
        <tr>
        <td>Ops</td>
        <td><a href='user.php?sort=login'>Login</a></td>
        <td><a href='user.php?sort=name'>Name</a></td>
        <td><a href='user.php?sort=acctid'>ID</a></td>
        <td><a href='user.php?sort=level'>Lev</a></td>
        <td><a href='user.php?sort=laston'>Zuletzt da</a></td>
        <td><a href='user.php?sort=lastip'>IP</a></td>
        <td><a href='user.php?sort=uniqueid'>ID</a></td>
        <td><a href='user.php?sort=emailaddress'>E-Mail</a></td>
        </tr>";

    addpregnav("/user.php\?sort=(login|name|acctid|level|laston|lastip|uniqueid)/");

    $rn=0;
    $int_count = db_num_rows($result);
    for ($i=0;$i< $int_count;$i++)
    {
        $row=db_fetch_assoc($result);
        $loggedin=user_get_online(0,$row,true);
        $laston=round((strtotime(date('r'))-strtotime($row[laston])) / 86400,0).' Tage';
        if (substr($laston,0,2)=='1 ')
        {
            $laston='1 Tag';
        }
        if (date('Y-m-d',strtotime($row[laston])) == date('Y-m-d'))
        {
            $laston='Heute';
        }
        if (date('Y-m-d',strtotime($row[laston])) == date('Y-m-d',strtotime(date('r').'-1 day')))
        {
            $laston='Gestern';
        }
        if ($loggedin)
        {
            $laston='Jetzt';
        }
        $row['laston']=$laston;
        if ($row[$order]!=$oorder)
        {
            $rn++;
        }
        $oorder = $row[$order];
        $str_output .= "<tr class='".($rn%2?"trlight":"trdark")."'>";

        $str_output .= "<td>";

        //ADDED LOG OFF HERE
        $str_output .= "[<a href='user.php?op=edit&amp;userid=$row[acctid]'>Edit</a>|"
                .create_lnk('Del','su_delete.php?ids[]='.$row['acctid'].'&ret='.urlencode(calcreturnpath()) ).'|'.
                create_lnk('Ban','su_bans.php?op=edit_ban&ids[]='.$row['acctid'].'&ret='.urlencode(calcreturnpath()) ).'|'.
                create_lnk('Logs','su_logs.php?op=search&type=debuglog&account_id='.$row['acctid']).'|'.
                ($access_control->su_check(access_control::SU_RIGHT_USERDISCU) ? create_lnk('Disku','su_userdiscu.php?op=new&id='.$row['acctid']) : '').'|'.
                "<a href='user.php?op=logoff&amp;userid=$row[acctid]'>Logout</a>]";
        addnav("","user.php?op=edit&userid=".(int)$row['acctid']);
        addnav("","user.php?op=setupban&userid=".(int)$row['acctid']);
        //ADDED LOG OFF HERE

        addnav("","user.php?op=logoff&userid=".(int)$row['acctid']);

        $str_output .= "</td><td>";
        $str_output .= $row['login'];
        $str_output .= "</td><td>";
        $str_output .= $row['name'];
        $str_output .= "</td><td>";
        $str_output .= $row['acctid'];
        $str_output .= "</td><td>";
        $str_output .= $row['level'];
        $str_output .= "</td><td>";
        $str_output .= $row['laston'];
        $str_output .= "</td><td>";
        $str_output .= $row['lastip'];
        $str_output .= "</td><td>";
        $str_output .= $row['uniqueid'];
        $str_output .= "</td><td>";
        $str_output .= $row['emailaddress'];
        $str_output .= "</td>";
        
        $str_output .= "</tr>";
    }
    $str_output .= "</table>";
}
output($str_output);
page_footer();
?>


