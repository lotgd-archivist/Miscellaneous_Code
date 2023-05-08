
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    $session[user][hitpoints] = round($session[user][hitpoints]*0.75);
    output("`nEin Liedchen trällernd gehst Du durch den Wald, immer wachsam auf
    der Suche nach Monstern. Du bist gerade in einem dunklen Teil des Waldes wo
    besonders hohe und dichte Bäume stehen. Deswegen konntest Du es auch noch nicht
    bemerken. Als Du wieder zu einer Lichtung kommst, fällt Dir auf, dass es immer
    noch dunkel ist. Du schaust zum Himmel und siehst die Bescherung.`n`^Es sind
    schwarze Gewitterwolken aufgezogen.`0 \"Fängt bestimmt gleich an zu regnen\" denkst
    Du, als Du aus der Ferne auch schon das tiefe Grollen des Donners hörst. Deine
    Laune wird nicht besser. `QGewitter....`9`n`nDu stehst immer noch auf der kleinen
    Lichtung als ganz plötzlich ein gewaltiger Blitz einschlägt.`0 Der gewaltige Schlag
    wirft Dich um und Du `Qverlierst Lebenspunkte.`0`n`nAber war das ein normaler Blitzschlag?`n`n
    Du vermutest, dass eine höhere Macht dahinter steckt, denn genauso plötzlich wie
    der Blitz eingeschlagen ist, sind die dunklen Wolken wieder verschwunden.`n`n`0");

    switch(e_rand(1,4)){
        case 1:
        output("`8Du bekommst 6 Level in dunklen Künsten gutgeschrieben.`0");
        $session[user][darkarts]+=6;
        $session[user][darkartuses]+=2;
        break;
        
        case 2:
        output("`8Du beherrscht die Mystik nun 6 Level besser.`0");
        $session[user][magic]+=6;
        $session[user][magicuses]+=2;
        break;
        
        case 3:
        output("`8Deine Diebesfähigkeiten verbessern sich um 6 Level.`0");
        $session[user][thievery]+=6;
        $session[user][thieveryuses]+=2;
        break;
        
        case 4:
        output("`8Du machst Dich wieder auf den Weg.`0");
        break;
    }
    $session[user][specialinc] = "";
}
?>


