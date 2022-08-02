<?php
require_once "common.php";

checkday();

page_header("Feolandors Kartenhäuser");
addcommentary();
$cost = $session['user']['level']*50;
if ($_GET["op"]=="pay"){
    if ($session['user']['gold'] < $cost) {
        output("`2`9Feolandor`2 runzelt die Stirn. `&\"He, das reicht nicht ganz, mein Freund. Ich sagte doch, der Spaß kostet `^$cost`& Goldstücke. So viel ist das auch wieder nicht und von irgendwas muss ein armer Gnom doch auch leben, hmm? Aber schau dich hier ruhig weiter um...\"`0");
        addnav("Zurück","cardhouse.php?op=enter");
    }
    else {
        $session['user']['gold']-=$cost;
        output("`9Feolandor`2 nickt zufrieden, als du ihm díe verlangte Goldmenge überreichst. `&\"Danke sehr! Viel Spaß wünsche ich.\"`0");
        addnav("Bau beginnen","cardhouse.php?op=build");
    }
}
elseif ($_GET["op"]=="enter"){
    output("`2Du betrittst das Innere des Zeltes. Irgendetwas kommt dir hier merkwürdig vor - du schaust nach links, nach rechts, nach oben, und bist dir schließlich sicher: Das Zelt IST innen größer als außen...`n`nDer gesamte Boden des Zeltes ist mit seltsam aussehenden `tSpielkarten`2 bedeckt, die dir unbekannte Zeichen und Symbole beinhalten.`n
In der Mitte des Zeltes steht ein großer Tisch, der dich geradezu dazu einlädt, auf ihm ein Kartenhaus zu bauen.`n`n`0");
    $session['user']['cardhouse']=0;
    if ($session['user']['cardhouseallowed']>0){
           output("`9Feolandor`2 spricht dich erneut an - er muss dir wohl gefolgt sein. `&\"Na, gefällt dir, was du siehst? Wenn du es auch einmal versuchen willst... es kostet dich nur `^$cost`& Goldstücke.\"`2 Abwartend sieht er dich an - willst du es wagen, mit dem Bau eines Kartenhauses zu beginnen und den Preis dafür zahlen, oder willst du dieses seltsame Zelt doch lieber wieder verlassen?`0`n`n");
           addnav("Preis zahlen","cardhouse.php?op=pay");
    } else {
           output("`9Feolandor`2 spricht dich erneut an - er muss dir wohl gefolgt sein. `&\"Wieder hier? Scheint dir ja wirklich zu gefallen.\"`2 Der Gnom grinst dich breit an und fährt dann fort: `&\"Aber für heute reicht es erstmal, meinst du nicht? Lass doch auch die anderen mal ran.\"`0`n`n");
    }
    addnav("Zelt verlassen","cardhouse.php?op=leave");
    viewcommentary("cardhouse","Zu anderen Architekten flüstern",30,"flüstert");
} elseif ($_GET["op"]=="build") {

    if ($session['user']['cardhouse']==0){
        output("`RDu beginnst mit dem Bau deines Kartenhauses.");
        $session['user']['cardhouseallowed']--;
    } else {
        output("`RDu baust weiter an deinem Kartenhaus.");
    }
    if (e_rand(1,20) == 1) {
        output("`n`4Doch das ganze Kartenhaus bricht zusammen.");

        if ($session['user']['cardhouse']<100){
            output("`n`RDie ganze Arbeit vergebens, entschließt du dich frustiert, ins Dorf zurückzukehren.`0`n`n");
            addnav("Zurück zum Dorf","village.php");
        } else {
            switch(e_rand(1,10)) {
            case 1:
            case 2:
            case 3:
            case 4:
                output("`n`4Du wirst unter dem riesigen Kartenhaus begraben.`nDu bist TOT und verlierst all dein Gold sowie 5% deiner Erfahrung.`0`n`n");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['gold']=0;
                $session['user']['experience']*=0.95;
                addnews("`9".$session['user']['name']."`9 wurde unter einem riesigen Kartenhaus begraben.");
                addnav("Zu den News","news.php");
                break;
            case 5:
            case 6:
                output("`n`4Du wirst unter dem riesigen Kartenhaus begraben.`nDu bist TOT und verlierst all dein Gold sowie 10% deiner Erfahrung.`0`n`n");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['gold']=0;
                $session['user']['experience']*=0.90;
                addnews("`9".$session['user']['name']."`9 wurde unter einem riesigen Kartenhaus begraben.");
                addnav("Zu den News","news.php");
                break;
            case 7:
                output("`n`4Du wirst unter dem riesigen Kartenhaus begraben.`nDu bist TOT und verlierst all dein Gold sowie 15% deiner Erfahrung.`0`n`n");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['gold']=0;
                $session['user']['experience']*=0.85;
                addnews("`9".$session['user']['name']."`9 wurde unter einem riesigen Kartenhaus begraben.");
                addnav("Zu den News","news.php");
                break;
            case 8:
            case 9:
                output("`n`4Unter den großen, schweren Karten wirst du regelrecht eingequetscht und es dauert eine Weile, bis man dich endlich ausgräbt.");
                output("`nWegen deiner Dummheit verlierst du 10% deiner Erfahrung.");
                output("`nFrustriert entschließt du dich, ins Dorf zurückzukehren.`0`n`n");
                addnav("Zurück zum Dorf","village.php");
                $session['user']['experience']*=0.90;
                $session['user']['hitpoints']*=0.40;
                break;
            case 10:
                if ($session['user']['gems'] > 9) {
                    output("`n`4Zwar gelingt es dir gerade noch, zur Seite zu springen, bevor die schweren Karten dich unter sich begraben, aber etwas später stellst du fest, dass dir dabei wohl einige Edelsteine verloren gegangen sein müssen..");
                    $session['user']['gems'] -= e_rand(1,4);
                }
                else {
                    output("`n`4Dir gelingt es gerade noch, zur Seite zu springen, bevor die schweren Karten dich unter sich begraben.");
                }
                output("`nFrustriert entschließt du dich, ins Dorf zurückzukehren.`0`n`n");
                addnav("Zurück zum Dorf","village.php");
                break;
            }
        }
    } else {
        $session['user']['cardhouse']+=2;
        output("`n`RDie Karten scheinen zu halten, dein Haus besteht jetzt aus ".$session['user']['cardhouse']." Karten.`0`n`n");
        addnav("Weiterbauen","cardhouse.php?op=build");
        addnav("Aufhören","cardhouse.php?op=finish");


    }

} elseif ($_GET["op"]=="leave") {
    output("`2Dir kommt dieses Zelt doch etwas ungewöhnlich vor, und du machst dich lieber aus dem Staub.`0");
    addnav("Zurück zum Dorf","village.php");
} elseif ($_GET["op"]=="finish"){

    if ($session['user']['cardhouse'] ==0)  {redirect("cardhouse.php?op=leave");}
    output("`QStolz bewunderst du dein Kartenhaus, das immerhin aus ganzen ".$session['user']['cardhouse']." Karten besteht.`0`n");
    if ($session['user']['cardhouse'] > $session['user']['maxcardhouse']) {
        $session['user']['maxcardhouse'] = $session['user']['cardhouse'];
        if ($session['user']['cardhouse']>100) {
            $sql = "SELECT maxcardhouse FROM accounts ORDER BY maxcardhouse DESC LIMIT 1";
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            if ($session['user']['cardhouse'] > $row['maxcardhouse']) {
                output("Du hast einen neuen Rekord aufgestellt!");
                switch(e_rand(1,2)) {
                    case 1:
                        $cxp=round($session['user']['experience']*0.20);
                        $session['user']['experience']+=$cxp;
                        output("`nDu hast dich als wahrer Meister des Kartenhausbaus erwiesen und erhälst $cxp Erfahrungspunkte.`n");
                        break;
                    case 2:
                        $gems=e_rand(5,8);
                        $session['user']['gems']+=$gems;
                        output("`nFeolandor tritt an dich heran und klopft dir anerkennend auf die Schulter. `&\"Sowas wollen die Kunden hier sehen! Leute wie du kurbeln mein Geschäft ordentlich an.\"`Q Er macht eine kurze Pause und scheint etwas zu zögern, greift dann aber in seine Taschen und fährt fort: `&\"Hier, erlaube mir, dir eine kleine Belohnung zukommen lassen.\"`Q Mit diesen Worten holt er einige Edelsteine hervor und überreicht sie dir.");
                        output("`nDu erhälst $gems Edelsteine für deine Leistung!`n");
                        break;
                }
            }
            else {
                switch(e_rand(1,2)) {
                    case 1:
                        $cxp=round($session['user']['experience']*0.07);
                        $session['user']['experience']+=$cxp;
                        output("Bei der ganzen Aktion hast du ein wenig an Geschick gewonnen und einiges gelernt - du erhälst $cxp Erfahrungspunkte.`n");
                        break;
                    case 2:
                        $gems=e_rand(2,3);
                        $session['user']['gems']+=$gems;
                        output("`nFeolandor tritt an dich heran und klopft dir anerkennend auf die Schulter. `&\"Ich habe zwar schon Besseres gesehen als das, aber auch sowas wird sicher etwas Eindruck auf die anderen Kunden machen.\"`Q Er macht eine kurze Pause und scheint etwas zu zögern, greift dann aber in seine Taschen und fährt fort: `&\"Hier, erlaube mir, dir eine kleine Belohnung zukommen lassen.\"`Q Mit diesen Worten holt er einige Edelsteine hervor und überreicht sie dir.");
                        output("`nDu erhälst $gems Edelsteine für deine Leistung!`n");
                        break;
                }
            }
        }
        output("`qDieses Haus wirst du noch lange in Erinnerung behalten.`0");
    }
    addnav("Zurück zum Dorf","village.php");


} else {

    output("`2Vom Dorfplatz schlenderst du langsam zu einem kleinen schmutzigen Zelt, vor dem ein genauso kleiner und schmutziger Gnom scheinbar nach Kunden Ausschau hält.`nEr stellt sich dir als `9Feolandor `2vor, und erzählt in großen Worten von seinen `tmagischen Karten`2 und was für große Kartenhäuser man aus diesen bauen könne.`nEr schlägt dir vor, dir die Sache selbst einmal anzuschauen und macht eine einladende Geste in Richtung des Zelteingangs.`0`n`n");
    $sql = "SELECT name FROM accounts WHERE maxcardhouse > 0 ORDER BY maxcardhouse DESC LIMIT 1";
    $result = db_query($sql);

    output("Derzeitiger Rekordhalter:");
    if (mysql_num_rows($result) == "0") output("`nNiemand!");
    else {
         $row = db_fetch_assoc($result);
       output("`n".$row['name']);
        }
    output("`n`n");

    addnav("Zelt betreten","cardhouse.php?op=enter");
    addnav("Zurück zum Dorf","village.php");
}

page_footer();

?>