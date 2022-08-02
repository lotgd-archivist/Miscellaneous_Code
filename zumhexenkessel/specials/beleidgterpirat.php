<?
/*
Der Wortkampf by Tiger313
Idee: Wolf
Umsetzung: Tiger313
Demo: http://www.das-ging-fix.de/dorte/MLC-Board2-1-3/logd/index.php

Anleitung:
----------
SQL:
####
ALTER TABLE `accounts` ADD `bpirat` INT( 2 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `accounts` ADD `buser` INT( 2 ) DEFAULT '0' NOT NULL ;

Diese Datei in den special ordner rein
FERTIG
*/
if (!isset($session)) exit();
if ($_GET[op]=="") 
     { 

     output("`3 Auf Deiner Suche kommt dir auf einmal ein `4Pirat `3entgegen.`n"); 
     output("Er schein ziemlich verärgert zu sein. Du versuchst ihn nicht direkt an zu schauen.`n"); 
     output("Als du aus versehen ihn bei vorbeilaufen ganz leicht berühst dreht der pirat durch.`n"); 
     output("Er vordert dich zu Kampf heraus und du ziehst sofort dein Schwert aber der `4Pirat `3zeigt dir den vogel und meint.`n"); 
     output("\"`9Was willst du mit dem Zahnstocher. Nur Schwächlinge wie du brauchen Waffen. Die stärkste Waffe sind `bWÖRTER`b also kämpfe mit dennen oder garnicht.`3\"`n`n"); 
     output("`3 Was machst du?.`n");
     
     addnav("Kämpfen","forest.php?op=kampf"); 
     addnav("zurück in den Wald","forest.php?op=wald"); 
     $session['user']['specialinc']="beleidgterpirat.php"; 

     } 
else if ($_GET[op]=="wald") 
     { 
     output("`3Du ignorierst den Piraten und gehst zurück in den Wald"); 
     addnav("weiter","forest.php");
     $session['user']['bpirat'] =0;
     $session['user']['buser'] =0;
     $session['user']['turns'] --; 
     $session['user']['specialinc']=""; 
     addnews("`7 ".$session[user][name]."`7 wahr zu FEIGE gegen den `9Piraten `7, in einem Kampf ohne Waffen, anzuträtten`7");
     } 
