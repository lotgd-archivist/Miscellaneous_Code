
<?php /* ******************* by Fly email: easykamikaze@lycos.de ********************* fight from mill.php seen at: www.hadrielnet.ch ******************* */ if (!isset($session)) exit(); if ($_GET[op]=="")      {      $seite = e_rand(1,2) ;      output("`3 Plötzlich merkst Du einen Ruck an Deinem ".($seite==1?"rechten":"linken")." Bein ");      output("und einen Augenblick später hängst Du kopfüber von einem Baum herab!");      addnav("versuchen sich zu befreien","berge.php?op=try");      $session[user][specialinc]="falle.php";      addnews($session[user][name]. " hängt kopfüber von einem einsamen Baum in den Bergen herab.");      } else if ($_GET[op]=="try")      {      output("`3Du versuchst mit Deiner Waffe das Seil durchzuschlagen.`n`n");      switch (e_rand(1,5))             {             case 1:             case 2:             case 3:                  $gewinn = e_rand(1,3);                  output("`3Nach einiger Zeit schaffst Du es, Dich zu befreien ");                  output("und landest unsanft auf dem Boden!`n`n");                  output("`4Du hast $gewinn ".($gewinn==1?"Waldkampf":"Waldkämpfe")." verloren.`n`n");                  $session['user']['turns'] -= $gewinn;         if ($session[user][turns] < 0){ $session[user][turns]=0;}         output("Bei dem Sturz hast du Dich leider verletzt.`n");         $session['user']['hitpoints'] -= $session['user']['level'];         if ($session[user][hitpoints] < 1){                                output("`n`4Du hast den Sturz nicht überlebt. Du kannst morgen wieder spielen!");                                $session[user][alive]=false;                                $session[user][hitpoints]=0;                                $session[user][experience]*=0.95;                                $session[user][gold] = 0;                                addnav("Tägliche News","news.php");                                addnews($session[user][name]. "`4 wurde tot unter einem einsamen Baum in den Bergen gefunden.");                                }         else                                {                                addnav("Die Lichtung verlassen","berge.php");                                $session[user][specialinc]="";                                if (e_rand(1,5)<4){                                output("Durch den Sturz hast Du Dir Deinen Arm verstaucht.");                                $session[bufflist]['arm'] = array("name"=>"`4verstauchter Arm",                                         "rounds"=>20,                                         "wearoff"=>"Deinem Arm geht es wieder besser!",                                         "defmod"=>0.86,                                         "atkmod"=>0.82,                                         "roundmsg"=>"Dein verstauchter Arm schmerzt fürchterlich!",                                         "activate"=>"defense");                                }                                }                     break;             case 4:             case 5:                  output("`3Nach einiger Zeit kommt plötzlich ein Mann aus dem Bergwald, genau auf Dich zu.`n");                   if (e_rand(1,5)<4){                   output ("`n`&\"Na, was ist uns denn da Feines ins Netz gegangen?\"`n`n");                   output("`n`n`3 Der Mann ist ein Strauchdieb und zieht seine Waffe!`n");                   $badguy = array(                                 "creaturename"=>"`\$Strauchdieb`0",                                 "creaturelevel"=>$session[user][level]+1,                                 "creatureweapon"=>"Dolch",                                 "creatureattack"=>$session['user']['attack'],                                 "creaturedefense"=>$session['user']['defence']+2,                                 "creaturehealth"=>round($session['user']['maxhitpoints']*1.25,0),                                 "diddamage"=>0);                         $session['user']['badguy']=createstring($badguy);                         $session['user']['specialinc']="falle.php";                         $battle=true;                         $session[user][specialinc]="";                         output("Da Du noch am Baum hängst, kannst Du nicht so gut kämpfen wie sonst.`n`n");                                $session[bufflist]['haengen'] = array("name"=>"`4vom Baum hängend",                                         "rounds"=>e_rand(1,2),                                         "wearoff"=>"Du stehst wieder auf Deinen Füßen!",                                         "defmod"=>0.76,                                         "atkmod"=>0.76,                                         "roundmsg"=>"Du hängst immer noch in der Luft!",                                         "activate"=>"defense");                   }                   else                   {                        $gewinn = e_rand(1,3);                   $session['user']['turns'] -= $gewinn;                       output("`4Du hast $gewinn ".($gewinn==1?"Waldkampf":"Waldkämpfe")." verloren.");                  output("`n`n`dDu hast Glück, es ist ein Wanderer, der gerade des Weges kam. Er ist so freundlich und befreit Dich.");                   addnav("Die Lichtung verlassen","berge.php");                   $session[user][specialinc]="";                   }                   break;                   }             } else if ($_GET[op]=="fight"){   // Kampf     $battle=true;     $session[user][specialinc]=""; } if ($battle) {     include("battle.php");     $session['user']['specialinc']="falle.php";         if ($victory){             $badguy=array();             $session['user']['badguy']="";             output("`n`7Du hast den Strauchdieb besiegt!`n`n");             debuglog("defeated the thief");             //Navigation             addnav("Zurück in die Berge","berge.php");             if (rand(1,2)==1) {                 $gem_gain = rand(2,3);                 $gold_gain = rand($session[user][level]*10,$session[user][level]*20);                 output("`7Als Du den Dieb durchsuchst, findest Du `^$gem_gain Edelsteine                 `7und `^$gold_gain Goldstücke`7.`n`n");             }             $exp = round($session[user][experience]*0.1);             output("Durch diesen Kampf steigt Deine Erfahrung um `^$exp Punkte`7.`n`n");             $session[user][experience]+=$exp;             $session[user][gold]+=$gold_gain;             $session[user][gems]+=$gem_gain;             $session['user']['specialinc']="";         } elseif ($defeat){             $badguy=array();             $session[user][badguy]="";             debuglog("was killed by a thief.");             output("`n`7Der Dieb hat Dich besiegt!`n`nDu verlierst 6% Deiner Erfahrung und all Dein Gold.`0");             output("`nDu kannst morgen             wieder kämpfen!`0");             addnav("Tägliche News","news.php");             addnews("`Q".$session[user][name]." `Qwurde in den Bergen von einem Strauchdieb getötet!");             $session[user][alive]=false;             $session[user][hitpoints]=0;             $session[user][gold]=0;             $session[user][experience]=round($session[user][experience]*.94,0);             $session[user][specialinc]="";         } else {             fightnav(true,false);         } } ?>
