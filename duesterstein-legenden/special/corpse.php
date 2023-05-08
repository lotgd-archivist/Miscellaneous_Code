
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    $raceid = e_rand(1,5);
    switch ($raceid) {
        case 1: $race = "`1einer Elfe`0"; break;
        case 2: $race = "`2eines Zwerges`0"; break;
        case 3: $race = "`3eines Trolls`0"; break;
        case 4: $race = "`4eines Menschen`0"; break;
        case 5: $race = "`5eines Orks`0"; break;
    }
    output("`nEigentlich dachtest Du, dies wird ein schöner Tag.... Aber dann findest
    Du auf Deinem Streifzug durch den Wald die Überreste ".$race.". Offensichtlich
    ein Opfer erbitterter Kämpfe im Wald. `nDu untersuchst die Fundstelle und kommst
    zu dem Schluß, dass vor Dir noch niemand hier war.`n`n`9Was wirst Du tun?`0");
    //abschluss intro
    addnav("Opfer begraben","forest.php?op=bur&race=$raceid");
    addnav("nach Wertsachen suchen","forest.php?op=exam&race=$raceid");
    addnav("Stadtwache holen","forest.php?op=call&race=$raceid");
    addnav("zurück in den Wald","forest.php?op=back");
    $session[user][specialinc] = "corpse.php";
}
else if ($HTTP_GET_VARS[op]=="exam"){   // opfer durchsuchen
    $raceid = $HTTP_GET_VARS[race];
    $spec = 0;
    switch ($raceid) {
        case 1:
        $race = "`1der Elfe`0";
        $text = "Was glaubst Du, bei so einer kleinen und zerbrechlichen Elfe zu finden?
        Richtig, auch `Qnach einiger Suche: Nichts!`0";
        break;
        
        case 2:
        $race = "`2des Zwerges`0";
        $gold = e_rand (100,500);
        $gem = e_rand (0,2);
        $text = "Da kennst natürlich die Affinität von Zwergen zu Reichtümern und so
        erwartest Du eigentlich etwas zu finden. `^Und richtig, nach einiger Zeit bemerkst
        Du $gold Goldstücke";
        if ( $gem > 0 ) {
            $text = $text." und $gem Edelsteine, die Du Dir gleich einsteckst.`0";
        }
        else {
            $text = $text.", die Du Dir gleich einsteckst.`0";
        }
        $session[user][gold]+= $gold;
        $session[user][gems]+= $gem;
        break;
        
        case 3:
        $race = "`3des Trolls`0";
        $text = "Dir ist die Stärke der Trolls bekannt und Du schaust besonders intensiv
        nach Dingen, die Dir im Kampf helfen würden.";
        $spec+=1;
        break;
        
        case 4:
        $race = "`4des Menschen`0";
        $hp = round($session[user][experience] * 0.025);
        $text = "Lange suchst Du in den übrig gebliebenen Habseligkeiten, ohne etwas
        brauchbares zu finden. Du schaust Dich nochmal genau um. Hier liegen auch keine
        Rüstung oder Waffen. Du kommst zu dem Schluß, dass es sich nicht um die Überreste
        eines jungen Kriegers handelt, sondern eher um die eines alten Mannes.`nDu kannst
        nicht erklären warum, aber ein wenig seiner Lebenserfahrung wird für Dich zugänglich.
        `^Du erhältest $hp Experience.";
        $session[user][experience]+= $hp;
        break;

        case 5:
        $race = "`5des Orks`0";
        $text = "Vorsichtig näherst Du Dich den Überresten der Kreatur. Es ekelt Dich an,
        aber tapfer durchsuchst Du alles. `QLeider findest Du nichts nützliches.";
        break;
    }
    output("`nEifrig machst Du Dich daran, die Überreste $race zu durchsuchen.`n`n
    $text`n`n");
    if ( $spec == 1 ) increment_specialty();
    if ( $raceid != 5 ) {
        output("`n`nMit der Suche offenbarst Du Deine pietätlose Gier.
        `QDu verlierst deshalb 3 Charmpunkte.`0");
        $session[user][charm]-=3;
    }
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="bur"){   // opfer begraben
    $raceid = $HTTP_GET_VARS[race];
    switch ($raceid) {
        case 1: $turn = 1; break;
        case 2: case 3: $turn = 2; break;
        case 4: case 5: $turn = 3; break;
    }
    if ( $turn == 1 ) $text = "Waldkampf"; else $text = "Waldkämpfe";
    output("`nDu nimmst Dir die Zeit, das Opfer würdevoll zu begraben. `QDabei verlierst
    Du $turn $text.`^`n`nDie Götter sind sehr erfreut über Dich, Du bekommst $turn Charmpunkte.
    Ausserdem schenken Dir die Götter einen permanenten Lebenspunkt und Du wirst komplett
    geheilt!`0");
    $session[user][turns]-= $turn;
    $session[user][charm]+= $turn;
    $session[user][maxhitpoints]+=1;
    if ($session[user][hitpoints] < $session[user][maxhitpoints])
       $session[user][hitpoints]=$session[user][maxhitpoints];
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="back"){   // zurück in den Wald
    $rand = e_rand(1,3);
    output("`n`2Dein Fund ist Dir genauso unheimlich wie die Umgebung hier. Du
    siehst zu, dass Du schnell weiter kommst.`0");
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="call"){   // stadtwache holen
    $raceid = $HTTP_GET_VARS[race];

    switch ($raceid) {
        case 1: case 2: case 3: case 4:
        output("`nDu läufst in die Stadt, sagst der Wache bescheid, führst sie zur Fundstelle
        und beantwortest geduldig all ihre Fragen.`0");
        $chance = e_rand(1,10);
        if ( e_rand >= 8 ) {
            output("`n`nLeider kommt die Stadtwache zu dem Schluß, dass Du selbst für
            den Tod des Opfers verantwortlich bist. Richtig nachweisen können sie es
            Dir zwar nicht, aber sie geben Dir sehr deutlich zu verstehen, dass Du Dich
            heute lieber nicht mehr im Wald blicken lassen solltest.`n`n
            Du hast heute keine Waldkämpfe mehr.`0");
            $session[user][turns]=0;
        }
        else {
            $hp = round($session[user][experience] * 0.025);
            output("`n`nDie Stadtwache bandankt sich bei Dir. Nur weil Du sie herbeigeholt
            hast, kann dieser Fall nun von den Behörden verfolgt werden.`n`n
            `6Für dieses umsichtige Verhalten bekommst Du $hp Experience.`0");
            $session[user][experience]+= $hp;
        }
        break;

        case 5:
        $gold = round($session[user][gold] * 0.40);
        output("`nAm Ende stellt die Stadtwache fest, dass es sich um ein Ork
        gehandelt haben muss. Plötzlich wird das Gespräch rauer. `Q\"Was fällt Dir ein,
        uns für sowas zu rufen\" schimpfen sie`0 mit Dir, \"dafür sind wir doch nun
        wirklich nicht zuständig!\" musst Du Dir anhören.`n`n");
        if ( $gold > 0 ) {
            output("`QFür den Fehlalarm der Stadtwache erhälst Du eine Rechnung über
            $gold Gold, die sofort bezahlt werden muss.`0");
            $session[user][gold]-= $gold;
        }
        break;
    }
    $session[user][specialinc] = "";
}
?>


