
<?php

// idea by Nyx
// coding by Nyx
require_once"common.php";
page_header("Der Friedhof");

if ($session[user][superuser] >= 0) {

    if($_GET['op'] == '' || $_GET['op']== 'search'){ 
        $grabopen = 0;
        $session[user][specialinc]="cementary.php";

        output("`^Als du durch die Wälter stolperst findest du ein verwachsenes Metalltor, welches scheinbar schon Jahrhunderte nicht mehr geöffnet wurde. Langsam gehst du auf das Tor zu, getrieben von deinem Interesse. Leise knacken kleine Äste unter deinen Füssen, als du dich näherst. Das leise Heulen des Windes, der modrige Geruch und die kleine Eule, welche im Geäst hinter dir sitzt lässt dich erahnen, dass du einen alten Friedhof gefunden hast.`0");
        addnav("Friedhof betreten","forest.php?op=enter");
        addnav("Lass den Toten ihre Ruhe","forest.php?op=leave");

    }

    else if ($_GET[op]=="enter")
        {
        $session[user][specialinc]="cementary.php";
        output("`^Leise quietschend öffnest du das grosse Tor und betrittst den Friedhof. Der Geruch von Moder schlägt dir nun stärker entgegen. Du blickst dich um und siehst einige Grabsteine und verwitterte Gräber.`0");
        addnav("Grabsteine lesen","forest.php?op=read");
        addnav("Den Friedhof verlassen","forest.php?op=leave");

        }

    else if ($_GET[op]=="read")
        {
        $session[user][specialinc]="cementary.php";
        output("`^Du näherst dich einem der Grabsteine und liest was darauf geschrieben steht:`0`n`n");
        $grabstein = e_rand(1,5);
        if ($grabstein == 1) {
            output("`@Hier liegen meine Gebeine, ich wollte es wären Deine.`0`n");
            }
        else if ($grabstein == 2) {
            output("`@Als Jungfrau geboren,`n");
            output("als Jungfrau gelebt,`n");
            output("als Jungfrau gestorben.`n");
            output("Kurz gesagt: Ungeöffnet zurück!`n`0");
            }
        else if ($grabstein == 3) {output("`@Der Grüne Drache war wohl schneller...`0`n");}
        else if ($grabstein == 4) {
            output("`@Sehr ungleich gehts auf Erden zu`n");
            output("Ich heut, der gestern, morgen Du.`0`n");
            }
        else if ($grabstein ==5) {
            output("`@Die Welt ist ganz und gar verdorben,`n");
            output("ich bin an einem Lebkuchen gestorben.`0`n");
            }
        output("`n`^Du denkst dir nur 'So ein Schwachsinn' und gehst weiter...`0");
        addnav("Grabsteine näher untersuchen","forest.php?op=search");
        addnav("Den Friedhof verlassen","forest.php?op=leave");
        }
    else if ($_GET[op]=="search")
        {
        $session[user][specialinc]="cementary.php";
        $suchergebnis = e_rand(1,10);
        if ($suchergebnis < 5)
            {output("Du schaust über die Gräber aber bemerkst keines was dich sonderlich interessieren würde. Du gehst besser lieber, hier ist es dir zu unheimlich...");
            }
        else if ($suchergebnis>=5)
            {output("`^Der Grabstein hier scheint irgendwie anderes zu sein, vielleicht wurde er erst vor kurzem geöffnet? Willst du reinschaun?`0");
            addnav("Grab öffnen","forest.php?op=open");
            }
        
        addnav("Den Friedhof verlassen","forest.php?op=leave");


        }
    else if ($_GET[op]=="open")
        {$session[user][specialinc]="cementary.php";
        output("`^Vorsichtig öffnest du das Grab und blickst hinein.");
            if ($session[user][maxhitpoints] < 100 && $session[user][hitpoints] > 5)
                {output("Da du etwas schwach bist rutscht dir der Sargdeckel aus den Händen und fällt dir auf die große Zehe. AUA!. Du verlierst 10% deiner Lebenspunkte`n`n");
                $session[user][hitpoints]*=0.9;
                }
            $inhalt = e_rand(1,3);
            if ($inhalt == 1)
                {output("Du findest im Inneren eine Skelett, und das war's auch schon. Enttäuscht ziehst du wieder ab");
                }
            else if ($inhalt == 2)
                { $gold = e_rand(100,1000);
                output("Du findest im Inneren ein Skelett und Grabbeigaben. Die meisten Dinge sind Schrott, doch der kleine Beutel interessiert dich. Du schnappst ihn dir und findest beim Öffnen $gold Gold. So ein Glück!");
                $session[user][gold]+=$gold;
                }
            else if ($inhalt == 3)
                {
                output("Du findest einen madenzerfressenen Kadaver. Bei dem Anblick wird dir so schlecht, dass du dich übergeben musst. Es dauert einige Zeit bis es dir wieder gut geht...`n");
                
                if ($session[user][turns]>1) {
                    output("`@Du verlierst einen Waldkampf`n");
                    $session[user][turns]--;}
                else {
                    output("Du bist zu geschwächt von den heutigen Strapazen und entfernst dich mühsam vom Friedhof, obwohl du noch gern etwas gerastet hättest...");
                    output("`@Du verlierst Lebenspunkte...");
                    $session[user][hitpoints]*=0.9;
                    }
                }
            
        addnav("Den Friedhof verlassen","forest.php?op=leave&grabopen=1");

        }

    else if ($_GET[op]=="leave")
        {
        $grabopen=$HTTP_GET_VARS[grabopen];
        addnav("Weiter","forest.php");
        output("`^Du kehrst wieder in die Wälder zurück und lässt den Toten ihre Ruhe.`0");
        $leavebonus=e_rand(1,7);
        if ($leavebonus > 4 && $grabopen <> 1)
            {output("`n`@Weil du den Toten ihre Ruhe gelassen hast bekommst du einen Charmepunkt dazu!`0");
            $session[user][charm]++;
            }
        else if ($leavebonus==4)
            {output("`n`@Du hättest wohl nicht so schnell flüchten sollen, beim Rausgehen stösst du dir den Kopf an der Friedhofstüre, die irgendjemand wieder geschlossen hat. Seltsam, die war doch noch offen??");
            if ($session[user][charm]>0) 
            {
                output("Durch die Verletzung im Gesicht verlierst du einen Charmepunkt.`0");
                $session[user][charm]--;
            }
            else
            {
                output("Du bist aber sowieso so hässlich dass das nicht weiter ins Gewicht fällt...`0");
            }
        $session[user][specialinc] = "";
        $session[user][turns]--;
            }
        }
    
}
else
{
    output("`^Du fühlst dich hier sehr unbehaglich und gehst weiter, in dem Wissen, dass du hier bald wieder zurückkommen wirst.`0");
}

?>

