
<?php

require_once "common.php";

page_header("Drachental");

/*
##>>>Drachental<<<##
##(c)2004 by angel##

Das Drachental sollte als Ersatz gelten für die Drachenbesitzer die selbst mit einer Karte zur
Orkburg nicht mehr dort hingelangen konnten. Wie es dann eingebaut wird, und ob es vielleicht
auch als special-ereigniss im Wald ist (dann bitte melden und ich änder den einführungstext und
co.) bleibt den Admins des jeweiligen Servers überlassen.

Geschrieben für:  http://www.nicegames.de
Idee: Nicegames-Forum Community

Modifications for Rabenthal by Raven

*/


$session['user']['specialinc'] = 'drachental.php';
//$session['user']['seendragon'] = 1;


// Drachental verlassen
if ($_GET['op']=="leave"){
    $session['user']['specialinc']="";
    output('`7Du lässt das Drachental hinter dir. Als du dich nach einigen Metern noch einmal umschaust, ist das Tal mitsamt den es umgebenden Felswänden verschwunden. Ob dir das jemand glauben wird?');
    output("`n`n`^Du vertrödelst einen Waldkampf!");  
    if ($session[user][turns]>0) $session[user][turns]--;
    //redirect('forest.php');

// zu müde also gleich wieder raus
}elseif ($_GET['op']=="leave2"){
        output('`7Du lässt das Drachental hinter dir. Als du dich nach einigen Metern noch einmal umschaust, ist das Tal mitsamt den es umgebenden Felswänden verschwunden. Ob dir das jemand glauben wird?');
// Vor'm Drachen Fliehen in die Bar
}elseif ($_GET['op']=="leaveinn"){
    $session['user']['specialinc']="";
    redirect('inn.php');

// Grünen Drachen suchen
} elseif ($_GET['op']=="dragon"){
    addnav("Betrete die Höhle","dragon.php");
    addnav("Renne weg wie ein Baby","forest.php?op=leaveinn");
    output("`\$Du betrittst den dunklen Eingang einer Höhle in den Tiefen des Waldes, ");
    output(" im Umkreis von mehreren hundert Metern sind die Bäume bis zu den Stümpfen niedergebrannt.  ");
    output("Rauchschwaden steigen an der Decke des Höhleneinganges empor und werden plötzlich ");
    output("von einer kalten Windböe verweht.  Der Eingang der Höhle liegt an der Seite eines Felsens ");
    output("ein Dutzent Meter über dem Boden des Waldes, wobei Geröll eine kegelförmige ");
    output("Rampe zum Eingang bildet.  Stalaktiten und Stalagmiten nahe des Einganges ");
    output("erwecken in dir dein Eindruck, dass der Höhleneingang in Wirklichkeit ");
    output("das Maul einer riesigen Bestie ist.  ");
    output("`n`nAls du vorsichtig den Eingang der Höhle betrittst, hörst - oder besser fühlst du, ");
    output("ein lautes Rumpeln, das etwa dreißig Sekunden andauert, bevor es wieder verstummt ");
    output("Du bemerkst, dass dir ein Schwefelgeruch entgegenkommt.  Das Poltern ertönt erneut, und hört wieder auf, ");
    output("in einem regelmäßigen Rhythmus.  ");
    output("`n`nDu kletterst den Geröllhaufen rauf, der zum Eingang der Höhle führt. Deine Schritte zerbrechen ");
    output("die scheinbaren Überreste ehemaliger Helden.");
    output("`n`nJeder Instinkt in deinem Körper will fliehen und so schnell wie möglich zurück ins warme Wirtshaus und ");
    output(" ".($session['user']['sex']?"zum noch wärmeren Seth":"zur noch wärmeren Violet").".  Was tust du?");

//Talkrunde
}elseif ($_GET['op']=="talk"){
    output("`4Du näherst dich einer Gruppe von Kämpfern, die um ein Feuer sitzen und von ihren Heldentaten berichten:`n`n");
    viewcommentary("dragonvally","Erfahrungen austauschen",30,"berichtet");
    addnav("Zurück","forest.php");

//Drachenschmiede

}elseif ($_GET['op']=="schmiede"){
    output("`7Du entschließt dich, eine Höhle, aus der besonders lautes Hämmern und ein dunkler Rauch kommt, genauer zu erforschen und kletterst den Hang hoch. In der Höhle angekommen, begrüßt dich ein Zwerg: \"`4Sei gegrüßt! Ich bin Aloras und hier der Schmied. Wir nehmen Waffen und Rüstungen und werden unser Bestes tun, sie im Drachenfeuer zu verbessern.`7\"");
    output("`nAloras schaut sich deine Ausrüstung an und meint dann zu dir:`n`n");
    $verteidigung = $session['user']['armordef'] + 2;
    $verteidigungskosten = $session['user']['armordef'] * 200;
    $angriff = $session['user']['weapondmg'] + 2;
    $angriffskosten = $session['user']['weapondmg'] * 200;
    if (strchr($session['user']['weapon'],"Qualitäts")){
        output("`4Deine Waffe kann ich leider nicht mehr verbessern. Das ist eine beinahe perfekte Arbeit.`n");
        if (strchr($session['user']['armor'],"Qualitäts")){
            output("Mit deiner Rüstung schaut's wohl genauso aus. Ich sehe schon, du hast ein Gespür für solche Dinge.");
        }elseif ($session['user']['armordef']==0){
            output("An deiner Rüstung kann ich sogar noch weniger machen. Besorg dir erstmal etwas Richtiges, bevor du nochmal meine Zeit vergeudest!");
        }else{
            output("An deiner Rüstung könnte ich allerdings noch etwas ändern. Danach würde sie `@$verteidigung`4 Rüstungsschutz haben. Allerdings verlange ich für diese Arbeit auch `^$verteidigungskosten Goldstücke`4, um meine Unkosten zu decken.");
            addnav("Rüstung verbessern","forest.php?op=powerruestung");
        }
    }elseif ($session['user']['weapondmg']==0){
        output("`4Hahaha! Was soll das denn sein? Eine Waffe? Na gut, wenn du meinst. Vielleicht lachen sich deine Gegner ja auch zu Boden, aber das kann selbst ich nicht verbessern. Besorg dir erstmal etwas, das man auch \"Waffe\" nennen kann!`n");
        if (strchr($session['user']['armor'],"Qualitäts")){
            output("Mit deiner Rüstung schaut's wohl genau anders aus. Die ist perfekt - da kann ich nichts machen.");
        }elseif ($session['user']['armordef']==0){
            output("Hahaha! Deine Rüstung schaut ja genauso aus!");
        }else{
            output("An deiner Rüstung könnte ich allerdings noch etwas ändern. Danach würde sie `@$verteidigung`4 Rüstungsschutz haben. Allerdings verlange ich für diese Arbeit auch `^$verteidigungskosten Goldstücke`4, um meine Unkosten zu decken.");
            addnav("Rüstung verbessern","forest.php?op=powerruestung");
        }
    }else{
        output("`4Hmm... ja, da könnte ich etwas machen. Mit meiner Bearbeitung würde sie `@$angriff`4 Schaden machen. Dafür verlange ich auch nur `^$angriffskosten Goldstücke`4.`n");
        addnav("Waffe verbessern","forest.php?op=powerwaffe");
        if (strchr($session['user']['armor'],"Qualitäts")){
            output("Mit deiner Rüstung schaut's da jedoch anders aus. Die ist einfach perfekt.");
        }elseif ($session['user']['armordef']==0){
            output("An deiner Rüstung kann ich jedoch nichts machen. Da solltest du dir lieber etwas Besseres besorgen!");
        }else{
            output("An deiner Rüstung kann ich ebenfalls etwas verändern. Danach würde sie `@$verteidigung`4 Rüstungsschutz haben. Allerdings verlange ich für diese Arbeit auch `^$verteidigungskosten Goldstücke`4, um meine Unkosten zu decken.");
            addnav("Rüstung verbessern","forest.php?op=powerruestung");
            $summe = $angriffskosten+$verteidigungskosten;
            $rabatt = $summe * 0.90;
            output("`nWenn du dir beide Sachen von mir machen lässt, geb ich dir beide Arbeiten zusammen etwas billiger. Dann würde es dich nur noch `^$rabatt Goldstücke`4 kosten!");
            addnav("Beides verbessern","forest.php?op=powerboth");

        }
    }
    addnav("Zurück ins Tal","forest.php");

//xxx verbessern
}elseif ($_GET['op']=="powerwaffe"){
    $angriffskosten = $session['user']['weapondmg'] * 200;
    if ($session['user']['gold'] < $angriffskosten){
        output("`7Aloras schaut dich an und brummt dann: \"`4Deine Waffe mach ich gerne, aber was schaust du mich so an? Bevor du nicht `^$angriffskosten Goldstücke`4 hast, mach ich nichts für dich!`7\"");
    }else{
        output("`7Aloras nimmt sich deine Waffe und verschwindet damit in seiner Schmiede. Nach etwa einer Stunde kommt er wieder und präsentiert dir deine neue Waffe, die nun mehr Schaden anrichten kann.");
        $session['user']['gold']-=$angriffskosten;
        $verbesserung = "Qualitäts ".$session['user']['weapon'];
        $session['user']['weapon']= $verbesserung;
        $session['user']['weapondmg']+=2;
        $session['user']['weaponvalue']+=$angriffskosten;
        $session['user']['attack']+=2;
  }
    addnav("Zurück ins Tal","forest.php");

}elseif ($_GET['op']=="powerruestung"){
    $verteidigungskosten = $session['user']['armordef'] * 200;
    if ($session['user']['gold'] < $verteidigungskosten){
        output("`7Aloras schaut dich an und brummt dann: \"`4Deine Rüstung mach ich gerne, aber was schaust du mich so an? Bevor du nicht `^$verteidigungskosten Goldstücke`4 hast, mach ich nichts für dich!`7\"");
    }else{
        output("`7Aloras nimmt sich deine Rüstung und verschwindet damit in seiner Schmiede. Nach etwa einer Stunde kommt er wieder und präsentiert dir deine neue Rüstung, die nun mehr Schutz bietet.");
        $session['user']['gold']-=$verteidigungskosten;
        $verbesserung = "Qualitäts ".$session['user']['armor'];
        $session['user']['armor']= $verbesserung;
        $session['user']['armordef']+=2;
        $session['user']['armorvalue']+=$verteidigungskosten;
        $session['user']['defence']+=2;
  }
  addnav("Zurück ins Tal","forest.php");

}elseif ($_GET['op']=="powerboth"){
    $angriffskosten = $session['user']['weapondmg'] * 200;
  $verteidigungskosten = $session['user']['armordef'] * 200;
  $summe = $angriffskosten+$verteidigungskosten;
  $rabatt = $summe * 0.90;
  if ($session['user']['gold'] < $rabatt){
      output("`7Aloras schaut dich an und brummt dann: \"`4Deine Ausrüstung mach ich gerne, aber was schaust du mich so an? Bevor du nicht `^$rabatt Goldstücke`4 hast, mach ich nichts für dich!`7\"");
  }else{
    output("`7Aloras nimmt sich deine Ausrüstung und verschwindet damit in seiner Schmiede. Nach etwa zwei Stunden kommt er wieder und präsentiert dir sein Werk.");
      $session['user']['gold']-=$rabatt;
      $verbesserung = "Qualitäts ".$session['user']['weapon'];
      $verbesserung2 = "Qualitäts ".$session['user']['armor'];
      $session['user']['weapon']= $verbesserung;
      $session['user']['armor']= $verbesserung2;
      $session['user']['weapondmg']+=2;
      $session['user']['armordef']+=2;
      $session['user']['weaponvalue']+=$angriffskosten;
      $session['user']['armorvalue']+=$verteidigungskosten;
      $session['user']['attack']+=2;
      $session['user']['defence']+=2;
    }
    addnav("Zurück ins Tal","forest.php");


//Höhlensucher
//-->Die Chance das man was bekommt und etwas verliert steht 50:50   3 Gute Fälle und 3 Schlechte
}elseif ($_GET['op']=="forschen"){
    output("`7Du kletterst an den steilen Hängen, um dich in der Gegend genauer umzusehen.");
    $bis = 6;
    if ( $session['user']['gems'] > 0 ) $bis = 5;
    $zufall=e_rand(1,$bis);
  if ($zufall == 1){
      output("`n`n`4Du greifst nach einem Stein, um dich festzuhalten, fällst jedoch 3 Meter tief und verlierst die meisten deiner Lebenspunkte!");
      $session['user']['hitpoints']=1;
  }elseif ($zufall == 2){
      output("`n`n`4Du erreichst das Ende eines Vorsprungs und siehst ein altes Drachenskelett. Beim genaueren Untersuchen der Überbleibsel findest du `%2 Edelsteine`4!");
      $session['user']['gems']+=2;
  }elseif ($zufall == 3){
      output("`n`n`4Du erreichst das Ende eines Vorsprungs und siehst ein altes Drachenskelett. Beim genaueren Untersuchen der Überbleibsel findest du `%einen Edelstein`4!");
      $session['user']['gems']++;
  }elseif ($zufall == 4){
          $wk=e_rand(1,3);
      output("`n`n`4Du kletterst gerade an einer Steilwand, als ein kleiner Babydrache vorbeifliegt und sich an deiner Edelsteinsammlung zu schaffen macht. Das Abwehren des Babydrachens kostet dich ".($wk==1?"einen Waldkampf":"$wk Waldkämpfe").".");
      $session['user']['turns']-=$wk;
  }elseif ($zufall == 5){
      $gold=$session['user']['level']*50;
      output("`n`n`4Du erreichst das Ende eines Vorsprungs und siehst ein altes Drachenskelett. Beim genaueren Untersuchen der Überbleibsel findest du `^$gold Goldstücke`4!");
      $session['user']['gold']+=$gold;
  }elseif ($zufall == 6){
      output("`n`n`4Du kletterst gerade an einer Steilwand, als ein kleiner Babydrache vorbeifliegt und sich an deiner Edelsteinsammlung zu schaffen macht. Du verlierst `%einen Edelstein`4!");
      $session['user']['gems']--;
  }
  addnav("Zurück in den Wald","forest.php?op=leave");

//Drachenhöhle
}elseif ($_GET[op]=="risiko"){
    output("`7Du betrittst eine Höhle und kommst nach einigen Metern in einen großen Raum, gefüllt mit Gold und Edelsteinen.");
    addnav("Zurück ins Tal","forest.php");
    addnav("Weiter gehen","forest.php?op=risiko1");
}elseif ($_GET[op]=="risiko1"){
    $glueck=e_rand(1,7);
    //~15%
    if ($glueck == 5){
        output("`4Als du um die nächste Ecke gehst, steht dir ein riesiger Drache gegenüber, der den Schatz bewacht. Bevor du dich versiehst, landest du auch schon im Reich der Schatten.");
        output("`n`^Du bist tot!");
        output("`n`^Du verlierst all dein Gold und 20% deiner Erfahrung!");
        output("`n`^Du kannst erst morgen wieder weiterkämpfen!");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.80;
        $session['user']['specialinc'] = '';
    addnews("`%".$session[user][name]."`5 wurde beim ersten Versuch die Drachenhöhle zu betreten vom Drachen geröstet.");
        addnav("Tägliche News","news.php");
    }else{
        output("`4Du gehst um eine Ecke und hörst immer deutlicher ein lautes Schnaufen. Du kannst furchtlos weitergehen oder dir ein bisschen vom Schatz nehmen und wieder verschwinden...");
        addnav("Flüchten","forest.php?op=leaverisiko1");
        addnav("Weiter","forest.php?op=risiko2");
    }
}elseif ($_GET[op]=="risiko2"){
    $glueck=e_rand(1,10);
    //~30%
    if ($glueck < 4){
        output("`4Du gehst weiter, bleibst aber an einer Schatztruhe hängen, die darauf mit einem lauten Knall umfällt, so dass das ganze Gold auf den Boden rollt. Dir ist die Truhe eigentlich völlig egal, doch als du dich wieder umdrehst, um den Weg weiterzulaufen, blickst du in die feuerroten Augen einen riesigen Drachens, der den Schatz bewacht. Du versuchst zwar, dich zu schützen und schnell zu verschwinden, doch es bringt alles nichts...");
        output("`n`^Du bist tot!");
        output("`n`^Du verlierst all dein Gold und 15% deiner Erfahrung!");
        output("`n`^Du kannst erst morgen wieder weiterkämpfen!");
        $session[user][alive]=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.85;
        $session['user']['specialinc'] = '';
    addnews("`%".$session[user][name]."`5 wurde beim ersten Versuch in der Drachenhöhle weiterzukommen vom Drachen geröstet.");
        addnav("Tägliche News","news.php");
    }else{
        output("`4Du gehst furchtlos weiter und kommst in eine riesige Kammer mit Schätzen, von denen du immer geträumt hast. Deine Freude schwindet allerdings sofort, als du den riesigen Drachen auf einem Berg von Gold und Edelsteinen schlafen siehst. Du kannst dir lautlos etwas nehmen oder furchtlos weitergehen in der Hoffnung auf noch mehr Reichtümer.");
        addnav("Flüchten","forest.php?op=leaverisiko2");
        addnav("Weiter","forest.php?op=risiko3");
    }
}elseif ($_GET[op]=="risiko3"){
    $glueck=e_rand(1,2);
    //50%
    if ($glueck == 1){
        output("`4Du läufst näher zum Drachen, um die schönsten Exemplare in dieser Schatzkammer zu rauben und steckst dir nebenbei die Taschen voll, doch dir fällt ein Edelstein aus der Tasche und der Drache wacht auf! Du versuchst zu flüchten, doch deine Taschen sind so voll, dass du kaum rennen kannst und dich der Drache schon nach wenigen Metern einholt...");
        output("`n`^Du bist tot!");
        output("`n`^Du verlierst all dein Gold und 12% deiner Erfahrung!");
    $pech=e_rand(1,2);
    if ($pech==1){
            output("`n`^Du wirst das Reich der Schatten heute aus eigener Kraft nicht mehr verlassen können!");
        addnews("`%".$session[user][name]."`5 wurde beim zweiten Versuch in der Drachenhöhle weiterzukommen vom Drachen geröstet.
            Leider gibts heute keine Wiederkehr mehr.");
        $session[user][gravefights]=round($session[user][gravefights]*0.66);
    }else{
            output("`n`^Du kannst erst morgen wieder weiterkämpfen!");
        addnews("`%".$session[user][name]."`5 wurde beim zweiten Versuch in der Drachenhöhle weiterzukommen vom Drachen geröstet.");
    }
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.88;
        $session['user']['specialinc'] = '';
        addnav("Tägliche News","news.php");
    }else{
        output("`4Du hast Glück und der Drache hat dich immer noch nicht bemerkt. Jetzt stehst du nur noch 15 Meter vom Drachen entfernt und erblickst die Schätze, auf denen er liegt. Traust du dich noch weiter?");
        addnav("Flüchten","forest.php?op=leaverisiko3");
        addnav("Weiter","forest.php?op=risiko4");
    }
}elseif ($_GET[op]=="risiko4"){
    $glueck=e_rand(1,4);
    //~75%
    if ($glueck < 4){
        output("`4Du läufst furchtlos zum Drachen und füllst dir die Taschen mit Gold und Edelsteinen. Als du einen besonders großen Edelstein, der dummerweise genau vor dem Maul des Ungetüms liegt, erblickst, vergisst du all deine Angst und versuchst, ihn hochzuheben. Von deinem wilden Einpacken wird jedoch der Drache wach und öffnet die Augen. Du bist so mit dem Edelstein beschäftigt, dass dir die Gefahr erst wieder klar wird, als dich das Feuer des Drachens umgibt und du jämmerlich verbrennst...");
        output("`n`^Du bist tot!");
        output("`n`^Du verlierst all dein Gold!");
        output("`n`^Die Lehren, die du aus diesem Abenteuer gezogen hast, gleichen fast jeden Erfahrungsverlust aus!");
        output("`n`^Du wirst das Reich der Schatten heute aus eigener Kraft nicht mehr verlassen können!");
    $session[user][gravefights]=round($session[user][gravefights]*0.66);
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold'] = 0;
        $session['user']['experience'] *= 0.95;
        $session['user']['specialinc'] = '';
    addnews("`%".$session[user][name]."`5 wurde beim dritten Versuch in der Drachenhöhle weiterzukommen vom Drachen geröstet.
        Leider gibts heute keine Wiederkehr mehr.");
        addnav("Tägliche News","news.php");
    }else{
        $money=e_rand(350,400);
        $gold=$session['user']['level']*$money;
        $gems=e_rand(4,6);
      output("`4Du läufst furchtlos zum Drachen und füllst dir die Taschen mit Gold und Edelsteinen. Als dir klar wird, dass du nicht mehr tragen kannst, entschließt du dich, die Höhle wieder zu verlassen.");
      output("`n`@Du erbeutest `^$gold Goldstücke`@ und `%$gems Edelsteine`@!");
      $session['user']['gold']+=$gold;
      $session['user']['gems']+=$gems;
        addnav("Zurück in den Wald","forest.php?op=leave");
  }

}elseif ($_GET['op']=="leaverisiko1"){
    $gold=$session['user']['level']*50;
    output("`4Aus Angst vor den Gefahren dieser Höhle schnappst du dir `^$gold Goldstücke`4 und verschwindet aus der Höhle.");
    $session['user']['gold']+=$gold;
    addnav("Zurück in den Wald","forest.php?op=leave");

}elseif ($_GET['op']=="leaverisiko2"){
    $gold=$session['user']['level']*150;
    $gems=e_rand(1,2);
    if ($gems == 1) output("`4Aus Angst vor den Gefahren dieser Höhle schnappst du dir schnell `^$gold Goldstücke`4 sowie `%einen Edelstein`4 und beschließt, die Höhle zu verlassen.");
    else output("`4Aus Angst vor den Gefahren dieser Höhle schnappst du dir schnell `^$gold Goldstücke`4 sowie `%zwei Edelsteine`4 und beschließt, die Höhle zu verlassen.");
    $session['user']['gold']+=$gold;
    $session['user']['gems']+=$gems;
    addnav("Zurück in den Wald","forest.php?op=leave");

}elseif ($_GET['op']=="leaverisiko3"){
    $gold=$session['user']['level']*250;
    $gems=e_rand(3,4);
    output("`4Aus Angst vor den Gefahren dieser Höhle schnappst du dir schnell `^$gold Goldstücke`4 sowie `%$gems Edelsteine`4 und beschließt, die Höhle zu verlassen.");
    $session['user']['gold']+=$gold;
    $session['user']['gems']+=$gems;
    addnav("Zurück in den Wald","forest.php?op=leave");

}elseif ($_GET['op']=="dame") {
    output("`7Auf deiner Erkungungstour triffst du in einer Höhle auf eine Echsendame, die dich auch sofort anspricht: \"`4Sei gegrüßt! Ich bin Sylaya und kenne mich mit Drachen wohl so gut aus wie kein anderer. Warum bist du zu mir gekommen?`7\"");
    addnav("Heilung","forest.php?op=heal");
    addnav("Drachenkunde (1 Edelstein)","forest.php?op=kunde");
    addnav("Zurück ins Tal","forest.php");

}elseif ($_GET['op']=="heal"){
    if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']){
        output("`7Du zeigst Sylaya deine Wunden und sie erklärt dir, dass sie sich gelegentlich mit dem Heiler aus dem Wald trifft und Heilmittel austauscht. Wenn du willst, wird sie dir deine Wunden bearbeiten behandeln.");
        //Ausschnitt aus dem healer.php
        $loglev = log($session['user']['level']);
        $cost = ($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10);
        // etwas günstiger ist es hier aber dann doch...
        $cost *= 0.8;
        $cost = round($cost,0);
        addnav("Heiltränke");
        addnav("`^Komplette Heilung`0","forest.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","forest.php?op=buy&pct=$i");
        }
    }
    else {
        output("`7Sylaya schaut dich an und meint grinsend: \"`4Du magst ".($session['user']['sex']?"eine gute Kriegerin":"ein guter Krieger")." sein, aber besonders klug bist du offenbar nicht.`7\"");
    }
    addnav("Zurück","forest.php?op=dame");
    addnav("Zurück ins Tal","forest.php");
}elseif ($_GET['op']=="buy"){
    //Ausschnitt aus dem healer.php
    $loglev = log($session['user']['level']);
    $cost = ($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10);
    // etwas günstiger ist es hier aber dann doch...
    $cost *= 0.8;
    $cost = round($cost,0);
    $newcost = round($_GET['pct']*$cost/100,0);
    if ($session['user']['gold'] >= $newcost){
       $session['user']['gold'] -= $newcost;
     $diff = round(($session['user']['maxhitpoints']-$session['user']['hitpoints'])*$_GET['pct']/100,0);
     $session['user']['hitpoints'] += $diff;
       output("`7Du trinkst den Trank, den dir Sylaya gegeben hat und musst feststellen, dass er im Geschmack besser, aber in der Wirkung wohl genauso ist, wie der des Heilers.");
       output("`n`n`#Du wurdest um $diff Punkte geheilt!");
    }else{
        output("`4Die Echsendame faucht dich an, sie nicht mehr zu belästigen, wenn du sowieso kein Gold hast!");
    }
    addnav("Zurück","forest.php?op=dame");
    addnav("Zurück ins Tal","forest.php");

}elseif ($_GET['op']=="kunde"){
    if ($session['user']['gems']==0){
        output("`4Die Echsendame faucht dich an, sie nicht mehr zu belästigen, wenn du sowieso keine Edelsteine hast!");
        addnav("Zurück ins Tal","forest.php");
    }else{
        output("`7Du gibst Sylaya einen Edelstein und sie beginnt, dir mehr über Drachen beizubringen. Nach Stunden des Zuhörens verabschiedest du dich jedoch wieder von ihr, da dich andere Pflichten rufen.");
        $rand=e_rand(1,6);
        if ($rand==1){
            output("`n`^Du fühlst dich erholt!");
            $lp = $session['user']['level'];
            $session['user']['hitpoints'] += $lp;
        }elseif ($rand==2){
            output("`n`^Du fühlst dich charmant!");
            $session['user']['charm']++;
        }elseif ($rand==3){
            output("`n`^Du fühlst dich fit!");
            $session['user']['turns']++;
        }elseif ($rand==4){
            output("`n`^Sie bedankt sich dafür, dass du solange zugehört hast und gibt dir zum Zeichen des Dankes deinen Edelstein wieder.");
            $session['user']['gems']++;
        }elseif ($rand==5){
            output("`n`^Das lange Sitzen auf dem kalten Höhlenboden war wohl nicht das Beste für dich, deine Gesundheit hat etwas gelitten!");
            $lp = $session['user']['level'];
            if ($session['user']['hitpoints'] > $lp){
                $session['user']['hitpoints'] -= $lp;
            }else {
                $session['user']['hitpoints'] = 1;
            }
        }elseif ($rand==6){
            output("`n`^Sie bedankt sich dafür, dass du so lange zugehört hast.");
            //Hier passiert nix ... auser das halt der rausgeschmissene Edelstein umsonst war :P
        }
        $session['user']['gems']--;
    }
    addnav("Zurück ins Tal","forest.php");
}
else {
    //Einleitungstext
    if ($session[user][turns]==0){
    output("`7Angekommen beim Drachental, stellst Du fest das Du schon viel zu müde bist um noch weiterzugehen,
        Es ist besser, wenn Du jetzt ins Dorf zurückgehst, bevor Dir in Deinem Zustand noch schlimmes wiederfährt.");
    addnav("Zurück in den Wald","forest.php?op=leave2");
    }else{
        output("`7Du hast den Wald durchquert und kommst auf der anderen Seite wieder raus. Du siehst eine lange ewige Einöde vor
            dir liegen, gehst aber weiter und hast nach Stunden des Laufens die Orientierung verloren und Durst, wie du ihn dir nie
            vorstellen konntest. Zuerst meinst du, deine Sinne spielen dir einen Streich, aber dann merkst du schnell, dass du auf zwei
            riesige Felswände zuläufst, zwischen denen ein Tal liegt. In der Hoffnung auf Wasser sammelst du nochmal deine letzten Kräfte und
            kommst schließlich im Tal an... kann dies das sagenumwobene Drachental sein?");
        output("`n`n`7Du nimmst gierig Wasser aus der nächstbesten Quelle zu dir und erkennst nun auch andere Krieger, die sich im
            Tal aufhalten und ihre Erfahrungen austauschen. Aus manchen Höhlen, die über die Hänge verteilt sind, hörst du Geräusche,
            aus denen du schließt, dass dort vermutlich gearbeitet wird. Es ist wahrhaftig das Drachental...");

       //Hauptmenü
        addnav("Zurück in den Wald","forest.php?op=leave");
        addnav("Tal");
        addnav("Drachenschmiede","forest.php?op=schmiede");
        addnav("Höhlen erforschen","forest.php?op=forschen");
        addnav("Drachenhöhle","forest.php?op=risiko");
        addnav("Mit anderen reden","forest.php?op=talk");
        addnav("Drachendame","forest.php?op=dame");

        if ($session['user']['level']>=15){
            addnav("G?`@Den Grünen Drachen suchen","forest.php?op=dragon");
        }
   }
}

?>


