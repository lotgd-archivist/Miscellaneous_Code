
<?

#####################################
#                                   #
#            Osterspezial           #
#            für den Wald           #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#        von Sefan Freihagen        #
#       mit Unterstützung von       #
#     Laserian, Amon Chan und mfs   #
#          Texte von Charina        #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################

require_once "common.php";
page_header("Eierhandel");
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
bild("eierhandel.jpg");
if ($_GET[op]==""){
   output("`n`n`c`b`@De`&r E`^ie`&r`^ha`&nd`@el`c`n`n`b");
   output("`n`n`@Du gehst gelangweilt durch den großen Wald. Und hoffst endlich mal auf Abwechslung.
   Denn egal wo du hin schaust überall nur Bäume und Grünzeug. Deine Waffe nervt dich auch schon.
   Denn du hast sie schon lange nicht mehr gelöst. Doch die kriegst du nicht runter. Deswegen packst
   du wütend dein kleines Messer und zerschneidest den Gürtel und schmeißt deine Waffe vor Wut weit
   weg. Erst jetzt merkst du vor Dummheit: `&\"Was mach ich jetzt, wenn ich angegriffen werde? Ich
   sterbe dann wahrscheinlich.\" `@Du überlegst was du machst.`n`n");
   addnav("Deine Waffe suchen gehen","forest.php?op=suchen");
   addnav("Einen Stock nehmen","forest.php?op=stock");
   $session[user][specialinc] = "eierhandel.php";
}

if ($_GET[op]=="suchen"){
   output("`n`n`@Du trampelst hinter deiner Waffe hinter her und merkst, dass du nun ganz wo anders
   bist. Es ist ein schönes weites Feld, das von dem Licht sehr hell beleuchtet wird. Und die
   verschiedenen Blumen riechen einfach wundervoll. Du bist im 7. Himmel so wundervoll riechen die
   Blumen. Dann bemerkst du deine Waffe zwischen dem Grünzeug. Du folgst dieser schnell. Aber du
   merkst, dass sie immer tiefer in das Feld gezogen wird. Dann springst du rasch auf deine Waffe
   zu und packst sie stolz. Und hebst diese glücklich in die Luft. Du hörst eine seltsame hohe Stimme:
   `&\"Hallo, du langes etwas, lass seltsames Ding liegen. Es mir gehören, habs gefunden.\" `@Dann siehst
   du verwirrt umher und merkst ein Häschen hält sich an deiner Waffe fest. Du fragst es verwirrt:
   `&\"Du kannst sprechen? Aber Moment mal das ist meine Waffe. Ich brauche sie dringender als du.\"
   `@Auf deine Frage antwortet das Häschen mit einem seltsamen Nicken. Kurz danach sagt es: `&\"Du
   seltsames Ding wollen? Dann müssen du mir Eier abkaufen. Eins kostet 10 Glitzer.\"`@`n`n");
   addnav("Eierkauf");
   addnav("Du kaufst 1 Ei für 10 Gold",",forest.php?op=einei");
   addnav("Du kaufst 5 Eier für 50 Gold","forest.php?op=eier");
   addnav("Du kaufst nichts","forest.php?op=nichts");
   $session[user][specialinc] = "eierhandel.php";
}

if ($_GET[op]=="stock"){
   output("`n`n`@Du nimmst einen Stock als Waffe und setzt deinen Weg fort.
   Als du den Stock genauer betrachtest siehst du, dass es deine Waffe ist.
   Du atmest erleichtert auf, weil du schon dachtest deine schöne Waffe wäre nun verloren.
   Du bleibst erstmal noch ein wenig hier sitzen, der Schock war etwas zu viel dich.`n`n");
   $session['user']['turns']-=3;
   addnav("Zurück zum Wald","forest.php");
}

if ($_GET[op]=="einei"){
   output("`n`n`@Das Häschen bedankt sich sehr für deinen Kauf und verschwindet ohne dich weiter
   zu stören. Und nun hast du ein Ei und deine Waffe. Als du den Weg zurück suchst, merkst du,
   dass die Eier, alles angemalte Edelsteine sind. Und nun bist du eigentlich reicher als zuvor
   und gehst stolz weiter jagen.`n`n");
   $session['user']['gems']+=1;
   addnav("Zurück zum Wald","forest.php");
}

if ($_GET[op]=="eier"){
   output("`n`n`@Das Häschen bedankt sich sehr für deinen Kauf und verschwindet ohne dich weiter
   zu stören. Und nun hast du fünf Eier und deine Waffe. Als du den Weg zurück suchst, merkst du,
   dass die Eier, alles angemalte Edelsteine sind. Und nun bist du eigentlich reicher als zuvor
   und gehst stolz weiter jagen.`n`n");
   $session['user']['gems']+=5;
   addnav("Zurück zum Wald","forest.php");
}

if ($_GET[op]=="nichts"){
   output("`n`n`@Du willst nichts kaufen, denn deine Mutter hat dir gesagt: `&\"Kaufe nichts von
   Fremden.\" `@Deswegen gehst du einfach so weg mit deiner Waffe. Plötzlich merkst du wie du
   unten angeknabbert wirst. Als du runter blickst, bemerkst du hunderte von Häschen. Du bist
   völlig entsetzt und schlägst um dich. Damit sie dich in Ruhe lassen. Doch plötzlich merkst
   du, die Häschen haben dir deine Beine weg geknabbert und du landest beinlos bei Ramius.`n`n");
   $session['user']['hitpoints']=0;
   addnav("Ramius besuchen","shades.php");
   addnews($session[user][name]." `@hat `&seine `^Beine `@wegen `&wütenden `^Händlerhäschen `@verloren!");
}

$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);

page_footer();
?>

