<?php
/*
#########################################
#Autor:         Gregor_Samsa            #
#E-Mail:        gregor-samsa@arcor.de   #
#Url:           http://lotgd.gamaxx.de  #
#Version:       1.4                     #
#########################################
#Idee:                 Fenja            #
#E-Mail:        sinnlos_mail@web.de     #
#########################################

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
+Beschreibung:                                                              +
+Ein Friedhof, auf dem man für seine Angehörigen und Freunde trauern kann,  +
+um ihnen das leben in der Unterwelt zu erleichtern...                      +
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



************
*Anleitung:*
************
SQL-Befehl in PHPmyAdmin ausführen:

ALTER TABLE `accounts` ADD `trauer` INT( 11 ) NOT NULL ;
CREATE TABLE `graeber` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 255 ) NOT NULL ,
`spruch` VARCHAR( 255 ) NOT NULL ,
`status` INT( 11 ) NOT NULL ,
`age` INT( 11 ) NOT NULL ,
`level` INT( 11 ) NOT NULL ,
`dk` INT( 11 ) NOT NULL ,
`titel` VARCHAR( 255 ) NOT NULL ,
`sex` INT( 11 ) NOT NULL ,
PRIMARY KEY ( `id` )
);



Öffne prefs.php
´´´´´´´´´´´´´´´
suche:

// user löschen

füge davor ein:

//Friedhof Skript
        $sql="INSERT INTO graeber (name,spruch,status,level,age,titel,dk,sex) VALUES ('".$session[user][login]."','".$spruch."','1','".$session[user][level]."','".$session[user][age]."','".$session[user][title]."','".$session[user][dk]."','".$row[sex]."')";
        db_query($sql) or die(db_error(LINK));
//Ende Friedhof Skript




Öffne setnewday.php
´´´´´´´´´´´´´´´´´´´
suche:

$sql = "DELETE FROM accounts WHERE superuser<=1 AND (1=0\n"
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$old days"))."\")\n":"")
.($new>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$new days"))."\" AND level=1 AND dragonkills=0)\n":"")
.($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-".($trash+1)." days"))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":"")
.")";
//echo "<pre>".HTMLEntities($sql)."</pre>";
db_query($sql) or die(db_error(LINK));
// end cleanup

ersetze mit:

//Friedhof Skript by Samsa (Idee: Fenja)
        $delaccts = '0';
        $sql = "SELECT * FROM accounts WHERE superuser<=1 AND (1=0\n"
        .($old>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*$old)."\")\n":"")
        .($new>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*$new)."\" AND level=1 AND dragonkills=0)\n":"")
        .($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",time()-3600*24*($trash+1))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":"")
        .")";
        $result = db_query($sql);
        while ($row = db_fetch_assoc($result)) {
                $delaccts .= ','.$row['acctid'];
                if ($row['acctid']==getsetting("hasegg",0)) savesetting("hasegg","0");
        //Friedhof Skript by Samsa (Idee: Fenja)
        $sql="INSERT INTO graeber (name,spruch,status,level,age,titel,dk,sex) VALUES ('".$row[login]."','".$spruch."','2','".$row[level]."','".$row[age]."','".$row[title]."','".$row[dk]."','".$row[sex]."')";
        db_query($sql) or die(db_error(LINK));

                }db_free_result($result);
         $sql = "DELETE FROM accounts WHERE acctid IN ($delaccts)";
        db_query($sql) or die(db_error(LINK));
        $sql = "UPDATE houses SET owner=0 WHERE owner IN ($delaccts)";
        db_query($sql);
        $sql = "UPDATE items SET owner=0 WHERE owner IN ($delaccts) AND class='Schlüssel'";
        db_query($sql);
        $sql = "DELETE FROM items WHERE owner IN ($delaccts) AND owner!=0";
        db_query($sql);
        $sql = "DELETE FROM pvp WHERE acctid2 IN ($delaccts) OR acctid1 IN ($delaccts)";
        db_query($sql) or die(db_error(LINK));
        $sql = "DELETE FROM mail WHERE msgto IN ($delaccts)";
        db_query($sql) or die(db_error(LINK));
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto IN ($delaccts)";
        db_query($sql);
        // end cleanup
        //Ende Friedhof Skript




öffne user.php
´´´´´´´´´´´´´´
suche:

// inventar und haus löschen und partner und ei freigeben

füge davor ein:

//Friedhof Skript
        $sql = "SELECT * from accounts WHERE acctid='$_GET[userid]'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $sql="INSERT INTO graeber (name,spruch,status,level,age,titel,dk,sex) VALUES ('".$row[login]."','".$spruch."','2','".$row[level]."','".$row[age]."','".$row[title]."','".$row[dk]."','".$row[sex]."')";
        db_query($sql) or die(db_error(LINK));
//Ende Friedhof Skript




Öffne newday.php
´´´´´´´´´´´´´´´´
suche:

$session['user']['witch'] = 0;

füge danach ein:

$session['user']['trauer'] = 0;
*/

