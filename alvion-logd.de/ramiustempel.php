
<?php
// Idee & Umsetzung
// Morpheus aka Apollon
// für LOGD.at 2005
// gewitmet meiner über alles geliebten Blume
require_once "common.php";
page_header("Tempel des Ramius");

$cost10 = (10);
$cost20 = (20);
$cost50 = (50);
$cost100 = (100);

if ($_GET['op']==""){
    output("`7`b`cTempel des Ramius`c`b");
    output("`n<table align='center'><tr><td><IMG SRC=\"images/tempel.jpg\"></tr></td></table>`n",true);
    output("`3Du betrittst einen Tempel, der recht finster ist und von vielen `TFacke`qln `3erleuchtet wird in seinem Inneren.");
    output("`3Die Halle des Tempels wirkt in diesem Schummerlicht unendlich groß, in ihrer Mitte kannst Du eine Statue des `5Ramius `3erkennen und davor einen `4Altar`3, vor dem ein `%Priester `3steht, der Dich näher heran winkt.`n");
    output("`3Vorsichtig trittst Du zu ihm vor den Altar: `7Sei mir gegrüßt, `6".$session['user']['name']."`7, Du bist also gekommen, weil Du gerne von der Welt der Lebenden aus die Gnade des großen `5Ramius `7erflehen willst.`n");
    output("`3Bei seinen Worten fröstelt Dich etwas, denn der `%Priester `3wirkt wie ein Geschöpf des `5Ramius`3, doch Du nickst, denn dies war Deine Absicht.`n");
    output("`7Nun `3fährt er fort `7das ist kein Problem, sofern Du genug `6Edelsteine `7besitzt, um den Preis zu bezahlen.`n");
    output("`3 Er sieht Dich durchdringend an: `610 Gefallen `7kosten Dich `6$cost10 Edelsteine`7, wieviel Gefallen möchtest Du kaufen?.`n");
        addnav("Anzahl/Kosten");
        addnav("`610 `%Gefallen - `6($cost10 Steine)","ramiustempel.php?op=10");
        addnav("`620 `%Gefallen - `6($cost20 Steine)","ramiustempel.php?op=20");
        addnav("`650 `%Gefallen - `6($cost50 Steine)","ramiustempel.php?op=50");
        addnav("`6100 `%Gefallen - `6($cost100 Steine)","ramiustempel.php?op=100");
     }
else if ($_GET['op']=="10"){
    if($session[user][gems]>=$cost10){
        output("`n`3Er nimmt Deine `6 $cost10 Edelsteine `3entgegen und wirft ihn in den Schlitz einer `TTruhe`3, die zu Füßen der `5Statue `3auf dem Altar steht, hebt dann seine Arme und blickt zur `5Statue `3auf, von deren Augen ein `^bl`6it`^za`6rt`^ig`6es `3Leuchten zu sehen ist.`n");
        output("`3Der Priester nimmt die Arme wieder herab, dreht sich um und blickt Dich an: `7Deine Bezahlung wurde akzeptiert und Dein Wunsch erfüllt, Dir wurden `610 Gefallen`7 bei `5Ramius `7gewährt.");
        $session['user']['deathpower']+=10;
        $session['user']['gems']-=$cost10;
         addnav("Noch mehr Gefallen kaufen", "ramiustempel.php");
    }else{
        output("`n`3 Er sieht Dich durchdringend an und beginnt dann schauerlich zu lachen:`7Du kommst hier her, ohne auch nur 1 Edelstein bei Dir zu haben, möchtest Du den Zorn des `5Ramius `7herausfordern?`n");
        output("`3 Mit diesen Worten wendet er sich von Dir ab und der Statue des `5Ramius `3wieder zu.`n");
        }
      }
