
<?php
/* coded by Ithil dae (alias Abraxas)
* Email: questbraxel@web.de
* April 2005
* www.zum-tanzenden-troll.de ; www.tanzender-troll.de
* v 0.01
* Wer einen Rhechtschraibfeler findet darf ihn behalten.
*
* Abegändert und gesäubert von Garlant
* Support auf Anfrage bei garlant@timeofmagic.de und bei Anpera.net
*/

require_once("common.php");
page_header("Beim Blumenmädchen");
output("`c`b`&Das Blumenmädchen Aeris`0`b`c`n`n");

if ($_GET['op']==""){
addnav("Zurück zum Markt","market.php");
addnav("Zurück zur Stadt","village.php");
knappentraining_link('aeris');

output("`rSchüchtern steht sie da, ihr Kleid is schmutzig, ihr Blick rührt von Trauer...`n
Aeris ist die Hüterin dieser wunderschönen Blumen. Sie hegt und pflegt diese, als wären es ihre Kinder.`n
Du kommst zu der Stelle des Marktplatzes, an der sie ihren Blumenstand hat. `M\"Kommt näher und seht euch um\"`r, sagt sie zu dir.`n`n");
if ($session['user']['gold'] > 10)  addnav("Einzelne Blumen");
if ($session['user']['gold'] > 10)  addnav("Ein `IGänseblümchen`R - 10","blumenmaedchen.php?op=send&op2=gift2");
if ($session['user']['gold'] > 15)  addnav("Ein `FLöwenzahn`R - 15","blumenmaedchen.php?op=send&op2=gift12");
if ($session['user']['gold'] > 25)  addnav("Eine °#ff4d7a;Wildrose</span> - 25 Gold","blumenmaedchen.php?op=send&op2=gift3");
if ($session['user']['gold'] > 50)  addnav("Eine `Crote Rose`R - 50 Gold","blumenmaedchen.php?op=send&op2=gift4");
if ($session['user']['gold'] > 80)  addnav("Eine `¤schwarze Rose`R - 80 Gold","blumenmaedchen.php?op=send&op2=gift6");
if ($session['user']['gold'] > 80)  addnav("Eine `&Lilie`R - 80 Gold","blumenmaedchen.php?op=send&op2=gift5");
if ($session['user']['gold'] > 120)  addnav("Eine `°weiße Orchidee`R - 120 Gold","blumenmaedchen.php?op=send&op2=gift13");
if ($session['user']['gold'] > 120)  addnav("Eine `hgelbe Orchidee`R - 120 Gold","blumenmaedchen.php?op=send&op2=gift14");
if ($session['user']['gold'] > 120)  addnav("Eine `5violette Orchidee`R - 120 Gold","blumenmaedchen.php?op=send&op2=gift15");
if ($session['user']['gold'] > 100) addnav("Blumensträuße");
if ($session['user']['gold'] > 100) addnav("Strauß `IGänseblümchen`R - 100 Gold","blumenmaedchen.php?op=send&op2=gift7");
if ($session['user']['gold'] > 150)  addnav("Strauß `&Löwenzahn`R - 150 Gold","blumenmaedchen.php?op=send&op2=gift16");
if ($session['user']['gold'] > 250) addnav("Strauß °#ff4d7a;Wildrosen</span> - 250 Gold","blumenmaedchen.php?op=send&op2=gift8");
if ($session['user']['gold'] > 500) addnav("Strauß `Crote Rosen`R - 500 Gold","blumenmaedchen.php?op=send&op2=gift9");
if ($session['user']['gold'] > 800) addnav("Strauß `¤schwarze Rosen`R - 800 Gold","blumenmaedchen.php?op=send&op2=gift11");
if ($session['user']['gold'] > 800) addnav("Strauß `&Lilien`R - 800 Gold","blumenmaedchen.php?op=send&op2=gift10");
if ($session['user']['gold'] > 1200)  addnav("Strauß `°weiße Orchideen`R - 1200 Gold","blumenmaedchen.php?op=send&op2=gift17");
if ($session['user']['gold'] > 1200)  addnav("Strauß `hgelbe Orchideen`R - 1200 Gold","blumenmaedchen.php?op=send&op2=gift18");
if ($session['user']['gold'] > 1200)  addnav("Strauß `5violette Orchideen`R - 1200 Gold","blumenmaedchen.php?op=send&op2=gift19");

// Aeris' besondere Aufmerksamkeiten - addon
addnav("Besonderes");
addnav("Aeris' besondere Aufmerksamkeiten","aerisspecial.php");
// end

  if ($session['user']['gold'] > 10){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift2\">`REin `IGänseblümchen`R - 10 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift2");
  }
    if ($session['user']['gold'] > 15){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift12\">`REin `FLöwenzahn`R - 15 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift12");
  }
    if ($session['user']['gold'] > 25){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift3\">`REine °#ff4d7a;Wildrose</span>`R - 25 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift3");
  }
    if ($session['user']['gold'] > 50){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift4\">`REine `Crote Rose`R - 50 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift4");
  }
    if ($session['user']['gold'] > 80){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift6\">`REine `¤schwarze Rose`R - 80 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift6");
  }
    if ($session['user']['gold'] > 80){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift5\">`REine `&Lilie`R - 80 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift5");
  }
    if ($session['user']['gold'] > 120){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift13\">`REine `°weiße Orchidee`R - 120 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift13");
  }
    if ($session['user']['gold'] > 120){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift14\">`REine `hgelbe Orchidee`R - 120 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift14");
  }
    if ($session['user']['gold'] > 120){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift15\">`REine `5violette Orchidee`R - 120 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift15");
  }
  output('`n');
    if ($session['user']['gold'] > 100){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift7\">`REin Strauß `IGänseblümchen`R - 100 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift7");
  }
    if ($session['user']['gold'] > 150){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift16\">`REin Strauß `FLöwenzahn`R - 150 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift16");
  }
    if ($session['user']['gold'] > 250){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift8\">`REin Strauß °#ff4d7a;Wildrosen</span>`R - 250 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift8");
  }
    if ($session['user']['gold'] > 500){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift9\">`REin Strauß `Crote Rosen`R - 500 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift9");
  }
    if ($session['user']['gold'] > 800){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift11\">`REin Strauß `¤schwarze Rosen`R - 800 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift11");
  }
    if ($session['user']['gold'] > 800){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift10\">`REin Strauß `&Lilien`R - 800 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift10");
  }
    if ($session['user']['gold'] > 1200){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift17\">`REin Strauß `°weiße Orchideen`R - 1200 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift17");
  }
    if ($session['user']['gold'] > 1200){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift18\">`REin Strauß `hgelbe Orchideen`R - 1200 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift18");
  }
    if ($session['user']['gold'] > 1200){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift19\">`REin Strauß `5violette Orchideen`R - 1200 Gold`0</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift19");
  }

