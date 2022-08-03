<?php
require_once "common.php";

isnewday ( 2 );

if ($_GET ['op'] == "block") {
    $sql = "UPDATE accounts SET biopet='`iGesperrt aufgrund unangebrachten Inhalts`i',biopettime='9999-12-31 23:59:59' WHERE acctid='{$_GET['userid']}'";
    systemmail ( $_GET ['userid'], "Deine Tier-Biografie wurde gesperrt", "Der Administrator hat beschlossen, dass deine Kurzbeschreibung unangebracht ist und hat sie gesperrt.`n`nWenn du darüber diskutieren willst, benutze bitte den Link zur Hilfeanfrage." );
    db_query ( $sql );
}
if ($_GET ['op'] == "unblock") {
    $sql = "UPDATE accounts SET biopet='',biopettime='0000-00-00 00:00:00' WHERE acctid='{$_GET['userid']}'";
    systemmail ( $_GET ['userid'], "Deine Tier-Biografie wurde wieder freigegeben", "Der Administrator hat beschlossen, deine Kurzbeschreibung wieder freizugeben. Du kannst wieder eine Beschreibung eingeben." );
    db_query ( $sql );
}
$sql = "SELECT name,acctid,biopet,biopettime FROM accounts WHERE biopettime<'9999-12-31' AND biopet>'' ORDER BY biopettime DESC LIMIT 100";
$result = db_query ( $sql );
page_header ( "Spieler-Tier-Kurzbeschreibungen" );
output ( "`b`&Spieler Tier Kurzbeschreibungen:`0`b`n" );
for($i = 0; $i < db_num_rows ( $result ); $i ++) {
    $row = db_fetch_assoc ( $result );
    if ($row ['biopettime'] > $session ['user'] ['recentcomments'])
        output ( "<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ", true );
    output ( "`![<a href='petbios.php?op=block&userid={$row['acctid']}'>Block</a>]", true );
    addnav ( "", "petbios.php?op=block&userid={$row['acctid']}" );
    output ( "`&{$row['name']}: `^" . soap ( $row ['biopet'] ) . "`n" );
    output ( "`n" );
}
db_free_result ( $result );
addnav ( "G?Zurück zur Grotte", "superuser.php" );
addnav ( "W?Zurück zum Weltlichen", "village.php" );
addnav ( "Aktualisieren", "petbios.php" );
$sql = "SELECT name,acctid,biopet,biopettime FROM accounts WHERE biopettime>'9000-01-01' AND biopet>'' ORDER BY biopettime DESC LIMIT 100";
$result = db_query ( $sql );
output ( "`n`n`b`&Gesperrte Beschreibungen:`0`b`n" );
for($i = 0; $i < db_num_rows ( $result ); $i ++) {
    $row = db_fetch_assoc ( $result );
    output ( "`![<a href='petbios.php?op=unblock&userid={$row['acctid']}'>Unblock</a>]", true );
    addnav ( "", "petbios.php?op=unblock&userid={$row['acctid']}" );
    output ( "`&{$row['name']}: `^`n" . soap ("`n`n". $row ['biopet'] ) . "`n");
    output ( "`n" );
}
db_free_result ( $result );
page_footer ();
?>