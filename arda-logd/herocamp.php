<?php
/*
Neues Feature zum Neubeginn für Spieler mit vielen DK's -
Alternative zum Schrein der Erneuerung

Erstellt von Trillian - 6.11.2004 - für http://www.the-addicted.de/~spite/logd

Datenbank-Ergänzungen:
ALTER TABLE accounts ADD herotattoo int(4) NOT NULL default '0';

Änderungen in common.php:
vor der Zeile :$beta = (getsetting("beta",0) == 1 || $session['user']['beta']==1);
folgendes einfügen: $ghosts = array(1=>"Schlange,2=>"Fuchs",3=>"Eber",4=>"Adler",5=>"Wolf",6=>"Pferd");
an passender Stelle: addnav("befestigtes Lager","herocamp.php");

Änderungen in hof.php:
Vor: addnav("Bestenlisten");
folgendes einfügen:
addnav("Wahre Helden");
addnav("Helden mit dem Segen der Tiergeister","hof.php?op=ghosts&subop=$subop&page=$page");

Vor:} elseif ($_GET[op] == "gems") {
folgendes einfügen:
} elseif ($_GET[op] == "ghosts") {
 $sql = "SELECT name,herotattoo as data1 FROM accounts WHERE locked=0 and herotattoo>0 ORDER BY herotattoo $order, dragonkills $order, level $order, acctid $order LIMIT $limit";
 if ($_GET[subop] == "least") $adverb = "geringsten";
 else $adverb = "stärksten";
 $title = "Die Krieger mit dem $adverb Segen der Tiergeister";
 $headers = array("Tätowierungen");
 display_table($title, $sql,false,false,$headers,false);

Änderungen in dragon.php:
Vor ,"avatar"=>1 (taucht zweimal auf)
jeweils: ,"herotattoo"=>1 einfügen

Vor while(list($key,$val)=each($session[user][dragonpoints])){
folgendes einfügen:
 $session[user][attack]+=$session[user][herotattoo];
 $session[user][defence]+=$session[user][herotattoo];

Änderungen in bio.php:
Die Zeile: $result = db_query("SELECT login,name,level,sex,... um herotattoo erweitern
Nach : output ("`^Bester Angriff: `@$row[punch]`n");
folgendes einfügen:
if ($row[herotattoo]) {
 output("`^Tätowierungen: ");
 for($i=1; $i<=$row[herotattoo];$i++){
 output("`@$ghosts[$i]");
 if ($i<$row[herotattoo]) output(", ");
 else output(".`n");
 }
}
*/

require_once("common.php");

checkday();

page_header("Heldenlager");
addcommentary();

$drunkenness = array(-1=>"absolut nüchtern",
 0=>"ziemlich nüchtern",
 1=>"kaum berauscht",
 2=>"leicht berauscht",
 3=>"angetrunken",
 4=>"leicht betrunken",
 5=>"betrunken",
 6=>"ordentlich betrunken",
 7=>"besoffen",
 8=>"richtig zugedröhnt",
 9=>"fast bewusstlos"
 );
 $drunk = round($session[user][drunkenness]/10-.5,0);
$cost = array("ale"=>$session['user']['level']*10,"beer"=>$session['user']['level']*15,
 "fire"=>$session['user']['level']*35,"death"=>$session['user']['level']*50);
$drunkinc = array("ale"=>33,"beer"=>40,"fire"=>50,"death"=>75);


