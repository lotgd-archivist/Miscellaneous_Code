
<?php 
require_once "common.php"; 
//if ($session[user][locate]!=23){
    $session[user][locate]=23;
//    redirect("bio.php?char=$_GET[char]&ret=$_GET[ret]");
//}
checkday(); 
$result = db_query("SELECT login,name,level,sex,title,specialty,
                                  hashorse,acctid,age,marriedto,charisma,resurrections,bio,
                                  dragonkills,race,avatar,housekey,charm, firstday, locate,
                                  guildID, clanID, guildRank
                                  FROM accounts WHERE login='$_GET[char]'");
$row = db_fetch_assoc($result); 
$row[login] = rawurlencode($row[login]); 

page_header("Charakter Biographie: ".preg_replace("'[`].'","",$row[name])); 
if ($row[firstday]==0){
    $anzahltage="unbekannt";
}else{
    $anzahl = getsetting("daysalive",0) - $row[firstday];
    $anzahl++;
    $anzahltage = "$anzahl Spieltagen";
}
$specialty=array(0=>"nicht spezifiziert","Dunkle Künste","Mystische Kräfte","Diebeskunst"); 
//$horses=array(0=>"None","Pony","Gelding","Stallion"); 
output("`^Biographie für $row[name]"); 
if ($session[user][loggedin]) output("<a href=\"mail.php?op=write&to=$row[login]\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=$row[login]").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true); 
if (getsetting("avatare",0)==1){ 
    if ($row[avatar]){ 
        $pic_size = @getimagesize($row[avatar]); 
        $pic_width = $pic_size[0]; 
        $pic_height = $pic_size[1]; 
        output("<table><tr><td valign='top'>`n`n<img src=\"$row[avatar]\" ",true); 
        if ($pic_width > 200) output("width=\"200\" ",true ); 
        if ($pic_height > 200) output("height=\"200\" ",true ); 
        output("alt=\"".preg_replace("'[`].'","",$row[name])."\">&nbsp;</td><td valign='top'>",true); 
    } else { 
        output("<table><tr><td>(kein Bild)&nbsp;&nbsp;&nbsp;</td><td>",true); 
    } 
} 
output("`n`n`^Titel: `@$row[title]`n"); 
output("`^Level: `@$row[level]`n"); 
output("`^Alter: `@$row[age]`^ Tage`n"); 
output("`^Wiedererweckt: `@$row[resurrections]x`n"); 
output("`^Rasse: `@{$races[$row['race']]}`n"); 
output("`^Geschlecht: `@".($row[sex]?"Weiblich":"Männlich")."`n");
if ($session[user][login]==$row[login] || $session[user][superuser]>=3){
    output("`^Charm: `@$row[charm]`^ Punkte`n"); 
}
output("`^Spezialgebiet: `@".$specialty[$row[specialty]]."`n"); 

$sql = "SELECT mountname FROM mounts WHERE mountid='{$row['hashorse']}'"; 
$result = db_query($sql); 
$mount = db_fetch_assoc($result); 
if ($mount['mountname']=="") 
       $mount['mountname'] = "`iKeines`i"; 
output("`^Tier: `@{$mount['mountname']}`n"); 

if ($row['dragonkills']>0) output("`^Drachenkills: `@{$row['dragonkills']}`n"); 

