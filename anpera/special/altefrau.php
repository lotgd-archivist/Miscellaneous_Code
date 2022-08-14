
ï»¿<?php



// 20060302



/* Scriptet by Â°*Amerilion*Â° comments to www.steffenmischnick@gmx.de

   special THX to Hadirel www.hadirelnet.ch and to FLY



   Ã¼berarbeitet von anpera */



if (!isset($session)) exit();

page_header("Alte Frau");

$min=e_rand(1,2);

$ming=e_rand(200,750);

$minga=e_rand(2000,2500,3000)  ;



if($_GET['op']=="" || $_GET['op']=="search"){

    output("WÃ¤hrend du den Wald durchziehst, endeckst du neben einem kleinen Bach ein seltsam windschiefes Haus, welches aus groben Steinen erbaut wurde, die langsam von Moos bedeckt werden.");

    output("Du Ã¼berlegst noch, ob du hineingehen sollst, als sich die morsche HolztÃ¼r knarrend Ã¶ffnet. Du blickst in das runzelige Gesicht einer alten Frau.");

    output("Nun stehst du vor der Wahl, mit ihr zu reden, oder aber weiterzugehen.")   ;

    addnav("ZuhÃ¶ren","forest.php?op=Zuhoeren");

    addnav("Weitergehen","forest.php?op=Weitergehen");

    $session['user']['specialinc']="altefrau.php";

}



if($_GET['op']=="Weitergehen"){

    output("Du stehst vor der Wahl nach links oder nach rechst zu gehen");

    $session['user']['specialinc']="altefrau.php";

    addnav("Rechts","forest.php?op=Rechts");

    addnav("Links","forest.php?op=Links");

}



if($_GET['op']=="Rechts" || $_GET['op']=="Links"){

    $min=e_rand(1,3);

    $ming=e_rand(200,750);

    if ($min>$session['user']['turns']) $min=$session['user']['turns'];

    $session['user']['specialinc']="";

    switch(e_rand(1,4)){

        case 1:

          $session['user']['turns']-=$min;

          output("Du irrst etwas umher, kannst aber nichts finden. Ein ganzer Waldkampf vertrÃ¶delt...");

        break;

        case 2:

          $session['user']['gold']-=$ming;

          if ($session['user']['gold']<0) $session ['user']['gold']=0;

          $session['user']['turns']-=$min;

        output("Du gehst nach ".$_GET['op']." und fÃ¤llst Ã¼ber einen Stein. Dabei verlierst du etwas Gold und liegst einige Zeit auf den Boden.");

        break;

        case 3:

          $session['user']['gems']+=$min;

          $session['user']['turns']-=$min;

        output("Du findest einen kleinen Beutel mit kostbaren Edelsteinen.") ;

        break;

        case 4:

          $session['user']['gems']-=$min;

          $session['user']['turns']-=$min;

        output("Du bemerkst erst viel spÃ¤ter, dass du ein paar Edelsteine verloren hast.");

        break;

    }

}



if($_GET['op']=="Zuhoeren"){

    output("`#Wie schÃ¶n, sonst rennen viele aus Angst, dass ich eine Hexe sein kÃ¶nnte, weg...`n`n`^");

    switch(e_rand(1,3)){

    case 1:

    case 2:

    output("`#und es ist dein Pech, dass du das nicht getan hast! `~R`4a`~b`4a`~n`4t`~i`4c`~u`4s!!!") ;

    output("Ein Blitz rast aus der HandflÃ¤che der Frau auf dich zu und tÃ¶tet dich!") ;

    $session['user']['alive']=0;

    $session['user']['hitpoints']=0;

    $session['user']['gold']=0;

    $session['user']['experience']*0.97;

    addnews($session['user']['name']."`0 starb durch die Hand einer alten Frau.");

    addnav("TÃ¤gliche News","news.php");

    break;

    case 3:

    output("`#dabei suche ich nur jemanden, der meinen Abenteuern lauscht.");

    output("Wirst du ihr zuhÃ¶ren oder in den Wald zurÃ¼ckkehren?");

    $session['user']['specialinc']="altefrau.php";

    addnav("Lauschen","forest.php?op=Weiter");

    addnav("ZurÃ¼ck","forest.php?op=Weitergehen");

    break;

    }

}



if($_GET['op']=="Weiter"){

    $min=e_rand(1,2);

    $minga=200*e_rand(4,6)  ;

    output("`#Wie schÃ¶n. Tritt bitte ein.");

    output("Du verbringst eine Zeit bei der Frau in der gemÃ¼tlichen HÃ¼tte und lernst etwas. AuÃŸerdem gibt sie dir etwas ihrer ReichtÃ¼mer.")  ;

    $session['user']['gold']+=$minga;

    $session['user']['turns']-=$min;

    $session['user']['experience']*=1.05;

    addnews($session['user']['name']."`0 bekam Reichtum und Wissen im Wald");

    $session['user']['specialinc']="";

}

?>

