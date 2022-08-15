
<?php
// 22062004
// New Hall of Fame features by anpera
// http://www.anpera.net/forum/viewforum.php?f=27
// with code from centralserver for 0.9.8; re-imported to 0.9.7
// Suchfunktion hinzugefügt am 07.08.06 von Fossla für www.atrahor.de (letzte Änderung: 17.10.06 Salator)
require_once "common.php";
require_once(LIB_PATH.'profession.lib.php');
page_header("Ruhmeshalle");
checkday();
$playersperpage = 50;
$max_su = "";
//" AND (name NOT LIKE '%*%' AND superuser < 3) ";
$op = "kills";
if ($_GET['op'])
{
    $op = $_GET['op'];
}
$subop = "most";
if ($_GET['subop'])
{
    $subop = $_GET['subop'];
}
$sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0";
if ($op == "kills")
{
    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND dragonkills>0".$max_su;
}
else if ($op == "days")
{
    //        $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND dragonkills>0 AND bestdragonage>0".$max_su;
}
else if ($op == "abwesend")
{
    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND dragonkills>0 AND DATEDIFF(NOW(),laston) > 3 AND location!=".USER_LOC_VACATION;
}
else if ($op == 'profs' || $op == 'races')
{
    $sql = '';
}
addnav("Bestenlisten");
addnav("Alter","hof.php?op=birth&subop=$subop");
addnav("Arenakämpfer","hof.php?op=battlepoints&subop=$subop");
addnav("Au Schwarte","hof.php?op=schweinerei&subop=".$subop);
addnav("Bettelstein","hof.php?op=beggar&subop=$subop");
addnav("Bewaffnung","hof.php?op=weapon&subop=$subop");
addnav("Bierkönige","hof.php?op=beer&subop=$subop");
addnav("Drachenkills", "hof.php?op=kills&subop=$subop");
addnav("Edelsteine", "hof.php?op=gems&subop=$subop");
addnav("Geschwindigkeit", "hof.php?op=days&subop=$subop");
//addnav("Goldener Joggingschuh","hof.php?op=runaway&subop=$subop");
addnav("Häftlinge","hof.php?op=kerker&subop=$subop");
addnav('j?Hasenjagd','hof.php?op=bunny&subop='.$subop);
addnav("m?Heizmeister","hof.php?op=spoil&subop=$subop");
addnav("Knappen","hof.php?op=disciple&subop=$subop");
addnav("Puppenbesitzer","hof.php?op=doll&subop=$subop");
addnav("Reichtum", "hof.php?op=money&subop=$subop");
addnav("RP-Punkte", "hof.php?op=rppoints&subop=$subop");
addnav("Schatzsucher","hof.php?op=treasure&subop=$subop");
addnav("Schlagkraft","hof.php?op=punch&subop=$subop");
addnav("Schönheit", "hof.php?op=charm&subop=$subop");
addnav("Stärke", "hof.php?op=tough&subop=$subop");
addnav("Tollpatsche", "hof.php?op=resurrects&subop=$subop");
addnav("Uffs Maul!","hof.php?op=beatenup&subop=$subop");
addnav("Verschollene","hof.php?op=abwesend&subop=$subop");
if ($session['user']['alive']==0)
{
    addnav("Jarcaths Lieblinge","hof.php?op=grave&subop=$subop");
}
if ($session['user']['superuser'])
{
    addnav('Admin');
    addnav('Urlauber','hof.php?op=vacation&subop='.$subop);
}
if ($sql)
{
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $totalplayers = $row['c'];

    $page = 1;
    if ($_GET['page'])
    {
        $page = (int)$_GET['page'];
    }
    $pageoffset = $page;
    if ($pageoffset > 0)
    {
        $pageoffset--;
    }
    $pageoffset *= $playersperpage;
    $from = $pageoffset+1;
    $to = min($pageoffset+$playersperpage, $totalplayers);

    if (empty($_POST['search']))
    {
        $limit = "$pageoffset,$playersperpage";
    }
    else
    {
        $limit = "0,$totalplayers";
    }

    addnav("Sortieren nach");
    addnav("Besten", "hof.php?op=$op&subop=most&page=$page");
    addnav("Schlechtesten", "hof.php?op=$op&subop=least&page=$page");
    addnav("Seiten");
    for ($i = 0; $i < $totalplayers; $i+= $playersperpage)
    {
        $pnum = ($i/$playersperpage+1);
        $min = ($i+1);
        $max = min($i+$playersperpage,$totalplayers);
        addnav("Seite $pnum ($min-$max)", "hof.php?op=$op&subop=$subop&page=$pnum");
    }

}
function display_table($title, $sql, $none=false, $foot=false, $data_header=false, $tag=false)
{
    global $session, $from, $to, $page, $limit;
    output("`c`b`^$title`0`b `7(Seite $page: $from-$to)`0`c`n");
    output('<table cellspacing="0" cellpadding="2" align="center"><tr class="trhead" align="center">',true);
    output("<td></td><td>`bRang`b</td><td>`bName`b</td>", true);
    if ($data_header !== false)
    {
        for ($i = 0; $i < count($data_header); $i++)
        {
            output("<td>`b".$data_header[$i]."`b</td>", true);
        }
    }

    if (!is_array($sql))
    {
        $result = db_query($sql) or die(db_error(LINK));
    }
    $count = (is_array($sql) ? sizeof($sql) : db_num_rows($result));

    if ($count == 0)
    {
        $size = ($data_header === false) ? 2 : 2+count($data_header);
        if ($none === false)
        {
            $none = "Keine Spieler gefunden";
        }
        output('<tr class="trlight"><td colspan="'. $size .'" align="center">`&' . $none .'`0</td></tr>',true);
    }
    else
    {
        if (empty($_POST['search']))
        {
            $suchname = '';
        }
        else
        {
            $suchname = strtolower($_POST['search']);
        }
        for ($i=0; $i<$count; $i++)
        {
            if (!is_array($sql))
            {
                $row = db_fetch_assoc($result);
            }
            else
            {
                $row = $sql[$i];
            }
            if (!empty($_POST['search']))
            {
                $name = strtolower($row['name']);
                $name = strip_appoencode($name,$int_mode=1,$bool_forbidden=true);
                $pos = strpos($name, $suchname);
                $same = false;
                if ($name == $suchname)
                {
                    $same = true;
                }
            }
            if ($pos === false && !empty($suchname) && $same == false)
            {
                // nichts ausgeben
            }
            else
            {
                if ($row['name']==$session['user']['name'])
                {
                    //output("<tr class='hilight'>",true);
                    output("<tr bgcolor='#330000'><td>`b`^->`b</td>",true);
                }
                else
                {
                    output('<tr class="' . ($i%2?"trlight":"trdark") . '"><td></td>',true);
                }
                output("<td>".($i+$from).".</td><td>`&{$row[name]}`0</td>",true);
                if ($data_header !== false)
                {
                    for ($j = 0; $j < count($data_header); $j++)
                    {
                        $id = 'data' . ($j+1);
                        $val = $row[$id];
                        if ($tag !== false)
                        {
                            $val = $val . " " . $tag[$j];
                        }
                        output("<td align='right'>".$val."</td>",true);
                    }
                }
                output("</tr>",true);
            }
        }
    }
    output('</table>', true);
    if ($foot !== false)
    {
        output("`n`c$foot`c");
    }
}
output("`c<form action='hof.php?op=" . $_GET['op'] . "&subop=" . $subop . "&page=1' method='POST'>Helden suchen: <input name='search' value='" . $_POST['search'] . "'><input type='submit' name='suchen' class='button' value='Suchen'></form>`c");
addnav('',"hof.php?op=$_GET[op]&subop=$subop&page=1");
$order = "DESC";
if ($_GET['subop'] == "least")
{
    $order = "ASC";
}
$sexsel = "IF(sex,'<img src=\"images/female.png\">&nbsp; &nbsp;','<img src=\"images/male.png\">&nbsp; &nbsp;')";
$loginsel = "IF(loggedin,'`@Online`0','`4Offline`0')";
$alivesel = "IF(alive,'`1Lebt`0','`4Tot`0')";
if ($_GET['op']=="money")
{
    $sql = "SELECT name,(goldinbank+gold+round((((rand()*10)-5)/100)*(goldinbank+gold))) AS data1 FROM accounts WHERE goldinbank>0 AND locked=0 ".$max_su." ORDER BY data1 $order, level $order, experience $order, acctid $order LIMIT $limit";
    $adverb = "reichsten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "ärmsten";
    }
    $title = "Die $adverb Krieger in diesem Land:";
    $foot = "(Vermögen +/- 5%)";
    $headers = array("Geschätztes Vermögen");
    $tags = array("Gold");
    display_table($title, $sql, false, $foot, $headers, $tags);
}
else if ($_GET['op'] == "gems")
{
    $sql = "SELECT name FROM accounts WHERE locked=0 ".$max_su." ORDER BY gems $order, level $order, experience $order, acctid $order LIMIT $limit";
    if ($_GET['subop'] == "least")
    {
        $adverb = "wenigsten";
    }
    else
    {
        $adverb = "meisten";
    }
    $title = "Die Krieger mit den $adverb Edelsteinen:";
    display_table($title, $sql);
}
else if ($_GET['op'] == "birth")
{

    if ($_GET['subop'] == "least")
    {
        $adverb = "kürzesten";
        $order='DESC';
    }
    else
    {
        $adverb = "längsten";
        $order='ASC';
    }

    $sql = "SELECT name,birthday AS data1,DATEDIFF(NOW(),laston) AS data2, dragonkills AS data3
    FROM accounts
    INNER JOIN account_extra_info USING(acctid)
    WHERE birthday!='' ORDER BY data1 $order, data3 DESC LIMIT $limit";
    $res = db_query($sql);

    $arr = array();

    while ($p = db_fetch_assoc($res))
    {
        $p['data1'] = getgamedate($p['data1']);

        if ($p['data2'] == 0)
        {
            $p['data2'] = 'Heute';
        }
        else if ($p['data2'] == 1)
        {
            $p['data2'] = 'Gestern';
        }
        else
        {
            $p['data2'] .= ' Tage';
        }
        $arr[] = $p;
    }

    $title = "Diese Krieger sind am $adverb in der Stadt:";
    $headers = array('Ankunft','Zuletzt gesehen','Drachenkills');
    $tags = array('','');
    display_table($title, $arr, false, '', $headers, $tags);
}
else if ($_GET['op'] == "treasure")
{

    $sql = "SELECT accounts.name,treasure_f AS data1 FROM account_extra_info LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid WHERE treasure_f>0 ORDER BY data1 $order LIMIT $limit";

    if ($_GET['subop'] == "least")
    {
        $adverb = "wenigsten";
    }
    else
    {
        $adverb = "meisten";
    }
    $title = "Diese Krieger haben die $adverb Schätze und Drachenreliquien gefunden:";
    $headers = array("Schätze");
    $tags = array("");
    display_table($title, $sql, false, '', $headers, $tags);
}
else if ($_GET['op'] == "kerker")
{

    $sql = "SELECT accounts.name,daysinjail AS data1 FROM accounts WHERE daysinjail>0 ORDER BY data1 $order LIMIT $limit";

    if ($_GET['subop'] == "least")
    {
        $adverb = "wenigsten";
    }
    else
    {
        $adverb = "meisten";
    }
    $title = "Diese Krieger haben die $adverb Tagesabschnitte im Kerker gesessen:";
    $foot = "Es gelten nur die Stunden, die tatsächlich abgesessen wurden, nicht die Strafen";
    $headers = array("In Haft");
    $tags = array("Tagesabschnitte");
    display_table($title, $sql, false, $foot, $headers, $tags);

}
else if ($_GET['op'] == "beer")
{

    $sql = "SELECT accounts.name,beerspent AS data1 FROM account_extra_info LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid WHERE beerspent>0 ORDER BY data1 $order LIMIT $limit";

    if ($_GET['subop'] == "least")
    {
        $adverb = "wenigste";
    }
    else
    {
        $adverb = "meiste";
    }
    $title = "Diese Krieger haben das $adverb Freibier spendiert:";
    $foot = "Auf ihr Wohl! Prost!";
    $headers = array("Freibier");
    $tags = array("Humpen");
    display_table($title, $sql, false, $foot, $headers, $tags);

}
else if ($_GET['op'] == "beggar")
{

    $sql = "SELECT accounts.name,beggar AS data1 FROM account_extra_info LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid WHERE beggar<>0 ORDER BY data1 $order LIMIT $limit";

    if ($_GET['subop'] == "least")
    {
        $adverb = "großzügigsten Spender";
    }
    else
    {
        $adverb = "gierigsten Bettler";
    }
    $town=getsetting("townname","Atrahor")."s";
    $title = "Die $adverb ".$town.":";
    $foot = "(Hier erscheint was insgesamt vom Bettelstein genommen wurde.`n
Negative Zahlen bedeuten, dass mehr gespendet als genommen wurde.)";
    $headers = array("entnommen");
    $tags = array("Gold");
    display_table($title, $sql, false, $foot, $headers, $tags);

}
else if ($_GET['op'] == "spoil")
{

    $sql = "SELECT accounts.name,disciples_spoiled AS data1 FROM account_extra_info LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid WHERE disciples_spoiled>0 ORDER BY data1 $order LIMIT $limit";

    if ($_GET['subop'] == "least")
    {
        $adverb = "wenigsten";
    }
    else
    {
        $adverb = "meisten";
    }
    $title = "Diese Krieger haben bislang die $adverb Knappen verheizt:";
    $foot = "Jünglinge, nehmt Euch in Acht!";
    $headers = array("Verloren");
    display_table($title, $sql, false, $foot, $headers, false);

}
else if ($_GET['op'] == "beatenup")
{

    $sql = "SELECT accounts.name,timesbeaten AS data1 FROM account_extra_info LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid WHERE timesbeaten>0 ORDER BY data1 $order LIMIT $limit";

    if ($_GET['subop'] == "least")
    {
        $adverb = "wenigste";
    }
    else
    {
        $adverb = "meiste";
    }
    $title = "Diese Helden haben bislang die $adverb Prügel kassiert:";
    $foot = "(Es werden nur erfolgreiche Prügelattacken gezählt, bei denen die Angreifer nicht vertrieben wurden.)";
    $headers = array("Prügel");
    $tags = array("x vermöbelt");
    display_table($title, $sql, false, $foot, $headers, $tags);

}
else if ($_GET['op'] == "runaway")
{

    $sql = "SELECT accounts.name,runaway AS data1 FROM account_extra_info LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid WHERE runaway>0 ORDER BY data1 $order LIMIT $limit";

    if ($_GET['subop'] == "least")
    {
        $adverb = "Wenigsten";
    }
    else
    {
        $adverb = "Häufigsten";
    }
    $title = "Diese Recken sind bislang am $adverb aus dem Kampf geflüchtet:";
    $foot = "(Es wird nur jeder erfolgreiche Fluchtversuch gewertet.)";
    $headers = array("davongelaufen");
    $tags = array("x geflohen");
    display_table($title, $sql, false, $foot, $headers, $tags);

}
else if ($_GET['op']=="weapon")
{
    $sql = "SELECT name,weapon AS data1,weapondmg AS data2 FROM accounts WHERE locked=0 ".$max_su." ORDER BY weapondmg $order, dragonkills $order, attack $order LIMIT $limit";
    $adverb = "mächtigsten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "schlichtesten";
    }
    $title = "Die $adverb Waffen in diesem Land:";
    $headers = array("Waffe","Waffenstärke");
    display_table($title, $sql, false, false, $headers, false);

}
else if ($_GET['op']=="disciple")
{
    $sql = "SELECT accounts.name,disciples.name AS data1,disciples.level AS data2 FROM disciples LEFT JOIN accounts ON accounts.acctid=disciples.master WHERE state>0 ORDER BY disciples.level $order, accounts.dragonkills $order LIMIT $limit";
    $adverb = "besten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "unerfahrensten";
    }
    $title = "Diese Krieger haben die $adverb Knappen:";
    $headers = array("Knappe","Level");
    display_table($title, $sql, false, false, $headers, false);

}
else if ($_GET['op']=="charm")
{
    $sql = "SELECT accounts.name,$sexsel AS data1,r.colname AS data2 FROM accounts LEFT JOIN races r ON r.id=race WHERE locked=0 ".$max_su." ORDER BY charm $order, level $order, experience $order, acctid $order LIMIT $limit";
    $adverb = "schönsten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "hässlichsten";
    }
    $title = "Die $adverb Krieger in diesem Land:";
    $headers = array("<img src=\"images/female.png\">/<img src=\"images/male.png\">", "Rasse");
    display_table($title, $sql, false, false, $headers, false);

}
else if ($_GET['op']=="tough")
{
    $sql = "SELECT accounts.name,level AS data2 ,r.colname as data1 FROM accounts LEFT JOIN races r ON r.id=race WHERE locked=0 AND acctid!=1 ".$max_su." ORDER BY ((maxhitpoints/30)+(attack*1.5)+(defence)) $order, level $order, experience $order, acctid $order LIMIT $limit";
    $adverb = "stärksten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "schwächsten";
    }
    $title = "Die $adverb Krieger in diesem Land:";
    $headers = array("Rasse", "Level");
    display_table($title, $sql, false, false, $headers, false);
}
else if ($_GET['op']=="schweinerei")
{
    $sql = "SELECT accounts.name,aei.schweinerei AS data2 ,accounts.dragonkills as data1 FROM accounts LEFT JOIN account_extra_info aei USING(acctid) WHERE locked=0 AND acctid!=1 ".$max_su." ORDER BY aei.schweinerei $order, dragonkills $order, level $order, acctid $order LIMIT $limit";
    $adverb = "besten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "schlechtesten";
    }
    $title = "Die $adverb Wildschweine-Werfer in diesem Land:";
    $headers = array("Drachenkills", "Punkte");
    display_table($title, $sql, false, false, $headers, false);
}
else if ($_GET['op']=="punch")
{

    // Godmode-Leute rausnehmen
    $max_su_punch = ' AND !('.su_check_other(SU_RIGHT_GODMODE).') ';

    $sql = "SELECT accounts.name,punch AS data1,r.colname AS data2 FROM accounts LEFT JOIN races r ON r.id=race WHERE locked=0 ".$max_su_punch." ORDER BY data1 $order, level $order, experience $order, acctid $order LIMIT $limit";
    $adverb = "härtesten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "armseligsten";
    }
    $title = "Die $adverb Schläge aller Zeiten:";
    $headers = array("Punkte","Rasse");
    display_table($title, $sql, false, false, $headers, false);
}
else if ($_GET['op']=="resurrects")
{
    $sql = "SELECT accounts.name,level AS data1 FROM accounts WHERE locked=0 ".$max_su." ORDER BY resurrections $order, level $order, experience $order, acctid $order LIMIT $limit";
    $adverb = "tollpatschigsten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "geschicktesten";
    }
    $title = "Die $adverb Krieger in diesem Land:";
    $headers = array("Level");
    display_table($title, $sql, false, false, $headers, false);
}
else if ($_GET['op']=="grave")
{
    $sql = "SELECT accounts.name,accounts.alive,deathpower AS data1,location,loggedin,$loginsel AS data2,laston,$alivesel AS data3,activated FROM accounts WHERE locked=0 ".$max_su." ORDER BY deathpower $order, level $order, experience $order, acctid $order LIMIT $limit";
    $adverb = "fleissigste";
    if ($_GET['subop'] == "least")
    {
        $adverb = "faulste";
    }
    $title = "`²J`ça`Âr`Îc`Âa`çt`²hs`^ $adverb Krieger:";
    if (!empty($_POST['search']))
    {
        $headers = array("Gefallen","Online","Status");
        display_table($title, $sql, false, false, $headers, false);
    }
    else
    {
        output("`c`b`^$title`0`b `7(Seite $page: $from-$to)`0`c`n");
        output('<table cellspacing="0" cellpadding="2" align="center" border="0"><tr class="trhead" align="center">',true);
        output("<td></td><td>`bRang`b</td><td>`bName`b</td><td>`bGefallen`b</td><td>`bOrt`b</td><td>`bStatus`b</td></tr>", true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0)
        {
            output('<tr class="trlight"><td colspan="5" align="center">`&Keine Spieler gefunden`0</td></tr>',true);
        }
        else
        {
            for ($i=0; $i<db_num_rows($result); $i++)
            {
                $row = db_fetch_assoc($result);
                if ($row['name']==$session['user']['name'])
                {
                    //output("<tr class='hilight'>",true);
                    output("<tr bgcolor='#330000'><td>`b`^->`b</td>",true);
                }
                else
                {
                    output('<tr class="'.($i%2?"trlight":"trdark").'"><td></td>',true);
                }
                output("<td>".($i+$from).".</td><td>`&{$row[name]}`0</td><td align='right'>`){$row[data1]}`0</td><td>",true);
                $loggedin = user_get_online(0,$row);
                if ($row['loggedin'] && $row['activated'] != USER_ACTIVATED_STEALTH)
                {
                    output("`#Online`0");
                }
                else if ($row['location']==USER_LOC_FIELDS)
                {
                    output("`3Die Felder`0");
                }
                else if ($row['location']==USER_LOC_INN)
                {
                    output("`3Zimmer in Kneipe`0");
                }
                else if ($row['location']==USER_LOC_HOUSE)
                {
                    output("`3Im Haus`0");
                }
                else if ($row['location']==USER_LOC_PRISON)
                {
                    output("`3Im Kerker`0");
                }
                else
                {
                    output("`3Die Felder`0");
                }
                output("</td><td>",true);
                if ($row['alive'])
                {
                    $row['data3']="`1Lebt`0";
                }
                else
                {
                    $row['data3']="`4Tot`0";
                }

                output($row['data3']);

                output("</td></tr>",true);
            }
        }
        output("</table>", true);
    }
}
else if ($_GET['op']=="days")
{
    $order = "ASC";
    if ($_GET['subop'] == "least")
    {
        $order = "DESC";
    }
    $sql = "SELECT accounts.name,bestdragonage AS data1, accounts.dragonkills FROM account_extra_info LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid WHERE bestdragonage>0 AND dragonkills>0 ORDER BY data1 $order LIMIT $limit";
    $adverb = "schnellsten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "langsamsten";
    }
    $title = "Helden mit den $adverb Drachenkills:";
    $headers = array("Bestzeit Tagesabschnitte");
    $none = "Es gibt noch keine Helden in diesem Land";
    display_table($title, $sql, $none, false, $headers, false);

}
else if ($_GET['op']=="doll")
{
    $order = "DESC";
    if ($_GET['subop'] == "least")
    {
        $order = "ASC";
    }
    $sql = "SELECT accounts.name,hvalue AS data1 FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE items.tpl_id='kpuppe' ORDER BY data1 $order LIMIT $limit";
    $adverb = "wertvollsten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "wertlosesten";
    }
    $title = "Diese Sammler besitzen die $adverb Puppen:";
    $headers = array("Wert der Puppe");
    $none = "Hier besitzt niemand eine Puppe.";
    $tags = array("DKs");
    display_table($title, $sql, $none, false, $headers, $tags);

}
else if ($_GET['op']=="battlepoints")
{
    $sql = "SELECT accounts.name,battlepoints AS data1,dragonkills AS data2 FROM accounts WHERE locked=0 ".$max_su." ORDER BY battlepoints $order, dragonkills $order, acctid $order LIMIT $limit";
    $adverb = "besten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "schlechtesten";
    }
    $title = "Die $adverb Arenakämpfer in diesem Land:";
    $headers = array("Punkte","Drachenkills");
    display_table($title, $sql, false, false, $headers, false);
}
else if ($_GET['op']=="abwesend")
{
    $sql = "SELECT accounts.name, DATEDIFF(NOW(),laston) AS data1,dragonkills AS data2 FROM accounts WHERE locked=0 AND DATEDIFF(NOW(),laston) > 3 AND dragonkills>0 AND location !=".USER_LOC_VACATION." ORDER BY data1 $order, dragonkills $order, acctid $order LIMIT $limit";
    $adverb = "am längsten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "am kürzesten";
    }
    $title = "Die $adverb Verschollenen in diesem Land:";
    $headers = array("Tagesabschnitte","Drachenkills");
    display_table($title, $sql, false, false, $headers, false);
}
else if ($op == 'bunny')
{
        $sql = 'SELECT accounts.acctid,accounts.login,accounts.name,bunnies AS data1,bunnyhunt AS data2
        FROM account_extra_info aei
        LEFT JOIN accounts  ON accounts.acctid=aei.acctid
        WHERE bunnies>0
        '.$str_sql_no_su.'
        ORDER BY data2 '.$order.', data1 '.$order.', accounts.dragonkills '.$order_rev.'
        LIMIT '.$limit;
        $countsql='SELECT count(*) AS c FROM account_extra_info WHERE bunnies>0';

        $title = 'Diese Krieger haben die '.($subop == 'least'?'wenigsten':'meisten').' Häschen eingefangen:';
        $foot = 'Die Jagd geht weiter!';
        $headers = array('Gefangene Hasen','Komplett gelöst');
        $tags = array('','Spiele');
        display_table($title, $sql, false, $foot, $headers, $tags);
}
else if ($_GET['op']=='vacation')
{
        $sql = 'SELECT accounts.acctid,accounts.login,accounts.name, DATEDIFF(NOW(),laston) AS data1,dragonkills AS data2
        FROM accounts
        WHERE locked=0
                AND location ='.USER_LOC_VACATION.'
        ORDER BY data1 '.$order.', dragonkills '.$order.', acctid '.$order.'
        LIMIT '.$limit;
        $countsql='SELECT count(*) AS c FROM accounts WHERE locked=0 AND DATEDIFF(NOW(),laston)>3 AND dragonkills>0 AND location='.USER_LOC_VACATION;

        $title = 'Die '.($_GET['subop'] == 'least'?'am kürzesten':'am längsten').' Verreisten in diesem Land:';
        $headers = array('Tagesabschnitte','Drachenkills');
        display_table($title, $sql, false, false, $headers, false);
}
else if($_GET['op'] == 'rppoints')
{
        $order = ($_GET['subop'] == 'least' ? 'ASC' : 'DESC');
        $sql = "SELECT accounts.name FROM account_stats LEFT JOIN accounts ON accounts.acctid=account_stats.acctid WHERE rppoints > 0 ORDER BY rppoints $order LIMIT $limit";
        $title = 'Spieler mit den '.($_GET['subop'] == 'least' ? 'wenigsten' : 'meisten').' RP-Punkten:`n
                  `n
                  `b`i`S(Es gehen nur RPs an öffentlich zugänglichen Orten in die Wertung mit ein!)`i`b`n`n';
        $none = "Es hat noch kein Spieler öffentlich RP gespielt.";
        display_table($title, $sql, $none, false, false, false);
}
else if ($_GET['op']=="profs")
{
    $arr_prof_list = array();

    $str_judges = '<tr class="trhead"><td>`bDie ehrenwerten Richter:`b</td></tr>';
    //$str_priests = '<tr class="trhead"><td>`bDie würdigen Priester:`b</td></tr>';
    $str_guards = '<tr class="trhead"><td>`bDie tapferen Wachen:`b</td></tr>';
    $str_mages = '<tr class="trhead"><td>`bDie weisen Magier:`b</td></tr>';
    //$str_harbor = '<tr class="trhead"><td>`bDie furchtlosen Hafenwächter:`b</td></tr>';
    $str_merchants = '<tr class="trhead"><td>`bDie angesehenen Händler:`b</td></tr>';
    $str_txt = '';

    $sql = 'SELECT accounts.name, profession, sex, login FROM accounts WHERE profession > 0 ORDER BY profession DESC, dragonkills DESC, acctid ASC';
    $res = db_query($sql);
    while ($a = db_fetch_assoc($res))
    {
        // Wenn Beruf öffentlich angezeigt werden soll
        if ($profs[$a['profession']][2])
        {
            if($session['user']['prefs']['popupbio'] == 1)
                {
                    $biolink="bio_popup.php?char=".rawurlencode($a['login']);
                    $str_biolink = "<a href='".$biolink."' target='_blank' onClick='".popup_fullsize($biolink).";return:false;'>`&".$a['name']."</a>";
                }
                else
                {
                    $biolink="bio.php?char=".rawurlencode($a['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);
                    $str_biolink = "<a href='".$biolink."'>`&".$a['name']."</a>";
                    addnav("","bio.php?char=".rawurlencode($a['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
                }

            $str_txt = '<tr class="trlight"><td><a href="mail.php?op=write&to='.rawurlencode($a['login']).'" target="_blank" onClick="'.popup('mail.php?op=write&to='.rawurlencode($a['login'])).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>'.$profs[$a['profession']][3].$profs[$a['profession']][$a['sex']].' `0'.$str_biolink.'`0</td></tr>';

            switch ($a['profession'])
            {
                case PROF_JUDGE:
                case PROF_JUDGE_HEAD:
                    $str_judges .= $str_txt;
                    break;

                case PROF_GUARD:
                case PROF_GUARD_SUB:
                case PROF_GUARD_HEAD:
                    $str_guards .= $str_txt;
                    break;

                case PROF_PRIEST:
                case PROF_PRIEST_HEAD:
                    $str_priests .= $str_txt;
                    break;

                case PROF_MAGE:
                case PROF_MAGE_HEAD:
                    $str_mages .= $str_txt;
                    break;

                case PROF_DDL_RECRUIT:
                case PROF_DDL_MATE:
                case PROF_DDL_LIEUTENANT:
                case PROF_DDL_CAPTAIN:
                    $str_harbor .= $str_txt;
                    break;
                    
                case PROF_MERCH:
                case PROF_MERCH_HEAD:
                    $str_merchants .= $str_txt;
                    break;

            }
        }
    }

    output('`c`b`&Helden dieser Stadt, die ein offizielles Amt innehaben:`c`b`n');

    $out = '`c<table cellspacing="2" cellpadding="2" align="center">';

    //$out .= $str_judges.$str_priests.$str_mages.$str_guards.$str_harbor;
    $out .= $str_judges.$str_mages.$str_guards.$str_merchants;

    $out .= '</table>`c';

    output($out,true);

}
else if ($_GET['op']=="paare")
{
    output("`n`nIn einem Nebenraum der Ruhmeshalle findest du eine Liste mit Helden ganz anderer Art. Diese Helden meistern gemeinsam die Gefahren der Ehe!`n`n");
    $sql = "SELECT acctid,name,marriedto FROM accounts WHERE sex=0 AND charisma=4294967295 ORDER BY acctid DESC";
    output('`c`b`&Heldenpaare dieser Welt`b`c`n');
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td><img src=\"images/female.png\">`b Name`b</td><td></td><td><img src=\"images/male.png\">`b Name`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0)
    {
        output("<tr><td colspan=4 align='center'>`&`iIn diesem Land gibt es keine Paare.`i`0</td></tr>",true);
    }
    for ($i=0; $i<db_num_rows($result); $i++)
    {
        $row = db_fetch_assoc($result);
        $sql2 = "SELECT name FROM accounts WHERE acctid=".$row['marriedto']."";
        $result2 = db_query($sql2) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result2);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td>`&$row2[name]`0</td><td>`) und `0</td><td>`&",true);
        output("$row[name]`0</td></tr>",true);
    }
    output("</table>",true);
}
else if ($_GET['op'] == 'races') {
    $str_rid = (isset($_GET['rid']) ? $_GET['rid'] : 'men');
    $sql = db_query('SELECT r.colname_plur,a.name,a.acctid,a.dragonkills FROM accounts a LEFT JOIN races r ON a.race = r.id WHERE r.id = "'.$str_rid.'" AND r.active = 1 ORDER BY a.dragonkills DESC,a.level DESC,a.acctid ASC');
    $int_count = db_num_rows($sql);
    if($int_count == 0) {
        output('`n`c`i`7Diese Rasse ist in Eranya nicht vertreten.`i`n');
    } else {
        for($i=0;$i<$int_count;$i++) {
            $row = db_fetch_assoc($sql);
            // Tabelle am Anfang starten + Rassenname als Überschrift setzen:
            if($i == 0) {
                output('`c`b`&Übersicht aller '.$row['colname_plur'].'`&:`b`n`n
                        <table><tr class="trhead"><td style="text-align: center;">`bName`b</td><td style="text-align: center;">`bDrachenkills`b</td></tr>');
            }
            // Daten in Tabelle eintragen:
            output('<tr class="'.($i%2 ? 'trdark' : 'trlight').'">
                        <td style="text-align: center;"><a href="bio_popup.php?id='.$row['acctid'].'" target="_blank" onClick="'.popup_fullsize('bio_popup.php?id='.$row['acctid']).';return:false;">`&'.$row['name'].'`0</a></td>
                        <td style="text-align: center;">`&'.$row['dragonkills'].'</td></tr>');
            addnav('','bio_popup.php?id='.$row['acctid']);
        }
        output('</table>`c');
    }
    // Alle Rassen verlinken:
    addnav('Rassenauswahl');
    $sql2 = db_query('SELECT id,name_plur FROM races WHERE active = 1 ORDER BY id ASC');
    $int_count = db_num_rows($sql2);
    for($j=0;$j<$int_count;$j++) {
        $row2 = db_fetch_assoc($sql2);
        addnav($row2['name_plur'],'hof.php?op=races&rid='.$row2['id']);
    }
}
else
{
    $sql = "SELECT accounts.name,dragonkills AS data1,level AS data2,'&nbsp;' AS data3, IF(dragonage,dragonage,'Unknown') AS data4, '&nbsp;' AS data5, IF(account_extra_info.bestdragonage,account_extra_info.bestdragonage,'Unknown') AS data6 FROM accounts LEFT JOIN account_extra_info ON account_extra_info.acctid=accounts.acctid WHERE dragonkills>0 AND locked=0 ".$max_su." ORDER BY dragonkills $order,level $order,experience $order, accounts.acctid $order LIMIT $limit";
    $adverb = "meisten";
    if ($_GET['subop'] == "least")
    {
        $adverb = "wenigsten";
    }
    $title = "Helden mit den $adverb Drachenkills:";
    $headers = array("Kills", "Level", "&nbsp;", "Tagesabschnitte", "&nbsp;", "Bestzeit");
    $none = "Es gibt noch keine Helden in diesem Land";
    display_table($title, $sql, $none, false, $headers, false);
}
// $sql = "SELECT accounts.name,bestdragonage AS data1, accounts.dragonkills FROM account_extra_info LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid WHERE bestdragonage>0 AND dragonkills>0 ORDER BY data1 $order LIMIT $limit";
addnav("Sonstiges");
addnav("Offizielle Ämter","hof.php?op=profs&subop=$subop");
addnav("Paare dieser Welt","hof.php?op=paare");
addnav('Rassenübersicht','hof.php?op=races');
addnav('Zurück');
if ($session['user']['alive'])
{
    addnav("Zum Stadtamt","dorfamt.php");
    addnav("#?Zurück zur Stadt","village.php");
}
else
{
    addnav("#?Zurück zu den Schatten","shades.php");
}
page_footer();
?>

