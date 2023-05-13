
<?php

// Idee und Umsetzung
// Morpheus in 2007 für www.morpheus-lotgd.de.vu 
// Mail to morpheus@magic.ms
// Gewidmet meiner über alles geliebten Blume
// Dank an Magier12, der mich darauf aufmerksam machte, daß ich geschlafen und unvollständig geprogt habe
require_once "common.php";
page_header("Midas Charmshop");
$charm=$session['user']['charm'];
$name=$session['user']['name'];
if ($_GET['op']==""){
    output("`c`bMidas Charmeshop`c`b`n`n");
    output("`3Du betrittst einen Ladenraum, der recht freundlich auf dich wirkt und in dem, außer einer Theke, hinter der ein Troll steht, und einem Regal hinter der Theke, in dem geschlossene Gläser und Flaschen stehen, die teils voll mit einer merkwürdig `^s`\$t`2r`%a`th`gl`Qe`6n`#d`&e`qn `5Flüssigkeit`3, teils aber auch leer sind, nichts zu sehen ist.`n");
    output("`3Erstaunt gehst du zur Theke, wobei du den Troll von oben bis unten musterst und nicht schlecht zu staunen beginnt, denn für gewöhnlich sind Trolle von Natur aus hässlich, dieser jedoch, bei dem es sich offenbar um `6Midas`3 handelt, wirkt ungemein schön.");
    output("`3Als du vor der Theke stehst, noch immer darüber grübelnd, wieso `6Midas `3nur so schön sein kann, spricht er dich an:`n`n");
    output("`7\"Sei willkommen, `^$name`7, in meinem bescheidenen Charmeshop, bei mir kannst du deinen Charm in bare `@Gems `7 umsetzen oder umgekehrt!\"`n`n");
    output("`3Erstaunt blickst du ihn an und dann an ihm vorbei auf die Flaschen, und, als ob er deine Frage geahnt hätte, sagt er zu dir:`n`n");
    output("`7\"Was Du dort im Regal stehen siehst, sind Flaschen, angefüllt mit dem Charme anderer Krieger, die bereits bei mir waren, den ich in dieser Form aufbewahre.");
    output("`7Da jeder Krieger seinen eigene Charme hat, strahlen die Flaschen in diesen vielen, verschiedenen Farben\"`3 erläutert er und sieht dich dann genau an, als könne er in deine Seele blicken.`n");
    output("`7\"Wie ich sehe, `^$name`7, besitzt Du `^$charm Charmepunkte`7, sag, möchtest du mir welchen verkaufen oder Charme erwerben?");
    output("`7Mein Angebot sieht wie folgt aus:`n");
    output("`7Für `%5 Charmepunkte`7, die du kaufen `7willst, musst du `\$2 Edelsteine `7oder `^2000 Gold `7bezahlen, für `%5 Charmepunkte`7, die du mir verkaufst`7, erhältst du `\$1 Gem`7.`n`n");
    output("`3Immer noch staunend über die Schönheit der `^s`\$t`2r`%a`th`gl`Qe`6n`#d`&e`qn `5Flüssigkeit`3 überlegst du, was du tun möchtest.");
    addnav("E?`\$Edelsteine","midcha.php?op=kauf&act=gems");
    addnav("G?`^Gold","midcha.php?op=kauf&act=gold");
    addnav("v?Charmepunkte verkaufen","midcha.php?op=verkauf");
    addnav("Z?Zurück","marktplatz.php");
    addnav("u?Zurück zum Dorf","village.php");
}
if($_GET['op']=="kauf"){
    switch($_GET['act']){
        case "gems":
            $gem=$session['user']['gems'];
                if ($gem<1){
                output("`7Tja, `6$name`7, Du hast leider im Moment keine `\$Edelsteine`7, um Dir Charme kaufen zu können`n");
            }else{
                output("`7Du hast im Moment`$ $gem Edelsteine `7bei dir, wie viel davon möchtest du in Charmepunkte investieren?`n");
                output("<form action='midcha.php?op=kauf2&act=gems' method='POST'><input name='buy' id='buy'><input type='submit' class='button' value='kaufen'></form>",true);
                output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
                addnav("","midcha.php?op=kauf2&act=gems");
            }
            break;
        case "gold":
            $gold=$session['user']['gold'];
                if ($gold<1000){
                output("`7Tja, `6$name`7, Du hast leider im Moment nicht genug `^Gold `7, um Dir Charme kaufen zu können`n");
            }else{
                output("`7Du hast im Moment `^$gold Goldstücke `7bei dir, wie viel davon möchtest du in Charmepunkte investieren?`n");
                output("<form action='midcha.php?op=kauf2&act=gold' method='POST'><input name='buy' id='buy'><input type='submit' class='button' value='kaufen'></form>",true);
                output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
                addnav("","midcha.php?op=kauf2&act=gold");
            }
            break;
    }        
        
    addnav("v?Charme verkaufen","midcha.php?op=verkauf");
    addnav("Z?Zurück","midcha.php");
//    addnav("Zurück zum Haus","village.php");
}
if ($_GET[op]=="kauf2"){
    $buy=$_POST[buy];
    $gem=$session['user']['gems'];
    $gold=$session['user']['gold'];
        if (($_GET['act']==gems && $gem<$buy) || ($_GET['act']=="gold" && $gold<$buy)) {
        output("`6Midas `3lacht schallend und schüttelt den Kopf:`7\"Ich fürchte, du hast nicht genug `^Gold `7oder `\$Edelsteine`7 bei dir, komm besser später noch einmal wieder!\"`n");
        }else{
        if ($buy > 0){ 
        switch($_GET['act']){
            case "gems":
                if ($buy % 2 == 0){
                    $cp=($buy/2)*5;
                    output("`6Midas `3nimmt deine `\$$buy Edelsteine `3, nickt zufrieden und wendet sich dann zum Regal, wo er eine Flasche aussucht, deren Größe der Gemmenge angemessen ist, die er dir dann reicht.`n");
                    output("`3Du setzt die Flasche an, leerst ihren Inhalt und spürst, wie dich eine `QW`2ä`\$r`&m`ge `3durchströmt und du um `%$cp Charmepunkte`3 schöner wirst`n");
                    $session['user']['gems']-=$buy;
                    $session['user']['charm']+=$cp;
                }else{
                    $nbuy=$buy-1;
                    $cp=($nbuy/2)*5;
//                    $session['user']['gems']+=1;
                    output("`6Midas `3grinst und gibt dir `\$einen Edelstein`3 zurück, denn du hast Dich offenbar verzählt, wendet sich dann um, sucht eine passende Flasche, deren Inhalt dem Gegenwert der `\$Edelsteine`3 entspricht. und reicht sie Dir.`n");
                    output("`3Du setzt die Flasche an, leerst ihren Inhalt und spürst, wie dich eine `QW`2ä`\$r`&m`ge `3durchströmt und du um `%$cp Charmepunkte`3 schöner wirst`n");
                    $session['user']['gems']-=$nbuy;
                    $session['user']['charm']+=$cp;
                }
                break;
            case "gold":
                if ($buy % 2000 == 0){
                    $cp=($buy/2000)*5;
                    output("`6Midas `3nimmt deine `^$buy `^Goldstücke `3, nickt zufrieden und wendet sich dann zum Regal, wo er eine Flasche aussucht, deren Größe der Gemmenge angemessen ist, die er dir dann reicht.`n");
                    output("`3Du setzt die Flasche an, leerst ihren Inhalt und spürst, wie dich eine `QW`2ä`\$r`&m`ge `3durchströmt und du um `%$cp Charmepunkte`3 schöner wirst`n");
                    $session['user']['gold']-=$buy;
                    $session['user']['charm']+=$cp;
                }else{
                    $diff=$buy%2000;
                    $nbuy=$buy-$diff;
                    $cp=($nbuy/2000)*5;
//                    $session['user']['gold']+=$diff;
                    output("`6Midas `3grinst und gibt dir `^$diff Goldstücke`3 zurück, denn du hast dich offenbar verzählt, wendet sich dann um, sucht eine passende Flasche, deren Inhalt dem Gegenwert des `^Goldes`3 entspricht und reicht sie dir.`n");
                    output("`3Du setzt die Flasche an, leerst ihren Inhalt und spürst, wie dich eine `QW`2ä`\$r`&m`ge `3durchströmt und du um `%$cp Charmepunkte`3 schöner wirst`n");
                    $session['user']['gold']-=$nbuy;
                    $session['user']['charm']+=$cp;
                }
                break;
            }
            
        }else{ 
                output("`6Midas `3lacht und meint, dass dir bei der Anzahl der Edelsteine oder Goldstücke wohl ein Fehler unterlaufen ist.");
        } 
    }
    addnav("k?Charmepunkte kaufen","midcha.php?op=kauf");
    addnav("v?Charmepunkte verkaufen","midcha.php?op=verkauf");
    addnav("Z?Zurück","midcha.php");
    addnav("u?Zurück zum Ort","village.php");
}
if($_GET['op']=="verkauf"){
    $charm=$session['user']['charm'];
        if ($charm<5){
        output("`7Du hast hast im Moment nicht genug Charm, um mir welchen verkaufen zu können.`n");
    }else{
        output("`7Du hast im Moment `^$charm Charmepunkte`3, wie viele davon willst du mir verkaufen?`n");
        output("<form action='midcha.php?op=verkauf2' method='POST'><input name='sell' id='sell'><input type='submit' class='button' value='verkaufen'></form>",true);
        output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
        addnav("","midcha.php?op=verkauf2");
    }
    addnav("v?Charmepunkte verkaufen","midcha.php?op=verkauf");
    addnav("k?Charmepunkte kaufen","midcha.php?op=kauf");
    addnav("Z?Zurück zum Ort","village.php");
}
if ($_GET[op]=="verkauf2"){
    $sell=$_POST[sell];
    $charm=$session['user']['charm'];
        if ($charm<$sell){ 
        output("`6Midas `3lacht schallend:`7\" Du kannst nicht mehr Charme verkaufen, als du besitzt!\"`n");
    }else{
        if ($sell > 0){ 
            if ($sell % 5 == 0){
                $gem=($sell/5);
                output("`6Midas `3nickt zufrieden, bittet dich hinter die Theke und durch eine Tür in einen Nachbarraum, in dem ein recht bequem wirkender Sessel steht, in dem er dich bittet, Platz zu nehmen, daneben ein kleiner, runder Tisch.");
                output("`3Er stellt sich vor dich, in der Hand eine Kugel, die an einer Kette hängt und seltsam `2l`@e`Tu`#c`&h`%t`Qe`6t`3 und die er vor deinen Augen, langsam und gleichmäßig, hin und her schwenkt, während er dich auffordert, auf sie zu blicken und dich zu konzentrieren.");
                output("`3Du folgst seiner Anweisung und bald werden deine Augenlider so schwer, daß du sie nicht mehr offen halten kannst und kurz einschlummerst.`n");
                output("`3Als du wieder erwachst, steht neben dir auf dem Tisch ein Glas mit `#Wasser `3 und `6Midas `3ist bereits wieder im Laden verschwunden.");
                output("`3Du trinkst das `#Wasser `3und gehst in den Laden, wo `6Midas `3bereits die Flasche mit deinem Charme, den er auf `&mag`7is`&che `3Weise von dir genommen hat, in den Regalen hat verschwinden lassen.");
                output("`3Als er dich sieht, beginnt er zu lächeln und reicht dir ein kleines `gSäckchen`3, in dem sich deine `@Gems `3befinden.`n`n");
                output("`7\"Du hast einen besonders schön strahlenden Charme, `6$name`3, es war mir eine Freude, mit dir Geschäfte zu machen\"`3 sagt er lächelnd, während du deine `@Gems `3in deinem eigenen Beutel verstaust.");
                $session['user']['charm']-=$sell;
                $session['user']['gems']+=$gem;
//                $session['user']['thirsty']+=1;
                }else{
                output("`6Midas `3lacht schallend laut und schüttelt den Kopf:`7\"Lerne erst einmal, Zahlen durch 5 zu teilen, dann versuche es noch einmal!\"`n");
            }
        }else{ 
                output("`6Midas lacht und meint, dass dir bei der Anzahl der Charmepunkte wohl ein Fehler unterlaufen ist.");
        } 
    }
    addnav("v?Charmepunkte verkaufen","midcha.php?op=verkauf");
    addnav("k?Charmepunkte kaufen","midcha.php?op=kauf");
    addnav("Z?Zurück zum Ort","village.php");
}
page_footer();


