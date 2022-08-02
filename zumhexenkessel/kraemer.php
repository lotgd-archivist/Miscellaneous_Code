<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";

page_header("Milos KrimsKrams");

output("`v`c`bMilos KrimsKrams`b`c`6 Du betrittst den Krämerladen und eine kleine Glocke über der Tür verkündet dein eintreten.`n
Es ist etwas duster im Raum bis auf die Lichtstrahlen die durch die beiden kleinen Fenster rechts und links neben der Tür dringen und in denen du die Staubpartikel siehst, die durch dein Eintreten aufgewirbelt wurden. Es ist so stickig dass du einen Augenblick glaubst keine Luft zubekommen. Abgesehen von deinen zögerlichen Schritten ist es vollkommen still im Krämerladen.`n
Lediglich das Ticken der großen alten Standuhr links in der Ecke durchbricht diese im Sekundentakt mit einem leisen Tick- Tack. Vor dir befindet sich ein großer massiver Holztresen auf dem allerlei Gläser stehen. Du gehst etwas näher heran und entdeckst Bonbons und Zuckerstangen darin. Langsam wendest du dich nach rechts um auch die Auslagen in den Regalen zu betrachten. Deine Augen weiten sich überrascht bei der riesigen Auswahl an Kerzen die du vor dir siehst. Es gibt sie in allen Farben und Größen und sogar einige mit Duftnoten wie Drachenblut, Morgensonne oder Mondschein. Ganz am Ende des Regals findest du auch einige seltsam anmutende Geräte aus Eisen, die nach Öl stinken.`n
Du gehst hinüber zur linken Seite des Raumes um auch die dortigen Waren in Augenschein zu nehmen.`n
Hier findest du verschiedene aromatischen Kaffee- und Teesorten sowie ein breites Sortiment an Musikinstrumenten unter anderem eine Blockflöte, eine Geige und eine Gitarre.`n
Du willst gerade ausprobieren wie viel von deinen früheren Musikstunden hängen geblieben ist als du schlürfende Schritte hinter dir hörst. `&Mein Name ist Milo. Mir gehört alles was du hier siehst. Willst du etwas kaufen?`n`n
`c`&`bPreisliste`b`n
`8Kugelkerze - 100 Gold`nDuftkerzen - 150 Gold`nKräütertee - 200 Gold`nSchnupftabak - 200 Gold `n
Ritualkerze - 250 Gold `nStiefelfett - 250 Gold `nHochzeitskerze - 300 Gold `nGewürze - 300 Gold `n
Felle - 350 Gold `nParfüm - 350 Gold `n
`n`n");

addnav("`bKaufen`b");
addnav("`&Kugelkerze","kraemer.php?op=klick");
addnav("`&Windlicht","kraemer.php?op=klick1");
addnav("`&Kräutertee","kraemer.php?op=klick2");
addnav("`&Schnupftabak","kraemer.php?op=klick3");
addnav("`&Ritualkerze","kraemer.php?op=klick4");
addnav("`&Stiefelfett","kraemer.php?op=klick5");
addnav("`&Hochzeitskerze","kraemer.php?op=klick6");
addnav("`&Gewürze","kraemer.php?op=klick7");
addnav("`&Felle","kraemer.php?op=klick8");
addnav("`&Parfüm","kraemer.php?op=klick9");

if($session['user']['jobid'] ==9){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","kraemer.php?op=da");
addnav("Kündigen","kraemer.php?op=go");
addnav("Stehlen","kraemer.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==10){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","kraemer.php?op=da");
addnav("Kündigen","kraemer.php?op=go");
addnav("Stehlen","kraemer.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

if ($_GET['op']=="send"){

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
addnav("Vorherige Seite","kraemer.php?op=send&op2=$gift&limit=".($page-1)."&search=$_POST[search]");
}
$limit="".($page*$ppp).",".($ppp+1);
$sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND lastip<>'".$session[user][lastip]."' AND charm>1 ORDER BY login,level LIMIT $limit";
$result = db_query($sql);
if (db_num_rows($result)>$ppp) addnav("Nächste Seite","kraemer.php?op=send&op2=$gift&limit=".($page+1)."&search=$_POST[search]");
output("`r`nÜberglücklich strahlt dich die Bedienung an.`n \"Für wen ist den das Geschenk bestimmt ?\"`n`n");
output("<form action='kraemer.php?op=send&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
addnav("","kraemer.php?op=send&op2=$gift");
output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);
for ($i=0;$i<db_num_rows($result);$i++){
$row = db_fetch_assoc($result);
output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='kraemer.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid'])."'>",true);
output($row['name']);
output("</a></td><td>",true);
output($row['level']);
output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);
addnav("","kraemer.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid']));
}
output("</table>",true);
}
if ($_GET['op']=="send2"){
$name=$_GET['name'];
$effekt="";
if ($HTTP_GET_VARS[op2]=="gift2"){

        $gift="Kugelkerze";
        $effekt="eine Kerze in Form einer Kugel";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Kugelkerze',$name,'Geschenk',5,'Diese Kugelkerze  hat dir ".$session[user][name]." geschenkt.')");
      $session['user']['gold']-=100;
   }
if ($HTTP_GET_VARS[op2]=="gift3"){

        $gift="Windlicht";
        $effekt="eine kleine Kerze in einem Tongefäß";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Windlicht',$name,'Geschenk',5,'Dieses Windlicht  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=150;
   }
if ($HTTP_GET_VARS[op2]=="gift4"){

        $gift="Kräutertee";
        $effekt="eine Mischung verschiedenster Kräuter für das Wohlbefinden";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Kräuteree',$name,'Geschenk',5,'Diesen Kräutertee  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=200;
   }
if ($HTTP_GET_VARS[op2]=="gift5"){

        $gift="Schnupftabak";
        $effekt="eine kleine Pris zur rechten Zeit sorgt für ein angenehmes Kribbeln in der Nase";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Schnupftabak',$name,'Geschenk',5,'Diesen Schnupftabak  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=200;
   }
  if ($HTTP_GET_VARS[op2]=="gift6"){

        $gift="Ritualkerze";
        $effekt="eine große Kerze passen für jedes magische Ritual";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Ritualkerze',$name,'Geschenk',5,'Dieses Ritualkerze  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=250;
   }
  if ($HTTP_GET_VARS[op2]=="gift7"){

        $gift="Stiefelfett";
        $effekt="zur Pflege des beanspruchten Schuhwerkes";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Stiefelfett',$name,'Geschenk',5,'Dieses Stiefelfett  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=250;
   }
 if ($HTTP_GET_VARS[op2]=="gift8"){

        $gift="Hochzeitskerze";
        $effekt="eine mit wunderschönem Blumenmuster verzierte weiße Kerze";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Hochzeitskerze',$name,'Geschenk',5,'Diese Hochzeitzkerze hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=300;
   }
 if ($HTTP_GET_VARS[op2]=="gift9"){

        $gift="Gewürze";
        $effekt="wohlduftende und schmackhafte Gewürze die jede Speise verfeinern";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Gewürze',$name,'Geschenk',5,'Diese Gewürze hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=300;
   }
 if ($HTTP_GET_VARS[op2]=="gift10"){

        $gift="Felle";
        $effekt="verschiedene Felle wie Schafsfelle und Rehfelle zum Kuscheln und Warmhalten";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Felle',$name,'Geschenk',5,'Diese Felle hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=350;
   }
 if ($HTTP_GET_VARS[op2]=="gift11"){

        $gift="Parfüm";
        $effekt="ein blumig duftendes Öl";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Parfüm',$name,'Geschenk',5,'Dieses Parfüm hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=350;
   }

$mailmessage=$session['user']['name'];
$mailmessage.="`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6";
$mailmessage.=$gift;
//you can change the following the match what you name your gift shop
$mailmessage.="`7 vom Krämerladen.`n".$effekt;
systemmail($name,"`2Geschenk erhalten!`2",$mailmessage);

output("`rMit leuchtenden Augen nimmt die Bedienung die Münze entgegen. \"Ich danke euch!\"`n
murmelt sie schüchtern und läuft los um die $gift zu überbringen...");

}

// Klick begin
if ($_GET['op']=="klick"){
if($session['user']['gold']>=100){
output("`rMöchtest du die Kugelkerze behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift2");
addnav("Behalten","kraemer.php?op=selbst&op3=gift2");
}elseif($session['user']['gold']<=100){
   output("Du hast keine 100 Gold übrig");
}
}
if ($_GET['op']=="klick1"){
if($session['user']['gold']>=150){
output("`rMöchtest du das Windlicht behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift3");
addnav("Behalten","kraemer.php?op=selbst&op3=gift3");
}elseif($session['user']['gold']<=150){
   output("Du hast keine 150 Gold übrig");
}
}
if ($_GET['op']=="klick2"){
if($session['user']['gold']>=200){
output("`rMöchtest du den Kräutertee behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift4");
addnav("Behalten","kraemer.php?op=selbst&op3=gift4");
}elseif($session['user']['gold']<=200){
   output("Du hast keine 200 Gold übrig");
}
}
if ($_GET['op']=="klick3"){
if($session['user']['gold']>=200){
output("`rMöchtest du den Schnupftabak behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift5");
addnav("Behalten","kraemer.php?op=selbst&op3=gift5");
}elseif($session['user']['gold']<=200){
   output("Du hast keine 200 Gold übrig");
}
}
if ($_GET['op']=="klick4"){
if($session['user']['gold']>=250){
output("`rMöchtest du die Ritualkerze behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift6");
addnav("Behalten","kraemer.php?op=selbst&op3=gift6");
}elseif($session['user']['gold']<=250){
   output("Du hast keine 250 Gold übrig");
}
}
if ($_GET['op']=="klick5"){
if($session['user']['gold']>=250){
output("`rMöchtest du das Stiefelfett behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift7");
addnav("Behalten","kraemer.php?op=selbst&op3=gift7");
}elseif($session['user']['gold']<=250){
   output("Du hast keine 250 Gold übrig");
}
}
if ($_GET['op']=="klick6"){
if($session['user']['gold']>=300){
output("`rMöchtest du die Hochzeitskerze behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift8");
addnav("Behalten","kraemer.php?op=selbst&op3=gift8");
}elseif($session['user']['gold']<=300){
   output("Du hast keine 300 Gold übrig");
}
}
if ($_GET['op']=="klick7"){
if($session['user']['gold']>=300){
output("`rMöchtest du die Gewürze behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift9");
addnav("Behalten","kraemer.php?op=selbst&op3=gift9");
}elseif($session['user']['gold']<=300){
   output("Du hast keine 300 Gold übrig");
}
}
if ($_GET['op']=="klick8"){
if($session['user']['gold']>=350){
output("`rMöchtest du die Gewürze behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift10");
addnav("Behalten","kraemer.php?op=selbst&op3=gift10");
}elseif($session['user']['gold']<=350){
   output("Du hast keine 350 Gold übrig");
}
}
if ($_GET['op']=="klick9"){
if($session['user']['gold']>=350){
output("`rMöchtest du das Parfüm behalten oder verschenken ?`n");
addnav("Verschenken","kraemer.php?op=send&op2=gift11");
addnav("Behalten","kraemer.php?op=selbst&op3=gift11");
}elseif($session['user']['gold']<=350){
   output("Du hast keine 350 Gold übrig");
}
}

if ($_GET['op']=="selbst"){
$session ['user']['acctid'];
$name=$session['user']['acctid'];
$effekt="";
if ($HTTP_GET_VARS[op3]=="gift2"){
        $gift="Kugelkerze";
        $effekt="eine Kerze in Form einer Kugel";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Kugelkerze',$name,'Geschenk',5,'Diese Kugelkerze hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=10;
   }
if ($HTTP_GET_VARS[op3]=="gift3"){
        $gift="Windlicht";
        $effekt="eine kleine Kerze in einem Tongefäß";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Windlicht',$name,'Geschenk',5,'Dieses Windlicht hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=15;
   }
if ($HTTP_GET_VARS[op3]=="gift4"){
        $gift="Kräutertee";
        $effekt="eine Mischung verschiedenster Kräuter für das Wohlbefinden";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Kräutertee',$name,'Geschenk',5,'Diesen Kräutertee  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=200;
   }
if ($HTTP_GET_VARS[op3]=="gift5"){
        $gift="Schnupftabak";
        $effekt="eine kleine Prise zur rechten Zeit sorgt für ein angenehmes Kribbeln in der Nase";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Schnupftabak',$name,'Geschenk',5,'Diesen Schnupftabak  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=200;
   }
  if ($HTTP_GET_VARS[op3]=="gift6"){
        $gift="Ritualkerze";
        $effekt="eine mit wunderschönem Blumenmuster verzierte weiße Kerze";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Ritualkerze',$name,'Geschenk',5,'Diese Ritualkerze  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=250;
   }
if ($HTTP_GET_VARS[op3]=="gift7"){
        $gift="Stiefelfett";
        $effekt="zur Pflege des beanspruchten Schuhwerkes";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Stiefelfett',$name,'Geschenk',5,'Dieses Stiefelfett  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=250;
   }
if ($HTTP_GET_VARS[op3]=="gift8"){
        $gift="Hochzeitskerze";
        $effekt="eine mit wunderschönem Blumenmuster verzierte weiße Kerze";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Hochzeitskerze',$name,'Geschenk',5,'Diese Hochzeitskerze  hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=300;
   }
if ($HTTP_GET_VARS[op3]=="gift9"){
        $gift="Gewürze";
        $effekt="wohlduftende und schmckhafte Gewürze die jede Speise verfeinern";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Gewürze',$name,'Geschenk',5,'Diese Gewürze hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=300;
   }
if ($HTTP_GET_VARS[op3]=="gift10"){
        $gift="Felle";
        $effekt="verschiedene Felle wie Schafsfelle und Rehfelle zum Kuscheln und Warmhalten";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Felle',$name,'Geschenk',5,'Diese Felle hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=350;
   }
if ($HTTP_GET_VARS[op3]=="gift11"){
        $gift="Parfüm";
        $effekt="ein blumig duftendes Öl";
      db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Parfüm',$name,'Geschenk',5,'Dieses Parfüm hat dir ".$session['user']['name']." geschenkt.')");
      $session['user']['gold']-=350;
   }

/*$mailmessage=$session['user']['accid'];
$mailmessage.="`7 Deine Ware ist geliefert worden .Es ist ein/e `6";
$mailmessage.=$gift;
//you can change the following the match what you name your gift shop
$mailmessage.="`7 vom Geschenkestand.`n".$effekt;
systemmail("`2Ware erhalten!`2",$mailmessage);*/

output("`rMit leuchtenden Augen nimmt die Bedienung die Münze entgegen. \"Ich danke euch!\"`n
murmelt sie schüchtern und läuft los um die $gift zu überbringen...");

}
if ($_GET['op']=="da"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`3Du drehst dich um und siehst dich einer komischen kleinen Gestalt gegenüber. Sie sieht aus wie ein Gnom und alles was du an Einzelheiten erkennen kannst, ist ein riesiger Zylinder auf dem Kopf, einen viel zu langen und viel zu weiten braunen Mantel und einen wunderschönen Gehstock, auf den sich das Männchen schwer zu stützen scheint. `& Seid gegrüßt.Suchst du Arbeit? `3 Du bejast diese Frage und .....`n`n");

switch(e_rand(1,5)){

       case '1':
output("`7Milo tritt noch einen Schritt zurück und mustert dich abschätzend. Du kannst nicht widerstehen und breitest die Arme aus um dich einmal im Kreis zu drehen, damit er dich von allen Seiten betrachten kann. Er grunzt leise was wohl bei ihm als Lachen galt und meint: `& Nun ja mehr als 2 Stunden wirst du wohl nicht durchhalten bei deinen mickrigen Muskeln. Du bekommst dafür 2000 Gold von mir. Und jetzt komm mit nach hinten.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['jobda']+=1;

break;

case '2':
output("`7Milo blickt nach deiner Frage ob er Arbeit für dich hat sinnend nach oben und tippt sich leicht mit einem Finger an sein Kinn. Du glaubst es dauert eine Ewigkeit bis er antwortet und schiebst das auf sein fortgeschrittenes Alter, das man unschwer an seinem faltigen Gesicht erkennen kann. Schließlich seufzt er und meint:`& Nun ja…ich glaube ich habe da noch einige Kisten im Keller und auf dem Dachboden die noch ausgepackt werden müssen. Das wirst selbst du schaffen. Ich denke in 3 Stunden hast du das erledigt und du bekommst von mir, natürlich nur wenn alles sauber in den Regalen steht, sagen wir 3000 Gold. Komm mit nach hinten ich zeige dir den Weg in den dunklen Keller.`7 Er geht kichernd voran.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;
case '3':
output("`7Du erwischst Milo gerade dabei wie er eine große Kiste vor sich herschiebt. Er hält sich den schmerzenden Rücken und blickt nach oben:`& Habt Dank ihr Götter.`7 Dann wendet er sich wieder dir zu und meint:`& Du kommst mir gerade recht. Soeben ist ein Freund von mir gestorben…ja ja traurige Sache aber nicht zu ändern…er hinterließ mir seine ganze Habe und diese muss nun schnellstmöglich aus seinem Haus hierher. Es liegt am anderen Ende des Dorfes deshalb wird dich das etwas Zeit kosten. Ich schätze so um die 4 Stunden. Ich werde dich dafür aber auch gut entlohnen, sagen wir mit 4000 Goldstücken.`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;
case '4':
output("`7Du fragst Milo händeringend nach Arbeit doch er lacht dich nur aus. Du gibst jedoch nicht so schnell auf und schließlich seufzt er genervt auf und meint: & Also gut wenn du unbedingt Arbeiten willst.Hauptsache du hörst auf mir in den Ohren zu liegen.`7 Er sieht sich kurz ratlos in seinem Laden um bevor ein Lächeln sein Gesicht erhellt.`& Wie du siehst habe ich es nicht so mit Sauberkeit und Ordnung. Also kannst du das heute übernehmen. Einen Großputz hat der Laden mal wieder nötig. Aber versuch die Kunden nicht zu stören! Ich denke in 5 Stunden kannst du mich mit 5000 Gold mehr in deiner Tasche wieder verlassen`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=2500;
$session['user']['jobda']+=1;

break;
case '5':
output("`7Du findest Milo in heller Aufregung vor sich hin murmelnd und von Zeit zu Zeit die Hände über dem Kopf zusammenschlagend. Du wiederholst deine Frage nach Arbeit erneut da er dich nicht gehört zu haben schien. Er sieht dich erst verwirrt an, schreit dann auf und sinkt vor dir auf die Knie: `& Du bist meine Rettung! Soeben ist der Händler mit meiner Lieferung für den ganzen Monat gekommen doch er besteht darauf mit seinem Wagen in 6 Stunden wieder abzufahren und ich wusste nicht wie ich die Waren in dieser wenigen Zeit hätte abladen sollen. Doch das wird nun deine Aufgabe sein. Ich gebe dir dafür 6000 Goldstücke. Und nun an die Arbeit wir haben doch keine Zeit!`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
}
}
if($session['user']['jobf'] >=1){
$session['user']['jobf']-=1;
}
}

if ($_GET['op']=="ste"){
if(($session['user']['turns'] <=2)||($session['user']['dieb'] <=1)){
switch(e_rand(1,12)){

       case '1':
output("`4Milo hat sich ein Bein gebrochen und kann seine Tageseinnahmen nicht selbst zur Bank bringen. Er sitzt aufrecht in seinem Bett und gibt dir letzte Anweisungen:`& Du gehst schnurstracks an Pegasus Laden vorbei und biegst bei MightyE  nach rechts ab. Dann  stehst du direkt vor der Bank. Dort gehst du hinein und zahlst das Geld ein.`4 Du hast ihm schon mindestens 10mal versucht zu erklären dass du ebenfalls ein Konto bei dieser Bank hast, also den Weg genau kennst, doch Milo geht lieber auf Nummer sicher. Schließlich gibt er dir einen großen Goldbeutel und ermahnt dich erneut vorsichtig zu sein. Kaum bist du as dem Haus kannst du nicht widerstehen einen Blick in den Beutel zu werfen. Soviel Gold hattest du noch nie auf einem Haufen gesehene. Deine Augen fangen gefährlich an zu glitzern als du dich umsiehst und dann einige Hände voll Gold in deiner Tasche verschwinden lässt. Den Rest bringst du brav zur Bank und als du am Ende des Arbeitstages nach hause kommst freust du dich über die 5000 Gold in deiner Tasche.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`4Du trägst weder einmal unzählige Kisten aus dem Keller in das Hinterzimmer um die Regale wieder aufzufüllen als du aus dem Verkaufsraum Milos Stimme vernimmst.`& Ich muss kurz hinüber zu Meister Golga und mit ihm über Winkel für ein neues Regal verhandeln. Mach hier unterdessen keine Unsinn`4 Dann schließt sich die Tür hinter ihm und du gehst weiter deiner Arbeit nach. Als du eine riesige Kerze in den Verkaufsraum trägst, wirfst du aus Versehen die Goldkiste herunter die auf der Tischkante stand. Schnell stellst du die Kerze ab und machst dich daran das Gold einzusammeln. Dass Milo soviel Gold hat wusstest du gar nicht und du wirst ein wenig neidisch. Du siehst dich kurz um und las niemand zu sehen ist steckst du dir etwas von dem Gold ein. Dann stellst du die Kiste wieder an ihren Platz und machst weiter deine Arbeit. Am Ende dieses Arbeitstages hast du nicht nur deinen Lohn sondern auch noch 3000 Goldstücke mehr in der Tasche.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`4Milo hat dich damit beauftragt alle Regale im Laden abzustauben. Da dies nicht unbedingt zu deinen Lieblingsaufgaben zählt gehst du dabei nicht sehr sorgfältig vor und wirfst aus Versehen die Geige herunter. Schnell blickst du dich ängstlich um ob Milo etwas bemerkt hat und hebst die glücklicherweise unbeschädigte Geige auf. Dabei fällt dir ein leises Rollen im Inneren des Instruments auf. Es wird sich doch wohl nichts durch den Sturz gelöst haben? Du lockerst vorsichtig den Boden der Geige und findest darin 6 Edelstein. Schnell lässt du sie in deine Tasche gleiten, befestigst den Boden der Geige wieder und staubst mit mehr Elan als vorher weiter die Regale ab.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`4Du findest in den tiefen von Milos Keller eine sonderbare Holzschnitzerei. Du zeigst sie ihm und er findet sie so scheußlich dass er dich damit zu Aeki schickt um sie zu verkaufen. Bei Aeki angekommen versucht dich dieser wie immer übers Ohr zu hauen, doch du kennst seinen Blick wenn dieser auf etwas Wertvolles fällt. Schließlich gibt er dir widerstrebend 20 Edelsteine für die Schnitzerei und du machst dich fröhlich pfeifend wieder auf den Weg in den Krämerladen. Plötzlich stoppst du abrupt und denkst nach. Milo weiß nicht wie viel die Schnitzerei wirklich wert war, er wollte sie nur aus seinem Laden haben. Er wird also nicht merken wenn du ein paar Edelsteine behältst, denn du hast sie ja gefunden und du musstest sie verkaufen, also wäre die Hälfte nur fair. Du überreichst Milo also freudestrahlend 16 Edelsteine, die er mit einem dankenden Grunzen annimmt. Du versuchst ein Grinsen zu unterdrücken als du die anderen 4 Edelsteine in deiner Tasche fühlst.`n`n");
$session['user']['turns']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`4Du wurdest auf den Dachboden geschickt um einige Staub bedeckte Kisten auf ihren Inhalt zu untersuchen. In der ersten findest du nur mottenzerfressene Kleidung und einige alte Briefe. Bei der zweiten Kiste musst du etwas Gewalt anwenden um sie aufzubekommen. Als du es schließlich geschafft hast siehst du eine weitere Kiste vor dir und schüttelst den Kopf. Wer kommt denn nur auf die Idee eine Kiste in einer Kiste einzuschließen. Nachdem du nun diese geöffnet hast, findest du darin eine weitere Kiste und fragst dich langsam ob du nur Kisten in dieser Kiste findest. Nach 7 geöffneten Kisten stößt du endlich auf eine kleine Kiste die bis zum Rand mit Gold gefüllt ist. Lächelnd steckst du dir zwei Hände voll in die Taschen und streckst die Hand aus um noch eine dritte folgen zu lassen als du hinter dir eine Stimme vernimmst:`& Hast du etwas gefunden?`4 Milo tauchte direkt hinter dir auf. Du zeigst ihm stolz deinen Pfund und er nimmt die Kiste mit hinunter ins Hinterzimmer. Als er verschwunden ist siehst du in deine Tasche und findest 1000 Goldstücke.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`4Du suchst im Keller nach etwas Brauchbarem für den Laden und öffnest einen Schrank. Darin findest du ein wunderschönes perlenbesticktes Brautkleid. Als du es näher betrachtest siehst du das die Knöpfe um das Kleid hinten zu schließen aus Edelsteinen bestehen, schnell machst du dich daran diese zu entfernen und lässt einen nach dem andern in deiner Tasche verschwinden. Plötzlich hörst du Schritte auf der Kellertreppe, schnell greifst du dir noch einen Edelstein ehe du dich mit dem Kleid in der Hand zu Milo umdrehst und ihm freudestrahlend deinen Fund zeigst. Er nimmt es vor sich hin murmelnd mit nach oben. Du lächelst als du in deinen Taschen 2 Edelsteine befühlst und eilst ihm dann hinterher.`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`4Milo hat ein wichtiges Gespräch mit seinem Kerzenlieferant und zieht sich mit diesem ins Hinterzimmer zurück. Er weist dich an die Kunden zu bedienen und nebenbei das Gold in der Kiste zu zählen damit er es dann zur Bank bringen kann. Da keine Kunden auftauchen beschäftigst du dich mit dem Gold. Nach einer Weile meinst du es würde Milo nicht auffallen wenn du die Sache für dich etwas interessanter gestalten würdest und zählst das Gold in der Form 5 für Milo 1 für mich. Nach einer Weile hörst du hinter dir ein Räuspern und siehst Milo im Türrahmen stehen. Der Lieferant scheint den Laden durch die Hintertür verlassen zu haben und Milo muss dich schon eine ganze Weile beobachtet haben. Du musterst ihn abschätzend um vorherzusehen wie er nun reagiert und denkst dass du gute Chance hast bis zur Tür zu kommen. Du sprintest los, wirst jedoch auf halbem Weg von etwas Schwerem im Rücken getroffen und fliegst der Länge nach hin. Du siehst nach oben und Milo steht auf deinem Rücken seine  Stock schmerzhaft in deinen Rücken bohrend: `& Na na na einen Dieb kann ich hier aber nicht brauchen. Als Ausgleich dafür was du mir bestimmt schon alles gestohlen hast nehme ich deinen heutigen Lohn. Du verschwindest jetzt besser und lässt dich hier nicht mehr sehen oder ich vergesse mich!`4 Mit diesen Worten springt er behände von deinem Rücken und du merkst dass du dich in Bezug auf seine Gebrechlichkeit wohl geirrt haben musst. Du siehst ein dass du ihm unterlegen bist, springst auf und rennst so schnell du kannst zur Tür hinaus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`4Als du wieder einmal für Milo den Laden übernommen hast kommt eine kleine alte Frau in den Laden und möchte ihren Kerzenvorrat erneuern. Sie kauft so viel ein dass du, als es ans Bezahlen geht, einfach aufrundest um nicht rechnen zu müssen. Sie merkt von alledem natürlich nichts und nickt dir lächelnd zu als sie den Laden verlässt. Nun hast du genug Zeit alles zu berechnen und merkst das eine Menge übrig bleibt. Es würde Milo bestimmt auffallen wenn mehr Gold in der Kasse wäre. Schnell legst du dir das überzählige Gold zurecht und willst es gerade in deine Tasche stecken als du aufblickst und Milo mit wachsamen Blick vor dir stehen siehst. Du lächelst ihn an und erklärst ihm schnell die Lage. Er sieht immer noch misstrauisch aus und meint: `&Ah ja dass du die Kunden etwas übers Ohr haust ist in Ordnung doch versuch das nur nicht bei mir verstanden? Ich nehme das Wechselgeld und du gehst jetzt Staub wischen. Und wenn ich dich noch einmal sehe wie du unnötigerweise mein Gold in den Händen hältst kannst du was erleben!`4 Er wirft dir noch einen drohenden Blick zu und geht dann ins Hinterzimmer.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
case '9':
output("`4Milo hat ein wichtiges Gespräch mit seinem Kerzenlieferant und zieht sich mit diesem ins Hinterzimmer zurück. Er weist dich an die Kunden zu bedienen und nebenbei das Gold in der Kiste zu zählen damit er es dann zur Bank bringen kann. Da keine Kunden auftauchen beschäftigst du dich mit dem Gold. Nach einer Weile meinst du es würde Milo nicht auffallen wenn du die Sache für dich etwas interessanter gestalten würdest und zählst das Gold in der Form 5 für Milo 1 für mich. Nach einer Weile hörst du hinter dir ein Räuspern und siehst Milo im Türrahmen stehen. Der Lieferant scheint den Laden durch die Hintertür verlassen zu haben und Milo muss dich schon eine ganze Weile beobachtet haben. Du musterst ihn abschätzend um vorherzusehen wie er nun reagiert und denkst dass du gute Chance hast bis zur Tür zu kommen. Du sprintest los, wirst jedoch auf halbem Weg von etwas Schwerem im Rücken getroffen und fliegst der Länge nach hin. Du siehst nach oben und Milo steht auf deinem Rücken seine  Stock schmerzhaft in deinen Rücken bohrend: `& Na na na einen Dieb kann ich hier aber nicht brauchen. Als Ausgleich dafür was du mir bestimmt schon alles gestohlen hast nehme ich deinen heutigen Lohn. Du verschwindest jetzt besser und lässt dich hier nicht mehr sehen oder ich vergesse mich!`4 Mit diesen Worten springt er behände von deinem Rücken und du merkst dass du dich in Bezug auf seine Gebrechlichkeit wohl geirrt haben musst. Du siehst ein dass du ihm unterlegen bist, springst auf und rennst so schnell du kannst zur Tür hinaus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`4Als du wieder einmal für Milo den Laden übernommen hast kommt eine kleine alte Frau in den Laden und möchte ihren Kerzenvorrat erneuern. Sie kauft so viel ein dass du, als es ans Bezahlen geht, einfach aufrundest um nicht rechnen zu müssen. Sie merkt von alledem natürlich nichts und nickt dir lächelnd zu als sie den Laden verlässt. Nun hast du genug Zeit alles zu berechnen und merkst das eine Menge übrig bleibt. Es würde Milo bestimmt auffallen wenn mehr Gold in der Kasse wäre. Schnell legst du dir das überzählige Gold zurecht und willst es gerade in deine Tasche stecken als du aufblickst und Milo mit wachsamen Blick vor dir stehen siehst. Du lächelst ihn an und erklärst ihm schnell die Lage. Er sieht immer noch misstrauisch aus und meint: `&Ah ja dass du die Kunden etwas übers Ohr haust ist in Ordnung doch versuch das nur nicht bei mir verstanden? Ich nehme das Wechselgeld und du gehst jetzt Staub wischen. Und wenn ich dich noch einmal sehe wie du unnötigerweise mein Gold in den Händen hältst kannst du was erleben!`4 Er wirft dir noch einen drohenden Blick zu und geht dann ins Hinterzimmer.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
case '11':
output("`4Milo hat ein wichtiges Gespräch mit seinem Kerzenlieferant und zieht sich mit diesem ins Hinterzimmer zurück. Er weist dich an die Kunden zu bedienen und nebenbei das Gold in der Kiste zu zählen damit er es dann zur Bank bringen kann. Da keine Kunden auftauchen beschäftigst du dich mit dem Gold. Nach einer Weile meinst du es würde Milo nicht auffallen wenn du die Sache für dich etwas interessanter gestalten würdest und zählst das Gold in der Form 5 für Milo 1 für mich. Nach einer Weile hörst du hinter dir ein Räuspern und siehst Milo im Türrahmen stehen. Der Lieferant scheint den Laden durch die Hintertür verlassen zu haben und Milo muss dich schon eine ganze Weile beobachtet haben. Du musterst ihn abschätzend um vorherzusehen wie er nun reagiert und denkst dass du gute Chance hast bis zur Tür zu kommen. Du sprintest los, wirst jedoch auf halbem Weg von etwas Schwerem im Rücken getroffen und fliegst der Länge nach hin. Du siehst nach oben und Milo steht auf deinem Rücken seine  Stock schmerzhaft in deinen Rücken bohrend: `& Na na na einen Dieb kann ich hier aber nicht brauchen. Als Ausgleich dafür was du mir bestimmt schon alles gestohlen hast nehme ich deinen heutigen Lohn. Du verschwindest jetzt besser und lässt dich hier nicht mehr sehen oder ich vergesse mich!`4 Mit diesen Worten springt er behände von deinem Rücken und du merkst dass du dich in Bezug auf seine Gebrechlichkeit wohl geirrt haben musst. Du siehst ein dass du ihm unterlegen bist, springst auf und rennst so schnell du kannst zur Tür hinaus.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`4Als du wieder einmal für Milo den Laden übernommen hast kommt eine kleine alte Frau in den Laden und möchte ihren Kerzenvorrat erneuern. Sie kauft so viel ein dass du, als es ans Bezahlen geht, einfach aufrundest um nicht rechnen zu müssen. Sie merkt von alledem natürlich nichts und nickt dir lächelnd zu als sie den Laden verlässt. Nun hast du genug Zeit alles zu berechnen und merkst das eine Menge übrig bleibt. Es würde Milo bestimmt auffallen wenn mehr Gold in der Kasse wäre. Schnell legst du dir das überzählige Gold zurecht und willst es gerade in deine Tasche stecken als du aufblickst und Milo mit wachsamen Blick vor dir stehen siehst. Du lächelst ihn an und erklärst ihm schnell die Lage. Er sieht immer noch misstrauisch aus und meint: `&Ah ja dass du die Kunden etwas übers Ohr haust ist in Ordnung doch versuch das nur nicht bei mir verstanden? Ich nehme das Wechselgeld und du gehst jetzt Staub wischen. Und wenn ich dich noch einmal sehe wie du unnötigerweise mein Gold in den Händen hältst kannst du was erleben!`4 Er wirft dir noch einen drohenden Blick zu und geht dann ins Hinterzimmer.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
}
}
if($session['user']['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}
}
if ($_GET['op']=="go"){
output("`3Seufzend betrittst du das Arbeitszimmer, in dem Milo sich immer drinnen verschanzt, wenn er mal nicht auf seinen Angestellten herum hackt. Wie immer saß der mickrige Gnom hinter seinem Schreibtisch und machte irgendwelchen Papierkram, von dem du ohnehin nichts verstehen konntest. `n
Mit einem mürrischen `&Was gibt’s? `3 starrt er dich an und du überlegst es dir gleich noch dreimal bevor du ihm endlich gestehst, dass du kündigen willst. `n
Du konntest richtig sehen, wie sich die Zornesfalten auf seiner Stirn bildeten und er warf dir nur noch einige Flüche hinterher zusammen mit der Meinung, dass aus dir nie etwas werden würde als du so schnell wie möglich den Laden verlässt.
`n");
$session['user']['jobid']=0;
}


addnav("`c`bzurück`c`b","village.php");
page_footer();
?>