<?
/*
//*-------------------------*
//|        Scriptet by      |
//|       ï¿½*Amerilion*ï¿½     |
//|      greenmano@gmx.de   |
//|       first seen at     |
//|      mekkelon.de.vu     |
//*-------------------------*
/*

//Sanela-Pack Version 1.1
*/
require_once("common.php");
page_header("Das Brunnenhaus");
addcommentary();

switch ($HTTP_GET_VARS['op']){

    case "":
    default;
    output("`n`c`b`3Das Brunnenhaus`b`c`n`n");
    output("`9Ein wenig am Rande des Platzes trittst du an den alten Brunnen, welcher durch ein kleines Dach geschï¿½tzt ist.");
    output("Du kannst dich auf seine Umrandung setzten, etwas trinken oder eine Goldmï¿½nze hineinwerfen.");
    output("Auï¿½erdem ist ein `^goldener Ring`9 in das Eisen eingearbeitet. An ihm zu drehen soll Glï¿½ck bringen.`n`n`n");
    addnav("Trinken","sanelabrunnen.php?subop=tr");
    addnav("Drehen","sanelabrunnen.php?subop=dre");
    addnav("Goldmï¿½nze werfen","sanelabrunnen.php?op=go");
    addnav("Zurï¿½ck nach Elfenstadt","sanela.php");
    viewcommentary("brunn","Hinzufï¿½gen",10);
    switch ($HTTP_GET_VARS['subop']){

        case "":
        default;
        break;

        case "tr":
        output("`9`n`n`n`nDu nimmst einen Schluck und");
        switch(e_rand(1,3)){

            case 1:
            output("es schmeckt wiederlich!");
            break;

            case 2:
            case 3:
            output("fï¿½hlst dich erfrischt.");
            break;
        }
        break;

        case "dre":
        output("`9`n`n`n`nDu drehst an den `^Ring`9 und denkst dir das an alten Sagen ja doch was dran sein mï¿½sste.");
        break;
    }
    break;

    case "go":
    addnav("Zurï¿½ck","sanelabrunnen.php");
    output("`9Du holst deinen Goldbeutel herraus und ï¿½berlegst wie viel Gold du reinwirfst.`n`n");
    output("<form action='sanelabrunnen.php?op=werf' method='POST'>
            <input type='TEXT' name='amount' width='5'>`n`n
            <input type='SUBMIT' value='Reinwerfen'></form>",true);
    addnav("","sanelabrunnen.php?op=werf");
    break;

    case "werf":
    if ($_POST['amount']<=0 || !is_numeric($_POST['amount'])){
        output("`9Du willst wohl doch nichts reinwerfen.");
    }elseif ($_POST['amount']>$session['user']['gold']){
        output("`9Du hast doch gar nicht so viel!");
    }else {
        output("`9Du wirfst ".$_POST['amount']." in den Brunnen und wï¿½nscht dir viel Glï¿½ck, bist sicher das eines Tages auch");
        output("zu bekommen, reibst nochmal am goldenen Ring und packst deinen Beutel wieder ein");
        $session['user']['gold']-=$_POST['amount'];
        savesetting("brunnengold",getsetting("brunnengold",0)+$_POST['amount']);
    }
    addnav("Zurï¿½ck","sanelabrunnen.php");
    break;
}

page_footer();
?>