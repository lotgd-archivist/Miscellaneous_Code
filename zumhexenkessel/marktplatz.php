<?php

/*
Marktplatz by Opal
Idee: User von www.aphroditus.de
Umsetzung von Opal aka Laurion aka Seraphin erreichbar auf http://www.convoitise.de/
Bitte drinn stehen lassen ......

SQLFelder:
ALTER TABLE `accounts` ADD `hose` varchar(100) NOT NULL default 'nichts an',
  `oberteil` varchar(100) NOT NULL default 'nichts an',
  `kleid` varchar(100) NOT NULL default 'nichts an',
  `jacke` varchar(100) NOT NULL default 'nichts an',
  `schuhe` varchar(100) NOT NULL default 'nichts an',
  `hoeschen` varchar(100) NOT NULL default 'nichts an',
  `uhemd` varchar(100) NOT NULL default 'nichts an',
  `ring` varchar(100) NOT NULL default 'nichts an',
  `pircing` varchar(100) NOT NULL default 'nichts an',
  `kette` varchar(100) NOT NULL default 'nichts an',
  `haare` varchar(100) NOT NULL default 'Glatze',
  `augen` varchar(100) NOT NULL default 'Neutral';

  CREATE TABLE IF NOT EXISTS `kauf` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `besitzerid` int(11) unsigned NOT NULL default '0',
  `teil` varchar(50) NOT NULL default '',
  `kat` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=48 ;


 Einbau:
suche 
$result = db_query("SELECT login,name,level 

füge danach ein

,hose,oberteil,kleid,jacke,schuhe,hoeschen,uhemd,ring,pircing,kette,haare,augen

sollte deine zeile so aussehen

$result = db_query("SELECT*

brauchst du keine änderungen vornehmen

Anzeige in der bio.php
output("`GAussehen`n");
output("`^Haare:$row[haare]`n");
output("`^Augen:$row[augen]`n");
output("`GOberbekleidung`n");
output("`^Hose:$row[hose]`n");
output("`^Oberteil:$row[oberteil]`n");
output("`^Kleid:$row[kleid]`n");
output("`^Jacke:$row[jacke]`n");
output("`^Schuhe:$row[schuhe]`n");
output("`GUnterbekleidung`n");
output("`^Hoeschen:$row[hoeschen]`n");
output("`^Oberteil:$row[uhemd]`n");
output("`GSchmuck`n");
output("`^Ring:$row[ring]`n");
output("`^Piercing:$row[pircing]`n");
output("`^Kette:$row[kette]`n`n");

Anzeige in der Vitalinfo

Suche in der common.php
.templatereplace

füge an passender stelle ein ...

.templatereplace("stathead",array("title"=>"Aussehen"))
        .templatereplace("statrow",array("title"=>"Augen","value"=>$u['augen']))
        .templatereplace("statrow",array("title"=>"Harre","value"=>$u['haare']))
        .templatereplace("stathead",array("title"=>"Oberbekleidung"))
        .templatereplace("statrow",array("title"=>"Hose","value"=>$u['hose']))
        .templatereplace("statrow",array("title"=>"Oberteil","value"=>$u['oberteil']))
        .templatereplace("statrow",array("title"=>"Kleid","value"=>$u['kleid']))
        .templatereplace("statrow",array("title"=>"Jacke","value"=>$u['jacke']))
        .templatereplace("statrow",array("title"=>"Schuhe","value"=>$u['schuhe']))
        .templatereplace("stathead",array("title"=>"Unterbekleidung"))
        .templatereplace("statrow",array("title"=>"Höschen","value"=>$u['hoeschen']))
        .templatereplace("statrow",array("title"=>"Oberteil","value"=>$u['uhemd']))
        .templatereplace("stathead",array("title"=>"Schmuck"))
        .templatereplace("statrow",array("title"=>"Ring","value"=>$u['ring']))
        .templatereplace("statrow",array("title"=>"Pircing","value"=>$u['pircing']))
        .templatereplace("statrow",array("title"=>"Kette","value"=>$u['kette']))

        marktplatz.php,common.php,bio.php und gebuesch.php hochladen fertig

*/
require_once "common.php";
addcommentary();
page_header("Marktplatz");
$session['user']['standort'] = "Marktplatz";

