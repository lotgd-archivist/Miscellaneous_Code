
<?php

//Zweistein.php
//Wiederspiegelungen mit realen Personen sind rein zufällig und nicht beabsichtigt ;)
//Hadriel - Code Begin

define("_type_","gif"); //Image Ending

switch($_GET[op]){
  case "":
  case "search":
  $session[user][specialinc]="zweistein.php";
        output("Auf deinen Streifzügen im Wald entdeckst du eine kleine, abgelegene Hütte.`n`nWas wirst du tun?");
        addnav("Anklopfen","forest.php?op=klopf");
        addnav("Weggehen","forest.php?op=goaway");
    break;
  case "goaway":
        $session[user][specialinc]="";
        redirect("forest.php");
    break;
  case "klopf":
        $session[user][specialinc]="zweistein.php";
    switch(e_rand(1,4)){
      case 1:
      case 3:
      case 4:
        output("Vorsichtig klopfst du an die Tür... doch niemand reagiert.");
        addnav("Einbrechen","forest.php?op=brech");
        addnav("Nochmals","forest.php?op=klopf");
        addnav("Zurück in den Wald","forest.php?op=goaway");
      break;
      case 2:
        output("Du klopfst an. Nach ein paar Sekunden öffnet ein alter Mann mit grauen Haaren dir die Tür.");
        output("\"`qHallo, mein ".($session[user][sex]==0?"Jungchen":"Mädel").", was hast du hier draussen im Wald verloren?`0\", fragt er dich.");
        addnav("Weitersprechen","forest.php?op=talk");
        addnav("Wegrennen","forest.php?op=goaway");
      break;
      }
    break;
    case "talk":
      $session[user][specialinc]="zweistein.php";
        output("\"`qSoso, du suchst also Monster, was? Nun, du könntest ja eine kleine Stärkung gebrauchen... ich habe hier mein Labor und einige Zutaten, die ich noch nicht getestet habe. `nWie wäre es, wenn du ein Versuchskaninchen spielen würdest?`0\"");
        addnav("Ja","forest.php?op=labory");
        addnav("Wegrennen","forest.php?op=goaway");
      break;
    case "brech":
      $session[user][specialinc]="";
      output("Du versuchst, die morsche Tür der Hütte einzutreten.`n");
        output("Im Inneren findest du");
    switch(e_rand(1,5)){
        case 1:
        case 4:
        case 5:
          output(" nichts interessantes.");
        break;
        case 2:
          $session[user][gold]+=e_rand(1,100);
          output(" ein paar Goldstücke.");
        case 3:
          $session[user][gems]++;
          output(" einen Edelstein.");
        break;
        }
      addnav("Zurück in den Wald","forest.php");
      break;
    case "labory":
        $session[user][specialinc]="zweistein.php";
        output("Du betrittst das kleine Labor in der Hütte. In jeder Ecke stehen komische Geräte, unter dem Dach kannst du ein Teleskop entdecken.");
        output("`n`n`nFolgende Zutaten stehen zur Wahl: ('Klicke' auf die Bilder)`n`n");
        output("<a href='forest.php?op=mix&col=red'><img src='./images/zweistein/t_red."._type_."' border='0'></a> ",true);
        output("<a href='forest.php?op=mix&col=blue'><img src='./images/zweistein/t_blue."._type_."' border='0'></a> ",true);
        output("<a href='forest.php?op=mix&col=green'><img src='./images/zweistein/t_green."._type_."' border='0'></a> ",true);
        output("<a href='forest.php?op=mix&col=yellow'><img src='./images/zweistein/t_yellow."._type_."' border='0'></a> ",true);
        output("<a href='forest.php?op=mix&col=dgreen'><img src='./images/zweistein/t_dgreen."._type_."' border='0'></a> ",true);
        output("<a href='forest.php?op=mix&col=brown'><img src='./images/zweistein/t_brown."._type_."' border='0'></a> ",true);

        allownav("forest.php?op=mix&col=red");
        allownav("forest.php?op=mix&col=blue");
        allownav("forest.php?op=mix&col=green");
        allownav("forest.php?op=mix&col=yellow");
        allownav("forest.php?op=mix&col=dgreen");
        allownav("forest.php?op=mix&col=brown");

        navhead("Zutaten");        
        addnav("1?Rot","forest.php?op=mix&col=red");
        addnav("2?Blau","forest.php?op=mix&col=blue");
        addnav("3?Grün","forest.php?op=mix&col=green");
        addnav("4?Gelb","forest.php?op=mix&col=yellow");
        addnav("5?Dunkelgrün","forest.php?op=mix&col=dgreen");
        addnav("6?Braun","forest.php?op=mix&col=brown");

      break;
    case "mix":
        $session[user][specialinc]="zweistein.php";
        output("wähle die 2. Zutat:`n`n");
        navhead("Zutaten");        
        if($_GET[col]!="red"){
            output("<a href='forest.php?op=mix2&col=red&col2=".$_GET[col]."'><img src='./images/zweistein/t_red."._type_."' border='0'></a> ",true);
            allownav("forest.php?op=mix2&col=red&col2=".$_GET[col]."");
            addnav("1?Rot","forest.php?op=mix2&col=red&col2=".$_GET[col]."");
        }
        if($_GET[col]!="blue"){
            output("<a href='forest.php?op=mix2&col=blue&col2=".$_GET[col]."'><img src='./images/zweistein/t_blue."._type_."' border='0'></a> ",true);
            allownav("forest.php?op=mix2&col=blue&col2=".$_GET[col]."");
            addnav("2?Blau","forest.php?op=mix2&col=blue&col2=".$_GET[col]."");
        }
        if($_GET[col]!="green"){
            output("<a href='forest.php?op=mix2&col=green&col2=".$_GET[col]."'><img src='./images/zweistein/t_green."._type_."' border='0'></a> ",true);
            allownav("forest.php?op=mix2&col=green&col2=".$_GET[col]."");
            addnav("3?Grün","forest.php?op=mix2&col=green&col2=".$_GET[col]."");
        }
        if($_GET[col]!="yellow"){
            output("<a href='forest.php?op=mix2&col=yellow&col2=".$_GET[col]."'><img src='./images/zweistein/t_yellow."._type_."' border='0'></a> ",true);
            allownav("forest.php?op=mix2&col=yellow&col2=".$_GET[col]."");
            addnav("4?Gelb","forest.php?op=mix2&col=yellow&col2=".$_GET[col]."");
        }
        if($_GET[col]!="dgreen"){
            output("<a href='forest.php?op=mix2&col=dgreen&col2=".$_GET[col]."'><img src='./images/zweistein/t_dgreen."._type_."' border='0'></a> ",true);
            allownav("forest.php?op=mix2&col=dgreen&col2=".$_GET[col]."");
            addnav("5?Dunkelgrün","forest.php?op=mix2&col=dgreen&col2=".$_GET[col]."");
        }
        if($_GET[col]!="brown"){
            output("<a href='forest.php?op=mix2&col=brown&col2=".$_GET[col]."'><img src='./images/zweistein/t_brown."._type_."' border='0'></a> ",true);
            allownav("forest.php?op=mix2&col=brown&col2=".$_GET[col]."");
            addnav("6?Braun","forest.php?op=mix2&col=brown&col2=".$_GET[col]."");
        }

      break;
    case "mix2":
      $session[user][specialinc]="";
        ////////////////////////////////////////////////////////////////////////////////////
        if($_GET[col]=="red" && $_GET[col2]=="blue"){ $explode=false;
        $energy=5; }
          if($_GET[col]=="red" && $_GET[col2]=="green"){ $explode=true;
          $energy=0;}
            if($_GET[col]=="red" && $_GET[col2]=="yellow"){ $explode=true;
            $energy=0;}
              if($_GET[col]=="red" && $_GET[col2]=="dgreen"){ $explode=false;
              $energy=5;}
                if($_GET[col]=="red" && $_GET[col2]=="brown"){ $explode=false;
                $energy=2;}
        ////////////////////////////////////////////////////////////////////////////////////
        if($_GET[col]=="blue" && $_GET[col2]=="red"){ $explode=true;
        $energy=0;}
          if($_GET[col]=="blue" && $_GET[col2]=="green"){ $explode=true;
          $energy=0;}
            if($_GET[col]=="blue" && $_GET[col2]=="yellow"){ $explode=true;
            $energy=0;}
              if($_GET[col]=="blue" && $_GET[col2]=="dgreen"){ $explode=false;
              $energy=3;}
                if($_GET[col]=="blue" && $_GET[col2]=="brown"){ $explode=false;
                $energy=2;}
        ////////////////////////////////////////////////////////////////////////////////////
        if($_GET[col]=="green" && $_GET[col2]=="red"){ $explode=false;
        $energy=5;}
          if($_GET[col]=="green" && $_GET[col2]=="blue"){ $explode=true;
          $energy=0;}
            if($_GET[col]=="green" && $_GET[col2]=="yellow"){ $explode=false;
            $energy=2;}
              if($_GET[col]=="green" && $_GET[col2]=="dgreen"){ $explode=true;
              $energy=0;}
                if($_GET[col]=="green" && $_GET[col2]=="brown"){ $explode=false;
                $energy=2;}
        ////////////////////////////////////////////////////////////////////////////////////
        if($_GET[col]=="yellow" && $_GET[col2]=="red"){ $explode=false;
        $energy=3;}
          if($_GET[col]=="yellow" && $_GET[col2]=="green"){ $explode=false;
          $energy=5;}
            if($_GET[col]=="yellow" && $_GET[col2]=="blue"){ $explode=false;
            $energy=3;}
              if($_GET[col]=="yellow" && $_GET[col2]=="dgreen"){ $explode=false;
              $energy=5;}
                if($_GET[col]=="yellow" && $_GET[col2]=="brown"){ $explode=false;
                $energy=2;}
        ////////////////////////////////////////////////////////////////////////////////////
        if($_GET[col]=="dgreen" && $_GET[col2]=="red"){ $explode=true;
        $energy=0;}
          if($_GET[col]=="dgreen" && $_GET[col2]=="green"){ $explode=true;
          $energy=0;}
            if($_GET[col]=="dgreen" && $_GET[col2]=="blue"){ $explode=true;
            $energy=0;}
              if($_GET[col]=="dgreen" && $_GET[col2]=="yellow"){ $explode=true;
              $energy=0;}
                if($_GET[col]=="dgreen" && $_GET[col2]=="brown"){ $explode=true;
                $energy=0;}
        ////////////////////////////////////////////////////////////////////////////////////
        if($_GET[col]=="brown" && $_GET[col2]=="red"){
        $explode=true; 
        $energy=0; 
        }
          if($_GET[col]=="brown" && $_GET[col2]=="green"){
          $explode=true;
          $energy=0; 
          }
            if($_GET[col]=="brown" && $_GET[col2]=="blue"){
            $explode=false; 
            $energy=4; 
            }
              if($_GET[col]=="brown" && $_GET[col2]=="yellow"){
              $explode=true;
              $energy=0;
              }
                if($_GET[col]=="brown" && $_GET[col2]=="dgreen"){
                $explode=true; 
                $energy=0;
                }
        ////////////////////////////////////////////////////////////////////////////////////
                if(!$explode){
                    output("Du trinkst den erstellten Trank und...");
                  switch($energy){
                    case 5:
                      output(" wirst vollständig geheilt!");
                      $session[user][hitpoints]=$session[user][maxhitpoints];
                    break;
                    case 2:
                      output(" bekommst einen Angriffspunkt!");
                      $session[user][attack]++;
                   break;
                   case 3:
                      output(" bekommst einen Verteidigungspunkt!");
                      $session[user][defence]++;
                    break;
                    case 4:
                      output(" bekommst 3 Lebenspunkte!");
                      $session[user][maxhitpoints]+=3;
                      break;
                    }
                  addnav("Der Wald","forest.php");
                  }else{
                    output("<img src='./images/zweistein/t_explode."._type_."'>",true);
                    output("`nDer Trank explodiert vor deiner Nase!");
                    if(e_rand(1,2)==1){
                      output("Schwer verletzt liegst du am Boden. Du hast die meisten deiner Lebenspunkte verloren.");
                      $session[user][hitpoints]=1;
                      addnav("Der Wald","forest.php");
                     }else{
                      output("`nDurch die Explosion stirbst du!`nDu verlierst 3% deiner Erfahrung.");
                      $session[user][alive]=false;
                      $session[user][hitpoints]=0;
                      $session[user][experience]*.97;
                      $session[user][gold]=0;
                       addnav("Tägliche News","news.php");
                       }
                   }
      break;
    }

//Hadriel - Code End
?>

