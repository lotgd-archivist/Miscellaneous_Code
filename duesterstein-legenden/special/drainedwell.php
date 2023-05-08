
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();
//if ($session[user][superuser]!=4) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`3Auf Deinem Weg durch den Wald stösst Du auf einen alten Brunnen.`0
    Die gemauerte Einfassung des Brunnen ist bereits mit wildem Efeu überwuchert,
    Moos hat sich festgesetzt und einige seltene Waldblumen blühen wunderschön
    auf dem Brunnenrand. Ganz offensichtlich war schon lange niemand mehr hier.
    Dich erfreut der wildromantische Anblick. Aber irgendjemand sollte mal ein
    wenig Arbeit investieren, damit der Brunnen nicht verfällt.`0");
    output("`n`nDich packt die Neugier und Du trittst noch ein wenig näher. Jetzt
    bemerkst Du, dass der Brunnen kein Wasser führt. Was möchtest Du nun machen?`0");
    //abschluss intro
    addnav("in den Brunnen steigen","forest.php?op=climb");
    addnav("Waldblumen pflücken","forest.php?op=flowers");
    addnav("am Brunnen arbeiten","forest.php?op=work");
    addnav("`neinfach weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "drainedwell.php";
}
else if ($HTTP_GET_VARS[op]=="climb"){   // in den Brunnen steigen
    // Current balance
    // 37,5% find large treasure
    // 62,5% dead
    output("`nSchnell setzt Du Dich auf den Brunnenrand und benutzt Löcher und
    Vorsprünge im alten Schacht, um geschickt hinabzusteigen.`0");
    switch(e_rand(1,4)){
        case 1:
        case 2:
        case 3:
        output("Du bist froh, den Abstieg geschafft zu haben und schaust Dich im
        Dämmerlicht um. Du vermutest, daß dieser Brunnen früher einmal als
        Wunschbrunnen verwendet wurde, denn Du siehst es am Boden glitzern und
        funkeln.`0");
        $gold = e_rand($session[user][level]*125,$session[user][level]*500);
        $gem = e_rand(2,5);
        output("`n`nGeschwind sammelst Du `^$gold Gold`0 und `Q$gem Edelsteine`0 ein!`0");
        output("`n`nNachdem Du ein kleines Freudentänzchen aufgeführt hast, musst
        Du nun wieder den Brunnenschacht hinaufklettern. Dir wird klar, dass es
        mit Deinen neuen Reichtümern ungleich schwieriger wird.`0");
        switch(e_rand(1,2)){
            case 1:
            output("`n`nDu kletterst geschickt nach oben, aber dann hälst Du Dich an
            einem Vorsprung fest, der Dein Gewicht nicht tragen kann. Du verfluchst
            Deine Abenteuerlust, aber es ist zu spät. `$Du stürzt zurück in den Brunnen
            und stirbst dort in aller Einsamkeit.`0");
             $session[user][alive]=false;
            $session[user][hitpoints]=0;
            $session[user][gold] = 0;
            $session[user][gems] = 0;
            addnav("Tägliche News","news.php");
            addnews($session[user][name]." wurde vom alten Brunnen im Wald verschluckt.");
            break;
            
            case 2:
            output("`n`nDu erinnerst Dich gut an die Stellen im Brunnenschacht, die
            Dir Halt geben und so erreichst Du nach einiger Zeit ein wenig erschöpft,
            aber glücklich wieder den Brunnenrand.`0");
            output("`nEin prüfender Blick in Deine Taschen sagt Dir, dass Du nicht
            geträumt hast. Es ist wahr! `@Du setzt zufrieden Deinen Weg fort, auch
            wenn Dich die Kletterpartie einen Waldkampf gekostet hat.`0");
            $session[user][turns]-=1;
            $session[user][gold] += $gold;
            $session[user][gems] += $gem;
            addnews($session[user][name]." hebt unter Lebensgefahr einen Schatz von $gold Gold und $gem Edelsteinen aus dem Brunnen.");
            break;
        }
        break;
        
        case 4:
        output("`n`n`$ Als Du einen Moment nicht aufpasst, verlierst Du den Halt und
        stürzt tief in den Schacht. Das kann niemand überleben!`0");
        $session[user][alive]=false;
        $session[user][hitpoints]=0;
        $session[user][gold] = 0;
        $session[user][gems] = 0;
        addnav("Tägliche News","news.php");
        addnews($session[user][name]." wurde vom alten Brunnen im Wald verschluckt.");
        break;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="flowers"){   // Waldblumen pflücken
    // Current balance:
    // 30% -2 turn
    // 30% +1 turn and healing
    // 30% nothing
    // 10% + 1 specialty
    output("`nDu pflückst Dir einen Straus der seltenen Waldblumen und riechst
    voller Freunde dran. Plötzlich merkst Du, dass die Blumen nicht nur hübsch
    aussehen, sondern auch eine recht `9eigenartige`0 Wirkung auf Dich haben.`0");
    switch(e_rand(1,10)){
        case 1:
        case 2:
        case 3:
        output("`n`nDu fühlst Dich plötzlich matt und etwas benebelt. Du must
        Dich etwas ausruhen und `3verlierst deswegen 2 Waldkämpfe.`0");
        $session[user][turns]-=2;
        break;
        case 4:
        case 5:
        case 6:
        output("`n`nDu fühlst Dich belebt und machst Dich voller Abenteuerlust
        wieder auf den Weg. `3Du hast einen zusätzlichen Waldkampf erhalten und
        bist vollständig gesund.`0");
        $session[user][turns]+=1;
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
            $session[user][hitpoints]=$session[user][maxhitpoints];
        break;
        case 7:
        case 8:
        case 9:
        output("`n`nOder hast Du Dich getäuscht? Nein, von einer seltsamen Wirkung
        spürst Du `9nichts`0 mehr. `3Du gehst mit Deinen Blumen weiter.`0");
        break;
        case 10:
        output("`n`n ");
        increment_specialty();
        break;
        }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="work"){   // am Brunnen arbeiten
    // Current balance:
    // always -1 turn, +1 Gem, +1 specialty
    output("`n`3Statt einen Waldkampf zu führen, machst Du Dich mit Eifer daran, den
    Brunnen wieder herzurichten.`0 Du entfernst das Efeu, pflanzt die Blumen vom Brunnenrand
    in den fruchtbaren Waldboden und besserst geschickt das Mauerwerk aus. Du merkst
    garnicht, dass Du von einer `9Elfe`0 beobachtet wirst.`0");
    output("`n`nAls Du endlich fertig bist, spricht Dich die Elfe an:`0");
    output("`n`9\"Dieser Brunnen musste schon lange in Ordnung gebracht werden,
    nur konnte ich alleine die schwere Arbeit nicht verrichten.\"`0 Sie zeigt Dir ihre
    Dankbarkeit, indem Sie Dir 1 Edelstein schenkt. `9\"Und für Deinen weiteren Weg
    gebe ich Dir gerne etwas von meiner Kraft ab.\"`n`0");
    $session[user][gems]+=1;
    increment_specialty();
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`5Du verlässt den Brunnen und setzt lieber Deinen Weg fort.");
    $session[user][specialinc]="";
}
?>


