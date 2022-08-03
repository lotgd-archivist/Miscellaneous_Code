<?php
//*-------------------------*
//|      nicedragon.php     |
//|        Scriptet by      |
//|       °*Amerilion*°     |
//|    for mekkelon.de.vu   |
//|      idea by Jamara     |
//*-------------------------*
/*

*Version 1.4*
History (Alle am 11.6 innerhalb von 1Stunde)
1.0
Läuft problemlos
1.1
http_get_vars geändert in $_GET
1.2
Schwerer Bug, Bilder gingen nach DK verloren
1.3
Neuordnung der anzeige in der Hall of fame
1.4
Auf ein neues eine Neuanordnung, diesmal denk ich die letzte

-------------------------------------------------------------------------------------------------------------
Die ähnlichkeit mancher Texte zur randdragon.php ist rein zufällig... deshalb hier nochma das (c) daraus ;)

~Random Green Dragon Encounter v1 by Timothy Drescher (Voratus) translatet by Anpera
-------------------------------------------------------------------------------------------------------------


INSTALL:

Öffne hof.php
SUCHE
addnav("Arenakämpfer","hof.php?op=battlepoints&subop=$subop&page=$page");
FÜGE DANACH EIN
addnav("Bilder", "hof.php?op=bild&subop=$subop&page=$page");

SUCHE
} elseif ($_GET[op]=="paare"){
FÜGE DAVOR EIN
} elseif ($_GET[op]=="bild"){
    $sql = "SELECT name,bild AS data1 FROM accounts WHERE locked=0 AND bild ORDER BY bild $order, acctid $order LIMIT $limit";
    $adverb = "begabtesten";
    if ($_GET[subop] == "least") $adverb = "unbegabtesten";
    $title = "Die $adverb Maler des `5lila Drachen`^ in diesem Land";
    $headers = array("Bilder");
    display_table($title, $sql, false, false, $headers, false);
SUCHE:
$sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND dragonkills>0";
FÜGE DANNACH EIN:
} elseif ($op == "bild") {
    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND bild";


Öffne dragon.php
SUCHE 2MAL
,"dragonpoints"=>1
FÜGE JEWEILS DANACH EIN
,"bild"=>1

SQL:
ALTER TABLE `accounts` ADD `bild` INT( 11 ) DEFAULT '0' NOT NULL
*/



if (!isset($session)) exit();


