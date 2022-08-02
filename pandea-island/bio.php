<?php

// 25052004

require_once "common.php";
checkday();
/* Update Part 1/2 by Serena of Pandea-Island 2008-07-02 - neues Feld familie in db_query eingefuegt */
$result = db_query("SELECT groeße,augenfarbe,haarfarbe,gewicht,specialdesc,familie,login,rpstars,name,level,skill,sex,title,specialty,hashorse,acctid,age,marriedto,pvpflag,charisma,resurrections,bio,dragonkills,race,avatar,avatar2,switchavatar,useavatar2,housekey,punch FROM accounts WHERE login='$_GET[char]'");
$row = db_fetch_assoc($result);
$row[login] = rawurlencode($row[login]);

page_header("Charakter Biographie: ".preg_replace("'[`].'","",$row[name]));
$specialty=array(0=>"nicht spezifiziert","Dunkle Künste","Mystische Kräfte","Diebeskunst");
//$horses=array(0=>"None","Pony","Gelding","Stallion");
output("`^`bBiographie für $row[name]`b");
if ($session[user][loggedin]) output("<a href=\"mail.php?op=write&to=$row[login]\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=$row[login]").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
output("`n`n`^Bild:`n");
if (getsetting("avatare",0)==1){
        if ($row[switchavatar]==0){
            if ($row[avatar]){
            $pic_size = @getimagesize($row[avatar]);
            $pic_width = $pic_size[0];
            $pic_height = $pic_size[1];
            if ($pic_width > 200) $row['avatar']="http://www.nicegames.de/tobig.jpg";
            if ($pic_height > 200) $row['avatar']="http://www.nicegames.de/tobig.jpg";
            output("<table><tr><td valign='top'>`n`n<img src=\"$row[avatar]\" alt=\"".preg_replace("'[`].'","",$row[name])."\">&nbsp;</td><td valign='top'>",true);
        }else{
            output("<table><tr><td>(kein Bild)&nbsp;&nbsp;&nbsp;</td><td>",true);
        }
    }else{
            if ($row[useavatar2]==1){
            if ($row[avatar2]){
                output("<table><tr><td valign='top'>`n`n<img src=\"$row[avatar2]\" alt=\"".preg_replace("'[`].'","",$row[name])."\">&nbsp;</td><td valign='top'>",true);
            }else{
                output("<table><tr><td>(kein Bild)&nbsp;&nbsp;&nbsp;</td><td>",true);
            }
        }else{
                output("<table><tr><td>(kein Bild)&nbsp;&nbsp;&nbsp;</td><td>",true);
        }
    }
}


