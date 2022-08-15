
<?php

/*setweather.php

An element of the global weather mod Version 0.5

Written by Talisman

Latest version available at http://dragonprime.cawsquad.net

translation: anpera*/

if ((int)getsetting('expirecontent',180) > 0)

    {

    db_query('DELETE FROM commentary WHERE postdate<"'.date('Y-m-d H:i:s',strtotime(date('c').'-'.getsetting('expirecontent',180).' days')).'"');

    db_query('DELETE FROM news WHERE newsdate<"'.date('Y-m-d H:i:s',strtotime(date('c').'-'.getsetting('expirecontent',180).' days')).'"');

    }

db_query('DELETE FROM mail WHERE sent<"'.date('Y-m-d H:i:s',strtotime(date('c').'-'.getsetting('oldmail',14).'days')).'"');

switch (e_rand(1,9))

    {

    case 1:

        $clouds = 'Wechselhaft und kÃ¼hl, mit sonnigen Abschnitten';

    break;

    case 2:

        $clouds = 'Warm und sonnig';

    break;

    case 3:

        $clouds = 'Regnerisch';

    break;

    case 4:

        $clouds = 'Neblig';

    break;

    case 5:

        $clouds = 'Kalt bei klarem Himmel';

    break;

    case 6:

        $clouds = 'HeiÃŸ und sonnig';

    break;

    case 7:

        $clouds = 'Starker Wind mit vereinzelten Regenschauern';

    break;

    case 8:

        $clouds = 'Gewittersturm';

    break;

    case 9:

        $clouds = 'Schneeregen';

    break;

    default:

        $clouds = 'Klarer Himmel';

    break;

    }

savesetting('weather',$clouds);

// Vendor in town?

if (e_rand(1,3) == 1)

    {

    savesetting('vendor',1);

    db_query('INSERT INTO news (newstext, newsdate, accountid) VALUES ("`qDer WanderhÃ¤ndler ist heute im Dorf!`0", NOW(), 0)');

    }

else

    savesetting('vendor',0);

// Other hidden paths

$spec = 'Keines';

$what = e_rand(1,3);

if ($what == 1)

    $spec = 'Waldsee';

if ($what == 3)

    $spec = 'Orkburg';

savesetting('dailyspecial',$spec);

// Gamedate-Mod by Chaosmaker

if (getsetting('activategamedate',0) == 1)

    {

    $date = getsetting('gamedate','0000-01-01');

    $date = explode('-',$date);

    $date[2] ++;

    switch ($date[2])

        {

        case 32:

            $date[2] = 1;

            $date[1] ++;

        break;

        case 31:

            if (in_array($date[1], array(4,6,9,11)))

                {

                $date[2] = 1;

                $date[1] ++;

                }

        break;

        case 30:

            if ($date[1] == 2)

                {

                $date[2] = 1;

                $date[1] ++;

                }

        break;

        case 29:

            if ($date[1] == 2 && ($date[0]%4 != 0 || ($date[0]%100 == 0 && $date[0]%400 != 0)))

                {

                $date[2] = 1;

                $date[1] ++;

                }

        break;

        }

    if ($date[1] == 13)

        {

        $date[1] = 1;

        $date[0] ++;

        }

    $date = sprintf('%04d-%02d-%02d',$date[0],$date[1],$date[2]);

    savesetting('gamedate',$date);

    }

// this now includes the database cleanup from index.php

$old = getsetting('expireoldacct',45)-5;

$new = getsetting('expirenewacct',10);

$trash = getsetting('expiretrashacct',1);

$result = db_query('SELECT acctid, emailaddress FROM accounts WHERE 1=0 '.('.$old.'>0?'OR (laston<"'.date('Y-m-d H:i:s',strtotime(date('c').'-'.$old.' days')).'")\n':'').' AND emailaddress!="" AND sentnotice=0');

for ($i = 0; $i < db_num_rows($result); $i ++)

    {

    $row = db_fetch_assoc($result);

    // can't send mail on anpera.net

    mail($row['emailaddress'],'LoGD Charakter verfÃ¤llt',"Einer oder mehrere deiner Charaktere von Legend of the Green Dragon auf ".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']." verfÃ¤llt demnÃ¤chst und wird gelÃ¶scht. Wenn du den Charakter retten willst, solltest du dich bald damit einloggen! Falls der Charakter ein Haus hatte, ist dieses bereits enteignet.","From: ".getsetting("gameadminemail","postmaster@localhost.com"));

    $sql = 'UPDATE accounts SET sentnotice=1, house=0, housekey=0, marriedto=0 WHERE acctid='.$row['acctid'];

    if ((int)$row['acctid'] == (int)getsetting('hasegg',0))

        savesetting('hasegg',0);

    db_query($sql);

    db_query('UPDATE houses SET owner=0, status=3 WHERE owner='.$row['acctid'].' AND status=1');

    db_query('UPDATE houses SET owner=0, status=4 WHERE owner='.$row['acctid'].' AND status=0');

    db_query('UPDATE items SET owner=0 WHERE owner='.$row['acctid']);

    db_query('DELETE FROM pvp WHERE acctid2='.$row['acctid'].' OR acctid1='.$row['acctid']);

    db_query('UPDATE accounts SET charisma=0, marriedto=0 WHERE marriedto='.$row['acctid']);

    }

$old += 5;

$sql = 'DELETE FROM accounts WHERE superuser<=1 AND (1=0\n'.($old>0?'OR (laston<"'.date('Y-m-d H:i:s',strtotime(date('c').'-'.$old.' days')).'")\n':'').($new>0?'OR (laston<"'.date('Y-m-d H:i:s',strtotime(date('c').'-'.$new.' days')).'" AND level=1 AND dragonkills=0)\n':'').($trash>0?'OR (laston<"'.date('Y-m-d H:i:s',strtotime(date('c').'-'.($trash+1).' days')).'" AND level=1 AND experience<10 AND dragonkills=0)\n':'').')'; 

echo "<pre>".HTMLEntities($sql)."</pre>";

db_query($sql);

// end cleanup

savesetting('lastdboptimize',date('Y-m-d H:i:s'));

$result = db_query('SHOW TABLES');

for ($i = 0; $i < db_num_rows($result); $i ++)

    {

    list($key,$val) = each(db_fetch_assoc($result));

    db_query('OPTIMIZE TABLE '.$val);

    }

?>

