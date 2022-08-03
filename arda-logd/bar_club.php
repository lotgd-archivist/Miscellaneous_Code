<?php

/*
  --- Lazalantins Nachtclub (Addon zum Polierten Panzer) ---

  Version 1.0

  Script by Lazalantin

  4.1.2006

  Email: dark-pilzkopf@web.de

  http://www.lemuria-legend.de/
  LoGD 0.9.7+jt ext (GER) Mystical Lemuria v1.0


  Erfordert bar.php (by Lazalantin)

*/


/*
  --- Einbauanleitung ---

  SQL

  ALTER TABLE `accounts` ADD `girl` int(11) NOT NULL default '0';


  newday.php

  finde:

  $session['user']['seenmaster'] = 0;

  füge danach ein:

  $session['user']['girl'] = 0;

  save & closed


  und noch in den selben ordner packen, wo die bar.php drin is

*/


require_once "common.php";
checkday();
addcommentary();


// Kosten für die Mädchen

$v = 5;    // Edelsteinkosten für Violet
$l = 3;    // Edelsteinkosten für Libra
$m1 = 1;   // Edelsteinkosten für Maria
$m2 =3000; // Goldkosten für Mia
$t = 200;  // Goldkosten für Tusnelda



page_header("Lazalantins Nachtclub");

$h = e_rand(0,10);

