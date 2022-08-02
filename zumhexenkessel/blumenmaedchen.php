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
output("`c`b`&Aeris das Blumenmädchen`0`b`c`n`n");

if ($_GET['op']==""){
addnav("Zurück");
addnav("zum Garten","gardens.php");
addnav("zum Dorf","village.php");
addnav("zum Marktplatz","marktplatzmenue.php");

output("`rSchüchtern steht sie da, ihr Kleid is dreckig, ihr Blick rührt von Trauer...`n
Aeris ist die Hüterin dieser Wunderschönen Blumen. Sie Hegt und pflegt dieser als wären es ihre kinder.`n
Du kommst zu der Stelle des Gartens, an der sie ihren Blumenladen hat. `8\"Kommt herein und seht euch um\"`r, sagt sie zu dir.");
if ($session['user']['gold'] > 10)  addnav("Einzelne Blumen");
if ($session['user']['gold'] > 10)  addnav("Ein Gänseblümchen - 10","blumenmaedchen.php?op=send&op2=gift2");
if ($session['user']['gold'] > 25)  addnav("Eine Wild Rose - 25 Gold","blumenmaedchen.php?op=send&op2=gift3");
if ($session['user']['gold'] > 50)  addnav("Eine Rote Rose - 50 Gold","blumenmaedchen.php?op=send&op2=gift4");
if ($session['user']['gold'] > 80)  addnav("Eine Weiße Rose - 80 Gold","blumenmaedchen.php?op=send&op2=gift5");
if ($session['user']['gold'] > 80)  addnav("Eine Schwarze Rose - 80 Gold","blumenmaedchen.php?op=send&op2=gift6");
if ($session['user']['gold'] > 100) addnav("Blumensträuse");
if ($session['user']['gold'] > 100) addnav("Straus Gänseblümchen - 100 Gold","blumenmaedchen.php?op=send&op2=gift7");
if ($session['user']['gold'] > 250) addnav("Straus Wild Rosen - 250 Gold","blumenmaedchen.php?op=send&op2=gift8");
if ($session['user']['gold'] > 700) addnav("Straus Rote Rosen - 700 Gold","blumenmaedchen.php?op=send&op2=gift9");
if ($session['user']['gold'] > 1000) addnav("Straus Weiße Rosen - 1000 Gold","blumenmaedchen.php?op=send&op2=gift10");
if ($session['user']['gold'] > 1000) addnav("Straus Schwarze Rosen - 1000 Gold","blumenmaedchen.php?op=send&op2=gift11");
// nav berufscribt bei Opal
if($session['user']['jobid'] ==8){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","blumenmaedchen.php?op=da");
addnav("Kündigen","blumenmaedchen.php?op=go");
addnav("Stehlen","blumenmaedchen.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

if($session['user']['jobid'] ==7){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","blumenmaedchen.php?op=da");
addnav("Kündigen","blumenmaedchen.php?op=go");
addnav("Stehlen","blumenmaedchen.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==18){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","blumenmaedchen.php?op=dazwei");
addnav("Kündigen","blumenmaedchen.php?op=go");
addnav("Stehlen","blumenmaedchen.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
// nav berufscribt bei Opal

  if ($session['user']['gold'] > 10){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift2\">Ein Gänseblümchen - 10 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift2");
  }
    if ($session['user']['gold'] > 25){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift3\">Eine Wild Rose - 25 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift3");
  }
    if ($session['user']['gold'] > 50){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift4\">Eine Rote Rose - 50 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift4");
  }
    if ($session['user']['gold'] > 80){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift5\">Eine Weiße Rose - 80 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift5");
  }
    if ($session['user']['gold'] > 80){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift6\">Eine Schwarze Rose - 80 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift6");
  }
    if ($session['user']['gold'] > 100){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift7\">Straus Gänseblümchen - 100 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift7");
  }
    if ($session['user']['gold'] > 250){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift8\">Straus Wild Rosen - 250 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift8");
  }
    if ($session['user']['gold'] > 700){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift9\">Straus Rote Rosen  - 700 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift9");
  }
    if ($session['user']['gold'] > 1000){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift10\">Straus Weiße Rosen - 1000 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift10");
  }
    if ($session['user']['gold'] > 1000){
    output("`n<a href=\"blumenmaedchen.php?op=send&op2=gift11\">Straus Schwarze Rosen - 1000 Gold</a><br>",true);
    addnav("","blumenmaedchen.php?op=send&op2=gift11");
  }

