
<? 
// Wahlen 1.0 by Raven @ Rabenthal.de
//
// Erstellt am 02.08.2004  Raven
//

require_once "common.php";
addcommentary();
checkday(); 
$text = "(noch ".$session[event][wahlwert]." Tage)";
if ( $session[event][wahlwert] == 1 ) $text = "(letzter Wahltag)";

page_header("Das Wahllokal"); 
if ($_GET[op]=="liste"){
    output("`n`b`c`2Die Wahlliste`0`c`b`n`n");
    $dorfalter = getsetting("daysalive",0);
    if ($_GET[sort]==1 || $_GET[sort]==" "){
        $sql="SELECT     a.acctid AS acctid, 
                a.name AS name, 
                a.dragonkills AS dragonkills, 
                a.firstday AS firstday, 
                a.laston AS laston
                FROM accounts a
                left join lotbd_guilds b on a.acctid = b.guildleader
                WHERE a.superuser <4 
                AND a.dragonkills >=15 
                AND a.prayer =0 
                AND a.acctid <>721
                AND b.guildleader is NULL
                ORDER  BY 
                dragonkills DESC , 
                firstday ASC , 
                laston DESC ";
    }
    if ($_GET[sort]==2){
        $sql="SELECT     a.acctid AS acctid, 
                a.name AS name, 
                a.dragonkills AS dragonkills, 
                a.firstday AS firstday, 
                a.laston AS laston
                FROM accounts a
                left join lotbd_guilds b on a.acctid = b.guildleader
                WHERE a.superuser <4 
                AND a.dragonkills >=15 
                AND a.prayer =0 
                AND a.acctid <>721
                AND b.guildleader is NULL 
                ORDER BY 
                firstday ASC, 
                dragonkills DESC, 
                laston DESC";
    }
    if ($_GET[sort]==3){
        $sql="SELECT     a.acctid AS acctid, 
                a.name AS name, 
                a.dragonkills AS dragonkills, 
                a.firstday AS firstday, 
                a.laston AS laston
                FROM accounts a
                left join lotbd_guilds b on a.acctid = b.guildleader
                WHERE a.superuser <4 
                AND a.dragonkills >=15 
                AND a.prayer =0 
                AND a.acctid <>721
                AND b.guildleader is NULL 
                ORDER BY 
                laston DESC, 
                dragonkills DESC, 
                firstday ASC";
    }
    output("<table cellspacing=0 border=0 align='center'>
        <tr>
        <td width=30>&nbsp;</td>
        <td align='left'>
        ",true);
    output("`b`&Die bisherigen Kandidaten mit 15 oder mehr DK`b`n`n");
    output("<table cellspacing=0 cellpadding=2 align='left'>
        <tr>
        <td>`b Name `b</td>
        <td width=10px></td>
        <td>`bDrachenkills`b</td>
        <td width=10px></td>
        <td>`b Alter`b</td>
        <td width=10px></td>
        <td>`bLastOn`b</td>
        </tr>",true);
    $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)==0){ 
              output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Götter in dieser Welt`i`0</td></tr>",true); 
        } 
        for ($i=0;$i<db_num_rows($result);$i++){ 
                $row = db_fetch_assoc($result);
        $alter = "".$dorfalter-$row[firstday]." Tage";
        if ($row[firstday]==0) $alter = "unbekannt";
        $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
        if (substr($laston,0,2)=="1 ") $laston="1 Tag";
        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
        if ($loggedin) $laston="Jetzt";
        if ($session[event][wahlen]){
            output("<tr class='".($i%2?"trlight":"trdark")."'>
                <td align='left'><a href='wahlen.php?op=einf&elect=".$row[acctid]."'>`&$row[name]</a></td>
                <td width=10px></td>
                <td align='center'>`&$row[dragonkills]</td>
                <td width=10px></td>
                <td align='right'>`&$alter`0</td>
                <td width=10px></td>
                <td align='right'>`&$laston`0</td>
            </tr>`&",true);
            addnav("","wahlen.php?op=einf&elect=$row[acctid]");
        }else{
            output("<tr class='".($i%2?"trlight":"trdark")."'>
                <td align='left'>`&$row[name]</a></td>
                <td width=10px></td>
                <td align='center'>`&$row[dragonkills]</td>
                <td width=10px></td>
                <td align='right'>`&$alter`0</td>
                <td width=10px></td>
                <td align='right'>`&$laston`0</td>
            </tr>`&",true);
        }
    }
    output("</table>",true);
    output("</td></tr></table>",true);
    addnav("Sortierung");
    addnav("Sortieren nach Drachenkämpfen","wahlen.php?op=liste&sort=1");
    addnav("Sortieren nach Alter","wahlen.php?op=liste&sort=2");
    addnav("Sortieren nach letztem Besuch","wahlen.php?op=liste&sort=3");
    addnav("-");
    addnav("Zum Wahllokal","wahlen.php");
}else if($_GET[op]=="einf"){
    $sql="SELECT acctid FROM election WHERE acctid = ".$session[user][acctid]."";
    $result=db_query($sql);
    if (db_num_rows($result)>0){
        output ("`6Du hast bereits einen Kandidaten gewählt, warte also bis zur nächsten Wahlperiode, dann kannst
            auch Du wieder Deine Stimme abgeben.`n");
    }else{
        $sql="INSERT INTO election (acctid,electid) VALUES(".$session[user][acctid].",".$_GET[elect].")";
        db_query($sql);
        $session[user][donation]+=5;
        output("`6Vielen Dank für Deine Beteiligung, Dir wurden 5 Donation Punkte dafür gutgeschrieben`0");
    }
    addnav("Zum Wahllokal","wahlen.php");
}else if($_GET[op]=="auswertung"){
    output("`n`b`c`2Das bisherige Ergebnis`0`c`b`n`n");
    $sql="SELECT count(*) as anzahl, electid, a1.name as name FROM election
        LEFT JOIN accounts as a1 ON a1.acctid = election.electid
        group by electid 
        ORDER BY anzahl DESC";
    output("<table cellspacing=0 border=0 align='center'>
        <tr>
        <td width=30>&nbsp;</td>
        <td align='left'>
        ",true);
    output("<table cellspacing=0 cellpadding=2 align='left'>
        <tr>
        <td>`b Anzahl Stimmen`b</td>
        <td width=10px></td>
        <td>`bName`b</td>
        </tr>",true);
    $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)==0){ 
              output("<tr><td colspan=4 align='center'>`&`iEs wurde noch nicht gewählt`i`0</td></tr>",true); 
        } 
        for ($i=0;$i<db_num_rows($result);$i++){ 
                $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'>
                <td align='center'>`&$row[anzahl]</a></td>
                <td width=10px></td>
                <td align='left'>`&$row[name]</td>
            </tr>`&",true);
    }
    output("</table>",true);
    output("</td></tr></table>",true);
    addnav("Zum Wahllokal","wahlen.php");

}else if($_GET[op]=="auswertdetail"){
    $sql1 = "SELECT electid, count(*) as anz from election
             group by electid
             order by anz DESC";
    $result1 = db_query($sql1) or die(sql_error($sql1));
    $max1 = db_num_rows($result1);
    for($i1=0;$i1<$max1;$i1++){
        $row1 = db_fetch_assoc($result1);
        $kandidat = $row1[electid];

            $sql2 = "SELECT a.name as Kandidat, b.name as Wähler from accounts as a, accounts as b, election as e
                     where e.electid = a.acctid and e.acctid = b.acctid
                     and e.electid = '".$kandidat."'
                     order by e.electid";
            $result2 = db_query($sql2) or die(sql_error($sql2));
            $max2 = db_num_rows($result2);
            $row2 = db_fetch_assoc($result2);
            output("`0Kandidat: $row2[Kandidat] -- `Q$max2 Stimmen:`n");
            output("`0-Wähler-: $row2[Wähler]");
            for($i2=1;$i2<$max2;$i2++){
                $row2 = db_fetch_assoc($result2);
                output("`0, $row2[Wähler]");
            }
        output("`n`n");
    }
    addnav("Zum Wahllokal","wahlen.php");
}else if ($_GET[op]=="board"){
    $boardid = "wahlen";
    if($_GET['act'] == "add1") {
        if (addmessageboard()) {
            output("In der Hoffnung, dass jeder von deinem Plakat kenntnis nimmt, hängst du es gut sichtbar an.");
        }
        elseif ($doublepost) {
            output("Es hängt schon so ein Plakat.");
        }
        else {
            output("Du heftest ein Plakat mit folgendem Inhalt an die Plakatwand:`n`n");                
            formmessageboard($boardid,'Nachricht');
        }
        addnav("Plakate ansehen","wahlen.php?op=board");
    }else{
        output("Neugierig betrachtest du die Wand, an der Plakate angebracht werden können.`n");
        viewmessageboard($boardid,'`nFolgende Plakate sind an der Wand angeschlagen.','`nEs sind keine Plakate angebracht');
        addnav("Selber hinzufügen","wahlen.php?op=board&act=add1");
    }
    addnav("Zum Wahllokal","wahlen.php");



}else{
        if ($session[user][locate]!=15){
            $session[user][locate]=15;
        redirect("wahlen.php");
        }
        output("`b`c`2Das Wahllokal`0`c`b"); 
    if ($session[user][dragonkills]<=4){
            output("`n`n`6Du hast das Wahllokal von Düstersteins betreten. Hier haben die Bürger, welche in Düsterstein schon Ruhm
            und Ehre erreicht haben, die Möglichkeit, den Rat des Landes zu wählen.`nDa Du noch ein bißchen jung
            bist, denkst Du Dir, das es besser ist, wenn nur die das machen, die hier auch schon einige Zeit
            in diesem Land verbracht haben und genau wissen, was das Beste für dieses Land ist. Du gehst lieber nochmal
            ein paar Drachen erlegen und wenn Du fünf Mal erfolgreich warst, dann wirst Du Dich sicher genug 
            fühlen, hier auch wählen zu können.
            `n`n`0");
        viewcommentary("wahllokal","Hier reden",30,"sagt");
        addnav("u?Auswertung aktuell","wahlen.php?op=auswertung");
        addnav("A?Aktualisieren","wahlen.php");
    }else{
            output("`n`n`6Du hast das Wahllokal von Düsterstein betreten. Hier hast Du die Möglichkeit, zu Wahlzeiten Deine
            Vertreter in den Verwaltungsrat von Düsterstein zu wählen, damit Deine Interessen verfolgt werden.
            Als mündiger Bürger dieses Landes weißt Du genau, was für das Wohl aller in diesem Land das Beste ist.
            `nAls kleinen Anreiz gewähren die Götter für die Wahl aber nochmal einige Donation Punkte als 
            kleine Belohnung.
            `n`0"); 
         output ("`6Wenn die Zeit gekommen ist, wirst Du die Kandidaten aus der Wahlliste per Fingerzeig 
            wählen können, doch bedenke - Du wirst nur eine Stimme haben. Die drei
            Kandidaten mit den meisten Stimmen und die bereit sind, die Aufgabe
            zu übernehmen, werden fortan für eine Legislaturperiode von 180 Spieltagen
            die Aufgaben eines Ratsherren in Düsterstein übernehmen. Bei Stimmengleichheit
            entscheiden die Götter über den endgültigen Wahlausgang.`0`n");
        if (!$session[event][wahlen]) output ("`6Die neue Wahlperiode beginnt leider 
                        erst in `^".$session[event][wahlwert]." `6Tagen.`0`n`n");
        if ($session[event][wahlen]) output ("`^Zur Zeit haben wir die aktuelle Wahlperiode $text`n`n`0");
            viewcommentary("wahllokal","Hier reden",30,"sagt");
        addnav("Wahlliste","wahlen.php?op=liste&sort=1");
         addnav("u?Auswertung aktuell","wahlen.php?op=auswertung");
        if ($session[user][superuser]>=4)  addnav("d?Auswertung detail","wahlen.php?op=auswertdetail");
    addnav ("Plakatwand","wahlen.php?op=board");
        addnav("A?Aktualisieren","wahlen.php");
    }
}
addnav("Zurück zum Dorfamt","dorfamt.php");
addnav("Zurück zum Dorf","village.php"); 
page_footer(); 
?>


