<? 
/* 
Abandonded Castle Maze 
Author Lonny Luberts 
with Mazes by Lonny, Kain (Paul Syverson), Tundrawolf, Hermione, Blayze of http://www.pqcomp.com/logd 
version 1.1 
June 2004 

add to dragon.php after ,"beta"=>1 
,"mazeedit"=>1 

Mysql inclusions 
ALTER TABLE accounts ADD `mazeedit` text NOT NULL 
ALTER TABLE accounts ADD `maze` text NOT NULL 
ALTER TABLE accounts ADD `mazeturn` int(11) NOT NULL default '0' 
ALTER TABLE accounts ADD `pqtemp` text NOT NULL 

pqtemp is used in a number of my mods for a temporary (recyclable) place to store info that 
I do not want players to see on the url. 
Mazes must always start at location 6! 
Location 6 should ALWAYS be a piece with a south nav for continuity. 
Mazes can end anywhere, and one should use every piece of the grid for their maze 
There is no BLANK maze piece... I could code this, however I would rather make 
dead ends for the player.  At present there is no limit to the number of times a 
player can enter and do a maze. 

I did not code this mod with any database access as an admin may want to let users 
make maps!  A bad map could cause errors!  Make sure all maps do NOT have any X's 
(there is checking for this and the app will not die, but your player will), make 
sure all corridors connect or terminate properly or you will have a confusing and 
unrealistic maze!  Do NOT use too many traps as players will no longer use a feature 
that constantly kills them.  Do NOT use more than one exit... the app allows for this 
however 2 exits will confuse the heck out of a player. 

there is code for potions, chow and trading/lonny's castle items in the random event routine.. comment 
these out if you are not using these mods! 
*/ 
require_once "common.php"; 
checkday(); 
page_header("Das verlassene Schloss"); 
if ($session['user']['hitpoints'] > 0) 
    { 
    } 
else 
    { 
    redirect("shades.php"); 
    } 
