
<?php
/* This file is part of "Magic Stones"
* made by Excalibur, refer to pietre.php
* for instructions and copyright notice */
/* 20. September 2005, Änderungen von Eliwood */

$backlink = array("Zurück ins Dorf","village.php");

require_once "common.php";
page_header("Allmightys magische Steine");

addnav($backlink[0],$backlink[1]);
if(function_exists("backplace")) backplace(4);
output("`!`b`c<font size='+1'>Allmightys magische Steintafel</font>`c`b`n`n",true);

// $session['user']['clean'] += 1;
output("`@Du betrittst eine verfallene Hütte. Vor dir steht `&Allmightys magische Steintafel`@ ,auf welcher alle magische Steine abgebildet , und deren Besitzer aufgelistet sind.`n");
output("<table cellspacing=2 cellpadding=2 align='center'>",true);
output("<tr bgcolor='#FF0000'><td align='center'>`&`b#`b</td><td align='center'>`&`bStein`b</td><td align='center'>`b`&Krieger`b</td></tr>",true);
$result = db_query("SELECT items.owner,items.id,items.name,accounts.name AS ownername FROM items INNER JOIN accounts ON accounts.acctid=items.owner AND class='Allmightys Stein' ORDER BY id");

$i = 1;
while($row = db_Fetch_Assoc($result))
{
  if($row['ownername'] == $session['user']['name']) output("<tr bgcolor='#007700'>", true);
  else output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
  output("<td align='center'>`&".$i."</td><td align='center'>`&`b{$row[name]}`b</td><td align='center'>`&`b{$row[ownername]}`b</td></tr>",true);
  $i++;
}

/*for ($i = 1; $i < 21; $i++){
$sql = "SELECT name FROM accounts WHERE pietra=$i";
$result = db_query($sql);
$row = db_fetch_assoc($result);
if (db_num_rows($result) == 0) {
$row[name]="`b`\$Verfügbar`b";
$pietra1="`5Unbekannt";
}else $pietra1=$pietre[$i];
if ($row[name] == $session[user][name]) {
output("<tr bgcolor='#007700'>", true);
} else {
output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
}
output("<td align='center'>`&".$i."</td><td align='center'>`&`b$pietra1`b</td><td align='center'>`&`b{$row[name]}`b</td></tr>",true);
}*/
output("</table>", true);
page_footer();
?>


