
<?php// The vendor Aeki sells furniture for houses and buys items found at beaten monsters in the forest.// items.class for furniture prototypes must be 'Möbel.Prot'.// items.class for bought furniture is set to 'Möbel' automatically.// Use itemeditor.php in admin grotto to generate furniture prototyps and items.// More classes will be added soon!//// Requires 'items' table first introduced with my houses mod and a few other modifications for// inventory management and drop items.// No installation instructions available so far. Sorry!!//// Vendor only appears on a few (game) days in village// This is controlled by weather mod by Talisman//// by anpera (2004) while listening to music by 'The Sweet' ;)require_once "common.php";addcommentary();page_header("Möbelhändler");define ('MAX_MOEBEL', getsetting("maxmoebel",500));if ($_GET[op]=="buy"){ // Wig-Wam Bam    if (!$_GET[id]){        $sorti=($_GET[sorti]?"$_GET[sorti]":"name ASC, id");        output("`CLarkon `_derweil tut, als wäre er mächtig beschäftigt und widmet sich ganz den `&Listen `_über seine `tBestände`_, doch wenn du genau hinsiehst, merkst du, dass er jeden deiner Schritte aufmerksam verfolgt, ");        output(" bereit, sofort zu dir zu kommen, wenn er meint, er könnte dir `tetwas `_verkaufen.`n`n ");        $sql = "SELECT count(id) AS c FROM items WHERE owner=0 AND class='Möbel.Prot'";        $result = db_query($sql);        $row = db_fetch_assoc($result);        $totalitems = $row['c'];        $ipp=25; // Items Per Page to display        if((int)$_GET['page']>0) $page=(int)$_GET['page'];        else $page=1;        $pageoffset = (int)$_GET['page'];        if ($pageoffset>0) $pageoffset--;        $pageoffset*=$ipp;        $from = $pageoffset+1;        $to = min($pageoffset+$ipp,$totalitems);        $limit=" LIMIT $pageoffset,$ipp ";        for ($i=0;$i<$totalitems;$i+=$ipp){            addnav("Seite ".($i/$ipp+1)." (".($i+1)."-".min($i+$ipp,$totalitems).")","moebel.php?op=buy&page=".($i/$ipp+1)."&sorti=$sorti");        }        $sql="SELECT * FROM items WHERE owner=0 AND class='Möbel.Prot' ORDER BY $sorti ASC $limit";        $result=db_query($sql);        $max = db_num_rows($result);        if (db_num_rows($result)){            output("`c`b`&Seite $page`b`c`n");            output("<table border='0' cellpadding='2' cellspacing='2'>",true);            output("<tr class='trhead'><td>`b<a href='moebel.php?op=buy&sorti=name&limit=$_GET[limit]'>Name</a>`b</td><td>`b<a href='moebel.php?op=buy&sorti=".urlencode("gold ASC,gems")."&limit=$_GET[limit]'>Gold</a>`b</td><td>`b<a href='moebel.php?op=buy&sorti=".urlencode("gems ASC,gold")."&limit=$_GET[limit]'>Edelsteine</a>`b</td></tr>",true);            allownav("moebel.php?op=buy&sorti=name&limit=$_GET[limit]");            allownav("moebel.php?op=buy&sorti=".urlencode("gold ASC,gems")."&limit=$_GET[limit]");            allownav("moebel.php?op=buy&sorti=".urlencode("gems ASC,gold")."&limit=$_GET[limit]");            for ($i=0;$i<$max;$i++){                  $row = db_fetch_assoc($result);                  if(($row['name']!="`7Verlobungsring" && $row['name']!="`^Ehering") || ($row['name']==="`7Verlobungsring" && $session['user']['marriedto']>0 && $session['user']['fragen']>=5) || ($row['name']==="`^Ehering" && $session['user']['marriedto']>0 && $session['user']['fragen']>=10)){                    $bgcolor=($i%2==1?"trlight":"trdark");                    output("<tr class='$bgcolor'><td><a href='moebel.php?op=buy&id=$row[id]'>$row[name]</a></td><td align='right'>`^$row[gold]`0 Gold</td><td align='right'>`#$row[gems]`0 Edelsteine</td></tr><tr class='$bgcolor'><td colspan='3'>$row[description]</td></tr>",true);                    allownav("moebel.php?op=buy&id=$row[id]");                }            }            output("</table>",true);                        } else {            output("`_Da `CLarkon `_heute schon ein gutes Geschäft gemacht hat, will er sich leider nicht von seinen verbliebenen Sachen trennen. Enttäuscht schlenderst du zurück zum Dorfplatz.");        }    }else{ // Alexander Graham Bell (what? no, he's not the author of this part. It's the name of a song by The Sweet)        $resultm=db_query("SELECT COUNT(*) AS anzahl FROM `items` where owner=".$session['user']['acctid']." AND value1=".$session['user']['house']);        $erg=db_fetch_assoc($resultm);        if($erg['anzahl']>MAX_MOEBEL){            output("`n<BIG>`QDu hast bereits mehr als `q".MAX_MOEBEL."`Q Gegenstände im Haus (`q".$erg['anzahl']."`Q). Mehr kannst du nicht einlagern! Du musst erst einige Dinge verkaufen, bevor du neue einlagern kannst.</BIG>",true);        }else{            $sql="SELECT * FROM items WHERE id=$_GET[id]";            $result=db_query($sql);              $row = db_fetch_assoc($result);            if ($session[user][gems]<$row[gems] || $session[user][gold]<$row[gold]){                output("`_Hoppla! Das kannst du dir nicht leisten. Der Händler schüttelt nur traurig den Kopf und verstaut $row[name] wieder in seinem Wagen.");                addnav("Etwas Anderes kaufen","moebel.php?op=buy");            }else if ($row['class']=="Möbel.Prot" && $session[user][housekey]<=0 ){                output("`_$row[name]`_ gefällt dir wirklich gut, aber da du kein eigenes Haus besitzt, kannst du mit Möbeln auch nichts anfangen.");                addnav("Etwas Anderes kaufen","moebel.php?op=buy");            }else if (db_num_rows(db_query("SELECT id FROM items WHERE name='$row[name]' AND owner=".$session[user][acctid]." AND class='Möbel'"))>0){                output("`_Du hast $row[name]`_ schon. Du überlegst, ob sich eine Neuanschaffung wirklich lohnt. Allerdings müsstest du dazu auch erst den alten Krempel verkaufen.");                addnav("Etwas anderes kaufen","moebel.php?op=buy");            }else{                output("`CLarkon `_reibt sich die Hände und übergibt dir $row[name]`_, während du ".($row[gold]?"`^$row[gold] `_Gold":"")." `_und ".($row[gems]?"`#$row[gems]`_ Edelsteine":"")." `_abzählst. ");                if ($row['class']=="Möbel.Prot") output(" Er ist dir noch kurz beim Transport behilflich, bevor er sich seinem nächsten Kunden zuwendet.");                addnav("Mehr kaufen","moebel.php?op=buy");                $sql="UPDATE items SET owner=".$session[user][acctid]." WHERE id=$_GET[id]";                // insert SQL for special classes here to reset their values                if ($row['class']=="Möbel.Prot") $sql="INSERT INTO items(name,class,owner,value1,gold,gems,description) VALUES ('$row[name]','Möbel',".$session[user][acctid].",".$session[user][house].",1,".(round($row[gems]/2)).",'$row[description]')";                $session[user][gold]-=$row[gold];                $session[user][gems]-=$row[gems];                db_query($sql);            }        }    }    addnav("Zurück","moebel.php");    addnav("Zurück zum Marktplatz","marktplatz.php");    addnav("Zurück zum Dorf","village.php");}else{ // Teenage Rampage    checkday();    output("`_ Endlich bist du da. Beim `TMöbelhändler `CLarkon`_, bei dem du schon lange einmal vorbei schauen wolltest, um dein `SHaus `_endlich fertig einzurichten.`n ");    output(" `_Schon am `tEingang `_kannst du sehen, dass der `THändler `_jede Menge `ßMöbel `_im Angebot hat, kaum findest du den Weg ins Innere, ");    output(" doch da tun sich vor dir schmale Gassen auf, und du kannst erkennen, dass `CLarkon `_sogar eine Art System im Aufstellen seiner `tWaren `_hat.`nNeugierig gehst du die engen Gänge entlang und schaust dir in Ruhe die einzelnen `tStücke `_an, bevor du dich entscheidest, was du dir heute für dein `SHaus `_kaufen willst `n`n`n");    addnav("Waren durchstöbern","moebel.php?op=buy");    addnav("Inventar anzeigen","prefs.php?op=inventory&class=Moebel&back=moebel.php");    addnav("Zurück zum Marktplatz","marktplatz.php");    addnav("Zurück zum Dorf","village.php");    viewcommentary("moebel","Unterhalten",25,"sagt",1,1);}page_footer();

