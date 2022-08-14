
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

        addnav("French Quarter");
        addnav("Erkunde Jackson Square","jacksonsquare.php");
        addnav("Moonwalk","moonwalk.php");
        addnav("Bourbon Street","bourbonstreet.php");
        if ($session['user']['fsk'] == 2)
        addnav("John W. Avenue [FSK18]","jwavenue.php");
        addnav("Old French Market","markt.php");
        addnav("Royal Street","royalstreet.php");
        addnav("Chartres Street","chartresstreet.php");
        //addnav("Oktoberfest","oktoberfest.php");
        //if ($session['user']['acctid'] == 1){addnav("Oktoberfest","oktoberfest.php");} 
        if ($settings['xmas'] > '0') {    
        addnav("Weihnachtsviertel","event_weihnachtsviertel.php");
        }
        if ($session['user']['fsk'] == 2){
        addnav("Crescent District [FSK18]");
        addnav("Storyville","storyville.php");
        addnav("Little Las Vegas","vegas.php");
        addnav("The Streets","streets.php");
        addnav("Abgeschiedene Orte","abgeschieden.php");
        }
        addnav("Central Business District");
        addnav("Downtown","americanquarter.php");
        addnav("Warehouse District","warehouse.php");

        addnav("Downriver");
        addnav("Marigny","marigny.php");
        addnav("Bywater","bywater.php");
        addnav("Upper Wards","upperwards.php");
        addnav("Lower Ward","lowerward.php");

        addnav("Uptown");
        addnav("Uptown","uptown.php");
        addnav("Audubon","audubon.php");
        addnav("Carrollton","carrollton.php");

        addnav("Middle");
        addnav("Mid-City & Esplanade Ridge","midcity.php");
        addnav("TremÃ©","treme.php");
        addnav("Central City","centralcity.php");

        addnav("Lakeside");
        addnav("Lakeview & Lakeshore","lakeview.php");
        addnav("Gentilly","gentilly.php");
        addnav("Eastern New Orleans","eastern.php");

        addnav("Myth Algiers");
        addnav("Hinter den Schleier","mythalgiers.php");

        addnav("Ausserhalb New Orleans");
        addnav("Golf of Mexico","golfofmexico.php");
        addnav("Flughafen","flughafen.php");
        addnav("Wald zum Bayou","forest.php");

            
        //Bewohnerliste und zurÃ¼ck zur Stadtauswahl
        addnav("Organisatorisches");
        addnav("JÃ¤gerhÃ¼tte","lodge.php");
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

page_header("Jackson Square");

$session['user']['standort'] = "Jackson Square";

//place();

output("



<div id=Beschreibung>
<div id=Ortsbild><img src='templates/orleans/img/jackson.png'></div>

<center>#662c08J#6d340da#743c12c#7c4418k#834c1ds#8b5423o#925c28n #99642dS#a16c33q#a87438u#b07c3ea#b78443r#bf8c49e</center>
`Ã°Der Jackson Square liegt im Zentrum der gitterfÃ¶rmigen Anlage des Vieux Carre gegenÃ¼ber dem Fluss. FrÃ¼her war der Platz als Paradeplatz 
als der â€žPlace d' Armesâ€œ (Waffenplatz) bekannt. Soldaten exerzierten vor der Stadtkirche, die von den Hauptquartieren des spanischen 
Stadtrats flankiert wurden. Nachdem die Spanier die Gemeinde Ã¼bernommen hatten, tauften sie den Platz â€žPlaza de Armasâ€œ. Die Bezeichnung 
Jackson Square entstand im 19. Jahrhundert, als eine Reiterstatue Andrew Jacksons auf der Platzmitte aufgestellt wurde. Am Jackson Square 
steht auch die nach dem Stadtbrand von 1788 in den Jahren 1789 bis 1794 neu errichtete rÃ¶misch-katholische St.-Louis-Kathedrale.
Hier ist der zentrale Treffpunkt fÃ¼r Jung und Alt.
`0
</div>

<style>
#Beschreibung {
   width: 500px;
   height: 250px;
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

output("`n`cAuf einem riesigen Flatscreen kannst du die wichtigsten Meldungen aus New Orleans erfahren:`0`c`n`c`i`&".$row['newstext']."`n",true);
output("`n<a href='popup_serverstory.php?op=volksstimme' target='_blank' onclick=\"window.open('popup_serverstory.php?op=volksstimme','popupserverstoryphp','scrollbars=yes,resizable=yes,width=700,height=500');return false;\">`m>> Lest die aktuellen News aus aller Welt! <<`0</a>`i`c`n`n", true);

/*if (getsetting('activategamedate','0')==1)*/ output("`cWeiter unten steht folgendes:`n
Das heutige Datum zeigt den`0 `&".getgamedate()."`c`0");
output("`cDie groÃŸe Uhr des Jackson Square zeigt`0 `&".getgametime()." Uhr.`c`0`");
output("`cDas heutige Wetter:`0 `&".$settings['weather']."#c0c0c0.`c`0`n`n");


viewcommentary("jacksonsquare","Ins GesprÃ¤ch kommen:`0`n");


checkday();

page_footer();

?>


