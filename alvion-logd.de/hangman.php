
<?php
#####################################################
#   -hangman for LotgD 0.97 by Solovaid             #
#   -grafiken by schansche(http://via-silianae.ch/) #
#   -24.11.05                                       #
#   -codet for http://ossilein.de                   #
#   -nutzung nur erlaubt wenn eigene source auch    #
#    frei zugänglich ist                            #
#   -Kontakt: godric@ossilein.de                    #
#             ossiboy29@t-online.de                 #
#   -ICQ: 348 295 075                               #
#                                                   #
#   -copyright für function display_table liegt     #
#    nicht bei mir                                  #
#                                                   #
#   -sql von hier unten ausführen und hangman.php   #
#    in den root                                    #
#   -an beliebiger stelle mit                       #
#    addnav("Hangman","hangman.php");               #
#    verlinken, addnavs im Code anpassen            #
#                                                   #
#   -beim eintragen von neuen Wörtern in die DB     #
#    unbedingt GROß schreiben, keine Umlaute,       #
#    kein ß verwenden. Leerzeichen und - können     #
#    mit verwendet werden                           #
#                                                   #
#####################################################

/*
CREATE TABLE `hangman_txt` (
        `id` int(10) unsigned NOT NULL auto_increment,
        `word` varchar(20) NOT NULL default '',
        `best` int(11) NOT NULL default '0',
        `errors` tinyint(3) unsigned NOT NULL default '6',
        `time` int(11) unsigned NOT NULL default '0',
        `name` varchar(255) NOT NULL default '',
        PRIMARY KEY  (id)
        ) TYPE=MyISAM;
         
        Einträge in der DB mit:
        INSERT INTO hangman_txt (word) VALUES ('TEST');
        
*/

$ergebnis = false; //setze es auf true wenn zum schluß das richtige wort angezeigt werden soll
require_once("common.php");

// functions and variable
$alphabet = array ('A' => 'a','B' => 'b','C' => 'c','D' => 'd','E' => 'e',
'F' => 'f','G' => 'g','H' => 'h','I' => 'i','J' => 'j','K' => 'k','L' => 'l',
'M' => 'm','N' => 'n','O' => 'o','P' => 'p','Q' => 'q','R' => 'r','S' => 's',
'T' => 't','U' => 'u','V' => 'v','W' => 'w','X' => 'x','Y' => 'y','Z' => 'z');
// session_start ();

//liest ein zufallswort aus der db aus
function zufallswort () { 
    $result = db_query("select id, word from hangman_txt ORDER BY RAND() LIMIT 1"); 
    $wort = db_fetch_assoc($result); 
    return ($wort); 
}

// überprüfen ob spieler zur bestenliste hinzugefügt werden kann
function kontrolle_rekorde ($wordid, $speed, $versuche) {
    $chk = array(false);
    $result =db_query("select * from hangman_txt where id = ".$wordid);
    $spalte = db_fetch_assoc($result);
    if ($versuche<$spalte['errors'] or ($versuche==$spalte['errors'] and $speed<$spalte['time']) or $spalte['name']="")
    $chk[0] = true;
    if ($spalte['errors']==6) $spalte['best']='*neu**';
    $chk['best'] = $spalte['best'];
    $chk['errors'] = $spalte['errors'];
    $chk['time'] = $spalte['time'];
    return $chk;
}

// liest die aktuelle bestenliste
function anzeige_rekorde ($wordid) {
    $result =db_query("select best, errors, time from hangman_txt where id = ".$wordid);
    $spalte = db_fetch_assoc($result);
    return $spalte;
}

// zeige alle ungeratenen buchstaben mit _
function berechne_eingabe ($wort, $ausklammern, $alphabet) {
    $alphabet = array_flip ($alphabet);
    $ausgeklammert = strtr ($ausklammern, $alphabet);
    $ausgeklammert = '['.$ausklammern.']';
    $ausgeklammert = preg_replace ("/$ausgeklammert/", '', '[ABCDEFGHIJKLMNOPQRSTUVWXYZ]');
    $eingabe = preg_replace ("/$ausgeklammert/", '_', $wort);
    return $eingabe;
}

