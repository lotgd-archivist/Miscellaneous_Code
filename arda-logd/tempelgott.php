<?php
/*Narjanas Tempel*/

require_once "common.php";
checkday();
page_header("Verlorener Tempel der Götter");
    
if ($_GET[op]=="") {
         
         addnav("In den Tempel","tempelgott.php?op=eintritt");
         addnav("Wieder leise gehen","tempelgott.php?op=raus");
         output("`2Du betrittst einen alten Tempel und siehst eine Tür. Du überlegst dir, ob du durchgehen willst oder nicht.");
         
     
    }        
    
        else if ($_GET[op]=="eintritt")
    {
        
        output("`^Du betrittst mutig den Tempel und erwartest, welcher Gott dir wohl gegenüberstehen wird.");
        if ($session['user']['gottempel']==0) {
            switch (e_rand(1,4))
            {
            case 1:
                output("`4Du begegnest Ellalith, dem Gott der Verwirrung. `nDu verlierst daher etwas Erfahrung.");
                $session['user']['experience']*=0.97;
                $session['user']['gottempel']='1' ;
                addnav("wieder raus","sanela.php");
                break;
            case 2:
                output("Du begegnest Viconia, die heute als Göttin des Glücks auftritt. Du findest einen Edelstein.");
                $session['user']['gems']++;
                $session['user']['gottempel']='1' ;        
                addnav("wieder raus","sanela.php");
                break;
            case 3:
                output("Du begegnest Viconia, die heute als Göttin des Pechs auftritt. Du verlierst einen Edelstein.");
                $session['user']['gottempel']='1' ;
                $session['user']['gems']--;
                addnav("wieder raus","sanela.php");
                break;
            
            case 4:
                output("`4Du begegnest Chire Kibarashi, dem Gott der Zerstreuung. `nDu bist so zerstreut das du die Zeit vergisst.");
                $session[user][turns]-=1;
                $session['user']['gottempel']='1' ;
                addnav("wieder raus","sanela.php");
                break;
            }
        } else {   
            addnav("Wieder raus","sanela.php");
            output("`2`n`nDu warst schon da und darfst nicht mehr rein...");
        } 
         }
         
         else if ($_GET[op]=="raus")
         {
         
         output("`êFeige entschließt du dich, dein Glück nicht herauszufordern und gehst schnell wieder raus.");
         addnav("Schnell raus rennen","sanela.php");
         }
         


page_footer();
?>