
<?php

#########################################
#            Class Tournament           #
#                Hadriel                #
#                                       #
# Needs the tournament addon by Hadriel #
#                                       #
#########################################

require_once "common.php";
page_header();
$melee_tour=getsetting('melee_rounds',5);
$bow_tour=getsetting('bow_rounds',5);
$emagic_tour=getsetting('emagic_rounds',5);
$gmagic_tour=getsetting('gmagic_rounds',5);
$cook_tour=getsetting('cook_rounds',5);
$swim_tour=getsetting('swim_rounds',5);

$script=basename(__FILE__);
define ("is_tournament",getsetting("tournament_c",9)-2);
adddbfield('accounts','melee_result','int(11)',0);
adddbfield('accounts','melee_skill','int(11)',10);
adddbfield('accounts','bow_result','int(11)',0);
adddbfield('accounts','bow_skill','int(11)',10);
adddbfield('accounts','emagic_result','int(11)',0);
adddbfield('accounts','emagic_skill','int(11)',10);
adddbfield('accounts','gmagic_result','int(11)',0);
adddbfield('accounts','gmagic_skill','int(11)',10);
adddbfield('accounts','cook_result','int(11)',0);
adddbfield('accounts','cook_skill','int(11)',10);
adddbfield('accounts','swim_result','int(11)',0);
adddbfield('accounts','swim_skill','int(11)',10);


