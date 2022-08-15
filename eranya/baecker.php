
<?php

//
// 24.08.05
// Bäckerei by Deedlit v.1.0.
// Prog. for Arith www.logd-shadow.de/logd
//

//***************************
// Beschreibung und Sprechen:
//***************************

require_once "common.php";
page_header("Bäckerei");
addcommentary();

if ($_GET['op']=="") {
    output("`b`c`tBäckerei mit vielen Köstlichkeiten`c`b`n`n`n");
    output("`^Hier in der Bäckerei gibt es jede Menge leckere Köstlichkeiten du weißt garnicht wo du zuerst hinschauen sollst.`n");
    output("`^Der Bäcker nähert sich dir hinter seiner Theke und spricht dich an:`n");
    output("`Q\"`iWas kann ich dir anbieten? Ein schönes Stück Kuchen, eine herzhafte Brezel, einen heißen Kaffee oder vielleicht etwas zu Naschen?\"`i`n`n");
    output("`^Die Auswahl ist üppig, aber du denkst, dass du schon etwas passendes finden wirst.`n`n");
    output("`^Du bemerkst, das auch andere Kunden in der Bäckerei sind und miteinander sprechen:`0`n`n");
viewcommentary("baeckerei","Du sprichst:",30,"sagt");

//************
// Navigation:
//************

addnav('Zurück');
addnav("Zurück zum Marktplatz","market.php");
knappentraining_link('baecker');
addnav("Waren");
addnav("Backwaren","baecker.php?op=ba");
addnav("Kuchen und Torten","baecker.php?op=ku");
addnav("Getränke","baecker.php?op=tr");
addnav("Süßigkeiten","baecker.php?op=sw");
}

