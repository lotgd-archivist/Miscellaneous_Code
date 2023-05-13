
<?php
/***************************

* Author: Hadriel
* Filename (original): baggage.php
* Idea: Rudman
* Silver bag in the forest? Curious...

***************************/

if(!isset($session[user][specialinc])) exit();

  
  switch($_GET['op']){
  
  case "":
  case "search":
  $session[user][specialinc]="baggage.php";
  $ausgabe="Du bemerkst auf deinen Streifzügen durch den Wald einen kichernden Kobold.`n"
         ."Er kichert unüberhörbar laut, sodass du fast die Ohren zuhalten musst. "
         ."Ein paar Sekunden später zeigt er dir an, dass du ihm folgen sollst.`n`n"
         ."Was wirst du tun?";
  output($ausgabe);
  addnav("Mitgehen","forest.php?op=show");
  addnav("Zurück in den Wald","forest.php?op=flee");
  break;
  
  case "show":
  $session[user][specialinc]="baggage.php";
  $ausgabe="Du folgst dem Kobold über mehrere Minuten hinweg, als ihr plötzlich an einer Lichtung ankommt."
         ."`nInmitten der Lichtung steht ein gefällter Baumstumpf, auf dem ein kleines, silbernes Köfferchen steht."
         ."`nAls du dich nach dem Kobold umsiehst, kannst du ihn nicht mehr entdecken.";
  output($ausgabe);
  addnav("Köfferchen öffnen","forest.php?op=go");
  addnav("Abbiegen","forest.php?op=flee");
  break;
  
  case "go":
  $session[user][specialinc]="";
  $ausgabe="Du öffnest das Köfferchen langsam.";
      switch(e_rand(1,5)){
      case 1:
      case 2:
      case 3:
      $ausgabe.=" In dem Köfferchen liegen einige Tränke herum. Als du einen Trinkst, wird dir schwindelig, jedoch fühlst du dich stärker!";
      output($ausgabe);
      $session[bufflist]['bag'] = array("name"=>"`tSchutz des Köfferchens",
                                        "rounds"=>20,
                                        "wearoff"=>"Du fühlst Dich wieder normal.",
                                        "defmod"=>1.1,
                                        "roundmsg"=>"Das silberne Köfferchen schützt dich.",
                                        "activate"=>"defense");
      break;
      case 4:
      case 5: 
      addnews($session[user][name]."`q hat die Macht eines `tsilbernen Köfferchens`q gespürt...");
      $session[user][experience]*.97;
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                                $session[user][gold]=0;
      $ausgabe.=" Jedoch steigt ein merkwürdiger Rauch aus dem Köfferchen. Als du Atem holen willst, erstickst du... `n`4Du verlierst 4% Erfahrung und alles Gold, dass du bei dir hast.";

      addnav("Tägliche News","news.php");
      output($ausgabe);
      break;
      }
  break;
  
  case "flee":
  $session[user][specialinc]="";
  break;
  
  }
?>

