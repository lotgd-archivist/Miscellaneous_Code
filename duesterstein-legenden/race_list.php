
<?
// Dwarf city Thais seen on www.hadriel.ch/logd
// and taken as basis for race-specific enhancements
//
// Modifications by gargamel @ www.rabenthal.de :
// - changed chat into a common module for all race-specific enhanements
// - location for other ingame races elf, human and troll
//
require_once("common.php");

$race = $session['user']['race'];

    $playersperpage=20;
    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND race = ".$race;
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $totalplayers = $row['c'];

    $pageoffset = (int)$_GET['page'];
    if ($pageoffset>0) $pageoffset--;
    $pageoffset*=$playersperpage;
    $from = $pageoffset+1;
    $to = min($pageoffset+$playersperpage,$totalplayers);


switch ( $race ) {
    case 1:    //troll
    page_header("Akwark");
    addnav("Zurück","race_troll.php?op=enter2");
    output("`c`b`&~~~ Trolle in dieser Welt (Seite ".($pageoffset/$playersperpage+1).": $from-$to von $totalplayers) ~~~`b`c`n");
    output("`7Hier findest Du ein Verzeichnis aller Trolle im Land. `qDamit Du nicht
    an den falschen gerätst...`n`n`0");
    break;

    case 2:    //elf
    page_header("Quintarra");
    addnav("Zurück","race_elf.php?op=enter2");
    output("`c`b`&~~~ Elfen in dieser Welt (Seite ".($pageoffset/$playersperpage+1).": $from-$to von $totalplayers) ~~~`b`c`n");
    output("`7Hier findest Du ein Verzeichnis aller Elfen im Land. `qDamit Du nicht
    an den falschen gerätst...`n`n`0");
    break;

    case 3:    //human
    page_header("Waldpark");
    addnav("Zurück","race_human.php?op=enter2");
    output("`c`b`&~~~ Menschen in dieser Welt (Seite ".($pageoffset/$playersperpage+1).": $from-$to von $totalplayers) ~~~`b`c`n");
    output("`7Hier findest Du ein Verzeichnis aller Menschen im Land. `qDamit Du nicht
    an den falschen gerätst...`n`n`0");
    break;

    case 4:    //dwarf
    page_header("Thais");
    addnav("Zurück","race_dwarf.php?op=enter2");
    output("`c`b`&~~~ Zwerge in dieser Welt (Seite ".($pageoffset/$playersperpage+1).": $from-$to von $totalplayers) ~~~`b`c`n");
    output("`7Hier findest Du ein Verzeichnis aller Zwerge im Land. `qDamit Du nicht
    an den falschen gerätst...`n`n`0");
    break;
}

    $limit=" LIMIT $pageoffset,$playersperpage ";
    addnav("Seiten");
    for ($i=0;$i<$totalplayers;$i+=$playersperpage){
        addnav("Seite ".($i/$playersperpage+1)." (".($i+1)."-".min($i+$playersperpage,$totalplayers).")","race_list.php?page=".($i/$playersperpage+1));
    }

    $sql = "SELECT acctid,name,login,alive,location,sex,level,laston,loggedin,lastip,uniqueid,charisma
            FROM accounts
            WHERE locked=0 AND race=".$race."
            ORDER BY level DESC, dragonkills DESC, login ASC $limit";
    $result = db_query($sql) or die(sql_error($sql));
    $max = db_num_rows($result);

    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Level</b></td><td><b>Name</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Stand</b></td><td><b>Ort</b></td><td><b>Status</b></td><td><b>Zuletzt da</b></tr>",true);
    for($i=0;$i<$max;$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^$row[level]`0");
        output("</td><td>",true);
        if ($session[user][loggedin]) output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);

        $link = "bio.php?char=".rawurlencode($row[login]) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
        if ($session[user][loggedin]) output("<a href='".$link."'>",true);
        addnav("",$link);

        if ($session[user][loggedin]) addnav("","bio.php?char=".rawurlencode($row['login'])."");
        output("`".($row[acctid]==getsetting("hasegg",0)?"^":"&")."$row[name]`0");
        if ($session[user][loggedin]) output("</a>",true);
        output("</td><td align=\"center\">",true);
        output($row[sex]?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">",true);
        output("</td><td>",true);
        if ($row[charisma]==4294967295) output("`Vverheiratet`0"); else output("`vSingle`0");
        output("</td><td>",true);
        $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
        if ($row[location]==0) output($loggedin?"`#Online`0":"`3Die Felder`0");
        if ($row[location]==1) output("`3Zimmer in Kneipe`0");
        if ($row[location]==2) output("`3Im Haus`0");
        output("</td><td>",true);
        output($row[alive]?"`1Lebt`0":"`4Tot`0");
        output("</td><td>",true);
        $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
        if (substr($laston,0,2)=="1 ") $laston="1 Tag";
        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
        if ($loggedin) $laston="Jetzt";
        output($laston);
        output("</td></tr>",true);
    }
    output("</table>",true);

page_footer();
?>


