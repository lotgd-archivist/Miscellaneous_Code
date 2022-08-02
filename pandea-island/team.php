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
page_header("Inselteam");

output("`n`n`n`n`n`n`c`bDas Inselteam`b`n`n");
$sql = "SELECT * FROM accounts WHERE `superuser`>0 AND `isadmin`='1' ORDER BY acctid DESC";
$result = db_query($sql) or die(sql_error($sql));
$max = db_num_rows($result);

output("`bDie Admins`b`n");
output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
output("<tr class='trhead'><td><b>Name</b></td><td><b>Aufgabenbereich</b></td></tr>",true);
for($i=0;$i<$max;$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^$row[name]`0");
        output("</td><td>",true);
        output("`&$row[tasks]`0");
        output("</td></tr>",true);
}
output("</table>",true);
output("`n`n");

output("<table border=0 cellpadding=2 cellspacing=1><tr><td>",true);


output("`bDie Juweliere`b`n");
output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
output("<tr class='trhead'><td><b>Name</b></td></tr>",true);
$owner = array();
$sql = 'SELECT a.acctid, a.name FROM accounts a LEFT JOIN shops_owner s  USING(acctid) WHERE s.shopid=1 ORDER BY a.name ASC';
$result = db_query($sql);
while ($orow = db_fetch_assoc($result)) {
        $owner[] = $orow;
}
$ownerout = '';
$p=0;
foreach ($owner AS $tihsowner) {
        if ($tihsowner['acctid']=='3632' || $tihsowner['acctid']=='10453'){
           $p++;
        }else{
           output("<tr class='".($p%2?"trdark":"trlight")."'><td>",true);
           output($ownerout.$tihsowner['name']);
           $ownerout = '`0';
           output("</td></tr>",true);
        }
        $p++;
}
output("</table>",true);


output("</td><td>",true);

output("`b`cDie Stadtwachen`b`n");
output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
output("<tr class='trhead'><td><b>Name</b></td></tr>",true);
$owner = array();
$sql = 'SELECT a.acctid, a.name FROM accounts a LEFT JOIN shops_owner s USING(acctid) WHERE s.shopid=2 ORDER BY a.name ASC';
$result = db_query($sql);
while ($orow = db_fetch_assoc($result)) {
        $owner[] = $orow;
}
$ownerout = '';
$x=0;
foreach ($owner AS $tihsowner) {
        if ($tihsowner['acctid']=='3632'|| $tihsowner['acctid']=='10453'){
           $x++;
        }else{
           output("<tr class='".($x%2?"trdark":"trlight")."'><td>",true);
           output($ownerout.$tihsowner['name']);
           $ownerout = '`0';
           output("</td></tr>",true);
        }
        $x++;
}
output("</table>",true);
output("</td></tr></table>",true);

output("`c");
page_footer();
?> 