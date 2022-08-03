<?php

// Kapelle 1.0 by lordraven
// aktuelle Version in Silienta 1.9
// Die kleine Kapelle wird benötigt, um Hochzeiten RP gemäß durorchzuführen.
// Dafür muß die Automatik in gardens.php, bei entsprechender Anzahl flirts zu heiraten geändert werden
// Die Kapelle registriert, wenn Paare 5 mal oder öfter miteinander geflirtet haben und erstellt eine
// Aufgebotsliste
// Superusern oder ernennten Priestern ist es möglich, die Hochzeit per Knopfdruck zu vollenden
// Dann wird die gesamte Zeremonie gesichert, ein Eintrag in die Hochzeitstabellen gemacht
// und man kann sich die Hochzeit in der Historie immer wieder ansehen
// Da alle aktuellen Kommentare in der Kapelle gesichert werden, muß vorher aufgeräumt werden. Diese
// Funktion steht Superusern oder dem Priester zur Verfügung.
//
// Erstellt am 28.05.2004  Lord Raven
// geändert letztmalig 21.10.06 Rikkarda www.silienta-logd.de
// Version 1.6 Kindertool
// Version 1.1 - Klingelbeutel hinzugefügt, 31.05.2004 Lord Raven
// Version 1.2 - Priester dürfen sich umziehen verändert 29.09.2004 Beleggrodion
// Version 1.3 - Priester können nun direkt in der Kapelle befördert/entlassen werden - Hadriel
// Version 1.4 - Scheidungs / Standesamt hinzugefügt - Hadriel
// Version 1.5 - Verlobungsmodifikationen - Rikkarda@silienta-logd.de Einbau siehe gardens.php
// Version 1.7 - Änderungen und Modifikationen an den Hochzeitslisten by Rikkarda
// Version 1.8 - Hochzeitsdatum wird nun in der Liste korrekt dargestellt by Rikkarda

// Version 1.9 - Hochzeitsliste wird nun korrekt dargestellt was /em und sagt betrifft by Rikkarda



// Contact: Admin@Silienta-logd.de oder http://www.silienta-logd.de
//
/* sql: //CREATE TABLE `wedding` (
// `wnr` int(11) NOT NULL auto_increment,
// `acctid1` int(11) NOT NULL default '0',
// `name1` varchar(255) NOT NULL default '',
// `acctid2` int(11) NOT NULL default '0',
// `name2` varchar(255) NOT NULL default '',
// `date` varchar(15) NOT NULL default '',
// `prayer` varchar(255) NOT NULL default '',
// PRIMARY KEY (`wnr`(
//) TYPE=MyISAM AUTO_INCREMENT=52 ;
//
//CREATE TABLE `wedding_text` (
//`wnr` int(11) NOT NULL default '0',
// `name` varchar(255) NOT NULL default '',
// `text` Text(leer) NOT NULL default '',
// KEY `wnr` (`wnr`(
//) TYPE=MyISAM;
//ALTER TABLE `accounts` ADD `prayer` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL
end sql */

define('LIB_MESSAGEBOARD',true); // by Eliwood

require_once "common.php";
addcommentary();
get_special_var();
$prayer=false;
if ($session[user][prayer] || $session[user][superuser]>=3){
    $prayer=true;
}

page_header("Die kleine Kapelle von Arda");
//if ($session[user][ort]!=12){
   // $session[user][ort]=12;
   
