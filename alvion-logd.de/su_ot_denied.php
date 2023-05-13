
<?php
/*
* Version:    03.01.2014
* Author:    Linus
* Email:    webmaster@alvion-logd.de
* Zweck:    Admintool für die OT-Gesperrten
*
*/

require_once "common.php";
require_once "func/isnewday.php";
isnewday(2);

page_header("Chars die für den OT gesperrt sind!");
output("`c`b<big>Chars die für den OT gesperrt sind:</big>`b <img src='./images/p.gif'>`c`n`n",true);
output("`<table cellpadding=2><tr><td>`bUserID`b</td><td>`bName`b</td><td>`bLogin`b</td><td>Sperre gesetzt am:</td></tr>",true);

    
$sql = "SELECT `acctid`, `name`, `ot_denied`, `denied_time`, `login` FROM `accounts` WHERE `ot_denied`>=1 ORDER BY acctid ASC";
$result = db_query($sql);
if (db_num_rows($result)==0){
    output("<tr><td colspan=3 align='center'>`&`iEs gibt keine gesperrten Chars`i`0</td><td>&nbsp;</td><td><img src='./images/dontknow.gif'></td></tr>",true);
}else{
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);

        output("<tr><td align=\"center\"><a href=\"su_multis.php?op=search&uid=".$row['acctid']."\">".$row['acctid']."</a></td><td>$row[name]</td><td>$row[login]</td><td>".(date('d.m.Y', $row[denied_time]))."</td></tr>",true);
        addnav("","su_multis.php?op=search&uid=".$row['acctid']."");
    }
}
output("</table>",true);

addnav("Z?Zurück","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");

page_footer();


