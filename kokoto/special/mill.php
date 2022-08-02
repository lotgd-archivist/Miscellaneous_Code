<?
// based upon webfind with
// correction, translation by gargamel @ www.rabenthal.de
// modification and heavy enhancements by gargamel @ www.rabenthal.de
// überarbeitet von Tidus www.kokoto.de
if (!isset($session)) exit();

$battle = false;


if ($_GET['op']=='cont'){   // zurück in den Wald
    output('`nDu blickst zur Sonne und siehst, dass es schon spät geworden ist.
    Du gehst nicht mehr zur Mühle, da Du keine Zeit für Waldkämpfe vertrödeln
    willst.`0');
    $session['user']['specialinc']='';
}else if ($_GET['op']=='cont2'){   // zurück in den Wald 2
    output('`n`5Der Weg zurück in den Wald ist weit und Du verlierst einen Waldkampf.
    `0Ob sich das alles gelohnt hat...?`0');
    $session['user']['turns']--;
    $session['user']['specialinc']='';
}else if ($_GET['op']=='mill'){   // vor der mühle
    output('`nDu setzt Deinen Fußmarsch fort und erreichst die Mühle. Bevor Du
    Dich genauer umsiehst, stillst Du erstmal Deinen Durst durch einen kräftigen
    Schluck erfrisches kühles Wasser aus dem Bach.`n
    `3Nun wendest Du Dich der Mühle zu...`n`n`0');
    addnav('Ruinen betreten','forest.php?op=enter');
    addnav('Zurück in den Wald','forest.php?op=cont2');
    $session['user']['specialinc'] = 'mill.php';
}else if ($_GET['op']=='enter'){   // Die Mühle
    $rand = mt_rand(1,10);
    output('`nDu stehst im grossen Innenraum der Mühle und schaust Dich gründlich um. `0');
    switch ($rand) {
        case '1':
        $gem = rand(2,3);
        $gold = rand($session['user']['level']50,$session['user']['level']100);
        output("`nInsbesondere suchst Du nach Wertgegenständen. Und tatsächlich!
        `6Du findest $gold `^Gold `5und $gem `%Edelsteine!`n`n
        `9Getrieben vom Schatzfieber fragst Du Dich, ob Du noch etwas bleiben sollst...`0");
        addnav('Weiter suchen','forest.php?op=enter');
        addnav('Zurück in den Wald','forest.php?op=cont2');
        $session['user']['gems']+=$gem;
        $session['user']['gold']+=$gold;
        $session['user']['specialinc']="mill.php";
        debuglog("Found $gem gems and $gold gold in the mill");
        break;

        case '2': case '3':
        output('`nKann man hier einen Schatz finden?`n
        Irgendwie sieht es nicht danach aus, und Du willst Dich bereits etwas enttäuscht
        auf den Weg zurück in den Wald machen.`n`n
        Dann findest Du doch noch etwas: `3Unter einigen Trümmern entdeckst Du eine
        offene Falltür.`0 Die muss in den Keller führen...`n`0');
        addnav('Keller betreten','forest.php?op=cellar');
        addnav('Zurück in den Wald','forest.php?op=cont2');
        $session['user']['specialinc']='mill.php';
        break;

        case '4': case '5': case '6':
        output('`nNachdem Du die Ruine eine ganze Weile gründlich durchsucht
        hast, `6findest Du absolut nichts,`n
        `QDeine Suche kostet Dich einen Waldkampf.`0');
        $session['user']['turns']--;
        $session['user']['specialinc']='';
        break;

        case '7': case '8': case '9': case '10':
        output('`nWährend Du die Ruinen durchsuchst, hörst Du plötzlich Schritte hinter
        Dir. Du drehst Dich schnell um, aber es ist zu spät. Der Geist des Müllers
        greift Dich an!`n`n`0');
        ///
        $badguy = array(
                "creaturename"=>"`\$Müller`0",
                "creaturelevel"=>$session['user']['level']1,
                "creatureweapon"=>"Sack voll Mehl",
                "creatureattack"=>$session['user']['attack']2,
                "creaturedefense"=>$session['user']['defence']2,
                "creaturehealth"=>round($session['user']['maxhitpoints']1.25,0),
                "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialinc']='mill.php';
        $battle=true;
        $session['user']['specialinc']='';
        break;
    }
}else if ($_GET['op']=='cellar'){   // Der Mühlenkeller
    output('`nZum Glück steht eine alter Holzleiter an der Wand. Du nimmst Sie und
    lässt sie durch die Falltür in den Keller runter. Vorsichtig steigst Du nun hinab.
    `9Hoffentlich hält die Leiter, denn sonst wärst Du wohl gefangen...`n`n`0`QUnten ist es still wie in einem Grab.`0 Du hörst nichts ausser Deine eigenen Schritte, die vom alten Gemäuer dumpf wiederhallen. Es tropft von
    der Decke und die Luft riecht verbraucht.`n
    `9Zu Deiner Rechten steht ein altes Regal, daneben ist eine Tür.`n`0');
    addnav('Regal untersuchen','forest.php?op=shelf');
    addnav('Tür benutzen','forest.php?op=furtherdown');
    addnav('Keller verlassen','forest.php?op=enter');
    $session['user']['specialinc']='mill.php';
}else if ($_GET['op']=='shelf'){   // Regal
    output("`nDu untersuchst das alte Regal und findest die Rezepte einiger Brotsorten
    in einem staubigen Heft auf dem obersten Regal. Du steckst das Heft erstmal ein.`n`n
    ".($session['user']['sex']?"Eine Frau, die backen kann, ":"Ein Mann, der backen kann, ")."
    wird attraktiver. `%Du gewinnst 3 Charmpunkte!`0");
    $session['user']['charm']+=3;
    $session['user']['specialinc']='';
}else if ($_GET['op']=='furtherdown'){   // weiter keller
    output('`nHinter der Tür versteckt sich ein Kriechkeller. Neugierig folgst Du,
    auf allen Vieren, dem Gang und stösst nach wenigen Metern an Ende. Es ist stockdunkel,
    aber Du könntest wenigstens tastend herausfinden, ob hier noch etwas ist.`n`n
    Ein wenig zögern streckst Du Deine Hand aus.`n`0');
    $rand = mt_rand(1,2);
    switch ($rand) {
        case '1':
        output('`nDu bekommst einen Schlag, dann spürst Du einen Schmerz in Deiner
        Hand. Du wurdest von einer Ratte gebissen.`n
        `QDu verlierst Lebenspunkte!`0');
        $session['user']['hitpoints']= round($session['user']['hitpoints']0.75);
        break;

        case '2':
        output('`nSofort spürst Du etwas kleines, hartes unter Deiner tastenden Handfläche. `0');
        $rand2 = mt_rand(1,3);
        switch ($rand2) {
            case '1':
            output('`%Aber es ist nur ein Stein. Enttäuscht gehst Du zurück ans Tageslicht.`0');
            break;

            case '2':
            output('`@Es fühlt sich nicht nur so an, nein, es ist ein Edelstein! Zufrieden
            gehst Du ans Tageslicht zurück.`0');
            $session['user']['gems']++;
            break;

            case '3':
            output('`^Es fühlt sich nicht nur so an, nein, es ist ein Goldstück!
            Du kehrst ans Tageslicht zurück. Ein bischen mehr hätte es ja sein können....`0');
            $session['user']['gold']++;
            break;
        }
    }
    output('`n`n`QDas Abenteuer hat Dich einen Waldkampf gekostet.`0');
    $session['user']['turns']--;
    $session['user']['specialinc']='';
}
//Battle Settings
else if ($_GET['op']=='run'){   // Flucht
    if (e_rand()3 == 0){
        output ('`c`b`&Du konntest dem Müller entkommen!`0`b`c`n');
        $_GET['op']="";
    }else{
        output('`c`b`$ Du konntest dem Müller nicht entkommen!`0`b`c');
        $battle=true;
        $session['user']['specialinc']='';
    }
}else if ($_GET['op']=='fight'){   // Kampf
    $battle=true;
    $session['user']['specialinc']='';
}else{
    output('`nDu kommst an einen kleinen Waldbach. Am Ufer kannst Du sehr gut
    entlanggehen und so folgst Du dem Bachlauf auf Deiner Suche nach Feinden.
    Der Bach mündet in einen größeren Bach, dem Du nun weiter folgst.`n
    `3Der Bach wird zunehmend breiter und ein gutes Stück vor Dir kannst Du die
    Überreste einer alte Mühle sehen.`n`0');
    // abschluss intro
    addnav('Gehe zur Mühle','forest.php?op=mill');
    addnav('Zurück in den Wald','forest.php?op=cont');
    $session['user']['specialinc'] = 'mill.php';
}
if ($battle) {
    include('battle.php');
    $session['user']['specialinc']='mill.php';
        if ($victory){
            $badguy=array();
            $session['user']['badguy']='';
            output('`n`7Du konntest nach einem harten Kampf den Geist des Müllers
            besiegen!');
            debuglog('defeated the miller');
            //Navigation
            addnav('Zurück in den Wald','forest.php');
            if (rand(1,2)==1) {
                $gem_gain = rand(2,3);
                $gold_gain = rand($session['user']['level']10,$session['user']['level']20);
                output(" Als Du Dich noch einmal umdrehst findest Du $gem_gain Edelsteine
                und $gold_gain Goldstücke.`n`n");
            }
            $exp = round($session['user']['experience']0.08);
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session['user']['experience']+=$exp;
            $session['user']['gold']+=$gold_gain;
            $session['user']['gems']+=$gem_gain;
            $session['user']['specialinc']='';
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']='';
            debuglog('was killed by a Miller.');
            output('`n`7Der Geist des Müllers war stärker!`n`nDu verlierst 6% Deiner Erfahrung.`0');
            output('`nGeister können nichts mit Reichtum anfangen. Du kannst morgen
            wieder kämpfen!`0');
            addnav('Tägliche News','news.php');
            addnews("`QDer Geist des Müllers hat ".$session['user']['name']." `Qwirklich fein gemahlen!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience'].94,0);
            $session['user']['specialinc']='';
        } else {
            fightnav(true,true);
        }
        }
?>