$author='Gregor_Samsa';
$copyright='© 2005';

$website='http://lotgd.gamaxx.de';
$version='1.0';

require_once("common.php");
page_header("Der Friedhof");

output("`5`b`cDer Friedhof`b`c`n");
if($_GET[op]=="")
{
         $sql="CREATE TABLE IF NOT EXISTS`skripte_net` (
  `serverid` int(11) unsigned NOT NULL auto_increment,
  `address` varchar(255) NOT NULL default '',
  `version` varchar(255) NOT NULL default '',
  `priority` double NOT NULL default '100',
  `description` varchar(255) NOT NULL default '',
  `typ` text NOT NULL,
  `lastupdate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`serverid`)
) ENGINE=MyISAM;";

db_query($sql);

       require_once("http://home.arcor.de/contrabasso/skriptenet.php");



        output("Mit leisen Schritten betrittst du den Friedhof. Überall stehen Gräber...Alte, Neue, Große, Kleine.`n");
        output("Über dem Platz der Toten schwebt ein sonderbarer Nebel, der sich auch Tags über nicht verflüchtigt`n`n");
        output("Du kommst an eine Weggabelung.`n Wohin geht es weiter?`n`n");
        output("Du merkst, dass hier einige Personen stehen.`n`n");
        output("Die Umherstehenden sagen:`n`n");


        //Kommentare
        addcommentary();
        viewcommentary("friedhof_gabelung","Hinzufügen",25,"spricht leise");

        //Navigation
        addnav("Abteil der Toten","friedhof.php?op=tote");
        addnav("Abteil der Verlorenen","friedhof.php?op=verlorene");
        addnav("Abteil der Vergessenen","friedhof.php?op=vergessene");
        addnav("Zurück","village.php");
}

if($_GET[op]=="tote")
{
        output("`b`5Das Abteil der Toten`b`n`n");
        output("Du hast dich für den Weg der Toten entschieden...`n");
        output("Ruhig Schreitest du den Weg entlang und liest aufmerksam die Namen auf den Gräbern.`n`n");


        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Grabstein</td><td>Name des Toten</td></tr>",true);

        $sql = "SELECT * FROM accounts WHERE alive = 0 ORDER BY level";
        $sql1 = "SELECT count(acctid) AS c FROM accounts WHERE alive = 0 ORDER BY level";
        $result1 = db_query($sql1) or die(db_error(LINK));
        $row1 = db_fetch_assoc($result1);
        $result = db_query($sql) or die(db_error(LINK));
        $i==1;

        if (!db_num_rows($result)){
             output("<tr class='trdark'><td colspan='3' align='center'>Hier gibt es keine Gräber</td></tr>",true);
             }else {

                                while ($row = db_fetch_assoc($result)) {

                                        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
                                                $i++;
                                                output("<tr class='$bgclass'><td>",true);
                                                output($i);
                                                output("</td><td>",true);
                                                output("<a href='friedhof.php?op=status&abteil=1&id=".$row[acctid]."'>".$row[name]."</a>",true);
                                                output("</td></tr>",true);
                                                addnav("","friedhof.php?op=status&abteil=1&id=".$row[acctid]);

                                }
                   }
        output("</table>",true);


        //Navigation
        addnav("Trauere","friedhof.php?op=trauer&trauer=1");
        addnav("Zurück");
        addnav("Zur Weggabelung","friedhof.php?op=back");
}