// sucht den gewählten Buchstaben im Wort
function buchstabe_finden ($buchstabe, $wort, $auswahl, $alphabet) {
    $alphabet = array_flip ($alphabet);
    $buchstabe = substr ($buchstabe, 0, 1);
    $buchstabe = strtr ($buchstabe, $alphabet);
    $auswahl.= $buchstabe;
    if (preg_match ("/$buchstabe/", $wort)) {
        $kontrolle = true;
    }
    else {
        $kontrolle = false;
    }
    $antwort = array ($auswahl, $kontrolle);
    return $antwort;
}

// nun zum programm!
page_header("Hangman");
$session['user']['standort']="`4Bei Ramius";
output("<font size='+2'>`n`c`b`@HANGMAN`b`n</font>",true);
//    output("<font size='-2'>`&by Solovaid for http://ossilein.de`0`c`n`n</font>",true);//muß unbedingt stehen bleiben, sonst verfällt das Nutzungsrecht!!!

if ($_GET['op']==''){
// ist nur dazu da um die namen zu aktuallisieren falls einer geändert wurde
        $sql = "UPDATE hangman_txt SET name='".$session['user']['name']."' WHERE  best='".$session['user']['acctid']."'";
        db_query($sql);
    redirect("hangman.php?op=rang");
}

//Rangliste zum Spiel
if ($_GET['op']=='rang'){
    $playersperpage = 50;
    $op = "rang";
    if ($_GET['op']) $op = $_GET['op'];
    $subop = "most";
    if ($_GET['subop']) $subop = $_GET['subop'];

    $sql = "SELECT count(name) AS c FROM hangman_txt WHERE best > 0 GROUP BY best";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $totalplayers = $row['c'];

    $page = 1;
    if ($_GET['page']) $page = (int)$_GET['page'];
    $pageoffset = $page;
    if ($pageoffset > 0) $pageoffset--;
    $pageoffset *= $playersperpage;
    $from = $pageoffset+1;
    $to = min($pageoffset+$playersperpage, $totalplayers);
    $limit = "$pageoffset,$playersperpage";

    function display_table_hangman($title, $sql, $none=false, $foot=false, $data_header=false, $tag=false){
        global $session, $from, $to, $page;
        output("`c`b`^$title`0`b `7(Seite $page: $from-$to)`0`c`n");
        output('<table cellspacing="0" cellpadding="2" align="center"><tr class="trhead">',true);
        output("<td>`bRang`b</td><td>`bName`b</td>", true);
        if ($data_header !== false) {
            for ($i = 0; $i < count($data_header); $i++) {
                output("<td>`b".$data_header[$i]."`b</td>", true);
            }
        }
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
            $size = ($data_header === false) ? 2 : 2+count($data_header);
            //echo $size;
            if ($none === false) $none = "Keine Schüler gefunden";
            output('<tr class="trlight"><td colspan="'. $size .'" align="center">`&' . $none .'`0</td></tr>',true);
        } else {
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                if ($row[name]==$session[user][name]){
                    //output("<tr class='hilight'>",true);
                    output("<tr bgcolor='#005500'>",true);
                } else {
                    output('<tr class="'.($i%2?"trlight":"trdark").'">',true);
                }
                output("<td>".($i+$from).".</td><td>`&{$row[name]}`0</td>",true);
                if ($data_header !== false) {
                    for ($j = 0; $j < count($data_header); $j++) {
                            $id = "data" . ($j+1);
                            $val = $row[$id];
                            if ($tag !== false) $val = $val . " " . $tag[$j];
                        output("<td align='center'>$val</td>",true);
                    }
                }
                output("</tr>",true);
            }
        }
        output("</table>", true);
        if ($foot !== false) output("`n`c$foot`c");
    }

    //Ausgabe der Rangliste
    $order = "DESC";
    if ($_GET[subop] == "least") $order = "ASC";
    $row8 = db_fetch_assoc(db_query('SELECT count(id) AS maxwort FROM hangman_txt'));
    $row9 = db_fetch_assoc(db_query('SELECT count(id) AS vergeben FROM hangman_txt WHERE best > 0'));
    output("`c`^Die Datenbank beinhaltet momentan`@ $row8[maxwort] `^Wörter,`ndavon sind schon `@$row9[vergeben] `^Rekorde vergeben.`n`n`@Ich glaube es ist an der Zeit einige Rekorde zu brechen ;-)`n`c");
    $sql = "SELECT name, COUNT(best) AS data1 FROM hangman_txt WHERE best > 0 GROUP BY name ORDER BY data1 $order";

    if ($_GET[subop] == "least") $adverb = "wenigsten";
    else $adverb = "meisten";
    $title = "Die Schüler die bei den $adverb Wörtern in der Top stehen";
    $headers = array("Wörter");
    $none = "Dieses Abenteuer hat noch keiner geschafft";
    display_table_hangman($title, $sql, $none, false, $headers, false);
        
    addnav("Hangman");
    addnav("zum Spiel","hangman.php?op=hangman");
    addnav("Zurück zur Spielauswahl","shades_spiele.php");
    addnav("Sortieren nach");
    addnav("Meisten", "hangman.php?op=$op&subop=most&page=$page");
    addnav("Wenigsten", "hangman.php?op=$op&subop=least&page=$page");
    addnav("Seiten");
    for($i = 0; $i < $totalplayers; $i+= $playersperpage) {
        $pnum = ($i/$playersperpage+1);
        $min = ($i+1);
        $max = min($i+$playersperpage,$totalplayers);
        addnav("Seite $pnum ($min-$max)", "hangman.php?op=$op&subop=$subop&page=$pnum");
    }
}

