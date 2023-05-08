
<?php

// 30062004

// created by Lonny Luberts for http://www.pqcomp.com/logd, built on idea from quest's giftshop with all new code.
// this file needs customization before use and is designed to be added in many places if need be
// as different gift shops.
// search and replace (newgiftshop.php) with what you name the giftshop php file
// search and replace (gift 1)-(your gift) with your gifts - make sure you use the space inbetween gift & 1 etc...
// if you do an auto replace with your editor.
// be sure to edit the return nav
// please feel free to use and edit this file, any major upgrades or improvements should be
// mailed to logd@pqcomp.com for consideration as a permenant inclusion
// please do not remove the comments from this file.
// Version: 03212004
//
// changes to fit ext (GER) and translation by anpera
// added items with buffs
// card modification by aska
require_once "common.php";
if ($session[user][locate]!=20){
    $session[user][locate]=20;
    redirect("newgiftshop.php");
}
checkday();
page_header("Geschenkeladen");
//checkevent();
output("`c`b`&Geschenkeladen`0`b`c`n`n");
if ($_GET[op]=="" && $session[user][turns]>0){
    output("`rDu betrittst den Geschenkeladen und siehst eine Menge einzigartiger Gegenstände.`n");
    output("Ein".($session[user][sex]?" junger Mann":"e junge Frau")." steht hinter der Ladentheke und lächelt dich sanft an.`n");
    output("Ein Schild an der Wand verspricht \"`iGeschenkverpackung und Lieferung frei.`i\"`n");
    // changed the next line to make sense for your gift shop (specialty)
    // output("`3This shop specializes in specialty, you see for sale...`n`n"); // only need one shop
    addnav("Tulpe","newgiftshop.php?op=send&op2=gift1");
    if ($session[user][gold] >= 10) addnav("Rose - 10 Gold","newgiftshop.php?op=send&op2=gift2");
    if ($session[user][gold] >= 20) addnav("Strauß Rosen - 20 Gold","newgiftshop.php?op=send&op2=gift3");
    if ($session[user][gold] >= 40) addnav("Pralinen - 40 Gold","newgiftshop.php?op=send&op2=gift4");
    if ($session[user][gold] >= 60) addnav("Freundschaftsbändchen - 60 Gold","newgiftshop.php?op=send&op2=gift5");
    if ($session[user][gold] >= 100) addnav("Freundschaftsanhänger - 100 Gold","newgiftshop.php?op=send&op2=gift6");
    if ($session[user][gold] >= 200) addnav("Halskette - 200 Gold","newgiftshop.php?op=send&op2=gift7");
    if ($session[user][gold] >= 500) addnav("Plüschdrachen - 500 Gold","newgiftshop.php?op=send&op2=gift8");
    if ($session[user][gold] >= 1000) addnav("Beutel Heilkräuter - 1000 Gold","newgiftshop.php?op=send&op2=gift9");
    if ($session[user][gold] >= 1500) addnav("Drachenei - 1500 Gold","newgiftshop.php?op=send&op2=gift10");
    if ($session[user][gold] >= 2000) addnav("Goldenes Amulett - 2000 Gold","newgiftshop.php?op=send&op2=gift11");
    if ($session[user][gold] >= 3000) addnav("Seltsamer Schädel - 3000 Gold","newgiftshop.php?op=send&op2=gift12");
    output("`n<ul><a href=\"newgiftshop.php?op=send&op2=gift1\">Tulpe</a><br>",true);
    addnav("","newgiftshop.php?op=send&op2=gift1");
    if ($session[user][gold] >= 10){
        output("<a href=\"newgiftshop.php?op=send&op2=gift2\">Rose - 10 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift2");
    }
    if ($session[user][gold] >= 20){
        output("<a href=\"newgiftshop.php?op=send&op2=gift3\">Strauß Rosen - 20 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift3");
    }
    if ($session[user][gold] >= 40){
        output("<a href=\"newgiftshop.php?op=send&op2=gift4\">Pralinen - 40 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift4");
    }
    if ($session[user][gold] >= 60){
        output("<a href=\"newgiftshop.php?op=send&op2=gift5\">Freundschaftsbändchen - 60 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift5");
    }
    if ($session[user][gold] >= 100){
        output("<a href=\"newgiftshop.php?op=send&op2=gift6\">Freundschaftsanhänger - 100 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift6");
    }
    if ($session[user][gold] >= 200){
        output("<a href=\"newgiftshop.php?op=send&op2=gift7\">Halskette - 200 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift7");
    }
    if ($session[user][gold] >= 500){
        output("<a href=\"newgiftshop.php?op=send&op2=gift8\">Plüschdrachen - 500 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift8");
    }
    if ($session[user][gold] >= 1000){
        output("<a href=\"newgiftshop.php?op=send&op2=gift9\">Beutel Heilkräuter - 1000 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift9");
    }
    if ($session[user][gold] >= 1500){
        output("<a href=\"newgiftshop.php?op=send&op2=gift10\">Drachenei - 1500 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift10");
    }
    if ($session[user][gold] >= 2000){
        output("<a href=\"newgiftshop.php?op=send&op2=gift11\">Goldenes Amulett - 2000 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift11");
    }
    if ($session[user][gold] >= 3000){
        output("<a href=\"newgiftshop.php?op=send&op2=gift12\">Seltsamer Schädel - 3000 Gold</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift12");
    }
    output("</ul>",true);
    // change this nav to return to the location you call it from
    addnav("Zurück zum Dorfplatz","village.php");
}else if ($session[user][turns]<=0){
    output("`rDer Geschenkeladen hat jetzt leider schon geschlossen.");
    addnav("Zurück zum Dorfplatz","village.php");
}
if ($_GET[op]=="send"){
    $gift=$_GET[op2];
    if (isset($_POST['search']) || $_GET['search']>""){
        if ($_GET['search']>"") $_POST['search']=$_GET['search'];
        $search="%";
        for ($x=0;$x<strlen($_POST['search']);$x++){
            $search .= substr($_POST['search'],$x,1)."%";
        }
        $search="name LIKE '".$search."' AND ";
        if ($_POST['search']=="weiblich") $search="sex=1 AND ";
        if ($_POST['search']=="männlich") $search="sex=0 AND ";
    }else{
        $search="";
    }
    $ppp=25; // Player Per Page to display
    if (!$_GET[limit]){
        $page=0;
    }else{
        $page=(int)$_GET[limit];
        addnav("Vorherige Seite","newgiftshop.php?op=send&op2=$gift&limit=".($page-1)."&search=$_POST[search]");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND uniqueid<>'".$session[user][uniqueid]."' AND charm>1 ORDER BY (acctid='".$session['user']['marriedto']."') DESC,login,level LIMIT $limit";
    $result = db_query($sql);
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","newgiftshop.php?op=send&op2=$gift&limit=".($page+1)."&search=$_POST[search]");
    output("`rWem willst du das Geschenk schicken?`n`n");
    output("<form action='newgiftshop.php?op=send&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
    addnav("","newgiftshop.php?op=send&op2=$gift");
    output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='newgiftshop.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid'])."'>",true);
        output($row['name']);
        output("</a></td><td>",true);
        output($row['level']);
        output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);
        addnav("","newgiftshop.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid']));
    }
    output("</table>",true);
    addnav("Zurück zum Laden","newgiftshop.php");
}

