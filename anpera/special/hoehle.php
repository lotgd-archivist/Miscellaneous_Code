
ï»¿<?php



// 20160407



/***************************************************

  Die HÃ¶hle, ein Special fÃ¼r den Wald

  by Basilius (Eliwood)

  Version 1.3

  Required  function give_new_loot:

  -----------------------------------------------------------

  function give_new_loot($name,$description,$gold=0,$gems=0)

  {

     // Gives Users a New Loot

     // by Basilius, Eliwood

     global $session;

     $sql = "INSERT INTO items (name,description,class,owner,gold,gems) "

           ."VALUES ('$name','$description','Beute','".$session['user']['acctid']."','$gold','$gems')";

     db_query($sql) or die("Ach, so nen Mist, schon wieder verbockt!");

  }

  -----------------------------------------------------------

***************************************************/

if (!isset($session)) exit();



function give_new_loot($name,$description,$gold=0,$gems=0){

     // Gives Users a New Loot

     // by Basilius, Eliwood

     global $session;

     $sql = "INSERT INTO items (name,description,class,owner,gold,gems) "

           ."VALUES ('$name','$description','Beute','".$session['user']['acctid']."','$gold','$gems')";

     db_query($sql) or die("Ach, so nen Mist, schon wieder verbockt!");

}



switch ($_GET['op'])