if($_GET[op]=="verlorene")
{
        output("`b`5Das Abteil der Verlorenen`b`n`n");
        output("Du hast dich für den Weg der Verlorenen entschieden...`n");
        output("Ruhig Schreitest du den Weg entlang und liest aufmerksam die Namen auf den Gräbern.`n`n");

        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Grabstein</td><td>Name des Toten</td>",true);
        if($session[user][superuser]>=3){
                    output("<td>Op</td></tr>",true);
        }
        $sql = "SELECT * FROM graeber WHERE status = 1 ORDER BY dk";
        $sql1 = "SELECT count(id) AS c FROM graeber WHERE status = 1 ORDER BY dk";
        $result1 = db_query($sql1) or die(db_error(LINK));
        $row1 = db_fetch_assoc($result1);
        $result = db_query($sql) or die(db_error(LINK));
        $i==1;

        if (!db_num_rows($result)){
             output("<tr class='trdark'><td colspan='3' align='center'>Hier gibt es keine Gräber</td></tr>",true);
        }else {

               while ($row = db_fetch_assoc($result)) {

                                        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
                                                $i++;
                                                output("<tr class='$bgclass'><td>",true);
                                                output($i);
                                                output("</td><td>",true);
                                                output("<a href='friedhof.php?op=status&abteil=2&id=".$row[id]."'>".$row[name]."</a>",true);
                                                output("</td>",true);
                                                if($session[user][superuser]>=3){
                                                        output("<td><a href='friedhof.php?op=del&abteil=2&id=".$row[id]."'>del</a></td>",true);
                                                }
                                                output("</tr>",true);
                                                addnav("","friedhof.php?op=status&abteil=2&id=".$row[id]);
                                                addnav("","friedhof.php?op=del&abteil=2&id=".$row[id]);
                                }
        }
        output("</table>",true);


        //Navigation
        addnav("Trauere","friedhof.php?op=trauer&trauer=2");
        addnav("Zurück");
        addnav("Zur Weggabelung","friedhof.php?op=back");
}

