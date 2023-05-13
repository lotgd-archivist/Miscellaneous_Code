
<?php

// 15082004

// Altar of Rebirth
// Idea by Luke
// recoding and german version by anpera

// modded by talion: Umbenennung für die Spieler gegen DP

require_once("common.php");

page_header("Schrein der Erneuerung");

$int_rename_dp = getsetting('user_rename',1000);
$titles = unserialize( stripslashes(getsetting('title_array',null)) );
$title = addslashes($titles[0][$session['user']['sex']]);

output("`c`b`6Der Schrein der Erneuerung`0`b`c`n");

if ($_GET['op']=="rebirth1") //Prüfung und Ausgabe der Verluste
{
    $sql = 'SELECT ctitle,cname FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
    $res = db_query($sql);
    $row_extra = db_fetch_assoc($res);

    $full=$_GET['full'];
    $n=$session['user']['name'];
    $neu = ($row_extra['cname'] ? $row_extra['cname'] : $session['user']['login']);

    if ($full=="true")
    {
        output("`6Du legst alle deine Besitztümer ab und beginnst mit dem beschriebenen Ritual. Noch einmal wollen die Götter von dir die Bestätigung, dass du dir diesen Schritt gut überlegt hast. Du wirst `balles`b verlieren, wenn du fortfährst. Du wirst zu:`n`n");
        if ($row_extra['ctitle'])
        {
            output("`6Name: `4$n`n");
        }
        else
        {
            output("`6Name: `4".$title." $neu`n");
        }
        output("`6Lebenspunkte: `410
        `n`6Level: `41
        `n`6Angriff: `41
        `n`6Verteidigung: `41
        `n`6Erfahrung: `40
        `n`6Gold: `4".getsetting("newplayerstartgold",10)."
        `n`6Edelsteine: `40
        `n`6Du verlierst deine Waffe, deine Rüstung und dein gesamtes Inventar.
        `n`6Du vergisst deine Rasse und alle besonderen Fähigkeiten.
        `n".($session['user']['house']?"Du verlierst dein Haus.`n":'')
        .($session['user']['hashorse']?"Du verlierst dein Tier.`n":'')
        .($session['user']['guildid']?"Du verlierst deine Gilde.`n":'')
        .($session['user']['profession']?"Du verlierst dein Amt.`n":'')
        ."Du verlierst alle Heldenpunkte.
        `n
        `n`bBist du zu diesem Schritt wirklich bereit?`b
        `n
        `n
        `n`0".
        create_lnk('Charakter neu beginnen','rebirth.php?op=rebirth2&full='.$full,true,true,'Willst du deinen Charakter wirklich neu starten?'));
    }
    else //if ($full=="false")
    {
        output("`n`6Du legst alle deine Besitztümer ab und beginnst mit dem beschriebenen Ritual. Noch einmal wollen die Götter von dir die Bestätigung, dass du dir diesen Schritt gut überlegt hast. Du wirst `beiniges`b verlieren, wenn du fortfährst. Du wirst zu:
        `n
        `n`6Name: `4".$session['user']['name']."
        `n`6Lebenspunkte: `4".($session['user']['level']*10)."
        `n`6Level: `4".$session['user']['level']."
        `n`6Angriff: `4".$session['user']['level']."
        `n`6Verteidigung: `4".$session['user']['level']."
        `n`6Erfahrung: `4".$session['user']['experience']."
        `n`6Gold: `40
        `n`6Edelsteine: `40
        `n`6Du verlierst deine Waffe, deine Rüstung und dein gesamtes Inventar.
        `n`6Du vergisst deine Rasse und alle besonderen Fähigkeiten.
        `n".($session['user']['house']?"Du verlierst dein Haus.`n":'')
        .($session['user']['hashorse']?"Du verlierst dein Tier.`n":'')
        .($session['user']['guildid']?"Du verlierst deine Gilde.`n":'')
        .($session['user']['profession']?"Du verlierst dein Amt.`n":'')
        ."Du kannst alle Heldenpunkte neu vergeben.
        `n
        `n`bBist du zu diesem Schritt wirklich bereit?`b
        `n
        `n
        `n`0".create_lnk('Charakter neu beginnen','rebirth.php?op=rebirth2&full='.$full,true,true,'Willst du deinen Charakter wirklich zurücksetzen?'));;
    }
    addnav("Zurück zum Club","rock.php");
}

else if ($_GET['op']=="rebirth2") //Erneuerung durchführen
{
    $uid=$session['user']['acctid'];
    require_once(LIB_PATH.'house.lib.php');

    $sql = 'SELECT ctitle,cname FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
    $res = db_query($sql);
    $row_extra = db_fetch_assoc($res);

    $neu = ($row_extra['cname'] ? $row_extra['cname'] : $session['user']['login']);

    // Gemeinsamkeiten
    if ($session['user']['guildid'] > 0)
    {
        require_once(LIB_PATH.'dg_funcs.lib.php');
        dg_remove_member($session['user']['guildid'],$session['user']['acctid'],true);
    }
    $session['user']['guildid']=0;
    $session['user']['guildfunc']=1;
    $session['user']['guildrank']=10;

    $session['user']['hashorse']=0;
    $session['user']['deathpower']=0;
    $session['user']['profession']=0;
    $session['user']['expedition']=0;
    $session['user']['bounty']=0;
    $session['user']['bufflist']="";
    $session['user']['goldinbank']=0;
    $session['user']['gems']=0;
    $session['user']['gemsinbank']=0;

    $session['user']['battlepoints']=0;
    $session['user']['drunkenness']=0;

    $session['user']['profession'] = 0;

    $session['user']['daysinjail']=0;

    $session['user']['punch']=1;

    $session['user']['dragonpoints']="";

    // Goldenes Ei
    if ($session['user']['acctid']==getsetting('hasegg',0))
    {
        savesetting('hasegg',stripslashes(0));
        $sql = 'UPDATE items SET owner=0 WHERE tpl_id="goldenegg"';
        db_query($sql);
    }

    if ($session['user']['house'])
    {
        // Hausschlüssel auf Verloren setzen
        $sql = 'UPDATE keylist SET owner=0 WHERE owner='.$uid.' AND type='.HOUSES_KEY_DEFAULT;
        db_query($sql);

        // Wenn Haus noch im Bau, auf leeres Grundstück zurücksetzen, sonst auf verlassen
        $sql = 'UPDATE houses SET owner=0,build_state=IF(
                                build_state = '.HOUSES_BUILD_STATE_INIT.',
                                    '.HOUSES_BUILD_STATE_EMPTY.',
                                    '.HOUSES_BUILD_STATE_ABANDONED.'
                                ),lastchange=NOW()
                WHERE owner='.$uid.'';
        db_query($sql);

        // Gemächer auf 0 setzen
        $sql = 'UPDATE house_extensions SET owner=0 WHERE owner='.$uid;
        db_query($sql);

        // EInladungen in Gemächer löschen (des Gelöschten und im Besitz des Gelöschten)
        $sql = 'DELETE FROM keylist WHERE type='.HOUSES_KEY_PRIVATE.' AND (value3='.$uid.' OR owner='.$uid.')';
        db_query($sql);
    }
    $session['user']['house']=0;

    // Besitzukrunden für Privatgemächer zurücksetzen
    db_query('UPDATE house_extensions SET owner=0 WHERE owner='.$session['user']['acctid']);

    $sql="UPDATE keylist SET owner=0 WHERE owner=".$session['user']['acctid']." AND type=".HOUSES_KEY_DEFAULT;
    db_query($sql);

    $sql="DELETE FROM keylist WHERE (owner=".$session['user']['acctid']." OR value3=".$session['user']['acctid'].") AND type=".HOUSES_KEY_PRIVATE;
    db_query($sql);

    // Inventar löschen
    item_delete(' owner='.$session['user']['acctid'],0 );

    // Einladungen in Expi-Zelt zurücksetzen
    // Runenstatus und Rezeptbuch leeren
    user_set_aei(array('DDL_tent'=>0,'runes_ident'=>'','combos'=>''));
    user_set_aei(array('DDL_tent'=>0),-1,'DDL_tent='.$session['user']['acctid']);

    // Fürstentitel vakant setzen
    $fuerst = stripslashes(getsetting('fuerst',''));
    if ($fuerst == $neu)
    {
        savesetting('fuerst','');
    }

    // Einträge im Strafregister löschen
    db_query('DELETE FROM cases WHERE accountid='.$session['user']['acctid']);
    db_query('DELETE FROM crimes WHERE accountid='.$session['user']['acctid']);

    //begonnenes Tauschquest zurücksetzen
    if($session['user']['exchangequest']<29)
    {
        $session['user']['exchangequest']=0;
    }

    if ($_GET['full']=="true")
    {
        addnews("`#".$session['user']['name']."`# hat seinem bisherigen Leben ein Ende gesetzt und einen Neuanfang beschlossen.");
        if (!$row_extra['ctitle'])
        {
            $session['user']['name']=$title.' '.$neu;
        }
        $session['user']['title']=$title;

        user_set_aei(array('ctitle'=>'','cname'=>'','ctitle_backup'=>''));

        $session['user']['level']=1;
        $session['user']['maxhitpoints']=10;
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        $session['user']['attack']=1;
        $session['user']['defence']=1;
        $session['user']['gold']=getsetting("newplayerstartgold",0);
        $session['user']['experience']=0;

        $session['user']['age']=0;
        $session['user']['reputation']+=25;

        $session['user']['dragonkills']=0;
        $session['user']['specialty']=0;
        $session['user']['sex']=CCharacter::SEX_UNDEF;
        foreach($session['user']['specialtyuses'] as $key=>$val)
        {
            $session['user']['specialtyuses'][$key]=0;
        }

        $session['user']['weapon']="Fäuste";
        $session['user']['armor']="Straßenkleidung";

        if ($session['user']['marriedto']>0 && $session['user']['marriedto']<4294967295 && $session['user']['charisma']>=999)
        {
            user_update(
                array
                (
                    'marriedto'=>0,
                    'charisma'=>0
                ),
                $session['user']['marriedto']
            );
            systemmail($session['user']['marriedto'],"`6".$session['user']['name']." ist nicht mehr der selbe`0","`6".$session['user']['name']."`6 hat sich ein neues Leben gegeben. Ihr seid nicht länger verheiratet.");
        }
        $session['user']['charisma']=0;
        $session['user']['marriedto']=0;
        $session['user']['weaponvalue']=0;
        $session['user']['armorvalue']=0;
        $session['user']['resurrections']=0;
        $session['user']['weapondmg']=0;
        $session['user']['armordef']=0;
        $session['user']['charm']=0;
        $session['user']['race']='';
        $session['user']['dragonage']=0;

        debuglog("REBIRTH vollständige Wiedergeburt - Id : ".$session['user']['acctid']." - Name : ".$session['user']['login']." - UniqueId : ".$session['user']['uniqueid']);

        addhistory('`^`b'.addslashes($session['user']['login']).' hat ein neues Leben begonnen!`b');

        //am Ausgang gibts Zwangslogout
        //by Salator: Geht das nicht auch ohne? So klappt das nicht mit der Umbenennung
        //$session['user']['laston'] = $session['user']['lasthit'] = date("Y-m-d H:i:s",time()-(86500/getsetting("daysperday",4))." seconds");
        $session['user']['lasthit']=date("Y-m-d H:i:s",strtotime(date("r")."-".(86500/getsetting("daysperday",4))." seconds"));
        output("`6Du stimmst zu.
        `nWährend du das Ritual durchführst und dich von deinem Besitz löst, spürst du auch deine Lebenkraft, deine Erfahrung und schließlich all deine Fähigkeiten schwinden. Du vergisst dein ganzes bisheriges Leben. Du fällst in eine lange Ohnmacht...");
    }
    else //if ($full=="false")
    {
        addnews("`#".$session['user']['name']."`# hat einen radikalen Lebenswandel beschlossen.");
        $session['user']['maxhitpoints']=$session['user']['level']*10;
        $session['user']['attack']=$session['user']['level']*2;
        $session['user']['defence']=$session['user']['level']*2;
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        $session['user']['gold']=getsetting("newplayerstartgold",0);
        $session['user']['reputation']+=25;
        $session['user']['specialty']=0;
        //Anwendungen hier nicht zurücksetzen
        $session['user']['weapon']="Fäuste der Erneuerung";
        $session['user']['armor']="Haut der Erneuerung";
        $session['user']['weaponvalue']=0;
        $session['user']['armorvalue']=0;
        $session['user']['weapondmg']=$session['user']['level']; 
        $session['user']['armordef']=$session['user']['level'];
        $session['user']['charm']=1;
        $session['user']['race']='';

        debuglog("RENEWAL Erneuerung (auf Level ".$session['user']['level'].')');

        $session['user']['lasthit']=date("Y-m-d H:i:s",strtotime(date("r")."-".(86500/getsetting("daysperday",4))." seconds"));
        output("`6Du stimmst zu.
        `nWährend du das Ritual durchführst und dich von deinem Besitz löst, spürst du auch deine Lebenkraft und all deine Fähigkeiten schwinden. Du vergisst vieles aus deinem bisherigen Leben und fällst in eine lange Ohnmacht...");

        addhistory('`^`bHat '.($session['user']['sex'] ? 'ihr' : 'sein').' Leben radikal gewandelt!`b');
    }

    if ($int_rename_dp && ($session['user']['donation'] - $session['user']['donationspent']) >= $int_rename_dp)
    {
        output('`n`n`0Genau jetzt eröffnet sich dir die Möglichkeit einer Umbenennung:
        `n`n'.create_lnk('Umbenennung!','rebirth.php?op=rename',true,true));
    }
    saveuser();
}
elseif ($_GET['op'] == 'rename') //optionale Namensänderung
{
    $str_msg = '';
    $shortname = $session['user']['login'];
    $str_name = $shortname;

    if ($_GET['act'] == 'save')
    {
        $bool_save = true;

        // Name checken
        // Auf jeden Fall Formatierungstags raus
        $str_name = strip_appoencode(trim($_POST['newname']),3);

        // Auf Korrektheit prüfen
        $str_valid = user_rename(0, stripslashes($str_name));

        if (true !== $str_valid)
        {

            switch ($str_valid)
            {

            case 'login_banned':
                $str_msg .= 'Dieser Name ist gebannt!';
                break;

            case 'login_blacklist':
                $str_msg .= 'Dieser Name ist verboten!';
                break;

            case 'login_dupe':
                $str_msg .= 'Diesen Namen gibt es leider schon!';
                break;

            case 'login_tooshort':
                $str_msg .= 'Dein gewählter Name ist zu kurz (Min. '.getsetting('nameminlen',3).' Zeichen)!';
                break;

            case 'login_toolong':
                $str_msg .= 'Dein gewählter Name ist zu lang (Max. '.getsetting('namemaxlen',3).' Zeichen)!';
                break;

            case 'login_badword':
                $str_msg .= 'Dein gewählter Name enthält unzulässige Begriffe!';
                break;

            case 'login_spaceinname':
                $str_msg .= 'Dein gewählter Name enthält Leerzeichen, was leider nicht erlaubt ist!';
                break;

            case 'login_specialcharinname':
                $str_msg .= 'Dein gewählter Name enthält Sonderzeichen, was leider nicht erlaubt ist!';
                break;

            case 'login_criticalcharinname':
                $str_msg .= 'Dein gewählter Name enthält eines der folgenden Zeichen, die für einen Namen nicht geeignet sind:`n
                '.str_replace('\\','',getsetting('criticalchars',''));
                break;

            case 'login_titleinname':
                $str_msg .= 'Dein gewählter Name enthält einen Titel, der ein Teil des Spiels ist!';
                break;

                default:
                $str_msg .= 'Irgendwas stimmt mit deinem Namen nicht, ich weiß nur nicht was ; ) Schreibe bitte eine Anfrage!';
                break;

            }

            output('`n`n`c`$`bFehler:`b `^'.$str_msg.'`c');
            $bool_save = false;

        }

        if ($bool_save)
        {

            $session['user']['donationspent'] += $int_rename_dp;

            // eintrag in history
            addhistory('`^`b'.$shortname.' hat einen neuen Namen angenommen!`b');

            debuglog(' änderte Namen. Vorher: '.$shortname);

            require_once(LIB_PATH.'board.lib.php');
            board_add('namechange',100,0,'Früherer Name: '.$shortname);

            // User in Registratur setzen
            user_set_aei(array('ctitle'=>'','namecheck'=>0,'namecheckday'=>1,'ctitle_backup'=>''));

            // Gesamtname aktualisieren
            user_set_name(0);

            output('`n`@`cGratuliere!`n`&Du bezahlst '.$int_rename_dp.' Donationpoints und bist von nun an bekannt unter dem Namen `b'.$session['user']['name'].'`b!`c');

        }

    }

    if (!$bool_save)
    {

        $str_lnk = 'rebirth.php?op=rename&act=save';
        addnav('',$str_lnk);

        output('<form action="'.$str_lnk.'" method="POST">
        `n`n`&Falls du dir nun für `b'.$int_rename_dp.' Donationpoints`b einen neuen Namen suchen möchtest,
        gib ihn in diesem Feld ein (ohne Farbcodes und Titel!):`n`n
        <input type="text" value="',true);
                rawoutput($str_name);
                output('" name="newname" size="25" maxlength="25">`n`n
        <input type="submit" value="Name ändern!">
        </form>');

        addnav('Abbruch','news.php');
    }
}

else //Startscreen
{
    checkday();

    output("`6Du gehst zu einer bedrohlich wirkenden Tür im hinteren Bereich des Clubs. ");
    if ($session['user']['dragonkills']>=getsetting('rebirth_dks',5))
    {
        addnav("Vollständige Wiedergeburt","rebirth.php?op=rebirth1&full=true");
        addnav("Erneuerung","rebirth.php?op=rebirth1&full=false");
        output("Wie von selbst öffnet sich die Tür. Dahinter siehst du einen mächtigen Altar der Götter. Du spürst förmlich, dass sich hier dein Leben grundlegend ändern kann. Eine Tafel vor dem Altar bestätigt dieses Gefühl: \"`4Hier kannst du die Fehler deiner Vergangenheit rückgängig machen und um einen Neuanfang bitten. Wisse aber, dass diese Entscheidung dazu die letzte deines Lebens darstellt. Du wirst morgen ohne deine weltlichen Güter und ohne Erinnerung auf dem Dorfplatz aufwachen. Nur mit der Chance ausgerüstet, es noch einmal besser zu machen.`6\"
        `n`nWillst du neu beginnen?
        `n`n`bVollständige Wiedergeburt:`b
        `nDu würdest wieder als ".$title." mit nichts als den gesammelten Donationpoints im Dorf aufwachen. Dein Leben würde beendet und im selben Moment von vorne beginnen.
        `n`\$Diese Option ist für Krieger gedacht, die bereits alles erreicht haben, oder die keinen Sinn mehr in ihrem einsamen Leben oberhalb der normalen Gesellschaft sehen.
        `n`n");
// Bad idea for balance...?
        output("`6`bErneuerung:`b
        `nHeldentaten, Titel, Ehepartner und deine Erinnerung bleiben dir erhalten, jedoch legst du alle anderen weltlichen Besitztümer ab und wirst es sehr schwer haben, dich wieder an das knallharte Leben mit dem Drachen zu gewöhnen. Dafür kannst du alle Heldenpunkte neu vergeben.");

        if($int_rename_dp)
        {
            output('`n`n`@`bNamensänderung:`b`n`2Möchtest du nur deinen `@Namen ändern`2, so schreibe bitte eine `@Anfrage `2in der du den Grund für diese Entscheidung sowie Wunschname, -rasse und -geschlecht nennst. Liegt ein triftiger RP Grund vor (also ein wenig mehr als `i"Der Name ist doof!") `iso kann dein Char auch ohne kompletten Verlust der weltlichen Güter umbenannt werden. Du würdest dann deine Gildenmitgliedschaft, deinen Partner und dein Amt verlieren, der Forenname wird geändert und Einträge in deine Aufzeichnungen und Dorfamt gemacht wie bisher.`n`n

            Die Preise werden pro DK berechnet. Ungefähre Zwischenwerte sind zum Beispiel:`n
            Unter 10 DKs pauschal: 100 DP`n
            20 DKs: 240 DP`n
            30 DKs: 390 DP`n
            50 DKs: 750 DP`n
            75 DKs: 1313 DP`n
            ab 100 DKs: 2000 DP`n`n

            Den genauen Preis bekommst du auf eine Anfrage hin genannt.`n

            (Die Möglichkeit einer Umbenennung im Zuge einer Erneuerung oder Wiedergeburt für '.$int_rename_dp.' DP bleibt jedoch weiterhin bestehen.)`n`n');

        }

    }
    else
    {
        output("Doch alle Versuche, diese Tür zu öffnen, schlagen fehl. Du erkundigst dich im Club nach dieser Tür und bekommst tatsächlich eine Antwort: \"`4Hinter dieser Tür steht ein mächtiger Altar der Götter. Es ist ein Altar des Vergesssens, des Todes und der Erneuerung. Nur sehr mächtigen Kriegern ist es gestattet, diesen Altar zu benutzen. Dort können sie über ihr bisheriges Leben nachdenken und um einen Neuanfang bitten. Du wirst noch ".(getsetting('rebirth_dks',5)-$session['user']['dragonkills'])." Heldentaten vollbringen müssen, bevor du den Schrein betreten kannst.`6\"");
    }

    addnav("C?Zurück zum Club","rock.php");
}
addnav("D?Zurück zum Dorf","village.php");

page_footer();
?>