// nun zum programm!
if ($_GET['op']=='hangman'){
    // das zu erratende Wort ist bereits gewählt worden
        if (isset($_GET['letter']) and isset($_SESSION['gchangman'])) {
            $variab = explode ('/', $_SESSION['gchangman']);
            $tip = $variab[0];
            $auswahl = $variab[1];
            $versuche = $variab[2];
            $checkbuchstabe = buchstabe_finden ($_GET['letter'], $tip, $auswahl, $alphabet);
            $auswahl = $checkbuchstabe[0];
            if (!$checkbuchstabe[1]) {
                $versuche = $versuche +1;
            }
            $eingabe = berechne_eingabe ($tip, $auswahl, $alphabet);
        } else {
            // alles löschen und neues wort auswählen
            
            $versuche=0;
            $auswahl = ('_');
            $wort = zufallswort ();
            $tip = $wort['word'];
            $_SESSION['gcid'] = $wort['id'];
            $eingabe = berechne_eingabe ($tip, $auswahl, $alphabet);
            $_SESSION['gcspeed'] = time();
        }
        // setzt die Variablen per PHP sessions
        $_SESSION['gchangman'] = ($tip.'/'.$auswahl.'/'.$versuche);

        if ($versuche>6) $versuche=6;
        // Ausgabe des zu lösenden Wortes
        output('<p><img src="images/hangman/hang_'.($versuche+1).'.png" style="border:0;width:100px;height:100px" alt="Miss '.$versuche.'/6" /></p>',true);
        output("\n<p>\n",true);
        $charakter = preg_split('//', $eingabe, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($charakter as $buchalpha) {
            $buchalpha = strtr ($buchalpha, $alphabet);
            if ($buchalpha==' ') {
                output("<font size='+2'>`b`6&nbsp;`b</font>",true);
            } else {
                output("<font size='+2'>`b`6".strtoupper($buchalpha)."`b</font>",true);
            }
            output("\n",true);
            output("<font size='+2'>`b`6&nbsp;`b</font>",true);
            output("\n",true);
        }
        output('</p>',true);
        addnav("Hangman");
        addnav("Neues Spiel","hangman.php?op=hangman");
        addnav("Zurück","hangman.php");


        //Auswertung
        if ($tip!=$eingabe) {
            if ($versuche>=6) {
                // hehe, erhängt ;-)
                    output("`\$Schade, du hast Dich erhängt.`n",true);
                    if ($ergebnis) output('Das zu erratene Wort war:`^ '.$tip,true);
                    $bestnow = anzeige_rekorde ($_SESSION['gcid']);
                    if ($bestnow['errors']==6) output('`n`KKein Grund zum ärgern, das Wort hat noch keiner erraten.</p>',true);
                    else {
                    if ($bestnow['best']=='') $bestnow['best']="Ein anonymer Spieler";
                    $sql3="select name from accounts where acctid = '".$bestnow['best']."'";
                    $result3 = db_query($sql3) or die(db_error(LINK));
                    $spielername1 = db_fetch_assoc($result3);
                    output("`n`^$spielername1[name] `Kbrauchte nur `^$bestnow[time] `KSekunden und `^$bestnow[errors] `KFehlversuche.</p>",true);
                }
                output("<a href='./hangman.php?op=hangman'>Neues Spiel</a>",true);
                addnav("","hangman.php?op=hangman");
            } else {
                // Alphabet anzeigen und gewählte Buchstaben markieren
                $wahl = preg_split('//', $auswahl, -1, PREG_SPLIT_NO_EMPTY);
                output("\n<p>\n",true);
                    foreach ($alphabet as $buchalpha) {
                $kontr = false;
                foreach ($wahl as $lett) {
                    if (!strcasecmp ($buchalpha, $lett)) {
                        $kontr = true;
                    }
                }
                if ($kontr) {
                    output("<font size='+2'>`b`6".strtoupper($buchalpha)."`b</font>",true);
                } else {
                    output(' <font size="+2"><a href="./hangman.php?op=hangman&letter='.$buchalpha.'">`b`@'.strtoupper($buchalpha).'`b</a></font>',true);
                }
                if ($buchalpha=='m') output("\n <br />",true);
                output("\n",true);
            }
                output("</p>\n",true);
        }
    } else {
        // Word gelöst
        output("\n<p>\n",true);
        output("`@Gratulation! Du hast das Wort erraten",true);
        $speed = time() - $_SESSION['gcspeed'];
        $oldbest = kontrolle_rekorde ($_SESSION['gcid'], $speed, $versuche);
        if ($oldbest['best']=='') $oldbest['best']="ein anonymer Spieler";
        $sql3="select name from accounts where acctid = '".$oldbest['best']."'";
        $result3 = db_query($sql3) or die(db_error(LINK));
        $spielername = db_fetch_assoc($result3);    
        if ($oldbest[0]) {
            // und das ist ein neuer rekord
            output("`@`nund du hast einen neuen Rekord mit `^$speed `@Sekunden und nur `^$versuche `@Fehlversuchen aufgestellt!",true);
            addnews("`&".$session[user][name]."`& hat einen neuen Rekord bei Hangman aufgestellt!`0");
            if ($oldbest['best']!='*neu**') output("`@Alter Rekordhalter: `^$spielername[name]",true);
            $result =db_query("update hangman_txt set errors = "
            .$versuche.", time = ".$speed.
            " where id = ".$_SESSION['gcid']);
            // übertragen der rekorddaten
            output ("</p>",true);
            $spielerid= $session['user']['acctid'];
            output ("<form method='post' action='./hangman.php?op=newrekord'>",true);
            output ("<input type='hidden' name='wordid' value='$_SESSION[gcid]' />",true);
            output ("<input type='hidden' name='besttime' value='$speed' />",true);
            output ("<input type='hidden' name='besttent' value='$versuche' />",true);
            output ("<input type='hidden' name='newbest' value='$spielerid' />",true);
            output ("`c<input type='submit' value='Rekord eintragen' name='go' />`c",true);
            output ("</form>",true);
        }
        // Wort gelöst aber ein anderer war schneller
        else {
            output("<p>`n`^aber`@ $spielername[name] `^brauchte nur`@ $oldbest[time] `^Sekunden und nur`@ $oldbest[errors] `^Fehlversuche.</p>",true);
            output("<a href='./hangman.php?op=hangman'>`cNeues Spiel`c</a>",true);
//            addnav("Hangman");
            addnav("","hangman.php?op=hangman");
//            addnav("Neues Spiel","hangman.php?op=hangman");
//            addnav("Zurück","hangman.php");
        }
    }
    addnav("","hangman.php?op=hangman&letter=a");
    addnav("","hangman.php?op=hangman&letter=b");
    addnav("","hangman.php?op=hangman&letter=c");
    addnav("","hangman.php?op=hangman&letter=d");
    addnav("","hangman.php?op=hangman&letter=e");
    addnav("","hangman.php?op=hangman&letter=f");
    addnav("","hangman.php?op=hangman&letter=g");
    addnav("","hangman.php?op=hangman&letter=h");
    addnav("","hangman.php?op=hangman&letter=i");
    addnav("","hangman.php?op=hangman&letter=j");
    addnav("","hangman.php?op=hangman&letter=k");
    addnav("","hangman.php?op=hangman&letter=l");
    addnav("","hangman.php?op=hangman&letter=m");
    addnav("","hangman.php?op=hangman&letter=n");
    addnav("","hangman.php?op=hangman&letter=o");
    addnav("","hangman.php?op=hangman&letter=p");
    addnav("","hangman.php?op=hangman&letter=q");
    addnav("","hangman.php?op=hangman&letter=r");
    addnav("","hangman.php?op=hangman&letter=s");
    addnav("","hangman.php?op=hangman&letter=t");
    addnav("","hangman.php?op=hangman&letter=u");
    addnav("","hangman.php?op=hangman&letter=v");
    addnav("","hangman.php?op=hangman&letter=w");
    addnav("","hangman.php?op=hangman&letter=x");
    addnav("","hangman.php?op=hangman&letter=y");
    addnav("","hangman.php?op=hangman&letter=z");
    addnav("","hangman.php?op=newrekord");
}

if ($_GET['op']=='newrekord'){
    $result =db_query("select * from hangman_txt where id = '".$_POST['wordid']."'");
    $spalte =db_fetch_assoc($result);
    if ($spalte['errors']==$_POST['besttent'] and $spalte['time']==$_POST['besttime']) {
        $res =db_query("update hangman_txt set best = '".$_POST['newbest'].
        "', name = '".$session['user']['name'].
        "'where id = '".$_POST['wordid']."'");
        output ("`c`@Neuer Rekord hinzugefügt!`c",true);
    }
    else output ("`c`\$Schade. Es trat ein Fehler auf und dein Name konnte nicht gespeichert werden.`c",true);
    output('<a href="./hangman.php?op=hangman">Neues Spiel</a>',true);
    addnav("Hangman");
    addnav("","hangman.php?op=hangman");
    addnav("Neues Spiel","hangman.php?op=hangman");
    addnav("Zurück","hangman.php");
    addnav("Zurück zur Spielauswahl","shades_spiele.php");    
}
output("`n`n`n`n`n<font size='-1'>`&by Solovaid for http://ossilein.de`0`n</font>",true);//muß unbedingt stehen bleiben, sonst verfällt das Nutzungsrecht!!!
output("`n<font size='-1'>`&PHP7-kompatibel durch Linus für http://alvion-logd.de`0`c`n`n</font>",true);

page_footer();


