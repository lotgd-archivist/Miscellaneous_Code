
<? 
// based upon an idea of Sixf00t4 for sixf00t4.com/dragon
// translation, modification by gargamel @ www.rabenthal.de

if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){ 
    output("`nAuf Deinem Weg durch den Wald musst Du eine `!Schlucht`0 überqueren. Du stehst vor einer wenig vertrauenswürdigen
    Hängebrücke. Die Halteseile sehen durch den langen Einfluss von Wind und Wetter schon arg angegriffen aus, der Plankenweg 
    hat einige sichtbare Löcher. Bestimmt sind da auch ein paar Bretter morsch.`n
    Du bist wirklich unschlüssig, ob Du die Brücke benutzen sollst. Leider weisst Du nicht, wie lange die Suche nach einer 
    anderen Möglichkeit zur Überquerung der Schlucht dauert....`0");
    addnav("Brücke nehmen","forest.php?op=take"); 
    addnav("Alternative suchen","forest.php?op=search"); 
    $session[user][specialinc] = "bridge.php"; 
    } 
else if ($HTTP_GET_VARS[op]=="take"){ 
    switch(e_rand(1,10)){ 
        case 1: 
        case 2: 
        case 3: 
        case 4: 
        output("`nDu betrittst die Brücke und gehst mutig voran. `7Plötzlich fängt die Brücke unter Deinen rhytmischen Schritten an zu schwingen.
        Du verlierst den Halt und musst erstmal warten, bis sich die Brücke wieder stabilisiert hat. `9Nach diesem Erlebnis hast Du genug
        und Du entscheidest Dich für eine dritte Möglichkeit: Du gehst einfach dahin zurück, wo Du hergekommen bist.`n`n
        `9Du verlierst einen Waldkampf.`0");
        $session[user][turns]--; 
        break; 
        case 5: 
        case 6: 
        case 7: 
        case 8: 
        if (e_rand(0,1)==0)    { 
            output("`nDu entschliesst Dich die Brücke zu nehmen. Deine ersten Schritte sind noch sehr vorsichtig, Du prüfst durch beherztes 
            Aufstampfen, ob alles stabil ist. Nun, hier am Brückenanfang kannst Du ja auch noch nicht tief fallen... `n
            Dann gehst Du mutigen Schrittes über die Brücke. Plötzlich bleibt Dir das Herz stehen: `8Eine Planke gibt nach und Du brichst
            durch. `0Glücklicherweise nur mit einem Bein, mit dem anderen steht Du fest und kannst so einen Absturz vermeiden. `3Ausser einer
            kleinen Zerrung, die Dir auf die Gesundheit schlägt, nimmst Du keinen Schaden und erreichst die gegenüberliegende Seite ohne 
            weitere Probleme.`0");
            $session[user][hitpoints]= round($session[user][hitpoints] * 0.85);
        }
        else {
            output("`nAlle Gefahren ignorierend stürmst Du über die Brücke. Am anderen Ende angekommen bist Du froh, dass Du es geschafft hast.`n
            `4Für Deinen Mut bekommst Du einen Charmpunkt.`0");
            $session[user][charm]++; 
            if (e_rand(0,1)==0){ 
                output("`6Am anderen Ende der Brücke findest Du `^1 Edelstein.`0");
                $session[user][gems]++; 
            } 
        }
        break; 
        case 9: 
        case 10:
        output("`nSorgfältig überprüfst Du nocheinmal die Seile und betrittst dann vorsichtig die Brücke. Vor jedem Schritt testest Du erst, ob 
        die nächste Planke Dich tragen wird. `2Bravo! Nur so kann man so eine Brücke besiegen.`n`n
        `@Du bekommst 400 Erfahrungspunkte.`0");
        $session[user][experience]+=400;
        break;
    }
    $session[user][specialinc]=""; 
}    
else if ($HTTP_GET_VARS[op]=="search"){
    $turns = e_rand(0,2);
    switch($turns){ 
        case 0:
        output("`nNach einer schier endlosen Suche findest Du einen riesigen Baum, der offenbar im letzten Sturm umgefallen ist. Zu Deinem Glück
        liegt der mächtige Stamm quer über der Schlucht und bietet Dir so einen sicheren Weg hinüber.`n
        `QDurch die lange Suche verlierst Du 2 Waldkämpfe.`0");
        $session[user][turns]-=2;
        break;

        case 1: 
        output("`nDu suchst eine ganze Weile, bis Du eine andere Brücke entdeckt hast. Du überquerst sicher die Schlucht, `%hast aber durch die
        Suche einen Waldkampf verloren.`0");
        $session[user][turns]--;
        break; 

        case 2:
        output("`nDu schaust Dich nach einer Alternative um und entdeckst einen kleinen Trampelpfad, der direkt hinter der Brücke startet. Du
        folgst dem Weg. Er führt Dich in engen Serpentinen die Schlucht hinunter und auf der anderen Seite wieder hinauf.`n
        `6Du hast, ohne Zeit zu verlieren, die Schlucht durchquert und setzt Deinen Weg fort.`0");
        break;
    }
    $session[user][specialinc]=""; 
} 
?> 

