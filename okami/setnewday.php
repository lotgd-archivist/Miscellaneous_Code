
<?php
/**
 * Diese Datei enthält Dinge die bei jedem neuen Tag einmalig durchgeführt werden müssen
 */

// Wann es Zeit zum Löschen veralteter Inhalte ist:
$int_last_cleanup = strtotime(getsetting('lastcleanup','0000-00-00 00:00:00'));
$int_cleanup_interval = getsetting('cleanupinterval',43200);
$int_expected_cleanup = $int_last_cleanup + $int_cleanup_interval;


// Vendor in town?
$chance=e_rand(1,4);
if ($chance==2)
{
    savesetting('vendor',1);
    $sql = 'INSERT INTO news(newstext,newsdate,accountid) VALUES (\'`qDer Wanderhändler ist heute im Dorf!`0\',NOW(),0)';
    db_query($sql);
}
else
{
    savesetting('vendor','0');
    $sql = 'INSERT INTO news(newstext,newsdate,accountid) VALUES (\'`qKeine Spur vom Wanderhändler...`0\',NOW(),0)';
    db_query($sql);
}

// Other hidden paths
$spec='Keines';
$what=e_rand(1,3);
if ($what==1) 
{
    $spec='Waldsee';
}
if ($what==3) 
{
    $spec='Orkburg';
}
savesetting('dailyspecial',$spec);

// Gamedate-Mod by Chaosmaker
if (getsetting('activategamedate',0)==1)
{
    if (getsetting('lastlogin','x')=='x') savesetting('lastlogin',date("Y-m-d H:i:s"));
    $daysperday = getsetting('daysperday',4);
    $dayparts = getsetting('dayparts',1);
    $currdaypart = ((date('H')-1)*($daysperday/24))%$dayparts;
    $date = getsetting('gamedate','0000-01-01');
    $actdaypart = getsetting('actdaypart',0);
    //Nächster Ingame-Tag, wenn neue Tageszeit < alte Tageszeit oder wenn letzter Newday zu lange her ist
    if ($currdaypart <= $actdaypart || ((strtotime(date("Y-m-d H:i:s"))-strtotime(getsetting('lastlogin',date("Y-m-d H:i:s"))))> (($dayparts*round(24/$daysperday))*3600) ))
    {
        $date = getsetting('gamedate','0000-01-01');
        $date = explode('-',$date);
        $date[2]++;
        switch ($date[2])
        {
            case 32:
                $date[2] = 1;
                $date[1]++;
                break;
            case 31:
                if (in_array($date[1], array(4,6,9,11)))
                {
                    $date[2] = 1;
                    $date[1]++;
                }
                break;
            case 30:
                if ($date[1]==2)
                {
                    $date[2] = 1;
                    $date[1]++;
                }
                break;
            case 29:
                if ($date[1]==2 && ($date[0]%4!=0 || ($date[0]%100==0 && $date[0]%400!=0)))
                {
                    $date[2] = 1;
                    $date[1]++;
                }
        }
        if ($date[1]==13)
        {
            $date[1] = 1;
            $date[0]++;
        }
        $date = sprintf('%04d-%02d-%02d',$date[0],$date[1],$date[2]);
    }
    savesetting('actdaypart',$currdaypart);
    savesetting('lastlogin',date("Y-m-d H:i:s"));
    savesetting('gamedate',$date);
}

//Mondphase setzen und alle 28 Tage nullen
//Ist nicht sonderlich genau, aber sollte reichen
$int_moon_day = getsetting('moon_date',0);
if($int_moon_day%28 == 0)
{
    savesetting('moon_date',1);
}
else 
{
    savesetting('moon_date',$int_moon_day+1);
}

// Wetter (sollte nach Datum erfolgen)
Weather::set_weather();

// Häuserangriffe zurücksetzen
db_query('UPDATE houses SET attacked=0 WHERE attacked > 0');

