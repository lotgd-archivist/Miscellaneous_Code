<?php
/*
Idee von Tidus und Lori
Geschrieben von Tidus für www.kokoto.de
Man darf dieses Special nur verwenden, wenn der Großteil der Source wie auch dieses Special selbst sichtbar sind.

Das Copyright darf nicht entfernt werden, bei der Suche nach Specials in Sourcen anderer Server ist kopieren strengstens erwünscht oder die Orginalversion bei anpera.net herunterladen (http://anpera.homeip.net/phpbb3/viewtopic.php?f=43&t=5169). Ergänzungen und Werte anpassen etc. gerne, aber Copyright muss auch dann erhalten bleiben! Bei Änderungen oder Ergänzungen, sowie Bugs und Anregungen steht es euch frei und wäre es nett, wenn ihr in dem Thread auf anpera.net euren Vorschlag einbringt, für uns und andere die daran Interesse haben könnten.
Gegenstück zu: troll.php
*/

if (!isset($session)) 
    exit();
if ($_GET['op']=='betreten'){
    if ($session['user']['sex']==0) 
        $rand = mt_rand(1,2); 
    else 
        $rand = mt_rand(3,4);
    switch($rand){
        case 1:
            page_header("Höhle");
            output("`n`c`@Spinne`c`n`nDu betrittst die Höhle und bemerkst, das dass Leuchten verschwunden ist. Vor dir tropft von der Decke eine komische Flüssigkeit herunter. Automatisch schaust du nach oben, in die rot leuchtenden Augen einer riesigen Spinne. Sie springt auf den Boden und dich greift dich sofort an!`n`n");
            $badguy = array(
                "creaturename" => "`@Riesenspinne`0",
                "creaturelevel" => $session['user']['level'],
                "creatureweapon" => "`@Gift und Netzfäden`0",
                "creatureattack" => $session['user']['attack']+10,
                "creaturedefense" => $session['user']['defense']+5,
                "creaturehealth" => e_rand(500,800),
                "diddamage" => 0);
            $session['user']['badguy'] = createstring($badguy);
            $session['user']['specialinc'] = 'spinne.php';
            $battle = true;
        break;
        case 2:
            $gold = mt_rand(10,100);
            page_header("Höhle");
            output("`n`c`T`bHöhle`b`c`n`n`tDu findest in der Höhle einige Münzen und Plunder, den du nicht gebrauchen kannst. Sieht nicht so aus als ob es eine Ansammlung von einem Menschen wäre. Hastig packst du die paar Münzen in deine Tasche und bemerkst nur, dass du bei fast jedem Schritt in dieser dunklen Höhle ein paar kleine Spinnen zertrittst. Ohne weiter Gedanken darüber zu verschwenden verlässt du die Höhle wieder.`n`n`^'.$gold.' Goldstücke `tgefunden.`n`$ -1 Waldkampf.");
            $session['user']['gold'] += $gold;
            $session['user']['turns']--;
            $session['user']['specialinc'] = '';
        break;
        case 3:
            page_header("Höhle");
            output("`n`c`\$Spinne`c`n`n`@Du denkst dir natürlich nichts dabei, ist ja nur eine Höhle wie jede andere auch! Das Leuchten stellte sich leider nicht als Goldstücke heraus. Du suchst weiter tiefer in der Höhle und auf einmal schreist du so laut du nur kannst und zwar vor Angst, weil vor dir eine RIESIGE Spinne auftaucht und dich mit rot leuchtenden Augen anschaut.Sie stellt sich wohl gerade vor, wie du schmeckst ... Was tun? AH genau! Du rennst schreiend wie von einem Drachen verfolgt aus der Höhle und rufst nach jemandem, der die Spinne tötet ... Alles sehr peinlich, da ein paar Kerle, die ja nicht wissen können wie RIESIG diese Spinne war, sich darüber kaputt Lachen und es rumerzählen.`n`n`\$Da dir dies so unglaublich peinlich ist verlierst du 2 Charmepunkte!");
            if ($session['user']['charm'] > 1)
                $session['user']['charm']-=2; 
            elseif ($session['user']['charm'] > 0)
                $session['user']['charm']--; 
            else 
                $session['user']['turns']--;
            $session['user']['turns']--;
            addnews("`@".$session['user']['name']."`@ wurde gesichtet, als Sie um Hilfe schreiend aus einer Höhle gerannt kam. Und ja, das Wort `$`bSpinne`b`@ kam auch darin vor...");
            $session['user']['specialinc']='';
        break;
        case 4:
            page_header("Höhle");
            output("`n`c`$Spinne`c`n`n`@Du denkst dir natürlich nichts dabei, ist ja nur eine Höhle wie jede andere auch! Das Leuchten stellte sich als Goldstücke heraus. Gerade als du dabei bist die paar Goldstücke einzustecken, eins nach dem anderen, die zu deinem Unglück in Spinnweben kleben bemerkst du, dass neben dir auf einmal eine Horde kleiner krabbliger Spinnen auf dich zumarschiert! Was tun? AH genau! Du rennst schreiend wie von einem Drachen verfolgt aus der Höhle und rufst nach jemandem der die Spinnen tötet ... Alles sehr peinlich da ein paar Kerle sich darüber kaputt Lachen und es rumerzählen.`n`n`\$Da dir dies so unglaublich peinlich ist verlierst du 2 Charmepunkte!");
            if ($session['user']['charm'] > 1)
                $session['user']['charm']-=2; 
            elseif ($session['user']['charm'] > 0)
                $session['user']['charm']--; 
            else 
                $session['user']['turns']--;
            $session['user']['turns']--;
            addnews("`@".$session['user']['name']."`@ wurde gesichtet, als Sie um Hilfe schreiend aus einer Höhle gerannt kam. Und ja, das Wort `$`bSpinnen`b`@ kam auch darin vor ... ... sehr oft sogar ... ...");
            $session['user']['specialinc']='';
        break;
    }
//Battle Settings
}
elseif ($_GET['op']=='run'){   // Flucht
    if (e_rand()%3 == 0){
    $session['user']['specialinc']='';
        output ("`c`b`&Du konntest der Spinne entkommen!`0`b`c`n");
        $_GET['op']='';
        addnews("`@".$session['user']['name']."`$ ist vor einer Spinne weggerannt! ... Ja einer `bSpinne`b ...");
    }else{
        output("`c`b`$Die Spinne war schneller als du!`0`b`c");
        $battle=true;
        $session['user']['specialinc']='spinne.php';
    }
}
else if ($_GET['op']=='sterben'){   // Flucht
    $session['user']['specialinc']='';
    output("`n`n`PDu rennst feige weg, so schnell du kannst. Auf dem Weg könnte es sein, dass du ein bisschen Gold oder Edelsteine verloren hast, aber da du so hastig geflohen bist kannst du es nicht genau sagen.`n`$ Auf jedenfall verlierst du 2 Charmepunkte für deine Feigheit! `n`n`(");
    if ($session['user']['charm']>1)
        $session['user']['charm']-=2;
    if ($session['user']['gold']>0 && $session['user']['gems']==0) 
        $session['user']['gold']*=0.9;
    if ($session['user']['gold']>0 && $session['user']['gems']>0) 
        {$session['user']['gems']--; 
        $session['user']['gold']*=0.9;}
    if ($session['user']['gold']==0 && $session['user']['gems']>2) 
        $session['user']['gems']-=2;
    $session['user']['turns']--;
}
else if ($_GET['op']=='fight'){   // Kampf
    $battle=true;
    $session['user']['specialinc']='spinne.php';
}
else{
    page_header("Wald");
    $session['user']['specialinc']='spinne.php';
    output("`n`c`T`bHöhle`b`c`n`n`tDu streifst mal wieder durch den Wald, suchst ein Abenteuer, ein unentdecktes Schloss oder irgendetwas, dass aufregender ist als nur Monster zu töten ... Peinlich genau schaust du überall hin und suchst Hinweise und Höhlen, schnell erkennst du, dass es doch wie immer nur der Wald ist in dem du jeden Tag umherläufst. Auf einmal stehst du, völlig unvorbereitet, vor einer großen Höhle, die der des Drachens relativ ähnlich sieht, jedoch ein Drache würde niemals hineinpassen ... etwas leuchtet dich aus dem dunklen Loch an.`n`nSchnell wegrennen oder Höhle betreten?");
    addnav("Weitergehen","forest.php?op=betreten");
    addnav("Wegrennen","forest.php?op=sterben");
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']='spinne.php';
        if ($victory){
            output("`@`n`nDu hast die Spinne besiegt! Du bist der Held aller Frauen!!");
            //Navigation
            $badguy=array();
            $session['user']['badguy']='';
            addnews("`@".$session['user']['name']."`$ hat eine `bSpinne`b erschlagen, er ist ein wahrer Frauenheld!");
            addnav("Zurück in den Wald","forest.php");
            if (rand(1,2)==1) {
                $gem_gain = rand(2,3);
                $gold_gain = rand($session['user']['level']*10,$session['user']['level']*20);
                output("`@ `nAls Du dich noch einmal umdrehst findest du `^$gem_gain`% Edelsteine`@
                und `^ $gold_gain Goldstücke.`@`n");
                $session['user']['gems']+=$gem_gain;
                $session['user']['gold']+=$gold_gain;
                }
            $exp = round($session['user']['experience']*0.08);
            output("Durch diesen Kampf steigt deine Erfahrung um $exp Punkte.`n`n");
            $session['user']['experience']+=$exp;
            $session['user']['specialinc']='';
        } 
        elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']='';
            output("`%`n`nDu verlierst 6% deiner Erfahrung.`0 `n Du konntest die Spinne einfach nicht besiegen ...`0");
            addnav("Tägliche News","news.php");
            addnews("`$".$session['user']['name']."`$ wurde von einer riesigen `bSpinne`b zerquetscht ... Sowas soll ein Frauenheld sein? Versager!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.94,0);
            $session['user']['specialinc']='';
        } 
        else {
            fightnav(true,true);
        }
}
?>