else if ($_GET[op]=="kampf") 
     { 
     output("`3 Du packst dein Schwert wieder weg.`n"); 
     output("`3Der Pirat stellt sich dir gegenüber auf,reuspelt sich noch mal und fängt an!`n`n"); 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     switch (e_rand(1,3)) 
             { 
             case 1:
             output("`9Mein Schwert wird dich aufspießen wie ein Schaschlik!`n`n");
                addnav("","forest.php?op=aoedobgtduasop");
        addnav("","forest.php?op=aoedodgtbuesop");
        addnav("","forest.php?op=aoedodgtduasop"); 
                addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoebobgtbuesop");
        output("<a href=forest.php?op=aoadebgtduasop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 2
        output("<a href=forest.php?op=aoedobgtduasop>`2Doch, doch, du hast sie nur nie gelernt.</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoedodgtduasop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); //gut 4
                output("<a href=forest.php?op=aoedodgtbuesop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 8
                output("<a href=forest.php?op=aoebobgtbuesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 6
        break;
       case 2: 
                output("`9Deine Fuchtelei hat nichts mit Fechtkunst zu tun!`n`n");
                addnav("","forest.php?op=aoadodgtduasop");
        addnav("","forest.php?op=aoebobgtbuasop");
        addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoebobgtbuesop");
        output("<a href=forest.php?op=aoadodgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoebobgtbuasop>`2Doch, doch, du hast sie nur nie gelernt.</a>`n", true); //gut 3
                output("<a href=forest.php?op=aoadobgtbuesop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 7
        output("<a href=forest.php?op=aoadebgtduasop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); //kombi 2
                output("<a href=forest.php?op=aoebobgtbuesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 6
        break;
       case 3: 
                output("`9Niemand wird mich verlieren sehen, auch du nicht.!`n`n");
                addnav("","forest.php?op=aoabobgtduesop");
        addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop");
        output("<a href=forest.php?op=aoabobgtduesop>`2Du kannst so schnell davonlaufen?</a>`n", true); //gut 6
                output("<a href=forest.php?op=aoadobgtduesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Hattest du das nicht vor kurzem getan.</a>`n", true); //kombi 2
        output("<a href=forest.php?op=aoedobgtduasop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 4
        break;
         }
         addnav("zurück","forest.php");
    } 
//*****************Anfang Kombinationen zu Verwirung**************//
else if ($_GET[op]=="aoadobgtduesop")   //kombi 1
     {
     $session['user']['bpirat']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat'] <5){ 
     addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     }
     } 
else if ($_GET[op]=="aoadebgtduasop")   //kombi 2
     {
     $session['user']['bpirat']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat'] <5){ 
     addnav("weiter","forest.php?op=kampf2");
   } else { 
   addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoedobgtduasop")   //kombi 3
     {
     $session['user']['bpirat']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoadodgtduasop")   //kombi 4
     {
     $session['user']['bpirat']++;
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     }
     } 
else if ($_GET[op]=="aoedebgtduasop")   //kombi 5
     {
     $session['user']['bpirat']++;
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoebobgtbuesop")   //kombi 6
     {
     $session['user']['bpirat']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoadobgtbuesop")   //kombi 7
     {
     $session['user']['bpirat']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoedodgtbuesop")   //kombi 8
     {
     $session['user']['bpirat']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     }
     } 
else if ($_GET[op]=="aoebobgtduesop")   //kombi 9
     {
     $session['user']['bpirat']++;
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoabodgtduesop")   //kombi 10
     {
     $session['user']['bpirat']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9HaHaHA Punkt für mich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['bpirat']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     }
     } 
else if ($_GET[op]=="aoedobgtduesop")   //gut kombi 1
     {
     $session['user']['buser']++;
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     }
     } 
else if ($_GET[op]=="aoebobgtduasop")   //gut kombi 2
     {
     $session['user']['buser']++;
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoebobgtbuasop")   //gut kombi 3
     {
     $session['user']['buser']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     }
     } 
else if ($_GET[op]=="aoedodgtduasop")   //gut kombi 4
     {
     $session['user']['buser']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoadebgtduesop")   //gut kombi 5
     {
     $session['user']['buser']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoabobgtduesop")   //gut kombi 6
     {
     $session['user']['buser']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoadodgtbuesop")   //gut kombi 7
     {
     $session['user']['buser']++; 
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoadobgtduasop")   //gut kombi 8
     {
     $session['user']['buser']++;
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoebodgtduasop")   //gut kombi 9
     {
     $session['user']['buser']++;
     $session['user']['specialinc']="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     } 
     }
else if ($_GET[op]=="aoadebgtduesop")   //gut kombi 10
     {
     $session['user']['buser']++; 
     $session[user][specialinc]="beleidgterpirat.php"; 
     output("`9Oh mist das wahr gemein Punkt für dich!`n`n");
     output("`qNeuer stand:`n ".$session['user']['bpirat']." Punkte Pirat `n ".$session['user']['buser']." Punkte Du!`n`n");
     if ($session['user']['buser']<5){ addnav("weiter","forest.php?op=kampf2");
   } else { addnav("weiter","forest.php?op=ergebnis");
     }
     }
//*****************Ende Kombinationen zu Verwirung**************//

else if ($_GET[op]=="kampf2") 
     {
     $session['user']['specialinc']="beleidgterpirat.php"; 
          switch (e_rand(1,66)) { 
        case 1: 
                output("`9Mein Schwert wird dich aufspießen wie ein Schaschlik!`n`n");
                addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtduesop>`2Doch, doch, du hast sie nur nie gelernt.</a>`n", true); //Kombi 1
                output("<a href=forest.php?op=aoadobgtbuesop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedebgtduasop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 5
        output("<a href=forest.php?op=aoedobgtduesop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); //gut 1
                output("<a href=forest.php?op=aoabodgtduesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 10
        break;
    case 2: 
                output("`9Deine Fuchtelei hat nichts mit Fechtkunst zu tun!`n`n");
                addnav("","forest.php?op=aoadebgtduasop"); //kombi 2
        addnav("","forest.php?op=aoadobgtduesop"); //kombi 1
                addnav("","forest.php?op=aoabodgtduesop"); //kombi 10
                addnav("","forest.php?op=aoebobgtduesop"); //kombi 9
        addnav("","forest.php?op=aoadobgtduasop"); //gut8
        output("<a href=forest.php?op=aoadebgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); 
                output("<a href=forest.php?op=aoadobgtduesop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true);
        output("<a href=forest.php?op=aoabodgtduesop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); 
                output("<a href=forest.php?op=aoebobgtduesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true);
        output("<a href=forest.php?op=aoadobgtduasop>`2Doch, doch, du hast sie nur nie gelernt.</a>`n", true); 
                break;
    case 3: 
                output("`9Niemand wird mich verlieren sehen, auch du nicht.`n`n");
                addnav("","forest.php?op=aoebobgtduasop");
        addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop");
        output("<a href=forest.php?op=aoebobgtduasop>`2Du kannst so schnell davonlaufen?</a>`n", true); //gut 2
                output("<a href=forest.php?op=aoadobgtduesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Hattest du das nicht vor kurzem getan.</a>`n", true); //kombi 2
        output("<a href=forest.php?op=aoedobgtduasop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 4
        break;
    case 4: 
                output("`9Ich hatte mal einen Hund, der war klüger als du.`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoedodgtduasop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Also mal wieder in der Nase gebohrt, wie?</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoedodgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //gut 4
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wieso, die könntest du viel eher brauchen.</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2Dann wäre koffeinfreier Kaffee ein erster Schritt zur Läuterung!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); //kombi 8
        break;
    case 5: 
                output("`9Du hast die Manieren eines Bettlers.`n`n");
                addnav("","forest.php?op=aoedobgtduasop");
        addnav("","forest.php?op=aoadodgtduasop");
        addnav("","forest.php?op=aoebobgtbuasop"); 
                addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoebobgtbuesop");
        output("<a href=forest.php?op=aoedobgtduasop>`2Ich schaudere, ich schaudere.</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoebobgtbuasop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //gut 3
        output("<a href=forest.php?op=aoedebgtduasop>`2Vielleicht solltest du es endlich mal benutzen.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 6
        break;
    case 6: 
                output("`9Jeder hier kennt dich als unerfahrenen Dummkopf.`n`n");
                addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedodgtbuesop");
        addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtbuesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 8
                output("<a href=forest.php?op=aoebobgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 9
        output("<a href=forest.php?op=aoadebgtduesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //gut 5
                output("<a href=forest.php?op=aoabodgtduesop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 10
        break;
    case 7: 
                output("`9Du kämpfst wie ein dummer Bauer.`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop"); 
                addnav("","forest.php?op=aoabobgtduesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 2
                output("<a href=forest.php?op=aoedobgtduasop>`2Vielleicht solltest du es endlich mal benutzen.</a>`n", true); //kombi 3
        output("<a href=forest.php?op=aoadodgtduasop>`2Das ich nicht lache! Du und welche Armee?</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoabobgtduesop>`2Ich schaudere, ich schaudere.</a>`n", true); //gut 6
        break;
    case 8: 
                output("`9Meine Narbe im Gesicht stammt aus einem harten Kampf.`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Also mal wieder in der Nase gebohrt, wie?</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 9: 
                output("`9Menschen fallen mir zu Füßen, wenn ich komme.`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Auch bevor sie deinen Atem riechen.</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //kombi 3
        break;
    case 10: 
                output("`9Dein Schwert hat schon bessere Zeiten gesehen.`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoebodgtduasop"); 
                addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduasop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoadobgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoebodgtduasop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //gut 9
        output("<a href=forest.php?op=aoebobgtduesop>`2Zu schade, daß das hier niemand tangiert.</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadebgtduasop>`2Dafür hab' ich in der Hand nicht die Gicht!</a>`n", true); //kombi 2
        break;
    case 11: 
                output("`9Die bist kein Gegner für mein geschultes Gehirn.`n`n");
                addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtduesop>`2Wieso, die könntest du viel eher brauchen.</a>`n", true); //Kombi 1
                output("<a href=forest.php?op=aoadobgtbuesop>`2Ich schaudere, ich schaudere.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedebgtduasop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); //kombi 5
        output("<a href=forest.php?op=aoedobgtduesop>`2Vielleicht solltest du es endlich mal benutzen.</a>`n", true); //gut 1
                output("<a href=forest.php?op=aoabodgtduesop>`2Aargh! Behalt sie für dich, sonst bekomm' ich noch Pusteln!</a>`n", true); //kombi 10
        break;
    case 12: 
                output("`9Trägst du immer noch Windeln.`n`n");
                addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoadobgtduesop");
                addnav("","forest.php?op=aoabodgtduesop");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        output("<a href=forest.php?op=aoadebgtduasop>`2Das ist ein Lachen, du schwächlicher Wicht! Aargh!</a>`n", true);  //kombi 2
                output("<a href=forest.php?op=aoadobgtduesop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); //kombi 1
        output("<a href=forest.php?op=aoabodgtduesop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true);  //kombi 10
                output("<a href=forest.php?op=aoebobgtduesop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 9
        output("<a href=forest.php?op=aoadobgtduasop>`2Wieso, die könntest du viel eher brauchen.</a>`n", true);  //gut8
                break;
    case 13: 
                output("`9An deiner Stelle würde ich zur Landratte werden.`n`n");
                addnav("","forest.php?op=aoebobgtduasop");
        addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop");
        output("<a href=forest.php?op=aoebobgtduasop>`2Hattest du das nicht vor kurzem getan.</a>`n", true); //gut 2
                output("<a href=forest.php?op=aoadobgtduesop>`2Dich zu töten wäre eine legale Reinigung!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 2
        output("<a href=forest.php?op=aoedobgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Grrr ... Dann ist alles klar, du bist deshalb so dick!</a>`n", true); //kombi 4
        break;
    case 14: 
                output("`9Alles, was du sagst, ist dumm.`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoedodgtduasop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoedodgtduasop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //gut 4
                output("<a href=forest.php?op=aoebobgtbuesop>`2Zu schade, daß das hier niemand tangiert.</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2Wenn ich mit dir fertig bin, brauchst Du 'ne Krücke!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Ja, ja, ich weiß, ein dreiköpfiger Affe!</a>`n", true); //kombi 8
        break;
    case 15: 
                output("`9Hast du eine Idee, wie du hier lebend herauskommst?`n`n");
                addnav("","forest.php?op=aoedobgtduasop");
        addnav("","forest.php?op=aoadodgtduasop");
        addnav("","forest.php?op=aoebobgtbuasop"); 
                addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoebobgtbuesop");
        output("<a href=forest.php?op=aoedobgtduasop>`2Ich schaudere, ich schaudere.</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Also mal wieder in der Nase gebohrt, wie?</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoebobgtbuasop>`2Wieso, die könntest du viel eher brauchen.</a>`n", true); //gut 3
        output("<a href=forest.php?op=aoedebgtduasop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Das ich nicht lache! Du und welche Armee?</a>`n", true); //kombi 6
        break;
    case 16: 
                output("`9Mein Schwert wird dich in 1000 Stücke reißen.`n`n");
                addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedodgtbuesop");
        addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtbuesop>`2Dich zu töten wäre dann eine legale Reinigung!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Dann wäre koffeinfreier Kaffee ein erster Schritt zur Läuterung!</a>`n", true); //kombi 8
                output("<a href=forest.php?op=aoebobgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 9
        output("<a href=forest.php?op=aoadebgtduesop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); //gut 5
                output("<a href=forest.php?op=aoabodgtduesop>`2Zu schade, daß das hier niemand tangiert!</a>`n", true); //kombi 10
        break;
    case 17: 
                output("`9Niemand wird sehen, daß ich so schlecht kämpfe wie du.`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop"); 
                addnav("","forest.php?op=aoabobgtduesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Aargh! Das sind große Worte für 'nen Kerl ohne Grips!</a>`n", true); //kombi 2
                output("<a href=forest.php?op=aoedobgtduasop>`2Ungh! Sie sitzt mir gegenüber, also was fragst du mich?</a>`n", true); //kombi 3
        output("<a href=forest.php?op=aoadodgtduasop>`2Ungh ... wobei mir vor allem vor deinem Atem graust.</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoabobgtduesop>`2Du kannst so schnell davonlaufen?</a>`n", true); //gut 6
        break;
    case 18: 
                output("`9Nach dem letzten Kampf war meine Hand blutüberströmt.`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Also mal wieder in der Nase gebohrt, wie?</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 19: 
                output("`9Kluge Gegner laufen weg, bevor sie mich sehen.`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Auch bevor sie deinen Atem riechen.</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //kombi 3
        break;
    case 20: 
                output("`9Überall in der Gegend kennt man meine Klinge.`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoebodgtduasop"); 
                addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduasop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Ja, ja, ich weiß, ein dreiköpfiger Affe!</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoadobgtbuesop>`2Hoffentlich zerrst Du mich nicht ins Separée!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoebodgtduasop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //gut 9
        output("<a href=forest.php?op=aoebobgtduesop>`2Aargh! Unglaublich erbärmlich, das sag' ab jetzt ich.</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadebgtduasop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 2
        break;
    case 21: 
                output("`9Bis jetzt wurde jeder Gegner von mir eliminiert!`n`n");
                addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtduesop>`2Hoffentlich zerrst Du mich nicht ins Separée!</a>`n", true); //Kombi 1
                output("<a href=forest.php?op=aoadobgtbuesop>`2Dich zu töten wäre dann eine legale Reinigung!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedebgtduasop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 5
        output("<a href=forest.php?op=aoedobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //gut 1
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        break;
    case 22: 
                output("`9Du bist so häßlich wie ein Affe im Negligé!`n`n");
                addnav("","forest.php?op=aoadebgtduasop"); //kombi 2
        addnav("","forest.php?op=aoadobgtduesop"); //kombi 1
                addnav("","forest.php?op=aoabodgtduesop"); //kombi 10
                addnav("","forest.php?op=aoebobgtduesop"); //kombi 9
        addnav("","forest.php?op=aoadobgtduasop"); //gut8
        output("<a href=forest.php?op=aoadebgtduasop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); 
                output("<a href=forest.php?op=aoadobgtduesop>`2Das ich nicht lache! Du und welche Armee?</a>`n", true);
        output("<a href=forest.php?op=aoabodgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); 
                output("<a href=forest.php?op=aoebobgtduesop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true);
        output("<a href=forest.php?op=aoadobgtduasop>`2Hoffentlich zerrst Du mich nicht ins Separée!</a>`n", true); 
                break;
    case 23: 
                output("`9Dich zu töten wäre eine legale Beseitigung!`n`n");
                addnav("","forest.php?op=aoebobgtduasop");
        addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop");
        output("<a href=forest.php?op=aoebobgtduasop>`2Dich zu töten wäre dann eine legale Reinigung!</a>`n", true); //gut 2
                output("<a href=forest.php?op=aoadobgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 2
        output("<a href=forest.php?op=aoedobgtduasop>`2Zumindest hat man meine identifiziert!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 4
        break;
    case 24: 
                output("`9Warst Du schon immer so häßlich oder bis Du mutiert?`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoedodgtduasop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoedodgtduasop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //gut 4
                output("<a href=forest.php?op=aoebobgtbuesop>`2Zumindest hat man meine identifiziert!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 8
        break;
    case 25: 
                output("`9Ich spieß' Dich auf wie eine Sau am Buffet!.`n`n");
                addnav("","forest.php?op=aoedobgtduasop");
        addnav("","forest.php?op=aoadodgtduasop");
        addnav("","forest.php?op=aoebobgtbuasop"); 
                addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoebobgtbuesop");
        output("<a href=forest.php?op=aoedobgtduasop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoebobgtbuasop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //gut 3
        output("<a href=forest.php?op=aoedebgtduasop>`2Wenn ich mit dir fertig bin, brauchst Du 'ne Krücke!</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 6
        break;
    case 26: 
                output("`9Wirst Du laut Testament eingeächert oder einbalsamiert?`n`n");
                addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedodgtbuesop");
        addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtbuesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 8
                output("<a href=forest.php?op=aoebobgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //kombi 9
        output("<a href=forest.php?op=aoadebgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //gut 5
                output("<a href=forest.php?op=aoabodgtduesop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 10
        break;
    case 27: 
                output("`9Ein jeder hat vor meiner Schwertkunst kapituliert!`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop"); 
                addnav("","forest.php?op=aoabobgtduesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 2
                output("<a href=forest.php?op=aoedobgtduasop>`2Vielleicht solltest du es endlich mal benutzen.</a>`n", true); //kombi 3
        output("<a href=forest.php?op=aoadodgtduasop>`2Das ich nicht lache! Du und welche Armee?</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoabobgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //gut 6
        break;
    case 28: 
                output("`9Ich werde Dich richten - und es gibt kein Plädoyer!`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Das ich nicht lache! Du und welche Armee?</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 29: 
                output("`9Himmel bewahre! Für einen Hintern wäre Dein Gesicht eine Beleidigung!`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //kombi 3
        break;
    case 30: 
                output("`9Fühl' ich den Stahl in der Hand, bin ich in meinem Metier!`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoebodgtduasop"); 
                addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduasop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoadobgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoebodgtduasop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); //gut 9
        output("<a href=forest.php?op=aoebobgtduesop>`2Zu schade, daß das hier niemand tangiert.</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadebgtduasop>`2Dafür hab' ich in der Hand nicht die Gicht!</a>`n", true); //kombi 2
        break;
    case 31: 
                output("`9Haben sich Deine Eltern nach Deiner Geburt sterilisiert?`n`n");
                addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtduesop>`2Wieso, die könntest du viel eher brauchen.</a>`n", true); //Kombi 1
                output("<a href=forest.php?op=aoadobgtbuesop>`2Ich schaudere, ich schaudere.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedebgtduasop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); //kombi 5
        output("<a href=forest.php?op=aoedobgtduesop>`2Zumindest hat man meine identifiziert!</a>`n", true); //gut 1
                output("<a href=forest.php?op=aoabodgtduesop>`2Aargh! Behalt sie für dich, sonst bekomm' ich noch Pusteln!</a>`n", true); //kombi 10
        break;
    case 32: 
                output("`9En garde! Touché!`n`n");
                addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoadobgtduesop");
                addnav("","forest.php?op=aoabodgtduesop");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        output("<a href=forest.php?op=aoadebgtduasop>`2Das ist ein Lachen, du schwächlicher Wicht! Aargh!</a>`n", true);  //kombi 2
                output("<a href=forest.php?op=aoadobgtduesop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); //kombi 1
        output("<a href=forest.php?op=aoabodgtduesop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true);  //kombi 10
                output("<a href=forest.php?op=aoebobgtduesop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 9
        output("<a href=forest.php?op=aoadobgtduasop>`2Oh, das ist ein solch übles Klischee!</a>`n", true);  //gut8
                break;
    case 33: 
                output("`9Überall im Drachental wird mein Name respektiert!`n`n");
                addnav("","forest.php?op=aoebobgtduasop");
        addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop");
        output("<a href=forest.php?op=aoebobgtduasop>`2Zu schade, daß das hier niemand tangiert.</a>`n", true); //gut 2
                output("<a href=forest.php?op=aoadobgtduesop>`2Dich zu töten wäre eine legale Reinigung!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 2
        output("<a href=forest.php?op=aoedobgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Grrr ... Dann ist alles klar, du bist deshalb so dick!</a>`n", true); //kombi 4
        break;
    case 34: 
                output("`9Niemand kann mich stoppen: mich - den Schrecken der See!`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoedodgtduasop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoedodgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //gut 4
                output("<a href=forest.php?op=aoebobgtbuesop>`2Zu schade, daß das hier niemand tangiert.</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2Wenn ich mit dir fertig bin, brauchst Du 'ne Krücke!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Ja, ja, ich weiß, ein dreiköpfiger Affe!</a>`n", true); //kombi 8
        break;
    case 35: 
                output("`9Mein Minenspiel zeigt Dir meine Mißbilligung!`n`n");
                addnav("","forest.php?op=aoedobgtduasop");
        addnav("","forest.php?op=aoadodgtduasop");
        addnav("","forest.php?op=aoebobgtbuasop"); 
                addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoebobgtbuesop");
        output("<a href=forest.php?op=aoedobgtduasop>`2Ich schaudere, ich schaudere.</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Also mal wieder in der Nase gebohrt, wie?</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoebobgtbuasop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //gut 3
        output("<a href=forest.php?op=aoedebgtduasop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Das ich nicht lache! Du und welche Armee?</a>`n", true); //kombi 6
        break;
    case 36: 
                output("`9Ganze Inselreiche haben vor mir kapituliert!`n`n");
                addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedodgtbuesop");
        addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtbuesop>`2Dich zu töten wäre dann eine legale Reinigung!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Dann wäre koffeinfreier Kaffee ein erster Schritt zur Läuterung!</a>`n", true); //kombi 8
                output("<a href=forest.php?op=aoebobgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 9
        output("<a href=forest.php?op=aoadebgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //gut 5
                output("<a href=forest.php?op=aoabodgtduesop>`2Zu schade, daß das hier niemand tangiert!</a>`n", true); //kombi 10
        break;
    case 37: 
                output("`9Du hast soviel Sexappeal wie ein Croupier!`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop"); 
                addnav("","forest.php?op=aoabobgtduesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Aargh! Das sind große Worte für 'nen Kerl ohne Grips!</a>`n", true); //kombi 2
                output("<a href=forest.php?op=aoedobgtduasop>`2Ungh! Sie sitzt mir gegenüber, also was fragst du mich?</a>`n", true); //kombi 3
        output("<a href=forest.php?op=aoadodgtduasop>`2Ungh ... wobei mir vor allem vor deinem Atem graust.</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoabobgtduesop>`2Hoffentlich zerrst Du mich nicht ins Separée!</a>`n", true); //gut 6
        break;
    case 38: 
                output("`9Bist Du das? Es riecht hier so nach Jauche und Dung!`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Dich zu töten wäre eine legale Reinigung!</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 39: 
                output("`9Wurdest Du damals von einem Schwein adoptiert?`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //kombi 3
        break;
    case 40: 
                output("`9Auch wenn Du es nicht glaubst, aus Dir mach' ich Haschee!`n`n");
                addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtduesop>`2Doch, doch, du hast sie nur nie gelernt.</a>`n", true); //Kombi 1
                output("<a href=forest.php?op=aoadobgtbuesop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedebgtduasop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 5
        output("<a href=forest.php?op=aoedobgtduesop>`2Wenn ich mit Dir fertig bin, bist Du nur noch Filet!</a>`n", true); //gut 1
                output("<a href=forest.php?op=aoabodgtduesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 10
        break;
    case 41: 
                output("`9Ich laß' Dir die Wahl: erdolcht, erhängt oder guillotiniert!`n`n");
                addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtduesop>`2Doch, doch, du hast sie nur nie gelernt.</a>`n", true); //Kombi 1
                output("<a href=forest.php?op=aoadobgtbuesop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedebgtduasop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true); //kombi 5
        output("<a href=forest.php?op=aoedobgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //gut 1
                output("<a href=forest.php?op=aoabodgtduesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 10
        break;
    case 42: 
                output("`9Dein Geplänkel bringt mich richtig in Schwung!`n`n");
                addnav("","forest.php?op=aoadebgtduasop"); //kombi 2
        addnav("","forest.php?op=aoadobgtduesop"); //kombi 1
                addnav("","forest.php?op=aoabodgtduesop"); //kombi 10
                addnav("","forest.php?op=aoebobgtduesop"); //kombi 9
        addnav("","forest.php?op=aoadobgtduasop"); //gut8
        output("<a href=forest.php?op=aoadebgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); 
                output("<a href=forest.php?op=aoadobgtduesop>`2Ich wollte, daß du dich wie zuhause fühlst.</a>`n", true);
        output("<a href=forest.php?op=aoabodgtduesop>`2Dann mach damit nicht rum wie mit dem Staubwedel.</a>`n", true); 
                output("<a href=forest.php?op=aoebobgtduesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true);
        output("<a href=forest.php?op=aoadobgtduasop>`2Dann wäre koffeinfreier Kaffee ein erster Schritt zur Läuterung!</a>`n", true); 
                break;
    case 43: 
                output("`9Ich weiß nicht, welche meiner Eigenschaften Dir am meisten imponiert!`n`n");
                addnav("","forest.php?op=aoebobgtduasop");
        addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop");
        output("<a href=forest.php?op=aoebobgtduasop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //gut 2
                output("<a href=forest.php?op=aoadobgtduesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Hattest du das nicht vor kurzem getan.</a>`n", true); //kombi 2
        output("<a href=forest.php?op=aoedobgtduasop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 4
        break;
    case 44: 
                output("`9Jetzt werde ich Dich erstechen, da hilft kein Protegée!`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoedodgtduasop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Also mal wieder in der Nase gebohrt, wie?</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoedodgtduasop>`2Das ich nicht lache! Du und welche Armee?</a>`n", true); //gut 4
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wieso, die könntest du viel eher brauchen.</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2Dann wäre koffeinfreier Kaffee ein erster Schritt zur Läuterung!</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); //kombi 8
        break;
    case 45: 
                output("`9Ist ein Blick in den Spiegel nicht jeden Tag für Dich eine Erniedrigung?`n`n");
                addnav("","forest.php?op=aoedobgtduasop");
        addnav("","forest.php?op=aoadodgtduasop");
        addnav("","forest.php?op=aoebobgtbuasop"); 
                addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoebobgtbuesop");
        output("<a href=forest.php?op=aoedobgtduasop>`2Dann wäre koffeinfreier Kaffee ein erster Schritt zur Läuterung!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoebobgtbuasop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //gut 3
        output("<a href=forest.php?op=aoedebgtduasop>`2Vielleicht solltest du es endlich mal benutzen.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 6
        break;
    case 46: 
                output("`9Ich lauf' auf glühenden Kohlen und barfuß im Schnee!`n`n");
                addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedodgtbuesop");
        addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtbuesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 8
                output("<a href=forest.php?op=aoebobgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 9
        output("<a href=forest.php?op=aoadebgtduesop>`2Ich glaub', es gibt für Dich noch eine Stelle beim Varieté!</a>`n", true); //gut 5
                output("<a href=forest.php?op=aoabodgtduesop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 10
        break;
    case 47: 
                output("`9Du bist eine Schande für Deine Gattung, so dilettiert!`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop"); 
                addnav("","forest.php?op=aoabobgtduesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 2
                output("<a href=forest.php?op=aoedobgtduasop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 3
        output("<a href=forest.php?op=aoadodgtduasop>`2Deine Mutter trägt ein Toupet!</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoabobgtduesop>`2Zumindest hat man meine identifiziert!</a>`n", true); //gut 6
        break;
    case 48: 
                output("`9Deine Mutter trägt ein Toupet!`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 49: 
                output("`9Durch meine Fechtkunst bin ich zum Siegen prädestiniert!`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Zu schade, daß das hier niemand tangiert!</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //kombi 3
        break;
    case 50: 
                output("`9Es mit mir aufzunehmen gleicht einer Odysse!`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 51: 
                output("`9Mein Antlitz zeugt von edler Abstammung!`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Dein Geruch allein reicht aus, und ich wär' kollabiert!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Zu schade, daß das hier niemand tangiert!</a>`n", true); //kombi 3
        break;
    case 52: 
                output("`9Ungh ... Memmen wie dich vernasch' ich zum Frühstück.`n`n");
                addnav("","forest.php?op=aoadebgtduasop"); //kombi 2
        addnav("","forest.php?op=aoadobgtduesop"); //kombi 1
                addnav("","forest.php?op=aoabodgtduesop"); //kombi 10
                addnav("","forest.php?op=aoebobgtduesop"); //kombi 9
        addnav("","forest.php?op=aoadobgtduasop"); //gut8
        output("<a href=forest.php?op=aoadebgtduasop>`2Ungh ... wobei mir vor allem vor deinem Atem graust.</a>`n", true); 
                output("<a href=forest.php?op=aoadobgtduesop>`2Ungh! Ich werde mich wehren, bis die Griffel dir qualmen!</a>`n", true);
        output("<a href=forest.php?op=aoabodgtduesop>`2Wenn ich mit dir fertig bin, brauchst Du 'ne Krücke!</a>`n", true); 
                output("<a href=forest.php?op=aoebobgtduesop>`2Oooh. Zu schade, daß keine davon in diesen Armen ist.</a>`n", true);
        output("<a href=forest.php?op=aoadobgtduasop>`2Grrr ... Dann ist alles klar, du bist deshalb so dick!</a>`n", true); 
                break;
    case 53: 
                output("`9Ich habe Muskeln an Stellen, von denen du nichts ahnst.`n`n");
                addnav("","forest.php?op=aoebobgtduasop");
        addnav("","forest.php?op=aoadobgtduesop");
        addnav("","forest.php?op=aoadebgtduasop"); 
                addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop");
        output("<a href=forest.php?op=aoebobgtduasop>`2Oooh. Zu schade, daß keine davon in diesen Armen ist.</a>`n", true); //gut 2
                output("<a href=forest.php?op=aoadobgtduesop>`2Ungh! Ich werde mich wehren, bis die Griffel dir qualmen!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Ungh ... wobei mir vor allem vor deinem Atem graust.</a>`n", true); //kombi 2
        output("<a href=forest.php?op=aoedobgtduasop>`2Das ist ein Lachen, du schwächlicher Wicht! Aargh!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Ungh! Mit Ausnahme von deiner Frau, soviel ist klar!</a>`n", true); //kombi 4
        break;
    case 54: 
                output("`9Gib auf oder ich zerquetsch' dich wie eine lästige Mücke.`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoedodgtduasop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Ungh! Ich werde mich wehren, bis die Griffel dir qualmen!</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoedodgtduasop>`2Wenn ich mit dir fertig bin, brauchst Du 'ne Krücke!</a>`n", true); //gut 4
                output("<a href=forest.php?op=aoebobgtbuesop>`2Aargh! Behalt sie für dich, sonst bekomm' ich noch Pusteln!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2Ungh! Sie sitzt mir gegenüber, also was fragst du mich?</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Aargh! Unglaublich erbärmlich, das sag' ab jetzt ich.</a>`n", true); //kombi 8
        break;
    case 55: 
                output("`9Meine Großmutter hat mehr Kraft als du Wicht!`n`n");
                addnav("","forest.php?op=aoedobgtduasop");
        addnav("","forest.php?op=aoadodgtduasop");
        addnav("","forest.php?op=aoebobgtbuasop"); 
                addnav("","forest.php?op=aoedebgtduasop"); 
                addnav("","forest.php?op=aoebobgtbuesop");
        output("<a href=forest.php?op=aoedobgtduasop>`2Ja, ja, ich weiß, ein dreiköpfiger Affe!</a>`n", true); //kombi 3
                output("<a href=forest.php?op=aoadodgtduasop>`2Er muß dir das Fechten beigebracht haben.</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoebobgtbuasop>`2Dafür hab' ich in der Hand nicht die Gicht!</a>`n", true); //gut 3
        output("<a href=forest.php?op=aoedebgtduasop>`2Vielleicht solltest du es endlich mal benutzen.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!</a>`n", true); //kombi 6
        break;
    case 56: 
                output("`9Nach diesem Spiel trägst du den Arm in Gips.`n`n");
                addnav("","forest.php?op=aoadobgtbuesop");
        addnav("","forest.php?op=aoedodgtbuesop");
        addnav("","forest.php?op=aoebobgtduesop"); 
                addnav("","forest.php?op=aoadebgtduesop"); 
                addnav("","forest.php?op=aoabodgtduesop");
        output("<a href=forest.php?op=aoadobgtbuesop>`2Und du wirst deine rostige Klinge nie wieder sehen.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Ungh! Sie sitzt mir gegenüber, also was fragst du mich?</a>`n", true); //kombi 8
                output("<a href=forest.php?op=aoebobgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 9
        output("<a href=forest.php?op=aoadebgtduesop>`2Das sind große Worte für 'nen Kerl ohne Grips!</a>`n", true); //gut 5
                output("<a href=forest.php?op=aoabodgtduesop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 10
        break;
    case 57: 
                output("`9Aargh ... ich zerreiße deine Hand in eine Million Fetzen.`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop"); 
                addnav("","forest.php?op=aoabobgtduesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Aargh! Behalt sie für dich, sonst bekomm' ich noch Pusteln!</a>`n", true); //kombi 2
                output("<a href=forest.php?op=aoedobgtduasop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 3
        output("<a href=forest.php?op=aoadodgtduasop>`2Deine Mutter trägt ein Toupet!</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoabobgtduesop>`2Grrrgh! Ich wußte gar nicht, daß du so weit zählen kannst. Aargh!</a>`n", true); //gut 6
        break;
    case 58: 
                output("`9Aaagh ... hey, schau mal da drüben!`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Ja, ja, ich weiß, ein dreiköpfiger Affe!</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 59: 
                output("`9Aargh ... ich werde deine Knochen zu Brei zermalmen.`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Aargh! Behalt sie für dich, sonst bekomm' ich noch Pusteln!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Ungh! Ich werde mich wehren, bis die Griffel dir qualmen!</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Ungh ... du bist das häßlichste Wesen, daß ich jemals sah ... grr.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Ich könnte es tun, hättest Du nur ein Atemspray!</a>`n", true); //kombi 3
        break;
    case 60: 
                output("`9Ich kenne Läuse mit stärkeren Muskeln.`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Aargh! Behalt sie für dich, sonst bekomm' ich noch Pusteln!</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 61: 
                output("`9Alle Welt fürchtet die Kraft meiner Faust.`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Ungh ... wobei mir vor allem vor deinem Atem graust.</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Ungh! Und Babys wohl auch, na, der Witz ist gelungen.</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Ungh! Mit Ausnahme von deiner Frau, soviel ist klar!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Zu schade, daß das hier niemand tangiert!</a>`n", true); //kombi 3
        break;
    case 62: 
                output("`9Ungh ... gibt es auf dieser Welt eine größere Memme als dich?`n`n");
                addnav("","forest.php?op=aoedebgtduasop");
        addnav("","forest.php?op=aoadebgtduasop");
        addnav("","forest.php?op=aoedobgtduasop"); 
                addnav("","forest.php?op=aoadodgtduasop"); 
                addnav("","forest.php?op=aoabobgtduesop");
        output("<a href=forest.php?op=aoedebgtduasop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoadebgtduasop>`2Aargh! Behalt sie für dich, sonst bekomm' ich noch Pusteln!</a>`n", true); //kombi 2
                output("<a href=forest.php?op=aoedobgtduasop>`2Oh, das ist ein solch übles Klischee!</a>`n", true); //kombi 3
        output("<a href=forest.php?op=aoadodgtduasop>`2Deine Mutter trägt ein Toupet!</a>`n", true); //kombi 4
                output("<a href=forest.php?op=aoabobgtduesop>`2Ungh! Sie sitzt mir gegenüber, also was fragst du mich?</a>`n", true); //gut 6
        break;
    case 63: 
                output("`9Ungh ... du bist das häßlichste Wesen, daß ich jemals sah ... grr.`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Ungh! Mit Ausnahme von deiner Frau, soviel ist klar!</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Zu schade, daß dich überhaupt keiner kennt.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wenn ich mit DIR fertig bin, bist Du nur noch Filet!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Für Dein Gesicht bekommst Du 'ne Begnadigung.</a>`n", true); //kombi 8
        break;
    case 64: 
                output("`9Ungh ... viele Menschen sagen, meine Kraft ist unglaublich.`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Aargh! Behalt sie für dich, sonst bekomm' ich noch Pusteln!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Aargh! Unglaublich erbärmlich, das sag' ab jetzt ich.</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Sollt' ich in Deiner Nähe sterben, möcht' ich, daß man mich desinfiziert!</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Ungh ... du bist das häßlichste Wesen, daß ich jemals sah ... grr.</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Aargh! Auch bevor sie deinen Atem riechen.</a>`n", true); //kombi 3
        break;
    case 65: 
                output("`9Ungh ... Ich hab' mit diesen Armen schon Kraken bezwungen.`n`n");
                addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoadodgtbuesop");
        addnav("","forest.php?op=aoebobgtbuesop"); 
                addnav("","forest.php?op=aoadobgtbuesop"); 
                addnav("","forest.php?op=aoedodgtbuesop");
        output("<a href=forest.php?op=aoadodgtbuesop>`2Ungh! Und Babys wohl auch, na, der Witz ist gelungen.</a>`n", true); //gut 7
                output("<a href=forest.php?op=aoadodgtbuesop>`2Aargh! Unglaublich erbärmlich, das sag' ab jetzt ich.</a>`n", true); //kombi 5
                output("<a href=forest.php?op=aoebobgtbuesop>`2Wenn ich mit dir fertig bin, brauchst Du 'ne Krücke!</a>`n", true); //kombi 6
        output("<a href=forest.php?op=aoadobgtbuesop>`2In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.</a>`n", true); //kombi 7
                output("<a href=forest.php?op=aoedodgtbuesop>`2Grrr ... Dann ist alles klar, du bist deshalb so dick!</a>`n", true); //kombi 8
        break;
    case 66: 
                output("`9Ungh, ha ... sehe ich da Spuren von Angst in deinem Gesicht?`n`n");
                addnav("","forest.php?op=aoebobgtduesop");
        addnav("","forest.php?op=aoadobgtduasop");
        addnav("","forest.php?op=aoabodgtduesop"); 
                addnav("","forest.php?op=aoadobgtduesop"); 
                addnav("","forest.php?op=aoedobgtduasop");
        output("<a href=forest.php?op=aoebobgtduesop>`2Das war ja auch leicht, Dein Atem hat sie paralysiert!</a>`n", true); //kombi 9
                output("<a href=forest.php?op=aoadobgtduasop>`2Das ist ein Lachen, du schwächlicher Wicht!</a>`n", true); //gut 8
                output("<a href=forest.php?op=aoabodgtduesop>`2Ungh! Und Babys wohl auch, na, der Witz ist gelungen.</a>`n", true); //kombi 10
        output("<a href=forest.php?op=aoadobgtduesop>`2Ungh! Mit Ausnahme von deiner Frau, soviel ist klar!</a>`n", true); //kombi 1
                output("<a href=forest.php?op=aoedobgtduasop>`2Zu schade, daß das hier niemand tangiert!</a>`n", true); //kombi 3
        break;
    } 
} 
else if ($_GET[op]=="ergebnis"){ 
    output("`c`7Der `6Sieger `7steht fest.`n Es ist .........`n`c ");
         addnav("zurück","forest.php");
         $session['user']['turns'] --;
         $session['user']['specialinc']="";
             if ($session['user']['bpirat']<5){
          $expplu = round($session['user']['experience'] * 0.15);
              $session['user']['experience']+=$expplu;
              $session['user']['ehre']+=3;
             $session['user']['bpirat'] =0;
                $session['user']['buser'] =0;
          output("`c`b ".$session['user']['name']."`b `n`7Er gewinnt somit `2$expplu `7Erfahrung und `45 `7Ehrenpunkte.`c`n");
         $buff = array("name"=>"`6Ego Wall`0","rounds"=>60,"wearoff"=>"`5`bDer Ego Wall ist verbraucht!.`b`0","defmod"=>1.8,"roundmsg"=>"Dich kann nichts umhauen dein EGO ist enorm!!","activate"=>"offense"); 
                $session['bufflist']['pirat']=$buff;
        addnews("`7".$session[user][name]."`7 hat die Begegnung mit dem `9Piraten `7glenzent überstanden`7");
    } else {
          $expplu = round($session['user']['experience'] * 0.10);
               $session['user']['experience']-=$expplu;
               $session['user']['hitpoints'] =1;
               $session['user']['bpirat'] =0;
                  $session['user']['buser'] =0;
              output("`c`9`b`DER PIRAT`b `n`7Du verlierst deswegen `2$expplu `7Erfahrung und fast dein `4Leben`7`c.`n");
             $buff = array("name"=>"`6Demoralisation`0","rounds"=>60,"wearoff"=>"`5`bDie Motivation kehrt zurück!.`b`0","defmod"=>0.7,"roundmsg"=>"Du hast einfach kein Bock mehr und willst nur nach hause","activate"=>"offense"); 
                $session['bufflist']['pirat']=$buff;
    addnews("`9Der Pirat `7hat ".$session[user][name]."`7 fast zu Tode Beleidigt`7");
    }
}
?> 