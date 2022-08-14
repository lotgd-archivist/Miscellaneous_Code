
<?php

/*

------------------------------------------------------------

|                        bett.php                           |

|                        Idee by                            |

|                         Elsia                             |

|                       Script by                           |

|                        Erenya                             |

|                 erenattarath@hotmail.com                  |

------------------------------------------------------------

*/

if (!isset($session)) exit();

if ($_GET['op']==""){

  output("`n`b`cBett des Bettkobolds`c`b`n");

  output("Nach deiner Reise im Wald entdeckst du mitten auf einer Lichtung ein Bett. Es sieht weich und flauschig aus. Aus ErzÃ¤hlungen anderer Bewohner ahnst du das dieses Bett dem Bettkobold gehÃ¶ren kÃ¶nnte. Du kÃ¶nntest hier bleiben und dieses Bett mal ausprobieren, oder du bleibst hier und wartest auf den Bettkobold um ihn den Gar auszumachen und an seine legendÃ¤ren SchÃ¤tze zu kommen. Oder lÃ¤sst du das Bett ein bett sein und rennst weg?");

  $session['user']['specialinc']="bett.php";

  addnav("In seinem Bett schlafen","forest.php?op=schlaf");

  addnav("Den Kobold tÃ¶ten","forest.php?op=tot");

  addnav("zurÃ¼ck in den Wald","forest.php?op=z");

}

if ($_GET['op']=="schlaf"){

  output("Du legst dich in das Bett und dÃ¶st ein, doch als der Bettkobold kommt, wird er ungeduldig, zumal er nicht gerne sein Bett mit jemanden teilt, und weckt dich aus deinem Traum. Nun gibt es nur noch eine MÃ¶glichkeit ihn zu besÃ¤nftigen.");

  $session['user']['specialinc']="bett.php";

  addnav("KÃ¤mpfe","forest.php?op=kampf");

}





if ($_GET['op']=="kampf"){

  output("`&Du entdeckst deinen Gegner `2Bettkobold`& der sich mit seiner Waffe `2 flauschiges Federkissen`& auf dich stÃ¼rzst.");

  $badguy = array(

                "creaturename"=>"`%Bettkobold`0",

                "creaturelevel"=>$session['user']['level'],

                "creatureweapon"=>"`%flauschiges Federkissen",

                "creatureattack"=>$session['user']['attack']*0.9,

                "creaturedefense"=>$session['user']['defence']*0.9,

                "creaturehealth"=>round($session['user']['maxhitpoints']*0.8,0),

                "diddamage"=>0);

        $session['user']['badguy']=createstring($badguy);

        $session['user']['specialinc']="bett.php";

        $battle=true;

        $session['user']['specialinc']="";

}

//Battle Settings

if ($_GET['op']=="run"){   // Flucht

    if (e_rand()%3 == 0){

    output("`c`bDu konntest dem Bettkobold entkommen.`b`c");

    $_GET['op']="";

    }else{

    output("`c`bDer bettkobold war schneller als du. `b`c");

            $battle=true;

    }

}

if ($_GET['op']=="fight"){   // Kampf

    $battle=true;

    $session['user']['specialinc']="";

}

if ($battle) {

    include("battle.php");

    $session['user']['specialinc']="bett.php";

        if ($victory){

            $badguy=array();

            $session['user']['badguy']="";

            output("`nDu konntest den Bettkobold besiegen, hattest aber schwer zu kÃ¤mpfen.");

            //debuglog("defeated a oger");

            //Navigation

            $session['user']['specialinc']="bett.php";

            addnav("Weiter","forest.php?op=weiter");

            if (rand(1,5)==1) {

                $gem_gain = rand(1,2);

                $gold_gain = rand($session['user']['level']*5,$session['user']['level']*10);

                output(" Du findest Du $gem_gain Edelsteine und $gold_gain GoldstÃ¼cke.`n`n");

            }

            $exp = round($session['user']['experience']*0.005);

            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");

            $session['user']['experience']+=$exp;

            $session['user']['gold']+=$gold_gain;

            $session['user']['gems']+=$gem_gain;

        } elseif ($defeat){

            $badguy=array();

            $session['user']['badguy']="";

            //debuglog("was killed by a oger.");

            output("WÃ¤hrend deines letzten Atemzugs siehst du wie der Bettkobold sich in seinem Bett gemÃ¼tlich macht und mit diesem wegfliegt.");

            addnav("TÃ¤gliche News","news.php");

            addnews("`0".$session['user']['name']." `0war so dreist sich in das Bett des Bettkoboldes zu legen!`0");

            $session['user']['alive']=0;

            $session['user']['hitpoints']=0;

            //$session['user']['gems']=0;

            $session['user']['gold']=0;

            $session['user']['experience']=round($session['user']['experience']*.95,0);

            $session['user']['specialinc']="";

        } else {

            fightnav(true,true);

        }



}

if ($_GET['op']=="weiter"){

  $session['user']['specialinc']="";

  output("Du hast den Bettkobold besiegt.");

}

if($_GET['op']=="tot"){

  switch(e_rand(1,4)){

  case 1:

  case 4:

  output("Du wartest das der Kobold kommt, als er auftaucht stÃ¼rzt du dich aus dem Hinterhalt heraus auf ihn und machst ihm wie geplant den Gar aus. Als du von seinen legendÃ¤ren SchÃ¤tzen aber nichts findest, suchst du Ã¼berall danach, bis du in der Matratze SÃ¤ckeweise Gold und Edelsteine findest.");

  $session['user']['gold']+=500;

  $session['user']['gems']++;

  $session['user']['specialinc']="";

  break;

  case 2:

  case 3:

  output("Du wartest das der Kobold kommt, als er auftaucht, stÃ¼rzt du dich aus dem Hinterhalt heraus auf ihn, doch gerade als du ihm den Gar ausmachen willst, trifft dich ein Blitz und befÃ¶rdert dich ins Reich der Toten.");

  $session['user']['hitpoints']=0;

  $session['user']['alive']=0;

  $session['user']['specialinc']="";

  addnews("`0".$session['user']['name']." `0wurde bei dem feigen Versuch den Bettkobold zu tÃ¶ten, von einem Blitz getroffen.");

  addnav("TÃ¤gliche News","news.php");

  }

}

if ($_GET['op']=="z"){

  output("Du wÃ¼sstest nicht, warum dich ein Bett mitten im Wald, das einer nicht bestÃ¤tigten Existenz gehÃ¶rt, interessieren sollte und gehst wieder zurÃ¼ck in den Wald.");

}

?>

