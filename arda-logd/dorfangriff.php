<?php
//01032006
 //Dorfangriff/Belagerung by -DoM v1.0
 //http://my-logd.com/motwd/
 //logd@gloth.org
 ######################################################################################################################################
 /*EINBAUANLEITUNG:
 
 1.Diese SQL-Befehle im PHPMYADMIN ausführen:
 ALTER TABLE accounts ADD dorfkampf int(4) NOT NULL default '0';
 ALTER TABLE accounts ADD gesamtkampf int(11) NOT NULL default '0';
 ######################################################################################################################################
 2.Öffne configurations.php:
 Suche:
 "Account Erstellung,title",
 Füge davor ein:
 "Dorfangriff-Einstellungen,title",
 "datage"=>"Spieltage bis zum nächsten autom. Angriff (X=Aus),int",
 "angriff"=>"Dorfangriff Aktiv?,bool",
 "dangreifer"=>"Dorf Angreifer (max. 9999),int",
 "gegner"=>"Name der Gegner: (max. 50 Zeichen)",
 
 Speichere configurations.php und lade sie hoch.
 ######################################################################################################################################
 3.Öffne village.php
 Suche:
 require_once "common.php";
 addcommentary();
 Füge danach ein:
 $aktiv = getsetting("angriff","0");
 if ($aktiv==1) {
 $anzahl = getsetting("dangreifer","0");
 }
 
 Suche:
 addnav("Wald","forest.php");
 Füge davor ein:
 if ($aktiv==1) addnav("`\$Die Stadt verteidigen","dorfangriff.php");
 
 Der Dorfangriff stellt ja eine Belagerung dar. Daher sollte jedwede möglichkeit aus dem Dorf zugelangen unterbunden werden.
 Dies sollte man so lösen:
 if ($aktiv!=1) addnav("Wald","forest.php");
 Also, wenn es mehrere Möglichkeiten gibt, aus dem Dorf zugelangen, dann setzte einfach vor den Betreffenden addnav der aus dem Dorf führt dies hier:
 if ($aktiv!=1)
 Beispiel:
 addnav("Sonst wo hin","blabla.php");
 Ändere in:
 if ($aktiv!=1) addnav("Sonst wo hin","blabla.php");
 
 Verstanden??? Wenn nicht mache alle Änderungen wieder rückgängig, und lass die Finger von diesem AddOn.....:P
 
 Suche:
 page_header("Dorfplatz");
 Füge danach ein:
 if ($aktiv==1) output("<h3>`b`c`\$ACHTUNG!`n Die Stadt wird belagert!`0`c`b</h3>`c`\$Der Späher der Stadtwache meldet, dass es noch $anzahl Angreifer sind`0`c`n",true);
 
 Speicher die village.php und lade sie hoch.
 ######################################################################################################################################
 4.Alternativ kannst du den Dorfangriff auch in der Grotte verlinken um von dort aus die Einstellungen vorzunehmen.
 Öffne superuser.php
 
 Suche:
 addnav("Mechanik");
 Füge davor ein:
 addnav("`\$Die Stadt verteidigen","dorfangriff.php");
 
 Speicher die superuser.php und lade sie hoch.
 ######################################################################################################################################
 5.Öffne setnewday.php
 
 Suche:
 ?> //Ganz unten am Ende der Datei
 Füge davor ein:
 //Dorfangriff by -DoM (http://logd.gloth.org) (logd@gloth.org) Anfang:
 $howmuchdays = getsetting("datage",0);
 $ststus = getsetting("angriff",0);
 if (($howmuchdays>1) && ($ststus==0)){
 savesetting("datage",$howmuchdays-1);
 }
 if (($howmuchdays==1) && ($ststus==0)){
 $anzahl = (e_rand(1000,3000));
 $name = array(1=>"`#He`8ili`#ge Kr`8ieg`#er`0","Ratten","Plünderer","`9G`&a`9l`&l`9i`&e`9r`0","Banditen","Hunnen","Räuber","Römer","Piraten"
 ,"Kreuzritter","Bewohner Wyrmlands","Heuschrecken","Centauren","Titanen","Skyten","`4Gr`2uft `4Ge`2se`4ll`2en`0"
 ,"`4Grab`6schän`4der`0","`\$Schreckens`7bringer`0","`QK`Tnochen`Qb`Trecher`0","`2D`@r`2a`2c`@h`2e`@n`2z`@ö`2l`@l`2n`@e`2r`0"
 ,"`~Ork`Tschlächter`0","`TW`tütende `TO`trks`0","`2Kampfzwerge`0","`4W`\$esen `4d`\$er `4N`\$acht`0","`3Schreiende `#Banshees`0"
 ,"`QD`8i`Qe `QI`8lluminat`Qi`0","`7Wargh Wölfe");
 $zufallsname = (e_rand(1,27));
 savesetting("gegner",$name[$zufallsname]);
 savesetting("dangreifer",$anzahl);
 savesetting("angriff",1);
 $wolken="`Qes purzeln ".$name[$zufallsname]."`Q vom Himmel";
 savesetting("weather",$wolken);
 }
 //Dorfangriff Ende
 
 Speicher die setnewday.php und lade sie hoch.
 ######################################################################################################################################
 6.Öffne die hof.php
 
 Suche:
 addnav("Arenakämpfer","hof.php?op=battlepoints&subop=$subop&page=$page");
 Füge danach ein:
 addnav("Erledigte Angreifer", "hof.php?op=angriff&subop=$subop&page=$page");
 
 Suche:
 } elseif ($_GET[op]=="battlepoints"){
 $sql = "SELECT name,battlepoints AS data1,dragonkills AS data2 FROM accounts WHERE superuser < 2 and locked=0 ORDER BY battlepoints $order, dragonkills $order, acctid $order LIMIT $limit";
 $adverb = "besten";
 if ($_GET[subop] == "least") $adverb = "schlechtesten";
 $title = "Die $adverb Arenakämpfer in diesem Land";
 $headers = array("Punkte","Phoenixkills");
 display_table($title, $sql, false, false, $headers, false);
 Fpge danach ein:
 }else if ($_GET[op]=="angriff"){
 $sql = "SELECT name,gesamtkampf AS data1 FROM accounts WHERE superuser < 2 and locked=0 AND gesamtkampf ORDER BY gesamtkampf $order, acctid $order LIMIT $limit";
 $adverb = "meisten";
 if ($_GET[subop] == "least") $adverb = "wenigsten";
 $title = "Die Krieger mit den $adverb erledigten Angreifern im ganzen Land";
 $headers = array("Angreifer");
 display_table($title, $sql, false, false, $headers, false);
 
 Speicher die hof.php und lade sie hoch.
 ######################################################################################################################################
 7.Öffne die dragon.php
 
 Suche 2 mal:
 ,"avatar"=>1
 Füge danach jeweils einmal ein:
 ,"gesamtkampf"=>1
 ,"dorfkampf"=>1
 
 Speicher die dragon.php und lade sie hoch.
 ######################################################################################################################################
 8. Lade die Datei dorfangriff.php in den Root deines LogD.
 
 Fertig...;-)
 (Wenn ich nichts vergessen habe.....*fg*)
 
 Viel Spass damit, Euer -DoM
 ######################################################################################################################################
 */
 require_once "common.php";
 checkday();
 addcommentary();
 page_header("Stadtangriff");
 
 $datage = getsetting("datage","X");
 $aktiv = getsetting("angriff","0");
 $anzahl = getsetting("dangreifer","0");
 $name = getsetting("gegner","0");
 
 if ($_GET['op']==""){
 output("`b`cDie Stadt verteidigen`b`c`n`n");
 output("`\$Die Stadt wird von einer Invasion von $name `\$bedroht! Die Stadtwache ist diesem geballten Angriff nicht gewachsen.
 Helfe mit, diesen Angriff abzuwehren und die Stadtbewohner vor Ihrem Untergang zu beschützen.`n
 Solange die Stadt belagert wird, sind die Stadttore für Alle geschlossen. Die Gefahr das die Invasoren die Stadttore überrennen, 
 wäre zu gross!`n`nDer Späher der Stadtwache meldet das es noch $anzahl Angreifer sind!`n`n `bWas wirst du tun? `nFeige in deinem Haus verkriechen, oder dich Todesmutig den Invasoren stellen?`b`n`n");
 viewcommentary("angriff","Rede mit den Anderen",15);
 
 addnav("Was tust du?");
 addnav("Auf in den Kampf","dorfangriff.php?op=angriff");
 addnav("Feige verkriechen","houses.php");
 addnav("Bisheriger Verlauf","dorfangriff.php?op=verlauf");
 addnav("Zum Heiler","dorfangriff.php?op=heiler");
 addnav("Sonstiges");
 addnav("Zurück zur Stadt","village.php");
 addnav("Aktualisieren","dorfangriff.php");
 if ($session['user']['superuser']==4){
 addnav("Admin-Ops");
 addnav("Einstellungen","dorfangriff.php?op=einstell");
 addnav("Verlauf leeren","dorfangriff.php?op=leeren");
 addnav("Zurück zur Grotte","superuser.php");
 }
 }
 
 if ($_GET['op']=="leeren"){
 isnewday(3);
 $sql = "UPDATE accounts SET dorfkampf = dorfkampf='0' WHERE dorfkampf > 0";
 db_query($sql);
 output("`b`cDIe Stadt verteidigen - Kampfverlauf manuell leeren`b`c`n`n");
 output("`QDer Veraluf wurde geleert");
 
 addnav("Weiter","dorfangriff.php");
 }
 
 if ($_GET['op']=="heiler"){
 $cost = ($session['user']['level']*25);
 output("`b`cDie Stadt verteidigen`b`c`n`n");
 output("`QDer Heiler im Wald hörte von diesem Angriff. So beschloss er sofort ins die Stadt zu kommen um den Kriegern mit seinen Heiltränken 
 zur Verfügung zu stehen. Er kommt auf dich zu und meint zu dir: `#\"Möchtest du einen Heiltrank zur kompletten Heilung kaufen, 
 er kostet dich nur `^$cost Gold`#?\"`Q.");
 output("`n`n`\$`bWas willst du tun?`n`b");
 
 addnav("Komplette Heilung","dorfangriff.php?op=heiler2&cost=$cost");
 addnav("Lieber doch nicht","dorfangriff.php");
 }
 
 if ($_GET['op']=="heiler2"){
 if ($session['user']['gold']>$_GET['cost']){
 if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
 output("`b`cDie Stadt verteidigen`b`c`n`n");
 output("`QDu übergibst dem Heiler `^".$_GET['cost']." Gold`Q und er überreicht dir einen Heiltrank, den du sofort runterschluckst!"); 
 output("`n`n`\$`bDu wurdest Vollständig geheilt!`n`b");
 $session['user']['gold']-=$_GET['cost'];
 $session['user']['hitpoints']=$session['user']['maxhitpoints'];
 }else{
 output("`b`cDie Stadt verteidigen`b`c`n`n");
 output("`QDer Heiler meint zu dir das du doch vollkommen Gesund bist und diesen Heiltrank gar nicht brauchst! Er brauche diese Tränke für die wahrhaft 
 angeschlagenen Bewohner.");
 }
 }else{
 output("`b`cDie Stadt verteidigen`b`c`n`n");
 output("`QDer Heiler schaut dich böse an. Du solltest vielleicht auch genug Gold bei dir haben und schickt dich weg.");
 }
 addnav("Zurück","dorfangriff.php");
 }
 
 if ($_GET['op']=="verlauf"){
 output("`b`cDie Stadt verteidigen`b`c`n`n");
 output("");
 output("<table border=0 cellpadding=0 cellspacing=0 align='center'>",true);
 output("<tr class='trhead'><td><b>`^Übersicht des bisherigen Schlachtverlaufs`0</b></td></tr>",true);
 $i = 0;
 $sql='SELECT name,dorfkampf,sex FROM accounts WHERE /*superuser<1 AND*/ dorfkampf>0 ORDER BY dorfkampf DESC';
 $result = db_query($sql) or die(db_error(LINK));
 if (db_num_rows($result) == 0) {
 output("<tr align='center'><td>`i`b~ ~ ~ Keine ~ ~ ~`b`i</td></tr>",true);
 }else{
 while ($row = db_fetch_assoc($result)) {
 output("<td align='center'>".($row[sex]?"`QDie Stadtbewohnerin ":"`QDer Stadtbewohner ")."",true);
 output($row['name']);
 output("`Qhat bisher ganze `6`b".$row['dorfkampf']."`b`Q $name`Q erledigt.</td>",true);
 output("</tr>",true);
 $i++;
 }
 output('</table>',true);
 }
 addnav("Aktualisieren","dorfangriff.php?op=verlauf");
 addnav("Zurück","dorfangriff.php");
 }
 
 if ($_GET['op']=="einstell"){
 isnewday(3);
 output("`b`cStadtangriff - Einstellungen`b`c`n`n");
 output("Hier kannst du die Einstellungen des Stadtangriffes direkt beeinflussen. Alle Änderungen die hier vorgenommen werden,`n");
 output("werden auch parallel in den Spieleinstellungen (configuration.php) übernommen.`n");
 output("`n`b`\$Aktuelle Einstellungen:`0`b");
 if ($aktiv=="0"){
 output("`\$`bTage bis zum nächsten automatischen Start des Angriffs: $datage"); 
 output("`n`b`\$Der Stadtangriff ist im Moment `ideaktiviert`i!`b`n");
 output("`b`\$Die aktuelle Gegneranzahl ist: $anzahl.`0`b`n");
 output("`b`\$Der aktuelle Gegnername lautet: $name.`0`b`n");
 }else{
 output("`n`b`\$Der Stadtangriff ist im Moment `i`@aktiviert`i`\$!`b`n");
 output("`b`\$Die aktuelle Gegneranzahl ist: $anzahl.`0`b`n");
 output("`b`\$Der aktuelle Gegnername lautet: $name.`0`b`n");
 }
 output("`n`b`^Hinweis: Alle Felder müssen ausgefüllt werden!`0`b`n");
 
 output("<form action='dorfangriff.php?op=change' method='POST'>",true);
 output("<table border='0' cellspacing='0' cellpadding='0'><tr class='trhead'><td>Einstellungen:</td><td></td></tr>",true);
 output("<tr><td>Tage bis zum nächst. autom. Angriff: (X=Aus) </td><td><input type='text' id='tage' name='tage' value='$datage' maxlength='3' size='4'></td></tr>",true);
 output("<tr><td>Angriff Aktivierung: (0 = Aus / 1 = an) </td><td><input type='text' id='an' name='an' value='$aktiv' maxlength='1' size='2'></td></tr>",true);
 output("<tr><td>Anzahl der Gegner: (max. 9999) </td><td><input type='text' id='zahl' name='zahl' value='$anzahl' maxlength='4' size='5'></td></tr>",true);
 output("<tr><td>Name der Gegner: (max. 50 Zeichen) </td><td><input type='text' id='name' name='name' value='$name' maxlength='50' size='50'></td></tr>",true);
 output("</table>",true);
 output("<input type='submit' class='button' value='Übernehmen'></form>",true);
 addnav("","dorfangriff.php?op=change");
 
 addnav("Zurück","dorfangriff.php");
 
 }
 if ($_GET['op']=="change"){
 isnewday(3);
 savesetting("datage",$_POST['tage']);
 savesetting("angriff",$_POST['an']);
 savesetting("dangreifer",$_POST['zahl']);
 savesetting("gegner",$_POST['name']);
 
 output("`b`cStadtangriff - Einstellungen`b`c`n`n");
 output("`b`\$Die Einstellungen wurden übernommen`0`b`n");
 
 addnav("Weiter","dorfangriff.php?op=einstell");
 
 }
 
 if ($_GET['op']=="angriff"){
 
 $level = $session['user']['level'];
 $atk = $session['user']['attack'];
 $def = $session['user']['defence'];
 $hp = $session['user']['hitpoints'];
 $dk = $session['user']['dragonkills'];
 $tattoo = $session['user']['herotattoo'];
 
 if ($dk <= 5){
 $levelbuff = $level;
 $atkbuff = $atk;
 $defbuff = $def;
 $hpbuff = $hp;
 }
 if (($dk > 5) && ($dk <= 10)){
 $levelbuff = ($level + e_rand(0,1));
 $atkbuff = $atk;
 $defbuff = $def;
 $hpbuff = $hp;
 }
 if (($dk > 10) && ($dk <= 20)){
 $levelbuff = ($level + e_rand(0,1));
 $atkbuff = $atk;
 $defbuff = $def;
 $hpbuff = ($hp + e_rand(0,5));
 }
 if (($dk > 20) && ($dk <= 50)){
 $levelbuff = ($level + e_rand(0,1));
 $atkbuff = $atk;
 $defbuff = $def;
 $hpbuff = ($hp + e_rand(0,20));
 }
 if (($dk > 50) && ($dk <= 100)){
 $levelbuff = ($level + e_rand(0,1));
 $atkbuff = ($atk + e_rand(0,2));
 $defbuff = ($def + e_rand(0,2));
 $hpbuff = ($hp + e_rand(0,40));
 }
 if ($dk > 100){
 $levelbuff = ($level + e_rand(0,1));
 $atkbuff = ($atk + e_rand(0,3));
 $defbuff = ($def + e_rand(0,3));
 $hpbuff = ($hp + e_rand(0,60));
 }
 
 
 $creaturegold = array(1=>"20",2=>"30",3=>"40",4=>"50",5=>"60",6=>"70",7=>"80",8=>"90",9=>"100",10=>"110",11=>"120",12=>"130",13=>"140",14=>"150",15=>"160",16=>"170",17=>"180",18=>"190");
 $creatureexp = array(1=>"10",2=>"15",3=>"20",4=>"25",5=>"30",6=>"35",7=>"40",8=>"45",9=>"50",10=>"55",11=>"60",12=>"65",13=>"70",14=>"80",15=>"90",16=>"100",17=>"110",18=>"120");
 
 $creaturegoldbuff = ($creaturegold[$levelbuff] + e_rand(0,150));
 $creatureexpbuff = ($creatureexp[$levelbuff] + e_rand(0,30));
 
 $gegner = array(1=>"`@kleiner $name `@Lümmel`0"
 ,2=>"`@kleiner $name `@Lümmel`0"
 ,3=>"`@$name`@ Lümmel`0"
 ,4=>"`@$name`@ Lümmel`0"
 ,5=>"`@ausgewachsener $name`0"
 ,6=>"`@ausgewachsener $name`0"
 ,7=>"`@Infanterist der $name`0"
 ,8=>"`@Infanterist der $name`0"
 ,9=>"`@Kavelerist der $name`0"
 ,10=>"`@Kavelerist der $name`0"
 ,11=>"`@Grenadier der $name`0"
 ,12=>"`@Grenadier der $name`0"
 ,13=>"`@Unteroffizier der $name`0"
 ,14=>"`@Hauptfeldwebel der $name`0"
 ,15=>"`@Hauptmann der $name`0"
 ,16=>"`@Oberst der $name`0"
 ,17=>"`@General der $name`0"
 ,18=>"`@Feldherr der $name`0"
 );
 
 $waffe = array(1=>"Besenstiel"
 ,2=>"Besenstiel"
 ,3=>"Keule"
 ,4=>"Keule"
 ,5=>"Knüppel"
 ,6=>"Knüppel"
 ,7=>"Messer"
 ,8=>"Degen"
 ,9=>"Lanze"
 ,10=>"Langlanze"
 ,11=>"Kurzschwert"
 ,12=>"Langschwert"
 ,13=>"Breitschwert"
 ,14=>"Großes Breitschwert"
 ,15=>"Zweihänder Schwert"
 ,16=>"Morgenstern"
 ,17=>"Kriegs Hammer"
 ,18=>"Kriegs Axt"
 );
 
 if ($anzahl>0){ 
 if ($session['user']['turns']>0){
 $badguy = array("creaturename"=>"$gegner[$levelbuff]"
 ,"creaturelevel"=>$levelbuff
 ,"creatureweapon"=>"$waffe[$levelbuff]"
 ,"creatureattack"=>$atkbuff
 ,"creaturedefense"=>$defbuff
 ,"creaturehealth"=>$hpbuff
 ,"creaturegold"=>"$creaturegoldbuff"
 ,"creatureexp"=>"$creatureexpbuff"
 ,"diddamage"=>0);
 $session['user']['badguy']=createstring($badguy);
 $_GET['op']="vorkampf";
 }else{
 output("`b`cDie Stadt verteidigen`b`c`n`n");
 output("`n`\$Du hast heute schon alle deine Runden aufgebraucht! Du hast aber schon ".$session['user']['dorfkampf']." Angreifer erledigt und kannst Stolz auf dich sein.");
 addnav("Zurück");
 addnav("Zurück","dorfangriff.php");
 }
 }else{
 output("`b`cDie Stadt verteidigen`b`c`n`n");
 output("`n`\$Gerade als du hinaus stürmst um dich in den Kampf zu stürzen, stellst du fest das keine Gegner mehr übrig sind. Entäuscht gehst du zurück in die Stadt.");
 addnav("Zurück");
 addnav("Zurück zur Stadt","village.php");
 }
 }
 if ($_GET['op']=="vorkampf"){
 output("`\$Du konntest die Stadtwache von deinen Fahigkeiten überzeugen. Die Wachen lassen dich durch eine kleine Tür nach draussen.`n");
 output("`\$Die Wachen rufen dir noch ihre Anfeuerungsrufe nach: `^\"MACH SIE FERTIG\"`\$ Angefeuert fühlst du dich gleich doppelt so Stark.`n");
 output("`\$Der ".$badguy['creaturename']." `\$bemerkt dich und stürzt sich auf dich!`n`n `$ `b`c- Der Kampf beginnt -`c`b`n`n`n");
 $_GET['op']="fight";
 
 }
 if ($_GET['op']=="fight"){
 $battle=true;
 
 }
 if ($battle){
 include_once("battle.php");
 if ($victory){
 $session['user']['dorfkampf']++;
 $session['user']['gesamtkampf']++;
// addnews("`5".$session['user']['name']."`8 hat einen Angreifer `8besiegt, dies ist ".($session['user']['sex']?"`8ihr ":"`8sein ")."`\$".$session['user']['dorfkampf'].".`8 erledigter $name`8!");
 $kaempfername = ($session['user']['name']);
 if($anzahl>1) {
 savesetting("dangreifer",$anzahl-1);
 }elseif ($anzahl==1){
 $_GET['op']="ende";
 }else{
 output("`n`n`\$Komisch, wie mir scheint hast du gerade einen Angreifer erschlagen, obwohl gar keine mehr da waren.
 Da muss dir doch glatt einer den letzten Schlag geklaut haben!`n`n");
 }
 if ($badguy['diddamage']!=1){
 $goldwin = $badguy['creaturegold']*2;
 $expwin = $badguy['creatureexp']*2;
 output("`\$`n`cAusgezeichneter Kampf!`nDu bekommst eine extra Runde`c`7`n Du verteidigst deine Stadt wirklich als ob es Gold wert wäre!`n");
 $session['user']['gold']+=$goldwin;
 $session['user']['experience']+=$expwin;
 output("`^ `n`nDu findest `\$$goldwin `^Gold `n");
 output("`^Du erhältst `\$$expwin `^Erfahrung `n");
 }else{
 $goldwin=$badguy['creaturegold'];
 $expwin = $badguy['creatureexp'];
 $session['user']['turns']--;
 output("`7 Du hast dein bestes getan um die Stadt zu verteidigen.`n `n Doch weisst du auch das du erst wieder in den Wald kannst, 
 wenn alle Angreifer besiegt sind.`n");
 $session['user']['gold']+=$goldwin;
 $session['user']['experience']+=$expwin;
 output("`^ `n`nDu findest `$ $goldwin `^Gold `n");
 output("`^Du erhältst `$ $expwin `^Erfahrung `n");
 }
 if ($anzahl > 1) {
 addnav("Nochmal Angreifen","dorfangriff.php?op=angriff");
 addnav("Zurück");
 addnav("Zurück zum Stadttor","dorfangriff.php");
 addnav("Zurück zur Stadt","village.php");
 }else{
 addnav("Zurück");
 addnav("Zurück zum Stadttor","dorfangriff.php");
 addnav("Zurück zur Stadt","village.php");
 }
 $badguy=array();
 }elseif ($defeat){
 $session['user']['dorfkampf']++;
 $session['user']['gesamtkampf']++;
 addnews("".$session['user']['name']."`8 wurde von ".($session['user']['sex']?"ihrem ":"seinem ")." `\$".$session['user']['dorfkampf'].".`8 Angreifer niedergeschlagen!");
 $session['user']['alive']=false;
 $session['user']['hitpoints']=0;
 $session['user']['gold']=0;
 $session['user']['experience']*=0.9;
 output("`b`&Du wurdest von `%".$badguy['creaturename']."`& niedergemetzelt!!!`n");
 output("`4Dein ganzes Gold wurde dir abgenommen!`n");
 output("Du kannst morgen weiter kämpfen.");
 addnav("Tägliche News","news.php");
 $session['user']['badguy']="";
 }else{
 fightnav(true,false);
 output("`n");
 switch(e_rand(1,11)){
 case 1:
 output("`b".$badguy['creaturename']."`4 versucht einen billigen Trick.`b`n");
 break;
 case 2:
 output("`b".$badguy['creaturename']."`4 Faucht dich an.`b`n");
 break;
 case 3:
 output("`b".$badguy['creaturename']."`4 tritt dir gegens Schienenbein.`b`n");
 break;
 case 4:
 output("`b".$badguy['creaturename']."`4 knurrt dich an.`b`n");
 break;
 case 5:
 output("`b".$badguy['creaturename']."`4 versucht, dir ein Ohr abzubeissen!`b`n");
 break;
 case 6:
 output("`b".$badguy['creaturename']."`4 schimpft dich einen Feigling!`b`n");
 break;
 case 7:
 output("`b".$badguy['creaturename']."`4 verpasst dir einen Sherriffstern.`b`n");
 break;
 case 8:
 output("`b".$badguy['creaturename']."`4 behauptet, deine Oma kämpft besser!`b`n");
 break;
 case 9:
 output("`b".$badguy['creaturename']."`4 sagt, du kämpfst wie ein Kind!`b`n");
 break;
 case 10:
 output("`b".$badguy['creaturename']."`4 sagt, dass du häslich bist und dass dir deine Mami komische Sachen zum Anziehen gibt!`b`n");
 break;
 case 11:
 output("`b".$badguy['creaturename']."`4 legt einen Striptease hin.`b`n");
 break;
 }
 }
 }
 if ($_GET['op']=="ende"){
 $gold = (e_rand(10000,20000));
 $gems = (e_rand(10,20));
 $exp = (e_rand(1000,2000));
 $charm = (e_rand(3,8));
 addnews("`&".$session['user']['name']."`^ erledigte den letzten Angreifer und ".($session['user']['sex']?"ihr":"ihm")." gebührt der Ruhm, denn ".($session['user']['sex']?"sie":"er")." brachte den Frieden zurück nach Arda!`0!");
 output("`n`n`n `qDu hast den letzten Angreifer erledigt, und bringst den Frieden zurück in die Stadt.`n Dafür verehrt dich die 
 gesamte Stadt.`nDu bekommst von den Stadtältesten für deine Leistungen `^$gold Gold `qund `#$gems Edelsteine`q.`n
 Von den `Qul`\$ti`4ma`~ti`4ven `\$Mäc`Qhten`q bekommst du obendrauf noch `\$$exp Erfahrungspunkte `qund `%$charm Charmpunkte`q.`n`n");
 $session['user']['charm']+=$charm;
 $session['user']['experience']+=$exp;
 $session['user']['gold']+=$gold;
 $session['user']['gems']+=$gems;
 $datage = (e_rand(80,170));
 savesetting("dangreifer","0");
 savesetting("angriff","0");
 savesetting("datage",$datage);
 $wolken="Kalt bei klarem Himmel";
 savesetting("weather",$wolken);
 }
 page_footer();
 ?>