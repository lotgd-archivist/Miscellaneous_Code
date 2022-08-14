
<?php
#####################################
#                                   #
#            Osterspezial           #
#           für den Wald            #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#            von Calamus            #
#     mit der Unterstützung von     #
#    Laserian, Amon Chan und mfs    #
#         Texte von Calamus         #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################
require_once "common.php";
page_header("Osterwerkstatt");
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}

if (!isset($session)) exit();
bild("osterwerkstatt.jpg");
if ($_GET[oster]==""){
        output("`@Aufmerksam gehst Du durch den Wald, als Du querab vom Weg ein Geräusch hörst. ");
        output("Du bleibst stehen und lauscht genauer. Es hört sich wie Gackern an - jedenfalls nicht gefährlich, ");
        output("aber doch ziemlich aufgeregt. Neugierig, wie Du nun mal bist, gehst Du dem Geräusch entgegen ");
        output("und je näher Du kommst, desto deutlicher hörst Du, dass es wirklich Gackern ist. Vor dir lichtet ");
        output("sich der Wald und in dem Moment, als Du aus dem Wald auf eine Lichtung tritts, ");
        output("kräht lauthals ein Hahn.`n`n");
        output("Auf der Lichtung siehst Du zwei Häuser, auf dem einen Giebel ist kunstvoll ein Hase ");
        output("abgebildet, auf dem anderen ein bunter Hahn. ");
        output("`@Was wirst Du tun - klopfst Du an der Tür mit dem Hasenbild oder an der Tür mit dem Bild des Hahns?");
        addnav("Gehe zum Hasen-Haus","forest.php?oster=hase");
        addnav("Gehe zum bunter Hahn-Haus","forest.php?oster=hahn");
        $session[user][specialinc]="osterwerkstatt.php";
}else if ($_GET[oster]=="hase"){
        $session[user][turns]++;
        output("`@Du klopfst an das Haus mit dem Bild das Hasen und trittst in das Haus. ");
        output("Du kommst in einen grossen Raum mit lauter Tischen an denen Häschen sitzen. ");
        output("Jedes Häschen hat einen Pinsel in der Hand und eine Palette mit bunt angemischten Farben ");
        output("in der Hand. Tuben mit roter, gelber und blauer Farbe liegen auf den Tischen, ");
        output("Körbchen mit weissen Eiern stehen auf der einen Seite, Körbchen mit ");
        output("bunten Eiern auf der anderen Seite, ");
        output("vor den Häschen in einem Eierbecher siehst Du das Ei, das jeweils gerade in Arbeit ist.`n`n");
        output("Hier kommen also all die hübschen Ostereier her. Ein alter Hase deutet auf einen freien Stuhl ");
        output("und eifrig machst auch Du Dich an die Arbeit und malst Eier an.`n`n");
        output("Nach einiger Zeit lobt Dich der alte Hase und bedankt sich für Deine Hilfe und gibt Dir ");
        output("ein besonders schön bemaltes Ei.`n`n");
        output("Du hättest in der Zeit `^3 Waldkämpfe `@machen können, aber das Geschenk des alten Hasen ");
        output("lässt Dich glücklich sein. Du siehst viel schöner aus und fühlst Dich viel stärker.");
        $session[user][charm]+=3;
        $session[user][turns]-=3;
        $session[user][attack]+=3;
        $session[user][defence]+=3;
        addnav("Zurück zum Wald","forest.php");
        addnews($session[user][name]. " `@hat `&die `^Osterwerkstatt `@gefunden, `&hat `^fleissig `@geholfen
        `&und `^wurde `@viel `&besser. `^Vielleicht `@solltest `&auch `^Du `@die `&Osterwerkstatt `^suchen...");
        $session[user][specialinc]="";
}else if ($_GET[oster]=="hahn"){
        $session[user][turns]++;
        output("`@Du klopfst an das Haus mit dem bunten Hahn und trittst in ");
        output("das Haus. Nun weist Du, woher das Gackern kam - in mehreren Etagen siehst Du Hennen ");
        output("auf Stangen hocken, hinter sich eine Holzrinne, in die jeweils von lautem Gegacker begleitet ");
        output("Ei um Ei fällt. Langsam rollen die Eier die Rinnen herunter und landen in grossen, ");
        output("mit Stroh ausgelegten Nestern. Aus diesen Nestern sammeln ein paar Häschen die Eier ");
        output("in grosse Körbe. Auf dem Mittelgang zwischen den Stangen läuft ein ");
        output("bunter Hahn auf und ab und bedenkt seine Hennen von ");
        output("Zeit zu Zeit mit einem lauten Kikeriki.`n`n");
        output("Als Dich der Hahn sieht deutet er auf ein volles Nest und den leeren Korb davor und ");
        output("eifrig beginnst Du die Eier in das Körbchen zu stapeln..`n`n");
        output("Nach einiger Zeit lobt Dich der Hahn und bedankt sich für Deine Hilfe mit einem ");
        output("Beutelchen, das er Dir gibt.`n`n");
        output("Du hättest in der Zeit `^3 Waldkämpfe `@machen können, aber Du fühlst Dich viel erfahrener. ");
        output("Als Du das Beutelchen öffnest, siehst Du Gold und Edelsteine.");
        $session[user][gold]+=1000;
        $session[user][gems]+=10;
        $session[user][experience]+=1000;
        $session[user][turns]-=3;
        addnav("Zurück zum Wald","forest.php");
        addnews($session[user][name]. " `@hat `&die `^Osterwerkstatt `@gefunden, `&hat `^fleissig `@geholfen
        `&und `^wurde `@viel `&reicher. `^Vielleicht `@solltest `&auch `^Du `@die `&Osterwerkstatt `^suchen...");
        $session[user][specialinc]="";
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