//}
if ($_GET[op]=="eintreten"){
    output("`c`i`9N`3i`#c`&ht weit vom Garten entfernt, steht ein großes, aus Stein gebautes Gebäude. Du öffnest die große, schwere Holztür, welche niemals verschlossen ist und trittst hin`#e`3i`9n`n

`9I`3m`# I`&nneren umfängt dich ein Ruhe, welche man nur selten vorfindet. Die Wände sind einfach weiß gestrichen, ab und zu schaut dich ein Engel mit gütigen und treuen Auge`#n `3a`9n`n

`9D`3i`#e`& Decke ist sehr hoch und voller Malereien. Kleine süße Engel schweben an der Decke und schauten freundlich he`#r`3a`9b. `n

 
`9D`3u`# g`&ehst weiter und läufst über einen breiten, sehr langen, roten Teppich. Während du läufst streichst du mit der Hand über die Bänke, die hier zahlreich stehen. Der Teppich endet vor einem kleinen Podest, auf dem ein Altar steht. Zwei große Kerzen und ein riesiger Blumenstrauß schmücken den Tisch hinter dem Al`#t`3a`9r.`n
 
`9D`3i`#e`& großen klaren Fenster geben genügend Licht, sodass du alles klar sehen kannst.`n
 
`9E`3i`#`&ne Treppe führt etwas weiter nach oben. Doch ist dort nichts interessantes, bis auf eine Orgel, welche die Hochzeiten immer begleitet. Doch kannst du von dort oben das Innere der Kirche überblicken und dir so noch einen letzten Eindruck verschaffen, ehe du die Treppe wieder herunter gehst und die Kirche verlä`#s`3s`9t.`i`c
`n");
    if ($session[user][charisma]==4294967295){
        output("Unwillkürlich erinnerst Du Dich an Deine eigene Hochzeit und würdest am liebsten nochmal
            heiraten.");
    }else{
        output("Möchtest Du nicht auch langsam mal hier stehen und heiraten?");
    }
    output("`n`n`0Leise hörst Du die Anwesenden miteinander flüstern:`n`n");
    viewcommentary("Kapelle","Flüster zu den Anwesenden",20,"sagt");
    addnav("Paare mit Aufgebot","chapel.php?op=merrylist");
    addnav("Bisherige Hochzeiten","chapel.php?op=hochzeiten");
    addnav("Alle Priester","chapel.php?op=showpriest");
 //   addnav("Mitteilungen","chapel.php?op=board");
    addnav("Klingelbeutel","chapel.php?op=klingelbeutel");
    addnav("Schrein des Priesters","chapel.php?op=priester");
    addnav("Scheidungsamt","chapel.php?op=scheidungsamt");
    addnav("Taufbecken","kindersystem-chapel.php");
   // addnav("Aktualisieren","chapel.php");
}else if ($_GET[op]=="merrylist"){
   $sql="SELECT acctid,name,marriedto FROM accounts WHERE sex=0 AND charisma>=5 AND charisma<100 ORDER BY acctid DESC";
        output("`c`b`&Paare dieser Welt mit bestelltem Aufgebot`b`c`n");
    if ($session[user][superuser]>=4){
            output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bAktion`b</td><td></td><td><img src=\"images/female.gif\">`b Name`b</td><td></td><td><img src=\"images/male.gif\">`b Name`b</td></tr>",true);
    }else{
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td><img src=\"images/female.gif\">`b Name`b</td><td></td><td><img src=\"images/male.gif\">`b Name`b</td></tr>",true);
    }
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
              output("<tr><td colspan=4 align='center'>`&`iIn diesem Land gibt es keine Paare`i`0</td></tr>",true);
        }
        for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
            $sql2 = "SELECT acctid,name,marriedto FROM accounts WHERE sex=1 AND charisma>=5 AND charisma<100 AND marriedto = ".$row[acctid]."";
            $result2 = db_query($sql2) or die(db_error(LINK));
        if (db_num_rows($result2)==0){
            //output("<tr class='".($i%2?"trlight":"trdark")."'><td>`&noch nicht`0</td><td>`( und `0</td><td>`&",true);
            //output("$row[name]`0</td></tr>",true);
        }else{
                    $row2 = db_fetch_assoc($result2);
            if ($prayer){
                      output("<tr class='".($i%2?"trlight":"trdark")."'>
                    <td>[ <a href='chapel.php?op=heirat&p1=$row2[acctid]&p2=$row[acctid]'>Heirat</a> ]</td>
                    <td>[ <a href='chapel.php?op=entf&p1=$row2[acctid]&p2=$row[acctid]'>Entfernen</a> ]</td>
                    <td>`&$row2[name]`0</td><td>`( und `0</td><td>`&",true);
                    output("$row[name]`0</td></tr>",true);
                addnav("","chapel.php?op=heirat&p1=$row2[acctid]&p2=$row[acctid]");
                addnav("","chapel.php?op=entf&p1=$row2[acctid]&p2=$row[acctid]");
            }else{
                      output("<tr class='".($i%2?"trlight":"trdark")."'><td>`&$row2[name]`0</td><td>`( und `0</td><td>`&",true);
                    output("$row[name]`0</td></tr>",true);
            }
        }
        }
        output("</table>",true);
    addnav("Zum Innenraum","chapel.php");

}else if ($_GET[op]=="entf"){
    $sql="UPDATE accounts SET charisma=0, marriedto=0,  WHERE acctid = ".$_GET[p1]." OR acctid = ".$_GET[p2]."";
    db_query($sql);
    output("Das Aufgebot wurde gelöscht");
    addnav("L?Zur Liste","chapel.php?op=merrylist");
    addnav("Zum Innenraum","chapel.php");
}/*else if ($_GET[op]=="board"){
    $boardid = "chapel";
    if($_GET['act'] == "add1") {
        if (addmessageboard()) {
            output("In der Hoffnung, dass jeder von deiner wichtigen Notiz kenntnis nimmt, hängst du sie gut sichtbar an.");
        }
        elseif ($doublepost) {
            output("Es hängt schon ein solcher Zettel.");
        }
        else {
            output("Du kramst einen Zettel und einen Stift hervor und schreibst ein paar Zeilen.`n`n");
            formmessageboard($boardid,'Notiz hinterlassen');
        }
        addnav("Mitteilungen ansehen","chapel.php?op=board");
    }else{
        output("Neugierig betrachtest du die Wand, an der Mitteilungen angebracht werden können.`n");
        output("Üblicherweise werden hier Termine der Hochzeiten angekündigt.`n");
        viewmessageboard($boardid,'`nFolgende Mitteilungen sind an der Wand angeschlagen.','`nEs sind keine Mitteilungen angebracht');
        addnav("Selber hinzufügen","chapel.php?op=board&act=add1");
    }
    addnav("Zum Innenraum","chapel.php");


} */ else if ($_GET[op]=="heirat"){
//    $hochzeitsdatum = "".dorftag(0).". Tag ".dorfjahr(0)."";
    //$hochzeitsdatum =     $hochzeitsdatum = "".getsetting("wochentag",0)." - ".getsetting("monate",0)." - ".getsetting("jahre",0)." ";
    $hochzeitsdatum=getsetting("gamedate",0) ;//by Rikkarda
    $klingelbeutel=getsetting("klingelbeutel",0);
    $fueruser1 = floor($klingelbeutel / 2);
    $fueruser2 = $klingelbeutel - $fueruser1;
    $sql="SELECT acctid,name FROM accounts WHERE acctid=".$_GET[p1]." OR acctid=".$_GET[p2]." order by sex ASC";
    $result=db_query($sql) or die(sql_error($sql));
    $count=db_num_rows($result);
    for ($i=0;$i<$count;$i++){
        $row=db_fetch_assoc($result);
        $names[]="`^$row[name]";
        $acctids[]=$row[acctid];
    }
    db_free_result($result);
    //Eintragen in Hochzeiten Tabelle
    $sql="INSERT INTO wedding (acctid1,name1,acctid2,name2,date,prayer) VALUES (".$acctids[0].",'".$names[0]."',".$acctids[1].",'".$names[1]."','".$hochzeitsdatum."','".$session[user][login]."')";
    db_query($sql);
    //Auslesen der Hochzeitsnummer
    $sql="SELECT wnr FROM wedding WHERE acctid1=".$acctids[0]." AND acctid2=".$acctids[1]." AND date='".$hochzeitsdatum."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    //Nun die Kapellen Kommentare auslesen - alle - deswegen muß vor einer Hochzeit aufgeräumt sein
    $sql="SELECT author,comment FROM commentary WHERE section='Kapelle' ORDER BY commentid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    $count=db_num_rows($result);
    for ($i=0;$i<$count;$i++){
        $row2=db_fetch_assoc($result);
        $sql2="SELECT name FROM accounts WHERE acctid=".$row2[author]."";
        $result2 = db_query($sql2) or die(db_error(LINK));
        $row3=db_fetch_assoc($result2);
        $row2[comment]=str_replace("'","",$row2[comment]);
        $sql3="INSERT INTO wedding_text (wnr,name,text) VALUES (".$row[wnr].",'".$row3[name]."','".$row2[comment]."')";
        db_query($sql3);
    }
    //Nun Hochzeit durchführen wenn alles geklappt hat
        set_special_var("charisma","4294967295","0",$_GET[p1],$_GET[p2],"Heirat");
    set_special_var("charisma","4294967295","0",$_GET[p2],$_GET[p1],"Heirat");
    set_special_var("charm","1","0",$_GET[p1],"0"," ");
    set_special_var("charm","1","0",$_GET[p2],"0"," ");
    set_special_var("seenlover","1","0",$_GET[p1],"0"," ");
    /*set_special_var("verlobt","Y","N",$_GET[p2],"N"," ");
        set_special_var("verlobt","Y","N",$_GET[p1],"N"," ");
   */ set_special_var("seenlover","1","0",$_GET[p2],"0"," ");
    set_special_var("donation","1","0",$_GET[p1],"0"," ");
    set_special_var("donation","1","0",$_GET[p2],"0"," ");
    set_special_var("goldinbank","".$fueruser1."","0",$_GET[p1],"0"," ");
    set_special_var("goldinbank","".$fueruser2."","0",$_GET[p2],"0"," ");
        addnews("`%".$names[0]." `&und `%$names[1]`& haben heute feierlich den Bund der Ehe geschlossen und haben
        als Geschenk der Gemeinde `^".$klingelbeutel." Gold `&erhalten!");
    systemmail($_GET[p1],"`%Hochzeit!`0","`&Du bist den Bund der Ehe mit `^".$names[0]."`& eingegangen");
    systemmail($_GET[p2],"`%Hochzeit!`0","`&Du bist den Bund der Ehe mit `^".$names[1]."`& eingegangen");
    output("Die Hochzeit zwischen $names[0] und $names[1] wurde vollzogen");
    savesetting("klingelbeutel","0");
    savesetting("innsaal","1");
            $sql4="DELETE FROM commentary WHERE section='Kapelle'";
        db_query($sql4);
    addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="hochzeiten"){
    $sql="SELECT wnr,date,name1,name2,prayer FROM wedding ORDER BY wnr ASC";
    output("`c`b`&Bisherige Hochzeiten in dieser Kapelle`b`c`n`n`n`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td width='100' height='40'>`b Datum `b</td><td><img src=\"images/male.gif\">`b Name`b</td><td width='40'></td><td><img src=\"images/female.gif\">`b Name`b</td><td>`b Priester`b</td><td></td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
              output("<tr><td colspan=4 align='center'>`&`iEs gab bisher keine Hochzeiten`i`0</td></tr>",true);
        }
        for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td align='left' width='100' height='40'>`&$row[date]</td><td>`&$row[name1]`0</td><td width='40'>`( und `0</td><td>`&",true);
            output("$row[name2]`0&nbsp;&nbsp;</td><td>`&$row[prayer]`0</td>",true);
       output("<td> [ <a href='chapel.php?op=hochzeitsliste&num=$row[wnr]'>Liste</a> ] </td>",true);
       addnav("","chapel.php?op=hochzeitsliste&num=$row[wnr]");
       //by Rikkarda für das löschen der Listen
        if ($session[user][superuser]>=4) {
        output(" <td> [ <a href='chapel.php?op=listeweg&num=$row[wnr]'>Löschen</a> ] </td></tr>",true);
        addnav("","chapel.php?op=listeweg&num=$row[wnr]");
         }else{
         output ("</tr>",true);
          
         //end
        
    
    }
    }
    output("</table>",true);
    
    addnav("Zum Innenraum","chapel.php");
    
}else if ($_GET[op]=="hochzeitsliste"){
    output("`c`b`&Schreiberlisten zur Hochzeit`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td></td><td>`b Kommentare`b</td></tr>",true);
    $ppp=20; // Player Per Page +1 to display
    if (!$_GET[limit]){
        $page=0;
    }else{
        $page=(int)$_GET[limit];
        addnav("Vorherige Übersicht","chapel.php?op=hochzeitsliste&num=".$_GET[num]."&limit=".($page-1)."");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $sql="SELECT name,text FROM wedding_text WHERE wnr=".$_GET[num]." LIMIT $limit";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","chapel.php?op=hochzeitsliste&num=".$_GET[num]."&limit=".($page+1)."");
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Listen`i`0</td></tr>",true);
    }else{
        for ($i=0;$i<db_num_rows($result);$i++){
            $row2 = db_fetch_assoc($result);

            /*korrekte /ms darstellung für www.silienta-logd.de by Rikkarda@silienta-logd.de*/

            $name = str_replace("`0","",$row2['name']);

            $namestrlen = strlen($name);

            $lastchar = $name{$namestrlen-1};

            $name = $name.($lastchar == "s" || $lastchar == "x"?"'`0":"s`0");

            /*end*/
            $row2[text] = str_replace("/me",$row2[name],$row2[text]);

            $row2[text] = str_replace("/X","",$row2[text]); //by Rikkarda@silienta-logd.de

            $row2[text] = str_replace("/ms",$name,$row2[text]); //by Rikkarda@silienta-logd.de
            $row2[text] = str_replace("::",": ",$row2[text]);
           
            if (substr($row2[text],0,1)==":"){
                $row2[text] = str_replace(":","".$row2[name]." ",$row2[text]);
            }
            output("<tr><td valign='middle'>o&nbsp;</td><td>$row2[text]</td></tr>",true);
        }
    }
    output("</table>",true);
    addnav("Hochzeitsübersicht","chapel.php?op=hochzeiten");
    addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="putzen"){
    if (!$prayer){
    }else{
        $sql="DELETE FROM commentary WHERE section='kapelle'";
        db_query($sql);
        output("`nDie Kapelle wurde aufgeräumt");
    }
    addnav("Zum Innenraum","chapel.php");
    //Hochzeitslisten löschen by Rikkarda@silienta-logd.de für Götter hier su4
    }else if ($_GET[op]=="listeweg"){
     output("`c`b`&Schreiberlisten zur Hochzeit`b`c`n");
    $sql="DELETE FROM wedding_text WHERE wnr=".$_GET[num]." ";

   
    db_query($sql);
    $sql2="DELETE FROM wedding WHERE wnr=".$_GET[num]." ";

    
    db_query($sql2);
    output ("Liste gelöscht");
    addnav("Hochzeitsübersicht","chapel.php?op=hochzeiten");
    addnav("Zum Innenraum","chapel.php");
   //end
    
}else if ($_GET[op]=="klingelbeutel"){
$klingelbeutel1=floor($settings['klingelbeutel']);
$klingelbeutel2=$klingelbeutel1+round($klingelbeutel1/10*e_rand(-1,1));
    output("`c`b`&Der Klingelbeutel`b`c`n");
    output("`6Leise stehst Du auf und gehst zu einem der Klingelbeutel, die etwas abseits in der Ecke stehen. Niemand
        beobachtet Dich und Du überlegst, ob Du nicht anonym eine Kleinigkeit spenden willst. Du weißt, daß diese
        Kollekte immer dem nächsten Brautpaar zur Verfügung gestellt wird, damit diese einen leichteren
        Start in Ihr Eheleben haben.`n`n Inzwischen sollten etwa `^$klingelbeutel2`6 Gold gespendet worden sein.");
    output("`6`n`nWas willst Du also tun?`0");
    addnav("Etwas hineinwerfen","chapel.php?op=klingelbeutel2");
    addnav("Leise wieder hinsetzen","chapel.php");
}else if ($_GET[op]=="klingelbeutel2"){
    output("`n`6Leider kannst Du nicht bis zum Boden sehen und weißt so nicht, wieviel schon im Klingelbeutel
        enthalten ist.`n`n");
    output("<form action='chapel.php?op=klingelbeutel3' method='POST'>Wieviel möchtest Du in den Klingelbeutel hinweinwerfen?: <input name='putgold' id='putgold' accesskey='b' width='2'>",true);
        output("<input type='submit' class='button' value='OK'></form>",true);
        output("<script language='javascript'>document.getElementById('putgold').focus();</script>",true);
        addnav("","chapel.php?op=klingelbeutel3");
    addnav("Leise wieder hinsetzen","chapel.php");
}else if ($_GET[op]=="klingelbeutel3"){
    $anzahl = abs((int)$_POST['putgold']);
    if ($session[user][gold] < $anzahl){
        output("`6`nDu Schelm, Du hast gar nicht so viel Gold bei Dir");
        addnav("Leise wieder hinsetzen","chapel.php");
    }else if ($anzahl > $session[user][level]*500){
        output("`6`nDu sollst Dich hier nicht ruinieren, eine kleinere Spende tut es auch.");
        addnav("Nochmal","chapel.php?op=klingelbeutel2");
        addnav("Leise wieder hinsetzen","chapel.php");
    }else{
        $session[user][gold]-=$anzahl;
        $vorhanden=getsetting("klingelbeutel",0);
        $vorhanden+=$anzahl;
        debuglog("`^Kapelle: `&".$session[user][name]." `@hat ".$anzahl." Gold gespendet!");
        savesetting("klingelbeutel",$vorhanden);
        if (e_rand(1,3)==1){
                    $session['bufflist']['chapel'] = array("name"=>"`8Segen der kleinen Kapelle","rounds"=>8,"wearoff"=>"Der Segen der kleinen Kapelle wirkt nicht mehr.","defmod"=>1.2,"roundmsg"=>"`8Der Segen der kleinen Kapelle schützt dich.","activate"=>"offense");
        }
        redirect("chapel.php?op=eintreten");
    }
    }else if($_GET[op]=="showpriest"){   //geändert für eine richtige onlineanzeige, da vorher die mit Sessionstimeout als online angezeigt wurden by Rikkarda
    $sql="SELECT name, loggedin,login,laston FROM accounts WHERE prayer>0 ORDER BY msgdate ASC";
    output("`n`c`n`b`2Priester in dieser Welt`b`n`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`b Name `b</td><td>`b On/Offline`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
              output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Priester in dieser Welt`i`0</td></tr>",true);
        }
        for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
        $alter = $dorfalter-$row[firstday];
        $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
        output("<tr class='".($i%2?"trlight":"trdark")."'>
                <td align='left'>`&$row[name] &nbsp;&nbsp;<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a></td>
               <td align='right'>".($loggedin?"`#Online":"`4Offline")."</td>
            </tr>`&",true);
    }
output("</table>", true);
  //addnav("Aktualisieren","chapel.php?op=showpriest");
  addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="priester"){
    output("`c`b`&Schrein des Priesters`b`c`n");
    if ($prayer){
        /*if ($session[user][locate] != 2){
            $session[user][locate]=2;
            redirect("chapel.php?op=priester");
        }*/
        if ($_GET[act]=="umziehen"){
            output("`n`6In dem Feld unten siehst Du Deine derzeitige Kleidung. Sollten Du diese ändern wollen,
                so ist Dir dieses nun möglich.`n`n");
            output("<form action='chapel.php?op=priester&act=umziehen2' method='POST'>Wie möchtest Du für die Veranstaltung aussehen?: <input name='putanzug' id='putanzug' accesskey='b' width='50'>",true);
                output("<input type='submit' class='button' value='OK'></form>",true);
                output("<script language='javascript'>document.getElementById('putanzug').focus();</script>",true);
                addnav("","chapel.php?op=priester&act=umziehen2");
            addnav("Zum Schrein","chapel.php?op=priester");
        }else if ($_GET[act]=="umziehen2"){
            if ($session[user][eventname]==NULL){
                $session[user][eventname]=$session[user][name];
            }
            if ($_POST['putanzug']==" "){
                output("`6Es wurde kein anderer Name gewählt.");
            }else{
                $session[user][name]=$_POST['putanzug'];
            }
            output("`6Du wirst als ".$session[user][name]." die Veranstaltung leiten.");
            addnav("Zum schrein","chapel.php?op=priester");
        }else{
            $fliesstext = "Bevor man eine Hochzeit beginnt, bitte im Schrein des Priesters 2x auf Kapelle aufräumen klicken, dies löscht SÄMTLICHE Inhalte im Kapellenraum, damit nur die Trauung an sich später in der Liste der Hochzeiten ist. Nachdem die RP Trauung gespielt ist, zb. nach dem Kuss des Paares, geht ihr auf Paare mit Aufgebot und klickt auf die Möglichkeit HEIRATEN. Dadurch wird dann automatisch der Text aus der Kapelle gelöscht und in die Liste der Hochzeiten kopiert. Bitte das aufräumen vorher nie vergessen, denn es sieht dann sehr ungewöhnlich aus..";
            output("`n`6Du stehst nun in dem Raum, der nur Priestern wie Dir zugänglich ist. Hier kannst Du vor
                Gottesdiensten oder Trauungen oder anderen Veranstaltungen erst einmal in Dich gehen und Dich
                entsprechend vorbereiten. Auch ist hier ooc erlaubt, was immer das auch bedeuten mag.
                Aber Fragen und Interna müssen schließlich besprochen werden können.");
            output("`n`^Wegweiser für die Durchführung:`n`n");
            rawoutput("<p>".$fliesstext."</p>");
            output("`n`6Was willst Du tun?`n`n");
            viewcommentary("Priesterraum","Spreche in den Raum",25,2,"sagt");
            addnav ("Funktionen");
            //addnav("Umziehen","chapel.php?op=priester&act=umziehen");
            addnav("Kapelle aufräumen","chapel.php?op=putzen");
            
            if ($session[user][prayer]>1 || $session[user][superuser]>=3){
                addnav("Priester ernennen","chapel.php?op=makeprayer");
                addnav("Priester entlassen","chapel.php?op=delprayer");
//addnav("Schlüssel vergeben","chapel.php?op=givekey");
  //          addnav("Schlüssel entziehen","chapel.php?op=takekey");
            }
        }
    }else{
        output("`6`nDeine Neugier ist verständlich, aber leider haben nur Priester Zutritt zu diesem Raum!");
    }
    //addnav("Aktualisieren","chapel.php?op=priester");
    addnav("Zum Innenraum","chapel.php");
    }else if ($_GET[op]=="givekey"){
    output("<form action='chapel.php?op=givekey2' method='POST'>",true);
    addnav("","chapel.php?op=givekey2");
    output("`bSchlüssel geben an:`b`nCharacter: <input name='name'>`n<input type='submit' class='button' value='Übergeben'>",true);
    output("</form>",true);
    addnav("Zurück zur Kapelle","chapel.php");
}else if ($_GET[op]=="givekey2"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Bestätige die Schlüsselübergabe an:`n`n");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='chapel.php?op=givekey3&id={$row['acctid']}'>",true);
        output("".$row['name']."");
        output("</a>`n",true);
        addnav("","chapel.php?op=givekey3&id={$row['acctid']}");
    }
    addnav("Zurück zur Kapelle","chapel.php");
}else if ($_GET[op]=="givekey3"){
        $sql = "SELECT * FROM items WHERE value1 = 310 AND owner = ".$_GET['id']."";
        $result=db_query($sql);
        if (db_num_rows($result)>0){
            output("Der Spieler hat bereits einen Schlüssel zum Haus");
        }else{
            $sql="SELECT max(value2) as zahl FROM items WHERE value1 = 310 AND class='Schlüssel'";
            $result=db_query($sql);
            $row=db_fetch_assoc($result);
            $i = $row[zahl] + 1;
            db_free_result($result);
            $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description)
                    VALUES ('Hausschlüssel',".$_GET['id'].",'Schlüssel',310,$i,0,0,'Schlüssel für Haus Nummer 310')";
            db_query($sql);
            $_GET['op']="";
            systemmail($_GET[id],"`%Hausschlüssel!`0","`&Du hast einen Hausschlüssel für das `tHaus der Priester`& bekommen.");
            output("Der Schlüssel wurde zugeteilt");
        }
        addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="takekey"){
    output("<form action='chapel.php?op=takekey2' method='POST'>",true);
    addnav("","chapel.php?op=takekey2");
    output("`bSchlüssel wegnehmen:`b`nCharacter: <input name='name'>`n<input type='submit' class='button' value='Wegnehmen'>",true);
    output("</form>",true);
    addnav("Zurück zur Kapelle","chapel.php");
}else if ($_GET[op]=="takekey2"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Bestätige die Schlüsselentzug von:`n`n");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='chapel.php?op=takekey3&id={$row['acctid']}'>",true);
        output("".$row['name']."");
        output("</a>`n",true);
        addnav("","chapel.php?op=takekey3&id={$row['acctid']}");

    }
    addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="takekey3"){
        $sql = "DELETE FROM items WHERE value1 = 310 AND owner = '{$_GET['id']}' AND class='Schlüssel'";
        db_query($sql);
        output("Der Schlüssel wurde entzogen");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="makeprayer"){
    output("<form action='chapel.php?op=makeprayer2' method='POST'>",true);
    addnav("","chapel.php?op=makeprayer2");
    output("`bFolgenden Char ins Priesteramt ernennen: <input name='name'>`n<input type='submit' class='button' value='Übergeben'>",true);
    output("</form>",true);
    addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="makeprayer2"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Bestätige Ernennung zum Priester von:`n`n");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='chapel.php?op=makeprayer3&id={$row['acctid']}'>",true);
        output("".$row['name']."");
        output("</a>`n",true);
        addnav("","chapel.php?op=makeprayer3&id={$row['acctid']}");

    }
    addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="makeprayer3"){
      set_special_var("prayer","1","0",$_GET[id],0," ");
        output("Die Ernennung ist erfolgt");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
        }else if($_GET[op]=="scheidungsamt"){
          $sql = "SELECT * FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $partner=$row[name];
        if($session[user][marriedto]=='4294967295'){ $partner="".($session[user][sex]?"Seth":"Violet").""; }
        output("`2Du näherst dich einem kleinen Schalter. Sogleich öffnet sich dieser und ein Mann schielt hervor.");
        if($session[user][acctid]==$row[marriedto] || $session[user][marriedto]=='4294967295'){
output("`@\"Aaah... du bist doch `2".$session[user][name]."`@, oder? Möchtest du dich hier wirklich von `2".$partner."`@ scheiden lassen? Überleg es dir gut...\"");
addnav("Scheiden lassen","chapel.php?op=scheid");
addnav("Lieber zurück","chapel.php");
}else{
output("Du denkst, hier ist nicht der richtige Ort für dich...");
addnav("Zurück","chapel.php");
}
}
else if($HTTP_GET_VARS['op']=="taufen"){
    output("`c`7`bDas Taufbecken`b`c");
addnav("Innenraum","chapel.php");
//    output("`n`n`4`bBruder Thomas`b`7 sagt `&\"Schön das ihr euer Kind taufen lassen wollt! Welches wollt ihr denn taufen lassen?\"`7 fragt er.`n`n");

    if($HTTP_GET_VARS[id] != "" && $HTTP_POST_VARS[tname] != "")
    {
        if($session['user']['sex'])
        {
            $art="mama";
            $art2 = "ihre";
        }
        else
        {
            $art="papa";
            $art2 = "seine";
        }

        $sql="UPDATE kinder SET name = '" . $HTTP_POST_VARS[tname] . "' WHERE $art = " . $session[user][acctid] . " and id = " . $HTTP_GET_VARS[id];
        $result = db_query($sql) or die(db_error(LINK));
        $sql="SELECT * FROM kinder WHERE id = " . $HTTP_GET_VARS[id];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);

        if($row[geschlecht])
            addnews($session[user][name] . " hat " . $art2 ." Tochter auf den Namen " . $HTTP_POST_VARS[tname] . " getauft.");
        else
            addnews($session[user][name] . " hat " . $art2 ."n Sohn auf den Namen " . $HTTP_POST_VARS[tname] . " getauft.");
    }

    if($_GET[id] != "" && $HTTP_POST_VARS[tname] == "")
    {
        addnav("Zurück","chapel.php?op=taufen");
        output("<form action='chapel.php?op=taufen&id=".$HTTP_GET_VARS[id]."' method='POST'>",true);
        output("Taufname : <input name=tname maxlength=50>`n`n", true);
        output("<input type='submit' class='button' value='Taufen'></form>",true);
        addnav("","chapel.php?op=taufen&id=".$HTTP_GET_VARS[id]);
    }
    else
    {
        if($session['user']['sex'])
            $sql="SELECT * FROM kinder WHERE mama = " . $session[user][acctid];
        else
            $sql="SELECT * FROM kinder WHERE papa = " . $session[user][acctid];

        output("<table border='0' cellpadding='3' cellspacing='0'><tr class='trhead'><td style=\"width:275px\">Name</td><td style=\"width:150px\" align=center>Geburtsdatum</td><td style=\"width:75px\" align=center>Geschlecht</td><td>&nbsp;</td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
            if($row['name'] == "")
                output("Neugeborenes", true);
            else
                output($row['name'],true);
            output("</td>",true);
            output("<td>",true);
                output("`c" . $row['gebdat'] . "`c",true);
            output("</td>",true);

            if($row['geschlecht'] == 1)
                output("<td>`c<img src=images/female.gif>`c</td>", true);
            else
                output("<td>`c<img src=images/male.gif>`c</td>", true);

            if($row['name'] == "")
            {
                output("<td>[<a href='chapel.php?op=taufen&id=".$row[id]."'>Taufen</a>]</td></tr>",true);
                addnav("","chapel.php?op=taufen&id=".$row[id]."");

            }
            else
                output("<td>&nbsp;</td></tr>",true);


        }
        output("</table>",true);

    }
}
else if($_GET[op]=="scheid"){
          $sql = "SELECT * FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $partner=$row[name];
if ($session[user][goldinbank]>0) $getgold=round($session[user][goldinbank]/2);
output("`@Der Mann schreibt einige Sätze auf ein Blatt Papier und legt es auf einen Stapel anderer - die Scheidung ist vollzogen!");
if ($session[user][charisma]==4294967295){
                        $sql = "UPDATE accounts SET marriedto=0,charisma=0 WHERE acctid='{$session['user']['marriedto']}'";
                        db_query($sql);
                        systemmail($session[user]['marriedto'],"`\$Scheidung!`0","`6`&{$session['user']['name']}`6 hat sich mit dir geschieden!`nDir werden `^$getgold`6 Gold von deinem ehemaligen Ehepartner zugesprochen.");
                        $session[user][goldinbank]-=$getgold;

                         }
                $session[user][marriedto]=0;
                $session[user][charisma]=0;
                $session['user']['seenlover']=1;
                $session[user][charm]-=1;
                addnews("`%".$session[user][name]." `&und `%".$partner."`& haben sich heute Scheiden lassen!");
                }else if($_GET[op]=="standesamt"){
                $sql = "SELECT * FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $partner=$row[name];
        $acct=$row[acctid];
        output("`2Du näherst dich einem kleinen Schalter. Sogleich öffnet sich dieser und ein Mann schielt hervor.");
        if($session[user][acctid]==$row[marriedto] && $session[user][charisma]>='5' && $row[charisma]>='5'){
output("`@\"Aaah... du bist doch `2".$session[user][name]."`@, oder? Möchtest du wirklich `2".$partner."`@ heiraten? Überleg es dir gut, denn die Ehe hat auch ihre tücken...\"");
addnav("Heiraten!","chapel.php?op=heiratimamt&acct=".$row[acctid]."");
addnav("Lieber zurück","chapel.php");
}else{
output("Du denkst, hier ist nicht der richtige Ort für dich...");
addnav("Zurück","chapel.php");
}


                }else if($_GET[op]=="heiratimamt"){
                $sql = "SELECT * FROM accounts WHERE acctid=".$_GET[acct]."";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
                                    output("`c`b`&Hochzeit!`0`b`c");
                                    output("`2Die Hochzeit ist vollzogen!");
                    if (getsetting("paidales",0)>=1){
                       $amt=e_rand(2,6);
                       output("`nEs bleiben nur $amt Ale vom Festschmaus übrig, die ihr freundlicherweise der Kneipe spendet.");
                      savesetting("paidales",getsetting("paidales",0)+$amt);
                    }
                    $session[user][charisma]=4294967295;
                    $sql = "UPDATE accounts SET charisma='4294967295',charm=charm+1 WHERE acctid='$_GET[acct]'";
                    db_query($sql);
                    addnews("`%".$session[user][name]." `&und `%$row[name]`& haben heute feierlich den Bund der Ehe geschlossen!!!");
                    systemmail($row[acctid],"`&Hochzeit!`0","`6 Du und `&".$session['user']['name']."`& habt nach zahlreichen gemeinsamen Flirts im Garten geheiratet.`nGlückwunsch!");
                    $session[user][seenlover]=1;
                    $session[bufflist][lover]=$buff;
                    $session[user][charm]+=1;
                    $session[user][donation]+=1;
}else if ($_GET[op]=="delprayer"){
    output("<form action='chapel.php?op=delprayer2' method='POST'>",true);
    addnav("","chapel.php?op=delprayer2");
    output("`bFolgenden Char aus Priesteramt entlassen: <input name='name'>`n<input type='submit' class='button' value='Übergeben'>",true);
    output("</form>",true);
    addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="delprayer2"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Bestätige Entlassung von:`n`n");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='chapel.php?op=delprayer3&id={$row['acctid']}'>",true);
        output("".$row['name']."");
        output("</a>`n",true);
        addnav("","chapel.php?op=delprayer3&id={$row['acctid']}");

    }
    addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="delprayer3"){
       set_special_var("prayer","0","0",$_GET[id],0," ");
        output("Die Entlassung ist erfolgt");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
}else {
    //$session[user][locate]=1;*/
    redirect("chapel.php?op=eintreten");
}
addnav("Zurück zum Dorf","village.php");
//addnav("Zurück zu den Gärten","gardens.php");
page_footer();
?>