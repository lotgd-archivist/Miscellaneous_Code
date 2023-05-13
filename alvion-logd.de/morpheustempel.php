
<?php
// Idee und Umsetzung
// Morpheus aka Apollon
// 2006 für logd.at(LoGD 0.9.7 +jt ext (GER) 3)
// Mail to Morpheus@magic.ms or Apollon@magic.ms
// gewidmet meiner über alles geliebten Blume
require_once "common.php";
page_header("Tempel des Morpheus");
if($_GET['op']==""){
    output("`7`b`cTempel des Morpheus`c`b");
    output("`n<table align='center'><tr><td><IMG SRC=\"images/morpheus.jpg\"></tr></td></table>`n",true);
    output("`3Du betrittst einen recht hellen Tempel, der aus Sandstein errichtet wurde.");
    output("`3Hinter dem Tor erkennst Du eine Gebtsnische und 2 Gänge, die nach rechts und links gehenund einen kleine Platz umschließen, auf dem eine mächtige Eiche wächst.`n");
    output("`3Im eigentlichen Tempelraum, den Du am Ende der kleinen Gänge erreichst, siehst Du in der Mitte eine Statue des Morpheus stehen, davor ein medithierender Priester.`n");
    output("Als er Dich bemerkt, beendet er seine Andacht und wendet sich Dir zu: `#Sei willkommen im Tempel des Morpheus, ".$session['user']['name']."!");
    output("`#Gehe ich recht in der Annahme, daß Du die Gnade des Gottes erflehen möchtest, um etwas über die Hohe Schule des Kampfes zu lernen?`n");
    output("`3Du nickst lächelnd mit dem Kopf und der Priester erklärt Dir:`# Ich bin gerne bereit dazu, so Du dem Gott ein entsprechendes Opfer bringst.");
    output("`#Die Erlanguung eines Angriffs- oder Verteidigungspunktes kostet Dich 30 Gems, die mußt Du dem Gott als Opfer darbringen`n`n");
    output("`#Bedenke, daß die Lektionen, die ich Dich lehren werde, auch Zeit beanspruchen.`n`n");
    addnav("Ich möchte etwas über die Kunst des Angriffs wissen - 30 Gems", "morpheustempel.php?op=att");
    addnav("Ich möchte etwas über die Kunst der Verteidigung wissen - 30 Gems", "morpheustempel.php?op=def");
    addnav("Zurück zum Klosterhof", "kloster.php");
    }
if($_GET['op']=="att"){
    if ($session[user][turns] >4) {
        if ($session[user][gems] >29) {
                $session[user][gems]-=30;
                $session[user][turns]-=5;
                     output(" `3Du greifst zu Deinem Beutel und überreichst dem Priester `@30 Gems`3, die er dankend entgegen nimmt und sie in einer Altarnische hinter sich verstaut.");
                     output(" `3Dann wendet er sich wieder um und geht zu einer Truhe, entnimmt ihr 2 Übungsschwerter, von denen er Dir eines überreicht.");
                     output(" `3Er beginnt, Dir Übungen, Kniffe und Hiebfolgen zu zeigen und sie Dir zu erklären, die Du nachahmst und verinnerlichst, was er Dir sagt und zeigt.`n");
                     output(" `3Nach einer langen Zeit der Übung nimmt er das Schwert wieder an sich und erteilt Dir den Segen des Gottes. Du hast 1 weiteren Angriffspunkt erhalten" );
                     $session[user][attack]+=1;
        }else{
                     output("`3Der Priester schüttelt lächelnd den Kopf:`5 Ich fürchte, Deine Gems werden nicht ausreichen, die Gunst des Gottes zu erlangen."); ;
        }
    }else{
        output("`3Der Priester schüttelt lächelnd den Kopf:`5 Du scheinst mir heute schon zu müde dazu zu sein. Ruhe Dich aus und kehre noch einmal wieder, wenn Du wieder erholter bist.");
    }
    addnav("Zurück zum Klosterhof", "kloster.php");
}
if($_GET['op']=="def"){
    if ($session[user][turns] >4) {
        if ($session[user][gems] >29) {
                $session[user][gems]-=30;
                $session[user][turns]-=5;
                     output(" `3Du greifst zu Deinem Beutel und überreichst dem Priester `@30 Gems`3, die er dankend entgegen nimmt und sie in einer Altarnische hinter sich verstaut.");
                     output(" `3Dann wendet er sich wieder um und geht zu einer Truhe, entnimmt ihr 2 Übungsschwerter und 2 Schilde, von denen er Dir je eines überreicht.");
                     output(" `3Er beginnt, Dir Übungen, Kniffe und Hiebfolgen zu zeigen und sie Dir zu erklären, die Du nachahmst und verinnerlichst, was er Dir sagt und zeigt.`n");
                     output(" `3Nach einer langen Zeit der Übung nimmt er das Schwert und den Schild wieder an sich und erteilt Dir den Segen des Gottes. Du hast 1 weiteren Verteidigungspunkt erhalten" );
                     $session[user][defence]+=1;
        }else{
                     output("`3Der Priester schüttelt lächelnd den Kopf:`5 Ich fürchte, Deine Gems werden nicht ausreichen, die Gunst des Gottes zu erlangen.");
        }
    }else{
        output("`3Der Priester schüttelt lächelnd den Kopf:`5 Du scheinst mir heute schon zu müde dazu zu sein. Ruhe Dich aus und kehre noch einmal wieder, wenn Du wieder erholter bist.");
    }
    addnav("Zurück zum Klosterhof", "kloster.php");
}
page_footer();
?>


