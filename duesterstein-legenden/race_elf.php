
<?
// idea of gargamel @ www.rabenthal.de
require_once("common.php");
addcommentary();
checkday();
page_header("Quintarra");

function navi() {
addnav("Quintarra");
addnav("Versammlungsplatz","race_chat.php");
addnav("R?Elfen-Register","race_list.php?page=1");
addnav("G?Ast des Glücks","race_game.php");
addnav("Sonstiges");
addnav("Zurück in den Wald","forest.php");
}

if ($_GET[mod]=="test") $session[user][race]=2;

if ($session[user][locate]!=28){
    $session[user][locate]=28;
    redirect("race_elf.php");
}

if ($_GET[op]=="") {
    output("`n`7Du stehst vor einem mächtigen Baum, der die anderen Bäume der Waldgruppe
    überragt. Die Baumkrone sieht aus, als trüge sie das Dach des Himmels... Dort oben
    nimmst Du Leben war.`n
    Fast schon verwachsen kannst Du im Stamm noch einen eingeritzten Hinweis erkennen:`n
    `qDieser Ort gehört nur den Elfen!`0");
    //abschluss intro
    addnav("In die Baumkrone","race_elf.php?op=enter");
    addnav("Zurück in den Wald","forest.php");
}
else if ($_GET[op]=="enter"){
    if ( $session[user][race] == 2 ) {
        output("`c`b`&~~~ Quintarra - Hort der Elfen ~~~`b`c`n`n");
    }
    output("`7Du erreichst die mächtige Baumkrone. Die Blätter dämpfen das Licht und
    tauchen alles in eine märchenhafte Atmosphäre.`0`n`n");
    if ( $session[user][race] != 2 ) {
        output("`7Als Du Dich umschaust, bemerkst Du auch die vielen Elfen, die
        hier geschäftig umherflattern. Sie bemerken Dich auch!`n
        Sofort stürzen sie sich auf Dich, um ihren Hort vor Dir zu schützen. Du bist
        in ihr Reich eingedrungen. Sie pusten Dir Blütenstaub ins Gesicht, der Dich
        sofort bewusstlos werden lässt. Nur durch einen Schleier bekommst Du mit, wie
        sie Dir alles nehmen, was Du hast. Ein Schubs und Du fällst aus der Baumkrone,
        zurück in den Wald.`n`n
        Du verlierst all Dein Gold und Deine Edelsteine, dazu alle weiteren Waldkämpfe
        und einen Großteil Deiner Gesundheit.`n`n
        Du hättest die Baumkrone nicht betreten sollen!`n`n
        `0");
        $session[user][gold]=0;
        $session[user][gems]=0;
        $session[user][turns]=0;
        $session[user][hitpoints]=1;
        addnews($session[user][name]." `^wurde bewusstlos im Wald gefunden!");
        addnav("weiter","forest.php");
    }
    else {
        output("Als Du Dich etwas umsiehst, erblickst Du die Elfen, die hier geschäftig
        umherflattern. Einige halten sich auf dem großen Versammlungsplatz vor Dir auf,
        andere benutzen einen der Astwege weiter ins Innere der Baumkrone.`n
        In magischer Schrift erkennst du auf Blättern, wohin die Wege führen. Neugierig
        liest Du...`0");
        navi();
    }
}
else if ($_GET[op]=="enter2"){
    output("`c`b`&~~~ Quintarra - Hort der Elfen ~~~`b`c`n`n");
    output("`7Du kehrst ins Zentrum der mächtigen Baumkrone zurück.`n
    In magischer Schrift erkennst du auf Blättern, wohin die Wege führen. Neugierig
    liest Du...`n`0");
    navi();
}

page_footer();
?>


