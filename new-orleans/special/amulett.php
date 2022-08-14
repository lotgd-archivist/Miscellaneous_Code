
<?php

//
//Idee und Umsetzung Ajuba aka LotTR  für erologd.de 26.07.2006
//mit einer Idee von Morpheus
if (!isset($session)) exit();

output("`b`c`\$Das Amulett`c`b`n`n");
if ($HTTP_GET_VARS[op]=="open"){
        output("`^voller Neugier öffnest du das Amulett");
    switch(e_rand(1,2)){
    case 1:
        output("`8Du erkennst einen wunderschönen Stein im Innern , seine Schönheit scheint dich zu verzaubern.");
        output("Nachdem du ihn eine Weile betrachtet hast fühlst du dich `rcharmanter`8.`n`n");
        output("`4Du erhällst 10 Charmpunkte");
        $session[user][charm]+=10;
        addnews("`8".$session[user][name]."`8 wurde von einem Amulett verzaubert!");
        $session[user][specialinc]="";
        break;
    case 2:
        if ( $session[user][turns]>=5){
        output("`8du erkennst einen wunderschönen Stein im Innern , seine Schönheit scheint auf dich zu verzaubern.Du verbringst mehrere Stunden damit ihn zu betrachten,darurch verlirst du 5 Waldkämpfe");
        $session[user][turns]-=5;
        $session[user][specialinc]="";
        addnews("`8".$session[user][name]."`8 verbrachte Stunden damit sich ein Amulett anzusehn!`0");
        break;
    }else{
output("`8du erkennst einen wunderschönen Stein im Innern , doch seine Schönheit scheint auf Dich negativ zu wirken... Du verlierst 3 Charmpunkte an das Amulett ");
        $session[user][charm]-=3;
        $session[user][specialinc]="";
        addnews("`8".$session[user][name]."`8 verlor Schönheit an ein Amulett!`0");
        break;
       }
   
    }
}else if ($HTTP_GET_VARS[op]=="leave"){
    output("`8Als du dir die Kette anschaust, kannst du nichts besonderes erkennen und gehtst zurück in den Wald.");
    $session[user][specialinc]="";
}else{

    output("`8Als du durch den Wald läufst endeckst du etwas funkelndes im Unterholz");
    output("Du gehst näher heran gehst erkennst das es sich um eine Kette handelt");
    output("An der Kette hängt ein Amulett ...`n");
    addnav("Amulett öffnen","forest.php?op=open");
    addnav("zurück in den Wald","forest.php?op=leave");
    $session[user][specialinc]="amulett.php";
}
?>


