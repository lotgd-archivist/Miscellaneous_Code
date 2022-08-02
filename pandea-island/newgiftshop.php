<?php

/* 30062004

created by Lonny Luberts for http://www.pqcomp.com/logd, built on idea from quest's giftshop with all new code.
this file needs customization before use and is designed to be added in many places if need be
as different gift shops.
search and replace (newgiftshop.php) with what you name the giftshop php file
search and replace (gift 1)-(your gift) with your gifts - make sure you use the space inbetween gift & 1 etc...
if you do an auto replace with your editor.
be sure to edit the return nav
please feel free to use and edit this file, any major upgrades or improvements should be
mailed to logd@pqcomp.com for consideration as a permenant inclusion
please do not remove the comments from this file.
Version: 03212004
changes to fit ext (GER) and translation by anpera
added items with buffs
modifications by ang37@arcor.de
*/

require_once "common.php";
checkday();
page_header("Geschenkeladen");
//checkevent();
output("`c`b`&Geschenkeladen`0`b`c`n`n");
if ($_GET[op]=="" && $session[user][turns]>0){
    output("`rDu betrittst den Geschenkeladen und siehst eine Menge einzigartiger Gegenstände.`n");
    output("Ein".($session[user][sex]?" junger Mann":"e junge Frau")." steht hinter der Ladentheke und lächelt dich sanft an.`n");
    output("Ein Schild an der Wand verspricht \"`iGeschenkverpackung und Lieferung frei.`i\"`n");
    // changed the next line to make sense for your gift shop (specialty)
    // output("`3This shop specializes in specialty, you see for sale...`n`n"); // only need one shop
    addnav("Zurück zum Garten","gardens.php");
    addnav("---------Ware---------");
    addnav("Tulpe","newgiftshop.php?op=send&op2=gift1");
    if ($session[user][gold] >= 10){
        addnav("10 Gold");
        addnav("Rose","newgiftshop.php?op=send&op2=gift2");
    }if ($session[user][gold] >= 20){
        addnav("20 Gold");
        addnav("Strauß Rosen","newgiftshop.php?op=send&op2=gift3");
    }if ($session[user][gold] >= 40){
        addnav("40 Gold");
        addnav("Pralinen","newgiftshop.php?op=send&op2=gift4");
    }if ($session[user][gold] >= 60){
        addnav("60 Gold");
        addnav("Freundschaftsbändchen","newgiftshop.php?op=send&op2=gift5");
    }if ($session[user][gold] >= 100){
        addnav("100 Gold");
        addnav("Freundschaftsanhänger","newgiftshop.php?op=send&op2=gift6");
    }if ($session[user][gold] >= 200){
        addnav("200 Gold");
        addnav("Halskette","newgiftshop.php?op=send&op2=gift7");
    }if ($session[user][gold] >= 500){
        addnav("500 Gold");
        addnav("Plüschdrachen","newgiftshop.php?op=send&op2=gift8");
    }if ($session[user][gold] >= 1000){
        addnav("1000 Gold");
        addnav("Beutel Heilkräuter","newgiftshop.php?op=send&op2=gift9");
        addnav("Freundschaftsring ","newgiftshop.php?op=send&op2=gift13");
    }if ($session[user][gold] >= 1500){
        addnav("1500 Gold");
        addnav("Drachenei","newgiftshop.php?op=send&op2=gift10");
    }if ($session[user][gold] >= 2000){
        addnav("2000 Gold");
        addnav("Goldenes Amulett","newgiftshop.php?op=send&op2=gift11");
    }if ($session[user][gold] >= 3000){
        addnav("3000 Gold");
        addnav("Seltsamer Schädel","newgiftshop.php?op=send&op2=gift12");
    }
    output("`n<ul><a href=\"newgiftshop.php?op=send&op2=gift1\">Tulpe<br></a><br>",true);
    addnav("","newgiftshop.php?op=send&op2=gift1");
    if ($session[user][gold] >= 10){
        output("`@10 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift2\">Rose</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift2");
        output("`n");
    }
    if ($session[user][gold] >= 20){
        output("`@20 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift3\">Strauß Rosen</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift3");
        output("`n");
    }
    if ($session[user][gold] >= 40){
        output("`@40 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift4\">Pralinen</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift4");
        output("`n");
    }
    if ($session[user][gold] >= 60){
        output("`@60 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift5\">Freundschaftsbändchen</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift5");
        output("`n");
    }
    if ($session[user][gold] >= 100){
        output("`@100 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift6\">Freundschaftsanhänger</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift6");
        output("`n");
    }
    if ($session[user][gold] >= 200){
        output("`@200 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift7\">Halskette</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift7");
        output("`n");
    }
    if ($session[user][gold] >= 500){
        output("`@500 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift8\">Plüschdrachen</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift8");
        output("`n");
    }
    if ($session[user][gold] >= 1000){
        output("`@1000 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift9\">Beutel Heilkräuter</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift9");
        output("<a href=\"newgiftshop.php?op=send&op2=gift13\">Freundschaftsring</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift13");
        output("`n");
    }
    if ($session[user][gold] >= 1500){
        output("`@1500 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift10\">Drachenei</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift10");
        output("`n");
    }
    if ($session[user][gold] >= 2000){
        output("`@2000 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift11\">Goldenes Amulett</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift11");
        output("`n");
    }
    if ($session[user][gold] >= 3000){
        output("`@3000 Gold`n");
        output("<a href=\"newgiftshop.php?op=send&op2=gift12\">Seltsamer Schädel</a><br>",true);
        addnav("","newgiftshop.php?op=send&op2=gift12");
        output("`n");
    }
    output("</ul>",true);
    // change this nav to return to the location you call it from
}else if ($session[user][turns]<=0){
    output("`rDer Geschenkeladen hat jetzt leider schon geschlossen.");
    addnav("Zurück zum Garten","gardens.php");
}
if ($_GET[op]=="send"){
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
/*    $sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND lastip<>'".$session[user][lastip]."' AND charm>1 ORDER BY (acctid='".$session['user']['marriedto']."' OR acctid='".$session['user']['friend1']."'
     OR acctid='".$session['user']['friend2']."' OR acctid='".$session['user']['friend3']."' OR acctid='".$session['user']['friend4']."' OR acctid='".$session['user']['friend5']."' OR acctid='".$session['user']['friend6']."' OR acctid='".$session['user']['friend7']."'
      OR acctid='".$session['user']['friend8']."' OR acctid='".$session['user']['friend9']."' OR acctid='".$session['user']['friend10']."') DESC,login,level LIMIT $limit";
*/
    $sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND acctid<>".$session[user][acctid]." AND lastip<>'".$session[user][lastip]."' AND charm>1 ORDER BY (acctid='".$session['user']['marriedto']."' OR acctid IN (SELECT friend FROM friendlist WHERE acctid=".$session['user']['acctid'].") ) DESC,login,level LIMIT $limit";


    $result = db_query($sql);
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","newgiftshop.php?op=send&op2=$gift&limit=".($page+1)."&search=$_POST[search]");
    output("`rWem willst du das Geschenk schicken?`n`n");
    output("<form action='newgiftshop.php?op=send&op2=$gift' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
    addnav("","newgiftshop.php?op=send&op2=$gift");
    output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='newgiftshop.php?op=sendcard&op2=$gift&name=".HTMLEntities($row['acctid'])."'>",true);
        output($row['name']);
        output("</a></td><td>",true);
        output($row['level']);
        output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);
        addnav("","newgiftshop.php?op=sendcard&op2=$gift&name=".HTMLEntities($row['acctid']));
    }
    output("</table>",true);
    addnav("Zurück zum Laden","newgiftshop.php");
}
if ($_GET[op]=="sendcard")
{
    $name=$_GET[name];
    $gift=$_GET[op2];
    output("Während dein Geschenk verpackt wird, wird dir angeboten eine Grußkarte mit dem Geschenk zu verschicken");
    if ($gift=="gift1"){
        $kosten=0;
    }
    if ($gift=="gift2"){
        $kosten=10;
    }
    if ($gift=="gift3"){
        $kosten=20;
    }
    if ($gift=="gift4"){
        $kosten=30;
    }
    if ($gift=="gift5"){
        $kosten=40;
    }
    if ($gift=="gift6"){
        $kosten=100;
    }
    if ($gift=="gift7"){
        $kosten=200;
    }
    if ($gift=="gift8"){
        $kosten=500;
    }
    if ($gift=="gift9" || $gift=="gift13"){
        $kosten=1000;
    }
    if ($gift=="gift10"){
        $kosten=1500;
    }
    if ($gift=="gift11"){
        $kosten=2000;
    }
    if ($gift=="gift12"){
        $kosten=3000;
    }
    $kostenges=$kosten+200;
    if ($session[user][gold] < $kostenges)
    {
        output("Dies würde allerdings weitere 200 Goldstücke kosten die du nicht bei dir hast. Möchtest du das Geschenk trotzdem verschicken?");
        addnav("Ja - verschicken","newgiftshop.php?op=send2&op2=$gift&name=$name&card=no");
        addnav("Nein - Zurück in den Garten","gardens.php");
    }
    else
    {
        output("Dies würde allerdings weitere 200 Goldstücke kosten. Möchtest du eine Karte?");
        addnav("Ja","newgiftshop.php?op=sendcard2&op2=$gift&name=$name");
        addnav("Nein ohne Karte verschicken","newgiftshop.php?op=send2&op2=$gift&name=$name&card=no");
    }
}
if ($_GET[op]=="sendcard2"){
    $name=$_GET[name];
    $gift=$_GET[op2];
    output("Dir wird die Karte und ein Stift gereicht auf dem du die Nachricht verfassen kannst.");
    output("<form action='newgiftshop.php?op=send2&op2=$gift&name=$name&card=yes' method='POST'>Dein Text: <input name='karte' value='$_POST[karte]'><input type='submit' class='button' value='Senden'></form>",true);
    addnav("Doch kein Geschenk verschicken","gardens.php");
    addnav("","newgiftshop.php?op=send2&op2=$gift&name=$name&card=yes");
}
if ($_GET[op]=="send2"){
    $name=$_GET[name];
    $effekt="";
    if ($_GET[op2]=="gift1"){
        $gift="Tulpe";
    }
    if ($_GET[op2]=="gift2"){
        $gift="Rose";
        $session[user][gold]-=10;
    }
    if ($_GET[op2]=="gift3"){
        $gift="Strauß Rosen";
        $session[user][gold]-=20;
    }
    if ($_GET[op2]=="gift4"){
        $gift="Schachtel Pralinen";
        $effekt="Natürlich futterst du sie sofort alle auf.";
        $session[user][gold]-=40;
    }
    if ($_GET[op2]=="gift5"){
        $gift="Freundschaftsbändchen";
        db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Freundschaftsbändchen',$name,'Geschenk',12,'Ein Freundschaftsbändchen von ".$session[user][name]."')");
        $session[user][gold]-=60;
    }
    if ($_GET[op2]=="gift6"){
        $gift="Freundschaftsanhänger";
        db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Freundschaftsanhänger',$name,'Geschenk',20,'Ein Freundschaftsänhänger von ".$session[user][name]."')");
        $session[user][gold]-=100;
    }
    if ($_GET[op2]=="gift7"){
        $gift="Halskette";
        db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Halskette',$name,'Geschenk',40,'Diese Halskette hat dir ".$session[user][name]." geschenkt.')");
        $session[user][gold]-=200;
    }
    if ($_GET[op2]=="gift8"){
        $gift="Plüschdrachen";
        $effekt="`RDer ist ja soooooo süüüüüüüüüüüssss!!!`0";
        db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Plüschdrachen',$name,'Geschenk',100,'`REin `@grüner Drachen`R aus Plüsch zum Kuscheln. Der ist von ".$session[user][name].".')");
        $session[user][gold]-=500;
    }
    if ($_GET[op2]=="gift9"){
        $gift="Beutel Heilkräuter";
        $buff = array("name"=>"`1Heilkräuter","rounds"=>20,"wearoff"=>"`1Die Heilkräuter verlieren ihre Wirkung!`0","regen"=>1.2,"roundmsg"=>"`1Die Heilkräuter heilen deine Wunden.`0","activate"=>"defense");
        $buff=serialize($buff);
        $effekt="Diese Heilkräuter heilen Wunden, bleiben aber nicht lange frisch.";
        db_query("INSERT INTO items (name,owner,class,gold,description,hvalue,buff) VALUES ('Beutel Heilkräuter',$name,'Geschenk',500,'$effekt',1,'$buff')");
        $session[user][gold]-=1000;
    }
    if ($_GET[op2]=="gift10"){
        $gift="Drachenei";
        $session[user][gold]-=1500;
    }
    if ($_GET[op2]=="gift11"){
        $gift="Goldenes Amulett";
        $buff = array("name"=>"`rAmulettaura`0","rounds"=>10,"wearoff"=>"`rDie Aura des Amuletts verschwindet.`0","defmod"=>1.1,"activate"=>"roundstart");
        $buff=serialize($buff);
        $effekt="Als du das Amulett anlegst, hüllt es dich in eine merkwürdige, schützende Aura. Der Beipackzettel verrät dir, dass das Amulett nach 3 Tagen seine Wirkung verliert und zu Staub zerfallen wird.";
        db_query("INSERT INTO items (name,owner,class,gold,description,hvalue,buff) VALUES ('Goldenes Amulett',$name,'Geschenk',1000,'$effekt',3,'$buff')");
        $session[user][gold]-=2000;
    }
    if ($_GET[op2]=="gift12"){
        $gift="Seltsamer Schädel";
        $gefallen=e_rand(5,10);
        $effekt="Du untersuchst dieses merkwürdige Geschenk genauer. Dabei rutscht es dir aus der Hand und zerplatzt am Boden in 1000 Stücke. Doch eine seltsame Kraft wird frei, die dir $gefallen Gefallen bei Ramius bringt!";
        db_query("UPDATE accounts SET deathpower=deathpower+$gefallen WHERE acctid=$name");
        $session[user][gold]-=3000;
    }
    if ($_GET[op2]=="gift13"){
        $gift="Freundschaftsring";
        $buff = array("name"=>"`yRing der Freundschaft`0","rounds"=>5,"wearoff"=>"`yDie magische Hilfe des Rings schwindet.`0","defmod"=>1.1,"activate"=>"roundstart");
        $buff=serialize($buff);
        $effekt="`yDu steckst den Ring an und siehst, wie sich dein Name in die Struktur einbrennt.";
        db_query("INSERT INTO items (name,owner,class,gold,description,hvalue,buff) VALUES ('Freundschaftsring',$name,'Geschenk',1000,'$effekt',3,'$buff')");
        $session[user][gold]-=1000;
    }
    $mailmessage=$session[user][name];
    $mailmessage.="`7 hat dir ein Geschenk geschickt.  Du öffnest es. Es ist ein/e `6";
    $mailmessage.=$gift;
    //you can change the following the match what you name your gift shop
    $mailmessage.="`7 aus dem Geschenkeladen.`n".$effekt;
    if($_GET[card]=="yes"){
        $mailmessage.="`7Es liegt außerdem eine kleine Grußkarte dabei: `n`n";
        $mailmessage.= $_POST[karte];
        $mailmessage.="`n";
        $session[user][gold]-=200;
    }
    systemmail($name,"`2Geschenk erhalten!`2",$mailmessage);
    output("`rDein(e) $gift wurde als Geschenk verschickt!");
    if (e_rand(1,3)==2){
        output(" Bei der Wahl des Geschenks und dem liebevollen Verpacken vergisst du die Zeit und vertrödelst einen Waldkampf.");
        $session[user][turns]--;
    }
    addnav("Weiter","newgiftshop.php");
}
page_footer();
?>