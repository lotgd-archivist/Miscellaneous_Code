
<?php
/*

------------------------------
|  salamander.php            |
|    Idee by                 |
|    Erenya                  |
|   Scripted by              |
|    Erenya                  |
| erenattarath@hotmail.com   |
------------------------------
*/

 if (!isset($session)) exit();
 if ($_GET[op]==""){
 output("Du läufst durch den Wald, auf der Suche nach Gegnern die dich stärken, doch plötzlich siehst du einen großen Stein, auf dem eine Familie kleiner Salamander ist. Was wirst du tun?");
 addnav("Erschlage die Salamanderfamilie","forest.php?op=kill");
 addnav("Verschone sie","forest.php?op=alive");
   $session[user][specialinc]="salamander.php";
    }else if ($_GET[op]=="kill"){
    output("Du beschließt die Salamander zu töten, weil dir nichts anderes in die Quere kommt.");
    switch(e_rand(1,5)){
    case 1:
    output("Du erschlägst die Salamander und verlierst dafür alle Runden.");
    $session[user][turns]=0;
    break;
    case 2:
    output("Für diese Kranke Tat wirst du von Mutter Natur mit dem Tode.");
          $session[user][hitpoints]=0;
    $session[user][alive]=false;
    $session[user][specialinc]="";
    addnews("`0".$session[user][name]."`0 wurde für das erschlagen unschuldiger Wesen von Mutter Natur bestraft.");
      addnav("Tägliche News","news.php");
      break;
      case 3:
      case 4:
      case 5:
      output("Du willst die Salamander erschlagen, doch gerade als du das tun willst, steigt eine Feuerwand empor und du bemerkst, dass diese Salamanderfamilie Schutzgeister des Feuers sind.");
       $session['user']['specialinc']="salamander.php";
    addnav("Kämpfe","forest.php?op=kampf");
            break;
        }
        }   
      
          if($_GET['op']=="kampf"){
       output("Du entdecktst den Feuergeist Salamander");
         $badguy = array(
                "creaturename"=>"`%Feuergeist Salamander`0",
                "creaturelevel"=>$session[user][level],
                "creatureweapon"=>"`%Feuriger Odem",
                "creatureattack"=>$session['user']['attack']*0.8,
                "creaturedefense"=>$session['user']['defence']*0.8,
                "creaturehealth"=>round($session['user']['maxhitpoints']*0.8,0),
                "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialinc']="salamander.php";
        $battle=true;
        $session['user']['specialinc']="";
}
//Battle Settings
if ($_GET['op']=="run"){   // Flucht
    if (e_rand()%3 == 0){
    output("`c`bDu konntest dem Feuergeist Salamander entkommen.`b`c");
    $HTTP_GET_VARS[op]="";
    }else{
    output("`c`bDer Feuergeist Salamander war schneller als du. `b`c");
            $battle=true;
    }
}
if ($_GET['op']=="fight"){   // Kampf
    $battle=true;
    $session[user][specialinc]="";
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="salamander.php";
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            output("`nDu konntest den Feuergeist Salamander in einem feurigen Kampf besiegen.");
            //debuglog("defeated a oger");
            //Navigation
            $session['user']['specialinc']="salamander.php";
            addnav("Weiter","forest.php?op=weiter");
            if (rand(1,5)==1) {
                $gem_gain = rand(1,2);
                $gold_gain = rand($session[user][level]*5,$session[user][level]*10);
                output(" Du findest Du $gem_gain Edelsteine und $gold_gain Goldstücke.`n`n");
            }
            $exp = round($session[user][experience]*0.005);
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session[user][experience]+=$exp;
            $session[user][gold]+=$gold_gain;
            $session[user][gems]+=$gem_gain;
        } elseif ($defeat){
            $badguy=array();
            $session[user][badguy]="";
            //debuglog("was killed by a oger.");
            output("Von deinem Leichnam ist nur noch ein Häufschen Asche über, welches im Wind verweht.");
            addnav("Tägliche News","news.php");
            addnews("".$session['user']['name']." `(wollte die Familie eines Feuergeistes töten.`0");
            $session[user][alive]=false;
            $session[user][hitpoints]=0;
            $session[user][gems]=0;
            $session[user][gold]=0;
            $session[user][experience]=round($session[user][experience]*.95,0);
            $session[user][specialinc]="";
        } else {
            fightnav(true,true);
        }

}
if ($_GET['op']=="weiter"){
$session['user']['specialinc']="salamander.php";
output("Du hast den Feuergeist Salamander besiegt.");
}     
 
          
 else if ($_GET[op]=="alive"){
       output("Diese Salamander haben dir nichts getan, weswegen du beschließt die Familie zu verschonen.");
       switch(e_rand(1,3)){
       case 1:
  output("Mit Freuden bemerkt Mutter Natur, was für ein gutes Herz du hast und beschließt dir einen kleinen Schatz zu schenken.");
  $session[user][gold]+=1000;
  $session[user][rubi]+=2;
  break;
  case 2:
    output ("Dankbar quieken dich die kleinen Baby Salamander an. Dein herz blüht vor Freude auf, weswegen du mehr Kraft bekommst noch ein paar Monster zu suchen.");
  $session[user][turns]+=5;
  break;
  case 3:
  output("Der Salamander gibt sich dir als Feuergeist zu erkennen, und lobt deine Edle Tat, indem er mit seinem heißen Atem deine Haut faltenfrei macht.");
  $session[user][charm]+=10;
  addnews("`0".$session[user][name]." wurde von dem Feuergeist für seine Güte belohnt.");
  break;
        }
       }
      
?>


