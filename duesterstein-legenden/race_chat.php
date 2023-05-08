
<?
// idea of gargamel @ www.rabenthal.de
require_once("common.php");
addcommentary();


function news() {
    $sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    db_free_result($result);
    //output("`@Es ist jetzt ".getgametime()." | Neueste Nachricht: ".$row[newstext]."`0`c`n`n");
    output("`@Neueste Nachricht: ".$row[newstext]."`0`c`n`n");
}

function chatintro ($verb="reden") {
    output("Du freust Dich darauf, über diese und andere Neuigkeiten zu ".$verb." -
    unter Deinesgleichen.`n`n`0");
}

switch ( $session['user']['race'] ) {
    case 1:    //troll
    page_header("Akwark");
    output("`c`b`&~~~ Versammlungsplatz ~~~`b`c`n");
    output("`7In bester Laune latscht Du auf den Versammlungsplatz. Auf der einen
    Seite ist an einem robusten Pfahl ein mystisch leutendes Brett genagelt, darauf
    kannst Du lesen:`n`n`c`0");
    news();
    chatintro("palavern");
    viewcommentary("trollchat","Palaver mit den Trollen",40,"palavert");
    addnav("Aktualisieren","race_chat.php");
    addnav("Zurück","race_troll.php?op=enter2");
    break;

    case 2:    //elf
    page_header("Quintarra");
    output("`c`b`&~~~ Versammlungsplatz ~~~`b`c`n");
    output("`7In bester Laune schwebst Du auf den Versammlungsplatz. Auf einem
    seltsamen übergrossen Blatt, dass an einem gedrehten Ast hoch über dem Platz hängt,
    kannst Du lesen:`n`n`c`0");
    news();
    chatintro("sprechen");
    viewcommentary("elfchat","Spreche mit den Elfen",40,"sagt");
    addnav("Aktualisieren","race_chat.php");
    addnav("Zurück","race_elf.php?op=enter2");
    break;

    case 3:    //human
    page_header("Waldpark");
    output("`c`b`&~~~ Versammlungsplatz ~~~`b`c`n");
    output("`7In bester Laune betrittst Du den Versammlungsplatz. An einem verschlungen
    gewachsenen Baum ist eine Pergamentrolle angeschlagen, auf der kannst Du lesen:`n`n`c`0");
    news();
    chatintro("reden");
    viewcommentary("humanchat","Rede mit den Menschen",40,"sagt");
    addnav("Aktualisieren","race_chat.php");
    addnav("Zurück","race_human.php?op=enter2");
    break;

    case 4:    //dwarf
    page_header("Thais");
    output("`c`b`&~~~ Versammlungsplatz ~~~`b`c`n");
    output("`7In bester Laune betrittst Du den Versammlungsplatz. An einem seltsamen
    rechteckigen Kristall, der zwischen zwei Säulen hängt, kannst Du lesen:`n`n`c`0");
    news();
    chatintro("schwatzen");
    viewcommentary("dwarfchat","Schwatze mit den Zwergen",40,"schwatzt");
    addnav("Aktualisieren","race_chat.php");
    addnav("Zurück","race_dwarf.php?op=enter2");
    break;
}
page_footer();
?>


