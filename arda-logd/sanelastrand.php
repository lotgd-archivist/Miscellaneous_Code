<?php
//*-------------------------*
//|   sanelastrand.php by   |
//|       °*Amerilion*°     |
//|      greenmano@gmx.de   |
//|  Texte by eulchen THX   |
//*-------------------------*

//Sanela-Pack Version 1.1


require_once "common.php";
page_header("Der Strand");
$session['user']['sanela']=unserialize($session['user']['sanela']);
output("`c`b`^Der Strand`n`n`c`b");

if($_GET['op']==""){
    output("`6Du gehst den kleinen Weg entlang an einigen Hütten vorbei und kommst");
    output("an einen Wald aus Dünengräsern. Du gehst durch sie hindurch und besteigst");
    output("eine kleine Düne. Auf der anderen Seite eröffnet sich ein wunderbarer, sauberer");
    output("Strand deinem Blick. Die Wellen spülen sanft an das Ufer. In einger");
    output("Entfernung ragen hohe Klippen in den Himmel hinauf.`n`n`n");
    addcommentary();
    viewcommentary("sanelastrand","Sprechen",10,"spricht");
    if ($session['user']['turns']>0 && $session['user']['sanela']['strand']<2) {
        addnav("Zur Klippe","sanelastrand.php?op=klippe");
    }
    addnav("entlang schlendern","sanelastrand.php?op=schlendern");
    addnav("zur Wegkreuzung","kreuzung.php");
    addnav("Zurück","sanela.php");
}
if($_GET['op']=="klippe"){
    output("`6Langsam schlenderst du am Strand entlang, bis du zu einer recht hohen");
    output("Klippe hinaufschauen kannst. An der Seite befinden sich Stufen, die in den");
    output("Fels eingemauert sind, sodass du kurzerhand beschließt, emporzuklettern.");
    output("Erschöpft kommst du oben an und als du dich umschaust stockt dir der");
    output("Atem. `n Dir bietet sich eine traumhafte Aussicht auf das Meer und den ");
    output("Nachthimmel. Die Sterne funkeln heller, als du es je gesehen hast");
    output("und du hast das Gefühl, dass sie zum greifen nah sind. Dir kommt der Gedanke,");
    output("dass sich dir solch eine Chance vielleicht nie wieder bietet und du für");
    output("deine große Liebe einen Stern vom Himmel holen könntest, denn was wäre das");
    output("nicht für ein wunderbares Geschenk? Unschlüssig stehst du da...");
    addnav("Stern vom Himmel holen","sanelastrand.php?op=stern");
    addnav("Zurück","sanelastrand.php");
    $session['user']['sanela']['strand']++;
    if($session['user']['turns']>0) $session['user']['turns']--;
}
if($_GET['op']=="stern"){
    switch(e_rand(1,4)){
        case 1:
        output("`6Vorsichtig greifst du nach den Sternen und bist überglücklich, dass es dir gelingt,");
        output("einen für deine große Liebe vom Himmel zu nehmen. Du bist sogar so glücklich, dass du");
        output("nicht merkst, dass ein alter Mann vorbeikommt, dir Geld und Edelsteine in die Hand drückt");
        output("und deinen Stern mitnimmt. Leicht verdattert stehst du da, aber vielleicht kannst du ja");
        output("von dem Gold etwas schönes für deinen Schatz kaufen?`n`n`^Du erhältst 2 Edelsteine und 2000 Gold!");
        $session['user']['gold']+=2000;
        $session['user']['gems']+=2;
        addnews($session['user']['name']." `6ist Schuld, dass am Himmel nun ein Stern weniger leuchtet!");
        addnav("Runterklettern","sanelastrand.php");
        break;
        case 2:
        output("`6Als du nach den Sternen greifen willst, bemerkst du plötzlich erst was du hier machst.");
        output("Du versuchst allen Geschöpfen der Welt ein Licht zu stehlen. Erschrocken zuckst du zurück");
        output("und stolperst dabei über einen Stein. Du bleibst eine Zeit liegen und siehst dir den Sternenhimmel");
        output("an. Als du schließlich wieder zum Strand runterkletterst empfängt dich eine Wache:`n");
        output("`#\"Klettern ohne Sicherung, das gibt eine Verwarnung!\",`n`6 spricht er dich an, hebt drohend");
        output("den Zeigefinger und marschiert von dannen. Dies haben einge andere Abenteuer mitbekommen welche");
        output("überall von deiner Dummheit erzählen`n`n`^Du verlierst 3 Charmpunkte.`nDu verlierst 3 Runden.");
        if($session['user']['turns']>3){
            $session['user']['turns']-=3;
        }else{
            $session['user']['turns']=0;
        }
        if($session['user']['reputation']>15){
            $session['user']['reputation']-=15;
        }else{
            $session['user']['reputation']=0;
        }
        addnav("Weiter","sanelastrand.php");
        break;
        case 3:
        case 4:
        output("`6Du greifst nach den Sternen, aber du hast wohl ein wenig hoch gegriffen und bist ihnen zu weit");
        output("hinterher gerannt, denn du fühlst auf einmal den Boden unter den Füßen nicht mehr! Noch bevor");
        output("du diese Tatsache wirklich realisierst, prallst du auf dem Wasser auf und verlierst das Bewusstsein...");
        output("`^`n`nDu bist `iertrunken`i.`nDu verlierst 5% deiner Erfahrung.`nDu verlierst all dein Gold.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['experience']*=0.95;
        $session['user']['gold']=0;
        addnews($session['user']['name']."`6 hat hoch nach den Sternen gegriffen und ist tief gefallen.");
        addnav("Tägliche News","news.php");
        break;
    }
}
if($_GET['op']=="schlendern"){
    if ($session['user']['sanela']['strand']<2){
        switch(e_rand(1,5)){
            case 1:
            case 2:
            case 3:
            output("`6Du gehst gemütlich am Strand entlang und genießt den Wind, der in deinen");
            output("Haaren spielt, an deiner Kleidung zerrt und alle deine Sorgen mit sich");
            output("hinwegzuwehen scheint. Achtlos ziehst du deine Schuhe aus und genießt");
            output("das Wasser, das sanft deine Füße umspielt. Du nutzt die Zeit der Ruhe,");
            output("um ein wenig mit dir ins Reine zu kommen und Kraft zu tanken. Als du zurück");
            output("in Richtung Dorf gehst, fühlst du dich ausgeglichen und glücklich, was");
            output("den anderen nicht unbemerkt bleibt.`n`n`^Du bekommst 2 Charmpunkte.`nDu bekommst");
            output("eine ruhige Hand.");
            $session['user']['charm']+=2;
            $session['bufflist']['sanela']['strand'] = array("name"=>"`^Strandsegen",
            "rounds"=>15,
            "wearoff"=>"`^Das Meeresrauschen in deinen Ohren verschwindet",
            "defmod"=>1,
            "atkmod"=>1.1,
            "roundmsg"=>"`^Ein leises Meeresrauschen hilft dir, dich für deinen Angriff zu konzentieren",
            "activate"=>"offense");
            addnav("Der Strand","sanelastrand.php");
            break;
            case 4:
            case 5:
            output("`6Du gehst ein wenig am Strand entlang, doch das Wetter verschlechtert sich zunehmend,");
            output("die Wellen werden größer, der Wind stärker. Da siehst du eine Gestalt im Wasser,");
            output("die zu ertrinken scheint, und entfernte Hilferufe dringen an dein Ohr.");
            addnav("Hilfe holen","sanelastrand.php?op=hole");
            addnav("Rette die Person","sanelastrand.php?op=rette");
            addnav("Nicht hinsehen","sanelastrand.php?op=ns");
            break;
        }
        $session['user']['sanela']['strand']++;
    }else{
        output("`6Du schlenderst den Strand entlang, genießt das leise Rauschen der Wellen");
        output("und das kreischen der Möwen. Du atmest den salzigen Duft des Meeres und");
        output("erfreust dich an diesem ruhigen Moment.");
        addnav("Der Strand","sanelastrand.php");
    }
}
if($_GET['op']=="hole"){
    output("`6Schnell rennst du zum Dorf zurück und benachrichtigst die Stadtwache. Eilig");
    output("führst du sie zurück zu der Stelle,");
    switch(e_rand(1,5)){
        case 1:
        output("worauf sie den Ertrinkenden sicher bergen.`n`n`^Du bekommst 500 Gold.`nDu bekommst 2 Charmpunkte.");
        $session['user']['gold']+=500;
        $session['user']['charm']+=2;
        addnav("Der Strand","sanelastrand.php");
        break;
        case 2:
        output("doch es ist weit und breit keiner mehr zu sehen. Die Stadtwache geht sauer wieder");
        output("ins Dorf zurück, da sie das ganze für einen üblen Scherz deinerseits hält.");
        output("`n`n`^Du verlierst 2 Charmepunkte.");
        if($session['user']['charm']>2){
            $session['user']['charm']-=2;
        }else{
            $session['user']['charm']=0;
        }
        addnav("Der Strand","sanelastrand.php");
        break;
        case 3:
        case 4:
        case 5:
        output("doch alle Hilfe kommt zu spät! Am Strand liegt die Leiche von ");
        output("einem kleinen Mädchen.`n `6Du gehst geschockt zurück zum Dorf, wo du dich erst mal");
        output("von dem Schrecken erholst.");
        if($session['user']['turns']>3){
            output("`n`n`^Du verlierst 3 Runden.");
            $session['user']['turns']-=3;
        }else{
            output("`n`n`^Du verlierst alle restlichen Runden.");
            $session['user']['turns']=0;
        }
        addnav("Zum Dorf","sanela-reise2.php");
        break;
    }
}
if($_GET['op']=="rette"){
    $g=e_rand(500,2500);
    $c=e_rand(1,3);
    output("`6Dir scheint, als ob du nicht mehr viel Zeit hast, und springst ins Meer, um diese Person zu retten.");
    switch(e_rand(1,3)){
        case 1:
        case 2:
        output("Es gelingt dir, das ertrinkende Mädchen rechtzeitig an Land zu ziehen, worauf");
        output("du von ihr mit Danksagungen überschüttet wirst. Da das die Tochter des");
        output("Möbelhausbesitzers war, erhältst du von dem dankbaren Vater eine Belohnung.");
        output("Ausserdem erzählt er allen von deiner Tat.`n`n`^Du bekommst ".$g." Gold und");
        output($c." Charmpunkte.");
        $session['user']['gold']+=$g;
        $session['user']['charm']+=$c;
        addnews($session['user']['name']."`6 rettete ein kleines Mädchen aus den Fluten.");
        addnav("Der Strand","sanelastrand.php");
        break;
        case 3:
        output("Als du bei dem Mädchen ankommst, klammert es sich voller Verzweiflung an dich,");
        output("doch du hast nichtmehr genügend Kraft, euch über Wasser zu halten.`n`n`^Du bist ertrunken.");
        output("Dein Gold haben die Fische.`nDeine ehrenhaften Absichten imponieren Ramius. Du bekommst 15 Gefallen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['experience']*=0.95;
        $session['user']['gold']=0;
        $session['user']['soulpoints']+=15;
        addnews($session['user']['name']." wurde von einer Nixe in die Irre geführt und starb in dem Glauben, ein kleines Mädchen zu retten versucht zu haben!");
        addnav("Tägliche News","news.php");
    }
}
if($_GET['op']=="ns"){
    output("Du gehst weiter, hast aber doch ein schlechtes Gewissen als du kurz darauf");
    output("einen verzweifelten Mann den Strand auf und ab laufen siehst.`n`nDein Ansehen");
    output("bei den übrigen Dorfbewohnern leidet beträchtlich darunter, dass du den Ertrinkenden");
    output("kaltblütig seinem Schicksal überlassen hast.");
    addnav("Der Strand","sanelastrand.php");
    if($session['user']['reputation']>15){
        $session['user']['reputation']-=15;
    }else{
        $session['user']['reputation']=0;
    }
}
$session['user']['sanela']=serialize($session['user']['sanela']);
page_footer();
?>