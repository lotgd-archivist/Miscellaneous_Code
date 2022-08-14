
<?php

//+-----------------------------+
//| Rabengruft, Version 1.5        |
//| Programmierung: sANg         |
//+-----------------------------+
//| Etwas fairer, als zuvor ;)    |
//+-----------------------------+


$dornen = $session['user']['hitpoints'] * 0.1;
$tür = $session['user']['hitpoints'] * 0.2;
$gold = $session['user']['level'] * 111;
$übelkeit = $session['user']['hitpoints'] * 0.3;

if(!isset($session)) exit();

switch($_GET['op']) {
    case '':
        $str_out = "`c`n`b`7Di`Te R`4ab`Ten`7gr`Tuf`4t`c`b`n`n
                    `4Du befindest dich auf deinem Weg durch den Wald,
                    stolperst über eine Wurzel, fällst und schlägt mit dem Kopf
                    auf etwas hartem, kalten auf.";
        $session['user']['hitpoints'] -= $tür;
        
        $str_out .= "`4Erstaunt fährst du mit den Händen darüber, schiebst
                    heruntergefallene Äste und Blätter zur Seite.
                    Das, was der Wald deinem Blick nun freigibt, lässt dich
                    verblüfft aufkeuchen. Eine metallene Tür, mitten im Boden,
                    übersäht mit Runen, in der Mitte ein Kreis, das Zentrum des
                    Kreises wrd von einem eingravierten Raben bedeckt. Stirnrunzelnd
                    öffnest du die Tür, blickst in einen tiefschwarzen, gähnenden
                    Abgrund. Was tust du?";
                    
        addnav("Herunterspringen", "forest.php?op=jump");
        addnav("Herunterklettern", "forest.php?op=climb");
        addnav("Zurück in den Wald", "forest.php?op=letitbe");
        
        $session['user']['specialinc'] = "rabengruft.php";
        break;
    
    case 'jump':
        switch(e_rand(1,4)) {
            case 1:
                $str_out = "`7Du nimmst deinen ganzen Mut zusammen und springst.
                            Im Fallen spürst du, wie es immer kälter wird. Schließlich
                            prallst du auf einem steineren Untergrund auf, danach bist du
                            nicht gerade besonders lebendig";
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                $session['user']['specialinc'] = "";
                
                addnav("Tägliche News", "news.php");
                
                addnews($session['user']['name']." `2wollte die Rabengruft erkunden und verstarb beim Aufprall.");
                break;
            case 2:
            case 3:
            case 4:
                $str_out = "`7Du nimmst deinen ganzen Mut zusammen und springst.
                            Nach einer Weile landest du auf etwas weichem, einer Art
                            Moospflanze. Rasch befreist du dich und blickst dich um.
                            Zu deinen Füßen erhellen einige Edelsteine den Raum,
                            den du betreten hast. Freudig liest du ein paar von ihnen auf.";
                $session['user']['gems'] += 3;
                
                $str_out .= "`nDann blickst du dich um, die relativ niedrige Decke wird
                            von dünnen Säulen gestützt, auch diese sind mit Runen übersäht.
                            Am anderen Ende des Raumes tuen sich zwei Türen vor dir auf,
                            bestehend aus robustem Holz, auch in sie wurtde der Rabe eingeschnitzt.
                            Du zögerst, hinter den Türen muss sich noch etwas verbergen, beide scheinen
                            unverschlossen, zu deiner rechten befindet sich ein schmaler Tunnel, der nach oben führt.
                            Was tust du?";
                            
                addnav("Linke Tür öffnen", "forest.php?op=leftopen");
                addnav("Rechte Tür öffnen", "forest.php?op=rightopen");
                addnav("Sammle weitere Edelsteine", "forest.php?op=moregems");
                addnav("Durch Tunnel fliehen", "forest.php?op=tunnel");
                
                $session['user']['specialinc'] = "rabengruft.php";
                break;
        }
        break;
        
    case 'climb':
        $str_out = "`6Vorsichtshalber beschließt du, lieber herunter zu klettern.
                    Du hangelst dich an Vertiefungen in der Wand herunter und spürst zugleich, wie
                    diese immer feuchter wird. Nach einer Weile des Kletterns rutscht du mit deinen Fingern ab
                    und fällst, der Aufprall raubt dir sämtliche Lebensgeister.`4 Du bist tot!!!";
                    
        $session['user']['alive'] = false;
        $session['user']['hitpoints'] = 0;
        
        addnav("Tägliche News", "news.php");
        
        addnews($session['user']['name']." `2wollte die Rabengruft erkunden und verstarb beim Aufprall.");
        $session['user']['specialinc'] = "";
        break;
        
    case 'letitbe':
        $str_out = "Du beschließt sicherheitshalber in den Wald zurück zu kehren.
                    Nach einer Weile hast du die Tür und den Abgrund auch wieder vergessen.";
                    
        $session['user']['specialinc'] = "";
        break;
    
    case 'leftopen':
        switch(e_rand(1,2)) {
            case 1:
                $str_out = "`7Du öffnest die linke Tür und springst entsetzt zurück!
                            In dem Raum vor liegen hunderte von Leichen, alle mit den schwarzen
                            Beulen der `GPest`7 übersäht. Du fühlst dich plötzlich schwach und fliehst.";
                
                $buff = array("name" => "`4B`$\eulenpest",
                              "roundmsg" => "`4Die Beulenpest plagt dich",
                              "wearoff" => "`b`\$Die Beulenpest lässt nach!`b",
                              "rounds" => "30",
                              "atkmod" => "0.78",
                              "defmod" => "0.7",
                              "survivenewday" => "0",
                              "activate" => "offense,defense");
                $session['bufflist']['beulenpest'] = $buff;
                
                addnav("Durch den Tunnel fliehen", "forest.php?op=tunnel");
                
                $session['user']['specialinc'] = "rabengruft.php";
                break;
            case 2:
                $str_out = "`7Du öffnest die linke Tür und trittst in einen vollkommen leeren Raum...`n
                            Plötzlich verschwindet dieser direkt vor deinen Augen und du wachst im Wald auf.
                            Du musst wohl ohnmächtig geworden sein, vorsichtig streichst über deine Haare,
                            spürst getrocknetes Blut, aber von einer Metalltür ist nichts zu sehen. Seufzend
                            läufst du zurück in den Wald, dein Schädel brummt, du wirst dich heute früher zu Bett begeben.
                            Na wenigstens lagen noch ein paar Edelsteine neben dir, als du aufgewacht bist!";
                
                $session['user']['gems'] += 3;
                if($session['user']['turns'] > 2)
                    $session['user']['turns'] -= 2;
                else
                    $session['user']['turns'] = 0;
                
                $session['user']['specialinc'] = "";
                break;
        }
        break;
    
    case 'rightopen':
        switch(e_rand(1,3)) {
            case 1:
                $str_out = "`7Du öffnest gespannt die Tür und...
                            findest einen vollkommen leeren Raum vor.
                            Aber, immerhin sammelst du dir doch noch einige
                            Edelsteine zusammen und verlässt dann die \"Rabengruft\" durch den Tunnel.";
                            
                $session['user']['gems'] += 4;
                
                addnav("Durch den Tunnel fliehen", "forest.php?op=tunnel");
                
                $session['user']['specialinc'] = "rabengruft.php";
                break;
            case 2:
            case 3:
                $str_out = "`7Du öffnest gespannt die Tür und...
                            deine Augenbrauen heben sich wie von selbst, denn in diesem
                            Raum befindet sich ein Opferaltar. Tief in den Stein gehauen
                            sind Runen, eine Abflussrille für das Blut der Opfer und wieder
                            ein Rabe. Am Ende des Raums befindet sich ein Tunnel nach oben.
                            Was nun?";
                            
                addnav("Opfere dich selbst", "forest.php?op=punish");
                addnav("Den Altar untersuchen", "forest.php?op=search");
                addnav("Gruft verlassen", "forest.php?op=tunnel");
                
                $session['user']['specialinc'] = "rabengruft.php";
                break;
        }
        break;
    
    case 'moregems':
        $str_out = "Du missachtest die Türen vollkommen, stattdessen
                    greifst du dir weitere Edelsteine, allerdings vergisst du, dass
                    diese ja auch den Raum erhellen. Kaum aus dem Boden gerissen,
                    erlischt das Leuchten in ihrem Innern. Schließlich kannst du kaum mehr die
                    Hand vor Augen erkennen und suchst in Panik nach dem Tunnel.
                    Nach dem du dich ewig lange an der Wand entlang getastet hast
                    gibt diese plötzlich deinen suchenden Händen nach und du kippst vorne
                    über. Das muss wohl eine Falltür gewesen sein.`4 Du bist tot!!!";
        
        $session['user']['gems'] += 6;
        $session['user']['alive'] = false;
        $session['user']['hitpoints'] = 0;
        
        $session['user']['specialinc'] = "";
        
        addnav("Tägliche News", "news.php");
        addnews($session['user']['name']."s `4Lebenslicht erlosch genauso, wie das Licht der Edelsteine in der Rabengruft.");
        break;
    
    case 'tunnel':
        $str_out = "`9Dir wird es doch ein wenig zu mulmig und du beschließt,
                    die Rabengruft zu verlassen. Oben musst du dich durch ein dichtes
                    Dorngestrüpp kämpfen und ziehst dir ein paar Kratzer zu.";
                    
        $session['user']['hitpoints'] -= $dornen;
        $session['user']['specialinc'] = "";
        break;
        
    case 'punish':
        $str_out =  "`7Du beschließt, dass du dich ehrerbietig auf den Opferaltar legen solltest.
                    Also schluckst du noch einmal schwer, und begibst dich dann auf den Altar zu,
                    legst dich darauf nieder.";
                            
        switch(e_rand(1,4)) {
            case 1:
                $str_out .= "`n`7Nach einer Weile nähert sich dir eine `Gschwarze`7,
                            gefiederte Gestalt...ohne ein Wort zu sprechen hebt sie einen `Gschwarzen
                            `7Dolch und rammt ihn dir gnadenlos in die Brust. Du hast bekommen, was du wolltest!";
                        
                if($session['user']['level'] < 10) {
                    $str_out .= "`4DU BIST TOT!!!";
                    
                    $session['user']['alive'] = false;
                    $session['user']['hitpoints'] = 0;
                    
                    addnav("Tägliche News", "news.php");
                    
                    addnews($session['user']['name']." `6opferte sich freiwillig dem dunklen Rabengott und wurde von diesem bestraft.");
                    
                    $session['user']['specialinc'] = "";
                }
                else {
                    $str_out .= "`nAllerdings stellt Ramius dich, auf Grund deines Mutes, vor die Wahl, ob du schwerverletzt
                                in die Welt der Lebendigen zurück-, oder in sein Totenreich willst, um hier vielleicht
                                die Chance auf einen Neuanfang zu bekommen.";
                                
                    addnav("Ich will leben!", "forest.php?op=alive");
                    addnav("Ich will sterben!", "forest.php?op=die");
                    
                    $session['user']['specialinc'] = "rabengruft.php";
                }
            break;
            case 2:
            case 3:
                $str_out .= "`n`7Nach einer Weile nähert sich dir eine `&weiße`7,
                            gefiederte Gestalt...ohne ein Wort zu sprechen legt sie dir sanft eine Hand auf die
                            Stirn. Deine Augenlider schließen sich wie von selbst. Später erwachst du im Wald
                            und bemerkst, dass die Sonne viel weiter im Osten steht, als zu Beginn deines Rabengruft-Abenteuers.
                            Außerdem fühlst du dich um einiges stärker!";
                            
                $session['user']['turns']+=10;
                $session['user']['maxhitpoints']++;
                $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
                
                addnews($session['user']['name']." `6opferte sich freiwillig dem lichten Rabengott und wurde von diesem belohnt.");
                $session['user']['specialinc'] = "";
                break;
            case 4:
                $str_out .= "`n`7Aber es geschieht absolut garnichts. Grummelnd verlässt du die Gruft über den
                            Tunnel, wenigstens fallen die Defizite den Gewinnen gegenüber nicht zu sehr ins Gewicht.";
                
                $session['user']['specialinc']="";
                break;
        }
        break;
    
    case 'alive':
        $str_out = "`mRamius nickt dir schmunzelnd zu und befördert dich in die Welt der Lebenden";
        
        if($session['user']['turns'] > 2)
            $session['user']['turns'] -= 2;
        else
            $session['user']['turns'] = 0;
        $session['user']['hitpoints'] = $dornen;
        
        $session['user']['specialinc'] = "";
        
        addnews($session['user']['name']." `mdurfte durch Ramius Gnade weiterleben und verstarb nicht in der Rabengruft");
        break;
    
    case 'die':
        $str_out = "`mRamius hebt erstaunt eine Augenbraue, öffnet dir dann aber das Tor zu den Schatten.
                    Für diese Geste allerdings gewährt er dir `^45`m Gefallen!";
                    
        $session['user']['deathpower'] += 45;
        $session['user']['alive'] = false;
        $session['user']['hitpoints'] = 0;
        
        addnav("Tägliche News", "news.php");
        
        addnews($session['user']['name']." `kging freiwillig von der Rabengruft zu Ramius");
        
        $session['user']['specialinc'] = "";
        break;

    case 'search':
        $str_out = "Du beschließt den Altar zu untersuchen und
                    besiehst ihn dir genauer. Du bemerkst";
                    
        switch(e_rand(1,3)) {
            case 1:
                $str_out .= ", dass der Altar ebenfalls mit jenem Raben an der Seite
                            verziert ist, nur ist dieser aus purem Gold. Rasch schabst
                            du mit deiner Waffe etwas davon ab, steckst es ein und
                            verlässt die Rabengruft durch den Tunnel";
                            
                $session['user']['gold']+=$gold;
                
                addnav("Durch den Tunnel!", "forest.php?op=tunnel");
                $session['user']['specialinc'] = "rabengruft.php";
                break;
            case 2:
                $str_out .= ", dass in den Altar Abflussrillen für das Blut der Opfer
                            eingemeißelt sind. Ja, tatsächlich, darin klebt noch getrocknetes
                            Blut. Warum auch immer, du kratzt vorsichtig mit deiner Waffe darüber
                            und ein betäubender Schwefelgestank verbreitet sich. Von Übelkeit überwältigt
                            , suchst du rasch durch den Tunnel das Weite. Du verlierst dabei ein paar
                            Lebenspunkte";
                            
                $session['user']['hitpoints'] -= $übelkeit;
                addnav("Durch den Tunnel!", "forest.php?op=tunnel");
                
                $session['user']['specialinc'] = "rabengruft.php";
                break;
            case 3:
                $str_out .= ", dass der Altar nichts wirklich interessantes aufweist.
                            Als du dich allerdings auf ihm abstützt, um wieder auf die Füße
                            zu kommen, bemerkst du, wie oben, an der Decke etwas scharrt. Rasch
                            siehst du nach oben und beobachtest, wie sich eine Luke aufschiebt,
                            aus der eine Strickleiter herausfällt. Ohne lange nachzudenken nimmst du
                            diese und steigst nach oben...nach langer Kletterpartie kommst du endlich
                            wieder auf der Lichtung an, von der dein Abenteuer startete, die Luke schließ sich
                            und du verspürst keine Lust, nochmal in die Rabengruft hinabzuspringen...so
                            kehrst du dem ganzen den Rücken zu und begibst dich in den Wald, alsbald hast du
                            das ganze auch wieder vergessen";
                
                $session['user']['specialinc'] = "";
                break;
        }
        break;
}

output($str_out);
?>

