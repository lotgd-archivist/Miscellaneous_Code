<?php



require_once "common.php";
page_header("Wähle deinen Gott:");
$session['user']['gottjanein'] = 1;

 if ((int)$_GET['setgott']!='')
    {
    $session['user']['gott']=(int)($_GET['setgott']);
        switch((int)$_GET['setgott'])
        {
        case 1:
            $session['user']['attack']+=2;
            output('`0Du betest `QT`qæ`tr`&u`Qn`0, den Gott des Krieges an. Im Kampf singst du seine Lobeslieder. Dein Angriff steigt um 2 !.');
            break;
        case 2:
            $session['user']['turns']+=5;
            output('Du betest `~K`Se`al`e`vf`ea`aï`Sr`~e`0 an! Sie ist die Göttin der Wälder und sie gewährt dir 5 zusätzliche Waldkämpfe am Tag!.');
            break;
        case 3:
            $session['user']['defence']+=2;
            output('`IS`F`eh`aa`eï`ad`Fr`Ie`0`0, die Göttin der Nacht, leitet deinen Weg. In Dunkelheit gehüllt, fällt es deinen Gegnern schwer dich zu treffen! +2 Verteidigung!.');
            break;
        case 4:
            
            $session['user']['gold']+=250;
            output('`UJ`Sy`Tg`Sg`Ul`So`Tn`0 ist dein Vorbild und Gott. Er gewährt dir für deine Studien 250 Gold am Tag!.');
            break;
                    }
        addnav('Zurück','village.php');

    }else{
    //Götter//
        output('Wer soll deine Gottheit sein?`n`n`$ HINWEIS: Kann bis zum nächsten Drachenkill nicht geändert werden!`n`n`$`bDie Götter sind NICHT ins RP einzubauen!`b`n`n');
output("`0<a href='gottwahl.php?setgott=1$resline'>`QT`qæ`tr`&u`Qn`0</a> ist der Gott des Krieges und wird deinen Angriff stärken!`n <a href='gottwahl.php?setgott=2$resline'>`~K`Se`al`e`vf`ea`aï`Sr`~e`0</a> ist die Göttin der Wälder. Sie wird die zusätzliche Waldkämpfe beschehren.`n <a href='gottwahl.php?setgott=3$resline'>`IS`F`eh`aa`eï`ad`Fr`Ie`0</a> ist die Göttin der Nacht und des Vergessens. Sie wird deine Verteidigung erhöhen!.`n <a href='gottwahl.php?setgott=4$resline'>`UJ`Sy`Tg`Sg`Ul`So`Tn`0</a> ist der Gott der Schriften und der Lyrik. Durch ihn erhälst du 250 Gold am Tag!.`n",true);
        
        addnav('Wähle deinen Gott');
        addnav('`QT`qæ`tr`&u`Qn`0',"gottwahl.php?setgott=1$resline",true);
        addnav('`~K`Se`al`e`vf`ea`aï`Sr`~e`0',"gottwahl.php?setgott=2$resline",true);
        addnav('`IS`F`eh`aa`eï`ad`Fr`Ie`0',"gottwahl.php?setgott=3$resline",true);
        addnav('`UJ`Sy`Tg`Sg`Ul`So`Tn`0',"gottwahl.php?setgott=4$resline",true);
        
        allownav("gottwahl.php?setgott=1$resline");
        allownav("gottwahl.php?setgott=2$resline");
        allownav("gottwahl.php?setgott=3$resline");
        allownav("gottwahl.php?setgott=4$resline");
    }

page_footer();
?>