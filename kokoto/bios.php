<?php
require_once "common.php";
isnewday(4);

if ($_GET['op']=="block"){
    $sql = "UPDATE accounts SET biotime='9999-12-31 23:59:59' WHERE acctid='".(int)$_GET['userid']."'";
    db_query($sql);
	systemmail($_GET['userid'],"Deine Biografie wurde gesperrt","Der Administrator hat beschlossen, dass deine Biografie unangebracht ist und hat sie gesperrt.`n`nWenn du darüber diskutieren willst, benutze bitte den Link zur Hilfeanfrage.");
	$sql = "UPDATE bio SET bio='`iGesperrt aufgrund unangebrachten Inhalts`i', tierbio='`iGesperrt aufgrund unangebrachten Inhalts`i' WHERE acctid='".(int)$_GET['userid']."'";
        db_query($sql);
}
if ($_GET['op']=="unblock"){
	$sql = "UPDATE accounts SET biotime='0000-00-00 00:00:00' WHERE acctid='".(int)$_GET['userid']."'";
	db_query($sql);
        systemmail($_GET['userid'],"Deine Biografie wurde wieder freigegeben","Der Administrator hat beschlossen, deine Biografie wieder freizugeben. Du kannst wieder eine Beschreibung eingeben.");
	$sql = "UPDATE bio SET bio='', tierbio='' WHERE acctid='".(int)$_GET['userid']."'";
        db_query($sql);
}

$sql = "SELECT accounts.acctid AS aacctid, accounts.name, accounts.biotime, bio.bio FROM accounts LEFT JOIN bio ON bio.acctid=accounts.acctid WHERE accounts.biotime<'9999-12-31' AND bio.bio>'' ORDER BY accounts.biotime DESC LIMIT 100";
$result = db_query($sql);
page_header("Spieler Biografien");
output("`b`&Spieler Biografien:`0`b`n");
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    if ($row['biotime']>$session['user']['recentcomments'])
        output("<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ",true);
    output("`![<a href='bios.php?op=block&userid={$row['aacctid']}'>Block</a>]",true);
    allownav("bios.php?op=block&userid={$row['aacctid']}");
    output("`&{$row['name']}: `^".nl2br(removeEvilAttributes(removeEvilTags($row['bio'])))."`n",true);
}
db_free_result($result);
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Aktualisieren","bios.php");
$sql = "SELECT accounts.acctid AS aacctid, accounts.name, accounts.biotime, bio.bio FROM accounts LEFT JOIN bio ON bio.acctid=accounts.acctid WHERE accounts.biotime>'9000-01-01' ORDER BY accounts.biotime DESC LIMIT 100";
$result = db_query($sql);
output("`n`n`b`&Gesperrte Beschreibungen:`0`b`n");
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    output("`![<a href='bios.php?op=unblock&userid={$row['aacctid']}'>Unblock</a>]",true);
    allownav("bios.php?op=unblock&userid={$row['aacctid']}");
    output("`&{$row['name']}: `^".soap($row['bio'])."`n");
}
db_free_result($result);
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>