switch($_GET[tk]){
  case "":
  output((is_tournament>0?(is_tournament==1?"Das nächste Turnier findet morgen statt!":"Das nächste Turnier findet in ".is_tournament." Tagen statt`n`n"):"Das Turnier läuft noch heute!`n`n"));
  output("`nDeine Nahkampffertigkeit: ".$session[user][melee_skill]."/100`n".grafbar(100,$session[user][melee_skill]),true);
  output("`nDeine Treffsicherheit: ".$session[user][bow_skill]."/100`n".grafbar(100,$session[user][bow_skill]),true);
  output("`nDeine Fertigkeit in Kampfmagie: ".$session[user][emagic_skill]."/100`n".grafbar(100,$session[user][emagic_skill]),true);
  output("`nDeine Fertigkeit in Naturmagie: ".$session[user][gmagic_skill]."/100`n".grafbar(100,$session[user][gmagic_skill]),true);
  output("`nDeine Kochkünste: ".$session[user][cook_skill]."/100`n".grafbar(100,$session[user][cook_skill]),true);
  output("`nDeine Schwimmkünste: ".$session[user][swim_skill]."/100`n".grafbar(100,$session[user][swim_skill]),true);
  addnav("TURNIER");
    addnav("zum Bayouwald","forest.php");    
addnav("zum Jackson Square","village.php");
  addnav("Aktualisieren",$script);
  if(is_tournament>0){
  addnav("Trainer");
  addnav("Nahkampftrainer",$script."?tk=talk1");
  addnav("Bogenschütztrainer",$script."?tk=talk2");
  addnav("Kampfmagiertrainer",$script."?tk=talk3");
  addnav("Naturmagiertrainer",$script."?tk=talk4");
  addnav("Kochtrainer",$script."?tk=talk5");
  addnav("Schwimmtrainer",$script."?tk=talk6");
  }else{
  addnav("Turniere");
  addnav("Nahkampfturnier",$script."?tk=1");
  addnav("Bogenschützenturnier",$script."?tk=2");
  addnav("Kampfmagieturnier",$script."?tk=3");
  addnav("Naturmagieturnier",$script."?tk=4");
  addnav("Kochturnier",$script."?tk=5");
  addnav("Schwimmturnier",$script."?tk=6");
  addnav("Rangliste",$script."?tk=results");
  }
  break;
  
  case 1:
  if($session[user][melee_result]==0){
      output("Willst du am Turnier teilnehmen?");
    addnav("Ja",$script."?tk=11");
    addnav("Nein",$script);
    }else{
    output("Du hast bereits am Turnier teilgenommen!");
    addnav("Zurück",$script);
    }
  break;
  
  case 11:
  output("Und los gehts!`n`n`n");
          $_SESSION[pt]=0;
      for($i=0;$i<$melee_tour;$i++){
        $chance="";
        $ra=$i+1;
    $chance=e_rand(100-$session[user][melee_skill],200-$session[user][melee_skill]);    
    if($chance>125){
        $hit="Daneben!";
    $pt_gain=1;
    $_SESSION[pt]++;
        }else if($chance>100 && $chance<=125){
        $hit="schlechter Schlag!";
    $_SESSION[pt]+=2;
    $pt_gain=2;
    }else if($chance>=75 && $chance<=100){
        $hit="mittelmässiger Schlag!";
    $_SESSION[pt]+=3;
    $pt_gain=3;
    }else if($chance>=33 && $chance<75){
        $hit="guter Schlag!";
    $_SESSION[pt]+=5;
    $pt_gain=5;
    }else if($chance>=0 && $chance<=33){
    $hit="perfekter Schlag!";
    $_SESSION[pt]+=10;
    $pt_gain=10;
    }    
        output($ra.". Schlag: ".$hit." (".$pt_gain." ".($pt_gain==1?"Punkt":"Punkte").")  `n`n");
    }
    output("`n`nErreichte Punktzahl: ".$_SESSION[pt]);
    $session[user][melee_result]=$_SESSION[pt];
    addnav("Zurück",$script);
    $_SESSION[pt]=0;
  break;
  
  case "talk1":
  $skill = $session[user][melee_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    addnav("Einfache Lektion nehmen ($goldcost1 Gold)",$script."?tk=skill1");
    addnav("Harte Lektion nehmen ($goldcost2 Gold)",$script."?tk=skill1_2");
    addnav("Doppelte Lektion nehmen ($goldcost3 Gold)",$script."?tk=skill1_3");
    addnav("Zurück",$script);
  break;
  
case "skill1":
addnav("Zurück",$script);
    $skill = $session[user][melee_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost1){
      if($session[user][melee_skill]<=99){
      output("Der Trainer Trainiert dich eine Lektion lang.`n");
      $skilladd=1;
      $session[user][gold]-=$goldcost1;
      $session[user][melee_skill]+=$skilladd;
      if($session[user][melee_skill]>=100)$session[user][melee_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][melee_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
    
    
  break;
  
  case "skill1_2":
  addnav("Zurück",$script);
    $skill = $session[user][melee_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost2){
      if($session[user][melee_skill]<=99){
      output("Der Trainer Trainiert dich eine intime Lektion lang.`n");
      $skilladd=e_rand(1,2);
      $session[user][gold]-=$goldcost2;
      $session[user][melee_skill]+=$skilladd;
      if($session[user][melee_skill]>=100)$session[user][melee_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][melee_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  case "skill1_3":
  addnav("Zurück",$script);
    $skill = $session[user][melee_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
        if($session[user][gold]>=$goldcost3){
      if($session[user][melee_skill]<=99){
      output("Der Trainer Trainiert dich eine doppelte Lektion lang.`n");
      $skilladd=e_rand(1,3);
      $session[user][gold]-=$goldcost3;
      $session[user][melee_skill]+=$skilladd;
      if($session[user][melee_skill]>=100)$session[user][melee_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][melee_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  
  #########################################################################
  
    case 2:
  if($session[user][bow_result]==0){
      output("Willst du am Turnier teilnehmen?");
    addnav("Ja",$script."?tk=22");
    addnav("Nein",$script);
    }else{
    output("Du hast bereits am Turnier teilgenommen!");
    addnav("Zurück",$script);
    }
  break;
  case 22:
  output("Und los gehts!`n`n`n");
      $_SESSION[pt2]=0;
      for($i=0;$i<$bow_tour;$i++){
        $chance="";
        $ra=$i+1;
    $chance=e_rand(100-$session[user][bow_skill],200-$session[user][bow_skill]);    
        if($chance>125){
        $hit="Daneben!";
    $pt_gain=1;
    $_SESSION[pt2]++;
        }else if($chance>100 && $chance<=125){
        $hit="schlechter Treffer!";
    $_SESSION[pt2]+=2;
    $pt_gain=2;
    }else if($chance>=75 && $chance<=100){
        $hit="mässiger Treffer!";
    $_SESSION[pt2]+=3;
    $pt_gain=3;
    }else if($chance>=33 && $chance<75){
        $hit="guter Treffer!";
    $_SESSION[pt2]+=5;
    $pt_gain=5;
    }else if($chance>=0 && $chance<=33){
    $hit="perfekter Treffer!";
    $_SESSION[pt2]+=10;
    $pt_gain=10;
    }    
        output($ra.". Treffer: ".$hit." (".$pt_gain." ".($pt_gain==1?"Punkt":"Punkte").")  `n`n");
    }
    output("`n`nErreichte Punktzahl: ".$_SESSION[pt2]);
    $session[user][bow_result]=$_SESSION[pt2];
    addnav("Zurück",$script);
    $_SESSION[pt2]=0;
  break;
  
  case "talk2":
  $skill = $session[user][bow_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    addnav("Einfache Lektion nehmen ($goldcost1 Gold)",$script."?tk=skill2");
    addnav("Harte Lektion nehmen ($goldcost2 Gold)",$script."?tk=skill2_2");
    addnav("Doppelte Lektion nehmen ($goldcost3 Gold)",$script."?tk=skill2_3");
    addnav("Zurück",$script);
  break;
  
  case "skill2":
  addnav("Zurück",$script);
    $skill = $session[user][bow_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost1){
      if($session[user][bow_skill]<=99){
      output("Der Trainer Trainiert dich eine Lektion lang.`n");
      $skilladd=1;
      $session[user][gold]-=$goldcost1;
      $session[user][bow_skill]+=$skilladd;
      if($session[user][bow_skill]>=100)$session[user][bow_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][bow_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
    
    
  break;
  
  case "skill2_2":
  addnav("Zurück",$script);
    $skill = $session[user][bow_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost2){
      if($session[user][bow_skill]<=99){
      output("Der Trainer Trainiert dich eine intime Lektion lang.`n");
      $skilladd=e_rand(1,2);
      $session[user][gold]-=$goldcost2;
      $session[user][bow_skill]+=$skilladd;
      if($session[user][bow_skill]>=100)$session[user][bow_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][bow_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  case "skill2_3":
  addnav("Zurück",$script);
    $skill = $session[user][bow_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
        if($session[user][gold]>=$goldcost3){
      if($session[user][bow_skill]<=99){
      output("Der Trainer Trainiert dich eine doppelte Lektion lang.`n");
      $skilladd=e_rand(1,3);
      $session[user][gold]-=$goldcost3;
      $session[user][bow_skill]+=$skilladd;
      if($session[user][bow_skill]>=100)$session[user][bow_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][bow_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  
  #########################################################################
  
  case 3:
  if($session[user][emagic_result]==0){
      output("Willst du am Turnier teilnehmen?");
    addnav("Ja",$script."?tk=33");
    addnav("Nein",$script);
    }else{
    output("Du hast bereits am Turnier teilgenommen!");
    addnav("Zurück",$script);
    }
  break;
  
  case 33:
  output("Und los gehts!`n`n`n");
  $_SESSION[pt3]=0;
      for($i=0;$i<$emagic_tour;$i++){
        $chance="";
        $ra=$i+1;
    $chance=e_rand(100-$session[user][emagic_skill],200-$session[user][emagic_skill]);    
        if($chance>125){
        $hit="Misslungen!";
    $pt_gain=1;
    $_SESSION[pt3]++;
        }else if($chance>100 && $chance<=125){
        $hit="schlechter Zauber!";
    $_SESSION[pt3]+=2;
    $pt_gain=2;
    }else if($chance>=75 && $chance<=100){
        $hit="mässiger Zauber!";
    $_SESSION[pt3]+=3;
    $pt_gain=3;
    }else if($chance>=33 && $chance<75){
        $hit="guter Zauber!";
    $_SESSION[pt3]+=5;
    $pt_gain=5;
    }else if($chance>=0 && $chance<=33){
    $hit="perfekter Zauber!";
    $_SESSION[pt3]+=10;
    $pt_gain=10;
    }    
        output($ra.". Zauber: ".$hit." (".$pt_gain." ".($pt_gain==1?"Punkt":"Punkte").")  `n`n");
    }
    output("`n`nErreichte Punktzahl: ".$_SESSION[pt3]);
    $session[user][emagic_result]=$_SESSION[pt3];
    addnav("Zurück",$script);
    $_SESSION[pt3]=0;
  break;
  
  case "talk3":
  $skill = $session[user][emagic_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    addnav("Einfache Lektion nehmen ($goldcost1 Gold)",$script."?tk=skill3");
    addnav("Harte Lektion nehmen ($goldcost2 Gold)",$script."?tk=skill3_2");
    addnav("Doppelte Lektion nehmen ($goldcost3 Gold)",$script."?tk=skill3_3");
    addnav("Zurück",$script);
  break;
  
  case "skill3":
  addnav("Zurück",$script);
    $skill = $session[user][emagic_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost1){
      if($session[user][emagic_skill]<=99){
      output("Der Trainer Trainiert dich eine Lektion lang.`n");
      $skilladd=1;
      $session[user][gold]-=$goldcost1;
      $session[user][emagic_skill]+=$skilladd;
      if($session[user][emagic_skill]>=100)$session[user][emagic_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][emagic_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
    
    
  break;
  
  case "skill3_2":
  addnav("Zurück",$script);
    $skill = $session[user][emagic_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost2){
      if($session[user][emagic_skill]<=99){
      output("Der Trainer Trainiert dich eine intime Lektion lang.`n");
      $skilladd=e_rand(1,2);
      $session[user][gold]-=$goldcost2;
      $session[user][emagic_skill]+=$skilladd;
      if($session[user][emagic_skill]>=100)$session[user][emagic_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][emagic_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  case "skill3_3":
  addnav("Zurück",$script);
    $skill = $session[user][emagic_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
        if($session[user][gold]>=$goldcost3){
      if($session[user][emagic_skill]<=99){
      output("Der Trainer Trainiert dich eine doppelte Lektion lang.`n");
      $skilladd=e_rand(1,3);
      $session[user][gold]-=$goldcost3;
      $session[user][emagic_skill]+=$skilladd;
      if($session[user][emagic_skill]>=100)$session[user][emagic_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][emagic_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  
  #########################################################################
  
  case 4:
  if($session[user][gmagic_result]==0){
      output("Willst du am Turnier teilnehmen?");
    addnav("Ja",$script."?tk=44");
    addnav("Nein",$script);
    }else{
    output("Du hast bereits am Turnier teilgenommen!");
    addnav("Zurück",$script);
    }
  break;
  
  case 44:
  output("Und los gehts!`n`n`n");
  $_SESSION[pt4]=0;
      for($i=0;$i<$gmagic_tour;$i++){
        $chance="";
        $ra=$i+1;
    $chance=e_rand(100-$session[user][gmagic_skill],200-$session[user][gmagic_skill]);    
            if($chance>125){
        $hit="Misslungen!";
    $pt_gain=1;
    $_SESSION[pt4]++;
        }else if($chance>100 && $chance<=125){
        $hit="schlechter Zauber!";
    $_SESSION[pt4]+=2;
    $pt_gain=2;
    }else if($chance>=75 && $chance<=100){
        $hit="mässiger Zauber!";
    $_SESSION[pt4]+=3;
    $pt_gain=3;
    }else if($chance>=33 && $chance<75){
        $hit="guter Zauber!";
    $_SESSION[pt4]+=5;
    $pt_gain=5;
    }else if($chance>=0 && $chance<=33){
    $hit="perfekter Zauber!";
    $_SESSION[pt4]+=10;
    $pt_gain=10;
    }    
        output($ra.". Zauber: ".$hit." (".$pt_gain." ".($pt_gain==1?"Punkt":"Punkte").") `n`n");
    }
    output("`n`nErreichte Punktzahl: ".$_SESSION[pt4]);
    $session[user][gmagic_result]=$_SESSION[pt4];
    addnav("Zurück",$script);
    $_SESSION[pt4]=0;
  break;
  
  case "talk4":
  $skill = $session[user][gmagic_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    addnav("Einfache Lektion nehmen ($goldcost1 Gold)",$script."?tk=skill4");
    addnav("Harte Lektion nehmen ($goldcost2 Gold)",$script."?tk=skill4_2");
    addnav("Doppelte Lektion nehmen ($goldcost3 Gold)",$script."?tk=skill4_3");
    addnav("Zurück",$script);
  break;
  
  case "skill4":
  addnav("Zurück",$script);
    $skill = $session[user][gmagic_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost1){
      if($session[user][gmagic_skill]<=99){
      output("Der Trainer Trainiert dich eine Lektion lang.`n");
      $skilladd=1;
      $session[user][gold]-=$goldcost1;
      $session[user][gmagic_skill]+=$skilladd;
      if($session[user][gmagic_skill]>=100)$session[user][gmagic_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][gmagic_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
    
    
  break;
  
  case "skill4_2":
  addnav("Zurück",$script);
    $skill = $session[user][gmagic_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost2){
      if($session[user][gmagic_skill]<=99){
      output("Der Trainer Trainiert dich eine intime Lektion lang.`n");
      $skilladd=e_rand(1,2);
      $session[user][gold]-=$goldcost2;
      $session[user][gmagic_skill]+=$skilladd;
      if($session[user][gmagic_skill]>=100)$session[user][gmagic_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][gmagic_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  case "skill4_3":
  addnav("Zurück",$script);
    $skill = $session[user][gmagic_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
        if($session[user][gold]>=$goldcost3){
      if($session[user][gmagic_skill]<=99){
      output("Der Trainer Trainiert dich eine doppelte Lektion lang.`n");
      $skilladd=e_rand(1,3);
      $session[user][gold]-=$goldcost3;
      $session[user][gmagic_skill]+=$skilladd;
      if($session[user][gmagic_skill]>=100)$session[user][gmagic_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][gmagic_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  
  
  #########################################################################
  
  case 5:
  if($session[user][cook_result]==0){
      output("Willst du am Turnier teilnehmen?");
    addnav("Ja",$script."?tk=55");
    addnav("Nein",$script);
    }else{
    output("Du hast bereits am Turnier teilgenommen!");
    addnav("Zurück",$script);
    }
  break;
  
  case 55:
  output("Und los gehts!`n`n`n");
  $_SESSION[pt5]=0;
      for($i=0;$i<$cook_tour;$i++){
        $chance="";
        $ra=$i+1;
    $chance=e_rand(100-$session[user][cook_skill],200-$session[user][cook_skill]);    
            if($chance>125){
        $hit="Eine schwarze Brühe, die nach nichts schmeckt!";
    $pt_gain=1;
    $_SESSION[pt5]++;
        }else if($chance>100 && $chance<=125){
        $hit="Ein Schwarzer Fleischklumpen mit rosanem Gemüse!";
    $_SESSION[pt5]+=2;
    $pt_gain=2;
    }else if($chance>=75 && $chance<=100){
        $hit="Ein leicht angebranntes Gericht!";
    $_SESSION[pt5]+=3;
    $pt_gain=3;
    }else if($chance>=33 && $chance<75){
        $hit="Ein gewöhnliches, sauber aussehendes Gericht!";
    $_SESSION[pt5]+=5;
    $pt_gain=5;
    }else if($chance>=0 && $chance<=33){
    $hit="Ein perfektest, wundervoll Aussehendes Gericht!";
    $_SESSION[pt5]+=10;
    $pt_gain=10;
    }    
        output($ra.". Gericht: ".$hit." (".$pt_gain." ".($pt_gain==1?"Punkt":"Punkte").")  `n`n");
    }
    output("`n`nErreichte Punktzahl: ".$_SESSION[pt5]);
    $session[user][cook_result]=$_SESSION[pt5];
    addnav("Zurück",$script);
    $_SESSION[pt5]=0;
  break;
  
  case "talk5":
  addnav("Zurück",$script);
  $skill = $session[user][cook_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    addnav("Einfache Lektion nehmen ($goldcost1 Gold)",$script."?tk=skill5");
    addnav("Harte Lektion nehmen ($goldcost2 Gold)",$script."?tk=skill5_2");
    addnav("Doppelte Lektion nehmen ($goldcost3 Gold)",$script."?tk=skill5_3");
    addnav("Zurück",$script);
  break;
  
case "skill5":
    $skill = $session[user][cook_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost1){
      if($session[user][cook_skill]<=99){
      output("Der Trainer Trainiert dich eine Lektion lang.`n");
      $skilladd=1;
      $session[user][gold]-=$goldcost1;
      $session[user][cook_skill]+=$skilladd;
      if($session[user][cook_skill]>=100)$session[user][cook_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][cook_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
    
    
  break;
  
  case "skill5_2":
  addnav("Zurück",$script);
    $skill = $session[user][cook_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost2){
      if($session[user][cook_skill]<=99){
      output("Der Trainer Trainiert dich eine intime Lektion lang.`n");
      $skilladd=e_rand(1,2);
      $session[user][gold]-=$goldcost2;
      $session[user][cook_skill]+=$skilladd;
      if($session[user][cook_skill]>=100)$session[user][cook_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][cook_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  case "skill5_3":
  addnav("Zurück",$script);
    $skill = $session[user][cook_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
        if($session[user][gold]>=$goldcost3){
      if($session[user][cook_skill]<=99){
      output("Der Trainer Trainiert dich eine doppelte Lektion lang.`n");
      $skilladd=e_rand(1,3);
      $session[user][gold]-=$goldcost3;
      $session[user][cook_skill]+=$skilladd;
      if($session[user][cook_skill]>=100)$session[user][cook_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][cook_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
    
  
  
  #########################################################################
  
  case 6:
  if($session[user][swim_result]==0){
      output("Willst du am Turnier teilnehmen?");
    addnav("Ja",$script."?tk=66");
    addnav("Nein",$script);
    }else{
    output("Du hast bereits am Turnier teilgenommen!");
    addnav("Zurück",$script);
    }
  break;
  
  case 66:
  output("Und los gehts!`n`n`n");
  $_SESSION[pt6]=0;
      for($i=0;$i<$swim_tour;$i++){
        $chance="";
        $ra=$i+1;
    $chance=e_rand(100-$session[user][swim_skill],200-$session[user][swim_skill]);    
            if($chance>125){
        $hit="Abgesoffen!";
    $pt_gain=1;
    $_SESSION[pt6]++;
        }else if($chance>100 && $chance<=125){
        $hit="Schlechte Zeit!";
    $_SESSION[pt6]+=2;
    $pt_gain=2;
    }else if($chance>=75 && $chance<=100){
        $hit="Langsame Zeit!";
    $_SESSION[pt6]+=3;
    $pt_gain=3;
    }else if($chance>=33 && $chance<75){
        $hit="Gute Zeit!";
    $_SESSION[pt6]+=5;
    $pt_gain=5;
    }else if($chance>=0 && $chance<=33){
    $hit="Perfekte Zeit!";
    $_SESSION[pt6]+=10;
    $pt_gain=10;
    }    
        output($ra.". Runde: ".$hit." (".$pt_gain." ".($pt_gain==1?"Punkt":"Punkte").")  `n`n");
    }
    output("`n`nErreichte Punktzahl: ".$_SESSION[pt6]);
    $session[user][swim_result]=$_SESSION[pt6];
    addnav("Zurück",$script);
    $_SESSION[pt6]=0;
  break;
  
  case "talk6":
  addnav("Zurück",$script);
  $skill = $session[user][swim_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    addnav("Einfache Lektion nehmen ($goldcost1 Gold)",$script."?tk=skill6");
    addnav("Harte Lektion nehmen ($goldcost2 Gold)",$script."?tk=skill6_2");
    addnav("Doppelte Lektion nehmen ($goldcost3 Gold)",$script."?tk=skill6_3");
    addnav("Zurück",$script);
  break;
  
case "skill6":
    $skill = $session[user][swim_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost1){
      if($session[user][swim_skill]<=99){
      output("Der Trainer Trainiert dich eine Lektion lang.`n");
      $skilladd=1;
      $session[user][gold]-=$goldcost1;
      $session[user][swim_skill]+=$skilladd;
      if($session[user][swim_skill]>=100)$session[user][swim_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][swim_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
    
    
  break;
  
  case "skill6_2":
  addnav("Zurück",$script);
    $skill = $session[user][swim_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
    if($session[user][gold]>=$goldcost2){
      if($session[user][swim_skill]<=99){
      output("Der Trainer Trainiert dich eine intime Lektion lang.`n");
      $skilladd=e_rand(1,2);
      $session[user][gold]-=$goldcost2;
      $session[user][swim_skill]+=$skilladd;
      if($session[user][swim_skill]>=100)$session[user][swim_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][swim_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  case "skill6_3":
  addnav("Zurück",$script);
    $skill = $session[user][swim_skill]*500-5000;
  $goldcost1 = 1000 + $skill;
  $goldcost2 = 2500 + $skill;
  $goldcost3 = 5000 + $skill;
        if($session[user][gold]>=$goldcost3){
      if($session[user][swim_skill]<=99){
      output("Der Trainer Trainiert dich eine doppelte Lektion lang.`n");
      $skilladd=e_rand(1,3);
      $session[user][gold]-=$goldcost3;
      $session[user][swim_skill]+=$skilladd;
      if($session[user][swim_skill]>=100)$session[user][swim_skill]=100;
      output("Du bist nun auf Stufe ".$session[user][swim_skill]);
      }else{
      output("Der Trainer schickt dich mit dem Grund, dass du schon zu erfahren bist, weg.");
      }
      
    }else{
    output("Der Trainer schickt dich mit dem Grund, dass du zu wenig Gold hast, weg.");
    }
  break;
  
  case "results":
  addnav("Nahkampf",$script."?tk=results&ty=melee_result");
  addnav("Bogenschiessen",$script."?tk=results&ty=bow_result");
  addnav("Kampfmagie",$script."?tk=results&ty=emagic_result");
  addnav("Naturmagie",$script."?tk=results&ty=gmagic_result");
  addnav("Kochen",$script."?tk=results&ty=cook_result");
  addnav("Schwimmen",$script."?tk=results&ty=swim_result");
  if(!isset($_GET[ty])) $_GET[ty]="melee_result";
  $sql="SELECT name, ".$_GET[ty]." FROM accounts WHERE ".$_GET[ty]." >0 ORDER BY ".$_GET[ty]." desc, dragonkills desc LIMIT 50";
   $result=db_query($sql);
  $ac=array('melee_result'=>'Nahkampf','bow_result'=>'Bogenschiessen','emagic_result'=>'Kampfmagie','gmagic_result'=>'Naturmagie','cook_result'=>'Kochen','swim_result'=>'Schwimmen');
   output("`7An der Nordseite des Platzes ist eine Steintafel auf der die aktuellen Turnierergebnisse der einzelnen Abenteuerer festhält. Du siehst hier die 50 Führenden.`n ");
   output("<center><font size='+2'>".$ac[$_GET[ty]]."</font><br><table><tr><td style='background-color:#AFDB02;color:#000000;font-weight:bold;'><center>Platz</center></td><td style='background-color:#AFDB02;color:#000000;font-weight:bold;'><center>Name</center></td><td style='background-color:#AFDB02;color:#000000;font-weight:bold;'>Resultat</td></tr>",true);
   $i=1;
   while ($row=db_fetch_assoc($result)){
       if($session[user][name]==$row[name]) output("<tr bgcolor='#005500'><td>$i.</td><td>".$row[name]."</td><td>".$row[$_GET[ty]]."</td></tr>",true);
     else output("<tr><td>$i.</td><td>".$row[name]."</td><td>".$row[$_GET[ty]]."</td></tr>",true);
   $i++;
   }
   output("</table></center>",true);
   addnav("Zurück",$script);
   
  break;
  }


page_footer();
?>

