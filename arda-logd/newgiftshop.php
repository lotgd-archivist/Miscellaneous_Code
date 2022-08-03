<?php

// 19122006 angepasst für silienta by rikkarda@silienta-logd.de
// überarbeitete Version mit Codeversc4hönerungen für www.silienta-logd.de
// code für Schädel von Eliwood vielen dank dafür
// created by Lonny Luberts for http://www.pqcomp.com/logd, built on idea from quest's giftshop with all new code.
// this file needs customization before use and is designed to be added in many places if need be
// as different gift shops.
// search and replace (newgiftshop.php) with what you name the giftshop php file
// search and replace (gift 1)-(your gift) with your gifts - make sure you use the space inbetween gift & 1 etc...
// if you do an auto replace with your editor.
// be sure to edit the return nav
// please feel free to use and edit this file, any major upgrades or improvements should be
// mailed to logd@pqcomp.com for consideration as a permenant inclusion
// please do not remove the comments from this file.
// Version: 03212004
//
// changes to fit ext (GER) and translation by anpera
// added items with buffs
// card modification by aska
//  Kerker-Addon by Maris (Maraxxus@gmx.de)
// own gift by kelko modified for silienta-logd.de
require_once "common.php";

/*if ($session[user][locate]!=20){
    $session[user][locate]=20;
    redirect("newgiftshop.php");
}

/*need a function*/
function getOfUser($id,$it) {

    $result = db_query("SELECT $it FROM accounts WHERE acctid=$id");
    
    if (db_num_rows($result) > 0) {
        $row = db_fetch_assoc($result);
        return $row[$it];
    } else {
        return "";    
    }

}
//end of this

//how much shall the custom gift cost?
$kostgold = 10000;    
$kostgems = 2;

page_header("Geschenkeladen");
//checkevent();
output("`c`b`&Geschenkeladen`0`b`c`n`n");