// GILDENMOD
dg_update_guilds();
// END GILDENMOD

// Zufallskommentarhistory leeren
savesetting('rcomhistory',' ');

//
// START : Dinge die genau einmal monatlich durchgeführt werden müssen
//
$timestamp = time();
$month = date('n',$timestamp);
$saved_month = getsetting('saved_month',12);
savesetting('saved_month',$month);

//Belohnung für den Spieler mit den meisten Einblendungen!
if((bool)getsetting('ci_goldpresse',0))
{
    if($month != $saved_month)
    {
        $str_sql = 'SELECT acctid FROM account_extra_info ORDER BY ci_image_counter DESC, acctid DESC LIMIT 1';
        $db_result = db_query($str_sql);
        $arr_result = db_fetch_assoc($db_result);

        if(count($arr_result)>0)
        {
            systemmail($arr_result['acctid'],'`^Goldpresse Award!','`^Du hast von allen Bürgern '.getsetting('townname','Atrahor').'s am häufigsten Deine Plakette in Foren oder Homepages gezeigt. Dafür möchten wir Dir mit '.getsetting('ci_dp_for_page_impressions',50).' DP danken! Weiter so!');
            debuglog($arr_result['acctid'].'erhielt einen DP Bonus ('.getsetting('ci_dp_for_page_impressions',50).') weil er/sie die Goldpresse Plakette am häufigsten präsentierte');
            if($arr_result['acctid']==$session['user']['acctid'])
            {
                $session['user']['donation']+=getsetting('ci_dp_for_page_impressions',50);
            }
            else
            {
                user_update(
                    array
                    (
                        'donation'=>array('sql'=>true,'value'=>'donation+'.getsetting('ci_dp_for_page_impressions',50))
                    ),
                    $arr_result['acctid']
                );
            }
        }

        //Counter zurück setzen!
        $str_sql = 'UPDATE account_extra_info SET ci_image_counter = 0';
        $db_result = db_query($str_sql);
        unset ($db_result);
        unset ($arr_result);
    }
}

// Wenn Symp.system aktiv:
if((bool)getsetting('symp_active','0'))
{
    // Ingame-monatliches Rücksetzen der Fürstenoptionen für Steuer und Haft
    $igmonth_stamp = getsetting('gamedate','0005-01-01');
    $igmonth = (int)substr($igmonth_stamp,5,2);
    $saved_igmonth = getsetting('saved_igmonth',9);
    if($igmonth != $saved_igmonth) {
        savesetting('prisonchange',1);
        savesetting('taxchange',1);
        savesetting('saved_igmonth',$igmonth);
    }

    // Die Schleife springt jeden Monat genau einmal an und kürt den Spieler mit der meisten Sympathie zum Fürst von Atrahor,
    // der alte bekommt natürlich seinen Titel automatisch entzogen
    if($month != $saved_month) {
        //Ergebnis des letzten Monats speichern
        $symp_list=array();
        $sql = 'SELECT ai.acctid, ai.sympathy, a.sex, a.name FROM account_extra_info ai LEFT JOIN accounts a USING(acctid) WHERE ai.sympathy>2 ORDER BY ai.sympathy DESC, a.dragonkills DESC LIMIT 50';
        $result=db_query($sql);
        while($row=db_fetch_assoc($result))
        {
            array_push($symp_list,$row);
        }
        savesetting('old_symp_vote_list',serialize($symp_list));
        //END Ergebnis speichern
        
        savesetting('callvendor',getsetting('callvendormax',5));

        savesetting('fuerst_donations','0');

        $sql = 'SELECT ai.acctid, a.login, a.sex, ai.cname FROM account_extra_info ai LEFT JOIN accounts a USING(acctid) ORDER BY ai.sympathy DESC, a.dragonkills DESC LIMIT 1';
        $res = db_query($sql);
        $row_extra = db_fetch_assoc($res);

        // Beide Formen zurücksetzen
        user_unique_ctitle(0,'`&Fürst von '.getsetting('townname','Atrahor'));
        user_unique_ctitle(0,'`&Fürstin von '.getsetting('townname','Atrahor'));

        if($row_extra['sex']) {
            user_unique_ctitle($row_extra['acctid'],'`&Fürstin von '.getsetting('townname','Atrahor'));
        }
        else {
            user_unique_ctitle($row_extra['acctid'],'`&Fürst von '.getsetting('townname','Atrahor'));
        }

        $sql = 'UPDATE account_extra_info SET sympathy=0, symp_given=0, symp_votes=0';
        db_query($sql);
        $sql = 'TRUNCATE TABLE sympathy_votes';
        db_query($sql);

        $new = (!empty($row_extra['cname']) ? $row_extra['cname'] : $row_extra['login']);

        // Amtshandlungen löschen
        db_query('DELETE FROM boards WHERE section="fuerst_act"');

        savesetting('fuerst',addslashes($new));

        addhistory('Wahl zu'.($row_extra['sex'] ? 'r Fürstin von '.getsetting('townname','Atrahor').'!' : 'm Fürsten von '.getsetting('townname','Atrahor').'!'),1,$row_extra['acctid']);
        addhistory('`&Neue'.($row_extra['sex'] ? ' `)Fürstin' : 'r `)Fürst').' `&von '.getsetting('townname','Atrahor').': `)`b'.$new.'`b`&!',0);

        systemmail($row_extra['acctid'],'`^Du BIST Fürst'.($row_extra['sex'] ? 'in' : '').'!','`^Deine immense Beliebtheit unter den Bürgern '.getsetting('townname','Atrahor').'s hat dir zum Fürstentitel verholfen! Herzlichen Glückwunsch.');
    }

}

