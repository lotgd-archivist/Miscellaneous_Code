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

ADD `room` enum('0','1') NOT NULL default '0',

ADD `roomid` INT ( 11 ) NOT NULL default '';





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

page_header("Das Freudenhaus");

addcommentary();



$stadt = "Zylyma";

$verw = "1";

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



if($session['user']['room']==1){

        output("`@Bitte beachtet, dass wenn Ihr in das Zimmer geht, erst wieder rausgehen könnt, wenn Ihr fertig seid. Also wartet ab, bis sich jemand gefunden hat, der sich zu Euch gesellt.`n`n`n");

        viewcommentary("frdn","Mit den Anderen reden",15);

        output("`n`n`YEine leicht bekleidete Frau lässt dich wissen das dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");

        addnav("Ins Zimmer","frdnhaus.php?op=room&id=".$_GET['id']."");

        addnav("Zurück ins Dorf","marktplatz.php");

} else {

output(" `c`eDas `WFr`eeu`\$den`eha`Wus`n`n
`WLa`4ng`esam betrittst du diesen Ort. Verlockende Düfte schlagen dir ins Ges`4ic`Wht.`n
`WDi`4e d`eunkle, verfüherische Stimmung des Ortes nimmt dich sofort in Be`4si`Wtz.`n
`WDa`4s L`eicht ist eher spärlich platziert, doch lässt es die Räume nicht düster wi`4rk`Wen.`n
`WDa`4s e`erste, was du siehst, ist die Bar und die wunderschöne, leicht bekleidete Frau dahi`4nt`Wer.`n
`WMa`4gi`esch scheint sie dich anzuziehen, doch je näher du ko`4mm`Wst,`n
`Wde`4st`eo deutlicher wird dir, daß sie nicht die Person ist, die du hier mieten ka`4nn`Wst.`n
`WBo`4a h`eeißt dich herzlich Willko`4mm`Wen.`n
`WSi`4e l`eeitet diesen Laden und alle Gesch`4äf`Wte.`n
`WIh`4re `esanfte Stimme zählt dir nun deine Möglichkeite`4n a`Wuf.`n
\"`WHa`4ll`eo Fremder. Herzlich Willkommen im Freuden`4ha`Wus.`n
`WHi`4er `ewerden dir alle Wünsche und Träume erf`4ül`Wlt.`n
`WNa`4tü`erlich nur gegen Bares, verstehst `4si`Wch.`n
`WDu `4ka`ennst auch dich anbieten, wenn du das wi`4ll`Wst.`n
`WDe`4r G`eewinn wird zur Hälfte get`4ei`Wlt.\"`c`n`n");

viewcommentary("frdn","Mit den Anderen reden:",25);

output("`n`n`4Eine leicht bekleidete Frau lässt dich wissen das dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");

addnav("Ein Zimmer wählen","frdnhaus.php?op=ver");

if($session['user']['gold']>=$verw) {

//addnav("Dich Anbieten","frdnhaus.php?op=anbiet");
addnav("Dich Anbieten","frdnhaus.php?op=anbiet");
}

addnav("Zurück ins Dorf","marktplatz.php");

}

 /*else {



        output("`@Leider hat das Freudenhaus ".$stadt."s immoment geschlossen. Besucht uns ab 21 Uhr.`n");

viewcommentary("frdn","Mit den Anderen reden:",25);

output("`n`n`YEine leicht bekleidete Frau lässt dich wissen das dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");

        addnav("Zurück ins Dorf","marktplatz.php");


}*/

break;

// ############################################################################################################################################################





// Raum für *peep* :D

// ####################################################

case "room";

addcommentary();

output("`@Das schöne Zimmer dieses Freudenhaus schmücken schöne Bilder an einer Wand, auf der rechten Seite steht ein schönes grosses Bett, indem Ihr Euch austoben könnt...`n`n");

viewcommentary("frdn-".$session['user']['roomid'],"Hinzufügen",25);

//output("`n`n`YEine leicht bekleidete Frau lässt dich wissen das dies ein Ort des Rollenspiels ist und du dich bitte an die Rpg-Regeln halten sollst.");



addnav("RP Beenden","frdnhaus.php?op=beend");

addnav("Zurück ins Dorf","marktplatz.php");



break;



// ############################################



// RP beenden

// #####################

case "beend";

output("Wirklich beenden?");
addnav("Ja, beenden","frdnhaus.php?op=beend2");
addnav("Nein, nur zum Ausgang...","frdnhaus.php");
break;

case "beend2";

output("Du kannst nun wieder in ein neues Zimmer bzw. Dich wieder selber anbieten.");

addnav("Zurück zum Eingang","frdnhaus.php");

$session['user']['room']=0;

$session['user']['roomid']=0;

break;





// ############################################



// Gold u. Zimmer ID

// ######################################################################################################

case "anbiet";



addnav("Zurück zum Freudenhaus","frdnhaus.php");

output("<form action=\"frdnhaus.php?op=send\" method='post'>Bitte gebe an, für wieviel Gold Du Dich verkaufen möchtest und das Zimmer heissen soll.:<br><br>",true);

output("<table><tr><td>Kosten:</td><td>Zimmername:</td></tr>",true);

output("<td valign=top><input name='kosten' size='15'></td>",true);

output("<td valign=top><input name='raum' size='30'></td>",true);

// output("<td valign=top><input name='id' size='10'></td>",true);

output("</table><input type='submit' value='Abschicken'></form>",true);

addnav("","frdnhaus.php?op=send");

output("`n");

break;

// ######################################################################################################



// Absenden und Eintragen

// ######################################################################################################

case "send";

if ($_POST["kosten"]<="20000" && $_POST['kosten']!="" && $_POST['raum']!=""){

$sql = "INSERT INTO frdn (acctid,user,kosten,sex,raum) VALUES ('".$session['user']['acctid']."','".$session['user']['name']."','".$_POST['kosten']."','".$session['user']['sex']."','".$_POST['raum']."')";

$result = db_query($sql) or die(db_error(LINK));

$id = db_insert_id(LINK);

$session['user']['gold']-=$verw;

$session['user']['room']=1;

// $session['user']['house'] = $houseid;

//$session['user']['roomid'] = $_POST['id'];

$session['user']['roomid'] = $id;

output("Erfolgreich eingetragen. Ihr müsst jetzt nurnoch warten, bis sich jemand meldet.");

addnav("Zurück ins Dorf","marktplatz.php");

}

else if ($_POST["raum"]=="" || $_POST["kosten"]==""){

        output("Bitte alle Felder ausfüllen.");

        addnav("Zurück","frdnhaus.php?op=anbiet");



}

else if($_POST['kosten']>"20000"){

output("Bitte einen Preis unter 20000 Gold wählen.");

addnav("Zurück","frdnhaus.php?op=anbiet");

addnav("Zurück ins Dorf","marktplatz.php");

}

break;

// ######################################################################################################





// 'Partner' auswählen

// ######################################################################################################

case "ver";

output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Zimmername</td><td>Name</td><td>Kosten</td><td>Nummer</td>",true);
if ($session['user']['superuser']>0){
output("<td>Aktion</td></tr>",true);
}

    $sql = "SELECT id,user,raum,kosten,sex FROM frdn";

    $row = db_fetch_assoc($result);

    $result = db_query($sql) or die(db_error(LINK));



if (db_num_rows($result)==0){



    output("<tr class='trdark'><td colspan=5 align='center'>`&`iDerzeit bietet sich niemand an!`i`0</td></tr>",true);

addnav("Zurück","frdnhaus.php");

} else {

        addnav("Doch lieber nicht","frdnhaus.php");

while ($row = db_fetch_assoc($result)) {



        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');

        output("<tr class='".$bgclass."'><td><a href='frdnhaus.php?op=getroom&id=".$row['id']."' onClick='return confirm(\"Willst du wirklich in dieses Zimmer?\");'>".$row['raum']."</a></td><td>".$row['user'],true);

    output("</td><td>".$row['kosten']."</td><td>".$row['id']."</td>",true);
    if ($session['user']['superuser']>0){
    output("<td><a href='frdnhaus.php?op=zimmdel&id=".$row['id']."'>Löschen</a></td>",true);
}

    addnav("","frdnhaus.php?op=getroom&id=".$row['id']);
addnav("","frdnhaus.php?op=zimmdel&id=".$row['id']);
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

$gold = $row['kosten'];

$id = $row['id'];

$acctid = $row['acctid'];



if($session[user][gold]>=$row[kosten]){

output("Du kannst nun in das Zimmer.");

addnav("Zurück","frdnhaus.php");

$session['user']['roomid'] = $row['id'];

$session['user']['gold'] -= $gold;

$session['user']['room']=1;

$sql = "UPDATE accounts SET goldinbank = goldinbank+$row[kosten] WHERE acctid='{$row['acctid']}'";

db_query($sql) or die(db_error(LINK));

$sql9 = "INSERT INTO mail (msgfrom,msgto,subject,body,sent) VALUES ('`System`0','$row[acctid]','`^Freudenhaus!`0','`&{$session['user']['name']}`6 hat sich für ein Zimmer bei Dir beworben und Dir dafür den Preis in Höhe von ".$row['kosten']." Gold auf deine Bank überwiesen!',now())";

db_query($sql9);

$sql2="DELETE FROM frdn WHERE id='$_GET[id]'";

$result2=db_query($sql2);

}

else if($session[user][gold]<$row[kosten]){

output("Das kannst Du Dir garnicht leisten!");

 addnav("Zurück","frdnhaus.php");

}





break;

// ######################################################################################################

// Zimmer löschen "ja o. nein?"

//##############

case "zimmdel";


output("Willst du das Zimmer wirklich löschen?");
addnav("Ja","frdnhaus.php?op=zimmdel1&id=".$_GET['id']."");
addnav("Nein","frdnhaus.php");

break;

//##############

// Zimmer löschen

//##############

case "zimmdel1";

$sql3 = "DELETE FROM frdn WHERE id='$_GET[id]'";

$result3 = db_query($sql3);


output("Du hast das Zimmer ".$_GET['id']." erfolgreich gelöscht.");
addnav("Zurück","frdnhaus.php");


break;
//##############

// Abschlusszeug

//##############

endswitch;

page_footer();

//###############

?> 