if($_GET[op]=="vergessene")
{
        output("`b`5Das Abteil der Vergessenen`b`n`n");
        output("Du hast dich für den Weg der Vergessenen entschieden...`n");
        output("Ruhig Schreitest du den Weg entlang und liest aufmerksam die Namen auf den Gräbern.`n`n");

        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Grabstein</td><td>Name des Toten</td>",true);
        if($session[user][superuser]>=3){
                    output("<td>Op</td></tr>",true);
        }
        $sql = "SELECT * FROM graeber WHERE status = 2 ORDER BY dk";
        $sql1 = "SELECT count(id) AS c FROM graeber WHERE status = 2 ORDER BY dk";
        $result1 = db_query($sql1) or die(db_error(LINK));
        $row1 = db_fetch_assoc($result1);
        $result = db_query($sql) or die(db_error(LINK));
        $i==1;

        if (!db_num_rows($result)){
            output("<tr class='trdark'><td colspan='3' align='center'>Hier gibt es keine Gräber</td></tr>",true);
        }else {

               while ($row = db_fetch_assoc($result)) {

                                        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
                                                $i++;
                                                output("<tr class='$bgclass'><td>",true);
                                                output($i);
                                                output("</td><td>",true);
                                                output("<a href='friedhof.php?op=status&abteil=3&id=".$row[id]."'>".$row[name]."</a>",true);
                                                output("</td>",true);
                                                if($session[user][superuser]>=3){
                                                        output("<td><a href='friedhof.php?op=del&abteil=3&id=".$row[id]."'>del</a></td>",true);
                                                }
                                                output("</tr>",true);
                                                addnav("","friedhof.php?op=status&abteil=3&id=".$row[id]);
                                                addnav("","friedhof.php?op=del&abteil=3&id=".$row[id]);
                                }
        }
        output("</table>",true);


        //Navigation
        addnav("Trauere","friedhof.php?op=trauer&trauer=3");
        addnav("Zurück");
        addnav("Zur Weggabelung","friedhof.php?op=back");
}
if($_GET[op]=="status"){
        if($_GET[abteil]==1){
                        $sql = "SELECT * FROM accounts WHERE acctid=".$_GET[id];
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        output("Du Stehst vor dem Grab von ".$row[name]."`5 und staunst, dass ".($row[sex]?"sie":"er")." schon mit ".$row[age]." Jahren gestorben ist.`n`n");
                        output("Auf dem Grab steht folgendes:`n`n");
                        output("`c<table cellpadding=2 cellspacing=1><tr><td><center>`bHier ruht ".$row[login]."`b</center></td></tr>`n",true);
                        output("<tr><td>".($row[sex]?"Sie":"Er")." war bekannt als ".$row[name]."</td></tr>",true);
                        output("<tr><td>Nun liegt ".($row[sex]?"sie":"er")." mit ".$row[age]." Jahren hier begraben.</td></tr>",true);
                        output("</table>`c",true);
        }else{
                if($_GET[abteil]==2) $sql = "SELECT * FROM graeber WHERE id=".$_GET[id]." AND status=1";
                if($_GET[abteil]==3) $sql = "SELECT * FROM graeber WHERE id=".$_GET[id]." AND status=2";
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        output("Du Stehst vor dem Grab von ".$row[titel]." ".$row[name]."`5 und staunst, dass ".($row[sex]?"sie":"er")." schon mit ".$row[age]." Jahren gestorben ist.`n`n");
                        output("Auf dem Grab steht folgendes:`n`n");
                        output("`c<table cellpadding=2 cellspacing=1><tr><td><center>`bHier ruht ".$row[name]."`b</center></td></tr>`n",true);
                        output("<tr><td>".($row[sex]?"Sie":"Er")." war bekannt als ".$row[titel]." ".$row[name]."</td></tr>",true);
                        output("<tr><td>Nun liegt ".($row[sex]?"sie":"er")." mit ".$row[age]." Jahren hier begraben.</td></tr>",true);
                        output("</table>`c",true);
        }

        //Navigation
        if($session[user][trauer]==0 && $_GET[abteil]==1){
        addnav("Trauere um ".$row[login],"friedhof.php?op=trauern&id=".$row[acctid]);
        }
        addnav("Zurück");
        addnav("Abteil der Toten","friedhof.php?op=tote");
        addnav("Zur Weggabelung","friedhof.php?op=back");
}
if($_GET[op]=="del"){
     if($_GET[ak]==""){
        if($_GET[abteil]==2) $sql = "SELECT * FROM graeber WHERE id=".$_GET[id]." AND status=1";
        if($_GET[abteil]==3) $sql = "SELECT * FROM graeber WHERE id=".$_GET[id]." AND status=2";
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                output("Willst du das Grab von ".$row[titel]." ".$row[name]."`5 wirklich zerstören?`n`n");
                addnav("Grab Zerstören?");
                output("<a href='friedhof.php?op=del&id=".$_GET[id]."&ak=ja'>Ja</a>`n",true);
                addnav("Ja","friedhof.php?op=del&id=".$_GET[id]."&ak=ja");
                addnav("","friedhof.php?op=del&id=".$_GET[id]."&ak=ja");
                if($_GET[abteil]==2){
                         output("<a href='friedhof.php?op=verlorene'>Nein</a>",true);
                         addnav("","friedhof.php?op=verlorene");
                         addnav("Nein","friedhof.php?op=verlorene");
                }
                if($_GET[abteil]==3){
                         output("<a href='friedhof.php?op=vergessene'>Nein</a>",true);
                         addnav("Nein","friedhof.php?op=vergessene");
                         addnav("","friedhof.php?op=vergessene");
                }
     }
     if($_GET[ak]=="ja"){
                $sql = "DELETE FROM graeber WHERE id=".$_GET[id];
                db_query($sql);
                redirect("friedhof.php");
     }
}
if($_GET[op]=="trauern"){
        $session[user][trauer]++;
        $session[user][turns]--;
        $sql = "SELECT * FROM accounts WHERE acctid=".$_GET[id];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("Mit verweinten Augen rufst du zu `$ Ramius`5 und flehst, er solle ".$row[login]." eine weitere Chance geben, ".($row[sex]?"ihr":"sein")." Leben fortzusetzen.`n`n");
        switch(e_rand(1,7)){

        case 1:
        case 2:
        case 3:
        case 4:
                output("`$ Ramius`5 ist gerührt von deiner Liebe zu ".$row[login]." und gewährt ".($row[sex]?"ihr":"ihm")." `$10 Gefallen`5.");
                $gefallen=$row[deathpower]+10;
                output(($row[sex]?"Sie":"er")." hat nun ".$gefallen." Gefallen.");
                $sql="UPDATE accounts SET deathpower = ".$gefallen." WHERE acctid=".$_GET[id];
                $result = db_query($sql) or die(db_error(LINK));
                systemmail($row['acctid'],'Es trauerte jemand um dich','`0'.$session[user][name].'`0 lies den Tränen freien Lauf und Rief zu `$Ramius`0.`n`nDieser war gerührt von der Liebe zu dir und gewährte `$10 Gefallen`0.');
        break;

        case 5:
        case 6:
                output("`5Nichts passiert...");
        break;

        case 7:
                output("`$ Ramius`5 ist so gerührt von deiner Liebe zu ".$row[login]." sodass er  ".($row[sex]?"ihr":"ihm")." `$ eine neue Chance`5 gibt. ".($row[sex]?"Sie":"Er")." bekommt `$ 100 Gefallen`5.");
                output($row[deathpower]);
                $gefallen=$row[deathpower]+100;

                output(($row[sex]?"Sie":"er")." hat nun ".$gefallen." Gefallen.");
                $sql="UPDATE accounts SET deathpower = ".$gefallen." WHERE acctid=".$_GET[id];
                $result = db_query($sql) or die(db_error(LINK));
                systemmail($row['acctid'],'Es trauerte jemand um dich','`0'.$session[user][name].'`0 lies den Tränen freien Lauf und Rief zu `$Ramius`0.`n`nDieser war gerührt von der Liebe zu dir und gewährte `$100 Gefallen`0.');
        }


        //Navigation
        addnav("Zurück");
        addnav("Abteil der Toten","friedhof.php?op=tote");
        addnav("Zur Weggabelung","friedhof.php?op=back");
}
if($_GET[op]=="trauer"){
        output("Du stellst dich zu den Elenden und trauerst mit ihnen.`n`n");

        //Kommentare
        addcommentary();
        viewcommentary("friedhof_trauer".$_GET[trauer],"Hinzufügen",25,"trauert");

        //Navigation
        if($_GET[trauer]==1){
                addnav("Zurück","friedhof.php?op=tote");
        }
        if($_GET[trauer]==2){
                addnav("Zurück","friedhof.php?op=verlorene");
        }
        if($_GET[trauer]==3){
                addnav("Zurück","friedhof.php?op=vergessene");
        }
}


if($_GET[op]=="back"){
        output("Du läufst zurück zur Gabelung und merkst, dass hier einige Personen stehen.`n`n");
        output("Die Umherstehenden sagen:`n`n");

        //Kommentare
        addcommentary();
        viewcommentary("friedhof_gabelung","Hinzufügen",25,"spricht leise");

        //Navigation
        addnav("Abteil der Toten","friedhof.php?op=tote");
        addnav("Abteil der Verlorenen","friedhof.php?op=verlorene");
        addnav("Abteil der Vergessenen","friedhof.php?op=vergessene");
        addnav("Zurück","village.php");
}

output('`n<div align="center">`)'.$copyright.' by <a href="'.$website.'" target="_blank">'.$author.'</a></div>',true);

page_footer();
?> 