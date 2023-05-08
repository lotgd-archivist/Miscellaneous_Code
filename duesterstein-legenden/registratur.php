
<?
require_once "common.php";
page_header("Die Registratur");

//namecheck status-info
//0 = ungeprüft
//2 = angemailt
//4 = erinnert
//9 = akzeptiert
//Steuerung
$trenn = 5;

function delete_user ($who=0) {
    $sql = "SELECT name from accounts WHERE acctid='$who'";
    $res = db_query($sql);
    $sql = "UPDATE items SET owner=0 WHERE owner=$who";
    db_query($sql);
    $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$who AND status=1";
    db_query($sql);
    $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$who AND status=0";
    db_query($sql);
    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$who";
    db_query($sql);
    $sql = "DELETE FROM pvp WHERE acctid2=$who OR acctid1=$who";
       db_query($sql) or die(db_error(LINK));
    $sql = "DELETE FROM accounts WHERE acctid='$who'";
    db_query($sql);
    output( db_affected_rows()." user deleted.");
    while ($row = db_fetch_assoc($res)) {
        addnews("`#{$row['name']} wurde von den Göttern zerstört.");
    }
}

if ($_GET[op]==""){   //intro
    addnav("Registraturen");
    addnav("ungeprüfte Namen","registratur.php?op=newname");
    addnav("angemailte Namen","registratur.php?op=mailname");
    addnav("akzeptierte Namen","registratur.php?op=accname");
    addnav("Navigation");
    addnav("Zurück ins Büro","dorfamt.php?op=verwaltungsbuero");
    addnav("Zurück zum Dorfamt","dorfamt.php");
    addnav("Zurück ins Dorf","village.php");

    output("Du betrittst die Registratur. In diesem etwas abseits gelegenen
    Gewölbe sind einige Schriftführer damit beschäftigt, das Einwohner-Verzeichnis
    von Rabenthal auf einem aktuellen Stand zu halten.`n`0");
}
else if ($_GET[op]=="newname") {    //Liste ungeprüfte Bewohner
    addnav("Navigation");
    addnav("Zurück zur Registratur","registratur.php");
    addnav("Zurück ins Büro","dorfamt.php?op=verwaltungsbuero");
    addnav("Zurück zum Dorfamt","dorfamt.php");
    addnav("Zurück ins Dorf","village.php");

    output("Du schlägst das grosse Buch der neuen Bewohner auf und blätterst
    aufmerksam durch die Seiten.`n
    Die folgenden Charakter-Namen sind noch unbearbeitet:`n`n`0");

    $sql = 'SELECT acctid, name, login, laston FROM accounts
            WHERE locked=0 and namecheck=0
            ORDER BY acctid DESC';
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("Es sind keine Bewohner mit ungeprüften Namen verzeichnet!`0`n");
    }
    else {
        output("<table border=0 cellpadding=2 cellspacing=1 >",true);
        output("<tr class='input'><td>Nummer</td><td>Spieler</td><td>Last On</td>
        <td>Namenswechsel</td><td>Akzeptieren</td><td>Löschung</td>",true);
        $count = 0;
        while ($row = db_fetch_assoc($result)) {
            if ( $count == 5 ) {
                output("<tr class='trmain'>",true);
                output("<td>----</td><td>----------</td><td>---------</td>",true);
                output("<td>----</td><td>--</td><td>-------</td>",true);
                output("</tr>",true);
                $count = 1;
            } else $count++;
            $tmp = $row['acctid'];
            $tmp2 = $row['name'];
            $tmp3 = $row['login'];
            output("<tr class='trmain'>",true);
            output("<td>".$tmp."</td><td>".$tmp2."</td>",true);
        output("<td>",true);
        $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
        if (substr($laston,0,2)=="1 ") $laston="1 Tag";
        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
        if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
        if ($loggedin) $laston="Jetzt";
        output($laston);
        output("</td>",true);
            output("<td><a href='registratur.php?op=mail&userid=".$tmp."'>`^Mail`0</a></td>",true);
            output("<td><a href='registratur.php?op=accept1&userid=".$tmp."'>`@ok`0</a></td>",true);
            output("<td><a href='registratur.php?op=delete1&userid=".$tmp."'>`4löschen`0</a></td>",true);
            output("</tr>",true);
            addnav("","registratur.php?op=mail&userid=$tmp");
            addnav("","registratur.php?op=accept1&userid=$tmp");
            addnav("","registratur.php?op=delete1&userid=$tmp");
        }
        output("</table>",true);
    }
}
else if ($_GET[op]=="mail") {   //mehlen
    //$wer = $_GET['name'];
    $to = $_GET['userid'];
    $subject = "Namensänderung";     //betreff
    $from = $session[user][acctid];  //absender: eingeloggter (halb)gott
    $body ="Hallo,
    ich möchte Dich bitten, Dir einen neuen Namen auszudenken und mir diesen mitzuteilen, damit ich ihn ändern kann.
    Er passt unserer Meinung nach nicht in dieses mittelalterliche Rollenspiel.`n
    Dein neuer Name darf keinen Titel (Lord, Graf, Meister etc.) und keine Beschreibung (ScharfesSchwert, grünerHund etc.) enthalten. Er sollte nach Mittelalter klingen, mindestens jedoch nach Mythen und Sagen. Englische Namen sind dafür nur bedingt geeignet. Namen von Prominenten, Personen der Zeitgeschichte oder Film-Helden sind ebenfalls nicht erwünscht.`n
    WICHTIG: Dein neuer Name wird automatisch für Dein Login übernommen, Dein Passwort bleibt unverändert.`n
    Sollte ich keine Antwort auf meine Mail bekommen, wird dein Char gelöscht.`n
    Liebe Grüße`n".$session[user][name];
    systemmail($to,$subject,$body,$from);
    $tag = getsetting("daysalive",0);
    $sql = "UPDATE accounts SET namecheck=2, namecheckday=$tag WHERE acctid=$_GET[userid]";
    db_query($sql);
    addnav("Zurück ins Dorf","village.php");
    addnav("","registratur.php?op=newname");
    redirect("registratur.php?op=newname");
}
else if ($_GET[op]=="accept1") {  //akzeptieren aus liste neue bewohner
    $sql = "UPDATE accounts SET namecheck=9 WHERE acctid=$_GET[userid]";
    db_query($sql);
    addnav("Zurück ins Dorf","village.php");
    addnav("","registratur.php?op=newname");
    redirect("registratur.php?op=newname");
}
else if ($_GET[op]=="accept2") {  //akzeptieren aus liste angemailte bewohner
    $sql = "UPDATE accounts SET namecheck=9 WHERE acctid=$_GET[userid]";
    db_query($sql);
    addnav("Zurück ins Dorf","village.php");
    addnav("","registratur.php?op=mailname");
    redirect("registratur.php?op=mailname");
}
else if ($_GET[op]=="delete1") {   //user löschen aus liste neue namen
    addnav("Zurück ins Dorf","village.php");
    delete_user ($_GET[userid]);
    addnav("","registratur.php?op=newname");
    redirect("registratur.php?op=newname");
}
else if ($_GET[op]=="delete2") {   //user löschen aus liste angemailte namen
    addnav("Zurück ins Dorf","village.php");
    delete_user ($_GET[userid]);
    addnav("","registratur.php?op=mailname");
    redirect("registratur.php?op=mailname");
}
else if ($_GET[op]=="accname") {  //liste akzeptierte Bewohner
    addnav("Navigation");
    addnav("Zurück zur Registratur","registratur.php");
    addnav("Zurück ins Büro","dorfamt.php?op=verwaltungsbuero");
    addnav("Zurück zum Dorfamt","dorfamt.php");
    addnav("Zurück ins Dorf","village.php");

    output("Du schmökerst im grossen Buch der Bewohner, wer alles in Rabenthal
    gemeldet ist:`n`n`0");

    $sql = 'SELECT acctid, name, login, title FROM accounts
            WHERE locked=0 and namecheck=9
            ORDER BY login ASC';
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("Es sind keine Bewohner mit akzeptierten Namen verzeichnet!`0`n");
    }
    else {
        $comp = "";
        $space = " ";
        output("<table border=0 cellpadding=2 cellspacing=1 >",true);
        output("<tr class='input'><td>Nummer</td><td>Name</td><td>Titel</td>",true);
        while ($row = db_fetch_assoc($result)) {
            $tmp = $row['acctid'];
            $letter = ucfirst( substr($row['login'],0,1));
            if ( $letter != $comp ) {
                output("<tr class='trmain'>",true);
                output("<td>".$space."</td><td>`6-----`^  `b".$letter."`b  `6------`0 </td><td>".$space."</td>",true);
                output("</tr>",true);

                $comp = $letter;
            }
            output("<tr class='trmain'>",true);
            output("<td>".$tmp."</td><td>".$row['login']."</td><td>".$row['title']."</td>",true);
            output("</tr>",true);
        }
        output("</table>",true);
    }
}
else if ($_GET[op]=="mailname") {   //liste angemailte Bewohner
    addnav("Navigation");
    addnav("Zurück zur Registratur","registratur.php");
    addnav("Zurück ins Büro","dorfamt.php?op=verwaltungsbuero");
    addnav("Zurück zum Dorfamt","dorfamt.php");
    addnav("Zurück ins Dorf","village.php");

    output("Du schmökerst in einer Pergament-Rolle, in der alle Bewohner verzeichnet
    sind, die wegen ihrer Namenswahl angeschrieben worden sind:`n`n`n`n`n`n`0");

    $sql = 'SELECT acctid, name, laston, namecheckday FROM accounts
            WHERE locked=0 and namecheck=2
            ORDER BY acctid ASC';
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("Es sind keine angeschriebenen Bewohner verzeichnet!`0`n");
    }
    else {
        output("<table border=0 cellpadding=2 cellspacing=1 >",true);
        output("<tr class='input'><td>Nummer</td><td>Spieler</td><td>Last On&nbsp;</td><td>Anschreiben</td><td>Namenswechsel</td><td>Akzeptieren</td><td>Löschung</td>",true);
        while ($row = db_fetch_assoc($result)) {
        if ($row[namecheckday] != 0){
        $tagheute = floor(getsetting("daysalive",0) / 6);
        $tagnum = $tagheute - floor($row[namecheckday] / 6);
        $tag = "".$tagnum." Tage";
        if ($tagnum == 0) $tag = "Heute";
        if ($tagnum == 1) $tag = "Gestern";
        }else{
        $tag = "Heute";
            }
            $tmp = $row['acctid'];
            output("<tr class='trmain'>",true);
            output("<td>".$tmp."</td><td>".$row['name']."</td>",true);
        output("<td>",true);
            $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
            if (substr($laston,0,2)=="1 ") $laston="1 Tag";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
            if ($loggedin) $laston="Jetzt";
            output($laston);
        output("</td>",true);
        output("<td>".$tag."</td>",true);
            output("<td><a href='dorfamt.php?op=verwaltungsbuero&act=rename3&id=".$tmp."'>`Qumbennen`0</a></td>",true);
            output("<td><a href='registratur.php?op=accept2&userid=".$tmp."'>`@ok`0</a></td>",true);
            output("<td><a href='registratur.php?op=delete2&userid=".$tmp."'>`4löschen`0</a></td>",true);
            output("</tr>",true);
               //umbenennung ueber verwaltungsbuero
            addnav("","dorfamt.php?op=verwaltungsbuero&act=rename3&id=$tmp");
            addnav("","registratur.php?op=delete2&userid=$tmp");
            addnav("","registratur.php?op=accept2&userid=$tmp");
        }
        output("</table>",true);
    }
}

page_footer();
?>


