<?php
require_once "common.php";
page_header("Ü18-Liste");
addnav("Zurück","superuser.php");
$sql = "SELECT acctid,name,login,veri FROM accounts";
output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
output("<tr class='trhead'><td><b>Name</b></td><td><b>Ü18</b></td></tr>",true);
for($i=0;$i<$max;$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
       if ($session[user][loggedin]) output("<a href=\"bio.php?char=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("bio.php?char=".rawurlencode($row['login'])."").";return false;\">",true);
        //if ($session[user][loggedin]) output("<a href='bio.php?char=".rawurlencode($row['login'])."'>",true);
        if ($session[user][loggedin]) addnav("","bio.php?char=".rawurlencode($row['login'])."");
        output("`".($row[acctid]==getsetting("hasegg",0)?"^":"&")."$row[name]`0");
        if ($session[user][loggedin]) output("</a>",true);
        output("</td><td>",true);
        if($row['veri']==0) output('Unter 18');
        if($row['veri']==1) output('Über 18');
        output("</td><td align=\"center\">",true);
               output("</td></tr>",true);
}
output("</table>",true);
page_footer();
?>