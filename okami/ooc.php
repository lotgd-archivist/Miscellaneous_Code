
<?php
require_once('common.php');

checkday();
require_once(LIB_PATH.'board.lib.php');
page_header('Debattierräume');
$session[user][standort]="OOC";

if ($_GET['op']=="diskus")
{
    output("`2Der Debattierraum liegt vor Dir!`n
    Hier bekommt das Volk Gehör und die Admins hören sich Wünsche, Anregungen und Beschwerden an. ");
    output("Wie Dir scheint ist schon eine rege Diskussion im Gange!`n`n");
    addcommentary(false);
    viewcommentary("rat","Rufen",30,"ruft");

  addnav('Räume');
    addnav('OOC - Raum','ooc.php?op=ooc');
    addnav('RP-Suche','ooc.php?op=brett');

    if($session['user']['alive'])
    {
    addnav("Zurück");
    addnav("Dorfamt","dorfamt.php");
        addnav("Platz", "village.php");
    }
    else
    {
        addnav("Zurück","shades.php");
    }

}

else if ($_GET['op']=="ooc")
{
//    output("`n`c`b`VOoC`b`c");
    output("`SDu läu`Yfst ge`trade d`aurch d`Gen Wal`kd, als du auf eine kleine  Lichtung tritts. In der Mitte steht ein riesiger Baum, er ist das Herz des Waldes, die meisten Bäume fanden hier  ihren Ursprung. Plötzlich fällt dir auf, das ein Ast aussieht als wäre er ein Türgriff. Aus neugierde ziehst du daran und tatsächlich, eine Tür öffnet sich. `n
Du blickst in einen schmalen, stockfinsteren Gang. Zuerst zögerst du noch hineinzugehen, aber dann besiegt deine Neugierde die Angst und du wagst ein paar Schritte hinein. Schon jetzt kannst du lautes Gelächter hören. Als du das Ende des Gangs findest und ihn erreicht hast, staunst du nicht schlecht. Nun befindest du dich im sagenhaften OoC-Raum, hier ist alles zufinden was die Seele begehrt.`n
Superweiche und bequeme Sofas stehen hier. Auch einige Sitzkissen in den Ecken und über den Boden verteilt, kannst du sie finden. Hier ist genug Platz für alle. Jeder hier ist freundlich und nett. Sie begrüßen dich herzlich. `GAlle f`areuen `tsich d`Yas du d`Sa bist.`n`n");
    output("`^Du hast den OOC Raum betreten. Wenn Du Gespräche führen möchtest, die sich außerhalb Deines Charakters befinden,
    so führe sie bitte hier! Sollten sich andere Mitspieler irgendwo anders OOC unterhalten, dann weise sie bitte freundlich
    per Brieftaube darauf hin, dass dies hier der richtige Ort dafür wäre!`n`n");
    addcommentary(false);
    viewcommentary("ooc","Tippen",30,"tippt");

  addnav('Räume');
    addnav('Diskussionsraum','ooc.php?op=diskus');
    addnav('RP-Suche ','ooc.php?op=brett');

    if($session['user']['alive'])
    {
        addnav("Zurück");
    addnav("Dorfamt","dorfamt.php");
        addnav("Platz","village.php");
    }
    else
    {
        addnav("Zurück","shades.php");
    }
}

else if ($_GET[op]=="brett")
{
    page_header('RP-Suche');
    output('`2An der Wand des OOC-Raums entdeckst du ein kleines, schwarzes Brett. Eine kleine Tafel informiert dich darüber, dass du hier OOC nach Spielpartnern suchen kannst, jeglicher anderer Spam jedoch gelöscht wird.`n`n');
    board_view('ooc',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'Folgende Nachrichten hängen am Brett:','Es befinden sich keine Nachrichten am Brett',true, true, false, true);
    output('`n`n');
    board_view_form('Aufhängen','Auch du kannst eine Nachricht hinterlassen:`n');
    output('`n`n');
    if ($_GET['board_action'] == "add")
    {
        if (board_add('ooc',14,1) == -1)
        {
            output('`4Du hast doch schon einen Zettel aufgehängt, das sollte wirklich reichen.`n`n');
        }
        else
        {
            redirect("ooc.php?op=brett");
        }
    }
    addnav('Räume');
  addnav('Diskussionsraum','ooc.php?op=diskus');
    addnav('OOC - Raum','ooc.php?op=ooc');

    if($session['user']['alive'])
    {
        addnav("Zurück");
    addnav("Dorfamt","dorfamt.php");
        addnav("Platz","village.php");
    }
    else
    {
        addnav("Zurück","shades.php");
    }
}

page_footer();
?>