if($_GET['op']=="market_inside")
{
    page_header("Zwielichtiger Händler");
    output("`b`c`]Zwielichtiger Händler`c`b`n`n
    Einen 'offiziellen' Schwarzmarkt gibt es auf Eranyas belebtestem Platz natürlich nicht. 
    Allerdings haben sich abseits der farbenfrohen und lautstarken Händler durchaus einige Gestalten 
    eingenistet, bei denen es Dinge zu erstehen gibt, die man lieber nur unterhalb der Ladentheke verkaufen 
    sollte. Angebot und Nachfrage bestimmen auch hier das Geschäft, doch die feilgebotenen Güter scheinen 
    fragwürdigen Ursprungs oder schlicht vom Karren gefallen zu sein. Gespräche führt man hinter vorgehaltener 
    Hand und nur im Flüsterton, denn wer hier von den Gesetzeshütern bei einer Transaktion erwischt wird, kann sich 
    eines Besuchs im Kerker so gut wie sicher sein.`n`n");
    viewcommentary("market_inside","Du sprichst:",30,"sagt");
    
addnav('Zurück');
addnav("Zurück zum Marktplatz","market.php");
}

if ($_GET['op']=="ba") {
output("`^Du besiehst dir die Auswahl an Backwaren genauer an.");
addnav("Sesambrötchen `^100`0 Gold","baecker.php?op=sesam");
addnav("Mohnbrötchen `^200`0 Gold","baecker.php?op=mohn");
addnav("Laugenbrötchen `^400`0 Gold","baecker.php?op=laugen");
addnav("Brezel `^500`0 Gold","baecker.php?op=brezel");
addnav("Laib Roggenbrot `^750`0 Gold","baecker.php?op=roggen");
addnav("Laib Bauernbrot `^1000`0 Gold","baecker.php?op=bauern");
addnav("Zurück","baecker.php");
}
if ($_GET['op']=="ku") {
output("`^Du besiehst dir die Auswahl an Kuchen und Torten genauer an.");
addnav("Stück Nusskuchen `^150`0 Gold","baecker.php?op=nuss");
addnav("Stück Apfelkuchen `^300`0 Gold","baecker.php?op=apfelkuchen");
addnav("Stück Zitronenkuchen `^500`0 Gold","baecker.php?op=zitronen");
addnav("Stück Erdbeertorte `^750`0 Gold","baecker.php?op=erdbeer");
addnav("Stück Himbeertorte `^1000`0 Gold","baecker.php?op=himbeer");
addnav("Stück Schokotorte `^1500`0 Gold","baecker.php?op=schoko");
addnav("Zurück","baecker.php");
}
if ($_GET['op']=="tr") {
output("`^Du besiehst dir die Auswahl an Getränken genauer an.");
addnav("Glas Orangensaft `^250`0 Gold","baecker.php?op=orangensaft");
addnav("Glas Apfelsaft `^250`0 Gold","baecker.php?op=apfelsaft");
addnav("Tasse Kaffee `^500`0 Gold","baecker.php?op=kaffee");
addnav("Tasse Tee `^500`0 Gold","baecker.php?op=tee");
addnav("Zurück","baecker.php");
}
if ($_GET['op']=="sw") {
output("`^Du besiehst dir die Auswahl an Süßigkeiten genauer an.");
addnav("Lakritzstange `^100`0 Gold","baecker.php?op=lakritz");
addnav("Lutscher `^200`0 Gold","baecker.php?op=lutscher");
addnav("Lebkuchen `^400`0 Gold","baecker.php?op=lebkuchen");
addnav("Kandierter Apfel `^800`0 Gold","baecker.php?op=apfel");
addnav("Zurück","baecker.php");
}

//***********
// Backwaren:
//***********


// Sesambrötchen
//**************
if ($_GET['op']=="sesam"){
   if ($session['user']['gold']<100){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,3)){
    case 1:
        output("`gDu nimmts das Sesambrötchen und verspeist es. Du findest, das es sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Sesambrötchen und verspeist es. Du bemerkst aber nicht, dass dir Sesamkörner zwischen den Zähnen stecken.`n");
        output("`gJedesmal wenn du lächelst, kann man sie sehen. Du verlierst `^2 `gCharmepunkte.");
            $session['user']['charm']-=2;
    break;
    case 3:
        output("`gDu nimmts das Sesambrötchen und verspeist es. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^10 `gLebenspunkte.");
            $session['user']['hitpoints']+=10;
    break;
        }
$session['user']['gold']-=100;
addnav("Zurück","baecker.php");
    }
}
// Mohnbrötchen
//*************
if ($_GET['op']=="mohn"){
   if ($session['user']['gold']<200){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,3)){
    case 1:
        output("`gDu nimmts das Mohnbrötchen und verspeist es. Du findest, das es sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Mohnbrötchen und verspeist es. Du bemerkst aber nicht, dass dir Mohnkörner zwischen den Zähnen stecken.`n");
        output("`gJedesmal wenn du lächelst, kann man sie sehen. Du verlierst `^1 `gCharmepunkte.");
            $session['user']['charm']-=1;
    break;
    case 3:
        output("`gDu nimmts das Mohnbrötchen und verspeist es. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^20 `gLebenspunkte.");
            $session['user']['hitpoints']+=20;
    break;
        }
$session['user']['gold']-=200;
addnav("Zurück","baecker.php");
    }
}
// Laugenbrötchen
//***************
if ($_GET['op']=="laugen"){
   if ($session['user']['gold']<400){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,3)){
    case 1:
        output("`gDu nimmts das Laugenbrötchen und verspeist es. Du findest, das es sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Laugenbrötchen und beißt hinein. Du bemerkst, dass es knochenhart ist und brichst dir einen Zahn ab.`n");
        output("`gDu hast höllische Zahnschmerzen und verlierst einige Lebenspunkte");
            $session['user']['hitpoints']*=0.8;
    break;
    case 3:
        output("`gDu nimmts das Laugenbrötchen und verspeist es. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^30 `gLebenspunkte.");
            $session['user']['hitpoints']+=30;
    break;
        }