output("</ul>",true);

}
//berufscribt bei Opal
if ($_GET['op']=="da"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`Langsam und zögerlich betrittst du den Blumenladen , dies soll nun deine neue Arbeitstelle sein ? Sachte schaust du dich um , plötzlich hörst du eine zarte Stimme `& Kann ich euch weiterhelfen? `2 fragt die Stimme . `n sacht und zögerlich fragst du `& Habt ihr Arbeit für mich `2 dann schaust du zu der zierlichen Person und wartest mit einem lächeln auf deinen Lippen auf Antwort. Die Person nickt dir zu `& Ja ich habe Arbeit für euch ,übrigens man nennt mich Aeris. Aber jetzt an die Arbeit !!!`n`n");

switch(e_rand(1,5)){

       case '1':
output("`& Ich kann euch heute für 2 Stunden Arbeit geben dafür bekommt ihr 1000 Gold von mir.`6 sagt Aeris zu dir und fügt dann noch zu `& Bitte bindet die Bestellungen die Zettel liegen auf dem Ladentisch. `6 `nDann verschwindet sie in einem der hinteren Räume und du machst dich an die Arbeit.`n Nach 2 Stunden verabschiedest du dich bei Aeris und steckst froh gelaunt das Gold ein.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['jobda']+=1;

break;

case '2':
output("`2Aeris schaut dich kurz an lächelt dann `& Könnten sie  3 Stunden auf den Laden aufpassen ich müsste ein paar Besorgungen tätigen ? `2froh nickst du ihr zu `&Ja sicher kann ich dieses `2  antwortest du. Aeris verabschiedet sich und legt dir deinen Lohn auf die Theke . Schnell packst du die 1500 Goldstücke ein und bedienst dann den ersten Kunden`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;
case '3':
output("`& Ich habe viel Arbeit heute für sie `2 meint Aeris zu dir und leise fügt sie hinzu `& Ich denke ihr braucht dafür vier Stunden das ist mir dann 2000 Goldstücke wert.`n `2 Freundlich nickt sie dir dabei zu und du machst dich an die Arbeit und steckst das Gold ein `n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;
case '4':
output("`&Schnell ihr müsst mir 5 Stunden helfen `2 sagt Aeris zu dir und deutet auf einen Stapel von Zetteln , beim genauen hinsehen staunst du nicht schlecht denn es sind alles Bestellungen .`n Schnell machst du dich ran und bearbeitest eine nach der anderen. Nach 5 Stunden sind alle Bestellungen verschickt und Aeris übereicht dir 2500 Gold als Lohn.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=2500;
$session['user']['jobda']+=1;

break;
case '5':
output("`2Etwas gestresst schaut dich Aeris an der Laden ist voller Kundschaft , sofort beginnst du die Leute mit zu bedienen.`n Nach 6 Stunden ist alle Arbeit erledigt und Aeris sagt zu dir `& Danke für eure Hilfe `2 Dann überreicht sie dir ein Säckchen mit 3000 Gold`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
}
}
if($session['user']['jobf'] >=1){
$session['user']['jobf']-=1;
}
addnav("Zurück");
addnav("zum Laden","blumenmaedchen.php");
addnav("zum Garten","gardens.php");
addnav("zum Dorf","village.php");
addnav("zum Marktplatz","marktplatzmenue.php");
}

