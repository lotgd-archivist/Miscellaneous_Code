
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

require_once "common.php";
require_once "func/systemmail.php";
addcommentary();
page_header("Aeris das Blumenmädchen");
output("`c`b`jA`*e`er`Xi`Qs `_das `jB`*lu`Èm`ge`en`8m`_ä`Od`ìc`Xh`qe`Qn`0`b`c`n`n");

if ($_GET['op']==""){    
    output("`jA`*e`er`Xi`Qs `_ist die Hüterin dieser wunderschönen Blumen. Sie hegt und pflegt diese, als wären es ihre Kinder.`n
        Schüchtern steht sie da, ihr Kleid ist schmutzig, ihr Blick ruht auf der bunten Blumenpracht.`n");
        
    if ($session['user']['gold'] > 10)  addnav("Einzelne Blumen");
    if ($session['user']['gold'] > 10)  addnav("Ein Gänseblümchen - 10","blumenmaedchen.php?op=send&op2=gift2");
    if ($session['user']['gold'] > 25)  addnav("Eine Wildrose - 25 Gold","blumenmaedchen.php?op=send&op2=gift3");
    if ($session['user']['gold'] > 50)  addnav("Eine Rote Rose - 50 Gold","blumenmaedchen.php?op=send&op2=gift4");
    if ($session['user']['gold'] > 80)  addnav("Eine Weiße Rose - 80 Gold","blumenmaedchen.php?op=send&op2=gift5");
    if ($session['user']['gold'] > 80)  addnav("Eine Schwarze Rose - 80 Gold","blumenmaedchen.php?op=send&op2=gift6");
    if ($session['user']['gold'] > 100) addnav("Blumensträuße");
    if ($session['user']['gold'] > 100) addnav("Strauß Gänseblümchen - 100 Gold","blumenmaedchen.php?op=send&op2=gift7");
    if ($session['user']['gold'] > 250) addnav("Strauß Wildrosen - 250 Gold","blumenmaedchen.php?op=send&op2=gift8");
    if ($session['user']['gold'] > 700) addnav("Strauß Rote Rosen - 700 Gold","blumenmaedchen.php?op=send&op2=gift9");
    if ($session['user']['gold'] > 1000) addnav("Strauß Weiße Rosen - 1000 Gold","blumenmaedchen.php?op=send&op2=gift10");
    if ($session['user']['gold'] > 1000) addnav("Strauß Schwarze Rosen - 1000 Gold","blumenmaedchen.php?op=send&op2=gift11");

    if ($session['user']['gold'] > 10){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift2\">Ein Gänseblümchen - 10 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift2");
    }
      if ($session['user']['gold'] > 25){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift3\">Eine Wildrose - 25 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift3");
    }
      if ($session['user']['gold'] > 50){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift4\">Eine Rote Rose - 50 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift4");
    }
      if ($session['user']['gold'] > 80){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift5\">Eine Weiße Rose - 80 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift5");
    }
      if ($session['user']['gold'] > 80){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift6\">Eine Schwarze Rose - 80 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift6");
    }
      if ($session['user']['gold'] > 100){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift7\">Strauß Gänseblümchen - 100 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift7");
    }
      if ($session['user']['gold'] > 250){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift8\">Strauß Wildrosen - 250 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift8");
    }
      if ($session['user']['gold'] > 700){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift9\">Strauß Rote Rosen  - 700 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift9");
    }
      if ($session['user']['gold'] > 1000){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift10\">Strauß Weiße Rosen - 1000 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift10");
    }
      if ($session['user']['gold'] > 1000){
      output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift11\">Strauß Schwarze Rosen - 1000 Gold</a>",true);
      addnav("","blumenmaedchen.php?op=send&op2=gift11");
    }
    output("</ul>",true);
    output("`n`n");
    viewcommentary("blumenmaedchen","Unterhalten",25,"sagt",1,1);
}

if ($_GET['op']=="send"){
    addnav("Zurück","marktplatz.php");
    $gift=$_GET['op2'];
    if (isset($_POST['search']) || $_GET['search']>""){
        if ($_GET['search']>"") $_POST['search']=$_GET['search'];
        $search="%";
        for ($x=0;$x<strlen($_POST['search']);$x++){
            $search .= substr($_POST['search'],$x,1)."%";
        }
        $search="name LIKE '".$search."' AND ";
        if ($_POST['search']=="weiblich") $search="sex=1 AND ";
        if ($_POST['search']=="männlich") $search="sex=0 AND ";
    }else{
        $search="";
    }
    $ppp=25; // Player Per Page to display
    if (!$_GET[limit]){
        $page=0;
    }else{
        $page=(int)$_GET[limit];
        addnav("Vorherige Seite","blumenmaedchen.php?op=send&op2=$gift&limit=".($page-1)."&search=$_POST[search]");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND lastip<>'".$session[user][lastip]."' AND charm>1 ORDER BY login,level LIMIT $limit";
    $result = db_query($sql);
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","blumenmaedchen.php?op=send&op2=$gift&limit=".($page+1)."&search=$_POST[search]");
    output("`r`nÜberglücklich strahlt dich Aeris an.`n \"Für wen sind die Blumen denn bestimmt?\"`n`n");
    output("<form action='blumenmaedchen.php?op=send&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
    addnav("","blumenmaedchen.php?op=send&op2=$gift");
    output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='blumenmaedchen.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid'])."'>",true);
        output($row['name']);
        output("</a></td><td>",true);
        output($row['level']);
        output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);
        addnav("","blumenmaedchen.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid']));
    }
        output("</table>",true);
    }