switch($_GET[op]) {
    case "send":
        $gift=$_GET[op2];
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
            addnav("Vorherige Seite","newgiftshop.php?op=send&op2=$gift&limit=".($page-1)."&search=$_POST[search]");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND uniqueid<>'".$session[user][uniqueid]."' AND charm>1 ORDER BY (acctid='".$session['user']['marriedto']."') DESC,login,level LIMIT $limit";
        $result = db_query($sql);
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","newgiftshop.php?op=send&op2=$gift&limit=".($page+1)."&search=$_POST[search]");
        output("`rWem willst du das Geschenk schicken?`n`n");
        output("<form action='newgiftshop.php?op=send&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","newgiftshop.php?op=send&op2=$gift");
        output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='newgiftshop.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid'])."'>",true);
            output($row['name']);
            output("</a></td><td>",true);
            output($row['level']);
            output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);
            addnav("","newgiftshop.php?op=send2&op2=$gift&name=".HTMLEntities($row['acctid']));
        }
        output("</table>",true);
        addnav("Zurück zum Laden","newgiftshop.php");
        break;
    
    case "send2":
        $name = $_GET[name];
        $gift = $_GET[op2];
        output(($session[user][sex]?"Der Verkäufer":"Die Verkäuferin")." fragt dich freundlich ob du nicht auch eine Karte mitschicken willst.`n");    
            
        output("<form action='newgiftshop.php?op=send3' method='POST'>",true);
        output("<input type='hidden' name='name' value='$name'>", true);
        output("An: ".getOfUser($_GET[name],'name')."`n");
        output("<input type='hidden' name='op2' value='$gift'>",true);
        output("Karte schicken?<input type='radio' checked='checked' name='card' value='no'>Nein <input type='radio' name='card' value='yes'>Ja, nämlich:`n<input name='cardtext' value='$_POST[cardtext]'>`n",true);
            
        if ($gift == "custom") {
            output("Der Name deines Geschenks:`n<input type='text' name='giftName' value='Was besonderes'>`n", true);
            output("Hier kannst du dein Geschenk beschreiben:`n");
            output("<textarea cols='70' rows='20' name='descr' class='input'></textarea>`n",true);
        }
            
        output("<input type='submit' class='button' value='Abschicken'>",true);
        output("</form>",true);
        addnav("","newgiftshop.php?op=send3");
            
        addnav("Doch nicht schicken","newgiftshop.php");
        break;
        
    case "send3":
        $name = $_POST[name];
            $gift = $_POST[op2];
            
            switch($_POST[op2]) {
                case "gift1":
                    $gift="Tulpe";
                    break;
                case "gift2":
                    $gift="Rose";
                    $session[user][gold]-=10;
                    break;
                case "gift3":
                    $gift="Strauß Rosen";
                    $session[user][gold]-=20;
                    break;
                case "gift4":
                    $gift="Pralinen";
                    $effekt="Natürlich futterst du sie sofort alle auf.";
                    $session[user][gold]-=40;
                    break;
                case "gift5":
                    $gift="Freundschaftsbändchen";
                    db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Freundschaftsbändchen',$name,'Geschenk',12,'Ein Freundschaftsbändchen von ".$session[user][name]."')");
                    $session[user][gold]-=60;
                    break;
                case "gift6":
                    $gift="Freundschaftsanhänger";
                    db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Freundschaftsanhänger',$name,'Geschenk',20,'Ein Freundschaftsänhänger von ".$session[user][name]."')");
                       $session[user][gold]-=100;
                    break;
                case "gift7":
                    $gift="Halskette";
                    db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Halskette',$name,'Geschenk',40,'Diese Halskette hat dir ".$session[user][name]." geschenkt.')");
                    $session[user][gold]-=200;
                    break;
                case "gift8":
                    $gift="Plüschdrachen";
                    $effekt="`RDer ist ja soooooo süüüüüüüüüüüssss!!!`0";
                    db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Plüschdrachen',$name,'Geschenk',100,'`REin `@Grüner Drachen`R aus Plüsch zum Kuscheln. Der ist von ".$session[user][name].".')");
                    $session[user][gold]-=500;
                    break;
                case "gift9":
                    $gift="Beutel Heilkräuter";
                    $buff = array("name"=>"`1Heilkräuter","rounds"=>20,"wearoff"=>"`1Die Heilkräuter verlieren ihre Wirkung!`0","regen"=>1.2,"roundmsg"=>"`1Die Heilkräuter heilen deine Wunden.`0","activate"=>"defense");
                    $buff=serialize($buff);
                    $effekt="Diese Heilkräuter heilen Wunden, bleiben aber nicht lange frisch.";
                    db_query("INSERT INTO items (name,owner,class,gold,description,hvalue,buff) VALUES ('Beutel Heilkräuter',$name,'Geschenk',500,'$effekt',1,'$buff')");
                    $session[user][gold]-=1000;
                    break;
                case "gift10":
                    $gift="Drachenei";
                    $session[user][gold]-=1500;
                    break;
                case "gift11":
                    $gift="Goldenes Amulett";
                    $buff = array("name"=>"`rAmulettaura`0","rounds"=>10,"wearoff"=>"`rDie Aura des Amuletts verschwindet.`0","defmod"=>1.1,"activate"=>"roundstart");
                    $buff=serialize($buff);
                    $effekt="Als du das Amulett anlegst, hüllt es dich in eine merkwürdige, schützende Aura. Der Beipackzettel verrät dir, dass das Amulett nach 3 Tagen seine Wirkung verliert und zu Staub zerfallen wird.";
                    db_query("INSERT INTO items (name,owner,class,gold,description,hvalue,buff) VALUES ('Goldenes Amulett',$name,'Geschenk',1000,'$effekt',3,'$buff')");
                    $session[user][gold]-=2000;
                    break;
                case "gift13":
                    $gift="Schutzamulett";
                    $effekt="Ein Amulett zum Schutz gegen Felderüberfälle";
                    db_query("INSERT INTO items (name,owner,class,gold,description,value1) VALUES ('Schutzamulett',$name,'Geschenk',1000,'$effekt',0)");
                    $session[user][gold]-=2000;
                    break;
                case "gift12":
                    $row = db_fetch_assoc(db_query("SELECT acctid,name,geschenk FROM accounts WHERE acctid=$name"));
                    if ("0" == $row['geschenk']) {
                        $gift="Seltsamer Schädel";
                        $gefallen=e_rand(5,10);
                        $effekt="Du untersuchst dieses merkwürdige Geschenk genauer. Dabei rutscht es dir aus der Hand und zerplatzt am Boden in 1000 Stücke. Doch eine seltsame Kraft wird frei, die dir $gefallen Gefallen bei Ramius bringt!";
                        db_query("UPDATE accounts SET deathpower=deathpower+$gefallen,geschenk='1' WHERE acctid='$row[acctid]'");
                        $session[user][gold]-=5000;
                    } else {
                        $gift="Seltsamer Schädel";
                        $gefallen=e_rand(5,10);
                        $effekt="Du untersuchst dieses merkwürdige Geschenk genauer. Dabei rutscht es dir aus der Hand und zerplatzt am Boden in 1000 Stücke. Du hörst aber nicht mehr als ein Lachen und fegst traurig die Stücke weg.`n";
                        $session[user][gold]-=5000;
                    }
                        break;
                case "gift14":
                    $gift="Freibrief";
                    $effekt="`&Ein Brief der dir einmalig Straffreiheit gewährt und dich einmal aus dem Kerker befreien kann. Hebe ihn gut auf und setze ihn weise ein. Der ist von ".$session[user][name].".)";
                    db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Freibrief',$name,'Geschenk',12,'Ein Freibrief von ".$session[user][name].". Damit kommst du einmal aus dem Kerker frei.')");
                    $session['user']['gold']-=10000;
                    $session['user']['gems']-=10;
                    break;
                case "gift15":
                    $sq3 = "SELECT name,acctid FROM accounts WHERE acctid=".$name."";
                    $result3=db_query($sq3);
                    $row3 = db_fetch_assoc($result3);
                
                    $sql="SELECT * FROM items WHERE class='Geschenk' AND owner=".$row3[acctid]." AND name='Einladung zum Essen'";
                    $result=db_query($sql);
                    if (db_num_rows($result)) { 
                        output("`&Das geht leider nicht. ".$row3[name]."`& hat bereits eine Verabredung. Da war wohl jemand schneller als du!");      
                        addnav("Zurück","newgiftshop.php"); 
                        page_footer();
                    } else {
                        $gift="Einladung zum Essen";
                        $effekt="`&Einladung zu einem romantischen Abend mit ".$session[user][name].".')";
                        db_query("INSERT INTO items (name,owner,class,value1,value2,hvalue,gold,description) VALUES ('Einladung zum Essen', $name,'Geschenk',".$session[user][acctid].",$name,0,0,'Eine Einladung zu einem romantischen Abendessen mit ".$session[user][name].".')");
                        db_query("INSERT INTO items (name,owner,class,value1,value2,hvalue,gold,description) VALUES ('Einladung zum Essen', ".$session[user][acctid].",'Geschenk',$name,".$session[user][acctid].",0,0,'Ein romantisches Abendessen mit ".$row3[name].".')");
                           $session['user']['gold']-=1500;
                       }
                       break;
                  case "custom":
                      $gift= $_POST['giftName'];
                       $session['user']['gold']-=$kostgold;
                    $session['user']['gems']-=$kostgems;
                    
                    db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('$gift',$name, 'Einzigartiges',0,'Von ".$session[user][name].": $_POST[descr]')");
                      break;
            }
            
            $mailmessage=$session[user][name];
            
            if($_POST[op2]!= 'custom') {
            
                $mailmessage.="`7 hat dir ein Geschenk geschickt. Du öffnest es. Es ist ein/e `6";
                $mailmessage.=$gift;
                
                //you can change the following the match what you name your gift shop
                $mailmessage.="`7 aus dem Geschenkeladen.`n".$effekt;
            
            } else {
                
                $mailmessage.= "`7 hat dir ein Geschenk geschickt: `6";
                $mailmessage.= $gift."`0`n";
                
            }
            
            if($_POST[card]=="yes"){
                $mailmessage.="`7Es liegt eine Karte, die du erstmal liest bei:`n";
                $mailmessage.= $_POST[cardtext];
                $mailmessage.="`n`n";
            }
            
            if($_POST['descr'] != '') 
                    $mailmessage.= "Du öffnest das Geschenk und sieht aus wie folgt:`n".$_POST['descr'];
                     
            systemmail($name,"`2Geschenk erhalten!`2",$mailmessage);
            debuglog('Hat ein Geschenk namens '.$gift.' versendet an: ',$name);
        
            output("`rDein $gift wurde als Geschenk verschickt!");
            if (e_rand(1,3)==2){
                  output(" Bei der Wahl des Geschenks und dem liebevollen Verpacken vergisst du die Zeit und vertrödelst einen Waldkampf.");
                   $session[user][turns]--;
            } 
            addnav("Weiter","newgiftshop.php");
        break;
        
    default:
        if ($session[user][turns]<=0) {
            output("`rDer Geschenkeladen hat jetzt leider schon geschlossen.");
            addnav("Zurück zum Dorfplatz","village.php");
            checkday();
            page_footer();
        }
        
        output("`rDu betrittst den Geschenkeladen und siehst eine Menge einzigartiger Gegenstände.`n");
        output("Ein".($session[user][sex]?" junger Mann":"e junge Frau")." steht hinter der Ladentheke und lächelt dich sanft an.`n");
        output("Ein Schild an der Wand verspricht \"`iGeschenkverpackung und Lieferung frei.`i\"`n");
        // changed the next line to make sense for your gift shop (specialty)
        // output("`3This shop specializes in specialty, you see for sale...`n`n"); // only need one shop
        addnav("Tulpe","newgiftshop.php?op=send&op2=gift1");
        
        $sql="SELECT * FROM items WHERE class='Geschenk' AND owner=".$session[user][acctid]." AND name='Einladung zum Essen'";
        $result=db_query($sql);
    
        if ($session[user][gold] >= 10) addnav("Rose - 10 Gold","newgiftshop.php?op=send&op2=gift2");
        if ($session[user][gold] >= 20) addnav("Strauß Rosen - 20 Gold","newgiftshop.php?op=send&op2=gift3");
        if ($session[user][gold] >= 40) addnav("Pralinen - 40 Gold","newgiftshop.php?op=send&op2=gift4");
        if ($session[user][gold] >= 60) addnav("Freundschaftsbändchen - 60 Gold","newgiftshop.php?op=send&op2=gift5");
        if ($session[user][gold] >= 100) addnav("Freundschaftsanhänger - 100 Gold","newgiftshop.php?op=send&op2=gift6");
        if ($session[user][gold] >= 200) addnav("Halskette - 200 Gold","newgiftshop.php?op=send&op2=gift7");
        if ($session[user][gold] >= 500) addnav("Plüschdrachen - 500 Gold","newgiftshop.php?op=send&op2=gift8");
        if ($session[user][gold] >= 1000) addnav("Beutel Heilkräuter - 1000 Gold","newgiftshop.php?op=send&op2=gift9");
        if ($session[user][gold] >= 1500) addnav("Drachenei - 1500 Gold","newgiftshop.php?op=send&op2=gift10");
        if ($session[user][gold] >= 2000) addnav("Goldenes Amulett - 2000 Gold","newgiftshop.php?op=send&op2=gift11");
        if ($session[user][gold] >= 2000) addnav("Schutzamulett - 2000 Gold","newgiftshop.php?op=send&op2=gift13");
        if ($session[user][gold] >= 5000) addnav("Seltsamer Schädel - 5000 Gold","newgiftshop.php?op=send&op2=gift12");
        
       /* if (($session[user][gold] >= 10000) && ($session['user']['gems'] >= 10)) {
    addnav("Freibrief - 10000 Gold, 10 Edelsteine","newgiftshop.php?op=send&op2=gift14");
     }*/
         $dinnerPossible = file_exists("dinner.php");
         if ($dinnerPossible) {
            if (($session['user']['gold']>=1500) && !(db_num_rows($result))) addnav("Einladung zum Essen - 1500 Gold","newgiftshop.php?op=send&op2=gift15");
        }
         /* own gift */        if (($session['user']['gold'] >= $kostgold) && ($session['user']['gems'] >= $kostgems)) {            addnav("Eigenes");            addnav("Schenk was eigenes - $kostgold Gold und $kostgems Edelsteine","newgiftshop.php?op=send&op2=custom");       }           /*end own gift*/ 

         
         output("`n<ul><a href=\"newgiftshop.php?op=send&op2=gift1\">Tulpe</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift1");
        
        if ($session[user][gold] >= 10){
            output("<a href=\"newgiftshop.php?op=send&op2=gift2\">Rose - 10 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift2");
        }
        if ($session[user][gold] >= 20){
            output("<a href=\"newgiftshop.php?op=send&op2=gift3\">Strauß Rosen - 20 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift3");
        }
        if ($session[user][gold] >= 40){
            output("<a href=\"newgiftshop.php?op=send&op2=gift4\">Pralinen - 40 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift4");
        }
        if ($session[user][gold] >= 60){
            output("<a href=\"newgiftshop.php?op=send&op2=gift5\">Freundschaftsbändchen - 60 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift5");
        }
        if ($session[user][gold] >= 100){
            output("<a href=\"newgiftshop.php?op=send&op2=gift6\">Freundschaftsanhänger - 100 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift6");
        }
        if ($session[user][gold] >= 200){
            output("<a href=\"newgiftshop.php?op=send&op2=gift7\">Halskette - 200 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift7");
        }
        if ($session[user][gold] >= 500){
            output("<a href=\"newgiftshop.php?op=send&op2=gift8\">Plüschdrachen - 500 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift8");
        }
        if ($session[user][gold] >= 1000){
            output("<a href=\"newgiftshop.php?op=send&op2=gift9\">Beutel Heilkräuter - 1000 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift9");
        }
        if ($session[user][gold] >= 1500){
            output("<a href=\"newgiftshop.php?op=send&op2=gift10\">Drachenei - 1500 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift10");
        }
        if ($session[user][gold] >= 2000){
            output("<a href=\"newgiftshop.php?op=send&op2=gift11\">Goldenes Amulett - 2000 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift11");
        }
        
           $res=db_query("SELECT name,value1,id FROM items WHERE name='Amulett des Schutzes' AND owner='".$session[user][acctid]."'");
                    if(db_num_rows($res)){
                                                           
                                         }else{
         if ($session[user][gold] >= 2000){
            output("<a href=\"newgiftshop.php?op=send&op2=gift13\">Schutzamulett - 2000 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift13");
        }
        }
        if ($session[user][gold] >= 5000){
            output("<a href=\"newgiftshop.php?op=send&op2=gift12\">Seltsamer Schädel - 5000 Gold</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift12");
        }
       /* if (($session[user][gold] >= 10000) && ($session['user']['gems'] >= 10)) {
            output("<a href=\"newgiftshop.php?op=send&op2=gift14\">Freibrief - 10000 Gold, 10 Edelsteine</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=gift14");
        }*/
        if ($dinnerPossible) {
            if (($session['user']['gold'] >= 1500) && !(db_num_rows($result))) {
                output("<a href=\"newgiftshop.php?op=send&op2=gift15\">Einladung zum Essen - 1500 Gold</a><br>",true);
                addnav("","newgiftshop.php?op=send&op2=gift15");
            }
        }
        
        if (($session['user']['gold'] >= $kostgold) && ($session['user']['gems'] >= $kostgems)) {
            output("<a href=\"newgiftshop.php?op=send&op2=custom\">Was eigenes - $kostgold Gold, $kostgems Edelsteine</a><br>",true);
            addnav("","newgiftshop.php?op=send&op2=custom");
        }
