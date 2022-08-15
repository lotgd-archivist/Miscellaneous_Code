
<?php

require_once 'common.php';

page_header('Einwohnertafel');

if ($_GET['ref'])

    {

    $werber = $_GET['ref'];

    $rest = '&ref='.$werber.'';

    $rest2 = '?ref='.$werber.'';

    }

if ($session['user']['loggedin'])

    {

    checkday();

    $session['user']['standort'] = 'Tafel';

    if ($session['user']['alive'])

        addnav('Brunnenplatz','village.php');

    else

        addnav('Schattenreich','shades.php');

    }

else

    addnav('Tore der Welt','index.php'.$rest2.'');

addnav('~*~');

addnav('Ansprechpartner','list.php?op=admin'.$rest.'');

addnav('~*~');

$playersperpage = 50;

$result = db_query('SELECT COUNT(acctid) AS c FROM accounts WHERE locked=0');

$row = db_fetch_assoc($result);

$totalplayers = $row['c'];

if ($_GET['op'] == 'search')

    {

    $search = '%';

    for ($x = 0; $x < strlen_c($_POST['name']); $x ++)

        $search .= substr_c($_POST['name'],$x,1).'%';

    $search = ' AND name LIKE "'.addslashes($search).'" ';

    //addnav("List Warriors","list.php");

    }

else

    {

    $pageoffset = (int)$_GET['page'];

    if ($pageoffset > 0)

        $pageoffset --;

    $pageoffset *= $playersperpage;

    $from = $pageoffset + 1;

    $to = min($pageoffset+$playersperpage,$totalplayers);

    $limit = ' LIMIT '.$pageoffset.', '.$playersperpage.' ';

    }

addnav('Seiten');

for ($i = 0; $i < $totalplayers; $i += $playersperpage)

    addnav('Seite '.($i/$playersperpage+1).' ('.($i+1).'-'.min($i+$playersperpage,$totalplayers).')','list.php?page='.($i/$playersperpage+1));

