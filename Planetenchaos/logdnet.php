
<?php

require_once 'common.php';

if ($_GET['op'] == '')

    {

    $result = db_query('SELECT lastupdate, serverid FROM logdnet WHERE address="'.$_GET['addy'].'"');

    $row = db_fetch_assoc($result);

    if (db_num_rows($result) > 0)

        {

        if (strtotime($row['lastupdate']) < strtotime(date('c').'-1 minutes'))

            {

            //echo strtotime($row[lastupdate])."<br>".strtotime("-5 minutes");

            db_query('UPDATE logdnet SET priority=priority*0.99');

            //use PHP server time for lastupdate in case mysql server and PHP server have different times.

            db_query('UPDATE logdnet SET priority=priority+1, description="'.soap($_GET['desc']).'", lastupdate="'.date('Y-m-d H:i:s').'" WHERE serverid='.$row['serverid'].'');

            //echo $sql;

            echo 'Ok - auf dem neuesten Stand';

            }

        else

            echo 'Ok - noch zu frÃ¼h fÃ¼r ein Update';

        }

    else

        {

        $result = db_query('INSERT INTO logdnet (address, description, lastupdate) VALUES ("'.$_GET['addy'].'", "'.soap($_GET['desc']).'", now())');

        echo 'Ok - hinzugefÃ¼gt';

        }

    }

elseif ($_GET['op'] == 'net')

    {

    $result = db_query('SELECT address, description FROM logdnet WHERE lastupdate>"'.date('Y-m-d H:i:s',strtotime(date('c').'-7 days')).'" ORDER BY priority DESC');

    for ($i = 0; $i < db_num_rows($result); $i ++)

        {

        $row = db_fetch_assoc($result);

        $row = serialize($row);

        echo $row."\n";

        }

    }

else

    {

    page_header('LoGD Netz');

    //$sql = "SELECT * FROM logdnet ORDER BY priority DESC";

    //$result=db_query($sql);

    addnav('ZurÃ¼ck zum Login','index.php');

    output('`@Eine Liste mit anderen LoGD Servern, die im LoGD-Netz registriert sind. (Sortiert nach Logins)`n`n');

    rawoutput('<table><tr><td>');

    output('`@`bServername und Link`b`0');

    rawoutput('</td><td width="130">');

    output('`@`bVersion`b`0');

    rawoutput('</td></tr>');

    $servers = file(getsetting('logdnetserver','http://lotgd.net/').'logdnet.php?op=net');

    //while (list($key,$val)=each($servers))

    foreach ($servers as $key => $val)

        {

        $row = unserialize($val);

        if (trim($row['description']) == '')

            $row['description'] = 'Ein anderer LoGD-Server';

        if (substr_c($row['address'],0,7) != 'http://')

            {}

        else

            output('<tr><td valign="top"><a href="'.HTMLEntities($row['address']).'" target="_blank">'.stripslashes(HTMLEntities($row['description'])).'`0</a></td><td valign="top" width="130">'.HTMLEntities($row['version']).'</td></tr>',true);

        }

    rawoutput('</table>');

    page_footer();

    }

?>