if ($_GET['op']=="send2"){
    $name=$_GET['name'];
    $effekt="";
    if ($_GET['op2']=="gift2"){
      $gift="Gänseblümchen";
      $session['user']['gold']-=10;
      $effekt="Ich lieeeebe `^Gänseblümchen";
    }
    if ($_GET['op2']=="gift3"){
      $gift="Wildrose";
      $session['user']['gold']-=25;
      $effekt="Ich lieeeebe `@Wildrosen`0.";
    }
    if ($_GET['op2']=="gift4"){
      $gift="Rote Rose";
      $session['user']['gold']-=50;
      $effekt="Ich lieeeebe `4Rote Rosen`0`n und dich auch";
    }
    if ($_GET['op2']=="gift5"){
      $gift="Weiße Rose";
      $session['user']['gold']-=80;
      $effekt="Ich lieeeebe `&Weiße Rosen`0";
    }
    if ($_GET['op2']=="gift6"){
      $gift="Schwarze Rose";
      $session['user']['gold']-=80;
      $effekt="Ich lieeeebe `~Schwarze Rosen`0.";
    }
    if ($_GET['op2']=="gift7"){
      $gift="Strauß Gänseblümchen";
      $session['user']['gold']-=100;
      $effekt="Ohh, wie niedlich. Ein paar `^Gänseblümlein`0.";
    }
    if ($_GET['op2']=="gift8"){
      $gift="Strauß Wildrosen";
      $session['user']['gold']-=250;
      $effekt="Zwar hat diese Rosenart Stacheln, aber das stört dich bei dem Anblick der wunderschönen Rosen nicht.";
    }
    if ($_GET['op2']=="gift9"){
      $gift="Strauß Rote Rosen";
      $session['user']['gold']-=700;
      $effekt="Du bemerkst, dass dich da Jemand sehr zu mögen scheint. `nDie `4Roten Rosen`0 sind wunderschön.";
    }
      if ($_GET['op2']=="gift10"){
      $gift="Strauß Weiße Rosen";
      $session['user']['gold']-=1000;
      $effekt="Die `&Weißen Rosen`0 erinnern dich an etwas, du weißt nur nicht, was.";
    }
    if ($_GET['op2']=="gift11"){
      $gift="Strauß Schwarze Rosen";
      $session['user']['gold']-=1000;
      $effekt="Du betrachtest den Strauß `~Schwarze Rosen`0 und weißt, dass diese etwas Besonderes und zugleich äußerst selten sind.";
    }
    $mailmessage=$session['user']['name'];
    $mailmessage.="`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6";
    $mailmessage.=$gift;
    //you can change the following the match what you name your gift shop
    $mailmessage.="`7 aus dem Blumenladen.`n".$effekt;
    systemmail($name,"`2Blumen erhalten!`2",$mailmessage);
    output("`rMit leuchtenden Augen nimmt Aeris die Münze entgegen. \"Ich danke Euch!\"`n
    murmelt sie schüchtern und läuft los, um die $gift zu überbringen...");
    
    addnav("Weiter","blumenmaedchen.php");
//    addnav("Weiter","marktplatz.php");
}

navhead("Marktplatz");
addnav("Zurück","marktplatz.php");

page_footer();


