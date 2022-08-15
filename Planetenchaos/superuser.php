
<?php

require_once 'common.php';

isnewday(2);

addcommentary();

addnav('Brunnenplatz','village.php');

addnav('~*~');

addnav('Mondengrotte','superuser.php');

$session['user']['standort'] == 'Mondengrotte';

if ($_GET['op'] == 'newsdelete')

    {

    db_query('DELETE FROM news WHERE newsid='.$_GET['newsid']);

    $return = $_GET['return'];

    $return = preg_replace('"[?&]c=[[:digit:]-]*"','',$return);

    $return = substr_c($return,strrpos_c($return,'/')+1);

    redirect($return);

    }

if ($_GET['op'] == 'commentdelete')

    {

    db_query('DELETE FROM commentary WHERE commentid='.$_GET['commentid']);

    $return = $_GET['return'];

    $return = preg_replace('"[?&]c=[[:digit:]-]*"','',$return);

    $return = substr_c($return,strrpos_c($return,'/')+1);

    if (strpos_c($return,'?')===false && strpos_c($return,'&')!==false)

        {

        $x = strpos_c($return,'&');

        $return = substr_c($return,0,$x-1).'?'.substr_c($return,$x+1);

        }

    redirect($return);

    }

if ($_GET['op'] == 'dbrepair')

    {

    $result = db_query('SHOW TABLES');

    for ($i = 0; $i < db_num_rows($result); $i ++)

        {

        list($key,$val) = each(db_fetch_assoc($result));

        db_query('REPAIR TABLE '.$val);

        output($val.' repariert`n`n');

        }

    output('erledigt`n`n');

}

page_header('Mondengrotte');

if ($_GET['op'] == 'checkcommentary')

    {

    viewcommentary("' or '1'='1","X",100);

    }

elseif ($_GET['op'] == 'bounties')

    {

    output('`c`bDie Kopfgeldliste`b`c`n');

    $result = db_query('SELECT name, alive, sex, level, laston, loggedin, lastip, uniqueid, bounty FROM accounts WHERE bounty>0 ORDER BY bounty DESC');

    rawoutput('<table border="0" cellpadding="2" cellspacing="1" bgcolor="#999999"><tr class="trhead"><th>Kopfgeld</th><th>Level</th><th>Name</th><th>Ort</th><th>Geschlecht</th><th>Status</th><th>Zuletzt da</th></tr>');

    for ($i = 0; $i < db_num_rows($result); $i ++)

        {

        $row = db_fetch_assoc($result);

        rawoutput('<tr class="'.($i%2?'trdark':'trlight').'"><td>');

        output('`^'.$row['bounty'].'`0');

        rawoutput('</td><td>');

        output('`^'.$row['level'].'`0');

        rawoutput('</td><td>');

        output('`&'.$row['name'].'`0');

        rawoutput('</td><td>');

        $loggedin = (date('U') - strtotime($row['laston']) < getsetting('LOGINTIMEOUT',900) && $row['loggedin']);

        output($row['location']?'`3Kneipe`0':($loggedin?'`#Online`0':'`3Die Felder`0'));

        rawoutput('</td><td>');

        output($row['sex']?'`!Weiblich`0':'`!MÃ¤nnlich`0');

        rawoutput('</td><td>');

        output($row['alive']?'`1Lebt`0':'`4Tot`0');

        rawoutput('</td><td>');

        //$laston=round((strtotime("0 days")-strtotime($row['laston'])) / 86400,0)." Tage";

        $laston = round((strtotime(date('c'))-strtotime($row['laston']))/86400,0).' Tage';

        if (substr_c($laston,0,2) == '1 ')

            $laston = '1 Tag';

        if (date('Y-m-d',strtotime($row['laston'])) == date('Y-m-d'))

            $laston = 'Heute';

        if (date('Y-m-d',strtotime($row['laston'])) == date('Y-m-d',strtotime(date('c').'-1 day')))

            $laston = 'Gestern';

        if ($loggedin)

            $laston = 'Jetzt';

        output($laston);

        rawoutput('</td></tr>');

        }

    rawoutput('</table>');

    }

else

    {

    output('`^Du tauchst in eine geheime HÃ¶hle unter, die nur wenige kennen. Dort wirst du von einigen '.($session['user']['sex']?'muskulÃ¶sen MÃ¤nnern mit nacktem OberkÃ¶rper':'einigen spÃ¤rlich bekleideten Frauen').' empfangen, die dir mit Palmwedeln entgegen winken und dir anbieten, dich mit Trauben zu fÃ¼ttern, wÃ¤hrend du auf einer mit Seide bedeckten griechisch-rÃ¶mischen Liege faulenzt.`n`n');

    viewcommentary('Mondengrotte','Mit anderen unterhalten:',25,'sagt');

    addnav('~Aktionen~');

    addnav('Anfragen','viewpetition.php');

    addnav('Biografien','bios.php');

    addnav('Schwarzes Brett','innboard.php');

    addnav('Kopfgeldliste','superuser.php?op=bounties');

    if (getsetting('avatare',0) == 1)

        addnav('Avatare','avatars.php');

    if ($session['user']['superuser'] >= 3)

        {

        addnav('Kommentare','superuser.php?op=checkcommentary');

        addnav('Spendenseite','donators.php');

        addnav('Retitler','retitle.php');

        addnav('Faillog & Mail','logs.php');

        if ($session['user']['superuser'] >= 5)

            addnav('Debuglog','debuglog.php');

        addnav('Datenbank reparieren','superuser.php?op=dbrepair');

        }

    addnav('~Editoren~');

    addnav('Monster-Editor','creatures.php');

    addnav('Spott-Editor','taunt.php');

    addnav('Waffen-Editor','weaponeditor.php');

    addnav('RÃ¼stungs-Editor','armoreditor.php');

    if ($session['user']['superuser'] >= 3)

        {

        addnav('User-Editor','user.php');

        addnav('Stalltier-Editor','mounts.php');

        addnav('Haustier-Editor','pets.php');

        addnav('Item-Editor','itemeditor.php');

        addnav('FlÃ¼che','fluch.php');

        addnav('Zauber','zauber.php');

        }

    if ($session['user']['superuser'] > 5)

        addnav('Hausmeister','suhouses.php');

    addnav('Wortfilter','badword.php');

    addnav('~Mechanik~');

    if ($session['user']['superuser'] > 5)

        addnav('Spieleinstellungen','configuration.php');

    addnav('HerfÃ¼hrende URLs','referers.php');

    addnav('Statistiken','stats.php');

    if ($session['user']['superuser'] > 5)

        addnav('Massen-Email','email.php');

    }

page_footer();

?>

