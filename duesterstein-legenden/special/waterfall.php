
<?
// found in www
// translation and mods by gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`3Du bemerkst einen kleinen Trampelpfad, der tiefer in den Wald
    hineinführt.`0 Der Weg ist schon ein wenig zugewachsen, große Farne wiegen
    sich in einer leichten Brise. Du schaust Dir den Pfad genauer an und bemerkst
    Fußabdrücke im weichen Waldboden. Merkwürdig. Du siehst nur Fußspuren, die
    dem Pfad folgen. `4Nichts deutet darauf hin, dass hier jemand zurückgekehrt ist....`0");
    output("`n`nWagemutig folgst Du dem Weg und nach kurzer Zeit hörst Du Wasser
    rauschen.`0");
    //abschluss intro
    addnav("Pfad weiter gehen","forest.php?op=trail");
    addnav("Zurück in den Wald","forest.php?op=leave");
    $session[user][specialinc]="waterfall.php";

}
else if ($HTTP_GET_VARS[op]=="trail"){   // pfad weiter folgen
    output("`n`3Du folgst dem Trampelpfad weiter in den Wald hinein...`0");
    switch (e_rand(1,12)) {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        output("`n`2Nach einigen Stunden verläufst Du Dich im Wald, ohne etwas zu
        finden.`n`n`QDu verlierst einen Waldkampf und findest schließlich doch den
        Hauptweg wieder.");
        $session[user][turns]--;
        break;
        
        case 6:
        case 7:
        case 8:
        output("`nNach wenigen Minuten kommst Du an einen Wasserfall, der sich gut
        versteckt von einem hohen Felsen malerisch in die Tiefe stürzt.`0");
        output("`n`n`8Dann bemerkst Du auch einen kleinen Sims in der Felswand hinter
        dem Wasserfall.`0 Willst Du den Sims erklimmen?`0");
        addnav("Erklimme den Sims","forest.php?op=ledge");
        addnav("Zurück in den Wald","forest.php?op=leaveleave");
        $session[user][specialinc]="waterfall.php";
        break;
        
        case 9:
        case 10:
        case 11:
        case 12:
        output("`nNach wenigen Minuten kommst Du an einen Wasserfall, der sich gut
        versteckt von einem hohen Felsen malerisch in die Tiefe stürzt.`0");
        output("`n`n`7Du bist mittlerweile sehr durstig und fragst Dich, ob man das
        Wasser trinken kann.`0");
        addnav("Durst stillen","forest.php?op=drink");
        addnav("Zurück in den Wald","forest.php?op=leaveleave");
        $session[user][specialinc]="waterfall.php";
        break;
    }
}
else if ($HTTP_GET_VARS[op]=="ledge"){   // Sims erklimmen
    $sims = e_rand(1,11);
    switch ($sims) {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        output("`nVorsichtig erklimmst Du die Felswand hinter dem Wasserfall, immer
        bedacht, nicht von dem schmalen Sims abzurutschen.`0");
        $gems = e_rand(1,2);
        if ( $gems == 1 ) $text = "einen Edelstein."; else $text = "zwei Edelsteine.";
        output ("`n`nDer Wasserfall lässt nur diffuses Licht durchscheinen, aber
        vielleicht ist es gerade das, was Dich auf ein funkeln aufmerksam macht.
        Du findest `9$text`0");
        $session[user][gems] += $gems;
        $session[user][specialinc]="";
        break;
        
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        $losthps = round($session[user][hitpoints]*.4);
        $session[user][hitpoints]-= $losthps;
        output("`nVorsichtig erklimmst Du die Felswand hinter dem Wasserfall, aber
        Du bist nicht vorsichtig genug. `^Du stürzt ab!`Q`n`nDeine Gesundheit ist um
        $losthps Lebenspunkte abgesackt.`0");
        if ($session[user][gold]>0) {
            $gold = round($session[user][gold]*.2);
            output("`nBei dem Sturz sind Dir leider auch $gold Goldstücke aus der
            Tasche gefallen.`0");
            $session[user][gold] -= $gold;
        }
        $session[user][specialinc]="";
        break;
        
        case 11:
        output("`nAls Du den Sims in purer Abenteuerlust hinauf stürmst, verlierst
        Du den Halt. Du schlägst hart gegen die Felswand und plumpst in das Becken
        am Fusse des Wasserfalls. `QDu bist bewußtlos und ertrinkst jämmerlich.`0");
        output("`n`nDu kannst morgen weiterspielen!`0");
        $session[user][turns]=0;
        $session[user][hitpoints]=0;
        $session[user][alive] = false;
        addnews($session[user][name]."ist jämmerlich im Wasserfall ertrunken.");
        addnav("Daily News","news.php");
        $session[user][specialinc]="";
        break;
    }
}
else if ($HTTP_GET_VARS[op]=="drink"){   // vom Wasser trinken
     $drink = e_rand(1,6);
     switch ($drink) {
         case 1:
         case 2:
         case 3:
         output("`n`2Du trinkst vom Wasserfall und fühlst Dich gleich erfrischt.`n`n");
         output("`^Du bist vollständig regeneriert!`0");
         if ($session[user][hitpoints] < $session[user][maxhitpoints])
            $session[user][hitpoints]=$session[user][maxhitpoints];
         break;
         
         case 4:
         output("`nDu gehst zu dem Becken am Fuß des Wasserfalls und trinkst gierig von
         dem klaren Wasser. Mit jedem Schluck fühlst Du ein warmes kribbeln im Bauch,
         daß Dir sehr angenehm ist. Du fühlst Dich vollständig erfrischt und gesünder
         als jemals zuvor.`0");
         output("`n`n`^Du hast einen permanten Lebenspunkt erhalten und bist vollständig
         regeneriert!`0");
         $session[user][maxhitpoints]+=1;
         if ($session[user][hitpoints] < $session[user][maxhitpoints])
            $session[user][hitpoints]=$session[user][maxhitpoints];
         break;

         case 5:
         case 6:
         output("`nDu trinkst hastig von dem klaren Wasser, dass Dir aber irgendwie
         nicht zu bekommen scheint. Du hast schreckliches Sodbrennen und musst Dir
         dagegen erstmal ein paar Kräuter sammeln.`n`n`QDu verlierst einen Waldkampf.`0");
         $session[user][turns] -= 1;
         break;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="leave") {   // zurück in den Wald
    output("`nDu schaust Dir nocheinmal den Trampelpfad an. Nein, hier ist offenbar
    wirklich niemals jemand zurückgekommen. `1Ein kalter Schauer jagt über Deinen Rücken
    und Dir fällt es leicht, lieber dem Hauptweg weiter zu folgen.`0");
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="leaveleave") {   // zurück 2. stufe
    output("`nDu denkst, dass man sich ja auch mal zurückhalten kann. Du kehrst auf
    den Hauptweg zurück, wirst das aber niemanden erzählen. `!Schließlich bist Du ja
    nicht feige...`0");
    $session[user][specialinc]="";
}
?>


