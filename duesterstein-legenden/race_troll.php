
<?
// idea of gargamel @ www.rabenthal.de
require_once("common.php");
addcommentary();
checkday();
page_header("Akwark");

function navi() {
addnav("Akwark");
addnav("Versammlungsplatz","race_chat.php");
addnav("R?Troll-Register","race_list.php?page=1");
addnav("G?Pfad des Glücks","race_game.php");
//addnav("D?Dunkler Pfad","race_spell.php");
addnav("Sonstiges");
addnav("Zurück in den Wald","forest.php");
}

if ($_GET[mod]=="test") $session[user][race]=1;

if ($session[user][locate]!=27){
    $session[user][locate]=27;
    redirect("race_troll.php");
}

if ($_GET[op]=="") {
    output("`n`7Du stehst am Beginn eines Trampelpfades, auf dem eindeutig am Boden
    Troll-Spuren zu erkennen sind. Nur Troll-Spuren..`n
    In der Ferne erkennst Du einige Öllampen, die den Pfad dürftig ausleuchten. Hier
    vorn bemerkst Du gerade noch ein fast überwuchertes Schild:`n
    `qDurchgang nur für Trolle!`0");
    //abschluss intro
    addnav("Pfad nehmen","race_troll.php?op=enter");
    addnav("Zurück in den Wald","forest.php");
}
else if ($_GET[op]=="enter"){
    if ( $session[user][race] == 1 ) {
        output("`c`b`&~~~ Das Troll-Lager Akwark ~~~`b`c`n`n");
    }
    output("`7Du erreichst eine düstere Lichtung. Das Licht der Öllampen kommt kaum
    bis hierher.`0`n`n");
    if ( $session[user][race] != 1 ) {
        output("`7Als Du Dich vorsichtig umsiehst, bemerkst Du wieder die vielen
        Troll-Spuren. Erst jetzt riechst Du den Gestank...`n
        Plötzlich wirst Du aus dem Hinterhalt überwältigt. Eine grosse Gruppe Trolle
        rauben Dich komplett aus. Nur zum Spaß schleppen sie Dich durch den dunklen
        Wald und graben Dich dann bis zum Hals ein. `n
        Als sie wieder abziehen, tritt Dir eine Trollin aus versehen auf den Kopf und
        bricht Dir dabei fast das Genick.`n`n
        Du verlierst all Dein Gold und Deine Edelsteine, dazu alle weiteren Waldkämpfe
        und einen Großteil Deiner Gesundheit.`n`n
        Hättest Du den Durchgang bloß nie gefunden!`n`n
        `0");
        $session[user][gold]=0;
        $session[user][gems]=0;
        $session[user][turns]=0;
        $session[user][hitpoints]=1;
        addnews($session[user][name]." `^wurde im Wald aufgefunden, bis zum Hals eingegraben!");
        addnav("weiter","forest.php");
    }
    else {
        output("Als Du Dich etwas umsiehst, erkennst Du mit Mühe, dass verschiedene
        kleine Pfade von dieser Lichtung weiterführen. Dann entdeckst Du auch die mit
        Moos bewachsenen Steinplatten vor jedem Pfad, die Dir durch eine tiefe,
        unsaubere Inschrift das jeweilige Ziel bekannt geben...`0");
        navi();
    }
}
else if ($_GET[op]=="enter2"){
    output("`c`b`&~~~ Das Troll-Lager Akwark ~~~`b`c`n`n");
    output("`7Du kehrst zurück zur schummrigen Lichtung.`n
    Du schaust Dich um und betrachtest wieder die Steinplatten vor den verschiedenen
    Pfaden.`n`0");
    navi();
}

page_footer();
?>


