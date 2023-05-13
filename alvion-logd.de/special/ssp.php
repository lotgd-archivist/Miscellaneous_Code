
<?php
/**
* Waldspecial   : Schere-Stein-Papier
* Autor         : Liath
* Server        : http://germany-project.de/logd
* Datum         : 15. März 2009
* 
* Kontakt       : Liath@mircportal.de 
* Forum         : http://anpera.homeip.net/phpbb3/viewtopic.php?f=43&t=4836
* 
* Beschreibung  :
* 
*   Man trifft im Wald auf einen kleinen Gnom, der einen auf eine Runde Schere-Stein-Papier einläd.
*   Dabei kann man, je nach Einstellung, Edelsteine, Gold oder Ansehen gewinnen/verlieren
* 
*   Der Gewinn/Verlust kann in den Variablen ganz einfach eingestellt werden, ansonsten ist nichts
*   weiter zu tun, ausser das Special in den entsprechenden Ordner hochzuladen und gegebenenfalls
*   freizuschalten
* 
* Anmerkung     :
* 
*   Dieses ist mein erstes Waldspecial, bei Fehlern meldet Euch bitte bei mir direkt oder im Forum
*/
/* 
*
* Optimierung und Bugfixes in 2014 by Linus
* für alvion-logd.de
*
*/

if (!isset($session)) exit();

// maximale Einsätze
$gems = '5';
$gold = '2500';
$reputation = '5';
$entscheid='';

$session['user']['specialinc']='ssp.php';

