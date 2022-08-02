<?php
require_once "common.php";
isnewday(2);
addcommentary();

page_header("User freischalten");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("U?Zurück zum Usereditor","user.php");
addnav("Aktualisieren","activate.php");

output("`n`n`n`n`n");

if ($_GET['op']=='activate') {
        $sql = 'SELECT emailaddress FROM accounts WHERE activated="0" AND acctid='.$_GET['userid'];
        $res = db_query($sql);
        if (db_affected_rows()==1) {
                $row = db_fetch_assoc($res);
                if ($row['emailaddress']!='') {
                        mail(
                                $row['emailaddress'],
                                "LoGD Account Freischaltung",
                                "Dein LoGD-Account wurde von einem Admin freigeschaltet! Du kannst dich ab jetzt einloggen.",
                                "From: ".getsetting("gameadminemail","postmaster@localhost.com")
                        );
                }
                db_query('UPDATE accounts SET activated="1" WHERE acctid='.$_GET['userid']);
                adminlog();
        }
} elseif ($_GET['op']=='userdelete') {
        output("`c`bUser löschen`b`c`n");
        $sql = "SELECT name FROM accounts WHERE acctid=".$_GET['userid'];
        $result = db_query($sql) or die(sql_error($sql));
        $row = db_fetch_assoc($result) or die(db_error(LINK));
        output('`nUser `$'.$row['name'].'`0 aus folgendem Grund löschen: ');

        output('<form action="activate.php?op=douserdelete&userid='.$_GET['userid'].'" method="POST">',true);
        output('Begründung auswählen: ');
        output('<select name="stdreason" size="1">',true);
        output('<option value="0">--- keine Begründung angeben ---</option>',true);
        $sql = 'SELECT reasonid, reason FROM deluser_reasons';
        $result = db_query($sql);
        while ($row = db_fetch_assoc($result)) {
                if (strlen($row['reason'])>50) $row['reason'] = substr($row['reason'],0,47).'...';
                output('<option value="'.$row['reasonid'].'">'.htmlentities($row['reason']).'</option>',true);
        }
        output('</select>',true);
        output(' `n`ioder`i eingeben: ');
        output('<input type="Text" name="reason" value="" maxlength="255" size="50" /> ',true);
        output('<input type="Checkbox" name="savereason" value="1">',true);
        output('Begründung speichern`n');
        output('</option>',true);

        output('<input type="Submit" value="löschen">',true);
        output('</form>',true);
        addnav('','activate.php?op=douserdelete&userid='.$_GET['userid']);
} elseif ($_GET['op']=='douserdelete') {
        deleteuser($_GET['userid'],$_POST['stdreason'],$_POST['reason'],$_POST['savereason'],false);
        adminlog();
}

output("<table>",true);
output("<tr>
<td>Ops</td>
<td><a href='activate.php?sort=login'>Login</a></td>
<td><a href='activate.php?sort=name'>Name</a></td>
<td><a href='activate.php?sort=laston'>Angemeldet</a></td>
<td><a href='activate.php?sort=lastip'>IP</a></td>
<td><a href='activate.php?sort=uniqueid'>ID</a></td>
<td><a href='activate.php?sort=emailaddress'>E-Mail</a></td>
</tr>",true);
addnav("","activate.php?sort=login");
addnav("","activate.php?sort=name");
addnav("","activate.php?sort=laston");
addnav("","activate.php?sort=lastip");
addnav("","activate.php?sort=uniqueid");
addnav("","activate.php?sort=emailaddress");

$order = "a.acctid";
if ($_GET['sort']!="") $order = "a.$_GET[sort]";
$offset=(int)$_GET['page']*100;
$sql = 'SELECT a.acctid,a.login,a.name,a.laston,a.lastip,a.emailaddress, a.uniqueid, IF(b.acctid>0,COUNT(*),0) AS multis FROM accounts a LEFT JOIN accounts b ON b.uniqueid=a.uniqueid AND b.acctid!=a.acctid WHERE a.activated="0" GROUP BY a.acctid ORDER BY '.$order.' LIMIT '.$offset.',100';
$result = db_query($sql) or die(db_error(LINK));
$rn = 0;
while ($row=db_fetch_assoc($result)) {
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Heute";
        elseif (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
        else $laston = date('d.m.Y',strtotime($row['laston']));
        $row[laston]=$laston;
        if ($row[$order]!=$oorder) $rn++;
        $oorder = $row[$order];
        output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);

        output("<td>",true);
        output("[<a href='activate.php?op=userdelete&userid=$row[acctid]'>Del</a>|".
                "<a href='activate.php?op=activate&userid=$row[acctid]'>Freischalten</a>]",true);
        addnav("","activate.php?op=userdelete&userid=".$row['acctid']);
        addnav("","activate.php?op=activate&userid=$row[acctid]");
        output("</td><td>",true);
        output($row['login']);
        output("</td><td>",true);
        output($row['name']);
        output("</td><td>",true);
        output($laston);
        output("</td><td>",true);
        output($row[lastip]);
        output("</td><td>",true);
        if ($row['multis']>1) {
                output('`4'.$row['uniqueid'].' (<a href="user.php?op=search&q='.$row['uniqueid'].'">'.$row['multis'].' weitere</a>)`0',true);
                addnav('','user.php?op=search&q='.$row['uniqueid']);
        }
        elseif ($row['multis']==1) {
                output($row['uniqueid'].' (<a href="user.php?op=search&q='.$row['uniqueid'].'">'.$row['multis'].' weiterer</a>)',true);
                addnav('','user.php?op=search&q='.$row['uniqueid']);
        }
        else output($row['uniqueid'].' ('.$row['multis'].' weitere)');
        output("</td><td>",true);
        output($row[emailaddress]);
        output("</td>",true);

        output("</tr>",true);
}

output("</table>",true);
page_footer();
?> 