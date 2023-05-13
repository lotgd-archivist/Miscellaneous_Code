
<?php
// Idee und Umsetzung
// Morpheus aka Apollon 
// 2006 für Morpheus.Lotgd(LoGD 0.9.7 +jt ext (GER) 3)
// Mail to Morpheus@magic.ms or Apollon@magic.ms
// gewidmet meiner über alles geliebten Blume
require_once "common.php";
addcommentary();
page_header("Der Glockenturm");
$session['user']['standort']="Glockenturm";

if($_GET['op']==""){
    output("`7`b`cDer Glockenturm`c`b");
    output("`3`n`nDu gehst durch eine schmale Tür in einen Raum, der im Erdgeschoss des Glockenturmes liegt und blickst nach oben.");
    $sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Beute' AND name='Phiole'";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        output("`3Die Treppe ist sehr steil und der Aufstieg bestimmt anstrengend, aber es gibt außer dem Balkon im obersten Geschoss, direkt unter dem Dach, auch noch einen Balkon auf halber Höhe.");
        output("`3Bis dahin auf zu steigen, wäre nicht ganz so anstrengend.");
        output("`3Du überlegst, was du tun sollst.");
        addnav("Zum Balkon in der Mitte","klosterturm.php?op=mitte");
        addnav("Zum Balkon ganz oben","klosterturm.php?op=oben");
        addnav("Zurück zum Klosterhof","kloster.php");
    }else{
        output("`3Die Treppe ist sehr steil und der Aufstieg bestimmt anstrengend, aber es gibt außer dem Balkon im obersten Geschoss, direkt unter dem Dach, auch noch einen Balkon auf halber Höhe.");
        if ($session['user']['level']==15){
            output("`3Als du nach vorne blickst, fällt dir eine Tür auf, die du zuvor noch nicht bemerkt hast.");
        }else{
            output("`3Bis dahin auf zu steigen, wäre nicht ganz so anstrengend.");
        }
        output("`3Du überlegst, was du tun sollst.");
        addnav("Zum Balkon in der Mitte","klosterturm.php?op=mitte");
        addnav("Zum Balkon ganz oben","klosterturm.php?op=oben");
        if ($session['user']['level']==15){
            addnav("Geheimnisvolle Tür","klosterturm.php?op=tuer");
        }
        addnav("Zurück zum Klosterhof","kloster.php");
    }
}
if($_GET['op']=="raum"){
    output("`3Wieder stehst du im Raum, in dem die Treppe nach oben führt und überlegst, was du tun sollst.");
    addnav("Zum Balkon in der Mitte","klosterturm.php?op=mitte");
    addnav("Zum Balkon ganz oben","klosterturm.php?op=oben");
/*
    if ($session['user']['level']==15){
        addnav("Geheimnisvolle Tür","klosterturm.php?op=tuer");
    }
*/
    addnav("Zurück zum Klosterhof","kloster.php");
}
if($_GET['op']=="mitte"){
    output("`3Du beginnst mit dem Aufstieg zum mittleren Balkon, der ziemlich antrengend ist, sich aber schon lohnt, da du eine recht gute Sicht ins Tal hast.");
    output("`3Wie muß die erst von ganz oben sein?!");
    addnav("Zum Balkon ganz oben","klosterturm.php?op=oben1");
    addnav("Wieder nach unten","klosterturm.php?op=raum");
    addnav("Direkt zum Klosterhof","kloster.php");
}
if($_GET['op']=="oben"){
    output("`3Du beginnst mit dem Aufstieg zum obersten Balkon, der antrengend ist, sich aber absolut lohnt, da du eine phantastische Sicht ins Tal und noch weiter hast.");
    output("`3In deiner Nähe stehen noch andere Gäste und unterhalten sich:`n`n");
    viewcommentary("klosterturm","`3Hinzufügen:`0",25,"staunt",1,1);
    addnav("Zum Balkon in der Mitte","klosterturm.php?op=mitte1");
    addnav("Wieder nach unten","klosterturm.php?op=raum");
    addnav("Direkt zum Klosterhof","kloster.php");
}
if($_GET['op']=="mitte1"){
    output("`3Du beginnst mit dem Abstieg zum mittleren Balkon, weil du neugierig bist, wie die Aussicht wohl von dort sein mag.");
    output("`3Dort angekommen stellst du fest, dass die Aussicht von Oben doch wirklich besser war.");
    addnav("Zum Balkon ganz oben","klosterturm.php?op=oben");
    addnav("Wieder nach unten","klosterturm.php?op=raum");
    addnav("Direkt zum Klosterhof","kloster.php");
}
if($_GET['op']=="oben1"){
    output("`3Du beginnst mit dem weiteren Aufstieg zum obersten Balkon, der genau so antrengend ist, sich aber absolut lohnt, da du von hier eine phantastische Sicht ins Tal hast, viel schöner als aus der Mitte.");
    output("`3In deiner Nähe stehen noch andere Gäste und unterhalten sich:");
    viewcommentary("klosterturm","`3Hinzufügen:`0",25,"staunt",1,1);
    addnav("Zum Balkon in der Mitte","klosterturm.php?op=mitte1");
    addnav("Wieder nach unten","klosterturm.php?op=raum");
    addnav("Direkt zum Klosterhof","kloster.php");
}
if($_GET['op']=="tuer"){
    output("`3Du öffnest die geheimnisvolle Tür und stellst fest, dass sie in den Keller des Turmes führt.");
    output("`3Mutig nimmst du dir eine Fackel und steigst hinab in den Keller, wo du in einen Gang kommst, der dich ein Stück in den Berg führt.");
    output("`3Plötzlich tut sich eine kleine Halle vor dir auf, in die Seitenwände sind sieben Pfeiler gemeißelt worden, die, wie im Vorraum, nach oben laufen und sich dort zu einem Stern treffen.");
    output("`3Obwohl es keine Fenster oder sonstige Lichtquellen gibt, ist der Raum hell erleuchtet und in der Mitte kannst du einen Altar sehen, auf dessen Vorderseite du Folgendes lesen kannst:`n`n");
    output("`6Krieger, der du den `@Drachen`6 suchen gehst, empfange den Segen der Götter in der kleinen Phiole auf dem Altar.");
    output("`6Möge dich der Segen sicher gegen den `@GRÜNEN DRACHEN `6führen und dir Heil bringen.`n`n");
    output("`3Du gehst zum Altar, nimmst die Phiole, öffnest sie, trinkst und spürst, wie dich eine geheimnisvolle Kraft durchdringt.");
        switch(e_rand(1,4)){
            case 1:
            output("`6 Deine Lebenspunkte haben sich erhöht!");
            $session[user][hitpoints]*=1.2;
            break;
            case 2:
            output("`6 Du hast 2 Anwendungen in mysthischen Kräften erhalten!");
            $session[user][magicuses]+=2;
            break;
            case 3:
            output("`6 Du hast 2 Anwendungen in dunklen Kräften erhalten!");
            $session[user][darkartuses]+=2;
            break;
            case 4:
            output("`6 Du hast 2 Anwendungen in Diebesfähigkeiten erhalten!");
            $session[user][thieveryuses]+=2;
            case 5:
            output("`6 Du hast 2 Anwendungen in Feuermagie erhalten!");
            $session[user][fireuses]+=2;
            case 6:
            output("`6 Du hast 2 Anwendungen in Weisser Magie erhalten!");
            $session[user][wmagieuses]+=2;
            break;
        }
    addnav("Weiter","klosterturm.php?op=tuer1");
}
if($_GET['op']=="tuer1"){
    $sql = "INSERT INTO items (name,owner,class,gold,description) VALUES ('Phiole',".$session[user][acctid].",'Beute',0,'Wertloser Plunder')";
    db_query($sql);
    output("`3Voll Ehrfurcht fällst du auf die Knie, dankst den Göttern für ihre Güte und steckst die Phiole noch ein, bevor du dich auf den Weg zurück nach oben machst.");
    output("`3Als du aus der Tür trittst, schließt sich hinter dir die Wand, als wäre dort niemals eine Tür gewesen.");
    addnav("Zum Balkon in der Mitte","klosterturm.php?op=mitte");
    addnav("Zum Balkon ganz oben","klosterturm.php?op=oben");
    addnav("Zurück zum Klosterhof","kloster.php");
}
page_footer();
?>

