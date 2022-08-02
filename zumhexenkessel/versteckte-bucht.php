<?php

# Idee, Texte, Bilder und Umsetzung by Shaddar und Filyina
# (C) Copyright by Soul of the black Dragon
# http://www.soul-of-the-black-dragon.de

require_once "common.php";
page_header("Versteckte Bucht");
//setPageFirstTitle("`wV`qe`Ur`6s`Mt`^e`Yc`xk`Xt`Oe `ýB`Îu`9c`úh`!t`0");

if ($_GET['op']=="") {
  //  showimage("Versteckte-Bucht","png");
    output("`UNic`6ht w`Meit `^von `Ydem `xkle`Xine`On Do`ýrf e`Întfernt, bist Du bei Deinen Erkundungen auf eine kleine, versteckt liegende Bucht gestoßen, die offensichtlich noch Niemand vor Dir entdeckt hat, denn alles hier wirkt noch gänzlich unberührt.");
    output("Die Natur selbst lässt es sich nicht nehmen, sich hier in voller Pracht zu zeigen. Verschiedene Palmarten säumen den Rand des Dschungels und bieten ihre Früchte an.");
    output("Das türkisfarbene Meer schickt seine Wellen auf den schneeweißen Sandstrand, der von zwei Landzungen eingefasst wird.");
    output("Gebannt siehst Du Dich näher um und saugst die Schönhei`ýt un`Od Ru`Xhe d`xies`Yes O`^rte`Ms in `6Dir `Uauf.`0`n`n");
    addcommentary();
    viewcommentary("Versteckte Bucht");

    addnav("Aktionen");
    addnav("Treibgut suchen","versteckte-bucht.php?op=treibgut");

} else if ($_GET['op']=="treibgut") {
  //  showimage("Bucht");
    output("`UGra`6de b`Mist `^du i`Yn de`xr kl`Xein`Oen B`ýuch`Ît angekommen und siehst dich dort ein wenig um. Als dein Blick über das Wasser gleitet und dort, nicht weit vom Strand entfernt, ein schwimmendes Objekt entdeckt.");
    output("Es sieht aus als wäre hier vor kurzem ein Schiff untergegangen und eine einsame Planke war das einzige Überbleibsel.");
    output("Als du allerdings genauer hinsiehst erkennst du, dass dieses Stück Holz in der Sonne glitzert. Doch halt. Holz glitzert doch nicht.");
    output("Nun wirst du neugierig und trittst ein paar Schritte ins Wasser hinein, allerdings kannst du die Planke so nicht erreichen.");
    output("Dafür müsstest du einige Meter schwimmen können, doch der kleine Schatz der bestimmt vor deiner Nase `ýsch`Owim`Xmt i`xst e`Ys do`^ch s`Mich`6er w`Uert.`0`n`n");

    addnav("Aktionen");
    addnav("Schwimmen","versteckte-bucht.php?op=schwimmen");

} else if ($_GET['op']=="schwimmen") {
//    showimage("Versteckte-Bucht","png");
    output("`USo s`6chn`Mell `^wir`Yd si`xche`Xr ke`Oin S`ýcha`Îtz mehr an dir vorbei geschwommen kommen, also nimmst du allen Mut zusammen und schwimmst auf die hölzerne Planke zu.");
    output("Es kommt dir viel weiter vor als es vom Strand aus aussah und als du das Treibgut erreicht hast bist du überglücklich, dich an etwas festhalten zu können.");
    output("Mehrmals atmest du tief ein und aus, froh darüber, dass die Strömung dich nicht noch weiter vom Weg abgebracht hat.");
    output("Als du dich wieder bei Kräften fühlst siehst du auf das Brett, an welches du dich die ganze Zeit klammerst und unterdrückst einen Schrei.");
    output("Auf dem Holz liegt ein Krieger, nun ja, zumindest war es sicherlich mal einer, denn viel hat die Zeit nicht von ihm übrig gelassen.");
    output("Außer der Knochen findest du nur seine gut erhaltene Rüstung, eine Waffe und einen großen ledernen Beutel.");
    output("Der arme Wicht wird diese Dinge sicher nicht mehr brauchen und so überlegst du, ob du sie ihm nicht einfach abnehmen solltest, um damit im Dschungel zu überleben.");
    output("Als du wieder zum Strand schaust wird dir allerdings klar, dass du nur eines zurück sc`ýhaf`Ofen `Xkan`xnst `Yohn`^e zu `Mert`6rin`Uken.`0`n`n");
    $dublonen = $session['user']['level'] * 2500;
    $min = $session['user']['level'];
    $max = $session['user']['level'] + 2;

    if ($_GET['act']=="dublonen") {
        $spende = round(e_rand($dublonen *= 0.9,$dublonen *= 1.1));
        if ($spende > getsetting("paidgold",0)) $spende = getsetting("paidgold",0);
        if (getsetting("paidgold",0) <= 0) {
            output(1,"Es sind keine Dublonen mehr verfügbar, die Du erhalten könntest !");
        } else {
 output(0,"Du bettelst erfolgreich um ".$spende." Dublonen.");
            $session['user']['gold'] += $spende;
            $session['user']['bettel_dublonen']++;
            if (getsetting("paidgold",0) - $spende < 1) {
                savesetting("paidgold","0");
            } else {
                savesetting("paidgold",getsetting("paidgold",0) - $spende);
            }
        }
    } else if ($_GET['act']=="item") {
        $sql = "SELECT * FROM items WHERE id=".$_POST['item']." AND owner=0";
        $result = db_query($sql);
        if (db_num_rows($result)==0) {
            output(1,"Du kannst das Item nicht erhalten, da es garnicht existiert !");
        } else {
            $row = db_fetch_assoc($result);
            if ($row['class']=="Waffe") {
                $session['user']['bettel_waffen']++;
            } else {
                $session['user']['bettel_ruestungen']++;
            }
            output(0,"Du hast die ".$row['class']." erfolgreich erhalten.");
            $sql = "UPDATE items SET owner=".$session['user']['acctid']." WHERE id=".$_POST['item']." AND owner=0";
            db_query($sql);
        }
    }

    if ($session['user']['bettel_dublonen'] > 0) {
        output(1,"Du hast vorerst genug Spenden in Anspruch genommen !");
    } else {
        output("<form action='versteckte-bucht.php?op=schwimmen&act=dublonen' method='POST'>",true);
        output("<table border='0' cellspacing='2' cellpadding='0'>",true);
        output("<tr class='trhead'><td colspan='2'>Dublonen finden:</td></tr>",true);
        output("<tr><td>`OCirka zu erhaltene Dublonen:`0</td> <td align='right'> `^ ".$dublonen." Dublonen`0 <img src='images/symbole/dublonen.png' title='Dublonen' alt='' /></td></tr>",true);
        output("<tr><td><input type='submit' value='Mitnehmen' /></td> <td></td></tr>",true);
        output("</table>",true);
        output("</form>`n`n",true);
        addnav("","versteckte-bucht.php?op=schwimmen&act=dublonen");
    }

    if ($session['user']['bettel_waffen'] >= 3) {
        output(1,"Du hast vorerst genug Spenden in Anspruch genommen !");
    } else {
        $sql = "SELECT * FROM items WHERE class='Waffe' AND owner=0 AND value1>=".$min." AND value1<=".$max." ORDER BY rand(".e_rand().") LIMIT 1";
        $result = db_query($sql);
        if (db_num_rows($result)==0) {
            output(1,"Es gibt keine Waffen die Du finden könntest !");
        } else {
            $row = db_fetch_assoc($result);
            output("<form action='versteckte-bucht.php?op=schwimmen&act=item' method='POST'>",true);
            output("<table border='0' cellspacing='2' cellpadding='0'>",true);
            output("<tr class='trhead'><td colspan='2'>Waffe finden:</td></tr>",true);
            output("<tr><td>`OCirka zu erhaltene Waffe:`0</td> <td align='right'>`&Wert: ".$min." - ".$max."`0  <input type='hidden' name='item' value='".$row['id']."' /> <img src='images/symbole/weapon.png' title='Waffe' alt='' /></td></tr>",true);
            output("<tr><td><input type='submit' value='Mitnehmen' /></td> <td></td></tr>",true);
            output("</table>",true);
            output("</form>`n`n",true);
            addnav("","versteckte-bucht.php?op=schwimmen&act=item");
        }
    }

    if ($session['user']['bettel_ruestungen'] >= 3) {
        output(1,"Du hast vorerst genug Spenden in Anspruch genommen !");
    } else {
        $sql = "SELECT * FROM items WHERE class='Rüstung' AND owner=0 AND value1>=".$min." AND value1<=".$max." ORDER BY rand(".e_rand().") LIMIT 1";
        $result = db_query($sql);
        if (db_num_rows($result)==0) {
            output(1,"Du gibt keine Rüstungen die Du finden könntest !");
        } else {
            $row = db_fetch_assoc($result);
            output("<form action='versteckte-bucht.php?op=schwimmen&act=item' method='POST'>",true);
            output("<table border='0' cellspacing='2' cellpadding='0'>",true);
            output("<tr class='trhead'><td colspan='2'>Rüstungen finden:</td></tr>",true);
            output("<tr><td>`OCirka zu erhaltene Rüstung:`0</td> <td align='right'>`&Wert: ".$min." - ".$max."`0 <input type='hidden' name='item' value='".$row['id']."' /> <img src='images/symbole/armor.png' title='Rüstung' alt='' /></td></tr>",true);
            output("<tr><td><input type='submit' value='Mitnehmen' /></td> <td></td></tr>",true);
            output("</table>",true);
            output("</form>`n`n",true);
            addnav("","versteckte-bucht.php?op=schwimmen&act=item");
        }
    }
}

addnav("Sonstiges");
if ($_GET['op']!="") addnav("Zur Bucht","versteckte-bucht.php");
addnav("Zum Piratennest","amrun.php");

page_footer();

?> 