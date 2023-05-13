
<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Ramius
//
// by Dev and Vecamien Febr 2007
/////////////////////////////////////////////////////////////////////////////
//debuglog("special:ramiusdonnerbalken.php");
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){
    case "tuer":
        $session['user']['specialinc']="";
        output("`(Du öffnest die Tür und ein Stöhnen entweicht aus deinem Mund...`n");
        switch(e_rand(1,3)){
            case '1':
                output("`(Vor dir sitzt Ramius auf dem Donnerbalken im Pyjama mit rosa Häschenpantoffeln und in seinen Händen eine Zeitung. Er grinst dich an, du schließt die Tür und sagst `QTschulligung! `(Du hörst Ramius noch lachen und er schreibt dir `^10 Gefallen `(gut.");
                $session['user']['deathpower']+=10;
                addnews($session['user']['name']." `(hat Ramius im Wald auf dem Donnerbalken erwischt!");
            break;
            case '2':
                output("`(Vor dir sitzt Ramius auf dem Donnerbalken im Pyjama mit rosa Häschenpantoffeln und in seinen Händen eine Zeitung. Schnell schleuderst du die Türe zu und japst erschrocken nach Luft. `^Du erhältst 100 Erfahrung!");                
                $session['user']['experience']+=100;
                addnews($session['user']['name']." `(kam mit einem Schrecken im Wald davon und dachte sich noch, dass man nicht jede Tür öffnen sollte");
            break;
            case '3':
                output("`(Vor dir sitzt Ramius auf dem Donnerbalken im Pyjama mit rosa Häschenpantoffeln und in seinen Händen eine Zeitung. Du taumelst zurück, reibst dir die Augen und fängst an zu beten. Ramius lacht schallend. Du rennst so schnell davon, dass du `^3 Waldkämpfe `(hinzu bekommst!");
                $session['user']['turns']+=3;
                addnews($session['user']['name']." `(rannte wie von einem Ramius gestochen aus dem Wald!");
            break;
        }
    break;

    case "wald":
        $session['user']['specialinc']="";
        output("`n `(Du willst nicht wissen, wer oder was in dieser Bretterbude ist, und rennst davon. `^Du verlierst einen Waldkampf!");
        if ($session['user']['turns']>1){
            $session['user']['turns']--;
        }else{
            $session['user']['turns']=0;
        }
    break;

    default:
        $spi;
        output("`(`n`nDu schlenderst durch den Wald und traust deinen Augen nicht. Vor dir steht eine kleine, schmale Holzbude, in der Mitte der Türe entdeckst du ein Herzchen. Du räusperst dich, und deiner Kehle entspringt ein leises `QHallo? `(Es kommt keine Antwort. Was machst du?`n`n");
        addnav("Tür öffnen",$fn."?op=tuer");
        addnav("zurück in den Wald",$fn."?op=wald");
    break;
}

