<?php
/* *******************
Inferno
Codiert von Fly
Idee von Fee
email: easykamikaze@lycos.de
*********************
fight from mill.php seen at: www.hadrielnet.ch
******************* 
Überarbeitet von Tidus www.kokoto.de
*/

if (!isset($session)) exit();
     
if ($_GET['op']=='wald'){
     output('`3Du steigst schnell auf einen Baum und vergewisserst Dich, dass kein Waldbrand ausgebrochen ist. Da Du keine Flammen siehst, kletterst Du wieder herab und suchst das Weite.');
     output('`4Du verlierst 2 Waldkämpfe!');
     $session['user']['turns']-=2;
     $session['user']['specialinc']='';
     }else if ($_GET['op']=='untersuchen'){
     $Meter = mt_rand(5,8);
     output("`3Du gehst zu der Einschlagstelle. Es ist ein Krater von ungefähr $Meter Meter Durchmesser.`n`n");
     output('Du trittst näher, schaust in den Krater und siehst einen glühenden Klumpen darin liegen.`nWillst Du herabsteigen?');
     $session['user']['specialinc']='inferno.php';
     addnav('ja','forest.php?op=j');
     addnav('nein','forest.php?op=n');
     }else if ($_GET['op']=='n'){
     output('`3Du willst damit nichts zu tun haben und suchst schnell das Weite.');

     $session['user']['specialinc']='';
     }else if ($_GET['op']=='j'){
      $session['user']['turns']--;
     output('`3 Du kletterst den Krater herab und stehst nur wenige Schritte von dem Klumpen entfernt. ');
     switch (mt_rand(1,5))
             {
             case '1':
             case '2':
                         output("`3  Plötzlich verformt sich dieser und bildet sich zu einem Golem. Er erblickt Dich und greift an.`n`n");
                         $badguy = array(
                                "creaturename"=>"`^Infernal`0",
                                "creaturelevel"=>$session['user']['level']1,
                                "creatureweapon"=>"`^Glühende Hände",
                                "creatureattack"=>$session['user']['attack']1,
                                "creaturedefense"=>$session['user']['defence']1,
                                "creaturehealth"=>round($session['user']['maxhitpoints']1.05,0),
                                "diddamage"=>0);
                        $session['user']['badguy']=createstring($badguy);
                        
                        $battle=true;
                        $session['user']['specialinc']='';
                        addnews("".$session['user']['name']." hat einen glühenden Golem besiegt.");
                        break;

             case '3':
             case '4':
             case '5':
                  output(' Dieser strahlt eine starke Aura aus. `n Willst Du versuchen ihn zu berühren?');
                  addnav('Berühren','forest.php?op=try');
                  addnav('zurück in den Wald','forest.php?op=geh');
                  $session['user']['specialinc']='inferno.php';
                   break;
             }

     }else if ($_GET['op']=='geh'){
    output('`3Du kletterst aus dem Krater und gehst zurück in den Wald.');
    $session['user']['specialinc']='';
}else if ($_GET['op']=='try'){
     output('`3 Du berührst den Klumpen und ');
     $session['user']['specialinc']='';
    switch (mt_rand(1,8)){
            case '1':
            case '2':
                 output('verbrennst Dir dabei Deine Hand.');
                 $lost = mt_rand(3,$session['user']['maxhitpoints']2) ;
                 $session['user']['hitpoints'] -= $lost;
                 $session['user']['maxhitpoints']--;

                 break;
            case '3':
            case '4':
            case '5':
                 output('Du fühlst, wie die Aura des Klumpen zu Dir hinüber fließt.');
                 output('`n');
                 switch (mt_rand(1,7)){
                         case '1':
                              output('Du fühlst Dich stärker und bekommst zwei permanente Lebenspunkte!');
                              $session['user']['maxhitpoints']+=2;
                              break;
                         case '2':
                              output("Deine Haut verkrampft sich und wird fester. Du fühlst Dich jetzt zwar sicherer, doch kannst Du Dich nicht mehr so schnell bewegen, wie vorher.");
                              $session['bufflist']['Haut'] = array("name"=>"`vSteinhaut",
                                        "rounds"=>20,
                                        "wearoff"=>"Deine Haut entspannt sich wieder!",
                                        "defmod"=>1.20,
                                        "atkmod"=>0.82,
                                        "roundmsg"=>"Deine Steinhaut beschwert Deinen Angriff, doch ist sie ein guter Schutz!",
                                        "activate"=>"defense");
                              break;
                         case '3':
                              output('Du fühlst Dich stärker und bekommst einen Verteidigungspunkt!');
                              $session['user']['defence']++;
                              break;
                         case '4':
                              output('Du fühlst Dich stärker und bekommst einen Angriffspunkt!');
                              $session['user']['attack']++;
                              break;
                         case '5':
                              output('Die Aura macht Dich schöner!');
                              $session['user']['charm']++;
                              break;
                         case '6':
                              output('Die Aura macht Dich müde und Du musst Dich eine Runde lang ausruhen!');
                              $session['user']['turns']--;
                              break;

                         case '7':
                              output('Die Aura verstärkt Deine Mystischen Kräfte ');
                              if (mt_rand(0,1)==0){
                               output('und Du bekommst 10 zusätzliche Anwendungen.');
                               $session['user']['magicuses'] = $session['user']['magicuses']  10;
                                }else{
                                 output('um 3 Levels!');
                                  $session['user']['magic'] = $session['user']['magic']  3;
                                  $session['user']['magicuses']++;
                                   }
                               break;



                 }
                 break;
            case '6':
            case '7':
            case '8':
                 output('nichts passiert. Der Klumpen ist kalt wie Eisen. Du untersuchst diesen ');
                 switch (mt_rand(1,3)){
                       case '1':
                       case '2':
                            output('und findest ein seltsames Metall.');
                            if ($session['user']['dragonkills']==0)
                                 {
                                 output("`n Du nimmst es mit, vielleicht kauft es Dir ja jemand ab.");
                                 $gold = mt_rand(5,200);
                                 $gems = mt_rand(0,1);
                                 $text = "ein seltenes Metall";
                                 $sql="INSERT INTO items(name,class,owner,value1,gold,gems,description) VALUES ('Metall','Beute',".$session['user']['acctid'].",0,$gold,$gems,'$text')";
                                 db_query($sql);
                                 }
                            else
                                {
                            output(' Du nimmst dies mit und begibst Dich zu den Schmieden in der Burg.');
                            addnav('zur Burg','forest.php?op=burg');
                              $session['user']['specialinc']='inferno.php';
                              }
                            break;
                       case '3':
                            output('und er zerbricht in tausend kleine Aschekörner. Enttäuscht gehst Du zurück in den Wald.');
                            break;

                 }
                 break;

    }

}else if ($_GET['op']=='burg'){
    $rand = mt_rand(0,1);
    output('`3Du brauchst einen Waldkampf, bis Du die Burg erreichst. Du redest mit dem '.($rand==1?"Waffenschmied":"Rüstungsschmied").'. `n Er meint, das wäre ein sehr seltenes und gutes Metall. Leider nur recht wenig, um daraus eine '.($rand==1?"Waffe":"Rüstung").' zu schmieden.  Er ist aber bereit es Dir abzukaufen und gibt Dir ');
    $gold = mt_rand(10,500);
    $gems = mt_rand(0,2);
    output("$gold Gold und $gems Edelsteine dafür.");
    $session['user']['specialinc']='';
    $session['user']['gold'] += $gold;
    $session['user']['gems'] += $gems;
    addnav('weiter', 'forest.php');
    
    }else if ($_GET['op']=='fight'){   // Kampf
    $battle=true;
    $session['user']['specialinc']='';
}else{
     output('`3Während Du suchend durch den Wald ziehst, erblickst Du plötzlich am Himmel einen riesigen Feuerball, der in Deine Richtung fliegt. Du bückst Dich.`n`n Doch der Feuerball hatte es nicht auf Dich abgesehen und schlägt einige Meter von Dir entfernt in den Wald ein. Sofort steigt aus der Einschlagsstelle Rauch auf.`n `nWillst Du dich zu der Einschlagstelle begeben oder lieber wieder in den Wald gehen?');

     addnav('untersuchen','forest.php?op=untersuchen');
     addnav('zurück in den Wald','forest.php?op=wald');
     $session['user']['specialinc']='inferno.php';
     }
     if ($battle) {
    include("battle.php");
    $session['user']['specialinc']='inferno.php';
        if ($victory){
            $badguy=array();
            $session['user']['badguy']='';
            output('`n`7Du hast das Wesen besiegt und es verfällt in lauter kleine Aschestücke!');
            debuglog("defeated the Infernal");
            //Navigation
            output('Du durchsuchst noch schnell den Krater');

            if (mt_rand(1,2)==1) {
                output(', doch findest Du nichts als Asche.');
            }
            else{
                 $text = "`&Die Reste eines `^Infernals`&.";
                 $gold = mt_rand(50,500);
                output(" und findest ein Stück Kohle. Du beschließt es mitzunehmen.");
                $sql="INSERT INTO items(name,class,owner,value1,gold,gems,description) VALUES ('Kohle','Beute',".$session['user']['acctid'].",0,$gold,0,'$text')";
                db_query($sql);
            }
            $exp = round($session['user']['experience']0.08);
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session['user']['experience']+=$exp;

            $session['user']['specialinc']='';
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']='';
            debuglog("was killed by a Infernal.");
            output('`n`7Das Wesen trifft Dich tödlich!`n`nDu verlierst 6% Deiner Erfahrung und all Dein Gold.`0');
            output('`nDu kannst morgen wieder kämpfen!`0');
            addnav('Tägliche News','news.php');
            addnews('`QEin Wind wehte '.$session['user']['name'].'`Qs Asche zum Dorfplatz`Q!');
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['gold']=0;
            $session['user']['experience']=round($session['user']['experience'].94,0);
            $session['user']['specialinc']='';
        } else {
            fightnav(true,false);
        }
        }
?>