//IDs des Adminteams sammeln (wird z.B. vom YOM-Löschen genutzt)
$result=db_query('SELECT acctid FROM accounts WHERE superuser>0');
$str_id_list='';
while($row=db_fetch_assoc($result))
{
    $str_id_list .= ','.$row['acctid'];
}
$str_id_list=substr($str_id_list,1);
savesetting('admin_id_list',$str_id_list);

//
// ENDE : Dinge die genau einmal monatlich durchgeführt werden müssen
//

// Die Dunklen Lande
$state=getsetting('DDL-state',6);
if ($state>1 && $state<11) // Beide Lager intakt ?
{
    $order_new=getsetting('DDL_new_order',6);
    $order_act=getsetting('DDL_act_order','0');
    $balance=getsetting('DDL-balance',5);
    $balance_malus=getsetting('DDL_balance_malus',10);
    $balance_push=getsetting('DDL_balance_push',50);
    $balance_lose=getsetting('DDL_balance_lose',-10);
    $order=getsetting('DDL-order',2);

    $order_act++;

    if ($balance<=$balance_lose) // Schlecht gekämpft
    {
        if ($order==1) // Verteidigung fehlgeschlagen
        {
            addnews_ddl('`4Der Feind hat unsere Linien durchbrochen!`&');
            addnews_ddl('`&Heutiger Tagesbefehl : Warten auf Weiteres!`&');
            $state--;
            savesetting('DDL-state',$state);
            if ($state<=1) // Niederlage ?
            {
                output('`4`n`nUnser Lager wurde zerstört!`n');
                addnews_ddl('`4Flieht um Euer Leben! Unser Lager wurde zerstört!`&');
            }
            savesetting('DDL-state',$state);
            savesetting('DDL_opps','0');
        }
        elseif ($order==3) // Angriff fehlgeschlagen
        {
            addnews_ddl('`@Unser Angriff kam zum Erliegen!`&');
            addnews_ddl('`&Heutiger Tagesbefehl : Warten auf Weiteres!`&');
        }
        savesetting('DDL_act_order','0');
        savesetting('DDL-balance','0');
    }
    if ($state>1 && $state<11)
    {

        if ($order_act>=$order_new) // Neue Tagesorder
        {
            $order = getsetting('DDL-order',2);
            $chance=e_rand(1,4);

            if ($order==2)
            {
                if ($balance>=$balance_push)
                { $chance++; } //Fleißiges Kämpfen ohne Tagesbefehl erhöht die Chance auf Angriff
                if ($balance<=($balance_lose*2))
                { $chance--; } //Faulheit ohne Tagesbefehl erhöht die Chance auf feindlichen Angriff
            }

            if ($order==1) // Auf Defensive folgt nie direkt Angriff
            { $chance--; }
            elseif ($order==3) // Vice Versa
            { $chance++; }

            if ($chance<=1) // Defensiv
            {
                addnews_ddl('`&Heutiger Tagesbefehl : `4Stellungen halten!`&');
                savesetting('DDL-order',1);
                $medalpoints=getsetting('DDL-medal',10);
                if ($medalpoints<35) $medalpoints++;
                savesetting('DDL-medal',$medalpoints);
            }
            elseif ($chance>=4) // Attacke
            {
                addnews_ddl('`&Heutiger Tagesbefehl : `^Angriff!`&');
                savesetting('DDL-order',3);
                $medalpoints=getsetting('DDL-medal',10);
                if ($medalpoints<35) $medalpoints++;
                savesetting('DDL-medal',$medalpoints);
            }
            else // Nix tun
            {
                addnews_ddl('`&Heutiger Tagesbefehl : Warten auf Weiteres!`&');
                savesetting('DDL-order',2);
            }
            $balance-=$balance_malus; // Tages-Malus
            savesetting('DDL_act_order','0');
            savesetting('DDL-balance','0');
        }
        else //Alter Order bleibt
        {
            $balance-=$balance_malus; // Tages-Malus
            savesetting('DDL_act_order',$order_act);
            savesetting('DDL-balance',$balance);
        }

    }
}
else // Count-Down für Neustart
{
    $to_restart=getsetting('DDL-restart',18);
    $days_passed=getsetting('DDL-days','0');
    if ($days_passed>=$to_restart) // Reset
    {
        if ($state<=1) addnews_ddl('`2Heute wurde ein neues Lager errichtet!`&');
        if ($state>=11) addnews_ddl('`2Heute hat der Feind ein neues Lager errichtet!`&');
        savesetting('DDL_act_order','0');
        savesetting('DDL-balance','0');
        savesetting('DDL-order',2);
        savesetting('DDL-state',6);
        savesetting('DDL-days','0');
        savesetting('DDL-medal','10');
    }
    else // Counter erhöhen
    {
        $days_passed++;
        savesetting('DDL-days',$days_passed);
    }
}


//Portale
require_once(LIB_PATH.'portals.lib.php');
portal_set_newday();

//Runenstatistik
$res = db_fetch_assoc(db_query('SELECT COUNT(*) AS cnt FROM items WHERE tpl_id IN ("r_algiz","r_ansuz","r_berkana","r_dagaz","r_ehwaz","r_eiwaz","r_fehu","r_gebo","r_hagalaz","r_ingwaz","r_isa","r_jera","r_kenaz","r_laguz","r_mannaz","r_naudiz","r_othala","r_pethro","r_raidho","r_sowilo","r_teiwaz","r_thurisaz","r_dummy","r_uruz","r_wunjo")'));
$r_count = $res['cnt'];
$r_onnew = getsetting('runes_count_newday',$r_count);
savesetting('runes_count_newday',$r_count);
$r_diff = $r_count - $r_onnew;
savesetting('runes_count_diff',$r_diff);

if( $int_expected_cleanup <= time() ) {

    savesetting('lastcleanup',date('Y-m-d H:i:s',$int_expected_cleanup));
    cleanup();

}
// END cleanup

?>

