<?php
#########################
#                       #
#  Die Alchemistin      #
#  Texte:               #
#  Manja und Laserian   #
#  Code:                #
#  Laserian             #
#  v 1.0                #
#                       #
#########################
require_once "common.php";
page_header("Die Alchemistin");
$acctid=$session['user']['acctid'];
loadtable("*","alchemie");
output("`c`#");
if($_GET['op']==""){
    output("`nEin wenig unsicher klopfst Du an die Tür eines kleinen, eher unscheinbaren Häuschens. Gerüchten zufolge soll sich hier eine Alchemistin befinden.`n
    Du öffnest die Tür - und bleibst erstaunt stehen. Dir gegenüber steht eine junge Frau in einer kunstvoll verzierten Robe, mustert Dich mit kühlem Blick.`n
    \"Mein Name ist Suiren - wie kann ich Euch helfen?\" spricht sie Dich mit leiser Stimme an. Du blickst Dich in dem Raum um, erkennst Behälter, Kessel, Kolben aus Glas.`n
    An einer Wand steht ein Regal voller Bücher. Beim genaueren Hinsehen kannst Du erkennen, dass es sich bei den Büchern um uralte Almanachen über Pflanzen und Kräuter,`n
    und deren Wirkung handelt, um gesammelte Rezepte in den verschiedensten Sprachen. Über einem kleinen Fenster siehst Du bebündelte Sträusse verschiedener Kräuter`n
    zum Trocknen von der Decke hängen, ein eigentümlicher Geruch liegt in der Luft - Du scheinst richtig zu sein - doch eine Alchemistin hast Du Dir anders vorgestellt, oder?`n
    Die leise Stimme der Alchmistin erklingt erneut \"Ich kann Euch in die Grundlagen der Alchemie einweisen oder Euch mein Labor zur Verfügung stellen, so Ihr denn schon`n
    im Brauen von Tränken bewandert seit. Auch, wenn Ihr einige Zutaten verkaufen wollt, seit Ihr hier richtig\"
    `n`n
    Was willst Du tun?");
    if($session['user']['active']==0){
    addnav("Alchemie lernen","alchemistin.php?op=lern");
    }else{
    addnav("Kräuter verkaufen","alchemistin.php?op=sell");
    addnav("Rezepte kaufen","alchemistin.php?op=rezepte");
    addnav("Tränke brauen","alchemie.php");
    }
}
if($_GET['op']=="lern"){
    output("Die Alchemistin erklärt dir, dass sie für die Ausbildung in der Alchemie `^50000 `#Gold und `%25 `#Edelsteine verlangt.`n
    Ausserdem wirst du wohl einen guten Teil deiner Zeit opfern müssen.");
    if($session['user']['gold']>49999 && $session['user']['gems']>24 && $session['user']['turns']>9){
    addnav("Alchemie lernen","alchemistin.php?op=lern2");
    }
}
if($_GET['op']=="lern2"){
    output("Die Alchemistin verbringt einige Stunden mit dir in ihrem Alchemielabor, und bringt dir die Grundkenntnisse der Alchemie bei.`n
    Ausserdem übergibt sie dir eine Abschrift ihres Rezeptes für einen kleinen Heiltrank.");
    $session['user']['gold']-=50000;
    $session['user']['gems']-=25;
    $session['user']['turns']-=10;
    $session['user']['active']=1;
    $zutat5 = e_rand(4,10);
    $zutat6 = e_rand(4,10);
    $add= "INSERT INTO `alchemie` (acctid,level,fertpkt,fertgesamt,recipe,zutat5heal,zutat6heal)
    VALUES ($acctid,\"1\",\"3\",\"3\",\"1\",$zutat5,$zutat6)";
    db_query($add);
    addnav("Zurück zur Alchemistin","alchemistin.php");
}
if($_GET['op']=="sell"){
    output("Welche Zutaten willst du verkaufen?`n`n
    Alraunenwurzel - `^3 `#Gold`n`n
    Skorpionstachel - `^4 `#Gold`n`n
    Dämonenhorn - `^7 `#Gold`n`n
    Weißer Lotos - `^12 `#Gold`n`n
    Tigerlilie - `^2 `#Gold`n`n
    Feenstaub - `^5 `#Gold`n`n
    Einhornhaar - `^8 `#Gold`n`n
    Engelsfeder - `^11 `#Gold`n`n
    Drachenblut - `^200 `#Gold `%2 `#Edelsteine`n`n
    Phönixfeder - `^350 `#Gold `%1 `#Edelstein`n`n
    ");
    if($session['alchemie']['zutat1']>0){
    addnav("Alraunenwurzel","alchemistin.php?op=sell2&act=1");
    }
    if($session['alchemie']['zutat2']>0){
    addnav("Skorpionstachel","alchemistin.php?op=sell2&act=2");
    }
    if($session['alchemie']['zutat3']>0){
    addnav("Dämonenhorn","alchemistin.php?op=sell2&act=3");
    }
    if($session['alchemie']['zutat4']>0){
    addnav("Weißer Lotos","alchemistin.php?op=sell2&act=4");
    }
    if($session['alchemie']['zutat5']>0){
    addnav("Tigerlilie","alchemistin.php?op=sell2&act=5");
    }
    if($session['alchemie']['zutat6']>0){
    addnav("Feenstaub","alchemistin.php?op=sell2&act=6");
    }
    if($session['alchemie']['zutat7']>0){
    addnav("Einhornhaar","alchemistin.php?op=sell2&act=7");
    }
    if($session['alchemie']['zutat8']>0){
    addnav("Engelsfeder","alchemistin.php?op=sell2&act=8");
    }
    if($session['alchemie']['zutat1s']>0){
    addnav("Drachenblut","alchemistin.php?op=sell2&act=9");
    }
    if($session['alchemie']['zutat2s']>0){
    addnav("Phönixfeder","alchemistin.php?op=sell2&act=10");
    }
    addnav("Zurück zur Alchemistin","alchemistin.php");
}
if($_GET['op']=="sell2"){
output("Wieviele Zutaten verkaufen?`n`n");
switch($_GET[act]){
case 1:
    output($session['alchemie']['zutat1']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=1' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=1");
break;
case 2:
    output($session['alchemie']['zutat2']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=2' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=2");
break;
case 3:
    output($session['alchemie']['zutat3']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=3' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=3");
break;
case 4:
    output($session['alchemie']['zutat4']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=4' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=4");
break;
case 5:
    output($session['alchemie']['zutat5']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=5' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=5");
break;
case 6:
    output($session['alchemie']['zutat6']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=6' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=6");
break;
case 7:
    output($session['alchemie']['zutat7']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=7' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=7");
break;
case 8:
    output($session['alchemie']['zutat8']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=8' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=8");
break;
case 9:
    output($session['alchemie']['zutat1s']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=9' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Setzen'></form>");
    addnav("","alchemistin.php?op=sell3&act=9");
break;
case 10:
    output($session['alchemie']['zutat2s']." verfügbar`n`n");
    rawoutput("<form action='alchemistin.php?op=sell3&act=10' method='POST'>
    <input type='text' id='zahl' name='zahl' maxlength='5' size='5'>
    <input type='submit' class='button' value='Verkaufen'></form>");
    addnav("","alchemistin.php?op=sell3&act=10");
break;
}
addnav("Zurück zur Alchemistin","alchemistin.php");
}
if($_GET['op']=="sell3"){
switch($_GET[act]){
case 1:
if($_POST['zahl']>$session['alchemie']['zutat1']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
if($_POST['zahl']==1){
$text="Alraunenwurzel";
}else{
$text="Alraunenwurzeln";
}
$gold=$_POST['zahl']*3;
    output("Du verkaufst `^".$_POST['zahl']." `#$text und bekommst `^$gold `#Gold.");
    $session['user']['gold']+=$gold;
    $session['alchemie']['zutat1']-=$_POST['zahl'];
}
break;
case 2:
if($_POST['zahl']>$session['alchemie']['zutat2']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
$text="Skorpionstachel";
$gold=$_POST['zahl']*4;
    output("Du verkaufst `^".$_POST['zahl']." `#$text und bekommst `^$gold `#Gold.");
    $session['user']['gold']+=$gold;
    $session['alchemie']['zutat2']-=$_POST['zahl'];
}
break;
case 3:
if($_POST['zahl']>$session['alchemie']['zutat3']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
if($_POST['zahl']==1){
$text="Dämonenhorn";
}else{
$text="Dämonenhörner";
}
$gold=$_POST['zahl']*7;
    output("Du verkaufst `^".$_POST['zahl']." `#$text und bekommst `^$gold `#Gold.");
    $session['user']['gold']+=$gold;
    $session['alchemie']['zutat3']-=$_POST['zahl'];
}
break;
case 4:
if($_POST['zahl']>$session['alchemie']['zutat4']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
if($_POST['zahl']==1){
$text="weißer Lotos";
}else{
$text="Stück weißer Lotos";
}
$gold=$_POST['zahl']*12;
    output("Du verkaufst `^".$_POST['zahl']." `#$text und bekommst `^$gold `#Gold.");
    $session['user']['gold']+=$gold;
    $session['alchemie']['zutat4']-=$_POST['zahl'];
}
break;
case 5:
if($_POST['zahl']>$session['alchemie']['zutat5']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
if($_POST['zahl']==1){
$text="Tigerlilie";
}else{
$text="Tigerlilien";
}
$gold=$_POST['zahl']*2;
    output("Du verkaufst `^".$_POST['zahl']." `#$text und bekommst `^$gold `#Gold.");
    $session['user']['gold']+=$gold;
    $session['alchemie']['zutat5']-=$_POST['zahl'];
}
break;
case 6:
if($_POST['zahl']>$session['alchemie']['zutat6']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
$text="Feenstaub";
$gold=$_POST['zahl']*5;
    output("Du verkaufst `^".$_POST['zahl']." `#Beutel $text und bekommst `^$gold `#Gold.");
    $session['user']['gold']+=$gold;
    $session['alchemie']['zutat6']-=$_POST['zahl'];
}
break;
case 7:
if($_POST['zahl']>$session['alchemie']['zutat7']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
if($_POST['zahl']==1){
$text="Einhornhaar";
}else{
$text="Einhornhaare";
}
$gold=$_POST['zahl']*8;
    output("Du verkaufst `^".$_POST['zahl']." `#$text und bekommst `^$gold `#Gold.");
    $session['user']['gold']+=$gold;
    $session['alchemie']['zutat7']-=$_POST['zahl'];
}
break;
case 8:
if($_POST['zahl']>$session['alchemie']['zutat8']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
if($_POST['zahl']==1){
$text="Engelsfeder";
}else{
$text="Engelsfedern";
}
$gold=$_POST['zahl']*11;
    output("Du verkaufst `^".$_POST['zahl']." `#$text und bekommst `^$gold `#Gold.");
    $session['user']['gold']+=$gold;
    $session['alchemie']['zutat8']-=$_POST['zahl'];
}
break;
case 9:
if($_POST['zahl']>$session['alchemie']['zutat1s']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
if($_POST['zahl']==1){
$text="Phiole";
}else{
$text="Phiolen";
}
$gold=$_POST['zahl']*200;
$gems=$_POST['zahl']*2;
    output("`@Du verkaufst `^".$_POST['zahl']." `#$text Drachenblut und bekommst `^$gold `#Gold und `%$gems `#Edelsteine.");
    $session['user']['gold']+=$gold;
    $session['user']['gems']+=$gems;
    $session['alchemie']['zutat1s']-=$_POST['zahl'];
}
break;
case 10:
if($_POST['zahl']>$session['alchemie']['zutat2s']){
output("So viele Zutaten besitzt du doch gar nicht.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}elseif($_POST['zahl']==0){
output("Du kannst nicht 0 Zutaten verkaufen.");
addnav("Zurück","alchemistin.php?op=sell");
addnav("Zurück zur Alchemistin","alchemistin.php");
}else{
if($_POST['zahl']==1){
$text="Phönixfeder";
}else{
$text="Phönixfedern";
}
$gold=$_POST['zahl']*350;
    output("Du verkaufst `^".$_POST['zahl']." `#$text und bekommst `^$gold `#Gold und `%".$_POST['zahl']." `#Edelsteine.");
    $session['user']['gold']+=$gold;
    $session['user']['gems']+=$_POST['zahl'];
    $session['alchemie']['zutat2s']-=$_POST['zahl'];
}
break;
}
addnav("Zurück zur Alchemistin","alchemistin.php");
}
if($_GET['op']=="rezepte"){
switch($_GET['act']){
default:
    output("Diese Rezepte stehen dir zur Verfügung:`n`n");
    if($session['alchemie']['recipe']==1){
    output("<a href='alchemistin.php?op=rezepte&act=1'>Heiltrank</a> - `^5000 `#Gold `%5 `#Edelsteine`n`n",true);
    addnav("","");
    }
    if($session['alchemie']['recipe']<=2){
    output("<a href='alchemistin.php?op=rezepte&act=2'>Schlafgift</a> - `^7500 `#Gold `%10 `#Edelsteine`n`n",true);
    addnav("","alchemistin.php?op=rezepte&act=2");
    }
    if($session['alchemie']['recipe']<=3){
    output("<a href='alchemistin.php?op=rezepte&act=3'>Lähmungsgift</a> - `^10000 `#Gold `%15 `#Edelsteine`n`n",true);
    addnav("","alchemistin.php?op=rezepte&act=3");

    }
    if($session['alchemie']['recipe']<=4){
    output("<a href='alchemistin.php?op=rezepte&act=4'>Steinhauttrank</a> - `^15000 `#Gold `%20 `#Edelsteine`n`n",true);
    addnav("","alchemistin.php?op=rezepte&act=4");
    }
    if($session['alchemie']['recipe']<=5){
    output("<a href='alchemistin.php?op=rezepte&act=5'>Krafttrank</a> - `^25000 `#Gold `%25 `#Edelsteine`n`n",true);
    addnav("","alchemistin.php?op=rezepte&act=5");
    }
    if($session['alchemie']['recipe']<=6){
    output("<a href='alchemistin.php?op=rezepte&act=6'>Lebenstrank</a> - `^50000 `#Gold `%30 `#Edelsteine`n`n",true);
    addnav("","alchemistin.php?op=rezepte&act=6");
    }
    if($session['alchemie']['recipe']<=7){
    output("<a href='alchemistin.php?op=rezepte&act=7'>Schönheitstrank</a> - `^75000 `#Gold `%35 `#Edelsteine`n`n",true);
    addnav("","alchemistin.php?op=rezepte&act=7");
    }
    if($session['alchemie']['recipe']<=8){
    output("<a href='alchemistin.php?op=rezepte&act=8'>Elixier der Stärke</a> - `^100000 `#Gold `%40 `#Edelsteine`n`n",true);
    addnav("","alchemistin.php?op=rezepte&act=8");
    }
    if($session['alchemie']['recipe']<=9){
    output("<a href='alchemistin.php?op=rezepte&act=9'>Elixier des Geschicks</a> - `^100000 `#Gold `%40 `#Edelsteine`n`n",true);
    addnav("","alchemistin.php?op=rezepte&act=9");
    }
    addnav("Zurück zur Alchemistin","alchemistin.php");
break;
case "1":
if($session['user']['gold']>=5000 && $session['user']['gems']>=5){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für einen Heiltrank aus.");
    $session['user']['gold']-=5000;
    $session['user']['gems']-=5;
    $session['alchemie']['recipe']=2;
    $session['alchemie']['zutat7heal']=e_rand(4,10);
    $session['alchemie']['zutat8heal']=e_rand(4,10);
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
case "2":
if($session['user']['gold']>=7500 && $session['user']['gems']>=10){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für ein Schlafgift aus.");
    $session['user']['gold']-=7500;
    $session['user']['gems']-=10;
    $session['alchemie']['recipe']=3;
    $session['alchemie']['zutat1poison']=e_rand(4,10);
    $session['alchemie']['zutat2poison']=e_rand(4,10);
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
case "3":
if($session['user']['gold']>=10000 && $session['user']['gems']>=15){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für ein Lähmungsgift aus.");
    $session['user']['gold']-=10000;
    $session['user']['gems']-=15;
    $session['alchemie']['recipe']=4;
    $session['alchemie']['zutat3poison']=e_rand(4,10);
    $session['alchemie']['zutat4poison']=e_rand(4,10);
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
case "4":
if($session['user']['gold']>=15000 && $session['user']['gems']>=20){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für einen Steinhauttrank aus.");
    $session['user']['gold']-=15000;
    $session['user']['gems']-=20;
    $session['alchemie']['recipe']=5;
    $session['alchemie']['zutat2defpush']=e_rand(4,10);
    $session['alchemie']['zutat5defpush']=e_rand(4,10);
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
case "5":
if($session['user']['gold']>=25000 && $session['user']['gems']>=25){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für einen Krafttrank aus.");
    $session['user']['gold']-=25000;
    $session['user']['gems']-=25;
    $session['alchemie']['recipe']=6;
    $session['alchemie']['zutat1attpush']=e_rand(4,10);
    $session['alchemie']['zutat6attpush']=e_rand(4,10);
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
case "6":
if($session['user']['gold']>=50000 && $session['user']['gems']>=30){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für einen Lebenstrank aus.");
    $session['user']['gold']-=50000;
    $session['user']['gems']-=30;
    $session['alchemie']['recipe']=7;
    $session['alchemie']['zutat1permhp']=e_rand(3,8);
    $session['alchemie']['zutat6permhp']=e_rand(3,8);
    $session['alchemie']['zutat7permhp']=e_rand(3,8);
    $session['alchemie']['zutat2spermhp']=1;
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
case "7":
if($session['user']['gold']>=75000 && $session['user']['gems']>=35){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für einen Schönheitstrank aus.");
    $session['user']['gold']-=75000;
    $session['user']['gems']-=35;
    $session['alchemie']['recipe']=8;
    $session['alchemie']['zutat2charme']=e_rand(3,8);
    $session['alchemie']['zutat5charme']=e_rand(3,8);
    $session['alchemie']['zutat6charme']=e_rand(3,8);
    $session['alchemie']['zutat1scharme']=1;
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
case "8":
if($session['user']['gold']>=100000 && $session['user']['gems']>=40){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für ein Elixier der Stärke aus.");
    $session['user']['gold']-=100000;
    $session['user']['gems']-=40;
    $session['alchemie']['recipe']=9;
    $session['alchemie']['zutat3permatt']=e_rand(4,10);
    $session['alchemie']['zutat7permatt']=e_rand(4,10);
    $session['alchemie']['zutat1spermatt']=1;
    $session['alchemie']['zutat2spermatt']=1;
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
case "9":
if($session['user']['gold']>=100000 && $session['user']['gems']>=40){
    output("Du reichst Suiren das Gold und die Edelsteine und sie händigt dir im Gegenzug das Rezept für ein Elixier des Geschicks aus.");
    $session['user']['gold']-=100000;
    $session['user']['gems']-=40;
    $session['alchemie']['recipe']=10;
    $session['alchemie']['zutat4permdef']=e_rand(4,10);
    $session['alchemie']['zutat8permdef']=e_rand(4,10);
    $session['alchemie']['zutat1spermdef']=1;
    $session['alchemie']['zutat2spermdef']=1;
}else{
    output("Als du nachsiehst ob du genug Gold und Edelsteine bei dir hast bemerkst du, dass dem nicht der Fall ist und teilst Suiren dies mit.`n
    Dich kurz kühl mustern, verstaut sie das Pergament wieder in einem der Kästen.");
}
break;
}
}
savetable("alchemie");
output("`c");
addnav("Zurück zum Dorf","village.php");
$copyright ="<div align='center'><a href=http://www.lotgd-midgar.de/index.php target='_blank'>&copy;Laserian`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?> 