$session['user']['gold']-=400;
addnav("Zurück","baecker.php");
    }
}
// Brezel
//*******
if ($_GET['op']=="brezel"){
   if ($session['user']['gold']<500){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,3)){
    case 1:
        output("`gDu nimmts die Brezel und verspeist sie. Du findest, das sie sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts die Brezel und beißt hinein. Ausversehen beißt du dir auf die Zunge.`n");
        output("`gDeine Zunge tut höllisch weh und du verlierst einige Lebenspunkte");
            $session['user']['hitpoints']*=0.85;
    break;
    case 3:
        output("`gDu nimmts die Brezel und verspeist es. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^40 `gLebenspunkte.");
            $session['user']['hitpoints']+=40;
    break;
              }
       $session['user']['gold']-=500;
       addnav("Zurück","baecker.php");

    }
}
// Laib Roggenbrot
//****************
if ($_GET['op']=="roggen"){
   if ($session['user']['gold']<750){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,3)){
    case 1:
        output("`gDu nimmts das Roggenbrot und verspeist es. Du findest, dass das Brot wirklich köstlich ist.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Roggenbrot und beißt hinein. Du bemerkst, das du auf etwas sehr Hartes gebissen hast.`n");
        output("`gDir tun deine Zähne weh und du verlierst einige Lebenspunkte.`n");
        output("`gDu stellst fest, das du auf Gold gebissen hast und wunderst dich, was manche Bäcker so verarbeiten.");
            $session['user']['gold']+=400;
            $session['user']['hitpoints']*=0.9;
    break;
    case 3:
        output("`gDu nimmts das Roggenbrot und verspeist es. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^60 `gLebenspunkte.");
            $session['user']['hitpoints']+=60;
    break;
        }
       $session['user']['gold']-=750;
       addnav("Zurück","baecker.php");

    }
}
// Laib Bauernbrot
//****************
if ($_GET['op']=="bauern"){
   if ($session['user']['gold']<1000){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,3)){
    case 1:
        output("`gDu nimmts das Bauernbrot und verspeist es. Du findest, dass das Brot wirklich köstlich ist.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Bauernbrot und beißt hinein. Du bemerkst, das du auf etwas sehr Hartes gebissen hast.`n");
        output("`gDir tun deine Zähne weh und du verlierst einige Lebenspunkte.`n");
        output("`gDu stellst fest, das du auf `^1 `gEdelstein gebissen hast und wunderst dich, was manche Bäcker so verarbeiten.");
            $session['user']['gems']+=1;
            $session['user']['hitpoints']*=0.95;
    break;
    case 3:
        output("`gDu nimmts das Bauernbrot und verspeist es. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu stellst fest, das du fit genug bist für `^1 `gweitere Runde.");
            $session['user']['turns']+=1;
    break;
 }
       $session['user']['gold']-=1000;
       addnav("Zurück","baecker.php");
    }
}

//*******************
// Kuchen und Torten:
//*******************

