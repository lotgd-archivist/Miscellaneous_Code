<?php

//08102005
/* ********************
Codierung von Ray
Ideen von Ray
ICQ:230406044
******************* */
/***************************** Einbauanleitung *****************************

öffne village.php und suche:
addnav("Trainingslager","train.php");

setze darunter:
addnav("Dojo","training.php");

*************************** Einbauanleitung Ende **************************/

require_once "common.php";
checkday();
page_header("Trainingshalle");

$level = $session['user']['level'];
$exp = e_rand(5,10);
$gold = e_rand(8,15);
$ergebnis = ($level*$exp);
$goldgebnis = ($level*$gold);
$anzahl = $_POST['round'];


if ($_GET['op']==""){


    output("`#Du betrittst ein großes Gebäude, die Akademie für Krieger, wie dir nach kurzer Musterung auffällt. Sofort beschleicht dich das Gefühl, dass hier etwas besonderes am Werk ist. Schnell entdeckst du auch was, denn du erkennst überall die Zeichen des Gottes Thor. Ihm scheinen diese Hallen geweiht. Wandmalereien berühmter Schlachten zieren die Wände. Waffen und Rüstungen großer Krieger umranden den Trainingsplatz. Ein großer, muskulöser Mann mustert dich prüfend, um zu entscheiden, ob du würdig bist eine Waffe zu führen. Er befindet dich für würdig und weist dir den Weg zur Waffenkammer. Dort kannst du Waffen, Rüstung und Schild für das Training ausleihen. 
Gerüstet lässt der Aufseher dich nun den Platz betreten. Sofort fällt dir die eigentümliche Form der Halle auf. Ein Stern, dessen Spitzen vom eigentlichen Bereich getrennt werden können, um Einzeltrainings durchzuführen. Ein Lächeln legt sich auf deine Lippen, als du dir deinen ersten Gegner suchst.`n");
    output("`@Wie viele Runden willst du den trainieren?`n`n`n");
    output("<form action='training.php?op=train2' method='POST'>
            <input type='TEXT' name='round' width=5>`n`n
            <input type='SUBMIT' value='Runden'></form>",true);
    addnav("","training.php?op=train2");

    addnav("Zurück","klingengasse.php");

}else if ($_GET['op']=="train2"){
    if ($_POST['round']<=0 || !is_numeric($_POST['round'])){
        output("`#Du willst wohl doch nicht Trainieren.");
        addnav("Zurück","klingengasse.php");
    }elseif ($_POST['round']>$session['user']['turns']){
        output("`#Du hast doch gar nicht so viel Runden!");
        addnav("Zurück","klingengasse.php");
    }else {
         $session['user']['turns']-=$_POST['round'];
        $test=$_POST['round'];
        $er= $ergebnis*$test;
        $session['user']['experience']+=$er;
        output("`#Du trainierst `^".$_POST['round']." `#Runden und bekommst `^".$er." `#Erfahrung.");
        //output("`n`n`4Debug:`nLevel - $level`nZufall - $exp`n Rechnung1 - $ergebnis `nRunden - $test`n Ergebnis - $ergebnis*$test `nERGEBNIS ;) - $er");
        addnav("Zurück","klingengasse.php");
    }
}
page_footer();
?>