if ($_GET[op]=="send2"){ 
    $name=$_GET[name];
    $effekt="";
    $gift = $_GET[op2];
    if($_GET[card]=="yes"||$_GET[card]=="no"){
    
        if ($_GET[op2]=="gift1"){
            $gift="Tulpe";
        } 
        if ($_GET[op2]=="gift2"){
            $gift="Rose";
            $session[user][gold]-=10;
        }
        if ($_GET[op2]=="gift3"){
            $gift="Strauß Rosen";
            $session[user][gold]-=20;
        }
        if ($_GET[op2]=="gift4"){
            $gift="Pralinen";
            $effekt="Natürlich futterst du sie sofort alle auf.";
            $session[user][gold]-=40;
        }
        if ($_GET[op2]=="gift5"){
            $gift="Freundschaftsbändchen";
            db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Freundschaftsbändchen',$name,'Geschenk',12,'Ein Freundschaftsbändchen von ".$session[user][name]."')");
            $session[user][gold]-=60;
        }
        if ($_GET[op2]=="gift6"){
            $gift="Freundschaftsanhänger";
            db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Freundschaftsanhänger',$name,'Geschenk',20,'Ein Freundschaftsänhänger von ".$session[user][name]."')");
            $session[user][gold]-=100;
        }
        if ($_GET[op2]=="gift7"){
            $gift="Halskette";
            db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Halskette',$name,'Geschenk',40,'Diese Halskette hat dir ".$session[user][name]." geschenkt.')");
            $session[user][gold]-=200;
        }
        if ($_GET[op2]=="gift8"){
            $gift="Plüschdrachen";
            $effekt="`RDer ist ja soooooo süüüüüüüüüüüssss!!!`0";
            db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Plüschdrachen',$name,'Geschenk',100,'`REin `@Gründer Drachen`R aus Plüsch zum Kuscheln. Der ist von ".$session[user][name].".')");
            $session[user][gold]-=500;
        }
        if ($_GET[op2]=="gift9"){
            $gift="Beutel Heilkräuter";
            $buff = array("name"=>"`1Heilkräuter","rounds"=>20,"wearoff"=>"`1Die Heilkräuter verlieren ihre Wirkung!`0","regen"=>1.2,"roundmsg"=>"`1Die Heilkräuter heilen deine Wunden.`0","activate"=>"defense");
            $buff=serialize($buff);
            $effekt="Diese Heilkräuter heilen Wunden, bleiben aber nicht lange frisch.";
            db_query("INSERT INTO items (name,owner,class,gold,description,hvalue,buff) VALUES ('Beutel Heilkräuter',$name,'Geschenk',500,'$effekt',1,'$buff')");
            $session[user][gold]-=1000;
        }
        if ($_GET[op2]=="gift10"){
            $gift="Drachenei";
            $session[user][gold]-=1500;
        }
        if ($_GET[op2]=="gift11"){
            $gift="Goldenes Amulett";
            $buff = array("name"=>"`rAmulettaura`0","rounds"=>10,"wearoff"=>"`rDie Aura des Amuletts verschwindet.`0","defmod"=>1.1,"activate"=>"roundstart");
            $buff=serialize($buff);
            $effekt="Als du das Amulett anlegst, hüllt es dich in eine merkwürdige, schützende Aura. Der Beipackzettel verrät dir, dass das Amulett nach 3 Tagen seine Wirkung verliert und zu Staub zerfallen wird.";
            db_query("INSERT INTO items (name,owner,class,gold,description,hvalue,buff) VALUES ('Goldenes Amulett',$name,'Geschenk',1000,'$effekt',3,'$buff')");
            $session[user][gold]-=2000;
        }
        if ($_GET[op2]=="gift12"){
            $gift="Seltsamer Schädel";
            $gefallen=e_rand(5,10);
            $effekt="Du untersuchst dieses merkwürdige Geschenk genauer. Dabei rutscht es dir aus der Hand und zerplatzt am Boden in 1000 Stücke. Doch eine seltsame Kraft wird frei, die dir $gefallen Gefallen bei Ramius bringt!";
            db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");
            $session[user][gold]-=3000;
        }
        $mailmessage=$session[user][name];
        $mailmessage.="`7 hat dir ein Geschenk geschickt. Du öffnest es. Es ist ein/e `6";
        $mailmessage.=$gift;
        //you can change the following the match what you name your gift shop
        $mailmessage.="`7 aus dem Geschenkeladen.`n".$effekt;
        if($_GET[card]=="yes"){
        $mailmessage.="`7Es liegt eine Karte mit folgenden Text bei: `n`n";
        $mailmessage.= $_POST[cardtext];
        $mailmessage.="`n";
        }
        systemmail($name,"`2Geschenk erhalten!`2",$mailmessage);
        output("`rDein $gift wurde als Geschenk verschickt!");
        if (e_rand(1,3)==2){
            output(" Bei der Wahl des Geschenks und dem liebevollen Verpacken vergisst du die Zeit und vertrödelst einen Waldkampf.");
            $session[user][turns]--;
        }
        addnav("Weiter","newgiftshop.php");
    }
    else{
        output(($session[user][sex]?"Der Verkäufer":"Die Verkäuferin")." fragt dich freundlich ob du nicht auch eine Karte mitschicken willst.`n");    
        output("<form action='newgiftshop.php?op=send2&op2=$gift&card=yes&name=$name' method='POST'>Folgenden Text schicken: <input name='cardtext' value='$_POST[cardtext]'><input type='submit' class='button' value='Senden'></form>",true);
        addnav("Keine Karte","newgiftshop.php?op=send2&op2=$gift&card=no&name=$name");
        addnav("","newgiftshop.php?op=send2&op2=$gift&card=yes&name=$name");
    }

}
page_footer();
?>

