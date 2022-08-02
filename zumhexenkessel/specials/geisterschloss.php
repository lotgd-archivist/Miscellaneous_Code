<?php
//
// by Squall and Dura
// Prog. for Vendal and Spira www.omega-grotte.de
// ALTER TABLE `accounts` ADD `adell` INT(11) NOT NULL DEFAULT '0' and ALTER TABLE `accounts` ADD `adell2` INT(11) NOT NULL DEFAULT '0'
// und in die dragon: ,"adell"=>1
// und am Ende die Hof: addnav("Die verrückte Adell", "hof.php?op=adell&subop=$subop&page=$page");
// //ANFANG Adell
/*elseif ($_GET['op']=="adell") {
    $order = "DESC";
    if ($_GET[subop] == "least") $order = "ASC";
    $sql = "SELECT name, IF(adell,adell,'Unknown') AS data1 FROM accounts WHERE adell>0 ORDER BY adell $order, level $order, experience $order, acctid $order LIMIT $limit";
    $adverb = "tapfersten";
    if ($_GET[subop] == "least") $adverb = "wenig tapferen";
    $title = "Die $adverb Helden im Kampf gegen die verrückte Adell!";
    $headers = array("Zwischensiege");
    $none = "Es gibt noch keine Helden in diesem Land";
    display_table($title, $sql, $none, false, $headers, false);
}
//ENDE Adell*/
require_once "common.php";
page_header("das dunkle Geisterschloss");
addcommentary();

if($_GET['op']==""){
if ($session['user']['adell']>=2){
output ("`4Du darfst erst nach einem Bosskill dein Glück erneut versuchen...verschwinde du Wurm!!");
addnav("Zurück","village.php");
}else{
        output("`c`n<img src='images/1_geisterschloss' width='358' height='269'>`c`n",true);
        output("`b`c`n`9Du läufts einen langen Weg vom Canyon aus...weit, weit....und immer weiter. Irgendwann kommst du an einem Ort an, der dunkel und ausgestorben ausschaut. Nur ein dunkles Schloss ist zu sehen. Du mustert es, beschließt trotz der unheimlichen Atmosphäre dieses zu betreten. Kaum die Türe geöffnet, kommt Dir dunkler Nebel entgegen...zittern rufst Du leichtsinnig wie Du nun mal bist, ein Hallo. Minuten vergehen, aber nichts passiert....nun liegt es an Dir....erkunde es oder renne weg.");
        addnav("Wegrennen","village.php");
        addnav("Mutig erkunden","geisterschloss.php?op=erkunden");
    }
}
// Erkunden
//**************
elseif ($_GET['op']=="erkunden") {
switch(e_rand(1,2)){
    case 1:
        output("`4Du hast Dich entschieden, das Schloss näher zu eforschen und zu untersuchen. Du schaust dich um und kannst nichts von Interesse entdecken. Somit verlässt du es wieder`n");
        $session['user']['turns']-=1;
        addnav("Ort verlassen","village.php");
    break;
    case 2:
        output("`4Du hast Dich entschieden, das Schloss näher zu erforschen und zu untersuchen. Du erblickst eine Tür, die rechts von einer Treppengablung aus zu erreichen ist. Du gehts mutig darauf zu....öffnest vorsichtig die Türe und tritt ein. Du siehts eine große Gestalt auf Dich zu kommen, die Dich ohne Grund angreift und schreit wie eine Bekloppte: `2Wie kannst Du es wagen mein Reich zu betreten, stirb Du Gör.`4 kaum sind die Worte gesprochen, mußt Du wohl oder übel kämpfen!`n");
        $session['user']['turns']-=5;
        $session['k']=1;
        addnav("Kämpfen","geisterschloss.php?op=k");
        //DEBUG:
        //output("Gegner:".$session[user][badguy]);
    break;
    }
}


if ($_GET['op']=="k"){
output("`9Du gehst mit gezogener Waffe auf den Schatten zu, welcher dich sofort angreift.");
$badguy = array(
                "creaturename"=>"`sDie verrückte Adell`0",
                "creaturelevel"=>$session[user][level]+30,
                "creatureweapon"=>"`7Weltenzerstörung",
                "creatureattack"=>$session['user']['attack']+5,
                "creaturedefense"=>$session['user']['defence']+5,
                "creaturehealth"=>round($session['user']['maxhitpoints']*2.05,0),
                "diddamage"=>7);
        $session['user']['badguy']=createstring($badguy);
        $battle=true;
}

//Battle with Adell

else if ($_GET['op']=="run"){   // Flucht
    if (e_rand()%3 == 0){
        output ("`c`b`&Du konntest Adell entkommen!`0`b`c`n");
        $_GET['op']="";
    }else{
        output("`c`b`\$Adell war schneller als du!`0`b`c");
        $battle=true;
    }
}
else if ($_GET[op]=="fight"){   // Kampf
    $battle=true;
}

if ($battle) {
    include("battle.php");
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            output("`n`9Du konntest nach einem schweren Kampf Adell besiegen!");
            //Navigation
            addnav("Weiter","village.php");
            if (rand(1,4)==1) {
                $gem_gain = rand(1,3);
                $gold_gain = rand($session['user']['level']*10,$session['user']['level']*15);
                output("Du findest $gem_gain Substanzen und $gold_gain Gil.`n`n");
            }
            $exp = round($session['user']['experience']*0.03);
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            addnews($session['user']['name']."`4 ging in ein Geistereschloss und kam lebend wieder herraus!");
            $session['user']['experience']+=$exp;
            $session['user']['gold']+=$gold_gain;
            $session['user']['gems']+=$gem_gain;
            $session['user']['adell']+=1;
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']="";
            output("`n`9Adell war stärker!`n`nDu verlierst 5% Deiner Erfahrung.`0");
            addnav("Nachrichtensender","news.php");
            addnews($session['user']['name']."`4 ging in ein Geisterschloss und wurde nie wieder gesehen!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['adell']-=1;
            $session['user']['gems']=round($session['user']['gems']*0.5);
            $session['user']['experience']=round($session['user']['experience']*.95,0);
        } else {
            fightnav(true,true);
        }
}
if ($_GET['op']=="erkunden"){
output("`s Du hast Adell besiegt,");
switch(e_rand(1,5)){
case 1:
case 2:
case 3:
                    output("als sich der Schatten erneuert und du wohl wieder ran musst.");
                    addnav("Kämpfe","geisterschloss.php?op=k");
break;
case 4:
case 5:
                    addnav("Ort verlassen","village.php");
                    output("als dich ein Funkeln in einer offnen Schatzkiste anfunkelt, läufts du daran vorbei und siehst einige Substanzen und nimmst sie an dir!");
                    output("Erfreut steckst du sie ein und gehst wieder deiner Wege.");
                    $gem=(e_rand(1,5));
                    output("`n`n`^Du bekommst $gem Substanzen.");
                    $session['user']['gems']+=$gem;
                    addnav("Weiter","village.php");
break;
                   }
}
page_footer();
?> 