// Nusskuchen
//***********
if ($_GET['op']=="nuss"){
   if ($session['user']['gold']<150){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,5)){
    case 1:
        output("`gDu nimmts das Stück Nusskuchen und ißt es genüsslich. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Stück Nusskuchen und ißt es genüsslich. Du bemerkst aber, das du jetzt mehr als satt bist.`n");
        output("`gDu bist so voll, das du dich fast nicht mehr bewegen kannst und das von einem Stück?! Du verlierst `^6 `gRunde.");
            $session['user']['turns']-=6;
    break;
    case 3:
        output("`gDu nimmts das Stück Nusskuchen und ißt es genüsslich. Du bemerkst, dass wohl einige Zutaten nicht mehr so frisch waren.`n");
        output("`gDu hast höllische Bauchschmerzen!`n");
        output("`gDu verlierst eine menge Lebenspunkte.");
            $session['user']['hitpoints']*=0.5;
    break;
    case 4:
        output("`gDu nimmts das Stück Nusskuchen und ißt es genüsslich. Du fühlst dich prächtig und etwas erholter.`n");
        output("`gDu regenerierst `^20 `gLebenspunkte.");
            $session['user']['hitpoints']+=20;
    break;
    case 5:
        output("`gDu nimmts das Stück Nusskuchen und ißt es genüsslich. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDie Härte der Nuss wird dich nun eine Zeitlang beschützen!");
            $session['bufflist']['hearte'] = array("name"=>"`THärte der Nuss`0",
                  "rounds"=>10,
                  "wearoff"=>"`TDie Härte der Nuss hat dich wieder verlassen!`0",
                  "defmod"=>1.1,
                  "atkmod"=>1,
                  "roundmsg"=>"`TDu bist etwas abgehärtet durch die Nuss im Nusskuchen.`0",
                  "activate"=>"defense");
    break;
 }
       $session['user']['gold']-=150;
       addnav("Zurück","baecker.php");
    }
}
// Apfelkuchen
//************
if ($_GET['op']=="apfelkuchen"){
   if ($session['user']['gold']<300){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,5)){
    case 1:
        output("`gDu nimmts das Stück Apfelkuchen und ißt es genüsslich. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Stück Apfelkuchen und ißt es genüsslich. Du bemerkst aber, das du jetzt mehr als satt bist.`n");
        output("`gDu bist so voll, das du dich fast nicht mehr bewegen kannst und das von einem Stück?! Du verlierst `^5 `gRunden.");
            $session['user']['turns']-=5;
    break;
    case 3:
        output("`gDu nimmts das Stück Apfelkuchen und ißt es genüsslich. Du bemerkst, dass wohl einige Zutaten nicht mehr so frisch waren.`n");
        output("`gDu hast höllische Bauchschmerzen!`n");
        output("`gDu verlierst einige Lebenspunkte.");
            $session['user']['hitpoints']*=0.7;
    break;
    case 4:
        output("`gDu nimmts das Stück Apfelkuchen und ißt es genüsslich. Du fühlst dich prächtig und etwas erholter.`n");
        output("`gDu regenerierst `^40 `gLebenspunkte.");
            $session['user']['hitpoints']+=40;
    break;
    case 5:
        output("`gDu nimmts das Stück Apfelkuchen und ißt es genüsslich. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDie Süße des Apfels wird dich nun eine Zeitlang beschützen!");
            $session['bufflist']['suesse'] = array("name"=>"`@Süße des Apfels`0",
                  "rounds"=>15,
                  "wearoff"=>"`@Die Süße des Apfels hat dich wieder verlassen!`0",
                  "defmod"=>1.2,
                  "atkmod"=>1,
                  "roundmsg"=>"`@Du fühlst dich durch die Süße des Apfelkuchens beflückelt.`0",
                  "activate"=>"defense");
    break;
 }
       $session['user']['gold']-=300;
       addnav("Zurück","baecker.php");
    }
}
// Zitronenkuchen
//***************
if ($_GET['op']=="zitronen"){
   if ($session['user']['gold']<500){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,5)){
    case 1:
        output("`gDu nimmts das Stück Zitronenkuchen und ißt es genüsslich. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Stück Zitronenkuchen und ißt es genüsslich. Du bemerkst aber, das du jetzt mehr als satt bist.`n");
        output("`gDu bist so voll, das du dich fast nicht mehr bewegen kannst und das von einem Stück?! Du verlierst `^4 `gRunden.");
            $session['user']['turns']-=4;
    break;
    case 3:
        output("`gDu nimmts das Stück Zitronenkuchen und ißt es genüsslich. Du bemerkst, dass wohl einige Zutaten nicht mehr so frisch waren.`n");
        output("`gDu hast höllische Bauchschmerzen!`n");
        output("`gDu verlierst einige Lebenspunkte.");
            $session['user']['hitpoints']*=0.8;
    break;
    case 4:
        output("`gDu nimmts das Stück Zitronenkuchen und ißt es genüsslich. Du fühlst dich prächtig und etwas erholter.`n");
        output("`gDu regenerierst `^60 `gLebenspunkte.");
            $session['user']['hitpoints']+=60;
    break;
    case 5:
        output("`gDu nimmts das Stück Zitronenkuchen und ißt es genüsslich. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDie Säure der Zitrone wird deine Gegner eine Zeitlang schwächen und dich stärken!");
            $session['bufflist']['saeure'] = array("name"=>"`^Säure der Zitrone`0",
                  "rounds"=>15,
                  "wearoff"=>"`^Die Säure der Zitrone hat dich wieder verlassen!`0",
                  "defmod"=>1,
                  "atkmod"=>1.1,
                  "roundmsg"=>"`^Du spürst, das es deinem Gegner den Mund zusammenzieht.`0",
                  "activate"=>"offense");
    break;
 }
       $session['user']['gold']-=500;
       addnav("Zurück","baecker.php");
    }
}
// Erdbeertorte
//*************
if ($_GET['op']=="erdbeer"){
   if ($session['user']['gold']<750){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,6)){
    case 1:
        output("`gDu nimmts das Stück Erdbeertorte und ißt es genüsslich. Du findest, das sie sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Stück Erdbeertorte und ißt es genüsslich. Du bemerkst aber, das du jetzt mehr als satt bist.`n");
        output("`gDu bist so voll, das du dich fast nicht mehr bewegen kannst und das von einem Stück?! Du verlierst `^3 `gRunden.");
            $session['user']['turns']-=3;
    break;
    case 3:
        output("`gDu nimmts das Stück Erdbeertorte und ißt es genüsslich. Du bemerkst, das du dich jetzt viel fiter fühlst.`n");
        output("`gDu bist so fit, das du noch etwas im Wald kämpfen willst. Du gewinnst `^1 `gzusätzliche Runde.");
            $session['user']['turns']+=1;
    break;
    case 4:
        output("`gDu nimmts das Stück Erdbeertorte und ißt es genüsslich. Du bemerkst, dass wohl einige Zutaten nicht mehr so frisch waren.`n");
        output("`gDu hast höllische Bauchschmerzen!`n");
        output("`gDu verlierst ein Paar Lebenspunkte.");
            $session['user']['hitpoints']*=0.85;
    break;
    case 5:
        output("`gDu nimmts das Stück Erdbeertorte und ißt es genüsslich. Du fühlst dich prächtig und etwas erholter.`n");
        output("`gDu regenerierst `^80 `gLebenspunkte.");
            $session['user']['hitpoints']+=80;
    break;
    case 6:
        output("`gDu nimmts das Stück Erdbeertorte und ißt es genüsslich. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDie Macht der Erdbeere wird dich eine Zeitlang begleiden!");
            $session['bufflist']['erdbeer'] = array("name"=>"`$Macht der Erdbeere`0",
                  "rounds"=>20,
                  "wearoff"=>"`$Die Macht der Erdbeere hat dich wieder verlassen!`0",
                  "defmod"=>1.1,
                  "atkmod"=>1.1,
                  "roundmsg"=>"`$Du spürst, wie dir die Macht der Erdbeere Kraft gibt.`0",
                  "activate"=>"offense,defense");
    break;
 }
       $session['user']['gold']-=750;
       addnav("Zurück","baecker.php");
    }
}
// Himbeertorte
//*************
if ($_GET['op']=="himbeer"){
   if ($session['user']['gold']<1000){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,6)){
    case 1:
        output("`gDu nimmts das Stück Himbeertorte und ißt es genüsslich. Du findest, das sie sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Stück Himbeertorte und ißt es genüsslich. Du bemerkst aber, das du jetzt mehr als satt bist.`n");
        output("`gDu bist so voll, das du dich fast nicht mehr bewegen kannst und das von einem Stück?! Du verlierst `^2 `gRunden.");
            $session['user']['turns']-=2;
    break;
    case 3:
        output("`gDu nimmts das Stück Himbeertorte und ißt es genüsslich. Du bemerkst, das du dich jetzt viel fiter fühlst.`n");
        output("`gDu bist so fit, das du noch etwas im Wald kämpfen willst. Du gewinnst `^2 `gzusätzliche Runden.");
            $session['user']['turns']+=2;
    break;
    case 4:
        output("`gDu nimmts das Stück Himbeertorte und ißt es genüsslich. Du bemerkst, dass wohl einige Zutaten nicht mehr so frisch waren.`n");
        output("`gDu hast höllische Bauchschmerzen!`n");
        output("`gDu verlierst ein Paar Lebenspunkte.");
            $session['user']['hitpoints']*=0.9;
    break;
    case 5:
        output("`gDu nimmts das Stück Himbeertorte und ißt es genüsslich. Du fühlst dich prächtig und etwas erholter.`n");
        output("`gDu regenerierst `^100 `gLebenspunkte.");
            $session['user']['hitpoints']+=100;
    break;
    case 6:
        output("`gDu nimmts das Stück Himbeertorte und ißt es genüsslich. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDie Macht der Himbeere wird dich eine Zeitlang begleiden!");
            $session['bufflist']['himbeer'] = array("name"=>"`VMacht der Himbeere`0",
                  "rounds"=>30,
                  "wearoff"=>"`VDie Macht der Himbeere hat dich wieder verlassen!`0",
                  "defmod"=>1.2,
                  "atkmod"=>1.2,
                  "roundmsg"=>"`VDu spürst, wie dir die Macht der Himbeere Kraft gibt.`0",
                  "activate"=>"offense,defense");
    break;
 }
       $session['user']['gold']-=1000;
       addnav("Zurück","baecker.php");
    }
}
// Schokotorte
//************
if ($_GET['op']=="schoko"){
   if ($session['user']['gold']<1500){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,6)){
    case 1:
        output("`gDu nimmts das Stück Schokotorte und ißt es genüsslich. Du findest, das sie sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts das Stück Schokotorte und ißt es genüsslich. Du bemerkst aber, das du jetzt mehr als satt bist.`n");
        output("`gDu bist so voll, das du dich fast nicht mehr bewegen kannst und das von einem Stück?! Du verlierst `^1 `gRunde.");
            $session['user']['turns']-=1;
    break;
    case 3:
        output("`gDu nimmts das Stück Schokotorte und ißt es genüsslich. Du bemerkst, das du dich jetzt viel fiter fühlst.`n");
        output("`gDu bist so fit, das du noch etwas im Wald kämpfen willst. Du gewinnst `^3 `gzusätzliche Runden.");
            $session['user']['turns']+=3;
    break;
    case 4:
        output("`gDu nimmts das Stück Schokotorte und ißt es genüsslich. Du bemerkst, dass wohl einige Zutaten nicht mehr so frisch waren.`n");
        output("`gDu hast höllische Bauchschmerzen!`n");
        output("`gDu verlierst ein Paar Lebenspunkte.");
            $session['user']['hitpoints']*=0.95;
    break;
    case 5:
        output("`gDu nimmts das Stück Schokotorte und ißt es genüsslich. Du fühlst dich prächtig und etwas erholter.`n");
        output("`gDu regenerierst `^150 `gLebenspunkte.");
            $session['user']['hitpoints']+=150;
    break;
    case 6:
        output("`gDu nimmts das Stück Schokotorte und ißt es genüsslich. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDie Macht der Schokolade wird dich eine Zeitlang begleiden!");
            $session['bufflist']['schoko'] = array("name"=>"`TMacht der Schokolade`0",
                  "rounds"=>50,
                  "wearoff"=>"`TDie Macht der Schokolade hat dich wieder verlassen!`0",
                  "defmod"=>1.3,
                  "atkmod"=>1.3,
                  "roundmsg"=>"`TDu spürst, wie dir die Macht der Schokolade Kraft gibt.`0",
                  "activate"=>"offense,defense");
    break;
 }
       $session['user']['gold']-=1500;
       addnav("Zurück","baecker.php");
    }
}