else if ($_GET['op']=="20"){
    if($session[user][gems]>=$cost20){
        output("`n`3Er nimmt Deine `6 $cost20 Edelsteine `3entgegen und wirft sie in den Schlitz einer `TTruhe`3, die zu Füßen der `5Statue `3auf dem Altar steht, hebt dann seine Arme und blickt zur `5Statue `3auf, von deren Augen ein `^bl`6it`^za`6rt`^ig`6es `3Leuchten zu sehen ist.`n");
        output("`3Der Priester nimmt die Arme wieder herab, dreht sich um und blickt Dich an: `7Deine Bezahlung wurde akzeptiert und Dein Wunsch erfüllt, Dir wurden `620 Gefallen`7 bei `5Ramius `7gewährt.");
        $session['user']['deathpower']+=20;
        $session['user']['gems']-=$cost20;
         addnav("Noch mehr Gefallen kaufen", "ramiustempel.php");
    }else{
        output("`n`3 Er sieht Dich durchdringend an und beginnt dann schauerlich zulachen: `7Du willst Gefallen kaufen ohne genug `6Edelsteine `3zu haben, möchtest Du den Zorn des `5Ramius `7herausfordern?`n");
        output("`3 Mit diesen Worten wendet er sich von Dir ab und der Statue des `5Ramius `3wieder zu.`n");
        }
      }
else if ($_GET['op']=="50"){
    if($session[user][gems]>=$cost50){
        output("`n`3Er nimmt Deine `6 $cost50 Edelsteine `3entgegen und wirft sie in den Schlitz einer `TTruhe`3, die zu Füßen der `5Statue `3auf dem Altar steht, hebt dann seine Arme und blickt zur `5Statue `3auf, von deren Augen ein `^bl`6it`^za`6rt`^ig`6es `3Leuchten zu sehen ist.`n");
        output("`3Der Priester nimmt die Arme wieder herab, dreht sich um und blickt Dich an: `7Deine Bezahlung wurde akzeptiert und Dein Wunsch erfüllt, Dir wurden `650 Gefallen`7 bei `5Ramius `7gewährt.");
        $session['user']['deathpower']+=50;
        $session['user']['gems']-=$cost50;
         addnav("Noch mehr Gefallen kaufen", "ramiustempel.php");
    }else{
        output("`n`3 Er sieht Dich durchdringend an und beginnt dann schauerlich zulachen: `7Du willst Gefallen kaufen ohne genug `6Edelsteine `3zu haben, möchtest Du den Zorn des `5Ramius `7herausfordern?`n");
        output("`3 Mit diesen Worten wendet er sich von Dir ab und der Statue des `5Ramius `3wieder zu.`n");
        }
      }
else if ($_GET['op']=="100"){
    if($session[user][gems]>=$cost100){
        output("`n`3Er nimmt Deine `6 $cost100 Edelsteine `3entgegen und wirft sie in den Schlitz einer `TTruhe`3, die zu Füßen der `5Statue `3auf dem Altar steht, hebt dann seine Arme und blickt zur `5Statue `3auf, von deren Augen ein `^bl`6it`^za`6rt`^ig`6es `3Leuchten zu sehen ist.`n");
        output("`3Der Priester nimmt die Arme wieder herab, dreht sich um und blickt Dich an: `7Deine Bezahlung wurde akzeptiert und Dein Wunsch erfüllt, Dir wurden `6100 Gefallen`7 bei `5Ramius `7gewährt.");
        $session['user']['deathpower']+=100;
        $session['user']['gems']-=$cost100;
         addnav("Noch mehr Gefallen kaufen", "ramiustempel.php");
    }else{
        output("`n`3 Er sieht Dich durchdringend an und beginnt dann schauerlich zulachen: `7Du willst Gefallen kaufen ohne genug `6Edelsteine `3zu haben, möchtest Du den Zorn des `5Ramius `7herausfordern?`n");
        output("`3 Mit diesen Worten wendet er sich von Dir ab und der Statue des `5Ramius `3wieder zu.`n");
        }
      }
addnav("`bZurück`b");
addnav("C?Zurück zum Club", "rock.php");
addnav("Z?Zurück zum Dorf", "village.php");

page_footer();
?>