//checkevent(); 
if ($_GET[op] == "" and $_GET[loc] == "") 
    { 
    output("`c`b`&Das verlassene Schloss`0`b`c`n`n"); 
    if (($session['user']['dragonkills'] > 9) && ($session[user][turns]>=1)) 
        { 
        output("`2 Als Du die Pyramide betrittst, versperrt hinter Dir plötzlich und unerwartet ein Felsblock den Eingang . Erschrocken rennst Du zurück und stemmst Dich dagegen, "); 
        output("doch so sehr Du es auch versuchst, er lässt sich nicht bewegen. Es scheint so, als ob Du einen anderen Ausgang finden müsstest! "); 
        output("Du schaust Dich im schummrigen Licht der Eingangshalle um und stelst fest, dass diese verdreckt, staubig und voll von den Resten früherer Besucher ist. `n"); 
        output("Na, wenn das mal kein gutes Omen ist...`n"); 
        if ($session['user']['hashorse']>0) 
            { 
            output("Schade, aber Dein {$playermount['mountname']} kann Dich nicht begleiten. Du bist jetzt auf Dich allein gestellt.`n"); 
            } 
        output("Außerdem bemerkst Du, dass dieser Ort irgendwie seltsam riecht-irgendwie magisch. Es scheint so, als ob all Deine Vorteile momentan vorüber wären...`n`n"); 
        if (count($session[bufflist])>0 && is_array($session[bufflist]) || $_GET[skill]!="") 
            { 
            $_GET[skill]=""; 
            if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']); 
            $session[bufflist]=array(); 
            } 
        $locale=6; 
        $session['user']['mazeturn']=0; 
        //they have to do an unfinished maze. 
        if ($session['user']['maze']=="") 
            { 
            //maze generation array.  Mazes are premade. 
            //as you add mazes make sure you change the e_rand value to match your quantity of mazes 
            switch(e_rand(1,22)) 
                { 
                case 1: 
                //author: Neolos 
                //title: 17 Steps 
                $maze = array(j,n,o,b,d,c,d,b,d,b,n,f,b,k,s,l,o,k,i,b,a,l,f,a,e,g,g,o,c,n,i,e,g,f,c,e,g,g,l,l,l,r,g,g,f,d,h,g,g,f,i,e,l,g,g,f,d,d,h,g,g,p,f,e,g,g,f,d,d,d,h,g,i,a,h,g,g,g,l,j,b,d,e,l,g,l,g,g,g,m,f,e,l,g,g,g,g,g,g,g,j,e,f,h,g,g,g,g,g,g,g,g,g,i,k,g,g,g,g,g,m,g,m,m,o,h,m,i,c,h,f,k,m,o,d,d,d,d,d,d,d,h,z); 
                break; 
                case 2: 
                //author: Neolos 
                //title: 30 Steps 
                $maze = array(j,n,o,b,d,a,d,b,d,b,n,f,b,k,s,l,g,l,i,b,e,l,f,a,e,g,g,g,f,n,i,h,g,f,c,e,g,g,g,g,l,r,l,g,f,d,h,g,g,f,c,e,l,g,g,f,d,d,h,g,g,p,f,e,g,g,f,d,d,d,c,h,i,a,h,g,g,g,l,j,b,d,k,l,g,l,g,g,g,m,f,e,l,g,g,g,g,g,m,g,j,e,f,h,g,g,g,g,g,z,g,g,g,i,k,g,g,g,g,g,g,g,m,m,o,h,m,i,c,h,m,g,i,d,d,d,d,d,d,d,d,d,h); 
                break; 
                case 3: 
                //author: Neolos 
                //title: 37 Steps 
                $maze = array(j,n,o,b,d,a,d,b,d,b,n,f,b,k,s,l,g,l,i,b,e,l,f,a,e,g,g,g,f,n,i,h,g,f,c,e,g,g,g,g,l,r,l,g,f,d,h,g,g,f,c,e,l,g,g,f,d,d,h,g,g,p,f,e,g,g,i,d,d,d,c,e,i,a,h,g,g,j,d,d,d,d,h,l,g,l,g,g,g,j,d,d,k,j,e,g,g,g,m,g,g,j,k,g,g,g,g,g,g,l,g,g,z,g,g,g,g,g,g,g,g,g,i,d,h,g,m,i,c,h,m,g,i,d,d,d,h,o,d,d,d,d,h); 
                break; 
                case 4: 
                //author: Neolos 
                //title: 31 Steps 
                $maze = array(j,d,d,b,d,a,d,b,d,d,k,f,b,k,s,l,g,l,i,k,j,h,f,a,e,g,g,g,f,n,m,g,l,f,c,e,g,g,m,g,l,r,g,m,f,d,h,g,g,j,c,e,l,i,k,f,d,d,h,g,g,p,f,e,j,h,f,d,d,d,h,g,i,a,e,i,k,g,j,d,d,d,h,l,g,g,j,h,g,f,d,d,k,j,e,g,g,i,k,g,g,j,k,g,g,g,z,g,j,h,g,g,m,g,g,g,g,g,g,i,k,g,i,d,h,g,g,m,g,m,j,h,i,d,d,d,c,c,n,i,d,c,n); 
                break; 
                case 5: 
                //author: Neolos 
                //title: 17 Steps 
                $maze = array(j,d,d,k,j,a,d,b,d,d,k,f,b,k,s,g,g,l,i,k,j,h,f,a,e,g,g,g,f,n,m,g,l,f,c,e,g,g,m,g,l,r,g,m,f,d,h,g,g,j,c,e,l,i,k,f,d,d,e,g,g,p,f,e,j,h,f,d,d,h,g,g,i,a,e,i,k,g,j,d,d,a,h,l,g,g,j,h,g,f,d,d,g,j,e,g,g,i,k,g,g,j,k,g,g,g,g,g,j,h,g,g,m,g,g,g,g,g,g,i,k,g,i,d,h,g,g,m,g,m,j,h,z,d,d,d,c,c,n,i,d,c,n); 
                break; 
                case 6: 
                //author: Neolos 
                //title: 28 Steps 
                $maze = array(j,d,d,d,d,a,d,b,d,d,k,f,b,k,s,l,g,l,i,k,j,h,f,a,e,g,g,m,f,n,m,g,l,f,c,e,g,f,n,g,l,r,g,m,f,d,h,g,g,j,c,e,l,i,k,f,d,d,e,g,g,p,f,e,j,h,f,d,d,h,g,g,i,a,e,f,n,g,j,d,d,a,h,l,g,f,h,z,g,f,d,d,g,o,e,g,g,g,g,f,c,n,o,c,d,c,c,h,m,g,f,d,d,d,d,d,d,d,d,d,h,g,j,d,b,b,b,b,b,d,b,n,m,i,d,c,c,c,c,c,d,c,n); 
                break; 
                case 7: 
                //author: Neolos 
                //title: 51 Steps 
                $maze = array(j,d,d,d,d,c,d,d,d,d,k,f,d,k,s,l,j,d,d,d,d,h,f,d,c,d,h,i,d,d,d,d,k,g,j,d,d,d,d,d,d,d,d,h,g,g,j,d,r,j,d,k,j,d,k,g,g,i,d,d,h,p,f,e,j,h,g,i,d,d,d,d,k,f,e,f,k,g,j,d,d,b,d,e,g,f,e,g,g,f,d,d,a,n,g,g,g,g,g,f,c,d,d,h,j,h,i,e,i,h,f,d,d,d,n,g,o,b,e,j,k,g,j,d,b,k,g,o,c,h,g,g,m,i,d,c,c,c,d,d,d,h,z); 
                break; 
                case 8: 
                //author: Neolos 
                //title: 45 Steps 
                $maze = array(j,d,d,d,b,a,d,d,d,d,k,f,d,k,s,m,g,o,b,d,d,h,f,d,c,h,j,h,o,c,d,d,k,m,j,d,d,h,z,o,d,d,d,h,j,h,j,d,r,g,j,k,j,d,k,g,j,c,d,k,i,h,g,g,j,h,g,i,b,d,c,d,k,g,g,f,k,i,k,i,d,b,d,e,g,i,h,g,j,h,o,d,a,n,g,f,d,k,g,i,d,k,j,h,j,h,f,n,g,g,j,k,g,i,n,g,o,h,j,h,g,g,g,g,j,k,i,n,j,h,j,e,i,c,c,h,i,d,d,h,o,h,m); 
                break; 
                case 9: 
                //author: Neolos 
                //title: 34 Steps 
                $maze = array(j,d,d,d,b,a,d,d,d,d,k,f,k,j,d,h,m,o,b,n,o,h,f,h,i,b,d,a,b,c,d,d,k,g,j,k,i,k,g,i,d,d,d,s,m,g,g,j,h,g,j,k,j,d,k,j,h,i,h,l,i,h,g,g,j,h,i,d,k,j,c,d,k,g,g,f,k,j,d,h,i,b,d,e,g,i,h,g,i,k,o,d,c,k,g,f,d,k,g,l,i,d,d,k,i,h,f,n,g,g,f,k,j,k,i,k,j,h,j,h,g,g,g,f,a,k,g,g,j,h,j,e,i,c,c,h,m,z,i,h,o,h,m); 
                break; 
                case 10: 
                //author: Neolos 
                //title: 41 Steps 
                $maze = array(z,j,d,d,b,a,d,d,d,d,k,g,i,d,d,h,m,o,b,d,k,g,f,d,k,j,d,a,b,c,d,e,g,g,l,g,i,k,g,i,d,d,h,g,m,g,g,j,h,g,j,k,j,k,g,j,h,g,g,l,i,h,g,g,g,g,i,k,g,f,c,d,k,g,g,g,g,j,h,g,i,b,d,e,g,i,h,g,i,n,g,j,c,k,g,f,d,k,g,j,d,h,f,d,c,h,i,d,h,g,f,d,k,m,j,d,d,d,d,d,e,g,l,i,d,h,o,d,d,d,k,g,i,c,n,o,d,d,d,d,d,h,m); 
                break; 
                case 11: 
                //author: justLeiche 
                //title: Karte_Leiche_1 
                $maze = array(j,s,j,d,d,a,d,d,d,d,k,i,d,a,d,n,i,k,o,b,d,h,j,k,g,l,o,k,i,k,i,d,k,m,f,c,c,d,h,r,e,j,d,h,q,g,j,k,l,z,o,e,i,d,n,g,g,g,g,g,i,k,i,d,d,k,g,g,g,g,g,j,h,o,d,d,e,g,g,g,g,g,g,j,d,b,d,h,g,g,g,g,g,g,g,j,h,o,k,g,g,g,g,g,g,g,i,d,d,h,g,g,g,g,g,g,i,d,d,d,k,g,g,g,g,g,i,d,k,j,k,g,i,c,h,i,h,o,d,c,h,i,h); 
                break; 
                case 12: 
                //author: justLeiche 
                //title: Karte_Leiche_2 
                $maze = array(j,s,j,d,d,a,d,d,d,d,k,i,d,a,d,n,i,r,j,d,d,h,j,k,g,l,j,d,k,i,d,d,k,m,f,c,h,f,n,i,d,b,d,h,q,g,j,k,g,o,d,d,c,d,n,g,g,g,g,i,d,d,d,k,o,k,g,g,g,i,d,n,o,d,a,d,e,g,g,g,j,d,d,d,d,h,o,h,g,g,g,g,l,j,k,z,o,d,k,i,c,h,g,g,g,g,g,j,d,h,j,d,d,h,g,g,g,g,i,d,k,f,d,d,d,h,g,g,g,j,k,g,i,d,d,d,d,h,i,c,h,i,h); 
                break; 
                case 13: 
                //author: justLeiche 
                //title: Karte_Leiche_3 
                $maze = array(j,s,j,d,d,a,d,d,k,j,k,i,d,a,d,n,i,r,l,i,h,g,j,k,g,l,j,d,k,i,b,k,g,m,f,c,h,i,n,i,d,e,i,h,q,g,j,d,d,k,j,n,i,d,k,g,g,g,j,d,h,i,d,k,o,e,g,g,g,i,b,n,o,d,a,d,e,g,g,i,k,i,d,k,j,h,l,g,g,g,j,h,l,l,z,g,j,c,e,i,e,g,j,e,g,l,g,g,j,h,j,h,f,h,g,g,g,g,g,i,k,f,n,i,k,m,g,g,m,i,n,g,i,d,n,i,d,c,c,d,d,d,h); 
                break; 
                case 14: 
                //author: justLeiche 
                //title: Karte_Leiche_4 
                $maze = array(j,n,j,d,d,a,d,d,b,d,k,i,d,e,j,k,i,r,l,g,l,g,j,d,h,g,f,d,n,f,h,g,g,g,j,d,h,i,s,j,h,j,c,h,g,i,d,d,d,k,g,q,i,d,k,g,j,k,j,d,c,e,i,k,o,e,i,h,g,i,b,n,g,o,a,d,e,j,d,h,l,i,n,i,k,m,l,g,g,o,b,h,j,b,k,g,o,c,e,f,b,h,l,g,g,g,i,k,j,h,g,i,d,h,g,m,g,o,e,i,k,g,j,k,l,m,z,g,j,h,l,g,i,h,i,c,d,h,i,h,o,c,h); 
                break; 
                case 15: 
                //author: justLeiche 
                //title: Karte_Leiche_5 
                $maze = array(j,d,d,d,d,a,d,d,b,d,k,g,j,k,j,k,i,r,l,g,l,g,g,g,m,q,i,d,d,c,h,g,g,g,g,j,d,d,d,d,d,k,f,h,g,g,g,j,d,d,d,k,g,f,k,g,g,g,g,j,d,k,g,g,m,g,g,g,g,g,g,z,h,g,g,o,e,g,g,g,g,i,d,d,h,g,l,g,g,g,g,i,d,d,d,d,h,i,e,g,g,i,d,d,d,d,d,k,j,h,g,f,n,j,d,d,d,d,h,i,k,g,f,n,g,s,j,k,j,n,l,g,i,c,d,h,i,h,i,c,d,c,h); 
                break; 
                case 16: 
                //author: justLeiche 
                //title: Karte_Leiche_6 
                $maze = array(j,s,j,d,d,a,d,d,b,d,k,i,d,a,d,k,i,r,l,g,l,g,j,k,g,l,f,d,n,f,h,g,g,m,f,c,h,i,n,j,h,j,c,h,q,g,j,d,d,k,g,l,i,d,k,g,g,g,j,d,h,g,i,k,o,e,g,g,g,i,b,n,g,o,a,d,e,g,g,i,k,i,n,i,k,m,l,g,i,h,j,h,j,b,k,g,o,c,e,j,b,h,z,g,g,g,i,k,j,h,g,g,j,h,g,g,g,o,e,i,k,g,i,h,l,m,g,m,j,h,l,g,i,d,d,c,d,c,d,h,o,c,h); 
                break; 
                case 17: 
                //author: justLeiche 
                //title: Karte_Leiche_7 
                $maze = array(j,d,k,j,d,a,d,b,k,j,k,g,l,m,g,l,f,n,g,i,h,g,i,a,d,c,h,i,k,f,b,k,g,l,g,j,b,b,n,g,g,r,m,g,g,p,g,m,f,d,e,g,l,j,e,i,d,h,j,h,j,h,g,f,h,g,o,b,n,m,j,c,n,g,g,j,h,l,i,b,n,g,j,k,g,s,g,l,g,j,c,n,g,g,g,g,o,c,h,g,g,j,d,h,m,i,h,o,d,k,i,a,e,j,k,z,o,d,k,j,h,l,g,g,g,g,i,d,k,i,a,n,i,h,i,h,i,d,d,c,d,c,n); 
                break; 
                case 18: 
                //author: justLeiche 
                //title: Karte_Leiche_8 
                $maze = array(j,d,b,b,d,c,d,b,k,j,k,g,l,m,i,d,k,o,e,i,h,g,i,a,d,b,n,i,k,f,b,k,g,l,g,j,a,n,j,h,g,r,m,g,g,p,m,m,j,h,l,g,l,j,e,i,d,j,b,h,o,e,g,f,h,g,o,b,h,m,j,z,g,g,g,j,h,l,i,b,k,i,k,i,h,s,g,l,g,q,g,i,n,i,d,k,o,c,h,g,g,i,d,d,k,j,h,o,d,k,i,a,k,j,d,h,g,j,k,j,h,l,g,g,g,j,d,h,g,i,a,n,i,h,i,c,c,d,d,c,d,c,n); 
                break; 
                case 19: 
                //author: justLeiche 
                //title: Karte_Leiche_9 
                $maze = array(j,d,b,b,d,a,d,b,k,j,k,g,l,m,i,n,g,o,e,i,h,g,i,a,d,b,n,g,l,f,b,k,g,l,g,j,a,n,f,h,g,r,m,g,g,p,m,m,l,g,l,g,l,j,e,i,d,b,b,h,g,g,g,f,h,g,o,b,h,m,j,e,g,g,g,j,h,l,i,b,k,m,g,i,h,s,i,k,g,q,g,i,n,i,d,d,d,k,m,f,c,a,d,d,k,o,d,d,c,k,m,z,m,j,d,h,j,d,k,j,h,l,i,d,c,d,d,h,l,g,i,k,i,d,d,d,d,d,d,c,c,d,h); 
                break; 
                case 20: 
                //author: justLeiche 
                //title: Karte_Leiche_10 
                $maze = array(j,d,b,b,d,a,d,d,d,d,k,g,l,m,i,n,g,j,d,d,k,g,i,a,d,b,n,g,i,d,k,i,h,l,g,j,a,n,f,n,o,e,j,k,g,p,m,m,l,g,l,j,h,g,g,i,d,b,b,h,g,g,i,d,h,g,o,b,h,m,j,e,g,j,n,j,h,l,i,b,n,m,g,f,h,s,i,k,g,q,g,j,k,i,c,d,h,j,h,f,c,e,z,g,l,o,d,d,c,k,f,k,m,j,c,h,j,d,b,k,g,g,i,n,g,j,k,m,l,m,i,e,i,d,d,c,h,i,d,c,d,d,h); 
                break; 
                case 21: 
                //author: justLeiche 
                //title: Karte_Leiche_11 
                $maze = array(j,d,d,d,z,i,d,d,d,d,k,i,d,d,k,r,b,b,d,d,k,g,o,b,d,a,d,h,i,d,k,g,g,l,m,j,a,n,j,n,o,e,g,g,f,p,m,m,l,g,l,j,h,g,g,i,d,b,b,h,g,g,i,n,g,g,o,b,h,m,j,e,g,j,n,g,g,l,i,b,n,m,g,f,h,s,g,g,g,q,g,j,k,i,c,d,a,e,g,f,c,e,m,g,l,o,d,h,g,g,f,k,m,j,c,h,j,d,b,e,g,g,i,n,g,j,k,m,l,m,g,g,i,d,d,c,h,i,d,c,d,c,h); 
                break; 
                case 22: 
                //author: justLeiche 
                //title: Karte_Leiche_12 
                $maze = array(j,d,d,d,d,a,d,d,k,l,z,i,d,d,k,r,a,b,n,f,e,g,o,b,d,e,o,h,i,k,g,m,g,l,m,j,a,n,j,d,e,i,k,g,f,p,m,g,l,g,l,f,n,g,g,i,d,d,a,h,i,h,i,n,g,g,o,b,n,m,o,b,k,j,n,g,g,l,i,b,n,j,h,i,c,d,h,g,g,q,g,l,i,d,d,d,d,k,g,f,c,e,f,b,d,d,d,d,h,g,f,k,m,g,i,d,d,d,d,k,g,g,i,n,g,j,d,d,d,d,h,g,i,d,d,h,i,d,d,d,d,d,h); 
                break; 
                } 
            $session['user']['maze']=implode($maze,","); 
            } 
        addnav("Weiter","abandoncastle.php?loc=6"); 
        } 
    else if($session['user']['dragonkills'] <= 9) 
        { 
        output("Du versuchst zu fliehen, aber es gelingt Dir nicht.`n"); 
        output("Komm wieder, wenn Du mehr Erfahrung hast Krieger.`n"); 
        addnav("Weiter","village.php"); 
        } 
    else 
        { 
        output("In Deinem Zustand willst Du in die Pyramide gehen?`n"); 
        output("Komm wieder, wenn Du Dich etwas ausgeruht hast Krieger.`n"); 
        output("Du hast keine Waldkämpfe mehr übrig.`n"); 
        addnav("Weiter","village.php"); 
        } 
    } 
