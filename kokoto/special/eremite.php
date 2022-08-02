<?php
// idea of gargamel @ www.rabenthal.de
// some changes by Tidus www.kokoto.de (wurde einiges nicht standard mäßiges hinzugefügt (Schwarzes Einhorn, eigenes Special), man sollte das special wo anders ziehn, oder muss es ebend entsprechend abändern.)
if (!isset($session)) exit();

if ($_GET['op']=='enter'){
    $was = mt_rand(1,2);
    switch ( $was ) {
        case '1':
        output('`nDu streifst den Vorhang zur Seite und betrittst das einzige Zimmer
        in dem Haus. Nur ein Tisch, Stuhl und ein Bett kannst Du ausmachen. Und dort,
        auf der anderen Seite, ist eine kleine Kochstelle.`n`n
        Ob sich ein genauerer Blick lohnt?`0');
        addnav('Haus durchsuchen','forest.php?op=such&sup=1');
        addnav('Zurück in den Wald','forest.php?op=cont');
        break;
        case '2':
        output('`nDu streifst den Vorhang zur Seite und betrittst das einzige Zimmer
        in dem Haus. Ein alter Mann sitzt an einem Tisch. Nur ein Bett und eine kleine
        Kochstelle siehst Du sonst noch.`n`n
        Der alte Mann scheint wehrlos...`0');
        addnav('Mann überfallen','forest.php?op=such&sup=2');
        addnav('Mann ansprechen','forest.php?op=talk');
        addnav('Zurück in den Wald','forest.php?op=cont');
        break;
    }
    $session['user']['specialinc'] = 'eremite.php';
}else if ($_GET['op']=='talk'){
    $was = mt_rand(1,15);
    output('`nFreundlich sprichst Du den alten Mann an. "Ich bin zufällig hier
    vorbei gekommen und wollte nach dem Rechten sehen" sagst Du.`n
    Der alte Mann nickt in Deine Richtung. Er ist offenbar über den Besuch erfreut.
    Dann erzählt er Dir seine halbe Lebensgeschichte. Als Du gerade gehen willst,
    spricht er Dich an. "Wartet, ich habe noch mehr zu erzählen."`n
    `gAls Du auch die zweite Hälfte seiner Lebensgeschichte gehört hast, ist ein
    Waldkampf verloren.`n`n`0');
    $session['user']['turns']--;
    switch ( $was ) {
        case '1':
        case '3':
        case '5':
        case '7':
        case '9':
        case '11':
        case '13':
        output('`tImmerhin hat er Dir den Weg zur legendären `VOrkburg`t beschrieben.`0');
        $session['user']['specialinc'] = 'castle.php';
        break;
        case '2':
        case '4':
        output('`tImmerhin hat er Dir den Weg zur legendären `^Goldmine`t beschrieben.`0');
        $session['user']['specialinc'] = 'goldmine.php';
        break;
        case '6':
        case '8':
        case '10':
        case '12':
        case '14':
        output('`tImmerhin hat er dir den weg zur `&magischen AllMightys Quelle`t beschrieben');
        $session['user']['specialinc'] = 'pietre.php';
        break;
        case '15':      
        if ($session['user']['dragonkills']>=15){
        output('`tImmerhin hat er Dir den Weg zum aufenthalt des Legendären schwarzen Einhorns verraten.`0');
        $session['user']['specialinc'] = 'blunicorn.php';
        }else{
        output('`tImmerhin hat er Dir den Weg zu einem `sverlassenen ort`t beschrieben!.`0');
        $session['user']['specialinc'] = 'getmount.php';
        }
        break;
    }
    addnav('Mann verlassen','forest.php');
}else if ($_GET['op']=='such'){
    $sup = $_GET['sup'];
    if ( $sup == 2 ) {
        output('`nDu bedrohst den alten Mann mit Deiner Waffe und bedeutest ihm, Dir
        keine Schwierigkeiten zu machen. Um Eindruck zu schinden, haust Du mit der
        Faust auf den Tisch.`0');
        addnews($session['user']['name'].'`$ hat einen armen alten Mann überfallen!!!');
        $session['user']['reputation']-=10;
    }
    output('`nIn aller Ruhe durchsuchst Du nun das Haus. Wie sollte es auch anders
    sein? `gIn so einer armseligen Hütte ist natürlich nichts zu finden.`0');
    $session['user']['specialinc']='';
}else if ($_GET['op']=='cont'){   // einfach weitergehen
    output('`n`gDu verlässt das Haus und setzt lieber Deinen Weg fort.`0');
    $session['user']['specialinc']='';
}else{
    output('`nEin kleines Häuschen steht am Waldrand. Es ist aus vielen Felssteinen
    gebaut, mit alten Ästen gestützt und offenbar mit Lehm verputzt. Nur ein
    Vorhang bildet die Tür.`n
    `gDas Haus eines Einsiedlers`0.`n`n
    Lebenszeichen kannst Du keine warnehmen. Keine Stimmen kannst Du hören. Keinen
    Rauch aus dem Schornstein sehen.`0');
    //abschluss intro
    addnav('Haus betreten','forest.php?op=enter');
    addnav('weitergehen','forest.php?op=cont');
    $session['user']['specialinc'] = 'eremite.php';
}
?>