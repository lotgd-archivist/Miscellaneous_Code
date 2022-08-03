<?php



/* Ein Freudenhaus *fg*

   Von : Taikun

   Domain: www.logd-midgar.de



-- 

--   SQL:

-- 

   CREATE TABLE `frdn` (

   `acctid` int(11) unsigned NOT NULL default '0',

  `id` int(10) unsigned NOT NULL auto_increment,

  `kosten` int(11) unsigned NOT NULL default '0',

  `user` varchar(200) NOT NULL default '0',

 `sex` tinyint(4) unsigned NOT NULL default '0',

 `raum` varchar(200) NOT NULL default '0',

  PRIMARY KEY  (`id`(,

  UNIQUE KEY `name` (`name`(

) TYPE=MyISAM AUTO_INCREMENT=1 ;



-- 

-- SQL Accounts:

-- 

ALTER TABLE accounts

ADD `room2` enum('0','1') NOT NULL default '0',

ADD `roomid2` INT ( 11 ) NOT NULL default '';





Version 0.3

FIXES o. Adds

*****
Admins können Räume löschen.
*****




*/



// Anfangssachen :)

// #############################################

require_once "common.php";

checkday();

page_header("Das Ferienhaus");

addcommentary();



$stadt = "Arda";

$verw = "10";

$start = "0:01";

$end  = "0:00";

$sql = "SELECT name, sex FROM accounts";

$result = db_query($sql);

$row = db_fetch_assoc($result);



switch($_GET['op']):

// #############################################

// Altersabfrage

// #############################################


// #############################################


// Anfangstext + Navigation

// #################################################################################################################################################################

case "";





/*if (date('H:i') >= $start && date('H:i') <= $end) { */



