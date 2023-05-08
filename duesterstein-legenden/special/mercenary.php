
<?
/*
Travelling Mercenaries - Forest Special
By Robert (Maddnet) and Talisman (DragonPrime)
*/
// translation, some modifications by gargamel @ www.rabenthal.de

if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nDu hörst Stimmen und ein lautes Gegröhle. Noch kannst Du es nicht
    zuordnen, aber schon nach ein paar Schritten siehst Du staunend die Quelle
    des Lärms: `n
    `%Ein Pferdegespann vor einem Planwagen.`0`n
    Wilde Gesellen schauen heraus, schwingen ihre Waffen und singen Kampfeslieder.`n`n
    Das Gespann hält vor Dir so, dass der Planwagen den Weg versperrt. Ein kräftiger
    Typ mit Fellumhang springt heraus, ein grosses rostiges Schwert in der Hand.`n
    `3\"Hejei Duu\" begrüsst er Dich.`n`n`0
    Du machst Dich auf das schlimmste gefasst und greifst zu Deiner Waffe. Da hebt
    der Typ beschwichtigend seine Hand. `3\"Wier sindd Söldnehr, Duu kanst uns kauffen\"
    raunt er Dir zu. `0Mittlerweile ist die ganze Truppe vom Wagen abgestiegen und
    Du blickst in 7 dumpfe Gesichter.`n`n
    `%Möglicherweise können die Typen ja wenigstens kämpfen... Für 1 Edelstein kannst
    Du einen Söldner anwerben, der Dich ein Stück Deines Weges begleiten wird.`n`0");
    //abschluss intro
    addnav("Söldner anwerben","forest.php?op=buy");
    addnav("Einfach weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "mercenary.php";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`5Du lässt die selbsternannten Söldner stehen und gehst weiter.
    Wahrscheinlich können die eh nichts....`0");
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="buy"){   // anwerben
    if ($session[user][gems] > 0 ) {
        output("`nDu nimmst das Angebot an. Nur fällt es Dir schwer, einen Söldner
        auszuwählen. Alle sehen irgendwie komisch aus.`n
        `2Du kommst auf folgende Idee: Die Söldner stellen sich hiner den Planwagen
        und Du wirfst ihnen den Edelstein im hohen Bogen über den Wagen hinweg zu.
        Wer den Edelstein fängt, ist angeheuert.`0`n`n
        Du wirfst....`n`n
        und....`n`n`0");
        $mercturns=(e_rand(8,22));
        switch ( e_rand(1,7) ) {
            case 1:
            output("`^Grimlock der Paladin fängt den Edelstein!`n`0
            Er verabschiedet sich von der Gruppe und zieht mit Dir weiter!");
            $session['bufflist']['Soeldner']
                    = array("name"=>"`#Grimlock",
                            "rounds"=>$mercturns,
                            "wearoff"=>"Grimlock verabschiedet sich von Dir und geht in die Kneipe.",
                            "defmod"=>1.2,
                            "atkmod"=>1.5,
                            "roundmsg"=>"Grimlock kämpft mit Dir.",
                            "activate"=>"defense");
            break;

            case 2:
            output("`^Tryxlk der blinde Troll fängt den Edelstein!`n`0
            Er verabschiedet sich von der Gruppe und zieht mit Dir weiter!");
            $session['bufflist']['Soeldner']
                    = array("name"=>"`#Tryxlk",
                            "rounds"=>$mercturns,
                            "wearoff"=>"Tryxlk verabschiedet sich von Dir und geht in die Kneipe.",
                            "defmod"=>1.1,
                            "atkmod"=>1.0,
                            "roundmsg"=>"Tryxlk kämpft mit Dir.",
                            "activate"=>"defense");
            break;

            case 3:
            output("`^Grog der betrunkene Zwerg fängt den Edelstein!`n`0
            Er verabschiedet sich von der Gruppe und zieht mit Dir weiter!");
            $session['bufflist']['Soeldner']
                    = array("name"=>"`#Grog",
                            "rounds"=>($mercturns-2),
                            "wearoff"=>"Grog verabschiedet sich von Dir und geht in die Kneipe.",
                            "defmod"=>0.8,
                            "atkmod"=>0.8,
                            "roundmsg"=>"Grog ist eher eine Belastung.",
                            "activate"=>"defense");
            break;

            case 4:
            output("`^Longstepper der Ranger fängt den Edelstein!`n`0
            Er verabschiedet sich von der Gruppe und zieht mit Dir weiter!");
            $session['bufflist']['Soeldner']
                    = array("name"=>"`#Longstepper",
                            "rounds"=>$mercturns,
                            "wearoff"=>"Longstepper verabschiedet sich von Dir und geht in die Kneipe.",
                            "defmod"=>1.3,
                            "atkmod"=>1.4,
                            "roundmsg"=>"Longstepper kämpft mit Dir.",
                            "activate"=>"defense");
            break;

            case 5:
            output("`^Tasha der Elfenkrieger fängt den Edelstein!`n`0
            Er verabschiedet sich von der Gruppe und zieht mit Dir weiter!");
            $session['bufflist']['Soeldner']
                    = array("name"=>"`#Tasha",
                            "rounds"=>$mercturns,
                            "wearoff"=>"Tasha verabschiedet sich von Dir und geht in die Kneipe.",
                            "defmod"=>1.0,
                            "atkmod"=>1.4,
                            "roundmsg"=>"Tasha kämpft mit Dir.",
                            "activate"=>"defense");
            break;

            case 6:
            output("`^Dagnar der alte Ritter fängt den Edelstein!`n`0
            Er verabschiedet sich von der Gruppe und zieht mit Dir weiter!");
            $session['bufflist']['Soeldner']
                    = array("name"=>"`#Dagnar",
                            "rounds"=>($mercturns+3),
                            "wearoff"=>"Dagnar verabschiedet sich von Dir und geht in die Kneipe.",
                            "defmod"=>1.5,
                            "atkmod"=>1.5,
                            "roundmsg"=>"Dagnar kämpft mit Dir.",
                            "activate"=>"defense");
            break;

            case 7:
            output("`^Bjorn der Speer fängt den Edelstein!`n
            Er verabschiedet sich von der Gruppe und zieht mit Dir weiter!");
            $session['bufflist']['Soeldner']
                    = array("name"=>"`#Bjorn",
                            "rounds"=>$mercturns,
                            "wearoff"=>"Bjorn verabschiedet sich von Dir und geht in die Kneipe.",
                            "defmod"=>1.3,
                            "atkmod"=>1.0,
                            "roundmsg"=>"Bjorn kämpft mit Dir.",
                            "activate"=>"defense");
            break;
        }
        $session[user][gems]--;
    }
    else {
        $hploss=round($session[user][hitpoints]*.8);
        $session[user][hitpoints]-=$hploss;
        output("`n`%Als die Söldner merken, dass Du gar keinen Edelstein hast, greifen
        Sie Dich ohne weitere Fragen an. Gegen die sieben Söldner hast Du natürlich
        keine Chance und Du trägst einige Blessuren davon.`0");
        output("`n`n`QDu verlierst $hploss Lebenspunkte!`0");
    }
    $session[user][specialinc]="";
}
?>


