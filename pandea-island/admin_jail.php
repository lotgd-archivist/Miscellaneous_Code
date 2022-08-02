<?php
/*
LoGD - Gefängniserweiterung
19.05.2004
Matthias "Vanion" Strauch

2013-09-30    aragon    div anpassungen (anzeige der strafen)
*/

require_once "common.php";
isnewday(2);

page_header("Einen User einknasten");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("U?Zurück zum Usereditor","user.php");
addnav("Liste aktualisieren","admin_jail.php");

addnav("N?Nervenheilanstalt","sanctum.php?op=room");//Nervenheilanstallt addon


output("Hier kannst du User, die ein wenig über die Stränge geschlagen haben, zu einer Prangerstrafe verdonnern. ");
output("Die Strafe wird in Spieltagen abgesessen, das heißt der Spieler kann so lange keine Aktionen durchführen! `n`n`n`n`n");

addnav("","admin_jail.php?op=change");

if($_GET[op] == "change") {
    $gamesettime = gametime();
    $startday = strtotime(date("Y-m-d 00:00:00",$gamesettime));
    $supposedsettime = time() - ($gamesettime-$startday)/getsetting("daysperday",4);
    $endtime = date('Y-m-d H:i:s',$supposedsettime + ($_POST['newtime']*3600*24 / getsetting("daysperday",4)));
    if ((int)$_POST['jailid'] > 0) {
        $sql = 'UPDATE accounts SET jailtime="'.$_POST['newtime'].'" WHERE acctid="'.$_POST['acctid'].'"';
        db_query($sql);
        if (db_affected_rows(LINK)==1) {
            $sql = 'UPDATE jail SET setby="'.$session['user']['acctid'].'",setdate=NOW(),freedate="'.$endtime.'",gamedays="'.$_POST['newtime'].'",reason="'.$_POST['newreason'].'" WHERE jailid="'.$_POST['jailid'].'"';
            db_query($sql);
        }
    }
    else {
        $sql = 'SELECT name, acctid FROM accounts WHERE login="'.$_POST['tochange'].'"';
        $result = db_query($sql);
        if (db_num_rows($result)==1) {
            $row = db_fetch_assoc($result);
            $sql = 'UPDATE accounts SET jailtime="'.$_POST['newtime'].'" WHERE acctid="'.$row['acctid'].'"';
            db_query($sql);
            $sql = 'INSERT INTO jail (acctid,name,setby,setdate,freedate,gamedays,reason)
                    VALUES ('.$row['acctid'].',"'.$row['name'].'","'.$session['user']['acctid'].'",NOW(),"'.$endtime.'","'.$_POST['newtime'].'","'.$_POST['newreason'].'")';
            db_query($sql);
        }
    }
/*
    $sql = "UPDATE accounts set jailtime = '$_POST[newtime]', jailreason='".$_POST['newreason']."', location=9 where login = '$_POST[tochange]'";
    db_query($sql);
*/
    adminlog();
}

//$sql = "SELECT acctid, name, jailtime, jailreason, login from accounts where jailtime > 0";
$sql = 'SELECT j.*, a.name AS setname, a2.jailtime FROM jail j LEFT JOIN accounts a2 USING(acctid) LEFT JOIN accounts a ON a.acctid=j.setby WHERE j.freedate > NOW() ORDER BY j.freedate ASC';
$result = db_query($sql);

output("`c`bAktuelle Knastbrüder:`b ");
output("<table><tr><td>Name</td><td>Restdauer</td><td>Begründung</td><td>Eingetragen</td><td>Gesamtdauer</td><td>Freilassung</td><td>Ändern</td></tr>",true);

while($row = db_fetch_assoc($result)) {
    output("<form method='post' action='admin_jail.php?op=change'>
            <input type='hidden' name='acctid' value='".$row['acctid']."'>
            <input type='hidden' name='jailid' value='".$row['jailid']."'>",true);
    output("<tr><td>".$row['name']."</td>
                    <td><input type='text' size='2' maxlength='2' name='newtime' value='".$row['jailtime']."'></td>
                    <td><input type='text' size='30' maxlength='255' name='newreason' value='".$row['reason']."'></td>
                    <td>{$row['setdate']} durch {$row['setname']}</td>
                    <td>{$row['gamedays']} Tage</td>
                    <td>{$row['freedate']}</td>
                    <td><input type='submit' value='Ändern'></td>
            </tr>",true);
    output("</form>",true);
}
output("</table>`n`n",true);

output("`bNeue Knaststrafe:`b `n");
output("<form action='admin_jail.php?op=change' method='post'>Name: <input type='text' size='10' name='tochange'> IG-Tage: <input type='text' size='2' name='newtime'> Begründung: <input type='text' size='30' maxlength='255' name='newreason'> <input type='submit'></form>`c",true);

output('`bPranger-Statistik:`b`n');
output('<table><tr><td>Account</td><td>Name</td><td>Strafen</td><td>Spieltage</td><td>erste Strafe</td><td>letzte Strafe</td><td>Strafen</td></tr>',true);
$sql = "SELECT COUNT(*) AS anzahl, SUM(gamedays) AS days, name, acctid, MIN(setdate) AS firstdate, MAX(setdate) AS lastdate, GROUP_CONCAT( reason SEPARATOR ';<BR>' ) AS reasons FROM jail GROUP BY acctid, name ORDER BY anzahl DESC";
$result = db_query($sql);
while ($row = db_fetch_assoc($result)) {
    output('<tr><td>'.$row['acctid'].'</td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['anzahl'].'</td>
                    <td>'.$row['days'].'</td>
                    <td>'.$row['firstdate'].'</td>
                    <td>'.$row['lastdate'].'</td>
                    <td>'.$row['reasons'].'</td>
            </tr>',true);
}
output('</table>',true);

page_footer();

?>