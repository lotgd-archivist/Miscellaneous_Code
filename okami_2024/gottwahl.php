
<?php

require_once "common.php";
page_header("Wähle deinen Gott:");
$session['user']['gottjanein'] = 1;

 if ($_GET['setgott']!="")
    {
    $session['user']['gott']=(int)($_GET['setgott']);
        switch($_GET['setgott'])
        {
        case "1":
            $session['user']['attack']+=5;
            output("Du betest Sharem, den Gott des Krieges an. Im Kampf singst du seine Lobeslieder. Dein Angriff steigt um 2 !.");
            break;
        case "2":
            
            $session['user']['turns']+=10;
            output("Du betest Shira an! Sie ist die Göttin der Wälder und sie gewährt dir 5 zusätzliche Waldkämpfe am Tag!.");
            break;
        case "3":
            $session['user']['defence']+=5;
            output("Kiri, die Göttin der Nacht, leitet deinen Weg. In Dunkelheit gehüllt, fällt es deinen Gegnern schwer dich zu treffen! +2 Verteidigung!.");
            break;
        case "4":
            
            $session['user']['gold']+=1000;
            output("Shiana ist dein Vorbild und Göttin. sie gewährt dir für deine Studien 250 Gold am Tag!.");
            break;
                    }
        addnav("Zurück","village.php");

    }else{
    //Götter//
        output("Wer soll deine Gottheit sein?`n`n");
        output("`\$HINWEIS: Kann bis zum nächsten Drachenkill nicht geändert werden!`n`n");
        output("<a href='gottwahl.php?setgott=1$resline'>Sharem</a> ist der Gott des Krieges und wird deinen Angriff stärken!`n",true);
        output("<a href='gottwahl.php?setgott=2$resline'>Shira</a> ist die Göttin der Wälder. Sie wird die zusätzliche Wandkämpfe beschehren.`n",true);
        output("<a href='gottwahl.php?setgott=3$resline'>Kiri</a> ist die Göttin der Nacht und des Vergessens. Sie wird deine Verteidigung erhöhen!.`n",true);
        output("<a href='gottwahl.php?setgott=4$resline'>Shiana</a> ist die Göttin der Schriften und der Lyrik. Durch sie erhälst du 1000 Gold am Tag!.`n",true);
        
        addnav("Wähle deinen Gott");
        addnav("Sharem","gottwahl.php?setgott=1$resline");
        addnav("Shira","gottwahl.php?setgott=2$resline");
        addnav("Kiri","gottwahl.php?setgott=3$resline");
        addnav("Shiana","gottwahl.php?setgott=4$resline");
        
        addnav("","gottwahl.php?setgott=1$resline");
        addnav("","gottwahl.php?setgott=2$resline");
        addnav("","gottwahl.php?setgott=3$resline");
        addnav("","gottwahl.php?setgott=4$resline");
    }

page_footer();
?>

