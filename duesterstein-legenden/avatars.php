
<?php
require_once "common.php"; 

isnewday(2); 

if ($_GET['op']=="block"){ 
    $sql = "UPDATE accounts SET avatar='' WHERE acctid=$_GET[userid]";
    systemmail($_GET['userid'],"Dein Avatar wurde entfernt","Der Administrator hat beschlossen, dass dein Avatar unangebracht ist und hat ihn entfernt.`n`nWenn du darüber diskutieren willst, benutze bitte den Link zur Hilfeanfrage."); 
    db_query($sql); 
    //adminlog();
} 

addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Aktualisieren","avatars.php");

// Seiten zum Blättern
$sql = "SELECT count(acctid) AS count FROM accounts WHERE avatar!=''";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$page=0;
while ($row[count]>0){
    $page++;
    addnav("$page Seite $page","avatars.php?page=".($page-1));
    $row[count]-=25;
}


if (empty($_GET['page'])) $offset = $_GET['page'] = 0;
else $offset=(int)$_GET['page']*25;

$sql = "SELECT name,acctid,avatar FROM accounts WHERE avatar!='' ORDER BY acctid DESC LIMIT $offset,25";
$result = db_query($sql); 
page_header("Spieleravatare"); 
output("`b`&Spieler Avatare:`0`b`n"); 
for ($i=0;$i<db_num_rows($result);$i++){ 
    $row = db_fetch_assoc($result); 
    output("`![<a href='avatars.php?op=block&userid={$row['acctid']}'>Entfernen</a>]",true);
    addnav("","avatars.php?op=block&userid={$row['acctid']}"); 
    output("`&{$row['name']}: `^"); 
    $pic_size = @getimagesize($row[avatar]); 
    $pic_width = $pic_size[0]; 
    $pic_height = $pic_size[1]; 
    output("<img src=\"$row[avatar]\" ",true); 
    if ($pic_width > 200) output("width=\"200\" ",true ); 
    if ($pic_height > 200) output("height=\"200\" ",true ); 
    output("alt=\"$row[name]\">&nbsp;`n`n",true); 

} 
db_free_result($result); 
page_footer(); 
?>

