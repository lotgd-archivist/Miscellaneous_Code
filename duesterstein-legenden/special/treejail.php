
<?
//////////////////////////
// Make By Kev          //
// 25.07.2004           //
// Copyright by Kev     //
// v0.2 Der Baum        //
// Idea by Hadriel      //
//////////////////////////
// some adjustments by gargamel @ www.rabenthal.de
// balancing: fewer rewards, fewer punishment
//
if (!isset($session)) exit();


if ($HTTP_GET_VARS[op]==""){
    output("`n`2`c`bDer Baum`b`c `n `n");
    output("`c`2Du gehst durch den Wald, plötzlich erreichst Du eine Kreuzung...`n`c");
    output("`cEs sind dort 3 Wege... `n`@Aber einer führt ins Verderben, also überlege gut, welchen Du nimmst...`c");
    output("`n`c`2Wolang willst du gehen???`n`c`0");

    addnav("Links","forest.php?op=ask");
    addnav("Grade aus","forest.php?op=ask");
    addnav("Rechts","forest.php?op=ask");
    addnav("Zurück in den Wald","forest.php?op=leave");

    $session[user][specialinc] = "treejail.php";
}

else if ($HTTP_GET_VARS[op]=="ask"){
    $rand = e_rand(1,6);
    switch ($rand){
        case 1:
        output("`2Du hast den falschen Weg gewählt! Dies war der Weg in die
        Unterwelt!`n
        `QDu verlierst Dein Leben und Dein Gold. Du kannst morgen wieder kämpfen.`0");
        $session[user][hitpoints]=0;
        $session[user][gold]=0;
        $session[user][alive]=false;
        $session[user][specialinc]="";
        addnav("Tägliche News","news.php");
        addnews($session[user][name]." `2wurde tot in der Nähe eines modrigen Baumes gefunden!`0");
        break;
        
        case 2:
        case 3:
        case 4:
        case 5:
        output("`2Du kommst zu einem Baum, er sieht etwas Modrig aus...`n
        Du hörst eine Stimme. Du denkst, sie kommt aus dem Baum, Du bist Dir aber nicht
        sicher...Obwohl...Da ist ein Schlitz im Stamm...`n
        Was willst Du tun?`n`0");
        addnav("Die Person Retten","forest.php?op=lance");
        addnav("Weitergehen","forest.php?op=lance1");
        $session[user][specialinc] = "treejail.php";
        break;
        
        case 6:
        output("`2Du hast den falschen Weg gewählt! Du benötigst die Zeit von 5
        Waldkämpfen um wieder zurück auf den Hauptweg zu kommen!`n`0");
        $session[user][turns]-=5;;
        $session[user][specialinc]="";
        break;
    }
}

else if ($HTTP_GET_VARS[op]=="lance"){
    $reward = round($session[user][experience] * 0.02);
    output("`2Du fängst sofort an die vermutete Person zu Retten und versuchst,
    den Schlitz größer zu Kriegen`n
    Als endlich der Schlitz groß genug ist kommt eine Frau dahinaus...`n
    `@\"Danke, Du hast mich gerettet! Ich wurde in den Baum eingesperrt und war
    viele Jahre dort drin, aber niemand wollte mich retten...`n
    Ich werde mich für Deine Hilfe bei Dir bedanken...\"`n`n
    `2Du bekommst für deine Hilfe `^500 Gold `2und `^$reward Erfahrungspunkte`2!`n`0");
    $session[user][gold]+=500;
    $session[user][experience] += $reward;
    $session[user][specialinc] = "";
}

else if ($HTTP_GET_VARS[op]=="lance1"){
    switch ( e_rand(1,3) ){
        case 1:
        case 2:
        output("`2Du gehst einfach weiter, da sagt eine Stimmte...`n
        `@Du hast der Frau, die im Baum ist, nicht geholfen! Ich werde Dich dafür
        bestrafen!`n`n
        `2Du Verlierst alle Deine Lebenspunkte, Du darfst morgen weiterkämpfen...`n`0");
        $session[user][hitpoints]=0;
        $session[user][alive]=false;
        $session[user][specialinc]="";
        addnav("Tägliche News","news.php");
        addnews($session[user][name]." `2wurde tot in der Nähe eines modrigen Baumes gefunden!`0");
        break;
        case 3:
        output("Du gehst weiter deines Weges");
    }
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="leave"){
    output("`2Du traust Dich nicht so recht einen Weg auszusuchen und gehst deshalb
    den Weg, den Du gekommen bist, wieder zurück.`0");
    $session[user][specialinc] = "";
}
?>


