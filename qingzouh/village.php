
<?php

// 21072004
require_once "common.php";
addcommentary();

$date_check = date("d.m");

$saint_omar_season = array(                12    => "winter",
                            1    => "winter",
                            2    => "winter",
                            3     => "spring",
                            4    => "spring",
                            5    => "spring",
                            6    => "summer",
                            7    => "summer",
                            8    => "summer",
                            9    => "fall",
                            10    => "fall",
                            11    => "fall"
                           );
//if($session['user']['acctid'] == 0) {
//    phpinfo();
//}

// Schatten
if ($session['user']['alive']){ }else{
    redirect("shades.php");
}

// Stadttor
if($session['user']['zugang'] == 0){
   redirect("passkontrolle.php");
}

// Kerker
if($session['user']['prison'] == 1){
    redirect("kerker.php");
}

if($session['user']['einzelhaft'] == 1){
    redirect("kerker.php");
}

if($session['user']['acctid'] == 0) {
    output($session['admin_msg']);
    unset($session['admin_msg']);
}

$sql = "SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid']."";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if(($row['acctid1'] == $session['user']['acctid'] AND $row['turn'] == 1) OR ($row['acctid2'] == $session['user']['acctid'] AND $row['turn'] == 2)){
    redirect("heldengasse_pvparena.php");
}

if (getsetting('automaster',1) && $session['user']['seenmaster']!=1){
    //masters hunt down truant students
    $exparray = array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
    while(list($key,$val) = each($exparray)){
        $exparray[$key] = round(
        $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100
        ,0);
    }
    $expreqd = $exparray[$session['user']['level']+1];
    if ($session['user']['experience'] > $expreqd AND $session['user']['level'] < 15){
        redirect("train.php?op=autochallenge");
    }else if ($session['user']['experience'] > $expreqd AND $session['user']['level'] >= 15){
        redirect("dragon.php?op=autochallenge");
    }
}
$session['user']['specialinc'] = "";
$session['user']['specialmisc'] = "";

        addnav("Zhóonguo");
        addnav("Nach Nanjin","nanjin.php");
        addnav("Die Seidenstraße","silkroad.php");
        addnav("Die große Mauer","mauer.php");
        addnav("Fluss Yangtze","yangtze.php");
        addnav("Songshan Gebirge","songshan.php");
        addnav("Die Küste","kuestezhoon.php");
        addnav("Dichte Wälder","waldzhoo.php");
        //if ($session['user']['fsk'] == 2)addnav("Storyville [fsk18]","storyville.php");
        if ($settings['xmas'] > '0') {    
        addnav("Weihnachtsviertel","event_weihnachtsviertel.php");
        }

        addnav("Shirasunai");
        addnav("Honshu - Hauptinsel","honshu.php");
        addnav("Kyushu","kyushu.php");
        addnav("Geisterinsel Miyajima","miya.php");
        addnav("Vulkan Fujiyama","fuyi.php");
        addnav("Gebirgskette","gebirgeshira.php");  
        
        

        addnav("Amthaya");
        addnav("Angkor","angkor.php");
        addnav("Angkor Wat","angkorwat.php");
        addnav("Khorat Plateau","khorat.php");
        addnav("Mekong Becken","mekong.php");

        addnav("Gaozh");
        addnav("Nach Khorkorum","gaozh.php");
        addnav("Das Heerlager","gaozh.php?op=heerlager");
        
        addnav("Das Grasland","gaozh.php?op=grasland");
        addnav("Altai Gebirge","gaozh.php?op=altai");
        addnav("Gobi Wüste","gaozh.php?op=gobi");



        addnav("Das Überirdische");
        addnav("Wolkenreich Chengzouh","chengzouh.php");
        addnav("Unterwelt","unterwelt.php");
        addnav("Wald der Prüfungen (Klickspiel)","forest.php");
        addnav("Die Torpassage","passkontrolle.php");

            
        //Bewohnerliste und zurück zur Stadtauswahl
        addnav("Organisatorisches");
        addnav("Jägerhütte","lodge.php");
        addnav("Aktuelle News","news.php");
        addnav("Bewohnerliste","list.php?ret=Jackson Square");
        addnav("Logout","login.php?op=logout",true);

//let users try to cheat, we protect against this and will know if they try.
addnav("","superuser.php");
addnav("","user.php");
addnav("","taunt.php");
addnav("","creatures.php");
addnav("","configuration.php");
addnav("","badword.php");
addnav("","armoreditor.php");
addnav("","bios.php");
addnav("","badword.php");
addnav("","donators.php");
addnav("","referers.php");
addnav("","retitle.php");
addnav("","stats.php");
addnav("","viewpetition.php");
addnav("","weaponeditor.php");

if(getsetting("topwebid", 0) != 0){
    addnav("Top Web Games");
    if(date("Y-W", strtotime($session['user']['lastwebvote'])) < date("Y-W")){
        $hilight="`&";
    }else{
        $hilight="";
        addnav("S?".$hilight."Stimme abgeben", "http://www.topwebgames.com/in.asp?id=".getsetting("topwebid", 0)."&acctid={$session['user']['acctid']}", false, true);
    }
}

page_header("Qingzouh");

$session['user']['standort'] = "Qingzouh";

//place();

output("


`n`n`n 
<div id=Beschreibung>
<div id=Ortsbild><img src='images/quin/Ortebilder/qing.png'></div>

<center>Qingzouh</center>
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem 
ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore 
magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea 
takimata sanctus est Lorem ipsum dolor sit amet.
</div>

<style>
#Beschreibung {
   width: 500px;
   height: 206px;
   position: relative;
   overflow: hidden;
   color: #c0c0c0;
   text-align: justify;
   margin: 0 auto;
}

#Beschreibung #Ortsbild {
   width: 100%;
   height: auto;
   position: absolute;
   left: 0;
   transition: 0.8s all ease-in-out; 
   -moz-transition: 0.8s all ease-in-out; 
   -webkit-transition: 0.8s all ease-in-out; 
   -ms-transition: 0.8s all ease-in-out; 
   -o-transition: 0.8s all ease-in-out;
}

#Beschreibung:hover #Ortsbild{
   left: -600px;
}
</style>
`n`n


",true);

$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);

//output("`c<img src=images/sonstiges/candle-light(1).gif>`c`n`n", true);

output("`n`cAuf einer Tafel hängen verschiedene Meldungen.`0`c`n`c`i`&".$row['newstext']."`n",true);
output("`n<a href='popup_serverstory.php?op=volksstimme' target='_blank' onclick=\"window.open('popup_serverstory.php?op=volksstimme','popupserverstoryphp','scrollbars=yes,resizable=yes,width=700,height=500');return false;\">`m>> Neuigkeiten aus Qingzouh.<<`0</a>`i`c`n`n", true);

/*if (getsetting('activategamedate','0')==1)*/ output("`cWeiter unten steht folgendes:`n
Das heutige Datum:`0 `&".getgamedate()."`c`0");
output("`cEs ist`0 `&".getgametime()." Uhr.`c`0`");
output("`cDas heutige Wetter:`0 `&".$settings['weather']."#c0c0c0.`c`0`n`n");


viewcommentary("jacksonsquare","Ins Gespräch kommen:`0`n");


checkday();

page_footer();

?>