if ($_GET['op']==""){
output("`n`c`b`^Der Drache!!!`b`c`n`n");
output("`2Bei deinem Streifzug durch die Wälder hörst du plötzlich ein lautes Brüllen. Das Geräusch lässt das Blut in deinen Adern gefrieren.`n");
output("Ein tiefes Stampfen ist hinter dir zu hören. Starr vor Schreck fühlst du einen Stoß heißen Atem in deinem Nacken. ");
output("Langsam drehst du dich um - und siehst einen riesigen `5Lila Drachen`2 vor dir stehen. ");
output("`n`nDas könnte Ärger geben...");
addnav("Angreifen!","forest.php?op=at");
addnav("Um Gnade winseln","forest.php?op=ug");
addnav("Rede dich raus","forest.php?op=red");
addnav("Lauf weg!","forest.php?op=run");
$session[user][specialinc]="nicedragon.php";
}
if($_GET['op']=="at"){
$gem=round($session['user']['gems']*0.1);
output("`2Du ziehst deine Waffe und rennst auf den `5Lila Drachen`2 zu. Dieser schaut dich");
output("verwundert an. Dann");
switch(e_rand(1,3)){
case 1:
case 2:
output("`2hebt sie den rechten Vorderfuss und schaut dich ein letztes mal entschuldigend an");
output("`n`n`^Du bist tot.`n".$gem." deiner Edelsteine wurden zerquetscht.`nDu verlierst wegen");
output("deiner Dummheit 3% Erfahrung");
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$session['user']['gems']=round ($session['user']['gems']*0.9);
$session['user']['experience']*0.97;
addnews($session['user']['name']." `5weiß nun das man nicht unter den Fuss eines Drachen stehen sollte...");
addnav("Tägliche News","news.php");
break;
case 3:
output("wendet er sich ab und steigt in die Lüfte. Du fühlst dich unendlich stark.`#");
increment_specialty();
}
}
if($_GET['op']=="ug"){
output("`2Du kauerst dich vor dem `5Lila Drachen`2 zusammen und flehst um dein Leben. Der Drachen schnaubt dir erneut seinen heißen Atem ");
output("entgegen. \"`3An jemandem, der so erbärmlich jammert, würde ich mir sicher nur den Magen verderben.");
output("`3Hau schon ab, dich intressiert doch eh mein `@Bruder`2\"`nDu beschließt, dass es das Beste ist, den Anweisungen der Kreatur zu folgen, und so ");
output("hoppelst du verängstigt davon.");
$session['user']['specialinc']="";
}
if($_GET['op']=="red"){
$session[user][specialinc]="nicedragon.php";
output("`2Du stehst einfach mit weichen Knien vor der `5lila Kreatur`2 und zitterst.");
output("Sie schaut dich von oben bis untern an, was bei ihrer imensen Größe nicht lange dauert.");
output("`n\"`3Sprich, du Abenteurer`2(du hörst Spott bei diesen Wort in der Stimme des Drachen mitklingen)`3");
output("was erdreistest du dich mir im Weg zu stehen?`2\"");
addnav("Grüner Drache?","forest.php?op=green");
addnav("Geschichte erzählen","forest.php?op=story");
addnav("`5Lila","forest.php?op=lila");
}

if($_GET['op']=="green"){
$exp=round($session['user']['experience']*0.08);
output("`2Der `5lila Drache `2schnaubt verächtlich...`n\"`3Er ist eine Schande für unser");
output("edles Geschlecht. Voller Bosheit und doch so dumm... Solch Wesen werden eines Tages dafür");
output("Sorge tragen das unser Geschlecht austirbt...Ich habe eine solche `iWut`i auf ihn das ich");
output("dir helfen werde ihn zu besiegen. Ich lasse dich an meiner Weisheit teilhaben!\"`^`n`n");
output("Deine Erfahrung steigt um $exp Punkte");
$session['user']['experience']+=$exp;
}
if($_GET['op']=="story"){
$charm=(e_rand(1,5));
output("`2Du erzählst den Drachen deine Geschichte.");
output("Dieser findet sie langweilig und schläft ein.");
switch(e_rand(1,3)){
case 1:
case 2:
output("`2Du schämst dich und verlierst ein paar Charmpunkte");
if ($session['user']['charm']>=$charm){
$session['user']['charm']-=$charm;
}else{
$session['user']['charm']=0;
}
break;
case 3:
output("`2Was du nutzt um ein Bild von ihm zu malen. Dieses gibts du in der Akademie des Drachen ab,");
output("`2wo du einerseits eine Belohnung erhälst und anderseits dein Name vermerkt wird.");
$session['user']['gold']+=1000;
$session['user']['bild']++;
break;
}
}
if($_GET['op']=="lila"){
output("\"`5JAAA!!!. Ich `ibin`i Lila!!!`2\"`n schreit dich der `5lila Drache`2 an.");
output("Er wirft dir einen Edelstein an den Kopf und als du wieder aufwachst ist er verschwunden.");
output("`n`n`^Du bekommst 1 Edelstein.`nDu verlierst die Hälfte deiner Lebenspunkte.`n");
$session['user']['hitpoints']=$session['user']['hitpoints']*0.5;
$session['user']['gems']++;
if ($session['user']['turns']>5){
output("`^Du verlierst 5 Runden.");
$session['user']['turns']-=5;
}else{
output("`^Du verlierst alle deine Runden");
$session['user']['turns']=0;
}
}
if($_GET['op']=="run"){
output("`2Eilig rennst du in den Wald, während der Drache dir nachschaut.");
}
?>