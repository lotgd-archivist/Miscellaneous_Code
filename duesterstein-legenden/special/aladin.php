
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nDu bist schon viele Male hier vorbeigezogen, um mit Monstern zu kämpfen.
    Aber Du hast es noch nie vorher wargenommen: Ein `6güldenes `^Glimmern`0 etwas abseits
    des Weges. Sollte es sich um Gold handeln?`n
    Zielstrebig gehst Du durch das Unterholz zu der Stelle, die Arme zur Abwehr von
    den tief hängenden Ästen der Bäume voraus. Als Du dort ankommst, wo Du den Schein
    gesehen hast, entdeckst Du `7nichts ausser einer alten Öllampe`0.`n`n
    Sollte es eine optische Täuschung gewesen sein?`n`n
    Als Du die Lampe hochhebst, um sie näher zu untersuchen, fängt sie erneut an in
    einem `6gelbgoldenen `^Licht`0 zu scheinen. Die bauchige Lampe hat einen sehr schönen
    Verschluß an der Stelle, wo man das Lampenöl einfüllt. Plötzlich öffnet sich der
    Verschluß und über der Lampe erscheint ein `@kleiner Waldgeist`0, der Dich genauso
    erschrocken anschaut wie Du ihn.`n
    Du bist nicht sicher, wie Du reagieren sollst.`0");
    //abschluss intro
    addnav("Waldgeist ansprechen","forest.php?op=talk");
    addnav("Lampe mitnehmen","forest.php?op=take");
    addnav("Geist wieder einschliessen","forest.php?op=cage");
    addnav("Zurück in den Wald","forest.php?op=leave");
    $session[user][specialinc] = "aladin.php";
}
else if ($HTTP_GET_VARS[op]=="talk"){   // geist ansprechen
    output("`nDu sprichst den `@Waldgeist`0 an und fragst, was er von Dir will.`n
    \"Du hast mich befreit und zum Dank werde ich Dir etwas von meiner Kraft
    übertragen. Nachdem ich so lange eingeschlossen war, muss ich nur erstmal
    überlegen, wie genau derZauberspruch lautet...\" `@antwortet der Waldgeist`0.
    \"Na hoffentlich fällt es ihm ein\" denkst Du Dir. Du hast nämlich keine
    Lust auf einen schiefgegangenen Zauber.`n`n
    `@Der Waldgeist beginnt eine geheimnisvolle Formel zu sprechen.`0`n`n");
    switch(e_rand(1,5)){
        case 1:
        output("`6Du beginnst zu zittern und fällst wie vom Blitz getroffen um.
        Ständig siehst Du den Schein der Lampe vor Dir, was Dich in nächster
        Zeit etwas behindern wird.`0");
        $session[bufflist]['aladin'] = array("name"=>"`@Blendung",
                                             "rounds"=>20,
                                             "wearoff"=>"Du kannst wieder klar sehen.",
                                             "atkmod"=>0.8,
                                             "roundmsg"=>"`#Du siehst den Gegner nicht richtig.",
                                             "activate"=>"offense");
        break;
        case 2:
        output("`6Ohne ersichtlichen Grund fühlst Du Dich glücklich. Der goldene
        Schein der Lampe gibt Dir Kraft um echtes Gold im Kampf zu erbeuten.`0");
        $session[bufflist]['aladin'] = array("name"=>"`^Kraft des Goldes",
                                             "rounds"=>30,
                                             "wearoff"=>"Dein Golddurst ist gestillt.",
                                             "atkmod"=>1.2,
                                             "roundmsg"=>"`#Du willst das Gold des Gegners!",
                                             "activate"=>"offense");
        break;
        case 3:
        output("`6Neugierig stierst Du noch eine Zeit auf den Waldgeist, aber es
        tut sich nichts. Offenbar hat er den richtigen Zauberspruch vergessen.`0");
        break;
        case 4:
        output("`6Wartend schaust Du den Waldgeist an, ohne das etwas passiert. Dir
        wird langweilig und Du setzt Deinen Weg fort. Dabei bemerkst Du nicht, wie
        Dir eine goldene Aura folgt.`0");
        $session[bufflist]['aladin'] = array("name"=>"`^Goldene Aura",
                                             "rounds"=>30,
                                             "wearoff"=>"Die goldene Aura wird schwächer und löst sich auf.",
                                             "atkmod"=>1.1,
                                             "minioncount"=>1,
                                             "minbadguydamage"=>1,
                                             "maxbadguydamage"=>10,
                                             "effectmsg"=>"Die goldene Aura schlägt zu!",
                                             "activate"=>"offense");
        break;
        case 5:
        output("`6Als nichts weiter passiert, verlässt du enttäuscht den Waldgeist.
        Hinter Deinem Rücken probiert der Geist einen zweiten Zauberspruch und ist
        diesmal erfolgreich.`0");
        $session[bufflist]['aladin'] = array("name"=>"`@Waldgeist-Zauber",
                                             "rounds"=>10,
                                             "wearoff"=>"Der Geist verflüchtigt sich.",
                                             "atkmod"=>1.1,
                                             "minioncount"=>1,
                                             "mingoodguydamage"=>1,
                                             "maxgoodguydamage"=>$session['user']['level'],
                                             "effectmsg"=>"Der Waldgeist kämpft mit Dir!",
                                             "activate"=>"roundstart");
        break;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="take"){   // lampe nehmen
    output("`nDu steckst die Lampe einfach ein und läßt den `@Waldgeist`0 dort schweben
    wo er jetzt schwebt.`n`0");
    switch(e_rand(1,6)){
        case 1:
        case 2:
        case 3:
        output("`nDer `@Waldgeist`0 ist froh, dass Du sein Gefängnis mitgenommen hast
        und `@schenkt Dir zum Dank 2 Waldkämpfe.`0");
        $session[user][turns]+=2;
        break;
        case 4:
        case 5:
        case 6:
        output("`nObwohl der `@Waldgeist`0 lange in dieser Öllampe eingesperrt war, ist
        es doch sein Zuhause geworden. Er ist wirklich alles andere als begeistert,
        dass Du die Lampe einsteckst `@und nimmt Dir aus Rache 2 Waldkämpfe.`0");
        $session[user][turns]-=2;
        break;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="cage"){   // geist einschliessen
    output("`nAls der `@Waldgeist`0 bemerkt, dass Du ihn wieder einsperren willst,
    verflucht er Dich noch schnell bevor Du ihn zurück in die Öllampe steckst und
    den Verschluß wieder sicher anbringst.`n
    `@Sein Fluch haftet Dir heute trotzdem an.`0");
        $session[bufflist]['aladin'] = array("name"=>"`@Fluch des Waldgeistes",
                                             "rounds"=>50,
                                             "wearoff"=>"Du musst an den Waldgeist denken und bist abgelenkt.",
                                             "atkmod"=>0.8,
                                             "roundmsg"=>"`#Der Waldgeist hat Dir verziehen.",
                                             "activate"=>"offense");
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="leave"){   // einfach weitergehen
    output("`n`5Dir wird es zu unheimlich. Du nimmst die Beine in die Hand und rennst
    zurück zum Waldweg.");
    $session[user][specialinc]="";
}
?>


