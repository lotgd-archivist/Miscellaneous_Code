<?php 

// 22062004

// translation found at http://logd.ist-hier.de
// small ... BIG ... modifications by anpera

if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]=="do"){
    $session[user][reputation]--;
    if ($session['user']['sex']>0){
        output("`c`êDu folgst dem Gott in die Büsche.`n
Nur wenige Minuten später verfallt ihr Beide in ein Heißes Spiel,`n
bei dem sich keiner von Euch mehr zurück halten kann.`n
`n
Erschöpft schläfst du neben ihm, auf dem weichem Moos Boden ein`n
und als Du erwachst, stellst du fest, dass...`n`c"); 
        switch(e_rand(1,10)){ 
            case 1: 
            case 2: 
            output("`êer verschwunden ist ohne sich zu verabschieden. Aufgrund deiner Erschöpfung, verlierst du einen Waldkampf."); 
            $session[user][turns]-=1; 
            $session[user][experience]+=150; 
            break; 
            case 3: 
            case 4: 
            output("`êer verschwunden ist ohne sich zu verabschieden. Du fühlst dich gut und könntest jetzt einen Kampf vertragen."); 
            $session[user][turns]+=1; 
            $session[user][experience]+=150; 
            break; 
            case 5: 
            output("`êer dir einen Beutel mit Edelsteinen da gelassen hat. Du fühlst dich benutzt, akzeptierst aber die Bezahlung!"); 
            $session[user][gems]+=3; 
            $session[user][experience]+=150; 
            break; 
            case 6: 
            output("`êer einen goldenen Apfel da gelassen hat. Als du in den Apfel beißt, fühlst du zusätzliche Lebenskraft in dir!"); 
            $session[user][maxhitpoints]+=1; 
            $session[user][experience]+=150; 
            break; 
            case 7: 
            case 8: 
            case 9: 
            case 10: 
            increment_specialty(); 
            break; 
        } 
        addnews($session[user][name]." `Lhatte ein langes Techtemechtel mit einem Gott im Busch");
        $session[user][specialinc]="";
        //addnav("Zurück in den Wald","forest.php");
    }else{ 
        output("`c`LDu folgst der Göttin in die Büsche.`n
Nur wenige Minuten später verfallt ihr Beide in ein Heißes Spiel,`n
bei dem sich keiner von Euch mehr zurück halten kann.`n
`n
Erschöpft schläfst du neben Ihr, auf dem weichem Moos Boden ein`n
und als Du erwachst, stellst du fest, dass...`n`c"); 
        switch(e_rand(1,10)){ 
            case 1: 
            case 2: 
            output("`Lsie verschwunden ist ohne sich zu verabschieden. Aufgrund deiner Erschöpfung, verlierst du einen Waldkampf."); 
            $session[user][turns]-=1; 
            $session[user][experience]+=150; 
            break; 
            case 3: 
            case 4: 
            output("`Lsie verschwunden ist ohne sich zu verabschieden. Du fühlst dich gut und könntest jetzt einen Kampf vertragen."); 
            $session[user][turns]+=1; 
            $session[user][experience]+=150; 
            break; 
            case 5: 
            output("`Lsie dir einen Beutel mit Edelsteinen da gelassen hat. Du fühlst dich benutzt, akzeptierst aber die Bezahlung!"); 
            $session[user][gems]+=3; 
            $session[user][experience]+=150; 
            break; 
            case 6: 
            output("`Lsie einen goldenen Apfel da gelassen hat. Als du in den Apfel beisst, fühlst du zusätzliche Lebenskraft in dir!"); 
            $session[user][maxhitpoints]+=1; 
            $session[user][experience]+=150; 
            break; 
            case 7: 
            case 8: 
            case 9: 
            case 10: 
            increment_specialty(); 
            break; 
        } 
        addnews($session[user][name]."`L hatte ein langes Techtemechtel mit einer Göttin im Busch.");
        $session[user][specialinc]="";
        //addnav("Zurück in den Wald","forest.php");
    }
}else if ($HTTP_GET_VARS[op]=="dont"){
    output("`LIn Gedanken an ".($session[user][sex]?"`Ldeinen Geliebten":"`Ldeine Geliebte")."`Lrennst du weg. Die Gottheit lacht auf und verschwindet in den Schatten des Waldes.");
    $session[user][specialinc]="";
    $session[user][reputation]+=2;
    //addnav("Zurück in den Wald","forest.php");
}else{
    if ($session['user']['sex']>0){
        output("`c`êAls Du durch den Wald wanderst, erscheint vor Dir eine Mann.`n
Dir stockt der Atem, seine Schönheit ist so überwältigend das Du dich kaum rühren kannst. `n
`n
Seine Kleidung erlaubt Dir, auf das zu schauen was Dich erwarten könnte, denn der verführerische `n  Mann stellt sich dir als „`4Got`\$t d`Eer L`\$ie`4be“`ê vor. `n
`n
Die Hand ausstreckend ladet er Dich auf etwas Heißes und Spannendes ein.`n
Nimmst Du an?`n`c"); 
    }else{
        output("`c`LAls Du durch den Wald wanderst, erscheint vor Dir eine Frau.`n
Dir stockt der Atem, ihre Schönheit ist so überwältigend das Du dich kaum rühren kannst. `n
`n
Ihre Kleidung erlaubt Dir, auf das zu schauen was Dich erwarten könnte, denn die verführerische `n Frau stellt sich dir als „`4Göt`\$ti`En d`\$er Li`4ebe`L“ vor. `n
`n
Die Hand ausstreckend ladet sie Dich auf etwas Heißes und Spannendes ein.`n
Nimmst Du an?`n`c
`n"); 
    }
        addnav("Gehe mit","forest.php?op=do"); 
        addnav("Laufe weg","forest.php?op=dont"); 
        $session[user][specialinc]="aphrodite.php"; 
}
?>