output("</ul>",true);

}

if ($_GET['op']=="send"){
addnav("Zurück zum Marktplatz","market.php");
addnav("Zurück zur Stadt","village.php");
$gift=$_GET['op2'];
if (isset($_POST['search']) || $_GET['search']>""){
if ($_GET['search']>"") $_POST['search']=$_GET['search'];
$search="%";
for ($x=0;$x<strlen($_POST['search']);$x++){
$search .= substr($_POST['search'],$x,1)."%";
}
$search="name LIKE '".$search."' AND ";
if ($_POST['search']=="weiblich") $search="sex=1 AND ";
if ($_POST['search']=="männlich") $search="sex=0 AND ";
}else{
$search="";
}
$ppp=25; // Player Per Page to display
if (!$_GET['limit']){
$page=0;
}else{
$page=(int)$_GET['limit'];
addnav("Vorherige Seite","blumenmaedchen.php?op=send&op2=$gift&limit=".($page-1)."&search=$_POST[search]");
}
$limit="".($page*$ppp).",".($ppp+1);
$sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session['user']['acctid']." AND lastip<>'".$session['user']['lastip']."' AND charm>1 ORDER BY login,level LIMIT $limit";
$result = db_query($sql);
if (db_num_rows($result)>$ppp) addnav("Nächste Seite","blumenmaedchen.php?op=send&op2=$gift&limit=".($page+1)."&search=$_POST[search]");
output("`r`nÜberglücklich strahlt dich Aeris an.`n `M\"Für wen sind die Blumen denn bestimmt?\"`r`n`n");
output("<form action='blumenmaedchen.php?op=send&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
addnav("","blumenmaedchen.php?op=send&op2=$gift");
output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);
for ($i=0;$i<db_num_rows($result);$i++){
$row = db_fetch_assoc($result);
output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='blumenmaedchen.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid'])."'>",true);
output($row['name']);
output("</a></td><td>",true);
output($row['level']);
output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);
addnav("","blumenmaedchen.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid']));
}
output("</table>",true);
}
if ($_GET['op']=="send2"){
$name=$_GET['name'];
$effekt="";
if ($_GET['op2']=="gift2"){
  $gift="ein `IGänseblümchen";
  $gift2="das `IGänseblümchen";
  $session['user']['gold']-=10;
  $effekt="Oh, wie niedlich, ein `IGänseblümchen`7!";
}
if ($_GET['op2']=="gift12"){
  $gift="ein `FLöwenzahn";
  $gift2="das `FLöwenzahn";
  $session['user']['gold']-=15;
  $effekt="Oft wird der `FLöwenzahn`7 als Unkraut verkannt, doch diese Blume leuchtet kräftig gelb und ist wunderschön.";
}
if ($_GET['op2']=="gift3"){
  $gift="eine °#ff4d7a;Wildrose";
  $gift2="die °#ff4d7a;Wildrose";
  $session['user']['gold']-=25;
  $effekt="`7Zwar hat diese Rosenart Dornen, doch das stört dich nicht. Die °#ff4d7a;Wildrose `7ist auf ihre Weise wunderschön.";
}
if ($_GET['op2']=="gift4"){
  $gift="eine `Crote Rose";
  $gift2="die `Crote Rose";
  $session['user']['gold']-=50;
  $effekt="`7Da scheint dich jemand sehr zu mögen. `nDie `Crote Rose`7 ist wunderschön.";
}
if ($_GET['op2']=="gift5"){
  $gift="eine `&Lilie";
  $gift2="die `&Lilie";
  $session['user']['gold']-=80;
  $effekt="`7Du betrachtest die `&Lilie`7 und gedenkst still derer, die nicht mehr unter uns weilen.";
}
if ($_GET['op2']=="gift6"){
  $gift="eine `¤schwarze Rose";
  $gift2="die `¤schwarze Rose";
  $session['user']['gold']-=80;
  $effekt="`7Ein Hauch von Magie kitzelt auf deiner Haut... Du betrachtest die `¤schwarze Rose`7 und weißt, dass sie ein Geschenk von ganz spezieller Natur ist.";
}
if ($_GET['op2']=="gift13"){
  $gift="eine `°weiße Orchidee";
  $gift2="die `°weiße Orchidee";
  $session['user']['gold']-=120;
  $effekt="`7Ihre Farbe und exotische Form machen diese `°weiße Orchidee`7 zu einem ganz besonderen Geschenk.";
}
if ($_GET['op2']=="gift14"){
  $gift="eine `hgelbe Orchidee";
  $gift2="die `hgelbe Orchidee";
  $session['user']['gold']-=120;
  $effekt="`7Ihre Farbe und exotische Form machen diese `hgelbe Orchidee`7 zu einem ganz besonderen Geschenk.";
}
if ($_GET['op2']=="gift15"){
  $gift="eine `5violette Orchidee";
  $gift2="die `5violette Orchidee";
  $session['user']['gold']-=120;
  $effekt="`7Ihre Farbe und exotische Form machen diese `5violette Orchidee`7 zu einem ganz besonderen Geschenk.";
}
if ($_GET['op2']=="gift7"){
  $gift="ein Strauß `IGänseblümchen";
  $gift2="den Strauß `IGänseblümchen";
  $session['user']['gold']-=100;
  $effekt="Oh, wie niedlich, ein Strauß `IGänseblümchen`7!";
}
if ($_GET['op2']=="gift16"){
  $gift="ein Strauß `FLöwenzahn";
  $gift2="den Strauß `FLöwenzahn";
  $session['user']['gold']-=150;
  $effekt="Oft wird der `FLöwenzahn`7 als Unkraut verkannt, doch dieser Strauß leuchtet kräftig gelb und ist wunderschön.";
}
if ($_GET['op2']=="gift8"){
  $gift="ein Strauß °#ff4d7a;Wildrosen";
  $gift2="den Strauß °#ff4d7a;Wildrosen";
  $session['user']['gold']-=250;
  $effekt="`7Zwar hat diese Rosenart Dornen, doch das stört dich nicht. Die °#ff4d7a;Wildrosen `7sind auf ihre Weise wunderschön.";
}
if ($_GET['op2']=="gift9"){
  $gift="ein Strauß `Crote Rosen";
  $gift2="den Strauß `Crote Rosen";
  $session['user']['gold']-=500;
  $effekt="`7Da scheint dich jemand sehr zu mögen. `nDie `Croten Rosen`7 sind wunderschön.";
}
  if ($_GET['op2']=="gift10"){
  $gift="ein Strauß `&Lilien";
  $gift2="den Strauß `&Lilien";
  $session['user']['gold']-=800;
  $effekt="`7Du betrachtest den Strauß `&Lilien`7 und gedenkst still derer, die nicht mehr unter uns weilen.";
}
if ($_GET['op2']=="gift11"){
  $gift="ein Strauß `¤schwarze Rosen";
  $gift2="den Strauß `¤schwarze Rosen";
  $session['user']['gold']-=800;
  $effekt="`7Ein Hauch von Magie kitzelt auf deiner Haut... Du betrachtest den Strauß aus `¤schwarzen Rosen`7 und weißt, dass er ein Geschenk von ganz spezieller Natur ist.";
}
if ($_GET['op2']=="gift17"){
  $gift="ein Strauß `°weiße Orchideen";
  $gift2="den Strauß `°weiße Orchideen";
  $session['user']['gold']-=1200;
  $effekt="`7Ihre Farben und exotischen Formen machen diese `°weißen Orchideen`7 zu einem ganz besonderen Geschenk.";
}
if ($_GET['op2']=="gift18"){
  $gift="ein Strauß `hgelbe Orchideen";
  $gift2="den Strauß `hgelbe Orchideen";
  $session['user']['gold']-=1200;
  $effekt="`7Ihre Farben und exotischen Formen machen diese `hgelben Orchideen`7 zu einem ganz besonderen Geschenk.";
}
if ($_GET['op2']=="gift19"){
  $gift="ein Strauß `5violette Orchideen";
  $gift2="den Strauß `5violette Orchideen";
  $session['user']['gold']-=1200;
  $effekt="`7Ihre Farben und exotischen Formen machen diese `5violetten Orchideen`7 zu einem ganz besonderen Geschenk.";
}
$mailmessage=$session['user']['name'];
$mailmessage.="`7 hat dir ein Geschenk geschickt. Du öffnest es. Es ist ";
$mailmessage.=$gift;
//you can change the following the match what you name your gift shop
$mailmessage.="`7 aus dem Blumenladen.`n".$effekt;
systemmail($name,"`2Blumen erhalten!`2",$mailmessage);
output("`rMit leuchtenden Augen nimmt Aeris die Münze entgegen. `M\"Ich danke euch\"`r, murmelt sie und läuft los, um {$gift2} `rzu überbringen.");

addnav("Weiter","market.php");
}

page_footer();
?>