{

  case "goforest":

    output("`3Du hast Bammel davor, die HÃ¶hle zu betreten und gehst wieder zurÃ¼ck in den Wald.");

    //addnews("`3".$session['user']['name']." `3hatte Angst davor, eine HÃ¶hle zu betreten.");

    $session['user']['specialinc']="";

    break;

  case "betritt":

    output("`3Du betrittst vorsichtig die HÃ¶hle. Riesige Stalagtiten hÃ¤ngen von der Decke runter, die Stalagmiten sehen aus, als ob sie nur auf unvorsichtige Wanderer warten, um sie aufzuspiessen. Im faden Licht meinst du sogar einige Blutspuren auf einem Stalagmit zu sehen. In der Mitte der HÃ¶hle bilden stattliche Stalagnate einen Kreis. In der Mitte des Kreises bildet ein Stalagmit eine Art von Tisch, auf dessen Mitte etwas zu sein scheint.");

    addnav("Weiter","forest.php?op=altar");

    $session['user']['specialinc']="hoehle.php";

    break;

  case "altar":

    output("`3Du gehst in Mitte des Stalagnatenkreises und siehst dort auf dem Tisch ein HÃ¤ufchen Gold liegen. In der Mitte des HÃ¤ufchens liegt ein Ring aus Glas und ein mit Edelsteinen verzierter Ring.");

    addnav("Gold nehmen","forest.php?op=nimmgold");

    addnav("Edelsteinring nehmen","forest.php?op=nimmring");

    addnav("Glasring nehmen","forest.php?op=nimmglas");

    addnav("Alles nehmen","forest.php?op=nimmalles");

    addnav("Nichts nehmen","forest.php?op=nimmnichts");

    $session['user']['specialinc']="hoehle.php";

    break;

  case "nimmgold":

    $rand = e_rand(1,3);

    if ($rand==1){

      $gold = e_rand(100,2000);

      output("`3Du nimmst dir die `^$gold GoldstÃ¼cke`3 und veschwindest so schnell du kannst wieder aus der HÃ¶hle.");

      addnews("`3".$session['user']['name']." wurde in einer HÃ¶hle reich und fand `^$gold GoldstÃ¼cke`3.");

      $session['user']['gold']+=$gold;

    }else{

      output("`3Du nimmst das Gold an dich, doch in dem Augenblick lÃ¶st du eine Falle aus. Ein Stalagtit lÃ¶st sich von der Decke und erschlÃ¤gt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");

      $session['user']['gold']=0;

      $session['user']['experience']*=0.9;

      $session['user']['hitpoints']=0;

      $session['user']['alive']=0;

      addnav("TÃ¤gliche News","news.php");

      addnews("`3".$session['user']['name']." wurde in einer HÃ¶hle zu gierig und wurde von einem Stalagtit erschlagen.");

    }

    $session['user']['specialinc'] = "";

    break;

  case "nimmring":

    $rand=e_rand(1,3);

    if ($rand == 1){

      output("`3Du packst dir den Ring und rennst so schnell du kannst wieder aus der HÃ¶hle raus.");

      give_new_loot("Ring","Ein mit Edelsteinen verzierter Ring",1000,3);

      addnews("`3".$session['user']['name']." hat in einer HÃ¶hle einen mit Edelsteinen verzierten Ring gefunden.");

    }else{

      output("`3Du nimmst dir den Edelstein verzierten Ring, doch in dem Augenblick lÃ¶sst du eine Falle aus. Ein Stalagtit lÃ¶st sich von der Decke und erschlÃ¤gt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");

      $session['user']['gold']=0;

      $session['user']['experience']*=0.9;

      $session['user']['hitpoints']=0;

      $session['user']['alive']=0;

      addnav("TÃ¤gliche News","news.php");

      addnews("`3".$session['user']['name']." wurde in einer HÃ¶hle zu gierig und wurde von einem Stalagtit erschlagen.");

    }

    $session['user']['specialinc']="";

    break;

  case "nimmglas":

    $rand=e_rand(1,3);

    if ($rand != 3){

      output("`3Du nimmst dir den Glasring und rennst so schnell du kannst aus der HÃ¶hle.");

      give_new_loot("Glasring","Ein WunderschÃ¶ner Ring aus Glas",100,0);

      addnews("`3".$session['user']['name']." hat einen (fast) wertlosen Glasring in einer HÃ¶hle gefunden.");

    }else{

      output("`3Du nimmst den Ring, doch in dem Augenblick lÃ¶sst du eine Falle aus. Ein Stalagtit lÃ¶st sich von der Decke und erschlÃ¤gt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");

      $session['user']['gold']=0;

      $session['user']['experience']*=0.9;

      $session['user']['hitpoints']=0;

      $session['user']['alive']=0;

      addnav("TÃ¤gliche News","news.php");

      addnews("`3".$session['user']['name']." wurde in einer HÃ¶hle zu gierig und wurde von einem Stalagtit erschlagen.");

    }

    $session['user']['specialinc'] = "";

    break;

  case "nimmalles":

      output("`3Du packst alles in deine Tasche, doch in dem Augenblick lÃ¶st du eine Falle aus. Ein Stalagtit lÃ¶sst sich von der Decke und erschlÃ¤gt dich.`n`n`\$Du bist TOT!`n`n`#Du verlierst all dein Gold und 10% Deiner Erfahrung.");

      $session['user']['gold']=0;

      $session['user']['experience']*=0.9;

      $session['user']['hitpoints']=0;

      $session['user']['alive']=0;

      addnav("TÃ¤gliche News","news.php");

      addnews("`3".$session['user']['name']." wurde in einer HÃ¶hle zu gierig und wurde von einem Stalagtit erschlagen.");

    $session['user']['specialinc'] = "";

    break;

  case "nimmnichts":

    $exp = e_rand(round($session['user']['experience']*0.05),round($session['user']['experience']*0.1));

    output("Du entschliesst dich, nichts zu nehmen und verlÃ¤sst die HÃ¶hle. Gerade als du beim Eingang angekommen bist, kracht ein Stalagmit auf den Altar. Du hast GlÃ¼ck gehabt, hÃ¤ttest du was davon genommen, wÃ¤rst du sicherlich gestorben. Du bekommst $exp Erfahrungspunkte.");

    $session['user']['experience']+=$exp;

    $session['user']['specialinc'] = "";

    break;

    default:

    output("`3WÃ¤hrend deiner StreifzÃ¼ge durch den Wald entdeckst du eine kleine Einbuchtung in einem Felsen.");

    switch (e_rand(1,5)){

        case 3:

        output("Du untersuchst den Felsen grÃ¼ndlich und entdeckst, dass sich hinter der Einbuchtung eine HÃ¶hle befinden muss.");

        if ($session['user']['turns']>1){

          $session['user']['turns']-=2;

          output("Du nimmst deine ".$session['user']['weapon']." und machst dich an der Einbuchtung zu schaffen. Die Zeit verrinnt, und als du den Eingang gross genug gemacht hast, merkst du, dass du locker 2 Monster in dieser Zeit hÃ¤ttest erledigen kÃ¶nnen.`n");

          $session['user']['specialinc']="hoehle.php";

          addnav("Die HÃ¶hle betreten","forest.php?op=betritt");

          addnav("Den Ort verlassen","forest.php?op=goforest");

        }else{

          $session['user']['turns']=0;

          output("Du nimmst deine ".$session['user']['weapon']." und machst dich an der Einbuchtung zu schaffen. Die Zeit verrinnt und nach einer Weile brichst du erschÃ¶pft zusammen. Du bist nun zu erschÃ¶pft um weiter zumachen oder gar noch ein Monster zu erledigen.`n");

          $session['user']['specialinc']="";

        }

        break;

      default:

        $gems = e_rand(0,2);

        $gold = e_rand($session['user']['level']*40,$session['user']['level']*90);

        output("`3Du untersuchst den Felsen grÃ¼ndlich und findest `^$gold GoldstÃ¼cke`3 und ".($gems==1?"`%1 Edelstein":"`%$gems Edelsteine")."`3. WÃ¤hrend du dich Ã¼ber den Schatz freust, hÃ¤ttest du locker ein Monster erledigen kÃ¶nnten.`n`nDu machst dich mit deinem Neuerworbenen Schatz auf zurÃ¼ck in den Wald.");

        if ($session['user']['turns']>0) $session['user']['turns']--;

        $session['user']['gold']+=$gold;

        $session['user']['gems']+=$gems;

        $session['user']['specialinc']="";

        break;

    }

    break;

}



?>

