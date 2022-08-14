
<?php

//08102005
/* ********************
Codierung von Ray
Ideen von Ray
ICQ:230406044
******************* */
/***************************** Einbauanleitung *****************************

öffne village.php und suche:
addnav("Trainingslager","train.php");

setze darunter:
addnav("Dojo","training.php");

*************************** Einbauanleitung Ende **************************/

require_once "common.php";
checkday();
page_header("Dojo");

$level = $session['user']['level'];
$exp = e_rand(5,10);
$gold = e_rand(8,15);
$ergebnis = ($level*$exp);
$goldgebnis = ($level*$gold);
$anzahl = $_POST['round'];


if ($_GET['op']==""){


    output("`#Mit Großen Augen fragt dich der Meister:`n");
    output("`@Wie viele Runden willst du den trainieren?`n`n`n");
    output("<form action='training.php?op=train2' method='POST'>
            <input type='TEXT' name='round' width=5>`n`n
            <input type='SUBMIT' value='Runden'></form>",true);
    addnav("","training.php?op=train2");
    addnav("zum Bayouwald","forest.php");    
addnav("zum Jackson Square","village.php");

}else if ($_GET['op']=="train2"){
    if ($_POST['round']<=0 || !is_numeric($_POST['round'])){
        output("`#Du willst wohl doch nicht Trainieren.");
    addnav("zum Bayouwald","forest.php");    
addnav("zum Jackson Square","village.php");
    }elseif ($_POST['round']>$session['user']['turns']){
        output("`#Du hast doch gar nicht so viel Runden!");
    addnav("zum Bayouwald","forest.php");    
addnav("zum Jackson Square","village.php");
    }else {
         $session['user']['turns']-=$_POST['round'];
        $test=$_POST['round'];
        $er= $ergebnis*$test;
        $session['user']['experience']+=$er;
        output("`#Du trainierst `^".$_POST['round']." `#Runden und erhählst `^".$er." `#Erfahrung.");
        //output("`n`n`4Debug:`nLevel - $level`nZufall - $exp`n Rechnung1 - $ergebnis `nRunden - $test`n Ergebnis - $ergebnis*$test `nERGEBNIS ;) - $er");
    addnav("zum Bayouwald","forest.php");    
addnav("zum Jackson Square","village.php");
    }
}
page_footer();
?>

