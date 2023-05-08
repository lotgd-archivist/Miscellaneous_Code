
<? 

// Kapelle 1.0 by lordraven
//
// Die kleine Kapelle wird benötigt, um Hochzeiten RP gemäß durchzuführen.
// Dafür muß die Automatik in gardens.php, bei entsprechender Anzahl flirts zu heiraten geändert werden
// Die Kapelle registriert, wenn Paare 5 mal oder öfter miteinander geflirtet haben und erstellt eine
// Aufgebotsliste
// Superusern oder ernennten Priestern ist es möglich, die Hochzeit per Knopfdruck zu vollenden
// Dann wird die gesamte Zeremonie gesichert, ein Eintrag in die Hochzeitstabellen gemacht
// und man kann sich die Hochzeit in der Historie immer wieder ansehen
// Da alle aktuellen Kommentare in der Kapelle gesichert werden, muß vorher aufgeräumt werden. Diese
// Funktion steht Superusern oder dem Priester zur Verfügung.
//
// Erstellt am 28.05.2004  Lord Raven
//
// Version 1.1 - Klingelbeutel hinzugefügt, 31.05.2004 Lord Raven
//

require_once "common.php";
addcommentary();
get_special_var();
$prayer=false;
if ($session[user][prayer] || $session[user][superuser]==4){
    $prayer=true;
}

