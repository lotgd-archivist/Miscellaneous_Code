
<?
// Dwarf city Thais seen on www.hadriel.ch/logd
// and taken as basis for race-specific enhancements
//
// Modifications by gargamel @ www.rabenthal.de :
// - technical changes to integrate modules working
//   with accompanied enhancements for the other
//   ingame races human, elf and troll.
// - integration of a member list (only own race)
//
require_once("common.php");
checkday();
page_header("Thais");

function navi() {
addnav("Thais");
addnav("Versammlungsplatz","race_chat.php");
//addnav("Alberich-Schrein","thais.php?op=shrine&step=1");
addnav("R?Zwergen-Register","race_list.php?page=1");
addnav("G?Stollen des Glücks","race_game.php");
addnav("Sonstiges");
addnav("Zurück in den Wald","forest.php");
}

if ($_GET[mod]=="test") $session[user][race]=4;

if ($session[user][locate]!=29){
    $session[user][locate]=29;
    redirect("race_dwarf.php");
}

if ($_GET[op]=="") {
    output("`n`7Du stehst vor einem niedrigen Tunnel, der scheinbar tief in einen
    kleinen Berg hineinführt. Seltsame Kristalle an den Wänden verbreiten ein
    `6schummriges Licht`7.`n
    Am Tunneleingang siehst Du, schon etwas unter Flechten verborgen, in den Fels
    gemeisselt: `qZutritt nur für Zwerge!`0");
    //abschluss intro
    addnav("Tunnel betreten","race_dwarf.php?op=enter");
    addnav("Zurück in den Wald","forest.php");
}
else if ($_GET[op]=="enter"){
    if ( $session[user][race] == 4 ) {
        output("`c`b`&~~~ Die unterirdische Zwergenstadt Thais ~~~`b`c`n`n");
    }
    output("`7Du entschließt Dich, dem Tunnel zu folgen und kommst tatsächlich
    nach einiger Zeit in eine sehr geräumige Halle. Von irgendwoher fällt Licht hinein
    und erleuchtet sie `&hell`7.`0`n`n");
    if ( $session[user][race] != 4 ) {
        output("`7Als Du Dich umschaust, siehst Du eine Gruppe Zwerge, die wohl
        gerade etwas zu feiern haben.`n
        Als sie Dich entdecken, ist es schon zu spät. Sie stürzen sich gemeinsam
        auf Dich und rauben Dich komplett aus!`n
        Aber es kommt noch schlimmer: Wie im Rausch schlagen sie auf Dich ein,
        tragen Dich schließlich in den Wald und fesseln Dich nackt an einen Baum.`n`n
        Du verlierst all Dein Gold und Deine Edelsteine, dazu alle weiteren Waldkämpfe
        und einen Großteil Deiner Gesundheit.`n`n
        Du hättest den Tunnel nicht betreten sollen!`n`n
        `0");
        $session[user][gold]=0;
        $session[user][gems]=0;
        $session[user][turns]=0;
        $session[user][hitpoints]=1;
        addnews($session[user][name]." `^wurde gefesselt und nackt an einem Baum gefunden.");
        addnav("weiter","forest.php");
    }
    else {
        output("Als Du Dich etwas umsiehst, fällt Dir auf der einen Seite ein großer
        Versammlungsplatz auf. Auf der gegenüberliegenden Seite führen Stollen scheinbar
        noch weiter ins Innere des Berges...über einigen hängt ein Wegweiser.
        Neugierig liest Du die Schilder...`0");
        navi();
    }
}
else if ($_GET[op]=="enter2"){
    output("`c`b`&~~~ Die unterirdische Zwergenstadt Thais ~~~`b`c`n`n");
    output("`7Du kehrst zurück zur geräumigen Eingangshalle.`n
    Du blickst auf den grossen Versammlungsplatz und betrachtest wieder die Schilder
    über den verschiedenen Stollen.`n`0");
    navi();
}
else if ($_GET[op]=="shrine"){
    if ( $_GET[step]==1 ) {
        output("`c`b`&~~~ Der Alberich - Schrein ~~~`b`c`n`n");
        output("`7Du betrittst eine kleine Kammer, die mit herrlichen und wertvollen
        Stoffen ausgeschlagen ist. In der Mitte steht eine Marmor-Stele, die mit Gold
        eingefasst ist. Einige Kerzen verleihen der Kammer ein würdevolles Licht. `n
        Du steht vor dem sagenhaften Alberich-Schrein.`n`n`0");
        addnav("Beistand erbitten","race_dwarf.php?op=shrine&step=2");
        addnav("Zurück","race_dwarf.php?op=enter2");
    }
    if ( $_GET[step]==2 ) {
        output("`c`b`&~~~ Der Alberich - Schrein ~~~`b`c`n`n");
        output("`7Still kniest Du Dich nieder, um Beistand zu erbitten. Du greifst nach
        einer Pergamentrolle, die am Fusse des Schreins liegt.`n
        Etwas erstaunt liest Du, dass der Beistand beschränkt ist und auch seinen Preis hat.`n
        Aber mit ein bischen Glück erhälst Du hier Gaben, die Dir als Zwerg fehlen.`0");
        addnav("Zurück","race_dwarf.php?op=enter2");
        addnav("Beistand");
        addnav("Schnelligkeit","race_dwarf.php?op=shrine&step=3a");
        addnav("Kampfkraft","race_dwarf.php?op=shrine&step=3b");
    }
}
page_footer();
?>


