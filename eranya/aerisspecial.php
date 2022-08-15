
<?php
// Aeris' besondere Aufmerksamkeiten - addon für blumenmaedchen.php
// erstellt für eranya.de
// @silva
// Frage an Jule: Tut zusätzlicher Check durch User vorm Abschicken Not?
require_once("common.php");
// Variablen festlegen
$int_preis = 100;                # Preis pro Aufmerksamkeit
$int_titlemaxlen = 100;            # max. Länge des Namens
$int_textmaxlen = 1000;            # max. Länge des Beschreibungstexts
// op's auflisten
page_header("Aeris' besondere Aufmerksamkeiten");
output("`c`b`rAeris' besondere Aufmerksamkeiten`b`c`n");
switch($_GET['op']) {
    case '':
        // arme Schlucker dürfen nichts versenden
        if($session['user']['gold'] < $int_preis) {
            output("`rDu fragst Aeris, ob sie bereit wäre, jemandem ein besonderes Geschenk zu überbringen. Sie nickt begeistert und bittet dich, zu ihr zu kommen, sobald du 
                   `^".$int_preis." `rGoldstücke zusammen hast.");
        // ... alle anderen können direkt mit der Suche starten
        } else {
            output("`rDu fragst Aeris, ob sie bereit wäre, jemandem ein besonderes Geschenk zu überbringen. Begeistert nickt sie und fragt direkt nach dem Namen des Empfängers. 
                    Außerdem bittet sie dich um `^".$int_preis." `rGoldstücke für ihren Botengang.`n`n");
            output("<form action='aerisspecial.php?op=search' method='post'>
                    `rName: <input name='searchuser'> <input type='submit' class='button' value='Suchen'>
                    </form>");
            addnav('','aerisspecial.php?op=search');
        }
    break;
    // User über Login suchen
    case 'search':
        output("`rAn wen soll deine besondere Aufmerksamkeit gehen?`n`n");
        // Nochmaliges Suchen zulassen
        if(strlen($_GET['searchuser']) > 0) {
            $_POST['searchuser'] = $_GET['searchuser'];
        }
        output("<form action='aerisspecial.php?op=search' method='post'>
                `rName: <input name='searchuser' value='".$_POST['searchuser']."'> <input type='submit' class='button' value='Suchen'>
                </form>");
        addnav('','aerisspecial.php?op=search');
        // Abfrage der infrage kommenden Charaktere + Seiten-Navigation
        $str_search="%";
        for($c=0;$c<strlen($_POST['searchuser']);$c++){
            $str_search .= substr($_POST['searchuser'],$c,1)."%";        # Suchbegriff vorbereiten
        }
        $int_anz = 25;                                                     # Max.anzahl pro Seite
        if(!isset($_GET['limit']) || $_GET['limit'] == 0){
            $int_page = 0;
        } else {                                                        # ggf. Seiten-Navigation ergänzen -> vorherige Seite
            $int_page = (int)$_GET['limit'];
            addnav("Vorherige Seite","aerisspecial.php?op=search&limit=".($int_page-1)."&searchuser=".$_POST['searchuser']);
        }
        $str_limit = ($int_page*$int_anz).",".($int_anz+1);
        $sql = "SELECT name,sex,acctid FROM accounts WHERE name LIKE '".$str_search."' AND acctid != ".$session['user']['acctid']." AND lastip != '".$session['user']['lastip']."' ORDER BY login,level LIMIT ".$str_limit;
        $result = db_query($sql);
        if (db_num_rows($result) > $int_anz) {                            # ggf. Seiten-Navigation ergänzen -> nächste Seite
            addnav("Nächste Seite","aerisspecial.php?op=search&limit=".($int_page+1)."&searchuser=".$_POST['searchuser']);
        }
        // Liste der gefundenen Charaktere erstellen
        output("`n`n
                <table>
                    <tr class='trhead'>
                        <td>Name</td><td>Geschlecht</td>
                    </tr>");
        for($i=0;$i<db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            $str_name = "<a href='aerisspecial.php?op=text&id=".$row['acctid']."'>`&".$row['name']."</a>";                # Charaktername inkl. Link
            output("<tr class='".($i%2 ? "trlight" : "trdark")."'><td>".$str_name."</td><td align='center'><img src='images/".($row['sex'] ? "female" : "male").".gif'></td></tr>");
            addnav("","aerisspecial.php?op=text&id=".$row['acctid']);
        }
        output("</table>`n`n");
    break;
    // Geschenktext formulieren
    case 'text':
        $int_userid = (int)$_GET['id'];
        $row = db_fetch_assoc(db_query("SELECT name FROM accounts WHERE acctid = ".$int_userid." LIMIT 1"));
        output("`rAeris notiert sich lächelnd den Namen von `&".$row['name']."`r, ehe sie dich fragt, welche besondere Aufmerksamkeit sie für dich überbringen soll.`n
                (Der Name darf max. ".$int_titlemaxlen." Zeichen und die Beschreibung max. ".$int_textmaxlen." Zeichen lang sein.)`n`n
                `n
                <form action='aerisspecial.php?op=send&id=".$int_userid."' method='post'>
                <script language='JavaScript'>
                <!--
                function CountMax(wert,el) {
                    var max = wert;
                    var handler_counter = document.getElementById(el+'_jscounter');
                    var handler = document.getElementById(el);
                    var str = handler.value;
                    wert = max - str.length;
                    if (wert < 0) {
                        handler.value = str.substring(0,max);
                        wert = max-str.length;
                        handler_counter.value = wert;
                    } else {
                        handler_counter.value = max - str.length;
                    }
                }
                //-->
                </script>
                `rName: <input name='gifttitle' id='gifttitle' onkeydown='CountMax(".$int_titlemaxlen.",\"gifttitle\");' onkeyup='CountMax(".$int_titlemaxlen.",\"gifttitle\");'> (noch <input type='text' id='gifttitle_jscounter' size='4' value='".$int_titlemaxlen."' readonly> Zeichen übrig)`n
                `n
                Beschreibung: (noch <input type='text' id='text_jscounter' size='4' value='".$int_textmaxlen."' readonly> Zeichen übrig)`n
                <textarea name='text' id='text' class='input' cols='50' rows='4' onkeydown='CountMax(".$int_textmaxlen.",\"text\");' onkeyup='CountMax(".$int_textmaxlen.",\"text\");'></textarea>`n
                `n
                <input type='submit' class='button' value='Absenden'>
                </form>`n`n`n");
        addnav("","aerisspecial.php?op=send&id=".$int_userid);
        // Vorschau
        output("`b`rPosthörnchen-Vorschau:`b`n`n
                `n
                <img src='images/uscroll.GIF' width='182' height='11' alt=''>
                `n
                `°".date('d. M, H:i')." - `i`^System`i`°:`n
                `n
                `b`I&rsaquo; `rBesondere Aufmerksamkeit`b`n
                `n
                `rMit einem Lächeln übergibt dir Aeris diese ganz besondere Aufmerksamkeit, die ".$session['user']['name']." `rfür dich ausgesucht hat:`n
                `n
                `b`h".js_preview('gifttitle')."`b `i`7(".js_preview('text')."`7)`i`n
                `n
                <img src='images/lscroll.GIF' width='182' height='11' alt=''>");
        // Charakterwechsel möglich machen
        addnav("Neue Suche");
        addnav("Anderen Charakter wählen","aerisspecial.php?op=search");
    break;
    // Nachricht versenden
    case 'send':
        $int_userid = (int)$_GET['id'];
        $str_title = substr(str_replace('`n','',closetags(trim(encode_specialchars(strip_tags($_POST['gifttitle']))),'`i`c`b')),0,$int_titlemaxlen);
        $str_text = substr(closetags(trim(encode_specialchars(strip_tags($_POST['text']))),'`i`c`b'),0,$int_textmaxlen);
        $str_mailtext = "`rMit einem Lächeln übergibt dir Aeris diese ganz besondere Aufmerksamkeit, die ".$session['user']['name']." `rfür dich ausgesucht hat:`n
                         `b`h".$str_title."`b `i`7(".$str_text."`7)`i";
        systemmail($int_userid,"`rBesondere Aufmerksamkeit",$str_mailtext);
        // Zum Schluss noch bestätigen und bezahlen
        output("`rAeris dankt dir und macht sich sogleich auf den Weg, um deine besondere Aufmerksamkeit zu überbringen. Du hast ihr dafür `h".$int_preis." `rGoldstücke gegeben.");
        $session['user']['gold'] -= $int_preis;
        addnav("Weiter");
        addnav("Weitere Aufmerksamkeit verschicken","aerisspecial.php");
    break;
    // Debug
    default:
        output("`b`^Fehler!`b`n
                `n
                `&Huch, was machst du denn hier? Da ist wohl gerade etwas schiefgelaufen. Sende bitte einen Screenshot dieser Seite via Anfrage an das Adminteam. Beschreibe möglichst auch, was du direkt vor dieser Meldung gemacht hast.`n
                `n
                `hfehlende op: ".$_GET['op']." in aerisspecial.php`n");
    break;
}
// Links, die zurück zu anderen Skripten führen
addnav("Zurück");
addnav("B?Zu den Blumen","blumenmaedchen.php");
addnav("Stand verlassen");
addnav("M?Zum Marktplatz","market.php");
addnav("S?Zur Stadt","village.php");
page_footer();
?>

