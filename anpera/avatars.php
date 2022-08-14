
ï»¿<?php



// 20060302



//anpera



require_once("common.php");



isnewday(2);



if ($_GET['op']=="block"){

    $sql = "UPDATE accounts SET avatar='' WHERE acctid="(int)$_GET['userid'];

    systemmail((int)$_GET['userid'],"Dein Avatar wurde entfernt","Der Administrator hat beschlossen, dass dein Avatar unangebracht ist, oder nicht funktionierte, und hat ihn entfernt.`n`nWenn du darÃ¼ber diskutieren willst, benutze bitte den Link zur Hilfeanfrage.");

    db_query($sql);

}

$ppp=25; // Player Per Page to display

if (!$_GET['limit']){

    $page=0;

}else{

    $page=(int)$_GET['limit'];

    addnav("Vorherige Seite","avatars.php?limit=".($page-1));

}

$limit="".($page*$ppp).",".($ppp+1);

$sql = "SELECT name,acctid,avatar FROM accounts WHERE avatar>'' ORDER BY acctid DESC LIMIT $limit";

$result = db_query($sql);

if (db_num_rows($result)>$ppp) addnav("NÃ¤chste Seite","avatars.php?limit=".($page+1)."");

page_header("Spieleravatare");

output("`b`&Spieler Avatare - Seite ".($page+1).":`0`b`n");

for ($i=0;$i<db_num_rows($result);$i++){

    $row = db_fetch_assoc($result);

    output("`![<a href='avatars.php?op=block&userid={$row['acctid']}'>Entfernen</a> | <a href='showava.php?userid={$row['acctid']}' target='_blank' onClick=\"".popup("showava.php?userid={$row['acctid']}").";return false;\">Anzeigen</a>]",true);

    addnav("","avatars.php?op=block&userid={$row['acctid']}");

    output("`&{$row['name']}: `^{$row['avatar']}`n");

}

db_free_result($result);

addnav("G?ZurÃ¼ck zur Grotte","superuser.php");

addnav("W?ZurÃ¼ck zum Weltlichen","village.php");

addnav("Aktualisieren","avatars.php");

page_footer();

?>