/*if ($row['rpstars']==0) $rp="`@Neutraler Rollenspieler";
if ($row['rpstars']==1) $rp=($row[sex]?"`@Rollenspielanfängerin":"`@Rollenspielanfänger");
if ($row['rpstars']==2) $rp=($row[sex]?"`@normale Rollenspielerin":"`@normaler Rollenspieler");
if ($row['rpstars']==3) $rp=($row[sex]?"`@gute Rollenspielerin":"`@guter Rollenspieler");
if ($row['rpstars']==4) $rp=($row[sex]?"`@ausgezeichnete Rollenspielerin":"`@ausgezeichneter Rollenspieler");
if ($row['rpstars']==5) $rp=($row[sex]?"`@vorbildliche Rollenspielerin":"`@vorbildlicher Rollenspieler");
if ($row['rpstars']==0) $rpstars="`7-";
if ($row['rpstars']==1) $rpstars="`@*";
if ($row['rpstars']==2) $rpstars="`@**";
if ($row['rpstars']==3) $rpstars="`@***";
if ($row['rpstars']==4) $rpstars="`@****";
if ($row['rpstars']==5) $rpstars="`@*****";
*/
$race=$row['race'];
output("`n`n`^Titel: `@$row[title]`n");
output("`^Level: `@$row[level]`n");
output("`^Alter: `@$row[age]`^ Tage`n");
output("`^Wiedererweckt: `@$row[resurrections]x`n");
output("`^Rasse: `@{$race}`n");
output("`^Geschlecht: `@".($row[sex]?"Weiblich":"Männlich")."`n");
if ($row['groeße']!='') output("`^Größe: `@$row[groeße]`n");
if ($row['gewicht']!='') output("`^Gewicht: `@$row[gewicht]`n");
if ($row['augenfarbe']!='') output("`^Augenfarbe: `@$row[augenfarbe]`n");
if ($race=='Gargoyle') output("`^Hautfarbe: `7steinern`n");
if ($row['haarfarbe']!=''){
   if ($race=='Echsenwesen') output("`^Schuppenfarbe: `@$row[haarfarbe]`n");
   if ($race=='Lykaner') output("`^Fellfarbe: `@$row[haarfarbe]`n");
   if ($race!='Echsenwesen' && $race!='Lykaner' && $race!='Gargoyle') output("`^Haarfarbe: `@$row[haarfarbe]`n");
}
if ($row['specialdesc']!=''){
   if ($race=='Zwerg') output("`^Bartlänge: `@$row[specialdesc]`n");
   if ($race=='Echsenwesen') output("`^Schwanzlänge: `@$row[specialdesc]`n");
   if ($race=='Lichtgestalt') output("`^Flügelspannweite: `@$row[specialdesc]`n");
}
/* Update Part 2/2 by Serena of Pandea-Island 2008-07-02 - neues Feld familie hinzugefuegt */
if ($row['familie']!='') output(" `@$row[familie]`n");
/* End Update Part 2/2 by Serena of Pandea-Island 2008-07-02 */
$skill = "SELECT name,color FROM skills WHERE id='{$row['skill']}'";
$resultskill = db_query($skill);
$faehigkeit = db_fetch_assoc($resultskill);
output("`^Spezialgebiet: $faehigkeit[color] $faehigkeit[name]`n");
//output("`^Rollenspiellevel: ".$rp."`n");
//output("`^Rollenspielauszeichnungen: ".$rpstars."`n`0");

$sql = "SELECT mountname FROM mounts WHERE mountid='{$row['hashorse']}'";
$result = db_query($sql);
$mount = db_fetch_assoc($result);
if ($mount['mountname']=="")
       $mount['mountname'] = "`iKeines`i";
output("`^Tier: `@{$mount['mountname']}`n");

if ($row['dragonkills']>0) output("`^Drachenkills: `@{$row['dragonkills']}`n");

output ("`^Bester Angriff: `@$row[punch]`n");
if ($row[housekey]) output("`^Hausnummer: `@$row[housekey]`n");
if ($row[marriedto]){
        if ($row[marriedto]==4294967295){
                output("`^Verheiratet mit: `@".($row[sex]?"Seth":"Violet")."`n");
        }elseif ($row[charisma]==4294967295){
                $sql = "SELECT name FROM accounts WHERE acctid='{$row['marriedto']}'";
                $result = db_query($sql);
                $partner = db_fetch_assoc($result);
                output("`^Verheiratet mit: `@{$partner['name']}`n");
        }
}

if ($row['pvpflag']=="5013-10-06 00:42:00") output("`4`iSteht unter besonderem Schutz`i");output ("</td></tr></table>",true);
if ($row['bio']>"")        output("`n`^Bio: `&`n".soap($row['bio'])."`n",true);

output("`n`^Letzte Leistungen (und Niederlagen) von $row[name]`^");
$result = db_query("SELECT * FROM news WHERE accountid=$row[acctid] ORDER BY newsdate DESC,newsid ASC LIMIT 100");
$odate="";
for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($odate!=$row[newsdate]){
                output("`n`b`@".date("D, M d",strtotime($row[newsdate]))."`b`n");
                $odate=$row[newsdate];
        }
        output($row[newstext]."`n");
}
        $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET[ret]);
        $return = substr($return,strrpos($return,"/")+1);
if ($_GET[ret]=="" || $return=="list.php?op=search"){
        addnav("Zur Liste der Krieger","list.php");
}else{
        addnav("Zurück",$return);
}
page_footer();

?> 