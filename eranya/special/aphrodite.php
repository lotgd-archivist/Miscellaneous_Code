
<?php 

// 22062004

// translation found at http://logd.ist-hier.de
// small ... BIG ... modifications by anpera

if (!isset($session)) exit();

if ($_GET['op']=="do"){
    $session['user']['reputation']--;
    if ($session['user']['sex']>0){
        output("`MDu folgst dem Gott in die Büsche. Wenige Minuten später sind nur noch leise Geräusche zu hören.`nAls du erwachst, stellst du fest, dass ...`n`n`^");
        switch(e_rand(1,10)){ 
            case 1: 
            case 2: 
            output("er verschwunden ist, ohne sich zu verabschieden. Aufgrund deiner Erschöpfung verlierst du einen Waldkampf.");
            $session['user']['turns']-=1; 
            $session['user']['experience']+=150; 
            break; 
            case 3: 
            case 4: 
            output("er verschwunden ist, ohne sich zu verabschieden. Du fühlst dich gut und könntest jetzt einen Kampf vertragen.");
            $session['user']['turns']+=1; 
            $session['user']['experience']+=150; 
            break; 
            case 5: 
            output("er dir einen Beutel mit Edelsteinen da gelassen hat. Du fühlst dich benutzt, akzeptierst aber die Bezahlung.");
            $session['user']['gems']+=3; 
            $session['user']['experience']+=150; 
            break; 
            case 6: 
            output("er einen goldenen Apfel da gelassen hat. Als du in den Apfel beißt, fühlst du zusätzliche Lebenskraft in dir!"); 
            $session['user']['maxhitpoints']+=1; 
            $session['user']['experience']+=150; 
            break; 
            case 7: 
            case 8: 
            case 9: 
            case 10: 
            increment_specialty(); 
            break; 
        } 
        addnews("`M".$session['user']['name']." `Mhatte einen Quicky mit Zhudesh.");
        $session['user']['specialinc']="";
        //addnav("Zurück in den Wald","forest.php");
    }else{ 
        output("`MDu folgst der Göttin in die Büsche. Wenige Minuten später sind nur noch leise Geräusche zu hören.`nAls du erwachst, stellst du fest, dass ...`n`n`^");
        switch(e_rand(1,10)){ 
            case 1: 
            case 2: 
            output("sie verschwunden ist, ohne sich zu verabschieden. Aufgrund deiner Erschöpfung verlierst du einen Waldkampf.");
            $session['user']['turns']-=1; 
            $session['user']['experience']+=150; 
            break; 
            case 3: 
            case 4: 
            output("sie verschwunden ist, ohne sich zu verabschieden. Du fühlst dich gut und könntest jetzt einen Kampf vertragen.");
            $session['user']['turns']+=1; 
            $session['user']['experience']+=150; 
            break; 
            case 5: 
            output("sie dir einen Beutel mit Edelsteinen da gelassen hat. Du fühlst dich benutzt, akzeptierst aber die Bezahlung.");
            $session['user']['gems']+=3; 
            $session['user']['experience']+=150; 
            break; 
            case 6: 
            output("sie einen goldenen Apfel da gelassen hat. Als du in den Apfel beisst, fühlst du zusätzliche Lebenskraft in dir.");
            $session['user']['maxhitpoints']+=1; 
            $session['user']['experience']+=150; 
            break; 
            case 7: 
            case 8: 
            case 9: 
            case 10: 
            increment_specialty(); 
            break; 
        } 
        addnews("`M".$session['user']['name']." `Mhatte einen Quicky mit Zhudesh.");
        $session['user']['specialinc']="";
        //addnav("Zurück in den Wald","forest.php");
    }
}else if ($_GET['op']=="dont"){
    output("`MIn Gedanken an ".($session['user']['sex']?"deinen Geliebten":"deine Geliebte")." rennst du weg. Die Gottheit lacht auf und verschwindet in den Schatten des Waldes.");
    $session['user']['specialinc']="";
    $session['user']['reputation']+=2;
    //addnav("Zurück in den Wald","forest.php");
}else{
    if ($session['user']['sex']>0){
        output("`MAls du durch den Wald wanderst, spricht dich ein stattlicher Mann an. Ihn scheint ein innerliches Licht zu erfüllen. \"`^Ich bin Zhudesh`M\", stellt er sich dir mit
                tiefer Stimme vor, \"`^und ich habe von deiner Schönheit gehört. Wahrlich, sie ist meiner ebenbürdig. Komm mit mir.`M\"`n
                Was tust du?");
    } else {
        output("`MAls du durch den Wald wanderst, erscheint dir eine wunderschöne Frau. Sie scheint ein innerliches Licht zu erfüllen. \"`^Ich bin Zhudesh`M\", stellt sie sich dir mit
                samtweicher Stimme vor, \"`^und ich habe bereits viel von deinen Künsten als Liebhaber gehört. Komm mit mir.`M\"`n
                Was tust du?");
    }
    addnav("Gehe mit","forest.php?op=do"); 
    addnav("Laufe weg","forest.php?op=dont"); 
    $session['user']['specialinc']="aphrodite.php"; 
}
?>

