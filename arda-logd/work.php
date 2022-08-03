<?php

//15102005
/* ********************
Codierung von Ray
Ideen von Ray
ICQ:230406044
******************** */
/***************************** Einbauanleitung *****************************
öffne inn.php und suche:
addnav("Was machst du?");

setze darunter:
addnav("Arbeiten","work.php");

*************************** Einbauanleitung Ende **************************/

require_once "common.php";
page_header("Arbeiten");

$level = $session['user']['level'];
$gold = e_rand(8,15);
$goldgebnis = ($level*$gold);
$anzahl = $_POST['round'];

if ($_GET['op']==""){
    output("`#Cedrik guckt dich nur an und fragt:`n");
    output("`@Wie viele runden willst du arbeiten?`n`n");

    output("<form action='work.php?op=work2' method='POST'>
            <input type='TEXT' name='round' width=5>`n`n
            <input type='SUBMIT' value='Runden'></form>",true);
    addnav("","work.php?op=work2");

    addnav("Zurück","inn.php");

}else if ($_GET['op']=="work2"){
    if ($_POST['round']<=0 || !is_numeric($_POST['round'])){
        output("`#Du willst wohl doch nicht Arbeiten.");
        addnav("Zurück","inn.php");
    }elseif ($_POST['round']>$session['user']['turns']){
        output("`#Du hast doch gar nicht so viel Runden!");
        addnav("Zurück","inn.php");
    }else {
         $session['user']['turns']-=$_POST['round'];
        $test=$_POST['round'];
        $er= $goldgebnis*$test;
        $session['user']['gold']+=$er;
        output("`#Du Arbeitest `^".$_POST['round']." `#Runden und erhählst `^".$er." `#Gold.");
        //output("`n`n`4Debug:`nLevel - $level`nZufall - $exp`n Rechnung1 - $ergebnis `nRunden - $test`n Ergebnis - $ergebnis*$test `nERGEBNIS ;) - $er");
        addnav("Zurück","inn.php");
    }
}
page_footer();
?> 