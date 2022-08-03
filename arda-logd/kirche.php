<?php
//*-------------------------*
//|         Kirche by       |
//|       °*Amerilion*°     |
//|      greenmano@gmx.de   |
//*-------------------------*

//Sanela-Pack Version 1.1

require_once "common.php";

page_header("Die Kirche");
$session['user']['sanela']=unserialize($session['user']['sanela']);
if($_GET['op']==""){
    output("`b`cDie Kirche`c`b`nDu betrittst die düstere, aus schwarzem Gestein erbaute Kirche, welche eine leicht");
    output("bedrückende Atmosphäre ausstrahlt. Es stehen lange Reihen von Bänken in ihr und vorne steht");
    output("ein, mit goldenen Reliquien verzierter Altar. Neben diesem ist ein Spendenstein aufgestellt");
    output("und ein Priester in dunkler Kutte steht, tief im Gebet versunken, vor einem goldenen Kreuz.");
    addnav("Die Kirche");
    addnav("Priester ansprechen","kirche.php?op=spreche");
    addnav("Seitennische","kirche.php?op=spende");
    addnav("Raus","sanela.php");
}
if ($_GET['op']=="spreche"){
    output("Du gehst auf den Priester zu und sprichst ihn an. Er sagt dir, dass er dir");
    output("Fragen nach dem Dorf und den Reliquien beantworten kann. Ausserdem spricht er von");
    output("einem Segen.");
    addnav("Dorf","kirche.php?op=spreche&subop=dorf");
    addnav("Segen","kirche.php?op=spreche&subop=segen");
    addnav("Reliquien","kirche.php?op=spreche&subop=reliquien");
    addnav("Verabschieden","kirche.php");

    if($_GET['subop']=="dorf"){
        output("`n`n`b`i`@Das Dorf in den Wäldern`i`b`n`n",true);
        output("`#Das hier ist ein kleines, von vielen Abenteueren besuchtes Dorf. Es liegt im");
        output("Wald. In der Nähe sind einge größere Hügel. Schon lange existiert es; alte");
        output("Mauern bezeugen dies.");
        output("Recht viele Abenteuer lassen sich hier nieder und kommen hier zu");
        output("Ruhm und Ehre. Ausserdem können auch die Händler hier einiges an Reichtum gewinnen.");
    }
    if($_GET['subop']=="segen"){
        output("`n`nDer Priester hebt segnend die Hände, berührt das goldene Kreuz, legt dir");
        output("danach die Hände auf den Kopf und spricht`n`n`b`^E Nomine Patres, et filli, et");
        output("`^spritus sancti.`b`n");
    }
    if($_GET['subop']=="reliquien"){
        output("`n`n`#Diese Reliquien stammen noch aus grauer Vorzeit und ihnen werden grosse");
        output("`#Kräfte zugeschrieben. So sollen sie schon einen Mann wieder sehend gemacht haben");
        output("`#obwohl er sie gar nicht berühren konnte - nur durch ihre Nähe.");
        if ($session['user']['sanela']['kirche'] <1){
            output("`n`n`7Er öffnet eine kleine Holzkiste und du wirfst einen kurzen Blick auf");
            output("die heiligen Gegenstände.");
            $session['user']['sanela']['kirche']++;
            switch(e_rand(1,3)){
                case 1:
                case 2:
                output("`n`n`^Du bemerkst, wie dich ein ehrfürchtiges Schaudern durchfährt.");
                break;
                case 3:
                output("`n`n`^Dich durchfährt ein freudiges Kribbeln und du bemerkst, dass du alles");
                output("tun musst, um diese wunderbaren Gegenstände zu beschützen. So ermutigt wirst du dich");
                output("besser zu verteidigen wissen.");
                $session['bufflist']['heilige'] = array("name"=>"`2Heilige Verteidigung",
                "rounds"=>25,
                "wearoff"=>"Die Gedanken an die Reliquien verblassen...",
                "defmod"=>1.20,
                "atkmod"=>1.00,
                "roundmsg"=>"Die Gedanken an die Reliquien bringen dich dazu, mehr auf deine Verteidigung zu achten",
                "activate"=>"defense");
                break;
            }
        }else{
            output("`#Doch du hast sie schon gesehen. Komm doch morgen wieder.");
        }
    }
}
if($_GET['op']=="spende"){
    output("Du betrittst eine kleine Seitennische. Hier steht ein kleiner Kasten in dem");
    output("Spenden gesammelt werden. Du schaust in deinen Goldbeutel und überlegst, ob du nicht");
    output("ein wenig Spenden willst.");
    if ($session['user']['sanela']['kirche'] <1){
        addnav("Wie viel?");
        $session['user']['sanela']['kirche']++;
        if($session['user']['gold']>100)addnav("100","kirche.php?op=spendee");
        if($session['user']['gold']>500)addnav("500","kirche.php?op=spendef");
        if($session['user']['gold']>1000)addnav("1000","kirche.php?op=spendet");
        if($session['user']['gold']>2500)addnav("2500","kirche.php?op=spendez");
        addnav("Zurück","kirche.php");
    }else{
        output("Du bemerkst, dass der Kasten schon voll ist und du nichts mehr hineinlegen");
        output("kannst.");
        addnav("Zurück","kirche.php");
    }
}
if($_GET['op']=="spendee"){
    output("Du legst 100 Gold in den Spendenkasten und fühlst dich froh und frei");
    addnav("Zurück","kirche.php");
    $session['user']['gold']-=100;
    if(e_rand(1,2)==1){
        output("und bemerkst voller Freude, dass du heute besonders eifrig sein wirst.");
        $session['user']['turns']+=2;
    } else {
        output("und bemerkst, dass du auf wunderbare Weise geheilt worden zu sein scheinst.");
        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
    }
}
if($_GET['op']=="spendef"){
    output("Du legst 500 Gold in den Kasten und bemerkst wie du dich beschwingt und stark fühlst.");
    $session['user']['gold']-=500;
    $session['user']['hitpoints']=$session['user']['maxhitpoints']*1.2;
    addnav("Zurück","kirche.php");
}
if($_GET['op']=="spendet"){
    output("Noch bevor du dein Geld auf dem Schrein hinterlegen kannst bemerkst du eine");
    output("ärmlich gekleidete Frau, die an dir vorbei in Richtung Prister geht. Dieser spricht aber nicht");
    output("mit ihr, sondern zeigt nur stumm auf die Ausgangstür. Die Frau dreht sich um und geht");
    output("aus der Kirche, wobei sie die Hände im Gesicht verbirgt.");
    addnav("Die Arme Frau");
    addnav("1000 Gold Spenden","kirche.php?op=tausendspenden");
    addnav("Ihr nachgehen","kirche.php?op=nachgehen");
    addnav("Den Prister zurechtweisen","kirche.php?op=pristertot");
}
if($_GET['op']=="spendez"){
    output("Du suchst 2500 Gold zusammen und legst diese in den Kasten. Dich durchfährt");
    output("eine tiefe Ruhe und du bekommst das Bedürfnis, einige Runden zu meditieren. Wofür");
    output("du allerdings Waldkämpfe brauchst.");
    addnav("Innere Ruhe");
    $session['user']['gold']-=2500;
    if($session['user']['turns']>0)addnav("Meditiere","kirche.php?op=meditiere");
    addnav("Zurück","kirche.php");
}
if($_GET['op']=="tausendspenden"){
    output("Du legst dein Gold endültig in den Kasten und schaust dich zufrieden um");
    $session['user']['gold']-=1000;
    addnav("Zurück","kirche.php?");
}
if($_GET['op']=="nachgehen"){
    output("Du gehst der Frau nach und findest sie aber nicht mehr");
    addnav("Egal...","sanela.php");
}
if($_GET['op']=="pristertot"){
    output("Dich durchfährt auf einmal eine böse Macht. Voll Wut ziehst du dein `w und");
    output("testest es am Prister. Voller Wahn in den Augen rennst du danach die Treppen");
    output("des Kirchturms hinauf, um dich mit dämonischem Singsang vom Glockenturm in den");
    output("Tod zu stürzen.`n`n");
    output("`\$Ramius, der Gott der Toten`( erscheint dir in einer Vision. Dafür, dass");
    output("du einen Prister getötet hast, sagt er dir wortlos, dass du keinen Gefallen mehr bei ihm hast.`n`n");
    addnews("`&".$session['user']['name']." hat im dämonischen Bann einen Prister getötet und sich selbst gerichtet");
    $session['user']['hitpoints']=0;
    $session['user']['alive']=false;
    $session['user']['soulpoints']=0;
    $session['user']['experience']*=0.75;
    addnav("Tägliche News","news.php");
}
if($_GET['op']=="meditiere"){
    output("Du überlegst, wie lange du in der ruhigen Kirche verweilen sollst.");
    if($session['user']['turns']>=2)addnav("Eine Runde","kirche.php?op=meditate&subop=1");
    if($session['user']['turns']>=3)addnav("Zwei Runden","kirche.php?op=meditate&subop=2");
    if($session['user']['turns']>=4)addnav("Drei Runden","kirche.php?op=meditate&subop=3");
    if($session['user']['turns']>=5)addnav("Vier Runden","kirche.php?op=meditate&subop=4");
    if($session['user']['turns']>=6)addnav("Fünf Runden","kirche.php?op=meditate&subop=5");
    addnav("Lieber gar nicht","kirche.php");
}
if($_GET['op']=="meditate"){
    output("Du bemerkst während deiner Meditation, wie du stärker wirst. Ausserdem kommt deine Lebensenergie wieder zu dir zurück.");
    switch($_GET['subop']){
        case "1":
        $session['bufflist']['ruhea']=array(    "name"=>"`^Ruhige Gedanken",
        "rounds"=>10,
        "wearoff"=>"Die alltägliche Hektik erfasst dich wieder",
        "defmod"=>1.10,
        "atkmod"=>1.10,
        "roundmsg"=>"Deine Gedanken bringen dich dazu, dass du in Ruhe auf den Kampf achten kannst.",
        "activate"=>"offense");
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        $session['user']['turns']--;
        break;
        case "2":
        $session['bufflist']['ruheb']=array(    "name"=>"`^Ruhige Gedanken",
        "rounds"=>20,
        "wearoff"=>"Die alltägliche Hektik erfasst dich wieder",
        "defmod"=>1.10,
        "atkmod"=>1.10,
        "roundmsg"=>"Deine Gedanken bringen dich dazu, dass du in Ruhe auf den Kampf achten kannst.",
        "activate"=>"offense");
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        $session['user']['turns']-=2;
        break;
        case "3":
        $session['bufflist']['ruhec']=array(    "name"=>"`^Ruhige Gedanken",
        "rounds"=>30,
        "wearoff"=>"Die alltägliche Hektik erfasst dich wieder",
        "defmod"=>1.10,
        "atkmod"=>1.10,
        "roundmsg"=>"Deine Gedanken bringen dich dazu, dass du in Ruhe auf den Kampf achten kannst.",
        "activate"=>"offense");
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        $session['user']['turns']-=3;
        break;
        case "4":
        output("Mehr als das sogar. Du fühlst dich erfahrener.");
        $session['bufflist']['ruhed']=array(    "name"=>"`^Ruhige Gedanken",
        "rounds"=>30,
        "wearoff"=>"Die alltägliche Hektik erfasst dich wieder",
        "defmod"=>1.15,
        "atkmod"=>1.15,
        "roundmsg"=>"Deine Gedanken bringen dich dazu, dass du in Ruhe auf den Kampf achten kannst.",
        "activate"=>"offense");
        $session['user']['hitpoints']=$session['user']['maxhitpoints']*1.2;
        $session['user']['experience']*=1.05;
        $session['user']['turns']-=4;
        break;
        case "5":
        output("Mehr als das sogar. Du fühlst dich erfahrener.");
        $session['bufflist']['ruhed']=array(    "name"=>"`^Ruhige Gedanken",
        "rounds"=>30,
        "wearoff"=>"Die alltägliche Hektik erfasst dich wieder",
        "defmod"=>1.15,
        "atkmod"=>1.15,
        "roundmsg"=>"Deine Gedanken bringen dich dazu, dass du in Ruhe auf den Kampf achten kannst.",
        "activate"=>"offense");
        $session['user']['hitpoints']=$session['user']['maxhitpoints']*1.25;
        $session['user']['experience']*=1.10;
        $session['user']['turns']-=5;
        break;
    }
    addnav("Zurück","kirche.php");
}
$session['user']['sanela']=serialize($session['user']['sanela']);
page_footer();
?>