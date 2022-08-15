
<?php
// Bregomil
// By Maris (Maraxxus@gmx.de)

if (!isset($session)) exit();

$dart_cost=3; // Kosten für die Scheibe
$sack_cost=5; // Kosten für den Sandsack
$dummy_cost=7; // Kosten für die Puppe
$teddy_cost=5; // Kosten für den Teddy
$cushion_cost=7; // Kosten für das Kissen

if ($_GET['op']==""){
        $session['user']['specialinc']="bregomil.php";
        output("`nDu gelangst an eine kleine Lichtung, die du irgendwo schon einmal gesehen hast. Doch diesmal erkennst du ein kleines Häuschen, nah am Waldrand. Die Hütte ist aus Stein und Holz gearbeitet und sieht sehr einladend aus. Die Tür steht weit offen und ohne zu überlegen gehst du näher und trittst ein.`n`5\"Willkommen, Freund!\"`0 ertönt es aus einer Ecke, in der ein kleiner,dünner Mann sitzt, der gerade an einem Stück Holz schnitzt.`n`5\"Ich bin Bregomil Auerhahn, Künstler und Handwerker. Ich habe mich auf die Fertigung von Übungsgeräten und Spielzeug spezialisiert. Seid nicht abgeschreckt von meinen Preisen, ich garantiere Euch höchste Qualität! Und dazu werde ich die Geräte mit dem Antlitz Eures schlimmsten Feindes versehen, damit das Training gleich doppelt so viel Spass macht! Na, was sagt Ihr ?\"`0");
        addnav("Übungsgerät kaufen?");
        addnav("Zielscheibe für ".$dart_cost." Edelsteine","forest.php?op=weiter&was=scheibe");
        addnav("Sandsack für ".$sack_cost." Edelsteine","forest.php?op=weiter&was=sack");
        addnav("Strohpuppe für ".$dummy_cost." Edelsteine","forest.php?op=weiter&was=puppe");
        addnav("Etwas Niedliches kaufen?");
        addnav("Teddybär für ".$teddy_cost." Edelsteine","forest.php?op=weiter&was=teddy&type=cute");
        addnav("Kuschelkissen für ".$cushion_cost." Edelsteine","forest.php?op=weiter&was=kissen&type=cute");
        addnav('Zurück');
        addnav("Danke, heute nicht!","forest.php?op=weg");
        $session['user']['specialinc'] = "bregomil.php";

}
else if ($_GET['op']=="weiter")
{
        $session['user']['specialinc']="bregomil.php";
        $was=$_GET['was'];
        if ($was=="scheibe") { $cost=$dart_cost; }
        if ($was=="sack") { $cost=$sack_cost; }
        if ($was=="puppe") { $cost=$dummy_cost; }
        if ($was=="teddy") { $cost=$teddy_cost; }
        if ($was=="kissen") { $cost=$cushion_cost; }
        $type = (isset($_GET['type']) ? $_GET['type'] : '');

        if ( $session['user']['gems'] < $cost )
        {
                output("`nDu hast leider nicht genug Edelsteine, um dir das leisten zu können. Also lächelst du peinlich berührt und machst dich davon.`0");
        }
        else
        {
                if ($_GET['who']=="")
                {
                        output("\"`#Mhm, mhm. Wem soll Euer neues ".($type == 'cute' ? "Spielzeug" : "Trainingsgerät")." denn ähnlich sehen?`&\"`n`n");
                        if ($_GET['subop']!="search")
                        {
                                output("<form action='forest.php?op=weiter&was=".$was."&subop=search&type=".$type."' method='POST'>
                                        Name: <input name='name' class='input'>`n
                                        `n
                                        Charaktertyp: <select name='char'><option value='hc' selected>Hauptchar</option>
                                                                          <option value='disc'>Knappe</option>
                                                                          <option value='xchar'>X-Char</option>
                                                      </select>`n
                                        `n
                                        <input type='submit' class='button' value='Suchen'>
                                        </form>",true);
                                addnav("","forest.php?op=weiter&was=".$was."&subop=search&type=".$type);
                        }
                        else
                        {
                                addnav("Neue Suche","forest.php?op=weiter&was=".$was."&type=".$type);
                                addnav("Kann ich das andere nochmal sehen?","forest.php");
                                $search = str_create_search_string($_POST['name']);
                                if($_POST['char'] == 'disc') {           // Nach Knappen suchen
                                    $sql = "SELECT d.name,ab.acctid
                                                   FROM disciples d LEFT JOIN account_bios ab ON ab.acctid = d.master
                                                        WHERE d.name LIKE '".$search."' AND d.state > 0 AND ab.has_addchars LIKE '%s:15:\"has_disciplebio\";i:1;%'
                                                              AND ab.addchars_details LIKE '%s:14:\"discbio_active\";s:1:\"1\";%'
                                                              ORDER BY d.level DESC";

                                } elseif($_POST['char'] == 'xchar') {    // Nach X-Chars suchen
                                    $sql = 'SELECT t.name,t.acctid,t.rp_infos FROM
                                            (
                                                SELECT ab.acctid,ab.mount_category AS name,ab.mount_rp_infos AS rp_infos FROM account_bios ab WHERE ab.mount_category LIKE "'.$search.'" AND ab.addchars_details LIKE \'%mountbio_active";s:1:"1%\' AND ab.has_addchars LIKE \'%s:12:\"has_mountbio\";i:1;%\'
                                                UNION ALL
                                                SELECT ab.acctid, ab.xchar_category AS name,ab.xchar_rp_infos AS rp_infos FROM account_bios ab WHERE ab.xchar_category LIKE "'.$search.'" AND ab.addchars_details LIKE \'%xcharbio_active";s:1:"1%\' AND ab.has_addchars LIKE \'%s:12:\"has_xcharbio\";i:1;%\'
                                            )
                                            t
                                            ORDER BY t.acctid';
                                } else {                                 // Nach Hauptchars suchen
                                    $sql = "SELECT name,acctid FROM accounts WHERE (locked=0 AND name LIKE '".$search."') ORDER BY level DESC";
                                }
                                //output($sql);
                                $result = db_query($sql) or die(db_error(LINK));
                                $max = db_num_rows($result);
                                if ($max > 100) {
                                        output("`n`n\"`#Auf Eure Beschreibung passen leider viel zu viele Bürger. Seid bitte etwas präziser.`&`n");
                                        $max = 100;
                                }
                                output("<table border=0 cellpadding=0><tr><td>Name</td></tr>",true);
                                for ($i=0;$i<$max;$i++){
                                        $row = db_fetch_assoc($result);
                                        $char = $_POST['char'];
                                        if($_POST['char'] == 'xchar') {  // falls X-Char gesucht wird, muss zw. 1. und 2. X-Char unterschieden werden
                                            $row['rp_infos'] = mb_unserialize($row['rp_infos']);
                                            if(isset($row['rp_infos']['mount_rp_race'])) {
                                                $char = 'mount';
                                            }                            // end Unterscheidung 1. und 2. X-Char
                                        }
                                        output("<tr><td><a href='forest.php?op=weiter&was=".$was."&char=".$char."&type=".$type."&who=".$row['acctid']."'>".$row['name']."</a></td></tr>",true);
                                        addnav("","forest.php?op=weiter&was=".$was."&char=".$char."&type=".$type."&who=".$row['acctid']);
                                }
                                output("</table>",true);
                        }
                }
                else
                {
                        if($_GET['char'] == 'disc') {           // Nach Knappen suchen
                            $sql = "SELECT name,master AS acctid FROM disciples WHERE master = ".$_GET['who'];
                        } elseif($_GET['char'] == 'mount') {    // Nach 1. X-Char suchen
                            $sql = "SELECT mount_category AS name,acctid FROM account_bios WHERE acctid = ".$_GET['who'];
                        } elseif($_GET['char'] == 'xchar') {    // Nach 2. X-Char suchen
                            $sql = "SELECT xchar_category AS name,acctid FROM account_bios WHERE acctid = ".$_GET['who'];
                        } else {                                // Nach Hauptchars suchen
                            $sql = 'SELECT name,acctid FROM accounts WHERE acctid = '.$_GET['who'];
                        }
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        output ("`5Soso... Euer ".($type == 'cute' ? "Spielzeug" : "Übungsgerät")." soll also so aussehen wie ".($row['name'])."`5?`0");
                        addnav("Erstellen");
                        addnav("Ja, weiter","forest.php?op=finish&was=".$was."&char=".$_GET['char']."&who=".$row['acctid']);
                        addnav("Nein, nochmal","forest.php?op=weiter&was=".$was."&type=".$type);
                        addnav("Abbruch");
                        addnav("Ich will etwas ganz anderes","forest.php");
                }
        }
}
else if ($_GET['op']=="finish"){
        if($_GET['char'] == 'mount') {          // Nach 1. X-Char suchen
            $sql = "SELECT mount_category AS name FROM account_bios WHERE acctid = ".$_GET['who'];
        } elseif($_GET['char'] == 'disc') {     // Nach Knappen suchen
            $sql = "SELECT name FROM disciples WHERE master = ".$_GET['who'];
        } elseif($_GET['char'] == 'xchar') {    // Nach 2. X-Char suchen
            $sql = "SELECT xchar_category AS name FROM account_bios WHERE acctid = ".$_GET['who'];
        } else {                                 // Nach Hauptchars suchen
            $sql = 'SELECT name FROM accounts WHERE acctid = '.$_GET['who'];
        }
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);

        if ($_GET['was']=="scheibe") {
                $was="deine Zielscheibe";
                $tpl_id = 'zielsch';
                $item['tpl_gems']=$dart_cost;
                $item['tpl_name']="Zielscheibe";
                $item['tpl_description']="Zum Üben. Auf der Scheibe befindet sich ein Bild von ".($row['name'])."";
        }
        if ($_GET['was']=="sack") {
                $was="deinen Sandsack";
                $tpl_id='sandsack';
                $item['tpl_gems']=$sack_cost;
                $item['tpl_name']="Sandsack";
                $item['tpl_description']="Zum Üben. Auf den Sack wurde ein Bild von ".($row['name'])." aufgenäht.";
        }
        if ($_GET['was']=="puppe") {
                $was="deine Strohpuppe";
                $tpl_id='strpuppe';
                $item['tpl_gems']=$dummy_cost;
                $item['tpl_name']="Strohpuppe";
                $item['tpl_description']="Zum Üben. Die Puppe hat täuschende Ähnlichkeit mit ".($row['name'])."";
        }
        if ($_GET['was']=="teddy") {
                $was="deinen Teddybär";
                $tpl_id='teddybear';
                $item['tpl_gems']=$teddy_cost;
                $item['tpl_name']="`ZT`fe`xd`zdy`fb`xä`zr";
                $item['tpl_description']="`zZum Kuscheln und Lieb haben. Das Plüschtier hat täuschende Ähnlichkeit mit ".($row['name'])."";
        }
        if ($_GET['was']=="kissen") {
                $was="dein Kuschelkissen";
                $tpl_id='cutecshion';
                $item['tpl_gems']=$cushion_cost;
                $item['tpl_name']="`9K`hu`Is`°c`&hel`9k`his`Is`°en";
                $item['tpl_description']="`IZum Kuscheln und Lieb haben. Auf das Kissen wurde ein Bild von ".($row['name'])." `Iaufgenäht.";
        }

        $session['user']['gems']-=$item['tpl_gems'];
        $name=$session['user']['acctid'];

        output ("Der Mann streicht deine Edelsteine ein und macht sich an die Arbeit. Nach einiger Zeit kommt er wieder und gibt dir ".($was).". Sieht tatsächlich so aus wie ".($row['name'])."!`n`nDu klemmst dir das Meisterwerk unter den Arm und gehst deines Weges.`nVergiss nicht, das gute Stück in deinem Haus einzulagern, damit es nicht verloren geht!");
        $session['user']['specialinc']="";

        item_add($name, $tpl_id, $item);

}
else if ($_GET['op']=="weg")
{
        output("`n`QDu beschließt, so etwas nicht nötig zu haben, und verlässt die Hütte.`0");
        $session['user']['specialinc']="";
}
?>

