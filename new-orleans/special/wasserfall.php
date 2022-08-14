
<?php
//*-------------------------*
//|       wasserfall.php    |
//|        Scriptet by      |
//|       °*Amerilion*°     |
//|   for mekkelon.de.vu    |
//| steffenmischnick@gmx.de |
//*-------------------------*

/* 

Beschreibung:
Ein One-Klick-Event, auf Wunsch von BaohX
Einfach innen specials ordner damit
-------------------------------------------------------------------
|Net vergessen ab und zu mal was bei anpera.net zu veröffentlichen|
-------------------------------------------------------------------

Texteil beim Schwimmen komplett aus der sanelasee.php entnommen, leicht modifiziert...
Die ist auch von mir, also darf ich das ;)

Wichtig: Aja, bevor ich es vergesse, Beta-Tester und Bug-fixer war Devilzimti.
*/


//Variable
$gold =(e_rand(2,1000));
$gem =(e_rand(2,4));
$exp = round($session['user']['experience']*0.3);

if (!isset($session)) exit();

if($_GET['op'] == '' || $_GET['op']== 'search'){ 
    //output("`c`b`1Der Wasserfall`b`c`n");
    output("`9Während du durch die Berge wanderst fällt dir nach einiger Zeit ein");
    output("Rauschen auf. Du folgst dm Geräusch und kommst an einen kleinen");
    output("Wasserfall, welcher aus einer Klippe oberhalb von dir hinabfließt.");
    output("Vor dir sammelt sich das klare Wasser in einem Becken und bildet");
    output("einen kleinen See. Eine Angel liegt im Gras vor dir, scheinbar");
    output("hat sie ein unachtsamer Angler vergessen.");
    addnav("Der Wasserfall");
    addnav("Angeln","berge.php?op=angel");
    addnav("Trinken","berge.php?op=trink");
    addnav("Schwimmen","berge.php?op=schwimm");
    addnav("Zurück in die Berge","berge.php?op=wald");
    $session['user']['specialinc']="wasserfall.php";
}
if ($_GET['op']=="angel"){
    //output("`c`b`1Der Wasserfall`b`c`n");
    output("`9Du nimmst die Angel in die Hand, suchst unter einigen Steinen nach Ködern");
    output("und setzt dich an das Seeufer. Nach einer Zeit");
    switch(e_rand(1,6)){
        case 1:
        output("wirst du mit einem Ruck nach vorne gezogen. Ein riesiger Fisch hatt angebissen....DICH ANGEBISSEN!!!");
        output("`n`n`4Du bist tot.`nDu verlierst all dein Gold.`nDu verlierst ".$exp." Erfahrungspunkte.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']*0.97;
        addnews($session['user']['name']."`9 wurde beim Angeln gefressen... von einem `bFISCH!!!`b");
        addnav("Tägliche News","news.php");
        break;
        case 2:
        case 3:
        case 4:
        case 5:
        output("spürst du einen Ruck an der Angel. Du holst aufgeregt die Leine ein und endeckst einen");
        output("Fisch am Haken. Du freust dich, entzündest ein kleines Lagerfeuer und willst ihn dir");
        output("zubereiten. Dabei findest du `^".$gold." Goldstücke`9, welche er scheinbar vor langer Zeit");
        output("gefressen hatt. Glücklich ziehst du weiter.`n`n");
        $session['user']['gold']+=$gold;
    $session[user][hungry]+=2;
        if ($session['user']['turns']>2){
            output("`4Du vertrödelst 2 Waldkämpfe.");
            $session['user']['turns']-=2;
        }else{
            output("`4Du vertrödelst alle deine übrigen Waldkämpfe.");
            $session['user']['turns']=0;
        }
        break;
        case 6:
        output("spürst du einen Ruck an der Angel. Du holst aufgeregt die Leine ein und entdeckst einen");
        output("Fisch am Haken. Du freust dich, entzündest ein kleines Lagerfeuer und willst ihn dir");
        output("zubereiten. Dabei findest du `^".$gem." Edelsteine`9, welche er scheinbar vor langer Zeit");
        output("gefressen hatt. Glücklich ziehst du weiter.`n`n");
        $session['user']['gems']+=$gem;
    $session[user][hungry]+=2;
        if ($session['user']['turns']>2){
            output("`4Du vertrödelst 2 Waldkämpfe.");
            $session['user']['turns']-=2;
        }else{
            output("`4Du vertrödelst alle deine übrigen Waldkämpfe.");
            $session['user']['turns']=0;
        }
        break;
    }
}
if ($_GET['op']=="trink"){
    //output("`c`b`1Der Wasserfall`b`c`n");
    output("`9Du kniest dich an einer Uferkante nieder und trinkst genüßlich einige");
    output("Schlucke des kalten Wassers");
    switch(e_rand(1,2)){
        case 1:
        output("als plötzlich von oben ein großer Stein hinabfällt. Er reißt dich");
        output("in das Wasserbecken und du verlierst bei dem panischen Versuch");
        output("hinauszukommen einen Teil deines Goldes.`n`n");
        if ($session['user']['gold']>$gold){
            output("`^Du verlierst ".$gold." Goldstücke.");
            $session['user']['gold']-=$gold;
        $session[user][thirsty]+=2;
        }else{
            output("`^Du verlierst all dein Gold.");
            if(!$session['user']['gold'])output("`nZum Glück trägst du kein Gold bei dir.");
            $session['user']['gold']=0;
        $session[user][thirsty]+=2;
        }
        break;
        case 2:
        output("und merkst wie sich deine Wunden schließen. Du wirst entspannter");
        output("und denkst dir, dass du deine Waffe nun ruhiger und treffsicherer führen wirst.");
        $session['bufflist']['wasser']=array("name"=>"`9Treffsicherheit",
        "rounds"=>30,
        "wearoff"=>"Die alltägliche Hektik erfasst dich",
        "defmod"=>1,
        "atkmod"=>1.15,
        "roundmsg"=>"Deine Gedanken bringen dich dazu; dasS du in Ruhe auf den Kampf achten kannst.",
        "activate"=>"offense");
    $session[user][thirsty]+=2;
        break;
    }
}
if ($_GET['op']=="schwimm"){
    //output("`c`b`1Der Wasserfall`b`c`n");
    output("`9Du entkleidest dich, lässt dich ins Wasser gleiten. Du schwimmst eine Zeit lang und ");
    switch (e_rand(1,7)){
        case 1:
        output(" bekommst einen Krampf im Bein. Mit letzter Kraft schwimmst du zum Ufer, schaffst es aber nicht mehr.");
        output("`n`n`4Du bist tot.`nDu verlierst dein Gold.`nDu verlierst ".$exp." deiner Erfahrungspunkte.");
        $session['user']['gold']=0;
        $session['user']['experience']*=0.97;
        $session['user']['alive']=0;
        $session['user']['hitpoints']=0;
        addnews($session['user']['name']."`9 ist ertrunken!");
        addnav("Die Schatten","shades.php");
        break;
        case 2:
        output(" bemerkst bei deinen Kleidern einen Edelstein, während du dich wieder anziehst.`n`n`^Du bekommst 1 Edelstein.");
        $session['user']['gems']+=1;
        break;
        case 3:
        output(" wirst du spürbar schöner. Dies muss eine Zauberquelle sein...`n`n`^Du bekommst 2 Charmepunkte.");
        $session['user']['charm']+=2;
        break;
        case 4:
        case 5:
        output(" findest beim Tauchen einen Beutel mit Gold.`n`n`^Du bekommst ".$gold." Goldstücke.");
        $session['user']['gold']+=$gold;
        break;
        case 6:
        output(" bemerkst bei deinen Kleidern zwei Edelsteine, während du dich wieder anziehst.`n`n`^Du bekommst 2 Edelsteine.");
        $session['user']['gems']+=2;
        break;
        /*case 9:
        output(" bekommst einen Krampf im Bein, mit letzter Kraft schwimmst du zum Ufer, schaffst es aber nicht.");
        output("`n`n`^Du bist tot.`nDu verlierst dein Gold.`nDu verlierst ".$exp." deiner Erfahrungspunkte.");
        $session['user']['gold']=0;
        $session['user']['experience']*=0.97;
        $session['user']['alive']=0;
        $session['user']['hitpoints']=0;
        addnews($session['user']['name']."`1 ist ertrunken!");
        addnav("Die Schatten","shades.php");
        break;
        case 10:
        if($session['user']['gems']>2){
            output(" dir fehlen zwei Edelsteine als du dich wieder anziehst.`n`n`^Du verlierst 2 Edelsteine");
            $session['user']['gems']-=2;
        }else{
            output(" dir fehlen alle Edelsteine die du noch hattest.");
            if(!$session['user']['gems'])output(" Sprich: Garkeine.");
            $session['user']['gems']=2;
        }
        break;*/
    }
}
if ($_GET['op']=="wald"){
    output("`9Du lässt den Wasserfall hinter dir...");
    $session['user']['specialinc']="";
}
?>