if($session['user']['room2']==1){

        output("`@Bitte beachtet, dass wenn Ihr in das Zimmer geht, erst wieder rausgehen könnt, wenn Ihr fertig seid. Also wartet ab, bis sich jemand gefunden hat, der sich zu Euch gesellt.`n`n`n");

        viewcommentary("frdn","Mit den Anderen reden",15);

        output("`n`n`YEine adrett gekleidete Dame lässt dich wissen, daß dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");

        addnav("Ins Zimmer","frdn.php?op=room&id=".$_GET['id']."");

        addnav("Zurück ins Dorf","sanela.php");

} else {

output("`@Willkommen in der Ferienhausanlage von ".$stadt.", ".($row['sex']?"werte":"werter")." ".$session['user']['name'].".

`@Hier könnt ihr mit eurem Liebsten ein paar ruhige Stunden, fernab des Trubels der Stadt, ungestört verbringen. Ihr könnt euch zu eurem Liebsten gesellen oder selbst ein eigenes Haus mieten.`n

Wenn Ihr selbst ein Haus mieten möchtet, kostet dies `^".$verw." Gold.`n`n`n");

viewcommentary("frdn","Mit den Anderen reden:",25);

output("`n`n`4Eine adrett gekleidete Dame lässt dich wissen, daß dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");

addnav("Ein Zimmer wählen","frdn.php?op=ver");

if($session['user']['gold']>=$verw) {

//addnav("Dich Anbieten","frdn.php?op=anbiet");
addnav("Haus mieten","frdn.php?op=miet");
}

addnav("Zurück ins Dorf","sanela.php");

}

 /*else {



        output("`@Leider hat das Freudenhaus ".$stadt."s immoment geschlossen. Besucht uns ab 21 Uhr.`n");

viewcommentary("frdn","Mit den Anderen reden:",25);

output("`n`n`YEine leicht bekleidete Frau lässt dich wissen das dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");

        addnav("Zurück ins Dorf","sanela.php");


}*/

break;

// ############################################################################################################################################################





// Raum für *peep* :D

// ####################################################

case "room";

addcommentary();

output("`@Das schöne Zimmer dieses Ferienhaus schmücken schöne Bilder an einer Wand, auf der rechten Seite steht ein schönes grosses Bett, indem Ihr Euch austoben könnt...`n`n");

viewcommentary("frdn-".$session['user']['roomid2'],"Hinzufügen",25);

output("`n`n`YEine adrett gekleidete Dame lässt dich wissen, daß dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");



addnav("RP Beenden","frdn.php?op=beend");

addnav("Zurück ins Dorf","sanela.php");



break;



// ############################################



// RP beenden

// #####################



case "beend";

output("Du kannst nun wieder in ein neues Zimmer bzw. dir wieder eines der Häuser mieten.");

addnav("Zurück zum Eingang","frdn.php");

$session['user']['room']=0;

$session['user']['roomid']=0;

break;





// ############################################



// Gold u. Zimmer ID

// ######################################################################################################

case "miet";



addnav("Zurück zum Ferienhaus","frdn.php");

output("<form action=\"frdn.php?op=send\" method='post'>Bitte gebe an, wie das Zimmer heissen soll.:<br><br>",true);

output("<table><tr><td>Kosten:</td><td>Zimmername:</td></tr>",true);

//output("<td valign=top><input name='kosten' size='15'></td>",true);

output("<td valign=top><input name='raum' size='30'></td>",true);

// output("<td valign=top><input name='id' size='10'></td>",true);

output("</table><input type='submit' value='Abschicken'></form>",true);

addnav("","frdn.php?op=send");

output("`n");

break;

// ######################################################################################################



// Absenden und Eintragen

// ######################################################################################################

case "send";

if ($_POST['kosten']!="" && $_POST['raum']!=""){

$sql = "INSERT INTO frdn (acctid,user,sex,raum) VALUES ('".$session['user']['acctid']."','".$session['user']['name']."','".$session['user']['sex']."','".$_POST['raum']."')";

$result = db_query($sql) or die(db_error(LINK));

$id = db_insert_id(LINK);

$session['user']['gold']-=$verw;

$session['user']['room2']=1;

// $session['user']['house'] = $houseid;

//$session['user']['roomid'] = $_POST['id'];

$session['user']['roomid2'] = $id;

output("Erfolgreich eingetragen. Ihr müsst jetzt nurnoch warten, bis sich jemand meldet.");

addnav("Zurück ins Dorf","sanela.php");

}

else if ($_POST["raum"]==""){

        output("Bitte alle Felder ausfüllen.");

        addnav("Zurück","frdn.php?op=anbiet");



}

/*else if($_POST['kosten']>"20000"){

output("Bitte einen Preis unter 20000 Gold wählen.");

addnav("Zurück","frdn.php?op=anbiet");

addnav("Zurück ins Dorf","sanela.php");

}   */

break;

// ######################################################################################################





// 'Partner' auswählen

// ######################################################################################################

case "ver";

output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Zimmername</td><td>Name</td><td>Nummer</td>",true);
if ($session['user']['superuser']>0){
output("<td>Aktion</td></tr>",true);
}

    $sql = "SELECT id,user,raum,sex FROM frdn";

    $row = db_fetch_assoc($result);

    $result = db_query($sql) or die(db_error(LINK));



if (db_num_rows($result)==0){



    output("<tr class='trdark'><td colspan=5 align='center'>`&`iDerzeit gibt es keine Ferienhäuser!`i`0</td></tr>",true);

addnav("Zurück","frdn.php");

} else {

        addnav("Doch lieber nicht","frdn.php");

while ($row = db_fetch_assoc($result)) {



        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');

        output("<tr class='".$bgclass."'><td><a href='frdn.php?op=getroom&id=".$row['id']."' onClick='return confirm(\"Willst du wirklich in dieses Ferienhaus?\");'>".$row['raum']."</a></td><td>".$row['user'],true);

    output("</td><td>".$row['kosten']."</td><td>".$row['id']."</td>",true);
    if ($session['user']['superuser']>0){
    output("<td><a href='frdn.php?op=zimmdel&id=".$row['id']."'>Löschen</a></td>",true);
}

    addnav("","frdn.php?op=getroom&id=".$row['id']);
addnav("","frdn.php?op=zimmdel&id=".$row['id']);
}

}

output("</table>",true);

output('</form>',true);



break;

// ######################################################################################################





// 'Partner' und Zimmer bekommen.

// ######################################################################################################

case "getroom";

$sql2 = "SELECT * FROM frdn WHERE id='$_GET[id]'";

$result2 = db_query($sql2);

$row = db_fetch_assoc($result2);

//$gold = $row['kosten'];

$id = $row['id'];

$acctid = $row['acctid'];



//if($session[user][gold]>=$row[kosten]){

output("Du kannst nun in das Zimmer.");

addnav("Zurück","frdn.php");

$session['user']['roomid'] = $row['id'];

//$session['user']['gold'] -= $gold;

$session['user']['room']=1;

//$sql = "UPDATE accounts SET goldinbank = goldinbank+$row[kosten] WHERE acctid='{$row['acctid']}'";

db_query($sql) or die(db_error(LINK));

$sql9 = "INSERT INTO mail (msgfrom,msgto,subject,body,sent) VALUES ('`System`0','$row[acctid]','`^Ferienhaus!`0','`&{$session['user']['name']}`6 hat sich für ein Ferienhaus bei Dir beworben!',now())";

db_query($sql9);

$sql2="DELETE FROM frdn WHERE id='$_GET[id]'";

$result2=db_query($sql2);



/*else if($session[user][gold]<$row[kosten]){

output("Das kannst Du Dir garnicht leisten!");

 addnav("Zurück","frdn.php");

}*/





break;

// ######################################################################################################

// Zimmer löschen "ja o. nein?"

//##############

case "zimmdel";


output("Willst du das Zimmer wirklich löschen?");
addnav("Ja","frdn.php?op=zimmdel1&id=".$_GET['id']."");
addnav("Nein","frdn.php");

break;

//##############

// Zimmer löschen

//##############

case "zimmdel1";

$sql3 = "DELETE FROM frdn WHERE id='$_GET[id]'";

$result3 = db_query($sql3);


output("Du hast das Zimmer ".$_GET['id']." erfolgreich gelöscht.");
addnav("Zurück","frdn.php");


break;
//##############

// Abschlusszeug

//##############

endswitch;

page_footer();

//###############

?> 