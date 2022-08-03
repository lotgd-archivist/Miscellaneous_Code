<?php
//*-------------------------*
//|         Wanderweg       |
//|        by Amerilion     |
//|       first seen at     |
//|    www.mekkelon.de.vu   |
//*-------------------------*


//Sanela-Pack Version 1.1

require_once "common.php";

$tu=e_rand(1,3);
$t=round($session['user']['turns']*0.2);
page_header("Wanderweg");
$session['user']['sanela']=unserialize($session['user']['sanela']);
output("`c`b`8Der Wanderweg`c`b`n`n");

if($_GET['op']==""){
    output("`8Als du beim Waldrand ankommst, bemerkst du einen alten Wegweiser.");
    output("Du betrachtest ihn und merkst, dass er schon lange hier stehen muss.");
    output("Du hoffst einfach mal, dass nicht irgendwelche Witzbolde ihn verdreht haben.");
    addnav("Wegweiser");
    addnav("Zum Dorf","village.php");
    if($session['user']['sanela']['sanela']<1){
        addnav("Sanela","wanderweg.php?op=sanela");
    }else{
        output("`n`n`4Du warst heute schon in Sanela und kannst nicht nocheinmal dorthin.");
    }
    addnav("In den Wald","forest.php");
}

if($_GET['op']=="sanela"){
    if($session['user']['dragonkills']>2){
        output("`8Du siehst im Gebüsch den kleinen alten Kobold sitzen, der dir den Weg nach");
        output("Sanela gezeigt hatt. Er wartet wohl auf den nächsten Abenteurer, der sich auf");
        output("den falschen Weg führen lässt.`n`n`^Du brauchst ".$tu." Runden");
        if($session['user']['turns']>$tu){
            addnav("Weiter","sanela.php");
            $session['user']['sanela']['sanela']++;
            $session['user']['turns']-=$tu;
        }else{
            output("`n`n`8Leider bist du zu müde, um den langen Weg auf dich zu nehmen.");
            output("Versuch es nochmal, wenn du ausgeruht bist.");
            addnav("Zurück","wanderweg.php");
        }
    }else{
        output("`8Du siehst dir den Wegweiser noch einmal genau an und willst grade losgehen,");
        output("als ein kleiner alter Kobold aus dem Gebüsch stürzt.\" `n`#Sooooo.... du willst");
        output("wohl nach Sanela, mhhhh? Nun jaaaaa... dieser Wegweiser zeigt in die falsche");
        output("Richtung, und du wirst dich wohl verirren...Doch wenn du den Phoenix drei mal");
        output("besiegt hast, werde ich dir den Weg verraten...\"`8,spricht er zu dir.");
        output("Du überlegst, ob du ihm Glauben schenken solltest oder nicht, denn Versuchen kannst");
        output("du es ja mal.");
        addnav("Nach Sanela `b?`b","wanderweg.php?op=versuch");
        addnav("Zurück in den Wald","forest.php");
    }
}
if($_GET['op']=="versuch"){
    output("`8Du lachst den Kobold aus, welcher dich nur schadenfroh ansieht. Du gehst los und");
    switch(e_rand(1,6)){
        case 1:
        if($session['user']['turns']>$t){
            output("findest nach langer Zeit tatsächlich Sanela.`n`n`^Du gehst einen Umweg und brauchst");
            output("".$t." Runden.");
            $session['user']['turns']-=$t;
            $session['user']['sanela']['sanela']++;
            addnav("Sanela`b!`b","sanela.php");
        }else{
            output("bemerkst das deine Kraft nicht reichen wird um Sanela jemals zu finden. Du");
            output("bräuchtest `^ ".$t."`8 Runden. So schaffst du es nicht und landest wieder im Dorf.");
            addnav("Dorf","village.php");
            $session['user']['turns']=0;
        }
        break;
        case 2:
        case 3:
        case 4:
        output("erkennst nach langer Zeit einige Hütten. Du und stolperst auf sie zu. Entäuscht bemerkst");
        output("du, dass es die Häuser von dem Dorf sind aus dem du gekommen bist.`n`n");
        output("`^Du bist im Kreis gelaufen und verlierst alle Runden");
        $session['user']['turns']=0;
        addnav("Dorf","village.php");
        break;
        case 5:
        case 6:
        output("du bemerkst nach langer Zeit, dass du es wohl doch nicht schaffen wirst. Nach einigen qualvollen Stunden");
        output("in denen ein alter Koboldfluch an dir nagt rafft es dich dahin.`n`n`^Du bist tot.`nDu verlierst all dein Gold.`n");
        output("Du verlierst 5% deiner Erfahrung.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']*=0.95;
        addnav("Tägliche News","news.php");
        break;
    }
}
$session['user']['sanela']=serialize($session['user']['sanela']);
page_footer();
?>