output("`n`n`b`c`%`@Marktplatz:`c`b`n");
output("`c<img src='images/marktplatz.jpg'>`n`c",true);
output("`n`t`cEine Schwenktür führt dich ins Innere des Einkaufszentrums.`n
Auf zwei Stockwerken kannst du mehrer Geschäfte und kleine Restaurants sehen, zu denen man mit einem Lift oder den Rolltreppen kommt.`n
Im unteren Stockwerk, also im Erdgeschoss befinden sich die ganzen Supermärkte.`n
Im ersten Stockwerk kleine Restaurants , Friseure und Kosmetikgeschäfte.`n
Im obersten Stockwerk , befindet sich die Serviceabteilung wo man auch alle Elektrikartikel finden kann.`n
Unterhalb der ersten Rolltreppe steht eine kleine gemütliche Bank die von zwei Yukkapalmen geziert wird.`n
Am Eingang des Einkaufszentrums befindet sich eine Art Warnsystem, so was wie eine Schranke , falls doch mal wer was mitgehen lässt.`n
Du kannst von unten bis in den obersten Stockwerk hinauf schauen und siehst das lustige Treiben der Kundschaft.`n
Lautes Geplapper und Kassengeklingel übertönt alle anderen Geräusche.`n
In der Mitte des Einkaufszentrums befindet sich ein kleiner gemütlicher Platz wo ein Brunnen die Aufmerksamkeit auf sich lenkt.`c`n
");
addnav("`bMarktstände`b");
addnav("Optiker","marktplatz.php?op=optiker");
addnav("Frisör 100 Gold","marktplatz.php?op=frisoer");
addnav("`bOberbekleidung`b");
addnav("Hosen 1500 Gold","marktplatz.php?op=hose");
addnav("Oberteile 1500 Gold","marktplatz.php?op=ober");
addnav("Kleider 2000 Gold","marktplatz.php?op=kleid");
addnav("Jacken 3000 Gold","marktplatz.php?op=jacken");
addnav("Schuhe 2500 Gold","marktplatz.php?op=schuhe");
addnav("`bUnterbekleidung`b");
addnav("Höschen 500 Gold","marktplatz.php?op=uhose");
addnav("Oberteil 1000 Gold","marktplatz.php?op=uhemd");
addnav("Umkleide","gebuesch.php");
addnav("`bSchmuck`b");
addnav("Ringe 3000 Gold","marktplatz.php?op=ring");
addnav("Pircing 5000 Gold","marktplatz.php?op=pirc");
addnav("Ketten 8000 Gold","marktplatz.php?op=kett");
addnav("`bZurück`b");

if ($_GET['op']=="frisoer"){
$cost=100;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernha' method='POST'>
        `bHaare:`b`n`n
        Was für Haare möchtest du genau haben (genaue angabe zb lange braune Haare mit Farbcodes) : <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernha");
}else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="optiker"){
$cost=100;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernop' method='POST'>
        `bHose:`b`n`n
        Was für eine Augenfarbe möchtest du genau haben ( mit Farbcodes) : <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernop");
}else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="hose"){
$cost=1500;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernh' method='POST'>
        `bHose:`b`n`n
        Was für eine Hose möchtest du genau haben ( mit Farbcodes) : <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernh");
}else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="ober"){
$cost=1500;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speicherno' method='POST'>
        `bHose:`b`n`n
        Was für eine Oberteil möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speicherno");
}else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="kleid"){
$cost=2000;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernkl' method='POST'>
        `bHose:`b`n`n
        Was für eine Kleid möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernkl");
        }else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="jacken"){
$cost=3000;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernj' method='POST'>
        `bHose:`b`n`n
        Was für eine Jacke möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernj");
        }else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="schuhe"){
$cost=2500;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speicherns' method='POST'>
        `bHose:`b`n`n
        Was für Schuhe möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speicherns");
        }else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="uhose"){
$cost=500;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernuh' method='POST'>
        `bHose:`b`n`n
        Was für ein Höschen möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernuh");
        }else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="uhemd"){
$cost=1000;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernuo' method='POST'>
        `bHose:`b`n`n
        Was für ein Oberteil möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernuo");
        }else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="ring"){
$cost=3000;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernr' method='POST'>
        `bHose:`b`n`n
        Was für einen Ring möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernr");
        }else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="pirc"){
$cost=5000;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernp' method='POST'>
        `bHose:`b`n`n
        Was für eine Pircing möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernp");
        }else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}elseif ($_GET['op']=="Kett"){
$cost=8000;
if ($session['user']['gold']>=$cost){
$session['user']['gold']-=$cost;
output("<form action='marktplatz.php?op=speichernk' method='POST'>
        `bHose:`b`n`n
        Was für eine Kette möchtest du genau haben ( mit Farbcodes): <input name='teil'>`n
        <input type='submit' class='button' value='Speichern'>
        </form>",true);
        addnav("","marktplatz.php?op=speichernk");
        }else{
output("`&Du hast nicht genug Gold um dir dies zu leisten`n");
}
}
if ($_GET[op]=="speichernh"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Hose')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speicherno"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Oberteil')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernkl"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Kleid')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernj"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Jacke')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speicherns"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Schuhe')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernuh"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Hoeschen')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernuo"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Unterhemd')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernr"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Ring')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernk"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Kette')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernp"){
$besitz = $session['user']['acctid'];
$sql = "INSERT INTO kauf(teil,besitzerid,kat) VALUES ('$teil','$besitz','Pircing')";
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernha"){
$besitz = $session['user']['acctid'];
$session['user']['haare']=$teil;
db_query($sql) or die(db_error(LINK));
}elseif ($_GET[op]=="speichernop"){
$besitz = $session['user']['acctid'];
$session['user']['augen']=$teil;
db_query($sql) or die(db_error(LINK));
}

addnav("Zurück","marktplatz.php");
//addnav("Gasse der Händler","gassedh.php");
addnav("Zurück zum Dorf","village.php");

output("`n`n`%`@Marktplatz:`n");
viewcommentary("Marktplatz","Hinzufügen",25);
page_footer();
?> 