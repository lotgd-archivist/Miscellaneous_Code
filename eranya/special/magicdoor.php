
<?php

// Find a magic door with special rooms
//
// idea and coding by: Joshua Schmidtke [alias Mikay Kun]
// 
// build: 2006-08-08
// version: 1.0
//
// 20.06.2006 Tippfehler (Forum 2.4.06) beseitigt
// 25.06.2006 Rechenart geändert.

if (!isset($session)) exit();

$session['user']['specialinc']="magicdoor.php";

switch($_GET['op'])
    {
    case "check":
        output("`@Du schaust dir die Tür genauer an und bemerkst einen goldenen Türgriff. Alles in dir verlang diese Tür zu öffnen. Als du den Türgriff berührst fängt er hell an zu leuchten und du hörst eine Stimme in deinem Kopf sagen:`n`n");
        output("`^\"Ich gehe erst auf, wenn du mir einen Tribut zahlst!\"`n`n");
        output("`@Was möchtest du der Tür zahlen?`n`n");
        addnav("Opfern");
        addnav("Nichts", "forest.php?op=0");
        addnav("1000 Gold", "forest.php?op=1");
        addnav("1 Edelstein", "forest.php?op=2");
        addnav("Blut opfern", "forest.php?op=3");
        addnav("Sonstiges");
        addnav("Flüchten", "forest.php?op=away");
        break;

    case "0":
        output("`@Du bedenkst nichts zu opfern, als du merkst wie deine Hand immer wärmer wird.`n`n");
        output("`^\"Du bist ein schlechter Mensch. Baal hat verlangen nach dir!\"`n`n");
        output("`@Mit diesen Worten entzündet sich deine Hand und dein ganzer Körper geht in Flammen auf.");
        addnav("Tägliche News","news.php");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['specialinc']="";
        addnews("`4".$session['user']['name']."`& ist bei dem Versuch eine Tür zu öffnen in Flammen aufgegangen.");
        break;
        
    case "1":
        if ($session['user']['gold']>=1000)
            {
            output("`@Du denkst daran ein bisschen Gold zu opfern, als der Beutel auch schon in Flammen aufgeht.`n`n");
            output("`^\"Du hast ein teures Opfer gebracht! Ich lasse dich eintreten.\"");
            addnav("Eintreten", "forest.php?op=magicroom");
            $session['user']['gold']-=1000;
            }
            
        else
            {
            output("`@Du möchtest Gold opfern, doch es fällt dir ein, dass du gar keines mehr hast. Du merkst aufeinmal, dass deine Hand warm wird und sie trägt Verbrennungen davon.`n`n");
            output("`^\"Das soll dir eine Lehre sein! Nun geh!\"");
            addnav("Wegrennen", "forest.php");
            $session['user']['specialinc']="";
            
            if ($session['user']['hitpoints']>1)
                {
                $session['user']['hitpoints']=intval($session['user']['hitpoints']/2);
                }
            }
        break;
        
    case "2":
        if ($session['user']['gems']>=1)
            {
            output("`@Du denkst daran einen Edelstein zu opfern, als der Beutel auch schon in Flammen aufgeht.`n`n");
            output("`^\"Du hast ein edles Opfer gebracht! Ich lasse dich eintreten.\"");
            addnav("Eintreten", "forest.php?op=magicroom");
            $session['user']['gems']--;
            }
            
        else
            {
            output("`@Du möchtest einen Edelstein opfern, doch es fällt dir ein, dass du gar keine mehr hast. Du merkst aufeinmal, dass deine Hand warm wird und sie trägt Verbrennungen davon.`n`n");
            output("`^\"Das soll dir eine Lehre sein! Nun geh!\"");
            addnav("Wegrennen", "forest.php");
            $session['user']['specialinc']="";
            
            if ($session['user']['hitpoints']>1)
                {
                $session['user']['hitpoints']=intval($session['user']['hitpoints']/2);
                }
            }
        break;
        
    case "3":
        $failingvictim=rand(2,3);
    
        if (($session['user']['hitpoints']>1) and ($session['user']['turns']>=$failingvictim))
            {
            output("`@Du denkst daran dein Blut zu opfern als du merkst, dass du dich immer schwächer fühlst.`nDer Türgriff färbt sich rot und die Stimme sagt:`n`n");
            output("`^\"Du hast ein großes Opfer abgelegt. Ich lasse dich eintreten.\"`n`n");
            output("`4`bDurch den Schwächeanfall hast du $failingvictim Waldkämpfe weniger.`b");
            addnav("Eintreten", "forest.php?op=magicroom");
            $session['user']['hitpoints']=1;
            $session['user']['turns']-=$failingvictim;
            }
            
        else
            {
            output("`@Du denkst daran, dein Blut zu opfern, doch du bist zu erschöpft dafür.");
            addnav("Zurück", "forest.php?op=check");
            }
        break;
        
    case "magicroom":
        page_header("Baal's magischer Raum");
        output("`@Die Tür öffnet sich und du machst einen Schrit in den Baum. Sofort schließt sich die Tür hinter dir und du fragst dich, was es hier wohl geben wird. Du gehst weiter und...`n`n");
        
        switch(rand(0,10))
            {
            case 0:
                output("`@betrittst den `4Raum des Todes`@. Überall wandeln Seelen von gefallenen Kriegern und Monstern herum und in der Mitte des Raums steht ein Altar mit einem Trank. Du gehts zu dem Trank und trinkst ihn aus. Dabei bemerkst wie er deine Seele säubert.`n`n");
                output("`4`bDu hast 5 Gefallen im Totenreich bekommen.`b`n`n");
                output("`@Der Raum fängt an sich zu drehen. Dir wird ganz schwindelig, und als der Raum zum Stehen kommt, befindest du dich wieder im Wald. Du glaubst kaum, dass dies nur ein Traum war.");
                $session['user']['specialinc']="";
                $session['user']['deathpower']+=5;
                break;
                
            case 1:
                output("`@betrittst den `4Raum des Wissens`@. Überall stehen Regale mit Büchern herum. Du lässt dir ein wenig Zeit um dir ein paar Bücher durchzulesen.`n`n");
                output("`4`bDu hast deine Erfahrung um 5% verbessert.`b`n`n");
                output("`@Der Raum fängt an sich zu drehen. Dir wird ganz schwindelig, und als der Raum zum Stehen kommt, befindest du dich wieder im Wald. Du glaubst kaum, dass dies nur ein Traum war.");
                $session['user']['specialinc']="";
                $session['user']['experience']+=$session['user']['experience']/100*5;
                break;
                
            case 2:
                output("`@betrittst den `4Raum der Schätze`@. Überall stehen Kisten mit Gold herum. Du denkst, dass es schon keiner merken wird, wenn du ein wenig Gold mitnimmst.`n`n");
                $goldtreasure=rand(100,2000);
                output("`4`bDu hast dir $goldtreasure Gold genommen.`b`n`n");
                output("`@Der Raum fängt an sich zu drehen. Dir wird ganz schwindelig, und als der Raum zum Stehen kommt, befindest du dich wieder im Wald. Du glaubst kaum, dass dies nur ein Traum war.");
                $session['user']['specialinc']="";
                $session['user']['gold']+=$goldtreasure;
                break;
                
            case 3:
                output("`@betrittst den `4Raum des Juweliers`@. Überall stehen Kisten mit Diamanten, Rubinen und Smaragden herum. In der Mitte des Raumes steht ein Handwerkertisch mit einem Beutel. Beim näheren betrachten des Beutels merkst du, dass dein Name auf dem Beutel steht und du denkst dir, dass der Beutel wohl für dich ist.`n`n");
                $gemtreasure=rand(2,3);
                output("`4`bDu hast $gemtreasure Edelsteine im Beutel gefunden.`b`n`n");
                output("`@Der Raum fängt an sich zu drehen. Dir wird ganz schwindelig, und als der Raum zum Stehen kommt, befindest du dich wieder im Wald. Du glaubst kaum, dass dies nur ein Traum war.");
                $session['user']['specialinc']="";
                $session['user']['gems']+=$gemtreasure;
                break;
                
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
                output("`@betrittst den `4Raum der unsterblichen Ruhe`@. Überall stehen Betten und Brunnen mit klarem kühlem Wasser herum. In der Mitte des Raumes steht ein prachtvoller Brunnen mit grün-blauem Wasser. Du bist erschöpft und trinkst von dem Wasser.`n`n");
                output("`4`bDeine Lebenspunkte regenerieren sich und du erhältst 2 Waldkämpfe.`b`n`n");
                output("`@Der Raum fängt an sich zu drehen. Dir wird ganz schwindelig und als der Raum zum Stehen kommt, befindest du dich wieder im Wald. Du glaubst kaum, dass dies nur ein Traum war.");
                $session['user']['specialinc']="";
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                $session['user']['turns']+=2;
                break;
            }
        break;
    
    case "back":
        output("`@Du hälst nicht viel von dieser Sache und meinst, dass es besser ist, die Tür einfach zu vergessen.");
        $session['user']['specialinc']="";
        break;
        
    case "away":
        switch(rand(0,10))
            {
            case 0: case 1: case 2: case 3: case 4: case 5: case 8: case 9: case 10:
                output("`@Du versuchst der Tür zu entkommen, aber deine Hand ist wie festgeklebt.");
                addnav("Ahh,... Mist!", "forest.php?op=check");
                break;
                
            case 6: case 7:
                output("`@Du schaffts es deine Hand von dem Türgriff loszureißen, und rennst so schnell du kannst.");
                addnav("Lauf, Johnny!", "forest.php");
                $session['user']['specialinc']="";
                break;
            }
        break;

    default:
        output("`@Auf der Suche nach weiteren Gegnern bemerkst du nicht, dass du den Weg verlassen hast und auf einer großen Lichtung gelandet bist. In der Mitte dieser Lichtung steht eine große Eiche.`nDu gehst auf die Eiche zu und bemerkst, das in ihr eine Tür eingelassen ist. Neben der Tür steht ein Schild:`n`n");
        output("`^Dies ist die große Tür des Gottes `QBaal`^. Sie offenbart jedem eine andere Dimension und hat die Fähigkeit Freude oder Leid über die Person zu bringen die es wagt einzutreten.`n`nGezeichnet `QBaal`n`n");
        output("`@Was willst du machen?`n`n");
        addnav("Tür untersuchen", "forest.php?op=check");
        addnav("Wegrennen", "forest.php?op=back");
        break;
    }

?>