if ($_GET[op]==""){
    if ($session[user][girl]>0){
        output("`TDu warst heute schon einmal in Lazalantins Nachtclub und ein freundlicher Tüsteher weist dir den Weg nach draußen.");
        addnav("Zurück","bar.php");
    }else{
        output("`TDu betrittst den kleinen Nachtclub von Lazalantin und siehst dich ersteinmal um.
                  Hier stehen überall edle Tische rum, mit Stangen an denen schöne, halbnackte Mädchen tanzen.
                  Um die Tische herum sind Stühle auf denen Gäste sitzen, die Getränke aus dem
                  Polierten Panzer trinken und den Mädchen beim tanzen zusehn. Weiter hinten im Raum
                  sind kleine Kammern und Zimmer, in denen Lazalantins Edelhuren liegen und auf
                  Gäste warten. Bewaffnete Türsteher sorgen für das Wohl der Mädchen und eine sanfte
                  Musik spielt im Hintergrund durch eine Band hübscher, halbnackter Frauen.");
        output("`n`n `TAn einem Schild, das hinten bei den Zimmern hängt, siehst du, dass momentan $h Mädchen frei sind, mit denen du dich vergnügen kannst.");
        output("`n`n `TEinige Gäste reden:`n`n");
        viewcommentary("bar_club","Mit anderen Gästen in dem Nachtclub reden:",15);

        addnav("Nachtclub");
        addnav("Sich an einen Tisch setzen","bar_club.php?op=tisch");
        if ($h>0){
        addnav("Ein Mädchen nehmen","bar_club.php?op=edel");
        }

        addnav("Wege");
        addnav("Zurück","bar.php");

    }

}elseif ($_GET[op]=="tisch"){
    if ($session[user][charm]<200){
        output("`TDu setzt dich an einen Tisch und bestellst dir etwas zu trinken, um den tanzenden Mädchen zugucken zu können.
                  Du beobachtest einige Mädchen eine Zeit lang, wie sie um die Stangen tanzen, als dich eine ansieht
                  und dir zuzwinkert.");
        addnav("Hingehen","bar_club.php?op=1");
        addnav("Zurück","bar_club.php");

    }elseif ($session[user][charm]>199 && $session[user][charm]<250){
        output("`TDu setzt dich an einen Tisch und bestellst dir etwas zu trinken, um den tanzenden Mädchen zugucken zu können.
                  Du beobachtest einige Mädchen eine Zeit lang, wie sie um die Stangen tanzen, als dich eine ansieht
                  und dir zuzwinkert. Dann öffnet sie langsam ihren Rock und kommt auf dich zu getanzt.");
        addnav("Auf sie zugehen","bar_club.php?op=2");
        addnav("Nicht beachten","bar_club.php");

    }elseif ($session[user][charm]>249 && $session[user][charm]<350){
        output("`TDu setzt dich an einen Tisch und bestellst dir etwas zu trinken, um den tanzenden Mädchen zugucken zu können.
                  Du beobachtest einige Mädchen eine Zeit lang, wie sie um die Stangen tanzen, als dich eine ansieht
                  und dir zuzwinkert. Sie guckt dich eine Weile lang an und öffnet dann langsam ihren Rock vor dir.
                  Dann kommt sie auf dich zu und entkleidet sich immer mehr vor dir. Schließlich steht sie vor dir
                  und gibt dir zu bedeuten, dass du ihr den Rock herunterziehen sollst.");
        addnav("Den Rock runterziehen","bar_club.php?op=3");
        addnav("Nichts machen","bar_club.php");

    }else{
        output("`TDu setzt dich an einen Tisch und bestellst dir etwas zu trinken, um den tanzenden Mädchen zugucken zu können.
                  Du beobachtest einige Mädchen eine Zeit lang, wie sie um die Stangen tanzen, als dich eine ansieht
                  und dir zuzwinkert. Sie guckt dich eine Weile lang an und öffnet dann langsam ihren Rock vor dir.
                  Dann kommt sie auf dich zu und entkleidet sich immer mehr vor dir. Sie zwinkert dir zu und deutet
                  dann an, das sie gerne mit dir in einen Hinterraum verschwinden würde...");
       addnav("Mit ihr gehen","bar_club.php?op=4");
       addnav("Sitzen bleiben","bar_club.php?op=33");

    }

}elseif ($_GET[op]=="edel"){
    output("`TDu gehst gleich nach hinten, zu den Zimmern, weil du eine von Lazalantins Edelmädchen nehmen willst.
              Einige von den Türen sind offen und die Mädchen hinter ihnen warten auf Kundschaft. Türsteher
              passen auf, das keiner rein geht, der vorher nicht bezahlt hat. An einem Schild stehen die
              Namen der Mädchen und ihre Preise. Mit wem würdest du gerne mal etwas Zeit alleine verbringen?");
    output("`n`n");
    output("<table border=2 cellpadding=1 cellspacing=1 bgcolor='#000000'>",true);
        output("<tr class='trhead'><td><b>Mädchen</b></td><td><b>Preis</b></td>",true);

        output("<tr class='trhead'><td><b>`%Violet</b></td><td><b>$v Edelstein(e)</b></td>",true);
        output("<tr class='trhead'><td><b>`\$Libra</b></td><td><b>$l Edelstein(e)</b></td>",true);
        output("<tr class='trhead'><td><b>`9Maria</b></td><td><b>$m1 Edelstein(e)</b></td>",true);
        output("<tr class='trhead'><td><b>`^Mia</b></td><td><b>$m2 Gold</b></td>",true);
        output("<tr class='trhead'><td><b>`@Tusnelda</b></td><td><b>$t Gold</b></td>",true);

    addnav("Mädchen");
    addnav("Violet ($v Edelsteine)","bar_club.php?op=h1");
    addnav("Libra ($l Edelsteine)","bar_club.php?op=h2");
    addnav("Maria ($m1 Edelstein)","bar_club.php?op=h3");
    addnav("Mia ($m2 Gold)","bar_club.php?op=h4");
    addnav("Tusnelda ($t Gold)","bar_club.php?op=h5");

    addnav("Wege");
    addnav("Zurück","bar_club.php");

}elseif ($_GET[op]=="1"){
    output("`TDu stehst auf um zu dem Mädchen an der Stange zu gehen.");
    output("`n`n");
    $session[user][girl]=1;
    switch (e_rand(1,5)){
        case 1:
        case 2:
        case 3:
        output("`TAls du dann vor ihr stehst, grinst sie dich vergnügt an. Dann öffnet sie langsam ihren
                  Rock und deine Erregung steigt ins unermessliche. Doch dann pfeift einer der Türsteher
                  und sie schließt ihren Rock schnell wieder. Aber trotzdem hast du etwas von ihr gesehen
                  und fühlst dich jetzt gut. Etwas aufgeheitert gehst du wieder.");
        $session[user][charm]++;
        break;

        case 4:
        case 5:
        output("`TAls du dann vor ihr stehst und voll erregt bist, weil du denkst, jetzt zieht sie sich
                  vor dir aus, grinst sie dich nur entschuldigend an und tanzt dann normal weiter. Etwas
                  beschämt ziehst du dich zurück und gehst.");
        $session[user][charm]--;
        break;

   }
   addnav("Zurück","bar.php");



}elseif ($_GET[op]=="2"){
    output("`TDu stehst auf um dem Mädchen entgegen zugehen.");
    output("`n`n");
    $session[user][girl]=1;
    switch (e_rand(1,5)){
        case 1:
        case 2:
        case 3:
        output("`TAls du dann bei ihr ankommst, grinst sie dich vergnügt an. Dann öffnet sie langsam ihren
                  Rock ganz vor dir und deine Erregung steigt ins unermessliche. Du wirfst einen Blick auf sie
                  und sie schließt ihren Rock schnell wieder. Aber trotzdem hast du etwas von ihr gesehen
                  und fühlst dich jetzt gut. Etwas aufgeheitert gehst du wieder.");
        $session[user][charm]++;
        break;

        case 4:
        case 5:
        output("`TAls du dann vor ihr stehst und voll erregt bist, weil du denkst, jetzt zieht sie sich
                  vor dir aus, bemerkt sie dich gar nicht sondern tanzt zu einem Mann, der ganz in der
                  Nähe sitzt. Verlegen und mit rotem Kopf verschwindest du.");
        $session[user][charm]--;
        break;

   }
   addnav("Zurück","bar.php");


}elseif ($_GET[op]=="3"){
    output("`TDu grinst, legst die Hände an ihren Rock und ziehst ihn mit einem Zug herunter.");
    output("`n`n");
    $session[user][girl]=1;
    switch (e_rand(1,5)){
        case 1:
        case 2:
        case 3:
        output("`TSie grinst vergnügt und setzt sich dann, nackt wie sie ist, auf deinen Schoß.
                 Deine Erregung steigt und sie streichelt dir sanft übers Gesicht. Viele
                 neidische Blicke anderer Gäste werden dir zugeworfen und du fühlst dich so
                 richtig gut. Als sie dann wieder aufsteht und zurück an die Stange geht,
                 könntest du jetzt richtig etwas Auslauf gebrauchen.");
        $session[user][charm]++;
        $session[user][turns]++;
        break;

        case 4:
        case 5:
        output("`TDoch sie zieht ihn schnell wieder hoch, holt aus und verpasst dir eine
                  schallende Backpfeife. Du weißt nicht wieso, doch dann packen dich auch schon
                  zwei Türsteher und zerren dich nach draußen. Alle im Club lachen dich aus!");
        $session[user][charm]--;
        if ($session[user][turns]>2){
            $session[user][turns]-=2;
        }else{
            $session[user][turns]=0;
        }
        break;

   }
   addnav("Zurück","bar.php");


}elseif ($_GET[op]=="4"){
    output("`TDu grinst verlegen, dann stehst du auf und verschwindest mit ihr in einem Hinterraum.");
    output("`TIhr legt euch auf ein großes Bett und sie lässt ihre Reize spielen. Es dauert sehr
              lange bis ihr dann endlich fertig seid, und es kommt dir vor, als ob Stunden vergangen wären,
              aber du fühlst dich wie im siebten Himmel. Danach fühlst du dich so gestärkt,
              dass du jetzt einen Kampf gebrauchen könntest.");
    $session[user][charm]++;
    $session[user][turns]++;
    $session[user][girl]=1;
    addnav("Zurück","bar.php");


}elseif ($_GET[op]=="33"){
    output("`TDu machst dem Mädchen klar, dass du nicht mit ihr ins Hinterzimmer verschwinden
              willst und sie verzieht ihren kleinen Schmollmund. Vielleicht war es doch nicht
              richtig sie abblitzen zu lassen, es hätte für dich eine kostenlose Nacht mit einem
              von Lazalantins besten Mädchen gegeben...");
    addnav("Zurück","bar_club.php");


}elseif ($_GET[op]=="h1"){
    if ($session[user][gems]<$v){
        output("`TDu möchtest eine Nacht mit diesem schönen Mädchen verbringen, aber ohne Geld keine Ware.");
        addnav("Zurück","bar_club.php");
    }else{
        output("`TDu sagst einem Türsteher, dass du gerne eine Nacht mit Violett wünschst und gibst ihm sein Geld.
                  Dann schließt er die Tür zu ihrem Zimmer auf und sie liegt dort, nackt, auf einem großen Bett,
                  während du zu ihr gehst... Als ihr nach langer Zeit endlich fertig seid, fühlst du dich wie
                  im siebten Himmel. Das war das beste Mädchen, das du je durchgenommen hast.");
                  switch (e_rand(1,5)){
                      case 1:
                      case 2:
                      case 3:
                      case 4:
                      output("`TDu fühlst dich einfach gut und könntest jetzt ein paar Monster mehr erschlagen!");
                      $session[user][turns]+=5;
                      $session[user][charm]++;
                      break;

                      case 5:
                      output("`TDoch irgendwie findest du dich nicht so besonders... Du hast dir eine `\$Geschlechtskrankheit `Tzugezogen!");
                      $session['bufflist']['555'] = array("name"=>"`\$Geschlechtskrankheit","rounds"=>60,"wearoff"=>"Du fühlst dich wieder etwas gesünder.","atkmod"=>0.6,"roundmsg"=>"Du bist krank!","activate"=>"offense");
                      $session[user][charm]--;
                      break;
                  }
        $session[user][gems]-=$v;
        $session[user][girl]=1;
        addnav("Zurück","bar.php");

    }


}elseif ($_GET[op]=="h2"){
    if ($session[user][gems]<$l){
        output("`TDu möchtest eine Nacht mit diesem schönen Mädchen verbringen, aber ohne Geld keine Ware.");
        addnav("Zurück","bar_club.php");
    }else{
        output("`TDu sagst einem Türsteher, dass du gerne eine Nacht mit Libra wünschst und gibst ihm sein Geld.
                  Dann schließt er die Tür zu ihrem Zimmer auf und sie liegt dort, nackt, auf einem großen Bett,
                  während du zu ihr gehst... Als ihr nach langer Zeit endlich fertig seid, fühlst du dich wie
                  im siebten Himmel. Das war das beste Mädchen, das du je durchgenommen hast.");
                  switch (e_rand(1,5)){
                      case 1:
                      case 2:
                      case 3:
                      case 4:
                      output("`TDu fühlst dich einfach gut und könntest jetzt ein paar Monster mehr erschlagen!");
                      $session[user][turns]+=3;
                      $session[user][charm]++;
                      break;

                      case 5:
                      output("`TDoch irgendwie findest du dich nicht so besonders... Du hast dir eine `\$Geschlechtskrankheit `Tzugezogen!");
                      $session['bufflist']['555'] = array("name"=>"`\$Geschlechtskrankheit","rounds"=>60,"wearoff"=>"Du fühlst dich wieder etwas gesünder.","atkmod"=>0.6,"roundmsg"=>"Du bist krank!","activate"=>"offense");
                      $session[user][charm]--;
                      break;
                  }
        $session[user][gems]-=$l;
        $session[user][girl]=1;
        addnav("Zurück","bar.php");

    }


}elseif ($_GET[op]=="h3"){
    if ($session[user][gems]<$m1){
        output("`TDu möchtest eine Nacht mit diesem schönen Mädchen verbringen, aber ohne Geld keine Ware.");
        addnav("Zurück","bar_club.php");
    }else{
        output("`TDu sagst einem Türsteher, dass du gerne eine Nacht mit Maria wünschst und gibst ihm sein Geld.
                  Dann schließt er die Tür zu ihrem Zimmer auf und sie liegt dort, nackt, auf einem großen Bett,
                  während du zu ihr gehst... Als ihr nach langer Zeit endlich fertig seid, fühlst du dich wie
                  im siebten Himmel. Das war das beste Mädchen, das du je durchgenommen hast.");
                  switch (e_rand(1,5)){
                      case 1:
                      case 2:
                      case 3:
                      case 4:
                      output("`TDu fühlst dich einfach gut und könntest jetzt ein paar Monster mehr erschlagen!");
                      $session[user][turns]+=2;
                      $session[user][charm]++;
                      break;

                      case 5:
                      output("`TDoch irgendwie findest du dich nicht so besonders... Du hast dir eine `\$Geschlechtskrankheit `Tzugezogen!");
                      $session['bufflist']['555'] = array("name"=>"`\$Geschlechtskrankheit","rounds"=>60,"wearoff"=>"Du fühlst dich wieder etwas gesünder.","atkmod"=>0.6,"roundmsg"=>"Du bist krank!","activate"=>"offense");
                      $session[user][charm]--;
                      break;
                  }
        $session[user][gems]-=$m1;
        $session[user][girl]=1;
        addnav("Zurück","bar.php");

    }


}elseif ($_GET[op]=="h4"){
    if ($session[user][gold]<$m2){
        output("`TDu möchtest eine Nacht mit diesem schönen Mädchen verbringen, aber ohne Geld keine Ware.");
        addnav("Zurück","bar_club.php");
    }else{
        output("`TDu sagst einem Türsteher, dass du gerne eine Nacht mit Mia wünschst und gibst ihm sein Geld.
                  Dann schließt er die Tür zu ihrem Zimmer auf und sie liegt dort, nackt, auf einem großen Bett,
                  während du zu ihr gehst... Als ihr nach langer Zeit endlich fertig seid, fühlst du dich wie
                  im siebten Himmel. Das war das beste Mädchen, das du je durchgenommen hast.");
                  switch (e_rand(1,5)){
                      case 1:
                      case 2:
                      case 3:
                      case 4:
                      output("`TDu fühlst dich einfach gut und könntest jetzt ein paar Monster mehr erschlagen!");
                      $session[user][turns]+=3;
                      $session[user][charm]++;
                      break;

                      case 5:
                      output("`TDoch irgendwie findest du dich nicht so besonders... Du hast dir eine `\$Geschlechtskrankheit `Tzugezogen!");
                      $session['bufflist']['555'] = array("name"=>"`\$Geschlechtskrankheit","rounds"=>60,"wearoff"=>"Du fühlst dich wieder etwas gesünder.","atkmod"=>0.5,"roundmsg"=>"Du bist krank!","activate"=>"offense");
                      $session[user][charm]--;
                      break;
                  }
        $session[user][gold]-=$m2;
        $session[user][girl]=1;
        addnav("Zurück","bar.php");

    }


}elseif ($_GET[op]=="h5"){
    if ($session[user][gold]<$t){
        output("`TDu möchtest eine Nacht mit diesem schönen Mädchen verbringen, aber ohne Geld keine Ware.");
        addnav("Zurück","bar_club.php");
    }else{
        output("`TDu sagst einem Türsteher, dass du gerne eine Nacht mit Tusnelda wünschst und gibst ihm sein Geld.
                  Dann schließt er die Tür zu ihrem Zimmer auf und sie liegt dort, nackt, und voll behaart am ganzen Körper
                  auf einem großen Bett, während du zu ihr gehst... Als ihr nach langer Zeit endlich fertig seid,
                  fühlst du dich irgendwie voll ätzend. Die Nacht mit einem Mädchen hattest du dir anders vorgestellt,
                  das war ja fast wie mit einem Mann.");
                  switch (e_rand(1,10)){
                      case 1:
                      case 2:
                      case 3:
                      case 4:
                      output("`TDu fühlst dich einfach zwar scheiße aber du könntest jetzt ein paar Monster mehr erschlagen!");
                      $session[user][turns]++;
                      break;

                      case 5:
                      case 6:
                      case 7:
                      case 8:
                      case 9:
                      case 10:
                      output("`TWie erwartet fühlst du dich nicht so besonders... Du hast dir eine `\$Geschlechtskrankheit `Tzugezogen!");
                      $session['bufflist']['555'] = array("name"=>"`\$Geschlechtskrankheit","rounds"=>80,"wearoff"=>"Du fühlst dich wieder etwas gesünder.","atkmod"=>0.3,"roundmsg"=>"Du bist krank!","activate"=>"offense");
                      $session[user][charm]--;
                      break;
                  }
        $session[user][gold]-=$t;
        $session[user][girl]=1;
        addnav("Zurück","bar.php");

    }

}

page_footer();
?> 