<?
/***
*  muuuh.php, 13.08.2009, LoGD 0.9.7
*  von Charon, www.plueschdrache.de  
*
*  Ein nicht ganz ernstes Special, inspiriert durch ein RL-Erlebnis einer Spielerin. *g*
*  Man kann es nicht ganz so einfach komplett umgehen, aber dafür ist es auch nicht tötlich.
***/

if (!isset($session)) exit();

if ($_GET['op']=="")
{
    output("`7`nDer dunkle Wald lichtet sich plötzlich und kurz darauf stehst du am Waldrand vor einer üppigen Wiese. Etwas entfernt erkennst du einen alten
        Bauernhof und als dein Blick zur Seite schweift, siehst du einige Kühe friedlich vor sich hin grasen. Eine Kuh steht etwas abseits von der Herde und grast 
        nur etwa 30 Schritte von dir entfernt. Sie scheint dich bisher nicht bemerkt zu haben oder du bist ihr einfach egal.");
    $session['user']['specialinc'] = "muuuh.php";
    addnav("Weitergehen","forest.php?op=go");
    addnav("Zurück in den Wald","forest.php?op=forest");
}
elseif ($_GET['op']=="go")
{
    output("`7`nDu gehst wenige Schritte auf die Kuh zu, ein kräftiges braun-weißes Tier, als diese nun ihren Kopf hebt und ein lautes \"`9Muuuh`7\" ertönt. Regungslos
        starrt sie dich mit ihren großen schwarzen Augen an. Was willst du nun tun?");
    $session['user']['specialinc'] = "muuuh.php";
    addnav("Zurückstarren","forest.php?op=stare");
    addnav("Lieber zurück in den Wald","forest.php?op=leave");        
}
elseif ($_GET['op']=="forest")
{
    output("`7`nHier gibts es wohl nichts von Interesse und so willst du dich wieder auf den Weg in den Wald machen. Doch kaum bist du zwei Schritte gegangen, knackt ein
        kleiner Ast unter deinen Füßen und du hörst ein lautes \"`9Muuuh`7\" hinter deinem Rücken. Du drehst dich um und bemerkst, dass die Kuh dich völlig regungslos 
        mit ihren großen schwarzen Augen anstarrt. Was willst du nun tun?");
    $session['user']['specialinc'] = "muuuh.php";
    addnav("Zurückstarren","forest.php?op=stare");
    addnav("Lieber zurück in den Wald","forest.php?op=leave");
}
elseif ($_GET['op']=="stare")
{
    output("`7`nDu stemmst die Arme in die Hüften und starrst fest entschlossen zurück. Für einen Moment schaut ihr euch tief in die Augen, als wolltet ihr einander 
        hypnotisieren, doch plötzlich schnaubt die Kuh und setzt sich in Bewegung. Immer schneller rennt sie auf dich zu und dir kommt der Gedanke, dass es vielleicht
        nicht deine beste Idee war, sie so anzustarren.");
    $session['user']['specialinc'] = "muuuh.php";
    addnav("Bleib ruhig stehen","forest.php?op=stay");
    addnav("Laufe schnell weg!","forest.php?op=run");
}
elseif ($_GET['op']=="leave")
{
    if(mt_rand(0,2))
    {
        output("`7`nDu drehst dich wieder um und willst in den Wald gehen. Doch kaum hast du kehrt gemacht, hörst du die Kuh schnauben und spürst nahezu ihre
            schweren Hufe über die Wiese trampeln. Du wirfst einen hastigen Blick über die Schulter und siehst sie immer schneller auf dich zurennen.");
        $session['user']['specialinc'] = "muuuh.php";
        addnav("Bleib ruhig stehen","forest.php?op=stay");
        addnav("Lauf schnell weg!","forest.php?op=run");
    }
    else
    {
        output("`7`nDer Blick ist dir unheimlich und so drehst du dich um und gehst wieder zurück in den Wald. Welch ein Glück, dass die grünen Drachen nicht
            `b`isolche`i`b Augen haben! Das Dorf wäre verloren!");
        $session['user']['specialinc'] = "";
        addnav("Zurück in den Wald","forest.php");
    }
}
elseif ($_GET['op']=="stay")
{
    switch(mt_rand(1,3))
    {
        case 1:
        output("`7`nAls 500 Kilo Kuh über dich hinwegtrampeln, kommst du messerscharf zu der Erkenntnis, dass heute wohl nicht dein Tag ist. Dir wird schwarz vor Augen
            und als du nach einer Weile wieder zu dir kommst, ist von der Kuh nichts mehr zu sehen. Stark geschwächt schleppst du dich wieder in den Wald.");
        if($session['user']['turns']>0) $session['user']['turns']--;
        $session['user']['hitpoints']=1;
        addnews("`%".$session['user']['name']." `2wurde von einer `tKuh `2niedergetrampelt.");
        break;
        case 2:
        $exp = round($session['user']['experience'] * 0.1);
        output("`7`nMutig und gefasst bleibst du stehen. Oder ist es etwa nur der Schreck der dich erstarren lässt? Doch das Glück scheint mit dir zu sein, denn die Kuh
            verlangsamt und kommt    schließlich nur wenige Schritte vor dir zum stehen. Ohne dich auch nur eines weiteren Blickes zu würdigen, beginnt sie wieder zu grasen,
            als wäre nie etwas geschehen. Mit leicht zittrigen Beinen gehst du einige Schritte rückwärts und eilst dann möglichst unauffällig wieder in den Wald.`n
            Du bekommst `%$exp Erfahrungspunkte`7.");
        $session['user']['experience']+=$exp;
        break;
        case 3:
        output("`7`nMutig und gefasst bleibst du stehen. Oder ist es etwa nur der Schreck der dich erstarren lässt? Doch das Glück scheint mit dir zu sein, denn die Kuh
            verlangsamt und kommt    schließlich nur wenige Schritte vor dir zum stehen. Als sie muuht und dich mit großen Augen auffordernd anschaut, wird dir klar, dass
            sie einfach nur gemolken werden möchte. Zum Glück findest du eine leere Flasche in deinem Rucksack und machst dich ans Werk. Als die Flasche voll ist, macht
            sich die Kuh wieder auf den Weg und du beschließt durstig von der Milch zu trinken. Hier würde es zum Glück auch niemand mitbekommen, denn "
            .($session['user']['sex']?"eine wahre Heldin":"ein wahrer Held")." muß ja bekanntlich auf ".($session['user']['sex']?"ihren":"seinen")." Ruf achten.`n
            `2Du fühlst dich erfrischt und regenerierst vollständig!");
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        break;
    }
    $session['user']['specialinc'] = "";
    addnav("Zurück in den Wald","forest.php"); 
}
elseif ($_GET['op']=="run")
{
    switch(mt_rand(1,4))
    {
        case 1:
        output("`7`nDu rennst so schnell du kannst, aber es reicht nicht bis zum rettenden Waldrand. Als 500 Kilo Kuh über dich hinwegtrampeln, kommst du messerscharf 
            zu der Erkenntnis, dass heute wohl nicht dein Tag ist. Dir wird schwarz vor Augen und als du nach einer Weile wieder zu dir kommst, ist von der Kuh nichts mehr
            zu sehen. Stark geschwächt schleppst du dich wieder in den Wald.");
        if($session['user']['turns']>0) $session['user']['turns']--;
        $session['user']['hitpoints']=1;
        addnews("`%".$session['user']['name']." `2wurde von einer `tKuh `2niedergetrampelt.");
        break;
        case 2:
        output("`7`nDu rennst so schnell du kannst auf den Wald zu und rettest dich mit einem gewagten Sprung in das Dickicht. Das war knapp! Die Kuh bleibt stehen und
            starrt kurz herüber. Dann beginnt sie wieder seelenruhig zu grasen, als wäre nie etwas geschehen. Leider hat sich auf der Flucht dein Goldbeutel geöffnet und
            du hast die Hälfte deines Goldes verloren.");
        $session['user']['gold']=round($session['user']['gold']*0.5);
        break;
        case 3:
        $gold=max(500, $session['user']['level']*e_rand(100,300));
        output("`7`nDu rennst so schnell du kannst auf den Wald zu und rettest dich mit einem gewagten Sprung in das Dickicht. Das war knapp! Die Kuh bleibt stehen und 
            starrt kurz herüber. Dann beginnt sie wieder seelenruhig zu grasen, als wäre nie etwas geschehen. Als du dich im Gebüsch umschaust, entdeckst du einen Beutel
            mit `^$gold Gold`7, den du schnell an dich nimmst.");
        $session['user']['gold']+=$gold;
        break;
        case 4:
        $gems=e_rand(1,3);
        output("`7`nDu rennst so schnell du kannst auf den Wald zu und rettest dich mit einem gewagten Sprung in das Dickicht. Das war knapp! Die Kuh bleibt stehen und 
            starrt kurz herüber. Dann beginnt sie wieder seelenruhig zu grasen, als wäre nie etwas geschehen. Als du dich im Gebüsch umschaust, entdeckst du einen Beutel
            mit `Q$gems Edelsteinen`7, den du schnell an dich nimmst.");
        $session['user']['gems']+=$gems;
        break;
    }
    $session['user']['specialinc'] = "";
    addnav("Zurück in den Wald","forest.php");
}
else
{
    output("`7Da ist wohl etwas schief gegangen...");
    addnav("Zurück in den Wald","forest.php");
}

?>