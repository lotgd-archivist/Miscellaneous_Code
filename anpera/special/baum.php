
ï»¿<?php

/*

Baum

by Vaan

12//10//2004

*/



if (!isset($session)) exit(); // wenn nicht eingeloggt, soll nix passieren

$session['user']['specialinc']="baum.php"; // wir befinden uns im Wald beim Special "baum.php".



if ($_GET['op']=="weiter"){ // Spieler geht weiter

    output("`2WÃ¼tend gehst du weiter.");

    output("Da du eine dicke Beule am Kopf hast `^verlierst `2du einen Charmepunkt!");

    $session['user']['charm']--; // 1 Charmepunkt wird abgezogen

    $session['user']['specialinc']=""; // Es geht ohne Special zurÃ¼ck in den Wald. Keine Navs nÃ¶tig.

}else if ($_GET['op']=="tret"){ // Spieler tritt gegen den Baum.

    output("`2Du trittst mit voller Wucht gegen den Baum. Doch du musst festellen, dass das gar keine gute Idee war! Ein Schwarm von Wespen schieÃŸt aus dem Baum auf dich zu.");

    output("`2Du willst wegrennen, doch die Wespen sind schon um dich herum und fangen an, auf dich einzustechen.");

    output("`n`bDu bist von den Wespenstichen gestorben`b.");

    $session['user']['alive']=0; // Spieler ist tot

    $session['user']['hitpoints']=0; // Keine Lebenspunkte Ã¼brig

    $session['user']['experience']*=0.95; // 5% Erfahrungsverlust

    addnav("T?TÃ¤gliche News","news.php");

    $session['user']['specialinc']=""; // Specialinc zurÃ¼cksetzen

    addnews($session['user']['name']."`0s Leiche wurde, von `^W`Te`^s`Tp`^e`Tn `0zerstochen, im `2Wald `0gefunden.");

}else{ // Spieler landet bei diesem Special.

    output("`2Du gehst den Waldweg entlang und guckst durch die Gegend... es macht richtig SpaÃŸ, durch den Wald zu laufen. Du entdeckst auf der rechten Seite einen Bach. Du schaust wÃ¤hrend des Gehens in Richtung Bach.");

    output("`\$`bDONG!!!`b`2 Du bist mit voller Wucht gegen einen Baum gelaufen.");

    output("`n`^Du verlierst einen GroÃŸteil deiner Lebenspunkte.");

    output("`n`2Was willst du nun machen?");

    if ($session['user']['hitpoints']>5) $session['user']['hitpoints']=5; // Nur wenn LP>5 macht es Sinn, auf 5 runter zu setzen.

    addnav("W?WÃ¼tend weiter gehen","forest.php?op=weiter");

    addnav("G?Gegen den Baum treten","forest.php?op=tret");

}

?>