page_header("Die kleine Kapelle von Düsterstein"); 
if ($_GET[op]=="eintreten"){
    output("`0Du betrittst die kleine, hübsche Kapelle des Dorfes. Wie immer bist Du fasziniert von der 
        wunderschönen Einrichtung, den Ornamenten in der Decke, den glänzenden Kronleuchtern und 
        der kleinen Orgel in der Ecke.`n
        Du siehst die kleine Tafel, auf der steht das hier heute schon bis zu ".maxspieler(1)." Einwohner
        die Kapelle gleichzeitig besucht haben.");
    if ($session[user][charisma]==4294967295){
        output("Unwillkürlich erinnerst Du Dich an Deine eigene Hochzeit und würdest am liebsten nochmal
            heiraten.");
    }else{
        output("Möchtest Du nicht auch langsam mal hier stehen und heiraten?");
    }
    output("`n`n`0Leise hörst Du die Anwesenden miteinander flüstern:`n`n");
    viewcommentary("Kapelle","Flüster zu den Anwesenden",25,"flüstert"); 
    addnav("Funktionen");
    addnav("Paare mit Aufgebot","chapel.php?op=merrylist");
    addnav("Bisherige Hochzeiten","chapel.php?op=hochzeiten");
      addnav("Mitteilungen","chapel.php?op=board");
    addnav("Klingelbeutel","chapel.php?op=klingelbeutel");
    addnav("Schrein des Priesters","chapel.php?op=priester");
    addnav("-");
    addnav("Aktualisieren","chapel.php");
}else if ($_GET[op]=="merrylist"){
    $sql="SELECT acctid,name,marriedto FROM accounts WHERE sex=0 AND charisma>=5 AND charisma<100 ORDER BY acctid DESC";
        output("`c`b`&Paare dieser Welt mit bestelltem Aufgebot`b`c`n"); 
    if ($session[user][superuser]>=4){
            output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bAktion`b</td><td></td><td><img src=\"images/female.gif\">`b Name`b</td><td></td><td><img src=\"images/male.gif\">`b Name`b</td></tr>",true); 
    }else{
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td><img src=\"images/female.gif\">`b Name`b</td><td></td><td><img src=\"images/male.gif\">`b Name`b</td></tr>",true); 
    }    
        $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)==0){ 
              output("<tr><td colspan=4 align='center'>`&`iIn diesem Land gibt es keine Paare`i`0</td></tr>",true); 
        } 
        for ($i=0;$i<db_num_rows($result);$i++){ 
                $row = db_fetch_assoc($result); 
            $sql2 = "SELECT acctid,name,marriedto FROM accounts WHERE sex=1 AND charisma>=5 AND charisma<100 AND marriedto = ".$row[acctid].""; 
            $result2 = db_query($sql2) or die(db_error(LINK)); 
        if (db_num_rows($result2)==0){
            //output("<tr class='".($i%2?"trlight":"trdark")."'><td>`&noch nicht`0</td><td>`) und `0</td><td>`&",true);
            //output("$row[name]`0</td></tr>",true);
        }else{
                    $row2 = db_fetch_assoc($result2); 
            if ($prayer){
                      output("<tr class='".($i%2?"trlight":"trdark")."'>
                    <td>[ <a href='chapel.php?op=heirat&p1=$row2[acctid]&p2=$row[acctid]'>Heirat</a> ]</td>
                    <td>[ <a href='chapel.php?op=entf&p1=$row2[acctid]&p2=$row[acctid]'>Entfernen</a> ]</td>
                    <td>`&$row2[name]`0</td><td>`) und `0</td><td>`&",true); 
                    output("$row[name]`0</td></tr>",true); 
                addnav("","chapel.php?op=heirat&p1=$row2[acctid]&p2=$row[acctid]");
                addnav("","chapel.php?op=entf&p1=$row2[acctid]&p2=$row[acctid]");
            }else{
                      output("<tr class='".($i%2?"trlight":"trdark")."'><td>`&$row2[name]`0</td><td>`) und `0</td><td>`&",true); 
                    output("$row[name]`0</td></tr>",true);
            }    
        }
        } 
        output("</table>",true);
    addnav("Zum Innenraum","chapel.php");

}else if ($_GET[op]=="entf"){
    $sql="UPDATE accounts SET charisma=0, marriedto=0 WHERE acctid = ".$_GET[p1]." OR acctid = ".$_GET[p2]."";
    db_query($sql);
    output("Das Aufgebot wurde gelöscht");
    addnav("L?Zur Liste","chapel.php?op=merrylist");
    addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="board"){
    $boardid = "chapel";
    if($_GET['act'] == "add1") {
        if (addmessageboard()) {
            output("In der Hoffnung, dass jeder von deiner wichtigen Notiz kenntnis nimmt, hängst du sie gut sichtbar an.");
        }
        elseif ($doublepost) {
            output("Es hängt schon ein solcher Zettel.");
        }
        else {
            output("Du kramst einen Zettel und einen Stift hervor und schreibst ein paar Zeilen.`n`n");                
            formmessageboard($boardid,'Notiz hinterlassen');
        }
        addnav("Mitteilungen ansehen","chapel.php?op=board");
    }else{
        output("Neugierig betrachtest du die Wand, an der Mitteilungen angebracht werden können.`n");
        output("Üblicherweise werden hier Termine der Hochzeiten angekündigt.`n");
        viewmessageboard($boardid,'`nFolgende Mitteilungen sind an der Wand angeschlagen.','`nEs sind keine Mitteilungen angebracht');
        addnav("Selber hinzufügen","chapel.php?op=board&act=add1");
    }
    addnav("Zum Innenraum","chapel.php");


}else if ($_GET[op]=="heirat"){
    $hochzeitsdatum = "".dorftag(0).". Tag ".dorfjahr(0)."";
    $klingelbeutel=getsetting("klingelbeutel",0);
    $fueruser1 = floor($klingelbeutel / 2);
    $fueruser2 = $klingelbeutel - $fueruser1;    
    $sql="SELECT acctid,name FROM accounts WHERE acctid=".$_GET[p1]." OR acctid=".$_GET[p2]." order by sex ASC";
    $result=db_query($sql) or die(sql_error($sql));
    $count=db_num_rows($result);
    for ($i=0;$i<$count;$i++){ 
        $row=db_fetch_assoc($result); 
        $names[]="`^$row[name]";
        $acctids[]=$row[acctid];  
    } 
    db_free_result($result);
    //Eintragen in Hochzeiten Tabelle
    $sql="INSERT INTO wedding (acctid1,name1,acctid2,name2,date,prayer) VALUES (".$acctids[0].",'".$names[0]."',".$acctids[1].",'".$names[1]."','".$hochzeitsdatum."','".$session[user][name]."')";
    db_query($sql);
    //Auslesen der Hochzeitsnummer
    $sql="SELECT wnr FROM wedding WHERE acctid1=".$acctids[0]." AND acctid2=".$acctids[1]." AND date='".$hochzeitsdatum."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    //Nun die Kapellen Kommentare auslesen - alle - deswegen muß vor einer Hochzeit aufgeräumt sein
    $sql="SELECT author,comment FROM commentary WHERE section='Kapelle' ORDER BY commentid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    $count=db_num_rows($result);
    for ($i=0;$i<$count;$i++){ 
        $row2=db_fetch_assoc($result); 
        $sql2="SELECT name FROM accounts WHERE acctid=".$row2[author]."";
        $result2 = db_query($sql2) or die(db_error(LINK));
        $row3=db_fetch_assoc($result2);
        $row2[comment]=str_replace("'","",$row2[comment]);
        $sql3="INSERT INTO wedding_text (wnr,name,text) VALUES (".$row[wnr].",'".$row3[name]."','".$row2[comment]."')";
        db_query($sql3);  
    } 
    db_free_result($result);
    db_free_result($result2);
    //Nun Hochzeit durchführen wenn alles geklappt hat
    //Variable,Wert,Vorzeichen,Spieler 1, Spieler 2, Text
    set_special_var("charisma","4294967295","0",$_GET[p1],$_GET[p2],"Heirat");
    set_special_var("charisma","4294967295","0",$_GET[p2],$_GET[p1],"Heirat");
    set_special_var("charm","1","0",$_GET[p1],"0"," ");
    set_special_var("charm","1","0",$_GET[p2],"0"," ");
    set_special_var("seenlover","1","0",$_GET[p1],"0"," ");
    set_special_var("seenlover","1","0",$_GET[p2],"0"," ");
    set_special_var("donation","1","0",$_GET[p1],"0"," ");
    set_special_var("donation","1","0",$_GET[p2],"0"," ");
    set_special_var("goldinbank","".$fueruser1."","0",$_GET[p1],"0"," ");
    set_special_var("goldinbank","".$fueruser2."","0",$_GET[p2],"0"," ");
        addnews("`%".$names[0]." `&und `%$names[1]`& haben heute feierlich den Bund der Ehe geschlossen und haben
        als Geschenk der Gemeinde `^".$klingelbeutel." Gold `&erhalten!!!");
    systemmail($_GET[p1],"`%Hochzeit!`0","`&Du bist den Bund der Ehe eingegangen");
    systemmail($_GET[p2],"`%Hochzeit!`0","`&Du bist den Bund der Ehe eingegangen");
    output("Die Hochzeit zwischen $names[0] und $names[1] wurde vollzogen");
    savesetting("klingelbeutel","0");    
    addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="hochzeiten"){
    $sql="SELECT wnr,date,name1,name2,prayer FROM wedding ORDER BY wnr ASC";
    output("`c`b`&Bisherige Hochzeiten in dieser Kapelle`b`c`n`n`n`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td width='100' height='40'>`b Datum `b</td><td><img src=\"images/male.gif\">`b Name`b</td><td width='40'></td><td><img src=\"images/female.gif\">`b Name`b</td><td>`b Priester`b</td><td></td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)==0){ 
              output("<tr><td colspan=4 align='center'>`&`iEs gab bisher keine Hochzeiten`i`0</td></tr>",true); 
        } 
        for ($i=0;$i<db_num_rows($result);$i++){ 
                $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td align='left' width='100' height='40'>`&$row[date]</td><td>`&$row[name1]`0</td><td width='40'>`) und `0</td><td>`&",true); 
            output("$row[name2]`0&nbsp;&nbsp;</td><td>`&$row[prayer]`0</td>",true);
        output("<td> [ <a href='chapel.php?op=hochzeitsliste&num=$row[wnr]'>Liste</a> ] </td></tr>",true);
        addnav("","chapel.php?op=hochzeitsliste&num=$row[wnr]");
    }
    output("</table>",true);
    addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="hochzeitsliste"){
    output("`c`b`&Schreiberlisten zur Hochzeit`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td></td><td>`b Kommentare`b</td></tr>",true);
    $ppp=20; // Player Per Page +1 to display
    if (!$_GET[limit]){
        $page=0;
    }else{
        $page=(int)$_GET[limit];
        addnav("Vorherige Übersicht","chapel.php?op=hochzeitsliste&num=".$_GET[num]."&limit=".($page-1)."");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $sql="SELECT name,text FROM wedding_text WHERE wnr=".$_GET[num]." LIMIT $limit";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","chapel.php?op=hochzeitsliste&num=".$_GET[num]."&limit=".($page+1)."");
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Listen`i`0</td></tr>",true);
    }else{
        for ($i=0;$i<db_num_rows($result);$i++){
            $row2 = db_fetch_assoc($result);
            $row2[text] = str_replace("/me",$row2[name],$row2[text]);
            $row2[text] = str_replace("::",": ",$row2[text]);
            $teste = substr($row2[text],1,3);
            if (substr($row2[text],0,1)==":"){
                $row2[text] = str_replace(":","".$row2[name]." ",$row2[text]);
            }
            output("<tr><td valign='middle'>o&nbsp;</td><td>$row2[text]</td></tr>",true);
        }
    }
    output("</table>",true);
    addnav("Hochzeitsübersicht","chapel.php?op=hochzeiten");
    addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="putzen"){
    if (!$prayer){
    }else{
        $sql="DELETE FROM commentary WHERE section='kapelle'";
        db_query($sql);
        output("`nDie Kapelle wurde aufgeräumt");
    }
    addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="klingelbeutel"){
    output("`c`b`&Der Klingelbeutel`b`c`n");
    output("`6Leise stehst Du auf und gehst zu einem der Klingelbeutel, die etwas abseits in der Ecke stehen. Niemand
        beobachtet Dich und Du überlegst, ob Du nicht anonym eine Kleinigkeit spenden willst. Du weißt, daß diese
        Kollekte immer dem nächsten Brautpaar zur Verfügung gestellt wird, damit diese einen leichteren 
        Start in Ihr Eheleben haben.");
    output("`6`n`nWas willst Du also tun?`0");
    addnav("Etwas hineinwerfen","chapel.php?op=klingelbeutel2");
    addnav("Leise wieder hinsetzen","chapel.php");
}else if ($_GET[op]=="klingelbeutel2"){
    output("`n`6Leider kannst Du nicht bis zum Boden sehen und weißt so nicht, wieviel schon im Klingelbeutel 
        enthalten ist.`n`n");
    output("<form action='chapel.php?op=klingelbeutel3' method='POST'>Wieviel möchtest Du in den Klingelbeutel hinweinwerfen?: <input name='putgold' id='putgold' accesskey='b' width='2'>",true);  
        output("<input type='submit' class='button' value='OK'></form>",true); 
        output("<script language='javascript'>document.getElementById('putgold').focus();</script>",true); 
        addnav("","chapel.php?op=klingelbeutel3");
    addnav("Leise wieder hinsetzen","chapel.php");
}else if ($_GET[op]=="klingelbeutel3"){
    $anzahl = abs((int)$_POST['putgold']);
    if ($session[user][gold] < $anzahl){
        output("`6`nDu Schelm, Du hast gar nicht so viel Gold bei Dir");
        addnav("Leise wieder hinsetzen","chapel.php");
    }else if ($anzahl > $session[user][level]*200){
        output("`6`nDu sollst Dich hier nicht ruinieren, eine kleinere Spende tut es auch.");
        addnav("Nochmal","chapel.php?op=klingelbeutel2");
        addnav("Leise wieder hinsetzen","chapel.php");
    }else{
        $session[user][gold]-=$anzahl;
        $vorhanden=getsetting("klingelbeutel",0);
        $vorhanden+=$anzahl;
        debuglog("`^Kapelle: `&".$session[user][name]." `@hat ".$anzahl." Gold gespendet!");
        savesetting("klingelbeutel",$vorhanden);
        if (e_rand(1,3)==1){ 
                    $session['bufflist']['chapel'] = array("name"=>"`8Segen der kleinen Kapelle","rounds"=>8,"wearoff"=>"Der Segen der kleinen Kapelle wirkt nicht mehr.","defmod"=>1.2,"roundmsg"=>"`8Der Segen der kleinen Kapelle schützt dich.","activate"=>"offense"); 
        }
        redirect("chapel.php?op=eintreten");
    }
}else if ($_GET[op]=="priester"){
    output("`c`b`&Schrein des Priesters`b`c`n");
    if ($prayer){
        if ($session[user][locate] != 2){
            $session[user][locate]=2;
            redirect("chapel.php?op=priester");
        }
        if ($_GET[act]=="umziehen"){
            output("`n`6In dem Feld unten siehst Du Deine derzeitige Kleidung. Sollten Du diese ändern wollen,
                so ist Dir dieses nun möglich.`n`n");
            output("<form action='chapel.php?op=priester&act=umziehen2' method='POST'>Wie möchtest Du für die Veranstaltung aussehen?: <input name='putanzug' id='putanzug' accesskey='b' width='50'>",true);  
                output("<input type='submit' class='button' value='OK'></form>",true); 
                output("<script language='javascript'>document.getElementById('putanzug').focus();</script>",true); 
                addnav("","chapel.php?op=priester&act=umziehen2");
            addnav("Zum Schrein","chapel.php?op=priester");
        }else if ($_GET[act]=="umziehen2"){
            if ($session[user][eventname]==NULL){    
                $session[user][eventname]=$session[user][name];
            }
            if ($_POST['putanzug']==" "){
                output("`6Es wurde kein anderer Name gewählt.");
            }else{
                $session[user][name]=$_POST['putanzug'];
            }
            output("`6Du wirst als ".$session[user][name]." die Veranstaltung leiten.");
            addnav("Zum schrein","chapel.php?op=priester");
        }else{
            output("`n`6Du stehst nun in dem Raum, der nur Priestern wie Dir zugänglich ist. Hier kannst Du vor
                Gottesdiensten oder Trauungen oder anderen Veranstaltungen erst einmal in Dich gehen und Dich
                entsprechend vorbereiten. Auch ist hier ooc erlaubt, was immer das auch bedeuten mag.
                Aber Fragen und Interna müssen schließlich besprochen werden können.");
            output("`n`6Was willst Du tun?`n`n");
            viewcommentary("Priesterraum","Spreche in den Raum",25,"sagt");
            addnav ("Funktionen");
            addnav("Umziehen","chapel.php?op=priester&act=umziehen");
            addnav("Kapelle aufräumen","chapel.php?op=putzen");
            addnav("Schlüssel vergeben","chapel.php?op=givekey");
            addnav("Schlüssel entziehen","chapel.php?op=takekey");
            if ($session[user][prayer]>1 || $session[user][superuser]>3){
                addnav("Priester ernennen","chapel.php?op=makeprayer");
                addnav("Priester entlassen","chapel.php?op=delprayer");
            }
            addnav("-");
        }
    }else{
        output("`6`nDeine Neugier ist verständlich, aber leider haben nur Priester Zutritt zu diesem Raum!");
    }
    addnav("Aktualisieren","chapel.php?op=priester");
    addnav("Zum Innenraum","chapel.php");
}else if ($_GET[op]=="givekey"){
    output("<form action='chapel.php?op=givekey2' method='POST'>",true);
    addnav("","chapel.php?op=givekey2");
    output("`bSchlüssel geben an:`b`nCharacter: <input name='name'>`n<input type='submit' class='button' value='Übergeben'>",true);
    output("</form>",true);
}else if ($_GET[op]=="givekey2"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Bestätige die Schlüsselübergabe an:`n`n");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='chapel.php?op=givekey3&id={$row['acctid']}'>",true);
        output("".$row['name']."");
        output("</a>`n",true);
        addnav("","chapel.php?op=givekey3&id={$row['acctid']}");
        addnav("Zurück zur Kapelle","chapel.php");
    }
}else if ($_GET[op]=="givekey3"){
        $sql = "SELECT * FROM items WHERE value1 = 309 AND owner = ".$_GET['id']."";
        $result=db_query($sql);
        if (db_num_rows($result)>0){
            output("Der Spieler hat bereits einen Schlüssel zum Haus");
        }else{
            $sql="SELECT max(value2) as zahl FROM items WHERE value1 = 309 AND class='Schlüssel'";
            $result=db_query($sql);
            $row=db_fetch_assoc($result);
            $i = $row[zahl] + 1;
            db_free_result($result);
            $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) 
                    VALUES ('Hausschlüssel',".$_GET['id'].",'Schlüssel',309,$i,0,0,'Schlüssel für Haus Nummer 309')";
            db_query($sql);
            $_GET['op']="";
            systemmail($_GET[id],"`%Hausschlüssel!`0","`&Du hast einen Hausschlüssel für das Dorfhaus bekommen.");
            output("Der Schlüssel wurde zugeteilt");
        }
        addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="takekey"){
    output("<form action='chapel.php?op=takekey2' method='POST'>",true);
    addnav("","chapel.php?op=takekey2");
    output("`bSchlüssel wegnehmen:`b`nCharacter: <input name='name'>`n<input type='submit' class='button' value='Wegnehmen'>",true);
    output("</form>",true);
}else if ($_GET[op]=="takekey2"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Bestätige die Schlüsselentzug von:`n`n");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='chapel.php?op=takekey3&id={$row['acctid']}'>",true);
        output("".$row['name']."");
        output("</a>`n",true);
        addnav("","chapel.php?op=takekey3&id={$row['acctid']}");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
    }
}else if ($_GET[op]=="takekey3"){
        $sql = "DELETE FROM items WHERE value1 = 309 AND owner = '{$_GET['id']}' AND class='Schlüssel'";
        db_query($sql);
        output("Der Schlüssel wurde entzogen");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="makeprayer"){
    output("<form action='chapel.php?op=makeprayer2' method='POST'>",true);
    addnav("","chapel.php?op=makeprayer2");
    output("`bFolgenden Char ins Priesteramt ernennen: <input name='name'>`n<input type='submit' class='button' value='Übergeben'>",true);
    output("</form>",true);
}else if ($_GET[op]=="makeprayer2"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Bestätige Ernennung zum Priester von:`n`n");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='chapel.php?op=makeprayer3&id={$row['acctid']}'>",true);
        output("".$row['name']."");
        output("</a>`n",true);
        addnav("","chapel.php?op=makeprayer3&id={$row['acctid']}");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
    }
}else if ($_GET[op]=="makeprayer3"){
        set_special_var("prayer","1","0",$_GET[id],0," ");
        output("Die Ernennung ist erfolgt");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
}else if ($_GET[op]=="delprayer"){
    output("<form action='chapel.php?op=delprayer2' method='POST'>",true);
    addnav("","chapel.php?op=delprayer2");
    output("`bFolgenden Char aus Priesteramt entlassen: <input name='name'>`n<input type='submit' class='button' value='Übergeben'>",true);
    output("</form>",true);
}else if ($_GET[op]=="delprayer2"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Bestätige Entlassung von:`n`n");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='chapel.php?op=delprayer3&id={$row['acctid']}'>",true);
        output("".$row['name']."");
        output("</a>`n",true);
        addnav("","chapel.php?op=delprayer3&id={$row['acctid']}");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
    }
}else if ($_GET[op]=="delprayer3"){
        set_special_var("prayer","0","0",$_GET[id],0," ");
        output("Die Entlassung ist erfolgt");
        addnav("Zurück zum Schrein","chapel.php?op=priester");
}else{
    $session[user][locate]=1;
    redirect("chapel.php?op=eintreten");
}
addnav("Zurück zum Dorf","village.php"); 
page_footer(); 
?>

