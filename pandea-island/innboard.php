<?php

// Admin part of inn messageboard by anpera
/*
info: if anything gets changed, add comments! or I'll punch you in the face over the internet !!!

changes:
2013-09-30    aragon    fixed to the "new messageboard-system" ... the one which exists for years but noone did any bugfixing

modification:
-

*/

require_once "common.php";

isnewday(2);

page_header("Das schwarze Brett");

if ($_GET['op']=="del"){
    $sql = "UPDATE accounts SET message='',msgdate='0000-00-00 00:00:00' WHERE acctid='{$_GET['userid']}'";
    db_query($sql);
    systemmail($_GET['userid'],"`0Nachricht gelöscht","`0Ein Administrator hat deine Nachricht vom schwarzen Brett genommen.`n`nWenn du darüber diskutieren willst, wende dich an ".$session[user][name]."`0, oder benutze bitte den Link zur Hilfeanfrage.");
    adminlog();
}

//$sql = "SELECT acctid,login,name,message,msgdate FROM accounts WHERE message>'' ORDER BY msgdate DESC";

$sql="SELECT boardid FROM `messageboard` GROUP BY boardid";
$result = db_query($sql) or die(db_error(LINK));
if (db_num_rows($result)<=0){
    output("Es gibt keine Nachrichten am schwarzen Brett.");
}else{

for ($i=0;$i<db_num_rows($result);$i++){
    $row=db_fetch_assoc($result);
    output("`n`0`bAm schwarzen Brett in `\$".$row['boardid']." `0sind folgende Nachrichten zu lesen:`b");
    $sql = "SELECT acctid,name,message FROM messageboard WHERE boardid=\"".$row['boardid']."\" ORDER BY messageid DESC";
    $res2 = db_query($sql) or die(db_error(LINK));
    while($row = db_fetch_assoc($res2)){
        output("`n`n");
        output("`& $row[name]`&:`n`^$row[message] ");
        output("[<a href='innboard.php?op=del&userid=$row[acctid]'>entfernen</a>]",true);
        addnav("","innboard.php?op=del&userid=$row[acctid]");
    }


}

}
db_free_result($result);
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Aktualisieren","innboard.php");
page_footer();
?> 