if ($_GET['op']=="dazwei"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`2 Du betrittst den Laden schaust dich um als du eine stimme vernimmst `& Kann ich euch helfen ? `2 fragt die Stimme und du Antwortest `& Ich möchte hier als Gärtner arbeiten. Könnt ihr Hilfe gebrauchen ? `2 langsam schaust du in die Richtung aus der die Stimme kam.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`&Ich bräuchte wahrlich Hilfe in der Gärtnerei für 2 Stunden das ist mir 1500 Goldstücke wert.`6 sagt  Aeris deutet auf die Hintertür und sagt bestimmend `n `& dort ist der Garten , wässert die Beete und meldet euch dann wieder bei mir `6 `n Du nickst und gehst in den Garten um die Beete zu bewässern. Nach 2 Stunden meldest du dich bei Aeris , bekommst von ihr dein Gold und verabschiedest dich`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;

case '2':
output("`6 Aeris nickt dir zu `& Wunderbar macht euch an die Arbeit,der Garten brauch dringend ein wenig Pflege `6 sagt sie und deutet auf den Garten. Schnell machst du dich an die Arbeit und kommst nach 3 Stunden zurück in den Laden um dich von Aeris zu verabschieden . Sie lächelt dich an sagt leise `& Danke für eure Hilfe `6 drückt dir dann 2250 Goldstücke in deine Hand `& Für eure geleistete Arbeit `6 sagt sie noch kurz und du verlässt dann den Laden mit deinem Lohn `n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=2250;
$session['user']['jobda']+=1;

break;
case '3':
output("`6Aeris schaut dich an deutet dann auf die Hintertür wo es zum Garten geht `& Ich brauche 3000 Rosen in verschiedenen Farben . Beginnt sie zu schneiden und meldet euch dann bei mir damit ich euch eueren Lohn geben kann. `6 sagt sie flink und du begibst dich in den Garten um Rosen zu schneiden .`n Nach 4 Stunden erscheinst du wieder im Laden legst sacht die Rosen ab und fragst `& Haben sei noch etwas für mich zu tun ? `6 `n Aeris schüttelt mit dem Kopf geht dann hinter die Theke um euch euer Gehalt zu geben .`n Ihr nehmt das Gold und zählt schnell nach , das hat sich ja wirklich gelohnt du bist 3000 Goldstücke reicher.`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '4':
output("`& Hilfe ? Schaut euch mal um ich brauche dringend frische Blumen für die Bestellungen auf der Theke `6 sagt Aeris bestimmend . du traust deinen Augen kaum als du zur Theke blickst. `n Dort häufen sich die Bestellungen schnell machst du dich ran. Für alle Bestellungen die Blumen frisch zu schneiden nach fünf Stunden kommst du mit den letzten Blumen in den Laden `& So fertig ! Oder habt ihr noch etwas werte Aeris `6 tönt es aus dir heraus. ´n Aeris schüttelt sanft den Kopf `& Nein , das war alles für Heute hier euer Lohn genau 3750 Gold `6 sagt sie mit froher Stimme und reicht dir das Gold.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=3750;
$session['user']['jobda']+=1;

break;
case '5':
output("`6 Etwas gestresst schaut dich Aeris an der Laden ist voller Kundschaft , sofort beginnst du im Garten frische Blumen zu schneiden.`n Nach 6 Stunden ist alle Arbeit erledigt und Aeris sagt zu dir `& Danke für eure Hilfe `2 Dann überreicht sie dir ein Säckchen mit 3000 Gold`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=4500;
$session['user']['jobda']+=1;

break;
}
}
if($session['user']['jobf'] >=1){
$session['user']['jobf']-=1;
}
addnav("Zurück");
addnav("zum Laden","blumenmaedchen.php");
addnav("zum Garten","gardens.php");
addnav("zum Dorf","village.php");
addnav("zum Marktplatz","marktplatzmenue.php");
}

if ($_GET['op']=="ste"){
if(($session['user']['turns'] <=2)||($session['user']['dieb'] <=1)){
switch(e_rand(1,12)){

       case '1':
output("`9Du schaust dich um und bemerkst das du alleine im Laden bist . Langsam gehst du zur Kasse öffnest sie und nimmst 5.000 Gold heraus. Keiner hat es bemerkt und beim den nächsten Kunden tippst du immer weniger Geld ein ...`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`9 Endlich ist deine Zeit gekommen du gehst zur Kasse, schaust dich um ob dich auch niemand beobachtet,nimmst dann schnell Geld aus der Kasse. Als du draußen bist schaust du wie gross deine Beute ist und du zählst 3000 Goldstücke`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`9 Du siehst wie Aeris eine große Truhe in eines der Hinterzimmer schleppt und du Fragst dich was da wohl drin sein mag .`n Aeris tritt auf dich zu `& Ich bin für  3 Stunden nicht im Laden da ich noch Besorgungen machen muss `9 sagt sie zu dir und geht dann hinaus. `n Dich überkommt immer die gleiche frage was wohl in der Truhe sein mag, langsam gehst du in das Hinterzimmer zur Truhe öffnest sie und traust deinen Augen kaum , in der Truhe befinden sich unzählige Edelsteine. Du nimmst dir einfach 6 Edelsteine den wenn dir fehlen wird es schon niemand bemerken.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`9 Dir fällt eine Truhe in der Ecke auf die gestern noch nicht da war , langsam gehst du zu dieser und öffnest sie , in der Truhe befinden sich Edelsteine und du beschließt dir einige auszuleihen .`n Da du alleine im Laden bist wird es schon keiner merken und so nimmst du 4 Edelsteine an dich.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`6Auf leisen Sohlen, wie eine Katze, schleichst du dich zur Kasse und grinst. `n
Die Einnahmen waren aber auch hoch genug, dass dir mal ein wenig mehr zustand und so greifst du ohne Reue hinein als plötzlich irgendjemand in der Nähe war. `n
So schnell du nur konntest machst du die Kasse wieder zu und verschwindest möglichst lautlos. Diese Tat hat dir insgesamt 1000 Gold eingebracht, die du einigermaßen zufrieden in die Tasche steckst und Feierabend machst`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`6Du warst bereits nahe an deinem Ziel und immer mehr Edelsteine verschwanden aus den Einnahmen in deiner Tasche. Einer nach dem anderen. `n
Auf einmal hörtest du das fröhliche Summen von Aeris in der Nähe. `n
Nein, sie durfte dich hier nicht erwischen. Nicht, bei so einer tat also ranntest du so schnell, wie dich deine Beine nur trugen davon, möglichst ohne von anderen gesehen zu werden. `n
Diese Aktion brachte dir immerhin 2 Edelsteine ein`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`6Kopfschüttelnd blickt Aeris auf dein Diebesgut als sie dich erwischt hatte und nun zur Rede stellte. `n
Mehr als zusammenhangsloses Stammeln brachtest du jedoch nicht mehr heraus. Zusammengekauert sitzt du da und wagst es nicht sie anzuschauen. Irgendwo zeigte sich auch in dir Reue, doch reichte eine Entschuldigung hier nicht mehr aus. `n
Aeris meinte nur noch, dass sie solch unzuverlässiges Personal nicht bräuchte und weist dir den Weg zur Tür. Hier brauchtest du ab sofort nicht mehr zur Arbeit zu erscheinen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`6Zugreifen und verschwinden… das hörte sich so leicht an und war einfach zu verlockend gewesen. Die Ersparnisse lagen doch so vor dir doch riefen förmlich danach gestohlen zu werden. `n
Aber wäre da Aeris nicht gewesen… betrübt sah sie dich an und musste sichtlich schwer schlucken, da sie so etwas niemals von dir gedacht hätte. Aber da sie recht gutmütig war, gab sie dir noch eine Chance, die du aufgrund deines eigenen Gewissens nicht unbedingt noch missbrauchen wolltest.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
              case '9':
output("`6Kopfschüttelnd blickt Aeris auf dein Diebesgut als sie dich erwischt hatte und nun zur Rede stellte. `n
Mehr als zusammenhangsloses Stammeln brachtest du jedoch nicht mehr heraus. Zusammengekauert sitzt du da und wagst es nicht sie anzuschauen. Irgendwo zeigte sich auch in dir Reue, doch reichte eine Entschuldigung hier nicht mehr aus. `n
Aeris meinte nur noch, dass sie solch unzuverlässiges Personal nicht bräuchte und weist dir den Weg zur Tür. Hier brauchtest du ab sofort nicht mehr zur Arbeit zu erscheinen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`6Zugreifen und verschwinden… das hörte sich so leicht an und war einfach zu verlockend gewesen. Die Ersparnisse lagen doch so vor dir doch riefen förmlich danach gestohlen zu werden. `n
Aber wäre da Aeris nicht gewesen… betrübt sah sie dich an und musste sichtlich schwer schlucken, da sie so etwas niemals von dir gedacht hätte. Aber da sie recht gutmütig war, gab sie dir noch eine Chance, die du aufgrund deines eigenen Gewissens nicht unbedingt noch missbrauchen wolltest.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
       case '11':
output("`6Kopfschüttelnd blickt Aeris auf dein Diebesgut als sie dich erwischt hatte und nun zur Rede stellte. `n
Mehr als zusammenhangsloses Stammeln brachtest du jedoch nicht mehr heraus. Zusammengekauert sitzt du da und wagst es nicht sie anzuschauen. Irgendwo zeigte sich auch in dir Reue, doch reichte eine Entschuldigung hier nicht mehr aus. `n
Aeris meinte nur noch, dass sie solch unzuverlässiges Personal nicht bräuchte und weist dir den Weg zur Tür. Hier brauchtest du ab sofort nicht mehr zur Arbeit zu erscheinen.`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`6Zugreifen und verschwinden… das hörte sich so leicht an und war einfach zu verlockend gewesen. Die Ersparnisse lagen doch so vor dir doch riefen förmlich danach gestohlen zu werden. `n
Aber wäre da Aeris nicht gewesen… betrübt sah sie dich an und musste sichtlich schwer schlucken, da sie so etwas niemals von dir gedacht hätte. Aber da sie recht gutmütig war, gab sie dir noch eine Chance, die du aufgrund deines eigenen Gewissens nicht unbedingt noch missbrauchen wolltest.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
}
}
if($session['user']['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}

addnav("Zurück");
addnav("zum Laden","blumenmaedchen.php");
addnav("zum Garten","gardens.php");
addnav("zum Dorf","village.php");
addnav("zum Marktplatz","marktplatzmenue.php");
}

if ($_GET['op']=="go"){
output("`6Wehmütig trittst du Aeris gegenüber und bringst deine Entscheidung vor, dass du kündigen willst. Sie schaut dich ein wenig traurig an, versucht aber deine Entscheidung zu respektieren und nickt daraufhin nur knapp. `n
Sie würde dich sicherlich vermissen, doch ließ sie dich ziehen und lächelt dir zum Abschied noch einmal nach auch wenn die hoffte, dich bald wieder begrüßen zu dürfen.`n");
$session['user']['jobid']=0;
addnav("Zurück");
addnav("zum Dorf","village.php");
addnav("zum Marktplatz","marktplatzmenue.php");
}


if ($_GET['op']=="send"){
addnav("Zurück","gardens.php");
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
  $gift="Wild Rose";
  $session['user']['gold']-=25;
  $effekt="Ich lieeeebe `@Wild Rosen`0.";
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
  $gift="Straus Gänseblümchen";
  $session['user']['gold']-=100;
  $effekt="Ohh, wie niedlich. Ein Paar `^Gäneblümlein`0.";
}
if ($_GET['op2']=="gift8"){
  $gift="Straus Wild Rosen";
  $session['user']['gold']-=250;
  $effekt="Zwar hat diese Rosenart Stacheln, aber das Stört dich bei dem Anblick der wunderschönen Rosen nicht.";
}
if ($_GET['op2']=="gift9"){
  $gift="Straus Rote Rosen";
  $session['user']['gold']-=700;
  $effekt="Du bermerkst, dass dich da wer sehr zu mögen scheint. `nDie `4Roten Rosen`0 sind wunderschön.";
}
  if ($_GET['op2']=="gift10"){
  $gift="Straus Weiße Rosen";
  $session['user']['gold']-=1000;
  $effekt="Die `&Weßen Rosen`0 erinnern dich an etwas, du weißt nur nicht was.";
}
if ($_GET['op2']=="gift11"){
  $gift="Straus Schwarze Rosen";
  $session['user']['gold']-=1000;
  $effekt="Du betrachtest den Straus `~Schwarze Rosen`0 und weißt das diese etwas besonderes und zugleich äußerst selten sind.";
}
$mailmessage=$session['user']['name'];
$mailmessage.="`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6";
$mailmessage.=$gift;
//you can change the following the match what you name your gift shop
$mailmessage.="`7 aus dem Blumenladen.`n".$effekt;
systemmail($name,"`2Blumen erhalten!`2",$mailmessage);
output("`rMit leuchtenden Augen nimmt Aeris die Münze entgegen. \"Ich danke euch!\"`n
murmelt sie schüchtern und läuft los um die $gift zu überbringen...");

addnav("Weiter","gardens.php");
}

page_footer();
?>