if($_GET['op']=='play') {
    $bet = $_GET['bet'];
    $set = $_GET['set'];
    
    output("`n`nAlso gut, fangen wir an. Wir wählen beide gleichzeitig Schere, Stein oder Papier.`n`n
            Schere schneidet Papier, Papier bedeckt den Stein und Stein zerstört die Schere. Die Regeln sind Dir ja bestimmt bekannt, so das wir direkt anfangen können
    `n`n`n`n");
    
    output("<table align='center'><tr><td>
    <a href='forest.php?op=choose&own=scissor&set=$set&bet=$bet'><img src='./images/ssp/ssp-scissor.gif' border='0'></a>
    <a href='forest.php?op=choose&own=stone&set=$set&bet=$bet'><img src='./images/ssp/ssp-stone.gif' border='0'></a>
    <a href='forest.php?op=choose&own=paper&set=$set&bet=$bet'><img src='./images/ssp/ssp-paper.gif' border='0'></a>
    </td></tr></table>`n`n",true);    
    
    addnav("","forest.php?op=choose&own=scissor&set=$set&bet=$bet");    
    addnav("","forest.php?op=choose&own=stone&set=$set&bet=$bet");
    addnav("","forest.php?op=choose&own=paper&set=$set&bet=$bet");
    
    addnav("Schere","forest.php?op=choose&own=scissor&set=$set&bet=$bet");
    addnav("Stein","forest.php?op=choose&own=stone&set=$set&bet=$bet");
    addnav("Papier","forest.php?op=choose&own=paper&set=$set&bet=$bet");    

} else if($_GET['op']=='choose') {    
    $bet = $_GET['bet'];
    $set = $_GET['set'];
    $scissor = "<img src='./images/ssp/ssp-scissor.gif' border='0'>";
    $stone = "<img src='./images/ssp/ssp-stone.gif' border='0'>";
    $paper = "<img src='./images/ssp/ssp-paper.gif' border='0'>";
    
    switch(mt_rand(1,3)) {
        case 1:
            $choose = 'scissor';
        break;
        case 2:
            $choose = 'stone';
        break;
        case 3:
            $choose = 'paper';
        break;
    }

    output("`^`c`n`nIhr schwingt eure Hände hin und her, nach einer kleinen Weile zeigt`n`n`n`n`n`c");
    output("<table>",true);
    if($_GET['own']=='scissor') {        
        if ($choose=='scissor') {         
            output("<tr><td>`b`4Deine Hand:`b</td><td>".$scissor."</td></tr>`n`n
                    <tr><td>`b`4Gegners Hand:`b</td><td>".$scissor."</td></tr>`n",true);
            $entscheid="unentschieden";
        }    else if ($choose=='stone') {                        
            output("<tr><td>`b`4Deine Hand:`b</td><td>".$scissor."`n`n
                    <tr><td>`b`4Gegners Hand:`b</td><td>".$stone."</td></tr>`n",true);
            $entscheid="verloren";
        } else if ($choose=='paper') {            
            output("<tr><td>`b`4Deine Hand:`b</td><td>".$scissor."`n`n
                    <tr><td>`b`4Gegners Hand:`b</td><td>".$paper."</td></tr>`n",true);
            $entscheid="gewonnen";
        }        
    }    else if($_GET['own']=='stone') {        
        if ($choose=='stone') {             
             output("<tr><td>`b`4Deine Hand:`b</td><td>".$stone."</td></tr>`n`n
                     <tr><td>`b`4Gegners Hand:`b</td><td>".$stone."</td></tr>`n",true);
            $entscheid="unentschieden";
        }    else if ($choose=='paper') {                    
            output("<tr><td>`b`4Deine Hand:`b</td><td>".$stone."</td></tr>`n`n
                  <tr><td>`b`4Gegners Hand:`b</td><td>".$paper."</td></tr>`n",true);
            $entscheid="verloren";
        }    else if ($choose=='scissor') {                        
            output("<td>`b`4Deine Hand:`b</td><td>".$stone."</td></tr>`n`n
                    <tr><td>`b`4Gegners Hand:`b</td><td>".$scissor."</td></tr>`n",true);
            $entscheid="gewonnen";
        }        
    }    else if($_GET['own']=='paper') {        
        if ($choose=='paper') {         
            output("<tr><td>`b`4Deine Hand:`b</td><td>".$paper."</td></tr>`n`n
                    <tr><td>`b`4Gegners Hand:`b</td><td>".$paper."</td></tr>`n",true);
            $entscheid="unentschieden";            
        } else if ($choose=='scissor') {                    
            output("<tr><td>`b`4Deine Hand:`b</td><td>".$paper."</td></tr>`n`n
                    <tr><td>`b`4Gegners Hand:`b</td><td>".$scissor."</td></tr>`n",true);    
            $entscheid="verloren";
        }    else if ($choose=='stone') {                        
            output("<tr><td>`b`4Deine Hand:`b</td><td>".$paper."</td></tr>`n`n
                    <tr><td>`b`4Gegners Hand:`b</td><td>".$stone."</td></tr>`n",true); 
            $entscheid="gewonnen";            
        }        
    }
    output("</table><br/>`n",true);
            
    switch($entscheid){
        case 'gewonnen':
            output("`n`n`gSo ein Mist, da habe ich doch tatsächlich verloren, also gut... hier ist dein Gewinn.`n");
            switch ($_GET['set']) {
                case 'gems':
                    $session['user']['gems']+=$bet;
                    output("`n`^Du gewinnst ".$bet." Edelsteine");
                break;
                case 'gold':
                    $session['user']['gold']+=$bet;
                    output("`n`^Du gewinnst ".$bet." Goldstücke");
                break;
                case 'reputation':
                    $session['user']['reputation']+=$bet;
                    output("`n`^Du gewinnst ".$bet." Ansehen");
                break;
            }
        break;
        case 'verloren':
            output("`n`n`gHah, ich habe dich besiegt. Das wird dich einiges kosten mein Freund.`n");
            switch ($_GET['set']) {
                case 'gems':
                    $session['user']['gems']-=$bet;
                    output("`n`^Du verlierst ".$bet." Edelsteine");
                break;
                case 'gold':
                    $session['user']['gold']-=$bet;
                    output("`n`^Du verlierst ".$bet." Goldstücke");
                break;
                case 'reputation':
                    $session['user']['reputation']-=$bet;
                    output("`n`^Du verlierst ".$bet." Ansehen");
                break;
            }
        break;
        default:
            output("`n`gAaargh, ein Unentschieden. Naja da kann man nichts machen. Vielleicht gewinne ich, ääähm... gewinnst du ja das nächste Mal.`n");
        break;
    }
    output("`n`n");
    $session['user']['specialinc'] = "";
    addnav("zurück","forest.php");    
}

else if($_GET['op']=='leave') {
    $session['user']['specialinc'] = "";
    redirect('forest.php');   
}

else {

    if ($session['user']['gems'] > 0) {
     
        if ($session['user']['gems'] >= $gems) { $bet = $gems; }
        else { $bet = $session['user']['gems']; }
        $set = 'gems';
        
        output("`^Auf deinen Streifzügen durch den Wald begegnest du einem kleinen Gnom. `n`n`gAah ein Opf.. äähm Mitspieler, sei mir gegrüsst ".$session['user']['name']."
                `n`n`gMöchtest du ein kleines Spiel mit mir spielen? Du kennst doch sicherlich das Spiel Schere-Stein-Papier. Lass uns doch um ein paar Edelsteine
                spielen, sagen wir um $bet von deinen Klunkern. Du hast doch bestimmt welche dabei.`n
        ");
    }
    else if ($session['user']['gold'] > 0) {
        
        if ($session['user']['gold'] >= $gold) { $bet = $gold; }
        else { $bet = $session['user']['gold']; }
        $set = 'gold';
        
        output("`^Auf deinen Streifzügen durch den Wald begegnest du einem kleinen Gnom. `gAah ein Opf.. äähm Mitspieler, sei mir gegrüsst ".$session['user']['name']."
                `n`n`gMöchtest du ein kleines Spiel mit mir spielen? Du kennst doch sicherlich das Spiel Schere-Stein-Papier. Lass uns doch um ein paar Goldstücke
                spielen, sagen wir um $bet von deinen Goldstücken. Du hast doch bestimmt welche dabei.`n
        ");
    }
    else {
        
        $bet = $reputation;
        $set = 'reputation';
        
        output("`^Auf deinen Streifzügen durch den Wald begegnest du einem kleinen Gnom. `gAah ein Opf.. äähm Mitspieler, sei mir gegrüsst ".$session['user']['name']."
                `n`n`gMöchtest du ein kleines Spiel mit mir spielen? Du kennst doch sicherlich das Spiel Schere-Stein-Papier. Lass uns doch einfach nur so spielen, dadurch
                kann sich höchstens dein Ansehen verändern.`n
        ");
    }
    
    addnav("mitspielen","forest.php?op=play&bet=$bet&set=$set");
    addnav("weiter gehen","forest.php?op=leave");    
}


