
<?php

// 11092004

/*setweather.php
An element of the global weather mod Version 0.5
Written by Talisman
Latest version available at http://dragonprime.cawsquad.net

translation: anpera
*/



// Wenn es Zeit zum Löschen veralteter Inhalte ist:
$int_last_cleanup = strtotime(getsetting('lastcleanup','0000-00-00 00:00:00'));
$int_cleanup_interval = getsetting('cleanupinterval',43200);
$int_expected_cleanup = $int_last_cleanup + $int_cleanup_interval;

if( $int_expected_cleanup < time() ) {
                
        savesetting('lastcleanup',date('Y-m-d H:i:s',$int_expected_cleanup));
        cleanup();
                
}
// END cleanup



/*// Vendor in town?
$chance=e_rand(1,4);
if ($chance==2)
{
        savesetting("vendor",1);
        $sql = 'INSERT INTO news(newstext,newsdate,accountid) VALUES (\'`qDer Wanderhändler ist heute in der Stadt!`0\',NOW(),0)';
        db_query($sql) or die(db_error($link));
}
else
{
        savesetting("vendor","0");
        $sql = 'INSERT INTO news(newstext,newsdate,accountid) VALUES (\'`qKeine Spur vom Wanderhändler...`0\',NOW(),0)';
        db_query($sql) or die(db_error($link));
} */

// Other hidden paths
$spec='Keines';
$what=e_rand(1,3);
if ($what==1) $spec='Waldsee';
if ($what==3) $spec='Orkburg';
savesetting('dailyspecial',$spec);

/* deaktiviert by Silva
// Gamedate-Mod by Chaosmaker
if (getsetting('activategamedate',0)==1) 
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
        savesetting('gamedate',$date);
}
// END Gamedate-Mod
*/

// Bestimmte Einstellungen werden nur noch einmal pro Tag aktualisiert - by Silva
$newdate = ''.convert_rpdate().'';
$olddate = getsetting('gamedate','0000-01-01');
if ($olddate != $newdate)
{
        // Ingame-Datum
        savesetting('gamedate',$newdate);
        // Wetter
        set_weather();
        // RPP-Aktionen - berechnen, wann Aktionen als Nächstes starten sollen
        $arr_d = array();
        $str_get_d = getsetting('rpp_time-limited_offers_periods','');
        if(strlen($str_get_d) > 1) $arr_d = unserialize($str_get_d);
        foreach($arr_d AS $k => $v)
        {
                if(date('Y-m-d',strtotime($v['nextstart'])) == date('Y-m-d'))
                {
                        $arr_d[$k]['laststart'] = $v['nextstart'];
                        $time_nextdate = date('Y-m-d',strtotime($v['nextstart'].' + '.($v['dauer'] + $v['wiederh']).' weeks'));
                        $arr_d[$k]['nextstart'] = $time_nextdate;
                        $str_d = serialize($arr_d);
                        savesetting('rpp_time-limited_offers_periods',$str_d);
                }
        }
        // end
        // RPP-Verdopplung aktiv? Dann runterzählen
        $doppeltrpp_dauer = (int)getsetting("doppeltrpp_dauer",0);
        if($doppeltrpp_dauer > 0) {
                $doppeltrpp_dauer--;
                savesetting('doppeltrpp_dauer',''.$doppeltrpp_dauer.'');
        }
        // end
        // Wanduhr-Zauber zurücksetzen
        $sqlh = db_query("SELECT houseid AS hid,cornerstone AS c,owner FROM houses WHERE cornerstone != ''");
        while($rowh = db_fetch_assoc($sqlh)) {
            systemmail($rowh['owner'],'`^Wanduhr-Zauber hat seine Wirkung verloren','`FDer Zauber der Wanduhr, der auf deinem Haus gelegen hat, hat seine Wirkung verloren. Die Zeiger der Uhr stehen wieder still.');
            debuglog('Wanduhr-Zauber hat seine Wirkung verloren',0,false,$rowh['owner']);
        }
        db_query("UPDATE houses SET status = cornerstone, cornerstone = '' WHERE cornerstone != ''");
        // end
        // Aprilscherz 2016 -> alle Werte zurücksetzen, sodass jeder Char neu wählen kann, auf welcher Seite er kämpft
        if(getsetting('forest_fightagainstspecialmonsters',0) == 1) {
            db_query("UPDATE settings s SET s.value = 0 WHERE s.setting = 'forest_fightagainstspecialmonsters_beaten_villains' OR s.setting = 'forest_fightagainstspecialmonsters_beaten_heroes'");
            db_query("UPDATE account_extra_info SET forest_special = 0");
        }
}
// END tägliche Einstellungen

// Häuserangriffe zurücksetzen
db_query('UPDATE houses SET attacked=0 WHERE attacked > 0');

// GILDENMOD
dg_update_guilds();
// END GILDENMOD

// Zufallskommentarhistory leeren
savesetting('rcomhistory',' ');

// Dorfrat (Fürstenersatz)
$timestamp = time();
$month = date("n",$timestamp);
$saved_month = getsetting("saved_month",12);
// Monatliches Rücksetzen der Dorfratoptionen für Steuer und Haft
$igmonth_stamp = getsetting('gamedate','0005-01-01');
$igmonth = (int)substr($igmonth_stamp,5,2);
$saved_igmonth = getsetting("saved_igmonth",9);
if($igmonth != $saved_igmonth)
{
        savesetting("prisonchange",1);
        savesetting("taxchange",1);
        savesetting("saved_igmonth",$igmonth);
}
if($month != $saved_month)
{
        savesetting("callvendor",getsetting("callvendormax",5));
        savesetting("saved_month",$month);
}

/*// Ostern-Worträtsel:
if((date('d.m.') == '27.03.' || date('d.m.') == '28.03.') && date('G') >= '10' && date('G') < '20') {
    easteregg_changeplace();
}
// end*/

?>

