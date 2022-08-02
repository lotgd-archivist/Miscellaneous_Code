<?php
/* coded by Ithil dae (alias Abraxas)
* Email: questbraxel@web.de
* April 2005
* www.zum-tanzenden-troll.de ; www.tanzender-troll.de
* v 0.01
* Wer einen Rhechtschraibfeler findet darf ihn behalten.
*
* Abegändert und gesäubert von Garlant
* Support auf Anfrage bei garlant@timeofmagic.de und bei Anpera.net
*/

require_once("common.php");
page_header("Aeris das Blumenmädchen");
output('`c`b`&Aeris das Blumenmädchen`0`b`c`n`n');

if ($_GET['op']=='' || $_GET['op']=='zurueck'){
addnav('Zurück','gardens.php');

output('`rSchüchtern steht sie da, ihr Kleid is dreckig, ihr Blick rührt von Trauer...`n
Aeris ist die Hüterin dieser Wunderschönen Blumen. Sie Hegt und pflegt dieser als wären es ihre kinder.`n
Du kommst zu der Stelle des Gartens, an der sie ihren Blumenladen hat. `8"Kommt herein und seht euch um"`r, sagt sie zu dir.');
addnav('Sträuse der anderen Art','blumenmaedchen.php?op=drachen');
if ($session['user']['gold'] > 10)  allownav('blumenmaedchen.php?op=send&op2=gift2');
if ($session['user']['gold'] > 25)  allownav('blumenmaedchen.php?op=send&op2=gift3');
if ($session['user']['gold'] > 50)  allownav('blumenmaedchen.php?op=send&op2=gift4');
if ($session['user']['gold'] > 80)  allownav('blumenmaedchen.php?op=send&op2=gift5');
if ($session['user']['gold'] > 80)  allownav('blumenmaedchen.php?op=send&op2=gift6');
if ($session['user']['gold'] > 100) allownav('blumenmaedchen.php?op=send&op2=gift7');
if ($session['user']['gold'] > 250) allownav('blumenmaedchen.php?op=send&op2=gift8');
if ($session['user']['gold'] > 700) allownav('blumenmaedchen.php?op=send&op2=gift9');
if ($session['user']['gold'] > 1000) allownav('blumenmaedchen.php?op=send&op2=gift10');
if ($session['user']['gold'] > 1000) allownav('blumenmaedchen.php?op=send&op2=gift11');

if ($session['user']['gold'] < 10){
      output('`n`n`&`cDu hast nicht genug Gold um Blumen kaufen zu können.`c`0',true);
    }
rawoutput("<table border='0' cellpadding='1'>");
  if ($session['user']['gold'] > 10){
  rawoutput("<tr class='trhead'><td><b>Blumen</b></td><td align='center'><b>Preis</b></td></tr> <tr><td><a href='blumenmaedchen.php?op=send&op2=gift2'>Ein Gänseblümchen</a></td><td>10 Gold</td></tr>");
  }
    if ($session['user']['gold'] > 25){
    rawoutput("<tr><td><a href='blumenmaedchen.php?op=send&op2=gift3'>Eine Wild Rose</a></td><td>25 Gold</td></tr>");
  }
    if ($session['user']['gold'] > 50){
    rawoutput("<tr><td><a href='blumenmaedchen.php?op=send&op2=gift4'>Eine Rote Rose</a></td><td>50 Gold</td></tr>");
  }
    if ($session['user']['gold'] > 80){
    rawoutput("<tr><td><a href='blumenmaedchen.php?op=send&op2=gift5'>Eine Weiße Rose</a></td><td>80 Gold</td></tr>");
  }
    if ($session['user']['gold'] > 80){
    output("<tr><td><a href=\"blumenmaedchen.php?op=send&op2=gift6\">Eine Schwarze Rose</a></td><td>80 Gold</td></tr>",true);
  }
    if ($session['user']['gold'] > 100){
    rawoutput("<tr><td><a href='blumenmaedchen.php?op=send&op2=gift7'>Straus Gänseblümchen</a></td><td>100 Gold</td></tr>");
  }
    if ($session['user']['gold'] > 250){
    rawoutput("<tr><td><a href='blumenmaedchen.php?op=send&op2=gift8'>Straus Wild Rosen</a></td><td>250 Gold</td></tr>");
  }
    if ($session['user']['gold'] > 700){
    rawoutput("<tr><td><a href='blumenmaedchen.php?op=send&op2=gift9'>Straus Rote Rosen</a></td><td>700 Gold</td></tr>");
  }
    if ($session['user']['gold'] > 1000){
    rawoutput("<tr><td><a href='blumenmaedchen.php?op=send&op2=gift10'>Straus Weiße Rosen</a></td><td>1000 Gold</td></tr>");
  }
    if ($session['user']['gold'] > 1000){
    rawoutput("<tr><td><a href='blumenmaedchen.php?op=send&op2=gift11'>Straus Schwarze Rosen</a></td><td>1000 Gold</td></tr>");
  }

rawoutput('</table></ul>');

}
if ($_GET['op']=='drachen'){
output('`n`n`r Du gehst in eine Ecke in der Blumen einer anderen, besonderen Art stehen. Du beginnst dich zu erkundigen, was dies den für eigenartige Blumen sind.
`8"Dies sind meine Drachensträuße. Viele sagen ihnen eine geheimnisvolle Wirkung nach, doch für mich sind diese einfach nur besonders schön und daher auch nicht
billig zu haben, da ich mich nur ungern von diesen Sträusen trenne."`r, erklärt dir Aeris.`8"Ich habe drei verschiedene Arten der Drachensträuße. Jeweils eine
schöner als die andere Art.`8"`n`n');
allownav('blumenmaedchen.php?op=send1&op2=gift12');
allownav('blumenmaedchen.php?op=send1&op2=gift13');
allownav('blumenmaedchen.php?op=send1&op2=gift14');
addnav("Zurück","blumenmaedchen.php?op=zurueck");
if ($session['user']['gold'] > 2000){
output("`n<a href='blumenmaedchen.php?op=send1&op2=gift12'>Kleiner Drachenstraus</a> `8\"Dieser kleine Drachenstraus kostest dich 2000 Gold.\" ",true);
} else {
output("Kleiner Drachenstraus`n `8\"Dieser kleine Drachenstraus kostest dich 2000 Gold, die du jedoch nicht bei dir hast.\" ");
}
if ($session['user']['gold'] > 3000){
output("`n<a href='blumenmaedchen.php?op=send1&op2=gift13'>Drachenstraus</a> `8\"Dieser Drachenstraus kostest dich 3000 Gold und ist um einiges schöner
als der kleine Drachenstraus.\" ",true);
} else {
output("Drachenstraus`n `8\"Für einen Drachenstrauß dieser Größe fehlt dir leider das nötige Gold.\" ");
}
if ($session['user']['gold'] > 3000){
output("`n<a href='blumenmaedchen.php?op=send1&op2=gift14'>Großer Drachenstraus</a> `8\"Dieser Große Drachenstraus kostest dich 4000 Gold und ist der schönste
der Drachensträuse.\" ",true);
} else {
output("Großer Drachenstraus`n `8\"Für diesen besonderen Drachenstraus fehlt dir leider das nötige Gold.\" ");
}
}
if ($_GET['op']=='send'){
addnav('Zurück','gardens.php');
$gift=$_GET['op2'];
if (isset($_POST['search']) || $_GET['search']>""){
if ($_GET['search']>"") $_POST['search']=$_GET['search'];
$search="%";
for ($x=0;$x<strlen_c($_POST['search']);$x++){
$search .= substr_c($_POST['search'],$x,1)."%";
}
$search="name LIKE '".mysql_real_escape_string($search)."' AND ";
if ($_POST['search']=='weiblich') $search="sex=1 AND ";
if ($_POST['search']=='männlich') $search="sex=0 AND ";
}else{
$search='';
}
$ppp=25; // Player Per Page to display
if (!$_GET['limit']){
$page=0;
}else{
$page=(int)$_GET['limit'];
addnav('Vorherige Seite',"blumenmaedchen.php?op=send&op2=$gift&limit=".($page1)."&search=".$_POST['search']);
}
$limit="".($page$ppp).",".($ppp1);
$sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session['user']['acctid']." AND lastip<>'".$session['user']['lastip']."' ORDER BY login,level LIMIT $limit";
$result = db_query($sql);
if (db_num_rows($result)>$ppp) addnav("Nächste Seite","blumenmaedchen.php?op=send&op2=$gift&limit=".($page1)."&search=".$_POST['search']);
output("`r`nÜberglücklich strahlt dich Aeris an.`n \"Für wen sind die Blumen denn bestimmt?\"`n`n");
output("<form action='blumenmaedchen.php?op=send&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='".$_POST['search']."'><input type='submit' class='button' value='Suchen'></form>",true);
allownav("blumenmaedchen.php?op=send&op2=$gift");
rawoutput("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>");
for ($i=0;$i<db_num_rows($result);$i++){
$row = db_fetch_assoc($result);
output("<tr class='".($i2?"trlight":"trdark")."'><td><a href='blumenmaedchen.php?op=send2&op2=$gift&name=".$row['acctid']."'>",true);
output($row['name']);
rawoutput("</a></td><td>");
output($row['level']);
rawoutput("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>");
allownav("blumenmaedchen.php?op=send2&op2=$gift&name=".$row['acctid']);
}
rawoutput('</table>');
}
if ($_GET['op']=='send1'){
addnav('Zurück','gardens.php');
$gift=$_GET['op2'];
if (isset($_POST['search']) || $_GET['search']>''){
if ($_GET['search']>'') $_POST['search']=$_GET['search'];
$search="%";
for ($x=0;$x<strlen_c($_POST['search']);$x++){
$search .= substr_c($_POST['search'],$x,1)."%";
}
$search="name LIKE '".mysql_real_escape_string($search)."' AND ";
if ($_POST['search']=='weiblich') $search="sex=1 AND ";
if ($_POST['search']=='männlich') $search="sex=0 AND ";
}else{
$search='';
}
$ppp=25; // Player Per Page to display
if (!$_GET['limit']){
$page=0;
}else{
$page=(int)$_GET['limit'];
addnav('Vorherige Seite',"blumenmaedchen.php?op=send1&op2=$gift&limit=".($page1)."&search=".$_POST['search']);
}
$limit="".($page$ppp).",".($ppp1);
$sql = "SELECT login,name,level,sex,acctid,dragonkills FROM accounts WHERE $search locked=0 AND acctid<>".$session['user']['acctid']." AND lastip<>'".$session['user']['lastip']."' AND charm>1 AND level<2 AND dragonkills>1 ORDER BY login,level LIMIT $limit";
$result = db_query($sql);
if (db_num_rows($result)>$ppp) addnav('Nächste Seite',"blumenmaedchen.php?op=send1&op2=$gift&limit=".($page1)."&search=".$_POST['search']);
output("`r`nÜberglücklich strahlt dich Aeris an.`n \"Für wen sind die Blumen denn bestimmt?\"`n`n");
output("<form action='blumenmaedchen.php?op=send1&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='".$_POST['search']."'><input type='submit' class='button' value='Suchen'></form>",true);
allownav("blumenmaedchen.php?op=send1&op2=$gift");
rawoutput("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>");
for ($i=0;$i<db_num_rows($result);$i++){
$row = db_fetch_assoc($result);
rawoutput("<tr class='".($i2?"trlight":"trdark")."'><td><a href='blumenmaedchen.php?op=send2&op2=$gift&name=".$row['acctid']."'>");
output($row['name']);
rawoutput("</a></td><td>");
output($row['level']);
rawoutput("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>");
allownav("blumenmaedchen.php?op=send2&op2=$gift&name=".$row['acctid']);
}
rawoutput("</table>");
}
if ($_GET['op']=='send2'){
$name=(int)$_GET['name'];
$effekt="";
if ($_GET['op2']=='gift2'){
  $gift='Gänseblümchen';
  $session['user']['gold']-=10;
  $effekt='Ich lieeeebe `^Gänseblümchen';
}
if ($_GET['op2']=='gift3'){
  $gift='Wild Rose';
  $session['user']['gold']-=25;
  $effekt='Ich lieeeebe `@Wild Rosen`0.';
}
if ($_GET['op2']=='gift4'){
  $gift='Rote Rose';
  $session['user']['gold']-=50;
  $effekt='Ich lieeeebe `4Rote Rosen`0`n und dich auch';
}
if ($_GET['op2']=='gift5'){
  $gift='Weiße Rose';
  $session['user']['gold']-=80;
  $effekt='Ich lieeeebe `&Weiße Rosen`0';
}
if ($_GET['op2']=='gift6'){
  $gift='Schwarze Rose';
  $session['user']['gold']-=80;
  $effekt='Ich lieeeebe `~Schwarze Rosen`0.';
}
if ($_GET['op2']=='gift7'){
  $gift='Straus Gänseblümchen';
  $session['user']['gold']-=100;
  $effekt='Ohh, wie niedlich. Ein Paar `^Gäneblümlein`0.';
}
if ($_GET['op2']=='gift8'){
  $gift='Straus Wild Rosen';
  $session['user']['gold']-=250;
  $effekt='Zwar hat diese Rosenart Stacheln, aber das Stört dich bei dem Anblick der wunderschönen Rosen nicht.';
}
if ($_GET['op2']=='gift9'){
  $gift='Straus Rote Rosen';
  $session['user']['gold']-=700;
  $effekt='Du bermerkst, dass dich da wer sehr zu mögen scheint. `nDie `4Roten Rosen`0 sind wunderschön.';
}
  if ($_GET['op2']=='gift10'){
  $gift='Straus Weiße Rosen';
  $session['user']['gold']-=1000;
  $effekt='Die `&Weißen Rosen`0 erinnern dich an etwas, du weißt nur nicht was.';
}
if ($_GET['op2']=='gift11'){
  $gift='Straus Schwarze Rosen';
  $session['user']['gold']-=1000;
  $effekt='Du betrachtest den Straus `~Schwarze Rosen`0 und weißt das diese etwas besonderes und zugleich äußerst selten sind.';
}
if ($_GET['op2']=='gift12'){
 $gift='kleiner Drachenstraus';
 $session['user']['gold']-=2000;
 $drachengold=e_rand(50,100);
  $effekt="Du schaust dir diesen Drachenstraus genauer an und bemerkst $drachengold Goldstücke in diesem!";
  db_query("UPDATE accounts SET gold=gold+$drachengold WHERE acctid=$name");
}
if ($_GET['op2']=='gift13'){
  $gift='Drachenstraus';
  $session['user']['gold']-=3000;
  $drachengold2=e_rand(70,150);
  $effekt="Du schaust dir diesen Drachenstraus genauer an und bemerkst $drachengold2 Goldstücke in diesem!";
  db_query("UPDATE accounts SET gold=gold+$drachengold2 WHERE acctid=$name");
}
if ($_GET['op2']=='gift14'){
  $gift='Großer Drachenstraus';
  $session['user']['gold']-=4000;
  $drachengold3=e_rand(90,200);
  $effekt="Du schaust dir diesen Drachenstraus genauer an und bemerkst $drachengold3 Goldstücke in diesem!";
  db_query("UPDATE accounts SET gold=gold+$drachengold3 WHERE acctid=$name");
}
$mailmessage=$session['user']['name'];
$mailmessage.="`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6";
$mailmessage.=$gift;
//you can change the following the match what you name your gift shop
$mailmessage.="`7 aus dem Blumenladen.`n".$effekt;
systemmail($name,"`2Blumen erhalten!`2",$mailmessage);
output('`rMit leuchtenden Augen nimmt Aeris die Münze entgegen. "Ich danke euch!"`n
murmelt sie schüchtern und läuft los um das eben gekaufte zu überbringen...');
addnav('Wege');
addnav('zurück','blumenmaedchen.php');
addnav('Weiter','gardens.php');
}

page_footer();
?>