//**********
// Getränke:
//**********

// Orangensaft
//************
if ($_GET['op']=="orangensaft"){
   if ($session['user']['gold']<250){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,2)){
    case 1:
        output("`gDu nimmts den Orangensaft und trinkst ihn. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts den Orangensaft und trinkst ihn. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^10 `gLebenspunkte.");
            $session['user']['hitpoints']+=10;
    break;
 }
    $session['user']['gold']-=250;
    addnav("Zurück","baecker.php");
    }
}
// Apfelsaft
//**********
if ($_GET['op']=="apfelsaft"){
   if ($session['user']['gold']<250){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,2)){
    case 1:
        output("`gDu nimmts den Apfelsaft und trinkst ihn. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts den Apfelsaft und trinkst ihn. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^10 `gLebenspunkte.");
            $session['user']['hitpoints']+=10;
    break;
 }
       $session['user']['gold']-=250;
       addnav("Zurück","baecker.php");
    }
}
// Kaffee
//*******
if ($_GET['op']=="kaffee"){
   if ($session['user']['gold']<500){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,5)){
    case 1:
        output("`gDu nimmts den Kaffee und trinkst ihn. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts den Kaffee und trinkst ihn. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^20 `gLebenspunkte.");
            $session['user']['hitpoints']+=20;
    break;
    case 3:
        output("`gDu nimmts den Kaffee und trinkst ihn. Du bemerkst, wie neue Kraft in dir aufsteigt.`n");
        output("`gDu gewinnst `^1 `gzusätzliche Runde.");
            $session['user']['turns']+=1;
    break;
    case 4:
        output("`gDu nimmts den Kaffee und trinkst ihn. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDie Kraft der Kaffeebohne wird dich eine Zeitlang begleiden!");
            $session['bufflist']['kaffee'] = array("name"=>"`~Kraft der Kaffeebohne`0",
                  "rounds"=>25,
                  "wearoff"=>"`~Die Kraft der Kaffeebohne hat dich wieder verlassen!`0",
                  "defmod"=>1.1,
                  "atkmod"=>1.1,
                  "roundmsg"=>"`~Du spürst, wie dich die Kraft der Kaffeebohne stärkt.`0",
                  "activate"=>"offense,defense");
    break;
    case 5:
        output("`gDu nimmts den Kaffee und trinkst ihn. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDer Kaffee war zu bitter und schwächt dich eine Zeitlang!");
            $session['bufflist']['bitter'] = array("name"=>"`~Bitterheit`0",
                  "rounds"=>25,
                  "wearoff"=>"`~Die Bitterheit hat dich wieder verlassen!`0",
                  "defmod"=>0.9,
                  "atkmod"=>0.9,
                  "roundmsg"=>"`~Du spürst, wie dich die Bitterheit schwächt.`0",
                  "activate"=>"offense,defense");
    break;
 }
       $session['user']['gold']-=500;
       addnav("Zurück","baecker.php");
    }
}
// Tee
//****
if ($_GET['op']=="tee"){
   if ($session['user']['gold']<500){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,5)){
    case 1:
        output("`gDu nimmts den Tee und trinkst ihn. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts den Tee und trinkst ihn. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^20 `gLebenspunkte.");
            $session['user']['hitpoints']+=20;
    break;
    case 3:
        output("`gDu nimmts den Tee und trinkst ihn. Du bemerkst, wie neue Kraft in dir aufsteigt.`n");
        output("`gDu gewinnst `^1 `gzusätzliche Runde.");
            $session['user']['turns']+=1;
    break;
    case 4:
        output("`gDu nimmts den Kaffee und trinkst ihn. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDie Macht des Teebeutels wird dich eine Zeitlang begleiden!");
            $session['bufflist']['tee'] = array("name"=>"`2Macht des Teebeutels`0",
                  "rounds"=>25,
                  "wearoff"=>"`2Die Macht des Teebeutels hat dich wieder verlassen!`0",
                  "defmod"=>1.1,
                  "atkmod"=>1.1,
                  "roundmsg"=>"`2Du spürst, wie dich die Macht des Teebeutels stärkt.`0",
                  "activate"=>"offense,defense");
    break;
    case 5:
        output("`gDu nimmts den Tee und trinkst ihn. Du spürst, dass eine merkwürdige Kraft in dir aufsteigt.`n");
        output("`gDer Tee war scheinbar schon sehr alt und schlecht. Er schwächt dich eine Zeitlang!");
            $session['bufflist']['schlecht'] = array("name"=>"`2Übelkeit`0",
                  "rounds"=>25,
                  "wearoff"=>"`2Die Übelkeit hat dich wieder verlassen!`0",
                  "defmod"=>0.9,
                  "atkmod"=>0.9,
                  "roundmsg"=>"`2Du spürst, wie dich die Übelkeit schwächt.`0",
                  "activate"=>"offense,defense");
    break;
 }
       $session['user']['gold']-=500;
       addnav("Zurück","baecker.php");
    }
}

