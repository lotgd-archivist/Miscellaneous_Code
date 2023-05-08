
<?
/*
Stones (slots)
simple little slots game for your casino
Author: Lonnyl of http://www.pqcomp.com/logd
Difficulty: Easy
no sql to add
simply upload, link it to your casino.php or whatever you may be using.
upload images to your images folder.
if not using a casino.php change the return nav at the bottom of this file.
(casino.php is not an avialble file, you need to make one of your own)
*/
require_once "common.php";
checkday();
page_header("Das Steinespiel");

// Gargamel inventory system
$session['user']['blockinventory']=1; // don't use the inventory

//checkevent();
output("`c`b`&Das Steinespiel`0`b`c`n`n");
addnav("~Optionen~");
if ($HTTP_GET_VARS[op] == ""){
    output("`nIn einer kleinen Nische sitzt eine alte Frau an einem Holztisch.
    Du schaust zweimal hin...Das ist doch Vessa!`n
    In der Hoffnung auf ein Edelsteingeschäft trittst Du näher.`n`n
    \"`3Willst Du ein Spiel wagen?`0\" fragt sie Dich.`n`n
    Vessa spielt nur um Edelsteine, Einsatz ist immer 1 Edelstein. Sie wird aus
    ihrem Beutel 3 Steine auf den Tisch fallen lassen, dabei kannst Du folgendes
    gewinnen:`n`n");
    output("<IMG SRC=\"images/stone1.gif\">
            <IMG SRC=\"images/stone1.gif\"> = 1 Edelstein`n`n",true);
    output("<IMG SRC=\"images/stone2.gif\">
            <IMG SRC=\"images/stone2.gif\"> = 2 Edelsteine`n`n",true);
    output("<IMG SRC=\"images/stone1.gif\">
            <IMG SRC=\"images/stone1.gif\">
            <IMG SRC=\"images/stone1.gif\"> = 5 Edelsteine`n`n",true);
    output("<IMG SRC=\"images/stone2.gif\">
            <IMG SRC=\"images/stone2.gif\">
            <IMG SRC=\"images/stone2.gif\"> = 10 Edelsteine`n`n",true);
    addnav("Edelstein setzen","stonesgame.php?op=play");
}
if ($HTTP_GET_VARS[op] == "play"){
    if ($session['user']['gems'] > 0){
    output("`2`nDu legst einen Edelstein als Einsatz auf den Tisch. `n
    Vessa schaut kurz und lässt dann 3 Steine aus ihrem Beutel fallen:`n");
    $session['user']['gems']-=1;
    $stoneone=e_rand(1,3000);
    $stonetwo=e_rand(1,4000);
    $stonethr=e_rand(1,5000);
    $stoneone=round($stoneone/1000);
    $stonetwo=round($stonetwo/1000);
    if ($stonetwo == 4) $stonetwo = 3;
    $stonethr=round($stonethr/1000);
    if ($stonethr > 3) $stonethr = 3;
    if ($stoneone == 0) $stoneone = 3;
    if ($stonetwo == 0) $stonetwo = 3;
    if ($stonethr == 0) $stonethr = 3;
    output("<IMG SRC=\"images/stone".$stoneone.".gif\"><IMG SRC=\"images/stone".$stonetwo.".gif\"><IMG SRC=\"images/stone".$stonethr.".gif\">`n",true);
    if ($stoneone == 3) $stoneone = 0;
    if ($stonetwo == 3) $stonetwo = 0;
    if ($stonethr == 3) $stonethr = 0;
    if ($stoneone == 2) $stoneone = 5;
    if ($stonetwo == 2) $stonetwo = 5;
    if ($stonethr == 2) $stonethr = 5;
    $stonetotal=($stoneone+$stonetwo+$stonethr);
    if ($stonetotal == 2 or $stonetotal == 7){
        //push
        $session['user']['gems']+=1;
        output("`2`nOhne etwas zu sagen schiebt Dir Vessa den Edelstein zurück.`n
        Du packst ihn schnell wieder in Deinen Beutel.`0");
    }elseif ($stonetotal == 10 or $stonetotal == 11){
        //double
        $session['user']['gems']+=2;
        output("`2`nOhne etwas zu sagen legt Vessa eine zweiten Edelsteine auf den Tisch.`n
        Du steckst sie gleich in Deinen Beutel.`0");
    }elseif ($stonetotal == 3 or $stonetotal == 8){
        $win=5;
        $session['user']['gems']+=$win;
        output("`2`nOhne etwas zu sagen legt Dir Vessa $win Edelsteine hin.`n
        Zügig packst Du sie in Deinen Beutel.`0");
    }elseif ($stonetotal == 15 or $stonetotal == 16){
        $win=10;
        $session['user']['gems']+=$win;
        output("`2`nOhne etwas zu sagen wirft Vessa sichtlich verärgert $win Edelsteine
        in Deine Richtung.`n
        Du grabscht sofort danach und packst sie in Deinen Beutel.`0");
    }else{
        output("`2`nVessa steckt lächelnd Deinen Einsatz ein und schaut Dich
        fragend an.`n`0");
    }
    addnav("Neues Spiel","stonesgame.php?op=play");
    }else{
        output("`nDu hast keinen Edelstein als Spieleinsatz!`n");
    }
}
//addnav("Casino Floor","casino.php");
if ($session['user']['guildID']!=0) {
    $id=$session['user']['guildID'];
} elseif ($session['user']['clanID']!=0) {
    $id=$session['user']['clanID'];
}
addnav("zurück","guild.php?op=member&action=cellar&id=".$id);
//addnav("zurück","guild.php?op=member&action=enter");
//http://127.0.0.1/logd/guild.php?op=member&action=enter&id=17&c=93-032316

//I cannot make you keep this line here but would appreciate it left in.
//rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Stones by Lonny @ http://www.pqcomp.com</a><br>");
page_footer();
?>