switch ($_GET['op'])

    {

    case 'admin':

        $res = db_query('SELECT superuser, name, acctid FROM accounts WHERE superuser>2 ORDER BY superuser ASC');

        rawoutput('<table align="center" cellspacing="2"><tr class="trhead"><th align="center">Name</th><th align="center">Position</th><th align="center">Bereich</th></tr>');

        for ($i = 0; $i < db_num_rows($res); $i ++)

            {

            $row = db_fetch_assoc($res);

            if ($row['superuser'] == 3)

                {

                $titel = 'Berater';

                $arbeit = 'Biohilfe';

                }

            if ($row['superuser'] == 4)

                {

                $titel = 'Oberste Berater';

                $arbeit = 'Anfragen';

                }

            if ($row['superuser'] == 5)

                {

                $titel = 'Stadtwache';

                $arbeit = 'Sperren';

                }

            if ($row['superuser'] == 6)

                {

                $titel = 'Herrscher';

                $arbeit = 'Regierung';

                }

            if ($row['superuser'] == 7)

                {

                $titel = '<img src="images/tot.gif">';

                $arbeit = 'Allen das Leben zur HÃ¶lle machen ;)';

                }

            rawoutput('<tr><td>');

            if ($session['user']['loggedin']) 

                {

                rawoutput('<a href="mail.php?op=write&to='.$row['acctid'].'" target="_blank" onClick="'.popup('mail.php?op=write&to='.$row['acctid'].'').';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Nachricht schreiben" border="0"></a>');

                }

            elseif ($session['user']['loggedin'])

                {

                rawoutput('<a href="adminmail.php?op=write&to='.$row['acctid'].'" target="_blank" onClick="'.popup('adminmail.php?op=write&to='.$row['acctid'].'').';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Nachricht schreiben" border="0"></a>');

                }

            output('`&'.$row['name'].'`0');

            output('</td><td align="center">'.$titel.'`0</td><td>'.$arbeit.'`0</td></tr>',true);

            }

        rawoutput('</table>');

    break;

    case 'repnav':

        output('Die Nerven von '.$_GET['id'].' wurde repariert!');

        db_query('UPDATE accounts SET allowednavs="" WHERE acctid='.$_GET['id'].'');

        $file = fopen('./cache/c'.$_GET['id'].'.txt','wb');

        fwrite($file,'');

        fclose($file);

    break;

    default:

        // Order the list by level, dragonkills, name so that the ordering is total! Without this, some users would show up on multiple pages and some users wouldn't show up

        if ($_GET['page'] == '' && $_GET['op'] == '')

            {

            output('`c`bDiese Einwohner sind gerade unterwegs`b`c`n');

            $sql = 'SELECT acctid, name, alive, location, sex, level, laston, loggedin, race, standort FROM accounts WHERE locked=0 AND loggedin=1 AND laston>"'.date('Y-m-d H:i:s',strtotime(date('c').'-'.getsetting('LOGINTIMEOUT',900).' seconds')).'" ORDER BY level DESC, dragonkills DESC, name ASC';

            }

        else

            {

            output('`c`bEinwohner aller Planeten (Seite '.($pageoffset/$playersperpage+1).': '.$from-$to.' von '.$totalplayers.')`b`c');

            $sql = 'SELECT acctid, name, alive, location, sex, level, laston, loggedin, race, standort FROM accounts WHERE locked=0 '.$search.' ORDER BY level DESC, dragonkills DESC, name ASC '.$limit;

            }

        if ($session['user']['loggedin'])

            {

            rawoutput('<form action="list.php?op=search!" method="POST">Nach Name suchen: <input name="name"><button>Suchen</button></form>');

            addnav('','list.php?op=search');

            }

        $result = db_query($sql);

        $max = db_num_rows($result);

        if ($max > 100)

            output('`$Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 100 werden angezeigt.`0`n');

        rawoutput('<table border="0" cellpadding="2" cellspacing="1" bgcolor="#999999" align="center"><tr class="trhead"><th>Level</th><th>Name</th><th>Rasse</th><th><img src="images/female.gif">/<img src="images/male.gif"></th><th>Ort</th><th>Status</th><th>Zuletzt da</th>'.($session['user']['superuser']>=4?'<th>Optionen</th>':'').'</tr>');

        for ($i = 0; $i < $max; $i ++)

            {

            $row = db_fetch_assoc($result);

            rawoutput('<tr class="'.($i%2?'trdark':'trlight').'"><td>');

            output('`^'.$row['level'].'`0');

            rawoutput('</td><td>');

            if ($session['user']['loggedin'])

                {

                rawoutput('<a href="mail.php?op=write&to='.rawurlencode($row['acctid']).'" target="_blank" onClick="'.popup('mail.php?op=write&to='.rawurlencode($row['acctid'])).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>');

                if ($session['user']['tagebuch'] == 1)

                    output('<a href="biodiary.php?char='.$row['acctid'].'&op=long&source='.$_GET['source'].'&page='.$_GET['page'].'&ID='.$row['acctid'].'"><img src="images/tagebuchico.jpg" width="16" height="16" alt="Tagebuch ansehen" border="0"></a>',true);

                rawoutput('<a href="bio.php?char='.rawurlencode($row['acctid']).'">');

                }

            output('`'.($row['acctid']==getsetting('hasegg',0)?'^':'&').''.$row['name'].'`0');

            if ($session['user']['loggedin'])

                rawoutput('</a>');

            rawoutput('</td><td>');

            output($colraces[$row['race']]);

            rawoutput('</td><td align="center">');

            rawoutput($row['sex']?'<img src="images/female.gif">':'<img src="images/male.gif">');

            rawoutput('</td><td>');

            output(''.$row['standort'].'`0');

            rawoutput('</td><td>');

            output($row['alive']?'`1Lebt`0':'`4Schatten`0');

            rawoutput('</td><td>');

            //$laston=round((strtotime("0 days")-strtotime($row['laston'])) / 86400,0)." Tage";

            $laston = round((strtotime(date('c'))-strtotime($row['laston'])) / 86400,0).' Tage';

            if (substr_c($laston,0,2) == '1 ')

                $laston = '1 Tag';

            if (date('Y-m-d',strtotime($row['laston'])) == date('Y-m-d'))

                $laston = 'Heute';

            if (date('Y-m-d',strtotime($row['laston'])) == date('Y-m-d',strtotime(date('c').'-1 day')))

                $laston = 'Gestern';

            if ($loggedin)

                $laston = 'Jetzt';

            output($laston);

            rawoutput('</td>');

            if ($session['user']['superuser'] >= 4)

                {

                rawoutput('<td>');

                rawoutput('<a href="list.php?op=repnav&id='.$row['acctid'].'">Badnav</a></ br>');

                /*if ($row['biohilfe'] == 0)

                    output('<a href="list.php?op=bio&was=an&id='.$row['acctid'].'">Bio melden</a>',true);

                else

                    output('<a href="list.php?op=bio&was=aus&id='.$row['acctid'].'">Bio melden</a>',true);*/

                rawoutput('</td>');

                }

            rawoutput('</tr>');

            }

        rawoutput('</table>');

    break;

    }



addnav('','biodiary.php?char='.$row['acctid'].'&op=long&source='.$_GET['source'].'&page='.$_GET['page'].'&ID='.$row['acctid'].'');

addnav('','bio.php?char='.rawurlencode($row['acctid']));

addnav('','list.php?op=repnav&id='.$row['acctid'].'');

//addnav('','list.php?op=bio&was=an&id='.$row['acctid'].'');

//addnav('','list.php?op=bio&was=aus&id='.$row['acctid'].'');

page_footer();

?>

