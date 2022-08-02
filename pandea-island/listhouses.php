<?php
require_once "common.php";
if ($session[user][loggedin]) {
        checkday();
        if ($session[user][alive]) {
                addnav("Zurück zum Dorf","village.php");
        } else {
                addnav("Zurück zu den Schatten","shades.php");
        }
}else{
        addnav("Login Seite","index.php");
}
page_header("Häuser im Bau oder bewohnt");

$sql = "SELECT * FROM `houses` WHERE `status` < 2 ORDER BY `houseid` ASC";
$result = db_query($sql);
$max = db_num_rows($result);

output("`n`c`b$max Häuser in dieser Welt");
output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
output("<tr class='trhead'><td>#</td><td><b>Hausnummer</b></td><td><b>Name</b></td></tr>",true);

for($i=0;$i<$max;$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        $u=$i+1;
        output("$u");
        output("</td><td>",true);
        output("`^$row[houseid]`0");
        output("</td><td>",true);
        output("`^$row[housename]`0");
        output("</td></tr>",true);
}
output("</table>",true);
page_footer();
?> 