output ("`^Bester Angriff: `@$row[punch]`n"); 
if ($row[housekey]) output("`^Hausnummer: `@$row[housekey]`n"); 
if ($row[marriedto]){ 
    if ($row[marriedto]==4294967295){ 
        output("`^Verheiratet mit: `@".($row[sex]?"Seth":"Violet")."`n"); 
    }elseif ($row[charisma]==4294967295){ 
        $sql = "SELECT name FROM accounts WHERE acctid='{$row['marriedto']}'"; 
        $result = db_query($sql); 
        $partner = db_fetch_assoc($result); 
        output("`^Verheiratet mit: `@{$partner['name']}`n"); 
    } 
} 
// Ort in der Bio ermitteln
$ort="unbekannt";
$wobistdu=$row[locate];
if ($wobistdu=="" || $wobistdu==" "){
    $wobistdu=0;
}
$sql5="SELECT description FROM navigation WHERE locate=".$wobistdu."";
$result5=db_query($sql5);
$anzahl5=db_num_rows($result5);
if ($anzahl5==0){
}else{
    $mach=db_fetch_assoc($result5);
    $ort=$mach[description];
}
if ($wobistdu>=500 && $wobistdu < 5000){        // Häuser
    $wobistdu-=500;
    $ort="Im Haus Nr. ".$wobistdu."";
}
if ($wobistdu>=5000 && $wobistdu <= 9500){        // Veranda
    $wobistdu-=5000;
    $ort="Auf Veranda Nr. ".$wobistdu."";
}
if ($wobistdu>=10000 && $wobistdu <= 14999){        // Clans
    $wobistdu-=10000;
    $ort="Clan Nr. ".$wobistdu."";
}
if ($wobistdu>=15000 && $wobistdu <= 19999){        // Gilden
    $wobistdu-=500;
    $ort="Gilde Nr. ".$wobistdu."";
}
output("`^Zur Zeit an Ort: `@`i".$ort."`i`n");
output("`^Im Spiel seit: `@$anzahltage`n");
if (getsetting("avatare",0)==1)output ("</td></tr></table>",true); 
if ($row['bio']>"") 
    output("`n`^Bio: `@`n".soap($row['bio'])."`n"); 
// Guilds/Clans Change
if ($row['guildID']!=0) {
    Require_once("guildclanfuncs.php");
    $ThisGuild=$session['guilds'][$row['guildID']];
    $GuildName=$ThisGuild['Name'];
    $PublicText=$ThisGuild['PublicText'];
    $sql2="select DisplayTitle from lotbd_guildranks where RankID='".$row['guildRank']."'";
              $result2=db_query($sql2);
              $row2 = db_fetch_assoc($result2);
              $Rank=$row2['DisplayTitle'];
    output("`^Gilde: `@".$GuildName."`n",true);
    output("`^Rang: `@".$Rank."`n",true);
    output("`^Motto: `@".$PublicText."`n`n");
}
if ($row['clanID']!=0) {
    Require_once("guildclanfuncs.php");
    $ThisClan=$session['guilds'][$row['clanID']];
    $ClanName=$ThisClan['Name'];
    $PublicText=$ThisClan['PublicText'];
    $sql2="select DisplayTitle from lotbd_guildranks where RankID='".$row['guildRank']."'";
              $result2=db_query($sql2);
              $row2 = db_fetch_assoc($result2);
              $Rank=$row2['DisplayTitle'];
    output("`^Clan: `@".$ClanName."`n",true);
    output("`^Rang: `@".$Rank."`n",true);
    output("`^Motto: `@".$PublicText."`n`n");
}
// End Guilds/Clans Change
output("`n`^Letzte Leistungen (und Niederlagen) von $row[name]`^"); 
$result = db_query("SELECT * FROM news WHERE accountid=$row[acctid] ORDER BY newsdate DESC,newsid ASC LIMIT 100"); 
$odate=""; 
for ($i=0;$i<db_num_rows($result);$i++){ 
    $row = db_fetch_assoc($result); 
    if ($odate!=$row[newsdate]){ 
        output("`n`b`@".date("D, M d",strtotime($row[newsdate]))."`b`n"); 
        $odate=$row[newsdate]; 
    } 
    output($row[newstext]."`n"); 
} 

if ($_GET[ret]==""){ 
    addnav("Zur Liste der Krieger","list.php"); 
}else{ 
    $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET[ret]); 
    $return = substr($return,strrpos($return,"/")+1); 
    if (strpos($return,"ewday")>0) $return = "village.php";
    addnav("Zurück",$return); 
} 
page_footer(); 

?>

