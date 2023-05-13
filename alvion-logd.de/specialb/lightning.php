
<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if($_GET['op'] == '' || $_GET['op']== 'search'){ 
    $session[user][hitpoints] = round($session[user][hitpoints]*0.75);
    output("`6Ein Liedchen trällernd gehst Du durch die Berge, immer wachsam auf
    der Suche nach Monstern. Du bist gerade in einem absolut dunklen Teil des Bergwaldes, wo
    besonders hohe und dichte Bäume stehen. Als Du wieder zu einer Lichtung kommst, fällt Dir auf, dass es immer
    noch dunkel ist.`n`nDu schaust zum Himmel und siehst die Bescherung.`n`7Es sind
    schwarze Gewitterwolken aufgezogen.`^ \"Fängt bestimmt gleich an zu regnen\" `6denkst
    Du, als Du aus der Ferne auch schon das tiefe Grollen des Donners hörst.`n`nDu stehst immer noch auf der kleinen
    Lichtung, als ganz plötzlich ein enormer `^Blitz `6einschlägt.`n`nDer gewaltige Schlag
    wirft Dich um und Du `\$verlierst einige Lebenspunkte`6.`n`n`^Aber war das ein normaler Blitzschlag?`6`n`n
    Du vermutest, dass eine höhere Macht dahinter steckt, denn genauso plötzlich wie
    der Blitz eingeschlagen ist, sind die dunklen Wolken wieder verschwunden.`n`n`0");
    
    switch(e_rand(1,7)){
        case 1:
            output("`8Grübelnd setzt Du Deinen Weg fort.`0");
        break;
        case 2:
            output("`8Du bekommst 6 Level zu den Dunklen Künsten gutgeschrieben.`0");
            $session[user][darkarts]+=6;
            $session[user][darkartuses]+=2;
        break;         
        case 3:
            output("`8Du beherrscht die Mystik nun 6 Level besser.`0");
            $session[user][magic]+=6;
            $session[user][magicuses]+=2;
        break;
        case 4:
            output("`8Deine Diebesfähigkeiten verbessern sich um 6 Level.`0");
            $session[user][thievery]+=6;
            $session[user][thieveryuses]+=2;
        break;
        case 5:
            output("`8Deine Feuerkünste verbessern sich um 6 Level.`0");
            $session[user][fire]+=6;
            $session[user][fireuses]+=2;
        break;
        case 6:
            output("`8Deine Weiße Magie verbessert sich um 6 Level.`0");
            $session[user][wmagie]+=6;
            $session[user][wmagieuses]+=2;
        break;
         
        case 7:
            output("`8Grübelnd setzt Du Deinen Weg fort.`0");
        break;
    }
    $session[user][specialinc] = "";
}
?>