if ($_GET["op"]=="enter"){
 output("`c`b`tDas Lager der Helden`b`c`n`n");
 output("`QAls du das Lager betrittst, siehst du eine kleine Gruppe von Wesen unterschiedlicher Rassen um ");
 output("ein Lagerfeuer herumsitzen.`n");

 if ($session['user']['herotattoo']) {
 output("Als die versammelten Helden dich bemerken, grüßen sie dir freundlich zu, und bieten dir einen ");
 output("Platz am Feuer an.`n");
 output("Du bist dir noch nicht ganz sicher, ob du dich zu ihnen setzen willst, oder lieber erst etwas ");
 output("zu trinken besorgen willst.`n");
 output("Vielleicht willst du aber auch dem Zelt des Tätowierers einen Besuch abstatten.`0");
 addnav("Ans Feuer setzen","herocamp.php?op=talk");
 addnav("Zur wandelnden Schenke","herocamp.php?op=drink");
 addnav("Tätowiererzelt","herocamp.php?op=tattoo");
 }
 else {
 output("Dort sitzen gleichermaßen Männer und Frauen, einige von ihnen hast du bereits in der Stadt gesehen, ");
 output("von anderen nur in Legenden gehört. Dies sind alles wahre Helden, und als du sie betrachtest, ");
 output("bemerkst du bei vielen von ihnen eine Tätowierung in Form einer Schlange.`n");
 output("Als dich einer von ihnen bemerkt, und deinen staunenden Blick bemerkt, zeigt er auf ein kleines Zelt ");
 output("am Rande des Lagers.`0`n");
 addnav("Zum Zelt gehen","herocamp.php?op=tattoo");
 }
 addnav("Lager verlassen","herocamp.php?op=leave");
} else if ($_GET["op"]=="leave") {
 output("`QDu verlässt das Lager der Helden und kehrst in die Stadt zurück.`0`n");
 addnav("Weiter","zwergenstadt.php");
} else if ($_GET["op"]=="talk") {
 output("`c`b`4Lagerfeuer`b`c`n`n");
 output("`3Du setzt dich zu den anderen Helden ans Lagerfeuer, und ihr plaudert über eure jüngsten Taten.`0`n`n");
 viewcommentary("herocamp","Geschichten erzählen",20,"erzählt");
 addnav("Lagerfeuer verlassen","herocamp.php?op=enter");

} else if ($_GET["op"]=="drink") {
 output("`c`b`^Zur wandelnden Schenke`b`c`n`n");
 output("`%Du wanderst langsam hinüber zu Khaled, der unter einem schief hängenden Zeltdach mehrere Fässer aufgestellt ");
 output("hat, und gerade dabei ist, einige Gläser zu spülen. Über ihm hängt ein schild mit dem hochtrabenden Namen ");
 output("`^Zur wandelnden Schenke`n");
 output("`%Als Khaled dich bemerkt, schaut er in deine Richtung und fragt :`n");
 output("`5Na, was kann ich heute für dich tun? Und bevor du fragst, ja ich bin mit Khalid verwandt ...`n`n`0");
 if ($drunkenness[$drunk]){
 output("`n`n`7Du fühlst dich ".$drunkenness[$drunk]."`n`n");
 }else{
 output("`n`n`7Du fühlst dich nicht mehr.`n`n");
 }
 output("<a href='herocamp.php?op=drink2&choice=ale'>Ich nehme nur ein Ale</a>`n",true);
 output("<a href='herocamp.php?op=drink2&choice=beer'>Gib mir ein zwergisches Starkbier</a>`n",true);
 output("<a href='herocamp.php?op=drink2&choice=fire'>Ich will ein Glas Höllenfeuer</a>`n",true);
 output("<a href='herocamp.php?op=drink2&choice=death'>Ich wähle den flüssigen Tod</a>`n",true);
 addnav("Ale - ".$cost["ale"],"herocamp.php?op=drink2&choice=ale");
 addnav("Starkbier - ".$cost["beer"],"herocamp.php?op=drink2&choice=beer");
 addnav("Höllenfeuer - ".$cost["fire"],"herocamp.php?op=drink2&choice=fire");
 addnav("flüssiger Tod - ".$cost["death"],"herocamp.php?op=drink2&choice=death");
 addnav("Zurück zum Platz","herocamp.php?op=enter");
 addnav("","herocamp.php?op=drink2&choice=ale");
 addnav("","herocamp.php?op=drink2&choice=beer");
 addnav("","herocamp.php?op=drink2&choice=fire");
 addnav("","herocamp.php?op=drink2&choice=death");
} else if ($_GET["op"]=="drink2"){
 if (!$_GET["choice"]) redirect("herocamp.php?op=drink");
 $choice = $_GET["choice"];
 if ($session['user']['gold']<$cost[$choice]) {
 output("`%Khaled schaut dich wütend an `5Bestell dir nichts, was du nicht auch bezahlen kannst!`n");
 output("`%Er schaut dich noch eine Weile finster an, bevor er sich wieder seinen Gläsern zuwendet.`0");
 } else if ($session['user']['drunkenness']+$drunkinc[$choice]>=100){
 output("`%Khaled lächelt dich müde an, und sagt, dass du das heute sicher nicht mehr vertragen wirst, ");
 output("und du lieber etwas schwächeres versuchen solltest ... Vielleicht ein Glas `&Milch`0");
 } else {
 $session[user][gold]-=$cost[$choice];
 $session[user][drunkenness]+=$drunkinc[$choice];
 if ($choice=="ale") {
 output("Khaled nimmt ein Glas und schenkt schäumendes Ale aus einem angezapften Fass hinter ihm ein. ");
 output("Er gibt dem Glas Schwung und es rutscht über die improvisierte Theke, wo du es mit deinen Kriegerreflexen fängst. ");
 output("`n`nDu drehst dich um, trinkst dieses herzhafte Gesöff auf ex.`n`0");
 switch(e_rand(1,3)){
 case 1:
 case 2:
 output("`&Du fühlst dich gesund!");
 $session[user][hitpoints]+=round($session[user][maxhitpoints]*.1,0);
 break;
 case 3:
 output("`&Du fühlst dich lebhaft!");
 $session[user][turns]++;
 }
 $session[bufflist][101] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
 } else if ($choice=="beer") {
 output("Khaled nimmt ein Glas und füllt es mit dunklem zwergischen Starkbier aus einem Fass rechts neben ihm . ");
 output("Er gibt dem Glas Schwung und es rutscht über die improvisierte Theke, wo du es mit deinen Kriegerreflexen fängst. ");
 output("`n`nDu drehst dich um, trinkst dieses starke Gesöff in einem langen Zug.`n`0");
 switch(e_rand(1,3)){
 case 1:
 case 2:
 output("`&Du fühlst dich gesund!");
 $session[user][hitpoints]+=round($session[user][maxhitpoints]*.15,0);
 break;
 case 3:
 output("`&Du fühlst dich lebhaft!");
 $session[user][turns]++;
 }
 $session[bufflist][101] = array("name"=>"`#Rausch","rounds"=>15,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.3,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
 } else if ($choice=="fire") {
 output("Khaled nimmt ein Glas, geht zu einem kleinen Fass an seiner rechten Seite, und fült es vorsichtig ");
 output("mit einer klaren Flüssigkeit. Er schiebt dir das Glas behutsam über die improvisierte Theke.`n");
 output("Du atmest kurz durch, und stürzst den Inhalt des Glases anschließend in einem Zuge herunter.`n");
 switch(e_rand(1,7)){
 case 1:
 case 2:
 case 3:
 output("`&Du fühlst dich gesund!");
 $session[user][hitpoints]+=round($session[user][maxhitpoints]*.2,0);
 $session[bufflist][101] = array("name"=>"`#schwerer Rausch","rounds"=>20,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.35,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
 break;
 case 4:
 $session['user']['turns']++;
 case 5:
 case 6:
 output("`&Du fühlst dich lebhaft!");
 $session[user][turns]++;
 $session[bufflist][101] = array("name"=>"`#schwerer Rausch","rounds"=>20,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.35,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
 break;
 case 7:
 output("`&Du fühlst dich gar nicht gut!");
 $session[user][turns]--;
 $session[user][hitpoints]-=round($session[user][maxhitpoints]*.2,0);
 if ($session[user][hitpoints]<1) $session[user][hitpoints]=1;
 $session[bufflist][101] = array("name"=>"`4übler `#Rausch","rounds"=>20,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>0.8,"roundmsg"=>"Du bist völlig berauscht.","activate"=>"offense");

 }
 }else {
 output("Khaled nimmt ein Glas, stellt es auf den Tresen und holt vorisichtig eine dunkle Flasche unter "); output("mit einer klaren Flüssigkeit. Er schiebt dir das Glas behutsam über die improvisierte Theke.`n");
 output("der Theke hervor. Langsam lässt er eine rötliche Flüssigkeit in das Glas laufen .`n");
 output("Als er die Flasche absetzt läuft ein einzelner Tropfen die Flasche herab, und als er die ");
 output("Holzplatte der Theke berührt kräuselt sich eine kleine Rauchfahne in die Höhe.`n");
 output("Du schluckst einmal kurz, aber jetzt gibt es kein Zurück mehr, willst du nicht dein Gesicht ");
 output("verlieren. Du nimmst deinen Mut zusammen, ergreifst das Glas und leerst es so schnell du nur kannst.`n");
 switch(e_rand(1,7)){
 case 1:
 case 2:
 case 3:
 output("`&Du fühlst dich gesund!");
 $session[user][hitpoints]+=round($session[user][maxhitpoints]*.25,0);
 $session[bufflist][101] = array("name"=>"`#schwerer Rausch","rounds"=>25,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.4,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
 break;
 case 4:
 $session['user']['turns']++;
 case 5:
 $session['user']['turns']++;
 case 6:
 case 7:
 output("`&Du fühlst dich lebhaft!");
 $session[user][turns]++;
 $session[bufflist][101] = array("name"=>"`#schwerer Rausch","rounds"=>25,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.4,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
 break;
 case 8:
 case 9:
 case 10:
 output("`&Du fühlst dich gar nicht gut!");
 $session[user][turns]--;
 $session[user][hitpoints]-=round($session[user][maxhitpoints]*.25,0);
 if ($session[user][hitpoints]<1) $session[user][hitpoints]=1;
 $session[bufflist][101] = array("name"=>"`4übler `#Rausch","rounds"=>25,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>0.7,"roundmsg"=>"Du bist völlig berauscht.","activate"=>"offense");
 break;
 case 11:
 output("`&Du fühlst dich absolut elend!");
 $session[user][turns]=0;
 $session[user][hitpoints]=1;
 $session[bufflist][101] = array("name"=>"`4übler `#Rausch","rounds"=>35,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>0.3,"roundmsg"=>"Dein Rausch lässt dich deinen Gegner kaum sehen.","activate"=>"offense");
 break;
 case 12:
 output("`&Du fühlst dich `4TOT!`nDu verlierst dein gesamtes Gold und 10% deiner Erfahrung.`0");
 $session[user][alive]=false;
 $session[user][hitpoints]=0;
 $session[user][gold]=0;
 $session[user][experience]*=0.9;
 addnews($session[user][name]." verkraftete den flüssigen Tod nicht.");
 addnav("Zu den News","news.php");
 }
 }
 }
 if ($session[user][alive]) addnav("Zurück","herocamp.php?op=drink");

} else if ($_GET["op"]=="tattoo") { 
 output("`c`b`VDas Zelt des Tätowierers`b`c`n`n"); 
 output("`9Du betrittst das Zelt am Rande des Platzes, und schaust dich im Inneren um. Du siehst viele Kisten "); 
 output("gefüllt mit fremdartigen Fläschchen und Dosen. Auf einem Tisch stehen in einem Gestell einige Nadeln. "); 
 output("Hinter dem Tisch siehst du einen alten Mann sitzen, der bei deinem Eintreten den Kopf hebt."); 
 if ($session[user][herotattoo]){ 
 output("`n`#Ah, willkommen zurück mein".($session[user][sex]?"e Tochter":" Sohn")."`#, lass mich deine "); 
 output("Tätowierungen sehen. Hm, ja die sehen gut verheilt aus.`n"); 
 if($session[user][dragonkills]<50) { 
 output("Aber leider hast du dir noch keine weitere Tätowierung verdient."); 
 addnav("Geschichte hören","herocamp.php?op=story"); 
 } else { 
 output("Wenn du willst, könnte ich dir eine weitere Tätowierung stechen."); 
 addnav("Tätowieren lassen","herocamp.php?op=maketattoo"); 
 } 
 } else {
 if ($session[user][dragonkills]>=50) {
 output("`n`#Ah, willkommen mein".($session[user][sex]?"e Tochter":" Sohn")."`#, ich habe dich bereits ");
 output("erwartet. Tritt doch ein, und lass mich dir eine Geschichte erzählen.`n");
 output("Vor langer Zeit lebten in diesem Wald die Tiergeister, sozusagen die Prototypen der Tiere die ");
 output("wir heute kennen, zu jeder Tierart gab es damals einen Geist, aber es gab auch andere Geister ");
 output("deren Tierarten schon lange ausgestorben sind. Mit dem Auftauchen der humanoiden Rassen verschwanden ");
 output("die Tiergeister, aber hier in diesem Wald kann man ihre Kraft immernoch spüren, auch wenn sie schon ");
 output("lange nicht mehr hier sind. Die Tiergeister schenken jenen ihre Gunst, die sich durch große Taten ");
 output("verdient gemacht haben, und doch bereit sind, von neuem zu beginnen.`n");
 output("Die Verbundenheit zu den Tiergeistern wird durch magische Tätowierungen besiegelt, du hast draußen ");
 output("vielleicht schon einige davon gesehen. Die Kunst, diese Tätowierungen anzufertigen wird in meiner ");
 output("Familie von Generation zu Generation weitergegeben.`n");
 output("Du hast dir als Drachentöter einen großen Namen gemacht, ich könnte dir eine Tätowierung stechen, ");
 output("falls du dies wünscht. Bedenke aber, dass du große Opfer dafür bringen musst, um den Segen der ");
 output("Tiergeister zu empfangen. Auch all deine weltlichen Besitztümer wirst du opfern müssen.");
 addnav("Tätowieren lassen","herocamp.php?op=maketattoo");
 }else{
 output("Der Mann schaut dich etwas verdutzt an. `3\"`#Wie bist du denn hier hereingekommen ?`3\" ");
 }
 }
 addnav("Zurück","herocamp.php?op=enter");
} else if ($_GET["op"]=="story") {
 output("`n`#Ah, willkommen mein".($session[user][sex]?"e Tochter":" Sohn")."`#, ich habe dich bereits ");
 output("erwartet. Tritt doch ein, und lass mich dir eine Geschichte erzählen.`n");
 output("Vor langer Zeit lebten in diesem Wald die Tiergeister, sozusagen die Prototypen der Tiere die ");
 output("wir heute kennen, zu jeder Tierart gab es damals einen Geist, aber es gab auch andere Geister ");
 output("deren Tierarten schon lange ausgestorben sind. Mit dem Auftauchen der humanoiden Rassen verschwanden ");
 output("die Tiergeister, aber hier in diesem Wald kann man ihre Kraft immernoch spüren, auch wenn sie schon ");
 output("lange nicht mehr hier sind. Die Tiergeister schenken jenen ihre Gunst, die sich durch große Taten ");
 output("verdient gemacht haben, und doch bereit sind, von neuem zu beginnen.`n");
 output("Die Verbundenheit zu den Tiergeistern wird durch magische Tätowierungen besiegelt, du hast draußen ");
 output("vielleicht schon einige davon gesehen. Die Kunst, diese Tätowierungen anzufertigen wird in meiner ");
 output("Familie von Generation zu Generation weitergegeben.`n");
 addnav("Zurück","herocamp.php?op=tattoo");
} else if ($_GET["op"]==maketattoo) {
 output("Bist du dir ganz sicher, dass du dir ein Tiergeist-Tattoo stechen lassen willst ?`n");
 output("Du wirst wieder als ".($session[user][sex]?"Bauernmädchen":"Bauernjunge")." erwachen, nur mit deinen ");
 output("Tätowierungen und deinen gesammelten Donation-Points.");
 output("`n`n`@Es wird kein Zurück geben, also überlege dir gut ob du alles aufgeben und völlig von vorne anfangen möchtest.`@`n`n");
 addnav("JA - ich bin bereit","herocamp.php?op=confirm");
 addnav("NEIN - zurück zum Heldenlager","herocamp.php?op=enter");
}else if ($_GET["op"]=="confirm") {
 $session[user][herotattoo]++;
 $ghost = $ghosts[$session[user][herotattoo]];
 if ($ghost == "") $ghost="Drache";
 addnews("`#".$session[user][name]."`# hat seinem bisherigen Leben ein Ende gesetzt und einen Neuanfang beschlossen. Der Segen des/der ".$ghost." wird ".($session[user][sex]?"sie":"ihn")." dafür ab nun begleiten");
 if (!$session[user][ctitle]){
 $n=$session[user][name];
 $session[user][name]=($session[user][sex]?"Diebin":"Dieb").substr($n,strlen($session[user][title]));
 }
 $session[user][title]=($session[user][sex]?"Diebin":"Dieb");
 $session[user][level]=1;
 $session[user][maxhitpoints]=10+(3*$session[user][herotattoo]);
 $session[user][attack]=1+(3*$session[user][herotattoo]);
 $session[user][defence]=1+(3*$session[user][herotattoo]);
 $session[user][gold]=getsetting("newplayerstartgold",0);
 $session[user][goldinbank]=0;
 $session['user']['gemsinbank']=0;
 $session[user][experience]=0;
 $session[user][gems]=0;
 $session[user][age]=0;
 $session[user][dragonpoints]="";
 $session[user][dragonkills]=0;
 $session[user][drunkenness]=0;
 $session[user][specialty]=0;
 $session[user][darkarts]=0;
 $session[user][thievery]=0;
 $session[user][magic]=0;
 $session[user][weapon]="Fäuste";
 $session[user][armor]="Lumpen";
 $session[user][hashorse]=0;

 $session[user][bufflist]="";
 if ($session[user][marriedto]>0 && $session[user][marriedto]<4294967295 && $session[user][charisma]==4294967295){
 $sql="UPDATE accounts SET marriedto=0,charisma=0 WHERE acctid=".$session[user][marriedto]."";
 db_query($sql);
 systemmail($session[user][marriedto],"`6".$session[user][name]." ist nicht mehr der selbe`0","`6{$session['user']['name']}`6 hat sich ein neues Leben gegeben. Ihr seid nicht länger verheiratet.");
 }
 $session[user][charisma]=0;
 $session[user][marriedto]=0;
 $session[user][weaponvalue]=0;
 $session[user][armorvalue]=0;
 $session[user][resurrections]=0;
 $session[user][weapondmg]=0;
 $session[user][armordef]=0;
 $session[user][charm]=0;
 $session[user][race]=0;
  $session[user][battlepoints]=0;
 $session[user][dragonage]=0;
 $session[user][deathpower]=0;
 $session[user][punch]=1;
 debuglog("REBIRTH ".date("Y-m-d H:i:s")."");
 $session[user][bounty]=0;
 /*if ($session[user][house]){
 if ($session[user][housekey]){
 $sql="UPDATE houses SET owner=0,status=3 WHERE owner=".$session[user][acctid]."";
 }else{
 $sql="UPDATE houses SET owner=0,status=4 WHERE owner=".$session[user][acctid]."";
 }
 db_query($sql);
 } */
// $session[user][house]=0;
// $session[user][housekey]=0;
 /*$sql="UPDATE items SET owner=0 WHERE owner=".$session[user][acctid]."";
 db_query($sql);
 $sql="DELETE FROM items WHERE owner=".$session[user][acctid]."";
 db_query($sql);*/
 $session[user][laston]="";
 $session[user][lasthit]=date("Y-m-d H:i:s",strtotime("-".(86500/getsetting("daysperday",4))." seconds"));
 output("`n`6Du stimmst zu.`nNachdem der alte Mann dir ein Abbild einer/s ".$ghost." auf den Körper gestochen ");
 output("hat, führt er dich zu einer Waldlichtung, auf der du deine gesamten Besitztümer ablegst.`n");
 output("Du fühlst, wie dich langsam eine unbekannte Kraft durchstömt, aber gleichzeitig merkst du, wie ");
 output("langsam deine Lebenskraft, deine Erfahrung und schließlich all deine Fähigkeiten ");
 output("schwinden. Du vergisst dein ganzes bisheriges Leben. Du fällst in eine lange Ohnmacht...");
 addnav("Zurück zur stadt","zwergenstadt.php");

} else {
 output("`c`b`THeldenlager`b`c`n`n");
 output("`QAls du gemütlich durch den Wald spazierst, erblickst du einen Ring aus Baumstämmen, die eine hohe Mauer");
 output(" bilden. Du gehst langsam an dieser Wand entlang, und siehst schließlich eine Unterbrechung der");
 output(" Wand, offensichtlich ein Tor, an dem drei Halb-Orks mit großen Äxten lehnen.`n");
 output("Als sie dich bemerken, stellen sie sich sofort aufrecht hin, greifen bedrohlich zu ihren Äxten und ");
 output("mustern dich misstrauisch.`n`n");
 if ($session['user']['herotattoo']){
 output("Als du ihnen deine Tätowierung zeigst, machen die Halb-Orks sofort respektvoll Platz, und bitten dich, ");
 output("doch einzutreten, und dich wie zu Hause zu fühlen.`n`0");
 addnav("Heldenlager betreten","herocamp.php?op=enter");
 } else if($session['user']['dragonkills']>=50){
 output("Die Halb-Orks mustern dich prüfend von Kopf bis Fuß, schließlich spricht einer von ihnen:`n");
 output("`2Unsere Meister schon viel haben von Euch gehört, ihr großer Drachentöter, oder? `n");
 output("`2Meister wollen euch kennenlernen, Ihr eintreten dürft.`n");
 output("`QMit diesen Worten, machen die Halb-Orks den Weg zum Tor frei.`0`n");
 addnav("Heldenlager betreten","herocamp.php?op=enter");
 } else {
 output("Die Halb-Orks mustern dich prüfend von Kopf bis Fuß, schließlich spricht einer von ihnen: `n");
 output("`2Du müssen noch mehr leisten, um Aufmerksamkeit von Meistern zu erlangen. ");
 output("Du hier nicht willkommen bist. Du besser gehen, bevor wir vertreiben dich.`n`n");
 output("`QNach einem kurzen Blick auf die scharfen Äxte der Halb-Orks beschließt du, lieber später ");
 output("wiederzukommen, wenn sie vielleicht entgegenkommender sind ... Und wenn du vielleicht diese ");
 output("mysteriösen `6Meister `Qausreichend beeindruckt hast ... wodurch auch immer ...`0");
 }
 addnav("Zurück zur Stadt","zwergenstadt.php");
}

page_footer();
?> 