//*************
// Süßigkeiten:
//*************

// Lakritzstange
//**************
if ($_GET['op']=="lakritz"){
   if ($session['user']['gold']<100){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,3)){
    case 1:
        output("`gDu nimmts die Lakritzstange und verspeist sie genüßlich. Du findest, das sie sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts die Lakritzstange und verspeist sie genüßlich. Du bemerkst aber nicht,`n");
        output("`gdass dir etwas von der Lakritzstange zwischen den Zähnen stecken bleibt.`n");
        output("`gJedesmal wenn du lächelst, kann man es sehen. Du verlierst `^2 `gCharmepunkte.");
            $session['user']['charm']-=2;
    break;
    case 3:
        output("`gDu nimmts die Lakritzstange und verspeist sie genüßlich. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^10 `gLebenspunkte.");
            $session['user']['hitpoints']+=10;
    break;
 }
    $session['user']['gold']-=100;
    addnav("Zurück","baecker.php");
    }
}
// Lutscher
//*********
if ($_GET['op']=="lutscher"){
   if ($session['user']['gold']<200){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,4)){
    case 1:
        output("`gDu nimmts den Lutscher und beginnst ihn genüßlich zu lutschen. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts den Lutscher und beginnst ihn genüßlich zu lutschen. Vor lauter Übereifer beißt du in den Lutscher`n");
        output("`gund brichst dir dabei einen Zahn ab.`n");
        output("`gDu verlierst einige Lebenspunkte.");
            $session['user']['hitpoints']*=0.8;
    break;
    case 3:
        output("`gDu nimmts den Lutscher und beginnst ihn genüßlich zu lutschen. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^20 `gLebenspunkte.");
            $session['user']['hitpoints']+=20;
    break;
    case 4:
        output("`gDu nimmts den Lutscher und beginnst ihn genüßlich zu lutschen. Als du ihn fertiggelutscht hast,`n");
        output("`gbemerkst du, dass er `^1 `gEdelstein umhüllt hat und du steckst ihn schnell ein.");
            $session['user']['gems']+=1;
    break;
 }
    $session['user']['gold']-=200;
    addnav("Zurück","baecker.php");
    }
}
// Lebkuchen
//**********
if ($_GET['op']=="lebkuchen"){
   if ($session['user']['gold']<400){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,4)){
    case 1:
        output("`gDu nimmts den Lebkuchen und verspeist ihn genüßlich. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts den Lebkuchen und verspeist ihn genüßlich. Du bemerkst, das er steinhart ist und brichst dir dabei einen Zahn ab.`n");
        output("`gDu verlierst einige Lebenspunkte.");
            $session['user']['hitpoints']*=0.85;
    break;
    case 3:
        output("`gDu nimmts den Lebkuchen und verspeist ihn genüßlich. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^40 `gLebenspunkte.");
            $session['user']['hitpoints']+=40;
    break;
    case 4:
        output("`gDu nimmts den Lebkuchen und verspeist ihn genüßlich. Als du in ihn hineinbeißt, bemerkst du etwas hartes!`n");
        output("`gDu findest in dem Lebkuchen `^500 `gGoldstücke.");
            $session['user']['gold']+=500;
    break;
 }
       $session['user']['gold']-=400;
       addnav("Zurück","baecker.php");
    }
}
// Kandierter Apfel
//*****************
if ($_GET['op']=="apfel"){
   if ($session['user']['gold']<800){
      output ("Du hast nicht genug Gold");
      addnav("Zurück","baecker.php");
   } else {
switch(e_rand(1,4)){
    case 1:
        output("`gDu nimmts den kandierten Apfel und verspeist ihn genüßlich. Du findest, das er sehr lecker schmeckt.`n");
        output("`gAber du spürst keinen besonderen Effekt.");
    break;
    case 2:
        output("`gDu nimmts den kandierten Apfel und verspeist ihn genüßlich. Du bemerkst, das er steinhart ist und brichst dir dabei einen Zahn ab.`n");
        output("`gDu verlierst einige Lebenspunkte.");
            $session['user']['hitpoints']*=0.9;
    break;
    case 3:
        output("`gDu nimmts den kandierten Apfel und verspeist ihn genüßlich. Du bemerkst, dass es dir jetzt viel besser geht.`n");
        output("`gDu regenerierst `^60 `gLebenspunkte.");
            $session['user']['hitpoints']+=60;
    break;
    case 4:
        output("`gDu nimmts den kandierten Apfel und verspeist ihn genüßlich. Du bemerkst, das etwas seltsames mit dir Geschieht!`n");
        output("`gDu fühlst dich su unbeschwert wie ein kleines Kind.");
            $session['bufflist']['kind'] = array("name"=>"`QUnbeschwertheit`0",
                  "rounds"=>25,
                  "wearoff"=>"`QDie Unbeschwertheit eines Kindes ist leider wieder verflogen!`0",
                  "defmod"=>1.2,
                  "atkmod"=>1.2,
                  "roundmsg"=>"`QDu fühlst dich so unbeschwert wie Kind.`0",
                  "activate"=>"offense,defense");
    break;
 }
       $session['user']['gold']-=800;
       addnav("Zurück","baecker.php");
    }
}
page_footer();
?>

