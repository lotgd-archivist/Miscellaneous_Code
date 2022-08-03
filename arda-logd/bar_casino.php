<?php

/*
  --- Lazalantins Casino (Addon zum Polierten Panzer) ---

  Version 1.0

  Script by Lazalantin

  3.1.2006

  Email: dark-pilzkopf@web.de

  http://www.lemuria-legend.de/
  LoGD 0.9.7+jt ext (GER) Mystical Lemuria v1.0


  Erfordert bar.php (by Lazalantin)

*/

/*
  --- Einbauanleitung ---

  SQL

  ALTER TABLE `accounts` ADD `einsatz` int(11) NOT NULL default '10';


  newday.php

  finde:

  $session['user']['seenmaster'] = 0;

  füge danach ein:

  $session['user']['einsatz'] = 10;

  save & closed


  und noch in den selben ordner packen, wo die bar.php drin is

*/

require_once "common.php";
checkday();
addcommentary();


$erg = e_rand(1,100);  // Einstellung für das Schildkrötenrennen

$j1 = getsetting("casinogold",100);
$j2 = getsetting("casinoes", 2);


page_header("Lazalantins Casino");

if ($_GET[op]==""){
    if ($session[user][einsatz]<1){
        output("`TDu hast heute schon lange genug gespielt in Lazalantins Casino. Ein Türsteher weist dir freundlich den Weg zurück.");
        addnav("Zurück","bar.php");
    }else{
        output("`TDu betrittst das kleine, illegale Casino von Lazalantins Bar und siehst dich ersteinmal um.
                  Überall sind Spieltische und überall wuseln Menschen und andere Rassen durch die Gegend.
                  Aber es gibt auch eine Menge Aufpasser, die schwer bewaffnet sind und scharfe Augen haben.
                  Hier gibt es alle möglichen Arten von Glücksspielen.");
        if ($session[user][gold]<1){ output("`TDu hast zwar kein Geld dabei, aber mal gucken kann man ja immer.");}
        output("`TGanz hinten im Raum und umringt von etlichen Zuschauern und Zockern befindet sich das \"Schildkrötenrennen\", bei dem drei Schildkröten in einem Rennen gegeneinander antreten.");
        output("`T`n`n An der Wand steht eine Tafel mit den momentanen Jackpots. Der `^Goldjackpot `Tbeträgt `b`^ $j1 `b`TGold und der `^Edelsteinjackpot `Tbeträgt `^`b $j2 `b`TEdelsteine.");
        output("`T `n`n Unterhalte dich mit anderen Casinogästen:`n");
        viewcommentary("barcasino","Unterhalte dich mit Casinogästen:",15);

        // Spiele
        addnav("Spiele");
        addnav("Roulette","bar_casino.php?op=rou");
        addnav("Wettspiel","bar_casino.php?op=wett");
        addnav("Goldspiel","bar_casino.php?op=g");
        addnav("Edelsteinspiel","bar_casino.php?op=e");

        addnav("Sonderspiele");
        addnav("Schildkrötenrennen","bar_casino.php?op=krot");

        addnav("Wege");
        addnav("Zurück","bar.php");
   }

}elseif ($_GET[op]=="rou"){
    output("`TDu gesellst dich an einen Roulettetisch, an dem gerade eine neue Runde startet. Auf was willst du setzen?");

    addnav("Setzen");
    addnav("Auf rot");
    addnav("100 Gold","bar_casino.php?op=r1");
    addnav("500 Gold","bar_casino.php?op=r2");
    addnav("1000 Gold","bar_casino.php?op=r3");
    addnav("Auf schwarz");
    addnav("100 Gold","bar_casino.php?op=r4");
    addnav("500 Gold","bar_casino.php?op=r5");
    addnav("1000 Gold","bar_casino.php?op=r6");

    addnav("Wege");
    addnav("Zurück","bar_casino.php");

}elseif ($_GET[op]=="r1"){
    if ($session[user][gold]<100){
        output("`TKein Geld, kein Spiel!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 100 Gold auf rot. Die Kugel kommt in die Trommel...");
        $session[user][einsatz]--;
        switch (e_rand(1,5)){
            case 1:
            case 2:
            case 3:
            output("`n`n`T... und stoppt genau bei einer roten Fläche! Du gewinnst deinen Einsatz zurück!");
            $session[user][gold]+=100;
            break;

            case 4:
            case 5:
            output("`n`n`T... doch die Kugel stoppt auf einer schwarzen Fläche! Du verlierst deinen Einsatz!");
            $session[user][gold]-=100;
            break;
        }
        addnav("Zurück","bar_casino.php");
    }



}elseif ($_GET[op]=="r2"){
    if ($session[user][gold]<500){
        output("`TKein Geld, kein Spiel!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 500 Gold auf rot. Die Kugel kommt in die Trommel...");
        $session[user][einsatz]--;
        switch (e_rand(1,5)){
            case 1:
            case 2:
            case 3:
            output("`n`n`T... und stoppt genau bei einer roten Fläche! Du gewinnst deinen Einsatz zurück!");
            $session[user][gold]+=500;
            break;

            case 4:
            case 5:
            output("`n`n`T... doch die Kugel stoppt auf einer schwarzen Fläche! Du verlierst deinen Einsatz!");
            $session[user][gold]-=500;
            break;
        }
        addnav("Zurück","bar_casino.php");
    }





}elseif ($_GET[op]=="r3"){
    if ($session[user][gold]<1000){
        output("`TKein Geld, kein Spiel!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 1000 Gold auf rot. Die Kugel kommt in die Trommel...");
        $session[user][einsatz]--;
        switch (e_rand(1,5)){
            case 1:
            case 2:
            case 3:
            output("`n`n`T... und stoppt genau bei einer roten Fläche! Du gewinnst deinen Einsatz zurück!");
            $session[user][gold]+=1000;
            break;

            case 4:
            case 5:
            output("`n`n`T... doch die Kugel stoppt auf einer schwarzen Fläche! Du verlierst deinen Einsatz!");
            $session[user][gold]-=1000;
            break;
        }
        addnav("Zurück","bar_casino.php");
    }


}elseif ($_GET[op]=="r4"){
    if ($session[user][gold]<100){
        output("`TKein Geld, kein Spiel!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 100 Gold auf schwarz. Die Kugel kommt in die Trommel...");
        $session[user][einsatz]--;
        switch (e_rand(1,5)){
            case 1:
            case 2:
            case 3:
            output("`n`n`T... und stoppt genau bei einer roten Fläche! Du gewinnst deinen Einsatz zurück!");
            $session[user][gold]-=100;
            break;

            case 4:
            case 5:
            output("`n`n`T... doch die Kugel stoppt auf einer schwarzen Fläche! Du verlierst deinen Einsatz!");
            $session[user][gold]+=100;
            break;
        }
        addnav("Zurück","bar_casino.php");
    }


}elseif ($_GET[op]=="r5"){
    if ($session[user][gold]<500){
        output("`TKein Geld, kein Spiel!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 500 Gold auf schwarz. Die Kugel kommt in die Trommel...");
        $session[user][einsatz]--;
        switch (e_rand(1,5)){
            case 1:
            case 2:
            case 3:
            output("`n`n`T... und stoppt genau bei einer roten Fläche! Du gewinnst deinen Einsatz zurück!");
            $session[user][gold]-=500;
            break;

            case 4:
            case 5:
            output("`n`n`T... doch die Kugel stoppt auf einer schwarzen Fläche! Du verlierst deinen Einsatz!");
            $session[user][gold]+=500;
            break;
        }
        addnav("Zurück","bar_casino.php");
    }


}elseif ($_GET[op]=="r6"){
    if ($session[user][gold]<1000){
        output("`TKein Geld, kein Spiel!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 1000 Gold auf schwarz. Die Kugel kommt in die Trommel...");
        $session[user][einsatz]--;
        switch (e_rand(1,5)){
            case 1:
            case 2:
            case 3:
            output("`n`n`T... und stoppt genau bei einer roten Fläche! Du gewinnst deinen Einsatz zurück!");
            $session[user][gold]-=1000;
            break;

            case 4:
            case 5:
            output("`n`n`T... doch die Kugel stoppt auf einer schwarzen Fläche! Du verlierst deinen Einsatz!");
            $session[user][gold]+=1000;
            break;
        }
        addnav("Zurück","bar_casino.php");
    }


}elseif ($_GET[op]=="wett"){
    output("`T Du machst dich auf zu einem Tisch, wo sie das Wettspiel spielen und willst vielleicht eine Runde mitspielen. Hier wird auf eine Zahl gewettet (zwischen eins und zehn) und der Wetteinsatz sind Gold oder Edelsteine. Bei Gewinn gibts doppelt zurück, bei Verlust is der Einsatz futsch.");
    addnav("Einsatz");
    addnav("1000 Gold","bar_casino.php?op=w1");
    addnav("5000 Gold","bar_casino.php?op=w2");
    addnav("2 Edelsteine","bar_casino.php?op=w3");
    addnav("5 Edelsteine","bar_casino.php?op=w4");

    addnav("Wege");
    addnav("Zurück","bar_casino.php");


}elseif ($_GET[op]=="w1"){
    if ($session[user][gold]<1000){
        output("`TKein Geld, keine Wette!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 1000 Gold ein. Wähle eine Zahl, auf die du wetten willst!");
        addnav("Zahl");
        addnav("1","bar_casino.php?op=we1");
        addnav("2","bar_casino.php?op=we1");
        addnav("3","bar_casino.php?op=we1");
        addnav("4","bar_casino.php?op=we1");
        addnav("5","bar_casino.php?op=we1");
        addnav("6","bar_casino.php?op=we1");
        addnav("7","bar_casino.php?op=we1");
        addnav("8","bar_casino.php?op=we1");
        addnav("9","bar_casino.php?op=we1");
        addnav("10","bar_casino.php?op=we1");
    }

}elseif ($_GET[op]=="w2"){
    if ($session[user][gold]<5000){
        output("`TKein Geld, keine Wette!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 5000 Gold ein. Wähle eine Zahl, auf die du wetten willst!");
        addnav("Zahl");
        addnav("1","bar_casino.php?op=we2");
        addnav("2","bar_casino.php?op=we2");
        addnav("3","bar_casino.php?op=we2");
        addnav("4","bar_casino.php?op=we2");
        addnav("5","bar_casino.php?op=we2");
        addnav("6","bar_casino.php?op=we2");
        addnav("7","bar_casino.php?op=we2");
        addnav("8","bar_casino.php?op=we2");
        addnav("9","bar_casino.php?op=we2");
        addnav("10","bar_casino.php?op=we2");
    }


}elseif ($_GET[op]=="w3"){
    if ($session[user][gems]<2){
        output("`TKeine Edelsteine, keine Wette!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 2 Edelsteine ein. Wähle eine Zahl, auf die du wetten willst!");
        addnav("Zahl");
        addnav("1","bar_casino.php?op=we3");
        addnav("2","bar_casino.php?op=we3");
        addnav("3","bar_casino.php?op=we3");
        addnav("4","bar_casino.php?op=we3");
        addnav("5","bar_casino.php?op=we3");
        addnav("6","bar_casino.php?op=we3");
        addnav("7","bar_casino.php?op=we3");
        addnav("8","bar_casino.php?op=we3");
        addnav("9","bar_casino.php?op=we3");
        addnav("10","bar_casino.php?op=we3");
    }


}elseif ($_GET[op]=="w4"){
    if ($session[user][gems]<5){
        output("`TKeine Edelsteine, keine Wette!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 5 Edelsteine ein. Wähle eine Zahl, auf die du wetten willst!");
        addnav("Zahl");
        addnav("1","bar_casino.php?op=we4");
        addnav("2","bar_casino.php?op=we4");
        addnav("3","bar_casino.php?op=we4");
        addnav("4","bar_casino.php?op=we4");
        addnav("5","bar_casino.php?op=we4");
        addnav("6","bar_casino.php?op=we4");
        addnav("7","bar_casino.php?op=we4");
        addnav("8","bar_casino.php?op=we4");
        addnav("9","bar_casino.php?op=we4");
        addnav("10","bar_casino.php?op=we4");
    }


}elseif ($_GET[op]=="we1"){
    output("`TOkay... du hast einen Einsatz gemacht und auf eine Zahl gewettet, jetzt kommt die Auslosung... `n`n Die Zahl wird gezogen und es ist... `n`n");
    $session[user][einsatz]--;
    switch (e_rand(1,10)){
        case 1:
        output("`T... die richtige! Du bekommst deinen Einsatz doppelt zurück!");
        $session[user][gold]+=1000;
        break;

        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        output("`T... die falsche! Du hast auf eine andere Zahl gewettet und somit verlierst du deinen Einsatz!");
        $session[user][gold]-=1000;
        break;
    }
    addnav("Zurück","bar_casino.php");


}elseif ($_GET[op]=="we2"){
    output("`TOkay... du hast einen Einsatz gemacht und auf eine Zahl gewettet, jetzt kommt die Auslosung... `n`n Die Zahl wird gezogen und es ist... `n`n");
    $session[user][einsatz]--;
    switch (e_rand(1,10)){
        case 1:
        output("`T... die richtige! Du bekommst deinen Einsatz doppelt zurück!");
        $session[user][gold]+=5000;
        break;

        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        output("`T... die falsche! Du hast auf eine andere Zahl gewettet und somit verlierst du deinen Einsatz!");
        $session[user][gold]-=5000;
        break;
    }
    addnav("Zurück","bar_casino.php");


}elseif ($_GET[op]=="we3"){
    output("`TOkay... du hast einen Einsatz gemacht und auf eine Zahl gewettet, jetzt kommt die Auslosung... `n`n Die Zahl wird gezogen und es ist... `n`n");
    $session[user][einsatz]--;
    switch (e_rand(1,10)){
        case 1:
        output("`T... die richtige! Du bekommst deinen Einsatz doppelt zurück!");
        $session[user][gems]+=2;
        break;

        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        output("`T... die falsche! Du hast auf eine andere Zahl gewettet und somit verlierst du deinen Einsatz!");
        $session[user][gems]-=2;
        break;
    }
    addnav("Zurück","bar_casino.php");


}elseif ($_GET[op]=="we4"){
    output("`TOkay... du hast einen Einsatz gemacht und auf eine Zahl gewettet, jetzt kommt die Auslosung... `n`n Die Zahl wird gezogen und es ist... `n`n");
    $session[user][einsatz]--;
    switch (e_rand(1,10)){
        case 1:
        output("`T... die richtige! Du bekommst deinen Einsatz doppelt zurück!");
        $session[user][gems]+=5;
        break;

        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        output("`T... die falsche! Du hast auf eine andere Zahl gewettet und somit verlierst du deinen Einsatz!");
        $session[user][gems]-=5;
        break;
    }
    addnav("Zurück","bar_casino.php");

}elseif ($_GET[op]=="g"){
    if ($session[user][gold]<1){
        output("`TDu hast kein Geld bei dir, also hast du auch nichts hier verloren!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu gehst an den großen Tisch, wo das Goldspiel gespielt wird, und wo du den Goldjackpot knacken kannst. Hier wird Gold gesetzt, glaubst du heute ist dein Tag und du kriegst den Jackpot?");

        addnav("Setzen");
        addnav("100 Gold","bar_casino.php?op=g1");
        addnav("500 Gold","bar_casino.php?op=g2");
        addnav("2000 Gold","bar_casino.php?op=g3");

        addnav("Wege");
        addnav("Zurück","bar_casino.php");
    }


}elseif ($_GET[op]=="g1"){
    if ($session[user][gold]<100){
        output("`TKein Gold, kein Einsatz!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 100 Gold... das erhöht den Jackpot natürlich... du ziehst dein Los und es ist...`n`n");
        savesetting("casinogold",getsetting("casinogold",0)+100);
        $session[user][einsatz]--;
        switch (e_rand(1,11)){
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            output("`T... eine Niete! Dein Geld ist futsch.");
            $session[user][gold]-=100;
            break;

            case 7:
            case 8:
            case 9:
            case 10:
            output("`T... ein Gewinn! Du erhälst deinen Einsatz zurück!");
            break;

            case 11:
            output("`^... DER JACKPOT!!! Du kannst es gar nicht glauben, doch du hast den Jackpot geknackt! Damit erhälst du $j1 Goldstücke!");
            $session[user][gold]+=$j1;
            savesetting("casinogold",100);
            break;

        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="g2"){
    if ($session[user][gold]<500){
        output("`TKein Gold, kein Einsatz!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 500 Gold... das erhöht den Jackpot natürlich... du ziehst dein Los und es ist...`n`n");
        savesetting("casinogold",getsetting("casinogold",0)+500);
        $session[user][einsatz]--;
        switch (e_rand(1,12)){
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            output("`T... eine Niete! Dein Geld ist futsch.");
            $session[user][gold]-=500;
            break;

            case 7:
            case 8:
            case 9:
            case 10:
            output("`T... ein Gewinn! Du erhälst deinen Einsatz zurück!");
            break;

            case 11:
            case 12:
            output("`^... DER JACKPOT!!! Du kannst es gar nicht glauben, doch du hast den Jackpot geknackt! Damit erhälst du $j1 Goldstücke!");
            $session[user][gold]+=$j1;
            savesetting("casinogold",100);
            break;

        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="g3"){
    if ($session[user][gold]<2000){
        output("`TKein Gold, kein Einsatz!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 2000 Gold... das erhöht den Jackpot natürlich... du ziehst dein Los und es ist...`n`n");
        savesetting("casinogold",getsetting("casinogold",0)+2000);
        $session[user][einsatz]--;
        switch (e_rand(1,11)){
            case 1:
            case 2:
            case 3:
            case 4:
            output("`T... eine Niete! Dein Geld ist futsch.");
            $session[user][gold]-=2000;
            break;

            case 7:
            case 8:
            case 9:
            case 10:
            output("`T... ein Gewinn! Du erhälst deinen Einsatz zurück!");
            break;

            case 5:
            case 6:
            case 11:
            output("`^... DER JACKPOT!!! Du kannst es gar nicht glauben, doch du hast den Jackpot geknackt! Damit erhälst du $j1 Goldstücke!");
            $session[user][gold]+=$j1;
            savesetting("casinogold",100);
            break;

        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="e"){
    if ($session[user][gems]<1){
        output("`TDu hast keine Edelsteine bei dir, also hast du auch nichts hier verloren!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu gehst an den großen Tisch, wo das Edelsteinspiel gespielt wird, und wo du den Edelsteinjackpot knacken kannst. Hier werden Edelsteine gesetzt, glaubst du heute ist dein Tag und du kriegst den Jackpot?");

        addnav("Setzen");
        addnav("2 Edelsteine","bar_casino.php?op=e1");
        addnav("5 Edelsteine","bar_casino.php?op=e2");
        addnav("10 Edelsteine","bar_casino.php?op=e3");

        addnav("Wege");
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="e1"){
    if ($session[user][gems]<2){
        output("`TKeine Edelsteine, kein Einsatz!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 2 Edelsteine... das erhöht den Jackpot natürlich... du ziehst dein Los und es ist...`n`n");
        savesetting("casinoes",getsetting("casinoes",0)+2);
        $session[user][einsatz]--;
        switch (e_rand(1,11)){
            case 1:
            case 2:
            case 3:
            case 4:
            output("`T... eine Niete! Deine Edelsteine sind futsch.");
            $session[user][gems]-=2;
            break;

            case 5:
            case 7:
            case 8:
            case 9:
            case 10:
            output("`T... ein Gewinn! Du erhälst deinen Einsatz zurück!");
            break;


            case 6:
            case 11:
            output("`^... DER JACKPOT!!! Du kannst es gar nicht glauben, doch du hast den Jackpot geknackt! Damit erhälst du $j2 Edelsteine!");
            $session[user][gems]+=$j2;
            savesetting("casinoes",2);
            break;

        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="e2"){
    if ($session[user][gems]<5){
        output("`TKeine Edelsteine, kein Einsatz!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 5 Edelsteine... das erhöht den Jackpot natürlich... du ziehst dein Los und es ist...`n`n");
        savesetting("casinoes",getsetting("casinoes",0)+5);
        $session[user][einsatz]--;
        switch (e_rand(1,11)){
            case 1:
            case 2:
            case 3:
            case 4:
            output("`T... eine Niete! Deine Edelsteine sind futsch.");
            $session[user][gems]-=5;
            break;

            case 5:
            case 7:
            case 8:
            case 9:
            output("`T... ein Gewinn! Du erhälst deinen Einsatz zurück!");
            break;


            case 6:
            case 10:
            case 11:
            output("`^... DER JACKPOT!!! Du kannst es gar nicht glauben, doch du hast den Jackpot geknackt! Damit erhälst du $j2 Edelsteine!");
            $session[user][gems]+=$j2;
            savesetting("casinoes",2);
            break;

        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="e3"){
    if ($session[user][gems]<10){
        output("`TKeine Edelsteine, kein Einsatz!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 10 Edelsteine... das erhöht den Jackpot natürlich... du ziehst dein Los und es ist...`n`n");
        savesetting("casinoes",getsetting("casinoes",0)+10);
        $session[user][einsatz]--;
        switch (e_rand(1,11)){
            case 1:
            case 2:
            case 3:
            case 4:
            output("`T... eine Niete! Deine Edelsteine sind futsch.");
            $session[user][gems]-=10;
            break;

            case 6:
            case 7:
            case 8:

            output("`T... ein Gewinn! Du erhälst deinen Einsatz zurück!");
            break;


            case 5:
            case 9:
            case 10:
            case 11:
            output("`^... DER JACKPOT!!! Du kannst es gar nicht glauben, doch du hast den Jackpot geknackt! Damit erhälst du $j2 Edelsteine!");
            $session[user][gems]+=$j2;
            savesetting("casinoes",2);
            break;

        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="krot"){
    output("`TDu begibst dich in die hintere Ecke, dort wo das illegale Schildkrötenrennen stattfindet. Hier gibt es drei verschiedene Schildkröten die gegeneinander antreten. `n`n");
    output("`\$ Michelangelo `n");
    output("`^ Leonardo `n");
    output("`@ Donatello `n");
    output("`T`n`n Wenn du auf Donatello setzt und er gewinnt bekommst du das doppelte deines Einsatzes zurück, bei Leonardo das dreifache und bei Michelangelo das vierfache... Auf wen willst du setzen?");

    addnav("Schildkröten");
    addnav("`\$Michelangelo","bar_casino.php?op=michi");
    addnav("`^Leonardo","bar_casino.php?op=leo");
    addnav("`@Donatello","bar_casino.php?op=dona");

    addnav("Wege");
    addnav("Zurück","bar_casino.php");

}elseif ($_GET[op]=="michi"){
    output("`TDu sagst dem Veranstalter, dass du auf Michelangelo setzen willst. Er sieht dich erst verwirrt an, doch dann breitet sich ein lächeln auf seinem Gesicht aus und er fragt dich, wie viel du setzen willst.");

    addnav("Michelangelo");
    addnav("100 Gold","bar_casino.php?op=michi1");
    addnav("500 Gold","bar_casino.php?op=michi2");
    addnav("1000 Gold","bar_casino.php?op=michi3");
    addnav("Doch nichts setzen","bar_casino.php?op=krot");

}elseif ($_GET[op]=="leo"){
    output("`TDu sagst dem Veranstalter, dass du auf Leonardo setzen willst. Er sieht dich erst verwirrt an, doch dann breitet sich ein lächeln auf seinem Gesicht aus und er fragt dich, wie viel du setzen willst.");

    addnav("Leonardo");
    addnav("100 Gold","bar_casino.php?op=leo1");
    addnav("500 Gold","bar_casino.php?op=leo2");
    addnav("1000 Gold","bar_casino.php?op=leo3");
    addnav("Doch nichts setzen","bar_casino.php?op=krot");

}elseif ($_GET[op]=="dona"){
    output("`TDu sagst dem Veranstalter, dass du auf Donatello setzen willst. Er sieht dich erst verwirrt an, doch dann breitet sich ein lächeln auf seinem Gesicht aus und er fragt dich, wie viel du setzen willst.");

    addnav("Donatello");
    addnav("100 Gold","bar_casino.php?op=dona1");
    addnav("500 Gold","bar_casino.php?op=dona2");
    addnav("1000 Gold","bar_casino.php?op=dona3");
    addnav("Doch nichts setzen","bar_casino.php?op=krot");

}elseif ($_GET[op]=="michi1"){
    if ($session[user][gold]<100){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 100 Gold auf Michelangelo! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=100;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=100;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TUnglaublich! Michelangelo hat gewonnen und du bekommst das vierfache deines Einsatzes zurück!");
            $session[user][gold]+=400;
        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="michi2"){
    if ($session[user][gold]<500){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 500 Gold auf Michelangelo! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=500;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=500;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TUnglaublich! Michelangelo hat gewonnen und du bekommst das vierfache deines Einsatzes zurück!");
            $session[user][gold]+=2000;
        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="michi3"){
    if ($session[user][gold]<1000){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 1000 Gold auf Michelangelo! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=1000;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=1000;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TUnglaublich! Michelangelo hat gewonnen und du bekommst das vierfache deines Einsatzes zurück!");
            $session[user][gold]+=4000;
        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="leo1"){
    if ($session[user][gold]<100){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 100 Gold auf Leonardo! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=100;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Schildkröte hat gewonnen! Du erhälst das dreifache deines Einsatzes zurück!");
            $session[user][gold]+=300;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=100;
        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="leo2"){
    if ($session[user][gold]<500){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 500 Gold auf Leonardo! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=500;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Schildkröte hat gewonnen! Du erhälst das dreifache deines Einsatzes zurück!");
            $session[user][gold]+=1500;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=500;
        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="leo3"){
    if ($session[user][gold]<1000){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 1000 Gold auf Leonardo! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=1000;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Schildkröte hat gewonnen! Du erhälst das dreifache deines Einsatzes zurück!");
            $session[user][gold]+=3000;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=1000;
        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="dona1"){
    if ($session[user][gold]<100){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 100 Gold auf Donatello! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Schildkröte hat gewonnen! Du erhälst das doppelte deines Einsatzes zurück!");
            $session[user][gold]+=200;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=100;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=100;
        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="dona2"){
    if ($session[user][gold]<500){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 500 Gold auf Donatello! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Schildkröte hat gewonnen! Du erhälst das doppelte deines Einsatzes zurück!");
            $session[user][gold]+=1000;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=500;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=500;
        }
        addnav("Zurück","bar_casino.php");
    }

}elseif ($_GET[op]=="dona3"){
    if ($session[user][gold]<1000){
        output("`TKein Geld, kein Einsatz im Schildkrötenrennen!");
        addnav("Zurück","bar_casino.php");
    }else{
        output("`TDu setzt 1000 Gold auf Donatello! Das Rennen beginnt und die Schildkröten starten... Das Endergebniss sieht wie folgt aus: `n`n");
        $session[user][einsatz]--;
        if ($erg<80){
            output("`T 1 Platz. `@Donatello`n");
            output("`T 2 Platz. `^Leonardo `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`n`T Deine Schildkröte hat gewonnen! Du erhälst das doppelte deines Einsatzes zurück!");
            $session[user][gold]+=2000;

        }elseif ($erg>79 && $erg<100){
            output("`T 1 Platz. `^Leonardo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `\$Michelangelo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=1000;

        }elseif ($erg==100){
            output("`T 1 Platz. `\$Michelangelo `n");
            output("`T 2 Platz. `@Donatello `n");
            output("`T 3 Platz. `^Leonardo `n");
            output("`TDeine Wette ging voll daneben. Dein Einsatz ist futsch.");
            $session[user][gold]-=1000;
        }
        addnav("Zurück","bar_casino.php");
    }

}
page_footer();
?> 