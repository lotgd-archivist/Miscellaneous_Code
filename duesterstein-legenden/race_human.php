
<?
// idea of gargamel @ www.rabenthal.de
require_once("common.php");
addcommentary();
checkday();
page_header("Waldpark");

function navi() {
addnav("Waldpark");
addnav("Versammlungsplatz","race_chat.php");
addnav("R?Menschen-Register","race_list.php?page=1");
addnav("G?Weg des Glücks","race_game.php");
addnav("Sonstiges");
addnav("Zurück in den Wald","forest.php");
}

if ($_GET[mod]=="test") $session[user][race]=3;

if ($session[user][locate]!=30){
    $session[user][locate]=30;
    redirect("race_human.php");
}

if ($_GET[op]=="") {
    output("`n`7Du stehst vor einem gewaltigen Tor, das hier ganz offensichtlich
    den Durchgang in einen versteckten Teil des Waldes darstellt. `n
    An dem Tor ist in grossen, bronzenen Buchstaben angeschlagen:`n
    `Q- Waldpark zu Rabenthal - `n
    Zutritt nur für Menschen!`0");
    //abschluss intro
    addnav("Waldpark betreten","race_human.php?op=enter");
    addnav("Zurück in den Wald","forest.php");
}
else if ($_GET[op]=="enter"){
    if ( $session[user][race] == 3 ) {
        output("`c`b`&~~~ Waldpark zu Rabenthal ~~~`b`c`n`n");
    }
    output("`7Du betrittst den Park und stehst in einer hübschen Gartenanlage. Bunte,
    fremde Blumen scheinen hier alles zu überfluten.`0`n`n");
    if ( $session[user][race] != 3 ) {
        output("`7Als Du Dich umschaust, siehst Du wirklich nur Menschen. Wie aus dem
        nichts kommen einige auf Dich zu. Sie tragen Uniformen und belehren Dich
        unfreundlich über die Parkordnung.`n
        Dann ergreifen sie Dich und schleifen Dich in Richtung Ausgang. Unglücklicherweise
        machen sie kurz an der Wachstation halt, die Du vorhin übersehen haben musst.
        Dort rauben sie Dich aus und stecken Dich mit Gewalt in einen Bambuskäfig. Kurz
        darauf wird der Käfig mit Dir irgendwo in der Nähe an einem Baum hochgezogen.`n`n
        Du verlierst all Dein Gold und Deine Edelsteine, dazu alle weiteren Waldkämpfe
        und einen Großteil Deiner Gesundheit.`n`n
        Du hättest den Waldpark nicht betreten sollen!`n`n
        `0");
        $session[user][gold]=0;
        $session[user][gems]=0;
        $session[user][turns]=0;
        $session[user][hitpoints]=1;
        addnews($session[user][name]." `^wurde eingesperrt in einem Bambuskäfig im Wald gefunden!");
        addnav("weiter","forest.php");
    }
    else {
        output("Als Du Dich etwas umsiehst, bemerkst Du mehrere kleine Wege, die sich
        scheinbar quer durch den Park ziehen. Du bist froh, dass hier auch eine Hinweistafel
        aufgestellt wurde, so kannst Du Dich orientieren...`0");
        navi();
    }
}
else if ($_GET[op]=="enter2"){
    output("`c`b`&~~~ Waldpark zu Rabenthal ~~~`b`c`n`n");
    output("`7Du kehrst zurück zu der wunderschönen Gartenanlage am Parkeingang und
    studierst die Hinweistafel`n`0");
    navi();
}

page_footer();
?>