//now let's navigate the maze 
if ($_GET[op] <> ""){ 
    $locale=$_GET[loc]; 
    if ($_GET[op] == "n"){ 
        $locale+=11; 
        redirect("abandoncastle.php?loc=$locale"); 
    } 
    if ($_GET[op] == "s"){ 
        $locale-=11; 
        redirect("abandoncastle.php?loc=$locale"); 
    } 
    if ($_GET[op] == "w"){ 
        $locale-=1; 
        redirect("abandoncastle.php?loc=$locale"); 
    } 
    if ($_GET[op] == "e"){ 
        $locale+=1; 
        redirect("abandoncastle.php?loc=$locale"); 
    } 
}else{ 
if ($_GET[loc] <> ""){ 
//now deal with random events good stuff first 
//good stuff diminshes the longer player is in the maze 
//this is big... with lots of cases to help keep options open for future events 
//the lower cases should be good things the best at the lowest number 
//and the opposite for bad things 
$maze=explode(",",$session['user']['maze']); 
$locale=$_GET[loc]; 
if ($locale=="") $locale=$session['user']['pqtemp']; 
$session['user']['pqtemp']=$locale; 
for ($i=0;$i<$locale-1;$i++){ 
} 
$navigate=ltrim($maze[$i]); 
output("`4"); 
if ($navigate <> "z"){ 
switch(e_rand($session['user']['mazeturn'],2500)){ 
    case 1: 
    case 2: 
    case 3: 
    case 4: 
    case 5: 
    case 6: 
    case 7: 
    case 8: 
    case 9: 
    case 10: 
    output("Du Glückspilz!  Du findest einen Edelstein!"); 
    $session['user']['gems']+=1; 
    break; 
    case 11: 
    case 12: 
    case 13: 
    case 14: 
    case 15: 
    case 16: 
    case 17: 
    case 18: 
    case 19: 
    case 20: 
    output("Du Glückspilz! Du findest 1000 Gold!"); 
    $session['user']['gold']+=1000; 
    break; 
    case 21: 
    case 22: 
    case 23: 
    case 24: 
    case 25: 
    case 26: 
    case 27: 
    case 28: 
    case 29: 
    case 30: 
    output("Du Glückspilz! Du findest 900 Gold!"); 
    $session['user']['gold']+=900; 
    break; 
    case 31: 
    case 32: 
    case 33: 
    case 34: 
    case 35: 
    case 36: 
    case 37: 
    case 38: 
    case 39: 
    case 40: 
    output("Du Glückspilz! Du findest 800 Gold!"); 
    $session['user']['gold']+=800; 
    break; 
    case 41: 
    case 42: 
    case 43: 
    case 44: 
    case 45: 
    case 46: 
    case 47: 
    case 48: 
    case 49: 
    case 50: 
    output("Du Glückspilz! Du findest 700 Gold!"); 
    $session['user']['gold']+=700; 
    break; 
    case 51: 
    case 52: 
    case 53: 
    case 54: 
    case 55: 
    case 56: 
    case 57: 
    case 58: 
    case 59: 
    case 60: 
    output("Du Glückspilz! Du findest 600 Gold!"); 
    $session['user']['gold']+=600; 
    break; 
    case 61: 
    case 62: 
    case 63: 
    case 64: 
    case 65: 
    case 66: 
    case 67: 
    case 68: 
    case 69: 
    case 70: 
    output("Du Glückspilz! Du findest 500 Gold!"); 
    $session['user']['gold']+=500; 
    break; 
    case 71: 
    case 72: 
    case 73: 
    case 74: 
    case 75: 
    case 76: 
    case 77: 
    case 78: 
    case 79: 
    case 80: 
    output("Du Glückspilz! Du findest 400 Gold!"); 
    $session['user']['gold']+=400; 
    break; 
    case 81: 
    case 82: 
    case 83: 
    case 84: 
    case 85: 
    case 86: 
    case 87: 
    case 88: 
    case 89: 
    case 90: 
    output("Du Glückspilz! Du findest 300 Gold!"); 
    $session['user']['gold']+=300; 
    break; 
    case 91: 
    case 92: 
    case 93: 
    case 94: 
    case 95: 
    case 96: 
    case 97: 
    case 98: 
    case 99: 
    case 100: 
    output("Du Glückspilz! Du findest 200 Gold!"); 
    $session['user']['gold']+=200; 
    break; 
    case 101: 
    case 102: 
    case 103: 
    case 104: 
    case 105: 
    case 106: 
    case 107: 
    case 108: 
    case 109: 
    case 110: 
    output("Du Glückspilz! Du findest 100 Gold!"); 
    $session['user']['gold']+=100; 
    break; 
    case 111: 
    case 112: 
    case 113: 
    case 114: 
    case 115: 
    case 116: 
    case 117: 
    case 118: 
    case 119: 
    case 120: 
    output("Du Glückspilz! Du findest 50 Gold!"); 
    $session['user']['gold']+=50; 
    break; 
    case 121: 
    case 122: 
        //comment out potions for if you are not using potion mod! 
        /* 
        if ($session['user']['potion']<5){ 
            output("Du Glückspilz! Du findest einen Heiltrank!"); 
            $session['user']['potion']+=1; 
        } 
        break; 
        */ 
    case 123: 
    case 124: 
        //comment out chow if you are not using chow mod! 
        /* 
        for ($i=0;$i<6;$i+=1){ 
            $chow[$i]=substr(strval($session['user']['chow']),$i,1); 
            if ($chow[$i] > 0) $userchow++; 
        } 
            if ($userchow<5){ 
        switch(e_rand(1,7)){ 
        case 1: 
            output("`^Fortuna lächelt Dich an und Du findest einen Laib Brot!`0"); 
            for ($i=0;$i<6;$i+=1){ 
                $chow[$i]=substr(strval($session['user']['chow']),$i,1); 
                if ($chow[$i]=="0" and $done < 1){ 
                    $chow[$i]="1"; 
                    $done = 1; 
                } 
                $newchow.=$chow[$i]; 
            } 
        break; 
        case 2: 
        output("`^Fortuna lächelt Dich an und Du findest ein Schweinskotelett!`0"); 
        for ($i=0;$i<6;$i+=1){ 
                $chow[$i]=substr(strval($session['user']['chow']),$i,1); 
                if ($chow[$i]=="0" and $done < 1){ 
                    $chow[$i]="2"; 
                    $done = 1; 
                } 
                $newchow.=$chow[$i]; 
            } 
        break; 
        case 3: 
        output("`^Fortuna lächelt Dich an und Du findest ein Stück Schinken!`0"); 
        for ($i=0;$i<6;$i+=1){ 
                $chow[$i]=substr(strval($session['user']['chow']),$i,1); 
                if ($chow[$i]=="0" and $done < 1){ 
                    $chow[$i]="3"; 
                    $done = 1; 
                } 
                $newchow.=$chow[$i]; 
            } 
        break; 
        case 4: 
        output("`^Fortuna lächelt Dich an und Du findest ein Steak!`0"); 
        for ($i=0;$i<6;$i+=1){ 
                $chow[$i]=substr(strval($session['user']['chow']),$i,1); 
                if ($chow[$i]=="0" and $done < 1){ 
                    $chow[$i]="4"; 
                    $done = 1; 
                } 
                $newchow.=$chow[$i]; 
            } 
        break; 
        case 5: 
        output("`^Fortuna lächelt Dich an und Du findest ein halbes Hähnchen!`0"); 
        for ($i=0;$i<6;$i+=1){ 
                $chow[$i]=substr(strval($session['user']['chow']),$i,1); 
                if ($chow[$i]=="0" and $done < 1){ 
                    $chow[$i]="5"; 
                    $done = 1; 
                } 
                $newchow.=$chow[$i]; 
            } 
        break; 
        case 6: 
        output("`^Fortuna lächelt Dich an und Du findest eine Flasche Milch!`0"); 
        for ($i=0;$i<6;$i+=1){ 
                $chow[$i]=substr(strval($session['user']['chow']),$i,1); 
                if ($chow[$i]=="0" and $done < 1){ 
                    $chow[$i]="6"; 
                    $done = 1; 
                } 
                $newchow.=$chow[$i]; 
            } 
        break; 
        case 7: 
        output("`^Fortuna lächelt Dich an und Du findest eine Flasche Wasser!`0"); 
        for ($i=0;$i<6;$i+=1){ 
                $chow[$i]=substr(strval($session['user']['chow']),$i,1); 
                if ($chow[$i]=="0" and $done < 1){ 
                    $chow[$i]="7"; 
                    $done = 1; 
                } 
                $newchow.=$chow[$i]; 
            } 
        break; 
        } 
        $session['user']['chow']=$newchow; 
        } 
    break; 
    */ 
    case 125: 
    case 126: 
    case 127: 
    case 128: 
    case 129: 
    case 130: 
    output("Du Glückspilz! Du findest 10 Gold!"); 
    $session['user']['gold']+=10; 
    break; 
    case 131: 
    case 132: 
    case 133: 
    case 134: 
    case 135: 
    case 136: 
    case 137: 
    case 138: 
    case 139: 
    case 140: 
    //output("You find "); 
    //comment out if you are not using the trading mod and lonny's castle! 
    //$session['user']['evil']-=1; 
    //find(); 
    //break; 

    case 2321: 
    case 2322: 
    case 2323: 
    case 2324: 
    case 2325: 
    case 2326: 
    case 2327: 
    case 2328: 
    case 2329: 
    case 2330: 
    output("Du hörst von irgendwo her ein fremdes unheimliches Geräusch."); 
    break; 
    case 2331: 
    case 2332: 
    case 2333: 
    case 2334: 
    case 2335: 
    case 2336: 
    case 2337: 
    case 2338: 
    case 2339: 
    case 2340: 
    output("Du hörst von irgendwo her einen markerschütternden Schrei."); 
    break; 
    case 2341: 
    case 2342: 
    case 2343: 
    case 2344: 
    case 2345: 
    case 2346: 
    case 2347: 
    case 2348: 
    case 2349: 
    case 2350: 
    output("Du nimmst einen verfaulten Geruch wahr."); 
    $session['user']['clean']+=1; 
    break; 
    case 2351: 
    case 2352: 
    case 2353: 
    case 2354: 
    case 2355: 
    case 2356: 
    case 2357: 
    case 2358: 
    case 2359: 
    case 2360: 
    output("Du siehst ein Skelett auf dem Boden liegen. Armer Kerl. Der hat anscheinend den Weg nicht gefunden..."); 
    break; 
    case 2361: 
    case 2362: 
    case 2363: 
    case 2364: 
    case 2365: 
    case 2366: 
    case 2367: 
    case 2368: 
    case 2369: 
    case 2370: 
    output("Du siehst eine Ratte auf etwas kauen, dass wie eine Hand aussieht."); 
    break; 
    case 2371: 
    case 2372: 
    case 2373: 
    case 2374: 
    case 2375: 
    case 2376: 
    case 2377: 
    case 2378: 
    case 2379: 
    case 2380: 
    output("Du hörst ganz in der Nähe ein Knurren."); 
    break; 
    case 2381: 
    case 2382: 
    case 2383: 
    case 2384: 
    case 2385: 
    case 2386: 
    case 2387: 
    case 2388: 
    case 2389: 
    case 2390: 
    output("Es läuft Dir ein kalter Schauer den Rücken herunter."); 
    break; 
    case 2391: 
    case 2392: 
    case 2393: 
    case 2394: 
    case 2395: 
    case 2396: 
    case 2397: 
    case 2398: 
    case 2399: 
    case 2400: 
    output("Du hörst von irgendwo her einen Hilfeschrei."); 
    break; 
    case 2401: 
    case 2402: 
    case 2403: 
    case 2404: 
    case 2405: 
    case 2406: 
    case 2407: 
    case 2408: 
    case 2409: 
    case 2410: 
    output("Irgendwo ganz in der Nähe hörst Du Hilfeschreie."); 
    break; 
    case 2411: 
    case 2412: 
    case 2413: 
    case 2414: 
    case 2415: 
    case 2416: 
    case 2417: 
    case 2418: 
    case 2419: 
    case 2420: 
    output("Du hörst von irgendwo her Hilfeschreie.  Plötzlich verstummen die Schreie."); 
    break; 
    case 2421: 
    case 2422: 
    case 2423: 
    case 2424: 
    case 2425: 
    case 2426: 
    case 2427: 
    case 2428: 
    case 2429: 
    case 2430: 
    output("Autsch! Du bist auf einen scharfen Gegenstand getreten!"); 
    $session['user']['hitpoints']-=1; 
    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1; 
    break; 
    case 2431: 
    case 2432: 
    case 2433: 
    case 2434: 
    case 2435: 
    case 2436: 
    case 2437: 
    case 2438: 
    case 2439: 
    case 2440: 
    output("Autsch! Du bist von einer Spinne gebissen worden."); 
    $session['user']['hitpoints']-=2; 
    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1; 
    break; 
    case 2441: 
    case 2442: 
    case 2443: 
    case 2444: 
    case 2445: 
    case 2446: 
    case 2447: 
    case 2448: 
    case 2449: 
    case 2450: 
    output("Autsch! Du bist von einer Ratte gebissen worden."); 
    $session['user']['hitpoints']-=3; 
    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1; 
    break; 
    case 2451: 
    case 2452: 
    case 2453: 
    case 2454: 
    case 2455: 
    case 2456: 
    case 2457: 
    case 2458: 
    case 2459: 
    case 2460: 
    output("Autsch! Du bist von einer riesigen Ratte gebissen worden."); 
    $session['user']['hitpoints']-=4; 
    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1; 
    break; 
    case 2461: 
    case 2462: 
    case 2463: 
    output("<big><big><big>`4Rumms!<small><small><small>`n",true); 
    output("`3Dir wird schwarz vor Augen. Bevor Du stirbst, siehst Du noch das Du von eisernen Pfählen die aus dem Boden kommen aufgespiesst wurdest.`n"); 
    $session['user']['hitpoints']=0; 
    addnews("`%".$session[user][name]."`5 ging ins Verlassene Schloss und kam nie wieder lebendig heraus."); 
    break; 
    case 2464: 
    case 2465: 
    case 2466: 
    case 2467: 
    case 2468: 
    case 2469: 
    case 2470: 
    case 2471: 
    redirect("mazemonster.php?op=ghost1"); 
    break; 
    case 2472: 
    case 2473: 
    case 2474: 
    case 2475: 
    case 2476: 
    case 2477: 
    case 2478: 
    case 2479: 
    redirect("mazemonster.php?op=ghost2"); 
    break; 
    case 2480: 
    case 2481: 
    case 2482: 
    case 2483: 
    case 2484: 
    case 2485: 
    case 2486: 
    redirect("mazemonster.php?op=bat"); 
    break; 
    case 2487: 
    case 2488: 
    case 2489: 
    case 2490: 
    case 2491: 
    case 2493: 
    case 2494: 
    redirect("mazemonster.php?op=rat"); 
    break; 
    case 2495: 
    case 2496: 
    redirect("mazemonster.php?op=minotaur"); 
    break; 
    case 2497: 
    case 2498: 
    output("<big><big><big>`4Rumms!<small><small><small>`n",true); 
    output("`3Dir wird schwarz vor Augen. Bevor Du stirbst, siehst Du noch das Du von eisernen Pfählen die aus dem Boden kommen aufgespiesst wurdest.`n"); 
    $session['user']['hitpoints']=0; 
    addnews("`%".$session[user][name]."`5 ging in die Pyramide und kam nie wieder lebendig heraus."); 
    break; 
    case 2499: 
    case 2500: 
    output("<big><big><big>`4Rumms!<small><small><small>`n",true); 
    output("`3Dir wird schwarz vor Augen. Bevor Du stirbst, siehst Du noch das Du von eisernen Pfählen die aus dem Boden kommen aufgespiesst wurdest.`n"); 
    $session['user']['hitpoints']=0; 
    addnews("`%".$session[user][name]."`5 ging in die Pyramide und kam nie wieder lebendig heraus."); 
    break; 
} 
} 
output("`7"); 
if ($navigate<>"z"){ 
if ($navigate=="x"){ 
    output("Du fällst am Ende der Welt herunter!"); 
    $session['user']['hitpoints']=0; 
    addnews("`%".$session[user][name]."`5 ging in die Pyramide und kam nie wieder lebendig heraus."); 
} 
if ($navigate=="p"){ 
    output("Du fällst in eine Grube gefüllt mit Speerspitzen. Du siehst noch das schummrige Licht über Dir langsam verschwinden, so wie Dein Leben schwindet.`n"); 
    $session['user']['hitpoints']=0; 
    addnews("`%".$session[user][name]."`5 ging in die Pyramide und kam nie wieder lebendig heraus."); 
} 
if ($navigate=="q"){ 
    output("Du trittst auf irgendetwas auf dem Boden. Du merkst wie es sich bewegt, und hörst dann wie sich eine grosse Menge Wasser bewegt."); 
    output("Der Gang füllt sich schnell mit Wasser und Du merkst wie Deine Lungen nach Luft schreien, als Dein Leben dahin schwindet.`n"); 
    $session['user']['hitpoints']=0; 
    addnews("`%".$session[user][name]."`5 ging in die Pyramide und kam nie wieder lebendig heraus."); 
} 
if ($navigate=="r"){ 
    output("Du hörst eine Türe hinter Dir zuschlagen, und bemerkst, dass Du in einem kleinen Raum eingesperrt bist. "); 
    output("Die Wände beginnen zu vibrieren und bewegen sich langsam auf Dich zu. Nun weisst Du, wie sich ein Käfer "); 
    output("unter Deinen Füssen fühlen muss."); 
    $session['user']['hitpoints']=0; 
    addnews("`%".$session[user][name]."`5 ging in die Pyramide und kam nie wieder lebendig heraus."); 
} 
if ($navigate=="s"){ 
    output("Aus dem Nichts schwingt eine Klinge horizontal durch den Gang.."); 
    output("Die Welt um Dich herum verschwimmt, als sich der obere Teil Deines Körpers vom unteren löst..`n"); 
    $session['user']['hitpoints']=0; 
    addnews("`%".$session[user][name]."`5 ging in die Pyramide und kam nie wieder lebendig heraus."); 
} 
if ($session['user']['hitpoints'] > 0){ 
if ($locale=="6"){ 
    output("`nDu befindest Dich in einem Gang in dem Du in folgende Richtungen gehen kannst "); 
}else{ 
output("`nDu befindest Dich in einem dunklen Gang in dem Du in folgende Richtungen gehen kannst"); 
} 
$session['user']['mazeturn']++; 
if ($navigate=="a" or $navigate=="b" or $navigate=="e" or $navigate=="f" or $navigate=="g" or $navigate=="j" or $navigate=="k" or $navigate=="l"){ 
    addnav("Norden","abandoncastle.php?op=n&loc=$locale"); 
    $directions.=" Norden"; 
    $navcount++; 
} 
if ($navigate=="a" or $navigate=="c" or $navigate=="e" or $navigate=="f" or $navigate=="g" or $navigate=="h" or $navigate=="i" or $navigate=="m"){ 
    if ($locale <> 6){ 
        addnav("Süden","abandoncastle.php?op=s&loc=$locale"); 
        $navcount++; 
        if ($navcount > 1) $directions.=","; 
        $directions.=" Süden"; 
    } 
} 
if ($navigate=="a" or $navigate=="b" or $navigate=="c" or $navigate=="d" or $navigate=="e" or $navigate=="h" or $navigate=="k" or $navigate=="n"){ 
    addnav("Westen","abandoncastle.php?op=w&loc=$locale"); 
    $navcount++; 
    if ($navcount > 1) $directions.=","; 
    $directions.=" Westen"; 
} 
if ($navigate=="a" or $navigate=="b" or $navigate=="c" or $navigate=="d" or $navigate=="f" or $navigate=="i" or $navigate=="j" or $navigate=="o"){ 
    addnav("Osten","abandoncastle.php?op=e&loc=$locale"); 
    $navcount++; 
    if ($navcount > 1) $directions.=","; 
    $directions.=" Osten"; 
} 
output($directions.".`n"); 
}else{ 
    addnav("Weiter","shades.php"); 
} 
//user map generation.... may make code to grey spots that a player has been 
$mazemap=$navigate; 
$mazemap.="maze.gif"; 
output("<IMG SRC=\"images/$mazemap\">\n",true); 
output("`n"); 
output("`n<small>`7Du = <img src=\"./images/mcyan.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\">`7, Eingang = <img src=\"./images/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\">`7, Ausgang = <img src=\"./images/mred.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\"><big>",true); 
$mapkey2="<table style=\"height: 130px; width: 110px; text-align: left;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td style=\"vertical-align: top;\">"; 
for ($i=0;$i<143;$i++){ 
        if ($i==$locale-1){ 
            $mapkey.="<img src=\"./images/mcyan.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">"; 
        }else{ 
            if ($i==5){ 
                $mapkey.="<img src=\"./images/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">"; 
            }else{ 
            if (ltrim($maze[$i])=="z"){ 
                $exit=$i+1; 
                $mapkey.="<img src=\"./images/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">"; 
            }else{ 
                $mapkey.="<img src=\"./images/mblack.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">"; 
            } 
        } 
    } 
    if ($i==10 or $i==21 or $i==32 or $i==43 or $i==54 or $i==65 or $i==76 or $i==87 or $i==98 or $i==109 or $i==120 or $i==131 or $i==142){ 
        $mapkey="`n".$mapkey; 
        $mapkey2=$mapkey.$mapkey2; 
        $mapkey=""; 
    } 
} 
$mapkey2.="</td></tr></tbody></table>"; 
output($mapkey2,true); 
if ($session['user']['superuser']>=0.5){ 
    output("Superuser Map`n"); 
    $mapkey2=""; 
    $mapkey=""; 
    for ($i=0;$i<143;$i++){ 
    $keymap=ltrim($maze[$i]); 
    $mazemap=$keymap; 
    $mazemap.="maze.gif"; 
    $mapkey.="<img src=\"./images/$mazemap\" title=\"\" alt=\"\" style=\"width: 20px; height: 20px;\">"; 
    if ($i==10 or $i==21 or $i==32 or $i==43 or $i==54 or $i==65 or $i==76 or $i==87 or $i==98 or $i==109 or $i==120 or $i==131 or $i==142){ 
        $mapkey="`n".$mapkey; 
        $mapkey2=$mapkey.$mapkey2; 
        $mapkey=""; 
    } 
    } 
    output($mapkey2,true); 
} 
if ($session['user']['superuser']>=2) addnav("!?Superuser Exit","abandoncastle.php?loc=$exit"); 
}else{ 
    //found your way out! 
    if (!is_array($session['bufflist']) || count($session['bufflist']) <= 0) { 
      $session['bufflist'] = unserialize($session['user']['buffbackup']); 
      if (!is_array($session['bufflist'])) $session['bufflist'] = array(); 
    } 
    if ($session['user']['hashorse']>0){ 
    output("Dein {$playermount['mountname']} begrüsst Dich freudig am Ausgang.`n"); 
    } 
    output("Du hast den Ausgang gefunden!`n"); 
    addnews("`%".$session[user][name]."`5 hat die Pyramide lebendig verlassen!  Und das in nur ".$session['user']['mazeturn']." Zügen!"); 
    $reward = 5000 - ($session['user']['mazeturn']*10); 
    if ($reward < 0) $reward = 0; 
    $gemreward = 0; 
    if ($session['user']['mazeturn'] < 101) $gemreward = 1; 
    if ($session['user']['mazeturn'] < 76) $gemreward = 2; 
    if ($session['user']['mazeturn'] < 51) $gemreward = 3; 
    if ($session['user']['mazeturn'] < 26) $gemreward = 4; 
    output("`2Du hast das Labyrinth verlassen in nur ".$session['user']['mazeturn']." Zügen.`n"); 
    output("`2Du bekommst eine Belohnung von ".$reward." Gold and ".$gemreward." Edelsteinen.`n"); 
    if ($session['user']['mazeturn'] <= 26) 
        { 
        output("Weil die Suche so anstrengend war, verlierst Du 1 Waldkampf.`n`n"); 
        $session['user']['turns']-=1; 
        } 
    else if ($session['user']['mazeturn'] <= 51) 
        { 
        output("Weil die Suche so anstrengend war, verlierst Du 2 Waldkämpfe.`n`n"); 
        $session['user']['turns']-=2; 
        } 
    else if ($session['user']['mazeturn'] <= 76) 
        { 
        output("Weil die Suche so anstrengend war, verlierst Du 3 Waldkämpfe.`n`n"); 
        $session['user']['turns']-=3; 
        } 
    else 
        { 
        output("Weil die Suche so anstrengend war, verlierst Du 4 Waldkämpfe.`n`n"); 
        $session['user']['turns']-=4; 
        } 
    addnav("Weiter","village.php"); 
    $session['user']['gold']+=$reward; 
    $session['user']['gems']+=$gemreward; 
    $session['user']['maze']=""; 
    $session['user']['mazeturn']=0; 
    $session['user']['pqtemp']=""; 
        } 
    } 
} 
page_footer(); 
?> 