addnav("Sonstiges");
    if (getsetting("activategamedate","0")>0){
        $cakecost=$session['user']['level']*15;
        addnav("Torte werfen ($cakecost Gold)","newgiftshop.php?op=cake");
    }
        output("</ul>",true);
        // change this nav to return to the location you call it from
        addnav("Zurück zum Dorfplatz","village.php");
        break;

}
if ($_GET['op']=="cake"){ // this part was done for claymore's birthday :)
    if (!isset($_POST['throw'])){
        $wer=getsetting("cakevip","");
        $geb = explode('-',getsetting('gamedate','0000-01-01'));
        $find = array('%Y','%y','%m','%n','%d','%j');
        $replace = array('','',sprintf('%02d',$geb[1]),(int)$geb[1],sprintf('%02d',$geb[2]),(int)$geb[2]);
        $geb = str_replace($find,$replace,getsetting('gamedateformat','%Y-%m-%d'));
        $result=db_query("SELECT login,name FROM accounts WHERE locked=0 AND birthday LIKE '%$geb%' AND acctid<>".$session[user][acctid]." ORDER BY login ASC");
        if ($wer=="" && db_num_rows($result)<=0){
            output("`r".($session[user][sex]?"Der Mann":"Die Frau")." hinter dem Ladentisch schaut dich verwirrt an, denn heute, $geb, ist für niemanden ein besonderer Tag. Das Bewerfen mit Torten könnte an Tagen, an denen das Ziel nicht Geburtstag hat, von diesem als Angriff gewertet werden. ");
            output("Darum sollte man auf diesen Brauch an normalen Tagen besser verzichten.`nEnttäuscht wendest du dich ab.");
            addnav("Zurück in den Garten","gardens.php");
        }else{
            output("`r".($session[user][sex]?"Der Mann":"Die Frau")." hinter dem Ladentisch setzt ein breites Grinsen auf und fragt dich, welches der Geburtstagskinder du gerne mit einer Torte beglücken möchtest, wie es hier Brauch ist.`n`n");
            output("<form action='newgiftshop.php?op=cake' method='POST'>`rZielperson: <select name='throw'>",true);
            if ($wer!="") output("<option value='".rawurlencode($wer)."'>$wer</option>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value='".rawurlencode($row['login'])."'>".preg_replace("'[`].'","",$row['name'])."</option>",true);
            }
            output("</select>`n`n<input type='submit' class='button' value='Torte kaufen'></form>",true);
            addnav("","newgiftshop.php?op=cake");
        }
    }else{
        $result=db_query("SELECT acctid,name,sex FROM accounts WHERE login='$_POST[throw]' LIMIT 1");
        if (db_num_rows($result)<=0){
            output("`r $_POST[throw]s Geburtstag ist entweder schon vorbei, oder es gibt ihn gar nicht.`nEnttäuscht wendest du dich ab.");
            addnav("Zurück in den Garten","gardens.php");
        }else if ($session[user][witch]>0){
            output("`rDu kannst heute leider keine Torte mehr werfen.");
            addnav("Zurück in den Garten","gardens.php");
        }else if ($session[user][gold]<$session[user][level]*15){
            output("`rDu hast nicht genug Gold für diesen Spaß dabei.");
            addnav("Zurück in den Garten","gardens.php");
        }else{
            $row = db_fetch_assoc($result);
            $result2=db_query("SELECT * FROM items WHERE class='Geschenk' AND owner=$row[acctid] AND name='Tortenreste'");
            $torte=e_rand(1,7);
            if (db_num_rows($result2)>0) $item = db_fetch_assoc($result2);
            if (db_num_rows($result2)>0) $torte=$item[value2];
            switch($torte){
                case 1:
                $wie="große und saftige";
                break;
                case 2:
                $wie="ganz süße";
                break;
                case 3:
                $wie="schokoladige";
                break;
                case 4:
                $wie="besonders sahnige";
                break;
                case 5:
                $wie="möglichst harte";
                break;
                case 6:
                $wie="besonders kalorienreiche";
                break;
                case 7:
                $wie="besonders klebrige";
            }
            $item[hvalue]++;
            $buff = array("name"=>"`rTortenreste`0","rounds"=>15,"wearoff"=>"`REinige der Tortenreste fallen von dir ab.`0","roundmsg"=>"`RTortenreste bremsen die Angriffe deines Gegners.`0","defmod"=>1.1,"activate"=>"roundstart");
            $buff=serialize($buff);
            output("`rDu suchst dir eine $wie Torte für `&$row[name]`r aus. Damit bewaffnet machst du dich auf die Suche nach `&$row[name]`r und als du ".($row[sex]?"sie":"ihn")." gefunden hast...`n`n");
            output("`&`b`c<font size='5'>*PLATSCH*</font>`b`c`r`n",true);
            output("`r...wirfst du sie ".($row[sex]?"ihr":"ihm")." mitten ins Gesicht und brüllst ".($row[sex]?"ihr":"ihm")." ein fröhliches `b`RHAPPY BIRTHDAY`b`r entgegen.`n`n");
            output("`&$row[name]`r wird an den Tortenresten sicher noch lange ".($row[sex]?"ihre":"seine")." Freude haben. ".($row[sex]?"Sie":"Er")." trägt damit die klebrigen Reste von $item[hvalue] Torten an sich herum." );
            if (db_num_rows($result2)>0){
                $sql="UPDATE items SET hvalue=$item[hvalue] WHERE class='Geschenk' AND owner=$row[acctid] AND name='Tortenreste'";
            }else{
                $sql="INSERT INTO items (name,owner,class,gold,description,value2,hvalue,buff) VALUES ('Tortenreste',$row[acctid],'Geschenk',0,'Tortenreste von $item[hvalue] Geburtstagstorten kleben an dir.',$torte,$item[hvalue],'$buff')";
                systemmail($row[acctid],"`rAchtung! Torte!","`&`bPLATSCH!`b`r Zu spät.`nDu wurdest von deiner ersten Geburtstagstorte getroffen... weitere werden heute bestimmt noch folgen. Es ist eine $wie Sorte. (Die genaue Anzahl siehst du in deinem Inventar.)");
            }
            db_query($sql);
            $session[user][reputation]+=2;
            $session[user][witch]++;
            $session[user][gold]-=(15*$session[user][level]);
            addnav("Zurück ins Dorf","village.php");
        }
    }
    addnav("Zurück zum Laden","newgiftshop.php");
}
checkday();
page_footer();
?>